<?php
    session_start();
    require_once '../process/dbconnect.php';

    // Check connection
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
        exit;
    } 

    //Assign some variables that we might need
    $userLoginID        = $_SESSION['userLogin'];
    
    //This needs to be checked because if page submits and reloads the values are reset
    if(isset($_POST['btn-edit'])) {
        $_SESSION['dogid']  = $_POST['id'];
        $dogid              = $_SESSION['dogid'];
    } else {
        $dogid = 0;
    }
    
    //Check to see if a handlers profile can be found
    $queryHandlerProfile = $dbconn->query("SELECT * FROM handlers WHERE id_login='{$_SESSION['userLogin']}'");
    $rowHandler     = $queryHandlerProfile->fetch_array();
    $countHandler   = $queryHandlerProfile->num_rows;

    if ($countHandler != 0) {
        //Variables to be used
        $hasHandlerProfile  = "yes";
        $firstname      = $rowHandler['first_name'];
        $lastname       = $rowHandler['last_name'];
        $province       = $rowHandler['province_name'];
        $idnumber       = $rowHandler['id_number'];
        $phone          = $rowHandler['phone_number'];
        $cell           = $rowHandler['cell_number'];
        $email          = $rowHandler['email'];
        $sadaanumber    = $rowHandler['sadaa_number'];
    } else {    //No handler profile was found
        //Used all the time
        $_SESSION['hasHandlerProfile'] = "no";
        $hasHandlerProfile = $_SESSION['hasHandlerProfile'];
        $msg = "<div class='alert alert-warning'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                    No Handler profile was found
                </div>";
    }
    
    //Find the dog profile record from the passed in $_POST['id'] for update mode
    if (!empty($_POST['id'])) {
        $queryDogProfile = $dbconn->query("SELECT * FROM dogs WHERE id='{$_SESSION['dogid']}'");
        $rowDog     = $queryDogProfile->fetch_array();
        $countDog   = $queryDogProfile->num_rows;
        if ($countDog != 0) {   //The dog profile was found
            $dogid      = $rowDog['id'];
            $callname   = $rowDog['call_name'];
            $dogage     = $rowDog['dog_age'];
            $dogbreed   = $rowDog['dog_breed'];
            $dogsex     = $rowDog['dog_sex'];
            $dogcolour  = $rowDog['dog_colour'];
            $dogpedigree = $rowDog['dog_pedigree'];
            $sawdanumber = $rowDog['sawda_number'];
            $sadaa      = $rowDog['ass_sadaa'];
            $safda      = $rowDog['ass_safda'];
            $sawtda     = $rowDog['ass_sawtda'];
            $sadsa      = $rowDog['ass_sadsa'];
            $sadda      = $rowDog['ass_sadda'];
            $saeds      = $rowDog['ass_saeds'];
            $active     = $rowDog['active_dog'];
            $dogsize    = $rowDog['dog_size_cat'];
            $dogheight  = $rowDog['dog_height'];
            $agility    = $rowDog['grade_agility'];
            $jumping    = $rowDog['grade_jumping'];
            $gamblers   = $rowDog['grade_gamblers'];
            $snooker    = $rowDog['grade_snooker'];
        } else {
            $msg = "<div class='alert alert-warning'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                        No Dog profile was found
                    </div>";
        }
    }
        
    //If a new dog registration record is created
    if (isset($_POST['btn-create'])) {
        // Prevent SQL injections & clear user invalid inputs
        $callname       = htmlentities($_POST['call_name']);
        $dogage         = htmlentities($_POST['dog_age']);
        $dogbreed       = htmlentities($_POST['dog_breed']);
        $dogpedigree    = htmlentities($_POST['dog_pedigree']);
        $dogcolour      = htmlentities($_POST['dog_colour']);
        $dogsex         = htmlentities($_POST['dog_sex']);
        $dogsizecat     = htmlentities($_POST['dog_size_cat']);
        $dogheight      = htmlentities($_POST['dog_height']);
        $sawdanumber    = htmlentities($_POST['sawda_number']);
        $agility        = htmlentities($_POST['grade_agility']);
        $jumping        = htmlentities($_POST['grade_jumping']);
        $gamblers       = htmlentities($_POST['grade_gamblers']);
        $snooker        = htmlentities($_POST['grade_snooker']);

        $callname   = $dbconn->real_escape_string($callname);
        $dogage     = $dbconn->real_escape_string($dogage);
        $dogbreed   = $dbconn->real_escape_string($dogbreed);
        $dogpedigree = $dbconn->real_escape_string($dogpedigree);
        $dogcolour  = $dbconn->real_escape_string($dogcolour);
        $dogsex     = $dbconn->real_escape_string($dogsex);
        $dogsizecat = $dbconn->real_escape_string($dogsizecat);
        $dogheight  = $dbconn->real_escape_string($dogheight);
        $sawdanumber = $dbconn->real_escape_string($sawdanumber);
        $agility    = $dbconn->real_escape_string($agility);
        $jumping    = $dbconn->real_escape_string($jumping);
        $gamblers   = $dbconn->real_escape_string($gamblers);
        $snooker    = $dbconn->real_escape_string($snooker);

        //Check database for record existance for this dog
        $queryDogProfile = $dbconn->query("SELECT * FROM dogs WHERE call_name='$callname'
                                                                AND id_handler='$userLoginID'");
        $rowDog     = $queryDogProfile->fetch_array();
        $countDog   = $queryDogProfile->num_rows;
        if ($countDog != 0) {   //The dog already exists in the database
            $msg = "<div class='alert alert-warning'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                        A profile for this dog already exists
                    </div>";
        } else {    //If no record was found for the dog, create one
            $uniqueDogID = '';
            $activeDog   = 'yes';

            //Assign checkbox values to variables for easy database update
            if (!empty($_POST['ass_sadaa'])) {
                $sadaa = "yes";
            } else {
                $sadaa = "no";
            }
            if (!empty($_POST['ass_safda'])) {
                $safda  = "yes";
            } else {
                $safda  = "no";
            }
            if (!empty($_POST['ass_sawtda'])) {
                $sawtda = "yes";
            } else {
                $sawtda = "no";
            }
            if (!empty($_POST['ass_sadsa'])) {
                $sadsa = "yes";
            } else {
                $sadsa = "no";
            }
            if (!empty($_POST['ass_sadda'])) {
                $sadda = "yes";
            } else {
                $sadda = "no";
            }
            if (!empty($_POST['ass_saeds'])) {
                $saeds = "yes";
            } else {
                $saeds = "no";
            }
            
            $insert = $dbconn->query("INSERT INTO dogs(id,id_handler,call_name,dog_age,
                                        dog_breed,dog_sex,dog_colour,dog_pedigree,
                                        sawda_number,ass_sadaa,ass_sawtda,ass_sadsa,
                                        ass_sadda,ass_saeds,ass_safda,active_dog,grade_agility,
                                        grade_jumping,grade_gamblers,grade_snooker,dog_size_cat,
                                        dog_height)
                                    VALUES('$uniqueDogID','$userLoginID','$callname',
                                        '$dogage','$dogbreed','$dogsex','$dogcolour',
                                        '$dogpedigree','$sawdanumber','$sadaa','$sawtda',
                                        '$sadsa','$sadda','$saeds','$safda','$activeDog',
                                        '$agility','$jumping','$gamblers','$snooker','$dogsizecat',
                                        '$dogheight')");
            if ($insert) {
                $msg = "<div class='alert alert-success'>
                            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Profile created.
                        </div>";
                $_SESSION['$hasDogProfile'] = 'yes';

                //header("Location: ../index.php");
            } else {
                echo "ERROR: Could not prepare SQL statement.";
                $_SESSION['$hasDogProfile'] = 'no';
            }
        }
    }
    
    //If a dog profile is being updated
    if (isset($_POST['btn-update'])) {
        $dogid = $_SESSION['dogid'];
        
        // Prevent SQL injections & clear user invalid inputs
        $callname       = htmlentities($_POST['call_name']);
        $dogage         = htmlentities($_POST['dog_age']);
        $dogbreed       = htmlentities($_POST['dog_breed']);
        $dogpedigree    = htmlentities($_POST['dog_pedigree']);
        $dogcolour      = htmlentities($_POST['dog_colour']);
        $dogsex         = htmlentities($_POST['dog_sex']);
        $dogsize        = htmlentities($_POST['dog_size_cat']);
        $dogheight      = htmlentities($_POST['dog_height']);
        $sawdanumber    = htmlentities($_POST['sawda_number']);
        $agility        = htmlentities($_POST['grade_agility']);
        $jumping        = htmlentities($_POST['grade_jumping']);
        $gamblers       = htmlentities($_POST['grade_gamblers']);
        $snooker        = htmlentities($_POST['grade_snooker']);
        $active         = htmlentities($_POST['active_dog']);

        $callname   = $dbconn->real_escape_string($callname);
        $dogage     = $dbconn->real_escape_string($dogage);
        $dogbreed   = $dbconn->real_escape_string($dogbreed);
        $dogpedigree = $dbconn->real_escape_string($dogpedigree);
        $dogcolour  = $dbconn->real_escape_string($dogcolour);
        $dogsex     = $dbconn->real_escape_string($dogsex);
        $dogsize    = $dbconn->real_escape_string($dogsize);
        $dogheight  = $dbconn->real_escape_string($dogheight);
        $sawdanumber = $dbconn->real_escape_string($sawdanumber);
        $agility    = $dbconn->real_escape_string($agility);
        $jumping    = $dbconn->real_escape_string($jumping);
        $gamblers   = $dbconn->real_escape_string($gamblers);
        $snooker    = $dbconn->real_escape_string($snooker);
        $active     = $dbconn->real_escape_string($active);

        //Assign checkbox values to variables for easy database update
        if (!empty($_POST['ass_sadaa'])) {
            $sadaa = "yes";
        } else {
            $sadaa = "no";
        }
        if (!empty($_POST['ass_safda'])) {
            $safda  = "yes";
        } else {
            $safda  = "no";
        }
        if (!empty($_POST['ass_sawtda'])) {
            $sawtda = "yes";
        } else {
            $sawtda = "no";
        }
        if (!empty($_POST['ass_sadsa'])) {
            $sadsa = "yes";
        } else {
            $sadsa = "no";
        }
        if (!empty($_POST['ass_sadda'])) {
            $sadda = "yes";
        } else {
            $sadda = "no";
        }
        if (!empty($_POST['ass_saeds'])) {
            $saeds = "yes";
        } else {
            $saeds = "no";
        }

        $update = $dbconn->query("UPDATE dogs SET call_name = '$callname', dog_age = '$dogage',
                                    dog_breed = '$dogbreed', dog_sex = '$dogsex', dog_colour = '$dogcolour',
                                    dog_pedigree = '$dogpedigree', sawda_number= '$sawdanumber',
                                    ass_sadaa = '$sadaa', ass_sawtda = '$sawtda', ass_sadsa = '$sadsa',
                                    ass_sadda= '$sadda', ass_saeds = '$saeds', ass_safda = '$safda',
                                    active_dog = '$active', grade_agility = '$agility',
                                    grade_jumping = '$jumping', grade_gamblers = '$gamblers',
                                    grade_snooker = '$snooker', dog_size_cat = '$dogsize', dog_height = '$dogheight'
                                WHERE id = '$dogid'");
        if ($update) {
            $msg = "<div class='alert alert-success'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Profile updated.
                    </div>";

            header("Location: mainprofile.php");
        } else {
            echo "ERROR: Could not prepare SQL statement.";
        }

            //header("Location: mainprofile.php");
            //exit;
/*
        } else {
            //echo "Error upading record: " .$dbconn->error;
            echo "query failed";
        }
*/
/*
        if ($stmt) {
            echo "Error: " .$dbconn->error;
            $msg = "<div class='alert alert-success'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Profile updated.
                    </div>";
            $_SESSION['$hasDogProfile'] = 'yes';
            //header("Location: mainprofile.php");
        } else {
            echo "ERROR: Could not prepare SQL statement.";
            $_SESSION['$hasDogProfile'] = 'no';
        }
*/
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
        <div class="col-sm-6 col-sm-offset-3">
            <h2 class="text-center form-heading">
                <span class="input-group-addon">
                    <img id="glyphicon-dog" height="30" src="../img/glyphicons-3-dog.png" alt="">
                    <strong>  Profile</strong>
                </span>
            </h2>
            <form class="custom-form" method="post" id="dogreg-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-9 form-group">
                            <label>Call Name</label>
                            <input type="text" name="call_name" placeholder="Enter Call Name Here.." class="form-control" 
                                   value="<?php if($dogid != 0){echo $callname;} else { ?><?php } ?>" autofocus required>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label>Age</label>
                            <input type="number" pattern="[0-9*]" name="dog_age" placeholder="Enter Age Here.." class="form-control" 
                                   value="<?php if($dogid != 0){echo $dogage;} else { ?><?php } ?>">
                        </div>
                    </div>					
                    <div class="row">
                        <div class="col-sm-9 form-group">
                            <label>Breed</label>
                            <input type="text" name="dog_breed" placeholder="Enter Breed Here.." class="form-control" 
                                   value="<?php if($dogid != 0){echo $dogbreed;} else { ?><?php } ?>">
                        </div>	
                        <div class="col-sm-3 form-group">
                            <label>Pedigree?</label>
                        <?php if($dogid != 0) { ?>
                            <select name="dog_pedigree" id="dog_pedigree" class="form-group">
                                <option value="No" <?php if($dogpedigree=="No") echo "selected='selected'" ?> >No</option>
                                <option value="Yes" <?php if($dogpedigree=="Yes") echo "selected='selected'" ?> >Yes</option>
                            </select>
                        <?php } else { ?>
                            <select name="dog_pedigree" id="dog_pedigree" class="form-group">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        <?php } ?>
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-9 form-group">
                            <label>Colour</label>
                            <input type="text" name="dog_colour" placeholder="Enter Colour Here.." class="form-control" 
                                   value="<?php if($dogid != 0){echo $dogcolour;} else { ?><?php } ?>">
                        </div>	
                        <div class="col-sm-3 form-group">
                            <label>Sex</label>
                        <?php if($dogid != 0) { ?>
                            <select name="dog_sex" id="dog_sex" class="form-group">
                                <option value="Male" <?php if($dogsex=="Male") echo "selected='selected'" ?> >Male</option>
                                <option value="Female" <?php if($dogsex=="Female") echo "selected='selected'" ?> >Female</option>
                            </select>
                        <?php } else { ?>
                            <select name="dog_sex" id="dog_sex" class="form-group">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        <?php } ?>
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-9 form-group">
                            <label>SAWDA Number</label>
                            <input type="text" name="sawda_number" placeholder="Enter SAWDA Number Here.." class="form-control" 
                                   value="<?php if($dogid != 0){echo $sawdanumber;} else { ?><?php } ?>">
                        </div>	
                        <div class="col-sm-3 form-group">
                            <label>Active Dog?</label>
                        <?php if($dogid != 0) { ?>
                            <select name="active_dog" id="active_dog" class="form-group">
                                <option value="Yes" <?php if($active=="Yes") echo "selected='selected'" ?> >Yes</option>
                                <option value="No" <?php if($active=="No") echo "selected='selected'" ?> >No</option>
                            </select>
                        <?php } else { ?>
                            <select name="active_dog" id="active_dog" class="form-group">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        <?php } ?>
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-3 form-group">
                            <label>Height</label>
                            <input type="number" name="dog_height" placeholder="Enter Height Here.." class="form-control" 
                                   value="<?php if($dogid != 0){echo $dogheight;} else { ?><?php } ?>">
                        </div>	
                        <div class="col-sm-3 form-group">
                            <label>Size Category</label>
                        <?php if($dogid != 0) { ?>
                            <select name="dog_size_cat" id="dog_size_cat" class="form-group">
                                <option value="Toy" <?php if($dogsize=="Toy") echo "selected='selected'" ?> >Toy</option>
                                <option value="Mini" <?php if($dogsize=="Mini") echo "selected='selected'" ?> >Mini</option>
                                <option value="Midi" <?php if($dogsize=="Midi") echo "selected='selected'" ?> >Midi</option>
                                <option value="Maxi" <?php if($dogsize=="Maxi") echo "selected='selected'" ?> >Maxi</option>
                            </select>
                        <?php } else { ?>
                            <select name="dog_size_cat" id="dog_size_cat" class="form-group">
                                <option value="Toy">Toy</option>
                                <option value="Mini">Mini</option>
                                <option value="Midi">Midi</option>
                                <option value="Maxi">Maxi</option>
                            </select>
                        <?php } ?>
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Agility</label>
                        <?php if($dogid != 0) { ?>
                            <select name="grade_agility" id="grade_agility" class="form-group">
                                <option value="1" <?php if($agility=="1") echo "selected='selected'" ?> >1</option>
                                <option value="2" <?php if($agility=="2") echo "selected='selected'" ?> >2</option>
                                <option value="3" <?php if($agility=="3") echo "selected='selected'" ?> >3</option>
                            </select>
                        <?php } else { ?>
                            <select name="grade_agility" id="grade_agility" class="form-group">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        <?php } ?>
                        </div>	
                        <div class="col-sm-6 form-group">
                            <label>Gambler</label>
                        <?php if($dogid != 0) { ?>
                            <select name="grade_gamblers" id="grade_gamblers" class="form-group">
                                <option value="1" <?php if($gamblers=="1") echo "selected='selected'" ?> >1</option>
                                <option value="2" <?php if($gamblers=="2") echo "selected='selected'" ?> >2</option>
                                <option value="3" <?php if($gamblers=="3") echo "selected='selected'" ?> >3</option>
                            </select>
                        <?php } else { ?>
                            <select name="grade_gamblers" id="grade_gamblers" class="form-group">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Jumping</label>
                        <?php if($dogid != 0) { ?>
                            <select name="grade_jumping" id="grade_jumping" class="form-group">
                                <option value="1" <?php if($jumping=="1") echo "selected='selected'" ?> >1</option>
                                <option value="2" <?php if($jumping=="2") echo "selected='selected'" ?> >2</option>
                                <option value="3" <?php if($jumping=="3") echo "selected='selected'" ?> >3</option>
                            </select>
                        <?php } else { ?>
                            <select name="grade_jumping" id="grade_jumping" class="form-group">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        <?php } ?>
                        </div>	
                        <div class="col-sm-6 form-group">
                            <label>Snooker</label>
                        <?php if($dogid != 0) { ?>
                            <select name="grade_snooker" id="grade_snooker" class="form-group">
                                <option value="1" <?php if($snooker=="1") echo "selected='selected'" ?> >1</option>
                                <option value="2" <?php if($snooker=="2") echo "selected='selected'" ?> >2</option>
                                <option value="3" <?php if($snooker=="3") echo "selected='selected'" ?> >3</option>
                            </select>
                        <?php } else { ?>
                            <select name="grade_snooker" id="grade_snooker" class="form-group">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        <?php } ?>
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-9 form-group">
                            <label>SELECT ASSOCIATIONS REGISTERED FOR</label>
                        </div>	
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>SADAA</label>
                        <?php if($dogid != 0) { ?>
                            <input type="checkbox" name="ass_sadaa" id="ass_sadaa" 
                                <?php if($sadaa == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="ass_sadaa" id="ass_sadaa" value="Yes">
                        <?php } ?>
                            <label>SAFDA</label>
                        <?php if($dogid != 0) { ?>
                            <input type="checkbox" name="ass_safda" id="ass_safda" value="Yes" 
                                <?php if($safda == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="ass_safda" id="ass_safda" value="Yes">
                        <?php } ?>
                            <label>SAWTDA</label>
                        <?php if($dogid != 0) { ?>
                            <input type="checkbox" name="ass_sawtda" id="ass_sawtda" value="Yes" 
                                <?php if($sawtda == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="ass_sawtda" id="ass_sawtda" value="Yes">
                        <?php } ?>
                            <label>SADSA</label>
                        <?php if($dogid != 0) { ?>
                            <input type="checkbox" name="ass_sadsa" id="ass_sadsa" value="Yes" 
                                <?php if($sadsa == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="ass_sadsa" id="ass_sadsa" value="Yes">
                        <?php } ?>
                            <label>SADDA</label>
                        <?php if($dogid != 0) { ?>
                            <input type="checkbox" name="ass_sadda" id="ass_sadda" value="Yes" 
                                <?php if($sadda == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="ass_sadda" id="ass_sadda" value="Yes">
                        <?php } ?>
                            <label>SAEDS</label>
                        <?php if($dogid != 0) { ?>
                            <input type="checkbox" name="ass_saeds" id="ass_saeds" value="Yes" 
                                <?php if($saeds == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="ass_saeds" id="ass_saeds" value="Yes">
                        <?php } ?>
                        </div>
                    </div>
                    <br>
                </div>

                <!-- Check if this is a new entry or an update -->
            <?php if($dogid != 0) { ?>
                <button type="submit" class="btn btn-default btn-block submit-button" name="btn-update" id="btn-update">Submit Form</button>
            <?php } else { ?>
                <button type="submit" class="btn btn-default btn-block submit-button" name="btn-create" id="btn-create">Submit Form</button>
            <?php } ?>
            </form>
        </div>
    </div>

    <!-- Scripts to include in the page -->
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
</body>
</html>