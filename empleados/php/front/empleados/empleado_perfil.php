<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    include_once '../../back/functions.php';
    //$permiso=array();
    $id_persona = $_POST['id_persona'];

    /*$foto = empleado::get_empleado_fotografia($id_persona);
    $encoded_image = base64_encode($foto['fotografia']);
    $Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";*/

    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">

      <script>
      $(function(){
        get_empleado_datos();
      });
      </script>
    </head>
    <body>
      
    </body>

  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
