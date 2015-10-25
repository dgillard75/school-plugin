<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/6/15
 * Time: 9:59 AM
 */


//require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_Page.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_CourseHTMLForm.class.php");
//require_once(WPLMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");

class LMS_CoursePage extends LMS_Page{

    protected $form_data;
    //protected $resultArr;

    public function LMS_CoursePage(){
        $this->form_data = new LMS_CourseHTMLForm();
        parent::__construct();
        $this->resultArr['status'] = false;
        $this->resultArr['errors'] = 0;
        $this->resultArr['etype'] = "";
    }

    /**
     * @return LMS_CourseHTMLForm
     */
    public function getFormInformation(){
        return $this->form_data;
    }

    /**
     * Process Form
     * @return bool
     */
    public function processForm(){
        $this->form_data->setField(CRS_FORM_ACTION, $_POST[CRS_FORM_ACTION]);
        $this->form_data->load($this->getPostData());
        $errors = $this->form_data->validate();

        if($errors!=0){
            $this->setResult("form",$errors);
            return $this->resultArr;
        }

        $courseDataDBArr = $this->prepFormDataForDatabase();
        // Do Database Work
        if ($this->form_data->getField(CRS_FORM_ACTION) == 'edit') {
            $dbresult = LMS_DBFunctions::update_course($courseDataDBArr);
        } else {
            $dbresult = LMS_DBFunctions::add_course($courseDataDBArr);
        }

        $this->setResult("db",$dbresult);
        return $this->resultArr;
    }

    /**
     * Process Get Request
     * @return bool
     */
    public function processGetRequest(){

        if(isset($_GET[CRS_FORM_ACTION])) {
            $this->form_data->setField(CRS_FORM_ACTION, $_GET[CRS_FORM_ACTION]);
            // if action is equal to edit, display student information.
            // all other cases display empty student information to be added.
            if ($_GET[CRS_FORM_ACTION] == 'edit' && !empty($_GET[CRS_FORM_COURSE_ID])) {
                $coursedata = LMS_DBFunctions::retrieve_course($_GET[CRS_FORM_COURSE_ID]);
                $this->form_data->setField(CRS_FORM_COURSE_ID, $_GET[CRS_FORM_COURSE_ID]);
                $this->form_data->load($coursedata);
            }
        }else {
            $this->form_data->setField(CRS_FORM_ACTION, "add");
        }

        $this->setResult("get",0);
        return $this->resultArr;
    }

    /**
     * Get Post Data
     * @return mixed
     */
    public function getPostData(){
        $postdata[CRS_FORM_CNAME] = strip_tags(stripslashes($_POST[CRS_FORM_CNAME]));
        $postdata[CRS_FORM_COURSE_DESC] = trim(addslashes($_POST[CRS_FORM_COURSE_DESC]));
        $postdata[CRS_FORM_COURSE_ID] = trim(addslashes($_POST[CRS_FORM_COURSE_ID]));
        $postdata[CRS_FORM_TABLE_ORDER] = trim(addslashes($_POST[CRS_FORM_TABLE_ORDER]));
        $postdata[CRS_FORM_STATUS] 		= $_POST[CRS_FORM_STATUS];
        return $postdata;
    }

    /**
     * Prep Form Data for Database
     * @return mixed
     */
    protected function prepFormDataForDatabase(){

        $dbArr[COURSES_TBL_COURSE_ID] = $this->form_data->getField(CRS_FORM_COURSE_ID);
        $dbArr[COURSES_TBL_CNAME] = $this->form_data->getField(CRS_FORM_CNAME);
        $dbArr[COURSES_TBL_DESC] = $this->form_data->getField(CRS_FORM_COURSE_DESC);
        $dbArr[COURSES_TBL_STATUS] = $this->form_data->getField(CRS_FORM_STATUS);
        $dbArr[COURSES_TBL_ORD] = $this->form_data->getField(CRS_FORM_TABLE_ORDER);
        $dbArr[COURSES_TBL_CIMG] = "";

        return $dbArr;

    }

}
?>