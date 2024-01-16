$(document).ready(function(){

  const appVueJusDetalle = new Vue({
    el: '#appjusDetalle',
    data: {
      items:'',
      justificacionDetalle:'',
      pedido:"",
      insumos:"",
      cronograma:'',
      docto_id:$('#docto_id').val(),
      validacion:1,
      categoria:$('#categoria_id').val(),
      criterios:'',
      condiciones_contrato:'',
      condiciones_econo:'',
      listado_literales:'',
      suma_:'',
      respuesta:false,
      dictamenes:''
    },
    created: function(){
      this.getJustificacionById(),
      this.get_pedido(),
      this.getLoadEditTable()
    },
    computed: {

    },
    methods: {
      getJustificacionById: function(){
        //if(this.docto_id>0){
          axios.get('documentos/php/back/justificacion/get_justificacion_by_id', {
            params: {
              docto_id:this.docto_id
            }
          }).then(function (response) {

            appVueJusDetalle.justificacionDetalle = response.data;
            this.respuesta=true;
            //alert(response.data.validacion);
          }).catch(function (error) {
            console.log(error);
          });
        //}
      },
      getOpcion: function(valor){
        this.validacion=valor;
      },
      get_pedido:function(){
        setTimeout(() => {
          axios.get('documentos/php/back/pedido/get_pedido_by_id', {
            params: {
              ped_tra:$('#ped_tra').val()
            }
          }).then(function (response) {
            appVueJusDetalle.pedido = response.data;
          }).catch(function (error) {
            console.log(error);
          });

          axios.get('documentos/php/back/pedido/get_insumos_by_pedido', {
            params: {
              ped_tra:$('#ped_tra').val()
            }
          }).then(function (response) {
            appVueJusDetalle.insumos = response.data;

          }).catch(function (error) {
            console.log(error);
          });

          axios.get('documentos/php/back/justificacion/get_dictamen', {
            params: {
              docto_id:this.docto_id
            }
          }).then(function (response) {
            appVueJusDetalle.dictamenes = response.data;

          }).catch(function (error) {
            console.log(error);
          });
        }, 200);

      },
      get_dictamenes:function(){

      },
      getLoadEditTable: function() {
        setTimeout(() => {
          //valor criterio
          $('.string').editable({
            url: 'documentos/php/back/justificacion/update_string.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'textarea',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
                appVueJusDetalle.getJustificacionById()
              }
            }
          });

        }, 400);

      }

    }
  })
})
