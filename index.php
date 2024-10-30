<?php
/*
  Plugin Name: Easy Blogroll Image
  Description:  Easily add an image from your media library to your blogroll items (links)
  Version: 1.2.1
  Author: Onexa
  Author URI: https://www.onexa.nl
  License: GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/




/**
 * Add JS for the media-editor to the add/edit link screen
 *
 * @author Hiranthi Herlaar
 * @since 1.0
 *
 * @return void
 **/
function onexa_blogroll_image_js ()
{
	if ( strstr($_SERVER['PHP_SELF'],'link.php') || strstr($_SERVER['PHP_SELF'],'link-add.php') )
	{
?>
	<script type="text/javascript">
	//<![CDATA[
	var current_image = '';
	
	function send_to_editor(html) {
		var source = html.match(/src=\".*\" alt/);
		source = source[0].replace(/^src=\"/, "").replace(/" alt$/, "");
		
		document.getElementById('link_image').value = source;
		
		tb_remove();
	}
	
	
	jQuery(document).ready(function($) {
	
		$('label[for=link_image]').append(' <div class="uploader"><a href="#" id="_onx_add_media"><img src="images/media-button.png?ver=20111005" alt="<?php _e('Add image', 'blogroll_image'); ?>"></a></div>');


	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;

	$('a[id="_onx_add_media"]').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$('input[name="link_image"]').val(attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}

		wp.media.editor.open(button);
		return false;
	});

	$('.add_media').on('click', function(){
		_custom_media = false;
	});
		
		
		$('#link_image').change(function() {
		    if ( $(this).val().length > 0 )
		    {
		    	// view image link doesn't exist yet
		    	if ($('#link_image_view').length < 1)
		    		$(this).fadeIn('slow').after('<a id="link_image_view" href="#" class="thickbox"><big>&raquo;</big></a>');
		    	// view image link is hidden
			  	if ( $('#link_image_view').css('display') == 'none' ) $('#link_image_view').fadeIn('slow');
			    
			    if ($('#link_image_view').length)
				    $('#link_image_view').attr('href', $(this).val());
		    }
		    // length of the image URL == 0
		    else
		    {
		    	$('#link_image_view').fadeOut('slow');
		    }
		}).blur(function() {
			$(this).change();
		});
		
		if ( $('#link_image').val().length > 0 ) $('#link_image').change();
	});
	//]]>
	</script>
<?php
	} // end if
} // end blogroll_image_js
add_action('admin_print_scripts','onexa_blogroll_image_js',99);



/**
 * Add thickbox to the add/edit link screen
 *
 * @author Hiranthi Herlaar
 * @since 1.0
 *
 * @return void
 **/
function onexa_blogroll_image_thickbox()
{
	if ( is_admin() && ( strstr($_SERVER['PHP_SELF'],'link.php') || strstr($_SERVER['PHP_SELF'],'link-add.php') ) )
	{
		add_thickbox();
		wp_enqueue_media();
	}
}
add_action('admin_init', 'onexa_blogroll_image_thickbox');