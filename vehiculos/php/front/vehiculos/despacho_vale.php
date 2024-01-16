<?php include_once '../../../../inc/functions.php'; ?>
    <?php 
        include_once '../../back/functions.php';
        $nro_vale = $_GET["nro_vale"];
        $vale = new vehiculos();
        $data = $vale::get_valeDespacho($nro_vale);
        // $estado = ($data["estado"] == 1)? "checked": "";

    ?>
    <div class="modal-header">
        <h3 class="modal-title">Despacho de Combustible</h5>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>
    <div class="modal-body" id="app_despacho">
        <form class="validation_despacho" name="miForm">

            <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                      <div class="">
                          <label for="nro_vale">Numero de Vale</label>
                          <div class="input-group  has-personalizado" >
                             <input type="text" class="form-control" id="nro_vale" name="nro_vale" value="<?php echo $data["nro_vale"]; ?>" disabled/>
                          </div>
                      </div>
                  </div>
                </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <div class="">
                    <div class="" >
                    <label for="id_galones">Galones autorizados</label>
                    <input type="number" class="form-control form-control-sm" id="id_galones" name="id_galones" disabled v-bind:max="capaTanque.capaT"></input> 
                    </div>
                </div>
              </div>
            </div>

            <div class="col-sm-2">
              <div class="form-group">
                <label for="">Tanque Lleno</label>
                <div class="input-group  has-personalizado">
                    <label class="css-input switch switch-success">   
                            <input name="chk_Tanque" id="chk_Tanque" data-id="" v-on:change="get_capa()" data-name="" type="checkbox" disabled false/><span></span> 
                    </label>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <div class="">
                    <label for="id_combustible">Tipo de combustible</label>
                    <select class="form-control form-control-sm" id="id_combustible" name="id_combustible" disabled >
                        <option v-for="t in tipos" v-bind:value="t.id_tipo" >{{ t.combust_str }}</option>
                    </select>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <div class="">
                    <div class="" >
                    <label for="id_galones">Galones entregados</label>
                    <input type="number" class="form-control form-control-sm" id="id_galones" name="id_galones" required v-bind:max="capaTanque.capaT"></input> 
                    </div>
                </div>
              </div>
            </div>

            <div class="col-sm-4">
              <div class="form-group">
                <div class="">
                    <div class="" >
                    <label for="km_actual">Kilometraje actual</label>
                    <input type="number" class="form-control form-control-sm" id="km_actual" name="km_actual" required v-bind:max="capaTanque.capaT"></input> 
                    </div>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <div class="" >
                    <label for="id_despacha">Bomba de Despacho</label>
                    <select class="form-control form-control-sm" id="id_despacha" name="id_despacha" >
                        <option v-for="c in conductores" v-bind:value="c.id_persona" >{{ c.conduc_str }}</option>
                    </select>
                </div>
              </div>
            </div> 


            <div class="col-sm-6">
              <div class="form-group">
                <div class="" >
                    <label for="id_despacha">Persona despacha</label>
                    <select class="form-control form-control-sm" id="id_despacha" name="id_despacha" >
                        <option v-for="c in conductores" v-bind:value="c.id_persona" >{{ c.conduc_str }}</option>
                    </select>
                </div>
              </div>
            </div> 

            <div class="col-sm-6">
              <div class="form-group">
                <div class="" >
                    <label for="id_recibe">Persona recibe</label>
                    <select class="form-control form-control-sm" id="id_recibe" name="id_recibe" >
                        <option v-for="c in conductores" v-bind:value="c.id_persona" >{{ c.conduc_str }}</option>
                    </select>
                </div>
              </div>
            </div> 


            <button class="btn btn-block btn-sm btn-info" onclick="save_despacho()"><i class="fa fa-save"></i> Guardar</button>
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

