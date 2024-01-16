<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1121)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $bodega;
    $id_viatico = $_POST['id_viatico'];
    $id_persona = $_POST['id_persona'];
    $id_renglon=$_POST['id_renglon'];

?>
<script src="viaticos/js/cargar_vue.js"></script>
<script src="viaticos/js/funciones.js"></script>
<script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
<script src="assets/js/plugins/vue/vue.js"></script>
<span class="btn-regresar" style="float:left;" onclick="get_viatico_detalle_encabezado('empleados_por_viatico')"></span> Regresar

    <input id="id_persona" hidden value="<?php echo $id_persona;?>"></input>
    <br><br>
    <h3>Empleados seleccionados</h3>
    <div class="row" id="myapp" style="margin-top:5px;">
      <!--<input :value="empleado.empleado"></input>-->
      <div class="col-sm-12">
        <ul>
          <li v-for="e in empleados_pro">
            {{ e.empleado }}
          </li>
        </ul>
        <hr>
      </div>
      <input id="id_renglon" hidden value="<?php echo $id_renglon?>"></input>

      <div class="col-sm-12" v-if="empleado.fecha_salida_lugar!='01-01-1900'">
        <div v-if="viaticos.status==939">
          <br>
          <h3><strong>Viático Liquidación</strong> - Porcentaje real: <strong class="text-success">{{empleado.porcentaje_real}}</strong></h3>
          <hr>
          <form class="js-validation-liquidacion form-horizontal push-10-t push-10" action="" method="" id="s_form">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_reintegro_hospedaje">Reintegro Hospedaje*</label>
                      <div class=" input-group  has-personalizado" >
                        <input type="text" class=" form-control input-sm" id="id_reintegro_hospedaje" name="id_reintegro_hospedaje" placeholder="@reintegro hospedaje" :value="empleado.cant_hospedaje" required autocomplete="off"  autocomplete="off"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_reintegro_alimentacion">Reintegro alimentación*</label>
                      <div class=" input-group  has-personalizado" >
                        <input type="text" class=" form-control input-sm" id="id_reintegro_alimentacion" name="id_reintegro_alimentacion" placeholder="@reintegro alimentación" :value="empleado.cant_alimentacion" required  autocomplete="off"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_otros_gastos">Otros Gastos*</label>
                      <div class=" input-group  has-personalizado" >
                        <input type="text" class=" form-control input-sm" id="id_otros_gastos" name="id_otros_gastos" placeholder="@reintegro alimentación" :value="empleado.cant_otros_gastos" required  autocomplete="off"/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-12 text-right">
                <button id="" class="btn btn-info btn-sm" onclick="generar_liquidacion()"><i class="fa fa-check"></i> Calcular liquidación </button>

              </div>
            </div>
          </form>
          <div>


          </div>
        </div>

      </div>
      <div v-else-if="empleado.porcentaje_real>0">
        show liquidación
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
