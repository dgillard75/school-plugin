<?php
add_action('init', 'iec_rich_text_tags', 9999);
function iec_rich_text_tags() {
	global $wpdb, $user, $current_user, $pagenow, $wp_version;

	if( $pagenow == 'edit-tags.php' ) {
		if(!user_can_richedit()) { return; }
		wp_enqueue_script('iec_rte', plugin_dir_url( __FILE__ ).'/rt_taxonomy.js', array('jquery'));
		$taxonomies = get_taxonomies();
		foreach($taxonomies as $tax) {
			add_action($tax.'_edit_form_fields', 'iec_add_form');
			add_action($tax.'_add_form_fields', 'iec_add_form');
		}
		
		add_filter('attachment_fields_to_edit', 'iec_add_form_media', 1, 2);
		add_filter('media_post_single_attachment_fields_to_edit', 'iec_add_form_media', 1, 2);
		
		if($pagenow == 'edit-tags.php' && isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && empty($_REQUEST['taxonomy'])) {
			add_action('edit_term','iec_rt_taxonomy_save');
		}
		
		foreach ( array( 'pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description' ) as $filter ) {
			remove_filter( $filter, 'wp_filter_kses' );
		}
	}
	
	// Enable shortcodes in category, taxonomy, tag descriptions
	if(function_exists('term_description')) {
		add_filter('term_description', 'do_shortcode');
	} else {
		add_filter('category_description', 'do_shortcode');
	}
}

function iec_add_form_media($form_fields, $post) {
	$form_fields['post_content']['input'] = 'html';
	
	// We remove the ' and " from the $name so it works for tinyMCE.
	$name = "attachments[$post->ID][post_content]";

	// Let's grab the editor.
	ob_start();
	wp_editor($post->post_content, $name, 
			array(
				'textarea_name' => $name, 
				'editor_css' => iec_rtt_get_css(),
			)
	);
	$editor = ob_get_clean();

	$form_fields['post_content']['html'] = $editor;

	return $form_fields;
}

function iec_rtt_get_css() {
	return '
	<style type="text/css">
		.wp-editor-container .quicktags-toolbar input.ed_button {
			width:auto;
		}
		.html-active .wp-editor-area { border:0;}		
		.wp-editor-container textarea.wp-editor-area{ width:99.8% !important; }
	</style>';
}

function iec_add_form($object = ''){
	global $pagenow;
	
	?>
	
	<style type="text/css">
		.quicktags-toolbar input { width:auto!important; }
		.wp-editor-area {border: none!important;}
	</style>
	
	<?php
	
	// This is a profile page
	if(is_a($object, 'WP_User')) {
		$content = html_entity_decode(get_user_meta($object->ID, 'description', true));
		$editor_selector = $editor_id = 'description';
		?>
	<table class="form-table rich-text-tags">
	<tr>
		<th><label for="description"><?php _e('Biographical Info'); ?></label></th>
		<td><?php wp_editor($content, $editor_id, 
			array(
				'textarea_name' => $editor_selector, 
				'editor_css' => iec_rtt_get_css(),
			)); ?><br />
		<span class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></span></td>
	</tr>
<?php
	} 
	// This is a taxonomy
	else {
		$content = is_object($object) && isset($object->description) ? html_entity_decode($object->description) : '';
		
		if( in_array($pagenow, array('edit-tags.php')) ) {
			$editor_id = 'tag_description';
			$editor_selector = 'description';
		} else {
			$editor_id = $editor_selector = 'category_description';
		}
		
		?>
<tr class="form-field">
	<th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
	<td><?php wp_editor($content, $editor_id, 
		array(
			'textarea_name' => $editor_selector,
			'editor_css' => iec_rtt_get_css(),
		)); ?><br />
	<span class="description"><?php _e('The description is not prominent by default, however some themes may show it.'); ?></span></td>
</tr>
<?php 
	}

}
?>