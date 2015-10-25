<?php

require_once(LMS_INCLUDE_DIR."LMS_Defines.php");

class LMS_SchoolDB
{

    //public function LMS_SchoolDB(){}


    public static function logDB($queryType, $message)
    {
        global $wpdb;
        global $user_login;
        get_currentuserinfo();

        $dateStr = "[" . date("d.M.Y H:m:s") . "]";

        if ($queryType == "INSERT") {
            $logmessage = $dateStr . "\t" . $user_login . "\tMYSQL Query:" . $wpdb->last_query . "\t" . $message . "\n";
        } else if ($queryType == "SELECT") {
            $logmessage = $dateStr . "\t" . $user_login . "\tMYSQL Query:" . $wpdb->last_query . "\t" . $message . "\n";
        } else if ($queryType == "DELETE") {
            $logmessage = $dateStr . "\t" . $user_login . "\tMYSQL Query:" . $wpdb->last_query . "\t" . $message . "\n";
        } else if ($queryType == "UPDATE") {
            $logmessage = $dateStr . "\t" . $user_login . "\tMYSQL Query:" . $wpdb->last_query . "\t" . $message . "\n";
        } else {
            $logmessage = "";
        }

        error_log($logmessage, 3, LMS_PLUGIN_PATH . "logs/db.log");
    }

    public static function debug($str, $result = 0)
    {
        $dateStr = "[" . date("d.M.Y H:m:s") . "]";
        $msg = $dateStr . "\t[debug]\t" . print_r($str, true) . "\n";
        error_log($msg, 3, LMS_PLUGIN_PATH . "logs/debug.log");
    }

    /**
     * register_student_account - inserts student record into database
     *
     * @param $account_info - Array of key Value Pairs
     *
     * @return int
     */
    public function register_student_account($account_info)
    {
        global $wpdb;
        /* print_r($term);
        echo $term->term_id;
        exit; */
        $result = $wpdb->insert(USERS_TABLE, array(
                USERS_FN => $account_info[USERS_FN],
                USERS_LN => $account_info[USERS_LN],
                USERS_PASSWORD => $account_info[USERS_PASSWORD],
                USERS_LOGIN => $account_info[USERS_LOGIN],
                USERS_EMAIL => $account_info[USERS_EMAIL],
                USERS_SADDRESS => $account_info[USERS_SADDRESS],
                USERS_SCITY => $account_info[USERS_SCITY],
                USERS_SSTATE => $account_info[USERS_SSTATE],
                USERS_SCOUNTRY => $account_info[USERS_SCOUNTRY],
                USERS_SPHONE => $account_info[USERS_SPHONE],
                USERS_SZIP => $account_info[USERS_SZIP],
                USERS_BADDRESS => $account_info[USERS_BADDRESS],
                USERS_BCITY => $account_info[USERS_BCITY],
                USERS_BSTATE => $account_info[USERS_BSTATE],
                USERS_BCOUNTRY => $account_info[USERS_BCOUNTRY],
                USERS_BPHONE => $account_info[USERS_BPHONE],
                USERS_BZIP => $account_info[USERS_BZIP]
            )
        );

        if (!$result) {
            return -1;
        } else {
            return $wpdb->insert_id;
        }
    }


    public static function register_student($wp_user_id)
    {
        global $wpdb;
        /* print_r($term);
        echo $term->term_id;
        exit; */

        //Verify User is a Wordpress Subscriber
        $userdata = get_userdata($wp_user_id);
        if ($userdata == false) {
            return -2;
        }
        //Insert user into register table
        $result = $wpdb->insert(REGISTERED_USERS_TABLE, array(
                REGISTERED_USERS_WP_ID => $wp_user_id,
                REGISTERED_USERS_LOGIN => $userdata->user_login
            )
        );

        if (!$result) {
            return -1;
        } else {
            return $wpdb->insert_id;
        }
    }

