<style>
	<?php include(LMS_PLUGIN_PATH."css/quiz.css"); ?>
</style>
<script>
	<?php include(LMS_PLUGIN_PATH."js/jq-all-debug.js"); ?>
	<?php include(LMS_PLUGIN_PATH."js/jQuiz.js"); ?>
</script>

<div id="main-col">
	<div id="content">
		<div class="entry-container fix">
			<div class="entry fix">
				<article class="whitearea">
					<form action="#" method="post" onsubmit="return areyousure()">
						<?php 	$i = 1; $count = count($questions);
							foreach($questions as $question) : ?>
							<div class="questionContainer <?php if($i>1) { echo 'hide'; } ?> radius">
								<div class="question"><b>Question <?php echo $question[QUIZ_QUEST_TABLE_QORDER]; ?>: </b><?php echo $question[QUIZ_QUEST_TABLE_QUES]; ?></div>
								<input type="hidden" name="list_of_question_ids[]" value="<?php echo $question[QUIZ_QUEST_TABLE_QUES_ID]; ?>" />
								<?php
								$answers =  (array)json_decode($question[QUIZ_QUEST_TABLE_ANS]);
								if(!isset($answers['ansa']) && !isset($answers['ansb']) && !isset($answers['ansc']) && !isset($answers['ansd'])) {
									$answers = unserialize($question['answers']);
								}
								$ansa = 'A';
								$ansb = 'B';
								$ansc = 'C';
								$ansd = 'D';
								?>

								<div class="answers">
									<ul>
										<?php foreach((array) $answers as $k=>$ans) { ?>
											<li>
												<label>
													<input type="radio" name="ans[<?php echo $question[QUIZ_QUEST_TABLE_QUES_ID]; ?>]" id="<?php echo 'q'.$question[QUIZ_QUEST_TABLE_QUES_ID].'-'.$k; ?>" value="<?php echo $$k; ?>" />
												<!--
												<input type="radio" name="ans_<?php echo $question[QUIZ_QUEST_TABLE_QUES_ID]; ?>" id="<?php echo 'q'.$question[QUIZ_QUEST_TABLE_QUES_ID].'-'.$k; ?>" value="<?php echo $$k; ?>" />
												---->
																<?php echo $ans; ?>
												</label>
											</li>

										<?php } ?>
									</ul>
								</div>
								<div class="btnContainer">
									<div class="prev">
										<?php if($i>1) { ?>
											<a class="btnPrev">&lt;&lt; Prev</a>
										<?php }?>
									</div>

									<div class="next">
										<?php if($i==$count) { ?>
											<input type="submit" name="submit" value="finish">
										<?php } else { ?>
											<a class="btnNext">Next &gt;&gt;</a>
										<?php } ?>
									</div>

									<div class="clear"></div>
								</div>
							</div>

							<?php $i++; endforeach; ?>

						<input type="hidden" name="totalques" value="<?php echo $count; ?>" />
						<input type="hidden" name="quizId" value="<?php echo $_GET["quizID"]; ?>" />
						<input type="hidden" name="quiz_trans_id" value="<?php echo $_SESSION["Quiz Progress"]["quiz_trans_id"]; ?>" />
					</form>
				</article>
			</div>
		</div>
	</div>
</div>
<script>
	function areyousure() {
		var r = confirm('Please review your all answer. If you are sure than click OK!');
		if(r) {
			return true;
		} else {
			return false;
		}
	}
</script>
