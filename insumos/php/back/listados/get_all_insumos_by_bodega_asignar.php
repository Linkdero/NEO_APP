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
      $insumos = insumo::get_all_insumos_by_bodega_asignar($bodega);
      $data = array();

      foreach ($insumos as $insumo){
        $accion='';
        if($insumo['id_status']==5337){
          $accion.='<button data-id="'.$insumo['numero_serie'].'" class="btn btn-sm btn-personalizado outline button-save insumo-agregar" ><i class="fa fa-check"></i></button>';
        }
        $emp='';
        $sub_array = array(
          'sicoin'=>$insumo['sicoin'],
          'tipo'=>$insumo['id_tipo_insumo'].'/'.$insumo['tipo'],
          'marca'=>$insumo['marca'],
          'modelo'=>$insumo['modelo'],
          'serie'=>$insumo['numero_serie'],
          'estado'=>$insumo['estado'],
          'resguardo'=>$insumo['resguardo'],
          'existencia'=>number_format($insumo['existencia'], 0, ".", ","),
          'empleado'=>$emp,
          'accion'=>$accion,
          'bodega'=>$bodega_nom
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


?>
