<?php
    $waba = $_REQUEST["waba"];
    if ( 0 < $_FILES['keyfile']['error'] ) {
        echo 'Error: ' . $_FILES['keyfile']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['keyfile']['tmp_name'], "keys/".$waba.".key");
    	echo "keys/".$waba.".key";
    }
?>

