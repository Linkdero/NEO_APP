var eventBus = new Vue();
var viewModelFacturaDetalle = new Vue({

  //el:'#app_factura',
  data:{
    orden_id:$('#orden_id_').val(),
    ped_num:$('#id_pedido_num').val(),
    factura:"",
    insumos:"",
    insumosf:"",
    accesos:"",
    asignarF:1,
    habilitar:false,
    privilegio:""
  },
  created: function(){
    this.getFactura(),
    this.getInsumosFactura(),
    //this.getAccesosCompras(),
    this.getInsumosParaFactura(),
    this.validarPedido();

    eventBus.$on('regresarPrincipal', (opc) => {
      this.getOpcion(opc);
    });

    eventBus.$on('recargarFactura', (opc) => {
      this.getFactura();
    });
  },
  components: {
    //insumosc
  },
  methods:{
    getPermisosUser: function(data) {
      console.log('Data from component component Privilegio', data);
      this.privilegio = data;
    },
    getFactura: function(){
      if(this.orden_id > 0){
        axios.get('documentos/php/back/factura/get_factura', {
          params: {
            orden_id:this.orden_id
          }
        }).then(function (response) {
          viewModelFacturaDetalle.factura = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      }
    },
    toggleSelect: function() {
      var select = this.selectAll;
      this.insumos.forEach(function(c) {
        c.checked = !select;
      });
      this.selectAll = !select;
    },selectAll: function() {


    },
    getInsumosParaFactura: function(){
      if($('#id_pedido_num').val() != ''){
        axios.get('documentos/php/back/factura/get_insumos_by_pedido', {
          params: {
            ped_num:$('#id_pedido_num').val()
          }
        }).then(function (response) {
          viewModelFacturaDetalle.insumos = response.data;

        }).catch(function (error) {
          console.log(error);
        });
      }

    },
    getInsumosFactura: function(){
      if(this.orden_id > 0){
        axios.get('documentos/php/back/factura/get_insumos_factura', {
          params: {
            orden_id:this.orden_id
          }
        }).then(function (response) {
          viewModelFacturaDetalle.insumosf = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      }
    },
    computeSubTotal: function(insumo) {
      //formatPrice is removed here because its not defined in the question
      var operacion = 0;
      var resultado = parseFloat(insumo.Pedd_canf);
      var recibido = (insumo.v_rec != '')?insumo.v_rec:0;
      operacion = insumo.Pedd_can - insumo.Pedd_canf;

      if(insumo.Pedd_canf == 0){
        resultado = parseFloat(recibido);
      }else{
        if(operacion < 0){

        }else{
          resultado = parseFloat(recibido) + parseFloat(insumo.Pedd_canf);
        }
      }

      if(resultado > insumo.Pedd_can){
        return 'ERROR';
      }else{
        return (resultado);
      }

    },
    contar_errores:function(){
      var count=0;
      $("#tb_detalle_factura tbody tr").each(function(index, element){
        id_row = ($(this).attr('id'));
        if($('#st'+index).text()=='ERROR'){
          count++;
        }
        console.log(index +' |-| '+count);
      });
      if(count==0){
        return true;
      }else{
        return false;
      }

    },
    validarPedido: function(){
      if(this.ped_num != ''){
        this.habilitar = true;
      }
    },
    agregarInsumosFactura: function(evt){
      if(viewModelFacturaDetalle.contar_errores()){

        jQuery('.jsValidacionFacturaInsumos').validate({
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
                var insumos = viewModelFacturaDetalle.insumos;


                  Swal.fire({
                  title: '<strong>¿Desea generar la factura?</strong>',
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
                    url: "documentos/php/back/factura/crear_insumos_factura.php",
                    dataType: 'json',
                    data: {
                      orden_id:$('#orden_id').val(),
                      insumos: insumos
                    }, //f de fecha y u de estado.

                    beforeSend:function(){
                    },
                    success:function(data){
                      //exportHTML(data.id);
                      //recargarDocumentos();
                      if(data.msg == 'OK'){


                        viewModelFacturaDetalle.getFactura();
                        viewModelFacturaDetalle.getInsumosFactura();
                        instanciaF.recargarFacturas();
                        Swal.fire({
                          type: 'success',
                          title: 'Insumos agregados',
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
      }else{
        evt.preventDefault();
        Swal.fire({
          type: 'error',
          title: 'Debe ingresar correctamente las cantidades',
          showConfirmButton: false,
          timer: 1100
        });

      }
    },
    guardaTipoPago: function(event){
      //event.preventDefault();
      jQuery('.jsValidacionAsignacionPago').validate({
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
                title: '<strong>¿Desea establecer el tipo de pago?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Establecer!'
                }).then((result) => {
                if (result.value) {
                  //alert(vt_nombramiento);
                  $.ajax({
                  type: "POST",
                  url: "documentos/php/back/factura/establecer_pago.php",
                  dataType: 'json',
                  data: {
                    orden_id:$('#orden_id').val(),
                    id_tipo_pago:$('#id_tipo_pago').val()
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){


                      viewModelFacturaDetalle.getFactura();
                      instanciaF.recargarFacturas();
                      Swal.fire({
                        type: 'success',
                        title: 'Tipo de pago establecido',
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
          id_tipo_pago:{ required: true},
        }

      });
    },
    guardaFase: function(event,tipo){
      //event.preventDefault();
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
              //regformhash(form,form.password,form.confirmpwd);

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
                  //alert(vt_nombramiento);
                  $.ajax({
                  type: "POST",
                  url: "documentos/php/back/factura/asignar_fase.php",
                  dataType: 'json',
                  data: {
                    orden_id:$('#orden_id').val(),
                    id_campo:$('#id_campo').val(),
                    tipo:tipo
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){


                      viewModelFacturaDetalle.getFactura();
                      instanciaF.recargarFacturas();
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
    },
    getAccesosCompras: function(){
      axios.get('documentos/php/back/listados/get_accesos', {
        params: {
        }
      }).then(function (response) {
        viewModelFacturaDetalle.accesos = response.data;
        console.log(response.data);
      }).catch(function (error) {
        console.log(error);
      });
    },
    asingarFacturaEmpleado: function(event){
      //alert('ajdfkla')
      //event.preventDefault();
      jQuery('.jsValidacionAsignarFactura').validate({
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
                  //alert(vt_nombramiento);
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
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){


                      viewModelFacturaDetalle.getFactura();
                      instanciaF.recargarFacturas();
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
    },
    getOpcion: function(opc){
      this.asignarF = opc;
    },
    showAsignar: function(opc){
      this.asignarF = opc
    }

  }


})

viewModelFacturaDetalle.$mount('#app_factura');
