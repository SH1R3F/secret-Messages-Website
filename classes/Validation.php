<?php


class Validation{

  private $_db     = null,
          $_errors = array(),
          $_passed = false;

  public function __construct(){
    $this->_db = DB::getInstance();
  }

  public function check($source, $items=array()){
    if(count($items)){
      foreach($items as $item => $rules){
        $value = @scape($source[$item]);
        foreach($rules as $rule => $rule_value){

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR REQUIRED FIELDS *~~~~~~~~~~~~~~~~~*/
          if($rule === 'required' && empty($value)){
            switch($item){
              case 'name':
                $this->addError($item, 'Please Enter your name');
              break;
              case 'email':
                $this->addError($item, 'Please Enter Your Email');
              break;
              case 'password':
                $this->addError($item, 'You Must Enter a Password');
              break;
              case 'newpassword':
                $this->addError($item, 'You Must Enter a Password');
              break;
              case 'confnewpassword':
                $this->addError($item, 'You Must Enter a Password');
              break;
              case 'username':
                $this->addError($item, 'Please Enter a username');
              break;
              case 'gender':
                $this->addError($item, 'Please Enter your Gender');
              break;
              case 'terms':
                $this->addError($item, 'You must accept our Terms and Conditions');
              break;
              case 'Required_Message':
                $this->addError($item, 'Your Must Fill This Field');
              break;
            }
          }

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR MINIMUM LENGTH FIELDS *~~~~~~~~~~~~~~~~~*/
          elseif($rule === 'min' && strlen($value) < $rule_value){
            switch($item){
              case 'name':
                $this->addError($item, 'The Name Must Be At Least 4 Characters');
              break;
              case 'email':
                $this->addError($item, 'Email Must Be At Least 5 digits');
              break;
              case 'password':
                $this->addError($item, 'Password Must Be At Least 8 digits');
              break;
              case 'newpassword':
                $this->addError($item, 'Password Must Be At Least 8 digits');
              break;
              case 'confnewpassword':
                $this->addError($item, 'Password Must Be At Least 8 digits');
              break;
              case 'username':
                $this->addError($item, 'The Username Must Be At Least 2 Characters');
              break;
              case 'Required_Message':
                $this->addError($item, 'Your Message Must Be At Least 1 Characters');
              break;
            }
          }

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR MAXIMUM LENGTH FIELDS *~~~~~~~~~~~~~~~~~*/
          elseif($rule === 'max' && strlen($value) > $rule_value){
            switch($item){
              case 'name':
                $this->addError($item, 'The Name Must Be At Most 50 Characters');
              break;
              case 'email':
                $this->addError($item, 'Email Must Be At Most 70 digits');
              break;
              case 'password':
                $this->addError($item, 'Password Must Be At Most 80 digits');
              break;
              case 'newpassword':
                $this->addError($item, 'Password Must Be At Most 80 digits');
              break;
              case 'confnewpassword':
                $this->addError($item, 'Password Must Be At Most 80 digits');
              break;
              case 'username':
                $this->addError($item, 'The Username Must Be At Most 50 Characters');
              break;
              case 'Required_Message':
                $this->addError($item, 'The Message Must Be At Most 1100 Characters');
              break;
            }
          }

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR PASSWORD CONFIRMATION FIELDS *~~~~~~~~~~~~~~~~~*/
          elseif($rule === 'identical' && $value !== $source[$rule_value]){
            switch($item){
              case 'confnewpassword':
              $this->addError($item, 'New Password And Confirm are not identical');
              break;
            }
          }

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR IMAGES UPLOADING *~~~~~~~~~~~~~~~~~*/
          elseif($rule === 'extension' && $_FILES[$item]['tmp_name']){
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES[$item]["tmp_name"]);
            if(!$check) {
              $this->addError($item, 'File is not an image.');
            }elseif( !in_array( strtolower(pathinfo(basename($_FILES[$item]["name"]),PATHINFO_EXTENSION)), $rule_value ) ){//#We only allow jpg, jpeg, png
              $this->addError($item, 'Sorry, only JPG, JPEG,and PNG files are allowed.');
            }elseif($_FILES[$item]["size"] > 500000){//#check for size we need it small
              $this->addError($item, 'Sorry, your file is too large.');
            }
          }

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR UNIQUE FIELDS *~~~~~~~~~~~~~~~~~*/
          elseif($rule === 'unique'){

            /*~~~~~~~~* UNIQUE USERNAME *~~~~~~~~*/
            if($item === 'username' && DB::getInstance()->CheckValue('users', array('username', $value))){
              $user = new User();
              if(!$user->isLoggedIn()){
                $this->addError($item, 'Sorry this username is taken!');
              }else{
                if($value !== $user->data()->username){
                  $this->addError($item, 'Sorry this username is taken!');
                }
              }
            }

            /*~~~~~~~~* UNIQUE EMAIL *~~~~~~~~*/
            elseif($item === 'email' && DB::getInstance()->CheckValue('users', array('email', $value))){
              $user = new User();
              if(!$user->isLoggedIn()){
                $this->addError($item, 'This email is already a user - <a href=\'login.php\'>Login</a>');
              }else{
                if($value !== $user->data()->email){
                  $this->addError($item, 'This email is already a user');
                }
              }
            }
          }

          /*~~~~~~~~~~~~~~~~~* SETTING ERROR MESSAGES FOR REGULAR EXPRESSIONS *~~~~~~~~~~~~~~~~~*/
          elseif($rule === 'regexp' && !preg_match($rule_value, $value)){
            switch($item){
              case 'name':
                $this->addError($item, 'Your name must contain only letters and numbers');
              break;
              case 'email':
                $this->addError($item, 'Please Enter a valid Email');
              break;
              case 'password':
                $this->addError($item, 'Password must be in letters, numbers, dashes, underscores and spaces');
              break;
              case 'newpassword':
                $this->addError($item, 'Password must be in letters, numbers, dashes, underscores and spaces');
              break;
              case 'confnewpassword':
                $this->addError($item, 'Password must be in letters, numbers, dashes, underscores and spaces');
              break;
              case 'username':
                $this->addError($item, 'Username must be only in English alphabets and can\'t contain symbols except underscores');
              break;
            }
          }
        }
      }
      if(empty($this->_errors)){
        $this->_passed = true;
      }
    }
    return false;
  }

  public function isPassed(){
    return $this->_passed;
  }

  public function ShowErrors(){
    return $this->_errors;
  }

  public function addError($to, $val){
    return $this->_errors[$to] = $val;
  }


}
