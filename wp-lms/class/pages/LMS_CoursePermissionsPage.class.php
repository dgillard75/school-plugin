<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 9/9/15
 * Time: 3:10 PM
 */

//require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_Page.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/LMS_StudentCourses.class.php");
//require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_CoursePermissionsHTMLForm.class.php");
//require_once(WPLMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");


class LMS_CoursePermissionsPage extends LMS_Page
{
    protected $form_data;

    public function LMS_CoursePermissionsPage()
    {
        $this->form_data = new LMS_CoursePermissionsHTMLForm();
        parent::__construct();
        $this->resultArr['status'] = false;
        $this->resultArr['errors'] = 0;
        $this->resultArr['etype'] = "";
    }

    /**
     * @return LMS_CoursePermissionsHTMLForm
     */
    public function getFormInformation(){
        return $this->form_data;
    }

    /**
     * Returns Course List
     * @return mixed
     */
    function get_course_list(){
        return $this->form_data->getField(COURSE_PERMISSIONS_FORM_COURSE_LIST);
    }

    /**
     * Returns Student Information
     *
     * @return mixed
     */
    function get_student_info(){
        return $this->form_data->getField("student_info");
    }

    /**
     * Get the Data From The Request
     * @return mixed
     */
    public function getRequestData(){
        $getdata[COURSE_PERMISSIONS_FORM_USER_ID] = array_key_exists(COURSE_PERMISSIONS_FORM_USER_ID,$_GET) ? $_GET[COURSE_PERMISSIONS_FORM_USER_ID] : "";
        $getdata[LMS_HTML_FORM_ACTION] = array_key_exists(LMS_HTML_FORM_ACTION,$_GET) ? $_GET[LMS_HTML_FORM_ACTION] : "";
        return $getdata;
    }

    /**
     * Get the data From the POST
     * @return mixed
     */
    function getPostData()
    {
        $postdata[COURSE_PERMISSIONS_FORM_COURSE_LIST] = $_POST[COURSE_PERMISSIONS_FORM_COURSE_LIST];
        $postdata[COURSE_PERMISSIONS_FORM_USER_ID] = strip_tags(stripslashes($_POST[COURSE_PERMISSIONS_FORM_USER_ID]));
        return $postdata;
    }

    /**
     * Get All The Courses for the School and mark which of those are active for a given student.
     *
     * @param $user_id
     * @return array
     */
    function get_all_course_information($user_id){
        //Get all the Student Courses
        $student = new LMS_StudentCourses($user_id);

        $this->form_data->setField("student_info", $student->getUserInformation() );
        $student_courses = $student->getCourseList();

        $ac = LMS_DBFunctions::get_active_courses(ARRAY_A);
        $active_courses = array();

        //Store array index as course_id for easy lookup
        foreach($ac as $c){
            $active_courses[$c[COURSES_TBL_COURSE_ID]] = $c;
        }

        //Go through Student Courses
        foreach($student_courses as $c){
            $cinfo = $c->getCourse();
            $active_courses[$cinfo["course_id"]]["checked"] = true;
        }

        return $active_courses;
    }

    /**
     * Process Form
     *
     * @return bool
     */
    function processForm()
    {
        $postdata = $this->getPostData();
        $courses_to_give_access = $postdata[COURSE_PERMISSIONS_FORM_COURSE_LIST];
        LMS_Log::print_r($courses_to_give_access,__FUNCTION__);
        $input_user_id = $postdata[COURSE_PERMISSIONS_FORM_USER_ID];

        //Validate user id is present


        //Get Current Active Courses
        $master_course_list = $this->get_all_course_information($input_user_id);
        //LMS_Log::print_r($master_course_list,__FUNCTION__);

        $current_user = wp_get_current_user();
        $admin_user =  $current_user->user_login;
        //Figure out what to update and delete
        foreach($master_course_list as $course) {
            /**
             * In Master List, but not in Courses to Give Access to. DELETE COURSE
             */
            if (array_key_exists("checked", $course)) {
                if (!in_array($course["course_id"], $courses_to_give_access)) {
                    //LMS_Log::print_r("Remove Access for  " . $course["course_id"], __FUNCTION__);
                    LMS_School::disenroll($input_user_id,$course["course_id"]);
                }
            } else {
                /**
                 * Not In Master List, but is in Courses to Give Access to. ADD COURSE
                 */
                if (in_array($course["course_id"], $courses_to_give_access)) {
                    //LMS_Log::print_r("Add Access for  " . $course["course_id"], __FUNCTION__);
                    LMS_School::enroll($input_user_id,$course["course_id"],$admin_user);
                }
            }
        }

        //Now Updates to database are done, Re-Query and set results for Form
        $updated_course_list = $this->get_all_course_information($input_user_id);
        $this->form_data->setField(COURSE_PERMISSIONS_FORM_COURSE_LIST, $updated_course_list );
        return $this->resultArr;
    }




    function processGetRequest()
    {
        $this->form_data->load($this->getRequestData());
        //Set Course Ids Array
        $user_id = $this->form_data->getField( COURSE_PERMISSIONS_FORM_USER_ID );

        $errors = $this->form_data->validate();
        if($errors!=0){
            $this->setResult("form",$errors);
        }else {
            //Get all the Student Courses
            $active_courses = $this->get_all_course_information($user_id);
            $this->form_data->setField(COURSE_PERMISSIONS_FORM_COURSE_LIST, $active_courses);

        }
        return $this->resultArr;
    }



}


?>