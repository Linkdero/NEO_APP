const graficas = httpVueLoader('./tickets/src/components/graficas.vue');
const viewModelTickets = new Vue({
    el: '#graficas',

    data: {
    },
    //Para que se ejecute al inicar el modulo
    created: function () {
        
    },
    components:
    {
        graficas
    },

    //Asi generamos funciones en VUE
    methods: {

    },
})
