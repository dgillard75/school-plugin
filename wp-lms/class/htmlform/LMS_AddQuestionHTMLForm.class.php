<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:58 PM
 */

//require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_HtmlForm.class.php");

define("QFORM_QUIZ_ID",     "quizeid");
define("QFORM_QUESTION_ID", "qid");

define("QFORM_QUESTION",    "ques");
define("QFORM_ORDER",       "ques_order");

define("QFORM_ANS_A",       "ansa");
define("QFORM_ANS_B",       "ansb");
define("QFORM_ANS_C",       "ansc");
define("QFORM_ANS_D",       "ansd");

define("QFORM_SERIALIZED",  "serialized");

define("QFORM_CORRECT_ANSWER", "correct_ans");

define("QFORM_ANS_ERROR",              "ans_error");
define("QFORM_QUESTION_ERROR",         "quest_error");
define("QFORM_QUESTION_ORDER_ERROR",   "ans_error");
define("QFORM_QUESTION_CANS_ERROR",     "correct_ans_error");
define("QFORM_INVALID_PARAMETERS",      "invalid_params");


class LMS_AddQuestionHTMLForm extends LMS_HtmlForm{

    protected $msgs = array(
        QFORM_ANS_ERROR              => "Please fill in the required 'Answer'.",
        QFORM_QUESTION_ERROR         => "Please fill in the required 'Question'.",
        QFORM_QUESTION_ORDER_ERROR   => "Please fill in the required 'Order for the Question'.",
        QFORM_QUESTION_CANS_ERROR    => "Please fill in the required 'Correct Answer'.",
        QFORM_INVALID_PARAMETERS     => "Invalid parameters, please click back and try again. Missing Quiz Id."
    );

    protected function init(){

        $this->setField(LMS_HTML_FORM_ACTION,"");

        $this->setField(QFORM_QUIZ_ID,"");
        $this->setField(QFORM_QUESTION_ID,"");

        $this->setField(QFORM_QUESTION,"");
        $this->setField(QFORM_ORDER,"");

        $this->setField(QFORM_ANS_A,"");
        $this->setField(QFORM_ANS_B,"");
        $this->setField(QFORM_ANS_C,"");
        $this->setField(QFORM_ANS_D,"");

        $this->setField(QFORM_CORRECT_ANSWER,"");

        $this->loadFormErrorMessage();
    }

    protected function loadFormErrorMessage(){
        $this->error_msgs = $this->msgs;
    }


    function __construct(){
        $this->fields = Array();
        $this->init();
    }

    public function load_question($dbdata){

        $this->setField(QFORM_QUESTION, $dbdata[QFORM_QUESTION]);
        $this->setField(QFORM_ORDER,$dbdata[QFORM_ORDER]);

        $this->setField(QFORM_CORRECT_ANSWER,$dbdata[QFORM_CORRECT_ANSWER]);

        //unseralize Database object
        $unserilizeAns = unserialize($dbdata['answers']);

        if(!empty($unserilizeAns)) {
            $this->setField(QFORM_ANS_A, $unserilizeAns[QFORM_ANS_A]);
            $this->setField(QFORM_ANS_B, $unserilizeAns[QFORM_ANS_B]);
            $this->setField(QFORM_ANS_C, $unserilizeAns[QFORM_ANS_C]);
            $this->setField(QFORM_ANS_D, $unserilizeAns[QFORM_ANS_D]);
        }

    }

    public function validate_get_request(){
        //Mandatory there is a Quiz Id Passed here if empty set error return
        if (empty($this->fields[QFORM_QUIZ_ID])) {
            $this->setError(QFORM_INVALID_PARAMETERS);
        }

        return $this->totalErrors();

    }


    public function validate()
    {

        if (empty($this->fields[QFORM_QUESTION])) {
            $this->setError(QFORM_QUESTION_ERROR);
        }

        if (empty($this->fields[QFORM_ORDER])) {
            $this->setError(QFORM_QUESTION_ORDER_ERROR);
        }

        if (empty($this->fields[QFORM_ANS_A])) {
            $this->setError(QFORM_ANS_ERROR);
        }

        if (empty($this->fields[QFORM_ANS_B])) {
            $this->setError(QFORM_ANS_ERROR);
        }

        if (empty($this->fields[QFORM_ANS_C])) {
            $this->setError(QFORM_ANS_ERROR);
        }

        if (empty($this->fields[QFORM_ANS_D])) {
            $this->setError(QFORM_ANS_ERROR);
        }

        if (empty($this->fields[QFORM_CORRECT_ANSWER])) {
            $this->setError(QFORM_QUESTION_CANS_ERROR);
        }

        return $this->totalErrors();
    }
}
?>