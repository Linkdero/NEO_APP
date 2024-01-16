
function guardar_visitas() {
  $.ajax({
    type: "POST",
    url: "quinta/php/back/empleado/save_empleado.php",
    data: { img_frontal: function () { return $('#img_1').val() } },
    success: function (data) {
      console.log(data);
    }
  });
}

function asignar_a_puerta(id_persona) {
  Swal.fire({
    title: '<strong>¿Desea asignar el agente a esta puerta?</strong>',
    text: "",
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Asignar!'
  }).then((result) => {
    if (result.value) {
      let puerta = $('#cmb-' + id_persona + ' option:selected').val();
      $.ajax({
        type: "POST",
        url: "quinta/php/back/asignacion/asignar_a_puerta.php",
        data: { id_persona: id_persona, puerta: puerta },
        success: function (data) {
          Swal.fire({
            type: 'success',
            title: 'Agente asignado',
            showConfirmButton: false,
            timer: 700
          });

          reload_asignaciones();
          reload_asignaciones();
        }

      }).done(function () {

      }).fail(function (jqXHR, textSttus, errorThrown) {


      });
    }
  });
}

function save_visita_(id_puerta) {

  jQuery('.validation_nueva_visita').validate({
    ignore: [],
    errorClass: 'help-block animated fadeInDown',
    errorElement: 'div',
    errorPlacement: function (error, e) {
      jQuery(e).parents('.form-group > div').append(error);
    },
    highlight: function (e) {
      let elem = jQuery(e);
      elem.closest('.form-group').removeClass('has-error').addClass('has-error');
      elem.closest('.help-block').remove();
    },
    success: function (e) {
      let elem = jQuery(e);
      elem.closest('.form-group').removeClass('has-error');
      elem.closest('.help-block').remove();
    },
    submitHandler: function (form) {
      let nombre = $('#nombre_visita').val();
      let dependencia = $('#lugar').val();
      let oficina = $('#oficina_visita').val();
      let nombre_oficina = (oficina != 0) ? $('#oficina_visita option:selected').text() : $('#oficina_otro').text();
      let autoriza = $('#autorizacion').val();
      let carnet = $('#carne').val();
      let objeto = $('#objeto').val();
      let ruta = $("#ruta").val();
      let cam1 = ($("#img_2").attr('src') == "./assets/img/photo.jpeg") ? false : true;
      let cam2 = ($("#img_2").attr('src') == "./assets/img/photo.jpeg") ? false : true;
      let data = { ruta, id_puerta, nombre, dependencia, oficina, nombre_oficina, autoriza, carnet, objeto, cam1, cam2 };
      console.log(data);
      return;
      Swal.fire({
        title: '<strong></strong>',
        text: "¿Guardar registo?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Guardar!'
      }).then((result) => {
        if (result.value) {
          $("#btn_save").prop('disabled', true);
          $.ajax({
            type: "POST",
            dataType: "json",
            url: "quinta/php/back/empleado/save_visita.php",
            data: data,
            beforeSend: function () {
              $('#loading').fadeIn("slow");
            },
            success: function (data) {
              let response = JSON.parse(data);
              $('#modal-remoto').modal('hide');
              if (response.code == "200") {
                Swal.fire({
                  type: 'success',
                  title: 'Información Guardada',
                  showConfirmButton: false,
                  timer: 2000
                });
                $("#formulario_visita")[0].reset();
                $("#btn_save").prop('disabled', false);
              } else {
                Swal.fire({
                  type: 'error',
                  title: 'Error al guardar el registro',
                  showConfirmButton: false,
                  timer: 2000
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
    rules: {
      'carne': {
        remote: {
          url: 'quinta/validar_carne_usado.php',
          data: {
            carne: function () { return $('#carne').val(); }
          }
        }
      }




    },
    messages: {
      'carne': {
        remote: "El carné está en uso, utilice otro."
      }
    }

  });
}

function save_visita(id_puerta) {

  jQuery('.validation_nueva_visita').validate({
    ignore: [],
    errorClass: 'help-block animated fadeInDown',
    errorElement: 'div',
    errorPlacement: function (error, e) {
      jQuery(e).parents('.form-group > div').append(error);
    },
    highlight: function (e) {
      let elem = jQuery(e);
      elem.closest('.form-group').removeClass('has-error').addClass('has-error');
      elem.closest('.help-block').remove();
    },
    success: function (e) {
      let elem = jQuery(e);
      elem.closest('.form-group').removeClass('has-error');
      elem.closest('.help-block').remove();
    },
    submitHandler: function (form) {
      let nombre = "";
      let dependencia = $('#lugar').val();
      let oficina = $('#oficina_visita').val();
      let nombre_oficina = (oficina != 0) ? $('#oficina_visita option:selected').text() : $('#oficina_otro').val();
      let autoriza = $('#autorizacion').val();
      let carnet = $('#carne').val();
      let objeto = $('#objeto').val();
      let ruta = $("#ruta").val();
      let cam1 = ($("#img_1").attr('src') == "./assets/img/photo.jpeg") ? false : true;
      let cam2 = ($("#img_2").attr('src') == "./assets/img/photo.jpeg") ? false : true;
      let data = { ruta, id_puerta, nombre, dependencia, oficina, nombre_oficina, autoriza, carnet, objeto, cam1, cam2 };
      if (cam1 || cam2) {
        Swal.fire({
          title: '<strong></strong>',
          text: "¿Guardar registo?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Guardar!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              dataType: "json",
              url: "quinta/php/back/empleado/save_visita.php",
              data: data,
              beforeSend: function () {
                $('#loading').fadeIn("slow");
              },
              success: function (data) {
                let response = JSON.parse(data);
                $('#modal-remoto').modal('hide');
                $("#formulario_visita")[0].reset();
                if (cam1) $("#img_1").attr('src', "./assets/img/photo.jpeg")
                if (cam2) $("#img_2").attr('src', "./assets/img/photo.jpeg")
                if (response.code == "200") {
                  Swal.fire({
                    type: 'success',
                    title: 'Información Guardada',
                    showConfirmButton: false,
                    timer: 2000
                  });
                } else {
                  Swal.fire({
                    type: 'error',
                    title: 'Error al guardar el registro',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }

              }
            }).done(function () {

            }).fail(function (jqXHR, textSttus, errorThrown) {
              alert(errorThrown);
            });
          }
        });
      } else {
        Swal.fire({
          type: 'error',
          title: 'Falta fotografia',
          showConfirmButton: false,
          timer: 2000
        });
      }
    },
    rules: {
      'carne': {
        remote: {
          url: 'quinta/php/back/empleado/validar_carne_usado.php',
          data: {
            carne: function () { return $('#carne').val(); }
          }
        }
      }




    },
    messages: {
      'carne': {
        remote: "El carné está en uso, utilice otro."
      }
    }
  });
}

function save_salida() {
  let carnet = $("#carneSalida").val();
  if (carnet && carnet.length == 3) {
    $.ajax({
      type: "POST",
      url: "quinta/php/back/empleado/exit_visita.php",
      data: { gafete: carnet },
      success: function (data) {
        if (data == 2) {
          Swal.fire({
            type: 'error',
            title: 'Ingrese correctamente el gafete',
            showConfirmButton: false,
            timer: 2000
          });
        } else {
          Swal.fire({
            type: 'success',
            title: 'Gafete devuelto',
            showConfirmButton: false,
            timer: 2000
          });
        }
        $("#carneSalida").val("");
      }
    });
  } else {
    Swal.fire({
      type: 'error',
      title: 'Debe ingresar el número de carné',
      showConfirmButton: false,
      timer: 2000
    });
    $("#carneSalida").val("");
  }
}

function drawImg(str) {
  $('#modalBody').html(`<div style="float: left;margin-bottom: 10px;">
                          <span  class="btn-circle" data-dismiss="modal" aria-label="Close"></span>
                        </div>
                        <img src="${str}" id="imgPreview" style="width: 100%;" >`);
  $('#imageModal').modal('show');
}
