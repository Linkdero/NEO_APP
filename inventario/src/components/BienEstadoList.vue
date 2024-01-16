<template>
  <combo :arreglo="arrayEstado" tipo="2" requerido="true" label="Estdado" codigo="cmbEstadoBien" row="col-sm-12"></combo>
</template>
<script>
module.exports = {
  props:["bien","bien_id","privilegio", "evento"],
  data: function(){
    return {
      arrayEstado:[]
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
          opcion:5
        }
      })
      .then(function (response) {
        this.arrayEstado = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });

      //fin
    }
  }


}

</script>
