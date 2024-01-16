<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $ini_= $_POST['ini'];
    $fin_ = $_POST['fin'];
    $red = $_POST['red'];
    $categoria = $_POST['categoria'];
    $usuario = $_POST['usuario'];

    $ini=date('Y-m-d', strtotime($ini_));
    $fin=date('Y-m-d', strtotime($fin_));

    $array_noticias = array();
    $array_noticias = (new noticia)->get_noticias($ini,$fin,$red,$categoria,$usuario);
    $data = array();
    $strFB = "https://www.facebook.com/";
    $strIG = "https://www.instagram.com/";
    $strTW = "https://www.twitter.com/";
    $btn_seguimiento = false;
    if(usuarioPrivilegiado()->hasPrivilege(284)){
      $btn_seguimiento = true;
    }
    foreach ($array_noticias as $noticia){
      $url_post = $noticia["noticia_url"];
      $tipo = 0;
      if(strpos($noticia["noticia_url"], $strFB) !== false){
        $red_social = "<i class='color-facebook fab fa-facebook-square fa-lg'></i> Facebook";
        $tipo = 1;
      }else if(strpos($noticia["noticia_url"], $strIG) !== false){
        $red_social = "<i class='color-instagram fab fa-instagram-square fa-lg'></i> Instagram";
        $tipo = 2;
      }else if(strpos($noticia["noticia_url"], $strTW) !== false){
        $red_social = "<i class='color-twitter fab fa-twitter-square fa-lg'></i> Twitter";
        $tipo = 3;
      }else{
        $red_social = "";
        $url_post = "URL INVALIDA";
      }

      $accion = '';
      $accion .= '<div class="btn-group btn-group-sm" role="group">
                  <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                  <div class="btn-group mr-2" role="group" aria-label="Second group">';
      $accion .= "<button  onclick='drawPost(this.value,".$tipo.");' value=".$url_post." class='btn btn-sm btn-personalizado outline'>Ver Post</button>";
      if($btn_seguimiento && $noticia['noticia_status'] == "1"){
        $accion .= '<span class="btn btn-sm btn-personalizado outline" onclick="validar_noticia('.$noticia['id_noticia'].')"><i class="fa fa-check"></i> Seguimiento</span>';;
      }

      if($noticia['noticia_status'] == "2"){
        $accion .= '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto" href="noticias/php/front/noticias/noticia_nueva.php?id='.$noticia['id_noticia'].'"><i class="fa fa-pencil"></i> Editar</span>';
      }
      
      $accion .= '</div></div></div>';
      if($noticia['noticia_status'] == 2 || $noticia['noticia_status'] == 3){
        $aprobado = $noticia['nombre'].' '.$noticia['apellido'];
      }else{
        $aprobado = "";
      }
      $sub_array = array(
        'fecha'=> date_format(new DateTime($noticia['fecha']), 'd-m-Y'),
        'alias'=> $noticia['noticias_alias'],
        'usuario'=> $noticia['noticia_usuario'],
        'observaciones'=> $noticia['noticia_observaciones'],
        'propietario'=> $noticia['primer_nombre'].' '.$noticia['primer_apellido'],
        'fuente'=> $red_social,
        'categoria'=> $noticia['noticia_categoria'],
        'estado'=> $noticia['noticia_status'],
        'ubicacion'=> $noticia['departamento'].", ".$noticia['municipio'],
        'aprobado'=> $aprobado,
        'accion' => $accion
      );

      $data[]=$sub_array;
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

    echo json_encode($results);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
