<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(1163)) { //1163 módulo recursos humanos
    $id_persona = null;
    include_once '../../back/functions.php';
    $id_persona = null;

    if (!empty($_GET['id_persona'])) {
      $id_persona = $_REQUEST['id_persona'];
    }


    if (!empty($_POST)) {
      header("Location: index.php?ref=_100");
    } else {
      //$persona=empleado::get_empleado_by_id($id_persona);
    }
    ?>

    <!DOCTYPE html>
    <html>

    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/modelFicha.1.4.js"></script>
    </head>
    <div id="ficha_app">
      <div class="modal-header">
        <h5 class="modal-title" id="">Ficha de la Persona</h5>
        <ul class="list-inline ml-auto mb-0">
          <li class="list-inline-item" data-toggle="tooltip" title="Imprimir" @click="printFicha()">
            <span class="link-muted h3">
              <i class="fa fa-print"></i>
            </span>
          </li>
          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <input type="text" id="id_pdf" name="id_pdf" hidden></input>
      <input type="text" id="id_persona" value="<?php echo $id_persona ?>" hidden></input>
      <div class="loaderr" id="id_cargando_ficha" style="position:absolute; margin-left:45%; margin-top:15%"></div>
      <vue-pdf-app :key="key" style="height: 80vh;" :pdf="pdf" :config="config" theme="dark" page-width="0"></vue-pdf-app>

    </div>
    </div>

  <?php } else {
    include('inc/401.php');
  }
} else {
  header("Location: index");
}
?>