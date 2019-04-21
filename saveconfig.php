<?php
	$message = $_POST["message"];
	$appid = $_POST["appid"];
	$waba = $_POST["waba"];
	$config = '{"appid":"'.$appid.'","message":'.json_encode($message).'}';
	file_put_contents("config/".$waba.".json",$config);
?>
