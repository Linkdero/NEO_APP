var events = [
  {'Date': new Date(2018, 2, 29), 'Title': 'Jueves Santo.'},
  {'Date': new Date(2018, 2, 30), 'Title': 'Viernes Santo'},
  {'Date': new Date(2018, 3, 16), 'Title': 'Asuto Impuesto'},
  {'Date': new Date(2018, 4, 1), 'Title': 'Dia del Trabajo'},
  {'Date': new Date(2018, 5, 30), 'Title': 'Día del ejército'}
];
var settings = {};
var element = document.getElementById('caleandar');
caleandar(element, events, settings);
