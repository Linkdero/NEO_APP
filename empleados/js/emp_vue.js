$(document).ready(function(){


  const app_vue = new Vue({
    el: '#em_app',
    data: {
      message: '!',
      secretarias: "",
      subsecretarias: "",
      direcciones:"",
      subdirecciones:"",
      departamentos:"",
      secciones:"",
      puestos:"",
      niveles:"",
      empleado:"",
      id_persona:$('#id_gafete').val(),
      id_superior:$('#id_subsecretaria_f').val(),
      empleado_id:""
    },
    created: function(){
      this.get_empleado(),
      this.get_secretaria_f(),
      this.get_subsecretaria_f(),
      this.get_puestos(),
      this.get_niveles_f(),
      this.get_empleado_id()/*,
      this.get_direccion_f()*/
      /*this.get_direcciones(),
      this.get_puestos()*/
      //this.allEmpleados();

    },
    methods: {
      get_empleado_id: function(){
        if(this.id_persona > 0){

          axios.get('empleados/php/back/empleado/get_empleado_id.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            app_vue.empleado_id = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      get_empleado: function(){
        if(this.id_persona > 0){

          axios.get('empleados/php/back/puestos/puesto_detalle.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            app_vue.empleado = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }

      },
      get_secretaria_f: function(){
        axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:2,
              tipo:887,
              superior:0,
              opcion:1
            }
        })
          .then(function (response) {
            app_vue.secretarias = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      get_subsecretaria_f: function(){
        axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:3,
              tipo:887,
              superior:4,
              opcion:1
            }
        })
          .then(function (response) {
            app_vue.subsecretarias = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      get_direccion_f: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:4,
              tipo:887,
              superior:$('#id_subsecretaria_f').val(),
              opcion:1
            }
          })
          .then(function (response) {
            app_vue.direcciones = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      get_subdireccion_f: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:$('#id_direccion_f').val(),
              tipo:887,
              superior:0,
              opcion:2
            }
          })
          .then(function (response) {
            app_vue.subdirecciones = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      get_departamento_f: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:$('#id_subdireccion_f').val(),
              tipo:887,
              superior:0,
              opcion:3
            }
          })
          .then(function (response) {
            app_vue.departamentos = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      get_secciones_f: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:$('#id_departamento_f').val(),
              tipo:887,
              superior:0,
              opcion:4
            }
          })
          .then(function (response) {
            app_vue.secciones = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      get_puestos: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_items.php', {
            params: {
              id_catalogo:31,
              tipo:0
            }
          })
          .then(function (response) {
            app_vue.puestos = response.data;
            setTimeout(() => {
              $("#id_puesto_f").select2({
              });

            });
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      get_niveles_f: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_direcciones.php', {
            params: {
              nivel:0,
              tipo:887,
              superior:0,
              opcion:5
            }
          })
          .then(function (response) {
            app_vue.niveles = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      }/*,
      puestos: function(){
        axios.get('viaticos/php/back/listados/get_horas.php', {
            params: {
              tipo:35
            }
        })
          .then(function (response) {
            app_vue.transportes = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      }
      /*,

      created: function(){
        this.viatico_by_id()
      }*/
    }
  })

  function get_data(){
    app_vue.$refs.viatico_by_id();
  }
})
