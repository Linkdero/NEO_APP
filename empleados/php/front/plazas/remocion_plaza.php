<?php
include_once '../../../../inc/functions.php';
include_once '../../back/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_plaza=null;
    $id_asignacion=null;

    $id_plaza=$_POST['id_plaza'];
    $id_asignacion=$_POST['id_asignacion'];

    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/emp_plaza_vue.js"></script>
      <script src="empleados/js/validaciones.js"></script>

      <script>


      </script>
    </head>
    <body>
      <input id="id_plaza" value="<?php echo $id_plaza?>" hidden></input>
      <input id="id_asignacion" value="<?php echo $id_asignacion?>" hidden></input>
      <div class="row" id="em_plaza_app">
        <div class="col-sm-12" v-if="empleado_plaza.emp_estado==891">
          <form class="js-validation-tramite-solvencia form-material">
            <div class="row">
              <!--inicio-->
              <div class="col-sm-4">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_nro_acuerdo">No. Acuerdo*</label>
                      <span class="form-icon-wrapper">
    										<span class="form-icon form-icon--left">
    											<i class="fa fa-pen form-icon__item"></i>
    										</span>
    										<span class="form-icon form-icon--right" >
    											<!--{{ new Date().getFullYear() }}-->
    										</span>
                          <input class="form-control form-control-sm form-icon-input-left" id="id_nro_acuerdo" required required autocomplete="off">
                        </input>

                    </div>
                  </div>
                </div>
              </div>
              <!-- fin-->
              <!--inicio-->
              <div class="col-sm-4">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_fecha_acuerdo">Fecha del Acuerdo*</label>
                      <span class="form-icon-wrapper">

                      <input class=" form-control form-control-sm" type="date" id="id_fecha_acuerdo" name="id_fecha_acuerdo" required autocomplete="off">
                    </input>

                    </div>
                  </div>
                </div>
              </div>
              <!-- fin -->
              <!--inicio-->
              <div class="col-sm-4">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_fecha_remocion">Fecha Reseción*</label>
                      <span class="form-icon-wrapper">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-calendar-check form-icon__item"></i>
                        </span>

                      <input class=" form-control form-control-sm form-icon-input-left" type="date" id="id_fecha_remocion" name="id_fecha_remocion" required autocomplete="off">
                    </input>

                    </div>
                  </div>
                </div>
              </div>
              <!-- fin -->


              <!-- inicio -->
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_detalle_remocion">Detalle*</label>
                      <span class="form-icon-wrapper">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-pen form-icon__item"></i>
                        </span>
                          <textarea class="form-control form-control-sm form-icon-input-left" id="id_detalle_remocion" name="detalle_remocion"  required rows="5" required required autocomplete="off"></textarea>

                    </div>
                  </div>
                </div>
              </div>
              <!-- fin -->

              <div class="col-sm-12 text-right">
                <button class="btn btn-sm btn-info text-right" v-if="empleado_plaza.estado==891" onclick="tramiteDeSolvencia()"><i class="fa fa-check"></i> Trámite de solvencia</button>
                <button class="btn btn-sm btn-danger" onclick="cargar_puestos_url('plazas_historial_empleado',2)"><i class="fa fa-times"></i> Cancelar</button>
              </div>

            </div>
          </form>

        </div>




					<div class="col-sm-12" v-if="empleado_plaza.emp_estado==5610">
            <form class="js-validation-crear-baja form-material">
              <br>
              <div class="row">

                <div class="col-sm-12">
                  <div class="alert alert-soft-danger fade show" role="alert">
  								<i class="fa fa-times-circle alert-icon mr-3"></i>
  								<span>Dar de baja al empleado</span>

  							</div>

                </div>





                <!-- fin -->
                <!-- inicio -->
                <div class="col-sm-6">

                  <div class="form-group">
                    <div class="form-material">
                      <label for="id_fecha_remocion">Tipo de baja*</label>
                      <span class="form-icon-wrapper">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-list form-icon__item"></i>
                        </span>
                      <select id="id_tipo_baja" name="combo_1" class="form-control form-control-sm form-icon-input-left" required>
                        <option v-for="i in items" v-bind:value="i.id_item" >{{ i.item_string }}</option>
                      </select>
                    </div>

                  </div>

                </div>
                <!-- fin -->
                <!--inicio-->
                <div class="col-sm-6">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_fecha_remocion">Fecha*</label>
                        <span class="form-icon-wrapper">
                          <span class="form-icon form-icon--left">
                            <i class="fa fa-calendar-check form-icon__item"></i>
                          </span>

                        <input class=" form-control form-control-sm form-icon-input-left" type="date" id="id_fecha_baja" name="id_fecha_baja" required autocomplete="off">
                      </input>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->


                <!-- inicio -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_detalle_remocion">Detalle*</label>
                        <span class="form-icon-wrapper">
                          <span class="form-icon form-icon--left">
                            <i class="fa fa-pen form-icon__item"></i>
                          </span>
                            <textarea class="form-control form-control-sm form-icon-input-left" id="id_detalle_baja" name="id_detalle_baja"  required rows="5" required required autocomplete="off"></textarea>

                      </div>
                    </div>
                  </div>
                </div>
                <!-- fin -->

                <div class="col-sm-12 text-right">
                  <button class="btn btn-sm btn-info text-right" v-if="empleado_plaza.emp_estado==5610" onclick="crearBaja()"><i class="fa fa-check"></i> Actualizar</button>
                  <button class="btn btn-sm btn-danger" onclick="cargar_puestos_url('plazas_historial_empleado',2)"><i class="fa fa-times"></i> Cancelar</button>
                </div>
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
