<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 5/11/15
 * Time: 3:29 PM
 */

$query="DELETE FROM user_courses WHERE id =".$_GET['delId'];
$re1=mysql_query($query);
$location = $_SERVER['HTTP_REFERER'].'&msg=del';
?>

<script type="text/javascript">
    window.location = '<?php echo $location; ?>';
</script>
