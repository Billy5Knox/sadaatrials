<?php
	session_start();

	if (!isset($_SESSION['userSession'])) {
		header("Location: about.php");
	} else if (isset($_SESSION['userSession'])!="") {
		header("Location: about.php");
	}

	if (isset($_GET['logout'])) {
		unset($_SESSION['userSession']);
		header("Location: about.php");
		session_destroy();
	}
?>