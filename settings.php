<?php require_once 'core/init.php';
if(Input::exists()){

  /*~~~~~~~~~~~~~~~* TOKEN OF THE FIRST SECTION *~~~~~~~~~~~~~~~*/
  if(Input::get('name') && Input::get('username')){//change with btn name

    /*~~~~~~~~~~* IF FIELDS HAVE CHANGEd *~~~~~~~~~~*/
    $notify = $user->data()->notifications;             //FOR NOTIFICATION RECOGNIZING
    $notifi = (Input::get('notification')) ? '1' : '0'; //FOR NOTIFICATION RECOGNIZING
    if(Input::get('name') !== $user->data()->name || Input::get('username') !== $user->data()->username || $_FILES['photo']['tmp_name'] || $notifi !== $notify){
       if(Token::check('personal_settings', Input::get('personal_settings'))){
         $validation = new Validation();
         $validation->check($_POST, array(
           'name'       => array(
             'required' => true,
             'min'      => '4',
             'max'      => '50',
             'regexp'   => '/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю]+$/'
           ),
           'username' => array(
             'required' => true,
             'min'      => '2',
             'max'      => '50',
             'unique'   => 'users',
             'regexp'   => '/^\w*[a-zA-Z]\w*$/i'
           ),
           'photo'    => array(
             'extension' => array('jpg', 'jpeg', 'png')
           )
         ));
         if($validation->isPassed()){
           $fields = array();
           if(Input::get('name') !== $user->data()->name){
             $fields['name'] = Input::get('name');
           }
           if(Input::get('username') !== $user->data()->username){
             $fields['username'] = Input::get('username');
           }
           if($_FILES['photo']['tmp_name']){
             $SQLimage = base64_encode(file_get_contents($_FILES['photo']['tmp_name']));
             $SQLimage = 'data:image/'.strtolower(pathinfo(basename($_FILES["photo"]["name"]),PATHINFO_EXTENSION)).';base64,'.$SQLimage;
             $fields['photo'] = $SQLimage;
           }
           if($notifi !== $notify){
             $fields['notifications'] = $notifi;
           }
           if(DB::getInstance()->update('users', $user->data()->id, $fields)){
             $changes1 = "<div class='alert alert-success'>Your Changes Have Been Saved</div>";
           }else{
             $changes1 = "<div class='alert alert-danger'>Your Changes Have Not Been Saved</div>";
           }
         }else{
           $errorMSG = $validation->ShowErrors();
         }
       }else{
         Redirect::refresh();
       }
    }
  }


  /*~~~~~~~~~~~~~~~* The Second Section (passwords) *~~~~~~~~~~~~~~~*/
  if(Input::get('password') && Input::get('newpassword') && Input::get('confnewpassword')){

    /*~~~~~~~~~~* IF FIELDS HAVE CHANGEd *~~~~~~~~~~*/
     if(Token::check('password_change', Input::get('password_change'))){

       /*~~~~~~~~* CHECK FOR ENTERED PASSWORD *~~~~~~~~*/
       if(Hash::make(Input::get('password'), $user->data()->salt) === $user->data()->password){
         $validation = new Validation();
         $validation->check($_POST, array(
           'newpassword'     => array(
             'required' => true,
             'min'      => '8',
             'max'      => '80',
             'regexp'   => '/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю\.\-~@]+$/i'
           ),
           'confnewpassword' => array(
             'required' => true,
             'min'      => '8',
             'max'      => '80',
             'regexp'   => '/^[\w абвгдеёжзийклмнопрстуфхцчшщъыьэю\.\-~@]+$/i',
             'identical' => 'newpassword'
           )
         ));
         if($validation->isPassed()){
           if(DB::getInstance()->update('users', $user->data()->id, array(
             'password' => Hash::make(Input::get('newpassword'), $user->data()->salt)
           ))){
             $changes2 = "<div class='alert alert-success'>Your Changes Have Been Saved</div>";
           }else{
             $changes2 = "<div class='alert alert-danger'>Your Changes Have Not Been Saved</div>";
           }
         }else{
           $errorMSG = $validation->ShowErrors();
         }
       }else{
         $errorMSG['password'] = 'Your Password Is Incorrect';
       }
     }else{
       Redirect::refresh();
     }
  }
}
require_once 'header.php'; ?>
<section class="settings" id="settings">
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <div class="categories">
          <ul><h4>Settings</h4>
            <li class="active settings-cat">
              <a href="#personal_informations"><i class="fa fa-user"></i>Personal Information</a>
            </li>
            <li class="settings-cat">
              <a href="#change_password"><i class="fa fa-lock"></i>Password</a>
            </li>
            <li class="settings-cat">
              <a href="#remove_account"><i class="fa fa-trash-o"></i>Remove Account</a>
            </li>
            <li>
              <a href="messages.php"><i class="fa fa-comments"></i>Back to messages</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-sm-9" id="content-area">
        <div class="setting" id="personal_informations">
          <h3 class="page-header bold">Edit personal information</h3>
          <?php echo @$changes1; ?>
          <form class="form-horizontal" role="form" method="post" action="<?php echo scape($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <div class="form-group row">
              <label for="name" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo ( !Input::get('name') )?$user->data()->name: Input::get('name'); ?>">
                <?php echo "<p class='text-danger'>".@$errorMSG['name']."</p>";?>
              </div>
            </div>
            <div class="form-group row">
              <label for="username" class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo ( !Input::get('username') )?$user->data()->username: Input::get('username'); ?>">
                <?php echo "<p class='text-danger'>".@$errorMSG['username']."</p>";?>
              </div>
            </div>


            <?php
            /* !~~~~ HIDDEN BECAUSE I DIDN'T ADD THIS FEATURE YET.. IF YOU WANNA ADD IT YOURSELF THEN YOU NEED TO ADD IT'S VALIDATION AND SEND A VERIFICATION EMAIL TO THE NEW ADDED EMAIL ~~~~!
            <div class="form-group row">
              <label for="email" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo ( !Input::get('email') )?$user->data()->email: Input::get('email'); ?>">
                <?php echo "<p class='text-danger'>".@$errorMSG['email']."</p>";?>
              </div>
            </div>
            */
            ?>
            <div class="form-group">
              <label for="photo" class="col-sm-2 control-label">Photo</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" id="photo" name="photo">
                <p class='text-danger errPhot'><?php echo @$errorMSG['photo'];?></p>
              </div>
            </div>
            <div class="checkbox col-sm-offset-2">
              <?php
                if($user->data()->notifications == 1){
                  $defaultValue = "checked";
                }else{
                  $defaultValue = "";
                }
                if(Input::exists() && !Input::get('notification')){
                  $defaultValue = "";
                }elseif(Input::exists() && Input::get('notification')){
                  $defaultValue = "checked";
                }
              ?>
              <label><input type="checkbox" name="notification" value="1" <?php echo $defaultValue; ?>> Notification</label>
              <input type="hidden" name="personal_settings" value="<?php echo Token::generate('personal_settings'); ?>" />
            </div>
            <input type="submit" class="submit-btn btn btn-default col-sm-offset-2" name="personal_change" value="Save Changes">
          </form>
        </div>

        <div class="setting" id="change_password">
          <h3 class="page-header bold">Change password</h3>
          <?php echo @$changes2; ?>
          <form class="form-horizontal" role="form" method="post" action="<?php echo scape($_SERVER['PHP_SELF']); ?>">
            <div class="form-group row">
              <label for="password" class="col-sm-2 control-label">Current Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" placeholder="Current Password" value="">
                <?php echo "<p class='text-danger'>".@$errorMSG['password']."</p>";?>
              </div>
            </div>
            <div class="form-group row">
              <label for="newpassword" class="col-sm-2 control-label">New Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="New Password" value="">
                <?php echo "<p class='text-danger'>".@$errorMSG['newpassword']."</p>";?>
              </div>
            </div>
            <div class="form-group row">
              <label for="confnewpassword" class="col-sm-2 control-label">Confirm New Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="confnewpassword" name="confnewpassword" placeholder="Confirm New Password" value="">
                <?php echo "<p class='text-danger'>".@$errorMSG['confnewpassword']."</p>";?>
              </div>
            </div>
            <input type="hidden" name="password_change" value="<?php echo Token::generate('password_change'); ?>" />
            <button type="submit" class="submit-btn btn btn-default col-sm-offset-2" name="passwordChanger">Change</button>
          </form>



        </div>
        <div class="setting" id="remove_account">
          <h3 class="page-header bold">Remove account</h3>
          <div class="alert alert-danger bold">
            Are you sure that you want to delete your account?
            <br />
            Deleting the account is irreversible!
          </div>
          <a href="messages.php"><button type="submit" class="submit-btn btn btn-default" name="account_remover" value="1">Cancel</button></a>
          <a href="remove.php"><button type="submit" class="submit-btn danger btn btn-default" name="account_remover" value="1">Remove</button></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'footer.php'; ?>
