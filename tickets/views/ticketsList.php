<?php
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);
if (function_exists('verificar_session')) {
  $permisos = array();
  $array = evaluar_flags_by_sistema($_SESSION['id_persona'], 8931);

  $pos = $array[3]['id_persona'];
  $permisos = array(
    'ageno' => ($array[2]['flag_insertar'] == 1) ? 1 : 0,
    // 'tecnico' => ($array[2]['flag_es_menu'] == 1) ? 1 : 0,
  );
  ?>
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datatables/new/fixedColumns.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/js/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="<?php echo $one->assets_folder; ?>/js/plugins/datatables/jquery.dataTables.min.css">
  <link rel="stylesheet"
    href="<?php echo $one->assets_folder; ?>/js/plugins/responsive/2.1.1/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="assets/js/plugins/datepicker/css/datepicker.css">

  <script src="assets/js/plugins/select2/select2.min.js"></script>
  <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
  <script src="assets/js/plugins/vue/vue.js"></script>
  <script src="assets/js/pages/components.js"></script>
  <script type="module" src="tickets/src/ticketModel.js"></script>
  <script src="assets/js/plugins/vue/http-vue-loader.js"></script>
  <script src="assets/js/plugins/ckeditor/ckeditor.js"></script>
  <script src="assets/js/plugins/datepicker/js/bootstrap-datepicker.js" referrerpolicy="origin"></script>

  <style>
    div.scrollmenu {
      height: 100px;
      overflow-y: scroll;
    }
  </style>

  <div id="body" class="u-content" width="100%">
    <div id="AppTickets" class="u-body">
      <!-- Overall Income -->
      <div class="row">
        <!-- Current Projects -->
        <div class="col-md-12 mb-12 mb-md-0">
          <div class="card h-100">
            <header class="card-header d-flex align-items-center">
              <h2 class="h3 card-header-title">Listado de Tickets</h2>
              <!-- Card Header Icon -->
              <ul class="list-inline ml-auto mb-0">

                <li class="list-inline-item" title="Agregar Ticket">
                  <span class="link-muted h3" @click="nuevoTicket(1)">
                    <i class="fa fa-plus"></i>
                </li>

                <li class="list-inline-item" title="Recargar">
                  <span class="link-muted h3" @click="recargarTablaTickets()">
                    <i class="fa fa-sync"></i>
                </li>

                <li v-if="<?php echo $permisos["ageno"] ?> == true" class="list-inline-item "
                  title="Agregar Ticket Ageno">
                  <span class="link-muted h3" @click="nuevoTicket(2)">
                    <i class="fa fa-user-plus"></i>
                </li>
              </ul>

              <!-- End Card Header Icon -->
            </header>

            <div class="card-body card-body-slide" width="100%" height="100%">
              <input type="hidden" id="id_filtro" value="0"></input>
              <input type="hidden" id="anderson" value="<?php echo $_SESSION['id_persona'] ?>"></input>
              <div class="">
                <table id="tb_tickets" class="table responsive table-sm table-bordered table-striped" width="100%">
                  <thead>
                    <tr>
                      <th class="text-center">Ticket</th>
                      <th class="text-center">Solicitante</th>
                      <th class="text-center">Estado</th>
                      <th class="text-center">Detalle</th>
                      <th class="text-center">Responsable</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- End Card Body -->
            </div>
            <!-- End Overall Income -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php

  /*}
else{
  include('inc/401.php');
}*/
} else {
  header("Location: index");
}
?>
<style>
  #body {
    min-width: 100%;
  }
</style>

