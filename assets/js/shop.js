function addToCart(plugin_name, price) {
	castorJquery('#shopping_cart').modal();
	price = parseFloat(price).toFixed(2);
	already_exists = false;
	castorJquery('#cart').children().each(
		function () {
			id = castorJquery(this).attr('id');
			if (id == "row"+plugin_name && id != "") {
				already_exists = true;
			}
		});
	if (!already_exists) {
		
			addition_hidden_input = '<input type="hidden" id="cart' + plugin_name + '" type="text" value="' + price + '" autocomplete="off" />';
			cart_row = '<div id="row' + plugin_name + '"><label for="' + plugin_name + '"  style="padding:3px"><strong>Plugin :</strong> ' + plugin_name + ' <strong>Price :</strong> &pound;' + price + '  <a style="float:right" href="#" class="btn btn-warning btn-mini" onClick=\'removeFromCart(\"' + plugin_name + '\"); return false;\'>Remove</a><br/></div>';
		
		// Oddly, when in a dialog, you cannot remove from the cart, so we'll put a hidden input somewhere else in the form.
		castorJquery(addition_hidden_input).appendTo('#hidden_inputs');
		castorJquery(cart_row).appendTo('#cart');

		total = 0;
		castorJquery('#hidden_inputs').children().each(
			function () {
				value = castorJquery(this).val();
				if (value != "")
					total = total + parseFloat(value);
			});
		castorJquery('#total').html(total.toFixed(2));
	}
}

function showCart() {
	castorJquery('#shopping_cart').modal();
}

function removeFromCart(plugin_name) {
	castorJquery("#cart" + plugin_name).remove();
	castorJquery("#row" + plugin_name).remove();
	total = 0;
	castorJquery('#hidden_inputs').children().each(
		function () {
			value = castorJquery(this).val();

			if (value != "")
				total = total + parseFloat(value);

		});
	castorJquery('#total').html(total);
}

function purchase() {
	castorJquery('#shopping_cart').modal('toggle');
	var items = '';
	castorJquery('#hidden_inputs').children().each(
		function () {
			value = castorJquery.trim(castorJquery(this).attr('id'));
			if (value.substr(0, 3) != "" && value.substr(0, 3) != "row")
				items = items + "," + value;
			if (items.length > 0)
				castorJquery('#license_server_account_details').modal();
		});
}

function sumbint() //Deliberate typo
{
	var items = '';
	var username_str = "&username=" + castorJquery("#license_server_username").val() + "&";
	var password_str = "&password=" + castorJquery("#license_server_password").val() + "&";
	castorJquery('#license_server_account_details').modal('toggle');
	castorJquery('#hidden_inputs').children().each(
		function () {
			value = castorJquery.trim(castorJquery(this).attr('id'));

			if (value.substr(0, 3) != "" && value.substr(0, 3) != "row") {
				value = value.substr(4, value.length)
				items = items + value + ",";
			}
		});
	total = castorJquery('#total').text(); // Just to decide what message to return to the user

	castorJquery.ajax({
		type: 'GET',
		url: ajax_url + "&task=purchase_plugins" + username_str + password_str + "items=" + items + "&total="+total,
		data: '',
		success: function(data)
			{
			castorJquery('#license_server_message').html(data);
			castorJquery('#invoice_created').modal('show');
			}
	});
	
}

