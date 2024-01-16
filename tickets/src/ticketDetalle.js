import { eBus } from './ticketModel.js';
const ticketdetalle = httpVueLoader('./tickets/src/components/descripcionTicket.vue');

var viewModelDetalleTicket = new Vue({
  data: {
    idTicket: $('#id_ticket').val(),
    keyReload:0
  },

  created: function () {
    this.keyReload ++;
  },

  components:
  {
    ticketdetalle
  },

  //Asi generamos funciones en VUE
  methods: {

  }
})

viewModelDetalleTicket.$mount('#AppDescripcionTicket');