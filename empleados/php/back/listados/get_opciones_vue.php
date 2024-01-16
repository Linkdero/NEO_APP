<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';


    $clase= new empleado();
    $nivel = $_GET['nivel'];
    $tipo = $_GET['tipo'];
    $superior=$_GET['superior'];
    $opcion=$_GET['opcion'];
    $direcciones = $clase->get_rrhh_direcciones($nivel,$tipo,$superior,$opcion);

    $data = array();

    if($direcciones["status"] == 200){
        $response = "";
        $sub_array = array(
          'id'=>'',
          'option'=>'- Seleccionar -'
        );
        $data[] = $sub_array;
        foreach($direcciones["data"] as $d){
            //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";
            $sub_array = array(
              'id'=>$d['id_direccion'],
              'option'=>$d['descripcion']
            );
            $data[] = $sub_array;
        }

    }else{
        $response = $direccion["msg"];
    }

  echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
