$(function() {
    $('.form-input-date').datepicker({
        format: "mm/dd/yyyy",
        autoclose: true
    });

    $('#users-search-export').on('click', function(e) {
        var id = window.location.href.indexOf('?')
        if (id >= 0) {
            window.location.href = window.location.href + '&export=csv';
        } else {
            window.location.href = window.location.href + '?export=csv';
        }
        e.preventDefault();
    });

    $('.form-course input[type=checkbox][name=online_only]').change(function(e) {
        var courseForm = $(this).closest('.form-course');
        if (this.checked) {
            courseForm.find('#date_start').prop('disabled', true);
            courseForm.find('#date_end').prop('disabled', true);
        } else {
            courseForm.find('#date_start').prop('disabled', false);
            courseForm.find('#date_end').prop('disabled', false);
        }
    });
    $('.form-course input[type=checkbox][name=online_only]').trigger('change');
});
