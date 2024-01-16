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
      <script src="empleados/js/emp_plaza_vue.js"></script>


      <script>


      </script>
    </head>
    <body>
      <div class="row" id="em_plaza_app">
        <!--<span class="col-sm-1 letra" ><span class="fa fa-user text-muted"></span></span>-->
        <span class="col-sm-12 letra"><small class="text-muted">Nombres y Apellidos: </small>
          <h5 class="font-weight-bold">{{empleado_plaza.nombre}}</h5>
          <hr>
        </span>
        <div class="col-sm-12">
          <span class="numberr">1</span><strong class=""> Datos de la plaza Presupuestaria</strong><br><br>
        </div>

        <div class="col-sm-6">
          <!--<hr>-->

          <div class="row">
            <span class="col-sm-1 letra" ><span class="fa fa-hotel text-muted"></span></span>
            <span class="col-sm-11 letra"><small class="text-muted">Código de la plaza: </small>
              <h5 class="font-weight-bold">{{empleado_plaza.cod_plaza}}</h5>
            </span>
            <span class="col-sm-1 letra" ><span class="fa fa-hotel text-muted"></span></span>
            <span class="col-sm-11 letra"><small class="text-muted">Partida presupuestaria: </small>
              <h5 class="font-weight-bold">{{empleado_plaza.partida}}</h5>
            </span>
          </div>
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
