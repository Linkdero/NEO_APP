<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $partida=null;

    if ( !empty($_GET['partida'])) {
      $partida = $_REQUEST['partida'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_101");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);


    }


    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/source_modal.js"></script>
      <script>


      </script>
    </head>
    <body>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial de la Plaza </h5> " <?php echo $partida; ?> "
        <ul class="list-inline ml-auto mb-0">






          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="u-content">
				<div class="u-body">
          <input type="text" id="partida" value="<?php echo $partida?>" hidden></input>

					<div class="" id="datos">
            <table id="tb_historial_plaza" class="table table-sm table-bordered table-striped" width="100%">
              <thead>
                <th class="text-center">Fecha Final</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Fecha Final</th>
              </thead>
              <tbody>
              </tbody>
            </table>
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
