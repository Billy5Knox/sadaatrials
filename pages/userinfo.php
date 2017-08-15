<?php
    session_start();
    require_once '../process/dbconnect.php';

    // Check connection
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
        exit;
    } 

    //Assign some variables that we might need
    $userLoginID    = $_SESSION['userLogin'];

    //Find the user profile of the current logged in user
    $queryuserProfile = $dbconn->query("SELECT * FROM users WHERE id='{$_SESSION['userLogin']}'");
    $rowUser     = $queryuserProfile->fetch_array();
    $countUser   = $queryuserProfile->num_rows;

    //If a user record was found successfully
    if ($countUser != 0) {
        //Variables to be used
        $_SESSION['username']   = $rowUser['username'];
        $_SESSION['first_name'] = $rowUser['first_name'];
        $_SESSION['last_name']  = $rowUser['last_name'];
        $_SESSION['email']      = $rowUser['email'];
        $_SESSION['password']   = $rowUser['password'];
        $_SESSION['active']     = $rowUser['active'];
        $_SESSION['admin_user'] = $rowUser['admin_user'];
        $_SESSION['admin_secr'] = $rowUser['admin_secr'];
        $_SESSION['regional_director'] = $rowUser['regional_director'];
        
        $hasUserProfile = "yes";
        $activeuser     = $rowUser['active'];
        $adminuser      = $rowUser['admin_user'];
        $adminsecr      = $rowUser['admin_secr'];
        $regionaldir    = $rowUser['regional_director'];
/*        
        $activeuser   = $rowUser['active'] ? ' checked="checked"' : '';
        $adminuser    = $rowUser['admin_user'] ? ' checked="checked"' : '';
        $adminsecr    = $rowUser['admin_secr'] ? ' checked="checked"' : '';
        $regionaldir  = $rowUser['regional_director'] ? ' checked="checked"' : '';
*/
    } else {    //No handler profile was found
        $hasUserProfile = "no";
        //Used all the time
        $msg = "<div class='alert alert-warning'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                    No User profile was found
                </div>";
    }

    if (isset($_POST['btn-update'])) {
        $username   = htmlentities($_POST['username']);
        $firstname  = htmlentities($_POST['first_name']);
        $lastname   = htmlentities($_POST['last_name']);
        $email      = htmlentities($_POST['email']);
        if (!empty($_POST['active_user'])) {
            $activeuser = "yes";
        } else {
            $activeuser = "no";
        }
        if (!empty($_POST['admin_user'])) {
            $adminuser = "yes";
        } else {
            $adminuser = "no";
        }
        if (!empty($_POST['admin_secr'])) {
            $adminsecr = "yes";
        } else {
            $adminsecr = "no";
        }
        if (!empty($_POST['regional_director'])) {
            $regionaldir = "yes";
        } else {
            $regionaldir = "no";
        }
        //Database update
        $update = $dbconn->query("UPDATE users SET username='$username', first_name='$firstname',
                                    last_name='$lastname', email='$email', active='$activeuser',
                                    admin_user='$adminuser', admin_secr='$adminsecr', regional_director='$regionaldir'
                                WHERE id='$userLoginID'");

        if ($update) {
            $msg = "<div class='alert alert-success'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Update complete.
                    </div>";
        } else {
            echo "Error: " .$dbconn->error;
        }
    }
?>

<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
                    <li><a href="index.php">Home <span class="sr-only"></span></a></li>
                <?php
                    if(isset($_SESSION['userSession'])) {
                ?>
                        <!-- Dropdown for profile information -->
                        <li class="dropdown">
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
                    <li><a href="../pages/about.php">About</a></li>
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
                                <li><a href="../pages/userinfo.php">User Info</a></li>
                            </ul>
                        </li>
                <?php
                    }
                ?>

                <?php //If session is not yet a valid logged in session then display
                    if(!isset($_SESSION['userSession'])) {
                ?>
                        <li><a href="../pages/login.php">Log In</a></li>
                        <li><a href="../pages/userreg.php">Sign Up</a></li>
                <?php
                    }
                ?>
                <?php //If session is a valid logged in session then display
                    if(isset($_SESSION['userSession'])) {
                ?>
                        <li><a href="../pages/logout.php?logout">Logout</a></li>
                <?php
                    }
                ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav><!-- nav class -->
    
    <div class="container">
        <div class="col-sm-4 col-sm-offset-4">
            <h2 class="text-center form-heading">
                <span class="input-group-addon">
                    <img id="glyphicon-user" height="30" src="../img/glyphicons-4-user.png" alt="">
                    <strong>  User Info</strong>
                </span>
            </h2>
            <form class="custom-form" method="post" id="user-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="text" name="username" placeholder="Username" value="<?php if($hasUserProfile == 'yes') {
                                    echo htmlentities($_SESSION['username']); } else { ?><?php } ?>" autofocus="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="text" name="first_name" placeholder="First Name" value="<?php if($hasUserProfile == 'yes') {
                                    echo htmlentities($_SESSION['first_name']); } else { ?><?php } ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php if($hasUserProfile == 'yes') {
                                    echo htmlentities($_SESSION['last_name']); } else { ?><?php } ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="email" name="email" placeholder="Email" value="<?php if($hasUserProfile == 'yes') {
                                    echo htmlentities($_SESSION['email']); } else { ?><?php } ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="input-column">
                                <input class="form-control" type="password" name="password" placeholder="Password" value="<?php if($hasUserProfile == 'yes') {
                                    echo htmlentities($_SESSION['password']); } else { ?><?php } ?>" required disabled="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="checkbox-inline">
                            <input type="checkbox" name="active_user" <?php if($activeuser == 'yes') echo 'checked="checked"'; ?> />Active?
                        </div>
                        <div class="checkbox-inline">
                            <input type="checkbox" name="admin_user" <?php if($adminuser == 'yes') echo 'checked="checked"'; ?> />Admin?
                        </div>
                        <div class="checkbox-inline">
                            <input type="checkbox" name="admin_secr" <?php if($adminsecr == 'yes') echo 'checked="checked"'; ?> />Admin Secr?
                        </div>
                        <div class="checkbox-inline">
                            <input type="checkbox" name="regional_director" <?php if($regionaldir == 'yes') echo 'checked="checked"'; ?> />RD?
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <br>
                        <button type="submit" class="btn btn-default btn-block submit-button" name="btn-update" id="btn-update">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div> <!-- container -->

    <!-- Scripts to include in the page -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</body>
</html>