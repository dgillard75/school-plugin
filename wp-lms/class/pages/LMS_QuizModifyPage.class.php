<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/6/15
 * Time: 9:59 AM
 */


require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_QuizModifyHTMLForm.class.php");

class LMS_QuizModifyPage extends LMS_Page{

    protected $form_data;
    protected $form_courses;
    protected $form_modules;

    public function LMS_QuizModifyPage(){
        $this->form_data = new LMS_QuizModifyHTMLForm();
        parent::__construct();
        $this->resultArr['status'] = false;
        $this->resultArr['errors'] = 0;
        $this->resultArr['etype'] = "";
        $this->quiz_title = "";
        $this->form_courses = array();
        $this->form_modules = array();
        $this->load_courses();
    }

    /**
     * Returns Form HTML form information
     *
     * @return LMS_QuizModifyHTMLForm
     */
    public function getFormInformation(){
        return $this->form_data;
    }

    /**
     * Load all course information to be displayed on form
     */
    protected function load_courses(){
        $this->form_courses = LMS_DBFunctions::get_all_courses(ARRAY_A);
    }

    /**
     * Load all modules to be displayed on form
     */
    protected function load_modules() {
        $this->form_modules = LMS_DBFunctions::get_modules($this->form_data->getField(QUIZMODFORM_CID));
    }

    /**
     * Get Courses
     *
     * @return array of courses
     */
    public function get_courses(){
        return $this->form_courses;
    }

    /**
     * Get Modules
     *
     * @return array of modules
     */
    public function get_modules(){
        return $this->form_modules;
    }

    /**
     * @return array of questions
     */
    public function get_questions(){
        //$questions = array();
        $questions = LMS_DBFunctions::get_quiz_questions($this->form_data->getField( QUIZMODFORM_QUIZ_ID ));
        //LMS_Log::print_r($questions);
        return $questions;
    }

    /**
     * Prefill form data with quiz information
     *
     */
    public function prefill_form_data() {
        $quiz_id     = $this->form_data->getField( QUIZMODFORM_QUIZ_ID );
        $quizdb_data = LMS_DBFunctions::select_query( QUIZ_TABLE, "WHERE " . QUIZ_TABLE_ID . "=" . $quiz_id, true );

        $this->form_data->setField(QUIZMODFORM_TITLE,$quizdb_data[QUIZ_TABLE_TITLE]);
        $this->form_data->setField(QUIZMODFORM_MID,$quizdb_data[QUIZ_TABLE_MID]);

        //Get Course information given module
        $course_info = LMS_DBFunctions::get_course_info_by_module_id($quizdb_data[QUIZ_TABLE_MID]);

        $this->form_data->setField(QUIZMODFORM_CID,$course_info[COURSES_TBL_COURSE_ID]);

        $this->load_modules();

    }

    /**
     * Responsible for processing all Get Request
     *
     * @return array Result for any errors
     */
    public function processGetRequest(){
        $this->form_data->load($this->getRequestData());

        if(isset($_GET[LMS_HTML_FORM_ACTION]) && $_GET[LMS_HTML_FORM_ACTION] == 'edit') {
            $this->form_data->setField(LMS_HTML_FORM_ACTION, $_GET[LMS_HTML_FORM_ACTION]);
            $errors = $this->form_data->validate();
            if($errors!=0){
                $this->setResult("form",$errors);
                return $this->resultArr;
            }
            //Prefill Form Data - Edit Mode
            $this->prefill_form_data();
       }else if(isset($_GET[QUIZMODFORM_CID])){
            $this->form_data->setField(QUIZMODFORM_CID, $_GET[QUIZMODFORM_CID]);
            $this->form_data->setField(LMS_HTML_FORM_ACTION, "add");
            //if course_id!=0, admin choose a course, load modules for that course
            if($_GET[QUIZMODFORM_CID] != "0") {
                $this->load_modules();
            }
        }else{
            $this->form_data->setField(LMS_HTML_FORM_ACTION, "add");
        }
        return $this->resultArr;
    }


    /**
     * Responsible for processing form on a POST request
     *
     * @return array of errors if applicable.
     */
    public function processForm(){
        $this->form_data->setField(LMS_HTML_FORM_ACTION, $_POST[LMS_HTML_FORM_ACTION]);
        $this->form_data->load($this->getPostData());
        $errors = $this->form_data->validate();

        if($errors!=0){
            $this->setResult("form",$errors);
            return $this->resultArr;
        }

        $dbData = $this->prepFormDataForDatabase();
        // Do Database Work
        if ($this->form_data->getField(LMS_HTML_FORM_ACTION) == 'edit') {
            $dbresult = LMS_DBFunctions::update_quiz($dbData);
        } else {
            $dbresult = LMS_DBFunctions::add_quiz($dbData);
        }

        $this->setResult("db",$dbresult);
        return $this->resultArr;
    }

    /**
     * Returns data from post request
     *
     * @return array
     */
    public function getPostData(){
        $postdata = array();
        $postdata[QUIZMODFORM_QUIZ_ID] = array_key_exists(QUIZMODFORM_QUIZ_ID,$_POST) ? $_POST[QUIZMODFORM_QUIZ_ID] : "";
        $postdata[QUIZMODFORM_CID] = $_POST[QUIZMODFORM_CID];
        $postdata[QUIZMODFORM_MID] = $_POST[QUIZMODFORM_MID];
        $postdata[QUIZMODFORM_TITLE] = $_POST[QUIZMODFORM_TITLE];
        return $postdata;
    }

    /**
     * Returns data from get Request
     *
     * @return array
     */
    public function getRequestData(){
        $getdata = array();
        $getdata[QUIZMODFORM_QUIZ_ID] = array_key_exists(QUIZMODFORM_QUIZ_ID,$_GET) ? $_GET[QUIZMODFORM_QUIZ_ID] : "";
        $getdata[QUIZMODFORM_CID] = array_key_exists(QUIZMODFORM_CID,$_GET) ? $_GET[QUIZMODFORM_CID] : "";
        $getdata[QUIZMODFORM_MID] = array_key_exists(QUIZMODFORM_MID,$_GET) ? $_GET[QUIZMODFORM_MID] : "";
        $getdata[LMS_HTML_FORM_ACTION] = array_key_exists(LMS_HTML_FORM_ACTION,$_GET) ? $_GET[LMS_HTML_FORM_ACTION] : "";
        return $getdata;
    }

    /**
     * Prep db array for database updates
     *
     * @return array
     */
    protected function prepFormDataForDatabase(){
        $dbArr = array();
        $dbArr[QUIZ_TABLE_MID] = $this->form_data->getField(QUIZMODFORM_MID);
        $dbArr[QUIZ_TABLE_TITLE] = $this->form_data->getField(QUIZMODFORM_TITLE);
        $dbArr[QUIZ_TABLE_ID] = $this->form_data->getField(QUIZMODFORM_QUIZ_ID);

        return $dbArr;
    }
}
?>