<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/6/15
 * Time: 9:59 AM
 */


require_once(WPLMS_PLUGIN_PATH . "class/pages/LMS_Page.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_AddQuestionHTMLForm.class.php");
require_once(WPLMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");

class LMS_AddQuestionPage extends LMS_Page{

    protected $form_data;
    //protected $resultArr;

    public function LMS_AddQuestionPage(){
        $this->form_data = new LMS_AddQuestionHTMLForm();
        parent::__construct();
        $this->resultArr['status'] = false;
        $this->resultArr['errors'] = 0;
        $this->resultArr['etype'] = "";
        $this->quiz_title = "";
    }

    public function getFormInformation(){
        return $this->form_data;
    }


    public function get_quiz_title(){

        $quiz_id = $this->form_data->getField(QFORM_QUIZ_ID);

        $quiz = LMS_DBFunctions::select_query(QUIZ_TABLE,"WHERE `id`=$quiz_id",true);
        return $quiz['title'];
    }

    protected function set_next_question_count(){

        $next_question_count = 1 ;
        $next_question = LMS_DBFunctions::get_next_count_for_question($this->form_data->getField(QFORM_QUIZ_ID));
        if($next_question)
            $next_question_count = intval($next_question[0][QUIZ_QUEST_TABLE_QORDER]) + 1;

        $this->form_data->setField(QUIZ_QUEST_TABLE_QORDER, $next_question_count);

    }


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
            //LMS_PageFunctions::debugPrint($this->form_data->toStr());
            $dbresult = LMS_DBFunctions::update_question_on_quiz($dbData);
        } else {
            $dbresult = LMS_DBFunctions::add_question_to_quiz($dbData);
        }

        $this->setResult("db",$dbresult);
        return $this->resultArr;
    }

    public function processGetRequest(){

        $this->form_data->load($this->getRequestData());
        $errors = $this->form_data->validate_get_request();
        //LMS_Log::print_r($errors,__FUNCTION__);
        if($errors!=0){
            $this->setResult("form",$errors);
            return $this->resultArr;
        }

        $this->set_next_question_count();
        if(isset($_GET[LMS_HTML_FORM_ACTION])) {
            $this->form_data->setField(LMS_HTML_FORM_ACTION, $_GET[LMS_HTML_FORM_ACTION]);

            // if action is equal to edit, display Question
            // all other cases display empty fiels.
            if ($_GET[LMS_HTML_FORM_ACTION] == 'edit') {
                if (isset($_GET[QFORM_QUESTION_ID]) && !empty($_GET[QFORM_QUESTION_ID])) {
                    $this->form_data->setField(QFORM_QUESTION_ID, $_GET[QFORM_QUESTION_ID]);
                    $quesid = $this->form_data->getField(QFORM_QUESTION_ID);
                    $ques_result = LMS_DBFunctions::select_query(QUIZ_QUEST_TABLE, "WHERE `".QUIZ_QUEST_TABLE_QUES_ID."`=$quesid", true);   //("SELECT * FROM `quize_questions` WHERE `qid`=$quesid");
                    //LMS_Log::print_r($ques_result, __FUNCTION__);
                    $this->form_data->load_question($ques_result);
                } else {
                    $this->form_data->setField(LMS_HTML_FORM_ACTION, "add");
                }
            } else {
                $this->form_data->setField(LMS_HTML_FORM_ACTION, "add");

            }
        }
        return $this->resultArr;
    }

    public function getPostData(){
        //$postdata[CRS_FORM_CNAME] = strip_tags(stripslashes($_POST[CRS_FORM_CNAME]));
        $postdata[QFORM_QUIZ_ID] = $_POST[QFORM_QUIZ_ID];
        $postdata[QFORM_QUESTION] = trim(addslashes($_POST[QFORM_QUESTION]));
        $postdata[QFORM_ANS_A] = trim(addslashes($_POST[QFORM_ANS_A]));
        $postdata[QFORM_ANS_B] = trim(addslashes($_POST[QFORM_ANS_B]));
        $postdata[QFORM_ANS_C] = trim(addslashes($_POST[QFORM_ANS_C]));
        $postdata[QFORM_ANS_D] = trim(addslashes($_POST[QFORM_ANS_D]));
        $postdata[QFORM_CORRECT_ANSWER] = $_POST[QFORM_CORRECT_ANSWER];
        $postdata[QFORM_ORDER] = $_POST[QFORM_ORDER];

        $postdata[QFORM_SERIALIZED] = array(QFORM_ANS_A => $postdata[QFORM_ANS_A],
                                            QFORM_ANS_B => $postdata[QFORM_ANS_B],
                                            QFORM_ANS_C => $postdata[QFORM_ANS_C],
                                            QFORM_ANS_D => $postdata[QFORM_ANS_D]);

        return $postdata;
    }


    public function getRequestData(){
        $getdata[QFORM_QUIZ_ID] = array_key_exists(QFORM_QUIZ_ID,$_GET) ? $_GET[QFORM_QUIZ_ID] : "";
        $getdata[LMS_HTML_FORM_ACTION] = array_key_exists(LMS_HTML_FORM_ACTION,$_GET) ? $_GET[LMS_HTML_FORM_ACTION] : "";
        LMS_Log::print_r($getdata,__FUNCTION__);
        return $getdata;
    }

    protected function prepFormDataForDatabase(){

        $dbArr[QFORM_QUIZ_ID] = $this->form_data->getField(QFORM_QUIZ_ID);
        $dbArr[QFORM_QUESTION_ID] = $this->form_data->getField(QFORM_QUESTION_ID);
        $dbArr[QFORM_QUESTION] = $this->form_data->getField(QFORM_QUESTION);
        $dbArr[QFORM_CORRECT_ANSWER] = $this->form_data->getField(QFORM_CORRECT_ANSWER);
        $dbArr[QFORM_ORDER] = $this->form_data->getField(QFORM_ORDER);
        $dbArr[QFORM_SERIALIZED] = $this->form_data->getField(QFORM_SERIALIZED);

        return $dbArr;
    }

}
?>