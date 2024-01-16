<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../functions.php';
?>
<?php
    $paises = viaticos::get_paises();
    $data = array();
    if($paises["status"] == 200){
        $response = "";
        $sub_array = array(
          'id_item'=>'GT',
          'item_string'=>'GUATEMALA'
        );
        $data[]=$sub_array;
        foreach($paises["data"] as $pais){
          $sub_array = array(
            'id_item'=>$pais['id_pais'],
            'item_string'=>$pais['nombre']
          );
          $data[]=$sub_array;
        }
    }else{
        $response = $pais["msg"];
    }
    echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
