<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/29/15
 * Time: 11:52 AM
 */


class LMS_DBFunctions
{

    public static function get_student_wpuser_data($user_id)
    {
        // Get user data
        $user_account = array();
        $userdata = get_userdata($user_id);
        //LMS_Log::print_r($userdata,__FUNCTION__);

        if ($userdata != false) {
            $user_account["user_id"] = $user_id;
            $user_account["user_login"] = $userdata->user_login;
            $user_account["user_nicename"] = $userdata->user_nicename;
            $user_account["display_name"] = $userdata->display_name;
            $user_account["user_email"] = $userdata->user_email;
            //LMS_SchoolDB::debug($userdata);
            $usermeta = array_map(function ($a) {
                return $a[0];
            }, get_user_meta($user_id));
            $user_account = array_merge($user_account, $usermeta);
        }

        return $user_account;
    }

    public static function select_query($table,$whereClause,$get_row=false){
        global $wpdb;
        $sql = "SELECT * FROM " . $table . " " . $whereClause;

        if($get_row)
            $result = $wpdb->get_row($sql, ARRAY_A);
        else
            $result = $wpdb->get_results($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }



    public static function select_count_query($table,$whereClause){
        global $wpdb;
        $sql = "SELECT count(*) FROM " . $table . " " . $whereClause;

        $result = $wpdb->get_var($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }

    public static function get_next_count_for_question($quiz_id){

        $whereClause = "WHERE ".QUIZ_QUEST_TABLE_QUIZ_ID."=".$quiz_id." ORDER BY ".QUIZ_QUEST_TABLE_QORDER." DESC";

        $result = self::select_query(QUIZ_QUEST_TABLE,$whereClause);
        return $result;
    }

    public static function get_total_number_of_questions($quiz_id){
        $whereClause = "WHERE ".QUIZ_QUEST_TABLE_QUIZ_ID."=".$quiz_id;
        $result = self::select_count_query(QUIZ_QUEST_TABLE,$whereClause);
        return $result;
    }

    /***************  QUIZZES/QUESTIONS FUNCTIONS ******************/
    public static function add_question_to_quiz($question_data){
        global $wpdb;
        $result = $wpdb->insert(QUIZ_QUEST_TABLE, array(
            QUIZ_QUEST_TABLE_QUIZ_ID    => $question_data[QUIZ_QUEST_TABLE_QUIZ_ID],
            QUIZ_QUEST_TABLE_QUES   => $question_data[QUIZ_QUEST_TABLE_QUES],
            QUIZ_QUEST_TABLE_CANS   => $question_data[QUIZ_QUEST_TABLE_CANS],
            QUIZ_QUEST_TABLE_ANS    => $question_data[QUIZ_QUEST_TABLE_ANS]
            )
        );
        return $result;
    }

    public static function update_question_on_quiz($question_data){
        global $wpdb;
        $result = $wpdb->update(QUIZ_QUEST_TABLE, array(
                QUIZ_QUEST_TABLE_QUIZ_ID    => $question_data[QUIZ_QUEST_TABLE_QUIZ_ID],
                QUIZ_QUEST_TABLE_QUES   => $question_data[QUIZ_QUEST_TABLE_QUES],
                QUIZ_QUEST_TABLE_CANS   => $question_data[QUIZ_QUEST_TABLE_CANS],
                QUIZ_QUEST_TABLE_ANS    => $question_data[QUIZ_QUEST_TABLE_ANS]),
                array(QUIZ_QUEST_TABLE_QUES_ID =>$question_data[QUIZ_QUEST_TABLE_QUES_ID])
            );
        return $result;
    }

	public static function get_quiz($module_id)
	{
		global $wpdb;
		$sql="SELECT * from ".QUIZ_TABLE. " where module_id=".$module_id." order by quize_order";
		$result = $wpdb->get_row($sql,ARRAY_A);
		LMS_Log::log_db("SELECT", __FUNCTION__);
		return $result;
	}


    public static function get_quizzes_by_course($course_id){
        global $wbdb;

        $quiz_list="";
        //get all module ids for course
        $modules = self::get_modules($course_id);
        foreach($modules as $module){
            //for each module retrieve Quiz
            $quiz = self::select_query(QUIZ_TABLE,"WHERE ".QUIZ_TABLE_MID."=".$module->module_id, true);
            if($quiz)
                $quiz_list[] = $quiz;
        }
        return $quiz_list;
    }

    /*
     * Parameters Information for Args Array:
     * orderby - Sort by 'ID', 'login', 'nicename', 'email', 'url', 'registered', 'display_name'.
     * order - ASC (ascending) or DESC (descending).
     * search - Use this argument to search users by email address, URL, ID, username or display_name.
     */

    /**
     *
     * Get All Quizes
     *
     * Parameters Information for Args Array:
     * orderby - Sort by 'ID', 'login', 'nicename', 'email', 'url', 'registered', 'display_name'.
     * order - ASC (ascending) or DESC (descending).
     * search - Use this argument to search users by email address, URL, ID, username or display_name.
     *
     *
     *
     * @param $args
     * @return mixed
     */
    public static function get_all_quizzes($query_args="")
    {
        global $wpdb;

        $sql = "SELECT quiz.*,module.module_name from wp_lms_quizzes quiz LEFT JOIN wp_lms_module module USING (module_id)";

        if (!empty($query_args)){
            if(isset($query_args["search"])) {
                $sql .= " WHERE " . QUIZ_TABLE_TITLE . " REGEXP '" . $query_args["search"] . "' or " . QUIZ_TABLE_ID . " REGEXP '" . $query_args["search"] ."'";
            }

            if(isset($query_args["orderby"])){
                $sql .= " ORDER BY ".$query_args["orderby"];

                if(isset($query_args["order"])) {
                    $sql .= " " . $query_args["order"];
                }
            }
        }
        $quizzes = $wpdb->get_results($sql, ARRAY_A);
        return $quizzes;
    }

    public static function add_quiz($data) {
        global $wpdb;
        $result = $wpdb->insert( QUIZ_TABLE, array(
                QUIZ_TABLE_TITLE    => $data[ QUIZ_TABLE_TITLE ],
                QUIZ_TABLE_MID      => $data[ QUIZ_TABLE_MID ],
                QUIZ_TABLE_DURATION => "60"
            )
        );

        return $result;
    }


    public static function update_quiz($data) {
        global $wpdb;
        $result = $wpdb->update( QUIZ_TABLE, array(
                QUIZ_TABLE_TITLE    => $data[ QUIZ_TABLE_TITLE ],
                QUIZ_TABLE_MID      => $data[ QUIZ_TABLE_MID ],
                QUIZ_TABLE_DURATION => "60"),
                array(QUIZ_TABLE_ID  =>$data[QUIZ_TABLE_ID])
        );
        return $result;
    }


    public static function add_user_progress_quiz($data){
        global $wpdb;
        $rc = $wpdb->insert(USER_QUIZ_PROGRESS_TABLE, array(
            USER_QUIZ_PROGRESS_TABLE_UID => $data[USER_QUIZ_PROGRESS_TABLE_UID],
            USER_QUIZ_PROGRESS_TABLE_MID => $data[USER_QUIZ_PROGRESS_TABLE_MID],
            USER_QUIZ_PROGRESS_TABLE_QID => $data[USER_QUIZ_PROGRESS_TABLE_QID]));


        if ($rc != false)
            return $wpdb->insert_id;

        return $rc;
    }

    public static function update_user_progress_quiz($data){
        global $wpdb;
        $rc = $wpdb->update(USER_QUIZ_PROGRESS_TABLE, array(
                USER_QUIZ_PROGRESS_TABLE_CDATE  => $data[USER_QUIZ_PROGRESS_TABLE_CDATE],
                USER_QUIZ_PROGRESS_TABLE_CANS   => $data[USER_QUIZ_PROGRESS_TABLE_CANS],
                USER_QUIZ_PROGRESS_TABLE_GRADE  => $data[USER_QUIZ_PROGRESS_TABLE_GRADE],
                USER_QUIZ_PROGRESS_TABLE_QUEST_TOTAL  => $data[USER_QUIZ_PROGRESS_TABLE_QUEST_TOTAL],
                USER_QUIZ_PROGRESS_TABLE_STATUS => $data[USER_QUIZ_PROGRESS_TABLE_STATUS]),
                array(USER_QUIZ_PROGRESS_TABLE_ID => $data[USER_QUIZ_PROGRESS_TABLE_ID])
        );

        return $rc;
    }



    /***************  LESSONS/UNITS FUNCTIONS ******************/
    public static function add_lesson($lesson_data)
    {
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
        $sql = "SELECT * FROM " . LESSONS_TABLE . " WHERE " . LESSONS_TBL_MODULE_ID . "='" . $id . "' ORDER BY unit_number";
        $result = $wpdb->get_results($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    public static function retrieve_lessons_by_course($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . LESSONS_TABLE . " WHERE " . LESSONS_TBL_COURSE_ID . "='" . $id;
        $result = $wpdb->get_results($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    /***************  COURSE FUNCTIONS *************/
    public static function get_all_courses($output_type = OBJECT)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE;
        //self::debug($sql);
        $result = $wpdb->get_results($sql, $output_type);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    public static function get_active_courses($output_type = OBJECT)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE . " WHERE " . COURSES_TBL_STATUS . " = 1";
        //self::debug($sql);
        $result = $wpdb->get_results($sql, $output_type);
        return $result;
    }

    /**
     * @param $course_id
     * @return mixed
     */
    public static function retrieve_course($course_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE . " WHERE " . COURSES_TBL_COURSE_ID . "=" . $course_id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }

    /**
     * Add New Course
     * @param $course_data
     * @return mixed
     */
    public static function add_course($course_data)
    {
        global $wpdb;
        $result = $wpdb->insert(COURSES_TABLE, array(
                COURSES_TBL_CNAME => $course_data[COURSES_TBL_CNAME],
                COURSES_TBL_DESC => $course_data[COURSES_TBL_DESC],
                COURSES_TBL_STATUS => $course_data[COURSES_TBL_STATUS],
            )
        );
        LMS_Log::log_db("INSERT", __FUNCTION__);
        return $result;
    }

    /**
     * Update Course Information
     * @param $course_data
     */
    public static function update_course($course_data)
    {
        global $wpdb;
        $result = $wpdb->update(COURSES_TABLE, array(
                COURSES_TBL_CNAME => $course_data[COURSES_TBL_CNAME],
                COURSES_TBL_DESC => $course_data[COURSES_TBL_DESC],
                COURSES_TBL_STATUS => $course_data[COURSES_TBL_STATUS],
                COURSES_TBL_ORD => $course_data[COURSES_TBL_ORD]
            ),
            array(COURSES_TBL_COURSE_ID =>$course_data[COURSES_TBL_COURSE_ID])
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
        $sql = "SELECT * FROM " . MODULE_TABLE . " WHERE " . MODULES_TBL_MODULE_ID . "='" . $module_id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }


    /**
     * @param $course_id
     * @return array
     */
    public static function retrieve_modules_and_lessons($course_id){
        $modules_and_lessons = array();
        $modules = self::get_modules($course_id);
        foreach($modules as $m){
            $modules_and_lessons[$m->module_number]  = (array)$m;
            $modules_and_lessons[$m->module_number]['units'] = self::retrieve_lesson_by_module($m->module_id);
        }
        return $modules_and_lessons;
    }


    /**
     * @param $data
     * @return int
     */
    public static function add_module($data)
    {
        global $wpdb;

        // insert module as wp post and get post id
        $my_post = array(
            'post_title' => $data[MODULES_TBL_MODULE_NAME],
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'topic'

        );

        $post_id = wp_insert_post($my_post);

        if ($post_id > 0) {
            $wpdb->insert(MODULES_TABLE, array(
                MODULES_TBL_MODULE_NAME => $data[MODULES_TBL_MODULE_NAME],
                MODULES_TBL_PAGEID => $post_id,
                MODULES_TBL_MODULE_NUMBER => $data[MODULES_TBL_MODULE_NUMBER],
                MODULES_TBL_COURSE_ID => $data[MODULES_TBL_COURSE_ID],
                MODULES_TBL_SHORT_DESC => $data[MODULES_TBL_SHORT_DESC]));

            $rc = ReturnCodes::SUCCESS;
        } else {
            $rc = ReturnCodes::FAIL;
        }

        LMS_Log::log_db(__FUNCTION__);
        return $rc;
    }

    public static function update_module($moduleId, $data)
    {
        global $wpdb;
        $wpdb->show_errors;
        $rc = $wpdb->update(MODULES_TABLE, array(
            MODULES_TBL_MODULE_NAME => $data[MODULES_TBL_MODULE_NAME],
            MODULES_TBL_MODULE_NUMBER => $data[MODULES_TBL_MODULE_NUMBER],
            MODULES_TBL_COURSE_ID => $data[MODULES_TBL_COURSE_ID],
            MODULES_TBL_SHORT_DESC => $data[MODULES_TBL_SHORT_DESC]),
            array(MODULES_TBL_MODULE_ID =>$moduleId)
        );

        if($rc === false){
            $rc = ReturnCodes::FAIL;
            LMS_Log::print_r($wpdb->last_query, __FUNCTION__);
        }else{
            $rc = ReturnCodes::SUCCESS;
        }
        return $rc;
    }

    public static function delete_module($data)
    {


    }


    /**
     * // ORDERS and Products
     * public function get_products(){
     * global $wpdb;
     * $sql = "SELECT * FROM ".PRODUCTS_TABLE;
     * $result = $wpdb->get_results($sql);
     * return $result;
     * }
     * **/

    // ORDERS and Products
    public static function get_product($pinfo, $byColumn = NO_COLUMN)
    {
        global $wpdb;

        if ($byColumn != NO_COLUMN)
            $whereClauseStr = " WHERE " . $byColumn . "=" . "\"" . $pinfo . "\"";
        else
            $whereClauseStr = "";

        $sql = "SELECT * FROM " . PRODUCTS_TABLE . $whereClauseStr;
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }


    public static function get_individual_product_modules($course_id)
    {

        $module_ids = self::get_course_modules($course_id, true);
        $moduleArr = array();
        //foreach module id get the corresponding module product
        foreach ($module_ids as $value) {
            $row = self::get_module_product_by_module_id($value->module_id);
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


    public static function get_product_course_mapping($product_id = null)
    {
        global $wpdb;
        if ($product_id != null) {
            $sql = "SELECT * FROM " . PRODUCTS_COURSES_TABLE . " where product_id=" . $product_id;
        } else {
            $sql = "SELECT * FROM " . PRODUCTS_COURSES_TABLE;
        }

        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }

    public static function get_course_modules($cid, $join = true)
    {
        global $wpdb;
        if ($join) {
            $sql = "SELECT m.* FROM " . MODULE_TABLE . " m LEFT JOIN " . MODULE_COURSE_TABLE . " mc ON m.module_id = mc.module_id WHERE mc.course_id=" . $cid . " ORDER BY m.module_number";
        } else {
            $sql = "SELECT * FROM " . MODULE_COURSE_TABLE . " WHERE course_id=" . $cid . " ORDER BY module_id";
        }
        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }

    public static function get_modules($cid, $join = true)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . MODULE_TABLE . " WHERE course_id=" . $cid . " ORDER BY " . MODULES_TBL_MODULE_NUMBER;
        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));
        return $result;
    }

    public static function get_course_module_by_id($module_id)
    {

        global $wpdb;
        $sql = "SELECT * FROM " . MODULE_COURSE_TABLE . " WHERE " . MODULES_TBL_MODULE_ID . "=" . $module_id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    /**
     * Get Course information given the module Id
     *
     * @param $module_id
     *
     * @return mixed
     */
    public static function get_course_info_by_module_id($module_id)
    {
        global $wpdb;
        $sql = "select courses.* from " . COURSES_TABLE . " courses LEFT JOIN " . MODULES_TABLE . " modules ON courses.course_id=modules.course_id WHERE modules.module_id = " . $module_id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }


    // ORDERS and Products
    public static function get_product_modules($pinfo, $byColumn = NO_COLUMN)
    {
        global $wpdb;

        if ($byColumn != NO_COLUMN)
            $whereClauseStr = " WHERE " . $byColumn . "=" . "\"" . $pinfo . "\"";
        else
            $whereClauseStr = "";

        $sql = "SELECT * FROM " . PRODUCTS_TABLE . $whereClauseStr;
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }

    /**
     * Gramts user access to course
     *
     * @param $user_id
     * @param $who_granted
     * @param $course_id
     * @param null $module_id
     *
     * @return int
     */
    public static function grant_access_to_course($user_id, $who_granted, $course_id, $module_id = NULL)
    {
        //Check to see if user granted access, if so just return.
        if(self::get_user_course_by_course($user_id,$course_id)!=NULL){
            return 1;
        }

        $data[USER_COURSES_TBL_COURSE_ID] = $course_id;
        $data[USER_COURSES_TBL_USER_ID] = $user_id;
        $data[USER_COURSES_TBL_GRANTOR] = $who_granted;
        $data[USER_COURSES_TBL_STATUS] = 1;

        //Add Entry to Course
        $rc = self::add_user_course_entry($data);
        if ($rc == false){
            return $rc;
        }

        $totalRowsInserted=1;

        $trans_id = $rc; //Trans Id will be returned upon success
        $modules = LMS_DBFunctions::get_course_modules($course_id, true);
        //LMS_Log::print_r($modules);
        foreach ($modules as $m) {
            $mdata[USER_MODULES_TBL_TRANS_ID] = $trans_id;
            $mdata[USER_MODULES_TBL_MODULE_ID] = $m->module_id;
            $mdata[USER_MODULES_TBL_GRANTOR] = $who_granted;
            if($module_id==NULL){
                $mdata[USER_MODULES_TBL_STATUS] = USER_COURSES_STATUS_ACTIVE;
            }elseif($module_id == $m->module_id){
                $mdata[USER_MODULES_TBL_STATUS] = USER_COURSES_STATUS_ACTIVE;
            }else{
                $mdata[USER_MODULES_TBL_STATUS] = USER_COURSES_STATUS_INACTIVE;
            }

            self::add_user_module_entry($mdata);
            $totalRowsInserted++;
        }

        return $totalRowsInserted;
    }


        public static function grant_access_to_module($user_id, $course_id, $who_granted, $module_id)
    {
        //Check to see if user granted access, if so just return.
        $usercourse = self::get_user_course_by_course($user_id, $course_id);
        if ($usercourse == NULL) {
            return false;
        }

        //Where Transid & Module Id
        $new_date = date("Y-m-d H:i:s");
        global $wpdb;
        $rc = $wpdb->update(
                        USER_MODULES_TABLE, array(
                        USER_MODULES_TBL_GRANTOR => $who_granted,
                        USER_MODULES_TBL_STATUS => USER_COURSES_STATUS_ACTIVE,
                        USER_COURSES_TBL_START_DATE => $new_date),
                        array(
                        USER_MODULES_TBL_TRANS_ID =>$usercourse[USER_COURSES_TBL_TRANS_ID],
                        USER_MODULES_TBL_MODULE_ID =>$module_id)
                            );
        return $rc;
    }


    // ** COURSE & MODULE ACCESS
    public static function add_user_course_entry($data)
    {
        global $wpdb;
        $rc = $wpdb->insert(USER_COURSES_TABLE, array(
                            USER_COURSES_TBL_USER_ID => $data[USER_COURSES_TBL_USER_ID],
                            USER_COURSES_TBL_COURSE_ID => $data[USER_COURSES_TBL_COURSE_ID],
                            //USER_COURSES_TBL_STATUS => $data[USER_COURSES_TBL_STATUS],
                            USER_COURSES_TBL_GRANTOR => $data[USER_COURSES_TBL_GRANTOR]));


        LMS_Log::log_db("INSERT", __FUNCTION__ . "\tReturn Value:" . intval($rc));

        if ($rc != false)
            return $wpdb->insert_id;

        return $rc;
    }

    // ** COURSE & MODULE ACCESS
    public static function add_user_module_entry($data)
    {
        global $wpdb;
        $rc = $wpdb->insert(USER_MODULES_TABLE, array(
            USER_MODULES_TBL_TRANS_ID => $data[USER_MODULES_TBL_TRANS_ID],
            USER_MODULES_TBL_MODULE_ID => $data[USER_MODULES_TBL_MODULE_ID],
            USER_MODULES_TBL_STATUS => $data[USER_MODULES_TBL_STATUS],
            USER_MODULES_TBL_GRANTOR => $data[USER_MODULES_TBL_GRANTOR]));

        LMS_Log::log_db("INSERT", __FUNCTION__ . "\tReturn Value:" . $rc);
        return $rc;
    }

    public static function get_user_course_by_course($user_id, $course_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . USER_COURSES_TABLE . " WHERE " . USER_COURSES_TBL_USER_ID . "=" . $user_id . " AND " . USER_COURSES_TBL_COURSE_ID . "=" . $course_id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    public static function get_user_courses_by_module($user_id, $module_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . USER_COURSES_TABLE . " WHERE " . USER_COURSES_TBL_USER_ID . "=" . $user_id . " AND " . USER_COURSES_TBL . "=" . $module_id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }


    public static function get_user_courses($sid)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . USER_COURSES_TABLE . " WHERE " . USER_COURSES_TBL_USER_ID . "=" . $sid;
        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }

    /**
     * Delete from User Course Table
     *
     * @param $trans_id
     * @return mixed
     */
    public static function delete_user_course($trans_id){
        global $wpdb;
        $sql = "DELETE FROM "  . USER_COURSES_TABLE . " WHERE " . USER_COURSES_TBL_TRANS_ID . "=" . $trans_id;
        return  $wpdb->query($sql);
    }

    /**
     * Delete from User Module Table
     *
     * @param $trans_id
     * @return mixed
     */
    public static function delete_user_modules($trans_id){
        global $wpdb;
        $sql = "DELETE FROM "  . USER_MODULES_TABLE . " WHERE " . USER_MODULES_TBL_TRANS_ID . "=" . $trans_id;
        return  $wpdb->query($sql);
    }


    public static function get_user_modules($id, $by = BY_TRANS_ID)
    {
        global $wpdb;
        if ($by == BY_USER_ID) {
            $sql = "SELECT usermodule.* FROM " . USER_COURSES_TABLE . " usercourses LEFT JOIN " . USER_MODULES_TABLE . " usermodule ON usercourses.trans_id = usermodule.trans_id WHERE usercourses.user_id=" . $id;
        } else {
            $sql = "SELECT * FROM " . USER_MODULES_TABLE . " WHERE " . USER_MODULES_TBL_TRANS_ID . "=" . $id;
        }
        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        if (empty($result)) {
            return $result;
        }

        $usermodules = array();
        foreach ($result as $value) {
            $usermodules[$value->module_id] = $value;
        }
        return $usermodules;
    }

    public static function get_user_modules_by_user($user_id,$module_id="")
    {
        global $wpdb;
        if(!empty($module_id)){
            $sql = "SELECT usermodule.* FROM " . USER_COURSES_TABLE . " usercourses LEFT JOIN " . USER_MODULES_TABLE . " usermodule ON usercourses.trans_id = usermodule.trans_id WHERE usercourses.user_id=" . $user_id . " AND usermodule.module_id=" . $module_id;
        }else {
            $sql = "SELECT usermodule.* FROM " . USER_COURSES_TABLE . " usercourses LEFT JOIN " . USER_MODULES_TABLE . " usermodule ON usercourses.trans_id = usermodule.trans_id WHERE usercourses.user_id=" . $user_id;
        }
        $result = $wpdb->get_results($sql);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }


    public static function get_quiz_questions($quiz_id)
    {
        global $wpdb;
        $sql="SELECT * from ".QUIZ_QUEST_TABLE." where ".QUIZ_QUEST_TABLE_QUIZ_ID. "=".$quiz_id." order by ".QUIZ_QUEST_TABLE_QORDER;
        $result = $wpdb->get_results($sql,ARRAY_A);
        LMS_Log::log_db("SELECT", __FUNCTION__);
        return $result;
    }


    /**
     * Retrieves Full list of Lessons ordered by Course and Module
     *
     * @return mixed
     */
    public static function retrieve_full_lesson_list($courseID=""){
        global $wpdb;
        $wpdb->show_errors();
        // Get a list of all units for this course in absolute order
        if(!empty($courseID)){
            $SQL = $wpdb->prepare("
			SELECT *
			FROM wp_lms_lessons wl
			  LEFT JOIN wp_lms_module wm ON wl.module_id=wm.module_id
            WHERE wl.course_id=%d
            ORDER BY wm.module_number, wl.unit_number		",
                $courseID
            );
        }else {
            $SQL = "
                SELECT *
                FROM wp_lms_lessons wl
                  LEFT JOIN wp_lms_module wm ON wl.module_id=wm.module_id
                ORDER BY wl.course_id, wm.module_number, wl.unit_number	";
        }
        return $wpdb->get_results($SQL,ARRAY_A);
    }

    /**
     * Retrieves Users Progress
     *
     * @param $user_id
     * @param $course_id
     * @return mixed
     */
    public static function retrieve_user_progress($user_id, $course_id){
        global $wpdb;
        $wpdb->show_errors();
    // Get a list of all units for this course in absolute order
        $SQL = $wpdb->prepare("
        SELECT *
        FROM wp_lms_user_progress up
          LEFT JOIN wp_lms_lessons wl ON up.unit_id=wl.id
        WHERE wl.course_id=%d AND up.user_id=%d",
            $course_id, $user_id
        );
        return $wpdb->get_results($SQL,ARRAY_A);
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function add_user_progress_entry($data)
    {
        global $wpdb;
        $rc = $wpdb->insert(USER_PROGRESS_TABLE, array(
            USER_PROGRESS_TABLE_USER_ID => $data[USER_PROGRESS_TABLE_USER_ID],
            USER_PROGRESS_TABLE_UNIT_ID => $data[USER_PROGRESS_TABLE_UNIT_ID],
            USER_PROGRESS_TABLE_COMPLETE_DATE => $data[USER_PROGRESS_TABLE_COMPLETE_DATE],
            USER_PROGRESS_TABLE_STATUS => $data[USER_PROGRESS_TABLE_STATUS]));

        return $rc;
    }

    public static function retrieve_product_access_settings($id, $field='product_id'){
        global $wpdb;
        $wpdb->show_errors();
        // Get a list of all units for this course in absolute order
        $SQL = "SELECT * FROM  wp_lms_product_course_access WHERE " . $field . "=" . $id;
        //LMS_Log::print_r($SQL);
        return $wpdb->get_results($SQL,ARRAY_A);
    }

}



?>