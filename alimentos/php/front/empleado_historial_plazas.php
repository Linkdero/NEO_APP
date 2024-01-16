<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    include_once '../../back/functions.php';
    //$permiso=array();

    if ( !empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_100");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);
      $foto = empleado::get_empleado_fotografia($id_persona);
      $encoded_image = base64_encode($foto['fotografia']);
      $Hinh = "<img class='img-fluid rounded-circle mb-3' src='data:image/jpeg;base64,{$encoded_image}' width='84'> ";
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/cargar.js"></script>
      <script src="empleados/js/source_modal.js"></script>
      <script>
      $(function(){

      });

      </script>
    </head>
    <body>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial de Plazas</h5>
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
          <input type="text" id="id_persona" value="<?php echo $id_persona?>" hidden></input>

					<div class="" id="datos">

  							<div class="row">


  								<div class="col-md-12" >

                        <table id="tb_plazas_por_empleado" class="table table-sm" width="100%">
                          <thead>
                            <th class="text-center">Partida</th>
                            <th class="text-center">Plaza</th>
                            <th class="text-center">Puesto</th>
                            <th class="text-center">Inicio</th>
                            <th class="text-center">Fin</th>
                            <th class="text-center">Sueldo</th>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>



<!--fin-->
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
  echo "<script type='text/javascript'> window.location='principal'; </script>";
}
?>
