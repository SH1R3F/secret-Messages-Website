<?php

class User{

  private $_db = null,
          $_SessionName,
          $_CookieName,
          $_data,
          $_isLogged;


  public function __construct($user = ''){
    $this->_db = DB::getInstance();
    $this->_SessionName = Config::get('sessions/session_name');
    $this->_CookieName = Config::get('cookies/cookie_name');
    if(!$user){
      if(Session::exists($this->_SessionName)){
        $user = Session::get($this->_SessionName);
        if($this->find($user)){
          $this->_isLogged = true;
        }else{
          //logout
        }
      }
    }else{
      $this->find($user);
    }
  }


  public function InsertNew($table, $fields = array()){
    if(count($fields)){
      $field  = array_keys($fields);
      $values = array_values($fields);
      $sql = "INSERT INTO users ( " . implode(', ', $field) . " ) VALUES ( '" . implode("', '", $values) . "' )";
      return $this->_db->query($sql);
    }
  }


  public function find($user = null){
    /*~~~~~~~~* IF INPUT HAS @ THEN IT'S AN EMAIL *~~~~~~~~*/
    if(preg_match('/@/', $user)){
      $field = 'email';
    }
    /*~~~~~~~~* IF INPUT IS ONLY NUMBERS THEN IT'S AN ID *~~~~~~~~*/
    elseif(is_numeric($user)){
      $field = 'id';
    }
    /*~~~~~~~~* ELSE THEN IT'S A USERNAME *~~~~~~~~*/
    else{
      $field = 'username';
    }
    $data = $this->_db->GetData('users', array($field, $user));
    if($data->count()){
      $this->_data = $data->first();
      return true;
    }
    return false;
  }


  public function login($username = null, $password = null, $remember = false){
    if(!$username && !$password && $this->exists()){
      Session::put($this->_SessionName, $this->data()->id);
    }else{
      $user = $this->find($username);
      if($user){
        if($this->data()->password === Hash::make($password, $this->data()->salt)){
          Session::put( $this->_SessionName, $this->data()->id );

          /*~~~~~~~~~* IF USER CHOSE TO REMEMBER THEN *~~~~~~~~~*/
          if($remember){
            $hash = Hash::unique();

            /*~~~~~~~~~* IF TABLE EXISTS OR CREATE IT *~~~~~~~~~*/
            if(!$this->_db->IsTable('users_session')){
              $this->_db->CreateTable('users_session', array(
                'id'      => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'user_id' => 'int NOT NULL',
                'hash'    => 'varchar(255) NOT NULL'
              ));
            }
            $hashCheck = $this->_db->GetData('users_session', array('user_id', $this->data()->id));
            if(!$hashCheck->count()){
              $this->_db->InsertNew('users_session', array(
                'user_id' => $this->data()->id,
                'hash'    => $hash
              ));
            }else{
              $hash = $hashCheck->result()[0]->hash;
            }
            Cookie::put($this->_CookieName, $hash, Config::get('cookies/cookie_expiry'));
          }
          return true;
        }
      }
    }
    return false;
  }

  public function exists(){
    return (!empty($this->data()))? true : false ;
  }

  public function data(){
    return $this->_data;
  }

  public function isLoggedIn(){
    return $this->_isLogged;
  }

  public function logout(){
    $this->_db->delete('users_session', array('user_id', $this->data()->id));
    Session::delete($this->_SessionName);
    Cookie::delete($this->_CookieName);
    Redirect::to('index.php');
  }

  public function RemoveAccount(){
    $this->_db->delete('users', array('id', $this->data()->id));
    Session::delete($this->_SessionName);
    Cookie::delete($this->_CookieName);
    Redirect::to('index.php');
  }


}
