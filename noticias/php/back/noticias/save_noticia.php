<?php
date_default_timezone_set('America/Guatemala');
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../functions.php';
    $alias = $_POST['alias'];
    $usuario = $_POST['usuario'];
    $red_social = $_POST['red_social'];
    $url = $_POST['url'];
    $categoria = $_POST['categoria'];
    $observaciones = $_POST['observaciones'];
    $fecha = date('Y-m-d H:i:s');
    $propietario = $_SESSION['id_persona'];
    $municipio = $_POST['municipio'];
    $opcion = $_POST['opcion'];

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($opcion == 1 && array_key_exists("id_noticia", $_POST)){
      $id_noticia = $_POST['id_noticia'];
      $sql = "UPDATE inf_noticia SET noticias_alias = :alias, noticia_usuario = :usuario, noticia_fuente = :fuente, noticia_url = :noticia, noticia_observaciones = :observaciones, noticia_propietario = :propietario, noticia_categoria = :categoria, noticia_municipio = :municipio, noticia_status = 1
              WHERE id_noticia = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(":alias", $alias);
      $stmt->bindParam(":usuario", $usuario);
      $stmt->bindParam(":fuente", $red_social);
      $stmt->bindParam(":noticia", $url);
      $stmt->bindParam(":observaciones", $observaciones);
      $stmt->bindParam(":propietario", $propietario);
      $stmt->bindParam(":categoria", $categoria);
      $stmt->bindParam(":municipio", $municipio);
      $stmt->bindParam(":id", $id_noticia);
      if($stmt->execute()){
        $response = array(
          "status" => 200,
          "msg" => "Ok"
        );        
      }else{
        $response = array(
          "status" => 400,
          "msg" => "Error"
        );
      }
    }else{
      $sql = "INSERT INTO inf_noticia (fecha, noticias_alias, noticia_usuario,noticia_fuente,noticia_url,noticia_observaciones,noticia_propietario,noticia_categoria,noticia_status,noticia_municipio)
              VALUES (?,?,?,?,?,?,?,?,?,?)";
      $stmt = $pdo->prepare($sql);
      if( $stmt->execute(array($fecha,$alias,$usuario,$red_social,$url,$observaciones,$propietario,$categoria,1,$municipio))){
        $response = array(
          "status" => 200,
          "msg" => "Ok"
        );        
      }else{
        $response = array(
          "status" => 400,
          "msg" => "Error"
        );
      }
    }
    echo json_encode($response);
    Database::disconnect_sqlsrv();

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
