<?php

function scape( $string ){
  return htmlspecialchars( htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
}




?>
