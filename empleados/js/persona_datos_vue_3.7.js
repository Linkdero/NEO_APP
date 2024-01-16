var eventBus = new Vue();
var instanciaP;

  viewModelPersona = new Vue({
    //el: '#persona_app',
    data: {
      id_persona:$('#id_persona').val(),
      familia:"",
      persona:"",
      escolaridad:"",
      opcion: 0,
      opciones:"",
      showButton: false,
      altura:0,
      idTipoCatalogo:"",
      privilegio:"",
      showCatalogo:false,
      cargarCatalogo:0
    },
    destroyed: function(){
      this.viewModelPersona;
    },
    mounted() {
      //this.$refs.infoBox.focus();
    },
    beforeDestroy() {
      window.removeEventListener('scroll', this.onScroll)
    },
    beforeCreate: function(){
      //alert('cargando');
      $('#data_').html('<div class="loaderr"></div>');
    },
    created: function(){
      this.get_persona_by_id();
      this.getOpciones();
      this.getCatalogoOpciones();
      this.getOpcion(1);
      this.getPrivilegio();

      this.$on('event_parent', function(id){
  			console.log('Event from parent component emitted', id);
  		});
      eventBus.$on('regresarPrincipal', (opc) => {
        this.getOpcion(opc);
      });
    },
    methods: {
      eventChild: function(id) {
  			console.log('Event from child component emitted', id),
        this.getOpcion(id);
  		},
  		eventParent: function() {
  			this.$emit('event_parent', 1)
  		},
      get_persona_by_id: function(){
        axios.get('empleados/php/back/persona/get_persona_by_id', {
          params: {
            id_persona:this.id_persona,
            tipo:1
          }
        }).then(function (response) {
          viewModelPersona.persona = response.data;

        }).catch(function (error) {
          console.log(error);
        });
      },
      get_familiares: function(){
        if(this.id_persona > 0){
          axios.get('empleados/php/back/persona/get_familiares.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelPersona.familia = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      getEscolaridad: function(){
        if(this.id_persona > 0){
          axios.get('empleados/php/back/persona/get_escolaridad.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelPersona.escolaridad = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      getOpcion: function(opc) {
        //alert(opc);
        if(opc==20){
          this.showCatalogo = true;
          this.cargarCatalogo = 1;
        }else{
          this.showCatalogo = false;
          this.cargarCatalogo = 0;
          this.opcion = opc;
        }

        setTimeout(() => {
          let width=this.$refs.infoBox.clientWidth;
          let height=this.$refs.infoBox.clientHeight;
          this.altura = height;
          console.log(this.altura);
          if(this.altura > 513){
            this.showButton=true;
          }else{
            this.showButton=false;
          }
        }, 100);

      },
      getOpciones: function() {
        //genera el menu de opciones
        if(this.id_persona > 0){
          axios.get('empleados/php/back/empleado/opciones.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelPersona.opciones = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      getCatalogoOpciones: function() {
        //genera el menu de opciones
        if(this.id_persona > 0){
          axios.get('empleados/php/back/listados/get_opciones_catalogo.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelPersona.opcionesCatalogo = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      submitItem(evt){
            evt.preventDefault();
            document.getElementById("formActualizarPersona").submit();
       },
       down: function() {
         $('.scrollable-div-persona').animate({scrollTop: $('section.ok').offset().top }, 'slow');
         return false;
       },
       onScroll: function(e) {
         //this.showButton=true;
         var obj = document.getElementById('div-persona');
         var y = $('.scrollable-div-persona').scrollTop();
         if(this.altura > 513){
           if (y > 100) {
             this.showButton = false;
           } else {
             this.showButton = true;
           }
         }else{
           this.showButton = false;
         }

      },
      /*getPrivilegio: function(){
        axios.get('empleados/php/back/functions/evaluar_privilegio', {
          params: {
            modulo:1163,
            pantalla:166
          }
        }).then(function (response) {
          this.privilegio  = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      },*/
      getPrivilegio: function(){
        axios.get('empleados/php/back/functions/evaluar_privilegio_asignaciones', {
        }).then(function (response) {
          this.privilegio  = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      },
    }
})

viewModelPersona.$mount('#persona_app');
instanciaP = viewModelPersona;



//})
