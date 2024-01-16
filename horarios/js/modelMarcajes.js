var eventBusPD = new Vue();
var instancia;
var instanciaM;
// seguimiento
//$(document).ready(function(){



//formulario detalle
  viewModelMarcaje = new Vue({
      data: {
        loading:false

      },
      mounted(){
        instanciaM = this;
      },

      computed: {

      },
      created: function(){

      },

      methods: {
        uploadMarcajes: function(e){
          e.preventDefault();

          /*jQuery('.jsValidacionUploadMarcaje').validate({
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
            submitHandler: function (form) {*/
              //inicio
              Swal.fire({
                title: '<strong>¿Desea subir el los marcajes?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, subir!'
              }).then((result) => {
                if (result.value) {
                  var formData = new FormData($("#formValidacionUploadMarcaje")[0]);
                  $.ajax({
                    type: "POST",
                    url: "horarios/php/back/marcajes/upload_marcajes_file_csv.php",
                    method:"POST",
                    dataType:"json",
                    data:formData,
                    contentType:false,
                    processData:false,
                    beforeSend: function () {
                      Swal.fire({
                        title: 'Espere..!',
                        text: 'Sincronizando horarios de Biométricos..',
                        onBeforeOpen () {
                          Swal.showLoading ()
                        },

                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                      })
                      function myFunc(){
                        Swal.close()
                      }
                    },
                    success: function (data) {

                        Swal.close();

                      if (data.msg == 'OK') {

                        Swal.fire({
                          type: 'success',
                          title: 'Horarios sincronizados',
                          showConfirmButton: false,
                          timer: 1100
                        });
                      } else {
                        Swal.fire({
                          type: 'error',
                          title: data.msg,
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

              //fin
            /*},
          });*/
        }
      }

  })

    viewModelMarcaje.$mount('#app_upload');
  //})






//instanciaPD = viewModelFormularioDetalle;

//instanciaPD.proveedorFiltrado();
