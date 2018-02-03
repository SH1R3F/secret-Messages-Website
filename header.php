<?php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <noscript>
      <meta http-equiv="refresh" content="0;URL='nojs.php'" />
    </noscript>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://sarahah.com/img/favicon.png" rel="icon" />
    <title>
      <?php

      switch($page){
        case 'register.php':
          echo 'Sign up for '.$site_name;
        break;
        case 'login.php':
          echo 'Login to '.$site_name;
        break;
        case 'about.php':
          echo 'About Us | '.$site_name;
        break;
        case 'contact.php':
          echo 'Contact Us | '.$site_name;
        break;
        case 'index.php':
          echo $site_name;
        break;
        case 'messages.php':
          echo 'Your Profile | '.$site_name;
        break;
        case 'privacy.php':
          echo 'Privaacy Policy | '.$site_name;
        break;
        case 'settings.php':
          echo 'Your Settings | '.$site_name;
        break;
        case 'terms.php':
          echo 'Terms and Conditions | '.$site_name;
        break;
        case 'send.php':
          echo $site_name.' | '.$newUser->data()->name ;//replace name to be dynamic
        break;
        case 'verify.php':
          echo $site_name.' - verify your email';
        break;
        default:
          echo $site_name;
        break;
      }

      ?>
    </title>

    <!-- including style files -->
    <link href="https://fonts.googleapis.com/css?family=Abel|Cairo|Exo+2" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/font-awesome.min.css" rel="stylesheet"/>
    <link href="css/style.css" rel="stylesheet"/>

    <!-- including Script files -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>
  <body>
    <!-- Starting Navbar -->
    <nav class='navbar navbar-default' id='Main_navbar'>
      <div class="container">
        <div class='navbar-header'>
          <button aria-expanded='false' class='navbar-toggle' data-target='#mainNavigation' data-toggle='collapse' type='button'>
            <span class='sr-only'>Menu</span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
            <span class='icon-bar'></span>
          </button>
          <a href="index.php" class='navbar-brand'>
            <?php echo $site_name;?>
          </a>
        </div>
        <div class='collapse navbar-collapse' id='mainNavigation'>
          <ul class='nav navbar-nav pull-right'>
            <?php
            if($user->isLoggedIn()){ ?>
              <li>
                <a href="messages.php">Messages</a>
              </li>
              <li>
                <a href="settings.php">Settings</a>
              </li>
              <li>
                <a href="about.php">About Us</a>
              </li>
              <li>
                <a href="logout.php">Logout</a>
              </li>
            <?php }else{ ?>
              <li>
                <a href="register.php">Register</a>
              </li>
              <li>
                <a href="login.php">Login</a>
              </li>
              <li>
                <a href="about.php">About Us</a>
              </li>
              <li>
                <a href="contact.php">Contact Us</a>
              </li>
            <?php }
            ?>
          </ul>
        </div>
      </div>
    </nav>
<?php
  if($user->isLoggedIn() && !$user->data()->email_verify && $page !== 'verify.php' && $page !== 'index.php' && $page !== 'verification.php'){
    ?>

    
    <!-- IF EMAIL IS NOT VERIFIED SHOW USER AN ALERT -->
    <div class="container">
      <div class="alert alert-info">
        <p class="lead">
          Verify Your Email. Is this Your Email -> '<?php echo $user->data()->email; ?>'&nbsp;&nbsp;<a href="verify.php" style="text-decoration: underline;">Verify It Right Now</a>
        </p>
      </div>
    </div>
    <?php
  }
?>
