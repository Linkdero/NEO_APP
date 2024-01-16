$(document).ready(function(){


  const app_vue_vale = new Vue({
    el: '#app_nuevo_vale',
    data: {
    //   message: '!',
    //   empleado_plaza:'',
    //   items:'',
    //   id_persona:$('#id_persona').val()
        conductores: "",
        correlativo: "",
        destino: "",
        placas: "",
        eventos: "",
        tipos: "",
        capa: "",
        capaTanque:"",
    },
    created: function(){
        this.get_correla(),
        this.get_conductores(),
        this.get_destino_combustible(),
        this.get_placas(),
        this.get_eventos(),
        this.get_capa()
    //   this.get_empleado_plaza(),
    //   this.get_items()
      /*,
      this.get_direccion_f()*/
      /*this.get_direcciones(),
      this.get_puestos()*/
      //this.allEmpleados();

    },
    methods: {
        get_correla: function(){
            // if(this.id_persona > 0){
    
              axios.get('vehiculos/php/back/listados/get_correla.php', {
                params: {
                //   id_persona: this.id_persona
                }
              }).then(function (response) {
                app_vue_vale.correlativo = response.data;
              }).catch(function (error) {
                console.log(error);
              });
            // }
    
          },
    
        get_conductores: function(){
          axios.get('vehiculos/php/back/listados/get_conductores.php', {
            params: {
            //   id_persona: this.id_persona
            }
          }).then(function (response) {
            app_vue_vale.conductores = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        
      },

  
      get_destino_combustible: function(){
        axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
          params: {
          //   id_persona: this.id_persona
          }
        }).then(function (response) {
          app_vue_vale.destino = response.data;
          setTimeout(() => {
              
            $("#id_destino").select2({
            });
            $('#id_destino').on("change",function(){
              app_vue_vale.get_placas($('#id_destino').val());
              app_vue_vale.getTipoCombustible();
              // $("chk_Tanque").val()=false;

            
          });
          }, 400);       
        }).catch(function (error) {
          console.log(error);
        });
      
      },

      get_placas: function(id_des){
        axios.get('vehiculos/php/back/listados/get_placas.php', {
          params: {
            id_destino:id_des
          }
        }).then(function (response) {
          if(id_des==1144 || id_des==1147){
            $('#div_vehiculo').show();
            $('#div_caracter').hide();
            app_vue_vale.placas = response.data;
          setTimeout(() => {
            $("#id_vehiculo").select2({
            });
            $('#id_vehiculo').on("change",function(){
              app_vue_vale.getTipoCombustible(id_des);
              app_vue_vale.get_capa_change();
              if( $('#chk_Tanque').is(':checked') ){
                app_vue_vale.get_capa();
              }
              
            });
            
          }, 400);
          }else{
            document.getElementById("id_vehiculo").selectedIndex = "";
            $('#chk_Tanque').val('false');
            $('#id_galones').val('0.00');
            $('#div_caracter').show();
            $('#div_vehiculo').hide();
          }
          app_vue_vale.getTipoCombustible(id_des);
          
        }).catch(function (error) {
          console.log(error);
        });
      },

      get_eventos: function(){
        axios.get('vehiculos/php/back/listados/get_eventos.php', {
          params: {
          }
        }).then(function (response) {
          app_vue_vale.eventos = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },

      get_tipo_Comb: function(){
        axios.get('vehiculos/php/back/listados/get_tipo.php', {
          params: {
          }
        }).then(function (response) {
          app_vue_vale.tipos = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },

      getTipoCombustible: function(id_des){
        id_car=0;
        if(id_des==1144 || id_des==1147){
          id_car=$('#id_vehiculo').val();
        }
        axios.get('vehiculos/php/back/listados/get_tipo.php', {
          params: {
            id_vehiculo:id_car
          }
        }).then(function (response) {
          app_vue_vale.tipos = response.data;
          
        }).catch(function (error) {
          console.log(error);
        });
      },

      get_capa: function(){
        axios.get('vehiculos/php/back/listados/get_capa.php', {
          params: {
            id_vehiculo:$('#id_vehiculo').val()
          }
        }).then(function (response) {
          app_vue_vale.capaTanque = response.data;
          if( $('#chk_Tanque').is(':checked') ){
            $('#id_galones').val(response.data.capaT);
          }else{
            $('#chk_Tanque').val('false');
            $('#id_galones').val('0.00');
          }
          
        }).catch(function (error) {
          console.log(error);
        });
      },
      get_capa_change: function(){
        axios.get('vehiculos/php/back/listados/get_capa.php', {
          params: {
            id_vehiculo:$('#id_vehiculo').val()
          }
        }).then(function (response) {
          app_vue_vale.capaTanque = response.data;           
          $('#id_galones').val(response.data.capaT);
        }).catch(function (error) {
          console.log(error);
        });
      },

    //   get_items: function(){
    //     //setTimeout(() => {
    //       //console.log($('#id_subsecretaria_f').val());
    //       axios.get('empleados/php/back/listados/get_items.php', {
    //         params: {
    //           id_catalogo:29,
    //           tipo:1
    //         }
    //       })
    //       .then(function (response) {
    //         app_vue_plaza.items = response.data;
    //       })
    //       .catch(function (error) {
    //         console.log(error);
    //       });
    //     //}, 200);
    //   }

    }
  })


})
