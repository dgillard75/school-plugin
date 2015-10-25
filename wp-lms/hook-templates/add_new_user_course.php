<?php
include_once(LMS_INCLUDE_DIR."ProductListing.php");

$plist = new ProductListing($_POST['courseid']);
$course = $plist->getCourse();
$listofmodules = $plist->getListOfModules();
?>

    <form action="#" method="post">
        <table cellspacing="0" cellpadding="4" border="1" width="550">
            <tbody>
                <tr>
                    <td class="header" align="center" colspan="3" bgcolor="#990000"  style="color:#ffffff;"><b><?php echo $course['cname']; ?></b></td>
                </tr>
                <tr>
                    <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" align="center" width="20"><input type="radio" name="setpicked" checked="checked" value="<?php echo $course['cprice']; ?>"  onclick="selectcours('full')" ></td>
                    <td bgcolor="#ffffff"><b>Full Course of <?php echo count($listofmodules);?> Modules</b></td>
                    <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $course['cprice']; ?></b></td>
                </tr>
                <tr>
                    <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
                </tr>
                <tr>
                    <td class="header" align="center" colspan="3" bgcolor="#990000" style="color:#ffffff;"><b>OR</b></td>
                </tr>
                <tr>
                    <td class="header" align="center" colspan="3"><b>&nbsp;</b></td>
                </tr>
            <?php $counter=1; foreach($listofmodules as $module) : ?>
                <tr>
                    <td bgcolor="#ffffff" width="20" align="center">
            <?php if($counter==1) : ?>
                    <input type="radio" name="setpicked" value="<?php echo $module['set_id']; ?>" onclick="selectcours('set')">
            <?php else : ?>
                    <img src="<?php bloginfo('template_url');?>/images/radio.gif">
            <?php endif ?>
                    </td>
                    <td bgcolor="#ffffff"><b><?php echo $module['module_name']; ?></b></td>
                    <td bgcolor="#ffffff" width="200" align="center"><b> $<?php echo $module['set_price']; ?></b></td>
                </tr>
            <?php $counter++; endforeach; ?>
                <tr>
                    <td align="center" colspan="3">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>" />
                        <input type="hidden" name="course_type" id="course_type" value="full" />
                        <input type="submit" value="Continue" name="continue">
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
