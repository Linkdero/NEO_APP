<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $factura_id=0;
  if(isset($_GET['factura_id'])){
    $factura_id=$_GET['factura_id'];
  }

  $clase = new documento;

  $e = $clase->get_estado_factura($factura_id);
  $asig = $clase->get_tecnico_asignado($factura_id);
  $data = array();

  $verificacion='';
  $titulo='';
  $estado_id=(!empty($e['Ped_status']))?$e['Ped_status']:0;
  $estado= (!empty($e['estado']))?$e['estado']:' Registrado ';
  $tipo_verificacion= (!empty($e['tipo_verificacion']))?$e['tipo_verificacion']:'';

  if($estado_id != 8170){
    if(usuarioPrivilegiado()->hasPrivilege(311) || usuarioPrivilegiado()->hasPrivilege(303) || usuarioPrivilegiado_acceso()->accesoModulo(7851)){
      if(evaluar_flag($_SESSION['id_persona'],8017,311,'flag_autoriza')==1 || usuarioPrivilegiado_acceso()->accesoModulo(7851)){
        if($estado_id==0){
          $verificacion=1;
        }
        if($estado_id==8156){
          $verificacion=2;
        }
        if($estado_id == 8140 && $e['Ped_bitacora_id'] == 0 || $estado_id == 8141 && $e['Ped_bitacora_id'] == 0){
          $verificacion=3;
          $titulo='Devolver documento';
        }
        if($estado_id == 8141 && $e['Ped_bitacora_id'] == 8157){
          $verificacion=4;
          $titulo='Recibir documento';
        }
      }else if($estado_id == 0 && evaluar_flag($_SESSION['id_persona'],8017,303,'flag_eliminar')==1 && $estado_id != 8170){
        $titulo = 'Anular Pedido';
        $verificacion = 0;
      }

    }
    if(usuarioPrivilegiado()->hasPrivilege(308) || usuarioPrivilegiado_acceso()->accesoModulo(7851)){
      if(evaluar_flag($_SESSION['id_persona'],8017,308,'flag_autoriza')==1 || usuarioPrivilegiado_acceso()->accesoModulo(7851)){
        if($estado_id==8140 && $e['Ped_bitacora_id'] == 8157){
          $verificacion=5;
          $titulo='Recibir documento';
        }
        if($estado_id == 8160){
          $verificacion=6;
        }
        if($estado_id == 8143 && $e['Ped_bitacora_id'] == 0){
          $verificacion=7;
          $titulo='Devolver documento';
        }





      }
    }



      if(evaluar_flag($_SESSION['id_persona'],8017,302,'flag_acceso')==1 || usuarioPrivilegiado_acceso()->accesoModulo(7851)){

        if($estado_id == 8143 && $e['Ped_bitacora_id'] == 8161){
          $verificacion=8;
          $titulo='Recibir documento';
        }

      }
      if(evaluar_flag($_SESSION['id_persona'],8017,302,'flag_autoriza')==1 || usuarioPrivilegiado_acceso()->accesoModulo(7851)){
        if($estado_id == 8164 && $e['Ped_bitacora_id' == '']){
          $verificacion=9;
          $titulo='Asignar y Revisar';
        }

        if($estado_id == 8164 && $e['Ped_bitacora_id']==8145){// revisión compras
          $verificacion=11;
          $titulo='Reasignar y Revisar';
        }
        if($estado_id == 8147){// anulado compras
          $verificacion=1100;
        }
        if($estado_id == 8144){
          $verificacion ==1000;
        }
      }

      //EMPLEADO ASIGNADO AL PEDIDO Y REMESA
      if(evaluar_flag($_SESSION['id_persona'],8017,302,'flag_actualizar')==1 && $_SESSION['id_persona']==$asig['id_persona'] || usuarioPrivilegiado_acceso()->accesoModulo(7851)){
        if($estado_id == 8164 && $e['Ped_bitacora_id']==8145){// revisión compras
          $verificacion=10;
        }
        if($estado_id == 8164 && $e['Ped_bitacora_id'] == 8148){
          $verificacion=12;
          $titulo='Reasignar y Revisar';
        }
        if($estado_id == 8146 && $e['Ped_bitacora_id']== 0){
          $verificacion=13;
        }
        if($estado_id == 8146 && $e['Ped_bitacora_id']== 8149){
          $verificacion=14;
        }
        if($estado_id == 8147){// anulado compras
          $verificacion=1100;
        }
      }

  }else{
    $verificacion = 1200;
  }


  //$verificacion= $asig['id_persona'];
  $data = array(
    'estado_id'=>$estado_id,
    'estado'=>$estado,
    'titulo'=>$titulo,
    'verificacion'=>$verificacion,
    'tipo_verificacion'=>$tipo_verificacion,
    'bitacora_id'=>$e['Ped_bitacora_id']
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
