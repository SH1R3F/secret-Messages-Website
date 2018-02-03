<?php require_once 'header.php';
/*~~~~~~~~~* IF REQUESTED METHOD IS POST *~~~~~~~~~*/
if(Input::exists('post')){
  if(Token::check('send_csrf', Input::get('send_csrf'))){
    $validation = new Validation();
    $validating = $validation->check($_POST, array(
      'Required_Message' => array(
        'required' => true,
        'min' => '1',
        'max' => '1100',
      )
    ));
    if($validation->isPassed()){
      if(!Messages::getInstance()->IsTable('user_'.$newUser->data()->id)){
        Messages::getInstance()->CreateTable($newUser->data()->id, array(
          'id'       => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
          'message'  => 'varchar(9999) NOT NULL',
          'SentDate' => 'datetime NOT NULL',
          'category' => 'int(1) NOT NULL'
        ));
      }
      if(Messages::getInstance()->InsertNew('user_' . $newUser->data()->id, array( 'message' => htmlspecialchars($_POST['Required_Message']), 'SentDate' => date('Y-m-d H:i:s'), 'category' => 0 ))){
        ?>
        <div class="send-message">
          <div class="container">
            <div class="sending-box">
              <div class="message-box text-center">
                <p class="lead" style="margin-top: 60px;">Thank You For Your Message... Feel free to try <?php echo $site_name; ?></p>
                <a href="register.php">Register</a>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
    }
  }else{
    Redirect::refresh();
  }
}

/*~~~~~~~~~* NORMAL PAGE LOADS NORMALLY *~~~~~~~~~*/
else{
?>
  <div class="send-message">
    <div class="container">
      <div class="sending-box">
        <?php
          if($newUser->data()->photo === ''){
            $image_src = 'http://placehold.it/100';
          }else{
            $image_src = $newUser->data()->photo;
          }
        ?>
        <div class="profile-img" style="background-image: url('<?php echo $image_src; ?>') ;">
        </div>
        <div class="message-box text-center">
          <h4 class="bold">
            <?php echo $newUser->data()->name; ?>
          </h4>
          <p>Leave a constructive message :)</p>
          <form method="post" action="<?php echo scape($_SERVER['PHP_SELF']); ?>">
            <textarea placeholder="Leave a message" name="Required_Message" value="<?php echo Input::get('Required_Message'); ?>" required></textarea>
            <?php echo @$errorMSG['Required_Message']; ?>
            <input type="hidden" name="send_csrf" value="<?php echo Token::generate('send_csrf'); ?>" />
            <button type="submit" name="submitMessage" class="submit-btn btn btn-default"> <i class="fa fa-pencil"></i> Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
}
require_once 'footer.php'; ?>
