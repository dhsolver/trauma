function getFileNames(files) {
    return Array.from(files).map(function(file) {
        return file.name;
    }).join(', ')
}

function scrollToBottom(selector) {
    var elem = $(selector);
    elem.scrollTop(elem[0].scrollHeight);
}

$(function() {
    $('.form-input-date').datepicker({
        format: "mm/dd/yyyy",
        autoclose: true,
    });

    var uploadedFiles;
    var failedFiles;
    var totalFilesCount;

    function uploadFileToS3(uploadDir, uploadFormId, file, uploadButton, uploadButtonText) {
        var formData = new FormData(document.getElementById('s3-form'));
        var fileName = file.name;
        var fileKey = uploadDir + '/' + Date.now() + '-' + fileName;
        formData.set('key', fileKey);
        if (S3_MAX_SIZE > 0 && file.size > S3_MAX_SIZE) {
            alert('Your file (' + fileName + ') exceeds the ' + Math.floor(S3_MAX_SIZE / 1024 / 1024) + ' MB size limit.');
            uploadButton.html(uploadButtonText);
            uploadButton.prop('disabled', false);
            return;
        }
        formData.append("file", file);
        $.ajax({
            url: $('#s3-form').attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST'
        }).done(function(data, status, xhr) {
            $('<input>').attr({
                type: 'hidden',
                name: 'fileKeys[]',
                value: fileKey
            }).appendTo(uploadFormId);
            $('<input>').attr({
                type: 'hidden',
                name: 'fileNames[]',
                value: fileName
            }).appendTo(uploadFormId);
            uploadedFiles.push(fileKey);
        }).fail(function(xhr, status, err) {
            console.log(fileName + ' is NOT uploaded.');
            failedFiles.push(fileName);
        }).always(function() {
            if (uploadedFiles.length + failedFiles.length === totalFilesCount) {
                uploadButton.html(uploadButtonText);
                uploadButton.prop('disabled', false);
                $(uploadFormId).submit();
            }
        });
    }

    $('button[data-upload="s3"]').click(function(e) {
        e.preventDefault();

        var fileInputId = $(this).data('upload-file');
        var files = $(fileInputId).prop('files');

        var uploadDir = $(this).data('upload-dir');
        var uploadFormId = $(this).data('upload-form');
        $(uploadFormId + ' input[name="fileKeys[]"]').remove();

        totalFilesCount = files.length;
        if (totalFilesCount === 0) {
            if (uploadFormId) $(uploadFormId).submit();
            return;
        }

        var uploadButton = $(this);
        var uploadButtonText = uploadButton.html();
        var uploadingText = $(this).data('uploading-text');
        uploadButton.text(uploadingText || 'Uploading ...')
        uploadButton.prop('disabled', true);

        uploadedFiles = [];
        failedFiles = [];
        for (var i=0; i<files.length; i++) {
            uploadFileToS3(uploadDir, uploadFormId, files[i], uploadButton, uploadButtonText);
        }
    });
});
