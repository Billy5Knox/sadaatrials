<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "sadaatrials";
	
    $dbconn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
    if ($dbconn->connect_errno) {
        die("ERROR : -> ".$dbconn->connect_error);
    } else {
        return $dbconn;
    }
?>