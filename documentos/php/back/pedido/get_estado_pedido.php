<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  include_once '../functions/return_privilegios.php';
  $priv = retornaPrivilegios();
  $response = array();
  $ped_tra=0;
  $privilegio = array();
  if(isset($_GET['ped_tra'])){
    $ped_tra=$_GET['ped_tra'];
  }

  $privilegio = evaluar_flags_by_sistema($_SESSION['id_persona'],8017);


  $clase = new documento;

  $e = $clase->get_estado_pedido($ped_tra);
  $asig = $clase->get_persona_asignada($ped_tra);
  $data = array();

  $verificacion='';
  $verificacion_s='';
  $titulo='';
  $estado_id=(!empty($e['Ped_status']))?$e['Ped_status']:0;
  $estado= (!empty($e['estado']))?$e['estado']:' Registrado ';
  $tipo_verificacion= (!empty($e['tipo_verificacion']))?$e['tipo_verificacion']:'';

  /*'plani_au'=>($array[8]['flag_autoriza'] == 1) ? true : false,
  'plani_rev'=>($array[8]['flag_actualizar'] == 1) ? true : false,

  'ssa_au'=>($array[6]['flag_autoriza'] == 1) ? true : false,

  'compras_au'=>($array[2]['flag_autoriza'] == 1) ? true : false,
  'compras_recepcion'=>($array[2]['flag_insertar'] == 1) ? true : false,
  'compras_tecnico'=>($array[2]['flag_actualizar'] == 1) ? true : false,
  'compras_asignar_tecnico'=>($array[2]['flag_actualizar'] == 1) ? true : false,

  'directorf_au'=>($array[5]['flag_autoriza'] == 1) ? true : false,
  'presupuesto_au'=>($array[12]['flag_autoriza'] == 1) ? true : false,
  'tesoreria_au'=>($array[13]['flag_autoriza'] == 1) ? true : false,*/

  if($estado_id != 8170){
    $um = usuarioPrivilegiado_acceso();
    //planificación
    //2 = compras;
    //6 subsecretaria
    //8 planificacion
    //5 director
    //12 presupuesto
    //13 tesoreria
    if($priv['plani']== 1 || $privilegio[3]['flag_es_menu'] == 1 || $um->accesoModulo(7851)){
      if($priv['plani_au'] && $priv['plani_seguimiento'] == 1 || $priv['plani_rev'] || $um->accesoModulo(7851)){

        if($estado_id==0){
          $verificacion=1;
        }
        if($estado_id==8156 && empty($asig['id_persona']) && $priv['plani_seguimiento'] == 1){
          $titulo = 'Asignar para revisión';
          $verificacion=1010;
        }
        if($estado_id==8156 && !empty($asig['id_persona']) && $priv['plani_seguimiento'] == 1){
          $titulo = 'Asignar para revisión';
          $verificacion=1010;
        }
        if($estado_id==8156 && !empty($asig['id_persona']) && $_SESSION['id_persona']==$asig['id_persona'] && $privilegio[8]['flag_actualizar']){
          $titulo = '';
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
        if($estado_id == 9108){
          $verificacion=4;
          $titulo='Recibir documento';
        }
      }else if($estado_id == 0 && $privilegio[3]['flag_eliminar'] == 1 && $estado_id != 8170){
        $titulo = 'Anular Pedido';
        $verificacion = 0;
      }

    }
    if($priv['ssa']== 1 || $um->accesoModulo(7851)){
      //subsecretaría
      if($priv['ssa_au'] == 1 || $um->accesoModulo(7851)){
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
        if($estado_id == 9109){
          $verificacion=7777;
        }
      }
    }

      if($privilegio[2]['flag_acceso'] == 1 || $um->accesoModulo(7851)){

        if($estado_id == 8143 && $e['Ped_bitacora_id'] == 8161){
          $verificacion=8;
          $titulo='Recibir documento';
        }

      }
      if($priv['compras_au'] == 1  || $um->accesoModulo(7851)){
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
      if($priv['compras_tecnico'] == 1 && $_SESSION['id_persona']==$asig['id_persona'] || $um->accesoModulo(7851)){
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
        if($estado_id == 8164 && $e['Ped_bitacora_id'] == 8148){
          $verificacion_s=999;
          //$titulo='Reasignar y Revisar';
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
    'verificacion_s' =>$verificacion_s,
    'tipo_verificacion'=>$tipo_verificacion,
    'bitacora_id'=>$e['Ped_bitacora_id']
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
