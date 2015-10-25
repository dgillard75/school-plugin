<?php
/**
 * Created by PhpStorm.
 * User: dgillard75
 * Date: 5/4/2015
 * Time: 8:09 PM
 */
global $wpdb; // this is how you get access to the database
if(isset($_POST['topicid']) && !empty($_POST['topicid'])) {
    mysql_query("UPDATE tbl_topics SET topic_name='".$_POST['topicname']."' WHERE id=".$_POST['topicid']);

} else {
    $moduleId = $_POST['moduleID'];
    $modulecourses = array();
    $modulepackages = array();

    $course_qry = mysql_query("SELECT c.term_id FROM module_course mc LEFT JOIN course c ON c.id= mc.course_id WHERE mc.module_id = $moduleId");
    $modulecourses = array();
    while($course_result = mysql_fetch_array($course_qry)) {
        $modulecourses[] = $course_result['term_id'];
    }

    $package_qry = mysql_query("SELECT p.term_id FROM  module_package mp LEFT JOIN package p ON p.id=mp.package_id WHERE mp.module_id = $moduleId");
    $modulepackages = array();
    while($package_result = mysql_fetch_array($package_qry)) {
        $modulepackages[] = $package_result['term_id'];
    }




    $my_post = array(
        'post_title'    => $_POST['topicname'],
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'topic',
        'post_parent'	  => $_POST['modulePageID'],
        'tax_input'     => array( 'course' => $modulecourses, 'package'=>$modulepackages )
    );

    $post_id = wp_insert_post( $my_post, $wp_error );

    $wpdb->insert('tbl_topics', array( 'topic_name' => $_POST['topicname'],'tbl_order' => $_POST['topicorder'],'module_id' => $_POST['moduleID'],'pageid' => $post_id));
    @$lastinsertid = $wpdb->insert_id;
    $qer1="select * from tbl_topics where id=".$lastinsertid." order by tbl_order";
    $re1=mysql_query($qer1);
    $row1=mysql_fetch_array($re1);
    echo "<tr>
						<td>
							<input type='text' name='orders[]' value='".$row1['tbl_order']."' style='width:40px'>
							<input type='hidden' name='topics[]' value='".$row1['id']."' style='width:40px'>
						</td>
						<td><span id='topic_name_".$row1['id']."'>".$row1['topic_name']."</span>
							<div id='topic_row_".$row1['id']."' style='display:none'>
								<input type='text' id='topic_".$row1['id']."' value='".$row1['topic_name']."' style='width:340px' />
								<input type='button' value='Update' style='width:60px' onclick='editTopic(".$row1['id'].")'/>
							</div>
							<div class=\"row-actions\">
								<span class=\"edit\"><a href='javascript:void(0)' onclick='editTopicRow(".$row1['id'].")'>Edit</a> </span>
								<span class=\"edit\"><a href='".$_POST['URL']."&delId=".$row1['id']."'>Delete</a> </span>
							</div>
						</td>
						<td><a href='".get_edit_post_link($row1['pageid'])."' target='_blank'>Content</td>
				   </tr>";

    /* echo "<tr>
        <td>".$_POST['topicname']."</td>
   </tr>"; */
}

?>