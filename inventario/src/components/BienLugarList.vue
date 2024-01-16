<template>
  <combo :arreglo="arrayLugares" tipo="2" requerido="true" label="Ubicación" codigo="cmbUbicacionBien" row="col-sm-12"></combo>
</template>
<script>
module.exports = {
  props:["bien","bien_id","privilegio", "evento"],
  data: function(){
    return {
      arrayLugares:[]
    }
  },
  mounted(){

  },
  created: function(){
    this.getEstadoList()
  },
  methods:{
    getEstadoList: function(){
      //inicio
      axios.get('inventario/model/Inventario', {
        params:
        {
          opcion:6,
          tipo: 2
        }
      })
      .then(function (response) {
        this.arrayLugares = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });

      //fin
    }
  }


}

</script>
