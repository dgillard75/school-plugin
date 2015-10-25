<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 9/25/15
 * Time: 3:15 PM
 */

$modules = LMS_DBFunctions::retrieve_modules_and_lessons(32);
?>

<?php foreach($modules as $m) : ?>
    <div id="col-1"><strong><?php echo $m[MODULES_TBL_MODULE_NAME]; ?></strong>
        <ul>
            <?php foreach($m['units'] as $unit) : ?>
                <li><?php echo $unit['title'] ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>


