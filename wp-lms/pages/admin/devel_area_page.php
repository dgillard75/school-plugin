<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

if(LMS_PageFunctions::hasFormBeenSubmitted()){
    $edd_add_to_cart_url = get_home_url() . "/?edd_action=add_to_cart&download_id=" . $_POST['download_id'];
    wp_redirect($edd_add_to_cart_url);
    exit(0);
}else {
    $slibrary = new LMS_OnlineSchoolLibrary( 5 );
    $course   = $slibrary->get_course();

    $payment_id = 8306;
    // Basic payment meta
    $payment_meta = edd_get_payment_meta( $payment_id );

    $user_info = $payment_meta['user_info'];

    LMS_Log::print_r($payment_meta['user_info']);

    // Cart details
    $cart_items = edd_get_payment_meta_cart_details( $payment_id );

    foreach($cart_items as $items){
        $product_id = $items['id'];

        $access = LMS_DBFunctions::retrieve_product_access_settings($product_id);
        LMS_Log::print_r($access);
        if($access){
            LMS_Log::print_r("Access granted for: ".$product_id);
        }else{
            LMS_Log::print_r("Access not granted for: ".$product_id);
        }

    }
    LMS_Log::print_r($cart_items);
}
?>

    <form action="#" method="POST">
        <table cellspacing="0" cellpadding="4" border="1" width="550">
            <tbody>
            <tr>
                <td class="header" align="center" colspan="3" bgcolor="#093459"  style="color:#ffffff;"><b><?php echo $course['product_name']; ?></b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" align="center" width="20"><input type="radio" name="download_id" checked="checked" value="<?php echo $course['product_id']; ?>"  onclick="selectcours('full')" ></td>
                <td bgcolor="#ffffff"><b>Full Course of <?php echo count($course['modules']);?> Modules</b></td>
                <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $course['product_price']; ?></b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3" bgcolor="#093459" style="color:#ffffff;"><b>OR</b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
            </tr>
            <?php $counter=1; foreach($course['modules'] as $module) : ?>
                <tr>
                    <td bgcolor="#ffffff" width="20" align="center">
                        <?php if($counter==1) : ?>
                            <input type="radio" name="download_id" value="<?php echo $module['product_id']; ?>" onclick="selectcours('set')">
                        <?php else : ?>
                            <img src="<?php bloginfo('template_url');?>/images/radio.gif">
                        <?php endif ?>
                    </td>
                    <td bgcolor="#ffffff"><b><?php echo $module['product_name']?></b></td>
                    <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $module['product_price']; ?></b></td>
                </tr>
                <?php $counter++; endforeach; ?>
            <tr>
                <td align="center" colspan="3">
                    <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>" />
                    <input type="hidden" name="course_type" id="course_type" value="full" />
                    <input type="hidden" name="action"  value="add" >
                    <button type="submit" name="submitButton" value="checkout">Add Product</button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <script>
        function selectcours(str) {
            jQuery('#course_type').val(str);
        }
    </script>

<?php
exit(0);
?>


//$listofcourses = $slibrary->get_courses();


/**
$addNewCourseUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_COURSES_ADD_PAGE,is_SSL());
$editCourseUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_COURSES_EDIT_PAGE, is_SSL());
$deleteCourseUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_COURSES_DELETE_PAGE,is_SSL());
$modulesUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_MODULES_PAGE,is_SSL());
$setsUrl = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_PAGE,is_SSL());
*/

/**
include_once(WPLMS_PLUGIN_PATH."test_addquestion.php");
exit(0);

LMS_Log::print_r($_GET,__FUNCTION__);
$args['blog_id'] = '1';
$args['search'] = '*ad*';
$blogusers = get_users( $args );
foreach ( $blogusers as $user2 ) {
    LMS_Log::print_r($user2->user_login,__FUNCTION__);
    //LMS_Log::print_r($user2,__FUNCTION__);

}
**/
/**
$args['search'] = "module 6";
$args['orderby'] = "id";
$args['order'] = "desc";


$quiz = LMS_DBFunctions::get_all_quizzes($args);
LMS_Log::print_r($quiz,__FUNCTION__);
*/

/**
global $wpdb;

$sql = "SHOW columns FROM ".QUIZ_TABLE;
$result = $wpdb->get_results($sql, ARRAY_A);

// #### Course - Fields to sue
$database_tables = array(
    'wp_lms_lessons',
    'wp_lms_module',
    'wp_lms_module_course',
    'wp_lms_products',
    'wp_lms_product_course',
    'wp_lms_product_module',
    'wp_lms_quizzes',
    'wp_lms_quiz_questions',
    'wp_lms_user_courses',
    'wp_lms_user_modules',
    'wp_lms_user_progress',
    'wp_lms_user_progress_quizzes');
**/

/*
foreach($database_tables as $table){
    define(strtoupper($table),$table);
    $sql = "SHOW columns FROM ".$table;
    $result = $wpdb->get_results($sql, ARRAY_A);
    foreach($result as $value) {
        $constant_name = $table . "_" . $value['Field'];
        define(strtoupper($constant_name), $value['Field']);
    }
}*/

$course_mapping = array(
    'CGH' => 10,
    'CBN' => 7,
    'CLM' => 5,
    'CH'  => 10,
    'CHM'  => 22,
    'TASLCC'  => 27,
    'ICC'  => 23,
    'HCME'  => 30,
    'EPWCH'  => 26,
    'ABPM'  => 6,
    'BTLR'  => 32
);


function parse_post_title($title){
    $parsed_title_list = array();
    $title_strings = explode(" ",$title);

    //now parse first element XXXX-XXXX-XXXX
    $first_element_strings = explode("-",$title_strings[0]);
    $parsed_title_list[$first_element_strings[2]] = $title_strings[2];
    return $parsed_title_list;
}
    //foreach($result as $value){
      //  $constant_name = QUIZ_TABLE."_".$value['Field'];

//    define(strtoupper($constant_name),$value['Field'] );
//}

//LMS_Log::print_r(get_defined_constants(true),__FUNCTION__);

//include_once(WPLMS_PLUGIN_PATH . "pages/admin/Units.php");

$args = array( 'posts_per_page' => -1,
               'post_type' => 'download');

//$postslist = get_the_term_list(

//LMS_Log::print_r(get_cat_ID( 'Modules' ));

$taxonomy = "download_category";

$postslist = get_posts($args);
foreach($postslist as $post) {

    if (strpos($post->post_title, 'MOD') !== FALSE) {

        LMS_Log::print_r(parse_post_title($post->post_title));

        //Course Id, Module Number
    }
}

    //LMS_Log::print_r($termlist);

    //$categories = get_the_category( $post->ID );
    //var_dump( $categories );
    //LMS_Log::print_r($categories);
    //LMS_Log::print_r(explode(" ",$post->post_title));
//}

//$modules = LMS_DBFunctions::retrieve_modules_and_lessons(32);
//LMS_Log::print_r($title);
?>



