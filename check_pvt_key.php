<?php
	$waba = $_GET["waba"];
	if(file_exists("keys/".$waba.".key"))
		echo "Yes";
	else
		echo "None";

?>
