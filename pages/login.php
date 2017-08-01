<?php
    session_start();
    require_once '../process/dbconnect.php';

    // Check connection
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
        exit;
    } 
/*
    if (isset($_SESSION['userSession'])!="") {
            header("Location: ../index.php");
            exit;
    }
*/	
    if (isset($_POST['btn-login'])) {
        // Prevent SQL injections & clear user invalid inputs
        $username = trim($_POST['username']);
        $username = strip_tags($username);
        $username = htmlspecialchars($username);
        $username = $dbconn->real_escape_string($username);
		
        $password = trim($_POST['password']);
        $password = strip_tags($password);
        $password = htmlspecialchars($password);
        $password = $dbconn->real_escape_string($password);

        $query = $dbconn->query("SELECT * FROM users WHERE username='$username'");
        $row = $query->fetch_array();
        $count = $query->num_rows;

        //if (password_verify($password,$row['password']) && $count==1) {
        if ($count==1) {
            $isCorrectPass = password_verify($password,$row['password']);
            $isActiveUser  = $row['active'];
            
            //Is the user an active user
            if ($isActiveUser == 'yes' && $isCorrectPass){
                $_SESSION['userSession'] = $row['id'];
                $_SESSION['userLogin']  = $row['id'];
                $_SESSION['firstname']  = $row['first_name'];
                $_SESSION['lastname']   = $row['last_name'];
                $_SESSION['email']      = $row['email'];
				
                //Check if the logged in user has a handler profile.
                $queryProfile = $dbconn->query("SELECT * FROM handlers WHERE id_login='{$_SESSION['userLogin']}'");
                $row = $queryProfile->fetch_array();
                $countProfile = $queryProfile->num_rows;

                //If no handler profile is found then go to the handler profile page.
                if ($countProfile == 0){
                    $_SESSION['hasHandlerProfile'] = "no";
                    header("Location: handlerreg.php");
                } else {
                    $_SESSION['hasHandlerProfile'] = "yes";
                    header("Location: ../index.php");
                }
            } elseif ($isActiveUser == 'no') {
                $msg = "<div class='alert alert-danger'>
                            <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                            The user account is not active!
                        </div>";
            } else {
                    $msg = "<div class='alert alert-danger'>
                                <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Invalid Password !
                            </div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Invalid Username or Password !
                    </div>";
        }
        $dbconn->close();
    }
?>

<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/Navigation-with-Button1.css">
<link rel="stylesheet" href="../css/Pretty-Login-Form.css">
<link rel="stylesheet" href="../css/styles.css">
<title>SADAA Trials</title>

<html>
<!--
<head>
</head>
-->
<body>
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
                    <li role="presentation"><a href="../index.php">Home </a></li>
                    <!--<li role="presentation"><a href="#">Second Item</a></li>-->
            <?php
                    if(isset($_SESSION['userSession'])) {
            ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">Profile <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="divider"></li>
                            <li role="presentation"><a href="handlerreg.php">Handler Registration</a></li>
                            <li role="presentation"><a href="#">Dog Registration</a></li>
                            <!--<li role="presentation"><a href="#">Third Item</a></li>-->
                        </ul>
                    </li>
            <?php
                    }
            ?>
                    <li role="presentation"><a href="about.php">About </a></li>
                </ul>
                <p class="navbar-text navbar-right actions">
                    <!--<a class="navbar-link login" href="#">Log In</a>-->
            <?php //If session is not yet a valid logged in session then display
                    if(!isset($_SESSION['userSession'])) {
            ?>
                        <!--<a class="navbar-link login" href="pages/login.php">Log In</a>-->
                        <a class="btn btn-default action-button" role="button" href="userreg.php">Sign Up</a>
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
        </div>
    </nav>
	
    <div class="row login-form">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center form-heading">Login Form</h2>
            <form class="custom-form" method="post" id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php
                if(isset($msg)){
                    echo $msg;
                }
            ?>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="text" name="username" placeholder="Username" autofocus="" required>
                        <!--<input class="form-control" type="email" name="email" placeholder="Email" autofocus="" required>-->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                </div>
<!--
                <div class="form-group">
                    <div class="dropdown">
                        <button class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Dropdown <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><a href="#">First Item</a></li>
                            <li role="presentation"><a href="#">Second Item</a></li>
                            <li role="presentation"><a href="#">Third Item</a></li>
                        </ul>
                    </div>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox">Remember me</label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="radio-option">Radio option 1</label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="radio-option" checked="">Radio option 2</label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="radio-option">Radio option 3</label>
                </div>
-->
                <button type="submit" class="btn btn-default btn-block submit-button" name="btn-login" id="btn-login">Log In</button>
            </form>
            <div>
                <!--<a href="#">Forgot Password?</a>-->
            </div>
        </div>
    </div>
    <script src="../scripts/jquery.min.js"></script>
    <script src="../scripts/bootstrap.min.js"></script>
</body>

</html>