<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/5/15
 * Time: 4:55 PM
 */


require_once(LMS_PLUGIN_PATH."inc/PageErrors.php");

abstract class HtmlForm {

    protected $fields;
    protected $formerrors;

    protected abstract function init();

    protected abstract function loadFormErrorMessage();

    protected abstract function validate();

    public function __construct(){
        $this->fields = array();
        $this->formerrors = new PageErrors('');
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
        return $this->formerrors->getErrorMsg($key);
    }

    public function setError($key) { $this->formerrors->setError($key);}

    public function totalErrors(){
        return $this->formerrors->numberOfErrors();
    }

}
?>