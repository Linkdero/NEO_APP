import { EventBus } from '../eventBus.js';
const ViaticoDetalle = httpVueLoader('./viaticos/js/components/ViaticoDetalle.vue');
const ViaticoEmpleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');
const MenuImpresion = httpVueLoader('./viaticos/js/components/ViaticoMenuImpresion.vue');

export const viaticoheaders = Vue.component("viaticoheaders",{
  props: ["id_viatico"],
  template: `
  <div class="modal-header">
    <h4 class="modal-title">Detalle del Nombramiento # {{ id_viatico }}</h4> <label class="btn btn-light btn-sm"  @click="recargarInfo()" style="margin-top:-10px"> <span class="fa fa-sync">  </span>
    </label>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado active btn-sm" @click="setOption(1)">
          <input type="radio" name="options" id="option1" autocomplete="off" checked> Detalle
        </label>
        <label class="btn btn-personalizado btn-sm" @click="setOption(2)">
          <input type="radio" name="options" id="option2" autocomplete="off" > Empleados
        </label>
        <label class="btn btn-personalizado btn-sm" id="actions1Invoker" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" @click="mostrarMenu()">
        <span  autocomplete="off" > Imprimir</span>
        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px; margin-right:20px" aria-labelledby="actions1Invoker" >
          <div class="card overflow-hidden" style="margin-top:-20px;">
            <div class="card-header d-flex align-items-center py-3">
              <h2 class="h4 card-header-title">Opciones:</h2>
            </div>
            <div  class="card-body animacion_right_to_left" style="padding: 0rem;" >
              <MenuImpresion :id_viatico="id_viatico" :key="keyMenu" :show_menu="showMenu" :evento="evento"></MenuImpresion>

            </div>
          </div>
        </div>
        </label>

        <label class="btn btn-personalizado btn-sm salida"  data-dismiss="modal" @click="destroyInstance()">
          <span name="options" id="option3" autocomplete="off" > Salir </span>
        </label>
      </div>


      <!--<li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>-->
    </ul>

  </div>




  <!-- fin -->
  `,
  mounted() {
  },
  data(){
    return {
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      viatico:"",
      showMenu:false,
      keyMenu:0
    }
  },

  components: {
    MenuImpresion
  },
  methods:{
    resetWindow: function (){
      // set the data for the component back to the original configuration
      this.$data = this.initialDataConfiguration;
    },
    setOption: function(opc){
      if(this.option == opc){
        //alert('Ya está cargado')
      }else{
        this.option = opc;
        EventBus.$emit('getOpcion', opc);
      }

    },
    getPrivilegios: function(data){
      this.privilegio = data;
      console.log(this.privilegio);
    },
    getEstadoV: function(data){
      this.estado = data;
      console.log(this.estado);
    },
    getViaticoDetalle: function(data){
      this.viatico = data;
    },
    mostrarMenu: function(){
      EventBus.$emit('recargarMenu', 1);
      /*if(this.showMenu == false){
        this.showMenu = true;
        this.keyMenu ++;
      }else if(this.showMenu == true){{
        this.showMenu = false;
      }*/
    },
    destroyInstance: function(){
      EventBus.$emit('destroyInstance');
    },
    recargarInfo: function(){
      EventBus.$emit('recargarViatico', 1);
      EventBus.$emit('recargarEmpleadosList', 1);

    }
  },
  created: function(){
    console.log(this.id_viatico);;
    this.evento = EventBus;
  }
})

