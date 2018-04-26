$(function() {
	console.log('jQuery loaded');
	$('.form-input-date').datepicker({
		format: "mm/dd/yyyy",
		autoclose: true,
		defaultViewDate: new Date(2000, 1, 1)
	});
});
