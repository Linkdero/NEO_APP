  var viewModelContratos = new Vue({
    //el: '#archivo_app',
    data: {
      pdf:'',
      tipo:$('#tipo').val(),
      url:'empleados/php/front/contratos/files/',
      nContrato:$('#id_archivo').val(),
      nResolucion:$('#id_archivor').val(),
      docContrato: false,
      docResolucion:false,
      config: {
        toolbar: false
      },
    },
    created: function(){

      this.viewContrato();
    },
    components: {
      VuePdfApp: window["vue-pdf-app"]
    },
    methods: {
      viewContrato: function(){
        if(this.nContrato != ''){
          this.docContrato = true;
        }
        if(this.nResolucion != ''){
          this.docResolucion = true;
        }

        if(this.tipo == 2){
          document.getElementById("opt2").checked = true;
          setTimeout(() => {
            this.pdf = this.url + this.nResolucion;
          },500);
        }else{
          setTimeout(() => {
            this.pdf = this.url + this.nContrato;
          },500);
        }


      },
      getOpcion: function(opc){
        if(opc == 1){
          setTimeout(() => {
            this.pdf = this.url + this.nContrato;
          },500);
        }else{
          setTimeout(() => {
            this.pdf = this.url + this.nResolucion;
          },500);
        }
      }
    }

  })

  viewModelContratos.$mount('#archivo_app');
