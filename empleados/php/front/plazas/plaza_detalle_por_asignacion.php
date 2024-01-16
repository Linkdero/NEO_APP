<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_plaza=$_POST['id_plaza'];
    $id_asignacion=$_POST['id_asignacion'];

    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/emp_plaza_vue.js"></script>


      <script>


      </script>
    </head>
    <body>
      <div class="row" id="em_plaza_app">

adfajdfljaldfjladksj

      </div>
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
