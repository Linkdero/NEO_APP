var eventBusOrdenO = new Vue();
var viewModelOrdenCompraO = new Vue({

  //el:'#app_factura',
  data:{
    tipo:$('#id_tipo').val(),
    clase_proceso:$('#id_clase_proceso').val(),
    orden:$('#nro_orden').val(),
    arreglo:','+$('#id_pago').val(),
    titulo:'',
    label:'',
    tag:'',
    privilegio:""
  },
  created: function(){
    this.setTitulo();
    eventBusOrdenO.$on('regresarPrincipal', (opc) => {
      this.getOpcion(opc);
    });
  },
  components: {
    //insumosc
  },
  methods:{
    setTitulo: function(){
      if(this.tipo == 0){
        this.label = 'Digite el CUR de compromiso *';
        this.titulo = 'Asignar CUR de compromiso';
      }else
      if(this.tipo == 1){
        this.label = 'Digite el CUR de devengado *';
        this.titulo = 'Asignar CUR de devengado';
      }else
      if(this.tipo == 2){
        //this.label = 'Digite el CUR de devengado *';
        this.titulo = 'Operado';
      }

      if(this.clase_proceso == 1){
        this.tag = 'Nro de Orden : '+this.orden;
      }else{
        this.tag = 'Nro de CYD : '+this.orden;
      }

    },
    getPermisosUser: function(data) {
      console.log('Data from component component Privilegio', data);
      this.privilegio = data;
    },
    operarOrden: function(data){
      jQuery('.jsValidacionOrdenOperar').validate({
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
          var form = $('#formValidacionOrdenOperar');

          Swal.fire({
            title: '<strong>¿Desea realizar la operación?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Operar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "documentos/php/back/factura/action/operar_orden.php",
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                },
                success: function (data) {
                  //exportHTML(data.id);
                  //recargarDocumentos();
                  if (data.msg == 'OK') {
                    $('#modal-remoto').modal('hide');
                    instanciaF.recargarOrdenes();
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    //impresion_pedido(data.id);
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
        },
      });
    }
  }
})

viewModelOrdenCompraO.$mount('#app_orden');
