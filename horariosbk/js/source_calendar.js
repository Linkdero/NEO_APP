var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
  initialDate: '2020-06-12',
  editable: true,
  selectable: true,
  businessHours: true,
  dayMaxEvents: true, // allow "more" link when too many events
  events: [
    {
      title: 'All Day Event',
      start: '2020-06-01'
    },
    {
      title: 'Long Event',
      start: '2020-06-07',
      end: '2020-06-10'
    },
    {
      groupId: 999,
      title: 'Repeating Event',
      start: '2020-06-09T16:00:00'
    },
    {
      groupId: 999,
      title: 'Repeating Event',
      start: '2020-06-16T16:00:00'
    },
    {
      title: 'Conference',
      start: '2020-06-11',
      end: '2020-06-13'
    },
    {
      title: 'Meeting',
      start: '2020-06-12T10:30:00',
      end: '2020-06-12T12:30:00'
    }
  ]
});

calendar.render();