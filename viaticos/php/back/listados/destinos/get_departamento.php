<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../functions.php';
?>
<?php
$pais=$_GET['pais'];
    $departamentos = viaticos::get_departamentos($pais);
    $data = array();
    if($departamentos["status"] == 200){

      $sub_array = array(
        'dep_id'=>'',
        'dep_string'=>'-- Seleccionar --'
      );

      $data[]=$sub_array;
      foreach($departamentos["data"] as $departamento){
        $sub_array = array(
          'dep_id'=>$departamento['id_departamento'],
          'dep_string'=>$departamento['nombre']
        );
        $data[]=$sub_array;
      }
    }else{
        $response = $departamentos["msg"];
    }
    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
