<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/21/15
 * Time: 4:59 PM
 */

include_once(WPLMS_PLUGIN_PATH. "inc/ProductListing.php");
include_once(WPLMS_PLUGIN_PATH. "class/LMS_PageFunctions.class.php");

$plist = new ProductListing($_GET["pcode"],PRODUCTS_TABLE_PCODE);
$product_course = $plist->getCourse();
$listofmodules = $plist->getListOfModules();


//Validate Parameters. Resend to
if(LMS_PageFunctions::hasFormBeenSubmitted()) {
    include_once(WPLMS_PLUGIN_PATH . "class/ShoppingCart.class.php");
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
    if (isset($_POST['action']) && isset($_POST['pcode'])) {
        $pcode = $_POST['pcode'];
        $action = $_POST['action'];

        switch ($action) {
            case "add":
                $sc->add($pcode, 1);
                break;
            case "remove":
                $sc->remove($pcode, 1);
                break;
        }

        $_SESSION["cart"] = $sc->get_cart();
        if (isset($_POST['submitButton'])) {
            if ($_POST['submitButton'] == "checkout") {
                //show cart
                $cart_total = $sc->get_total();
                require('cart_page_template.php');
                exit();
            } else if ($_POST['submitButton'] == "keep-shopping") {
                $url = "";
            }
        }
    }
}


?>

<form action="" method="GET">
    <table cellspacing="0" cellpadding="4" border="1" width="550">
        <tbody>
        <tr>
            <td class="header" align="center" colspan="3" bgcolor="#093459"  style="color:#ffffff;"><b><?php echo $product_course[PRODUCTS_TABLE_NAME]; ?></b></td>
        </tr>
        <tr>
            <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" align="center" width="20"><input type="radio" name="pcode" checked="checked" value="<?php echo $product_course[PRODUCTS_TABLE_PCODE]; ?>"  onclick="selectcours('full')" ></td>
            <td bgcolor="#ffffff"><b>Full Course of <?php echo count($listofmodules);?> Modules</b></td>
            <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $product_course[PRODUCTS_TABLE_PRICE]; ?></b></td>
        </tr>
        <tr>
            <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td class="header" align="center" colspan="3" bgcolor="#093459" style="color:#ffffff;"><b>OR</b></td>
        </tr>
        <tr>
            <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
        </tr>
        <?php $counter=1; foreach($listofmodules as $module) : ?>
            <tr>
                <td bgcolor="#ffffff" width="20" align="center">
                    <?php if($counter==1) : ?>
                        <input type="radio" name="pcode" value="<?php echo $module[PRODUCTS_TABLE_PCODE]; ?>" onclick="selectcours('set')">
                    <?php else : ?>
                        <img src="<?php bloginfo('template_url');?>/images/radio.gif">
                    <?php endif ?>
                </td>
                <td bgcolor="#ffffff"><b><?php echo $module[PRODUCTS_TABLE_NAME]; ?></b></td>
                <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $module[PRODUCTS_TABLE_PRICE]; ?></b></td>
            </tr>
            <?php $counter++; endforeach; ?>
        <tr>
            <td align="center" colspan="3">
                <input type="hidden" name="course_id" value="<?php echo $product_course['course_id']; ?>" />
                <input type="hidden" name="course_type" id="course_type" value="full" />
                <input type="hidden" name="action"  value="add" >
                <button type="submit" name="submitButton" value="checkout">Check Out</button>
                <button type="submit" name="submitButton" value="keep-shopping">Add Another Course</button>
            </td>
        </tr>
        </tbody>
    </table>
</form>

<script>
    function selectcours(str) {
        jQuery('#course_type').val(str);
    }
</script>
