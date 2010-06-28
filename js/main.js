jQuery(document).ready( function () {
	
	jQuery('#search input').focus( function() {
		jQuery(this).addClass('focus');
		jQuery(this).val('');
	});
	
	jQuery('#search input').blur( function() {
		jQuery(this).removeClass('focus');
		
		if ( jQuery(this).val() == '' ) {
			jQuery(this).val('Search');
		}
		
	});
	
} );