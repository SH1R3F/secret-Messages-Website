<?php require_once 'core/init.php';
if(Input::exists()){
  /*~~~~~~~~~~~~~~* CHECK FOR TOKEN *~~~~~~~~~~~~~~*/
  if(Token::check('login_csrf', Input::get('login_csrf'))){//Token::check('login_csrf', Input::get('login_csrf'))

    /*~~~~~~~~~~~~~~* FIELDS ARE REQUIRED *~~~~~~~~~~~~~~*/
    if(Input::get('email') && Input::get('password')){
      $validation = new Validation();
      $validation->check($_POST, array(
        'email'    => array(
          'required' => true,
          'min'      => '2',
          'max'      => '70',
        ),
        'password' => array(
          'required' => true,
          'min'      => '8',
          'max'      => '80',
        )
      ));
      if($validation->isPassed()){

        /*~~~~~~~~~~~~~~* CHECK REMEMBER *~~~~~~~~~~~~~~*/
        $login = $user->login(Input::get('email'), Input::get('password'), Input::get('remember_me'));
        if($login){
          Redirect::to('messages.php');
        }else{
          $errMSG = "<p class='alert alert-danger'>Incorrect Data - <a href='#'>Forgot password</a>? or <a href='register.php'>Register</a></p>";
        }
      }
    }
  }else{
    Redirect::refresh();
  }
}
?>
<?php require_once 'header.php'; ?>
<div class="login-page">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <h1 class="page-header text-center">Login</h1>
        <div class="login-form">
          <form class="form-horizontal" role="form" method="post" action="<?php echo scape($_SERVER['PHP_SELF']); ?>">
            <?php echo @$errMSG; ?>
            <div class="form-group">
              <label for="email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email" placeholder="Please enter your email" value="<?php echo Input::get('email'); ?>">
                <p class='text-danger errName'><?php echo @$errorMSG['email'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" placeholder="Your password">
                <p class='text-danger errName'><?php echo @$errorMSG['password'];?></p>
              </div>
            </div>
            <input type="hidden" name="login_csrf" value="<?php echo Token::generate('login_csrf'); ?>" />
            <div class="checkbox text-center">
              <label><input type="checkbox" name="remember_me"> Remember me</label>
              <button type="submit" class="btn btn-default">Login</button>
            </div>
            <a href="#" class="forgot-password">Forgot My Password</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once 'footer.php'; ?>
