<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/6/15
 * Time: 9:59 AM
 */


require_once(WPLMS_PLUGIN_PATH . "class/htmlform/LMS_ModuleHTMLForm.class.php");


class LMS_ModulePage extends LMS_Page{

    protected $form_data;

    public function LMS_ModulePage(){
        $this->form_data = new LMS_ModuleHTMLForm();
        $this->form_data->setField("","");
        parent::__construct();
        $this->resultArr['status'] = false;
        $this->resultArr['errors'] = 0;
        $this->resultArr['etype'] = "";
    }

    public function getFormInformation(){
        return $this->form_data;
    }


    public function processForm(){
        $this->form_data->setField(LMS_HTML_FORM_ACTION, $_POST[LMS_HTML_FORM_ACTION]);
        //$isUpdate = ($this->course_form_data->getField(CRS_FORM_ACTION) == "edit");
        $this->form_data->load($this->getPostData());
        $errors = $this->form_data->validate();

        if($errors!=0){
            $this->setResult("form",$errors);
            return $this->resultArr;
        }

        $moduleDataDBArr = $this->prepFormDataForDatabase();
        // Do Database Work
        if ($this->form_data->getField(LMS_HTML_FORM_ACTION) == 'edit') {
            $dbresult = LMS_DBFunctions::update_module($_POST[MODULES_TBL_MODULE_ID],$moduleDataDBArr);
        } else {
            $dbresult = LMS_DBFunctions::add_module($moduleDataDBArr);
        }

        $this->setResult("db",$dbresult);
        return $this->resultArr;
    }


    public function processGetRequest(){

        if(isset($_GET[MODULES_TBL_MODULE_ID])) {
            //if action is not set but module id is, default to Edit Mode
            if(!isset($_GET[LMS_HTML_FORM_ACTION]))
                $actionVar = "edit";
            else
                $actionVar = $_GET[LMS_HTML_FORM_ACTION];

            $this->form_data->setField(LMS_HTML_FORM_ACTION, $actionVar);
            // if action is equal to edit, display student information.
            // all other cases display empty student information to be added.f
            if ($actionVar == 'edit' && !empty($_GET[MODULES_TBL_MODULE_ID])) {
                $data = LMS_DBFunctions::retrieve_module($_GET[MODULES_TBL_MODULE_ID]);
                $this->form_data->setField(MODULES_TBL_MODULE_ID, $_GET[MODULES_TBL_MODULE_ID]);
                $this->form_data->load($data);
            }else if($actionVar == 'delete' && !empty($_GET[MODULES_TBL_MODULE_ID])){

            }
        }else {
            $this->form_data->setField(LMS_HTML_FORM_ACTION, "add");
        }

        $this->setResult("get",0);
        return $this->resultArr;
    }

    public function getPostData(){
        $postdata[MODULES_TBL_MODULE_ID] = strip_tags(stripslashes($_POST[MODULES_TBL_MODULE_ID]));
        $postdata[MODULES_TBL_MODULE_NAME] = strip_tags(stripslashes($_POST[MODULES_TBL_MODULE_NAME]));
        $postdata[MODULES_TBL_MODULE_NUMBER] = strip_tags(stripslashes($_POST[MODULES_TBL_MODULE_NUMBER]));
        $postdata[MODULES_TBL_COURSE_ID] = strip_tags(stripslashes($_POST[MODULES_TBL_COURSE_ID]));
        $postdata[MODULES_TBL_SHORT_DESC] = strip_tags(stripslashes($_POST[MODULES_TBL_SHORT_DESC]));

        return $postdata;
    }

    protected function prepFormDataForDatabase(){

        $dbArr[MODULES_TBL_MODULE_NAME] = $this->form_data->getField(MODULES_TBL_MODULE_NAME);
        $dbArr[MODULES_TBL_MODULE_NUMBER] = $this->form_data->getField(MODULES_TBL_MODULE_NUMBER);
        $dbArr[MODULES_TBL_COURSE_ID] = $this->form_data->getField(MODULES_TBL_COURSE_ID);
        $dbArr[MODULES_TBL_SHORT_DESC] = $this->form_data->getField(MODULES_TBL_SHORT_DESC);

        return $dbArr;

    }

}
?>