<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/4/15
 * Time: 5:17 PM
 */

global $wpdb;
$id = $_POST['packageid'];
$result = mysql_query("SELECT * FROM package WHERE id='".$id."' order by tbl_order");
$package = mysql_fetch_array($result);
$moduleresult = mysql_query("SELECT * FROM `module` WHERE module_id in (select  module_id from module_package
	where package_id='".$package['id']."') ORDER BY tbl_order");
$totalmodule = mysql_num_rows($moduleresult);
$split = floor($totalmodule)/2;
$setsql="SELECT * FROM package_sets WHERE package_id='".$package['id']."' ORDER BY set_order";
$SectionsResult = mysql_query($setsql);
$i=0;
?>
    <form action="#" method="post">
        <table cellspacing="0" cellpadding="4" border="1" width="550">
            <tbody>
            <tr>
                <td class="header" align="center" colspan="3" bgcolor="#990000"  style="color:#ffffff;"><b><?php echo $package['package_name']; ?></b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" align="center" width="20"><input type="radio" name="setpicked" checked="checked" value="<?php echo $package['package_price']; ?>"  onclick="selectcours('full')" ></td>
                <td bgcolor="#ffffff"><b>Full package of <?php echo $totalmodule;?> Modules</b></td>
                <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $package['package_price']; ?></b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
            </tr>
            <tr><td class="header" align="center" colspan="3" bgcolor="#990000" style="color:#ffffff;"><b>OR</b></td>
            </tr>
            <tr>
                <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
            </tr>

            <?php $i=1; while($sections = mysql_fetch_array($SectionsResult))
            {
                $set_modulesql="SELECT COUNT( * ) AS totalmod FROM  `sets_modules` WHERE set_id ='".$sections['id']."'";
                $setmodule = mysql_query($set_modulesql);
                $setmodules = mysql_fetch_array($setmodule);

                ?>

                <tr>
                    <td bgcolor="#ffffff" width="20" align="center">
                        <?php

                        if($i==1) { ?>
                            <input type="radio" name="setpicked" value="<?php echo $sections['set_price']; ?>" onclick="selectcours('set')">
                        <?php } else { ?>
                            <img src="<?php bloginfo('template_url');?>/images/radio.gif">
                        <?php } ?>
                    </td>
                    <td bgcolor="#ffffff"><b><?php echo $sections['set_name'].'&nbsp;-&nbsp;'. $setmodules['totalmod'] .'&nbsp;Modules'; ?></b></td>
                    <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $sections['set_price']; ?></b></td>

                </tr>
                <?php $i++; } ?>
            <tr>
                <td align="center" colspan="3">
                    <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>" />
                    <input type="hidden" name="package_type" id="package_type" value="full" />
                    <input type="submit" value="Continue" name="countinue">

                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <script>
        function selectcours(str) {
            jQuery('#package_type').val(str);
        }

    </script>
