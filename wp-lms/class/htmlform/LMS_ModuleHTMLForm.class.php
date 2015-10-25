<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:58 PM
 */


//require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_HtmlForm.class.php");
//require_once(WPLMS_PLUGIN_PATH . "class/LMS_PageErrors.class.php");

define("MODULE_FORM_MOD_ACTION_GETVAR","action");
define("MODULE_FORM_MOD_ID_GETVAR","module_id");
define("MODULES_DB_ERROR","module_db_error");


class LMS_ModuleHTMLForm extends LMS_HtmlForm{

    protected $msgs = array(
        MODULES_TBL_MODULE_NAME => "Please fill in the required 'Module Title' field.",
        MODULES_TBL_MODULE_NUMBER => "Please fill in the required 'Module Number' field.",
        MODULES_TBL_SHORT_DESC => "Please fill in the required 'Module Desc' field.",
        MODULES_DB_ERROR => "Please fill in the required 'Module Desc' field.",

    );

    protected function init(){
        $this->setField(MODULES_TBL_MODULE_NAME,"");
        $this->setField(MODULES_TBL_MODULE_NUMBER,"");
        $this->setField(MODULES_TBL_COURSE_ID,"");
        $this->setField(MODULES_TBL_SHORT_DESC,"");
        $this->setField(MODULES_DB_ERROR,"");

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

        if (empty($this->fields[MODULES_TBL_MODULE_NAME])) {
            $this->setError(MODULES_TBL_MODULE_NAME);
        }

        if (empty($this->fields[MODULES_TBL_MODULE_NUMBER]) || !is_numeric($this->fields[MODULES_TBL_MODULE_NUMBER])) {
            $this->setError(MODULES_TBL_MODULE_NUMBER);
        }

        if (empty($this->fields[MODULES_TBL_SHORT_DESC])) {
            $this->setError(MODULES_TBL_SHORT_DESC);
        }

        return $this->totalErrors();
    }
}
?>