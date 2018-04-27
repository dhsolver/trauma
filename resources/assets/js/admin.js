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
});
