<?php
include_once '../inc/functions.php';
include_once '../inc/Database.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()):

  include_once 'php/back/functions.php';


        $carne = $_GET['carne'];
        $puerta = visita::get_puerta_por_usuario($_SESSION['id_persona']);
        $id_puerta=$puerta['id_puerta'];
        if(!empty($_GET['carne'])){
            $carne = $_REQUEST['carne'];
        }

        $fecha =date('Y-m-d');

        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT no_gafete FROM tbl_control_visitas
                WHERE id_puerta=? AND no_gafete=? AND salida=?";
        $p = $pdo->prepare($sql);
        $p->execute(array($id_puerta,$carne,0));
        $estado = $p->fetch();

        Database::disconnect_sqlsrv();

        if($estado['no_gafete'] == $carne)
        {

        echo 'false';
        }
        else{
          echo 'true';
        }

else:
    header("Location: ../index.php");
endif;



/*

*/
?>
