<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 9/24/15
 * Time: 9:58 AM
 */

class LMS_ProductCourseAccess {


    static function get_products_for_course_access($args){
        $list_of_products = array();
        $postslist = get_posts( $args );
        foreach($postslist as $posts){
            $list_of_products[$posts->ID]['product_info'] = $posts;
            $access_settings = LMS_DBFunctions::retrieve_product_access_settings($posts->ID);
            if($access_settings){
                //found an entry
                $modified_access_settings = array();
                foreach($access_settings as $product_setting){
                    //retrieve name for course or module
                    if($product_setting['product_type'] == 'module'){
                        //get module name and set it in list of products
                        $module_info = LMS_DBFunctions::retrieve_module($product_setting['wp_lms_id']);
                        $product_setting['wp_lms_title'] = $module_info[MODULES_TBL_MODULE_NAME];
                    }else{
                        //get course name and set it in list of products
                        $course_info = LMS_DBFunctions::retrieve_course($product_setting['wp_lms_id']);
                        $product_setting['wp_lms_title'] = $course_info[COURSES_TBL_CNAME];
                    }
                    $modified_access_settings[] = $product_setting;
                }
                $list_of_products[$posts->ID]['access_settings'] = $modified_access_settings;
            }
        }
        return $list_of_products;
    }


    static function grant_course_access_for_products($course_list){


    }


    static function grant_module_access_for_product($module){


    }


}