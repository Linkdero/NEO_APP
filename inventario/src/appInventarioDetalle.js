import { EventBus } from './eventBus.js';
import { privilegios } from './components/GlobalComponents.js';
const bienheader = httpVueLoader('./inventario/src/components/BienHeader.vue');
const biendetalle = httpVueLoader('./inventario/src/components/BienDetalle.vue');
const bienestadolist = httpVueLoader('./inventario/src/components/BienEstadoList.vue');
const bienlugarlist = httpVueLoader('./inventario/src/components/BienLugarList.vue');

  var modelBienDetalle = new Vue({
    //el: '#vdetalle',
    data: {
      bien_id:$('#bien_id').val(),
      sicoin_code:$('#sicoin_code').val(),
      tipo:$('#tipo').val(),
      option:1,
      privilegio:"",
      estado:"",
      evento:"",
      keyReload:0,
      viatico:"",
      loading:false
    },
    destroyed: function(){
      this.modelBienDetalle;
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
    },
    methods:{
      setOption: function(opc){
        this.option = opc;

      },
      getPrivilegios: function(data){
        this.privilegio = data;
      },
      verificarBien: function(){
        //inicio
        var thisInstance = this;
        if($('#id_persona').val() == ''){
          Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un empleado.',
            showConfirmButton: false,
            timer: 1100
          });
        }else{
          //inicio
          Swal.fire({
            title: '<strong>¿Desea verificar este bien?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, verificar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "inventario/model/Inventario.php",
                dataType: 'json',
                data: {
                  bien_id:$('#bien_id').val(),
                  estado_id:$('#cmbEstadoBien').val(),
                  lugar_id:$('#cmbUbicacionBien').val(),
                  id_persona:$('#id_persona').val(),
                  opcion:4
                }, //f de fecha y u de estado.
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

                  EventBus.$emit('recargarInventarioTable');

                  $('#modal-remoto').modal('hide')
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
          //fin
        }

        //fin
      }

    },
    components: {
      bienheader, biendetalle, privilegios, bienestadolist, bienlugarlist
    }
  });

  modelBienDetalle.$mount('#biendetalle');



//})
