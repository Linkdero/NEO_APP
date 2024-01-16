<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions_contratos.php';

    $id_reng_num='';
  	if(isset($_GET['id_reng_num'])){
  		$id_reng_num=$_GET['id_reng_num'];
  	}
    $c=contrato::get_contrato_por_asignacion($id_reng_num);
    $data = array();

    $data = array(
      'tipo_contrato'=>$c['tipo_contrato'],
      'reng_num'=>$c['reng_num'],
      'nro_contrato'=>$c['nro_contrato'],
      'fecha_inicio'=>date('Y-m-d', strtotime($c['fecha_inicio'])),
      'fecha_contrato'=>date('Y-m-d', strtotime($c['fecha_contrato'])),
      'fecha_fin'=>date('Y-m-d', strtotime($c['fecha_finalizacion'])),
      'fecha_acuerdo_aprobacion'=>date('Y-m-d', strtotime($c['fecha_acuerdo_aprobacion'])),
      'acuerdo'=>$c['nro_acuerdo_aprobacion'],
      'monto'=>$c['monto_contrato'],
      'monto_mensual'=>$c['monto_mensual'],
      'nivel_f'=>$c['id_nivel_servicio'],
      'categoria_f'=>$c['id_categoria'],
      'secretaria_f'=>$c['id_secretaria_servicio'],
      'subsecretaria_f'=>$c['id_subsecretaria_servicio'],
      'direccion_f'=>$c['id_direccion_servicio'],
      'subdireccion_f'=>$c['id_subdireccion_servicio'],
      'departamento_f'=>$c['id_depto_servicio'],
      'seccion_f'=>$c['id_seccion_servicio'],
      'puesto_f'=>(!empty($c['id_puesto_funcional'])) ? $c['id_puesto_funcional'] : $c['id_puesto_servicio'],
      'puesto_s'=>$c['id_puesto_servicio'],
      'observaciones'=>$c['observaciones'],
      'id_tipo_servicio'=>$c['id_tipo_servicio'],
      'id_status'=>$c['emp_estado'],
    );

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
