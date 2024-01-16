$(document).ready(function(){
  var app = new Vue({
    el: '#myapp',
    data: {
      viaticos: "",
      cliente_id: 0
    },
    methods: {
      allRecords: function(){

        axios.get('viaticos/php/front/viaticos/ajaxfile.php')
        .then(function (response) {
            app.viaticos = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      viatico_by_id: function(){
        if(this.cliente_id > 0){

          axios.get('viaticos/php/front/viaticos/ajaxfile.php', {
              params: {
                  cliente_id: this.cliente_id
              }
          })
            .then(function (response) {
              app.viaticos = response.data;
            })
            .catch(function (error) {
              console.log(error);
            });
        }

      }
    }
  })

})
