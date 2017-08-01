<?php
    session_start();
    require_once '../process/dbconnect.php';
    //include ('pages/login.php');

    if (isset($_POST['btn-forgot'])) {
        $emailSearch = $dbconn->real_escape_string($_POST['email']);
        $emailQuery = $dbconn->query("SELECT * FROM users WHERE email='$emailSearch'");
        $row = $emailQuery->fetch_array();
        $countProfile = $emailQuery->num_rows;
        
        if (!empty($_POST['emailSearch']) && ($countProfile != 0)){
            $_SESSION['info'] = $_POST['email'];
            header("Location: info.php");
        }
        
        if (empty($_POST['email'])) {
            $ree  = "What is your email?";
        } elseif ($countProfile < 1) {
            $ree = "That email does not exist";
        }
                
        if ($emailQuery->num-rows > 0) {
            $str = "12314lkjlkahaskiiel13421093092ss801";
            $str = str_shuffle($str);
            $str = substr($str, 0, 10);
            $url = "http://localhost/sadaa/pages/forgotpassword.php?token=$str&email=$emailSearch";
            
            mail($emailSearch, "Reset Password", "To reset password, please visit this link: $url",
                "From: admin@sadaatrials.co.za\r\n");
        } else {
            $msg = "<div class='alert alert-danger'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Email was not found!
                    </div>";
        }
    }
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
                                    <li role="presentation"><a href="pages/handler.php">Handler</a></li>
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
                                <a class="btn btn-default action-button" role="button" href="pages/logout.php?logout">Logout</a>
                        <?php
                            }
                        ?>
                        </p>
                    </div>
                </div>
            </nav>
        </div>
        <div class="row login-form">
            <div class="col-md-4 col-md-offset-4">
                <h2 class="text-center form-heading">Forgot Password</h2>
                <form class="custom-form" method="post" id="forgot-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <?php
                    if(isset($msg)){
                        echo $msg;
                    }
                ?>
                    <div class="form-group">
                        <div class="col-lg-13 input-column">
                            <input class="form-control" type="text" name="email" placeholder="Email" autofocus="" required>
                            <!--<input class="form-control" type="email" name="email" placeholder="Email" autofocus="" required>-->
                        </div>
                    </div>
<!--
                    <div class="form-group">
                        <div class="col-lg-13 input-column">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                        </div>
                    </div>
-->
                    <button type="submit" class="btn btn-default btn-block submit-button" name="btn-forgot" id="btn-forgot">Request Password</button>
                </form>
            </div>
        </div>
        <script src="scripts/jquery.min.js"></script>
        <script src="scripts/bootstrap.min.js"></script>
    </body>
</html>