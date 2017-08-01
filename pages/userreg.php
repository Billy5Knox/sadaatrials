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
        $username 	= $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name 	= $_POST['last_name'];
        $email 		= $_POST['email'];
        $password 	= $_POST['password'];

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
        $query = $dbconn->query("SELECT id, email, password, username FROM users WHERE username='$username'");
        $row = $query->fetch_array();
        $count = $query->num_rows;

        if(empty($username) OR empty($first_name) OR empty($last_name) OR empty($email) OR empty($password)){
            $msg = "<div class='alert alert-warning'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Please fill in all the fields.
                    </div>";
        } elseif ($count == 0) {
            $insert = $dbconn->query("INSERT INTO users(id,username,first_name,last_name,email,password,active)
                                        VALUES('','$username','$first_name','$last_name','$email','$password','Yes')");
            if ($insert) {
                    $msg = "<div class='alert alert-success'>
                                <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! You are now registered.
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
                        <!--<a class="btn btn-default action-button" role="button" href="userreg.php">Sign Up</a>-->
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
            <h2 class="text-center form-heading">Sign Up Form</h2>
            <form class="custom-form" method="post" id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php
                if(isset($msg)){
                        echo $msg;
                }
            ?>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="text" name="username" placeholder="Username" autofocus="" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="text" name="first_name" placeholder="First Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="text" name="last_name" placeholder="Last Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="email" name="email" placeholder="Email" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-13 input-column">
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>
                </div>
<!--
                <div class="form-group">
                    <div class="col-sm-4 label-column">
                        <label class="control-label" for="dropdown-input-field">Dropdown </label>
                    </div>
                    <div class="col-sm-4 input-column">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Dropdown <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li role="presentation"><a href="#">First Item</a></li>
                                <li role="presentation"><a href="#">Second Item</a></li>
                                <li role="presentation"><a href="#">Third Item</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox">I've read and accept the terms and conditions</label>
                </div>
-->
                <button type="submit" class="btn btn-default btn-block submit-button" name="btn-login" id="btn-login">Submit Form</button>
            </form>
        </div>
    </div>
    <script src="../scripts/jquery.min.js"></script>
    <script src="../scripts/bootstrap.min.js"></script>
</body>

</html>