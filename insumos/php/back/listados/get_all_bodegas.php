<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';

    $datos = insumo::get_acceso_bodega_usuario($_SESSION['id_persona']);
    /*foreach($datos AS $d){
      $bodega = $d['id_bodega_insumo'];
      $bodega_nom = $d['descripcion_corta'];*/

      $bodegas = array();
      $bodegas = insumo::get_bodegas_insumos();
      $data = array();

      foreach ($bodegas as $bodega){
        $accion='';
        $total=insumo::get_usuarios_por_bodega($bodega['id_item']);
        $accion.='<button data-id="" class="btn btn-sm btn-personalizado outline button-save" data-toggle ><i class="fa fa-pen"></i></button>';

        $emp='';
        $sub_array = array(
          'id_bodega'=>$bodega['id_item'],
          'bodega'=>$bodega['descripcion_corta'],
          'total'=>$total['total'],
          'activos'=>'',
          'inactivos'=>'',
          'accion'=>$accion
        );

        $data[]=$sub_array;
      }
    //}


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
