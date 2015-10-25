<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:58 PM
 */

//require_once(WPLMS_PLUGIN_PATH . "inc/LMS_Defines.php");
//require_once(WPLMS_PLUGIN_PATH . "class/LMS_HtmlForm.class.php");
//require_once(WPLMS_PLUGIN_PATH . "class/LMS_PageFunctions.class.php");


define("QUIZMODFORM_QUIZ_ID", "quizID");
define("QUIZMODFORM_CID",     "courseID");
define("QUIZMODFORM_MID",     QUIZ_TABLE_MID);
define("QUIZMODFORM_TITLE",   QUIZ_TABLE_TITLE);


define("QUIZMODFORM_TITLE_ERROR",      "quiz_title_error");

class LMS_QuizModifyHTMLForm extends LMS_HtmlForm{

    protected $msgs = array(
        QUIZMODFORM_TITLE_ERROR      => "Please fill in the required 'Answer'.",
        INVALID_PARAMETERS           => "Invalid parameters, please click back and try again. Missing Quiz Id."
    );

    protected function init(){
        $this->setField(LMS_HTML_FORM_ACTION,"");
        $this->setField(QUIZMODFORM_CID,"");
        $this->setField(QUIZMODFORM_MID,"");
        $this->setField(QUIZMODFORM_TITLE,"");
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
        if(LMS_PageFunctions::hasFormBeenSubmitted()){
            if (empty($this->fields[QUIZMODFORM_TITLE])) {
                $this->setError(QUIZMODFORM_TITLE_ERROR);
            }

            if (empty($this->fields[QUIZMODFORM_CID])) {
                $this->setError(QUIZMODFORM_TITLE_ERROR);
            }
            if (empty($this->fields[QUIZMODFORM_MID])) {
                $this->setError(QUIZMODFORM_TITLE_ERROR);
            }
        }

        if($this->getField(LMS_HTML_FORM_ACTION)=="edit" && empty($this->fields[QUIZMODFORM_QUIZ_ID])) {
            $this->setError( INVALID_PARAMETERS );
        }

        return $this->totalErrors();
    }
}
?>