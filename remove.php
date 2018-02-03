<?php
require_once 'core/init.php';
$user = new User();
$user->removeAccount();
Redirect::to('index.php');
