/*Vue.component('select2', {
  props: ['options', 'value'],
  template: '#select2-template',
  mounted: function () {
    var vm = this
    $(this.$el)
      // init select2
      .select2({ data: this.options })
      .val(this.value)
      .trigger('change')
      // emit event on change.
      .on('change', function () {
        vm.$emit('input', this.value)
      })

  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el)
      	.val(value)
      	.trigger('change');
        this.$emit('change',value);
    },
    options: function (options) {
      // update options
      $(this.$el).empty().select2({ data: options })
    }
  },
  destroyed: function () {
    $(this.$el).off().select2('destroy')
  }
})*/

$(document).ready(function(){



  const app_vue_listados = new Vue({
    el: '#listados_app',
    //template: '#demo-template',
    data: {
      message: '!',
      selected: 2,
      empleados:'',
      items:'',
      secretarias: "",
      subsecretarias: "",
      direcciones:"",
      subdirecciones:"",
      departamentos:"",
      secciones:"",
      puestos:"",
      niveles:"",
      plazas_disponibles:"",
      plaza_seleccionada:"",
      //plaza_id: "niklesh",
      plaza_detalle: "",
      options: [
      { id: 1, text: 'Hello' },
      { id: 2, text: 'World' }
    ]

    },
    created: function(){
      this.get_empleados_listado(),
      this.get_plazas_disponibles()

    },
    methods: {

      /*get_subsecres: function(){
        axios.get('empleados/php/back/listados/get_opciones_vue.php', {
            params: {
              nivel:3,
              tipo:887,
              superior:4,
              opcion:1
            }
        })
          .then(function (response) {
            app_vue.options = response.data;
            alert(response.data);
          })
          .catch(function (error) {
            console.log(error);
          });
      },*/
      get_empleados_listado: function(){
        axios.get('empleados/php/back/listados/get_empleados_listado.php', {
          params: {

            }
          }).then(function (response) {
            app_vue_listados.empleados = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        },
        get_plazas_disponibles: function(){
          axios.get('empleados/php/back/listados/get_plazas_disponibles.php', {
            params: {

              }
            }).then(function (response) {
              app_vue_listados.plazas_disponibles = response.data;
              setTimeout(() => {
                $("#id_plaza").select2({
                });
                $('#id_plaza').on("change",function(){
                  app_vue_listados.get_plaza_seleccionada($(this).val());// = $(this).val();
                  console.log('Plaza : '+$(this).val());
              });
              }, 400);
            }).catch(function (error) {
              console.log(error);
            });
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
              }, 400);
            })
            .catch(function (error) {
              console.log(error);
            });
          //}, 200);
        },
        echange:function(e){
          alert(e);
        },
        get_plaza_seleccionada:function(id_plaza){
          axios.get('empleados/php/back/plazas/get_plaza_by_id.php', {
            params: {
              id_plaza: id_plaza
            }
          }).then(function (response) {
            if(response.data.id_plaza!=null){
              $('#plaza_detalle').show();
              app_vue_listados.plaza_detalle = response.data;
            }else{
              $('#plaza_detalle').hide();
            }
            //alert(response.data);
          }).catch(function (error) {
            console.log(error);
          });
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
        }
      }
    })
  })
