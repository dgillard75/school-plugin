<?php
/**
 * Created by PhpStorm.
 * User: dgillard75
 * Date: 5/4/2015
 * Time: 9:35 PM
 */

if($courseID != '') {
    $query="DELETE FROM course_sets WHERE id =".$_GET['delId'];
}
if($packageID != '') {
    $query="DELETE FROM package_sets WHERE id =".$_GET['delId'];
}

$re1=mysql_query($query);
mysql_query("DELETE FROM sets_modules WHERE set_id=".$_GET['delId']);
$location = $_SERVER['HTTP_REFERER'].'&msg=del';


<script type="text/javascript">
    window.location = '<?php echo $location; ?>';
</script>

?>