<script>
  function showModal2(tipo, id) {
    let imgModal = $('#modal-remoto-lg');
    let imgModalBody = imgModal.find('.modal-content');
    //let id_persona = parseInt($('#bar_code').val());
    let thisUrl = '';
    if (tipo == 2) {
      thisUrl = 'tickets/views/descripcionTicket.php';
    }
    if (tipo == 3 || tipo == 4) {
      thisUrl = 'tickets/views/asignarTecnico.php';
    }
    $.ajax({
      type: "GET",
      url: thisUrl,
      dataType: 'html',
      data: {
        tipo: tipo,
        id: id
      },
      beforeSend: function (data) {
        imgModal.modal('show');
        imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
      },
      success: function (data) {
        imgModalBody.html(data);
      }
    });
  }

  function asignarTicket(tipo, id) {
    Swal.fire({
      title: '<strong>¿Desea asignarse el Ticket #' + id + '?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, Generar!'
    }).then((result) => {
      if (result.value) {
        //alert(vt_nombramiento);
        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 7,
            aprobado: 2,
            idtick: id
          }, //f de fecha y u de estado.
          beforeSend: function () {
            //$('#response').html('<span class="text-info">Loading response...</span>');
            //alert('message_before')
          },
          success: function (data) {
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

        }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {
          alert(errorThrown);
        });
      }
    })
  }

  function reAsginarTicket(tipo, id) {
    var imgModal = $('#modal-remoto-lg');
    var imgModalBody = imgModal.find('.modal-content');
    //let id_persona = parseInt($('#bar_code').val());
    var thisUrl = '';
    thisUrl = 'tickets/views/reAsginarTicket.php';

    $.ajax({
      type: "GET",
      url: thisUrl,
      dataType: 'html',
      data: {
        tipo: tipo,
        id: id
      },
      beforeSend: function () {
        imgModal.modal('show');
        imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
      },
      success: function (data) {
        imgModalBody.html(data);
      }
    });
  }

  function aprobar(tipo, id) {
    Swal.fire({
      title: '<strong>¿Desea Aprobar el Ticket #' + id + '?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, Generar!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 9,
            aprobado: 1,
            idtick: id
          }, //f de fecha y u de estado.
          beforeSend: function () {
            //$('#response').html('<span class="text-info">Loading response...</span>');
            //alert('message_before')
          },
          success: function (data) {
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
        }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {
          alert(errorThrown);
        });

        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 18,
            idTick: id,
            tipoEnviar: 3,
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
    })
  }

  function terminarTicket(tipo, id) {
    axios.get('tickets/model/tickets.php', {
      params: {
        opcion: 20,
        id: id,
        tipo: 1
      }
      //Si todo funciona se imprime el json con las direcciones
    }).then(function (response) {
      let requerimientos = response.data;
      let json = {}
      var reformattedArray = requerimientos.map(function (o) {
        json[o.nombre] = o.nombre;
      });
      console.log(json)
      Swal.fire({
        title: 'Seleccione el requerimiento finalizado',
        input: 'select',
        inputOptions: json,
        inputPlaceholder: 'Seleccionar requerimientos',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Finalizado!'
      }).then((result) => {
        if (result.value) {
          let desc = ($(".swal2-select").val());
          finalizarTicket(desc, tipo, id)
        }
      })
    }.bind(this)).catch(function (error) {
      console.log(error);
    })
  }

  function finalizarTicket(nombreTicket, tipo, id) {
    console.log(nombreTicket)
    Swal.fire({
      title: 'Ticket finalizado agregue descripción de lo realizado',
      input: 'textarea',
      type: 'question',
      inputPlaceholder: "Agregue Descripción",
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, Finalizar Ticket!',
    }).then((result) => {
      if (result.value) {
        var descripcionTecnico = $(".swal2-textarea").val();
        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 11,
            aprobado: 2,
            idtick: id,
            nombreReq: nombreTicket,
            descripTec: descripcionTecnico
          }, //f de fecha y u de estado.
          beforeSend: function () {
            //$('#response').html('<span class="text-info">Loading response...</span>');
            //alert('message_before')
          },
          success: function (data) {
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
        }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {

          alert(errorThrown);

        });
      }
    })
  }

  function rechazar(tipo, id) {
    Swal.fire({
      title: '<strong>¿Desea Rechazar el Ticket #' + id + '?</strong>',
      text: "",
      type: 'error',
      input: 'textarea',
      inputPlaceholder: "Agregue Descripción",
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡RECHAZAR!'
    }).then((result) => {
      if (result.value) {
        var descripcionRechazo = $(".swal2-textarea").val();
        let idTick = $('#id_ticket').val()
        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 15,
            aprobado: 4,
            idtick: id
          },
          beforeSend: function () { },
          success: function (data) {
            if (data.msg == 'OK') {
              Swal.fire({
                type: 'error',
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
        }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {
          alert(errorThrown);
        });
        var descripcionRechazo = $(".swal2-textarea").val();

        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 18,
            idTick: id,
            tipoEnviar: 2,
            decrip: descripcionRechazo
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
    })
  }

  function calificarTicket(tipo, id) {
    Swal.fire({
      title: '<strong> Califique el apoyo dado al realizar el Ticket #' + id + '</strong>',
      type: 'success',
      input: 'range',
      text: 'Tu calificación es valiosa para nosotros',
      inputAttributes: {
        min: 1,
        max: 5,
        step: 1,
        class: "inputClass"
      },
      inputValue: 3,
      showCancelButton: true,
      confirmButtonColor: 'btn btn-success',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'CALIFICAR!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "GET",
          url: "tickets/model/tickets.php",
          dataType: 'json',
          data: {
            opcion: 16,
            aprobado: 5,
            idtick: id,
            idCalificacion: $('.inputClass').val()
          },
          beforeSend: function () { },
          success: function (data) {
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
        }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {
          alert(errorThrown);
        });
      }
    })
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
</script>