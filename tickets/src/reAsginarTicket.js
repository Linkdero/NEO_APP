const ticketdetalle = httpVueLoader('./tickets/src/components/detalleReasginarTicket.vue')
const viewModelAsignacionDetalleTicket = new Vue({
  el: '#reAsginarTicket',
  components:
  {
    ticketdetalle
  },
  data: {
    componentKey: 0,
    renderComponent: true,
  },

  beforeDestroy() {
    window.removeEventListener('scroll', this.onScroll)
  },
  beforeCreate: function () {
    $('#data_').html('<div class="loaderr"></div>');
  },

  created: function () {

  },

  //Asi generamos funciones en VUE
  methods: {
    ticketNuevo: function () {
      jQuery('.jsValidacionDirecciones').validate({
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
          let combo = document.getElementById("direcciones");
          let selected = combo.options[combo.selectedIndex].text;

          let combo2 = document.getElementById("departamentos");
          let selected2 = combo2.options[combo2.selectedIndex].text;

          let combo3 = document.getElementById("requerimientos");
          let selected3 = combo3.options[combo3.selectedIndex].text;
          let selected4 = document.getElementById("descripcion").value;


          Swal.fire({
            title: '<strong>¿Desea reasignar?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Reasignar!'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "GET",
                url: "tickets/model/tickets.php",
                dataType: 'json',
                data: {
                  opcion: 5,
                  direcciones: selected,
                  departamentos: selected2,
                  requerimientos: $('#requerimientos').val(),
                  descripcion: selected4
                }, //f de fecha y u de estado.
                beforeSend: function () {
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:
                  function (data) {
                    if (data.msg == 'OK') {
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                      $('#tb_tickets').DataTable().ajax.reload();
                      let i = enviarNUevo();
                    } else {
                      Swal.fire({
                        type: 'error',
                        title: data.id,
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                    $('#modal-remoto-lg').modal('hide');
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
  },
})


function enviarNUevo() {
  $.ajax({
    type: "GET",
    url: "tickets/model/tickets.php",
    dataType: 'json',
    data: {
      opcion: 18,
      idTick: 1,
      tipoEnviar: 4
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

  $.ajax({
    type: "GET",
    url: "tickets/model/tickets.php",
    dataType: 'json',
    data: {
      opcion: 19,
      tipoEnviar: 5
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
}

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

    beforeSend: function () { },
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