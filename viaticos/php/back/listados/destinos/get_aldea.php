<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../functions.php';
?>
<?php
    $id_municipio = $_GET["municipio"];
    $aldeas = viaticos::get_aldeas($id_municipio);

    if($aldeas["status"] == 200){
        $response = "";
        $sub_array = array(
          'lugar_id'=>'',
          'lugar_string'=>'-- Seleccionar --'
        );
        $data[]=$sub_array;
        foreach($aldeas["data"] as $aldea){
          $sub_array = array(
            'lugar_id'=>$aldea['id_aldea'],
            'lugar_string'=>$aldea['nombre']
          );
          $data[]=$sub_array;
        }
    }else{
        $response = $aldeas["msg"];
    }
    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
