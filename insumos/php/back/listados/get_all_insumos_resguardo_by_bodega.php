<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
      $bodega_nom = $d['descripcion_corta'];

      $insumos = array();
      $insumos = insumo::get_insumos_resguardo_con_empleado($bodega);
      $data = array();

      foreach ($insumos as $insumo){
        $accion='';
        //if($insumo['id_status']==5337){
          //
        //}
        $accion.='<button class="btn btn-sm btn-personalizado outline button-save" ><i class="fa fa-check"></i> Entregar</button>';
        $emp='';
        /*if($insumo['id_status']==5338 || $insumo['id_status']==5339 || $insumo['id_status']==5491){
          $e=insumo::get_last_empleado_asignado($insumo['id_prod_ins_detalle']);
          $emp=$e['primer_nombre'];
        }

        /*$asignado='<span class="badge badge-success">Disponible</span>';
        if($insumo['flag_asignado']==1){
          $asignado='<span class="badge badge-danger">Asignado.</span>';
        }*/
        $e = insumo::get_last_empleado_asignado($insumo['id_prod_ins_detalle']);
        $emp=$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.
        $e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'];
        $transaccion=$e['id_doc_insumo'];
        $emp.='--'.$transaccion;

        $sub_array = array(
          'tipo'=>$insumo['id_tipo_insumo'].'/'.$insumo['tipo'],
          'marca'=>$insumo['marca'],
          'modelo'=>$insumo['modelo'],
          'serie'=>$insumo['numero_serie'],
          'estado'=>$insumo['estado'],
          'resguardo'=>$insumo['resguardo'],
          'empleado'=>$emp,
          'accion'=>$accion,
          'bodega'=>$bodega_nom,
          'gafete'=>"[".$e['id_persona']."]"
        );

        $data[]=$sub_array;
      }
    }


  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($results);


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

//15824D1020
?>
