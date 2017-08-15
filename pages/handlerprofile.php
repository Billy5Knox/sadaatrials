<?php
    session_start();
    require_once '../process/dbconnect.php';

    // Check connection
    if ($dbconn->connect_error) {
        die("Connection failed: " . $dbconn->connect_error);
        exit;
    } 

    //Assign some variables that we might need
    $userLoginID = $_SESSION['userLogin'];
    
    //This needs to be checked because if page submits and reloads the values are reset
    if(isset($_POST['btn-edit'])) {
        $_SESSION['handlerid']  = $_POST['id'];
        $handlerid              = $_SESSION['handlerid'];
    } else {
        $handlerid = 0;
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
        $sadaanumber    = $rowHandler['sadaa_number'];
        $street         = $rowHandler['street_address'];
        $area           = $rowHandler['area_name'];
        $city           = $rowHandler['city_name'];
        $province       = $rowHandler['province_name'];
        $postalcode     = $rowHandler['postal_code'];
        $idnumber       = $rowHandler['id_number'];
        $fax            = $rowHandler['fax_number'];
        $phone          = $rowHandler['phone_number'];
        $cell           = $rowHandler['cell_number'];
        $emailaddress   = $rowHandler['email'];
        $membertype     = $rowHandler['member_type'];
        $judge          = $rowHandler['qualified_judge'];
        $willjudge      = $rowHandler['will_judge'];
        $active         = $rowHandler['active_handler'];
    } else {    //No handler profile was found
        //Used all the time
        $_SESSION['hasHandlerProfile'] = "no";
        $hasHandlerProfile = $_SESSION['hasHandlerProfile'];
        $msg = "<div class='alert alert-warning'>
                    <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                    No Handler profile was found
                </div>";
    }

    if (isset($_POST['btn-create'])) {
        // Prevent SQL injections & clear user invalid inputs
        $firstname      = htmlentities($_POST['first_name']);
        $lastname       = htmlentities($_POST['last_name']);
        $sadaanumber    = htmlentities($_POST['sadaa_number']);
        $street         = htmlentities($_POST['street_address']);
        $area           = htmlentities($_POST['area_name']);
        $city           = htmlentities($_POST['city_name']);
        $province       = htmlentities($_POST['province_name']);
        $postalcode     = htmlentities($_POST['postal_code']);
        $idnumber       = htmlentities($_POST['id_number']);
        $fax            = htmlentities($_POST['fax_number']);
        $phone          = htmlentities($_POST['phone_number']);
        $cell           = htmlentities($_POST['cell_number']);
        $emailaddress   = htmlentities($_POST['email']);
        $membertype     = htmlentities($_POST['member_type']);

        $first_name = trim($_POST['first_name']);
        $first_name = strip_tags($first_name);
        $first_name = htmlspecialchars($first_name);
        $firstname  = $dbconn->real_escape_string($firstname);

        $last_name = trim($_POST['last_name']);
        $last_name = strip_tags($last_name);
        $last_name = htmlspecialchars($last_name);
        $lastname  = $dbconn->real_escape_string($lastname);

        $sadaa_number = trim($_POST['sadaa_number']);
        $sadaa_number = strip_tags($sadaa_number);
        $sadaa_number = htmlspecialchars($sadaa_number);
        $sadaanumber  = $dbconn->real_escape_string($sadaanumber);

        $street_address = trim($_POST['street_address']);
        $street_address = strip_tags($street_address);
        $street_address = htmlspecialchars($street_address);
        $street         = $dbconn->real_escape_string($street);

        $area_name = trim($_POST['area_name']);
        $area_name = strip_tags($area_name);
        $area_name = htmlspecialchars($area_name);
        $area      = $dbconn->real_escape_string($area);

        $city_name = trim($_POST['city_name']);
        $city_name = strip_tags($city_name);
        $city_name = htmlspecialchars($city_name);
        $city      = $dbconn->real_escape_string($city);

        $province_name = trim($_POST['province_name']);
        $province_name = strip_tags($province_name);
        $province_name = htmlspecialchars($province_name);
        $province      = $dbconn->real_escape_string($province);

        $postal_code = trim($_POST['postal_code']);
        $postal_code = strip_tags($postal_code);
        $postal_code = htmlspecialchars($postal_code);
        $postalcode  = $dbconn->real_escape_string($postalcode);

        $id_number = trim($_POST['id_number']);
        $id_number = strip_tags($id_number);
        $id_number = htmlspecialchars($id_number);
        $idnumber  = $dbconn->real_escape_string($idnumber);

        $fax_number = trim($_POST['fax_number']);
        $fax_number = strip_tags($fax_number);
        $fax_number = htmlspecialchars($fax_number);
        $fax        = $dbconn->real_escape_string($fax);

        $phone_number = trim($_POST['phone_number']);
        $phone_number = strip_tags($phone_number);
        $phone_number = htmlspecialchars($phone_number);
        $phone        = $dbconn->real_escape_string($phone);

        $cell_number = trim($_POST['cell_number']);
        $cell_number = strip_tags($cell_number);
        $cell_number = htmlspecialchars($cell_number);
        $cell        = $dbconn->real_escape_string($cell);

        $email = trim($_POST['email']);
        $email = strip_tags($email);
        $email = htmlspecialchars($email);
        $emailaddress = $dbconn->real_escape_string($emailaddress);

        $member_type = trim($_POST['member_type']);
        $member_type = strip_tags($member_type);
        $member_type = htmlspecialchars($member_type);
        $membertype  = $dbconn->real_escape_string($membertype);

        //Check database for record existance
        $queryProfile = $dbconn->query("SELECT * FROM handlers WHERE first_name='$firstname'
                                                                 AND last_name='$lastname'
                                                                 AND email='$emailaddress'
                                                                 AND id_login='$userLoginID'");
        $row    = $queryProfile->fetch_array();
        $count  = $queryProfile->num_rows;

        if ($count == 0) {
            $uniqueHandlerID = '';
            $activeHandler  = 'yes';

            //Assign checkbox values to variables for easy database update
            if (!empty($_POST['qualified_judge'])) {
                $judge = "yes";
            } else {
                $judge = "no";
            }
            if (!empty($_POST['will_judge'])) {
                $willjudge  = "yes";
            } else {
                $willjudge  = "no";
            }
            
            $insert = $dbconn->query("INSERT INTO handlers(id,id_login,first_name,last_name,
                                        sadaa_number,street_address,area_name,city_name,
                                        province_name,postal_code,id_number,fax_number,
                                        phone_number,cell_number,email,member_type,
                                        qualified_judge,will_judge,active_handler)
                                    VALUES('$uniqueHandlerID','$userLoginID','$firstname',
                                        '$lastname','$sadaanumber','$street','$area',
                                        '$city','$province','$postalcode',$idnumber,
                                        '$fax','$phone','$cell','$emailaddress',
                                        '$membertype','$judge','$willjudge','$activeHandler')");
            if ($insert) {
                $msg = "<div class='alert alert-success'>
                            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Profile created.
                        </div>";
                $_SESSION['$hasHandlerProfile'] = 'yes';

                header("Location: dogprofile.php");
            } else {
                echo "ERROR: Could not prepare SQL statement.";
                $_SESSION['$hasHandlerProfile'] = 'no';
            }
        } else {
            $msg = "<div class='alert alert-success'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp;
                        A profle already exists.
                    </div>";
        }
    }
    
    //If a handler profile is being updated
    if (isset($_POST['btn-update'])) {
        $handlerid  = $_SESSION['handlerid'];
        
        // Prevent SQL injections & clear user invalid inputs
        $firstname      = htmlentities($_POST['first_name']);
        $lastname       = htmlentities($_POST['last_name']);
        $sadaanumber    = htmlentities($_POST['sadaa_number']);
        $street         = htmlentities($_POST['street_address']);
        $area           = htmlentities($_POST['area_name']);
        $city           = htmlentities($_POST['city_name']);
        $province       = htmlentities($_POST['province_name']);
        $postalcode     = htmlentities($_POST['postal_code']);
        $idnumber       = htmlentities($_POST['id_number']);
        $fax            = htmlentities($_POST['fax_number']);
        $phone          = htmlentities($_POST['phone_number']);
        $cell           = htmlentities($_POST['cell_number']);
        $emailaddress   = htmlentities($_POST['email']);
        $membertype     = htmlentities($_POST['member_type']);

        $firstname      = $dbconn->real_escape_string($firstname);
        $lastname       = $dbconn->real_escape_string($lastname);
        $sadaanumber    = $dbconn->real_escape_string($sadaanumber);
        $street         = $dbconn->real_escape_string($street);
        $area           = $dbconn->real_escape_string($area);
        $city           = $dbconn->real_escape_string($city);
        $province       = $dbconn->real_escape_string($province);
        $postalcode     = $dbconn->real_escape_string($postalcode);
        $idnumber       = $dbconn->real_escape_string($idnumber);
        $fax            = $dbconn->real_escape_string($fax);
        $phone          = $dbconn->real_escape_string($phone);
        $cell           = $dbconn->real_escape_string($cell);
        $emailaddress   = $dbconn->real_escape_string($emailaddress);
        $membertype     = $dbconn->real_escape_string($membertype);

        //Assign checkbox values to variables for easy database update
        if (!empty($_POST['qualified_judge'])) {
            $judge = "yes";
        } else {
            $judge = "no";
        }
        if (!empty($_POST['will_judge'])) {
            $willjudge  = "yes";
        } else {
            $willjudge  = "no";
        }
        
        $update = $dbconn->query("UPDATE handlers SET first_name = '$firstname', last_name = '$lastname',
                                    sadaa_number = '$sadaanumber', street_address = '$street',
                                    area_name = '$area', city_name = '$city', province_name = '$province',
                                    postal_code = '$postalcode', id_number = '$idnumber', fax_number = '$fax',
                                    phone_number = '$phone', cell_number = '$cell', email = '$emailaddress',
                                    member_type = '$membertype', qualified_judge = '$judge',
                                    will_judge = '$willjudge'
                                WHERE id = '$handlerid'");
        if ($update) {
            $msg = "<div class='alert alert-success'>
                        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Profile updated.
                    </div>";

            header("Location: mainprofile.php");
        } else {
            echo "ERROR: Could not prepare SQL statement.";
        }
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
                    <img id="glyphicon-user" height="30" src="../img/glyphicons-4-user.png" alt="">
                    <strong>  Profile</strong>
                </span>
            </h2>
            <form class="custom-form" method="post" id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" placeholder="Enter First Name Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $firstname;} else { ?><?php } ?>" autocomplete="First Name" autofocus required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" placeholder="Enter Last Name Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $lastname;} else { ?><?php } ?>" autocomplete="Last Name" required>
                        </div>
                    </div>					
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="street_address" placeholder="Enter Address Here.." class="form-control" 
                               value="<?php if($handlerid != 0){echo $street;} else { ?><?php } ?>" autocomplete="Address">
                    </div>	
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" name="area_name" placeholder="Enter Area Here.." class="form-control" 
                               value="<?php if($handlerid != 0){echo $area;} else { ?><?php } ?>" autocomplete="Area">
                    </div>	
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>City</label>
                            <input type="text" name="city_name" placeholder="Enter City Name Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $city;} else { ?><?php } ?>" autocomplete="City">
                        </div>	
                        <div class="col-sm-4 form-group">
                            <label for="province">Province</label>
                        <?php if($handlerid != 0) { ?>
                            <select name="province_name" id="province" class="form-group">
                                <option value="Eastern Cape" <?php if($province=="Eastern Cape") echo "selected='selected'" ?> >Eastern Cape</option>
                                <option value="Free State" <?php if($province=="Free State") echo "selected='selected'" ?> >Free State</option>
                                <option value="Gauteng" <?php if($province=="Gauteng") echo "selected='selected'" ?> >Gauteng</option>
                                <option value="KwaZulu-Natal" <?php if($province=="KwaZulu-Natal") echo "selected='selected'" ?> >KwaZulu-Natal</option>
                                <option value="Limpopo" <?php if($province=="Limpopo") echo "selected='selected'" ?> >Limpopo</option>
                                <option value="Mpumalanga" <?php if($province=="Mpumalanga") echo "selected='selected'" ?> >Mpumalanga</option>
                                <option value="Northern Cape" <?php if($province=="Northern Cape") echo "selected='selected'" ?> >Northern Cape</option>
                                <option value="North West" <?php if($province=="North West") echo "selected='selected'" ?> >North West</option>
                                <option value="Western Cape" <?php if($province=="Western Cape") echo "selected='selected'" ?> >Western Cape</option>
                            </select>
                        <?php } else { ?>
                            <select name="province_name" id="province" class="form-group">
                                <option value="Eastern Cape">Eastern Cape</option>
                                <option value="Free State">Free State</option>
                                <option value="Gauteng">Gauteng</option>
                                <option value="KwaZulu-Natal">KwaZulu-Natal</option>
                                <option value="Limpopo">Limpopo</option>
                                <option value="Mpumalanga">Mpumalanga</option>
                                <option value="Northern Cape">Northern Cape</option>
                                <option value="North West">North West</option>
                                <option value="Western Cape">Western Cape</option>
                            </select>
                        <?php } ?>
                        </div>	
                        <div class="col-sm-4 form-group">
                            <label>Postal Code</label>
                            <input type="number" pattern="[0-9*]" name="postal_code" placeholder="Enter Postal Code Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $postalcode;} else { ?><?php } ?>">
                        </div>		
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>ID Number</label>
                            <input type="text" name="id_number" placeholder="Enter ID Number Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $idnumber;} else { ?><?php } ?>" autocomplete="ID Number" required="">
                        </div>		
                        <div class="col-sm-6 form-group">
                            <label>Fax Number</label>
                            <input type="phone" name="fax_number" placeholder="Enter Fax Number Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $fax;} else { ?><?php } ?>" autocomplete="Fax Number">
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Phone Number</label>
                            <input type="phone" name="phone_number" placeholder="Enter Phone Number Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $phone;} else { ?><?php } ?>" autocomplete="Phone Number">
                        </div>		
                        <div class="col-sm-6 form-group">
                            <label>Cell Number</label>
                            <input type="phone" name="cell_number" placeholder="Enter Cellphone Number Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $cell;} else { ?><?php } ?>" autocomplete="Cell Number">
                        </div>		
                    </div>
                    <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="Enter Email Address Here.." class="form-control" 
                                   value="<?php if($handlerid != 0){echo $emailaddress;} else { ?><?php } ?>" autocomplete="email" required>
                    </div>	
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>SADAA Number</label>
                            <input type="text" name="sadaa_number" placeholder="Enter SADAA Number Here If Available" class="from-control" 
                                   value="<?php if($handlerid != 0){echo $sadaanumber;} else { ?><?php } ?>" autocomplete="SADAA Number">
                        </div>
                        <label for="membertype">Membership Type</label>
                    <?php if($handlerid != 0) { ?>
                        <select name="member_type" id="membertype" class="form-group">
                            <option value="Single Member" <?php if($membertype=="Single Member") echo "selected='selected'" ?> >Single Member</option>
                            <option value="Additional Family Member" <?php if($membertype=="Additional Family Member") echo "selected='selected'" ?> >Additional Family Member</option>
                            <option value="Scholar" <?php if($membertype=="Scholar") echo "selected='selected'" ?> >Scholar</option>
                            <option value="Full Time Student" <?php if($membertype=="Full Time Student") echo "selected='selected'" ?> >Full Time Student</option>
                            <option value="Pensioner (60y+)" <?php if($membertype=="Pensioner (60y+)") echo "selected='selected'" ?> >Pensioner (60y+)</option>
                        </select>
                    <?php } else { ?>
                        <select name="member_type" id="membertype" class="form-group">
                            <option value="Single Member" <?php if($membertype=="Single Member") echo "selected='selected'" ?> >Single Member</option>
                            <option value="Additional Family Member" <?php if($membertype=="Additional Family Member") echo "selected='selected'" ?> >Additional Family Member</option>
                            <option value="Scholar" <?php if($membertype=="Scholar") echo "selected='selected'" ?> >Scholar</option>
                            <option value="Full Time Student" <?php if($membertype=="Full Time Student") echo "selected='selected'" ?> >Full Time Student</option>
                            <option value="Pensioner (60y+)" <?php if($membertype=="Pensioner (60y+)") echo "selected='selected'" ?> >Pensioner (60y+)</option>
                        </select>
                    <?php } ?>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label>Are you a qualified Judge?</label>
                        <?php if($handlerid != 0) { ?>
                            <input type="checkbox" name="qualified_judge" id="qualified_judge" value="Yes"
                            <?php if($judge == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="qualified_judge" id="qualified_judge" value="Yes">
                        <?php } ?>
                        </div>
                        <div class="form-group">
                            <label>If so, will you judge for SADAA?</label>
                        <?php if($handlerid != 0) { ?>
                            <input type="checkbox" name="will_judge" id="will_judge" value="Yes"
                            <?php if($willjudge == 'yes') echo 'checked="checked"'; ?> />
                        <?php } else { ?>
                            <input type="checkbox" name="will_judge" id="will_judge" value="Yes">
                        <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Check if this is a new entry or an update -->
            <?php if($handlerid != 0) { ?>
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