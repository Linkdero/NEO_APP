<template>
  <div class="modal-header">
    {{ arrayCertificaciones }}
  </div>
</template>
<script>
module.exports = {
  props:["bien","certificacion_id","privilegio", "evento", ],
  data: function(){
    return {
      arrayCertificaciones:[]
    }
  },
  mounted(){

  },
  created: function(){

  },
  methods:{
    getCertificacionesById: function(){
      //inicio
      axios.get('inventario/model/Inventario', {
        params:
        {
          opcion:13,
          certificacion_id: this.certificacion_id
        }
      })
      .then(function (response) {
        this.arrayCertificaciones = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });

      //fin
    }
  }


}

</script>
