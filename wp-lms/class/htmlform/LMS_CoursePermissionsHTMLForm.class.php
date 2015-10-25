<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 9/9/15
 * Time: 3:28 PM
 */

/**
 * Define all the Form Input Values Here
 *
 */

define("COURSE_PERMISSIONS_FORM_USER_ID", "user_id");
define("COURSE_PERMISSIONS_FORM_COURSE_LIST", "course_list");

/**
 *
 * Define Errors here
 *
 */
define("COURSE_PERMISSIONS_PARAMETERS", "invalid_parameters");

//require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_HtmlForm.class.php");

class LMS_CoursePermissionsHTMLForm extends LMS_HtmlForm
{

    protected $msgs = array(
        COURSE_PERMISSIONS_PARAMETERS => "Invalid parameters, please click back and try again. Missing User Id."
    );


    protected function init()
    {
        $this->setField(LMS_HTML_FORM_ACTION, "");
        $this->setField(COURSE_PERMISSIONS_FORM_USER_ID, "");
        $this->setField(COURSE_PERMISSIONS_FORM_COURSE_LIST, "");
        $this->loadFormErrorMessage();
    }

    protected function loadFormErrorMessage()
    {
        $this->error_msgs = $this->msgs;
    }

    function __construct()
    {
        $this->fields = Array();
        $this->init();
    }

    public function validate()
    {
        if (empty($this->fields[COURSE_PERMISSIONS_FORM_USER_ID])) {
            $this->setError(COURSE_PERMISSIONS_PARAMETERS);
        }
        return $this->totalErrors();
    }

}
