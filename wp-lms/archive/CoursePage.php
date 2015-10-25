<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/6/15
 * Time: 9:59 AM
 */


require_once(LMS_PLUGIN_PATH."inc/Page.php");
require_once(LMS_PLUGIN_PATH."inc/CourseHtmlForm.php");
require_once(LMS_PLUGIN_PATH."inc/HForm.php");


class CoursePage extends \LMS\Page{

    protected $course_form_data;
    protected $resultArr;

    public function CoursePage(){
        $this->course_form_data = new CourseHTMLForm();
        parent::__construct();
        $this->resultArr['status'] = false;
        $this->resultArr['errors'] = 0;
        $this->resultArr['etype'] = "";
    }

    public function getFormInformation(){
        return $this->course_form_data;
    }


    public function processForm(){
        $this->course_form_data->setField(CRS_FORM_ACTION, $_POST[CRS_FORM_ACTION]);
        //$isUpdate = ($this->course_form_data->getField(CRS_FORM_ACTION) == "edit");
        $this->course_form_data->load($this->getPostData());
        $errors = $this->course_form_data->validate();

        if($errors!=0){
            $this->setResult("form",$errors);
            return $this->resultArr;
        }

        $courseDataDBArr = $this->prepFormDataForDatabase();
        // Do Database Work
        if ($this->course_form_data->getField(CRS_FORM_ACTION) == 'edit') {
            //LMS_PageFunctions::debugPrint($this->course_form_data->toStr());
            $dbresult = LMS_SchoolDB::update_course($courseDataDBArr);
        } else {
            $dbresult = LMS_SchoolDB::add_course($courseDataDBArr);
        }

        $this->setResult("db",$dbresult);
        return $this->resultArr;
    }

    protected function setResult($type,$result){

        $this->resultArr['etype'] = $type;
        $this->resultArr['errors'] = $result;

        if($type == 'db'){
            if ($result > 0)
                $this->resultArr['status'] = true;
            else{
                $this->course_form_data->setError(SAF_DB_ERROR);
                $this->resultArr['status'] = false;
            }
        }


        if($type == 'form'){
            if ($result == 0)
                $this->resultArr['status'] = true;
            else{
                $this->resultArr['status'] = false;
            }
        }

        if($type == 'get'){
            $this->resultArr['status'] = true;
        }
    }

    public function processGetRequest(){

        if(isset($_GET[CRS_FORM_ACTION])) {
            $this->course_form_data->setField(CRS_FORM_ACTION, $_GET[CRS_FORM_ACTION]);
            // if action is equal to edit, display student information.
            // all other cases display empty student information to be added.
            if ($_GET[CRS_FORM_ACTION] == 'edit' && !empty($_GET[CRS_FORM_COURSE_ID])) {
                $coursedata = \LMS_SchoolDB::retrieve_course($_GET[CRS_FORM_COURSE_ID]);
                $this->course_form_data->setField(CRS_FORM_COURSE_ID, $_GET[CRS_FORM_COURSE_ID]);
                $this->course_form_data->load($coursedata);
                //echo "<pre>Found student_data:" . var_dump($coursedata) . "</pre>";
            }
        }else {
            $this->course_form_data->setField(CRS_FORM_ACTION, "add");
        }

        $this->setResult("get",0);
        return $this->resultArr;
    }

    public function getPostData(){
        $postdata[CRS_FORM_CNAME] = strip_tags(stripslashes($_POST[CRS_FORM_CNAME]));
        $postdata[CRS_FORM_CCODE] = strip_tags(stripslashes($_POST[CRS_FORM_CCODE]));
        $postdata[CRS_FORM_CPRICE] = strip_tags(stripslashes($_POST[CRS_FORM_CPRICE]));
        $postdata[CRS_FORM_DPRICE] = strip_tags(stripslashes($_POST[CRS_FORM_DPRICE]));
        $postdata[CRS_FORM_IMG] = strip_tags(stripslashes($_POST[CRS_FORM_IMG]));
        $postdata[CRS_FORM_CTIME] = strip_tags(stripslashes($_POST[CRS_FORM_CTIME]));
        $postdata[CRS_FORM_COURSE_TIME_TYPE] = strip_tags(stripslashes($_POST[CRS_FORM_COURSE_TIME_TYPE]));
        $postdata[CRS_FORM_SHORT_DESC] = strip_tags(stripslashes($_POST[CRS_FORM_SHORT_DESC]));
        $postdata[CRS_FORM_COURSE_DESC] = trim(addslashes($_POST[CRS_FORM_COURSE_DESC]));
        $postdata[CRS_FORM_STATUS] 		= $_POST[CRS_FORM_STATUS];
        $postdata[CRS_FORM_TERM_ID] 		= $_POST[CRS_FORM_TERM_ID];
        $postdata[CRS_FORM_TERM_TAXONOMY] = $_POST[CRS_FORM_TERM_TAXONOMY];
        $postdata[CRS_FORM_TBL_ORD] = $_POST["sort_order"];

        return $postdata;
    }

    protected function prepFormDataForDatabase(){

        $dbArr[COURSES_TBL_CCODE] = $this->course_form_data->getField(CRS_FORM_CCODE);
        $dbArr[COURSES_TBL_CNAME] = $this->course_form_data->getField(CRS_FORM_CNAME);
        $dbArr[COURSES_TBL_CPRICE] = $this->course_form_data->getField(CRS_FORM_CPRICE);
        $dbArr[COURSES_TBL_CIMG] = $this->course_form_data->getField(CRS_FORM_IMG);
        $dbArr[COURSES_TBL_DISCOUNT_PRICE] = $this->course_form_data->getField(CRS_FORM_DPRICE);
        $dbArr[COURSES_TBL_CTIMETYPE] = $this->course_form_data->getField(CRS_FORM_COURSE_TIME_TYPE);
        $dbArr[COURSES_TBL_SHORT_DESC] = $this->course_form_data->getField(CRS_FORM_SHORT_DESC);
        $dbArr[COURSES_TBL_DESC] = $this->course_form_data->getField(CRS_FORM_COURSE_DESC);
        $dbArr[COURSES_TBL_TERM_ID] = $this->course_form_data->getField(CRS_FORM_TERM_ID);
        $dbArr[COURSES_TBL_TERM_TAXONOMY_ID] = $this->course_form_data->getField(CRS_FORM_TERM_TAXONOMY);
        $dbArr[COURSES_TBL_STATUS] = $this->course_form_data->getField(CRS_FORM_STATUS);
        $dbArr[COURSES_TBL_ORD] =$this->course_form_data->getField(CRS_FORM_TBL_ORD);

        return $dbArr;

    }

}
?>