<?php require_once 'header.php';
if(Input::get('verify_token', 'get') && Input::get('user_id', 'get')){
  if(!$user->isLoggedIn()){
    $id = Input::get('user_id');
  }else{
    $id = $user->data()->id;
  }
  if($user->data()->email_hash === Input::get('verify_token', 'get')){
    if(DB::getInstance()->update('users', $id, array('email_verify' => '1'))){
      $headerMsg = "Thank you for verifying your email";
    }else{
      $headerMsg = "Some error happened please contact the admin -> ".Config::get('contact/email');
    }
  }else{
    $headerMsg = "Invalid parameters";
  }
}else{
  Redirect::to('index.php');
}
?>
<div class="verifing">
  <div class="container">
    <div class="verify-box">
      <div class="status"></div>
      <h2 class="page-header"><?php echo @$headerMsg; ?></h2>
      <p class="lead">
        This is your email  <?php echo $user->data()->email; ?>
      </p>
    </div>
  </div>
</div>
<?php require_once 'footer.php'; ?>
