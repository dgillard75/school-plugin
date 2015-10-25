<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 6/23/15
 * Time: 5:27 PM
 */

ini_set('display_errors', true);
error_reporting(E_ALL);

include_once(WPLMS_PLUGIN_PATH . "class/ShoppingCart.class.php");
include_once(WPLMS_PLUGIN_PATH . "class/LMS_PageFunctions.php");

define("KEEP_SHOPPING_URL", "");
define("CHECKOUT_URL","");



//ShortCode PHP functionality to dynamically handle the choice of a product or module
$sc = new ShoppingCart($_SESSION);

/**
 *  Variables:
 *
 *  [pcode]
 *  [course_id]
 *  [course_type]
 *  [action]
 *  [pcode]
 *
 */


//Validate Parameters. Resend to
if(LMS_PageFunctions::hasFormBeenSubmitted()) {
    if(isset($_POST['action']) && isset($_POST['pcode']) ) {
        $pcode = $_POST['pcode'];
        $action = $_POST['action'];

        switch($action){
            case "add":
                $sc->add($pcode, 1);
                break;
            case "remove":
                $sc->remove($pcode, 1);
                break;
        }

        $_SESSION["cart"] = $sc->get_cart();
    }


}else {
    // return back to Referring URL, if that dont exist back to all_courses


}

$cart_total = $sc->get_total();


if(isset($_POST['submitButton'])){
    if($_POST['submitButton'] == "checkout"){
        //show cart
        require('cart_page_template.php');
        exit();
    }else if($_POST['submitButton'] == "keep-shopping"){
        $url="";
    }
}

echo "<pre>\n";
$sc->printCart();
echo "</pre><br>";

echo "<pre> POST VARIABLES:<br>".print_r($_POST,true)."</pre>";
?>