    public static function get_student_wpuser_data($user_id)
    {
        //$wordpressUserObj = get_userdat$
        // Get user data
        $user_account = array();
        $userdata = get_userdata($user_id);
        if ($userdata != false) {
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

    public static function get_student($user_id)
    {
        global $wpdb;

        $sql = "SELECT * FROM " . DB_LMS_REG_USERS_TABLE . " where " . DB_LMS_REG_USERS_WP_ID . "='" . $user_id . "'";
        //LMS_SchoolDB::debug($sql);
        $user_account = $wpdb->get_row($sql, ARRAY_A);
        if ($user_account != null) {
            $wpuserdata = self::get_student_wpuser_data($user_id);
            //LMS_SchoolDB::debug($wpuserdata);
            $user_account = array_merge($user_account, $wpuserdata);
        }

        return $user_account;
    }

    public static function get_all_students($search)
    {
        global $wpdb;
        $allStudents = array();
        $sql = "SELECT * FROM " . DB_LMS_REG_USERS_TABLE;
        if (!empty($search)) {
            $sql .= "  .WHERE `first_name` like '%" $search . "%' or `last_name` like '%" . $search . "%' or `user_email` like '%" . $search . "%' or `user_login` like '%" . $search . "%'";
        }
        $result = $wpdb->get_results($sql, ARRAY_A);

        if ($result != null) {
            foreach ($result as $value) {
                $wpuserdata = self::get_student_wpuser_data($value["wp_user_id"]);
                //LMS_SchoolDB::debug($wpuserdata);
                $allStudents[] = array_merge($value, $wpuserdata);
            }
        }

        return $allStudents;
    }

    public static function retrieve_all_students($search)
    {
        global $wpdb;
        $sql = "SELECT * FROM tbl_users";
        if (!empty($search)) {
            $sql .= " WHERE `first_name` like '%" . $search . "%' or `last_name` like '%" . $search . "%' or `user_email` like '%" . $search . "%' or `user_login` like '%" . $search . "%'";
        }
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public function delete_student_account($student_id)
    {
        global $wpdb;
        $sql = $wpdb->prepare("DELETE FROM " . USERS_TABLE . " where id = %s", $student_id);

        return $wpdb->query($sql);
    }

    /**
     * update_student_account - Update Student Record
     *
     * @param $student_id
     * @param $accountInfo
     *
     * @return mixed
     */
    public function update_student_account($student_id, $account_info)
    {
        global $wpdb;
        $result = $wpdb->update(USERS_TABLE, array(
                USERS_FN => $account_info[USERS_FN],
                USERS_LN => $account_info[USERS_LN],
                USERS_PASSWORD => $account_info[USERS_PASSWORD],
                USERS_SADDRESS => $account_info[USERS_SADDRESS],
                USERS_SCITY => $account_info[USERS_SCITY],
                USERS_SSTATE => $account_info[USERS_SSTATE],
                USERS_SCOUNTRY => $account_info[USERS_SCOUNTRY],
                USERS_SPHONE => $account_info[USERS_SPHONE],
                USERS_SZIP => $account_info[USERS_SZIP],
                USERS_BADDRESS => $account_info[USERS_BADDRESS],
                USERS_BCITY => $account_info[USERS_BCITY],
                USERS_BSTATE => $account_info[USERS_BSTATE],
                USERS_BCOUNTRY => $account_info[USERS_BCOUNTRY],
                USERS_BPHONE => $account_info[USERS_BPHONE],
                USERS_BZIP => $account_info[USERS_BZIP]
            ),
            array(USERS_ID => $student_id)
        );

        return $result;
    }

    public static function get_student_account($student_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . DB_LMS_USERS_COURSES_TABLE . " where id='" . $student_id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }

    public static function get_all_courses()
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE;
        //self::debug($sql);
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public static function get_all_active_courses()
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE . " WHERE status=1";
        //self::debug($sql)
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }


    public static function get_students_courses($sid)
    {
        global $wpdb;
        $sql = "select * FROM " . DB_LMS_USERS_COURSES_TABLE . " where user_id='" . $sid . "'";
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public static function get_student_course($sid, $cid)
    {
        global $wpdb;
        $sql = "select * FROM " . DB_LMS_USERS_COURSES_TABLE . " where user_id='" . $sid . "'" . "and course_id='" . $cid . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }

    public static function get_all_courses_avail_to_student($student_id)
    {
        global $wpdb;
        $studentCourses = self::get_students_courses($student_id);
        $cid = '';
        $cnt = sizeOf($studentCourses);
        if ($cnt > 0) {
            //Students have purchased courses, exclude those from list
            $totalCommas = $cnt - 1;
            foreach ($studentCourses as $course) {
                $cid .= '"' . $course->course_id . '"';
                if ($totalCommas > 0) {
                    $cid .= ",";
                    $totalCommas--;
                }
            }
            $sql = "SELECT * FROM " . COURSES_TABLE . " where id NOT IN (" . $cid . ")";
            $result = $wpdb->get_results($sql);
        } else {
            //Students have purchased no courses, just getAllCourses
            $result = self::get_all_courses();
        }
        self::logDB("SELECT", __FUNCTION__);

        return $result;
    }


    public function get_count_of_modules_for_course($course_id)
    {
        global $wpdb;
        $sql = "SELECT count(*) FROM " . MODULE_COURSE_TABLE . "where course_id='" . $course_id . "'";
        $result = $wpdb->get_results($sql);

        return $result;
    }


    public static function retrieve_course($course_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_TABLE . " WHERE id='" . $course_id . "' order by tbl_order";
        $result = $wpdb->get_row($sql, ARRAY_A);
        self::logDB("SELECT", __FUNCTION__);

        return $result;
    }


    public static function add_course($course_data)
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

    public static function update_course($course_data)
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

    }

    public static function delete_course($student_id)
    {


    }


    public static function update_order_of_courses($order_number, $course_id)
    {
        global $wpdb;
        $sql = "UPDATE " . COURSES_TABLE . " SET `tbl_order`=" . $order_number . " WHERE id=" . $course_id;
        $result = $wpdb->get_results($sql);

        return $result;
    }


    public static function get_package_sets($package_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . PACKAGE_SETS_TABLE . " WHERE package_id='" . $package_id . "' ORDER BY set_order";
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public static function get_course_sets($course_id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_SETS_TABLE . " WHERE course_id='" . $course_id . "' ORDER BY set_order";
        //LMS_SchoolDB::debug($sql);
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public static function get_course_sets_by_setid($id)
    {
        global $wpdb;
        $sql = "SELECT * FROM " . COURSES_SETS_TABLE . " WHERE id='" . $id . "'";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /************************  USER_COURSE_SET FUNCTIONS ************************************/

    public static function insert_user_course_set($data)
    {
        global $wpdb;
        $result = $wpdb->insert(USER_COURSES_SET_TABLE, array(
                USER_COURSES_SET_TBL_USER_ID => $data[USER_COURSES_SET_TBL_USER_ID],
                USER_COURSES_SET_TBL_COURSE_ID => $data[USER_COURSES_SET_TBL_COURSE_ID],
                USER_COURSES_SET_TBL_SET_ID => $data[USER_COURSES_SET_TBL_SET_ID],
                USER_COURSES_SET_TBL_START_DATE => $data[USER_COURSES_SET_TBL_START_DATE],
                USER_COURSES_SET_TBL_END_DATE => $data[USER_COURSES_SET_TBL_END_DATE],
                USER_COURSES_SET_TBL_PAID_AMOUNT => $data[USER_COURSES_SET_TBL_PAID_AMOUNT],
                USER_COURSES_SET_TBL_AMOUNT => $data[USER_COURSES_SET_TBL_AMOUNT],
                USER_COURSES_SET_TBL_PAYMENT_METHOD => $data[USER_COURSES_SET_TBL_PAYMENT_METHOD],
                USER_COURSES_SET_TBL_STATUS => $data[USER_COURSES_SET_TBL_STATUS]
            )
        );
        self::logDB("INSERT", __FUNCTION__);

        return $result;
    }

    public static function get_user_courses_set($cid, $uid)
    {
        global $wpdb;
        $sql = "select * from " . USER_COURSES_SET_TABLE . " where course_id='" . $cid . "' and user_id='" . $uid . "' and paid_amount !='0'";
        LMS_SchoolDB::debug($sql);
        $result = $wpdb->get_results($sql);

        return $result;
    }

    /********************** USER_COURSES FUNCTIONS *************************************/
    public static function insert_user_course($data)
    {
        global $wpdb;
        $wpdb->show_errors = true;
        $wpdb->suppress_errors = false;
        $result = $wpdb->insert(USER_COURSES_TABLE, array(
                USER_COURSES_TBL_USER_ID => $data[USER_COURSES_TBL_USER_ID],
                USER_COURSES_TBL_COURSE_ID => $data[USER_COURSES_TBL_COURSE_ID],
                USER_COURSES_TBL_END_DATE => $data[USER_COURSES_TBL_END_DATE],
                USER_COURSES_TBL_PAID_AMOUNT => $data[USER_COURSES_TBL_PAID_AMOUNT],
                USER_COURSES_TBL_PENDING_AMOUNT => $data[USER_COURSES_TBL_PENDING_AMOUNT],
                USER_COURSES_TBL_SIGNUP_TYPE => $data[USER_COURSES_TBL_SIGNUP_TYPE],
                USER_COURSES_TBL_STATUS => $data[USER_COURSES_TBL_STATUS],
                USER_COURSES_TBL_PAYMENT_METHOD => $data[USER_COURSES_TBL_PAYMENT_METHOD]
            )
        );
        /**
         * if ($wpdb->last_error) {
         * die('error=' . var_dump($wpdb->last_query) . ',' . var_dump($wpdb->error));
         * }**/

        self::logDB("INSERT", __FUNCTION__);

        return $result;
    }

    public static function delete_user_course($student_id, $course_id, $ucid = null)
    {
        global $wpdb;

        if ($ucid != null) {
            $sql = $wpdb->prepare("DELETE FROM " . USER_COURSES_TABLE . " WHERE id = %d", $ucid);
        } else {
            $sql = $wpdb->prepare("DELETE FROM " . USER_COURSES_TABLE . " WHERE course_id = %d AND user_id = %d",
                $course_id, $student_id);
        }

        print "<pre>" . __FUNCTION__ . ":" . print_r($sql, true) . "</pre>";

        $results = $wpdb->query($sql);
        self::logDB("DELETE", __FUNCTION__ . "\tResult:" . print_r($results, true));

        return $results;

    }

    public static function delete_user_course_set($student_id, $course_id, $ucid = null)
    {
        global $wpdb;


        if ($ucid != null) {
            $sql = $wpdb->prepare("DELETE FROM " . USER_COURSES_SET_TABLE . " WHERE id = %d", $ucid);
        } else {
            $sql = $wpdb->prepare("DELETE FROM " . USER_COURSES_SET_TABLE . " WHERE course_id = %d AND user_id = %d",
                $course_id, $student_id);
        }

        $results = $wpdb->query($sql);
        self::logDB("DELETE", __FUNCTION__ . "\tResult:" . print_r($results, true));

        return $results;
    }


    public static function is_student_enrolled($student_id, $cid, $setId = "")
    {
        global $wpdb;

        if ($setId == "") {
            $sql = "select count(*) from " . USER_COURSES_TABLE . " where id='" . $student_id . "' and course_id='" . $cid . "'";
        } else {
            $sql = "select count(*) from " . USER_COURSES_SET_TABLE . " where id='" . $student_id . "' and course_id='" . $cid . "' and set_id='" . $setId . "'";
        }
        $count = $wpdb->get_var($sql);
        self::logDB("SELECT", __FUNCTION__ . "\tResult:" . print_r($count, true));
        if ($count > 0) {
            return true;
        }

        return false;
    }

    public function get_courses_for_student($student_id)
    {
        global $wpdb;
        $sql = "select * FROM " . USER_COURSES_TABLE . " where user_id='" . $student_id . "'";
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public static function get_course_modules($cid,$join=true)
    {
        global $wpdb;
        if($join) {
            $sql = "SELECT m.* FROM " . MODULE_TABLE . " m LEFT JOIN " . MODULE_COURSE_TABLE . " mc ON m.module_id = mc.module_id WHERE mc.course_id=" . $cid . " ORDER BY m.module_number";
        }else{
            $sql = "SELECT * FROM " . MODULE_COURSE_TABLE . " WHERE course_id=" . $cid;
        }
        $result = $wpdb->get_results($sql);

        return $result;
    }

    public static function get_courses_set_by_cid_n_sid($sid, $cid)
    {
        global $wpdb;
        $sql = "select * from " . COURSES_SETS_TABLE . " where id='" . $sid . "' and course_id='" . $cid . "'";
        //echo "<pre>".$sql."</pre>";
        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }

    public static function get_set_modules($set_id)
    {
        global $wpdb;
        $sql = "select * FROM " . SET_MODULES_TABLE . " where set_id='" . $set_id . "'";
        //echo "<pre>".$sql."</pre>";
        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }

    public static function get_module($module_id)
    {
        global $wpdb;
        $sql = "select * FROM " . MODULE_TABLE . " where module_id='" . $module_id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);

        //echo "<pre>".$sql."</pre>";
        return $result;
    }

    public static function get_course_by_module_id($module_id)
    {
        global $wpdb;
        $sql = "select * FROM " . MODULE_COURSE_TABLE . " where module_id='" . $module_id . "'";
        $result = $wpdb->get_row($sql, ARRAY_A);
        echo "<pre>" . $sql . "</pre>";

        return $result;
    }

    public static function get_module_courses()
    {
        global $wpdb;
        $sql = "select * FROM " . MODULE_COURSE_TABLE;
        $result = $wpdb->get_results($sql, ARRAY_A);
        echo "<pre>" . $sql . "</pre>";

        return $result;
    }

    public static function add_course_to_student_account($student_id, $course_id, $course_type, $set_id = null)
    {
        global $wpdb;
        if (empty($student_id) || empty($course_id) || empty($course_type)) {
            return ErrorCodeConstants::FAIL;
        }

        $courseinfo = self::retrieve_course($course_id);
        if (!isset($courseinfo['ctime'])) {
            return ErrorCodeConstants::FAIL;
        }


        //check to see if student has course already
        if (self::is_student_enrolled($student_id, $course_id)) {
            $dateStr = "[" . date("d.M.Y H:m:s") . "]";
            $logMessage = $dateStr . "\t" . __FUNCTION__ . ": Student[" . $student_id . "] already enrolled in Course[" . $course_id . "]\n";
            error_log($logMessage, 3, LMS_PLUGIN_PATH . "logs/error.log");

            return ErrorCodeConstants::ALREADY_EXIST;
        }

        $currentdate = date('Y-m-d H:i:s');
        $course_endtime = '+' . $courseinfo['ctime'] . ' ' . $courseinfo['course_time_type'];
        $newDate = strtotime($course_endtime);
        $enddate = date('Y-m-d H:i:s', $newDate);


        /**
         *
         *
         * Course Type is FULL
         *  - Insert into
         *
         */

        $uc_data[USER_COURSES_TBL_USER_ID] = $student_id;
        $uc_data[USER_COURSES_TBL_COURSE_ID] = $course_id;
        $uc_data[USER_COURSES_TBL_END_DATE] = $enddate;
        $uc_data[USER_COURSES_TBL_STATUS] = ProductStatusConstants::ACTIVE;
        $uc_data[USER_COURSES_TBL_PAYMENT_METHOD] = "'By Admin";
        if ($course_type == "full") {
            $uc_data[USER_COURSES_TBL_PAID_AMOUNT] = $courseinfo['cprice'];
            $uc_data[USER_COURSES_TBL_PENDING_AMOUNT] = "0";
            $uc_data[USER_COURSES_TBL_SIGNUP_TYPE] = "1";
            $results = self::insert_user_course($uc_data);

            return $results;
        }


        /**
         *
         * Course Type == Set
         *
         */
        if ($course_type == "set" && isset($set_id)) {
            $results = $courseSetResults = self::get_course_sets($course_id);
            //self::debug($courseSetResults);

            foreach ($courseSetResults as $cset) {
                $ucset_data[USER_COURSES_SET_TBL_USER_ID] = $student_id;
                $ucset_data[USER_COURSES_SET_TBL_COURSE_ID] = $course_id;
                $ucset_data[USER_COURSES_SET_TBL_SET_ID] = $set_id;
                $ucset_data[USER_COURSES_SET_TBL_AMOUNT] = $cset->set_price;
                //Find the right course, insert and return
                if ($cset->id == $set_id) {
                    $pending_amount = $courseinfo["cprice"] - $cset->set_price;
                    /***** Set USER COURSE SET DATA******/
                    $ucset_data[USER_COURSES_SET_TBL_END_DATE] = $enddate;
                    $ucset_data[USER_COURSES_SET_TBL_START_DATE] = $currentdate;
                    $ucset_data[USER_COURSES_SET_TBL_PAYMENT_METHOD] = "By Admin";
                    $ucset_data[USER_COURSES_SET_TBL_PAID_AMOUNT] = $cset->set_price;
                    $ucset_data[USER_COURSES_SET_TBL_STATUS] = ProductStatusConstants::ACTIVE;


                    /***** Set USER COURSE DATA******/
                    $uc_data[USER_COURSES_TBL_PAID_AMOUNT] = $cset->set_price;
                    $uc_data[USER_COURSES_TBL_PENDING_AMOUNT] = $pending_amount;
                    $uc_data[USER_COURSES_TBL_SIGNUP_TYPE] = "0";
                    self::insert_user_course($uc_data);
                } else {
                    $ucset_data[USER_COURSES_SET_TBL_END_DATE] = "";
                    $ucset_data[USER_COURSES_SET_TBL_START_DATE] = "";
                    $ucset_data[USER_COURSES_SET_TBL_PAYMENT_METHOD] = "";
                    $ucset_data[USER_COURSES_SET_TBL_PAID_AMOUNT] = "";
                    $ucset_data[USER_COURSES_SET_TBL_STATUS] = ProductStatusConstants::AVAIL;
                }

                self::insert_user_course_set($ucset_data);
                unset($ucset_data);
            }//end of foreach
        } else {
            //Course_type is invalid;
            $results = 0;
        }

        return $results;
    }

    /**
     * if($_POST['course_type']=='full') {
     * mysql_query("insert into user_courses set user_id='".$_GET['uid']."', course_id='".$_POST['course_id']."', end_date='".$enddate."', payment_method ='By Admin',paid_amount='".$course['cprice']."',pending_amount='0',signup_type='1', status='Active'");
     * } else if($_POST['course_type']=='set') {
     * $pending_amount = $course['cprice'] - $_POST['setpicked'];
     * $qry = mysql_query("insert into user_courses set user_id='".$_GET['uid']."', course_id='".$_POST['course_id']."', end_date='".$enddate."', payment_method ='By Admin',paid_amount='".$_POST['setpicked']."',pending_amount='".$pending_amount."',signup_type='0', status='Active'");
     *
     * $setresult = mysql_query("SELECT * FROM course_sets WHERE course_id='".$_POST['course_id']."'");
     * $i=1;
     *
     * while($courseset1 = mysql_fetch_array($setresult)) {
     * if($i==1) {
     * mysql_query("insert into user_courses_sets set user_id='".$_GET['uid']."', course_id='".$_POST['course_id']."', set_id='".$courseset1['id']."', start_date='".$currentdate."', end_date='".$enddate."', payment_method ='By Admin',paid_amount='".$_POST['setpicked']."',amount='".$_POST['setpicked']."'");
     * }
     * else
     * {
     * mysql_query("insert into user_courses_sets set user_id='".$_GET['uid']."', course_id='".$_POST['course_id']."', set_id='".$courseset1['id']."',amount='".$courseset1['set_price']."'");
     * }
     * $i++;
     * }
     * }
     *
     * }
     **/

    public static function delete_course_from_student_account($student_id, $course_id, $ucid = null)
    {

        $resultUC = self::delete_user_course($student_id, $course_id, $ucid);
        $resultUCSet = self::delete_user_course_set($student_id, $course_id, $ucid);

    }

    public function get_country_list()
    {
        global $wpdb;
        $sql = "SELECT * FROM country";
        $result = $wpdb->get_results($sql);

        return $result;
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
            $whereClauseStr = " WHERE "  . $byColumn . "=" . "\"". $pinfo . "\"";
        else
            $whereClauseStr = "";

        $sql = "SELECT * FROM " . PRODUCTS_TABLE . $whereClauseStr;
        $result = $wpdb->get_row($sql, ARRAY_A);
        self::logDB("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

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
        self::logDB("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }
    public static function get_all_products()
    {
        global $wpdb;
        $sql = "SELECT * FROM " . PRODUCTS_TABLE;
        $result = $wpdb->get_results($sql);

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
        self::logDB("SELECT", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }


    public static function get_courses_by_product_id($product_id)
    {
        global $wpdb;
        $sql = "select crs.* from " . COURSES_TABLE . " crs LEFT JOIN " . PRODUCTS_COURSES_TABLE . " pcrs ON crs.id = pcrs.course_id WHERE pcrs.product_id = " . $product_id . " ORDER BY crs.tbl_order";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }


    public static function get_module_product_by_module_id($id)
    {
        global $wpdb;
        $sql = "select products.* from " . PRODUCTS_TABLE . " products LEFT JOIN " . PRODUCTS_MODULE_TABLE . " pmodule ON products.product_id = pmodule.product_id WHERE pmodule.module_id = " . $id;
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result;
    }

    public static function update_module_table($module_id, $course_id)
    {
        global $wpdb;
        $sql = "UPDATE " . MODULE_TABLE . " SET `course_id`=" . $course_id . " WHERE module_id=" . $module_id;
        $result = $wpdb->get_results($sql);
        LMS_SchoolDB::debug($sql);
        self::logDB("UPDATE", __FUNCTION__ . "\tResult:" . print_r($result, true));

        return $result;
    }

    public static function migrate_courses_to_module_table()
    {
        global $wpdb;
        //get all mappings from module_course_table;
        $mcourses = self::get_module_courses();
        $step = 0;
        foreach ($mcourses as $value) {
            self::update_module_table($value['module_id'], $value['course_id']);
        }
    }

    public static function insert_product($data)
    {
        global $wpdb;
        $wpdb->show_errors = true;
        $wpdb->suppress_errors = false;
        $result = $wpdb->insert("wp_lms_products", array(
                "product_name" => $data["product_name"],
                "product_code" => $data["product_code"],
                "discount" => $data["discount"],
                "price" => $data["price"],
                "product_img" => $data["product_img"],
                "product_type" => $data["product_type"],
                "product_desc" => $data["product_desc"],
                "status" => $data["status"],
                "product_order" => $data["product_order"]
            )
        );

        if (!$result) {
            return -1;
        } else {
            return $wpdb->insert_id;
        }
    }


    public static function insert_product_course($data)
    {
        global $wpdb;
        $wpdb->show_errors = true;
        $wpdb->suppress_errors = false;
        $result = $wpdb->insert("wp_lms_product_course", array(
                "product_id" => $data["product_id"],
                "course_id" => $data["course_id"]
            )
        );

        if (!$result) {
            return -1;
        } else {
            return $wpdb->insert_id;
        }
    }


    public static function insert_product_module($data)
    {
        global $wpdb;
        $wpdb->show_errors = true;
        $wpdb->suppress_errors = false;
        $result = $wpdb->insert("wp_lms_product_module", array(
                "product_id" => $data["product_id"],
                "module_id" => $data["module_id"]
            )
        );

        if (!$result) {
            return -1;
        } else {
            return $wpdb->insert_id;
        }
    }

    public static function map_courses_to_products($courseIdList, $product_id)
    {

        $data["product_id"] = $product_id;
        foreach ($courseIdList as $id) {
            $data["course_id"] = $id;
            self::insert_product_course($data);
        }
    }

    public static function map_modules_to_products($moduleIdList, $product_id)
    {

        $data["product_id"] = $product_id;
        foreach ($moduleIdList as $id) {
            $data["module_id"] = $id;
            self::insert_product_module($data);
        }
    }

    public static function add_product($data, $idList, $type = "course")
    {

        $data['product_type'] = $type;

        //check to see if product code exist already
        $result = self::get_product($data[PRODUCTS_TABLE_PCODE], PRODUCTS_TABLE_PCODE);
        //echo "<pre>RESULT=" . print_r($result, true) . "</pre>\n";
        if ($result == NULL) {
            $product_id = self::insert_product($data);
        } else {
            $product_id = $result["product_id"];
        }

        if ($type == "course") {
            //Now Map  one or more courses to the product
            self::map_courses_to_products($idList, $product_id);
        }

        if ($type == "module") {
            self::map_modules_to_products($idList, $product_id);
        }

    }


    public static function migrate_products_from_courses()
    {
        global $wpdb;
        //get all mappings from module_course_table;
        $courses = self::get_all_active_courses();
        $step = 0;
        //echo "<pre>".print_r($courses, true)."</pre>";
        foreach ($courses as $value) {
            $data['product_name'] = $value['cname'];
            $data['product_code'] = $value['ccode'];
            $data['price'] = $value['cprice'];
            $data['discount'] = $value['discount_price'];
            $data['product_img'] = $value['course_img'];
            $data['product_order'] = $value['tbl_order'];
            $data['status'] = $value['status'];
            $data['product_desc'] = $value['short_descp'];
            $data['product_type'] = "course";
            echo "<pre>" . print_r($data, true) . "</pre>\n";
            $idList[] = $value["id"];
            self::add_product($data, $idList);
            unset($idList);
            unset($data);
            unset($value);
            //break;
            //self::update_module_table( $value['module_id'], $value['course_id'] );
        }
    }

    public static function migrate_products_from_modules()
    {
        global $wpdb;
        //get all mappings from module_course_table;
        //$courses = self::get_all_active_courses();
        //$step     = 0;
        //echo "<pre>".print_r($courses, true)."</pre>";

        //Retrieve set_id by way of course Id
        //$courseSet = selfet_course_sets($this->course["id"]);

        //get all courses
        $courses = self::get_all_active_courses();
        foreach ($courses as $row) {
            $courseSet = self::get_course_sets($row["id"]);
            foreach ($courseSet as $set) {
                $mset = LMS_SchoolDB::get_set_modules($set->id);
                $module = LMS_SchoolDB::get_module($mset['module_id']);
                //$mObj["set_id"] = $mset['set_id'];
                //$mObj["set_name"] = $set->set_name;
                $mObj[PRODUCTS_TABLE_NAME] = $module["module_name"];
                $mObj[PRODUCTS_TABLE_PRICE] = $set->set_price;
                $mObj[PRODUCTS_TABLE_PTYPE] = "module";
                $mObj[PRODUCTS_TABLE_PCODE] = "MOD" . $set->set_order. "-" . $row["ccode"];
                $mObj[PRODUCTS_TABLE_PORDER] = $set->set_order;
                $mObj[PRODUCTS_TABLE_STATUS] = 1;
                $mObj[PRODUCTS_TABLE_DESC] = "";
                $mObj[PRODUCTS_TABLE_PIMG] = "";
                $mObj[PRODUCTS_TABLE_DPRICE] = "";

                $idList[] = $mset['module_id'];
                echo "<pre> ID:" . print_r($mObj, true) . "</pre>\n";
                self::add_product($mObj, $idList,$mObj[PRODUCTS_TABLE_PTYPE] );
                unset($idList);
                unset($mObj);

                //$mObj["pageid"] = $module["pageid"];
                //$mObj["tbl_order"] = $module["tbl_order"];
                //::debug($module);
            }
        }
    }
    /**
     * foreach ( $courses as $value ) {
     * $data['product_name'] = $value['cname'];
     * $data['product_code'] = $value['ccode'];
     * $data['price'] = $value['cprice'];
     * $data['discount'] = $value['discount_price'];
     * $data['product_img'] =  $value['course_img'];
     * $data['product_order'] = $value['tbl_order'];
     * $data['status'] = $value['status'];
     * $data['product_desc'] = $value['short_descp'];
     * $data['product_type'] = "course";
     * echo "<pre>".print_r($data, true)."</pre>\n";
     * $idList[]=$value["id"];
     * self::add_product($data,$idList);
     * unset($idList);
     * //break;
     * //break;
     * //self::update_module_table( $value['module_id'], $value['course_id'] );
     * }
     * **/
}
?>