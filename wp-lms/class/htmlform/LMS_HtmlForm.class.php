<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:55 PM
 */


//require_once(WPLMS_PLUGIN_PATH . "class/LMS_PageErrors.class.php");

define("LMS_HTML_FORM_ACTION",    "action");
define("INVALID_PARAMETERS",      "invalid_params");

abstract class LMS_HtmlForm {

    protected $fields;
    protected $formerrors;
    public $errors;
    public $error_msgs;


    protected abstract function init();

    protected abstract function loadFormErrorMessage();

    protected abstract function validate();

    public function __construct(){
        $this->fields = array();
        $this->errors = array();
        $this->error_msgs = array();
    }

    public function load($arr)
    {
        foreach ($arr as $key => $value) {
            $this->fields[$key] = $value;
        }
    }

    public function getField($key){
        return $this->fields[$key];
    }

    public function setField($key, $value){
        $this->fields[$key] = $value;
    }

    public function getAllFields(){
        return $this->fields;
    }

    public function toStr($delim="\n")
    {
        $str = get_class($this) . ":".$delim;
        foreach ($this->fields as $key => $value) {
            $str .= "fields[".$key."].==>".$value.$delim;
        }
        return $str;
    }

    public function getFormErrorMsg($key)
    {
        return $this->error_msgs[$key];
    }

    public function getFormErrors()
    {
        return $this->errors;
    }

    public function setError($key) { $this->errors[$key]=true;}

    public function totalErrors(){
        return count($this->errors);
    }
}
?>