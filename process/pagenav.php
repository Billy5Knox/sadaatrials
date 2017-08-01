<?php
	session_start();
	require_once 'process/dbconnect.php';
	//include ('pages/login.php');
?>

<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/Navigation-with-Button1.css">
<link rel="stylesheet" href="css/Pretty-Login-Form.css">
<link rel="stylesheet" href="css/styles.css">

					<div class="navbar-header">
						<a class="navbar-brand navbar-link" href="#">SADAA Trials</a>
						<button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse" id="navcol-1">
						<ul class="nav navbar-nav">
							<!--<li class="active" role="presentation"><a href="#">Home </a></li>-->
							<!--<li role="presentation"><a href="#">Second Item</a></li>-->
						<?php
							if(isset($_SESSION['userSession'])) {
						?>
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Profile <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										<li role="presentation"><a href="#">Handler</a></li>
										<li role="presentation"><a href="#">Dog</a></li>
										<!--<li role="presentation"><a href="#">Third Item</a></li>-->
									</ul>
								</li>
						<?php
							}
						?>
							<li role="presentation"><a href="pages/about.php">About </a></li>
						</ul>
						<p class="navbar-text navbar-right actions">
						<?php //If session is not yet a valid logged in session then display
							if(!isset($_SESSION['userSession'])) {
						?>
								<a class="navbar-link login" href="pages/login.php">Log In</a>
								<a class="btn btn-default action-button" role="button" href="pages/userreg.php">Sign Up</a>
						<?php
							}
						?>
						
						<?php //If session is a valid logged in session then display
							if(isset($_SESSION['userSession'])) {
						?>
								<a class="btn btn-default action-button" role="button" href="logout.php?logout">Logout</a>
						<?php
							}
						?>
						</p>
					</div>
