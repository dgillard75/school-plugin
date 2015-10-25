<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 4/24/15
 * Time: 9:20 AM
 */

define("LMS_WELCOME_EMAIL","WelcomeEmail.html");
define("LMS_COURSE_ACTIVE_EMAIL","CourseActive.html");
define("LMS_TEMPLATE_DIRECTORY", "");
define("LMS_FROM_ADDRESS","info@hstinstitute.com");


class LMS_Email{

    const LMS_WELCOME = 0;
    const LMS_COURSE_ACTIVE = 1;

    public static $list_of_templates = array( LMS_WELCOME_EMAIL, LMS_COURSE_ACTIVE_EMAIL);
    public static $subject = array (
                    "Welcome to House Hold Staff Training Institute",
                    "Your Courses Are Now Active"
    );

    public static function send($whichEmail, $from, $to, $valuesArr){

        if(!array_key_exists($whichEmail, self::$list_of_templates)){
            return -1;
        }

        $emailcontents = file_get_contents(self::$list_of_templates[$whichEmail]);

        foreach ($valuesArr as $key => $value){
            $keyStr = "**".$key."**";
            $emailcontents = str_replace($keyStr,$value, $emailcontents);
        }

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "From: <".$from.">"."\r\n";

        mail($to,self::$subject[$whichEmail],$emailcontents,$headers);
    }

}

/**
$v = array(
    "first_name" => "Dakarai",
    "last_name" => "Gillard",
    "login" => "dgilard75");

LMS_Email::send(LMS_Email::LMS_WELCOME,"HSTI@celebstaff.com","mryahoo@celebstaff.com",$v);
**/
?>