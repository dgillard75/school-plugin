
jQuery(document).ready(function() {
	
	/********** ADDING NEW TOPIC ********/
	
	jQuery('#addnewTopic').click(function() {
		var topicname = jQuery('#newTopic').val();
		var topicorder = jQuery('#neworder').val();
		var moduleID = jQuery('#moduleID').val();
		var modulePageID = jQuery('#modulePageID').val();
		var URL = jQuery('#URL').val();
		
		if(topicname == '') {
			alert('Please Enter Topic name');
			return false;
		} else {
				var data = {
						action: 'add_new_topic',
						topicname: topicname,
						topicorder: topicorder,
						moduleID: moduleID,
						modulePageID: modulePageID,
						URL:URL
					};

				jQuery.post(ajaxurl, data, function(response) {
				
					jQuery('#topictable').append(response);
					jQuery('#newTopic').val(' ');
					jQuery('#neworder').val(' ');
				});

		}
	});

	/********** ADDING NEW TOPIC ********/	
	/*
	jQuery('#course_img_button').click(function() {
		formfield = jQuery('#course_img').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
	
	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#course_img').val(imgurl);
		tb_remove();
	}*/
	
	// /*****************search packeges ************************/
	
	jQuery('#searchsubmit').click(function() {
		var searchinput = jQuery('#postsearchinput').val();
		
		
		if(searchinput == '') {
			
			return false;
		} else {
				var data = {
						action: 'search_packege',
						searchinput: searchinput,
						
					};

				jQuery.post(ajaxurl, data, function(response) {
				
					jQuery('#packtable').html('');
					jQuery('#packtable').append(response);
					
				});

		}
	});
	
	// /*****************End search packeges ************************/
	// /*****************Enter quizes************************/
	
		jQuery('#addnewquizes').click(function() {
		var quizename = jQuery('#newquizes').val();
		var duration = jQuery('#duration').val();
		var moduleID = jQuery('#moduleID').val();
		//alert('quizename' +quizename +'duration' + duration);
		if(quizename == '') {
			alert('Please Enter Quize name');
			return false;
		}
		else if(duration == '') {
			alert('Please Enter Duration');
			return false;
		} else {
				var data = {
						action: 'add_new_quize',
						quizename: quizename,
						duration: duration,
						moduleID: moduleID,
						
					};

				jQuery.post(ajaxurl, data, function(response) {
					location.reload(); 
					jQuery('#quizename').val(response['title']);
					jQuery('#duration').val(response['duration']);
					jQuery('#addques').show();
					
				});

		}
	});
	
	
	
	
	
	
	
});
