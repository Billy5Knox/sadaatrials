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
            header("Location: about.php");
            exit;
    }
*/	
   
    $userLoginID = $_SESSION['userLogin'];
    //Check to see if a handlers profile can be found
    $queryProfile = $dbconn->query("SELECT * FROM handlers WHERE id_login='{$_SESSION['userLogin']}'");
    $row = $queryProfile->fetch_array();
    $count = $queryProfile->num_rows;

    if ($count != 0) {
        $_SESSION['id_login'] = $row['id_login'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        $_SESSION['sadaa_number'] = $row['sadaa_number'];
        $_SESSION['street_address'] = $row['street_address'];
        $_SESSION['area_name'] = $row['area_name'];
        $_SESSION['city_name'] = $row['city_name'];
        $_SESSION['province_name'] = $row['province_name'];
        $_SESSION['postal_code'] = $row['postal_code'];
        $_SESSION['id_number'] = $row['id_number'];
        $_SESSION['fax_number'] = $row['fax_number'];
        $_SESSION['phone_number'] = $row['phone_number'];
        $_SESSION['cell_number'] = $row['cell_number'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['member_type'] = $row['member_type'];
        $_SESSION['qualified_judge'] = $row['qualified_judge'];
        $_SESSION['will_judge'] = $row['will_judge'];
        $_SESSION['active_handler'] = $row['active_handler'];

        //Variables used with the display purposes
        $province = $_SESSION['province_name'];
        $membertype = $_SESSION['member_type'];
        $qualified_judge = $_SESSION['qualified_judge'];
        $will_judge = $_SESSION['will_judge'];
        
        //Used all the time
        $_SESSION['hasHandlerProfile'] = "yes";
        $hasHandlerProfile = $_SESSION['hasHandlerProfile'];
    } else {
        //Used all the time
        $_SESSION['hasHandlerProfile'] = "no";
        $hasHandlerProfile = $_SESSION['hasHandlerProfile'];
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
/*            
        $first_name = trim($_POST['first_name']);
        $first_name = strip_tags($first_name);
        $first_name = htmlspecialchars($first_name);
*/
        $firstname = $dbconn->real_escape_string($firstname);
/*
        $last_name = trim($_POST['last_name']);
        $last_name = strip_tags($last_name);
        $last_name = htmlspecialchars($last_name);
*/
        $lastname = $dbconn->real_escape_string($lastname);
/*
        $sadaa_number = trim($_POST['sadaa_number']);
        $sadaa_number = strip_tags($sadaa_number);
        $sadaa_number = htmlspecialchars($sadaa_number);
*/
        $sadaanumber = $dbconn->real_escape_string($sadaanumber);
/*
        $street_address = trim($_POST['street_address']);
        $street_address = strip_tags($street_address);
        $street_address = htmlspecialchars($street_address);
*/
        $street = $dbconn->real_escape_string($street);
/*
        $area_name = trim($_POST['area_name']);
        $area_name = strip_tags($area_name);
        $area_name = htmlspecialchars($area_name);
*/
        $area = $dbconn->real_escape_string($area);
/*
        $city_name = trim($_POST['city_name']);
        $city_name = strip_tags($city_name);
        $city_name = htmlspecialchars($city_name);
*/
        $city = $dbconn->real_escape_string($city);
/*
        $province_name = trim($_POST['province_name']);
        $province_name = strip_tags($province_name);
        $province_name = htmlspecialchars($province_name);
*/
        $province = $dbconn->real_escape_string($province);
/*
        $postal_code = trim($_POST['postal_code']);
        $postal_code = strip_tags($postal_code);
        $postal_code = htmlspecialchars($postal_code);
*/
        $postalcode = $dbconn->real_escape_string($postalcode);
/*
        $id_number = trim($_POST['id_number']);
        $id_number = strip_tags($id_number);
        $id_number = htmlspecialchars($id_number);
*/
        $idnumber = $dbconn->real_escape_string($idnumber);
/*
        $fax_number = trim($_POST['fax_number']);
        $fax_number = strip_tags($fax_number);
        $fax_number = htmlspecialchars($fax_number);
*/
        $fax = $dbconn->real_escape_string($fax);
/*
        $phone_number = trim($_POST['phone_number']);
        $phone_number = strip_tags($phone_number);
        $phone_number = htmlspecialchars($phone_number);
*/
        $phone = $dbconn->real_escape_string($phone);
/*
        $cell_number = trim($_POST['cell_number']);
        $cell_number = strip_tags($cell_number);
        $cell_number = htmlspecialchars($cell_number);
*/
        $cell = $dbconn->real_escape_string($cell);
/*
        $email = trim($_POST['email']);
        $email = strip_tags($email);
        $email = htmlspecialchars($email);
*/
        $emailaddress = $dbconn->real_escape_string($emailaddress);
/*
        $member_type = trim($_POST['member_type']);
        $member_type = strip_tags($member_type);
        $member_type = htmlspecialchars($member_type);
*/
        $membertype = $dbconn->real_escape_string($membertype);
/*
        $qualified_judge = trim($_POST['qualified_judge']);
        $qualified_judge = strip_tags($qualified_judge);
        $qualified_judge = htmlspecialchars($qualified_judge);
*/
//        $judge = $dbconn->real_escape_string($judge);
/*
        $will_judge = trim($_POST['will_judge']);
        $will_judge = strip_tags($will_judge);
        $will_judge = htmlspecialchars($will_judge);
*/
//        $willjudge = $dbconn->real_escape_string($willjudge);

        //Check database for record existance
        $queryProfile = $dbconn->query("SELECT * FROM handlers WHERE first_name='$firstname'
                                                                 AND last_name='$lastname'
                                                                 AND email='$emailaddress'
                                                                 AND id_login='$userLoginID'");
        $row = $queryProfile->fetch_array();
        $count = $queryProfile->num_rows;

        //Assign checkbox values to variables for easy database update
        if (!empty($_POST['qualified_judge'])) {
            $checkboxJudge = "yes";
        } else {
            $checkboxJudge = "no";
        }
        if (!empty($_POST['will_judge'])) {
            $checkboxWillJudge  = "yes";
        } else {
            $checkboxWillJudge  = "no";
        }
        
        if ($count == 0) {
            $uniqueHandlerID = '';
            $activeHandler  = 'yes';
            $paramBindings  = 'issssssssisssssssss';
            if ($stmt = $dbconn->prepare("INSERT handlers(id,id_login,first_name,last_name,
                                            sadaa_number,street_address,area_name,city_name,
                                            province_name,postal_code,id_number,fax_number,
                                            phone_number,cell_number,email,member_type,
                                            qualified_judge,will_judge,active_handler)
                                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
                $stmt->bind_param($paramBindings,$uniqueHandlerID, $userLoginID, $firstname, $lastname,
                                    $sadaanumber, $street, $area, $city, $province, $postalcode,
                                    $idnumber, $fax, $phone, $cell, $emailaddress, $membertype,
                                    $checkboxJudge, $checkboxWillJudge, $activeHandler);
                $stmt->execute();
                $stmt->close();
                $msg = "<div class='alert alert-success'>
                            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Thank you! Profile created.
                        </div>";

                $hasHandlerProfile = "yes";

                //These are to be used for the emailing notification sent on registration
                $to         = "billyknox@gmail.com.co.za";
                $subject    = "New SADAA handler registration";
                $emailFrom  = "admin@sadaatrials.co.za";
                $message    = "<p>First Name: $firstname <br>
                                   Last Name: $lastname <br>
                                      Street: $street <br>
                                        Area: $area <br>
                                        City: $city <br>
                                    Province: $province <br>
                                 Postal Code: $postalcode <br>
                                   ID Number: $idnumber <br>
                                         Fax: $fax <br>
                                       Phone: $phone <br>
                                        Cell: $cell <br>
                                       Email: $emailaddress <br>
                                SADAA Number: $sadaanumber <br>
                                 Member Type: $membertype <br>
                                 Is A Judge?: $checkboxJudge <br>
                                 Will Judge?: $checkboxWillJudge <br><br>
                                Please do not reply to this email address.
                               </p>";
                $headers    = "From: SADAA Trials <admin@sadaatrials.co.za>\r\n";
                $headers    = "Reply-To: admin@sadaatrials.c.za\r\n";
                $headers    = "Content-type: text/html\r\n";
                $sent = mail($to, $subject, $message, $headers);

                header("Location: ../index.php");
            } else {
                echo "ERROR: Could not prepare SQL statement.";
            }
        }
    }
	
    if (isset($_POST['btn-update'])) {
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
        $judge          = htmlentities($_POST['qualified_judge']);
        $willjudge      = htmlentities($_POST['will_judge']);

        //Assign checkbox values to variables for easy database update
        if (!empty($_POST['qualified_judge'])) {
            $checkboxJudge = "yes";
        } else {
            $checkboxJudge = "no";
        }
        if (!empty($_POST['will_judge'])) {
            $checkboxWillJudge  = "yes";
        } else {
            $checkboxWillJudge  = "no";
        }

        $paramBindings  = 'sssssssissssssssi';

        if ($stmt = $dbconn->prepare("UPDATE handlers SET first_name = ?, last_name = ?,
                                        sadaa_number = ?, street_address = ?, area_name = ?,
                                        city_name = ?, province_name = ?, postal_code = ?,
                                        id_number = ?, fax_number = ?, phone_number = ?,
                                        cell_number = ?, email = ?, member_type = ?,
                                        qualified_judge = ?, will_judge = ?
                                    WHERE id_login = ?")) {
            $stmt->bind_param($paramBindings, $firstname, $lastname, $sadaanumber, $street, $area,
                                $city, $province, $postalcode, $idnumber, $fax, $phone, $cell,
                                $emailaddress, $membertype, $checkboxJudge, $checkboxWillJudge,
                                $userLoginID);
            $stmt->execute();
            $stmt->close();
            //Redirect the user once the record is updated
            header("Location: ../index.php");
        } else {
            echo "Error updating record: " . $dbconn->error;
        }
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
        <div class="col-md-8 col-md-offset-2">
            <h2 class="text-center form-heading">Handler - SADAA Information</h2>
            <form class="custom-form" method="post" id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" placeholder="Enter First Name Here.." class="form-control" value="<?php echo htmlentities($_SESSION['firstname']); ?>" autocomplete="First Name" autofocus required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" placeholder="Enter Last Name Here.." class="form-control" value="<?php echo htmlentities($_SESSION['lastname']); ?>" autocomplete="Last Name" required>
                        </div>
                    </div>					
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="street_address" placeholder="Enter Address Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['street_address']); } else { ?><?php } ?>" autocomplete="Address"></textarea>
                    </div>	
                    <div class="form-group">
                        <label>Area</label>
                        <input type="text" name="area_name" placeholder="Enter Area Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['area_name']); } else { ?><?php } ?>" autocomplete="Area"></textarea>
                    </div>	
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>City</label>
                            <input type="text" name="city_name" placeholder="Enter City Name Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['city_name']); } else { ?><?php } ?>" autocomplete="City">
                        </div>	
                        <div class="col-sm-4 form-group">
                            <label for="province">Province</label>
                        <?php if($hasHandlerProfile == 'yes') { ?>
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
                            <input type="number" pattern="[0-9*]" name="postal_code" placeholder="Enter Postal Code Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['postal_code']); } else { ?><?php } ?>" autocomplete="Postal Code">
                        </div>		
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>ID Number</label>
                            <input type="text" name="id_number" placeholder="Enter ID Number Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['id_number']); } else { ?><?php } ?>" autocomplete="ID Number"><!--required-->
                        </div>		
                        <div class="col-sm-6 form-group">
                            <label>Fax Number</label>
                            <input type="text" name="fax_number" placeholder="Enter Fax Number Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['fax_number']); } else { ?><?php } ?>" autocomplete="Fax Number">
                        </div>	
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" placeholder="Enter Phone Number Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['phone_number']); } else { ?><?php } ?>" autocomplete="Phone Number">
                        </div>		
                        <div class="col-sm-6 form-group">
                            <label>Cell Number</label>
                            <input type="text" name="cell_number" placeholder="Enter Cellphone Number Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['cell_number']); } else { ?><?php } ?>" autocomplete="Cell Number">
                        </div>		
                    </div>
                    <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" placeholder="Enter Email Address Here.." class="form-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['email']); } else { ?><?php } ?>" autocomplete="email" required>
                    </div>	
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Already a member? SADAA Number</label>
                            <input type="text" name="sadaa_number" placeholder="Enter SADAA Number Here If Available" class="from-control" value="<?php if($hasHandlerProfile == 'yes') { echo htmlentities($_SESSION['sadaa_number']); } else { ?><?php } ?>" autocomplete="SADAA Number">
                        </div>
                        <label for="membertype">Membership Type</label>
                    <?php if($hasHandlerProfile == 'yes') { ?>
                        <select name="member_type" id="membertype" class="form-group">
                            <option value="Single Member" <?php if($membertype=="Single Member") echo "selected='selected'" ?> >Single Member</option>
                            <option value="Additional Family Member" <?php if($membertype=="Additional Family Member") echo "selected='selected'" ?> >Additional Family Member</option>
                            <option value="Scholar" <?php if($membertype=="Scholar") echo "selected='selected'" ?> >Scholar</option>
                            <option value="Full Time Student" <?php if($membertype=="Full Time Student") echo "selected='selected'" ?> >Full Time Student</option>
                            <option value="Pensioner (60y+)" <?php if($membertype=="Pensioner (60y+)") echo "selected='selected'" ?> >Pensioner (60y+)</option>
                        </select>
                    <?php } else { ?>
                        <select name="member_type" id="membertype" class="form-group">
                            <option value="Single Member">Single Member</option>
                            <option value="Additional Family Member">Additional Family Member</option>
                            <option value="Scholar">Scholar</option>
                            <option value="Full Time Student">Full Time Student</option>
                            <option value="Pensioner (60y+)">Pensioner (60y+)</option>
                        </select>
                    <?php } ?>
                    </div>
                    <div class="comments">
                        <label>Annual Membership Fees: Single members and first member of a familty: R120 p.a.
                                Additional familty member: R96 p.a. Scholars, students and pensioners: R72 p.a.
                        </label>
                    </div>
                    <div class="comments">
                        <label><b>NB:</b> All applications must be submitted to the local SADAA Regional Director
                                with the appropriate fees. SADAA membership runs from 1st July to 30th June each year.
                                Subscriptions fall due on 1st July. New members who join during the year will pay a pro rata
                                subscription for that year (your Regional Director will calculate the pro rata amount due).
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Are you a qualified Judge?</label>
                <?php if($hasHandlerProfile == 'yes') { ?>
                        <input type="checkbox" name="qualified_judge" id="qualified_judge" value="Yes"
                    <?php if($qualified_judge == 'yes') echo "checked='checked'" ?> />
                <?php } else { ?>
                        <input type="checkbox" name="qualified_judge" id="qualified_judge" value="Yes">
                <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>If so, would you judge for SADAA?</label>
                <?php if($hasHandlerProfile == 'yes') { ?>
                        <input type="checkbox" name="will_judge" id="will_judge" value="Yes"
                    <?php if($will_judge == 'yes') echo "checked='checked'" ?> />
                <?php } else { ?>
                        <input type="checkbox" name="will_judge" id="will_judge" value="Yes">
                <?php } ?>
                    </div>
                    <div class="comments">
                        <label><b>NB:</b> Judges joining SADAA from other organizations are required to undergo
                                a period of assessment, which may include training.
                        </label>
                    </div>
                </div>

            <?php if ($hasHandlerProfile == 'no') { ?> <!--If a new profile is being created display create button-->
                    <button type="submit" class="btn btn-default btn-block submit-button" name="btn-create" id="btn-create">Submit Form</button>
            <?php } ?>
            <?php if ($hasHandlerProfile == 'yes') { ?> <!--If an already created profile is being modified display the update button-->
                    <button type="submit" class="btn btn-default btn-block submit-button" name="btn-update" id="btn-update">Update Form</button>
            <?php } ?>
            </form>
        </div>
    </div>
    <script src="../scripts/jquery.min.js"></script>
    <script src="../scripts/bootstrap.min.js"></script>
</body>

</html>