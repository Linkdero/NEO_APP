  var viewModelFicha = new Vue({
    //el: '#archivo_app',
    data: {
      pdf:'',
      key:0,
      id_persona:$('#id_persona').val(),
      url:'empleados/php/front/empleados/urbina.pdf',
      //url:'empleados/php/front/contratos/files/',
      /*nContrato:$('#id_archivo').val(),
      nResolucion:$('#id_archivor').val(),
      docContrato: false,
      docResolucion:false,*/
      config: {
        toolbar: false
      },
    },
    destroyed: function(){
      this.viewModelFicha;
    },
    created: function(){
      imprimirFicha(this.id_persona,2)

      this.getFicha();
      this.key +=1;
    },
    components: {
      VuePdfApp: window["vue-pdf-app"]
    },
    methods: {
      printFicha: function(){
        imprimirFicha(this.id_persona,1)
      },
      getFicha: function(){
        setTimeout(() => {
          this.pdf = $('#id_pdf').val() ;//+ 'urbina.pdf';
        },900);
      }
    }

  })

  viewModelFicha.$mount('#ficha_app');
