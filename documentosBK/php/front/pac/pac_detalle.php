<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(8017)) { //privilegio documento
    date_default_timezone_set('America/Guatemala');

    $pac_id = null;

    if ( !empty($_GET['pac_id'])) {
      $pac_id = $_REQUEST['pac_id'];
    }



    if (!empty($_POST)){
      header("Location: index.php?ref=_900");
    }else{

    }

?>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="ISO-8859-1">

    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">

    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
    <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
    <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
    <script src="documentos/js/pac/pac_detalle.js"></script>
    <script>
    </script>
    <div id="app_pac_detalle">
      <div class="modal-header">
        <h3>Detalle del Plan</h3>
        <ul class="list-inline ml-auto mb-0">
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-personalizado btn-sm" >
              <input type="radio" name="options" id="option1" autocomplete="off" checked > Detalle
            </label>
            <!--<label class="btn btn-personalizado btn-sm" @click="getOpcion(2)">
              <input type="radio" name="options" id="option2" autocomplete="off"> Seguimiento
            </label>
            <label class="btn btn-personalizado btn-sm" @click="getOpcion(3)">
              <input type="radio" name="options" id="option3" autocomplete="off"> Bitácora
            </label>
            <label class="btn btn-personalizado btn-sm" @click="getOpcion(4)" >
              <input type="radio" name="options" id="option1" autocomplete="off" > Facturas
            </label>-->
            <label class="btn btn-personalizado btn-sm salida" >
              <span name="options" id="option3" autocomplete="off"  > Salir
            </label>
          </div>
        </ul>
      </div>
      <div class="modal-body">
        <!--<input id="id_cambio" :value="cambio" hidden></input>-->
        <input id="pac_id" value="<?php echo $pac_id?>" hidden></input>
        <input id="id_direccion" :value="pac.id_direccion" hidden></input>
        <!--<input id="id_verificacion" :value="estado.verificacion" hidden></input>-->
        <div class="row">

          <pac-detalle :pac_id="pac_id" v-on:event_child="getPacD"></pac-detalle>
          <pac-meses-listado :pac_id="pac_id" :arreglo="pac"></pac-meses-listado>
        </div>

      </div>
    </div>


  <?php
  }
} else {
  header("Location: index.php");
}
  ?>
