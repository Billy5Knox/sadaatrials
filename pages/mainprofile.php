<?php
    session_start();
    require_once '../process/dbconnect.php';

    // Check connection
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
        exit;
    } 

    //Find the handler profile record
    $queryHandlerProfile = $dbconn->query("SELECT * FROM handlers WHERE id_login='{$_SESSION['userLogin']}'");
    $rowHandler     = $queryHandlerProfile->fetch_array();
    $countHandler   = $queryHandlerProfile->num_rows;
    
    if ($countHandler == 0) {
        echo "here now handler";
        $msg = "<div class='alert alert-success'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                    Handler profile could not be found.
                </div>";
        return;
    }

/*    
    if ($countHandler != 0) {
        //Find the dog profiles for the handler
        $queryDogProfile = $dbconn->query("SELECT * FROM dogs WHERE id_handler='$userLoginID'");
        $rowDog     = $queryDogProfile->fetch_array();
        $countDog   = $queryDogProfile->num_rows;
        
        if ($countDog == 0) {
            echo "here now";
            $msg = "<div class='alert alert-success'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                        Dog profile could not be found.
                    </div>";
            return;
        }
        
        if ($countDog != 0) {
            echo "here";
            
        }
    }
*/
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
        <div class="col-sm-4 col-sm-offset-4">
            <!--<h3>Handler Profile</h3>-->
        <?php
            if(isset($msg)){
                echo $msg;
            }
        ?>
            <span class="input-group-addon">
                <img id="glyphicon-user" height="30" src="../img/glyphicons-4-user.png" alt="">
                <strong>  Profile</strong>
            </span>
            <table class="table table-striped table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php   //Find the handler record to display
                    $queryhandler = "SELECT * FROM handlers WHERE id_login='{$_SESSION['userLogin']}'";
                    if ($resulth = mysqli_query($dbconn, $queryhandler)) {
                        while($rowh = mysqli_fetch_array($resulth)) {
                            echo "<form action='handlerprofile.php' method='POST'>";
                            echo "<tr>";
                            echo "<td>" . $rowh['first_name'] . "</td>";
                            echo "<td><form action='handlerprofile.php' method='POST'>
                                        <input type='hidden' name='id' value='".$rowh["id"]."'/>
                                        <input type='submit' name='btn-edit' value='Edit' />
                                      </form></td>";
                            echo "</tr>";
                            echo "</form>";
                        }
                    } else {
                        $msg = "<div class='alert alert-success'>
                                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                                    Unable to find handler profile for current user
                                </div>";
                    }
                ?>
                </tbody>
            </table>
            <!--<h3>Dog's Profile</h3>-->
            <span class="input-group-addon">
                <img id="glyphicon-dog" height="30" src="../img/glyphicons-3-dog.png" alt="">
                <strong>  Profile</strong>
            </span>
            <table class="table table-striped table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>Callname</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php   //Find the dog/s record/s to display
                    $querydog = "SELECT * FROM dogs WHERE id_handler='{$_SESSION['userLogin']}'";
                    if ($resultd = mysqli_query($dbconn, $querydog)) {
                        while($rowd = mysqli_fetch_array($resultd)) {
                            echo "<form action='dogprofile.php' method='POST'>";
                            echo "<tr>";
                            echo "<td>" . $rowd['call_name'] . "</td>";
                            echo "<td><form action='dogprofile.php' method='POST'>
                                        <input type='hidden' name='id' value='".$rowd["id"]."'/>
                                        <input type='submit' name='btn-edit' value='Edit' />
                                      </form></td>";
                            echo "</tr>";
                            echo "</form>";
                        }
                    } else {
                        $msg = "<div class='alert alert-success'>
                                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                                    Unable to find dog profiles for current user
                                </div>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div> <!-- container -->

    <!-- Scripts to include in the page -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</body>

</html>