var instanciaF;
viewModelFactura = new Vue({

  el:'#app_factura_n',
  data:{

    factura:"",
    insumos:"",
    insumosf:"",
    sopcion:1,
    nogValidacion:false

  },
  mounted(){
    instanciaF = this;
  },
  created: function(){

  },
  components: {
    //insumosc
  },
  methods:{
    getsOpcion: function(opc){
      this.sopcion = opc;
    },
    validarNog: function(event){
      console.log(event.currentTarget.value);
      var opcion = event.currentTarget.value;
      if(opcion == 2){
        this.nogValidacion = true;
      }else{
        this.nogValidacion = false;
      }

      console.log(this.nogValidacion);
    },
    agregarFactura: function(){
      jQuery('.jsValidacionFacturaNueva').validate({
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
                  url: "documentos/php/back/factura/crear_factura.php",
                  dataType: 'json',
                  data: {
                    fecha_factura:$('#fecha_factura').val(),
                    factura_serie:$('#factura_serie').val(),
                    factura_nro:$('#factura_nro').val(),
                    factura_monto:$('#factura_monto').val(),
                    proveedor:$('#id_proveedor').val(),
                    id_modalidad:$('#id_modalidad').val(),
                    id_nog:$('#id_nog').val(),
                    id_regimen:$('#id_regimen').val()
                    //pedido_interno:$('#id_pedido_interno').val()
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){
                      $('#cambio').val('1');
                      instanciaF.recargarFacturas();
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


        },
        rules: {
          id_proveedor:{ required: true},
          id_tipo_proveedor:{ required: true},
          'factura_nro': {
            remote: {
              url: 'documentos/php/back/factura/validar_factura.php',
              data: {
                factura_nro: function(){ return $('#factura_nro').val();},
                factura_serie: function(){ return $('#factura_serie').val();}
              }
            }
          }
        },
        messages: {
            'factura_nro': {
                remote: "Esta factura ya existe."
            }
        }


      });
    },
    guardaProveedor: function(){
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

                      viewModelFactura.getsOpcion(1);
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
    },
    proveedorFiltrado: function(){
      $('.proveedor').select2({
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
    }

  }


})


instanciaF.proveedorFiltrado();
