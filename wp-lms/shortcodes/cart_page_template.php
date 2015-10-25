    <div class="entry-content">
        <div class='mailmunch-forms-before-post' style='display: none !important;'></div>
        <div id="edd_checkout_wrap">
            <form id="edd_checkout_cart_form" method="post">
                <div id="edd_checkout_cart_wrap">
                    <table id="edd_checkout_cart" class="ajaxed">
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
                        <th colspan="5" class="edd_cart_total">Total: <span class="edd_cart_amount" data-subtotal="150" data-total="150">&#36;<?php echo $cart_total; ?></span></th>
                    </tr>
                    </tfoot>
                </table>
                </div>
                <fieldset id="edd_purchase_submit">
                    <p id="edd_final_total_wrap">
                        <strong>Purchase Total:</strong>
                        <span class="edd_cart_amount" data-subtotal="99" data-total="99">&#36;99.00</span>
                    </p>

                    <input type="hidden" name="edd-user-id" value="33"/>
                    <input type="hidden" name="edd_action" value="purchase"/>
                    <input type="hidden" name="edd-gateway" value="paypal" />

                    <input type="submit" class="edd-submit blue button" id="edd-purchase-button" name="edd-purchase" value="Purchase"/>


                </fieldset>
            </form>
        </div>