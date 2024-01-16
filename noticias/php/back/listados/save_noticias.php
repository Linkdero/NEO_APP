<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../functions.php';

    foreach ($array_noticias as $noticia){
      $sub_array = array(
        //'id_noticia'=>$noticia['id_noticia'],
        'fecha'=> date_format(new DateTime($noticia['fecha']), 'd-m-Y'),
        'alias'=>$noticia['noticias_alias'],
        'usuario'=>$noticia['noticia_usuario'],
        'post'=>$noticia['noticia_url'],
        'observaciones'=>$noticia['noticia_observaciones'],
        'propietario'=>$noticia['primer_nombre'].' '.$noticia['segundo_nombre'].' '.$noticia['tercer_nombre'].' '.$noticia['primer_apellido'].' '.$noticia['segundo_apellido'].' '.$noticia['tercer_apellido'],
        'fuente'=> $noticia["red_social"],
        'categoria'=>$noticia['noticia_categoria'],
        'estado'=>$noticia['noticia_status'],
        'accion'=>'<span class="btn btn-sm btn-info"><i class="fa fa-check"></i></span>'
      );

      $data[]=$sub_array;
    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($results);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
