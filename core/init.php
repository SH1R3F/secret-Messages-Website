<?php
session_start();
$site_name = 'ASRAR';

$GLOBALS['config'] = array(
  'database'     => array(
    'host'     => 'localhost', //your first database host (users database)
    'users_db' => 'users',     //your database name (for users)
    'username' => 'user',      //your database user who have grants to update, delete and insert
    'password' => 'password',  //your database user's password

  	'host2' => 'localhost',   //your second database host (messages database)
    'messg_db' => 'messages', //your database name (for messages)
    'msgUsrNm' => 'user',     //your database user who have grants to update, delete and insert
    'msgPsWrd' => 'password'  //your database user's password
  ),

  'social_links' => array(
    'vk'       => 'https://www.facebook.com/Slumdog.Mellionare',
    'twitter'  => 'https://www.facebook.com/Slumdog.Mellionare',
    'facebook' => 'https://www.facebook.com/Slumdog.Mellionare',
    'author'   => 'https://www.facebook.com/Slumdog.Mellionare'
  ),

  'sessions' => array(
    'session_name' => 'user',
  ),

  'cookies' => array(
    'cookie_name'   => 'remember',
    'cookie_expiry' => '604800'
  ),

  'contact' => array(
    'email' => 'lord.zukoh@gmail.com'
  )
);

spl_autoload_register(function($class){
  require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';
require_once 'functions/array.php';
$user = new User();

if(Cookie::exists(Config::get('cookies/cookie_name')) && !Session::exists(Config::get('sessions/session_name'))){
  $hash = Cookie::get(Config::get('cookies/cookie_name'));
  $hashCheck = DB::getInstance()->GetData('users_session', array('hash', $hash));
  if($hashCheck->count()){
    $user = new User($hashCheck->first()->user_id);
    $user->login();
  }
}


$page = basename($_SERVER['PHP_SELF']);
if(!$user->isLoggedIn()){
  if($page === 'messages.php' || $page === 'settings.php' || $page === 'logout.php' || $page === 'verify.php' || $page === 'remove.php'){
    Redirect::to('login.php');
  }
}else{
  if($user->data()->email_verify){
    if($page === 'verify.php' || $page === 'verification.php'){
      Redirect::to('messages.php');
    }
  }
  if($page === 'login.php' || $page === 'register.php'){
    Redirect::to('messages.php');
  }
}

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



if($page === 'send.php'){
  if(Input::exists('get')){
    $username = Input::get('user', 'get');
    $newUser = new User($username);
    if(!$newUser->data()){
      Redirect::to('index.php');
    }
  }
}
