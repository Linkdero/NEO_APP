<template>
  <div class="modal-header">
    <h4 v-if="tipo == 1" class="modal-title">Detalle del Bien # {{ sicoin_code }}</h4>
    <h4 v-if="tipo == 2" class="modal-title">Generar Certificación</h4>
    <h4 v-if="tipo == 3" class="modal-title">Entregar Certificación</h4>

    <ul class="list-inline ml-auto mb-0">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-personalizado btn-sm salida"  data-dismiss="modal">
          <span name="options" id="option3" autocomplete="off" > Salir </span>
        </label>
      </div>


      <!--<li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
        <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>-->
    </ul>

  </div>
</template>
<script>
module.exports = {
  props:["bien","bien_id","privilegio", "evento", "sicoin_code","tipo"],
  data: function(){
    return {
      bienD:""
    }
  },
  mounted(){

  },
  created: function(){

  },
  methods:{

  }


}

</script>
