<?php

if(LMS_PageFunctions::hasFormBeenSubmitted()){
    $edd_add_to_cart_url = get_home_url() . "/?edd_action=add_to_cart&download_id=" . $_POST['download_id'];
    /*
     * Need to add checks to make sure the following
     *  - Customers dont add the same course twice
     *  - Customers dont add a module and the full course of that module
     */

    wp_redirect($edd_add_to_cart_url);
    exit(0);
}else {
    $id = $_GET['id'];
    $slibrary = new LMS_OnlineSchoolLibrary( $id );
    $course   = $slibrary->get_course();
}
?>

<form action="#" method="POST">
    <table cellspacing="0" cellpadding="4" border="1" width="550">
        <tbody>
        <tr>
            <td class="header" align="center" colspan="3" bgcolor="#093459"  style="color:#ffffff;"><b><?php echo $course['product_name']; ?></b></td>
        </tr>
        <tr>
            <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" align="center" width="20"><input type="radio" name="download_id" checked="checked" value="<?php echo $course['product_id']; ?>"  onclick="selectcours('full')" ></td>
            <td bgcolor="#ffffff"><b>Full Course of <?php echo count($course['modules']);?> Modules</b></td>
            <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $course['product_price']; ?></b></td>
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
        <?php $counter=1; foreach($course['modules'] as $module) : ?>
            <tr>
                <td bgcolor="#ffffff" width="20" align="center">
                    <?php if($counter==1) : ?>
                        <input type="radio" name="download_id" value="<?php echo $module['product_id']; ?>" onclick="selectcours('set')">
                    <?php else : ?>
                        <img src="<?php bloginfo('template_url');?>/images/radio.gif">
                    <?php endif ?>
                </td>
                <td bgcolor="#ffffff"><b><?php echo $module['product_name']?></b></td>
                <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $module['product_price']; ?></b></td>
            </tr>
            <?php $counter++; endforeach; ?>
        <tr>
            <td align="center" colspan="3">
                <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>" />
                <input type="hidden" name="course_type" id="course_type" value="full" />
                <input type="hidden" name="action"  value="add" >
                <button type="submit" name="submitButton" value="checkout">Add Product</button>
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
