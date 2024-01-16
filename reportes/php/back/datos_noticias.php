<?php
include '../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    date_default_timezone_set('America/Guatemala');
    include_once '../../../noticias/php/back/functions.php';
    $opcion = $_POST["opcion"];
    $ini_=$_POST['ini'];
    $fin_=$_POST['fin'];
    $red=$_POST['red'];
    $categoria=$_POST['categoria'];

    $ini=date('Y-m-d', strtotime($ini_));
    $fin=date('Y-m-d', strtotime($fin_));

    $dataUser = array();
    $dataGroup = array();
    if($opcion == "1"){
        $rowsGroup = noticia::get_noticias_by_group($ini,$fin,$red,$categoria);
        foreach ($rowsGroup as $rowGroup){
            if($rowGroup["descripcion"] == "Facebook"){
                $color = "#3B5998";
                $url_bullet = "assets/img/noticias/fb.png";
            }else if($rowGroup["descripcion"] == "Twitter"){
                $color = "#00ACEE";
                $url_bullet = "assets/img/noticias/tw.png";
            }else if($rowGroup["descripcion"] == "Instagram"){
                $color = "#6E52C5";
                $url_bullet = "assets/img/noticias/ig.png";
            }
            $dataGroup[] = array(
                "nombre" => $rowGroup["descripcion"],
                "cantidad" => $rowGroup["conteo"],
                "color" => $color,
                "bullet"=> $url_bullet
            );
        }

        $rowsUsuario = noticia::get_noticias_by_usuario($ini,$fin,$red,$categoria);
        foreach ($rowsUsuario as $rowUser){
            $dataUser[] = array(
                "usuario" => $rowUser["noticia_usuario"],
                "cantidad" => $rowUser["conteo"]
            );
        }
    }
    echo json_encode(array(
        "notice" => $dataGroup,
        "user" => $dataUser
    ));
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
