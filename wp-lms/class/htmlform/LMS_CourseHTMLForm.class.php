<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:58 PM
 */

//require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_HtmlForm.class.php");

define("CRS_FORM_ACTION","action");
define("CRS_FORM_COURSE_ID","courseID");
define("CRS_FORM_CNAME","course_title");
define("CRS_FORM_IMG","course_img");
define("CRS_FORM_COURSE_DESC","course_desc");
define("CRS_FORM_STATUS","status");
define("CRS_FORM_TABLE_ORDER","tbl_order");


class LMS_CourseHTMLForm extends LMS_HtmlForm{

    protected $msgs = array(
        CRS_FORM_CNAME => "Please fill in the required 'Course Title' field.",
        CRS_FORM_COURSE_DESC => "Please fill in the required 'Course Description' field.",
        CRS_FORM_COURSE_ID => "Invalid Parameters. Please try again"
    );

    protected function init(){
        $this->setField(CRS_FORM_COURSE_ID,"");
        $this->setField(CRS_FORM_CNAME,"");
        $this->setField(CRS_FORM_IMG,"");
        $this->setField(CRS_FORM_COURSE_DESC,"");
        $this->setField(CRS_FORM_STATUS,"");
        $this->setField(CRS_FORM_TABLE_ORDER,"");

        $this->loadFormErrorMessage();
    }

    protected function loadFormErrorMessage(){
        $this->error_msgs = $this->msgs;
    }


    function __construct(){
        $this->fields = Array();
        $this->init();
    }


    public function validate()
    {
        if($this->fields[CRS_FORM_ACTION] == "edit" && empty($this->fields[CRS_FORM_COURSE_ID])){
            $this->setError(CRS_FORM_COURSE_ID);
        }

        if (empty($this->fields[CRS_FORM_CNAME])) {
            $this->setError(CRS_FORM_CNAME);
        }

        if (empty($this->fields[CRS_FORM_COURSE_DESC])) {
            $this->setError(CRS_FORM_COURSE_DESC);
        }

        return $this->totalErrors();
    }
}
?>