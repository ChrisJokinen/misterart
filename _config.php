<?php
  /**
   * Config file
   * 2020-05-07
   * Chris Jokinen
   * 
   * Use for state init
   */

   if($debug){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
   }
   else{
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
   }

   $_config = require_once('lib/_config.php');
?>