<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../functions.php';
?>
<?php
    $id_departamento = $_GET["departamento"];
    $municipios = viaticos::get_municipios($id_departamento);
    $data = array();
    if($municipios["status"] == 200){
      $sub_array = array(
        'muni_id'=>'',
        'muni_string'=>'-- Seleccionar --'
      );
      $data[]=$sub_array;
        foreach($municipios["data"] as $municipio){
          $sub_array = array(
            'muni_id'=>$municipio['id_municipio'],
            'muni_string'=>$municipio['nombre']
          );
          $data[]=$sub_array;
        }
    }else{
        $response = $municipios["msg"];
    }
    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
