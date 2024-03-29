<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){
    $arreglo = $_GET['arreglo'];
    $orden = '';//$_GET['nro_orden'];
    //echo $tipo_ope;
    /*include_once '../../back/functions.php';
    $p_i = documento::get_pedido_by_pedido_interno(1,date('Y'));
    echo $p_i['Ped_tra'];*/
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>

  <script src="documentos/js/validaciones.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="assets/js/pages/components.js"></script>
  <script src="documentos/js/components/components1.13.js"></script>
  <script src="documentos/js/facturas/modelAsignarCheque1.1.js" ></script>

  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <script src="assets/js/plugins/select2/select2.min.js"></script>


  <script>
  </script>
</head>

<div id="appAsignarCheque">


  <div class="modal-header">
    <h3>Asignar Cheque</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" >
    <privilegios-user v-on:privilegio_user="getPermisosUser"></privilegios-user>
    <input id="cambio" hidden></input>
    <form class="jsValidacionAsignarCheque" id="formAsignarCheque">
      <h3>Factura Seleccionada</h3>
      <hr>
      <input id="orden_id_c" name="orden_id_c" value="<?php echo $arreglo?>" hidden></input>

        <facturas-seleccionadas :arreglo="arreglo" tipo="1"></facturas-seleccionadas>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="fecha_factura">Seleccionar cheque:*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="js-example-disabled-results" id="nro_cheque" name="nro_cheque" style="width:100%">
                      <option v-for="c in arregloCheques" :disabled="c.disponible" v-bind:value="c.id_item"> {{ c.item_string }} </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button  class="btn btn-sm btn-block btn-info" @click="asignarCheque()"><i class="fa fa-check-circle"></i> Asignar el Chequen</button>
      </div>

    </form>

  </div>



  <?php
 }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index.php");
}
?>
