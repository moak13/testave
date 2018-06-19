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

<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Ave Voting App</title>
    <meta name="description" content="Landmark University Community Dedicated Voting Platform">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="user/images/apple-touch-icon.png">
    <link rel="shortcut icon" type="image/ico" href="user/images/favicon.ico" />

    <!-- Plugin-CSS -->
    <link rel="stylesheet" href="user/css/bootstrap.min.css">
    <link rel="stylesheet" href="user/css/owl.carousel.min.css">
    <link rel="stylesheet" href="user/css/themify-icons.css">
    <link rel="stylesheet" href="user/css/animate.css">
    <link rel="stylesheet" href="user/css/magnific-popup.css">

    <!-- Main-Stylesheets -->
    <link rel="stylesheet" href="user/css/space.css">
    <link rel="stylesheet" href="user/css/theme.css">
    <link rel="stylesheet" href="user/css/overright.css">
    <link rel="stylesheet" href="user/css/normalize.css">
    <link rel="stylesheet" href="user/css/style.css">
    <link rel="stylesheet" href="user/css/responsive.css">
    <script src="user/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body data-spy="scroll" data-target="#mainmenu" data-offset="50">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <div class="preloade">
        <span><i class="ti-rocket"></i></span>
    </div>

    <!--Header-Area-->
    <header class="blue-bg relative fix" id="home">
        <div class="section-bg overlay-bg dewo ripple"></div>
        <div class="particles-js" id="particles-js"></div>
        <!--Mainmenu-->
        <nav class="navbar navbar-default mainmenu-area navbar-fixed-top" data-spy="affix" data-offset-top="60">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" data-toggle="collapse" class="navbar-toggle" data-target="#mainmenu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="#" class="navbar-brand">
                        <!--<img src="images/logo.png" alt="">-->
                        <h2 class="text-white logo-text">ave</h2>
                    </a>
                </div>
                <div class="collapse navbar-collapse navbar-right" id="mainmenu">
                    <ul class="nav navbar-nav">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#work">Work</a></li>
                        <li><a href="#feature">Become A Contestant</a></li>
                        <li><a href="#team">Team</a></li>
                        <li><a href="#client">Client</a></li>
                        <li><a href="#price">Pricing</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li>
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown">{{user.regno}}</a>
                            <div class="dropdown-menu" style="background-color: transparent, padding: 3px;">
                                <a class=" dropdown-item" href="#">Change Password</a>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                        <li><a href="#">Get Started</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--Mainmenu/-->
        <div class="space-100"></div>
        <div class="space-20 hidden-xs"></div>
        <!--Header-Text-->
        <div class="container text-white">
            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <div class="space-100"></div>
                    <div class="home_screen_slide_main">
                        <div class="home_text_slide">
                            <div class="item">
                                <h1>Ave <br />Voting Platform</h1>
                                <div class="space-10"></div>
                                <p>Another Web Application built by FSTACKDEV </p>
                                <div class="space-50"></div>
                            </div>
                            <div class="item">
                                <h1>College Of <br />Agricultural Sciences</h1>
                                <div class="space-10"></div>
                                <p>We teal the land and plant those crops </p>
                                <div class="space-50"></div>
                            </div>
                            <div class="item">
                                <h1>College Of <br />Business Sciences</h1>
                                <div class="space-10"></div>
                                <p>Keeping in touch with the world's finance </p>
                                <div class="space-50"></div>
                            </div>
                            <div class="item">
                                <h1>College Of <br />Science and Engineering</h1>
                                <div class="space-10"></div>
                                <p>Creating solution anytime anywhere </p>
                                <div class="space-50"></div>
                            </div>
                            <div class="item">
                                <h1>Welcome To <br />College Week 2019!</h1>
                                <div class="space-10"></div>
                                <p>Bringing every department together with Joy and Happiness </p>
                                <div class="space-50"></div>
                            </div>
                        </div>
                        <h3 id="Timercount"></h3>
                    </div>
                </div>
                <div class="hidden-xs hidden-sm col-md-4">
                    <div class="auth"></div>
                    <!--<div class="home_screen_slide">
                        <div class="single_screen_slide wow fadeInRight">
                            <div class="item"><img src="images/screen/screen1.jpg" alt=""></div>
                            <div class="item"><img src="images/screen/screen2.jpg" alt=""></div>
                            <div class="item"><img src="images/screen/screen3.jpg" alt=""></div>
                            <div class="item"><img src="images/screen/screen4.jpg" alt=""></div>
                            <div class="item"><img src="images/screen/screen5.jpg" alt=""></div>
                        </div>
                    </div>-->
                    <!--<div class="screens">
                    <span><img src="user/imgages/preview/preview_1.jpg" alt="" class="img-responsive" data-parallax='{"y": 40}'></span>
                    <span><img src="assest/img/demo/preview_2.jpg" alt="" class="img-responsive" data-parallax='{"y": -150}'></span>
                    <span><img src="assest/img/demo/preview_4.jpg" alt="" class="img-responsive" data-parallax='{"y": -150}'></span>
                    <span><img src="assest/img/demo/preview_5.jpg" alt="" class="img-responsive" data-parallax='{"y": 40}'></span>
                    <span><img src="assest/img/demo/preview_3.jpg" alt="" class="img-responsive" data-parallax='{"y": -70}'></span>
                    </div>
                    <div class="home_screen_nav">
                        <span class="ti-angle-left testi_prev"></span>
                        <span class="ti-angle-right testi_next"></span>
                    </div>-->
                </div>
            </div>
            <div class="space-80"></div>
        </div>
        <!--Header-Text/-->
    </header>
    <!--Header-Area/-->
    <section>
        <div class="space-80"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="well well-hover text-center">
                        <p class="md-icon"><span class="ti-paint-bucket"></span></p>
                        <div class="space-10"></div>
                        <h5 class="text-uppercase">Easy to use</h5>
                        <p>Ave is designed to quickly allow easy voting experince anytime anywhere.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="well well-hover text-center">
                        <p class="md-icon"><span class="ti-cup"></span></p>
                        <div class="space-10"></div>
                        <h5 class="text-uppercase">Awesoem Design</h5>
                        <p>It's built to capture your hearts and render your fun.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Work-Section-->
    <section class="gray-bg" id="work">
        <div class="space-80"></div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
                    <h3 class="text-uppercase">How it works</h3>
                    <p>The following states the simple route on how this platform works. Any attempt to do otherwise just lands you in a long long wastage of time.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="hover-shadow">
                        <div class="space-60">
                            <img src="user/images/icon/icon1.png" alt="">
                        </div>
                        <div class="space-20"></div>
                        <h5 class="text-uppercase">Login First</h5>
                        <p>You login to the platform with your valid credentials to access the various categories on this platform for voting. This obviously can't be done without you registering first.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp" data-wow-delay="0.4s">
                    <div class="hover-shadow">
                        <div class="space-60">
                            <img src="user/images/icon/icon2.png" alt="">
                        </div>
                        <div class="space-20"></div>
                        <h5 class="text-uppercase">DATA ANALYSIS</h5>
                        <p>Lorem ipsum dolor sit ameteped consecteadop adipisicing elitab sed eiusmod temporara incident</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp" data-wow-delay="0.6s">
                    <div class="hover-shadow">
                        <div class="space-60">
                            <img src="user/images/icon/icon3.png" alt="">
                        </div>
                        <div class="space-20"></div>
                        <h5 class="text-uppercase">Face Testing</h5>
                        <p>Lorem ipsum dolor sit ameteped consecteadop adipisicing elitab sed eiusmod temporara incident</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 text-center wow fadeInUp" data-wow-delay="0.8s">
                    <div class="hover-shadow">
                        <div class="space-60">
                            <img src="user/images/icon/icon4.png" alt="">
                        </div>
                        <div class="space-20"></div>
                        <h5 class="text-uppercase">SHOW RESULT</h5>
                        <p>Lorem ipsum dolor sit ameteped consecteadop adipisicing elitab sed eiusmod temporara incident</p>
                    </div>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2 text-center wow fadeInUp">
                    <div class="down-offset ">
                        <img src="images/mobile1.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Work-Section/-->
     <!--Download-Section-->
     <section class="relative fix">
        <div class="space-80"></div>
        <div class="section-bg overlay-bg">
            <img src="user/images/bg3.jpg" alt="">
        </div>
        <div class="container" id="feature">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center text-white">
                    <h3 class="text-uppercase">Become a contestant</h3>
                    <p>Kindly click the button below to register as a canditate for a particular category. Please do know that Terms and Conditions do apply for approval into selected choice category.</p>
                </div>
            </div>
            div class="row text-white wow fadeInUp">
                <div class="col-xs-12 col-sm-4">
                    <a href="#" class="big-button floatright">
                        <span>Kindly</span>
                        <br>
                        <strong>Apply</strong>
                        <span class="big-button-icon">
                            <span class="ti-angle-right"></span>
                        </span>
                    </a>
                    <div class="space-10"></div>
                </div>
            </div><div class="space-60"></div>
            <
        </div>
        <div class="space-80"></div>
    </section>
    <!--Download-Section/-->
    <!--Video-Section-->
    <section class="section-video relative fix">
        <div class="section-bg overlay-bg alpha">
        </div>
        <video class="ivideo" id="cvideo">
            <source src="http://quomodosoft.com/videos/App_Reminisound_Promotion_Video.webm" type="video/webm">
        </video>
        <div class="section-video-text text-center">
            <button type="button" id="vbutton" class="video-button"><span class="ti-control-play"></span></button>
            <div class="space-50"></div>
            <h2 class="text-white">Watch Video Demo</h2>
        </div>
    </section>
    <!--Video-Section/-->
    <!--Screenshot-Section-->
    <section>
        <div class="space-80"></div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
                    <h3 class="text-uppercase">APP SCREENSHOTS</h3>
                    <p>Lorem ipsum madolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor coli incididunt ut labore Lorem ipsum madolor sit amet.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row wow fadeIn">
                <div class="col-xs-12">
                    <div class="space-60"></div>
                    <div class="list_screen_slide">
                        <div class="item">
                            <a href="images/screen/screen1.jpg" class="work-popup">
                                <img src="images/screen/screen1.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen2.jpg" class="work-popup">
                                <img src="images/screen/screen2.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen3.jpg" class="work-popup">
                                <img src="images/screen/screen3.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen4.jpg" class="work-popup">
                                <img src="images/screen/screen4.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen5.jpg" class="work-popup">
                                <img src="images/screen/screen5.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen1.jpg" class="work-popup">
                                <img src="images/screen/screen1.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen2.jpg" class="work-popup">
                                <img src="images/screen/screen2.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen3.jpg" class="work-popup">
                                <img src="images/screen/screen3.jpg" alt="">
                            </a>
                        </div>
                        <div class="item">
                            <a href="images/screen/screen4.jpg" class="work-popup">
                                <img src="images/screen/screen4.jpg" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="space-40"></div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Screenshot-Section/-->
    <!--Team-Section-->
    <section class="gray-bg" id="team">
        <div class="space-80"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
                    <h3 class="text-uppercase">Osthir team</h3>
                    <p>Lorem ipsum madolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor coli incididunt ut labore Lorem ipsum madolor sit amet.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="single-team relative panel fix">
                        <img src="images/team/team1.jpg" alt="">
                        <div class="team_details text-center">
                            <h5 class="text-uppercase">JEMY SEDONCE</h5>
                            <p>Co. Founder</p>
                            <div class="social-menu">
                                <hr>
                                <a href="#"><span class="ti-facebook"></span></a>
                                <a href="#"><span class="ti-twitter-alt"></span></a>
                                <a href="#"><span class="ti-linkedin"></span></a>
                                <a href="#"><span class="ti-pinterest-alt"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="single-team relative panel fix">
                        <img src="images/team/team2.jpg" alt="">
                        <div class="team_details text-center">
                            <h5 class="text-uppercase">Deborah Brown</h5>
                            <p>UX Designer</p>
                            <div class="social-menu">
                                <hr>
                                <a href="#"><span class="ti-facebook"></span></a>
                                <a href="#"><span class="ti-twitter-alt"></span></a>
                                <a href="#"><span class="ti-linkedin"></span></a>
                                <a href="#"><span class="ti-pinterest-alt"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="single-team relative panel fix">
                        <img src="images/team/team3.jpg" alt="">
                        <div class="team_details text-center">
                            <h5 class="text-uppercase">Harry Banks</h5>
                            <p>Founder</p>
                            <div class="social-menu">
                                <hr>
                                <a href="#"><span class="ti-facebook"></span></a>
                                <a href="#"><span class="ti-twitter-alt"></span></a>
                                <a href="#"><span class="ti-linkedin"></span></a>
                                <a href="#"><span class="ti-pinterest-alt"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 wow fadeInUp" data-wow-delay="0.8s">
                    <div class="single-team relative panel fix">
                        <img src="images/team/team4.jpg" alt="">
                        <div class="team_details text-center">
                            <h5 class="text-uppercase">Victoria Clark</h5>
                            <p>Creative Director</p>
                            <div class="social-menu">
                                <hr>
                                <a href="#"><span class="ti-facebook"></span></a>
                                <a href="#"><span class="ti-twitter-alt"></span></a>
                                <a href="#"><span class="ti-linkedin"></span></a>
                                <a href="#"><span class="ti-pinterest-alt"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Team-Section/-->
    <!--Client-Section-->
    <section id="client">
        <div class="space-80"></div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-8 col-md-offset-2 text-center">
                    <div class="well well-lg">
                        <div class="client-details-content">
                            <div class="client_details">
                                <div class="item">
                                    <h3>M S NEWAZ</h3>
                                    <p>Ceative Director</p>
                                    <q>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incubt consectetur aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut com modo consequat. Duis aute irure dolor in reprehenderit.</q>
                                </div>
                                <div class="item">
                                    <h3>M S NEWAZ</h3>
                                    <p>Ceative Director</p>
                                    <q>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incubt consectetur aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut com modo consequat. Duis aute irure dolor in reprehenderit.</q>
                                </div>
                                <div class="item">
                                    <h3>M S NEWAZ</h3>
                                    <p>Ceative Director</p>
                                    <q>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incubt consectetur aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut com modo consequat. Duis aute irure dolor in reprehenderit.</q>
                                </div>
                                <div class="item">
                                    <h3>M S NEWAZ</h3>
                                    <p>Ceative Director</p>
                                    <q>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incubt consectetur aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut com modo consequat. Duis aute irure dolor in reprehenderit.</q>
                                </div>
                                <div class="item">
                                    <h3>M S NEWAZ</h3>
                                    <p>Ceative Director</p>
                                    <q>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incubt consectetur aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut com modo consequat. Duis aute irure dolor in reprehenderit.</q>
                                </div>
                                <div class="item">
                                    <h3>M S NEWAZ</h3>
                                    <p>Ceative Director</p>
                                    <q>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incubt consectetur aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut com modo consequat. Duis aute irure dolor in reprehenderit.</q>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-40"></div>
            <div class="row wow fix fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 relative">
                    <div class="client-photo-list">
                        <div class="client_photo">
                            <div class="item">
                                <div class="box100">
                                    <img src="images/client/client1.png" alt="">
                                </div>
                            </div>
                            <div class="item">
                                <div class="box100">
                                    <img src="images/client/client2.png" alt="">
                                </div>
                            </div>
                            <div class="item">
                                <div class="box100">
                                    <img src="images/client/client3.png" alt="">
                                </div>
                            </div>
                            <div class="item">
                                <div class="box100">
                                    <img src="images/client/client1.png" alt="">
                                </div>
                            </div>
                            <div class="item">
                                <div class="box100">
                                    <img src="images/client/client2.png" alt="">
                                </div>
                            </div>
                            <div class="item">
                                <div class="box100">
                                    <img src="images/client/client3.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="client_nav">
                        <span class="ti-angle-left testi_prev"></span>
                        <span class="ti-angle-right testi_next"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Client-Section-->
    <!--Price-section-->
    <section class="relative fix" id="price">
        <div class="section-bg overlay-bg fix">
            <img src="images/bg2.jpg" alt="">
        </div>
        <div class="space-80"></div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center text-white">
                    <h3 class="text-uppercase">AFORTABLE PRICE</h3>
                    <p>Lorem ipsum madolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor coli incididunt ut labore Lorem ipsum madolor sit amet.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 wow fadeInLeft">
                    <div class="panel price-table text-center">
                        <h3 class="text-uppercase price-title">Basic</h3>
                        <hr>
                        <div class="space-30"></div>
                        <ul class="list-unstyled">
                            <li><strong class="amount">&#36; <span class="big">20</span></strong>/Month</li>
                            <li>100 MB Disk Space</li>
                            <li>2 Subdomains</li>
                            <li>5 Email Accounts</li>
                            <li>Webmail Support</li>
                            <li>Customer Support 24/7</li>
                        </ul>
                        <div class="space-30"></div>
                        <hr>
                        <a href="#" class="btn btn-link text-uppercase">Purchase</a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 wow flipInY">
                    <div class="panel price-table active center text-center">
                        <h3 class="text-uppercase price-title">STABDARD</h3>
                        <hr>
                        <div class="space-30"></div>
                        <ul class="list-unstyled">
                            <li><strong class="amount">&#36; <span class="big">39</span></strong>/Month</li>
                            <li>100 MB Disk Space</li>
                            <li>2 Subdomains</li>
                            <li>5 Email Accounts</li>
                            <li>Webmail Support</li>
                            <li>Customer Support 24/7</li>
                        </ul>
                        <div class="space-30"></div>
                        <hr>
                        <a href="#" class="btn btn-link text-uppercase">Purchase</a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 wow fadeInRight">
                    <div class="panel price-table text-center">
                        <h3 class="text-uppercase price-title">UNLIMITED</h3>
                        <hr>
                        <div class="space-30"></div>
                        <ul class="list-unstyled">
                            <li><strong class="amount">&#36; <span class="big">59</span></strong>/Month</li>
                            <li>100 MB Disk Space</li>
                            <li>2 Subdomains</li>
                            <li>5 Email Accounts</li>
                            <li>Webmail Support</li>
                            <li>Customer Support 24/7</li>
                        </ul>
                        <div class="space-30"></div>
                        <hr>
                        <a href="#" class="btn btn-link text-uppercase">Purchase</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Price-section/-->
    <!--Question-section-->
    <section class="fix">
        <div class="space-80"></div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
                    <h3 class="text-uppercase">Frequently asked questions</h3>
                    <p>Lorem ipsum madolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor coli incididunt ut labore Lorem ipsum madolor sit amet.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 col-md-6 wow fadeInUp">
                    <div class="space-60"></div>
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Sedeiusmod tempor inccsetetur aliquatraiy? </a></h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodas temporo incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrd exercitation ullamco laboris nisi ut aliquip ex comodo consequat. Duis aute dolor in reprehenderit.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Tempor inccsetetur aliquatraiy?</a></h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Lorem ipsum dolor amet, consectetur adipisicing ?</a></h4>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Lorem ipsum dolor amet, consectetur adipisicing ?</a></h4>
                            </div>
                            <div id="collapse4" class="panel-collapse collapse">
                                <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden-xs hidden-sm col-md-5 col-md-offset-1 wow fadeInRight ">
                    <img src="images/2mobile.png" alt="">
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Question-section/-->
    <!--Download-Section-->
    <section class="relative fix">
        <div class="space-80"></div>
        <div class="section-bg overlay-bg">
            <img src="images/bg3.jpg" alt="">
        </div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center text-white">
                    <h3 class="text-uppercase">Download appro Taday</h3>
                    <p>Lorem ipsum madolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor coli incididunt ut labore Lorem ipsum madolor sit amet.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row text-white wow fadeInUp">
                <div class="col-xs-12 col-sm-4">
                    <a href="#" class="big-button alignright">
                        <span class="big-button-icon">
                            <span class="ti-android"></span>
                        </span>
                        <span>available on</span>
                        <br>
                        <strong>Play store</strong>
                    </a>
                    <div class="space-10"></div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <a href="#" class="big-button aligncenter">
                        <span class="big-button-icon">
                            <span class="ti-android"></span>
                        </span>
                        <span>available on</span>
                        <br>
                        <strong>Play store</strong>
                    </a>
                    <div class="space-10"></div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <a href="#" class="big-button alignleft">
                        <span class="big-button-icon">
                            <span class="ti-android"></span>
                        </span>
                        <span>available on</span>
                        <br>
                        <strong>Play store</strong>
                    </a>
                    <div class="space-10"></div>
                </div>
            </div>
        </div>
        <div class="space-80"></div>
    </section>
    <!--Download-Section/-->
    <!--Blog-Section-->
    <section id="blog">
        <div class="space-80"></div>
        <div class="container">
            <div class="row wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
                    <h3 class="text-uppercase">LATEST FROM BLOG</h3>
                    <p>Lorem ipsum madolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor coli incididunt ut labore Lorem ipsum madolor sit amet.</p>
                </div>
            </div>
            <div class="space-60"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="panel text-center single-blog">
                        <img src="images/blog/blog1.jpg" class="img-full" alt="">
                        <div class="padding-20">
                            <ul class="list-unstyled list-inline">
                                <li><span class="ti-user"></span> By: Admin</li>
                                <li><span class="ti-calendar"></span> Feb 01, 2017</li>
                            </ul>
                            <div class="space-10"></div>
                            <a href="blog-details-left-sidebar.html"><h3>Beautiful Place for your Great Journey</h3></a>
                            <div class="space-15"></div>
                            <p>Lorem dolor sit amet, consectetur floralm adipisicing elit, sed do eiusmod tem aincididunt elauta labore eta dolore magna aliqualy eminem faenimve...</p>
                            <div class="space-20"></div>
                            <a href="blog-details-right-sidebar.html" class="btn btn-link">Read more</a>
                            <div class="space-20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="panel text-center single-blog">
                        <img src="images/blog/blog2.jpg" class="img-full" alt="">
                        <div class="padding-20">
                            <ul class="list-unstyled list-inline">
                                <li><span class="ti-user"></span> By: Admin</li>
                                <li><span class="ti-calendar"></span> Feb 01, 2017</li>
                            </ul>
                            <div class="space-10"></div>
                            <a href="blog-details-right-sidebar.html"><h3>Beautiful Place for your Great Journey</h3></a>
                            <div class="space-15"></div>
                            <p>Lorem dolor sit amet, consectetur floralm adipisicing elit, sed do eiusmod tem aincididunt elauta labore eta dolore magna aliqualy eminem faenimve...</p>
                            <div class="space-20"></div>
                            <a href="blog-details-right-sidebar.html" class="btn btn-link">Read more</a>
                            <div class="space-20"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="panel text-center single-blog">
                        <img src="images/blog/blog3.jpg" class="img-full" alt="">
                        <div class="padding-20">
                            <ul class="list-unstyled list-inline">
                                <li><span class="ti-user"></span> By: Admin</li>
                                <li><span class="ti-calendar"></span> Feb 01, 2017</li>
                            </ul>
                            <div class="space-10"></div>
                            <a href="blog-details-left-sidebar.html"><h3>Beautiful Place for your Great Journey</h3></a>
                            <div class="space-15"></div>
                            <p>Lorem dolor sit amet, consectetur floralm adipisicing elit, sed do eiusmod tem aincididunt elauta labore eta dolore magna aliqualy eminem faenimve...</p>
                            <div class="space-20"></div>
                            <a href="blog-details-right-sidebar.html" class="btn btn-link">Read more</a>
                            <div class="space-20"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="space-60"></div>
                <div class="col-xs-12 text-center">
                    <a href="blog.html" class="btn btn-link active">View All</a>
                </div>
                <div class="space-60"></div>
            </div>
        </div>
    </section>
    <!--Blog-Section/-->
    <hr>
    <!--instagram section-->
    <div class="container">
        <div class="space-80"></div>
        <div class="row">
            <div class="col-xs-12">
                <ul class="instagram instagram-slide list-unstyle list-inline"></ul>
                <div class="space-80"></div>
            </div>
        </div>
    </div>
    <!--instagram section/-->
    <!--Map-->
    <div id="contact"></div>
    <div id="maps"></div>
    <!--Map/-->
    <!--Footer-area-->
    <footer class="black-bg">
        <div class="container">
            <div class="row">
                <div class="offset-top">
                    <div class="col-xs-12 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="well well-lg">
                            <h3>Get in Touch</h3>
                            <div class="space-20"></div>
                            <form action="http://quomodosoft.com/html/appro/demo/process.php" id="contact-form" method="post">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="form-name" class="sr-only">Name</label>
                                            <input type="text" class="form-control" id="form-name" name="form-name" placeholder="Name" required>
                                        </div>
                                        <div class="space-10"></div>
                                    </div>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="form-email" class="sr-only">Email</label>
                                            <input type="email" class="form-control" id="form-email" name="form-email" placeholder="Email" required>
                                        </div>
                                        <div class="space-10"></div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="form-subject" class="sr-only">Email</label>
                                            <input type="text" class="form-control" id="form-subject" name="form-subject" placeholder="Subject" required>
                                        </div>
                                        <div class="space-10"></div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="form-message" class="sr-only">comment</label>
                                            <textarea class="form-control" rows="6" id="form-message" name="form-message" placeholder="Message" required></textarea>
                                        </div>
                                        <div class="space-10"></div>
                                        <button class="btn btn-link no-round text-uppercase" type="submit">Send message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="well well-lg">
                            <h3>Address</h3>
                            <div class="space-20"></div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis ab quia officia, minus obcaecati corporis! Tenetur tempore, inventore cum sapiente minima accusantium illo animi, doloribus rerum deleniti cumque, consequatur eaque in unde facilis consectetur, eius eligendi nostrum. Facilis, recusandae, eos!</p>
                            <div class="space-25"></div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="border-icon sm"><span class="ti-headphone"></span></div>
                                        </td>
                                        <td><a href="callto:+0044545989626">+0044 545 989 626</a></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="border-icon sm"><span class="ti-email"></span></div>
                                        </td>
                                        <td>
                                            <a href="mailto:marveltheme@gmail.com">marveltheme@gmail.com</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="border-icon sm"><span class="ti-location-pin"></span></div>
                                        </td>
                                        <td>
                                            <address>28 Green Tower, Street Name New York City, USA</address>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-80"></div>
            <div class="row text-white wow fadeInUp">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <h3 class="text-uppercase text-center">SubscribE OUR NEWSLETTER</h3>
                    <div class="space-15"></div>
                    <form id="mc-form" class="subscrie-form">
                        <label class="mt10" for="mc-email"></label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="mc-email" placeholder="Your email address here...">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-info">Subscribe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="space-80"></div>
            <div class="row text-white wow fadeIn">
                <div class="col-xs-12 text-center">
                    <div class="social-menu">
                        <a href="#"><span class="ti-facebook"></span></a>
                        <a href="#"><span class="ti-twitter-alt"></span></a>
                        <a href="#"><span class="ti-linkedin"></span></a>
                        <a href="#"><span class="ti-pinterest-alt"></span></a>
                    </div>
                    <div class="space-20"></div>
                    <p>@  <a href="https://themeforest.net/user/themectg">ThemeCTG</a> all right resurved. Designed by <a href="https://themeforest.net/user/quomodotheme">Quomodotheme</a></p>
                </div>
            </div>
            <div class="space-20"></div>
        </div>
    </footer>
    <!--Footer-area-->

    <!--Vendor JS-->
    <script src="user/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="user/js/vendor/bootstrap.min.js"></script>
    <!--Plugin JS-->
    <script src="user/js/owl.carousel.min.js"></script>
    <script src="user/js/scrollUp.min.js"></script>
    <script src="user/js/magnific-popup.min.js"></script>
    <script src="user/js/ripples-min.js"></script>
    <script src="user/js/contact-form.js"></script>
    <script src="user/js/spectragram.min.js"></script>
    <script src="user/js/particles.min.js"></script>
    <script src="user/js/particles-app.js"></script>
    <script src="user/js/ajaxchimp.js"></script>
    <script src="user/js/wow.min.js"></script>
    <script src="user/js/plugins.js"></script>
    <!--Active JS-->
    <script src="user/js/main.js"></script>
    <!--Maps JS-->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTS_KEDfHXYBslFTI_qPJIybDP3eceE-A&amp;sensor=false"></script>
    <script src="user/js/maps.js"></script>
    <script>
                // Set the date we're counting down to
	    var countDownDate = new Date("April 11, 2019 23:59:59").getTime();

	    // Update the count down every 1 second
	    var x = setInterval(function() {

	      // Get todays date and time
	      var now = new Date().getTime();

	      // Find the distance between now an the count down date
	      var distance = countDownDate - now;

	      // Time calculations for days, hours, minutes and seconds
	      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

	      // Display the result in the element with id="demo"
	      document.getElementById("Timercount").innerHTML = days + "d " + hours + "h "
	      + minutes + "m " + seconds + "s ";

	      // If the count down is finished, write some text
	      if (distance < 0) {
	        clearInterval(x);
	        document.getElementById("Timercount").innerHTML = "VOTING HAS NOW CLOSED";
	      }
	    }, 1000);

	</script>
</body>

</html>


