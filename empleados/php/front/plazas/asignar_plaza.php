<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_partida=null;

    if ( !empty($_GET['partida'])) {
      $id_partida = $_REQUEST['partida'];
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
      <script src="empleados/js/cargar.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/plugins/fuse/fuse.js"></script>

      <script>
      $(document).ready(function(){
        cargar_asignar_plaza(<?php echo $id_partida;?>);
      })

      </script>
    </head>
    <body>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asignar Plaza </h5> " <?php echo $id_partida; ?> "
        <ul class="list-inline ml-auto mb-0">






          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="modal-body">
        <input type="text" id="partida" value="<?php echo $partida?>" hidden></input>

        <div class="" id="datos_partida">
          <!-- inicio -->
          <!-- fin-->
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
