<?php include_once '../../../../inc/functions.php';
        include_once '../../back/functions.php';
        sec_session_start();
        if (function_exists('verificar_session') && verificar_session()){
          if(usuarioPrivilegiado_acceso()->accesoModulo(1162)){

            $nro_vale = $_GET["nro_vale"];
            $tipo_ = $_GET['tipo'];
    
            /*$tipo = new vehiculos();
            $datat = $tipo::get_TipoDespacho($nro_vale);
            //$vale = new vehiculos();
            $xTipo = "Placa";
            if ($datat["id_tipo_uso"] == 1144) {
                $data = $tipo::get_valeDespacho($nro_vale);
            }elseif ($datat["id_tipo_uso"] == 1147) {
                $data = $tipo::get_valeDespacho_Ext($nro_vale);
            }else {
                $xTipo = "Caracteristicas";
                $data = $tipo::get_valeDespacho_Gen($nro_vale);
            }*/

?>
<script src="assets/js/pages/components.js"></script>
    <script src="vehiculos/js/validaciones_vue.js"></script>
    <script src="vehiculos/js/vales_vue_des.js"></script>
<script src="vehiculos/js/validaciones.js"></script>
    <div class="modal-header">
        <h5 class="modal-title">Anulación de Vale de Combustible</h5>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>

    <div class="modal-body" id="app_despacha_vale">
    <input value="<?php echo $nro_vale?>" id="id_nro_vale" hidden></input>
    <detalle-vale :nro_vale="nroVale" v-on:enviar_vale="recibirVale"></detalle-vale>
    <hr>
    <formulario-vales type="2" :id_vale="nroVale" :data_vale="arreglo" ></formulario-vales>

<!--     **********  forma anterior *************
      <form class="validation_anula_vale">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <div class="">
                <label for="nro_vale">Número de Vale</label>
                <div class="input-group  has-personalizado" >
                  <input type="text" class="form-control" id="nro_vale" name="nro_vale" value="<?php echo $data["nro_vale"]; ?>" disabled/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <label for="id_destino">Destino de Combustible</label>
                <div class="input-group  has-personalizado" >
                  <input type="text" class="form-control" id="id_uso" name="id_uso" value="<?php echo $data["uso"]; ?>" disabled/>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6" id="div_vehiculo">
            <div class="form-group">
              <div class="">
                <label for="lbl_vehiculo" >
                <?php echo $xTipo ?></label>
                <div class="input-group  has-personalizado" >
                  <input type="text" class="form-control" id="nro_placa" name="nro_placa" value="<?php echo $data["nro_placa"]; ?>" disabled/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <div class="">
                <label for="id_cantidad">Galones</label>
                <div class="input-group  has-personalizado" >
                  <input type="text" class="form-control" id="cant_autor" name="cant_autor" value="<?php echo $data["cant_autor"]; ?>" disabled/>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-2">
            <div class="form-group">
              <label for="">Lleno</label>
              <div class="input-group  has-personalizado">
                <label class="css-input switch switch-success">   
                  <input name="chk_Tanque" id="chk_Tanque" type="checkbox" <?php if($data['tlleno']==1) {echo 'checked';}?> disabled/><span></span> 
                </label>
              </div>
            </div>
          </div>

          <div class="col-sm-6" id="div_tipo_comb">
            <div class="form-group">
              <div class="">
                <label for="lbl_Tipo_Comb" >Tipo de Combustible</label>
                <div class="input-group  has-personalizado" >
                  <input type="text" class="form-control" id="tipo_comb" name="tipo_comb" value="<?php echo $data["tipo_comb"]; ?>" disabled/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <label for="recibe">Conductor</label>
              <div class="input-group  has-personalizado" >
                <input type="text" class="form-control" id="recibe" name="recibe" value="<?php echo $data["recibe"]; ?>" disabled/>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" style="background-color: #f66" class="btn btn-block btn-sm btn-info" onclick="anulaVale()"><i class="fa fa-times"></i> Anular</button>
      </form> -->

      
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