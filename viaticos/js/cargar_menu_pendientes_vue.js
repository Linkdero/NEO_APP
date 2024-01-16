$(document).ready(function(){
  const app_vue_pendientes = new Vue({
    el: '#pendientes_div',
    data: {
      pendientes_n:""
    },
    created: function(){
      this.nombramientos_pendientes()

    },
    methods: {
      nombramientos_pendientes: function(){

        axios.get('viaticos/php/back/listados/nombramientos_pendientes.php',{

        })
        .then(function (response) {
            app_vue_pendientes.pendientes_n = response.data;
            //alert(response.data)
            console.log(response.data);
        })
        .catch(function (error) {
            console.log(error);
        });
      }



    }
  })

})
