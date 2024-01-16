$(document).ready(function(){


  const app_vue_plaza = new Vue({
    el: '#em_plaza_app',
    data: {
      message: '!',
      empleado_plaza:'',
      items:'',
      id_persona:$('#id_gafete').val()

    },
    created: function(){
      this.get_empleado_plaza(),
      this.get_items(),
      this.get_status_baja()

      /*,
      this.get_direccion_f()*/
      /*this.get_direcciones(),
      this.get_puestos()*/
      //this.allEmpleados();

    },
    methods: {
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
    }
  }

  })


})
