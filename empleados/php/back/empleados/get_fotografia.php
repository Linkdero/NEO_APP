<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona=$_POST['id_persona'];
  $e = array();
  $e = empleado::get_empleado_fotografia($id_persona);
  $data = array();

  $encoded_image = base64_encode($e['fotografia']);
  $Hinh='<div></div>';
  if($e['fotografia']!=''){
    $Hinh.='<div class="col-md-4 border-md-right border-light text-center" style="">
      <div class="img_foto img-contenedor_profile" style="border-radius:50%">';
    $Hinh.= "<div class='img-fluid mb-3'><img class='img-fluid mb-3 img_foto text-center width:50px' style='' src='data:image/jpeg;base64,{$encoded_image}' > </div>";
    $Hinh.="</div></div>";
  }else{
    $Hinh='Sin <br> fotografía';
  }


  $data = array(
    'fotografia'=>$Hinh

  );




    echo json_encode($data);


else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
