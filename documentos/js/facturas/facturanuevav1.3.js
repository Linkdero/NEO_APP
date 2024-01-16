//import { EventBus } from '../components/eventBus.js';
var eventBus = new Vue();
var instanciaFN;
var viewModelFactura = new Vue({

  el:'#app_factura_n',
  data:{

    factura:"",
    insumos:"",
    insumosf:"",
    sopcion:1,
    nogValidacion:false,
    privilegio:"",
    ingresoTipo: 1,
    tipoOperacion: $('#id_tipo_operacion').val(),
    opcionesIngreso:[{'id_item':'', 'item_string':'Seleccionar'},{'id_item':1, 'item_string':'Factura'},{'id_item':2, 'item_string':'Cotización'}],
    opcionSimplificado:[],
    mostrarSimplificado:false
  },
  mounted(){
    instanciaFN = this;
  },
  created: function(){
    eventBus.$on('valorSeleccionado', (valor) => {
      this.ingresoTipo = valor;
    });

    this.$nextTick(() => {
      this.proveedorFiltrado();
    });
    var today = new Date().toISOString().split('T')[0];
    var date = today.split("-"),
    hoy = new Date(date[0], date[1], date[2]);

    var calculado = new Date();
    dateResul = hoy.getDate() - 15;
    var dia = (dateResul < 10 ) ? '0'+ dateResul : dateResul;
    var mes = (hoy.getMonth() < 10 ) ? '0'+hoy.getMonth() : hoy.getMonth();
    var minimo = hoy.getFullYear() + "-" +(mes) +"-" +dia;


    console.log('Mostrando fecha: '+minimo + ' AND '+ today);

    document.getElementsByName("fecha_factura")[0].setAttribute('max', today);
    document.getElementsByName("fecha_factura")[0].setAttribute('min', minimo);
  },
  components: {
    //insumosc
  },
  methods:{
    getPermisosUser: function(data) {
      console.log('Data from component component Privilegio', data);
      this.privilegio = data;
    },
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
              var form = $('#formValidacionFacturaNueva');
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
                  data: form.serialize(),

                  beforeSend:function(){
                    //console.log('Cargando...');
                  },
                  success:function(data){
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if(data.msg == 'OK'){
                      $('#cambio').val('1');
                      //var datos = { 'opcion' : 0, 'clase' : 'btn-f1'};
                      //EventBus.$emit('reloadTableInvoices', datos)
                      instanciaF.recargarFacturas(0,'btn-f1');
                      $('#modal-remoto').modal('hide');
                      //viewModelFacturaDetalle.getFactura();
                      Swal.fire({
                        type: 'success',
                        title: data.message,
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
    opcSimplificado: function(event){
      var opcion = event.currentTarget.value;
      if(opcion == 2){
        this.mostrarSimplificado = true;
      }else{
        this.mostrarSimplificado = false;
        this.opcionSimplificado = '';
      }
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


//instanciaFN.proveedorFiltrado();
