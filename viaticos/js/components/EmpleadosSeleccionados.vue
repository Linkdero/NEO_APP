<template>
  <div class="card shadow-card">
    <!-- Modal content -->
    <div class="card-header">
      <h3>Empleados seleccionados: {{ empleados_pro.length }}</h3>
    </div>
    <div class="emps_select" >
      <div class="row">
        <table class="table table-sm table-striped">
          <tr class="" v-for="(e, index) in empleados_pro">
            <td class=" ">{{ index + 1 }}</td>
            <td class=" ">
              {{ e.empleado }}
            </td>
          </tr>
        </table>
      </div>


    </div>


  </div>
</template>
<script>

module.exports = {

  props:["viatico","id_viatico","privilegio","estado_nombramiento", "evento", "tipos", "tipol", "operacion", "personas", "renglones","id_persona"],
  data: function(){
    return {
      empleados_pro:[]
    }
  },
  components:{
  },
  mounted(){
  },
  created: function(){
    this.getEmpleadosSeleccionados();
  },
  methods:{
    getEmpleadosSeleccionados: function(){
      axios.get('viaticos/php/back/viatico/get_empleados_para_procesar.php',{
        params:{
          id_persona: this.personas//$('#id_persona').val()
        }
      })
      .then(function (response) {
          this.empleados_pro = response.data;
      }.bind(this))
      .catch(function (error) {
          console.log(error);
      });
    }
  }
}

</script>
<style>
.emps_select{
  max-height:300px; overflow-y: auto; overflow-x: hidden
}
</style>
