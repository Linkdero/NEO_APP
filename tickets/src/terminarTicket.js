import { eBus } from './ticketModel.js';

var viewModalTerminarTicket = new Vue({
  data: {
    idTicket: $('#id_ticket').val(),
    keyReload:0
  },

  destroyed: function () {
    this.viewModalTerminarTicket;
  },

  created: function () {
    this.keyReload += 1;
  },

  components:
  {
    modal
  },

  //Asi generamos funciones en VUE
  methods: {

  }
})

viewModalTerminarTicket.$mount('#finalizarTicket');