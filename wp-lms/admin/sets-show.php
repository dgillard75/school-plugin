<?php
/**
 * Created by PhpStorm.
 * User: dgillard75
 * Date: 5/4/2015
 * Time: 10:01 PM
 */

$add_sets_url = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_PAGE, is_SSL());
$sets_url_course = $add_sets_url."&courseID=";
$add_sets_url_pkg = $add_sets_url."&packageId=";
$del_sets_url = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_DELETE_PAGE, is_SSL());

$sets_url = LMS_PageFunctions::getAdminUrlPage(ADMIN_SETS_PAGE, is_SSL());

if( isset($_GET['courseID']) && !empty($_GET['courseID'])) {
    $courseID = $_GET['courseID'];
    $url=$sets_url."&action=add&courseID=".$courseID."&redirect=".urlencode($sets_url."&action=show&courseID=".$courseID);
    $dbresults = LMS_SchoolDB::get_course_sets($courseID);
    $cresult =  LMS_SchoolDB::retrieve_course($courseID);
    $title = $cresult['cname'];
}else if( isset($_GET['packageID']) && !empty($_GET['packageID'])) {
    $packageID = $_GET['packageID'];
    $url=$add_sets_url_pkg."&action=add&".$packageID."&redirect=".urlencode($add_sets_url);
    $dbresults = LMS_SchoolDB::get_package_sets($packageID);
    $title = "";
}else{
    wp_redirect($sets_url);
    exit();
}

?>
    <div class="wrap">
        <div id="icon-edit-pages" class="icon32"></div>
        <h2>Sets <a class="add-new-h2" href="<?php echo $url; ?>">Add New Set</a></h2>
        <h3>Course: <?php echo $title; ?></h3>
    </div>
	<?php if( isset($_GET['msg']) && !empty($_GET['msg'])) : ?>
    <div class="updated below-h2" id="message" style="padding:5px; margin:5px 0px">
        <?php if($_GET['msg'] == 'add') {
            echo "Module added Successfully";
        } elseif($_GET['msg'] == 'edit') {
            echo "Module updated Successfully";
        } elseif($_GET['msg'] == 'del') {
            echo "Module deleted Successfully";
        } elseif($_GET['msg'] == 'order') {
            echo "Order updated Successfully";
        }
        ?>
    </div>
<?php endif;?>
    <form action="" method="post">
    <p>
        <input type="submit" value="Update Order" />
    </p>
    <table class="widefat">
        <thead>
        <tr>
            <th>Order</th>
            <th>SET Name</th>
            <th>Price</th>
            <th>Discount Price</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Order</th>
            <th>SET Name</th>
            <th>Price</th>
            <th>Discount Price</th>
        </tr>
        </tfoot>
        <tbody>
        <?php foreach ($dbresults as $row1) : ?>
        <tr>
            <td>
                <input type='text' name='orders[]' value='<?php echo $row1->set_order; ?>' style='width:40px'>
                <input type='hidden' name='sets[]' value='<?php echo $row1->id; ?>' style='width:40px'>
            </td>
            <td>
                <?php echo $row1->set_name; ?>
                <div class="row-actions"><span class="edit"><a href="<?echo $url."&setId=".$row1->id; ?>"Edit</a>|</span>
                    <span class="trash"><a href="<? echo $del_sets_url."&delId=".$row1->id; ?>" onclick='return confirm(\" Are you sure! You want to delete this record \")'>Delete</a> </span>
                </div>
            </td>
            <td><?php echo $row1->set_price; ?></td>
            <td><?php echo $row1->discount_price; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </form>

