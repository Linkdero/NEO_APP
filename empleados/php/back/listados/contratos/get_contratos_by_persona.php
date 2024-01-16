<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    include_once '../../functions_contratos.php';
    $id_persona=$_GET['id_persona'];
    $contratos = array();
    $contratos=contrato::get_contratos_por_empleado($id_persona,1);
    $data = array();

    foreach($contratos as $c){
      if(!empty($c['nro_contrato'])){
        $f_r='';
        /*if($e['fecha_efectiva_resicion']!=''){
          $f_r=fecha_dmy($e['fecha_efectiva_resicion']);
        }*/
        $accion='<div class="btn-group">';
        $accion.='<button class="btn btn-sm  btn-personalizado outline"';
        if($c['id_status']==891 || $c['id_status']==5610){
          if(usuarioPrivilegiado()->hasPrivilege(167)){
            //$accion.='onclick="cargar_remocion_empleado('.$e['id_plaza'].','.$e['id_asignacion'].',1)"';
          }else{
          $accion.="disabled";
          }
        }else{
          $accion.='disabled';
        }
        $accion.='><i class="fa fa-times"></i></button>';
        //$accion.='<button class="btn-sm btn btn-personalizado outline" onclick="cargar_remocion_empleado('.$e['id_plaza'].','.$e['id_asignacion'].',2)"><i class="fa fa-eye"></i></button>';
        $accion.='</div>';
        $sub_array = array(
          'tipo_contrato'=>$c['tipo_contrato'],
          'renglon'=>$c['Renglon'],
          'id_persona'=>$c['id_persona'],
          'id_empleado'=>$c['id_empleado'],
          'nombre_completo'=>'',//$c['nombre_completo'],
          'reng_num'=>$c['reng_num'],
          'nro_contrato'=>$c['nro_contrato'],
          'nro_acuerdo_aprobacion'=>$c['nro_acuerdo_aprobacion'],
          'fecha_acuerdo_aprobacion'=>fecha_dmy($c['fecha_acuerdo_aprobacion']),
          'fecha_inicio'=>(!empty(fecha_dmy($c['fecha_inicio']))) ? fecha_dmy($c['fecha_inicio']) : '',
          'fecha_finalizacion'=>(!empty($c['fecha_finalizacion'])) ? fecha_dmy($c['fecha_finalizacion']) : '',

          'fecha_fin'=>(!empty(fecha_dmy($c['fecha_efectiva_resicion']))) ? $c['fecha_efectiva_resicion'] : '',
          'monto_mensual'=>number_format($c['monto_mensual'],2,'.',','),
          'puesto'=>$c['puesto'],
          'id_status'=>$c['id_status'],
          'estado'=>$c['estado'],
          'archivo'=>$c['archivo'],
          'accion'=>$accion



          //'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido']
        );
        $data[]=$sub_array;
      }

    }

    $output = array(
      "data"    => $data
    );


    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
