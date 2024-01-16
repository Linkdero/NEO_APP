$(document).ready(function(){
  const app_vue_jus = new Vue({
    el: '#app_justificacion',
    data: {
      pedidos:"",
      pedido:"",
      insumos:"",
      pedido_seleccionado:0,
      message3: "",
      totalcharacter3: 450,
      message4: "",
      totalcharacter4: 450,
      dictamenes:[{dictamen:'',fecha:''}],
      mostrarDictamen:0
    },
    computed: {},
    created: function(){
      this.get_pedidos()
    },
    methods: {
      charCount3: function(){
        var total=450;
        var left = total - this.message3.length;
         this.totalcharacter3 = left;

       },
       charCount4: function(){
         var total=450;
         var left = total - this.message4.length;
          this.totalcharacter4 = left;

        },
      validaciones: function(){
        this.validar1=false;
        this.validar2=false;
        this.emitido=true;
        this.recibido=false;
      },
      get_pedidos: function(){
        axios.get('documentos/php/back/listados/get_pedidos', {
          params: {
            tipo:1
          }
        }).then(function (response) {
          app_vue_jus.pedidos = response.data;
          setTimeout(() => {
            $("#id_pedido").select2({});
            $( "#id_pedido" ).change(function(){
              var id=$(this).attr('id');
              var valor =$('#'+id).val();
              app_vue_jus.get_pedido_detalle(valor);
            });
          }, 200);

        }).catch(function (error) {
          console.log(error);
        });
      },
      get_pedido_detalle(ped_tra){
        //var ped_tra=event.currentTarget.value;
        //alert(ped_tra);
        if(ped_tra!=''){
          this.pedido_seleccionado=1;
        }else{
          this.pedido_seleccionado=0;
        }
        axios.get('documentos/php/back/pedido/get_pedido_by_id', {
          params: {
            ped_tra:ped_tra
          }
        }).then(function (response) {
          app_vue_jus.pedido = response.data;
        }).catch(function (error) {
          console.log(error);
        });

        axios.get('documentos/php/back/pedido/get_insumos_by_pedido', {
          params: {
            ped_tra:ped_tra
          }
        }).then(function (response) {
          app_vue_jus.insumos = response.data;

        }).catch(function (error) {
          console.log(error);
        });
      },
      crear_justificacion: function(){

      },
      validacionMostrarConfirma: function(){
        if( $('#rd_dg_si').is(':checked') )
        {
          this.mostrarDictamen=1;
        }else{
          this.mostrarDictamen=0;
        }
      },
      deleteRow(index, d) {
        var idx = this.dictamenes.indexOf(d);
        console.log(idx, index);
        if (idx > -1) {
          this.dictamenes.splice(idx, 1);
        }
      },
      addNewRow() {
        console.log(this.dictamenes.length);
        this.dictamenes.push({dictamen:'',fecha:''});
      }
    }
  })

})
