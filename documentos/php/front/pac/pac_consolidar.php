<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  if (usuarioPrivilegiado_acceso()->accesoModulo(8017)) { //privilegio documento
    date_default_timezone_set('America/Guatemala');

    $planes = null;
    $renglon = null;

    if ( !empty($_GET['planes'])) {
      $planes = $_REQUEST['planes'];
    }

    if ( !empty($_GET['renglon'])) {
      $renglon = $_REQUEST['renglon'];
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
    <script src="assets/js/plugins/contador/flip.min.js"></script>
    <link href="assets/js/plugins/contador/flip.min.css" media="all" rel="stylesheet" />

    <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css" />

    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
    <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
    <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
    <script src="documentos/js/pac/pac_detalle_listado.js"></script>
    <style>
    .tick {
  font-size:1rem; white-space:nowrap; font-family:arial,sans-serif;
}

.tick-flip,.tick-text-inline {
  font-size:2em;
}

.tick-label {
  margin-top:1em;font-size:1em;
}

.tick-char {
  width:1.5em;
}

.tick-text-inline {
  display:inline-block;text-align:center;min-width:1em;
}

.tick-text-inline+.tick-text-inline {
  margin-left:-.325em;
}

.tick-group {
  margin:0 .5em;text-align:center;
}

.tick-text-inline {
   color: #595d63 !important;
}

.tick-label {
   color: #595d63 !important;
}

.tick-flip-panel {
   color: #fff !important;
}

.tick-flip {
   font-family: !important;
}

.tick-flip-panel-text-wrapper {
   line-height: 1.45 !important;
}

.tick-flip-panel {
   background-color: #3c3e3c !important;
}

.tick-flip {
   border-radius:0.12em !important;
}
    </style>


    <script>
    function handleTickInit(tick) {

        // set final value, tick will animate towards it
        setTimeout(function(){
            tick.value = $('#id_renglon_consolidar').val();
        }, 100);

        // play with arrive() values to tune animation speed and duration
        // 1. first value is maximum speed of increase
        // 2. second value is speed at which the increase ramps up

    }

    </script>
    <div id="app_pac_detalle_listado">
      <div class="modal-header">
        <h3>Planes selccionados: {{ totalPlanes }}</h3>
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
        <form class="jsValidacionConsolidarPac">
          <input id="planes" name="planes" value="<?php echo $planes?>" hidden></input>
          <input id="id_renglon_consolidar" name="id_renglon_consolidar" value="<?php echo $renglon?>" hidden></input>
          <input id="id_direccion" :value="pac.id_direccion" hidden></input>
          <!--<input id="id_verificacion" :value="estado.verificacion" hidden></input>-->

          <div class="row">

            <div class="col-sm-5" >
              <div class="row" style="max-height:1020px; overflow-y: auto; overflow-x: none">
                <pac-detalle-listado :pac_id="planes" v-on:event_child="getPacD" columna="col-sm-11"></pac-detalle-listado>
              </div>
            </div>

            <div class="col-sm-7">
              <div class="row">
                <div class="col-sm-6">
                  <h1>Renglón: {{ id_renglon }} </h1>
                  <div class="tick" data-value="0" data-did-init="handleTickInit">

                      <div data-layout="horizontal center" data-repeat="true" data-transform="arrive(9, .001) -> round -> pad('   ') -> split -> delay(rtl, 100, 150)">

                          <span data-view="flip"></span>

                      </div>

                  </div>
                  <div class="renglon_"></div>
                </div>
                <campo row="col-sm-6" label="Nombre de la compra:" codigo="id_nombre_consolidado" tipo="text" requerido="true"></campo>
                <campo row="col-sm-12" label="Descripción del bien o servicio:" codigo="id_descripcion_consolidado" tipo="textarea" requerido="true"></campo>
              </div>


              <meses-listado columna="col-sm-3" label="Mes de compra:*" v-on:send_months="recibirMeses" tipo="2"></meses-listado>
            </div>
            <div class="col-sm-12 text-right">
              <button class="btn btn-sm btn-info" @click="consolidarPac($event)"><i class="fa fa-check"></i> Consolidar</button>
            </div>

          </div>
        </form>


      </div>
    </div>




  <?php
  }
} else {
  header("Location: index.php");
}
  ?>
