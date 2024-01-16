<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';

  $id_persona = $_POST['id_persona'];
  $HORARIO = new Horario();
  $data = $HORARIO->get_empleado_fotografia($id_persona);
  
  if($data['fotografia'] != ""){
    $encoded_image = base64_encode($data['fotografia']);
    $foto = "<div class='col-md-4 border-md-right border-light text-center' style=''>
                <div class='img_foto img-contenedor_profile' style='border-radius:50%'>
                    <div class='img-fluid mb-3'><img class='img-fluid mb-3 img_foto text-center width:50px' src='data:image/jpeg;base64,{$encoded_image}' ></div>
                </div>
            </div>";
  }else{
    $foto = "<div class='col-md-4 border-md-right border-light text-center' style=''>
                <div class='img_foto img-contenedor_profile' style='border-radius:50%'>
                    <div class='img-fluid mb-3'><img class='img-fluid mb-3 img_foto text-center width:50px' src='assets/img/default-avatar.png' ></div>
                </div>
            </div>";
  }
  echo $foto;

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
