

var viewModal = new Vue({
    data: {
        ID: $('#opcion').val(),
    },

    destroyed: function () {

    },

    created: function () {
        //this.abirModal()
    },

    components:
    {

    },

    //Asi generamos funciones en VUE
    methods: {
        abirModal: function(){
            $('#exampleModal2').modal({ show:true });
        }
    }
})

viewModal.$mount('#elmodal');