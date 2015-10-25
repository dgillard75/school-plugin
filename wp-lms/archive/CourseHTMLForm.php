<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:58 PM
 */


require_once(LMS_PLUGIN_PATH."inc/HForm.php");
require_once(LMS_PLUGIN_PATH."inc/PageErrors.php");

define("CRS_FORM_ACTION","action");
define("CRS_FORM_COURSE_ID","courseID");
define("CRS_FORM_CCODE","ccode");
define("CRS_FORM_CNAME","cname");
define("CRS_FORM_CPRICE","cprice");
define("CRS_FORM_IMG","course_img");
define("CRS_FORM_DPRICE","discount_price");
define("CRS_FORM_CTIME","ctime");
define("CRS_FORM_COURSE_TIME_TYPE","course_time_type");
define("CRS_FORM_SHORT_DESC","short_descp");
define("CRS_FROM_DESC","course_discription");
define("CRS_FORM_TERM_ID","term_id");
define("CRS_FORM_TERM_TAXONOMY","term_taxonomy_id");
define("CRS_FORM_COURSE_DESC","course_discription");
define("CRS_FORM_STATUS","status");
define("CRS_FORM_TBL_ORD","tbl_order");


class CourseHTMLForm extends HtmlForm{

    protected $msgs = array(
        CRS_FORM_CNAME => "Please put a valid First Name.",
        CRS_FORM_CPRICE => "Please put a valid Price.",
        CRS_FORM_CTIME => "Please enter a valid course time",
        CRS_FORM_SHORT_DESC => "Please enter a valid description.",
    );

    protected function init(){
        $this->setField(CRS_FORM_CCODE,"");
        $this->setField(CRS_FORM_CNAME,"");
        $this->setField(CRS_FORM_CPRICE,"");
        $this->setField(CRS_FORM_IMG,"");
        $this->setField(CRS_FORM_DPRICE,"");
        $this->setField(CRS_FORM_COURSE_TIME_TYPE,"");
        $this->setField(CRS_FORM_SHORT_DESC,"");
        $this->setField(CRS_FORM_CTIME,"");

        $this->setField(CRS_FORM_TERM_ID,"");
        $this->setField(CRS_FORM_TERM_TAXONOMY,"");
        $this->setField(CRS_FORM_COURSE_DESC,"");
        $this->setField(CRS_FORM_STATUS,"");
        $this->setField(CRS_FORM_TBL_ORD,"");

        $this->loadFormErrorMessage();
    }

    protected function loadFormErrorMessage(){
        $this->formerrors = $this->msgs;
    }


    function __construct(){
        $this->fields = Array();
        $this->init();
        $this->formerrors = new PageErrors($this->msgs);
    }


    public function validate()
    {

        if (empty($this->fields[CRS_FORM_CNAME])) {
            $this->formerrors->setError(CRS_FORM_CNAME);
        }

        if (empty($this->fields[CRS_FORM_CPRICE]) || !is_numeric($this->fields[CRS_FORM_CPRICE])) {
            $this->formerrors->setError(CRS_FORM_CPRICE);
        }

        if (empty($this->fields[CRS_FORM_COURSE_TIME_TYPE])) {
            $this->formerrors->setError(CRS_FORM_COURSE_TIME_TYPE);
        }

        if (empty($this->fields[CRS_FORM_SHORT_DESC])) {
            $this->formerrors->setError(CRS_FORM_SHORT_DESC);
        }
        /**
        if (empty($this->fields[CRS_FORM_CNAME])) {
            $this->formerrors->setError(CRS_FORM_CNAME);
        }**/

        return $this->formerrors->numberOfErrors();
    }
}
?>