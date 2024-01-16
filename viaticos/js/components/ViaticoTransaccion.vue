<template>
  <div>
    <div v-if="viatico.dias_pendiente >= 0 && viatico.status != 940">
      <div v-if="tipos >= 1 && tipol == 0">
        <button id="" class="btn btn-info btn-sm" @click="cargarInput(1,1)"><i class="fa fa-check"></i> Actualizar horas </button>
        <button id="" v-if="viatico.personas_c==0" class="btn btn-info btn-sm" @click="seguimientoViatico(939)"><i class="fa fa-check"></i> Constancia </button>
      </div>
      <div v-if="tipol == 1 &&  tipos == 0">
        <button id="" class="btn btn-info btn-sm" @click="cargarInput(2,2)"><i class="fa fa-check"></i> Actualizar montos </button>

      </div>
      <div v-else-if="tipol > 1 &&  tipos == 0">
        <div class="alert alert-soft-danger" >
          <i class="fa fa-minus-circle alert-icon mr-3"></i>
          <span>Al momento de liquidar las facturas no puede seleccionar mas de un empleado.</span>
        </div>
      </div>
      <div v-if="tipol >= 1 &&  tipos >= 1">
        <div class="alert alert-soft-danger" >
          <i class="fa fa-minus-circle alert-icon mr-3"></i>
          <span>Debe seleccionar a las personas que estén en el mismo proceso.</span>
        </div>
      </div>
      <button id="" v-if="viatico.personas_l==0" class="btn btn-info btn-sm" @click="seguimientoViatico(940)"><i class="fa fa-check"></i> Liquidar </button>
    </div>
    <div v-else-if="viatico.dias_pendiente == -10 && viatico.status != 940">
      <div class="alert alert-soft-danger" >
        <i class="fa fa-minus-circle alert-icon mr-3"></i>
        <span>Ya no puede dar seguimiento a este nombramiento.</span>
      </div>
    </div>
  </div>
</template>
<script>
module.exports = {
  props:["viatico","id_viatico","privilegio","estado_nombramiento", "evento", "tipos", "tipol"],
  data: function(){
    return {
      empleados:""
    }
  },
  mounted(){

  },
  created: function(){
    this.evento.$on('recibirArreglo', (data) => {
      this.empleados = data;
      alert(data)
    })
  },
  methods:{
    cargarInput: function(id,operacion){
      this.evento.$emit('mostrarModal',true);
      this.evento.$emit('setOperacion',operacion);
      this.evento.$emit('recibirEmpleados', this.empleados);
    },
    seguimientoViatico: function(codigo){
      this.evento.$emit('seguimientoViatico',codigo);
      setTimeout(() => {
        this.evento.$emit('recargarViaticosTable','');
      }, 300);
    },
    reloadTable: function(){
      this.evento.$emit('recargarViaticosTable','');
    }
  }


}

</script>
