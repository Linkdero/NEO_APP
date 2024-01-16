
Vue.component('detalle-plazas', {
  props:["id_persona","privilegio","datos_persona","datos_plaza"],
  template:`
  <div class="">
    <div class="" v-if="opcion == 1 || opcion==6">
      <!--permiso de plazas -->
      <div v-if="datos_persona.id_empleado!=0">
        <span  v-if="privilegio.acciones == true" class="btn btn-sm btn-soft-info" @click="getOpcion(5)"><i class="fa fa-plus"></i> Asignar plaza</span>
      </div>
      <div v-else>
        No tiene un empleado asignado
      </div>

      <div  v-if="plazas.length > 0" >
        <div class="el-wrapper" v-for="p in plazas">
          <div class="box-up">
            <div class="img-info">
              <div class="info-inner">
                <div class="row">
                  <div class="col-sm-2 text-left">
                    <dato-persona icono="far fa-calendar-check" texto="Codigo de la Plaza" :dato="p.cod_plaza" tipo="0"></dato-persona>
                  </div>
                  <div class="col-sm-6 text-left">
                    <dato-persona icono="far fa-calendar-check" texto="Partida Presupuestaria:" :dato="p.partida" tipo="0"></dato-persona>
                  </div>

                  <div class="col-sm-12 text-right" style="position:absolute;margin-top:15px">
                    <h2 v-if="p.status==891" class="text-success">{{p.estado}} <i class="fa fa-check-circle"></i></h2>
                    <h2 v-else-if="p.status==1210" class="text-info">{{p.estado}} <i class="fa fa-arrow-up"></i></h2>
                    <h2 v-else class="text-danger">{{p.estado}} <i class="fa fa-times-circle"></i></h2>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box-down">

            <!--<div class="h-bg"  v-if="privilegio.acciones == true">
              <div class="h-bg-inner"></div>
            </div>-->
            <!--<a class="cart" href="#"  v-if="privilegio.acciones == true">


              <span class="add-to-cart">
                  permiso de asignación de plaza
                  <span class="txt" v-if="p.status==891 || p.status==5610" @click="getOpcionRemocion(p.id_plaza,p.id_asignacion,6)"><i class="fa fa-user-times"></i> Remocion</span>
                  <span class="txt" @click="getEmpleadoPlaza(p.id_plaza,p.id_asignacion,9)"><i class="fa fa-file-powerpoint"></i> Ver plaza</span>

              </span>
            </a>-->
            <div class="row" style="margin-top:-5px">
              <div class="col-sm-1 text-left">
                <dato-persona icono="far fa-calendar-check" texto="Inicio" :dato="p.inicio" tipo="0"></dato-persona>
              </div>
              <div class="col-sm-1 text-left">
                <dato-persona icono="far fa-calendar-times" texto="Fin" :dato="p.final" tipo="0"></dato-persona>
              </div>
              <div class="col-sm-1 text-left">
                <dato-persona icono="far fa-calendar-check" texto="Sueldo" :dato="p.sueldo" tipo="0"></dato-persona>
              </div>
              <div class="col-sm-4 text-left">
                <dato-persona icono="far fa-calendar-check" texto="Puesto:" :dato="p.puesto" tipo="0"></dato-persona>
              </div>
              <div class="col-sm-5 text-right" style="margin-top:5px">
              <span class="btn btn-sm btn-danger" v-if="p.status==891 || p.status==5610" @click="getOpcionRemocion(p.id_plaza,p.id_asignacion,6)"><i class="fa fa-user-times"></i> Remocion</span>
              <span class="btn btn-sm btn-info" @click="getEmpleadoPlaza(p.id_plaza,p.id_asignacion,9)"><i class="fa fa-file-powerpoint"></i> Ver plaza</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <h3>El empleado no tiene plazas asignadas</h3>
      </div>
    </div>
    <form-asignacion-plaza v-if="opcion==5" :id_persona="id_persona" :id_gafete="id_persona" :id_empleado="datos_persona.id_empleado" :id_asignacion="0" :tipo_accion="1" ></form-asignacion-plaza>
    <div v-if="opcion==6" class="">
      <div id="myModal" class="modal-vue">

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-file-signature">
                </i><span v-if="datos_plaza.emp_estado==5610" class="text-white"> Dar de baja al empleado </span><span class="text-white" v-else> Trámite de Solvencia</span>
              </h4>
              <span class="close-icon"  @click="getOpcion(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <!--inicio -->
              <input id="id_plaza_re" :value="id_plaza" hidden></input>
              <input id="id_asignacion_re" :value="id_asignacion" hidden></input>
              <div class="row">
                <div class="col-sm-12" v-if="datos_plaza.emp_estado==891">
                  <form class="js-validation-tramite-solvencia form-material">
                    <div class="row">
                      <!--inicio-->
                      <campo row="col-sm-4" label="No. del Acuerdo*" codigo="id_nro_acuerdo_re" tipo="text" requerido="true"></campo>
                      <campo row="col-sm-4" label="Fecha del Acuerdo*" codigo="id_fecha_acuerdo_re" tipo="date" requerido="true"></campo>
                      <campo row="col-sm-4" label="Fecha Reseción*" codigo="id_fecha_remocion_re" tipo="date" requerido="true"></campo>
                      <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_remocion_re" tipo="textarea" requerido="true"></campo>
                      <div class="col-sm-12 text-right">
                        <button class="btn btn-sm btn-info text-right" v-if="datos_plaza.estado==891" @click="tramiteDeSolvencia()"><i class="fa fa-check"></i> Trámite de solvencia</button>
                        <button class="btn btn-sm btn-danger" @click="getOpcion(1)"><i class="fa fa-times"></i> Cancelar</button>
                      </div>

                    </div>
                  </form>

                </div>
                <div class="col-sm-12" v-if="datos_plaza.emp_estado==5610">
                  <form class="js-validation-crear-baja form-material">
                    <div class="row">
                      <!-- inicio -->
                      <combo row="col-sm-6" label="Tipo de baja*" codigo="id_tipo_baja_bj" :arreglo="items" tipo="2" requerido="true"></combo>
                      <campo row="col-sm-6" label="Fecha*" codigo="id_fecha_baja_bj" tipo="date" requerido="true"></campo>
                      <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_baja_bj" tipo="textarea" requerido="true"></campo>
                      <div class="col-sm-12 text-right">
                        <button class="btn btn-sm btn-info text-right" v-if="datos_plaza.emp_estado==5610" @click="crearBaja()"><i class="fa fa-check"></i> Actualizar</button>
                        <button class="btn btn-sm btn-danger" @click="getOpcion(1)"><i class="fa fa-times"></i> Cancelar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <!-- fin -->
            </div>
          </div>
        </div>
      </div>
      <!-- fin remocion-->
    </div>
    <!-- inicio detalle plaza -->
    <div v-if="opcion==9">
      <h3>Detalle de la plaza</h3>
      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <div class="row">
            <div class="col-sm-6">
                <div class="">
                  <div class="">
                    <h4>{{emp_plaza.cargo}}</h4>
                    <small class="text-muted">Codigo: {{emp_plaza.cod_plaza}}</small>
                  </div>
                  <br><br>
                  <div class="">
                    <h2>{{emp_plaza.sueldo}}</h2>
                    <small class="text-muted">Sueldo + Bonos</small>
                    <small class="text-success">{{emp_plaza.estado}}</small>
                  </div>
                </div>

            </div>
            <div class="col-sm-6">
              <ul class="timeline">
                <h4>Dependencia Nominal</h4>
                <li><a target="_blank">{{emp_plaza.secretaria_n}}</a></li>
                <li><a target="_blank">{{emp_plaza.subsecretaria_n}}</a></li>
                <li><a target="_blank">{{emp_plaza.direccion_n}}</a></li>
                <li><a target="_blank">{{emp_plaza.subdireccion_n}}</a></li>
                <li><a target="_blank">{{emp_plaza.departamento_n}}</a></li>
                <li><a target="_blank">{{emp_plaza.seccion_n}}</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <form-asignacion-plaza v-if="opcion==9" :id_persona="id_persona" :id_gafete="id_persona" :id_empleado="datos_persona.id_empleado" :id_asignacion="id_asignacion_plaza" :tipo_accion="2"></form-asignacion-plaza>
    </div>
    <!-- fin detalle plaza -->
  </div>
  `,
  data(){
    return {
      opcion:1,
      id_plaza:"",
      id_asignacion:"",
      emp_plaza:"",
      items:[],
      plazas:[]
    }
  },
  mounted: function() {
  },
  methods: {
    getOpcion: function(opc){
      this.opcion = opc;
      if(opc != 6){
        this.id_plaza="";
        this.id_asignacion="";
      }
    },
    getOpcionRemocion: function(plaza, asignacion, opc){
      this.opcion=opc;
      this.id_plaza=plaza;
      this.id_asignacion=asignacion;
    },
    getPlazas: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/listados/get_empleado_historial_plazas.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.plazas = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    getStatusBaja: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:29,
          tipo:2
        }
      })
      .then(function (response) {
        this.items = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getEmpleadoPlaza: function(id_plaza,id_asignacion,opc){
      this.id_asignacion_plaza=id_asignacion;
      this.opcion=opc;
      axios.get('empleados/php/back/plazas/get_plaza_by_id.php', {
        params: {
          id_plaza: id_plaza
        }
      }).then(function (response) {
        this.emp_plaza = response.data;
        setTimeout(() => {
          $("#id_puesto_f").select2({
          });

        });
        //alert(response.data.id_plaza)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    tramiteDeSolvencia: function(){
      viewModelDatosEmp.tramiteDeSolvencia();
    },
    crearBaja: function(){
      viewModelDatosEmp.crearBaja();
    }
  },
  created: function() {
    this.getPlazas();
    this.getStatusBaja();
    eventBus.$on('showDetallePlazas', (opc) => {
      if(opc == 5){
        this.getPlazas();
        this.getOpcion(1);
      }else{
        this.getOpcion(opc);
      }

    });
  },
});

// detalle Contratos
Vue.component('detalle-contratos', {
  props:["id_persona","privilegio","datos_persona","datos_plaza"],
  template:`
  <div class="">
    <div class="row" v-if="opcion == 1 || opcion == 10 || opcion == 20">
      <div class="col-sm-12">
        <!--permiso de Contratos -->
          <div v-if="datos_persona.id_empleado!=0">
            <div v-if="privilegio.nominas == true" class="btn-group">
              <div v-if="datos_plaza.tipo==7">
                <span v-if="datos_plaza.renglon=='011'" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Asignar contrato</span>
                <br><br>
              </div>
              <div v-else-if="datos_plaza.tipo==1075 && viewPDF == false">
                <span v-if="datos_plaza.emp_estado==908 && datos_plaza.renglon=='031' || datos_plaza.emp_estado==908 && datos_plaza.renglon=='029'" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Renovar contrato</span>
                <span v-else-if="datos_plaza.emp_estado==911 || datos_plaza.emp_estado==909" class="btn btn-sm btn-soft-info" @click="getOpcion(7)"><i class="fa fa-plus"></i> Asignar contrato</span>
                <br><br>
              </div>
            </div>

          </div>
          <div v-else>
            <span>No tiene un empleado asignado</span>
          </div>
        <!--fin del permiso -->
      </div>


      <div class="col-sm-12" v-if="displayedPosts.length > 0 && viewPDF == false" >

        <table id="tb_contratos_por_persona" class="table table-sm table-striped table-bordered" width="100%">
          <thead>
            <!--<th class="text-center">ID</th>-->
            <th class="text-center">Renglón</th>
            <th class="text-center">Contrato</th>
            <th class="text-center">Acuerdo</th>
            <th class="text-center">Inicio</th>
            <th class="text-center">Fin</th>
            <th class="text-center">Finalización</th>
            <th class="text-center">Sueldo</th>
            <th class="text-center">Puesto</th>
            <th class="text-center">Contrato</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Acción</th>
          </thead>
          <tbody>
            <tr v-for="c in displayedPosts">
              <td class="text-center">{{c.renglon}}</td>
              <td class="text-center">{{c.nro_contrato}}</td>
              <td class="text-center">{{c.nro_acuerdo_aprobacion}}</td>
              <td class="text-center">{{c.fecha_acuerdo_aprobacion}}</td>
              <td class="text-center">{{c.fecha_finalizacion}}</td>
              <td class="text-center">{{c.fecha_fin}}</td>
              <td class="text-center">{{c.monto_mensual}}</td>
              <td class="text-center">{{c.puesto}}</td>
              <td class="text-center"><span class="text-info" @click="viewContrato(c.archivo)">{{c.archivo}}</span></td>
              <td class="text-center">
                <span v-if="c.id_status==908" class="badge badge-soft-success">{{c.estado}}</span>
                <span v-else class="badge badge-soft-danger">{{c.estado}}</span>
              </td>
              <td class="text-center">
                <div class="btn-group" v-if="privilegio.nominas == true">
                  <!--permiso de asignación de contrato -->

                    <button class="btn-sm btn btn-soft-info" v-if="c.id_status==908" @click="getOpcionFinContrato(c.reng_num,c.renglon,10)"><i class="fa fa-user-times"></i></button>
                    <button class="btn-sm btn btn-soft-info" @click="getContratoById(c.reng_num,15)"><i class="fa fa-file-powerpoint"></i></button>
                    <button class="btn-sm btn btn-soft-info" v-if="c.id_status==908"  @click="getOpcionFinContrato(c.reng_num,c.renglon,20)"><i class="fa fa-file-upload"></i></button>
                  <!--fin del permiso -->

                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-sm-12" v-else-if="displayedPosts.length > 0 && viewPDF == true">
        <button class="btn btn-info btn-sm" @click="viewContratos()" ><i class="fa fa-arrow-left"></i> Regresar</button>
        <vue-pdf-app style="height: 50vh;" :config="config" :pdf="pdf" theme="dark"></vue-pdf-app>
      </div>
      <div v-else>
        <h3>El empleado no tiene contratos asignados</h3>
      </div>
    </div>
    <!-- fin -->
    <form-asignacion-contrato v-if="opcion==7" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="0" tipo_accion="1"></form-asignacion-contrato>
    <form-asignacion-contrato v-if="opcion==15" :id_persona="id_persona" :id_empleado="datos_persona.id_empleado" :id_reng_num="id_reng_num" tipo_accion="2"></form-asignacion-contrato>
    <!-- inicio finalizacion contrato -->
    <div v-if="opcion==10">
      <div id="myModal" class="modal-vue">
        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-file-signature">
                </i><span class="text-white"> Finalizar Contrato </span>
              </h4>
              <span class="close-icon"  @click="getOpcion(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">

              <form class="js-validation-finalizacion-contrato form-material">
                <div class="row">
                <input id="reng_num" :value="reng_num" hidden></input>
                  <!--inicio-->
                  <input id="id_tipo_renglon" name="id_tipo_renglon" :value="renglon" hidden></input>

                  <campo row="col-sm-4" label="No. Acuerdo*" codigo="id_nro_acuerdo_fc" tipo="text" requerido="true"></campo>
                  <campo row="col-sm-4" label="Fecha del Acuerdo*" codigo="id_fecha_acuerdo_fc" tipo="date" requerido="true"></campo>
                  <campo row="col-sm-4" label="Fecha Reseción*" codigo="id_fecha_finalizacion_fc" tipo="date" requerido="true"></campo>
                  <combo row="col-sm-12" label="Tipo de Finalización*" codigo="id_tipo_fc" :arreglo="tipos_baja_contrato" tipo="3" requerido="true"></combo>
                  <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_remocion_fc" tipo="textarea" requerido="true"></campo>

                  <div class="col-sm-12 text-right">
                    <button class="btn btn-sm btn-info text-right"  @click="finalizarContrato()"><i class="fa fa-check"></i> Finalizar contrato</button>
                    <button class="btn btn-sm btn-danger" @click="getOpcion(1)"><i class="fa fa-times"></i> Cancelar</button>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- fin finalizacion contrato-->
    <!-- inicio subir contrato -->
    <div v-if="opcion == 20">
      <div id="myModal" class="modal-vue" >

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-file">
                </i><span class="text-white"> Subir Contrato</span>
              </h4>
              <span class="close-icon" @click="getOpcion(1)" v-if="cargando == false">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body"  v-if="cargando == false">
              <div style="height: 10px;"></div>
              <div id='outputImage'></div>
              <form class="jsValidacionSubirContrato" action='' method='' enctype='' id="formSubirContrato" >
                <input id="reng_num" name="reng_num" :value="reng_num" hidden></input>
                <input id="id_tipo_renglon" name="id_tipo_renglon" :value="renglon" hidden></input>
                <input type="text" id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                <input type="text" id="sizefile" name="sizefile" :value="sizeFile" hidden ></input>
                <div class="row">
                  <combo row="col-sm-12" label="Tipo de documento*" codigo="id_tipo" :arreglo="arregloTipoDocs" tipo="3" requerido="true"></combo>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="col-xs-12">
                        <div class="">
                          <input class="form-control" type='file' id="id_contrato_pdf" name='id_contrato_pdf' @change="getSizeFile" accept="application/pdf" required></input>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <button class="btn-sm btn btn-info btn-block" @click="uploadContrato()" value=''><i class="fa fa-upload"></i> Subir Contro en PDF</button>
                  </div>
                </div>
              </form>

            </div>
            <div class="card-body" v-else-if="cargando == true">
              <div class='progress' id="progressDivId">
                <div class='progress-bar' id='progressBar'></div>
                <div class='percent' id='percent'>0%</div>
              </div>
              <div class="loaderr">
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
    <!-- fin subir contrato -->
  </div>
  `,
  data(){
    return {
      cargando:false,
      opcion:1,
      id_contrato:"",
      id_reng_num:"",
      reng_num:"",
      emp_plaza:"",
      items:[],
      contratos:[],
      tipos_baja_contrato:[],
      page: 1,
			perPage: 9,
      pages: [],
      renglon:"",
      viewPDF: false,
      pdf:"",
      config: {
        toolbar: false
      },
      sizeFile:0,
      arregloTipoDocs:
      [
        {
          'id_item':'',
          'item_string':'-- Seleccionar --'
        },
        {
          'id_item':'1',
          'item_string':'Contrato'
        },
        {
          'id_item':'2',
          'item_string':'Resolución'
        },
      ]
    }
  },
  computed: {
    displayedPosts () {
      return this.paginate(this.contratos);
    }
  },
  components: {
    VuePdfApp: window["vue-pdf-app"]
  },
  watch: {
    contratos () {
      this.setPages();
    }
  },
  mounted: function() {
  },
  methods: {
    getOpcion: function(opc){
      if(opc != 15 && opc != 20){
        this.id_contrato="";
        this.id_asignacion="";
        this.id_reng_num = "";
        this.renglon = "";
      }

      this.opcion = opc;

    },
    getOpcionFinContrato: function(reng_num, renglon,opc){
      this.opcion=opc;
      this.reng_num=reng_num;
      this.renglon = renglon;
      this.getStatusBaja(renglon);
    },
    getContratos: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/listados/contratos/get_contratos_by_persona.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.contratos = response.data;
          //alert('Works!!!')
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    setPages () {
      let numberOfPages = Math.ceil(this.contratos.length / this.perPage);
      for (let index = 1; index <= numberOfPages; index++) {
        this.pages.push(index);
      }
    },
    paginate (posts) {
      let page = this.page;
      let perPage = this.perPage;
      let from = (page * perPage) - perPage;
      let to = (page * perPage);
      return  posts.slice(from, to);
    },
    getStatusBaja: function(renglon){
      var tipo = (renglon == '029') ? 4 : 5;
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:29,
          tipo:tipo
        }
      })
      .then(function (response) {
        this.tipos_baja_contrato = response.data;
        $("#id_tipo_fc").select2({
          placeholder: "Seleccionar tipo de baja",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getContratoById: function(reng_num,opc){
      this.id_reng_num = reng_num;
      this.opcion = opc;
    },
    finalizarContrato: function(){
      viewModelDatosEmp.finalizarContrato();
    },
    getSizeFile: function(){
      var fileName = document.getElementById('id_contrato_pdf').files[0].name;
      var fileSize = document.getElementById('id_contrato_pdf').files[0].size;
      var fileType = document.getElementById('id_contrato_pdf').files[0].type;

      var fileModifiedDate = document.getElementById('id_contrato_pdf').files[0].lastModifiedDate;

      var file_info = fileName+"\n"+fileSize+"\n"+fileType+"\n"+fileModifiedDate;
      this.sizeFile = fileSize;
      //alert(this.sizeFile);
    },
    uploadContrato: function(){
      //inicio
      var thisInstance = this;


      jQuery('.jsValidacionSubirContrato').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);

          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          Swal.fire({
            title: '<strong>¿Desea subir el contrato en formato PDF?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              var formData = new FormData($("#formSubirContrato")[0]);

              $.ajax({
                xhr: function() {
                  var xhr = new window.XMLHttpRequest();
                  xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                      var percentComplete = ((evt.loaded / evt.total) * 100);
                      $(".progress-bar").width(percentComplete + '%');
                      $(".progress-bar").html(percentComplete+'%');
                    }
                  }, false);
                  return xhr;
                },
                type: "POST",
                url: "empleados/php/back/contratos/action/subir_contrato.php",
                method:"POST",
                dataType:"json",
                data:formData,
                contentType:false,
                processData:false,
                beforeSend: function () {
                  thisInstance.cargando = true;
                },
                uploadProgress: function(event, position, total, percentComplete){
                  var percentValue = percentComplete + '%';
                 $("#progressBar").animate({
                     width: '' + percentValue + ''
                 }, {
                     duration: 5000,
                     easing: "linear",
                     step: function (x) {
                       percentText = Math.round(x * 100 / percentComplete);
                         $("#percent").text(percentText + "%");
                       if(percentText == "100") {
                            $("#outputImage").show();
                       }
                     }
                 });
                },
                success: function (data) {
                  if (data.msg == 'OK') {
                    thisInstance.opcion = 1;
                    thisInstance.cargando = false;
                    thisInstance.getContratos();
                    recargarEmpleadosC(1);

                    Swal.fire({
                      type: 'success',
                      title: 'Documento subido',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  } else {
                    thisInstance.cargando = false;
                    Swal.fire({
                      type: 'error',
                      title: 'Error',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).done(function () {
              }).fail(function (jqXHR, textSttus, errorThrown) {
                alert(errorThrown);
              });
            }
          })
        }
      });
      //fin
    },
    viewContrato: function(archivo){
      this.pdf = "empleados/php/front/contratos/files/" + archivo;
      this.viewPDF = true;
    },
    viewContratos: function(){
      this.viewPDF = false;
      this.pdf = '';
    }
  },
  created: function() {
    this.getContratos();
    //this.mostrarContrato();
    eventBus.$on('showDetalleContratos', (opc) => {
      if(opc == 5){
        this.getContratos();
        this.getOpcion(1);
      }else{
        this.getOpcion(1);
      }

    });
  },
});


//detalle Ubicaciones
Vue.component('detalle-ubicaciones', {
  props:["id_persona","privilegio","datos_persona","datos_plaza"],
  template:`
  <div class="">
    <div class="row" v-if="opcion == 1 || opcion==6">
      <!-- inicio -->
      <div class="col-sm-12" v-if="(datos_plaza.emp_estado==891 || datos_plaza.emp_estado==908) && privilegio.acciones == true">
        <!--permiso de plazas -->
          <span style="margin-top:0px" class="btn btn-sm btn-soft-info" @click="getOpcion(4)"><i class="fa fa-plus"></i> Asignar Ubicación</span>
        <!--permiso de plazas -->
          <br><br>
      </div>
      <div class="col-md-12" >
        <!--<script src="empleados/js/source_modal.js"></script>-->
        <table id="tb_plazas_por_empleado_h" class="table table-sm table-striped table-bordered" width="100%">
          <thead>
            <!--<th class="text-center">ID</th>-->
            <th class="text-center">Plaza</th>
            <th class="text-center">Secretaría</th>

            <th class="text-center">Subsecretaría</th>
            <th class="text-center">Dirección</th>
            <th class="text-center">Subdirección</th>
            <th class="text-center">Departamento</th>
            <th class="text-center">Sección</th>
            <th class="text-center">Puesto</th>
            <th class="text-center">Inicio</th>
            <th class="text-center">Fin</th>
            <th class="text-center">Acuerdo</th>
            <th class="text-center">Acción</th>
          </thead>
          <tbody>
            <tr v-for="u in ubicaciones">
              <td class="text-center"><strong>{{u.cod_plaza}}</strong></td>
              <td class="text-center"><small>{{u.s}}</small></td>
              <td class="text-center"><small>{{u.ss}}</small></td>
              <td class="text-center"><small>{{u.dir}}</small></td>
              <td class="text-center"><small>{{u.sdir}}</small></td>
              <td class="text-center"><small>{{u.dep}}</small></td>
              <td class="text-center"><small>{{u.sec}}</small></td>
              <td class="text-center"><small>{{u.puesto}}</small></td>
              <td class="text-center"><small>{{u.fecha_ini}}</small></td>
              <td class="text-center"><small>{{u.fecha_fin}}</small></td>
              <td class="text-center"><small>{{u.acuerdo}} | {{ u.reng_num}}</small></td>
              <td class="text-center">
                <div class="btn-group" v-if="u.status==1 && privilegio.acciones == true">
                  <span class="btn-soft-info btn-sm btn" @click="valida_ubicacion(u.id_asignacion,u.reng_num,14)"><i class="fa fa-pencil-alt"></i>
                  </span>
                  <span class="btn-soft-info btn-sm btn" @click="valida_ubicacion(u.id_asignacion,u.reng_num,2)"><i class="fa fa-check-circle"></i>
                  </span>
                  <span class="btn-soft-danger btn-sm btn" @click="valida_ubicacion(u.id_asignacion,u.reng_num,3)"><i class="fa fa-trash-alt"></i>
                  </span>
                </div>
                <div v-else-if="u.status==2">
                  <span class="text-info"><i class="fa fa-check-circle"></i>
                  </span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- fin -->
    </div>
    <form-asignacion-ubicacion v-if="opcion==4" :id_persona="id_persona" :plazas="plazas" tipo_accion="1"></form-asignacion-ubicacion>
    <form-asignacion-ubicacion v-if="opcion==14" :id_persona="id_persona" :id_asignacion="id_asignacion_u" :reng_num="id_reng_num_u" :plazas="plazas" tipo_accion="2"></form-asignacion-ubicacion>
  </div>
  `,
  data(){
    return {
      opcion:1,
      id_asignacion_u:"",
      id_reng_num_u:"",
      id_ubicacion:"",
      emp_plaza:"",
      items:[],
      plazas:[],
      ubicaciones:[]
    }
  },
  mounted: function() {
  },
  methods: {
    getOpcion: function(opc){
      this.opcion = opc;
      if(opc != 14){
        this.id_plaza="";
        this.id_ubicacion="";
      }
      if(opc == 4){
        this.getPlazas();
      }else{
        this.plazas = [];
      }
    },
    valida_ubicacion(id_asignacion,reng_num,opc){
      var instancia = this;
      if(opc == 14){

        this.opcion = opc;
        this.id_asignacion_u = id_asignacion;
        this.id_reng_num_u = reng_num;
      }else{
        var title='¿Desea validar esta ubicación?', msg='¡Si validar!', color='#28a745';
        if(opc==3){
          title='¿Desea anular esta ubicación?', msg='¡Si anular!', color='#d33';
        }
        //inicio
        Swal.fire({
          title: '<strong>'+title+'</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: color,
          cancelButtonText: 'Cancelar',
          confirmButtonText: msg
        }).then((result) => {
          if (result.value) {
            //alert(vt_nombramiento);
            $.ajax({
              type: "POST",
              url: "empleados/php/back/puestos/validar_ubicacion.php",
              data: {
                id_asignacion,
                reng_num,
                opc
              }, //f de fecha y u de estado.
              beforeSend:function(){
                //$('#response').html('<span class="text-info">Loading response...</span>');
                //alert('message_before')
              },
              success:function(data){
                Swal.fire({
                  type: 'success',
                  title: 'Ubicación actualizada',
                  showConfirmButton: false,
                  timer: 1100
                });
                instancia.getUbicaciones();
                //alert(data);
              }
            }).done( function() {
            }).fail( function( jqXHR, textSttus, errorThrown){
              alert(errorThrown);
            });
          }
        })
        //fin
      }
    },
    getUbicaciones: function(){
      if(this.id_persona > 0){

        axios.get('empleados/php/back/listados/get_ubicaciones_por_asignacion.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.ubicaciones = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    getPlazas: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/listados/get_empleado_historial_plazas.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.plazas = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
  },
  created: function() {
    this.getUbicaciones();
    eventBus.$on('showDetalleUbicaciones', (opc) => {
      if(opc == 5){
        this.getUbicaciones();
        this.getOpcion(1);
      }else{
        this.getOpcion(opc);
      }

    });
  },
});
