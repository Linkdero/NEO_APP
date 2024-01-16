<template>
  <div v-html="opciones.response">


  </div>
</template>
<script>

module.exports = {

  props:["id_viatico","show_menu","evento"],
  data: function(){
    return {
      opciones:''
    }
  },
  components:{
    //viaticoconstancia, viaticoliquidacion
  },
  mounted(){
  },
  created: function(){
    this.keyReload ++;
  },
  methods:{
    getMenuImpresiones: function(){
      axios.get('viaticos/php/back/viatico/menu/menu_impresion.php', {
        params: {
          correlativo: this.id_viatico
        }
      })
      .then(function (response) {
        this.opciones = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    //console.log(this.show_menu);
    this.evento.$on('recargarMenu', (data) => {
      //alert('message')
      this.getMenuImpresiones();
    })

  }
}

</script>
