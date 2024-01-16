import { EventBus } from './eventBus.js';


import { privilegios } from './components/GlobalComponents.js';
import { direcciones, empleados, departamentos } from '../../assets/js/pages/GlobalComponents.js';
const bienheader = httpVueLoader('./inventario/src/components/BienHeader.vue');
const certificacioneslist = httpVueLoader('./inventario/src/components/CertificacionesList.vue');

var eventBus = new Vue();
  var modelBienCertificacionEntrega = new Vue({
    //el: '#vdetalle',
    data: {
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      keyReload:0,
      viatico:"",
      loading:false,
      valorc:0,
      direccion_id:$('#direccion_id').val()
    },
    destroyed: function(){
      this.modelBienCertificacionEntrega;
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
      this.evento = EventBus;
      EventBus.$on('getOpcion', (data) => {
        this.option = data;
        if(data == 2){
          this.keyReload ++;
        }
      });
      EventBus.$on('destroyInstance', () => {
        //alert('jaldfjñlajsdlfk');
        this.$destroy();
      });
      this.recargarDireccion();

    },
    methods:{
      setOption: function(opc){
        this.option = opc;
      },
      getPrivilegios: function(data){
        this.privilegio = data;
      },
      recargarDireccion: function(){
        setTimeout(() => {
          EventBus.$emit('sendDireccion', this.direccion_id);
        }, 800);

      },
      generarCertificacion: function(){
        //inicio
        var thisInstance = this;
        jQuery('.jsValidacionEntregarCertificacion').validate({
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
              var form = $('#formValidacionEntregarCertificacion');
              Swal.fire({
                title: '<strong>¿Desea entregar la Certificaicón?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Entregar!'
                }).then((result) => {
                  if (result.value) {
                    //alert(vt_nombramiento);
                    $.ajax({
                      type: "POST",
                      url: "inventario/model/Inventario.php",
                      dataType: 'json',
                      data: {
                        opcion:12,
                        certificacion_id:$('#certificacion_id').val(),
                        id_empleado:$('#id_empleado').val(),
                        fecha_entrega:$('#fecha_entrega').val(),
                      }, //f de fecha y u de estado.f de fecha y u de estado.

                    beforeSend:function(){
                    },
                    success:function(data){
                      if(data.msg == 'OK'){
                        Swal.fire({
                          type: 'success',
                          title: data.message,
                          showConfirmButton: false,
                          timer: 1100
                        });
                        $('#modal-remoto').modal('hide');
                        data = { 'clase' : 'btn-ce2', 'opcion': 2}
                        EventBus.$emit('recargarCertificacionesTable',data);
                      }else{
                        Swal.fire({
                          type: 'error',
                          title: data.message,
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
        //fin
      }
    },
    components: {
      bienheader, certificacioneslist, privilegios, direcciones, empleados, departamentos
    }
  });

  modelBienCertificacionEntrega.$mount('#biencertificacionentrega');



//})
