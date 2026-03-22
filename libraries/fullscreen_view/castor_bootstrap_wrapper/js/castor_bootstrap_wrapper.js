// Turn radios into btn-group
castorJquery(document).ready(function() {
	castorJquery('.radio.btn-group label').addClass('btn');
	castorJquery('.btn-group label:not(.active)').click(function()
	{
		var label = castorJquery(this);
		var input = castorJquery('#' + label.attr('for'));
	
		if (!input.prop('checked')) {
			label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
			if (input.val() == '') {
				label.addClass('active btn-primary');
			} else if (input.val() == 0) {
				label.addClass('active btn-danger');
			} else {
				label.addClass('active btn-success');
			}
			input.prop('checked', true);
			input.trigger('change');
		}
	});
	castorJquery('.btn-group input[checked=checked]').each(function()
	{
		if (castorJquery(this).val() == '') {
			castorJquery('label[for=' + castorJquery(this).attr('id') + ']').addClass('active btn-primary');
		} else if (castorJquery(this).val() == 0) {
			castorJquery('label[for=' + castorJquery(this).attr('id') + ']').addClass('active btn-danger');
		} else {
			castorJquery('label[for=' + castorJquery(this).attr('id') + ']').addClass('active btn-success');
		}
	});
});
