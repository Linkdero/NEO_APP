//import { EventBus } from './eventBus.js';
import { eBus } from './appRequisiciones.js';

//import { viaticoheaders, privilegios, estadonom } from './components/GlobalComponents.js';
import { retornaprivilegios } from './components/GlobalComponents.js';
const insumosfiltrado = httpVueLoader('bodega/src/components/InsumosFiltrado.vue');
const insumos = httpVueLoader('bodega/src/components/InsumosSeleccionados.vue');
const unidades = httpVueLoader('bodega/src/components/UnidadesList.vue');
const reqheader = httpVueLoader('bodega/src/components/RequisicionHeader.vue');
const reqdetalle = httpVueLoader('bodega/src/components/RequisicionDetalle.vue');
/*const viaticoempleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');*/

  var modelRequisicionDetalle = new Vue({
    //el: '#vdetalle',
    data: {
      requisicion_id:$('#requisicion_id').val(),
      tipo:1,
      option:1,
      estado:"",
      evento:"",
      keyReload:0,
      insumos:[],
      req:"",
      privilegios:"",
      bodega:$('#bodega').val(),
      visualizarBodega:''
    },
    destroyed: function(){
      //this.modelRequisicionDetalle;
    },
    mounted() {
      //this.$refs.infoBox.focus();
    },
    beforeDestroy() {
      window.removeEventListener('scroll', this.onScroll)
    },
    beforeCreate: function(){
      //alert('cargando');
      console.log(this.privilegio);
      $('#data_').html('<div class="loaderr"></div>');
    },
    created: function(){
      this.evento = eBus;
      this.getBodega();
      eBus.$on('getOpcion', (data) => {
        this.option = data;
        console.log(data);
        if(data == 2){
          this.keyReload ++;
        }
      });
    },
    methods:{
      getInsumosL: function(data) {
        this.insumos = data;
      },
      getRequisicionInfo: function(data){
        this.req = data;
      },
      setOption: function(opc){
        this.option = opc;

      },
      getPrivilegiosFromComponent: function(data){
        this.privilegios = data;
      },
      getEstadoV: function(data){
        this.estado = data;
      },
      getViaticoDetalle: function(data){
        this.viatico = data;
      },
      recargarRequisicion: function(){
        var thisInstance = this;
        thisInstance.evento.$emit('recargarRequisicion');
        thisInstance.evento.$emit('recargarInsumos');
      },
      reloadTable: function(){
        this.evento.$emit('recargarTablaRequisiciones',1);
      },
      getBodega: function(){
        axios.get('bodega/model/Requisicion.php',{
          params:{
            opcion:12,
            bodega_id:this.bodega
          }
        })
        .then(function (response) {
          this.visualizarBodega = response.data
        }.bind(this))
        .catch(function (error) {
            console.log(error);
        });
      },
      updateRequisicion: function(estado,event){
        var thisInstance = this;


        thisInstance.evento.$emit('sendInsumosToParent')
        var insumos = thisInstance.insumos;

        var validacion = false;
        var mensaje = '';
        var button = '';
        var color = '';

        if(estado == 2){
          event.preventDefault();
          validacion = true;
          mensaje = 'Desea revisar esta requisición.';
          button = 'Si, revisar';
          thisInstance.saveActualizacion(mensaje,button,estado, '', '28a745');
        }else if(estado == 6){
          event.preventDefault();
          validacion = true;
          mensaje = 'Desea autorizar esta requisición.';
          button = 'Si, autorizar';
          thisInstance.saveActualizacion(mensaje,button,estado, '', '28a745');
        }/*else if(estado == 7){
          event.preventDefault();
          validacion = true;
          mensaje = 'Desea autorizar esta requisición.';
          button = 'Si, autorizar';
          thisInstance.saveActualizacion(mensaje,button,estado, '', '28a745');
        }*/else if(estado == 8 || estado == 9){
          event.preventDefault();
          validacion = true;
          mensaje = 'Desea rechazar esta requisición.';
          button = 'Si, rechazar';
          thisInstance.saveActualizacion(mensaje,button,estado, '', 'F3C13A');
        }
        else if(estado == 3 || estado == 7){
          //inicio
          if(thisInstance.req.requisicionStatus == 7){
            event.preventDefault();
            validacion = true;
            mensaje = 'Desea autorizar esta requisición.';
            button = 'Si, autorizar';
            thisInstance.saveActualizacion(mensaje,button,estado, '', '28a745');
          }else{
            //inicio
            jQuery('.jsValidacionRequisicionUpdate').validate({
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
                event.preventDefault();
                thisInstance.evento.$emit('sendInsumosToParent')
                var insumos = thisInstance.insumos;
                if(thisInstance.insumos.find((item) => item.cant_autorizada_dsp == 'ERROR' && item.checked == true)){
                  validacion = false;
                  mensaje = 'Debe verificar que las cantidades estén correctamente.';
                }else{
                  validacion = true
                  mensaje = 'Desea autorizar esta requisición';
                  button = 'Si, autorizar';
                }

                if(validacion == false){
                  //incio
                  Swal.fire({
                    type: 'error',
                    title: mensaje,
                    showConfirmButton: true,
                    //timer: 1100
                  });
                  //fin
                }else{
                  //inicio
                  thisInstance.saveActualizacion(mensaje,button,estado, insumos, '28a745');
                  //fin
                }

                //if (thisInstance.insumos.length >= 1) {


              },
            });
            //fin
          }
          //fin
        }else if(estado == 5){
          event.preventDefault();
          validacion = true;
          mensaje = 'Desea anular esta requisición.';
          button = 'Si, Anular';
          thisInstance.saveActualizacion(mensaje,button,estado, '','d33');
        }

      },
      saveActualizacion: function(mensaje, button, estado, insumos, color){
        var thisInstance = this;
        Swal.fire({
          title: '<strong>'+mensaje+'</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#'+color,
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡'+button+'!'
        }).then((result) => {
          if (result.value) {
            //alert(vt_nombramiento);
            $.ajax({
              type: "POST",
              url: "bodega/model/Requisicion.php",
              dataType: 'json',
              data: {
                opcion:10,
                requisicion_id:thisInstance.requisicion_id,
                estado:estado,
                /*unidad:$('#id_unidad').val(),
                id_bodega:$('#id_bodega').val(),
                id_observaciones:$('#id_observaciones').val(),*/
                insumos: insumos
              }, //f de fecha y u de estado.
              beforeSend: function () {

              },
              success: function (data) {

                //exportHTML(data.id);
                //recargarDocumentos();
                if (data.msg == 'OK') {
                  thisInstance.evento.$emit('recargarRequisicion');
                  thisInstance.evento.$emit('recargarInsumos');
                  thisInstance.evento.$emit('recargarTablaRequisiciones',1);
                  setTimeout(() => {
                    thisInstance.evento.$emit('setEditableFase');
                  }, 900);

                  //thisInstance.evento.$emit('recargarPorcentajeTotal',1);

                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });//impresion_pedido(data.id);

                  $('#id_unidad').val()
                  $('#id_bodega').val()
                  $('#id_observaciones').val()
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
      insumosfiltrado, insumos, unidades, reqheader, reqdetalle, retornaprivilegios
    }
  });

  modelRequisicionDetalle.$mount('#reqdetalle');

//})
