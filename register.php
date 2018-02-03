<?php
require_once 'core/init.php';
//CHECK IF NO USERS TABLE EXISTS AND CREATE IT
if(!DB::getInstance()->isTable('users')){
  DB::getInstance()->CreateTable('users', array(
    'id'            => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
    'name'          => 'varchar(255) NOT NULL',
    'email'         => 'varchar(255) NOT NULL',
    'password'      => 'varchar(255) NOT NULL',
    'salt'          => 'varchar(255) NOT NULL',
    'username'      => 'varchar(255) NOT NULL',
    'gender'        => 'int(1) NOT NULL',
    'country'       => 'varchar(255) NOT NULL',
    'photo'         => 'LONGBLOB',
    'notifications' => 'int(1) NOT NULL',
    'joinDate'      => 'datetime',
    'email_verify'  => 'int(1) NOT NULL',
    'email_hash'    => 'varchar(255) NOT NULL',
  ));
}
if(Input::exists() && Input::get('register_csrf')){
  if(Token::check('register_csrf', Input::get('register_csrf'))){//

    /*~~~~~~~~~~~~* VALIDATE REGISTRATION INPUTS *~~~~~~~~~~~~*/
    $validation = new Validation();
    $validation->check($_POST, array(
      'name' => array(
        'required' => true,
        'min' => '4',
        'max' => '50',
        'regexp' => '/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю]+$/'
      ),
      'email' => array(
        'required' => true,
        'min' => '5',
        'unique' => 'users',
        'max' => '70',
        'regexp' => '/^[\w\.]+@[A-z_0-9]+\.[A-z0-9]+$/'
      ),
      'password' => array(
        'required' => true,
        'min' => '8',
        'max' => '80',
        'regexp' => '/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю\.\-~@]+$/i'
      ),
      'username' => array(
        'required' => true,
        'min' => '2',
        'max' => '50',
        'unique' => 'users',
        'regexp' => '/^\w*[a-zA-Z]\w*$/i'
      ),
      'gender' => array(
        'required' => true,
      ),
      'photo' => array(
        'extension' => array('jpg', 'jpeg', 'png')
      ),
      'terms' => array(
        'required' => true
      ),
    ));

    if($validation->isPassed()){
      //submit right now
      if($_FILES["photo"]['tmp_name']){//if image is uploaded
        $SQLimage = base64_encode(file_get_contents($_FILES['photo']['tmp_name']));
        $SQLimage = 'data:image/'.strtolower(pathinfo(basename($_FILES["photo"]["name"]),PATHINFO_EXTENSION)).';base64,'.$SQLimage;;
      }else{
        $SQLimage = "";
      }
      $salt = Hash::salt(32);

      if($user->InsertNew('users', array(
          'name'          => Input::get('name'),
          'email'         => Input::get('email'),
          'password'      => Hash::make(Input::get('password'), $salt),
          'salt'          => $salt,
          'username'      => Input::get('username'),
          'gender'        => Input::get('gender'),
          'country'       => Input::get('country'),
          'photo'         => $SQLimage,
          'notifications' => Input::get('notifications'),
          'joinDate'      => date('Y-m-d H:i:s'),
          'email_verify'  => 0,
          'email_hash'    => Hash::unique(),
        ))){
          $login = $user->login(Input::get('email'), Input::get('password'));
          if($login){
            //Create Table for messages
            if(Messages::getInstance()->CreateTable('user_' . $user->data()->id, array(
              'id'       => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
              'message'  => 'varchar(9999) NOT NULL',
              'SentDate' => 'datetime NOT NULL',
              'category' => 'int(1) NOT NULL'
            ))){
              Redirect::to('messages.php');
            }else{
              echo $user->data()->id;
            }
          }
        }
    }else{
      $errs = $validation->ShowErrors();
    }
  }else{
    Redirect::refresh();
  }
}
?>

<?php require_once 'header.php'; ?>
<div class="register-page" id="register_page">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h1 class="page-header text-center">Register</h1>
        <div class="register-form">
          <form class="form-horizontal" role="form" method="post" action="<?php echo scape($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <div class="form-group">
              <label for="name" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required value="<?php echo Input::get('name'); ?>" />
                <p class='text-danger errName'><?php echo @$errs['name'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?php echo Input::get('email'); ?>" />
                <p class='text-danger errEmail'><?php echo @$errs['email'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required value="<?php echo Input::get('password'); ?>" />
                <p class='text-danger errPass'><?php echo @$errs['password'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label for="username" class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required value="<?php echo Input::get('username'); ?>" />
                <p class='text-danger errUser'><?php echo @$errs['username'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label for="gender" class="col-sm-2 control-label">Gender</label>
              <div class="col-sm-10">
                <select class="form-control" name="gender" id="gender" >
                  <option value="">-</option>
                  <option value="1">Male</option>
                  <option value="2">Female</option>
                </select>
                <p class='text-danger errGend'><?php echo @$errs['gender'];?></p>
              </div>
            </div>
            <div class="form-group">
              <label for="country" class="col-sm-2 control-label">Country</label>
              <div class="col-sm-10">
                <select class="form-control" name="country" id="country" >
                  <option value="RU">Russia</option>
                	<option value="BY">Belarus</option>
                	<option value="KZ">Kazakhstan</option>
                	<option value="KG">Kyrgyzstan</option>
                	<option value="EG">Egypt</option>
                	<option value="UA">Ukraine</option>
                	<option value="CH">Chechnya</option>
                	<option value="UZ">Uzbekistan</option>
                	<option value="TJ">Tajikistan</option>
                	<option value="TM">Turkmenistan</option>
                	<option value="RO">Romania</option>
                	<option value="CA">Canada</option>
                	<option value="US">United States</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="photo" class="col-sm-2 control-label">Photo (optional)</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" id="photo" name="photo">
                <p class='text-danger errPhot'><?php echo @$errs['photo'];?></p>
              </div>
            </div>
            <div class="checkbox col-sm-offset-2">
              <label><input type="checkbox" name="notification" value="notification"> Notification</label>
            </div>
            <div class="checkbox col-sm-offset-2">
              <label><input type="checkbox" name="terms" value="terms" > I have read and accept Terms and Conditions</label>
              <p class='text-danger errTerm'><?php echo @$errs['terms'];?></p>
            </div>
            <div class="submit-btn col-sm-offset-2">
              <input type="hidden" name="register_csrf" value="<?php echo Token::generate('register_csrf'); ?>" />
              <button type="submit" class="btn btn-default" name="register_btn" id="register_btn">Register</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php

 require_once 'footer.php';
?>
