jQuery(document).ready( function () {
	
	jQuery.getJSON( CWM.template_directory + '/image.php', function(data) {
		jQuery('#banner').css('background', 'url("' + CWM.template_directory + '/' + data.img + '") no-repeat');
		jQuery('#banner').css('width', data.width + 'px');
		jQuery('#banner').css('height', data.height + 'px');
		
		jQuery('#banner .flickr_link').attr('href', data.flickr);
	} );
	
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