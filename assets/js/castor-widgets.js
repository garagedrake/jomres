castorJquery(document).ready(function() {
	castorJquery( '#jr_widgets_dropdown a' ).on( 'click', function( event ) {
		var $target = castorJquery( event.currentTarget ),
		val = $target.attr( 'data-value' ),
		$inp = $target.find( 'input' ),
		idx;

		if ( ( idx = jr_widgets_selected.indexOf( val ) ) > -1 ) {
			jr_widgets_selected.splice( idx, 1 );
			setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
			toggle_castor_widget(val, 0);
		} else {
			jr_widgets_selected.push( val );
			setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
			toggle_castor_widget(val, 1);
		}

		return false;
	});

	castorJquery( '.jr-widget-column' ).sortable({
		connectWith: '.jr-widget-column',
		dropOnEmpty: true,
		handle: '.jr-widget-heading',
		cancel: '.jr-widget-toggle',
		placeholder: 'jr-widget-placeholder',
		start: function(e, ui){
			ui.placeholder.width(ui.item.width());
			ui.placeholder.height(ui.item.height());
		},
		stop: function(e, ui){
			add_placeholders();
			toggle_castor_widget(ui.item.data('id'), 1, ui.item.parent().data('id'), ui.item.index());
		}
	});

	castorJquery( '.jr-widget-toggle' ).on( "click", function() {
		var icon = castorJquery( this );
		icon.toggleClass( 'fa-toggle-off fa-toggle-on' );
		icon.closest( '.jr-widget-panel' ).find( '.jr-widget-content' ).toggle();
	});
	
	add_placeholders();
});

function add_placeholders() {
	castorJquery('.jr-widget-column.ui-sortable').each(function () {
		if (castorJquery.trim(castorJquery(this).html()) == '') {
			castorJquery(this).html('<div class="jr-widget-empty"></div>');
		} else {
			if (castorJquery.trim(castorJquery(this).html()) != '<div class="jr-widget-empty"></div>') {
				castorJquery(this).find('.jr-widget-empty').remove();
			}
		}
	});
}

function toggle_castor_widget(jr_w, jr_w_enabled, jr_w_col, jr_w_pos) {
	divs = '';
	parent_div = '.jr-widget-col'+jr_w_col+' > div';
	castorJquery(parent_div).map(function() {
		divs  = divs + this.id+",";
	});

	castorJquery.ajax({
		type: 'GET',
		dataType: 'html',
		url: live_site_ajax + '&task=toggle_castor_widget_ajax',
		data: {
			jr_widget: jr_w,
			jr_widget_enabled: jr_w_enabled,
			jr_widget_column: jr_w_col,
			jr_widget_position: jr_w_pos,
			jr_widget_order: divs,
		},
		success: function(data) {
			var jr_widget_response = JSON && JSON.parse(data) || castorJquery.parseJSON(data);
			
			if (jr_widget_response.enabled == 1) {
				castorJquery('.jr-widget-col1').find('.jr-widget-empty').remove();

				if (castorJquery('#jr_widget_' + jr_widget_response.widget).length === 0) {
					castorJquery('.jr-widget-col1').append(jr_widget_response.content);
				}
			} else {
				if (castorJquery('#jr_widget_' + jr_widget_response.widget).length) {
					castorJquery('#jr_widget_' + jr_widget_response.widget).remove();
					add_placeholders();
				}
			}
			
			castorJquery(".jr-widget-column").sortable('refresh');
		}
	});
}


