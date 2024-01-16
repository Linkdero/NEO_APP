var eventBus = new Vue();
var viewModelGlobalEntregar = new Vue({

  //el:'#app_factura',
  data:{
    arreglo:$('#id_facts').val(),
    titulo:'',
    label:'',
    tag:'',
    privilegio:"",
    direccion:"",
    valorRecibido:""
  },
  created: function(){
    eventBus.$on('obtenerDireccion', (valor) => {
      //alert('Works!!');
      this.getDireccionById(valor);
      //componentes de empleados
      setTimeout(() => {
        console.log(this.direccion);
        eventBus.$emit('recargarListadoDeEmpleados', this.direccion);
      }, 300);


    });

    eventBus.$on('valorSeleccionado', (valor) => {
      this.valorRecibido = valor;
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
    getDireccionById: function(id_direccion) {
      axios.get('documentos/php/back/functions/get_direccion_by_id', {
        params: {
          id_direccion:id_direccion
        }
      }).then(function (response) {
        this.direccion = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    entregarFacturas: function(data){
      jQuery('.jsValidacionGlobalEntregar').validate({
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
          var form = $('#formValidacionGlobalEntregar');

          Swal.fire({
            title: '<strong>¿Desea Entregar a Dirección?</strong>',
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
                url: "documentos/php/back/factura/action/entregar_global.php",
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                },
                success: function (data) {
                  //exportHTML(data.id);
                  //recargarDocumentos();
                  if (data.msg == 'OK') {
                    $('#modal-remoto').modal('hide');
                    instanciaF.recargarFacturas(0);
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

viewModelGlobalEntregar.$mount('#app_global_entregar');
