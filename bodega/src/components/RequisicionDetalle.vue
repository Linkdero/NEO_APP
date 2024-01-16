<template>
  <div class="card shadow-card">
    <div class="card-header">
      <span v-html="requisicion.estado"></span>
    </div>
    <div class="col-sm-12">
      <div class="row">
        <div class="col-sm-12">
          <dato-persona texto="No. de Requisición" :dato="requisicion.requisicionNum" icono="fa-regular fa-hashtag"></dato-persona>
          <dato-persona texto="Fecha" :dato="requisicion.fecha" icono="fa-regular fa-calendar-check"></dato-persona>
          <dato-persona texto="Dirección" :dato="requisicion.direccion" icono="fa fa-house"></dato-persona>
          <dato-persona texto="Unidad" :dato="requisicion.unidad" icono="fa-regular fa-house"></dato-persona>
          <dato-persona texto="Bodega" :dato="requisicion.bodega" icono="fa-regular fa-warehouse-full"></dato-persona>

        </div>
        <div class="col-sm-12" >

          <br><br>
          <strong>Observaciones: </strong><br>
          <p style="text-align: justify;" v-html="requisicion.requisicionObservaciones"></p>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
  module.exports = {
    props:["requisicion_id","requsicion_num","evento","tipo"],
    data: function(){
      return {
        requisicion:""
      }
    },
    mounted(){

    },
    created: function(){
      this.getRequisicion();
      this.evento.$on('recargarRequisicion', (data)=>{
        this.getRequisicion();
      })
    },
    methods:{
      getRequisicion: function(){
        //inicio
        axios.get('bodega/model/Requisicion.php',{
          params:{
            opcion:2,
            tipo:1,
            requisicion_id:this.requisicion_id
          }
        })
        .then(function (response) {
          this.requisicion = response.data;
          this.$emit('send_status_req', this.requisicion);

        }.bind(this))
        .catch(function (error) {
            console.log(error);
        });
        //fin
      },
      setOption: function(opc){
        this.option = opc;
        this.evento.$emit('getOpcion', opc);
      },
    }
  }
</script>
