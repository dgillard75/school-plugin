<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/29/15
 * Time: 12:05 PM
 */









//Define Types for various random DB queries.
define("BY_TRANS_ID",  "tid");
define("BY_USER_ID",  "uid");
define("BY_MODULE_ID","mid");
define("BY_COURSE_ID","cid");


class ReturnCodes{
    const ALREADY_EXIST = -2;
    const FAIL = 0;
    const SUCCESS = 1;
}

?>