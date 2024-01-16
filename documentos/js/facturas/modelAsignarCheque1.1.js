var eventBus = new Vue();
//import { eventBus } from '../components/eventBus.js';
var viewModelAsignarCheque = new Vue({

  //el:'#app_factura',
  data:{
    tipo:$('#id_tipo').val(),
    arreglo:$('#orden_id_c').val(),
    titulo:'',
    label:'',
    tag:'',
    privilegio:"",
    direccion:"",
    valorRecibido:"",
    arregloCheques:[],
    evento:""
  },
  created: function(){
    this.getChequesList();
    this.evento = eventBus;
  },
  components: {
    //insumosc
  },
  methods:{
    getPermisosUser: function(data) {
      console.log('Data from component component Privilegio', data);
      this.privilegio = data;
    },
    getChequesList: function(id_direccion) {
      axios.get('documentos/php/back/voucher/listados/get_cheques_disponibles_list', {
        params: {
        }
      }).then(function (response) {
        this.arregloCheques = response.data;
        setTimeout(() => {
          $('#nro_cheque').select2();
        }, 800);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    asignarCheque: function(data){
      jQuery('.jsValidacionAsignarCheque').validate({
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
          var form = $('#formAsignarCheque');

          Swal.fire({
            title: '<strong>¿Desea asignar este cheque a la factura?</strong>',
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
                url: "documentos/php/back/factura/action/asignar_cheque.php",
                dataType: 'json',
                data: form.serialize(),
                beforeSend: function () {
                },
                success: function (data) {
                  //exportHTML(data.id);
                  //recargarDocumentos();
                  if (data.msg == 'OK') {
                    $('#modal-remoto').modal('hide');
                    instanciaF.recargarFacturas(0,'');
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

viewModelAsignarCheque.$mount('#appAsignarCheque');
