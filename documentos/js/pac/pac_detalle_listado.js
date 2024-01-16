var eventBusPACD = new Vue();
// seguimiento
//$(document).ready(function(){



//formulario detalle
  viewModelPACDetalleListado = new Vue({
      data: {
        planes:$('#planes').val(),
        id_renglon:$('#id_renglon_consolidar').val(),
        //formulario:$('#formulario').val(),
        //f1h:0,
        pac:"",
        opcion: 1,
        totalPlanes:0,
        months:[]
      },
      mounted(){
        //instanciaPD = this;
      },

      computed: {
        /*itemsWithSubTotal() {
          return this.insumos.map(item => ({
            item,
            subtotal: this.computeSubTotal(item)
          }))
        }*/
      },
      created: function(){
        this.contarRenglon();
      },

      methods: {
        getOpcion: function(opc){
          this.opcion = opc;
        },
        getPacD: function(data) {
          console.log('Data from component PAC', data);
          this.pac = data;
          this.totalPlanes = data.length;
    		},
        recibirMeses: function (data) {
          this.months = data;
        },
        contarRenglon: function(){

        },
        handleTickInitt: function(tick){

        },
        consolidarPac: function(event){
          var total = 0;
          var thisInstance = this;
          if (this.months.find((item) => item.checked == true)) {
            //inicio
            jQuery('.jsValidacionConsolidarPac').validate({
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
                //var months = viewModelPACDetalleListado.months;
                //alert(total);

                if (thisInstance.months.find((item) => item.checked == true)) {
                  Swal.fire({
                    title: '<strong>¿Desea consolidar el plan de compra?</strong>',
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
                      //eventBusPAC.$emit('recargarMeses',1);
                      $.ajax({
                        type: "POST",
                        url: "documentos/php/back/pac/action/crear_pac_consolidado.php",
                        dataType: 'json',
                        data: {
                          id_nombre_consolidado:$('#id_nombre_consolidado').val(),
                          id_descripcion_consolidado:$('#id_descripcion_consolidado').val(),
                          id_planes:thisInstance.planes,
                          months: thisInstance.months,
                          id_renglon:thisInstance.id_renglon
                        }, //f de fecha y u de estado.

                        beforeSend: function () {
                        },
                        success: function (data) {
                          //exportHTML(data.id);
                          //recargarDocumentos();
                          if (data.msg == 'OK') {
                            $('#id_nombre').val('');
                            $('#id_unidad').val('');
                            $('#id_renglon').val('');
                            //$('#id_renglon').empty();
                            $('#id_renglon').val(null).trigger('change');
                            $('#id_descripcion').val('');
                            $('#id_ejercicio_ant').val('');
                            $('#id_year_anterior').val('');
                            $('#id_descripcion_year').val('');

                            eventBusPAC.$emit('recargarMeses', 1);
                            viewModelPac.tablePlan.ajax.reload(null, false);
                            viewModelPac.ejercicioAnterior = false;
                            Swal.fire({
                              type: 'success',
                              title: 'Plan generado',
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
                } else {
                  Swal.fire({
                    type: 'error',
                    title: 'Debe seleccionar al menos un mes',
                    showConfirmButton: false,
                    timer: 1100
                  });
                }


              },
              rules: {
                combo1: { required: true },
                'pedido_nro': {
                  remote: {
                    url: 'documentos/php/back/pedido/validar_num_pedido.php',
                    data: {
                      ped_num: function () { return $('#pedido_nro').val(); }
                    }
                  }
                }
              },
              messages: {
                'pedido_nro': {
                  remote: "El número de pedido ya fue utilizado."
                }
              }

            });
            //fin
          } else {
            event.preventDefault();
            Swal.fire({
              type: 'error',
              title: 'Debe seleccionar al menos un mes.',
              showConfirmButton: false,
              timer: 1100
            });
          }
        },
      }
    })

    viewModelPACDetalleListado.$mount('#app_pac_detalle_listado');

//instanciaPD = viewModelPACDetalleListado;

//instanciaPD.proveedorFiltrado();

//})
