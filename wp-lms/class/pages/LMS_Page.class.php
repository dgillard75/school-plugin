<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/7/15
 * Time: 1:51 PM
 */

/**
 * Class LMS_Page - Abstract Class for Admin Pages that require any inputs or forms
 */
abstract class LMS_Page {

    protected $resultArr;

    function __construct(){
        $this->resultArr = array();
    }

    /**
     * Process Form - Abstract method
     * @return mixed
     */
    abstract protected function processForm();

    /**
     * Process Get Request
     * @return mixed
     */
    abstract protected function processGetRequest();

    /**
     * Process Post Data - Abstract method
     * @return mixed
     */
    abstract protected function getPostData();

    /**
     * Set the Result of an action
     * @param $type
     * @param $result
     */
    protected function setResult($type,$result){
        $this->resultArr['etype'] = $type;

        if($type == 'db') {
            if ($result > 0) {
                $this->resultArr['status'] = true;
                $this->resultArr['errors'] = 0;
            } else {
                $this->resultArr['errors'] = 1;
                $this->resultArr['status'] = false;
            }
        }

        if($type == 'form'){
            if ($result == 0) {
                $this->resultArr['status'] = true;
                $this->resultArr['errors'] = 0;
            }else{
                $this->resultArr['status'] = false;
                $this->resultArr['errors'] = $result;

            }
        }

        if($type == 'get'){
            $this->resultArr['status'] = true;
        }
    }

}
?>