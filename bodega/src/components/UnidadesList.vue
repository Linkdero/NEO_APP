<template>
  <!--inicio-->
  <div :class="columna">
    <div class="form-group">
      <div class="">
        <div class="">
          <label for="id_unidad">Unidad*</label>
          <div class=" input-group  has-personalizado">
            <select class="js-select2 form-control form-control-sm" id="id_unidad" name="combo1" required>
              <option v-for="u in unidades" v-bind:value="u.id_departamento"><strong>{{ u.id_departamento }}</strong> - {{ u.nombre}}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- fin -->
</template>
<script>
module.exports = {
  props:["columna_global","columna","evento"],

  data(){
    return {
      unidades: []
    }
  },
  methods:{
    getUnidades: function() {
      axios.get('bodega/model/Requisicion', {
        params: {
          opcion: 8
        }
      }).then(function (response) {
        this.unidades = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  mounted: function(){
    this.getUnidades();
  }
}

</script>
