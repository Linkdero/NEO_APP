const modal = httpVueLoader('./tickets/src/components/modal.vue');

const viewModelAsignarTicket = new Vue({
  data: {
    idTicket: $('#id_ticket').val(),
    keyReload:0
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

viewModelAsignarTicket.$mount('#aprobacion');