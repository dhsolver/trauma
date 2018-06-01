$(function() {
    $('.form-input-date').datepicker({
        format: "mm/dd/yyyy",
        autoclose: true,
    });

    $('button[data-upload="s3"]').click(function(e) {
        e.preventDefault();

        var fileInputId = $(this).data('upload-file');
        var files = $(fileInputId).prop('files');
        if (files.length === 0) return;

        var uploadDir = $(this).data('upload-dir');
        var uploadFormId = $(this).data('upload-form');
        $(uploadFormId + ' input[name="fileKeys[]"]').remove();

        var button = $(this);
        var buttonText = button.text();
        button.text('Uploading ...')
        button.prop('disabled', true);

        var uploadedFiles = [];
        var failedFiles = [];

        for (var i=0; i<files.length; i++) {
            var file = files[0];
            var formData = new FormData(document.getElementById('s3-form'));
            var fileKey = uploadDir + '/' + Date.now() + '-' + file.name;
            formData.set('key', fileKey);
            if (file.size > S3_MAX_SIZE) {
                alert('Your file (' + file.name + ') is too large to upload');
                button.text(buttonText);
                button.prop('disabled', false);
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
                console.log(fileKey + ' is uploaded.');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'fileKeys[]',
                    value: fileKey
                }).appendTo(uploadFormId);
                uploadedFiles.push(file.name);
            }).fail(function(xhr, status, err) {
                console.log(file.name + ' is NOT uploaded.');
                console.log('upload failed', xhr, status. err);
                failedFiles.push(file.name);
            }).always(function() {
                if (uploadedFiles.length + failedFiles.length === files.length) {
                    $(uploadFormId).submit();
                }
            });
        }
    });
});
