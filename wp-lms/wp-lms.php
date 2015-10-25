<?php
/*
 * Plugin Name: WP LMS School
 * Version: 1.1
 * Plugin URI:
 * Description: WP LMS is WordPress Learning Managment System (L.M.S.) plugin tailored for HouseHold Staffing using PDFs as the lessons
 * Author: Dakarai Gillard
 * Author URI: http://flyplugins.com
 */

/*

 Copyright 2012-2015 Household Staff Training Institute

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */



ini_set('display_errors', true);
error_reporting(E_ALL);

/** The current version of the database. */
define('WPLMS_DATABASE_VERSION', 		'3.0');

/** The current version of the database. */
define('WPLMS_DATABASE_KEY', 			'WPCW_Version');

/** The key used to store settings in the database. */
define('WPLMS_DATABASE_SETTINGS_KEY', 	'WPCW_Settings');

/** The ID used for menus */
define('WPLMS_PLUGIN_ID', 				'WPLMS_school');

/** THE Page ID for all the Admin pages */
define('WPLMS_ADMIN_MAIN_DASHBOARD', 				'WPLMS_school');
define('WPLMS_ADMIN_STUDENT_PAGE_ID', 				'WPLMS_students');
define('WPLMS_ADMIN_COURSES_PAGE_ID', 				'WPLMS_courses');
define('WPLMS_ADMIN_MODULES_PAGE_ID', 				'WPLMS_modules');
define('WPLMS_ADMIN_QSUMMARY_PAGE_ID', 				'WPLMS_quizSummary');
define('WPLMS_ADMIN_QPOOL_PAGE_ID', 				'WPLMS_questionPool');
define('WPLMS_ADMIN_VIEW_DETAILS_PAGE_ID', 			'WPLMS_viewDetails');
define('WPLMS_ADMIN_COURSE_ACCESS_PAGE_ID', 		'WPLMS_courseAccess');
define('WPLMS_ADMIN_TEST_PAGE_ID',                  'WPLMS_testArea');
define('WPLMS_ADMIN_MODIFY_QUIZ_ID',                'WPLMS_modifyQuiz');
define('WPLMS_ADMIN_PROD_COURSE_PAGE_ID',           'WPLMS_productCourseAccess');
define('WPLMS_ADMIN_EDIT_PROD_ACCESS_PAGE_ID',      'WPLMS_editProductCourseAccess');

/** The ID of the plugin for update purposes, must be the file path and file name. */
define('WPLMS_PLUGIN_UPDATE_ID', 		'wp-lms/wp-lms.php');

/** The ID used for menus */
define('WPLMS_MENU_POSITION', 			384289);

/** Plugin Path Directory */
define("WPLMS_PLUGIN_PATH",             ABSPATH."wp-content/plugins/wp-lms/");

/** Plugin Path Directory */
define("WPLMS_ADMIN_PAGES_DIR",         WPLMS_PLUGIN_PATH."pages/admin");

/** Remove this LATER after all the changes */
define("LMS_PLUGIN_PATH",               WPLMS_PLUGIN_PATH);




function WPLMS_plugin_setup($force){
    //initial setup


}


/**
 * Initialization functions for plugin.
 */
