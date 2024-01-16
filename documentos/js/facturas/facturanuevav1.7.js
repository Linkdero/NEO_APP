//import { EventBus } from '../components/eventBus.js';
var eventBus = new Vue();
var instanciaFN;
var viewModelFactura = new Vue({

  el:'#app_factura_n',
  data:{
    tituloIngreso:"Ingresar Factura",
    factura:"",
    insumos:[],
    insumosf:"",
    sopcion:1,
    nogValidacion:false,
    isBajaCuantia:false,
    privilegio:"",
    ingresoTipo: 1,
    tipoOperacion: $('#id_tipo_operacion').val(),
    opcionesIngreso:[{'id_item':'', 'item_string':'Seleccionar'},{'id_item':1, 'item_string':'Factura'},{'id_item':2, 'item_string':'Cotización'},{'id_item':3, 'item_string':'Recibo'},{'id_item':4, 'item_string':'Formulario SAT'}],
    opcionSimplificado:[],
    mostrarSimplificado:false,
    habilitar:false,
    idRenglon:"",
    evento:""
  },
  computed:{
    totalImporte() {
      return this.insumos.reduce(function (total, value) {
        var totally;
        if(value.checked == true){
          //total = total + Number(value.lineas);
          total = total + value.importe;
        }
        return total;
      }, 0);
    }
  },
  watch: {
    /*'insumos': {
      handler (newValue, oldValue) {
        newValue.forEach((item) => {

          var operacion = 0;
          var resultado = parseFloat(item.Pedd_canf);
          var recibido = (item.v_rec != '') ? item.v_rec : 0;

          operacion = item.Pedd_can - item.Pedd_canf;

          if(item.v_rec.length > 0){
            if(item.precio_unitario.length > 0){
              item.importe =  item.v_rec * item.precio_unitario;
            }else{
              item.importe = 0;
            }
          }else{
            item.importe = 0;
          }



          if(item.Pedd_canf == 0){
            resultado = parseFloat(recibido);
          }else{
            if(operacion < 0){

            }else{
              resultado = parseFloat(recibido) + parseFloat(item.Pedd_canf);
            }
          }

          if(resultado > item.Pedd_can){
            item.recibido = 'ERROR';
          }else{
            item.recibido = resultado;
          }



          if(item.checked == false){
            item.importe = '';
          }
        })
      },
      deep: true
    }*/
  },
  mounted(){
    instanciaFN = this;
  },
  created: function(){
    var thisInstance = this;
    this.evento = eventBus;
    this.evento.$on('valorSeleccionado', (valor) => {
      this.ingresoTipo = valor;
      this.proveedorFiltrado();
      this.setTitle(valor);
        //$("#modal-remoto").animate({"width":"200px","margin-left":"-100px"},600,'linear');
    });

    this.evento.$on('recibir_insumos', (data) => {
      this.insumos = data;
      console.log(this.insumos);
    });

    this.$nextTick(() => {
      this.proveedorFiltrado();
    });
    var today = new Date().toISOString().split('T')[0];
    var date = today.split("-");
    var hoy = new Date(date[0], date[1] - 1, date[2]);

    var calculado = new Date();
    hoy.setDate(hoy.getDate() - 15);

    console.log('Mostrando fecha hoy: ' + hoy.toISOString());

    var dia = (hoy.getDate() < 10) ? '0' + hoy.getDate() : hoy.getDate();
    var mes = (hoy.getMonth() + 1 < 10) ? '0' + (hoy.getMonth() + 1) : hoy.getMonth() + 1;
    var minimo = hoy.getFullYear() + "-" + mes + "-" + dia;

    console.log('Mostrando fecha: ' + minimo + ' AND ' + today);

    document.getElementsByName("fecha_factura")[0].setAttribute('max', today);
    document.getElementsByName("fecha_factura")[0].setAttribute('min', minimo);

    setTimeout(() => {
      $('#id_renglon').on('select2:select', function (e) {
        var data = e.params.data;
        thisInstance.idRenglon = (data.id == 211) ? data.id : '';
        if(data.id == 211 && thisInstance.isBajaCuantia == true){
          $("#modal-remoto .modal-dialog").addClass("modal-lg2g",'fast');
        }else{
          thisInstance.evento.$emit('limpiarInsumos');
          $("#modal-remoto .modal-dialog").removeClass("modal-lg2g");
        }


      });
    }, 900);
  },
  components: {
    //insumosc
  },
  methods:{
    validarPedido: function(){
      if(this.ped_num != ''){
        this.habilitar = true;
      }
    },
    limpiarItem: function(){

    },
    setTitle: function(opcion){
      if(opcion == 1){
        this.tituloIngreso = "Ingresar Factura";
      }else if(opcion == 2){
        this.tituloIngreso = "Ingresar Cotización";
      }else if(opcion == 3){
        this.tituloIngreso = "Ingresar Recibo";
      }else if(opcion == 4){
        this.tituloIngreso = "Ingresar Formulario SAT";
      }
    },
    getInsumosParaFactura: function(){
      var thisInstance = this;
      if($('#id_pedido_num').val() != ''){
        axios.get('documentos/php/back/factura/get_insumos_by_pedido', {
          params: {
            ped_num:$('#id_pedido_num').val()
          }
        }).then(function (response) {
          thisInstance.insumos = response.data;

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
    },
    selectAll: function() {


    },
    getPermisosUser: function(data) {
      //console.log('Data from component component Privilegio', data);
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
      }else if(opcion != 2){
        this.nogValidacion = false;
      }

      if(opcion == 1){
        this.isBajaCuantia = true;
      }else if(opcion != 1){
        this.isBajaCuantia = false;
      }

      if(this.idRenglon == 211 && this.isBajaCuantia == true){
        $("#modal-remoto .modal-dialog").addClass("modal-lg2g",'fast');
      }else{
        this.evento.$emit('limpiarInsumos');
        $("#modal-remoto .modal-dialog").removeClass("modal-lg2g");
      }

      console.log(this.nogValidacion);
    },
    agregarFactura: function(){
      var thisInstance = this;
      var insumos = thisInstance.insumos;
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
            //alert('lakdjfñlkjdflkj')
            if(thisInstance.idRenglon == 211 && thisInstance.isBajaCuantia == true){
              if(viewModelFactura.insumos.length == 0){
                Swal.fire({
                  type: 'error',
                  title: 'Debe seleccionar un PYR.',
                  showConfirmButton: false,
                  timer: 1100
                });
              }else{
                //inicio
                thisInstance.saveFactura();
              }
            }else{
              thisInstance.saveFactura();
            }

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
    saveFactura: function(){
      //inicio
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
            //data: form.serialize(),
            data:{
              id_tipo_operacion:$('#id_tipo_operacion').val(),
              factura_id:$('#factura_id').val(),
              id_tipo_ingreso:$('#id_tipo_ingreso').val(),
              fecha_factura_ingreso:$('#fecha_factura_ingreso').val(),
              fecha_factura:$('#fecha_factura').val(),
              factura_serie:$('#factura_serie').val(),
              factura_nro:$('#factura_nro').val(),
              factura_monto:$('#factura_monto').val(),
              id_nog:$('#id_nog').val(),
              id_proveedor:$('#id_proveedor').val(),
              id_modalidad:$('#id_modalidad').val(),
              pedido_interno:$('#pedido_interno').val(),
              id_regimen:$('#id_regimen').val(),
              id_renglon:$('#id_renglon').val(),
              password:$('#password').val(),
              pago_opcional:$('#pago_opcional').val(),

              orden_id:$('#orden_id_').val(),
              insumos: viewModelFactura.insumos
            },

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
                $("#modal-remoto .modal-dialog").removeClass("modal-lg2g");
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
      //fin
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
    clearClass: function(i,bln){
      if(bln == false){
        i.v_rec = '';
        i.precio_unitario = '';

        /*$('.cf1'+idx).removeClass('has-error');
        $('.cf2'+idx).removeClass('has-error');
        $('.cf3'+idx).removeClass('has-error');
        $('.cf4'+idx).removeClass('has-error');
        $('.cf5'+idx).removeClass('has-error');
        $('.cf6'+idx).removeClass('has-error');
        $('.cf7'+idx).removeClass('has-error');

        $('#txtnit'+idx+'-error').hide();
        $('#txtfecha'+idx+'-error').hide();
        $('#txtserie'+idx+'-error').hide();
        $('#txtnumero'+idx+'-error').hide();
        $('#txtmonto'+idx+'-error').hide();
        $('#txtpropina'+idx+'-error').hide();
        $('#txtproveedor'+idx+'-error').hide();*/


      }
    },
    changeValue: function(){
      viewModelFactura.$forceUpdate();
    },
    proveedorFiltrado: function(){
      setTimeout(() => {
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
      }, 200);
    }

  }


})


//instanciaFN.proveedorFiltrado();
window.addEventListener("beforeprint", (event) => {
  console.log("Before print");
});
