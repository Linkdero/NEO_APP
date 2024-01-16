<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    $id_persona=$_POST['id_persona'];
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/source_modal.js"></script>
      <script>
      $(function(){

      });

      </script>
    </head>
    <body>
      <div class="row">
        <div class="col-md-12" >
          <table id="tb_plazas_por_empleado_h" class="table table-sm table-striped table-bordered" width="100%">
            <thead>
              <!--<th class="text-center">ID</th>-->
              <th class="text-center">Plaza</th>
              <th class="text-center">Partida</th>

              <th class="text-center">Puesto</th>
              <th class="text-center">Inicio</th>
              <th class="text-center">Fin</th>
              <th class="text-center">Sueldo</th>
              <th class="text-center">Estado</th>
              <th class="text-center">Acción</th>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>

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
