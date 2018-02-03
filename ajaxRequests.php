<?php include 'core/init.php';
$user = new User();

/*~~~~~~~~~~~* CHECK USERNAME EXISTS OR NOT *~~~~~~~~~~~*/
if(Input::get('username')){
  echo DB::getInstance()->CheckValue('users', array('username', Input::get('username')));
}

/*~~~~~~~~~~~* CHECK EMAIL EXISTS OR NOT *~~~~~~~~~~~*/
if(Input::get('email')){
  echo DB::getInstance()->CheckValue('users', array('email', Input::get('email')));
}

/*~~~~~~~~~~~~~~~~~~~~~~~~*SET or REMOVE LIKE BY AJAX*~~~~~~~~~~~~~~~~~~~~~~~~*/
if(Input::get('csrf_token_like') && is_numeric(Input::get('msg_id')) ){
  if(Token::CheckLike('messages_csrf', Input::get('csrf_token_like'))){
    if($user->isLoggedIn()){
      echo Messages::getInstance()->isLiked( $user->data()->id,  Input::get('msg_id'));
    }
  }
}

/*~~~~~~~~~~~* DELETE MESSAGE *~~~~~~~~~~~*/
if(Input::get('csrf_token_remove') && is_numeric(Input::get('remove_msg_id')) ){
  if(Token::CheckLike('messages_csrf', Input::get('csrf_token_remove'))){
    if($user->isLoggedIn()){
      echo (Messages::getInstance()->delete( $user->data()->id,  Input::get('remove_msg_id'))) ? 1 : 0;
    }
  }
}


/*~~~~~~~~~~~* SEND VERIFICATION LINK TO THE USER *~~~~~~~~~~~*/
if(Token::CheckLike('verify_token', Input::get('verify_token'))){
  if($user->isLoggedIn()){
    $actual_link = str_replace("ajaxRequests.php", "", $actual_link);
    $to = $user->data()->email;//$user->data()->email
    $hash = $user->data()->email_hash;
    $id = $user->data()->id;
    $subject = "Verify Your Email";
    $link = $actual_link."verification.php?verify_token=$hash&user_id=$id";
    $message = "Verify Your Email from this link -> $link";
    if(mail($to, $subject, $message)){
      echo 'We\'ve Sent You an email.';
    }
  }
}
