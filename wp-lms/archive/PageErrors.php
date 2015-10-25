<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/6/15
 * Time: 9:54 AM
 */


class PageErrors {
    protected $errors = Array();
    protected $msgs = Array();
    /*
    protected $msgs = array(
        SAF_FN => "Please put a valid First Name.",
        SAF_LN => "Please put a valid Last Name.",
        SAF_EMAIL => "Please enter a valid email.",
        SAF_LOGIN => "Please enter a valid login.",
        SAF_DB_ERROR => "Database Error. Please Try Again."
    );*/

    public function PageErrors($errorMessages){
        $this->msg = $errorMessages;
    }

    public function setError($field){
        $this->errors[$field]=true;
    }

    public function getErrorMsg($field){
        $outmessage = "";
        if(array_key_exists($field, $this->errors)){
            $outmessage = $this->msgs[$field];
        }
        return $outmessage;
    }

    public function numberOfErrors(){
        return count($this->errors);
    }

}
?>