function WPLMS_plugin_init()
{
    // Load translation support
    $domain = 'wp_lms'; // This is the translation locale.

    // Check the WordPress language directory for /wp-content/languages/wp_lms/wp_lms-en_US.mo first
    $locale = apply_filters('plugin_locale', get_locale(), $domain);
    load_textdomain($domain, WP_LANG_DIR . '/wp_lms/' . $domain . '-' . $locale . '.mo');

    // Then load the plugin version
    load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)) . '/language/');

    // Run setup
    WPLMS_plugin_setup(false);

    include_once(WPLMS_PLUGIN_PATH ."inc/wplms_database.inc.php");
    wplms_database_load_tables_definitions();


    require_once(WPLMS_PLUGIN_PATH . "inc/wplms_defines.inc.php");
    require_once(WPLMS_PLUGIN_PATH . "inc/wplms_log.inc.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_PageFunctions.class.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_StudentCourseList.class.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_StudentCourses.class.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_School.class.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_OnlineSchoolLibrary.class.php");
    require_once(WPLMS_PLUGIN_PATH . "class/LMS_UserProgress.class.php");
    require_once(WPLMS_PLUGIN_PATH . 'class/pages/LMS_FrontEndLessonPage.class.php');

    // ### Admin
    if (is_admin()) {
        //Includes and Libraries
        require_once(WPLMS_PLUGIN_PATH . "class/LMS_PageErrors.class.php");
        require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_HtmlForm.class.php");
        require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_Page.class.php");
        require_once(WPLMS_PLUGIN_PATH . "class/LMS_ProductCourseAccess.php");

        //Set Global Defines
        define("WPLMS_ADMIN_URL",               LMS_PageFunctions::wp_admin_url());

        /** Full URLS */
        define('WPLMS_ADD_COURSE_URL',          WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_COURSES_PAGE_ID."&action=new");
        define('WPLMS_EDIT_COURSE_URL',         WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_COURSES_PAGE_ID."&action=edit");
        define('WPLMS_ADD_MODULE_URL',          WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_MODULES_PAGE_ID."&action=new");
        define('WPLMS_EDIT_MODULE_URL',         WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_MODULES_PAGE_ID."&action=edit");

        define('WPLMS_DASHBOARD_URL',           WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_MAIN_DASHBOARD);
        define('WPLMS_STUDENTS_URL',            WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_STUDENT_PAGE_ID);
        define('WPLMS_QMODIFY_URL',             WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_MODIFY_QUIZ_ID);

        define('WPLMS_QMODIFY_ADD_URL',        WPLMS_QMODIFY_URL."&action=add");
        define('WPLMS_QMODIFY_EDIT_URL',       WPLMS_QMODIFY_URL."&action=edit");
        define('WPLMS_QPOOL_URL',              WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_QPOOL_PAGE_ID);
        define('WPLMS_EDIT_PROD_ACCESS_URL',   WPLMS_ADMIN_URL."?page=".WPLMS_ADMIN_EDIT_PROD_ACCESS_PAGE_ID);

        // Menus
        add_action('admin_menu', 'WPLMS_menu_MainMenu');
        add_action('admin_menu', 'WPLMS_excludeFromMenu');

    }else{


    }

    //Admin Scripts
    add_action('admin_enqueue_scripts', 'WPLMS_adminScripts');

    //AJAX calls
    add_action('wp_ajax_add_new_user_course', 'WPLMS_add_new_user_course');

    //ShortCodes
    add_shortcode('wplms_course_info',    'WPLMS_courseInfo');
    add_shortcode('wplms_choose_product', 'WPLMS_chooseProduct');
    add_shortcode('wplms_shopping_cart',  'WPLMS_shoppingCart');
    add_shortcode('wplms_checkout',       'WPLMS_checkout');
    add_shortcode('wplms_lessons',        'WPLMS_lessons');
    add_shortcode('wplms_mycourses',      'WPLMS_mycourses');
	add_shortcode('wplms_quiz',           'WPLMS_quiz');

}

/** Initialize Plugin */
add_action('init', 'WPLMS_plugin_init');


function WPLMS_excludeFromMenu(){
    add_submenu_page(null,'Add Quiz', 'Add Quiz', 'administrator', 'WPLMS_modifyQuiz', 'WPLMS_modifyQuiz');
    add_submenu_page(null,'View Course Access', 'View Course Access', 'administrator', WPLMS_ADMIN_COURSE_ACCESS_PAGE_ID, 'WPLMS_courseAccess');
    add_submenu_page(null,'Edit Product Course Access', 'Edit Product Course Access', 'administrator', WPLMS_ADMIN_EDIT_PROD_ACCESS_PAGE_ID, 'WPLMS_editProductCourseAccess');

    /**
     * Developmental Area for Plugin
     */
    add_submenu_page(null,'Test Area', 'Test Area', 'administrator', WPLMS_ADMIN_TEST_PAGE_ID, 'WPLMS_testArea');


}


