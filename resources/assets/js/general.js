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
        if (file.size > S3_MAX_SIZE) {
            alert('Your file (' + fileName + ') is too large to upload');
            uploadButton.text(uploadButtonText);
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
                $(uploadFormId).submit();
            }
        });
    }

    $('button[data-upload="s3"]').click(function(e) {
        e.preventDefault();

        var fileInputId = $(this).data('upload-file');
        var files = $(fileInputId).prop('files');
        totalFilesCount = files.length;
        if (totalFilesCount === 0) return;

        var uploadDir = $(this).data('upload-dir');
        var uploadFormId = $(this).data('upload-form');
        $(uploadFormId + ' input[name="fileKeys[]"]').remove();

        var uploadButton = $(this);
        var uploadButtonText = uploadButton.text();
        uploadButton.text('Uploading ...')
        uploadButton.prop('disabled', true);

        uploadedFiles = [];
        failedFiles = [];
        for (var i=0; i<files.length; i++) {
            uploadFileToS3(uploadDir, uploadFormId, files[i], uploadButton, uploadButtonText);
        }
    });
});
