<template>
  <div class="col-sm-12">
    <table class="table table-sm table-bordered">
      <thead>
        <th class="">Fecha</th>
        <th class="">Fecha</th>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</template>
<style>
</style>
<script>
module.exports = {
  props:["asignacion","asignacion_id","privilegio","estado", "evento","tipo","modalnombre","vista"],
  data() {
    return {
      //solicitud:"",
      sDetalle:"",
      msg:"",
      fecha:""
    }
  },
  mounted(){

  },
  created: function(){

  },
  methods:{
    sepption: function(opc){
      //this.option = opc;
      this.even.$emit('geppcion', opc);
    },
    getBitacoraById: function(){
      axios.get('transportes/model/Asignacion.php',{
        params:{
          opcion:1,
          filtro:0,
          solicitud_id: ','+this.asignacion_id
        }
      })
      .then(function (response) {
        this.sDetalle = response.data[0];
        /*const uniques = [...new Set(this.solicitudes.map(item => item.solicitud_fecha))]
        this.even.$emit('getFechasConteo',uniques);*/
      }.bind(this))
      .catch(function (error) {
          console.log(error);
      });
    }
  }
}
</script>
