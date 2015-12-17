<div class="poll-results">
<?php
  foreach ($model->choices as $choice) {
  	if($model->totalVotes > 0){
  		$percent = 100 * round($choice->votes / $model->totalVotes, 3);
  	} else {
  		$percent = 0;
  	}
    $this->renderPartial('application.modules.poll.views.pollchoice._resultsChoice', array(
      'choice' => $choice,
      'percent' =>$percent,
      'voteCount' => $choice->votes,
    ));
  }
?>
</div>
