/*jQuery(document).ready(function() {
	jQuery('#upload_image_button').click(function() {
		 formfield = jQuery('#upload_image').attr('name');
		 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		 return false;
	});
 
	window.send_to_editor = function(html) {
		 imgurl = jQuery('img',html).attr('src');
		 jQuery('#upload_image').val(imgurl);
		 tb_remove();
	}							
});
*/

var uploadID          = '';
var storeSendToEditor = '';
var newSendToEditor   = '';

jQuery(document).ready(function() {
	storeSendToEditor = window.send_to_editor;
	newSendToEditor   = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery("#" + uploadID).val(imgurl);
		tb_remove();
		window.send_to_editor = storeSendToEditor;
  };
});

function Uploader(id) {
	window.send_to_editor = newSendToEditor;
	uploadID = id;
	formfield = jQuery('.upload').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	return false;
}


