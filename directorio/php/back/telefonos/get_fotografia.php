<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';


    $id_persona = $_POST['id_persona'];

    $Hinh = 'Sin foto';
    if (!is_int($id_persona)) {

      $e = array();
      $e = Directorio::get_empleado_fotografia($id_persona);
      $data = array();
      if ($e['fotografia'] != '') {
        $encoded_image = base64_encode($e['fotografia']);

        $Hinh = "<div class='col-md-4 border-md-right border-light text-center' style='' style='height:10px'>
                  <div class='img_foto img-contenedor_profile' style='border-radius:50%' >
                    <div class='img-fluid mb-3'><img class='img-fluid mb-3 img_foto text-center'  src='data:image/jpeg;base64,{$encoded_image}' ></div>
                  </div>
              </div>";
      } else {
        $Hinh = "<div class='col-md-4 border-md-right border-light text-center' style='' style='height:10px'>
                <div class='img_foto img-contenedor_profile' style='border-radius:50%' >
                  <div class='img-fluid mb-3'><img class='img-fluid mb-3 img_foto text-center'  src='assets/img/logo-mobile.png' ></div>
                </div>
              </div>";
      }
    } else {
      $Hinh = "<div class='col-md-4 border-md-right border-light text-center' style='' style='height:10px'>
            <div class='img_foto img-contenedor_profile' style='border-radius:50%' >
              <div class='img-fluid mb-3'><img class='img-fluid mb-3 img_foto text-center'  src='assets/img/logo-mobile.png' ></div>
            </div>
          </div>";
    }

    $data = array(
      'Foto' => $Hinh
    );

    echo json_encode($data);


  else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
  endif;


    ?>
