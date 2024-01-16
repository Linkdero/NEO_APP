<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    include_once '../../back/functions.php';
    $archivo = null;
    $archivor = null;
    $tipo = null;
    //$permiso=array();

    if ( !empty($_GET['archivo'])) {
      $archivo = $_REQUEST['archivo'];
    }
    if ( !empty($_GET['archivor'])) {
      $archivor = $_REQUEST['archivor'];
    }
    if ( !empty($_GET['tipo'])) {
      $tipo = $_REQUEST['tipo'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_100");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/modelContrato.js"></script>
    </head>
    <div id="archivo_app">
      <div class="modal-header">
        <h5 class="modal-title" id="">Contrato actual</h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="modal-body">
        <input type="text" id="id_archivo" value="<?php echo $archivo?>" hidden></input>
        <input type="text" id="id_archivor" value="<?php echo $archivor?>" hidden></input>
        <input type="text" id="tipo" value="<?php echo $tipo?>" hidden></input>

        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn btn-personalizado active btn-sm" @click="getOpcion(1)" checked v-if="docContrato == true">
            <input type="radio" name="options" id="option1" autocomplete="off" checked > Contrato
          </label>
          <label class="btn btn-personalizado btn-sm" id="opt2" @click="getOpcion(2)" v-if="docResolucion == true">
            <input type="radio" name="options" id="option2" autocomplete="off"> Resolución
          </label>
        </div>
        <br>
        <br>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="contrato" role="tabpanel" aria-labelledby="home-tab">
            <vue-pdf-app style="height: 50vh;" :pdf="pdf" :config="config" theme="dark"></vue-pdf-app>
          </div>
        </div>


      </div>
    </div>
  </div>

  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
