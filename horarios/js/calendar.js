

let calendarEl = document.getElementById('calendar');
let calendar;

function cargar_calendario(opt) {
  var e = document.getElementById("direccion");
  var dir = e.options[e.selectedIndex].text;
  if (opt == 2) {
    calendar.destroy();
  }
  $.ajax({
    type: "POST",
    url: "horarios/php/back/calendario/get_mes.php",
    dataType: "json",
    data: {
      opcion: 2,
      dir: dir
    },
    success: function (events) {
      calendar = new FullCalendar.Calendar(calendarEl, {
        defaultDate: new Date(),
        navLinks: true,
        editable: false,
        eventLimit: true,
        events: events,
        plugins: ['interaction', 'dayGrid', 'timeGrid', 'list', 'rrule'],
        locale: 'es',
        nowIndicator: true,
        header: {
          left: 'prev,next,today',
          center: 'title',
          right: ''
        },
        eventClick: function (info) {
          let params = info.event.extendedProps;
          $.ajax({
            url: 'horarios/php/front/calendario/ver_vacaciones.php',
            type: 'POST',
            data: { vac_id: params["vac_id"] },
            success: function (response) {
              $('.modal-content').html(response);
              $('#modal-remoto-lg').modal('show');
            }
          });
        }
      });
      calendar.render();
    }
  });
}
cargar_calendario();





