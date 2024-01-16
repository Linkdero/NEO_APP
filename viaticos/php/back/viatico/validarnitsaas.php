<?php
include_once '../../../../inc/functions.php';
include_once '../../../../inc/Database.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :


      $nit = null;
      $id_pais = null;

      if(!empty($_GET['txtnit0'])){
        $nit = $_GET['txtnit0'];
      }
      if(!empty($_GET['txtnit1'])){
        $nit = $_GET['txtnit1'];
      }
      if(!empty($_GET['txtnit2'])){
        $nit = $_GET['txtnit2'];
      }
      if(!empty($_GET['txtnit3'])){
        $nit = $_GET['txtnit3'];
      }

      if(!empty($_GET['id_pais'])){
        $id_pais = $_GET['id_pais'];
      }

      if($id_pais == 'GT'){
        if($nit == '23714859')
        {
          echo 'true';
        }
        else {
          echo 'false';
        }
      }else{
        echo 'true';
      }

else:
    header("Location: ../index.php");
endif;

?>
