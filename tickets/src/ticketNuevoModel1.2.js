const direccioneslist = httpVueLoader('./tickets/src/components/direccionLista.vue');
const viewModelNuevoTicket = new Vue({
  el: '#AppTicketNuevo',
  components:
  {
    direccioneslist
  },
  data: {
    idTicket: $('#id_ticket').val(),
  },

  beforeDestroy() {
    window.removeEventListener('scroll', this.onScroll)
  },
  beforeCreate: function () {
    $('#data_').html('<div class="loaderr"></div>');
  },
  //Asi generamos funciones en VUE
  methods: {
    ticketNuevo: function (idTipo) {
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
          let fecha = $('#fechaInicial').val()
          let fechaF = $('#fechaFinal').val()
          Swal.fire({
            title: '<strong>¿Desea generar Ticket?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              let tipo = $('#opcion').val()
              if (tipo == 2) {
                if (fecha > fechaF) {
                  alert("La fecha de finalización es mayor que la inicial, compruebe los datos ingresados")
                  return
                }
                $.ajax({
                  type: "GET",
                  url: "tickets/model/tickets.php",
                  dataType: 'json',
                  data: {
                    opcion: 5,
                    direcciones: $('#direcciones').val(),
                    departamentos: $('#departamentos').val(),
                    requerimientos: $('#requerimientos').val(),
                    descripcion: $('#descripcion').val(),
                    tipo: tipo,
                    idSolicitante: $('#usuarios').val(),
                    dirSolicita: $('#direccionesSoli').val(),
                    fecha: fecha,
                    fechaF: fechaF
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
                        let i = enviarNUevo(idTipo);
                      } else {
                        Swal.fire({
                          type: 'error',
                          title: data.id,
                          showConfirmButton: false,
                          timer: 1100
                        });
                      }
                      $('#modal-remoto-lgg2').modal('hide');
                    }
                }).done(function () {
                }).fail(function (jqXHR, textSttus, errorThrown) {
                  alert(errorThrown);
                });
              } else if (tipo == 1) {
                if($("#descripcion").val() == null){
                  alert("Agregue una descripción")
                  return
                }
                $.ajax({
                  type: "GET",
                  url: "tickets/model/tickets.php",
                  dataType: 'json',
                  data: {
                    opcion: 5,
                    direcciones: $('#direcciones').val(),
                    departamentos: $('#departamentos').val(),
                    requerimientos: $('#requerimientos').val(),
                    descripcion: $('#descripcion').val(),
                    tipo: tipo,
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
                        enviarNUevo(idTipo);
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

            }
          })
        },
      });
    }
  },
})


function enviarNUevo(idTipo) {
  if(idTipo == 1){
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

CKEDITOR.replace("descripcion", {
  language: 'es',
  width: '100%',
  height: '170',
  removeButtons: 'Source,Save,Preview,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CopyFormatting,BidiLtr,BidiRtl,Language,Unlink,Anchor,Link,Flash,Smiley,Iframe,BGColor,Maximize,ShowBlocks,About,Cut,Copy,Paste,PasteText,PasteFromWord,FontSize,Image,Table,Format,Styles,CreateDiv,Print,NewPage,Templates,HorizontalRule,PageBreak'
});