<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 9/24/15
 * Time: 9:45 AM
 */

$args = array( 'posts_per_page' => -1, 'post_type' => 'download');
$postslist = LMS_ProductCourseAccess::get_products_for_course_access($args);

?>

<h2>Easy Digital Downloads &amp; WP-LMS Automatic Course Access Settings</h2>

<table class="wpcw_tbl widefat" id="wpcw_members_tbl" class="widefat">
    <thead>
    <tr>
        <th id="wplms_members_id" scope="col" >Product ID</th>
        <th id="wplms_members_name" scope="col" >Product Name</th>
        <th id="wplms_members_levels" scope="col" >Users with this product can access:</th>
        <th id="wplms_members_actions" scope="col" >Actions</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th id="wplms_members_id" scope="col" >Product ID</th>
        <th id="wplms_members_name" scope="col" >Product Name</th>
        <th id="wplms_members_levels" scope="col" >Users with this product can access:</th>
        <th id="wplms_members_actions" scope="col" >Actions</th>
    </tr>
    </tfoot>
    <tbody>
    <?php foreach($postslist as $post) : ?>
        <tr class="alternate">
            <td ><a href=" <?php echo get_edit_post_link( $post['product_info']->ID); ?> "><?php echo $post['product_info']->ID ?></a></td>
            <td ><?php echo $post['product_info']->post_title ?></td>
            <td >
                <ul class="wpcw_tickitems">
                    <?php
                    if(array_key_exists('access_settings',$post)) :
                        foreach($post['access_settings'] as $settings) :
                            ?>
                            <li class="wpcw_enabled"><?php echo $settings['wp_lms_title'] ?></li>
                        <?php   endforeach;
                    else :
                        ?>
                        <li class="wpcw_disabled">No Courses or Modules can be accessed.</li>
                    <?php
                    endif;
                    ?>
                </ul>
            </td>
            <td ><a href="<?php echo WPLMS_EDIT_PROD_ACCESS_URL."&level_id=".$post['product_info']->ID ?>" class="button-secondary">Edit Course Access Settings</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>