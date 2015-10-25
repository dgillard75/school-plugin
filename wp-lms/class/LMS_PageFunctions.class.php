<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 4/28/15
 * Time: 9:20 AM
 */


//Defines For Search and Order Variables
define("QUERY_SEARCH_VAR",          "search");
define("QUERY_ORDER_BY_VAR",        "orderby" );
define("QUERY_ORDER_NEXT_REQ",      "order_next_request" );
define("QUERY_ORDER_VAR",           "order");
define("QUERY_STRING_VAR",          "query_string");

/**
 * Class LMS_PageFunctions
 *
 */
class LMS_PageFunctions{
    /**
     * Returns the admin full page url when provided with the right args.
     *
     * @param $whichPage
     * @param bool $isSSL
     * @return string
     */
    public static function getAdminUrlPage($whichPage, $isSSL = false)
    {
        if ($isSSL)
            $_url = "https://";
        else
            $_url = "http://";

        list($real_uri, $query_string) = explode("?", $_SERVER['REQUEST_URI']);
        $_url .= $_SERVER['HTTP_HOST'] . $real_uri . "?" . $whichPage;
        return $_url;
    }

    public static function wp_admin_url(){
        if(is_ssl())
            $_url = "https://";
        else
            $_url = "http://";

        $real_uri = explode("?", $_SERVER['REQUEST_URI']);
        $_url .= $_SERVER['HTTP_HOST'] . $real_uri[0];
        return $_url;
    }

    public static function debugPrint($msg){
        echo "<pre>". $msg ."</pre>";
    }

    public static function hasFormBeenSubmitted(){
        return ($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    public static function get_args_for_search_and_sort(){
        $args[QUERY_ORDER_NEXT_REQ] = "desc";

        if (self::hasFormBeenSubmitted()) {
            if (isset($_POST[QUERY_SEARCH_VAR])) {
                $searchterm = $_POST[QUERY_SEARCH_VAR];
                $args[QUERY_SEARCH_VAR] = "*" . $searchterm . "*";
            }
        }else {
            if (isset($_GET[QUERY_SEARCH_VAR])) {
                $searchterm = $_GET[QUERY_SEARCH_VAR];
                $args[QUERY_SEARCH_VAR] = $searchterm;
            }

            if (isset($_GET[QUERY_ORDER_BY_VAR])) {
                $args[QUERY_ORDER_BY_VAR] = $_GET[QUERY_ORDER_BY_VAR];
            }

            if (isset($_GET[QUERY_ORDER_VAR])) {
                $args[QUERY_ORDER_VAR] = $_GET[QUERY_ORDER_VAR];

                if ($_GET[QUERY_ORDER_VAR] == "asc")
                    $args[QUERY_ORDER_NEXT_REQ] = "desc";
                else
                    $args[QUERY_ORDER_NEXT_REQ] = "asc";
            }
        }

        return $args;
    }

/*
    public static function students_home_url()
    {
        return self::getAdminUrlPage(STUDENTS_ADMIN_PAGE,is_SSL());
    }
*/
}
?>