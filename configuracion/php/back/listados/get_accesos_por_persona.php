<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $persona=$_POST['id_persona'];
    $accesos = array();
    $accesos=configuracion::get_accesos_by_persona($persona);
    $data = array();

    foreach ($accesos as $a){

      $id_acceso=$a['id_acceso'];
      $accion='';
      $accion.='<div class="btn-group btn-group-sm" role="group">
      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
      <div class="btn-group mr-2" role="group" aria-label="Second group">';
      $accion.='<button class="btn btn-personalizado outline btn-sm" onclick="mostrar_privilegios_por_accesos('.$id_acceso.',2)"><i class="fa fa-search"></i></button>';
      if($a['id_status']==1119){
        $accion.='<button class="btn btn-personalizado outline btn-sm" onclick="inactivar_acceso('.$id_acceso.')"><i class="fa fa-times"></i></button>';
      }else if($a['id_status']==1120){
        $accion.='<button class="btn btn-personalizado outline btn-sm" onclick="activar_acceso('.$id_acceso.')"><i class="fa fa-check"></i></button>';
      }
      $accion.='<button class="btn btn-personalizado outline btn-sm" ><i class="fa fa-exchange-alt" onclick="cargar_copiar_privilegios('.$id_acceso.','.$a['id_modulo'].')"></i></button>';

      $accion.='</div></div></div>';



      $sub_array = array(

        'id_acceso'=>$a['id_acceso'],
        'empleado'=>$a['primer_nombre'].' '.$a['segundo_nombre'].' '.$a['tercer_nombre'].' '.$a['primer_apellido'].' '.$a['segundo_apellido'].' '.$a['tercer_apellido'],
        'id_modulo'=>$a['id_modulo'],
        'modulo'=>$a['modulo'],
        'estado'=>$a['estado'],
        'accion'=>$accion
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
