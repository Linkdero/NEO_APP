<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../../functions.php';
    $id_persona=$_GET['id_persona'];
    $telefonos = array();
    $telefonos = empleado::get_telefonos_by_empleado($id_persona);
    $data = array();

    $permiso = "";


    foreach ($telefonos as $t){
      if($t['nro_telefono']!=0){
        $arreglo = array(
          'id_persona'=>$t['id_persona'],
          'id_telefono'=>$t['id_telefono'],
          'flag_privado'=>($t['Telefono_Privado'] == 1) ? true : false,
          'flag_activo'=>($t['Telefono_Activo'] == 1) ? true : false,
          'flag_principal'=>($t['Telefono_Principal'] == 1) ? true : false,
          'tipo'=>$t['id_tipo_referencia'],
          'id_tipo_telefono'=>$t['id_tipo_telefono'],
          'nro_telefono'=>$t['nro_telefono'],
          'observaciones'=>$t['observaciones'],
        );
        $sub_array = array(
          'id_telefono'=>$t['id_telefono'],
          'nombre_tipo_referencia'=>$t['nombre_tipo_referencia'],
          'nombre_tipo_telefono'=>$t['nombre_tipo_telefono'],
          'nro_telefono'=>$t['nro_telefono'],
          'fprivado'=>($t['Telefono_Privado'] == 1) ? true : false,
          'factivo'=>($t['Telefono_Activo'] == 1) ? true : false,
          'fprincipal'=>($t['Telefono_Principal'] == 1) ? true : false,
          'cprivado'=>($t['Telefono_Privado'] == 1) ? 'fa fa-check-circle text-info' : 'fa fa-times-circle text-danger',
          'cactivo'=>($t['Telefono_Activo'] == 1) ? 'fa fa-check-circle text-info' : 'fa fa-times-circle text-danger',
          'cprincipal'=>($t['Telefono_Principal'] == 1) ? 'fa fa-check-circle text-info' : 'fa fa-times-circle text-danger',
          'arreglo'=>$arreglo,
          'permiso'=>$permiso
        );

        $data[]=$sub_array;
      }

    }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data);

    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
