import { eBus } from './appTransporte.js';


//import { viaticoheaders, privilegios, estadonom } from './components/GlobalComponents.js';
const transporteheader = httpVueLoader('./transportes/src/components/TransporteHeader.vue');
const transportelist = httpVueLoader('./transportes/src/components/SolicitudDetalleList.vue');
const vehiculoslist = httpVueLoader('./transportes/src/components/VehiculosList.vue');
const vehiculosseleccionadoslist = httpVueLoader('./transportes/src/components/VehiculosSeleccionadosList.vue');
const formulariovehiculo = httpVueLoader('./transportes/src/components/VehiculoFormulario.vue');
/*const viaticoempleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');*/
//var eBus = new Vue();
  var modelAsignarVehiculo = new Vue({
    //el: '#stasignacion',
    data: {
      codigos:$('#codigos').val(),
      tipo:$('#tipo').val(),
      nombremodal:$('#nombremodal').val(),
      arreglo:[],
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      keyReload:0,
      destino:[],
      placas:[],
      idVehiculo:"",
      fechasCount:"",
      picked:"",
      tipoTransporte:[{"id_item":"","item_string":"-- Seleccionar --"},{"id_item":"1","item_string":"Llevar y Traer"},{"id_item":"2","item_string":"Llevar"},{"id_item":"3","item_string":"Traer"}],
      visible:false,
      arreglo:[]
    },
    destroyed: function(){
      //this.modelAsignarVehiculo;
    },
    mounted() {
      //this.$refs.infoBox.focus();
    },
    beforeDestroy() {
      window.removeEventListener('scroll', this.onScroll)
    },
    beforeCreate: function(){
      //alert('cargando');
      $('#data_').html('<div class="loaderr"></div>');
    },
    created: function(){

      this.evento = eBus;

      eBus.$on('getIdVehiculo', (valor) => {
        this.idVehiculo = valor;
      });

      eBus.$on('getFechasConteo', (valor) => {
        this.fechasCount = valor;
      });
      eBus.$on('getOpcion', (data) => {
        this.option = data;
        console.log(data);
        if(data == 2){
          this.keyReload ++;
        }
      });
    },
    methods:{
      onChange(event) {
        var data = event.target.value;
        console.log(data);
        this.evento.$emit('getDestinoVehiculo', data);
      },
      setOption: function(opc){
        this.option = opc;

      },
      getPrivilegios: function(data){
        this.privilegio = data;
      },
      getEstadoV: function(data){
        this.estado = data;
      },
      getViaticoDetalle: function(data){
        this.viatico = data;
      },
      addNewRow: function(){
        //inicio
        var thisInstance = this;
        jQuery('.jsValidacionAsignarVehiculoGlobal').validate({
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
            //regformhash(form,form.password,form.confirmpwd);
            Swal.fire({
              title: '<strong>¿Desea asignar este vehículo y el conductor?</strong>',
              text: "",
              type: 'question',
              showCancelButton: true,
              showLoaderOnConfirm: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, asignar!'
            }).then((result) => {
              if (result.value) {
                var tipo_vehiculo = ($('#rd_propios').is(':checked')) ? 1144: 1147;
                thisInstance.arreglo = {
                  tipo_vehiculo:tipo_vehiculo,
                  conductor_id:$('#id_quien_lleva').val(),
                  conductor_text:$("#id_quien_lleva option:selected").text(),
                  vehiculo_id:$('#id_vehiculo').val(),
                  vehiculo_text:$("#id_vehiculo option:selected").text(),
                  tipo_transporte_id:$('#id_tipo_transporte').val(),
                  tipo_transporte_text:$("#id_tipo_transporte option:selected").text(),
                };
                thisInstance.evento.$emit('getVehiculoSeleccionado',thisInstance.arreglo);
                thisInstance.arreglo = '';
              }
            })
          }
        });
        //fin
      },
      updateEstadoSolicitud: function(estado,message,estado_ant){
        //eBus.$emit('reloadTransporte',5);
        //this.evento.$emit('clearSeleccionSolicitudes');

        var thisInstance = this;
        var titulo = (estado == 2) ? '¿Desea autorizar la solicitud?' : '¿Desea cancelar la solicitud?';
        var color = (estado == 2) ? '#28a745' : '#d33';
        var btnmessage = (estado == 2) ? '¡Si, autorizar!' : '¡Si, cancelar!';

        Swal.fire({
          title: titulo,
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: color,
          cancelButtonText: 'Cancelar',
          confirmButtonText: btnmessage
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              url: "transportes/model/Transporte.php",
              dataType: 'json',
              data: {
                opcion:7,
                solicitud_id:thisInstance.codigos,
                message:message,
                status_actual:estado,
                status_anterior:estado_ant
              }, //f de fecha y u de estado.
              beforeSend: function () {

              },
              success: function (data) {
                //exportHTML(data.id);
                //recargarDocumentos();
                if (data.msg == 'OK') {

                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });//impresion_pedido(data.id);
                  $('#'+thisInstance.nombremodal).modal('hide');
                  eBus.$emit('recargarTablaTransporte',estado);
                  eBus.$emit('clearSeleccionSolicitudes');
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
    components: {
      transporteheader, transportelist, vehiculoslist, vehiculosseleccionadoslist, formulariovehiculo
      //viaticodetalle, privilegios, estadonom
      //viaticoheaders,viaticodetalle,viaticoempleados
    }
  });

  modelAsignarVehiculo.$mount('#stasignacion');

//})
