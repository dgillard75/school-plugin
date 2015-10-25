<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/29/15
 * Time: 4:59 PM
 */

class LMS_Log {

    public static function log_db($message){
        global $wpdb;
        global $user_login;
        get_currentuserinfo();
        $dateStr = "[" . date("d.M.Y H:m:s") . "]";
        $logmessage = $dateStr . "\t" . $user_login . "\tMYSQL Query:" . $wpdb->last_query . "\t" . $message . "\n";
        error_log($logmessage, 3, LMS_PLUGIN_PATH . "logs/db.log");
    }

    public static function print_r($var, $function=__FUNCTION__, $prefix=""){
        echo "<pre>".$function.":".$prefix."</br>";
        print_r($var);
        echo "</pre>";
    }
}

?>