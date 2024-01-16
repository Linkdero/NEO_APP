<?php include_once '../../../../inc/functions.php';
        include_once '../../back/functions.php';
        sec_session_start();
        if (function_exists('verificar_session') && verificar_session()){
          if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){

            $nro_vale = $_GET["nro_vale"];
    
            $tipo = new vehiculos();
            $datat = $tipo::get_TipoDespacho($nro_vale);
            $xTipo = "Placa";
            if ($datat["id_tipo_uso"] == 1144) {
                $data = $tipo::get_valeDespacho($nro_vale);
            }elseif ($datat["id_tipo_uso"] == 1147) {
                $data = $tipo::get_valeDespacho_Ext($nro_vale);
            }else {
                $xTipo = "Caracteristicas";
                $data = $tipo::get_valeDespacho_Gen($nro_vale);
            }
            $id_tipo=$data["id_tipo_combustible"]
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="vehiculos/js/validaciones.js"></script>
    <script src="vehiculos/js/vales_vue_des.js"></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
    <script src="assets/js/plugins/select2/select2.min.js"></script>

    <script>
      setTimeout(() => {
        $("#id_despacha").select2({})
        $("#id_recibe"  ).select2({})}, 400);
    </script>

</head>
<body>

    <div class="modal-header">
        <h5 class="modal-title">Despacho de Vale de Combustible</h5>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>

    <div class="modal-body" id="app_despacha_vale">
      <form class="validation_desp_vale" name="miForm">

      <div class="row">

        <div class="col-sm-3">
          <div class="form-group">
            <div class="">
                <label for="nro_vale" class="col-form-label-md">Número de Vale</label>
                <div class="input-group  has-personalizado" >
                  <input type="text" class="form-control form-control-sm" id="nro_vale" name="nro_vale" value="<?php echo $data["nro_vale"]; ?>" disabled/>
                </div>
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <div class="">
              <label for="cant_autor" class="col-form-label-md">Galones</label>
              <div class="input-group  has-personalizado" >
                <input type="number" class="form-control form-control-sm" id="cant_autor" name="cant_autor" value="<?php echo $data["cant_autor"]; ?>" disabled/>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-1">
          <div class="form-group">
            <label for="" class="col-form-label-md">Lleno</label>
            <div class="input-group  has-personalizado">
              <label class="css-input switch switch-success switch-sm">   
                <input name="chk_Tanque" id="chk_Tanque" type="checkbox" <?php if($data['tlleno']==1) {echo 'checked';} ?> disabled/><span></span> 
              </label>
            </div>
          </div>
        </div>

        <div class="col-sm-5" id="div_tipo_comb">
          <div class="form-group">
            <div class="">
              <label for="lbl_Tipo_Comb" class="col-form-label-md">Tipo de Combustible</label>
              <div class="input-group  has-personalizado" >
                <input type="text" class="form-control form-control-sm" id="tipo_comb" name="tipo_comb" value="<?php echo $data["tipo_comb"]; ?>" disabled/>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="row">

      <div class="col-sm-6">
        <div class="form-group">
          <div class="" >
            <label for="id_despacha">Persona despacha gasolina</label>
              <select class="form-control form-control-sm" id="id_despacha" name="id_despacha" >
                  <option v-for="d in despacha" v-bind:value="d.id_persona" >{{ d.despacha_str }}</option>
              </select>
          </div>
        </div>
      </div> 

      <div class="col-sm-6">
        <div class="form-group">
          <div class="" >
            <label for="id_recibe">Persona recibe gasolina</label>
              <select class="form-control form-control-sm" id="id_recibe" name="id_recibe" >
                  <option v-for="r in recibe" v-bind:value="r.id_persona" >{{ r.recibe_str }}</option>
              </select>
          </div>
        </div>
      </div> 

      </div>

      <div class="row">

      <div class="col-sm-4">
        <div class="form-group">
          <div class="">
            <label for="id_bomba">Bomba de despacho</label>
              <select class="form-control form-control-sm" id="id_bomba" name="id_bomba" required>
                  <option v-for="b in bomba" v-bind:value="b.id_bomba" >{{ b.bomba_str }}</option>
              </select>
          </div>
        </div>
      </div>

      <div class="col-sm-1"></div>
      <div class="col-sm-3">
        <div class="form-group">
          <div class="">
            <div class="" >
              <label for="cant_galones">Galones entregados</label>
              <input type="number" class="form-control form-control-sm" id="cant_galones" name="cant_galones" required ></input> 
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-1"></div>
      <div class="col-sm-3">
        <div class="form-group">
          <div class="">
            <label for="km_actual" class="col-form-label-md">Kilometraje actual</label>
            <div class="input-group  has-personalizado" >
                <input type="number" class="form-control form-control-sm" id="km_actual" name="km_actual" requiered ></input> 
            </div>
          </div>
        </div>
      </div>      

      </div>

      <div class="col-sm-3" hidden>
        <div class="form-group">
          <div class="">
            <label for="id_tipo_combustible" class="col-form-label-md">id_tipo_combustible</label>
            <div class="input-group  has-personalizado" >
              <input type="text" class="form-control form-control-sm" id="id_tipo_combustible" name="id_tipo_combustible" value="<?php echo $id_tipo; ?>" disabled/>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
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
          <button type="submit" style="background-color: #28a745" class="btn btn-block btn-sm btn-info" onclick="despachaVale()"><i class="fa fa-gas-pump"></i> Despachar</button>
        </div>

    </div>

    </form>

      <script>
        function mostrar_motivo(){
          if (! $('#chk_estado').is(':checked') ){
            $('#row_estado').append(`<label id="label_motivo" for="motivo">Motivo</label>
                <div class="input-group has-personalizado" id="div_motivo" style="margin: 10px 0px 10px 0px; ">
                <input type="text" class="form-control" id="motivo" name="motivo" required/>
                </div>`);
          }else{
                $('#div_motivo').remove();
                $('#label_motivo').remove();
                $('#motivo-error').remove();
          }
        }
      </script>
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
