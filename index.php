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
<title>SADAA Trials</title>

<html>
<!--
    <header>
            <h1>SADAA Trials</h1>
            <h3>To help make SADAA admin a breeze</h3>
    </header>
-->
    <body>
        <div>
            <nav class="navbar navbar-default navigation-clean-button">
                <div class="container">
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
                                        <li class="divider"></li>
                                        <li role="presentation"><a href="pages/handlerreg.php">Handler Registration</a></li>
                                        <li role="presentation"><a href="#">Dog Registration</a></li>
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
                                    <a class="btn btn-default action-button" role="button" href="pages/logout.php?logout">Logout</a>
                        <?php
                                }
                        ?>
                        </p>
                    </div>
                </div>
            </nav>
        </div>
<!--
		<div class="footer-basic">
			<footer>
				<div class="social">
					<a href="#"><i class="icon ion-social-instagram"></i></a>
					<a href="#"><i class="icon ion-social-snapchat"></i></a>
					<a href="#"><i class="icon ion-social-twitter"></i></a>
					<a href="#"><i class="icon ion-social-facebook"></i></a>
				</div>
				<ul class="list-inline">
					<li><a href="#">Home</a></li>
					<li><a href="#">Services</a></li>
					<li><a href="#">About</a></li>
					<li><a href="#">Terms</a></li>
					<li><a href="#">Privacy Policy</a></li>
				</ul>
				<p class="copyright">Developed by Billy Knox © 2017</p>
			</footer>
		</div>
-->
        <script src="scripts/jquery.min.js"></script>
        <script src="scripts/bootstrap.min.js"></script>
    </body>
</html>