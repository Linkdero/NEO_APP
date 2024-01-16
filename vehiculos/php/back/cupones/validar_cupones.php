<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  $c_ini = null;
  $c_fin = null;
  if(!empty($_GET['c_ini'])){
    $c_ini = $_REQUEST['c_ini'];
  }
  if(!empty($_GET['c_fin'])){
    $c_fin = $_REQUEST['c_fin'];
  }


  if($c_fin < $c_ini){
    echo 'false';
  }else{
    echo 'true';
  }

else:
    header("Location: ../index.php");
endif;
