//import { EventBus } from './eventBus.js';
import { eBus } from './appTransporte.js';

//import { viaticoheaders, privilegios, estadonom } from './components/GlobalComponents.js';
/*const transporteheader = httpVueLoader('./transportes/src/components/TransporteHeader.vue');
const solicituddetalle = httpVueLoader('./transportes/src/components/SolicitudDetalle.vue');
const solicitudvehiculos = httpVueLoader('./transportes/src/components/SolicitudVehiculos.vue');*/
/*const viaticoempleados = httpVueLoader('./viaticos/js/components/viaticoempleados.vue');*/

import { retornadireccion, retornaprivilegios, empleadosdireccion } from './components/GlobalComponents.js';
import { direcciones, empleados, departamentos } from '../../assets/js/pages/GlobalComponents.js';

  var modelSolicitudNueva = new Vue({
    //el: '#vdetalle',
    data: {
      destinos:[],
      arrayT:[
        {"id_item":"","item_string":"-- Seleccionar --"},
        {"id_item":"1","item_string":"Minuto (s)"},
        {"id_item":"2","item_string":"Hora (s)"},
        {"id_item":"3","item_string":"Día (s)"}
      ],
      direccion:"",
      privilegios:"",
      columna:"col-sm-3",
      numbers: {"uno":"1","dos":"2","tres":"3"},
      motivo:[]
    },
    destroyed: function(){
      //this.modelSolicitudNueva;
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
      this.getMotivos();
      eBus.$on('getOpcion', (data) => {
        this.option = data;
        console.log(data);
        if(data == 2){
          this.keyReload ++;
        }
      });
    },
    methods:{
      setOption: function(opc){
        this.option = opc;

      },
      getDireccionFromComponent: function(data){
        this.direccion = data;
      },
      getPrivilegiosFromComponent: function(data)
      {
        this.privilegios = data;
        if(this.privilegios.encargado_transporte == true || this.privilegios.jefe_transporte == true){
          //this.columna = "col-sm-4";
        }else{
          //this.columna = "col-sm-3";
          setTimeout(() => {
            //inicio
            var today = new Date().toISOString().split('T')[0];
            var date = today.split("-"),
            hoy = new Date(date[0], date[1], date[2]);

            var calculado = new Date();
            var dateResul = hoy.getDate() - 15;
            dateResul = (dateResul < 0) ? dateResul * -1 : dateResul;
            console.log('resultado r: '+ dateResul);
            var dia = (dateResul < 10 ) ? '0'+ dateResul : dateResul;
            var mResult = hoy.getMonth()-1;
            mResult = (mResult < 0) ? mResult * -1 : mResult;
            var mes = (mResult < 10 ) ? '0'+mResult : mResult;
            var minimo = hoy.getFullYear() + "-" +(mes) +"-" +dia;


            console.log('Mostrando fecha: '+minimo + ' AND '+ today);

            //document.getElementsByName("fecha_salida")[0].setAttribute('max', today);
            var todayy = new Date().toISOString().slice(0, 16);

            var dayy = new Date();
            var dayyy = dayy.setMinutes(dayy.getMinutes()+15);
            var year = dayy.toLocaleString("es-GT", { year: "numeric" });
            var month = dayy.toLocaleString("default", { month: "2-digit" });
            var day = dayy.toLocaleString("default", { day: "2-digit" });

            var hour = dayy.toLocaleString("es-GT", { hour: "numeric" });
            var minute = dayy.toLocaleString("default", { minute: "2-digit" });

            //var min =` ${year.replace(',','-')}`;
            var min = year+'-'+month+'-'+day+'T'+hour+':'+minute;

            console.log(dayyy);

            document.getElementsByName("fecha_salida")[0].min = min;
            document.getElementsByName("fecha_regreso")[0].min = min;

            //fin
          }, 500);

        }
      },
      deleteRow(index, d) {
        var idx = this.destinos.indexOf(d);

        console.log(idx, index);
        if (idx > -1) {
          this.destinos.splice(idx, 1);

        }
      },
      getMotivos: function(){
        axios.get('transportes/model/Transporte', {
          params: {
            opcion:12,

          }
        }).then(function (response) {
          this.motivos = response.data;
          console.log('motivos::   ' +response.data);

          //console.log(this.privilegio);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      },
      recargarTabla: function(){
        eBus.$emit('recargarTablaTransporte',1);
      },
      addNewRow: function(){
        if ($('#departamento').val() != null) {
          //viewModelPedido.insumos = response.data;
          this.destinos.push({
            departamento_id:$('#departamento').val(),
            departamento:$("#departamento option:selected").text(),
            municipio_id:$('#municipio').val(),
            municipio:$("#municipio option:selected").text(),
            lugar_id:$('#poblado').val(),
            lugar:$("#poblado option:selected").text(),
          })
        } else {
          Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un destino',
            showConfirmButton: false,
            timer: 1100
          });
        }
      },
      saveSolicitud: function(){
        //inicio
        var thisInstance = this;
        jQuery('.jsValidacionSolicitudTransporteNueva').validate({
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
            var destinos = thisInstance.destinos;
            if (thisInstance.destinos.length >= 1) {
              Swal.fire({
                title: '<strong>¿Desea generar la solicitud de Transporte?</strong>',
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
                    url: "transportes/model/Transporte.php",
                    dataType: 'json',
                    data: {
                      opcion:3,
                      fecha_salida: $('#fecha_salida').val(),
                      duracion: $('#duracion').val(),
                      cant_personas:$('#cant_personas').val(),
                      id_tipo_seleccion: $('#id_tipo_seleccion').val(),
                      cant_codigos: $('#cant_codigos').val(),
                      motivo_solicitud: $('#motivo_solicitud').val(),
                      observaciones:$('#id_observaciones').val(),
                      fecha_regreso:$('#fecha_regreso').val(),
                      responsable:$('#id_empleados_list').val(),
                      id_direccion:$('#id_direccion').val(),
                      id_departamento:$('#id_departamento').val(),
                      id_empleado:$('#id_empleado').val(),
                      destinos: destinos
                    }, //f de fecha y u de estado.
                    beforeSend: function () {
                    },
                    success: function (data) {
                      //exportHTML(data.id);
                      //recargarDocumentos();
                      if (data.msg == 'OK') {
                        $('#fecha_salida').val();
                        $('#duracion').val();
                        $('#cant_personas').val();
                        $('#id_tipo_seleccion').val();
                        $('#cant_codigos').val();
                        $('#motivo_solicitud').val();
                        $('#observaciones').val();
                        $('#fecha_regreso').val();
                        $('#id_empleados_list').val();
                        $('#modal-remoto-lg').modal('hide');
                        thisInstance.destinos.splice(0, thisInstance.destinos.length);
                        thisInstance.recargarTabla();
                        /*$('#pedido_nro').val('');
                        $('#fecha_pedido').val('');
                        $('#id_unidad').val('');
                        $('#id_observaciones').val('');
                        $('#id_observaciones').text('');
                        thisInstance.totalCharacter = 500;
                        thisInstance.messageCharacter = '';
                        $('.categoryName').val('');
                        $('.categoryName').val(null).trigger('change');
                        $('#pacName').val('');
                        thisInstance.isUrgente = false;
                        thisInstance.validarUrgente();
                        $('#pacName').val(null).trigger('change');
                        viewModelPedido.limpiar_lista();
                        viewModelPedido.recargarTabla();*/
                        Swal.fire({
                          type: 'success',
                          title: 'Solicitud generada',
                          showConfirmButton: false,
                          timer: 1100
                        });//impresion_pedido(data.id);
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
            } else {
              Swal.fire({
                type: 'error',
                title: 'Debe ingresar al menos un destino',
                showConfirmButton: false,
                timer: 1100
              });
            }
          },
        });
        //fin
      }

    },
    components: {
      retornadireccion, retornaprivilegios, empleadosdireccion,
      direcciones,empleados,departamentos
      //transporteheader, solicituddetalle, solicitudvehiculos
      //viaticodetalle, privilegios, estadonom
      //viaticoheaders,viaticodetalle,viaticoempleados
    }
  });

  modelSolicitudNueva.$mount('#stnuevo');

//})
