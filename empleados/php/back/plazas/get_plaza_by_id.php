<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions_plaza.php';

    $plazas = array();
    $id_plaza='';
  	if(isset($_GET['id_plaza'])){
  		$id_plaza=$_GET['id_plaza'];
  	}
    $p=plaza::get_plaza_by_id_sueldo($id_plaza);
    $data = array();

    $data = array(
      'id_plaza'=>$p['id_plaza'],
      'cod_plaza'=>$p['cod_plaza'],
      'cod_puesto'=>$p['codigo_puesto_oficial'],
      'estado'=>$p['estado_plaza'],
      'cargo'=>$p['descripcion_plaza'],
      'secretaria_n'=>$p['nombre_secretaria_presupuestario'],
      'subsecretaria_n'=>$p['nombre_subsecretaria_presupuestario'],
      'direccion_n'=>$p['nombre_direccion_presupuestaria'],
      'subdireccion_n'=>(!empty($p['nombre_subdireccion_presupuestaria']))?$p['nombre_subdireccion_presupuestaria']:'Sin asignación',
      'departamento_n'=>(!empty($p['nombre_depto_presupuestario']))?$p['nombre_depto_presupuestario']:'Sin asignación',
      'seccion_n'=>(!empty($p['nombre_seccion_presupuestaria']))?$p['nombre_seccion_presupuestaria']:'Sin asignación',
      'sueldo'=>number_format($p['monto_sueldo_plaza'],2,'.',','),
      'sueldo_base'=>$p['monto_sueldo_base']
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
