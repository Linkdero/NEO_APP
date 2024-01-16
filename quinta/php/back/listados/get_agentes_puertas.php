<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $agentes = array();

    $clase = new visita;
    $g=$clase->get_puerta_por_usuario($_SESSION['id_persona']);
    $grupo = "'%".$g['grupo']."%'";
    $agentes = $clase->get_agentes_puertas($g['grupo']);
    $puertas = $clase->get_puertas();

    $data = array();
    foreach ($agentes as $a){
      $combo='';
      $combo.='<select class="form-control chosen-select-width"  id="cmb-'.$a['id_persona'].'">';
      foreach($puertas as $p){
        $combo.='<option value="'.$p['id_puerta'].'"';
        if($p['id_puerta']==$a['id_puerta']){
          $combo.='selected';
        }
        $combo.='>'.$p['nombre_puerta'].'</option>';
      }

      $combo.='</select>';
      $sub_array = array(
        'id_persona'=>$a['id_persona'],
        'agente'=> $a['primer_nombre'].' '.$a['segundo_nombre'].' '.$a['tercer_nombre'].' '.$a['primer_apellido'].' '.$a['segundo_apellido'].' '.$a['tercer_apellido'],
        'grupo'=> $a['grupo'],
        'puertas'=>$combo,
        'accion'=>'<span class="btn outline btn-sm btn-personalizado" onclick="asignar_a_puerta('.$a['id_persona'].')"><i class="fa fa-check"></i></span>'
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
