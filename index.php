<?php
session_start();
require 'mailing.php';
require 'hashing.php';
require_once('connect.php');
function clean_input($in) {
	// $res = mysqli_escape_string($in);
	$res = stripslashes($in);
	$res = trim($res);
	return $res;
}
$db = new dbConnect();
$conn = $db->connect();



if(isset($_POST['regno'])){
		unset($_SESSION['message']);
		unset($_SESSION['messages']);
		$send_verify = new Mailing();
		
		function clean($text){
			$res = trim($text);
			$res = stripslashes($text);
			return $res;
		}

		$regno = clean($_POST['regno']);

		function check($regno){
			$db = new dbConnect();
			$conn = $db->connect();
			$query = $conn->prepare("SELECT * FROM allstud WHERE regno = :regno LIMIT 1");
			$query->execute(array(':regno' => $regno));
			$res = $query->fetch(PDO::FETCH_ASSOC);
			if($query->rowCount() > 0){
			   $mail = $res['email'];
			   $que = $conn->prepare("SELECT * FROM users WHERE regno = :regno LIMIT 1");
			   $que->execute(array(':regno'=>$regno));
			   $res = $que->fetch(PDO::FETCH_ASSOC);
			   if($que->rowCount() > 0){
			   		$mess = "info";
	            	return $mess;
			   }else{
			   		$mess = $mail;
			   		return $mess;
			   }
			}else{
				$mess = NULL;
			    return $mess;
			}
		}

		function random_char(){
			// where char stands for the string u want to randomize
			$char = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$char_length = 5;
			$cl = strlen($char);
			$randomize = '';
			for($i = 0; $i < $char_length; $i++ ){
				$randomize .= $char[rand(0, $cl - 1)]; 
			}
			return $randomize;
		}

		$token = random_char();
		// echo check($regno);

		if(check($regno) == NULL){
			$_SESSION['message'] = "User Does Not exist";
			$_SESSION['messageType'] ="alert alert-danger";
		}elseif(check($regno)== "info"){
	        $_SESSION['message'] = "User Already exists";
	        $_SESSION['messageType'] ="alert alert-danger";
		}else{
			if($send_verify->mail_verification(check($regno), $token)){
				$stmt = $conn->prepare("INSERT INTO users (regno, email, password) VALUES (:regno, :email, :password)");
				if($stmt->execute(array(':regno' => $regno, ':email' => check($regno), ':password' => passwordHash::hash($token)))){
					$_SESSION['message'] = "Registered Successfully Please Check your Mail For Your Password";
					$_SESSION['messageType'] ="alert alert-success";
					header('location: index.php');
				}
			}else{
				$_SESSION['message'] = "Baba, Park Well and Try Again";
				$_SESSION['messageType'] ="alert alert-danger";
				header('location: index.php');
			}
		}
	}

if(isset($_POST['logUser']) && isset($_POST['logPass'])) {
	unset($_SESSION['message']);
	unset($_SESSION['messages']);
	// clean input\
	$regno = clean_input($_POST['logUser']);
	$password = clean_input($_POST['logPass']);
	try {
		$stmt = $conn->prepare("SELECT _id, regno, email,password FROM users WHERE (regno=:regno)"); 
	    $stmt->bindParam(':regno', $regno);
		$stmt->execute();
		$res = $stmt->fetch(PDO::FETCH_ASSOC);
    	if ( $stmt->rowCount() > 0 ) {
    		if(passwordHash::check_password($res['password'], $password)){
				$_SESSION['user_id'] = $res['_id'];
	    		$_SESSION['email'] = $res['email'];
	    		header('location:index.php');
    		}else{
    			$_SESSION['messages'] = "Wrong Password";
    			$_SESSION['messageType'] = "alert alert-danger";
	    		header('location:index.php');
    		}
    	} else {
    		$_SESSION['messages'] = "Please Register First";
    		$_SESSION['messageType'] = "alert alert-danger";
	    	header('location:index.php');
    	}
	} catch (PDOException $ex) {
		header('location:index.php');
	}
	
}
?>

