<?php
include_once '../../../../inc/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="vehiculos/js/validaciones.js"></script>
    <script src="vehiculos/js/vales_vue_new.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>
  

    <script>
      setTimeout(() => {
        $("#id_conductor" ).select2({
        })}, 400);
    </script>    

</head>
<body>
<div class="modal-header">
    <h5 class="modal-title">Nuevo Vale de Combustible</h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item">
        <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body" id="app_nuevo_vale">
    <form class="validation_nuevo_vale" name="miForm">
      <div class="row">

      <div class="col-sm-6">
        <div class="form-group">
          <div class="">
              <label for="id_destino">Destino de Combustible</label>
              <select class="form-control form-control-sm" id="id_destino" name="id_destino" v-on:change="mostrar_caracter()">
                <option v-for="d in destino" v-bind:value="d.id_desti" >{{ d.destino_str }}</option>
              </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6" id="div_vehiculo">
        <div class="form-group">
          <div class="">
            <label for="lbl_vehiculo" >Placa</label>
              <select class="form-control form-control-sm" id="id_vehiculo" name="id_vehiculo" v-on:change="getTipoCombustible()" >
                  <option v-for="p in placas" v-bind:value="p.id_vehiculo" >{{ p.nro_placa }}</option>
              </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6" id="div_caracter" style="display:none">
        <div class="form-group">
          <div class="">
            <label for="lbl_Caract">Caracteristicas</label>
              <div class="input-group  has-personalizado" >
                <input type="text" class="form-control form-control-sm" id="txt_caracter" name="txt_caracter" placeholder="Ingrese descripcion" autocomplete="off"/>
              </div>
          </div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="form-group">
          <div class="">
            <div class="" >
              <label for="id_galones">Galones autorizados</label>
              <input type="number" class="form-control form-control-sm" id="id_galones" name="id_galones" required ></input>   <!-- v-bind:value="capaTanque.capaT"   v-bind:max="capaTanque.capaT"  --> 
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label for="">Tanque Lleno</label>
          <div class="input-group  has-personalizado">
            <label class="css-input switch switch-success">   
                    <input name="chk_Tanque" id="chk_Tanque" data-id="" v-on:change="get_capa()" data-name="" type="checkbox" false/><span></span> 
             </label>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <div class="">
            <label for="id_combustible">Tipo de combustible</label>
              <select class="form-control form-control-sm" id="id_combustible" name="id_combustible" required>
                  <option v-for="t in tipos" v-bind:value="t.id_tipo" >{{ t.combust_str }}</option>
              </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <div class="" >
            <label for="id_conductor">Conductor</label>
              <select class="form-control form-control-sm" id="id_conductor" name="id_conductor" >
                  <option v-for="c in conductores" v-bind:value="c.id_persona" >{{ c.conduc_str }}</option>
              </select>
          </div>
        </div>
      </div> 

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="descripcion">Observaciones</label>
                <div class=" input-group  has-personalizado" >
                  <textarea type="text" rows='3' class=" form-control " id="observa" name="observa" placeholder="Observaciones" autocomplete="off"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
        <button class="btn btn-block btn-sm btn-info" onclick="save_vale_combustible()"><i class="fa fa-save"></i> Guardar</button>    
        </div>
      </div>
      
    </form>
  </div>



</div>
  <script>
    set_dates();

   </script>

<?php }
else{
  include('inc/401.php');
}
}
else{
  header("Location: index");
}
?>