export const privilegios = Vue.component("privilegios",{
  //props: ["id_persona"],
  template: `

  `,
  mounted() {
  },
  data(){
    return {
      option:""
    }
  },
  methods:{
    getPrivilegio: function(){
      axios.get('viaticos/php/back/viatico/evaluar_privilegio', {
      }).then(function (response) {
        this.privilegio  = response.data;
        this.$emit('sendprivilegio', this.privilegio)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
  },
  created: function(){
    this.getPrivilegio();
  }
})

export const estadonom = Vue.component("estadonom",{
  props: ["id_viatico"],
  template: `

  `,
  mounted() {

  },
  data(){
    return {
      estadoViatico:""
    }
  },
  methods:{
    getEstadoViatico: function(){
      axios.get('viaticos/php/back/viatico/get_estado_nombramiento.php', {
        params: {
          vt_nombramiento: this.id_viatico
        }
      }).then(function (response) {
        this.estadoViatico = response.data;
        this.$emit('sendestado', this.estadoViatico)
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
  },
  created: function(){
    this.getEstadoViatico();
    EventBus.$on('recargarEstado', () => {
      this.getEstadoViatico();
      EventBus.$emit('recargarViatico');
      EventBus.$emit('recargarViaticosTable');
    });
  }
})

Vue.component("combo-items", {
  props: ["label", "col","id_catalogo","codigo"],
  template: `

      <combo :row="col" :label="label" :codigo="codigo" :arreglo="items" tipo="2" requerido="true"></combo>

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      items:[]
    }
  },
  mounted: function() {

  },
  methods:{
    getItems: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:this.id_catalogo,
          tipo:0
        }
      })
      .then(function (response) {
        this.items = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    }

  },
  created: function(){
    this.getItems();
  }
});

Vue.component("search-invoice",{
  template: `
    <span class="btn btn-sm btn-soft-info" @click="busquedaFactura"><i class="fa fa-search"></i> Buscar Factura</span>
  `,
  mounted(){

  },
  data(){
    return {
      infoInvoice:""
    }
  },
  methods:{
    busquedaFactura: function(){
      //inicio
      Swal.fire({
        title: 'Ingrese la serie y número de factura a buscar.',
        html: `<div class="row"><div class="col-sm-6"><input type="text" id="nro_serie" class="form-control form-control-sm" placeholder="Serie"></input></div>
        <div class="col-sm-6"><input type="text" id="nro_factura" class="form-control form-control-sm" placeholder="Número"></input></div></div>`,
        showCancelButton: true,
        confirmButtonColor: 'btn btn-success',
        confirmButtonText: 'Buscar <i class="fa fa-search"></i>',
        showLoaderOnConfirm: true,
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
          const nro_serie = Swal.getPopup().querySelector('#nro_serie').value
          const nro_factura = Swal.getPopup().querySelector('#nro_factura').value
          if (!nro_serie || !nro_factura) {
            Swal.showValidationMessage(`Por favor ingrese la serie y número de factura.`)
          }
          return { nro_serie: nro_serie, nro_factura: nro_factura }
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
        $.ajax({
          url:'viaticos/php/back/viatico/liquidacion/buscarFactura.php',
          method:"POST",
          dataType: 'json',
          data: {
            nro_serie:result.value.nro_serie,
            nro_factura:result.value.nro_factura
          },
          beforeSend:function(){
            $('#loading').show();
          },
          success:function(data){
            //alert(data.msg);
            if(data.msg == 'OK'){
              Swal.fire({

                title: 'Detalle de la Factura',//data.message,
                html:`
                <div class="row">
                  <div class="col-sm-6 text-left">
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Gafete</small>
                        <h3 class="font-weight-bold">${data.id_empleado}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Empleado</small>
                        <h3 class="font-weight-bold">${data.empleado}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Comisión</small>
                        <strong><h3 class="font-weight-bold">${data.vt_nombramiento}</h3></strong>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Dirección</small>
                        <h3 class="font-weight-bold">${data.direccion}</h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 text-left">
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Serie</small>
                        <h3 class="font-weight-bold">${data.factura_serie}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Nro. de factura</small>
                        <h3 class="font-weight-bold">${data.factura_numero}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Monto</small>
                        <h3 class="font-weight-bold">${data.factura_monto}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Propina</small>
                        <h3 class="font-weight-bold">${data.factura_propina}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Nit y lugar</small>
                        <h3 class="font-weight-bold">${data.lugarNit} | ${data.lugarNombre}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Fecha</small>
                        <h3 class="font-weight-bold">${data.fecha}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Tiempo</small>
                        <h3 class="font-weight-bold">${data.tiempo_t}</h3>
                      </div>
                    </div>
                    <div class="media align-items-center item-persona">
                      <div class="media-body">
                        <small class="mb-0">Concepto</small>
                        <h3 class="font-weight-bold">${data.concepto}</h3>
                      </div>
                    </div>

                  </div>
                </div>
                  `,
                showConfirmButton: true,
                //timer: 1100
              });
            }else{
              Swal.fire({
                type: 'error',
                title: 'No existe factura ingresada con esos datos',
                showConfirmButton: true,
                //timer: 1100
              });
            }
         }
       })
      })
    }
    //fin
  }
})