<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ave Voting App</title>
    <meta name="description" content="Landmark University Community Dedicated Voting Platform">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="user/images/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/ico" href="user/images/favicon.ico" />

    <!-- Plugin-CSS -->
    <link rel="stylesheet" href="user/css/bootstrap.min.css">
    <link rel="stylesheet" href="user/css/magnific-popup.css">
    <link rel="stylesheet" href="user/css/icofonts.css">
    <link rel="stylesheet" href="user/css/font-awesome.min.css">
    <link rel="stylesheet" href="user/css/ionicons.min.css">
	<link rel="stylesheet" href="user/css/animate.css">


    <!-- Main-Stylesheets -->
    <link rel="stylesheet" href="user/css/demo.css">





    <link rel="stylesheet" type="text/css" media="screen" href="user/css/style.css" />
</head>
<body>
    <!--=== PRELOADER ===-->
    <div class="preloader">
        <div class="loder-content">
            <img src="user/images/preloader.gif" alt="">
        </div>
    </div>

    <!--=== MAINMENU AREA ===-->
    <header id="header" class="on-scroll">
        <nav class="navbar navbar-default navbar-fixed-top nav-center-aligned">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                      <span class="menu-sign">Menu <img src="user/images/logo.png" alt=""></span>
                    </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="#" target="_blank"><span>01</span>Slider</a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
    </header>
    <!--=== MAINMENU AREA END ===-->

    <!--=== TOP AREA ===-->
    <section id="intro" class="ripple">
        <div class="container-fluid">
            <div class="template-info" data-parallax='{"y": -300}'>
                <a class="logo"><img src="assest/img/logo.png" alt=""></a>
                <div class="page-headline">
                    <h1 class="white">Voting Engine</h1>
                    <h5 class="white">Just Inspiring more while we can </h5>
                </div>
                <a href="home.php" class="btn-primary page-scroll">Get Started</a>
            </div>
            <div class="col-md-4 col-md-push-1 animate-box" data-animate-effect="fadeInRight">
				<div class="form-wrap">
					<?php
						if(isset($_SESSION['messages'])) {
							echo '<div class="'.$_SESSION['messageType'].' alert-dismissible">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										'.$_SESSION['messages'].'
								</div>';
						}
					?>
					<?php
						if(isset($_SESSION['message'])) {
							echo '<div class="'.$_SESSION['messageType'].' alert-dismissible">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
								  '.$_SESSION['message'].'
								</div>';
						}
					?>
					<?php 
						if(!isset($_SESSION['user_id'])){
					?>
					<div class="tab">
						<ul class="tab-menu">
							<li class="gtco-first"><a href="#" data-tab="signup">Sign up</a></li>
							<li class="active gtco-second"><a href="#" data-tab="login">Login</a></li>
								</ul>
									<div class="tab-content">
										
										<div class="tab-content-inner" data-content="signup">
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
												
												<div class="row form-group">
													<div class="col-md-12">
														<label for="user.email">Reg No</label>
														<input type="text" class="form-control" name="regno">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-md-12">
														<input type="submit"  class="btn btn-primary" value="Sign up">
													</div>
												</div>
											</form>	
										</div>
										
										<div class="tab-content-inner active" data-content="login">
											<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
															
												<div class="row form-group">
													<div class="col-md-12">
														<label for="username">Reg No</label>
														<input type="text" class="form-control" name="logUser">
													</div>
												</div>
												<div class="row form-group">
													<div class="col-md-12">
														<label for="password">Password</label>
														<input type="password" class="form-control" name="logPass">
													</div>
												</div>

												<div class="row form-group">
													<div class="col-md-12">
														<input type="submit" class="btn btn-primary" value="Login">
													</div>
												</div>
											</form>	
											
										</div>


									</div>

								</div>
								<?php 
									}else{
								?>
									<div class="row form-group">
										<div class="col-md-12">
											<h2>We apologize for the inconvieniences in the CSE Voting. Please be assured of dedication to serving you. Thank you.</h2>
											<a href="logout.php"><button type="button" class="btn btn-primary">Logout</button></a>
											<a href="cat.php"><button type="button" class="btn btn-primary">Categories</button></a>
										</div>
									</div>
								<?php 
									}
								?>
							</div>
						</div>
        </div>
    </section>
    <!--=== TOP AREA END ===-->

    <!--=== SCRIPTS ===-->
	<script src="user/js/anime/anime.min.js"></script>
    <script src="user/js/jquery-3.1.1.min.js"></script>
    <script src="user/js/bootstrap.min.js"></script>
    <script src="user/js/plugins/jquery.parallax-scroll.js"></script>
    <script src="user/js/plugins/jquery.ripples-min.js"></script>
    <script src="user/js/plugins/jquery.easing.min.js"></script>
    <script src="user/js/active.js"></script>
</body>
</html>