/**
 * Main Menu setup for plugin for admin panel
 */
function WPLMS_menu_MainMenu(){

    add_menu_page('WP LMS School', 'WP LMS School', 'administrator',WPLMS_PLUGIN_ID,'WPLMS_dashboard');

    add_submenu_page(WPLMS_PLUGIN_ID,'Students',    'Students',    'administrator',WPLMS_ADMIN_STUDENT_PAGE_ID,'WPLMS_students');
    add_submenu_page(WPLMS_PLUGIN_ID,'Add Course',  'Add Course',  'administrator',WPLMS_ADMIN_COURSES_PAGE_ID,'WPLMS_addcourse');
    add_submenu_page(WPLMS_PLUGIN_ID,'Add Modules', 'Add Modules', 'administrator',WPLMS_ADMIN_MODULES_PAGE_ID,'WPLMS_addmodule');

    /** Horizontal bar in menu to divide section */
    add_submenu_page(WPLMS_PLUGIN_ID, false, '<span class="wpcw_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);

    add_submenu_page(WPLMS_PLUGIN_ID,'Quiz Summary', 'Quiz Summary',   'administrator', WPLMS_ADMIN_QSUMMARY_PAGE_ID,'WPLMS_quizSummary');
    add_submenu_page(WPLMS_PLUGIN_ID,'Question Pool', 'Question Pool', 'administrator', WPLMS_ADMIN_QPOOL_PAGE_ID,   'WPLMS_questionPool');

    /** Horizontal bar in menu to divide section */
    add_submenu_page(WPLMS_PLUGIN_ID, false, '<span class="wpcw_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #CCC;"></span>', 'manage_options', '#', false);
    add_submenu_page(WPLMS_PLUGIN_ID,'Product - Course Access', 'Product - Course Access',   'administrator', WPLMS_ADMIN_PROD_COURSE_PAGE_ID,WPLMS_ADMIN_PROD_COURSE_PAGE_ID);



    add_submenu_page(null,'Stud','Student Courses', 'administrator','studentCourses','WPLMS_studentcourses');
    add_submenu_page(null,'Add Question', 'Add Question', 'administrator', 'add_question', 'WPLMS_addQuestion');

}


function WPLMS_buttons() {
    add_filter( "mce_external_plugins", "wptuts_add_buttons" );
    add_filter( 'mce_buttons',          'wptuts_register_buttons' );
}

function WPLMS_add_buttons( $plugin_array ) {
    $plugin_array['wptuts'] = plugins_url('/wp-lms/js/wptuts-plugin.js',dirname(__FILE__));
    return $plugin_array;
}

function WPLMS_register_buttons( $buttons ) {
    array_push( $buttons,'showmodule','module_position' ); // dropcap', 'recentposts
    return $buttons;
}


/************** Main Menu Callback Functions  *******************/
function WPLMS_dashboard(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/dashboard_page.php";
}

function WPLMS_students(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/all_students.php";
}

function WPLMS_addcourse(){
    WPLMS_buttons();
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/course_modify_page.php";
}

function WPLMS_addmodule(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/modules_modify_page.php";
}

function WPLMS_modifyQuiz(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/quiz_modify.php";
}

function WPLMS_studentcourses(){
    require_once WPLMS_PLUGIN_PATH . "/pages/student_courses_admin_page.php";
}

function WPLMS_addQuestion(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/add_question.php";
}

function WPLMS_quizSummary(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/quiz_summary.php";
}

function WPLMS_questionPool(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/question_pool.php";
}

function WPLMS_courseAccess(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/admin_course_permissions.php";
}

function WPLMS_testArea(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/devel_area_page.php";
}

function WPLMS_productCourseAccess(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/product_course_access_summary.php";
}

function WPLMS_editProductCourseAccess(){
    require_once WPLMS_PLUGIN_PATH . "/pages/admin/a.php";
}


/********************* Admin Styles/Scripts *****************/
function WPLMS_adminScripts(){
    wp_register_style( 'custom_wp_admin_css_default', plugin_dir_url( __FILE__ ).'css/style.css' );
    wp_enqueue_style( 'custom_wp_admin_css_default' );

    wp_register_style( 'custom_wp_admin_wplms_default', plugin_dir_url( __FILE__ ).'css/wplms_admin.css' );
    wp_enqueue_style( 'custom_wp_admin_wplms_default' );

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script( 'custom_wp_admin_script_default', plugin_dir_url( __FILE__ ).'js/schools_scripts.js',array('media-upload','thickbox'));
    wp_enqueue_script( 'custom_wp_admin_script_default' );
    wp_enqueue_style('thickbox');

    wp_register_script( 'custom_wp_admin_script_default1', plugin_dir_url( __FILE__ ).'js/courses.js');
    wp_enqueue_script( 'custom_wp_admin_script_default1' );
    wp_register_script( 'custom_wp_admin_script_default2', plugin_dir_url( __FILE__ ).'js/script.js');
    wp_enqueue_script( 'custom_wp_admin_script_default2' );

    wp_register_script( 'custom_wp_admin_script_default3', plugin_dir_url( __FILE__ ).'js/simpletabs_1.3.js');
    wp_enqueue_script( 'custom_wp_admin_script_default3' );
}

/********************* AJAX Calls  *****************/
function WPLMS_add_new_user_course(){

}

function pw_edd_on_complete_purchase( $payment_id ) {

    // Basic payment meta
    $payment_meta = edd_get_payment_meta( $payment_id );

    // Cart details
    $cart_items = edd_get_payment_meta_cart_details( $payment_id );

    // do something with payment data here
    $logmessage = var_export($cart_items, true) . "\nPayment_Id = " . " $payment_id";
    error_log($logmessage, 3, WPLMS_PLUGIN_PATH . "logs/purchase.log");


}
add_action( 'edd_complete_purchase', 'pw_edd_on_complete_purchase' );

/********************* SHORTCODE Callback Functions ********************/

/**
 *
 */
function WPLMS_courseInfo($atts, $content = NULL){
    $retvalue="";
    $atts = shortcode_atts(array(
        'query' => 'all_course',
        'id'    => '',
        'field' => ''
    ), $atts);

    switch($atts['query']) {
        case 'modules':
            ob_start();
            require_once(WPLMS_PLUGIN_PATH . "shortcodes/modules_and_units.php");
            $retvalue = ob_get_clean();
            break;
        case 'courses':
            $courses = LMS_DBFunctions::retrieve_course($atts['id']);
            if($courses && array_key_exists($atts['field'], $courses))
                $retvalue = $courses[$atts['field']];
            break;
        default:
            break;
    }

    return $retvalue;
}

/**
 *
 */
function WPLMS_chooseProduct(){
    ob_start();
    require_once(WPLMS_PLUGIN_PATH. "shortcodes/choose_product.php");
    return ob_get_clean();
}

/**
 *
 */
function WPLMS_shoppingCart(){
    require_once(WPLMS_PLUGIN_PATH. "shortcodes/checkout.php");
}

/**
 *
 */
function WPLMS_checkout(){
    require_once(WPLMS_PLUGIN_PATH. "shortcodes/checkout.php");
}

/**
 *
 */
function WPLMS_lessons(){
    ob_start();
    require_once(WPLMS_PLUGIN_PATH. "shortcodes/lessons.php");
    return ob_get_clean();
}

function WPLMS_mycourses(){
    ob_start();
    require_once(WPLMS_PLUGIN_PATH. "shortcodes/mycourse.php");
    return ob_get_clean();
}

function WPLMS_quiz(){
	require_once(WPLMS_PLUGIN_PATH. "shortcodes/take_quiz.php");
}