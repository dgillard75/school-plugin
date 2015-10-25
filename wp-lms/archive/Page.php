<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/7/15
 * Time: 1:51 PM
 */

namespace LMS;

include_once(LMS_PLUGIN_PATH."inc/LMS_SchoolDB.php");

abstract class Page {

    protected $lmsdb;

    function __construct(){
        $this->init();
    }

    protected function init() {
        $this->lmsdb = new \LMS_SchoolDB();
    }

    abstract protected function processForm();

    abstract protected function processGetRequest();

    abstract protected function getPostData();

}
?>