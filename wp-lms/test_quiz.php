<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 8/12/15
 * Time: 1:29 PM
 */

include_once(LMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");

$url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url1=$url."&action=addNew&redirect=".urlencode($url);
$quizeurl=$url."&action=questions&redirect=".urlencode($url);

$quizzes = LMS_DBFunctions::get_all_quizzes();
// LMS_Log::print_r($quizzes);
?>

<h2>Quiz &amp; Survey Summary</h2>

<form id="wpcw_quizzes_search_box" method="get" action="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary">
    <p class="search-box">
        <label class="screen-reader-text" for="wpcw_quizzes_search_input">Search Quizzes</label>
        <input id="wpcw_quizzes_search_input" type="text" value="" name="s"/>
        <input class="button" type="submit" value="Search Quizzes"/>

        <input type="hidden" name="page" value="WPCW_showPage_QuizSummary" />
    </p>
</form>
<br/><br/>
<div class="tablenav wpcw_tbl_paging"><div class="wpbs_paging tablenav-pages"><span class="displaying-num">Displaying 1 &ndash; 50 of 109</span><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=1" class="page-numbers current" data-pagenum="1">1</a><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=2" class="page-numbers " data-pagenum="2">2</a><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=3" class="page-numbers " data-pagenum="3">3</a><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=2" class="next page-numbers" data-pagenum="2">&raquo;</a></div></div>
<table id="wpcw_tbl_quiz_summary" class="widefat wpcw_tbl" class="widefat">
    <thead>
    <tr>
        <th id="quiz_id" scope="col" class="sorted desc"><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=&order=asc&orderby=quiz_id"><span>ID</span><span class="sorting-indicator"></span></a></th>
        <th id="quiz_title" scope="col" class="sortable"><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=&order=asc&orderby=quiz_title"><span>Quiz Title</span><span class="sorting-indicator"></span></a></th>
        <th id="associated_unit" scope="col" >Associated Unit</th>
        <th id="total_questions" scope="col" >Questions</th>
        <th id="actions" scope="col" >Actions</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th id="quiz_id" scope="col" class="sorted desc"><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=&order=asc&orderby=quiz_id"><span>ID</span><span class="sorting-indicator"></span></a></th>
        <th id="quiz_title" scope="col" class="sortable"><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=&order=asc&orderby=quiz_title"><span>Quiz Title</span><span class="sorting-indicator"></span></a></th>
        <th id="associated_unit" scope="col" >Associated Unit</th>
        <th id="total_questions" scope="col" >Questions</th>
        <th id="actions" scope="col" >Actions</th>
    </tr>
    </tfoot>
    <tbody>
    <tr class="alternate">
        <?php foreach($quizzes as $quiz) : ?>
        <td class="quiz_id"><?php echo $quiz[QUIZ_TABLE_ID] ?></td>
        <td class="quiz_title"><b><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_ModifyQuiz&quiz_id=115"><?php echo $quiz[QUIZ_TABLE_TITLE] ?></a></b></td>
        <td class="associated_unit">
            <span class="associated_unit_unit"><b>Unit</b>: <a href="http://testenv.com/school/module-5/cgh-m5-quiz/" target="_blank" title="View  'Quiz: Module 5'...">Quiz: Module 5</a></span>
            <span class="associated_unit_course"><b>Course:</b> <a href="admin.php?page=WPCW_showPage_ModifyCourse&course_id=1" title="Edit  'How to Become a Celebrity Green Housekeeper'...">How to Become a Celebrity Green Housekeeper</a></span>
        </td>
        <td class="total_questions wpcw_center">12</td>
        <td class="actions">
            <ul class="wpcw_action_link_list">
                <li><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_ModifyQuiz&quiz_id=115" class="button-primary">Edit</a></li>
                <li><a href="https://testenv.com/school/wp-admin/admin.php?page=WPCW_showPage_QuizSummary&pagenum=&action=delete&quiz_id=115" class="button-secondary wpcw_action_link_delete_quiz wpcw_action_link_delete" rel="Are you sure you wish to delete this quiz?">Delete</a></li>
            </ul>
        </td>
        <?php break; endforeach; ?>
    </tr>
    </tbody>
</table>


<form action="" method="post">

    <div class="wrap">
        <div id="icon-edit-pages" class="icon32"></div>
        <h2><?php echo $modulename; ?></h2>

    </div>

    <?php if( isset($_GET['msg']) && !empty($_GET['msg'])) { ?>
        <div class="updated below-h2" id="message" style="padding:5px; margin:5px 0px">
            <?php if($_GET['msg'] == 'add') {
                echo "Question added Successfully";
            } elseif($_GET['msg'] == 'edit') {
                echo "Question updated Successfully";
            } elseif($_GET['msg'] == 'del') {
                echo "Question deleted Successfully";
            }
            ?>
        </div>
    <?php }
    $quizzes = LMS_DBFunctions::get_quizzes(40);
    LMS_Log::print_r($quizzes);
    //$qer1="select * from quizes where module_id=".$_GET['moduleId']." order by quize_order";
    //$re1=mysql_query($qer1);
    //$row1=mysql_fetch_array($re1);
    ?>
    <p>
        Add New Quiz :

        <input type="text" size="60" name="newquizes" id="newquizes" value="<?php echo $quizzes['title'];  ?>" placeholder="Enter Quiz Name"/>
        <input type='text' name='duration' value='<?php echo $quizzes['duration']; ?>' id="duration" size="40" placeholder="Enter Duration">&nbsp;&nbsp;
        <input type="button" size="50" id="addnewquizes" value="<?php if(trim($_GET['moduleId']) == trim($quizzes['module_id'])) { echo 'Update Quiz'; } else { echo 'Add New Quiz'; } ?>" />
        <input type="hidden" id="moduleID" value="<?php echo $_GET['moduleId']; ?>" />

    </p>
    <?php if($quizzes['id'] != '') { ?>
        <div class="wrap" id="addques" >
            <h2><a class="add-new-h2" href="<?php echo $quizeurl."&quizeId=".$quizzes['id']; ?>">Add New Questions</a>	</h2>
            <p><input type="submit" value="Update Order" /></p>
        </div>
    <?php } ?>
    <div class="wrap" id="addques" style="display:none;">
        <h2><a class="add-new-h2" href="<?php echo $quizeurl."&quizeId=".$quizzes['id']; ?>">Add New Questions</a>	</h2>
        <p><input type="submit" value="Update Order" /></p>
    </div>
    <table class="widefat">
        <thead>
        <tr>
            <th>Order</th>
            <th>Question</th>
            <th>Answers</th>


        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Order</th>
            <th>Question</th>
            <th>Answers</th>

        </tr>
        </tfoot>
        <tbody id="quizestable">
        <?php
        $questions = LMS_DBFunctions::get_quiz_questions($quizzes['id']);
        foreach($questions as $row1){
            $unserilizeAns = unserialize($row1['answers']);
            if(empty($unserilizeAns)) {
                $unserilizeAns = (array)json_decode($row1['answers']);
            }
            if($row1['correct_ans']=='A'){
                $Allans = '(A). <strong>'.$unserilizeAns['ansa'].'</strong><br/>(B). '.$unserilizeAns['ansb'].'<br/>(C). '.$unserilizeAns['ansc'].'<br/>(D). '.$unserilizeAns['ansd'];
            }
            elseif($row1['correct_ans']=='B'){ $Allans = '(A). '.$unserilizeAns['ansa'].'<br/><strong>(B). '.$unserilizeAns['ansb'].'</strong><br/>(C). '.$unserilizeAns['ansc'].'<br/>(D). '.$unserilizeAns['ansd']; }
            elseif($row1['correct_ans']=='C'){ $Allans = '(A). '.$unserilizeAns['ansa'].'<br/>(B). '.$unserilizeAns['ansb'].'<br/><strong>(C). '.$unserilizeAns['ansc'].'</strong><br/>(D). '.$unserilizeAns['ansd']; }
            elseif($row1['correct_ans']=='D'){ $Allans = '(A). '.$unserilizeAns['ansa'].'<br/>(B). '.$unserilizeAns['ansb'].'<br/>(C). '.$unserilizeAns['ansc'].'<br/><strong> (D). '.$unserilizeAns['ansd'].'</strong>'; }
            else{ $Allans = '(A). '.$unserilizeAns['ansa'].'<br/>(B). '.$unserilizeAns['ansb'].'<br/>(C). '.$unserilizeAns['ansc'].'<br/>(D). '.$unserilizeAns['ansd']; };

            echo "<tr>
				<td>
					<input type='text' name='orders[]' value='".$row1['ques_order']."' style='width:40px'>
					<input type='hidden' name='questions[]' value='".$row1['qid']."' style='width:40px'>
				</td>
		<td>".$row1['ques']."
				<div class=\"row-actions\">
					<span class=\"edit\"><a href='".$quizeurl."&quesid=".$row1['qid']."'>Edit</a>|</span>
					<span class=\"trash\"><a href='".$url."&delId=".$row1['qid']."' onclick='return confirm(\" Are you sure! You want to delete this record \")'>Delete</a></span>


				</div>
			</td>";
            echo "<td>".$Allans."</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
