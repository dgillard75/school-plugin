
jQuery(document).ready(function() {
	jQuery('#notcourses').change(function() {
	var courseid = jQuery('#notcourses').val();
		var data = {
				action: 'add_new_user_course',
				courseid: courseid,
			};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#returnresult').html(response);
		});
	});
	
	jQuery('#notpackages').change(function() {
	var packageid = jQuery('#notpackages').val();
		var data = {
				action: 'add_new_user_package',
				packageid: packageid,
			};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#returnresult').html(response);
		});
	});
	
	
	
});
function activeinactive(id,action) {
			var aiaction =action;
		
	var data = {
				action: 'updatestatus',
				id: id,
				aiaction: aiaction,
			};
		jQuery.post(ajaxurl, data, function(response) {
			location.reload();
		});
//jQuery('#course_type').val(str);
}
function activeinactivepack(id,action) {
			var aiaction =action;
		
	var data = {
				action: 'updatepack_status',
				id: id,
				aiaction: aiaction,
			};
		jQuery.post(ajaxurl, data, function(response) {
			location.reload();
		});
//jQuery('#course_type').val(str);
}
