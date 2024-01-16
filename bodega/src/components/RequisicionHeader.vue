<template>
  <div class="modal-header" v-if="requisicion_id > 0">
    <h4 class="modal-title">Detalle de la Requisición # {{ requsicion_num }}</h4>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado active btn-sm" @click="setOption(1)">
          <input type="radio" name="options" id="option1" autocomplete="off" checked> Detalle
        </label>
        <!--<label class="btn btn-personalizado btn-sm" @click="setOption(2)">
          <input type="radio" name="options" id="option2" autocomplete="off" > Empleados
        </label>
        <label class="btn btn-personalizado btn-sm" id="actions1Invoker" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown">
        <span  autocomplete="off"> Imprimir</span>
        <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px; margin-right:20px" aria-labelledby="actions1Invoker" >
          <div class="card overflow-hidden" style="margin-top:-20px;">
            <div class="card-header d-flex align-items-center py-3">
              <h2 class="h4 card-header-title">Opciones:</h2>
            </div>
          </div>
        </div>
        </label>-->
        <label class="btn btn-personalizado btn-sm"  data-dismiss="modal">
          <span name="options" id="option3" autocomplete="off" > Salir </span>
        </label>
      </div>
    </ul>
  </div>
  <div class="modal-header" v-else>
    <h4 class="modal-title">Asignar Vehículo</h4>
    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado btn-sm"  data-dismiss="modal">
          <span name="options" id="option3" autocomplete="off" > Salir </span>
        </label>
      </div>
    </ul>
  </div>

</template>
<script>
  module.exports = {
    props:["requisicion_id","requsicion_num"],
    data: function(){
      return {
        empleados:""
      }
    },
    mounted(){

    },
    created: function(){

    },
    methods:{
      setOption: function(opc){
        this.option = opc;
        this.evento.$emit('getOpcion', opc);
      },
    }
  }
</script>
