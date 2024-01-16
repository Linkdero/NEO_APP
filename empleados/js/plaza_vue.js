$(document).ready(function(){


  const app_vue_plaza_tr = new Vue({
    el: '#plaza_app',
    data: {
      message: '!',
      plaza_detalle:'',
      items:'',
      id_plaza:$('#id_plaza').val()
    },
    created: function(){
      this.get_plaza_by_id()

    },
    methods: {
      get_plaza_by_id: function(){
        axios.get('empleados/php/back/plazas/get_plaza_by_id', {
          params: {
            id_plaza: $('#id_plaza').val()
          }
        }).then(function (response) {
          app_vue_plaza_tr.plaza_detalle = response.data;

        }).catch(function (error) {
          console.log(error);
        });

    }/*,
      get_empleado_plaza: function(){
        if(this.id_persona > 0){

          axios.get('empleados/php/back/plazas/plaza_detalle_empleado.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            app_vue_plaza.empleado_plaza = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }

      },
      get_items: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_items.php', {
            params: {
              id_catalogo:29,
              tipo:1
            }
          })
          .then(function (response) {
            app_vue_plaza.items = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },

    get_status_baja: function(){
      //setTimeout(() => {
        //console.log($('#id_subsecretaria_f').val());
        axios.get('empleados/php/back/listados/get_items.php', {
          params: {
            id_catalogo:29,
            tipo:2
          }
        })
        .then(function (response) {
          app_vue_plaza.items = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      //}, 200);
    }*/
  }

  })


})
