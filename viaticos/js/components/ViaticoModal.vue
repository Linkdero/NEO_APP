<template>
  <div id="myModal" class="modal-vue" style="overflow-x:hidden">
    <!-- Modal content -->
    <!--Personas: {{ personas }} || renglones: {{ renglones }}-->

    <div class="modal-vue-content" style="margin-top:0px; margin-left:50px; width: 96.4%; max-height:800px">
    <!--<div class="modal-vue-content" style="margin-top:0px; margin-left:200px; width: 1000px; max-height:800px">-->
      <div class="card shadow-card">
        <header class="header-color">
          <h4 class="card-header-title" >
            <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
            </i><span class="text-white" v-if="operacion == 1"> Actualizar Horarios</span><span v-else-if="operacion == 2 || operacion == 4" class="text-white" > Calcular montos</span><span v-else-if="operacion == 3" class="text-white" > Sustituir empleado</span>
          </h4>
          <span style="margin-top:-2.2rem; margin-right:-15px"class="close-icon" @click="hideModal()">
            <i class="fa fa-times"></i>
          </span>
        </header>
        <div class="card-body">
          <!--peracion: {{ operacion }}-->

          <viaticoconstancia :key="'zz'+llave" v-if="operacion == 1" :viatico="viatico" :tipos="tipos" :tipol="tipol" :evento="evento" :personas="personas" :renglones="renglones"></viaticoconstancia>
          <viaticoliquidacion :key="'yy'+llave" v-if="operacion == 2 || operacion == 4" :viatico="viatico" :tipos="tipos" :tipol="tipol" :evento="evento" :personas="personas" :renglones="renglones"></viaticoliquidacion>
          <sustituir :key="'nmnm'+llave" v-if="operacion == 3" :viatico="viatico" :tipos="tipos" :tipol="tipol" :evento="evento" :personas="personas" :renglones="renglones" :id_empleado="id_persona"></sustituir>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const viaticoconstancia = httpVueLoader('./ViaticoConstancia.vue');
const viaticoliquidacion = httpVueLoader('./ViaticoLiquidacion.vue');
const sustituir = httpVueLoader('./Sustituir.vue');

module.exports = {

  props:["viatico","id_viatico","privilegio","estado_nombramiento", "evento", "tipos", "tipol", "operacion", "personas", "renglones","id_persona","llave"],
  data: function(){
    return {
      keyReload:0
    }
  },
  components:{
    viaticoconstancia, viaticoliquidacion, sustituir//, empleados
  },
  mounted(){
  },
  created: function(){
    this.keyReload ++;
  },
  methods:{
    hideModal: function(){
      this.evento.$emit('mostrarModal',false);
    },
    cargarInput: function(){
    },
    seguimientoViatico: function(codigo){
      //this.evento.$emit('seguimientoViatico',codigo);
    }
  }
}

</script>
