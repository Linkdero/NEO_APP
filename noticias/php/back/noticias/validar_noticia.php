<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    date_default_timezone_set('America/Guatemala');
    $id_noticia = $_POST['id_noticia'];
    $estado = $_POST['estado'];
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE inf_noticia SET noticia_status = :estado, usuario_aprobacion = :usuario WHERE id_noticia = :noticia";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("usuario", $_SESSION["id_persona"]);
    $stmt->bindParam("noticia", $id_noticia);
    $stmt->bindParam("estado", $estado);
    if($stmt->execute()){
        echo json_encode(array(
            "status" => true,
            "msg" => "Ok"
        ));
    }else{
        echo json_encode(array(
            "status" => false,
            "msg" => "Error"
        ));
    }
    Database::disconnect_sqlsrv();
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
