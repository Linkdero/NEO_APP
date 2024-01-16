var eventBus = new Vue();
var instanciaV;

app_vue_vale = new Vue({
      //el: '#app_despacha_vale',
      data: {
          nroVale: $('#id_nro_vale').val(),
          types: $('#id_tipo').val(),
          despacha: "",
          recibe: "",
          bomba: "",
          arreglo:""
      },
      destroyed: function(){
        this.app_vue_vale;
      },
    created: function(){
          this.get_despacha(),
          this.get_recibe(),
          this.get_bomba()
    },
    methods: {
      recibirVale: function(data){
        this.arreglo = data;
        console.log(data);

      },
      get_despacha: function(){
          axios.get('vehiculos/php/back/listados/get_despacha.php', {
            params: {}
          }).then(function (response) {
            app_vue_vale.despacha = response.data;
          }).catch(function (error) {
            console.log(error);
          });
      },

      get_recibe: function(){
        axios.get('vehiculos/php/back/listados/get_recibe.php', {
          params: {}
        }).then(function (response) {
          app_vue_vale.recibe = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },      

      get_bomba: function(){
        axios.get('vehiculos/php/back/listados/get_bomba.php', {
          params: {id_tipo:$('#id_tipo_combustible').val()}
        }).then(function (response) {
          app_vue_vale.bomba = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },  

    }
  })

  app_vue_vale.$mount('#app_despacha_vale');
  instanciaV = app_vue_vale;
  
  

  