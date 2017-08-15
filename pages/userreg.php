<?php
    session_start();
    require_once '../process/dbconnect.php';

    // Check connection
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
        exit;
    } 
    //echo "Connected successfully";	

    if (isset($_SESSION['userSession'])!="") {
        header("Location: about.php");
        exit;
    }

    if (isset($_POST['btn-login'])) {
        // Prevent SQL injections & clear user invalid inputs
        $username   = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $email      = $_POST['email'];
        $password   = $_POST['password'];

        $username = trim($_POST['username']);
        $username = strip_tags($username);
        $username = htmlspecialchars($username);
        $username = $dbconn->real_escape_string($username);

        $first_name = trim($_POST['first_name']);
        $first_name = strip_tags($first_name);
        $first_name = htmlspecialchars($first_name);
        $first_name = $dbconn->real_escape_string($first_name);

        $last_name = trim($_POST['last_name']);
        $last_name = strip_tags($last_name);
        $last_name = htmlspecialchars($last_name);
        $last_name = $dbconn->real_escape_string($last_name);

        $email = trim($_POST['email']);
        $email = strip_tags($email);
        $email = htmlspecialchars($email);
        $email = $dbconn->real_escape_string($email);

        $password = trim($_POST['password']);
        $password = strip_tags($password);
        $password = htmlspecialchars($password);
        $password = $dbconn->real_escape_string($password);
        $password = password_hash($password, PASSWORD_DEFAULT);

        //Check database for record existance
        //$query = $dbconn->query("SELECT id, email, password, username FROM users WHERE username='$username'");
        $query = $dbconn->query("SELECT * FROM users WHERE username='$username'");
        $row    = $query->fetch_array();
        $count  = $query->num_rows;

        if(empty($username) OR empty($first_name) OR empty($last_name) OR empty($email) OR empty($password)){
            $msg = "<div class='alert alert-warning'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Please fill in all the fields.
                    </div>";
        } elseif ($count == 0) {
            $useractive = "yes";
            $insert = $dbconn->query("INSERT INTO users(id,username,first_name,last_name,email,password,active)
                                        VALUES('','$username','$first_name','$last_name','$email','$password','$useractive')");
            if ($insert) {
                $msg = "<div class='alert alert-success'>
                            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! User has been activated.
                        </div>";
            } 
        } else {
            $msg = "<div class='alert alert-danger'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Sorry... This username already exists.
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
                    <!--
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">Profile <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="mainprofile.php">Summary</a></li>
                                <li class="divider"></li>
                                <li><a href="handlerprofile.php">Handler</a></li>
                                <li><a href="dogprofile.php">Dog</a></li>
                            </ul>
                        </li>
                    -->
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
                        <li><a href="login.php">Log In</a></li>
                        <!--<li><a href="userreg.php">Sign Up</a></li>-->
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
            <h2 class="text-center form-heading">Sign Up</h2>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="text" name="first_name" placeholder="First Name" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="text" name="last_name" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="email" name="email" placeholder="Email" required>
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
                        <button type="submit" class="btn btn-default btn-block submit-button" name="btn-login" id="btn-login">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Scripts to include in the page -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</body>

</html>