//var modal = httpVueLoader('./tickets/src/components/modal.vue');

var viewModelAsignarTicket = new Vue({
  data: {
    idTicket: $('#id_ticket').val(),
    tecnicos: [],
    keyReload: 0
  },

  created: function () {
    this.keyReload += 1;
    this.getTecnicos();
    setTimeout(() => {
      $('.jsTecnicos').select2();
    }, 200);

  },
  //Asi generamos funciones en VUE
  methods: {
    getTecnicos: function () {
      axios
        .get("tickets/model/tickets.php", {
          params: {
            opcion: 10,
          },
          //Si todo funciona se imprime el json con los tecnicos
        })
        .then(
          function (response) {
            this.tecnicos = response.data;

            //Si falla da mensaje de error
          }.bind(this)
        )
        .catch(function (error) {
          console.log(error);
        });
    },
    asignarTecnico: function () {
      jQuery('.jsValidacionAsignarTecnico').validate({
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
          var form = $('#formNuevoEscolaridad');
          Swal.fire({
            title: '<strong>¿Desea asignar el o los técnicos?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, asignar!'
          }).then((result) => {
            if (result.value) {
              let ticket = $('#id_ticket').val()
              $.ajax({
                type: "GET",
                url: "tickets/model/tickets.php",
                dataType: 'json',
                data: {
                  opcion: 13,
                  estado:1,
                  id: ticket,
                  tecnicosAsignados: $('#tecnicos').val()
                }, //f de fecha y u de estado.
                beforeSend: function () {
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:
                  function (data) {
                    $('#modal-remoto-lg').modal('hide');
                    if (data.msg == 'OK') {
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                      $('#tb_tickets').DataTable().ajax.reload();
                    } else {
                      Swal.fire({
                        type: 'error',
                        title: data.id,
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                  }
              }).done(function () {

              }).fail(function (jqXHR, textSttus, errorThrown) {

                alert(errorThrown);

              });

              $.ajax({
                type: "GET",
                url: "tickets/model/tickets.php",
                dataType: 'json',
                data: {
                  opcion: 18,
                  idTick: ticket,
                  tipoEnviar: 1
                }, //f de fecha y u de estado.
                beforeSend: function () {
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success: function (data) {
                  var reformattedArray = data.correos.map(function (obj) {
                    sendMail(obj.emisor, obj.receptor, obj.body, obj.subject)
                  });

                }
              }).done(function () {

              }).fail(function (jqXHR, textSttus, errorThrown) {

                alert(errorThrown);

              });

              function sendMail(emisor, receptor, body, subject) {
                $.ajax({
                  type: "POST",
                  url: "https://saas.gob.gt/mailer/",
                  dataType: 'json',
                  data: {
                    emisor: emisor,
                    receptor: receptor,
                    body: body,
                    subject: subject
                    /*,
                    subject:subject,
                    body:body*/

                  }, //f de fecha y u de estado.

                  beforeSend: function () {
                  },
                  success: function (data) {
                    if (data.msg == 'OK') {
                      $('#modal-remoto-lg').modal('hide');
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                    } else {
                      Swal.fire({
                        type: data.message,
                        title: data.id,
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                    console.log(data);
                  }
                }).done(function () {

                }).fail(function (jqXHR, textSttus, errorThrown) {

                });
              }

            }
          })
        },
        rules: {
        }
      });
    }
  }
})

viewModelAsignarTicket.$mount('#appAsignarTecnico');
