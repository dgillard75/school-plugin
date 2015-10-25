<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/27/15
 * Time: 3:21 PM
 */



/**   Get Parameters for adding, removing from cart
 *       action (add/remove)
 *       pcode - product code to add or remove
 *
 *
 *
 */
include_once(LMS_PLUGIN_PATH . "class/ShoppingCart.class.php");
include_once(LMS_INCLUDE_DIR."LMS_PageFunctions.php");

// Check to see if what action Take:
$sc = new ShoppingCart($_SESSION);
$process_cart = false;
$pcode="";
$action="";
if(LMS_PageFunctions::hasFormBeenSubmitted()){
    if(isset($_POST['action']) && isset($_POST['pcode']) ) {
        $pcode = $_POST['pcode'];
        $action = $_POST['action'];
        $process_cart = true;
    }
}else {
    if(isset($_GET['action']) && isset($_GET['pcode']) ) {
        $pcode = $_GET['pcode'];
        $action = $_GET['action'];
        $process_cart = true;
    }
}

if($process_cart){
    switch ($action) {
        case "add":
            $sc->add($pcode, 1);
            break;
        case "remove":
            $sc->remove($pcode);
            break;
    }
    $_SESSION["cart"] = $sc->get_cart();
}

/**
if(isset($_GET['action']) && isset($_GET['pcode']) ) {
    $pcode = $_GET['pcode'];
    $action = $_GET['action'];
    switch ($action) {
        case "add":
            $sc->add($pcode, 1);
            break;
        case "remove":
            $sc->remove($pcode);
            break;
    }
    //save cart information
    $_SESSION["cart"] = $sc->get_cart();
}
**/
?>


<div class="entry-content">
    <div class='mailmunch-forms-before-post' style='display: none !important;'></div><div id="edd_checkout_wrap"><form id="edd_checkout_cart_form" method="post"><div id="edd_checkout_cart_wrap"><table id="edd_checkout_cart" class="ajaxed">
                    <thead>
                    <tr class="edd_cart_header_row">
                        <th class="edd_cart_item_name">Item Name</th>
                        <th class="edd_cart_item_price">Item Price</th>
                        <th class="edd_cart_actions">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($_SESSION["cart"] as $item_key => $value) : ?>
                        <tr class="edd_cart_item" id="edd_cart_item_0_3117" data-download-id="3117">
                            <td class="edd_cart_item_name">
                                <span class="edd_checkout_cart_item_title"><?php echo $item_key ?></span>
                            </td>
                            <td class="edd_cart_item_price">&#36;<?php echo $value[SHOPPING_CART_PRICE_KEY]; ?></td>
                            <td class="edd_cart_actions">
                                <a class="edd_cart_remove_item_btn" href="<?php echo get_permalink(6141)."?action=remove&pcode=".$item_key ?>">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <!-- Show any cart fees, both positive and negative fees -->

                    </tbody>
                    <tfoot>



                    <tr class="edd_cart_footer_row edd_cart_discount_row"  style="display:none;">
                        <th colspan="5" class="edd_cart_discount">
                        </th>
                    </tr>


                    <tr class="edd_cart_footer_row">
                        <th colspan="5" class="edd_cart_total">Total: <span class="edd_cart_amount" data-subtotal="150" data-total="150">&#36;<?php echo $sc->get_total(); ?></span></th>
                    </tr>
                    </tfoot>
                </table>
            </div>