<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(8017)){

    include_once '../../back/functions.php';
    $id_viatico=null;
    $tipo_filtro=null;

?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script src="viaticos/js/cargar.js"></script>
  <link href="assets/js/plugins/x-editable/bootstrap-editable.css" rel="stylesheet"/>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.min.js"></script>
  <script src="assets/js/plugins/x-editable/bootstrap-editable.js"></script>
  <script src="documentos/js/documento_vue.js"></script>
  <script src="documentos/js/validaciones.js"></script>
  <script src="documentos/js/funciones.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>

  <script>
  </script>
</head>
<body>
  <div class="modal-header">
    <h5>Documento Nuevo</h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>
  </div>
  <div class="modal-body">
    <div id="app_documento">
      <form class="jsValidacionDocumentoNuevo">
        <div class="row">
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_nombre">Nombre*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control form-control_ input-sm" id="id_nombre" name="id_nombre" placeholder="@Nombre" required autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio-->
          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_fecha_salida_c">Título*</label>
                  <div class=" input-group  has-personalizado" >
                    <textarea rows="2" oninput="this.value = this.value.toUpperCase()" class="js-datepicker form-control form-control_ input-sm" id="id_titulo_documento" name="id_titulo_documento" placeholder="@Título" required autocomplete="off"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!-- inicio -->

          <div class="col-sm-4 text-right">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for=""></label>
                  <div class="input-group  has-personalizado" >
                    <label class="css-input input-sm switch switch-info" style="float:right"><input id="rd_emitido" name="tipo_correspondencia" v-on:change="formulario_requerido()" v-model="emitido" v-bind:disabled="validar1" type="radio"/><span></span> <span id="lbl_rdemitido">Emitido {{validar1}} -- {{emitido}}</span></label>
                  </div>
                  <label for="id_confirma"></label>
                  <div class="input-group  has-personalizado" >
                    <label class="css-input input-sm switch switch-info" style="float:right"><input id="rd_recibido" name="tipo_correspondencia" v-on:change="formulario_requerido()" v-model="recibido" v-bind:disabled="validar2" type="radio"/><span></span> <span id="lbl_rdrecibido">Recibido {{validar2}} -- {{recibido}}</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin-->
          <!-- inicio -->
          <div class="col-sm-4 text-right">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="id_confirma">Correlativo obligatorio</label>
                  <div class="input-group  has-personalizado" >
                      <label class="css-input input-sm switch switch-success" style="float:right"><input id="chk_doc_externo" v-on:change="formulario_obligatorio()" v-bind:disabled="chk_obli" v-model="chequeado" type="checkbox"/><span></span> <span v-if="chequeado">SI</span><span v-else>NO</span></label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin-->

          <!-- inicio -->
          <div class="col-sm-4 text-right">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="id_confirma">Necesita Respuesta</label>
                  <div class="input-group  has-personalizado" >
                      <label class="css-input input-sm switch switch-success" style="float:right"><input id="chk_doc_respuesta" v-on:change="docto_respuesta()" v-model="chequeado2" v-bind:disabled="validar2" type="checkbox"/><span></span> <span v-if="chequeado2">SI</span><span v-else>NO</span></label>
                  </div>
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
                  <label for="id_fecha_salida_c">Fecha*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="date" class="js-datepicker form-control form-control_ input-sm" id="id_fecha_documento" name="id_fecha_documento" placeholder="@Fecha" required autocomplete="off" />
                  </div>
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
                  <label for="id_categoria">Tipo de documento*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="js-select2 form-control form-control-sm" id="id_categoria" name="combo1" v-on:change="mostrar_correspondencia($event)">
                      <option v-for="categoria in items" v-bind:value="categoria.id_item">{{ categoria.item_string }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio correlativo obligatorio-->
          <div class="col-sm-4">
            <div class="form-group" id="fg_correlativo_respuesta" v-show="obligatorio_interno==false">
              <div class="">
                <div class="">
                  <label for="id_correlativo_respuesta">Correlativo*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control form-control_ input-sm" :class="[testSelect==true ? 'bg-soft-danger' : 'bg-soft-success']" id="id_correlativo_respuesta" :required="testSelect" name="id_correlativo_respuesta" placeholder="@Correlativo respuesta" autocomplete="off" />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group" id="fg_correlativo_respuesta_"  v-show="obligatorio_interno==true">
              <div class="">
                <div class="">
                  <label for="id_correlativo_respuesta">Correlativo*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-sm input-sm" id="id_correlativo_respuesta"></select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin -->
          <!--inicio correspondencia-->
          <div class="col-sm-12">
            <div v-show="display" class="row">
              <!--inicio-->
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_hora_salida_c">Destinatarios*</label>
                      <div class=" input-group  has-personalizado" >
                        <select class="js-select2 form-control form-control-sm" id="id_destinatarios" name="a_destinatarios[]" :required="display" multiple>
                          <option v-for="d in destinatarios" v-bind:value="d.dest_id">{{ d.dest_string }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- fin -->
              <!--inicio-->
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="">
                    <div class="">
                      <label for="id_hora_salida_c">Destinatarios CC</label>
                      <div class=" input-group  has-personalizado" >
                        <select class="js-select2 form-control form-control-sm" id="id_destinatarios_cc" name="a_destinatarios_cc[]" multiple>
                          <option v-for="d in destinatarios" v-bind:value="d.dest_id">{{ d.dest_string }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- fin -->

            </div>
            <!-- fin correspondencia-->
          </div>

          <!--inicio-->
          <div class="col-sm-12 text-right">
            <button class="btn-sm btn btn-info" onclick="crearDocumento()"><i class="fa fa-check"></i> Generar</button>
          </div>
        </div>
      </form>
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
