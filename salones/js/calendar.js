

let calendarEl = document.getElementById('calendar');
let calendar;

function cargar_calendario(opt) {
  if (opt == 2) {
    calendar.destroy();
  }
  $.ajax({
    type: "POST",
    url: "salones/php/back/listados/get_solicitudes.php",
    dataType: "json",
    data: { opcion: 2 },
    success: function (events) {
      calendar = new FullCalendar.Calendar(calendarEl, {
        defaultDate: new Date(),
        navLinks: true,
        editable: false,
        eventLimit: true,
        defaultView: 'timeGridWeek',
        events: events,
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'rrule'],
        locale: 'es',
        nowIndicator: true,
        header: {
          left: 'prev,next,today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek'
        },
        eventClick: function (info) {
          let params = info.event.extendedProps;
          $.ajax({
            url: 'salones/php/front/control/solicitud_detalle.php',
            type: 'post',
            data: { params },
            success: function (response) {
              $('#modal-body').html(response);
              $('#modal-remoto').modal('show');
            }
          });
        }
      });
      calendar.render();
    }
  });
}

cargar_calendario();





