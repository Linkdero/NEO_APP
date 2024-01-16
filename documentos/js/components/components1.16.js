Vue.component("direcciones",{
  props: ["columna","tipo"],
  template: `
  <!--inicio-->
  <div :class="columna">
  <combo-change :row="columna" label="Seleccionar dirección" codigo="id_direccion" :arreglo="direcciones" tipo="3" requerido="true"></combo-change>

  </div>
  <!-- fin -->
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      direcciones:[],
      direccion:""
    }
  },
  methods:{
    getDirecciones: function() {
      axios.get('documentos/php/back/listados/get_direcciones', {
        params: {

        }
      }).then(function (response) {
        this.direcciones = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getDireccionById: function(valor) {
      axios.get('documentos/php/back/functions/get_direccion_by_id', {
        params: {
          id_direccion:valor
        }
      }).then(function (response) {
        this.direccion = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getDirecciones();
    eventBus.$on('valorSeleccionado', (valor) => {
      if(this.tipo == 1){
        eventBus.$emit('obtenerDireccion', valor);
      }
      //this.getDireccionById(valor);
    });
  }
})

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

Vue.component("proveedores",{
  props: ["columna","codigo"],
  template: `
  <!--inicio-->
  <div :class="columna">
    <div class="form-group">
      <div class="">
        <div class="">
          <label :for="codigo">Proveedor*</label> <span class="btn btn-sm btn-soft-info badge" @click="getOpc(2)"><i class="fa fa-plus"></i></span>
          <div class=" input-group  has-personalizado">
            <select class="js-select2 form-control form-control-sm proveedor" :id="codigo" :name="codigo" required>

            </select>
          </div>
        </div>
      </div>
    </div>
    <div v-if="opc == 2">
      <div id="myModal" class="modal-vue" >

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
                </i><span class="text-white" v-if="opc == 2"> Proveedor</span><span class="text-white" v-else> Proveedor</span>
              </h4>
              <span style="margin-top:-2.2rem"class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionProveedorNuevo">
                <div class="row">
                  <!--inicio-->
                  <campo row="col-sm-12" label="NIT:*" codigo="proveedor_nit" tipo="text" requerido="true"></campo>
                  <campo row="col-sm-12" label="Razón Social:*" codigo="proveedor_nombre" tipo="text" requerido="true"></campo>
                  <!-- fin -->
                  <!--inicio-->
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_tipo_proveedor">Tipo*</label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm" style="width:100%" id="id_tipo_proveedor" name="id_tipo_proveedor" required>
                              <option value="">-- Seleccionar --</option>
                              <option value="1">Bien / Insumo</option>
                              <option value="2">Servicio</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin -->
                  <div class="col-sm-12 text-right">
                    <button class="btn btn-sm btn-info" @click="guardaProveedor()"><i class="fa fa-plus-circle"></i> Guardar</button>
                    <span class="btn btn-sm btn-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</span>

                  </div>
                </div>
              </form>
            </div>
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
      opc: 1,
      unidades:[]
    }
  },
  methods:{
    getOpc: function(opc){
      this.opc = opc;
    },
    proveedorFiltrado: function(){

      setTimeout(() => {
        $('.proveedor').select2({
          width: '100%',
          placeholder: 'Selecciona un proveedor',
          ajax: {
            url: 'documentos/php/back/proveedor/get_proveedor_filtrado.php',
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
        //$(".proveedor").select2({ width: '100%' });
      }, 1100);

    },
    guardaProveedor: function(){
      var thisInstance = this;
      jQuery('.jsValidacionProveedorNuevo').validate({
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
              //var insumos = viewModelPedidoDetalle.insumos;


                Swal.fire({
                title: '<strong>¿Desea guardar este Proveedor?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Generar!'
                }).then((result) => {
                if (result.value) {
                  //alert(vt_nombramiento);
                  $.ajax({
                  type: "POST",
                  url: "documentos/php/back/proveedor/crear_proveedor.php",
                  dataType: 'json',
                  data: {
                    proveedor_nit:$('#proveedor_nit').val(),
                    proveedor_nombre:$('#proveedor_nombre').val(),
                    id_tipo_proveedor:$('#id_tipo_proveedor').val()
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){
                      $('#proveedor_nit').val('');
                      $('#proveedor_nombre').val('');
                      $('#id_tipo_proveedor').val('');

                      thisInstance.getOpc(1);
                      Swal.fire({
                        type: 'success',
                        title: 'Proveedor guardado',
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
          id_tipo_proveedor:{ required: true},
          'proveedor_nit': {
            remote: {
              url: 'documentos/php/back/proveedor/validar_proveedor.php',
              data: {
                proveedor_nit: function(){ return $('#proveedor_nit').val();}
              }
            }
          }
        },
        messages: {
            'proveedor_nit': {
                remote: "Este NIT ya existe."
            }
        }

      });
    }
  },
  created: function(){
    this.proveedorFiltrado();
  }
})

Vue.component("pedidos-por-estado",{
  props: ["label","columna","verificacion","seleccionado","estado","id","multiple","is_multiple"],
  template: `
    <div :class="columna">
      <div class="form-group">
        <div class="">
          <div class="">
            <label :for="id">{{ label }}</label>
            <div class="input-group  has-personalizado">
              <select  v-if="is_multiple == 'false'" :id="id" :name="id" class="grupo_empleados js-select2 form-control form-control-sm input-sm pedidos_estado"  required style="width:100%" v-on:change="getValues">
                <option v-for="p in pedidos" :value="p.ped_tra">{{ p.ped_num  }}</option>
              </select>
              <select  v-else-if="is_multiple == 'true'" :id="id" :name="id+'[]'" class="grupo_empleados js-select2 form-control form-control-sm input-sm pedidos_estado" multiple required style="width:100%" v-on:change="getValues">
                <option v-for="p in pedidos" :value="p.ped_tra">{{ p.ped_num  }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <span v-if="info.length > 0" class="text-info ">Pedidos seleccionados: {{ info.length }} </span>
      <div v-if="info.length > 0" style="max-height:300px; overflow-y:auto; overflow-x:hidden" class="card">
        <br>

        <div v-for="i in info">
          <div class="col-sm-12">
            <h4>Pedido Nro. <strong>{{ i.ped_num }}</strong> - Fecha: <strong>{{ i.Ped_fec }}</strong></h4>
            <h6 style="text-align:justify">{{ i.Ped_obs}}</h6>
            <insumos :ped_tra="i.ped_tra" :pedido="pedido" clase="table-dark"></insumos>
          </div>
          <hr>
        </div>
      </div>
    </div>
  `,
  mounted() {
    //console.log('Component mounted.')
  },
  data(){
    return {
      pedidos:[],
      info:[],
      pedido:""
    }
  },
  methods:{
    getPedidosByEstado: function() {
      var thisInstance = this;
      setTimeout(() => {
        /*$('.pedidos_estado').select2({
          width: '100%',
          tags: true,
          maximumSelectionSize: 10,
          minimumResultsForSearch: Infinity,
          multiple: true,
          minimumInputLength: 1,
          placeholder: "Search Employee",
          //data:o,

          ajax: {
            url: 'documentos/php/back/pedido/listados/get_pedidos_estado_filtrado.php',
            allowClear: true,
            dataType: 'json',
            delay: 250,
            params: {
              contentType: "application/json"
            },
            id: function(i) {
              return i;
            },
            data: function(term, page){
              return{
                q: term, //search term
                    page_limit: 10, // page size
                    page: page, // page number
              }
            },
            results: function (data) {
              return {
                results: data
              };
            },
            cache: false
          }
        });
        $('#'+this.id).on('change', function() {//alert( this.value );
          var selected = $('#'+this.id).val();
          console.log(selected);
          thisInstance.getPedidosSeleccionados(selected);
        });
        //$(".proveedor").select2({ width: '100%' });*/
        axios.get('documentos/php/back/listados/get_pedidos_by_estado', {
          params: {
            estado:this.estado
          }
        }).then(function (response) {
          this.pedidos = response.data;

          //$(this.el).trigger("change");
          $("#"+this.id).select2({});
          $('#'+this.id).on('change', function() {//alert( this.value );
            var selected = $('#'+this.id).val();
            console.log(selected);
            thisInstance.getPedidosSeleccionados(selected);
          });
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }, 500);


    },
    getPedidosSeleccionados: function(array){

      axios.get('documentos/php/back/actas/listados/get_pedidos_seleccionados', {
        params: {
          array:JSON.stringify(array)
        }
      }).then(function (response) {
        this.info = response.data;

        //$(this.el).trigger("change");
        $("#"+this.id).select2({});
        $('#'+this.id).on('change', function() {//alert( this.value );
          var selected = $('#'+this.id).val();
          console.log(selected);
          setTimeout(() => {
            eventBusPE.$emit('buscarInsumos');
          }, 600);

        });
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getValues: function(){
      alert('message');
    }
  },
  created: function(){
    this.getPedidosByEstado();

  }
})



Vue.component("empleados-listado",{
  props: ["arreglo","columna","verificacion","seleccionado"],
  template: `
    <div :class="columna">
      <div class="form-group">
        <div class="">
          <div class="">
            <label for="id_ejercicio_ant">{{ label }}</label>
            <i v-if="loading == true" class="fa fa-sync fa-spin"></i>
            <div class="input-group  has-personalizado" >
              <select :id="id" :name="id" class="grupo_empleados js-select2 form-control form-control-sm input-sm" required style="width:100%">
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
      label:'',
      id:'',
      loading:false
    }
  },
  methods:{
    getEmpleadosPorDireccion: function() {
      this.loading = true;
      setTimeout(() => {
        this.empleados.length = 0;
        var url = '';
        var paramentro = '';
        if(this.verificacion == 7998){
          //directores para actas
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 7998;
          this.label = 'Seleccionar Director: ';
          this.id = 'id_director_financiero';
        }else
        if(this.verificacion == 7999){
          //jefe para actas
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 7999;
          this.label = 'Seleccionar jefe o jefa: ';
          this.id = 'id_jefes_compras';
        }else
        if(this.verificacion == 1010){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 311;
          this.label = 'Asignar empleado para revisión: ';
          this.id = 'id_empleados_list';
        }
        else if(this.verificacion == 9 || this.verificacion == 10 || this.verificacion == 11 || this.verificacion == 12){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 302;
          this.label = 'Asignar técnico: ';
          this.id = 'id_empleados_list';
        }else{
          this.label = 'Seleccionar empleado: ';
          this.empleados = [];
          this.empleados = this.arreglo.id_direccion;
          url = 'documentos/php/back/listados/get_empleados_por_direccion';
          parametro = this.empleados;
          this.id = 'id_empleados_list';
        }
        axios.get(url, {
          params: {
            id_direccion:parametro
          }
        }).then(function (response) {
          this.empleados = response.data;
          this.loading = false;

          $("#id_empleados_list").select2({
            placeholder: "-Seleccionar-",
            allowClear: true
          });
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }, 1100);
    }
  },
  created: function(){
    this.getEmpleadosPorDireccion();

    eventBus.$on('recargarListadoDeEmpleados', (valor) => {
      this.getEmpleadosPorDireccion();
    });
  }
})

Vue.component("pedido", {
  props: ["ped_tra"],
  template: `
  <div class="col-sm-12">

    <div class="row">
      <div class="col-sm-3" style="min-height:150px; border-right:2px dashed #F2F1EF;">
        <div class="row">
          <div class="col-sm-6">
            <small class="text-muted">Pedido No. </small>
             <h5><strong>{{pedido.pedido_num}}</strong></h5>
          </div>
          <div class="col-sm-6">
             <small class="text-muted">Fecha: </small>
              <h5 class="ped_fecha" :data-pk="pedido.ped_tra" data-name="Ped_fec" ><strong>{{pedido.fecha}}</strong></h5>
          </div>
          <div class="col-sm-12">
            <hr>
            <div class="row">
              <porcentaje-pedido :ped_tra="ped_tra"></porcentaje-pedido>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6" style="min-height:150px; border-right:2px dashed #F2F1EF;">
        <div class="col-sm-12">
          <small class="text-muted">Observaciones: </small>
         <h5  style="text-align:justify" class="ped_descripcion" :data-pk="pedido.ped_tra" data-name="Ped_obs" ><strong>{{pedido.observaciones}}</strong></h5>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="">
          <div class="col-sm-12">
          <h5>Creado por:</h5>
            <h5>{{ pedido.empleado }}</h5>
          </div>
          <div class="col-sm-12" style="z-index:1">
            <fotografia-empleado :id_persona="idPersona"   tipo="1"></fotografia-empleado>
          </div>
        </div>
      </div>
    </div>
    <hr>
  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      pedido:"",
      idPersona:""
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
        this.idPersona = response.data.id_persona;
        this.$emit('event_child', response.data)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });

    },
    setEditable: function(){
      setTimeout(() => {
        if(this.pedido.creador == true && this.pedido.tiempo == true && this.pedido.poder_editar == true){
        //if(this.pedido.tiempo == true && this.pedido.poder_editar == true){
          $('.ped_fecha').editable({
            url: 'documentos/php/back/pedido/action/update_pedido.php',
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
              if(response.msg=='OK'){
                console.log(response.valor_nuevo);
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
                console.log(response.valor_nuevo);
                $(this).text(response.valor_nuevo);
                //instancia.reloadTable();
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

        }else{
          $('.ped_fecha').data('type', 'date').editable('destroy');
          $('.ped_descripcion').data('type', 'textarea').editable('destroy');


        }
      }, 800);
    }
  },
  created: function(){
    this.getPedido();
    this.setEditable();
    eventBusPD.$on('recargarPedido', (opc) => {
      console.log('Works!!!');
      this.getPedido();
      this.setEditable();
    });
  }
});

Vue.component("pedido-notificaciones",{
  props: [],
  template: `
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {

    }
  },
  methods:{
    showNotificacion: function() {
      var isPushEnabled = false;



      Push.create("Control de Pedidos",{
        body: 'mensaje',
        icon: "assets/svg/mockups/LOGO_SAAS.png",
        timeout: 85000,
        onClick: function () {
          alert('message');
        }
      });
    }
  },
  created: function(){
    this.showNotificacion();
  }
})

Vue.component("documentos-respaldo-pedido", {
  props: ["ped_tra","verificacion","pedido","privilegio"],
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
              <span v-if="doctoActual.id_status == 3 && pedido.creador == true && pedido.tiempo == true" class="btn btn-sm btn-soft-info" @click="enviarCorreo"><i class="fa fa-arrow-left"></i></span>
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
                    <td class="text-center"><span v-if="d.id_status == 0 && privilegio.plani_au == true" @click="revisarDocumento(d.reng_num, 4)" class="btn btn-sm btn-soft-info"><i class="fa fa-check"></i></span></td>
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
    enviarCorreo: function(){
      sendMail('hugo.delacruz@saas.gob.gt','hugo.delacruz@saas.gob.gt','Mensaje de prueba');
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
                      title: data.message,
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
    saveValidacion: function(ped_tra, reng_num, id){

      var thisInstance = this;
      var msg = (id == 2) ? 'Aprobar' : 'Anular';
      var color = (id == 2) ? '#28a745' : '#d33';

      var emisor = 'hugo.delacruz@saas.gob.gt';
      var receptor = 'hugo.delacruz@saas.gob.gt';
      var body = (id == 2) ? 'Se aprobó su documento de respaldo' : 'Se anuló se documento de respaldo';

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

                sendMail(data.mail.emisor,data.mail.receptor,data.mail.body, data.mail.subject );
                Swal.fire({
                  type: 'success',
                  title: data.message,
                  showConfirmButton: false,
                  timer: 1100
                });
              } else {
                Swal.fire({
                  type: 'error',
                  title: data.id,
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
    },
    aprobarDocumento: function(reng_num,id){
      var ped_tra = this.ped_tra;
      var thisInstance = this;
      var x = id;

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

        }
      })

      if($('#docto_obs').val() != ''){
        thisInstance.saveValidacion(ped_tra, reng_num, x);
      }
    },
  },
  created: function(){
    setTimeout(() => {
      this.getDocumentosRespaldo();
      this.mostrarDocumento();
      impresion_pedido(this.ped_tra,2);
    }, 1000);


  }
});

Vue.component("acta-pedido", {
  props: ["ped_tra","verificacion","pedido","privilegio"],
  template: `
      <div v-if="privilegio.compras_tecnico == true || privilegio.compras_asignar_tecnico == true">

            <form class="jsValidacionActaPedido" action='' method='' enctype='' id="formValidacionActaPedido">
              <input type="text" id="ped_tra" name="ped_tra" :value="ped_tra" hidden></input>
              <div class="row">
              <empleados-listado columna="col-sm-6" verificacion="7998"></empleados-listado>
              <empleados-listado columna="col-sm-6" verificacion="7999"></empleados-listado>
                <div class="col-sm-6" >
                  <div class="form-group">
                    <div class="">
                      <div class="">
                        <label for="id_tipo_pago">Tipo de Pago*</label>
                        <div class=" input-group  has-personalizado" >
                          <select class="form-control form-control-sm" style="width:100%" id="id_tipo_pago" name="id_tipo_pago" required>
                            <option value="">-- Seleccionar --</option>
                            <option value="1">Cheque</option>
                            <option value="2">Acreditamiento</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <campo row="col-sm-6" label="Fecha del Acta:*" codigo="id_fecha_acta" tipo="datetime-local" requerido="true"></campo>
                <campo row="col-sm-6" label="Monto:*" codigo="id_monto" tipo="number" requerido="true"></campo>
                <combo row="col-sm-6" label="Tipo de adjuticación:*" codigo="id_tipo_seleccion" :arreglo="arregloTipo" tipo="3" requerido="true"></combo>
                <combo row="col-sm-6" label="Tipo de compra:*" codigo="id_tipo_compra" :arreglo="arregloTipoAdj" tipo="3" requerido="true"></combo>
                <proveedores columna="col-sm-6" codigo="id_proveedor"></proveedores>

                <pedidos-por-estado estado="8148" columna="col-sm-12" id="id_ped_tra" label="Pedidos en cotización" is_multiple="true"></pedidos-por-estado>
                <campo row="col-sm-12" label="Observaciones:*" codigo="docto_obs" tipo="textarea" requerido="true"></campo>
                <div class="col-sm-12">
                  <button class="btn-sm btn btn-info btn-block" @click="generarActa()" value=''><i class="fa fa-check-circle"></i> Generar Acta</button>
                </div>
                <div class="col-sm-6">
                </div>
              </div>
            </form>
          </div>
          <!--<div v-else>
          </div>
        </div>
        <div v-else>
          El PYR necesita estar en fase de cotización para generar el acta.
        </div>-->
      </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      acta:'',
      opc:1,
      tecnico:"",
      arregloTipo:
      [
        {
          'id_item':'',
          'item_string':'-- Seleccionar --'
        },
        {
          'id_item':'1',
          'item_string':'ofrecer mejor calidad'
        },
        {
          'id_item':'2',
          'item_string':'ofrecer mejor tiempo de entrega'
        },
        {
          'id_item':'3',
          'item_string':'ofrecer mejor calidad y tiempo de entrega'
        },
        {
          'id_item':'4',
          'item_string':'es el único'
        },
      ],
      arregloTipoAdj:
      [
        {
          'id_item':'',
          'item_string':'-- Seleccionar --'
        },
        {
          'id_item':'1',
          'item_string':'Compra'
        },
        {
          'id_item':'2',
          'item_string':'Servicio'
        }
      ]
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
    getTecnicoAsginado: function(){
      axios.get('documentos/php/back/pedido/get_persona_asignada', {
        params: {
          ped_tra:this.ped_tra
        }
      }).then(function (response) {
        this.tecnico = response.data;
        //this.verificacion = response.data.verificacion;
        //alert(this.verificacion);
      }).catch(function (error) {
        console.log(error);
      });
    },
    generarActa: function(){
      var thisInstance = this;
      jQuery('.jsValidacionActaPedido').validate({
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
            title: '<strong>¿Desea generar el Acta?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              var formData = new FormData($("#formValidacionActaPedido")[0]);
              $.ajax({
                type: "POST",
                url: "documentos/php/back/actas/action/crear_acta.php",
                method:"POST",
                dataType:"json",
                data:formData,
                contentType:false,
                processData:false,
                beforeSend: function () {
                },
                success: function (data) {
                  if (data.msg == 'OK') {
                    instancia.recargarTableActas();
                    $('#modal-remoto').modal('hide');
                    //imprimirActa();
                    /*thisInstance.opc = 1;
                    thisInstance.mostrarDocumento();
                    thisInstance.getDocumentosRespaldo();*/
                    Swal.fire({
                      type: 'success',
                      title: 'Acta generada',
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
    //this.getTecnicoAsginado();
    /*setTimeout(() => {
      this.getDocumentosRespaldo();
      this.mostrarDocumento();
      impresion_pedido(this.ped_tra,2);
    }, 1000);*/


  }
});

Vue.component("porcentaje-pedido", {
  props: ["ped_tra"],
  template: `
  <div class="col-sm-12" v-if="porcentaje.estado != ''">
    <div class="row">
      <div class="col-sm-12 text-left">
        <div style="margin-top:0px; ">
          <span class="badge-sm" :class="porcentaje.texto">{{ porcentaje.estado }}<br>{{ porcentaje.verificacion }}</span>
          <div class="progress progress-striped skill-bar " style="height:6px">
            <div class="progress-bar animated" :class="porcentaje.bg" role="progressbar" :aria-valuenow="porcentaje.valor" aria-valuemin="0" aria-valuemax="100" :style="{width: [percent]}"></div>
          </div>
        </div>

      </div>
      <div class="col-sm-12" v-if="porcentaje.ped_status == 8141 || porcentaje.ped_status == 8144 || porcentaje.ped_status == 9108 || porcentaje.ped_status == 9109">
        Motivo: <br>
        {{ obsRechazo.observaciones }}
        <br>

      </div>
      <hr>
    </div>

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
      percent:"",
      obsRechazo : ""
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

        if(this.porcentaje.ped_status == 8141 || this.porcentaje.ped_status == 8144 || this.porcentaje.ped_status == 9108 || this.porcentaje.ped_status == 9109){
          axios.get('documentos/php/back/pedido/detalle/get_motivo_rechazo', {
            params: {
              ped_tra:this.ped_tra
            }
          }).then(function (response) {
            this.obsRechazo = response.data;
          }.bind(this)).catch(function (error) {
            console.log(error);
          });
        }
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
  props: ["ped_tra","tipo", "clase", "pedido"],
  template: `
  <div class="row">
  <!--{{ pedido }}-->
    <div class="col-sm-12" v-if="pedido.creador == true && pedido.tiempo == true && pedido.poder_editar == true">
      <div class="row">
        <!--inicio-->
        <div class="col-sm-10">
          <div class="form-group">
            <div class="">
              <div class="">
                <label for="id_pedido">Insumo</label> <span class="btn btn-sm btn-soft-info" @click="filtrado"><i class="fa fa-sync"></i> Recargar Filtro</span> Utilizar este botón si no le deja filtrar
                <div class=" input-group  has-personalizado">
                  <select class="categoryName_ form-control" style="width:100%" id="Ppr_id_" name="categoryName_"></select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- fin -->
        <!--inicio-->
        <div class="col-sm-2">
          <div class="form-group">
            <div class="form-material">
              <label>Agregar insumo</label>
              <span class="btn btn-sm btn-soft-info btn-block" @click="addNewRow()"><i class="fa fa-plus-circle"></i> Agregar</span>
            </div>

          </div>

        </div>
        <!-- fin -->
      </div>
    </div>
    <div class="col-sm-12">
    <!--lineas: {{ linesTotal}}-->
    <input id="id_total_insped" :value="linesTotal" hidden></input>
      <porcentaje-insumos-pedido :total="linesTotal"></porcentaje-insumos-pedido>
    </div>
    <div class="col-sm-12">
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
          <th class="text-center" style="width:15px">Acción</th>
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
            <td scope="row" class="trashIconContainer text-center">
              <span v-if="pedido.creador == true && pedido.tiempo == true  && pedido.poder_editar == true" class="btn btn-sm btn-danger" @click="deleteRow(i.Ppr_id)"><i class="far fa-trash-alt"></i></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      insumos:[],
      totalOcupado: 0
    }
  },
  computed: {
    linesTotal: function() {
      if (!this.insumos) {
        return 0;
      }
      return this.insumos.reduce(function (total, value) {
        return total + Number(value.lineas);
      }, 0);
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
        eventBusPE.$emit('recargarPorcentajeTotal',1);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    totalLineasSum() {
      this.totalLineas = 0;
      this.totalLineas = this.insumos.reduce((a, b) => {
        return a + Number(b['lineas']);
      }, 0);

      console.log(this.totalLineas);
    },
    deleteRow(ppr_id) {
      var thisInstance = this;
      var pedTra = this.ped_tra;

      Swal.fire({
        title: '<strong>¿Quiere eliminar este insumo del Pedio ?</strong>',
        //text: "Ingrese la cantidad.",
        type: 'question',
        //input: 'number',
        //inputPlaceholder: 'Eliminar',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, eliminar!',
      }).then((result) => {
        //alert('2222');
        if (result.value) {
          //alert('alkdjflaksdf');
          $.ajax({
            type: "POST",
            url: 'documentos/php/back/pedido/action/delete_insumo',
            dataType: 'json',
            data: {
              ped_tra:pedTra,
              ppr_id:ppr_id
              //id_persona:$('#id_empleados_list').val()
            }, //f de fecha y u de estado.

            beforeSend:function(){
              //alert('p: '+punto);
            },
            success:function(data){
              if(data.msg == 'OK'){
                Swal.fire({
                  type: 'success',
                  title: 'Insumo eliminado',
                  showConfirmButton: false,
                  timer: 1100
                });
                thisInstance.getInsumos();
                eventBusPE.$emit('recargarPorcentajeTotal',1);
              }else{
                Swal.fire({
                  type: 'error',
                  title: data.id,
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
    addNewRow() {
      var thisInstance = this;
      var pedTra = this.ped_tra;
      var lineaInsumo = 0;
      if ($('#Ppr_id_').val() != null) {
        axios.get('documentos/php/back/pedido/get_insumo_seleccionado', {
          params: {
            Ppr_id: $('#Ppr_id_').val()
          }
        }).then(function (response) {
          console.log(response.data);
          lineaInsumo = response.data.lineas;
          var punto = 0;
            Swal.fire({
              title: '<strong>¿Quiere agregar este insumo al Pedio ?</strong>',
              text: "Ingrese la cantidad.",
              type: 'question',
              input: 'number',
              inputPlaceholder: 'Agregar',
              showCancelButton: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, Agregar!',
              inputValidator: function (inputValue) {
                return new Promise(function (resolve, reject) {
                  if (inputValue && inputValue.length > 0) {
                    resolve();
                    punto = inputValue;
                  } else {
                    Swal.fire({
                      type: 'error',
                      title: 'Debe ingresar la cantidad',
                      showConfirmButton: false,
                      timer: 1100
                    });

                  }
                });
              }
            }).then((result) => {
              //alert('2222');
              if (result.value) {
                //alert('alkdjflaksdf');
                $.ajax({
                  type: "POST",
                  url: 'documentos/php/back/pedido/action/add_insumo',
                  dataType: 'json',
                  data: {
                    ped_tra:pedTra,
                    ppr_id:$('#Ppr_id_').val(),
                    total_lineas: $('#id_total_insped').val(),
                    lineas_insumo: lineaInsumo,
                    cantidad:punto
                    //id_persona:$('#id_empleados_list').val()
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                    //alert('p: '+punto);
                  },
                  success:function(data){
                    if(data.msg == 'OK'){
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                      thisInstance.getInsumos();
                      eventBusPE.$emit('recargarPorcentajeTotal',1);
                      $('.categoryName_').val('');
                      $('.categoryName_').val(null).trigger('change');
                    }else{
                      Swal.fire({
                        type: 'error',
                        title: data.message,
                        showConfirmButton: true,
                        //timer: 1100
                      });
                    }

                  }
                }).done( function() {

                }).fail( function( jqXHR, textSttus, errorThrown){
                  alert(errorThrown);

                });




              }
            })
            //viewModelPedido.insumos = response.data;

          //}


        }).catch(function (error) {
          console.log(error);
        });
      } else {
        Swal.fire({
          type: 'error',
          title: 'Debe seleccionar un insumo',
          showConfirmButton: false,
          timer: 1100
        });
      }


    },
    filtrado: function () {
      setTimeout(() => {
        $('.categoryName_').select2({
          placeholder: 'Selecciona un insumo',
          ajax: {
            url: 'documentos/php/back/pedido/get_insumo_filtrado.php',
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
      }, 1000);
    }
  },
  created: function(){
    this.getInsumos();
    this.filtrado();

    eventBusPE.$on('buscarInsumos', () =>{
      this.getInsumos();
    })
  }
});


Vue.component("seguimiento-list", {
  props: ["ped_tra","verificacion"],
  template: `
  <div class="row">
    <div class="col-sm-12" v-if="verificacion != 7777">
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
                <input :id="'chk'+s.ped_seguimiento_id" :name="'chk'+s.ped_seguimiento_id" :value="s.ped_seguimiento_id" @click="establecerPedidoVerificacion(8139,$event)" v-model="s.checked" type="checkbox"/><span></span>
              </label>
            </td>

            <!--<td class="text-center">{{i.Pedd_can}}</td>-->
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-sm-12 slide_up_anim text-right" v-if="cch1 >= 1 && verificacion == 2">
      <span class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8140,1)"><i class="fa fa-check"></i> Aprobar</span>
      <span class="btn btn-danger btn-sm btn-estado" @click="asignarEstadoPedido(8141,2)"><i class="fa fa-times"></i> Rechazar</span>
    </div>

    <div class="col-sm-12 slide_up_anim text-right" v-if=" verificacion == 6">
      <span class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8143,1)" v-if="cch1 >= 1 "><i class="fa fa-check"></i> Aprobar</span>
      <span class="btn btn-warning btn-sm btn-estado" @click="asignarEstadoPedido(9108,2)"><i class="fa fa-check"></i> Rechazar para corrección</span>
      <span class="btn btn-danger btn-sm btn-estado" @click="asignarEstadoPedido(8144,2)"><i class="fa fa-times"></i> Anular</span>
    </div>
    <div class="col-sm-12 slide_up_anim text-right" v-if=" verificacion == 7777">
      <span class="btn btn-warning btn-sm btn-estado" @click="asignarEstadoPedido(9108,2)"><i class="fa fa-check"></i> Rechazar para corrección</span>
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
      var chequeado = ( $('#chk'+id).is(':checked') )?1:0;
      if( $('#chk'+id).is(':checked') ){
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
     <div class="col-sm-12" v-if="verificacion == 1 || verificacion == 1010 || verificacion == 3 || verificacion == 4 || verificacion == 5 || verificacion == 7 || verificacion == 8 || verificacion == 9 || verificacion == 11 || verificacion == 12">
        <h2>{{ titulo }}</h2>
        <div class="row">
          <div class="col-sm-6">

            <select id="id_empleados_list" class="grupo_empleados js-select2 form-control form-control-sm input-sm" style="width:100%">
              <option v-for="e in empleados" :value="e.id_persona">{{ e.empleado }}</option>
            </select>
          </div>
          <div class="col-sm-6 text-right">
            <span v-if="verificacion == 1 || verificacion == 4"class="btn btn-info btn-sm" @click="asignarEstadoPedido(8156,1)"><i class="fa fa-check"></i> Recibir</span>
            <span v-if="verificacion == 1010" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8139,1)"><i class="fa fa-check"></i> Asignar para revisión</span>
            <span v-if="verificacion == 3" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8157,1)"><i class="fa fa-check"></i> Devolver </span>
            <span v-if="verificacion == 5"class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8160,1)"><i class="fa fa-check"></i> Recibir</span>
            <span v-if="verificacion == 7"class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8161,1)"><i class="fa fa-check"></i> Devolver </span>
            <span v-if="verificacion == 7"class="btn btn-danger btn-sm btn-estado"><i class="fa fa-times"></i> Anular</span>
            <span v-if="verificacion == 8" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8164,1)"><i class="fa fa-check"></i> Recibir</span>
            <!--<span class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Anular</span>-->
            <span v-if="verificacion == 9 || verificacion == 11 || verificacion == 12" class="btn btn-info btn-sm btn-estado" @click="asignarEstadoPedido(8145,1)"><i class="fa fa-check"></i> Asignar y Cotizar</span>
            <span v-if="verificacion == 9 || verificacion == 11 || verificacion == 12" class="btn btn-danger btn-sm btn-estado" @click="asignarEstadoPedido(8147,2)"><i class="fa fa-times"></i> Anular</span>
            <span v-if="verificacion == 9 || verificacion == 11 || verificacion == 12" class="btn btn-warning btn-sm btn-estado" @click="asignarEstadoPedido(9109,2)"><i class="fa fa-check"></i> Solicitar a SAA token para rechazar</span>
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
        if(this.verificacion == 1010){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 311;
          this.label = 'Asignar empleado para revisión: ';
        }
        else if(this.verificacion == 9 || this.verificacion == 10 || this.verificacion == 11 || this.verificacion == 12){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 302;
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
          sendMail(data.emisor, data.receptor, data.body, data.subject)
          /*if(data.msg == 'OK'){
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
          console.log(data);*/
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
                    $('#modal-remoto-lgg2').modal('hide');
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
  props: ['columna','valor'],
  template: `

  <combo :row="columna" label="Renglón:*" codigo="id_renglon" :arreglo="renglones" tipo="3" :valor="valorId"></combo>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      renglones:[],
      valorId:""
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
    setTimeout(() => {
      this.valorId = this.valor;
      if(this.valor.length > 0){
        $('#id_renglon').val(this.valorId).trigger('change');
      }
    }, 900);

    this.getRenglones();
  }
})

Vue.component("meses-listado",{
  props: ['columna', "label","tipo"],
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
                <input :name="'txt'+index + tipo" :id="'txt'+index + tipo" class="form-control input-sm" min="1" v-model="m.cantidad" type="number" autocomplete="off" required></input>
              </div>
            </div>
          </div>
        </td>
        <td style="width:50px">
          <div class="form-group" style="margin-bottom:0rem" v-if="m.checked == true" >
            <div class="">
              <div class="">
                <input :name="'m'+index + tipo" :id="'m'+index + tipo" class="form-control input-sm" min="1" v-model="m.monto" type="number" autocomplete="off" required></input>
              </div>
            </div>
          </div>
        </td>
        <td class="text-center" width="10px">
          <input class="tgl tgl-flip text-center" :id="'ck'+m.id_mes + tipo" :name="'ck'+m.id_mes + tipo" type="checkbox" v-on:change="setMes(m.id)" v-model="m.checked"/>
          <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="'ck'+m.id_mes + tipo" ></label>
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

Vue.component("pac-detalle-listado", {
  props: ["pac_id","columna"],
  template: `
  <div :class="columna">
    <div class="el-wrapper" v-for="(pac, index) in planes" :key="index">
      <div class="row">
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-12">
              <small class="text-muted">Nombre del Plan </small>
               <h5><strong class="descripcion_" :data-pk="pac.pac_id + index" data-name="pac_nombre" >{{pac.nombre}}</strong></h5>
            </div>

          </div>

        </div>
        <div class="col-sm-6">
           <small class="text-muted">Renglón: </small>
            <h5><strong class="renglones" :data-pk="pac_id + index" data-name="pac_renglon">{{pac.renglon}}</strong></h5>
            <h5><strong >{{pac.renglon_nm}}</strong></h5>
        </div>
        <div class="col-sm-12">
          <small class="text-muted">Detalle del Plan: </small>
          <h5 style="text-align:justify"><strong class="descripcion_" :data-pk="pac_id" data-name="pac_detalle">{{pac.detalle}}</strong></h5>
        </div>
        <div class="col-sm-12">
          <small class="text-muted">Dirección: </small>
          <h5 style="text-align:justify"><strong>{{pac.direccion}}</strong></h5>
        </div>
        <div class="col-sm-12" >
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label :for="'id_ejercicio_ant' + index">Ejercicio Anterior</label>
                    <div class="input-group  has-personalizado" >
                      <label class="css-input input-sm switch switch-success"><input :disabled="pac.disabled == true" :id="'id_ejercicio_ant' + index" :name="'id_ejercicio_ant' + index" type="checkbox" v-model="pac.anterior" @change="setEditable()"/><span></span> <span id="lbl_rdrecibido"><small>Eventos multianuales</small></span></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-3" v-if="pac.anterior == true">
              <br>
              <small class="text-muted">Ejercicio anterior: </small>
              <h5 style="text-align:justify"><strong class="descripcion_cor" :data-pk="pac_id" data-name="pac_ejercicio_ant">{{pac.pac_ejercicio_ant}}</strong></h5>
            </div>
            <div class="col-sm-4" v-if="pac.anterior == true">
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

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      pac:"",
      renglones:[],
      ejercicioAnterior:false,
      creador: false,
      planes:[]
    }
  },
  methods:{
    getPlan: function(pac_id){
      axios.get('documentos/php/back/pac/get_pac_by_array', {
        params: {
          pac_id:pac_id
        }
      }).then(function (response) {
        this.planes = response.data;
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
                      orden_id:$('#orden_id_').val(),
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
  props: ["columna", "option","orden_id", "privilegio","tipo"],
  template: `
  <div :class="columna">
    <div >
      <div class="row">
        <div class="col-sm-12">
          <h3>Detalle del Técnico</h3>
        </div>
        <div class="col-sm-6 text-right" v-if="privilegio.compras_asignar_tecnico == true">
          <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-user-plus"></i></span>
        </div>
      </div>
      <br>
      <div class="row" v-if="tipo == 1">
        <div class="col-sm-3" v-if="tecnico.id_persona > 0">
          <fotografia-empleado :id_persona="tecnico.id_persona"  style="margin-top:-25px;z-index:0; margin-left:-15px" tipo="1"></fotografia-empleado>
        </div>
        <div class="col-sm-9">
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
                    if(thisInstance.tipo == 1){
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
                    }else if(thisInstance.tipo == 2){
                      $.ajax({
                      type: "POST",
                      url: "documentos/php/back/factura/action/asignar_tecnico_global.php",
                      dataType: 'json',
                      data: {
                        orden_id:$('#orden_id').val(),
                        id_persona:$('#id_empleados_list').val()
                      }, //f de fecha y u de estado.
                      beforeSend:function(){
                      },
                      success:function(data){
                        if(data.msg == 'OK'){
                          instanciaF.recargarFacturas();
                          //thisInstance.getOpc(1);
                          $('#modal-remoto-lg').modal('hide');
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
    if(this.tipo == 1){
      this.getTecnico();
      this.opc = this.option;
    }

  }
})


Vue.component("asignar-modalidad-compra",{
  props: ["columna", "option", "factura", "privilegio"],
  template: `
  <div :class="columna">
  {{ factura.tecnico_au }}
    <div >
      <div class="row">
        <div class="col-sm-6">
          <h3>Detalle del pago</h3>
        </div>
        <!--<div class="col-sm-6 text-right">
          <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-file-invoice-dollar"></i></span>
        </div>-->
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
            <dato-persona v-if="factura.clase_proceso == 2" texto="COM-DEV:" :dato="factura.nro_orden" tipo="0"></dato-persona>
            <dato-persona v-if="factura.clase_proceso == 3" texto="C-YD:" :dato="factura.nro_orden" tipo="0"></dato-persona>
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
                    <h3 v-else-if="factura.forma_de_pago == 2 && factura.f_orden == true && factura.clase_proceso == 2">Agregar COM-DEV</h3>
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
                                <option value="2">COM-DEV</option>
                                <option value="3">COMDEV</option>
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
              <!--{{ factura.estado_factura }}-->
              <div class="row">
                <div class="col-sm-12" v-if="factura.estado_factura == 8337 || factura.estado_factura == 8342">
                  <form class="jsValidacionSaveBitacora" id="formValidacionSaveBitacora">
                    <input id="orden_id" name="orden_id" :value="orden_id" hidden></input>
                    <input id="id_estado" name="id_estado" value="8338" hidden></input>
                    <div class="row">
                    </div>
                    Entregar a Dirección
                      <direcciones tipo="1"></direcciones>
                      <empleados-listado :arreglo="direccion" verificacion="0"></empleados-listado>
                      <campo row="col-sm-12" label="Detallar que se entregó:*" codigo="obs" tipo="textarea" requerido="true"></campo>
                      <button class="btn btn-soft-info" @click="saveBitacora(8338, 'Se entregó factura para razonamiento')"><i class="fa fa-check-circle"></i> Entregar a Dirección</button>
                      <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                    </div>

                  </form>
                </div>
                <div class="col-sm-12" v-if="factura.estado_factura == 8338">
                  <form class="jsValidacionSaveBitacora" id="formValidacionSaveBitacora">
                    <div class="row">
                      Recibir la Factura
                      {{factura.direccion}} adfa fadf
                      <input id="orden_id" name="orden_id" :value="orden_id" hidden></input>
                      <input id="id_estado" name="id_estado" value="8341" hidden></input>
                      <empleados-listado :arreglo="factura.direccion" verificacion="0"></empleados-listado>
                      <button class="btn btn-soft-info" @click="saveBitacora(8341,'Se razonó la factura.')"><i class="fa fa-check-circle"></i> Recibir factura razonada</button>
                      <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                    </div>

                  </form>

                </div>


                <div class="col-sm-12" v-if="factura.estado_factura == 8341">
                  <div class="row">
                    <combo-change :row="columna" label="Seleccionar operación*" id="select_operacion" :arreglo="opcionesRecibido" tipo="3" requerido="true"></combo-change>
                    <form class="jsValidacionSaveBitacora" id="formValidacionSaveBitacora" v-if="valorRecibido == 2">
                      <input id="orden_id" name="orden_id" :value="orden_id" hidden></input>
                      <input id="id_estado" name="id_estado" value="8338" hidden></input>
                      <div class="row">
                      </div>
                      Entregar a Dirección

                        <empleados-listado :arreglo="factura.direccion" verificacion="0"></empleados-listado>
                        <campo row="col-sm-12" label="Detallar que se entregó:*" codigo="obs" tipo="textarea" requerido="true"></campo>
                        <button class="btn btn-soft-info" @click="saveBitacora(8338, 'Se entregó factura para razonamiento')"><i class="fa fa-check-circle"></i> Entregar a Dirección</button>
                        <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                      </div>

                    </form>

                    <form class="jsValidacionSaveBitacora" id="formValidacionSaveBitacora" v-if="valorRecibido == 1">
                      <div class="row">
                        Recibir la Factura

                        <input id="orden_id" name="orden_id" :value="orden_id" hidden></input>
                        <input id="id_estado" name="id_estado" value="8340" hidden></input>
                        <button class="btn btn-soft-info" @click="saveBitacora(8341,'Se razonó la factura.')"><i class="fa fa-check-circle"></i> Factura razonada</button>
                        <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                      </div>

                    </form>
                  </div>

                </div>

                <!--<div class="col-sm-12" v-if="factura.estado_factura == 8339">
                  Factura Publicada
                  <button class="btn btn-soft-info" @click="saveBitacora(8342, 'Se publicó factura en GUATECOMPRAS')"><i class="fa fa-check-circle"></i> Publicar factura</button>
                  <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                  Asignar Orden de Compra
                  <input id="id_estado" name="id_estado" :value="factura.estado_factura"></input>
                  <button class="btn btn-soft-info" @click="saveBitacora(8342, 'Se publicó factura en GUATECOMPRAS')"><i class="fa fa-check-circle"></i> Publicar factura</button>
                  <button class="btn btn-soft-danger" @click="getOpc(1)"><i class="fa fa-times-circle"></i> Cancelar</button>
                </div>-->

              </div>
              <form class="jsValidacionAsignacionEmpleado" v-if="factura.estado_factura == 0">
                <div class="row">
                  <input id="id_estado" name="id_estado" :value="factura.estado_factura"></input>
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
      opc: 0,
      tecnico:"",
      direccion:"",
      valorRecibido:0,
      opcionesRecibido:[{'id_item':'','item_string':'-- Seleccionar --'},{'id_item':'1','item_string':'Razonamiento correcto'},{'id_item':'2','item_string':'Devolver para razonamiento'}]
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

      if(estado != 0){
        jQuery('.jsValidacionSaveBitacora').validate({
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
              var form = $('#formValidacionSaveBitacora');
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
                data: form.serialize(), //f de fecha y u de estado.
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
          rules: {
            id_campo:{ required: true},
          }
        });
      }else{
        this.opc = 1;
      }


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
                      orden_id:$('#orden_id_').val(),
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
    getDireccionById: function(id_direccion) {
      axios.get('documentos/php/back/functions/get_direccion_by_id', {
        params: {
          id_direccion:id_direccion
        }
      }).then(function (response) {
        this.direccion = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    //this.getEstadoFactura();
    this.opc = this.option;
    eventBus.$on('obtenerDireccion', (valor) => {
      //alert('Works!!');
      this.getDireccionById(valor);
      //componentes de empleados
      eventBus.$emit('recargarListadoDeEmpleados', this.direccion);
    });

    eventBus.$on('valorSeleccionado', (valor) => {
      this.valorRecibido = valor;
    });
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
                      <option value="2">COM-DEV</option>
                      <option value="3">CYD</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!--<campo v-if="vCheque == true" row="col-sm-12" label="Nro. de Cheque:*" codigo="nro_cheque" tipo="number" requerido="true"></campo>-->
          <campo v-if="vClaseProceso == 1" row="col-sm-12" label="Nro. de Orden:*" codigo="nro_orden" tipo="number" requerido="true"></campo>
          <campo v-if="vClaseProceso == 2" row="col-sm-12" label="Nro. de COM-DEV:*" codigo="nro_COM-DEV" tipo="number" requerido="true"></campo>
          <campo v-if="vClaseProceso == 3" row="col-sm-12" label="Nro. de CYD:*" codigo="nro_cyd" tipo="number" requerido="true"></campo>
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

//extras
Vue.component("porcentaje-insumos-pedido", {
  props: ["total"],
  template: `
    <div class="text-left">
      <div style="margin-top:0px; ">
        <span class="badge-sm" :class="porcentaje.texto">Espacio utilizado :{{ porCiento }} %</span> - <strong>Muestra el porcentaje utilizado en el área de impresión disponible del PYR electrónico</strong>
        <div class="progress progress-striped skill-bar " style="height:6px">
          <div class="progress-bar animated" :class="bg" role="progressbar" :aria-valuenow="total" aria-valuemin="0" aria-valuemax="108" :style="{width: [percent]}"></div>
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
      percent:"",
      bg: 'bg-info',
      valor:0,
      porCiento:0,
      totalCiento: 108
    }
  },
  methods:{
    getPorcentaje: function(){

      setTimeout(() => {
        this.timer(this.tempo, this.total);
      }, 100);

    },
    timer: function(tempo, dato) {
      var vm = this;
    	var setIntervalRef = setInterval(function() {
        if(vm.progress < dato){
          vm.progress++;

        }else{
          vm.progress--;
        }
        vm.percent = vm.progress+"%";
        var calculo = Math.round(((dato) * 100 )/ 108);
        vm.porCiento = (calculo > 97.5) ? 100 : calculo;

        if(vm.progress == 108)    {
          this.bg = 'bg-success';
        }else{
          this.bg = 'bg-info';
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

    eventBusPE.$on('recargarPorcentajeTotal', (opc) => {
      this.getPorcentaje();
    });
  }
});

Vue.component("facturas-seleccionadas", {
  props: ["arreglo"],
  template: `
    <div style="max-height:300px; overflow-y:auto; overflow-x:hidden">
      <div style="margin-top:0px; ">
        <table id="facturas_seleccionadas_table" class="table table-sm table-striped">
          <thead>
            <th>NIT</th>
            <th>Proveedor</th>
            <th>Serie</th>
            <th>Número</th>
            <th>Monto</th>
          </thead>
          <tbody>
            <tr v-for="f in facturas">
              <td class="text-center">{{ f.nit }}</td>
              <td class="text-center">{{ f.proveedor }}</td>
              <td class="text-center">{{ f.serie }}</td>
              <td class="text-center">{{ f.factura }}</td>
              <td class="text-right">{{ f.monto }}</td>
            </tr>
          </tbody>

        </table>
      </div>
      <br>
    </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      facturas:[]
    }
  },
  methods:{
    getFacturas: function(){
      axios.get('documentos/php/back/factura/listados/get_facturas_seleccionadas', {
        params: {
          arreglo:this.arreglo
        }
      }).then(function (response) {
        this.facturas = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },

  },
  created: function(){
    this.getFacturas();
  }
});

Vue.component("cheques-disponibles", {
  props: [""],
  template: `
    <combo row="col-sm-12" label="Seleccionar Cheque:*" codigo="id_cheque" :arreglo="chequesList" tipo="3" requerido="true"></combo>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      chequesList:[]
    }
  },
  methods:{
    getFacturas: function(){
      axios.get('documentos/php/back/voucher/listados/get_cheques_disponibles_list', {
        params: {
        }
      }).then(function (response) {
        this.cheques = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },

  },
  created: function(){
    this.getFacturas();
  }
});


//ordenes compra
Vue.component("ordenes-seleccionadas", {
  props: ["arreglo","tipo"],
  template: `
    <div class="col-sm-12" v-if="tipo == 1" style="max-height:300px; overflow-y:auto; overflow-x:hidden">
      <div style="margin-top:0px; ">
        <table id="ordenes_seleccionadas_table" class="table table-sm table-striped" width="100%">
          <thead>
            <th>Tipo de pago</th>
            <th>Registro</th>

          </thead>
          <tbody>
            <tr v-for="ord in ordenes">
              <td class="text-center">{{ ord.tipo_pago }}</td>
              <td class="text-center">{{ ord.nro_registro }}</td>
            </tr>
          </tbody>

        </table>
      </div>
      <br>
    </div>
    <div v-else-if="tipo == 2">
      Orden
      <dato-persona icono="fa fa-hashtag" texto="Tipo de pago:" :dato="ordenes.tipo_pago" tipo="1"></dato-persona>
      <dato-persona icono="fa fa-hashtag" texto="Registro:" :dato="ordenes.nro_registro" tipo="1"></dato-persona>
    </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      ordenes:[]
    }
  },
  methods:{
    getOrdenes: function(){
      axios.get('documentos/php/back/orden/listados/get_ordenes_seleccionadas', {
        params: {
          arreglo:this.arreglo
        }
      }).then(function (response) {
        this.ordenes = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },

  },
  created: function(){
    this.getOrdenes();
  }
});

// filtrar insumo componente
Vue.component("filter-insumos", {
  props: ["ped_tra","tipo", "clase", "pedido","id_filtrado","tipo","evento"],
  template: `
  <div class="row">
  <!--{{ pedido }}-->
    <div class="col-sm-12">
      <div class="row">
        <!--inicio-->
        <div :class="widthStyle">
          <div class="form-group">
            <div class="">
              <div class="">
                <label for="id_pedido">Insumo</label> <span class="btn btn-sm btn-soft-info" @click="filtrado"><i class="fa fa-sync"></i> Recargar Filtro</span> Utilizar este botón si no le deja filtrar
                <div class=" input-group  has-personalizado">
                  <select class="categoryName_ form-control" style="width:100%" :id="id_filtrado" :name="id_filtrado"></select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-2" v-if="tipo == 1">
         <span class="btn btn-soft-info btn-block" @click="sendInfoInsumo()"><i class="fa fa-plus-circle"></i></span>
        </div>
        <!-- fin -->
      </div>
    </div>


  </div>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      insumos:[],
      totalOcupado: 0,
      widthStyle:'col-sm-10',
    }
  },
  computed: {
    linesTotal: function() {
      if (!this.insumos) {
        return 0;
      }
      return this.insumos.reduce(function (total, value) {
        return total + Number(value.lineas);
      }, 0);
    }
  },
  methods:{
    totalLineasSum() {
      this.totalLineas = 0;
      this.totalLineas = this.insumos.reduce((a, b) => {
        return a + Number(b['lineas']);
      }, 0);

      console.log(this.totalLineas);
    },
    filtrado: function () {
      var thisInstance = this;
      setTimeout(() => {
        $('#' + thisInstance.id_filtrado).select2({
          placeholder: 'Selecciona un insumo',
          ajax: {
            url: 'documentos/php/back/pedido/get_insumo_filtrado.php',
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
      }, 1000);
    },
    sendInfoInsumo: function(){
      //console.log(this.evento);
      this.evento.$emit('enviar_info_insumo', $('#' +this.id_filtrado).val())
    },
  },
  created: function(){
    this.filtrado();
    this.widthStyle = (this.tipo == 1) ? 'col-sm-10' : 'col-sm-12';
  }
});


Vue.component("insumos-filtrado", {
  props: ["ped_tra","tipo", "clase", "pedido","id_filtrado","evento"],
  template: `
  <div class="row">
  <!--{{ pedido }}-->

    <div class="col-sm-12">
    <!--lineas: {{ linesTotal}}-->
    <input id="id_total_insped" :value="linesTotal" hidden></input>
      <porcentaje-insumos-pedido :total="linesTotal"></porcentaje-insumos-pedido>
    </div>
    <div class="col-sm-12">
      <table class="table table-sm table-bordered table-striped" :class="clase">
        <thead>
          <!--<th class="text-center" style="width:15px">Renglon</th>-->
          <th class="text-center" style="width:15px">Insumo</th>
          <th class="text-center" style="width:15px">Cod.Pre</th>
          <th class="text-center" style="width:100px">Nombre</th>
          <th class="text-center" style="width:15px">Pres.</th>
          <th class="text-center" style="width:15px">Med.</th>
          <th class="text-center" style="width:25px">Cantidad</th>
          <th class="text-center" style="width:25px">Costo</th>
          <th class="text-center" style="width:60px">Importe</th>

          <th class="text-center" style="width:60px">Cuatrimestre</th>
          <th class="text-center" style="width:60px">Comprado</th>
          <th class="text-center" style="width:60px">Total</th>
          <th class="text-center" style="width:15px">Acción</th>
        </thead>
        <tbody>
          <tr v-for="(i, index) in insumos">
            <!--<td class="text-center">{{i.Ppr_Ren}}</td>-->
            <td class="text-center">{{i.Ppr_cod}}</td>
            <td class="text-center">{{i.Ppr_codPre}}</td>

            <td class="text-center">{{i.Ppr_Nom}}</td>
            <td class="text-center">{{ i.Ppr_Pres }}</td>

            <td class="text-center">{{i.Ppr_Med}}</td>
            <td class="text-center" style="width:60px">
              <div class="form-group">
                <div class="">
                  <div class="">
                    <input type="number"  :name="'cant' + index" :id="'cant' + index"  class="form-control input-sm" v-model="i.cantidad" autocomplete="off" min="1" required></input>
                  </div>
                </div>
              </div>
            </td>
            <td class="text-center" style="width:60px">
              <div class="form-group">
                <div class="">
                  <div class="">
                    <input type="number"  :name="'pre' + index" :id="'pre' + index"  class="form-control input-sm" v-model="i.precio_unitario" autocomplete="off" min="0.1" required></input>
                  </div>
                </div>
              </div>
            </td>
            <td class="text-center"><span>{{ i.importe }}</span></td>
            <td class="text-center">{{ i.cuatrimestre }}</td>
            <td class="text-center">{{ i.gastado }}</td>

            <td class="text-center">{{ i.total }}</td>

            <td scope="row" class="trashIconContainer text-center">
              <span class="btn btn-sm btn-danger" @click="deleteRow(index, i, i.Ppr_lineas)"><i class="far fa-trash-alt"></i></span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  `,
  watch: {
    'insumos': {
      handler (newValue, oldValue) {
        newValue.forEach((item) => {

          var operacion = parseFloat(item.cantidad * item.precio_unitario);
          var total = item.gastado;
          if(operacion < 1){
            item.importe = 'ERROR';
            item.total = item.gastado;
          }else{
            item.importe = operacion;
            item.total = parseFloat(item.importe) + parseFloat(item.gastado);
            //total = parseFloat(item.gastado + operacion);
          }



        })
      },
      deep: true
    }
  },
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      insumos: [{
        Ppr_id: '',
        Ppr_can: '',
        cantidad: '',
        precio_unitario:'',
        Ppr_cod: '',
        Ppr_codPre: '',
        Ppr_Nom: '',
        Ppr_Des: '',
        Ppr_Pres: '',
        Ppr_Ren: '',
        Ppr_Med: "",
        Ppr_lineas: "",
        importe:"",
        gastado: "",
        cuatrimestre: "",
        total:""
      }],
      totalOcupado: 0
    }
  },
  computed: {
    linesTotal: function() {
      if (!this.insumos) {
        return 0;
      }
      return this.insumos.reduce(function (total, value) {
        return total + Number(value.lineas);
      }, 0);
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
        //eventBusPE.$emit('recargarPorcentajeTotal',1);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    totalLineasSum() {
      this.totalLineas = 0;
      this.totalLineas = this.insumos.reduce((a, b) => {
        return a + Number(b['lineas']);
      }, 0);

      console.log(this.totalLineas);
    },
    deleteRow(index, d, lineas) {
      var thisInstance = this;
      var idx = this.insumos.indexOf(d);
      if (idx > -1) {
        /*eventBusPE.$emit('recargarPorcentajeTotal',1);
        viewModelPedido.totalLineas = viewModelPedido.totalLineas - lineas;*/
        this.insumos.splice(idx, 1);

      }
    },
    addNewRow() {
      var thisInstance = this;
      if ($('#' + thisInstance.id_filtrado).val() != null) {
        axios.get('documentos/php/back/pedido/get_insumo_seleccionado', {
          params: {
            Ppr_id: $('#' + thisInstance.id_filtrado).val()
          }
        }).then(function (response) {

          if (thisInstance.insumos.find((item) => item.Ppr_id == response.data.Ppr_id)) {
            Swal.fire({
              type: 'error',
              title: 'El producto ya existe en la lista',
              showConfirmButton: false,
              timer: 1100
            });
          } else {
            /*viewModelPedido.totalLineas += parseInt(response.data.lineas);
            if(viewModelPedido.totalLineas > 108){
              viewModelPedido.totalLineas = viewModelPedido.totalLineas - parseInt(response.data.lineas);
              eventBusPE.$emit('recargarPorcentajeTotal',1);
              Swal.fire({
                type: 'error',
                title: 'El insumo que desea agregar no se agregó porque supera el espacio en el formulario electrónico',
                showConfirmButton: true,
                //timer: 1100
              });
            }else{*/

              $('#' + thisInstance.id_filtrado).val('');
              $('#' + thisInstance.id_filtrado).val(null).trigger('change');
              //viewModelPedido.insumos = response.data;
              //eventBusPE.$emit('recargarPorcentajeTotal',1);
              thisInstance.insumos.push({
                Ppr_id: response.data.Ppr_id,
                Ppr_can: '',
                cantidad: '',
                precio_unitario: '',
                Ppr_cod: response.data.Ppr_cod,
                Ppr_codPre: response.data.Ppr_codPre,
                Ppr_Nom: response.data.Ppr_Nom,
                Ppr_Des: response.data.Ppr_Des,
                Ppr_Pres: response.data.Ppr_Pres,
                Ppr_Ren: response.data.Ppr_Ren,
                Ppr_Med: response.data.Ppr_Med,
                Ppr_lineas: response.data.lineas,
                importe: '',
                gastado: response.data.gastado,
                cuatrimestre: response.data.cuatrimestre
              })

              thisInstance.evento.$emit('recibir_insumos', thisInstance.insumos);
            //}
          }
        }).catch(function (error) {
          console.log(error);
        });
      } else {
        Swal.fire({
          type: 'error',
          title: 'Debe seleccionar un insumo',
          showConfirmButton: false,
          timer: 1100
        });
      }
    },
  },
  created: function(){
    this.insumos.splice(0, 1);
    this.evento.$on('enviar_info_insumo', (data)=>{
      this.addNewRow();
    })
    this.evento.$on('limpiarInsumos', () => {
      this.insumos.splice(0, 1);
    })
  }
});
