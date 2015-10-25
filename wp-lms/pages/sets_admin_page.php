<?php
    //require('conf.php');
    include_once(LMS_PLUGIN_PATH."inc/LMS_PageFunctions.php");
    include_once(LMS_PLUGIN_PATH."inc/LMS_SchoolDB.php");

    $url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    if(LMS_PageFunctions::hasFormBeenSubmitted()) {
        $sets = $_POST['sets'];
        $orders = $_POST['orders'];
        $counts = sizeof($sets);

        if ($courseID != '') {
            for ($i = 0; $i < $counts; $i++) {
                mysql_query("UPDATE course_sets SET `set_order`=" . $orders[$i] . " WHERE id=" . $sets[$i]);
            }
        }
        if ($packageID != '') {
            for ($i = 0; $i < $counts; $i++) {
                mysql_query("UPDATE package_sets SET `set_order`=" . $orders[$i] . " WHERE id=" . $sets[$i]);
            }
        }

        $location = $_SERVER['HTTP_REFERER'] . '&msg=order';
        wp_redirect($location);
        exit();
    }

    $courses = LMS_SchoolDB::get_all_courses();
    $packages = LMS_SchoolDB::get_all_packages();
    $sets_url = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_PAGE, is_SSL());
    $add_sets_url = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_ADD_PAGE, is_SSL());

    if(!empty($_GET['action'])){
        if ($_GET['action'] == 'add') {
            require(LMS_PLUGIN_PATH.'admin/sets-add.php');
            exit();
        }
        if ($_GET['action'] == 'delete') {
            require(LMS_PLUGIN_PATH.'admin/sets-delete.php');
            exit();
        }

        if($_GET['action'] == 'show'){
            require(LMS_PLUGIN_PATH.'admin/sets-show.php');
            exit();
        }
    }
?>

    <!---  Sets Default Page ---->
    <br>
    <br>
	<div style="margin-top:20px; float:left">Choose Course :
        <select onchange="gotocourse(this.value)">
		    <option value="0">Select Course</option>
        <?php foreach ($courses as $course): ?>
            <option value="<?php echo $course->id;  ?>"><?php echo $course->cname;  ?></option>
        <?php endforeach ?>
        </select>
    </div>
    <div style="margin-top:20px; float:left">Choose Package :
        <select onchange="gotopackage(this.value)">
		    <option value="0">Select Package</option>
        <?php foreach ($packages as $package): ?>
    		<option value="<?php echo $package->id;  ?>"><?php echo $package->package_name;  ?></option>
        <?php endforeach; ?>
        </select>
    </div>

	<script type="text/javascript">
	function gotocourse(courseID) {	
		if(courseID ==0) {
			alert('Please choose course');
		} else {
			window.location = '<?php echo $sets_url; ?>'+'&action=show&courseID='+courseID;
		}	
	}

	function gotopackage(courseID) {	
		if(courseID ==0) {
			alert('Please choose Package');
		} else {
			window.location = '<?php echo $sets_url; ?>'+'&action=show&packageID='+courseID;
		}	
	}	
	</script>

    <?php exit(); ?>

