<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_partida=null;
    $id_plaza = null;
    $opcion = null;

    if ( !empty($_GET['partida'])) {
      $id_partida = $_REQUEST['partida'];
    }

    if ( !empty($_GET['id_plaza'])) {
      $id_plaza = $_REQUEST['id_plaza'];
    }

    if ( !empty($_GET['opcion'])) {
      $opcion = $_REQUEST['opcion'];
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

      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src="assets/js/pages/components.js?v=<?php echo time();?>"></script>
      <script src="empleados/js/appComponentes.1.3.js"></script>
      <script src="empleados/js/appPlazaNueva.js"></script>

    </head>
    <body>
      <div class="modal-header">
        <h5 class="modal-title">Nueva Plaza </h5>
        <ul class="list-inline ml-auto mb-0">






          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <input id="id_plaza" name="id_plaza" value="<?php echo $id_plaza ?>" hidden></input>
      <input id="opcion" name="opcion" value="<?php echo $opcion ?>" hidden></input>
      <div id="plaza_nueva_app" class="modal-body">
        <form class="jsValidacionPlazaNueva" id="formValidacionPlazaNueva">
          <div class="row">
            <!-- inicio -->

            <div class="col-sm-6">
              <campo tipo="text" codigo="id_cod_plaza" label="Codigo de la Plaza:*" requerido="true" row="col-sm-12" :valor="plazaDetalle.cod_plaza"></campo>
              <campo tipo="textarea" codigo="id_partida" label="Partida Presupuestaria:*" requerido="true" row="col-sm-12" :valor="plazaDetalle.partida_presupuestaria"></campo>

              <!--<combo :arreglo="arrayPuestos" codigo="id_puesto_n" label="Puesto de la plaza:*" row="col-sm-12" tipo="2" requerido="true" :valor="idPuestoN"></combo>-->

              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label>Puesto Nominal:*</label>
                      <div class=" input-group  has-personalizado" >
                        <select id="id_puesto_n" name="id_puesto_n" class='form-control form-control-sm' v-model='idPuestoN' required style="width:100%">
                          <option v-for='data in arrayPuestos' :value='data.id_item'>{{ data.item_string }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- fin-->
              <table class="table table-sm table-bordered">
                <tbody>
                  <tr v-for="(s, index) in arraySueldos" v-if="opcion == 1">
                    <td v-if="index > 0">{{ s.item_string }}</td>
                    <td v-if="index > 0">
                      <div class="form-group" :class="'txtMontoS'+index">
                        <div class="">
                          <div class="">
                            <div class="input-group  has-personalizado" >
                              <input :id="'txtMontoS'+index" :name="'txtMontoS'+index" class="form-control form-control-sm" v-model="s.monto_n" type="number" :required="s.bln_confirma == true" v-bind:disabled="(s.bln_confirma == false)"></input>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td v-if="index > 0">
                      <input class="tgl tgl-flip text-center" :id="'chk'+index" :name="'chk'+index" type="checkbox" v-model="s.bln_confirma" :value="1" @change="clearClass(index,s.bln_confirma)"/>
                      <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="'chk'+index" ></label>
                    </td>
                  </tr>
                  <tr v-for="(s, index) in arraySueldos" v-if="opcion == 2">
                    <td>{{ s.item_string }}</td>
                    <td>
                      <div class="form-group" :class="'txtMontoS'+index">
                        <div class="">
                          <div class="">
                            <div class="input-group  has-personalizado" >
                              <input :id="'txtMontoS'+index" :name="'txtMontoS'+index" class="form-control form-control-sm" v-model="s.monto_n" type="number" :required="s.bln_confirma == true" v-bind:disabled="(s.bln_confirma == false)"></input>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <input class="tgl tgl-flip text-center" :id="'chk'+index" :name="'chk'+index" type="checkbox" v-model="s.bln_confirma" :value="1" @change="clearClass(index,s.bln_confirma)"/>
                      <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="'chk'+index" ></label>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-sm-6">
              Ubicación Nominal
              <formulario-nominal row1='col-sm-6' row2='col-sm-6' :arreglo="plazaDetalle" :tipo_accion="opcion"></formulario-nominal>
              Ubicación Funcional
              <formulario-funcional row1='col-sm-6' row2='col-sm-6' :arreglo="plazaDetalle" :tipo_accion="opcion"></formulario-funcional>
            </div>
            <div class="col-sm-12 text-right" v-if="opcion == 1">
              <button class="btn btn-sm btn-info" @click="savePlaza(1)"><i class="fa fa-check-circle"></i> Crear plaza</button>
            </div>
            <div class="col-sm-12 text-right" v-if="opcion == 2">
              <button class="btn btn-sm btn-info" @click="savePlaza(2)"><i class="fa fa-check-circle"></i> Actualizar plaza</button>
            </div>

          </div>
        </form>
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
