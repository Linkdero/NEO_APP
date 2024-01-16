$(document).ready(function(){

  const app_vue_doc = new Vue({
    el: '#app_documento',
    data: {
      items:'',
      destinatarios:'',
      display:"",
      testSelect:"",
      chk_obli:"",
      chequeado:"",
      chequeado2:"",
      emitido:true,
      recibido:"",
      validar1:"",
      validar2:"",
      obligatorio_interno:false,
      id_categoria:$('#id_categoria').val()
    },
    computed: {},
    created: function(){
      this.get_items(),
      this.get_destinatarios(),
      this.formulario_requerido(),
      this.validaciones()
    },
    methods: {
      validaciones: function(){
        this.validar1=false;
        this.validar2=false;
        this.emitido=true;
        this.recibido=false;
      },
      get_items: function(){
        axios.get('documentos/php/back/listados/get_categorias', {
          params: {
            tipo:142
          }
        }).then(function (response) {
          app_vue_doc.items = response.data;
          //setTimeout(() => {
          //  $("#id_categoria").select2({});
          //}, 200);

        }).catch(function (error) {
          console.log(error);
        });
      },
      get_destinatarios: function(){
        axios.get('documentos/php/back/listados/get_destinatarios', {
          params: {}
        }).then(function (response) {
          app_vue_doc.destinatarios = response.data;
          setTimeout(() => {
            $("#id_destinatarios").select2({});
            $("#id_destinatarios_cc").select2({});
          }, 200);
        }).catch(function (error) {
          console.log(error);
        });
      },
      mostrar_correspondencia(event){
        //console.log(event.currentTarget.value);
        var categoria=event.currentTarget.value;
        //alert(categoria);
        if(categoria==8047 || categoria==8048 || categoria==8049){
          //app_vue_doc.formulario_obligatorio();
          this.emitido=true;
          this.display=false;
          this.validar1=false;
          this.recibido=false;
          this.chequeado2=false;
          this.validar2=true;
        }else{
          this.validar1=false;this.validar2=false;
          this.display=true;
          app_vue_doc.get_destinatarios()
        }

      },
      formulario_requerido: function(){
        if( $('#rd_emitido').is(':checked') )
        {
          this.chequeado=true;
          this.testSelect=true;
          this.recibido=false;
          $('#fg_correlativo_respuesta').removeClass('has-error');
          this.chk_obli=false;
          this.obligatorio_interno=true;
          console.log(this.obligatorio_interno);

        }else{
          this.obligatorio_interno=false;
          console.log(this.obligatorio_interno);
          this.chequeado=false;
          this.emitido=false;
          this.testSelect=true;
          this.chk_obli=true;
        }
      },
      docto_respuesta: function(){
        if( $('#chk_doc_respuesta').is(':checked') ){
          this.chequeado2=true;
        }else{
          this.chequeado2=false;
        }
      },
      formulario_obligatorio: function(){
        if( $('#chk_doc_externo').is(':checked') )
        {
          this.testSelect=true;
          $('#fg_correlativo_respuesta').removeClass('has-error');
          document.getElementById("chk_doc_externo").disabled = false;
        }else{
          this.testSelect=false;
          //document.getElementById("chk_doc_externo").disabled = true;
          $('#fg_correlativo_respuesta').removeClass('has-error');
          $('#id_correlativo_respuesta-error').hide();

          //alert('uncheck')
          document.getElementById("formulario_anterior").hidden=true
        }
      }
    }
  })
})
