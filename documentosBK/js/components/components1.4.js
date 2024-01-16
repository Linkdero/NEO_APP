Vue.component("unidades",{
  props: ["columna"],
  template: `
  <!--inicio-->
  <div :class="columna">
    <div class="form-group">
      <div class="">
        <div class="">
          <label for="id_unidad">Unidad*</label>
          <div class=" input-group  has-personalizado">
            <select class="js-select2 form-control form-control-sm" id="id_unidad" name="combo1">
              <option v-for="u in unidades" v-bind:value="u.id_departamento"><strong>{{ u.id_departamento }}</strong> - {{ u.nombre}}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- fin -->
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      unidades:[]
    }
  },
  methods:{
    getUnidades: function() {
      axios.get('documentos/php/back/listados/get_unidades', {
        params: {

        }
      }).then(function (response) {
        this.unidades = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getUnidades();
  }
})

Vue.component("empleados-listado",{
  props: ["arreglo","columna","verificacion","seleccionado"],
  template: `
    <div :class="columna">
      <div class="form-group">
        <div class="">
          <div class="row">
            <label for="id_ejercicio_ant">{{ label }}</label>
            <div class="input-group  has-personalizado" >
              <select id="id_empleados_list" class="grupo_empleados js-select2 form-control form-control-sm input-sm" required style="width:100%">
                <option v-for="e in empleados" :value="e.id_persona">{{ e.empleado }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
  mounted() {
    //console.log('Component mounted.')
  },
  data(){
    return {
      empleados:[],
      label:''
    }
  },
  methods:{
    getEmpleadosPorDireccion: function() {
      setTimeout(() => {
        this.empleados.length = 0;
        var url = '';
        var paramentro = '';
        if(this.verificacion == 9 || this.verificacion == 10 || this.verificacion == 11 || this.verificacion == 12){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = '';
          this.label = 'Asignar técnico: ';
        }else{
          this.label = 'Seleccionar empleado: ';
          url = 'documentos/php/back/listados/get_empleados_por_direccion';
          parametro = this.arreglo.id_direccion;
        }
        axios.get(url, {
          params: {
            id_direccion:parametro
          }
        }).then(function (response) {
          this.empleados = response.data;

          $("#id_empleados_list").select2({});
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }, 1100);
    }
  },
  created: function(){
    this.getEmpleadosPorDireccion();
  }
})

Vue.component("pedido", {
  props: ["ped_tra"],
  template: `
  <div class="col-sm-12">

    <div class="row">
      <div class="col-sm-3">
        <div class="row">
          <div class="col-sm-6">
            <small class="text-muted">Pedido No. </small>
             <h5><strong class="ped_num" :data-pk="pedido.ped_tra" data-name="Ped_num" >{{pedido.pedido_num}}</strong></h5>
          </div>
          <div class="col-sm-6">
             <small class="text-muted">Fecha: </small>
              <h5 id="fecha_pedido" class="ped_fecha" :data-pk="pedido.ped_tra" data-name="Ped_fec" ><strong>{{pedido.fecha}}</strong></h5>
          </div>
        </div>


      </div>
      <div class="col-sm-9">
        <small class="text-muted">Observaciones: </small>
         <h5 style="text-align:justify" class="ped_descripcion" :data-pk="pedido.ped_tra" data-name="Ped_obs" >{{pedido.observaciones}}</h5>
      </div>
  </div>
  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      pedido:""
    }
  },
  methods:{
    getPedido: function(){
      axios.get('documentos/php/back/pedido/get_pedido_by_id', {
        params: {
          ped_tra:this.ped_tra
        }
      }).then(function (response) {
        this.pedido = response.data;
        this.$emit('event_child', response.data)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });

    },
    setEditable: function(){
      setTimeout(() => {
        if(this.pedido.creador == true && this.pedido.tiempo == true){
          $('.ped_fecha').editable({
            url: 'documentos/php/back/pedido/action/update_fecha_general.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);

              }
            }
          });

          $('.ped_descripcion').editable({
            url: 'documentos/php/back/pedido/action/update_pedido.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'textarea',
            placement: 'bottom',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='OK'){
                $(this).text(response.valor_nuevo);
                instancia.reloadTable();
                //viewModelPac.tablePlan.ajax.reload(null, false);

              }
            }
          });

          $('.ped_num').editable({
            url: 'documentos/php/back/pedido/action/update_pedido.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'number',
            placement: 'bottom',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='OK'){
                $(this).text(response.valor_nuevo);
                instancia.reloadTable();
                //viewModelPac.tablePlan.ajax.reload(null, false);

              }
            }
          });

        }

      }, 700);
    }
  },
  created: function(){
    this.getPedido();
    //this.setEditable();
    eventBusPD.$on('recargarPedido', (opc) => {
      //alert('Works!!!');
      this.getPedido();
    });
  }
});

Vue.component("documentos-respaldo-pedido", {
  props: ["ped_tra","verificacion","pedido"],
  template: `
    <div class="row">
      <hr>
      <div class="col-sm-12">
        <div class="row">

        </div>
        <br>
        <div class="" v-if="doctoActual.ped_tra > 0">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#drespaldo" role="tab" aria-controls="drespaldo" aria-selected="true">Documento de Respaldo</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#dlistado" role="tab" aria-controls="profile" aria-selected="false">Lista</a>
            </li>

            <ul class="list-inline ml-auto mb-0">
              <span v-if="doctoActual.id_status == 3 && pedido.creador == true && pedido.tiempo == true" class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus"></i></span>
            </ul>

          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="drespaldo" role="tabpanel" aria-labelledby="home-tab">
              <br>
              <span :class="doctoActual.color">{{ doctoActual.estado }}</span>
              <br><br>
              <iframe id="targetDiv" type="application/pdf" src="" width="100%" height="500px"></iframe>

            </div>
            <div class="tab-pane fade" id="dlistado" role="tabpanel" aria-labelledby="profile-tab">
              <table class="table table-sm table-striped">
                <thead>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Estado</th>
                  <th class="text-center">Subido por</th>
                  <th class="text-center">Revisado por</th>
                  <th class="text-center">Observaciones</th>
                  <th class="text-center">Acción</th>
                </thead>
                <tbody>
                  <tr v-for="d in documentos">
                    <td class="text-center">{{ d.archivo }}</td>
                    <td class="text-center"><span :class="d.color"> {{ d.estado }}</span></td>
                    <td class="text-center">{{ d.operador }}</td>
                    <td class="text-center">{{ d.revisador }}</td>
                    <td class="text-center">{{ d.observaciones }}</td>
                    <td class="text-center"><span v-if="d.id_status == 0" @click="revisarDocumento(d.reng_num, 4)" class="btn btn-sm btn-soft-info"><i class="fa fa-check"></i></span></td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div v-else style="height:100px">
          <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus"></i></span><h3>Debe de subir el documento de respaldo en formato PDF.</h3>
        </div>
      </div>
      <div v-if="opc == 2 || opc == 3 || opc == 4">
        <div id="myModal" class="modal-vue" >

          <!-- Modal content -->
          <div class="modal-vue-content">
            <div class="card shadow-card">
              <header class="header-color">
                <h4 class="card-header-title" >
                  <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-file">
                  </i><span class="text-white" v-if="opc == 2"> Subir documento</span><span class="text-white" v-else> Revisar Documento</span>
                </h4>
                <span class="close-icon" @click="getOpc(1)">
                  <i class="fa fa-times"></i>
                </span>
              </header>
              <div class="card-body">
                <form class="jsValidacionSubirDocumento" action='' method='' enctype='' id="formDocumentoRespaldo" v-if="opc == 2">
                  <input type="text" id="ped_tra" name="ped_tra" :value="ped_tra" hidden></input>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-12">
                          <div class="">
                            <input class="form-control" type='file' id="id_documento_respaldo" name='id_documento_respaldo' accept="application/pdf" required></input>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <button class="btn-sm btn btn-info btn-block" @click="subirDocumento()" value=''><i class="fa fa-upload"></i> Subir Documento</button>
                    </div>
                  </div>
                </form>
                <form class="jsValidacionRevisarDocumento" action='' method='' enctype='' id="formRevisarDocumento" v-if="opc == 4">
                  <input type="text" id="ped_tra" name="ped_tra" :value="ped_tra" hidden></input>
                  <div class="row">
                    <campo row="col-sm-12" label="Observaciones:*" codigo="docto_obs" tipo="textarea" requerido="true"></campo>
                    <div class="col-sm-6">
                      <button class="btn-sm btn btn-info btn-block" @click="aprobarDocumento(rengNum,2)" value=''><i class="fa fa-check-circle"></i> Aprobar Documento</button>
                    </div>
                    <div class="col-sm-6">
                      <button class="btn-sm btn btn-danger btn-block" @click="aprobarDocumento(rengNum,3)" value=''><i class="fa fa-times-circle"></i> Anular Documento</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>


  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      documentos:[],
      documentosRespaldo:"",
      doctoActual:"",
      rengNum:0,
      opc: 1
    }
  },
  methods:{
    getOpc: function(opc){
      this.opc = opc;
      this.rengNum = 0;
    },
    revisarDocumento: function(reng, opc){
      console.log(reng);
      this.rengNum = reng;
      this.opc = opc;
    },
    getDocumentosRespaldo: function(){
      axios.get('documentos/php/back/pedido/detalle/get_documentos_listado', {
        params: {
          ped_tra:this.ped_tra
        }
      }).then(function (response) {
        this.documentos = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    mostrarDocumento: function(){
      axios.get('documentos/php/back/pedido/detalle/get_docto_actual', {
        params: {
          ped_tra:this.ped_tra
        }
      }).then(function (response) {
        this.doctoActual = response.data;
        setTimeout(() => {
          $("#targetDiv").attr("src", 'documentos/php/front/pedidos/files/'+this.doctoActual.archivo);
          $("#targetDiv").show();
        }, 600);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    subirDocumento: function(){
      var thisInstance = this;
      jQuery('.jsValidacionSubirDocumento').validate({
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
            title: '<strong>¿Desea subir el documento de respaldo?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              var formData = new FormData($("#formDocumentoRespaldo")[0]);
              $.ajax({
                type: "POST",
                url: "documentos/php/back/pedido/action/subir_documento.php",
                method:"POST",
                dataType:"json",
                data:formData,
                contentType:false,
                processData:false,
                beforeSend: function () {
                },
                success: function (data) {
                  if (data.msg == 'OK') {
                    thisInstance.opc = 1;
                    thisInstance.mostrarDocumento();
                    thisInstance.getDocumentosRespaldo();
                    Swal.fire({
                      type: 'success',
                      title: 'Documento subido',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  } else {
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
    },
    aprobarDocumento: function(reng_num,id){
      var ped_tra = this.ped_tra;
      var thisInstance = this;
      var msg = (id == 1) ? 'Aprobar' : 'Anular';
      var color = (id == 1) ? '#28a745' : '#d33';
      jQuery('.jsValidacionRevisarDocumento').validate({
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
            title: '<strong>¿Desea '+msg+' el documento de respaldo?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: color,
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, '+msg+'!'
          }).then((result) => {
            if (result.value) {
              var formData = new FormData($("#formRevisarDocumento")[0]);
              $.ajax({
                type: "POST",
                url: "documentos/php/back/pedido/action/aprobar_documento.php",

                dataType:"json",
                data:{
                  ped_tra:ped_tra,
                  reng_num:reng_num,
                  docto_obs:$('#docto_obs').val(),
                  tipo:id
                },
                beforeSend: function () {
                },
                success: function (data) {
                  if (data.msg == 'OK') {
                    thisInstance.opc = 1;
                    thisInstance.mostrarDocumento();
                    thisInstance.getDocumentosRespaldo();
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                  } else {
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
    },
  },
  created: function(){
    this.getDocumentosRespaldo();
    this.mostrarDocumento();
    //this.setEditable();
    eventBusPD.$on('recargarPedido', (opc) => {
      //alert('Works!!!');
      this.getPedido();
    });
  }
});


Vue.component("porcentaje-pedido", {
  props: ["ped_tra"],
  template: `
    <div class="text-left">
      <div style="margin-top:0px; ">
        <span class="badge-sm" :class="porcentaje.texto">{{ porcentaje.estado }}<br>{{ porcentaje.verificacion }}</span>
        <div class="progress progress-striped skill-bar " style="height:6px">
          <div class="progress-bar animated" :class="porcentaje.bg" role="progressbar" :aria-valuenow="porcentaje.valor" aria-valuemin="0" aria-valuemax="100" :style="{width: [percent]}"></div>
        </div>
      </div>
      <br>
    </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      porcentaje:"",
      tempo:10,
      progress: 0,
      completed: false,
      percent:""
    }
  },
  methods:{
    getPorcentaje: function(){
      axios.get('documentos/php/back/pedido/get_porcentaje_by_pedido', {
        params: {
          ped_tra:this.ped_tra
        }
      }).then(function (response) {
        this.porcentaje = response.data;
        setTimeout(() => {
          this.timer(this.tempo, response.data.valor);
        }, 100);

        this.$emit('event_child', response.data)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    timer: function(tempo, dato) {
      var vm = this;
    	var setIntervalRef = setInterval(function() {
        if(vm.progress < dato){
          vm.progress++;
          vm.percent = vm.progress+"%";
        }

        if (vm.progress == dato) {
        	clearInterval(setIntervalRef);
        	vm.completed = true;
				}
      }, tempo);
    }
  },
  created: function(){
    this.getPorcentaje();

    eventBusPD.$on('recargarPorcentaje', (opc) => {
      this.getPorcentaje();
    });
  }
});


Vue.component("insumos", {
  props: ["ped_tra","tipo", "clase"],
  template: `
  <table class="table table-sm table-bordered table-striped" :class="clase">
    <thead>
      <th class="text-center" style="width:15px">Renglon</th>
      <th class="text-center" style="width:15px">Insumo</th>
      <th class="text-center" style="width:15px">Cod.Pre</th>
      <th class="text-center" style="width:100px">Nombre</th>
      <th class="text-center"  v-if="tipo == 1" style="width:100px">Descripción</th>
      <th class="text-center" v-if="tipo == 1"style="width:15px">Pres.</th>
      <th class="text-center" style="width:15px">Med.</th>
      <th class="text-center" style="width:15px">Cantidad</th>
    </thead>
    <tbody>
      <tr v-for="i in insumos">
        <td class="text-center">{{i.Ppr_Ren}}</td>
        <td class="text-center">{{i.Ppr_cod}}</td>
        <td class="text-center">{{i.Ppr_codPre}}</td>

        <td class="text-center">{{i.Ppr_Nom}}</td>
        <td class="text-justify" v-if="tipo == 1">{{i.Ppr_Des}}</td>
        <td class="text-center" v-if="tipo == 1">{{i.Ppr_Pres}}</td>
        <td class="text-center">{{i.Ppr_Med}}</td>
        <td class="text-center">{{i.Pedd_can}}</td>
        <!--<td class="text-center">{{i.Pedd_can}}</td>-->
      </tr>
    </tbody>
  </table>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      insumos:[]
    }
  },
  methods:{
    getInsumos: function(){
      axios.get('documentos/php/back/pedido/get_insumos_by_pedido', {
        params: {
          ped_tra:this.ped_tra
        }
      }).then(function (response) {
        this.insumos = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getInsumos();

  }
});


Vue.component("seguimiento-list", {
  props: ["ped_tra","verificacion"],
  template: `
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-sm table-bordered table-striped">
        <thead>
          <th class="text-center" style="width:15px">Tipo</th>
          <th class="text-center" style="width:15px">Nombre</th>
          <th class="text-center" style="width:15px">Acción</th>

        </thead>
        <tbody>
          <tr v-for='(s, index) in seguimiento_list' :key="index" >
            <td class="text-center">{{s.tipo_seguimiento}}</td>
            <td class="text-center">{{s.ped_seguimiento_nom}}</td>
            <td class="text-center">
              <label class="css-input input-sm switch switch-sm switch-success" style="max-height:10px">
                <input :id="s.ped_seguimiento_id" :name="s.ped_seguimiento_id" :value="s.ped_seguimiento_id" @click="establecerPedidoVerificacion(8139,$event)" v-model="s.checked" type="checkbox"/><span></span>
              </label>
            </td>

            <!--<td class="text-center">{{i.Pedd_can}}</td>-->
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-sm-12 slide_up_anim text-right" v-if="cch1 >= 3 && verificacion == 2">
      <span class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8140,1)"><i class="fa fa-check"></i> Aprobar</span>
      <span class="btn btn-danger btn-sm btn-estado" @click="asignarEstadoPedido(8141,2)"><i class="fa fa-times"></i> Rechazar</span>
    </div>
    <div class="col-sm-12 slide_up_anim text-right" v-if="cch1 >= 1 && verificacion == 6">
      <span class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8143,1)"><i class="fa fa-check"></i> Aprobar</span>
      <span class="btn btn-danger btn-sm btn-estado" @click="asignarEstadoPedido(8144,2)"><i class="fa fa-times"></i> Anular</span>
    </div>
  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      cch1:0,
      contador:0,
      seguimiento_list:[]
    }
  },
  methods:{
    getSeguimientoList: function(){
      setTimeout(() => {

        axios.get('documentos/php/back/listados/get_seguimiento_list', {
            params: {
              ped_tra:this.ped_tra,
              verificacion:this.verificacion
            }
          }).then(function (response) {

            this.seguimiento_list = response.data;
            var i = 0;
            $.each(this.seguimiento_list,function(pos, elemento){
              if(elemento.checked)
              i += parseInt(1);
            })

            this.contador = i;
            this.cch1 = this.contador;
            console.log(this.cch1);

          }.bind(this)).catch(function (error) {
            console.log(error);
          });

      }, 1000);
              //var select = this.selectAll;
        //this.cch1 = this.cch1;


    },
    establecerPedidoVerificacion: function(estado_ve, event){
      //alert(estado_ve+ ' |-| '+ event.currentTarget.value)
      var id = event.currentTarget.value;
      var chequeado = ( $('#'+id).is(':checked') )?1:0;
      if( $('#'+id).is(':checked') ){
        this.cch1 += 1;
      }else{
        this.cch1 -= 1;
      }

      console.log(this.cch1 );

      if(estado_ve==8139 || estado_ve == 8142){
        //inicion
        $.ajax({
          type: "POST",
          url: "documentos/php/back/pedido/establecer_estado_verificacion.php",
          data: {
            ped_tra:this.ped_tra,
            estado_id:estado_ve,
            tipo_verificacion:id,
            chequeado:chequeado
            //id_persona:$('#id_empleados_list').val()
          }, //f de fecha y u de estado.

          beforeSend:function(){
          },
          success:function(data){
            viewModelPedidoDetalle.cambio = 1;
            viewModelPedidoDetalle.validar_estado_pedido();
            viewModelPedidoDetalle.get_bitacora();
            eventBusPD.$emit('recargarPorcentaje', 1);
          }
        }).done( function() {

        }).fail( function( jqXHR, textSttus, errorThrown){
          alert(errorThrown);

        });
        //fin
      }

    },
    asignarEstadoPedido: function(estado_id, tipo){
        viewModelPedidoDetalle.asignar_estado_pedido(estado_id, tipo);
    }
  },
  created: function(){
    this.getSeguimientoList();

    eventBusPD.$on('recargarSeguimientoList', (opc) => {
      //alert('Works!!!');
      this.getSeguimientoList();
    });
  }
});


Vue.component("personas-direccion", {
  props: ["pedido","verificacion","titulo"],
  template: `
  <div>
    <div class="row">
      <div class="col-sm-12" v-if="verificacion == 3 || verificacion == 7 || verificacion == 10 || verificacion == 12 || verificacion == 13 || verificacion == 1000 || verificacion == 1100">
        <h2>Notificar para recoger</h2>
        <div class="row">

          <div class="col-sm-6">
            <input type="text" class="form-control form-control-sm" id="destinatarios" name="destinatarios" placeholder="@corre de la persona"></input>

          </div>
          <div class="col-sm-6">
            <span class="btn btn-info btn-sm" @click="sendMail()"><i class="fa fa-envolope"></i> Enviar correo</span>
          </div>
        </div>
      </div>
     <div class="col-sm-12" v-if="verificacion == 1 || verificacion == 3 || verificacion == 4 || verificacion == 5 || verificacion == 7 || verificacion == 8 || verificacion == 9 || verificacion == 11 || verificacion == 12">
        <h2>{{ titulo }}</h2>
        <div class="row">
          <div class="col-sm-6">

            <select id="id_empleados_list" class="grupo_empleados js-select2 form-control form-control-sm input-sm" style="width:100%">
              <option v-for="e in empleados" :value="e.id_persona">{{ e.empleado }}</option>
            </select>
          </div>
          <div class="col-sm-6 text-right">
            <span v-if="verificacion == 1 || verificacion == 4"class="btn btn-info btn-sm" @click="asignarEstadoPedido(8156,1)"><i class="fa fa-check"></i> Recibir</span>
            <span v-if="verificacion == 3" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8157,1)"><i class="fa fa-check"></i> Devolver </span>
            <span v-if="verificacion == 5"class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8160,1)"><i class="fa fa-check"></i> Recibir</span>
            <span v-if="verificacion == 7"class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8161,1)"><i class="fa fa-check"></i> Devolver </span>
            <span v-if="verificacion == 7"class="btn btn-danger btn-sm btn-estado"><i class="fa fa-times"></i> Anular</span>
            <span v-if="verificacion == 8" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8164,1)"><i class="fa fa-check"></i> Recibir</span>
            <!--<span class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Anular</span>-->
            <span v-if="verificacion == 9 || verificacion == 11 || verificacion == 12" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8145,1)"><i class="fa fa-check"></i> Asignar y Cotizar</span>
            <span v-if="verificacion == 9 || verificacion == 11 || verificacion == 12" class="btn btn-danger btn-sm btn-estado" @click="asignarEstadoPedido(8147,2)"><i class="fa fa-times"></i> Anular</span>
          </div>

        </div>
     </div>


    </div>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      empleados:[]
    }
  },
  methods:{
    getEmpleadosPorDireccion: function() {
      setTimeout(() => {
        this.empleados.length = 0;
        var url = '';
        var paramentro = '';
        if(this.verificacion == 9 || this.verificacion == 10 || this.verificacion == 11 || this.verificacion == 12){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = '';
        }else{
          url = 'documentos/php/back/listados/get_empleados_por_direccion';
          parametro = this.pedido.direccion_funcional;
        }
        axios.get(url, {
          params: {
            id_direccion:parametro
          }
        }).then(function (response) {
          this.empleados = response.data;

          $("#id_empleados_list").select2({});
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }, 1100);
    },
    asignarEstadoPedido: function(estado_id, tipo){
        viewModelPedidoDetalle.asignar_estado_pedido(estado_id, tipo);
    },
    sendMail: function(){
      $.ajax({
        type: "POST",
        url: "documentos/php/back/pedido/enviar_correo.php",
        dataType: 'json',
        data: {
          destinatarios:$('#destinatarios').val(),
          ped_tra:this.pedido.ped_tra
          /*,
          subject:subject,
          body:body*/

        }, //f de fecha y u de estado.

        beforeSend:function(){
        },
        success:function(data){
          if(data.msg == 'OK'){
            Swal.fire({
              type: 'success',
              title: 'Correo enviado',
              showConfirmButton: false,
              timer: 1100
            });
          }else{
            Swal.fire({
              type: data.msg,
              title: data.id,
              showConfirmButton: false,
              timer: 1100
            });
          }
          console.log(data);
        }
      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){
        alert(errorThrown);

      });
    }
  },
  created: function(){
    this.getEmpleadosPorDireccion();
    eventBusPD.$on('recargarEmpleadosList', (opc) => {
      //alert('Works!!!');
      this.getEmpleadosPorDireccion();
    });

  }
});

//inicio 1h

Vue.component("formulario-1h", {
  props: ["env_tra", "formulario"],
  template: `
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-2">
        Número de Factura: <br>
        Serie: <br>
        Nit: <br>
        Proveedor: <br>
        Total: <br>
        Bodega: <br>
        Formulario 1H: <br>
        Serie 1H <br>
      </div>
      <div class="col-sm-8">
        <div class="row">

        {{ formulario1h.Env_num }}<br>
        {{ formulario1h.Ser_ser }}<br>
        {{ formulario1h.Prov_id }}<br>
        {{ formulario1h.Prov_nom }}<br>
        {{ formulario1h.Env_tot }}<br>
        {{ formulario1h.Bod_nom }}<br>
        {{ formulario1h.Fh_nro }}<br>
        {{ formulario1h.Fh_ser }}<br>

        </div>


      </div>

  </div>
  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      formulario1h:""
    }
  },
  methods:{
    getFormulario: function(){
      axios.get('documentos/php/back/formularios1h/get_formulario_by_id', {
        params: {
          env_tra:this.env_tra,
          formulario:this.formulario
        }
      }).then(function (response) {
        this.formulario1h = response.data;
        this.$emit('detalle-form-1h', response.data)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });

    }
  },
  created: function(){
    this.getFormulario();
  }
});

Vue.component("productos-1h", {
  props: ["env_tra"],
  template: `
    <div class="col-sm-12">
      <table class="table table-sm table-bordered table-striped" style="width:100%" width="100%">
        <thead>
          <th class="text-center">Renglon</th>
          <th class="text-center">Producto</th>
          <th class="text-center">Medida</th>
          <th class="text-center"">Cantidad</th>
        </thead>
        <tbody>
          <tr v-for="p in productos">
            <td class="text-center">{{p.Renglon_PPR }}</td>
            <td class="text-center">{{p.Pro_des}}</td>
            <td class="text-center">{{p.Med_nom}}</td>
            <td class="text-center">{{p.Envd_can}}</td>
          </tr>
        </tbody>
      </table>
    </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      productos:[]
    }
  },
  methods:{
    getDetalleFormulario: function(){
      axios.get('documentos/php/back/formularios1h/get_productos_by_formulario', {
        params: {
          env_tra:this.env_tra
        }
      }).then(function (response) {
        this.productos = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });

    }
  },
  created: function(){
    this.getDetalleFormulario();

  }
});

Vue.component("seguimiento-1h", {
  props: ["arreglo"],
  template: `
    <form >

      <div class="row">
      <empleados-listado :arreglo="arreglo" columna="col-sm-12"></empleados-listado>

      </div>
    </form>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      productos:[]
    }
  },
  methods:{
    generarSeguimiento: function(){

    }
  },
  created: function(){

  }
});

//PAC
Vue.component("pac-detalle", {
  props: ["pac_id"],
  template: `
  <div class="col-sm-12">
    <div class="row">
      <div class="col-sm-3">
        <div class="row">
          <div class="col-sm-12">
            <small class="text-muted">Nombre del Plan </small>
             <h5><strong class="descripcion_" :data-pk="pac_id" data-name="pac_nombre" >{{pac.nombre}}</strong></h5>
          </div>
          <div class="col-sm-12">
             <small class="text-muted">Renglón: </small>
              <h5><strong class="renglones" :data-pk="pac_id" data-name="pac_renglon">{{pac.renglon}}</strong></h5>
              <h5><strong >{{pac.renglon_nm}}</strong></h5>
          </div>
        </div>
      </div>
      <div class="col-sm-9" >
        <div class="row">
          <div class="col-sm-12">
            <small class="text-muted">Detalle del Plan: </small>
            <h5 style="text-align:justify"><strong class="descripcion_" :data-pk="pac_id" data-name="pac_detalle">{{pac.detalle}}</strong></h5>
          </div>
          <div class="col-sm-12" >
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <div class="">
                    <div class="row">
                      <label for="id_ejercicio_ant">Ejercicio Anterior</label>
                      <div class="input-group  has-personalizado" >
                        <label class="css-input input-sm switch switch-success"><input id="id_ejercicio_ant" name="id_ejercicio_ant" type="checkbox" :disabled="creador == false && pac.id_status > 1" v-model="ejercicioAnterior" @change="setEditable()"/><span></span> <span id="lbl_rdrecibido"><small>Eventos multianuales</small></span></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-3" v-if="ejercicioAnterior == true">
                <br>
                <small class="text-muted">Ejercicio anterior: </small>
                <h5 style="text-align:justify"><strong class="descripcion_cor" :data-pk="pac_id" data-name="pac_ejercicio_ant">{{pac.pac_ejercicio_ant}}</strong></h5>
              </div>
              <div class="col-sm-6" v-if="ejercicioAnterior == true">
                <br>
                <div class="row">
                  <div class="col-sm-6">
                    <small class="text-muted">Detalle del ejercicio: </small>
                    <h5 style="text-align:justify"><strong class="descripcion_" :data-pk="pac_id" data-name="pac_ejercicio_ant_desc">{{pac.pac_ejercicio_ant_desc}}</strong></h5>
                  </div>
                  <div class="col-sm-6" v-if="creador == true">
                    <span class="btn btn-soft-info btn-sm" @click="limpiarEjercicio()" v-if="pac.pac_ejercicio_ant != 0"><i class="fa fa-brush"></i> Limpiar</span>
                  </div>
                </div>


              </div>
            </div>
          </div>
        </div>



      </div>
    </div>
  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      pac:"",
      renglones:[],
      ejercicioAnterior:false,
      creador: false
    }
  },
  methods:{
    getPlan: function(pac_id){
      axios.get('documentos/php/back/pac/get_pac_by_id', {
        params: {
          pac_id:pac_id
        }
      }).then(function (response) {
        this.pac = response.data;
        this.ejercicioAnterior = response.data.anterior;
        this.creador = response.data.creador;
        this.$emit('event_child', response.data)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    limpiarEjercicio: function(){
      axios.get('documentos/php/back/pac/action/quitar_anterior', {
        params: {
          pac_id:this.pac_id
        }
      }).then(function (response) {
        if(response.data.msg == 'OK'){
          this.getPlan(this.pac_id);
          instancia.reloadTable();
          this.ejercicioAnterior = false;

        }
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    setEditable: function(){

      var thisInstance = this;
      var pacId = this.pac_id;
      axios.get('documentos/php/back/pac/listados/get_renglones', {
        params: {

        }
      }).then(function (response) {
        this.renglones = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
      setTimeout(() => {
        if(this.creador == true && this.pac.id_status == 1){
          $('.f_fecha').editable({
            url: 'viaticos/php/back/viatico/ac/update_fecha_general.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);

              }
            }
          });
          $('.renglones').editable({
            url: 'documentos/php/back/pac/action/update_descripcion.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'select',
            source:this.renglones,
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='OK'){
                $(this).text(response.valor_nuevo);
                thisInstance.getPlan(pacId);
                instancia.reloadTable();

              }
            }
          });

          $('.descripcion_').editable({
            url: 'documentos/php/back/pac/action/update_descripcion.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'textarea',
            placement: 'bottom',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='OK'){
                $(this).text(response.valor_nuevo);
                instancia.reloadTable();
                //viewModelPac.tablePlan.ajax.reload(null, false);

              }
            }
          });

          $('.descripcion_cor').editable({
            url: 'documentos/php/back/pac/action/update_descripcion.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'text',
            placement: 'bottom',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='OK'){
                $(this).text(response.valor_nuevo);
                instancia.reloadTable();
                //viewModelPac.tablePlan.ajax.reload(null, false);

              }
            }
          });

        }

      }, 700);

    }
  },
  created: function(){
    this.getPlan(this.pac_id);
    this.setEditable();

    eventBusPACD.$on('recargarPlan', (opc) => {
      //alert('Works!!!'+ opc);
      this.getPlan(opc);
    });
  }
});

Vue.component("pac-meses-listado",{
  props: ['pac_id','arreglo',],
  template: `
  <div class="col-sm-12">
    <form class="jsValidacionUpdateMeses">
      <div class="">
        <div class="">
          <table class="table table-sm table-bordered table-striped" style="width:100%" >
            <thead>
              <th class="text-center" style="width:15px">Código</th>
              <th class="text-center" style="width:15px">Mes</th>
              <th class="text-center" style="width:50px">Cantidad</th>
              <th class="text-center" style="width:50px">Monto</th>

              <th class="text-center" style="width:50px">Cantidad Real</th>
              <th class="text-center" style="width:50px">Monto Real</th>
              <th class="text-center" width="120px">Todos

              </th>

            </thead>
            <tbody>
              <tr v-for="(m, index) in meses">
                <td class="text-center">{{m.id_mes}}</td>
                <td class="text-center">{{m.mes}}</td>
                <td >
                  <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true" >
                    <div class="" v-if="arreglo.creador == true && arreglo.id_status == 1">
                      <div class="">
                        <input :name="'txt'+index" :id="'txt'+index" class="form-control input-sm" min="1" v-model="m.cantidad" type="number" autocomplete="off" required></input>
                      </div>
                    </div>
                    <div v-else class="text-center">
                      {{ m.cantidad }}
                    </div>
                  </div>
                </td>
                <td>
                  <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true" >
                    <div class="" v-if="arreglo.creador == true && arreglo.id_status == 1">
                      <div class="">
                        <input :name="'m'+index" :id="'m'+index" class="form-control input-sm" min="1" v-model="m.monto" type="number" autocomplete="off" required></input>
                      </div>
                    </div>
                    <div v-else class="text-center">
                      {{ m.monto  }}
                    </div>
                  </div>
                </td>
                <td >
                  <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true && m.compras == true" >
                    <div class="" v-if="arreglo.id_status == 1">
                      <div class="">
                        <input :name="'txtc'+index" :id="'txtc'+index" class="form-control input-sm" min="1" v-model="m.cantidad_real" type="number" autocomplete="off" required></input>
                      </div>
                    </div>
                    <div v-else class="text-center">
                      {{ m.cantidad_real }}
                    </div>
                  </div>
                </td>
                <td>
                  <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true && m.compras == true" >
                    <div class="" v-if="arreglo.id_status == 1">
                      <div class="">
                        <input :name="'mc'+index" :id="'mc'+index" class="form-control input-sm" min="1" v-model="m.monto_real" type="number" autocomplete="off" required></input>
                      </div>
                    </div>
                    <div v-else class="text-center">
                      {{ m.monto_real  }}
                    </div>
                  </div>
                </td>
                <td class="text-center">
                  <input class="tgl tgl-flip text-center" :id="m.id_pac_mes" :name="m.id_pac_mes" type="checkbox" v-on:change="setValor()" :disabled="arreglo.creador ==  false" v-model="m.checked"/>
                  <label  class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="m.id_pac_mes" ></label>
                </td>
              </tr>
            </tbody>
          </table>
          <h3 v-if="arreglo.id_status == 3" class="text-success">Autorizado en compras</h3><h3 v-if="arreglo.id_status == 4" class="text-danger">Anulado en compras</h3>
          <div v-if="compras == true && arreglo.id_status == 1" class="text-right">
            <button class="btn btn-info btn-sm" @click="eventAccion(1)"><i class="fa fa-check"></i> Autorizar</button>
            <span class="btn btn-danger btn-sm" @click="eventAccion(55)"><i class="fa fa-times"></i> Anular</span>
          </div>
          <div class="row" v-if="arreglo.creador == true && arreglo.id_status == 1">
            <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
          </div>

        </div>
      </div>

    </form>
  </div>


  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      meses:[],
      selectAll: false,
      creador: false,
      compras: false
    }
  },
  methods:{
    getMesesById: function() {
      axios.get('documentos/php/back/pac/listados/get_meses_by_pac', {
        params: {
          pac_id:this.pac_id
        }
      }).then(function (response) {
        this.meses = response.data;
        this.compras = response.data[0].compras;
        console.log(this.compras);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    setValor: function(){
      this.meses.forEach(function(c) {
        if(c.checked == false){
          c.cantidad = '';
          c.monto = '';
        }
        //viewModelViaticoDetalle.subtotal(c.id, c.cuota_diaria,2);
      });
    },
    eventAccion: function(id){
      if(id == 1){
        this.updateMeses();
      }else if(id == 55){
        Swal.fire({
          title: '<strong>¿Desea anular esta compra?</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Anular!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              url: "documentos/php/back/pac/action/anular_plan.php",
              dataType: 'json',
              data: {
                pac_id:this.pac_id
              }, //f de fecha y u de estado.
              beforeSend: function () {
              },
              success: function (data) {
                if (data.msg == 'OK') {
                  eventBusPACD.$emit('recargarPlan',this.pac_id);
                  instancia.reloadTable();
                  thisInstance.getMesesById();
                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });
                } else {
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
    },
    updateMeses: function(){
      var thisInstance = this;
      var months = this.meses;
      var pac_id = this.pac_id;
      jQuery('.jsValidacionUpdateMeses').validate({
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
            title: '<strong>¿Desea actualizar los meses?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "documentos/php/back/pac/action/update_meses.php",
                dataType: 'json',
                data: {
                  pac_id:pac_id,
                  months: months
                }, //f de fecha y u de estado.
                beforeSend: function () {
                },
                success: function (data) {
                  if (data.msg == 'OK') {
                    eventBusPACD.$emit('recargarPlan',pac_id);
                    thisInstance.getMesesById();
                    instancia.reloadTable();

                    Swal.fire({
                      type: 'success',
                      title: 'Meses actualizados',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  } else {
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
    }
  },
  created: function(){
    this.getMesesById();
    setTimeout(() => {
      this.creador = this.arreglo.creador;
    }, 700);
  }
})


Vue.component("renglones-listado",{
  props: ['columna'],
  template: `

  <combo :row="columna" label="Renglón:*" codigo="id_renglon" :arreglo="renglones" tipo="3" requerido="true"></combo>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      renglones:[]
    }
  },
  methods:{
    getRenglones: function() {
      axios.get('documentos/php/back/listados/get_renglones', {
        params: {

        }
      }).then(function (response) {
        this.renglones = response.data;
        $("#id_renglon").select2({});
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getRenglones();
  }
})

Vue.component("meses-listado",{
  props: ['columna', "label"],
  template: `
  <table class="table table-sm table-bordered table-striped" >
    <thead>
      <th class="text-center" style="width:15px">Código</th>
      <th class="text-center" style="width:15px">Mes</th>
      <th class="text-center" style="width:50px">Cantidad</th>
      <th class="text-center" style="width:50px">Monto</th>
      <th class="text-center" width="120px">Todos
        <div class="custom-control custom-checkbox text-center" style="position:absolute;margin-top:-1rem">
          <input id="id_meses" class="custom-control-input" type="checkbox" @click="toggleSelect" :checked="selectAll" checked>
          <label class="custom-control-label" for='id_meses'></label>
        </div>
      </th>

    </thead>
    <tbody>
      <tr v-for="(m, index) in meses">
        <td class="text-center">{{m.id_item}}</td>
        <td class="text-center">{{m.item_string}}</td>
        <td style="width:50px">
          <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true" >
            <div class="">
              <div class="">
                <input :name="'txt'+index" :id="'txt'+index" class="form-control input-sm" min="1" v-model="m.cantidad" type="number" autocomplete="off" required></input>
              </div>
            </div>
          </div>
        </td>
        <td style="width:50px">
          <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true" >
            <div class="">
              <div class="">
                <input :name="'m'+index" :id="'m'+index" class="form-control input-sm" min="1" v-model="m.monto" type="number" autocomplete="off" required></input>
              </div>
            </div>
          </div>
        </td>
        <td class="text-center" width="10px">
          <input class="tgl tgl-flip text-center" :id="'ck'+m.id_mes" :name="'ck'+m.id_mes" type="checkbox" v-on:change="setMes(m.id)" v-model="m.checked"/>
          <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="'ck'+m.id_mes" ></label>
        </td>
      </tr>
    </tbody>
    <tfoot v-if="selectAll == true">
      <tr>
        <td></td>
        <td class="text-right">TODOS</td>
        <td><input class="form-control form-control-sm" @keyup="setAllCantidad($event)" type="number"></input></td>
        <td><input class="form-control form-control-sm" @keyup="setAllMontos($event)" type="number"></input></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      meses:[],
      selectAll: false
    }
  },
  methods:{
    getMeses: function() {
      axios.get('documentos/php/back/listados/get_meses', {
        params: {

        }
      }).then(function (response) {
        this.meses = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    setMes: function(id){
      var total = this.meses.length;
      var seleccionados = 0;
      this.meses.forEach(function(c) {
        if(c.checked == true){
          seleccionados += 1;
        }else{
          c.cantidad = '';
          c.monto = '';
          seleccionados -=1;
        }
        //viewModelViaticoDetalle.subtotal(c.id, c.cuota_diaria,2);
      });
      if(total == seleccionados){
        this.selectAll = true;
      }else{
        this.selectAll = false;
      }
      //this.enviarMeses();
      this.$emit('send_months', this.meses)
    },
    toggleSelect: function() {
      var select = this.selectAll;
      this.meses.forEach(function(c) {
        c.checked = !select;
        if(c.checked != true){
          c.cantidad = '';
          c.monto = '';
        }
        //viewModelViaticoDetalle.subtotal(c.id, c.cuota_diaria,2);
      });
      this.selectAll = !select;
      //this.enviarMeses();
      this.$emit('send_months', this.meses)
    },
    setAllCantidad: function(event){
      var q = event.currentTarget.value;
      this.meses.forEach(function(c) {
        c.cantidad = q;
      });
    },
    setAllMontos: function(event){
      var m = event.currentTarget.value;
      this.meses.forEach(function(c) {
        c.monto = m;
      });
    },

  },
  created: function(){
    this.getMeses();
    eventBusPAC.$on('recargarMeses', (opc) => {
      this.getMeses();
    });

  }
})

Vue.component("pac-filtrado",{
  props: ["columna"],
  template: `
  <!--inicio-->
  <div :class="columna">
    <div class="form-group">
      <div class="">
        <div class="">
          <label for="id_unidad">Plan de Compra*</label>
          <div class=" input-group  has-personalizado">
            <div class=" input-group  has-personalizado">
              <select class="pacName form-control" style="width:100%" id="pacName" name="pacName"></select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- fin -->
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      unidades:[]
    }
  },
  methods:{
    getPlanes: function() {
      alert('message')
      $('.pacName').select2({
        placeholder: 'Selecciona un plan de compra',
        ajax: {
          url: 'documentos/php/back/pac/detalle/get_plan_filtrado.php',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }
  },
  created: function(){
    this.getPlanes();
  }
})


//facturas
Vue.component("factura-bitacora",{
  props: ["orden_id", "privilegio", "factura"],
  template: `
  <div class="row">
    <div>
      <table class="table table-sm table-striped table-bitacora-fac ">
        <thead>
          <th class="text-center">Tipo</th>
          <th class="text-center">Operador</th>
          <th class="text-center">Fecha y hora</th>
          <th class="text-center">Observaciones</th>
        </thead>
        <tbody>
          <tr v-for='(b, index) in bitacora' :key="index" >
            <td class="text-center">{{b.tipo}}</td>
            <td class="text-center">{{b.operador}}</td>
            <td class="text-center">{{b.fecha}}</td>
            <td class="text-center">{{b.observaciones}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue" >
        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
                </i><span class="text-white"> Establecer estado</span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">

              <form class="jsValidacionAsignacionEmpleado">
                <div class="row">
                adfadf
                {{ factura.estado_factura }}


                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>


  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc: 1,
      bitacora:"",
      estadoF:0
    }
  },
  methods:{
    getBitacoraByFactura: function(){
      //setTimeout(() => {
        this.estadoF = this.factura.estado_factura;
        axios.get('documentos/php/back/factura/detalle/get_bitacora_factura', {
          params: {
            orden_id: this.orden_id
          }
        }).then(function (response) {
          this.bitacora = response.data;

        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      //}, 900);
    },
    eventAccion: function(id){
      var thisInstance = this;
      if(id == 1){
        jQuery('.jsValidacionAsignacionEmpleado').validate({
            ignore: [],
            errorClass: 'help-block animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                var elem = jQuery(e);

                elem.closest('.form-group').removeClass('has-error').addClass('has-error');
                elem.closest('.help-block').remove();
            },
            success: function(e) {
                var elem = jQuery(e);

                elem.closest('.form-group').removeClass('has-error');
                elem.closest('.help-block').remove();
            },
            submitHandler: function(form){

                  Swal.fire({
                  title: '<strong>¿Desea asignar a la Factura?</strong>',
                  text: "",
                  type: 'question',
                  showCancelButton: true,
                  showLoaderOnConfirm: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: '¡Si, Asignar!'
                  }).then((result) => {
                  if (result.value) {
                    $.ajax({
                    type: "POST",
                    url: "documentos/php/back/factura/asignar_factura.php",
                    dataType: 'json',
                    data: {
                      orden_id:$('#orden_id').val(),
                      id_persona:$('#id_empleados_list').val()
                    }, //f de fecha y u de estado.
                    beforeSend:function(){
                    },
                    success:function(data){
                      if(data.msg == 'OK'){
                        thisInstance.getTecnico();
                        viewModelFacturaDetalle.getFactura();
                        instanciaF.recargarFacturas();
                        thisInstance.getOpc(1);
                        Swal.fire({
                          type: 'success',
                          title: 'Persona asignada',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }else{
                        Swal.fire({
                          type: 'error',
                          title: 'Error',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }
                    }
                  }).done( function() {
                  }).fail( function( jqXHR, textSttus, errorThrown){
                    alert(errorThrown);
                  });
                }
              })
          },
          rules: {
            id_campo:{ required: true},
          }
        });
      }else{
        this.opc = 1;
      }
    },
    getOpc: function(opc){

      this.opc = opc;
      if(opc == 1){
        eventBus.$emit('regresarPrincipal', 1);
      }
    }
  },
  created: function(){
    this.getBitacoraByFactura();
    this.opc = this.option;

    eventBus.$on('recargarBitacoraFactura', (opc) => {
      this.getBitacoraByFactura();
    });
  }
})


Vue.component("asignar-tecnico",{
  props: ["columna", "option","orden_id", "privilegio"],
  template: `
  <div class="row">
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-6">
          <h3>Detalle del Técnico</h3>
        </div>
        <div class="col-sm-6 text-right" v-if="privilegio.compras_asignar_tecnico == true">
          <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-user-plus"></i></span>
        </div>
      </div>
      <br>
      <div class="row" >
        <div class="col-sm-2" v-if="tecnico.id_persona > 0">
          <fotografia-empleado :id_persona="tecnico.id_persona"  style="margin-top:-25px;z-index:0;position:absolute" tipo="1"></fotografia-empleado>
        </div>
        <div class="col-sm-6">
          <dato-persona texto="Técnico asignado:" :dato="tecnico.tecnico" tipo="0"></dato-persona>
        </div>
      </div>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue" >

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
                </i><span class="text-white"> Asignar Técnico</span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionAsignacionEmpleado">
                <div class="row">
                  <empleados-listado columna="col-sm-12" verificacion="9"></empleados-listado>
                  <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>


  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc: 1,
      tecnico:""
    }
  },
  methods:{
    getTecnico: function(){
      axios.get('documentos/php/back/factura/get_tecnico_actual', {
        params: {
          orden_id: this.orden_id
        }
      }).then(function (response) {
        this.tecnico = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    eventAccion: function(id){
      var thisInstance = this;
      if(id == 1){
        jQuery('.jsValidacionAsignacionEmpleado').validate({
            ignore: [],
            errorClass: 'help-block animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                var elem = jQuery(e);

                elem.closest('.form-group').removeClass('has-error').addClass('has-error');
                elem.closest('.help-block').remove();
            },
            success: function(e) {
                var elem = jQuery(e);

                elem.closest('.form-group').removeClass('has-error');
                elem.closest('.help-block').remove();
            },
            submitHandler: function(form){

                  Swal.fire({
                  title: '<strong>¿Desea asignar a la Factura?</strong>',
                  text: "",
                  type: 'question',
                  showCancelButton: true,
                  showLoaderOnConfirm: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: '¡Si, Asignar!'
                  }).then((result) => {
                  if (result.value) {
                    $.ajax({
                    type: "POST",
                    url: "documentos/php/back/factura/asignar_factura.php",
                    dataType: 'json',
                    data: {
                      orden_id:$('#orden_id').val(),
                      id_persona:$('#id_empleados_list').val()
                    }, //f de fecha y u de estado.
                    beforeSend:function(){
                    },
                    success:function(data){
                      if(data.msg == 'OK'){
                        thisInstance.getTecnico();
                        viewModelFacturaDetalle.getFactura();
                        instanciaF.recargarFacturas();
                        thisInstance.getOpc(1);
                        Swal.fire({
                          type: 'success',
                          title: 'Persona asignada',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }else{
                        Swal.fire({
                          type: 'error',
                          title: 'Error',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }
                    }
                  }).done( function() {
                  }).fail( function( jqXHR, textSttus, errorThrown){
                    alert(errorThrown);
                  });
                }
              })
          },
          rules: {
            id_campo:{ required: true},
          }
        });
      }else{
        this.opc = 1;
      }
    },
    getOpc: function(opc){

      this.opc = opc;
      if(opc == 1){
        eventBus.$emit('regresarPrincipal', 1);
      }
    }
  },
  created: function(){
    this.getTecnico();
    this.opc = this.option;
  }
})


Vue.component("asignar-modalidad-compra",{
  props: ["columna", "option", "factura", "privilegio"],
  template: `
  <div class="row">
  {{ factura.tecnico_au }}
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-6">
          <h3>Detalle del pago</h3>
        </div>
        <div class="col-sm-6 text-right">
          <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-file-invoice-dollar"></i></span>
        </div>
      </div>

      <br>
      <div class="" >
        <dato-persona texto="Modalidad de compra:" :dato="factura.tipo" tipo="0"></dato-persona>


        <div class="row">
          <div class="col-sm-4">
            <dato-persona texto="Forma de pago:" :dato="factura.forma_pago_text" tipo="0"></dato-persona>
          </div>
          <div class="col-sm-4" v-if="factura.forma_de_pago == 1">
            <dato-persona texto="Cheque:" :dato="factura.cheque" tipo="0" v-if="factura.forma_de_pago == 1"></dato-persona>
          </div>
          <div class="col-sm-4" v-if="factura.forma_de_pago == 2">
            <dato-persona v-if="factura.clase_proceso == 1" texto="Nro. de Orden:" :dato="factura.nro_orden" tipo="0"></dato-persona>
            <dato-persona v-if="factura.clase_proceso == 2" texto="CYD:" :dato="factura.nro_orden" tipo="0"></dato-persona>
            <dato-persona v-if="factura.clase_proceso == 3" texto="COM-DEV:" :dato="factura.nro_orden" tipo="0"></dato-persona>
          </div>
          <div class="col-sm-2" v-if="factura.forma_de_pago == 2">
            <dato-persona texto="CUR C:" :dato="factura.cur" tipo="0"></dato-persona>
          </div>
          <div class="col-sm-2" v-if="factura.forma_de_pago == 2">
            <dato-persona texto="CUR L:" :dato="factura.cur_devengado" tipo="0"></dato-persona>
          </div>
        </div>
      </div>

    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue" >

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
                </i><span class="text-white"> Asignar Pago</span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">

            Modalidad: {{ factura.modalidad_pago }} <br>
            Cheque: {{ factura.f_cheque }} <br>
            Tipo registro: {{ factura.f_clase_proceso }} <br>
            Orden: {{ factura.f_orden }} <br>
            Cur: {{ factura.f_cur }} <br>
            CurD: {{ factura.f_curl }} <br>
            compra: {{ factura.forma_de_pago }}<br>
              <form class="jsValidacionAsignacionFase">
                <div class="row" v-if="factura.tipo_pago == 0">
                  <!--inicio-->
                  <div class="col-sm-4">
                    <h3>Modalidad de Compra</h3>
                  </div>
                  <div class="col-sm-12" >
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_campo">Modalida de compra</label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm" style="width:100%" id="id_campo" name="id_campo" required>
                              <option value="">-- Seleccionar --</option>
                              <option value="1" v-if="factura.factura_total <= 25000">Baja Cuantía</option>
                              <option value="2" v-if="factura.factura_total > 25000">Acreditamiento</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <accion confirmacion="0" cancelar="35" v-on:event_child="eventAccion"></accion>
                </div>
                <!-- fin -->
                <div class="row" v-if="factura.tipo_pago == 1 && factura.modalidad_pago == true">
                  <!-- inicio -->
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_campo">Forma de pago</label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm" style="width:100%" id="id_campo" name="id_campo" required>
                              <option value="">-- Seleccionar --</option>
                              <option value="3">Caja Chica</option>
                              <option value="1">Cheque</option>
                              <option value="2">Acreditacimiento</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin -->
                  <accion confirmacion="5" cancelar="35" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>

            <!-- fin -->
            <!-- inicio -->
            <div v-if="factura.tipo_pago > 0 && factura.modalidad_pago == false" class="col-sm-12">
              <div >
                <form class="jsValidacionAsignacionFase" v-if="factura.f_cheque == true || factura.f_orden == true || factura.f_cur == true || factura.f_curl == true || factura.f_clase_proceso == true">
                  <div class="row">
                    <!--inicio-->
                    <div class="col-sm-12">
                    <h3 v-if="factura.forma_de_pago == 1 && factura.f_cheque == true">Agregar Cheque</h3>
                    <h3 v-else-if="factura.forma_de_pago == 2 && factura.f_clase_proceso == true">Seleccionar clase de proceso</h3>
                    <h3 v-else-if="factura.forma_de_pago == 2 && factura.f_orden == true && factura.clase_proceso == 1">Agregar Nro. de Orden</h3>
                    <h3 v-else-if="factura.forma_de_pago == 2 && factura.f_orden == true && factura.clase_proceso == 2">Agregar CYD</h3>
                    <h3 v-else-if="factura.forma_de_pago == 2 && factura.f_cur == true">Agregar CUR de compromiso</h3>
                    <h3 v-else-if="factura.forma_de_pago == 2 && factura.f_curl == true">Agregar CUR de liquidación</h3>

                    </div>
                    <div class="col-sm-12" v-if="factura.forma_de_pago == 2 && factura.f_clase_proceso == true">
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <div class=" input-group  has-personalizado" >
                              <select class="form-control form-control-sm" style="width:100%" id="id_campo" name="id_campo" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="1">Número de Orden</option>
                                <option value="2">CYD</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12" v-else>
                      <div class="form-group">
                        <div class="">
                          <div class="">
                            <div class=" input-group  has-personalizado" >
                              <input class="form-control form-control-sm" id="id_campo" name="id_campo"></input>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- fin -->

                    <accion v-if="factura.forma_de_pago == 1 && factura.f_cheque == true" confirmacion="1" cancelar="35" v-on:event_child="eventAccion"></accion>
                    <accion v-else-if="factura.forma_de_pago == 2 && factura.f_orden == true" confirmacion="2" cancelar="35" v-on:event_child="eventAccion"></accion>
                    <accion v-else-if="factura.forma_de_pago == 2 && factura.f_cur == true" confirmacion="3" cancelar="35" v-on:event_child="eventAccion"></accion>
                    <accion v-else-if="factura.forma_de_pago == 2 && factura.f_curl == true" confirmacion="4" cancelar="35" v-on:event_child="eventAccion"></accion>
                    <accion v-else-if="factura.forma_de_pago == 2 && factura.f_clase_proceso == true" confirmacion="6" cancelar="35" v-on:event_child="eventAccion"></accion>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>


  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc: 1,
      pago:""
    }
  },
  methods:{
    getPagoByFactura: function(){
      axios.get('documentos/php/back/factura/get_pago_factura', {
        params: {
          orden_id: this.orden_id
        }
      }).then(function (response) {
        this.pago = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },

    eventAccion: function(id){
      var thisInstance = this;
      var tipo = id;
      if(tipo != 35){
        jQuery('.jsValidacionAsignacionFase').validate({
            ignore: [],
            errorClass: 'help-block animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                var elem = jQuery(e);
                elem.closest('.form-group').removeClass('has-error').addClass('has-error');
                elem.closest('.help-block').remove();
            },
            success: function(e) {
                var elem = jQuery(e);
                elem.closest('.form-group').removeClass('has-error');
                elem.closest('.help-block').remove();
            },
            submitHandler: function(form){
                  Swal.fire({
                  title: '<strong>¿Desea asignar el documento?</strong>',
                  text: "",
                  type: 'question',
                  showCancelButton: true,
                  showLoaderOnConfirm: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: '¡Si, Asignar!'
                  }).then((result) => {
                  if (result.value) {
                    $.ajax({
                    type: "POST",
                    url: "documentos/php/back/factura/asignar_fase.php",
                    dataType: 'json',
                    data: {
                      orden_id:$('#orden_id').val(),
                      valor:$('#id_campo').val(),
                      tipo:tipo
                    }, //f de fecha y u de estado.
                    beforeSend:function(){
                    },
                    success:function(data){
                      if(data.msg == 'OK'){
                        viewModelFacturaDetalle.getFactura();
                        instanciaF.recargarFacturas();
                        thisInstance.getPagoByFactura();
                        thisInstance.opc = 1;
                        $('#id_campo').val();
                        Swal.fire({
                          type: 'success',
                          title: 'Documento Asignado',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }else{
                        Swal.fire({
                          type: 'error',
                          title: 'Error',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }
                    }
                  }).done( function() {
                  }).fail( function( jqXHR, textSttus, errorThrown){
                    alert(errorThrown);
                  });
                }
              })
          },
          rules: {
            id_campo:{ required: true},
          }
        });
      }else{
        this.opc = 1;
      }
    },
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 1){
        eventBus.$emit('regresarPrincipal', 1);
      }
    }
  },
  created: function(){
    this.opc = this.option;
  }
})


Vue.component("factura-estado",{
  props: ["columna", "option","orden_id","factura"],
  template: `
  <div class="row">
    <div class="card-body">
      <div class="row">
        <div class="col-sm-6">
          <h3>Seguimiento de la factura</h3>
        </div>
        <div class="col-sm-6 text-right">
          <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-receipt"></i></span>
        </div>
      </div>
    </div>

    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue" >
        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
                </i><span class="text-white"> Asignar Técnico</span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              {{ factura.estado_factura }}
              <div class="row">
                <div class="col-sm-12" v-if="factura.estado_factura == 8337">
                  Entregar a Dirección
                  <button class="btn btn-soft-info" @click="saveBitacora(8338, 'Se entregó factura para razonamiento')"><i class="fa fa-check-circle"></i> Entregar a Dirección</button>
                  <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                </div>
                <div class="col-sm-12" v-if="factura.estado_factura == 8338">
                  Entregada a Dirección
                  Fase de Razonamiento

                  <button class="btn btn-soft-info" @click="saveBitacora(8339,'Se razonó la factura.')"><i class="fa fa-check-circle"></i> Factura razonada</button>
                  <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                </div>
                <div class="col-sm-12" v-if="factura.estado_factura == 8339">
                  Factura Publicada
                  Asignar Orden de Compra

                  <button class="btn btn-soft-info" @click="saveBitacora(8342, 'Se publicó factura en GUATECOMPRAS')"><i class="fa fa-check-circle"></i> Publicar factura</button>
                  <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                </div>

                <div class="col-sm-12" v-if="factura.estado_factura == 8339">
                  Factura Publicada
                  Asignar Orden de Compra

                  <button class="btn btn-soft-info" @click="saveBitacora(8342, 'Se publicó factura en GUATECOMPRAS')"><i class="fa fa-check-circle"></i> Publicar factura</button>
                  <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                </div>

              </div>
              <form class="jsValidacionAsignacionEmpleado">
                <div class="row">
                  <empleados-listado columna="col-sm-12" verificacion="9"></empleados-listado>
                  <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>


  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc: 1,
      tecnico:""
    }
  },
  methods:{
    getOpc: function(opc){

      this.opc = opc;
      if(opc == 1){
        eventBus.$emit('regresarPrincipal', 1);
      }
    },
    getEstadoFactura: function(){
      axios.get('documentos/php/back/factura/get_estado_factura', {
        params: {
          orden_id: this.orden_id
        }
      }).then(function (response) {
        this.tecnico = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    saveBitacora: function(estado,obs){
      var thisInstance = this;
      Swal.fire({
      title: '<strong>¿Desea operar la Factura?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      showLoaderOnConfirm: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, operar!'
      }).then((result) => {
      if (result.value) {
        $.ajax({
        type: "POST",
        url: "documentos/php/back/factura/action/save_estado.php",
        dataType: 'json',
        data: {
          orden_id:this.orden_id,
          estado:estado,
          obs:obs
        }, //f de fecha y u de estado.
        beforeSend:function(){
        },
        success:function(data){
          if(data.msg == 'OK'){
            eventBus.$emit('recargarFactura', 1);
            instanciaF.recargarFacturas();
            thisInstance.getOpc(1);
            eventBus.$emit('recargarBitacoraFactura',1);
            Swal.fire({
              type: 'success',
              title: 'Factura operada',
              showConfirmButton: false,
              timer: 1100
            });
          }else{
            Swal.fire({
              type: 'error',
              title: 'Error',
              showConfirmButton: false,
              timer: 1100
            });
          }
        }
      }).done( function() {
      }).fail( function( jqXHR, textSttus, errorThrown){
        alert(errorThrown);
      });
    }
  })

    },
    eventAccion: function(id){
      var thisInstance = this;
      if(id == 1){
        jQuery('.jsValidacionAsignacionEmpleado').validate({
            ignore: [],
            errorClass: 'help-block animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                var elem = jQuery(e);

                elem.closest('.form-group').removeClass('has-error').addClass('has-error');
                elem.closest('.help-block').remove();
            },
            success: function(e) {
                var elem = jQuery(e);

                elem.closest('.form-group').removeClass('has-error');
                elem.closest('.help-block').remove();
            },
            submitHandler: function(form){

                  Swal.fire({
                  title: '<strong>¿Desea asignar a la Factura?</strong>',
                  text: "",
                  type: 'question',
                  showCancelButton: true,
                  showLoaderOnConfirm: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonText: 'Cancelar',
                  confirmButtonText: '¡Si, Asignar!'
                  }).then((result) => {
                  if (result.value) {
                    $.ajax({
                    type: "POST",
                    url: "documentos/php/back/factura/asignar_factura.php",
                    dataType: 'json',
                    data: {
                      orden_id:$('#orden_id').val(),
                      id_persona:$('#id_empleados_list').val()
                    }, //f de fecha y u de estado.
                    beforeSend:function(){
                    },
                    success:function(data){
                      if(data.msg == 'OK'){
                        thisInstance.getTecnico();
                        viewModelFacturaDetalle.getFactura();
                        instanciaF.recargarFacturas();
                        thisInstance.getOpc(1);
                        Swal.fire({
                          type: 'success',
                          title: 'Persona asignada',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }else{
                        Swal.fire({
                          type: 'error',
                          title: 'Error',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }
                    }
                  }).done( function() {
                  }).fail( function( jqXHR, textSttus, errorThrown){
                    alert(errorThrown);
                  });
                }
              })
          },
          rules: {
            id_campo:{ required: true},
          }
        });
      }else{
        this.opc = 1;
      }
    }
  },
  created: function(){
    //this.getEstadoFactura();
    this.opc = this.option;
  }
})

Vue.component("factura-operar",{
  props: ["privilegio", "factura", "tipo", "arreglo", "tipo_operar"],
  template: `
    <!--inicio-->
    <div class="" v-if="tipo == 1">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <!--<li class="nav-item">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#torden" role="tab" aria-controls="drespaldo" aria-selected="true">Asignar Orden</a>
        </li>-->
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#tcur1" role="tab" aria-controls="tcur1" aria-selected="true" @click="asignarTipo(1)">CUR de compromiso</a>
        </li>
        <li class="nav-item">
          <a class="nav-link"  data-toggle="tab" href="#tcur2" role="tab" aria-controls="tcur2" aria-selected="false" @click="asignarTipo(2)">CUR devengado</a>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <br>
        <!--<div class="tab-pane fade show active" id="torden" role="tabpanel" aria-labelledby="home-tab">


        </div>-->
        <div class="tab-pane fade show active" id="tcur1" role="tabpanel" aria-labelledby="tcur1">
          <form class="jsValicacionCurCompromiso">
            <div class="row">
              <combo row="col-sm-6" label="Orden de compra:*" codigo="id_orden_c" :arreglo="ordenes" tipo="3" requerido="true"></combo>
              <campo row="col-sm-6" label="CUR compromiso:*" codigo="id_cur_c" tipo="number" requerido="true"></campo>
              <div class="col-sm-12 text-right">
                <button type="submit" class="btn-sm btn btn-info btn-block" @click="procesarCur(1,8346,'Se asignó CUR de compromiso')" value=''><i class="fa fa-check-circle"></i> Asignar CUR de compromiso</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane fade show" id="tcur2" role="tabpanel" aria-labelledby="tcur2">
          <form class="jsValicacionCurDevengado">
            <div class="row">
              <combo row="col-sm-6" label="Orden de compra:*" codigo="id_orden_d" :arreglo="ordenes" tipo="3" requerido="true"></combo>
              <campo row="col-sm-6" label="CUR Devengado:*" codigo="id_cur_d" tipo="number" requerido="true"></campo>
              <div class="col-sm-12 text-right">
                <button type="submit" class="btn-sm btn btn-info btn-block" @click="procesarCur(2,8348,'Se asignó CUR devengado')" value=''><i class="fa fa-check-circle"></i> Asignar CUR devengado</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div v-else-if="tipo == 2">
      <form class="jsValidacionClaseProceso" id="formValidacionClaseProceso">
      <input id="id_tipo_ope" name="id_tipo_ope" :value="tipo_operar" hidden></input>
      <input id="id_arreglo" name="id_arreglo" :value="arreglo" hidden></input>
        <div class="row">
          <!-- inicio -->
          <div class="col-sm-12" v-if="tipo_operar == 1">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_forma">Forma de pago</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="id_forma" name="id_forma" @change="validarAcreditamiento($event)" required>
                      <option value="">-- Seleccionar --</option>
                      <option value="3">Caja Chica</option>
                      <option value="1">Cheque</option>
                      <option value="2">Acreditacimiento</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <div class="col-sm-12" v-if="tipo_operar == 1 && tipoAcreditamiento == true || tipo_operar == 2">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_forma">Seleccionar clase de proceso</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control form-control-sm" style="width:100%" id="clase_proceso" name="clase_proceso" @change="validarProceso($event)" required>
                      <option value="">-- Seleccionar --</option>
                      <option value="1">Número de Orden</option>
                      <option value="2">CYD</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <campo v-if="vCheque == true" row="col-sm-12" label="Nro. de Cheque:*" codigo="nro_cheque" tipo="number" requerido="true"></campo>
          <campo v-if="vClaseProceso == 1" row="col-sm-12" label="Nro. de Orden:*" codigo="nro_orden" tipo="number" requerido="true"></campo>
          <campo v-if="vClaseProceso == 2" row="col-sm-12" label="Nro. de CYD:*" codigo="nro_cyd" tipo="number" requerido="true"></campo>
          <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
        </div>
      </form>


    </div>



    <!-- fin -->
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      ordenes:[],
      //tipo:1,
      nombre:'id_orden_c',
      nombreC:'id_cur_c',
      clase:'.jsValicacionCurCompromiso',
      tipoAcreditamiento:false,
      vCheque:false,
      vClaseProceso:''
    }
  },
  methods:{
    asignarTipo: function(opc){
      this.tipo = opc;
      console.log(this.tipo);

      this.getOrdenesPendientes();
      if(this.tipo == 1){
        this.nombre = 'id_orden_c';
        this.nombreC = 'id_cur_c';
        this.clase = '.jsValicacionCurCompromiso';
      }else
      if(this.tipo == 2){
        this.clase = '.jsValicacionCurDevengado';
        this.nombre = 'id_orden_d';
        this.nombreC = 'id_cur_d';
      }

    },
    validarAcreditamiento: function(event){
      var opcion = event.currentTarget.value;
      console.log(opcion);
      if(opcion == 2){
        this.tipoAcreditamiento = true;
      }else{
        this.tipoAcreditamiento = false;
      }

      if(opcion == 1){
        this.vClaseProceso = '';
        this.vCheque = true;
      }else{
        this.vCheque = false;
      }
    },
    validarProceso: function(event){
      var opcion = event.currentTarget.value;
      console.log(opcion);
      if(opcion != ''){
        this.vClaseProceso = opcion;
      }else{
        this.vClaseProceso = '';
      }
    },
    procesarCur: function(tipo,estado,obs){
      var orden = $('#'+this.nombre).val();
      var cur = $('#'+this.nombreC).val();
      jQuery(this.clase).validate({
          ignore: [],
          errorClass: 'help-block animated fadeInDown',
          errorElement: 'div',
          errorPlacement: function(error, e) {
              jQuery(e).parents('.form-group > div').append(error);
          },
          highlight: function(e) {
              var elem = jQuery(e);

              elem.closest('.form-group').removeClass('has-error').addClass('has-error');
              elem.closest('.help-block').remove();
          },
          success: function(e) {
              var elem = jQuery(e);

              elem.closest('.form-group').removeClass('has-error');
              elem.closest('.help-block').remove();
          },
          submitHandler: function(form){
              //regformhash(form,form.password,form.confirmpwd);
                Swal.fire({
                title: '<strong>¿Desea procesar el número de orden de compra?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Procesar!'
                }).then((result) => {
                if (result.value) {
                  //alert(vt_nombramiento);
                  $.ajax({
                  type: "POST",
                  url: "documentos/php/back/factura/action/operar_factura.php",
                  dataType: 'json',
                  data: {
                    tipo:tipo,
                    orden:orden,
                    cur:cur,
                    estado:estado,
                    obs:obs

                    //pedido_interno:$('#id_pedido_interno').val()
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){
                      $('#cambio').val('1');
                      //instancia.recargarFacturas();

                      if(tipo == 1){
                        instanciaF.recargarFacturas(1,'btn-f2');
                      }else{
                        instanciaF.recargarFacturas(2,'btn-f3');
                      }
                      $('#modal-remoto').modal('hide');
                      //viewModelFacturaDetalle.getFactura();
                      Swal.fire({
                        type: 'success',
                        title: 'Factura generada',
                        showConfirmButton: false,
                        timer: 1100
                      });

                    }else{
                      Swal.fire({
                        type: 'error',
                        title: 'Error',
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }

                  }

                }).done( function() {


                }).fail( function( jqXHR, textSttus, errorThrown){

                  alert(errorThrown);

                });

              }
            })
        }
      });
    },
    eventAccion: function(id){
      if(id == 1){
        this.procesarClase();
      }else{
        $('#modal-remoto').modal('hide');
      }
    },
    procesarClase: function(){
      jQuery('.jsValidacionClaseProceso').validate({
          ignore: [],
          errorClass: 'help-block animated fadeInDown',
          errorElement: 'div',
          errorPlacement: function(error, e) {
              jQuery(e).parents('.form-group > div').append(error);
          },
          highlight: function(e) {
              var elem = jQuery(e);

              elem.closest('.form-group').removeClass('has-error').addClass('has-error');
              elem.closest('.help-block').remove();
          },
          success: function(e) {
              var elem = jQuery(e);

              elem.closest('.form-group').removeClass('has-error');
              elem.closest('.help-block').remove();
          },
          submitHandler: function(form){
              //regformhash(form,form.password,form.confirmpwd);
              var form = $('#formValidacionClaseProceso');

                Swal.fire({
                title: '<strong>¿Desea procesar procesar las facturas seleccionadas?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Procesar!'
                }).then((result) => {
                if (result.value) {
                  //alert(vt_nombramiento);
                  $.ajax({
                  type: "POST",
                  url: "documentos/php/back/factura/action/asignar_fase_global.php",
                  dataType: 'json',
                  data: form.serialize(),
                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){
                      $('#cambio').val('1');
                      //instancia.recargarFacturas();
                      instanciaF.tipoBajaCuantia = 0;
                      instanciaF.tipoNog = 0;
                      instanciaF.recargarFacturas(0,'btn-f2');
                      $('#modal-remoto').modal('hide');
                      //viewModelFacturaDetalle.getFactura();
                      Swal.fire({
                        type: 'success',
                        title: 'Facturas operadas',
                        showConfirmButton: false,
                        timer: 1100
                      });

                    }else{
                      Swal.fire({
                        type: 'error',
                        title: 'Error',
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                  }
                }).done( function() {
                }).fail( function( jqXHR, textSttus, errorThrown){
                  alert(errorThrown);
                });
              }
            })
        }
      });
    },
    getOrdenesPendientes: function() {
      axios.get('documentos/php/back/factura/listados/get_ordenes_pendientes', {
        params: {
          tipo:this.tipo
        }
      }).then(function (response) {

        this.ordenes = response.data;
        console.log(this.ordenes);
        $("#"+this.nombre).select2({});
      }.bind(this)).catch(function (error) {
        console.log(error);
      });

    }
  },
  created: function(){
    this.getOrdenesPendientes();
  }
})



// functions
Vue.component("privilegios-user",{
  props: [],
  template: `

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      privilegio:""
    }
  },
  methods:{
    getPrivilegio: function() {
      axios.get('documentos/php/back/functions/evaluar_privilegio', {
        params: {

        }
      }).then(function (response) {
        this.privilegio = response.data;
        this.$emit('privilegio_user', response.data);
        //console.log(this.privilegio);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getPrivilegio();
  }
})
