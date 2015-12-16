<?php

class SendMail {
 /**
 * Отправка почты
 * @param str $to
 * @param str $subject
 * @param str $message
 */
 static function send($to,$subject,$message,$useHtml = false, $smtp = false) {

 if($smtp){ // send mail through smtp. need add smtp params to config
 	$__smtp= Yii::app()->params['smtp'];

 	Yii::app()->mail->IsSMTP();
 	Yii::app()->mail->Subject = $subject;
 	Yii::app()->mail->Body = $message;
 	Yii::app()->mail->Host = $__smtp['host'];
 	Yii::app()->mail->SMTPDebug = $__smtp['debug'];
 	Yii::app()->mail->SMTPAuth = $__smtp['auth'];
 	Yii::app()->mail->Host = $__smtp['host'];
 	Yii::app()->mail->Port = $__smtp['port'];
 	Yii::app()->mail->Username = $__smtp['username'];
 	Yii::app()->mail->Password = $__smtp['password'];
 	Yii::app()->mail->CharSet = $__smtp['charset'];

 	Yii::app()->mail->From = $__smtp['from'];
 	Yii::app()->mail->FromName = $__smtp['fromname'];

 	if ( is_array($to) ) {
 		$arrayToSend = array();
 		foreach ($to as $value) 
 		{
 			Yii::app()->mail->ClearAllRecipients();
 			Yii::app()->mail->AddAddress($value);
 			if(Yii::app()->mail->Send()){
 				$arrayToSend[$value] = true;
 			}
 		}
 		return $arrayToSend;
 	} else {
 		Yii::app()->mail->AddAddress($to);
 		if(Yii::app()->mail->Send()){
 			return true;
 		}
 	}

 } else {

 	$mailer           = Yii::app()->mail;
 	$mailer->From     = 'noreply@'.Yii::app()->params['serverName'];
 	$mailer->FromName = Yii::app()->name;
 	$mailer->Subject  = $subject;
 	$mailer->Body     = $message;
 	$mailer->isHtml((boolean)$useHtml);
 	if ( is_array($to) ) {
 		$arrayToSend = array();
 		foreach ($to as $value) 
 		{
 			$mailer->ClearAllRecipients();
 			$mailer->AddAddress($value);
 			if($mailer->Send()){
 				$arrayToSend[$value] = true;
 			}
 		}
 		return $arrayToSend;
 	} else {
 		$mailer->AddAddress($to);
 		if($mailer->Send()){
 			return true;
 		}
 	}

 }
 return false;

}
}