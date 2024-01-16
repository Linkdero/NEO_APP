<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';

  $nro_vale = $_GET["nro_vale"];
    
  $tipo = new vehiculos();
  $datat = $tipo::get_TipoDespacho($nro_vale);
  $xTipo = "Placa";
  $data=array();
  $response = array();
  if ($datat["id_tipo_uso"] == 1144) {
      $data = $tipo::get_valeDespacho($nro_vale);
  }elseif ($datat["id_tipo_uso"] == 1147) {
      $data = $tipo::get_valeDespacho_Ext($nro_vale);
  }else {
      $xTipo = "Caracteristicas";
      $data = $tipo::get_valeDespacho_Gen($nro_vale);
  }
  $id_tipo=$data["id_tipo_combustible"];

  $response = array(
    'fecha'=>$data['fecha'], 
    'nro_vale'=>$data['nro_vale'],  
    'nro_placa'=>$data['nro_placa'], 
    'uso'=>$data['uso'], 
    'id_combustible'=>$data['id_combustible'],
    'id_tipo_combustible'=>$data['id_tipo_combustible'],
    'tipo_comb'=>$data['tipo_comb'], 
    'tlleno'=>$data['tlleno'], 
    'cant_autor'=>$data['cant_autor'], 
    'recibe'=>$data['recibe']
  );

//echo $output;
echo json_encode($response);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
