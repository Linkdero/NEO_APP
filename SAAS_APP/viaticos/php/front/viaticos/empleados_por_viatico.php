<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $id_empleado=null;
    $bodega;
    //if ( !empty($_GET['id_viatico'])) {
      $id_viatico = $_POST['id_viatico'];

$clase= new viaticos();
$horas = $clase->get_items(37);
?>

    <!--{{allEmpleados()}}-->
    <script src="viaticos/js/funciones.js"></script>
    <script src="viaticos/js/source_modal.js"></script>
    <script src="viaticos/js/cargar_vue.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.checkboxes.css">
    <script src='assets/js/plugins/datatables/new/dataTables.checkboxes.min.js'></script>
    <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
    <script src="assets/js/plugins/vue/vue.js"></script>


    <!--<h3>Personas autorizadas:   <strong><span class="text-right" id="personas">{{viaticos.personas}}</span></strong></h3>-->
    <div class="row">
      <div class="col-sm-12 shadow-none">




            <table id="tb_empleados_por_nombramiento" class="table table-sm table-bordered " width="100%">
              <thead>
                <th class="text-center">...</th>
                <th class="text-center">Cod.</th>
                <th class="text-center">Empleado</th>

                <th class="text-center">VA</th>
                <th class="text-center">VC</th>
                <th class="text-center">VL</th>
                <th class="text-center">% P</th>
                <th class="text-center">% R</th>
                <th class="text-center">MONTO P</th>
                <th class="text-center">MONTO R</th>
                <th class="text-center">Re</th>
                <th class="text-center">Co</th>
                <th class="text-center">Estado</th>
                <!--<th class="text-center">Cheque</th>-->
                <th class="text-center">Acción</th>
                <th class="text-center">...</th>
              </thead>

            </table>
          </div>
          <div class="col-sm-12" id="myapp">
            <div v-if="viaticos.personas>0">
              <div v-if="viaticos.status==938 || viaticos.status==7959">
                <button id="" class="btn btn-info btn-sm" onclick="get_viatico_detalle_encabezado('constancia_global')"><i class="fa fa-check"></i> Actualizar horas </button>
                <button id="" v-if="viaticos.personas_c==0" class="btn btn-info btn-sm" onclick="au_solicitud(939)"><i class="fa fa-check"></i> Constancia </button>
              </div>
            </div>
            <div v-if="viaticos.status==939">
              <button id="" class="btn btn-info btn-sm" onclick="get_viatico_detalle_encabezado('liquidacion_global')"><i class="fa fa-check"></i> Actualizar montos </button>
              <button id="" v-if="viaticos.personas_l==0" class="btn btn-info btn-sm" onclick="au_solicitud(940)"><i class="fa fa-check"></i> Liquidar </button>
            </div>


              <div v-if="viaticos.status==938">
                <hr>
                <script>

                setTimeout('toggleB()',200);

                function obtener_municipios(){
                  get_municipios();
                }

                function obtener_aldeas(){
                  get_aldeas();
                }

                </script>


                <span id="id_country" hidden >{{viaticos.id_pais}}</span>
                <span id="id_confirma" hidden>{{viaticos.confirma_lugar}}</span>
                <div class="form-group">
                  <div class="">
                    <div class="row">
                      <label for="id_confirma">Confirma lugar*</label>
                      <div class=" input-group  has-personalizado" >

                          <label class="css-input input-sm switch switch-danger"><input id="chk_confirma" onchange="mostar_formulario_confirma()" type="checkbox"/><span></span> <span id="lbl_chk">SI</span></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="formulario_confirma" style="display:none" class="slide_up_anim">
                <form class="js-validation-confirma-lugar">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="departamento">Departamento</label>
                            <div class=" input-group  has-personalizado" >
                                <select class="form-control form-control-sm" id="departamento" onchange="get_municipios()" required name="combo_dep">
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="municipio">Municipio</label>
                            <div class=" input-group  has-personalizado" >
                                <select class="form-control form-control-sm" id="municipio" onchange="obtener_aldeas()" required name="combo_mun">
                                </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <label for="aldea">Aldea</label>
                            <div class=" input-group  has-personalizado" >
                                <select class="form-control form-control-sm" id="aldea">
                                </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <button id="" class="btn btn-info btn-sm btn-block" onclick="confirmar_lugar()"><i class="fa fa-check"></i> Guardar nuevo lugar </button>
                </form>

              </div>



          </div>

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
