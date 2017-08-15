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
        $row   = $query->fetch_array();
        $count = $query->num_rows;

        //if (password_verify($password,$row['password']) && $count==1) {
        if ($count==1) {
            $isCorrectPass = password_verify($password,$row['password']);
            $isActiveUser  = $row['active'];
            //Is the user an active user
            if ($isActiveUser == 'yes') {   //If the user account is active
                if ($isCorrectPass) {   //If the correct password was used
                    $_SESSION['userSession'] = $row['id'];
                    $_SESSION['userLogin']  = $row['id'];
                    $_SESSION['firstname']  = $row['first_name'];
                    $_SESSION['lastname']   = $row['last_name'];
                    $_SESSION['email']      = $row['email'];

                    //Check if the logged in user has a handler profile.
                    $queryHandlerProfile = $dbconn->query("SELECT * FROM handlers WHERE id_login='{$_SESSION['userLogin']}'");
                    $rowHandler     = $queryHandlerProfile->fetch_array();
                    $countHandler   = $queryHandlerProfile->num_rows;
                    
                    //If no handler profile is found then go to the handler profile page.
                    if ($countHandler == 0){    //If no handler registration record was found
                        $_SESSION['hasHandlerProfile'] = "no";
                        header("Location: handlerprofile.php");
                    } else {    //If a handler profile record was found
                        $_SESSION['hasHandlerProfile'] = "yes";
                        $_SESSION['id_login']   = $row['id_login'];
                        header("Location: ../index.php");
                    }
                } else {    //Wrong password was entered
                    $msg = "<div class='alert alert-danger'>
                                <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Invalid Password !
                            </div>";
                }
            } else {    //If the user account is not active
                $msg = "<div class='alert alert-danger'>
                            <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                            The user account is not active!
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
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="../css/styles.css">

<title>SADAA Trials</title>

<html>
<body>
    <!-- Top navigation bar of the page -->
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img alt="SADAA Trials" src="../img/logo-sm.jpg" class="img-responsive img-rounded"/>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="../index.php">Home <span class="sr-only"></span></a></li>
                <?php
                    if(isset($_SESSION['userSession'])) {
                ?>
                        <li class="dropdown">   <!-- Dropdown for profile information -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="mainprofile.php">Summary</a></li>
                                <li class="divider"></li>
                            <?php if($_SESSION['hasHandlerProfile'] == 'no') { ?>
                                <li><a href="handlerprofile.php">Handler</a></li>
                            <?php } ?>
                                <li><a href="pages/dogprofile.php">New Dog</a></li>
                            </ul>
                        </li>
                <?php
                    }
                ?>
                    <li><a href="about.php">About</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                <?php   //If Admin user then allow these options
                    if(isset($_SESSION['userSession'])) {
                ?>
                        <!-- Dropdown for profile information -->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="userinfo.php">User Info</a></li>
                            </ul>
                        </li>
                <?php
                    }
                ?>
                    
                <?php //If session is not yet a valid logged in session then display
                    if(!isset($_SESSION['userSession'])) {
                ?>
                        <!--<li><a href="login.php">Log In</a></li>-->
                        <li><a href="userreg.php">Sign Up</a></li>
                <?php
                    }
                ?>
                <?php //If session is a valid logged in session then display
                    if(isset($_SESSION['userSession'])) {
                ?>
                        <li><a href="logout.php?logout">Logout</a></li>
                <?php
                    }
                ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav><!-- nav class -->
	
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <h2 class="text-center form-heading">Login</h2>
            <form class="custom-form" method="post" id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php
                if(isset($msg)){
                    echo $msg;
                }
            ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="text" name="username" placeholder="Username" autofocus="" required>
                                <!--<input class="form-control" type="email" name="email" placeholder="Email" autofocus="" required>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-default btn-block submit-button" name="btn-login" id="btn-login">Log In</button>
                    </div>
                </div>
            </form>
            <div>
                <!--<a href="#">Forgot Password?</a>-->
            </div>
        </div>
    </div><!-- container -->

    <!-- Scripts to include in the page -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</body>

</html>