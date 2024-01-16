<?php
include_once '../../../../inc/functions.php';
include_once '../../../../inc/Database.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :


      $serie = null;
      $numero = null;
      $nit = null;
      $id_pais = null;

      if(!empty($_GET['nit0'])){
        $nit = $_GET['nit0'];
      }
      if(!empty($_GET['nit1'])){
        $nit = $_GET['nit1'];
      }
      if(!empty($_GET['nit2'])){
        $nit = $_GET['nit2'];
      }
      if(!empty($_GET['nit3'])){
        $nit = $_GET['nit3'];
      }


      if(!empty($_GET['serie0'])){
        $serie = $_GET['serie0'];
      }
      if(!empty($_GET['serie1'])){
        $serie = $_GET['serie1'];
      }
      if(!empty($_GET['serie2'])){
        $serie = $_GET['serie2'];
      }
      if(!empty($_GET['serie3'])){
        $serie = $_GET['serie3'];
      }


      if(!empty($_GET['numero0'])){
        $numero = $_GET['numero0'];
      }
      if(!empty($_GET['numero1'])){
        $numero = $_GET['numero1'];
      }
      if(!empty($_GET['numero2'])){
        $numero = $_GET['numero2'];
      }
      if(!empty($_GET['numero3'])){
        $numero = $_GET['numero3'];
      }


      if(!empty($_GET['id_pais'])){
        $id_pais = $_GET['id_pais'];
      }

      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      if($id_pais != 'GT'){
        echo 'true';
      }else{
        //inicio
        $sql = "SELECT factura_nit, factura_serie, factura_numero FROM vt_nombramiento_factura WHERE factura_serie = ? AND factura_numero = ? AND bln_confirma = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($serie, $numero,1));
        $response = $stmt->fetch();
        Database::disconnect_sqlsrv();

        if(!empty($nit)){
          echo 'true';
        }else{
          if($response['factura_serie'] == $serie && $response['factura_numero'] == $numero)
          {
            echo 'false';
          }
          else {
            echo 'true';
          }
        }
        //fin
      }




else:
    header("Location: ../index.php");
endif;

?>
