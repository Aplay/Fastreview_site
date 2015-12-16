<?php
	/* if($data->lastlog){
		echo $data->lastlog->datetime;
		if($data->lastlog->userid){
			echo '<br>'.$data->lastlog->userid->username;
		}
		$ar = ActionLog::getEventNames();
		echo ' '.$ar[$data->lastlog->event];
	} */

	echo $data->updated_date;
	if(!empty($data->lasteditor)){
		echo '<br>'.$data->lasteditorid->username;
	}
?>