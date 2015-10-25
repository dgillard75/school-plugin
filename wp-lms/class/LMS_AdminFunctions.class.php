<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/29/15
 * Time: 11:52 AM
 */

include_once(LMS_PLUGIN_PATH."inc/db_defines.php");
include_once(LMS_PLUGIN_PATH."inc/LMS_Defines.php");
include_once(LMS_PLUGIN_PATH."class/LMS_Log.class.php");


class LMS_AdminFunctions {

    /***************  LESSONS/UNITS FUNCTIONS ******************/
    public static function add_lesson($lesson_data){
        global $wpdb;
        $result = $wpdb->insert(LESSONS_TABLE, array(
            LESSONS_TBL_COURSE_ID => $lesson_data[LESSONS_TBL_COURSE_ID],
            LESSONS_TBL_MODULE_ID => $lesson_data[LESSONS_TBL_MODULE_ID],
            LESSONS_TBL_UNIT_NUMBER => $lesson_data[LESSONS_TBL_UNIT_NUMBER],
            LESSONS_TBL_NAME => $lesson_data[LESSONS_TBL_NAME],
            LESSONS_TBL_FILENAME => $lesson_data[LESSONS_TBL_FILENAME]
            )
        );
        return $result;
    }


    /**
     * @param $course_id
     * @return mixed
     */
    public static function retrieve_lesson($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . LESSONS_TABLE . " WHERE id='" . $id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        //LMS_Log::log_db("SELECT", __FUNCTION__);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }

    public static function retrieve_lesson_by_module($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . LESSONS_TABLE . " WHERE ".LESSONS_TBL_MODULE_ID."='" . $id . "' ORDER BY unit_number" ;
        $result = $wpdb->get_results($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        //LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }

    /***************  COURSE FUNCTIONS *************/

    public static function get_all_courses($output_type = OBJECT)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE;
        //self::debug($sql);
        $result = $wpdb->get_results($sql,$output_type);

        return $result;
    }


    /**
     * @param $course_id
     * @return mixed
     */
    public static function retrieve_course($course_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE . " WHERE id='" . $course_id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    /**
     * @param $course_data
     * @return mixed
     */
    public static function add_course($course_data)
    {
        global $wpdb;
        $result = $wpdb->insert(COURSES_TABLE, array(
                COURSES_TBL_CNAME => $course_data[COURSES_TBL_CNAME],
                COURSES_TBL_CPRICE => $course_data[COURSES_TBL_CPRICE],
                COURSES_TBL_CCODE => $course_data[COURSES_TBL_CCODE],
                COURSES_TBL_DISCOUNT_PRICE => $course_data[COURSES_TBL_DISCOUNT_PRICE],
                COURSES_TBL_CIMG => $course_data[COURSES_TBL_CIMG],
                COURSES_TBL_CTIME => $course_data[COURSES_TBL_CTIME],
                COURSES_TBL_CTIMETYPE => $course_data[COURSES_TBL_CTIMETYPE],
                COURSES_TBL_SHORT_DESC => $course_data[COURSES_TBL_SHORT_DESC],
                COURSES_TBL_DESC => $course_data[COURSES_TBL_DESC],
                COURSES_TBL_TERM_ID => $course_data[COURSES_TBL_TERM_ID],
                COURSES_TBL_TERM_TAXONOMY_ID => $course_data[COURSES_TBL_TERM_TAXONOMY_ID],
                COURSES_TBL_STATUS => $course_data[COURSES_TBL_STATUS],
                COURSES_TBL_ORD => $course_data[COURSES_TBL_ORD]
            )
        );

        return $result;
    }

    /**
     * @param $course_data
     */
    public static function update_course($course_data)
    {
        global $wpdb;
        $result = $wpdb->update(COURSES_TABLE, array(
                COURSES_TBL_CNAME => $course_data[COURSES_TBL_CNAME],
                COURSES_TBL_CPRICE => $course_data[COURSES_TBL_CPRICE],
                COURSES_TBL_CCODE => $course_data[COURSES_TBL_CCODE],
                COURSES_TBL_DISCOUNT_PRICE => $course_data[COURSES_TBL_DISCOUNT_PRICE],
                COURSES_TBL_CIMG => $course_data[COURSES_TBL_CIMG],
                COURSES_TBL_CTIME => $course_data[COURSES_TBL_CTIME],
                COURSES_TBL_CTIMETYPE => $course_data[COURSES_TBL_CTIMETYPE],
                COURSES_TBL_SHORT_DESC => $course_data[COURSES_TBL_SHORT_DESC],
                COURSES_TBL_DESC => $course_data[COURSES_TBL_DESC],
                COURSES_TBL_TERM_ID => $course_data[COURSES_TBL_TERM_ID],
                COURSES_TBL_TERM_TAXONOMY_ID => $course_data[COURSES_TBL_TERM_TAXONOMY_ID],
                COURSES_TBL_STATUS => $course_data[COURSES_TBL_STATUS],
                COURSES_TBL_ORD => $course_data[COURSES_TBL_ORD]
            )
        );

        return $result;
    }

    /**
     * @param $course_id
     */
    public static function delete_course($course_id)
    {


    }

    /***************  MODULE FUNCTIONS *************/

    /**
     * @param $course_id
     * @return mixed
     */
    public static function retrieve_module($module_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . MODULE_TABLE . " WHERE ".MODULES_TBL_MODULE_ID. "='" . $module_id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }
    /**
     * @param $data
     * @return int
     */
    public static function add_module($data){
        global $wpdb;

        // insert module as wp post and get post id
        $my_post = array(
            'post_title'    => $data[MODULES_TBL_MODULE_NAME],
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'topic'

        );

        $post_id = wp_insert_post($my_post);

        if($post_id > 0) {
            $wpdb->insert(MODULES_TABLE, array(
                MODULES_TBL_MODULE_NAME => $data[MODULES_TBL_MODULE_NAME],
                MODULES_TBL_PAGEID => $post_id,
                MODULES_TBL_MODULE_NUMBER => $data[MODULES_TBL_MODULE_NUMBER],
                MODULES_TBL_COURSE_ID => $data[MODULES_TBL_COURSE_ID],
                MODULES_TBL_SHORT_DESC => $data[MODULES_TBL_SHORT_DESC]));

            $rc = ReturnCodes::SUCCESS;
        }else {
            $rc = ReturnCodes::FAIL;
        }

        LMS_Log::log_db(__FUNCTION__);
        return $rc;
    }

    public static function update_module($moduleId, $data){
        global $wpdb;
        $sql = "SELECT ".MODULES_TBL_PAGEID. " FROM ".MODULES_TABLE. " WHERE ". MODULES_TBL_MODULE_ID. "=". $moduleId;
        $post_id = $wpdb->get_var($sql);

        $my_post = array(
            'ID'   => $post_id,
            'post_title'    => $data['module_name'],
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'topic'
        );

        wp_update_post( $my_post );
        $wpdb->update(MODULES_TABLE, array(
                MODULES_TBL_MODULE_NAME => $data[MODULES_TBL_MODULE_NAME],
                MODULES_TBL_PAGEID => $post_id,
                MODULES_TBL_MODULE_NUMBER => $data[MODULES_TBL_MODULE_NUMBER],
                MODULES_TBL_COURSE_ID => $data[MODULES_TBL_COURSE_ID],
                MODULES_TBL_SHORT_DESC => $data[MODULES_TBL_SHORT_DESC]));
        LMS_Log::log_db(__FUNCTION__);
        return ReturnCodes::SUCCESS;
    }

    public static function delete_module($data){


    }





    public static function get_individual_product_modules($course_id){

        $module_ids = self::get_course_modules($course_id,false);
        $moduleArr = array();
        //foreach module id get the corresponding module product
        foreach($module_ids as $value){
            $row = self::get_module_product_by_module_id($value->module_id);
            //echo "<pre> ID:" . print_r($row,true). "</pre>\n";
            $index = intval($row["product_order"]);
            $row["module_id"] = $value->module_id;
            $moduleArr[$index] = $row;
            //break;
        }
        return $moduleArr;
    }


    public static function get_module_product_by_module_id($id)
    {
        global $wpdb;
        $sql = "select products.* from " . PRODUCTS_TABLE . " products LEFT JOIN " . PRODUCTS_MODULE_TABLE . " pmodule ON products.product_id = pmodule.product_id WHERE pmodule.module_id = " . $id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }


    public static function get_product_course_mapping($product_id=null)
    {
        global $wpdb;
        if($product_id!=null){
            $sql = "SELECT * FROM " . PRODUCTS_COURSES_TABLE . " where product_id=". $product_id;
        }else {
            $sql = "SELECT * FROM " . PRODUCTS_COURSES_TABLE;
        }

        //echo "<pre>".$sql."</pre>";

        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }

    public static function get_course_modules($cid,$join=true)
    {
        global $wpdb;
        if($join) {
            $sql = "SELECT m.* FROM " . MODULE_TABLE . " m LEFT JOIN " . MODULE_COURSE_TABLE . " mc ON m.module_id = mc.module_id WHERE mc.course_id=" . $cid . " ORDER BY m.module_number";
        }else{
            $sql = "SELECT * FROM " . MODULE_COURSE_TABLE . " WHERE course_id=" . $cid . " ORDER BY ".MODULES_TBL_MODULE_NUMBER;
        }
        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }

    // ORDERS and Products
    public static function get_product_modules($pinfo, $byColumn = NO_COLUMN)
    {
        global $wpdb;

        if ($byColumn != NO_COLUMN)
            $whereClauseStr = " WHERE "  . $byColumn . "=" . "\"". $pinfo . "\"";
        else
            $whereClauseStr = "";

        $sql = "SELECT * FROM " . PRODUCTS_TABLE . $whereClauseStr;
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }
}

?>