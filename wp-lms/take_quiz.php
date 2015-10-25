<?php
/**
 * Created by PhpStorm.
 * User: celeb
 * Date: 8/21/15
 * Time: 3:03 PM
 */

include_once(LMS_PLUGIN_PATH . "class/LMS_DBFunctions.class.php");
include_once(LMS_PLUGIN_PATH . "class/LMS_PageFunctions.class.php");

//$moduleID = $_SESSION['Quiz_Module_Id'];
//$courseID = $_SESSION['Quiz_Course_Id'];

function validate(){
	// is user logged in

	//is quiz_id present

	// do you user have permission to take quiz (Validate user is enrolled)

}

/**
 * Retreive All Correct answers from database for a given quiz_id.  Returns array of answers
 *
 * @param $quiz_id
 *
 * @return array
 */
function get_all_correct_answers($quiz_id){
	$correct_answers = array();
	$ans = LMS_DBFunctions::select_query(QUIZ_QUEST_TABLE, "WHERE ".QUIZ_QUEST_TABLE_QUIZ_ID." = ".$quiz_id." ORDER BY ". QUIZ_QUEST_TABLE_QORDER);
	foreach($ans as $value){
		$correct_answers[$value[QUIZ_QUEST_TABLE_QUES_ID]] = $value[QUIZ_QUEST_TABLE_CANS];
	}
	return $correct_answers;
}

/**
 * Log start of quiz into database
 *
 * @param $quizID
 *
 * @return mixed
 */
function start_quiz_progress_record($quizID){
	$data[USER_QUIZ_PROGRESS_TABLE_UID] = get_current_user_id();
	$data[USER_QUIZ_PROGRESS_TABLE_QID] = $quizID;
	$quizdata = LMS_DBFunctions::select_query(QUIZ_TABLE,"WHERE ".QUIZ_TABLE_ID."=".$quizID,true);
	$data[USER_QUIZ_PROGRESS_TABLE_MID] = $quizdata[QUIZ_TABLE_MID];

	return LMS_DBFunctions::add_user_progress_quiz($data);
}

/**
 * Mark quiz as completed and update quiz results based of transaction id of quiz.
 *
 * @param $trans_id
 * @param $quiz_results
 *
 * @return mixed
 */
function complete_quiz_progress_record($trans_id, $quiz_results){
	$data[USER_QUIZ_PROGRESS_TABLE_ID] = $trans_id;
	$data[USER_QUIZ_PROGRESS_TABLE_CDATE] = date('Y-m-d H:i:s');
	$data[USER_QUIZ_PROGRESS_TABLE_CANS] = $quiz_results["correct"];
	$data[USER_QUIZ_PROGRESS_TABLE_GRADE] = $quiz_results["grade"];
	$data[USER_QUIZ_PROGRESS_TABLE_QUEST_TOTAL] = $quiz_results["total"];

	if($quiz_results["grade"] > 60){
		$data[USER_QUIZ_PROGRESS_TABLE_STATUS] = 'PASS';
	}else{
		$data[USER_QUIZ_PROGRESS_TABLE_STATUS] = 'FAIL';
	}

	return LMS_DBFunctions::update_user_progress_quiz($data);
}


function initiate_quiz($qid){


	$whereClause = "WHERE ".USER_QUIZ_PROGRESS_TABLE_UID."=".get_current_user_id()." AND ".USER_QUIZ_PROGRESS_TABLE_QID."=".$qid." AND quiz_status='IN-PROGRESS'";

	//If Quiz is already started use that quiz progress, do not create another entry unless its a retake of the quiz
	// - There should only be one IN-PROGRESS quiz been taken by a given user for the same quiz_id
	$curr_quiz_progress = LMS_DBFunctions::select_query(USER_QUIZ_PROGRESS_TABLE,$whereClause,true);

	if(!$curr_quiz_progress){
		$quiz_transaction_id = start_quiz_progress_record($qid);
	}else{
		$quiz_transaction_id = $curr_quiz_progress[USER_QUIZ_PROGRESS_TABLE_ID];
	}

	$quizdata = LMS_DBFunctions::select_query(QUIZ_TABLE,"WHERE ".QUIZ_TABLE_ID."=".$qid,true);
	if($quizdata > 0){
		$session_array =  array('quiz_trans_id' => $quiz_transaction_id,
								'quiz_id'=>$qid,
		                        'quiz_title'=>$quizdata[QUIZ_TABLE_TITLE]);
		$_SESSION["Quiz Progress"] = $session_array;
		LMS_Log::print_r($_SESSION);
	}
}

function display_quiz(){
	$quizId=1;
	$questions = LMS_DBFunctions::get_quiz_questions($quizId);
	include_once(WPLMS_PLUGIN_PATH . "/shortcodes/templates/show_quiz_template.php");
}

function grade_quiz(){
	$quiz_results = array();
	$quiz_results["correct"]  = 0;
	$quiz_results["wrong"]  = 0;
	$quiz_results["total"] = 0;

	$correct_answers = get_all_correct_answers($_POST["quizId"]);
	LMS_Log::print_r($correct_answers);
	$student_answers = $_POST["ans"];
	foreach($student_answers as $qid=>$answer){
		if($answer == $correct_answers[$qid]){
			$quiz_results["correct"]++;
		}else{
			$quiz_results["wrong"]++;
		}
		$quiz_results["total"]++;
	}

	$quiz_results["grade"]  = number_format(($quiz_results["correct"] / $quiz_results["total"])*100,2);

	LMS_Log::print_r($quiz_results);
	return $quiz_results;
}

// Heart of Shortcode below here on whats going on.

if(LMS_PageFunctions::hasFormBeenSubmitted()){
	//Quiz have been submitted. Get Session and retrieve Transation ID

	LMS_Log::print_r($_POST);

	//GradeQuiz
	$quiz_results = grade_quiz();

	//Store Results in Database
	LMS_Log::print_r($_SESSION);

	complete_quiz_progress_record($_POST['quiz_trans_id'],$quiz_results);

}else{
	//Initiate Quiz
	initiate_quiz($_GET["quizID"]);

	//Show Quiz
	display_quiz();

	exit();
}

?>
