<template>
  <div>
    <div class="js-validation-sustituye " action="" method="" id="s_form">
      <input id="id_persona" :value="personas" hidden></input>
      <input id="id_renglon" :value="renglones" hidden></input>
      <div class="form-group">
        <div class="">
          <div class="">
            <label for="id_empleado_sustituye">Sustituir por*</label>
            <div class=" input-group  has-personalizado" >
              <select class="form-control form-control_ chosen-select-width" id="id_empleado_sustituye">
                <option v-for="emp in empleados" v-bind:value="emp.id_persona">{{ emp.empleado }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group text-right form-material">
        <button id="" class="btn btn-info btn-sm btn-block" @click="sustituirEmpleado()"><i class="fa fa-check"></i> Sustituir </button>
      </div>
    </div>
  </div>
</template>
<script>

module.exports = {

  props:["viatico","id_viatico","privilegio","estado_nombramiento", "evento", "tipos", "tipol", "operacion", "personas", "renglones","id_empleado"],
  data: function(){
    return {
      keyReload:0,
      empleados:[]
    }
  },
  components:{
    //viaticoconstancia, viaticoliquidacion
  },
  mounted(){
  },
  created: function(){
    this.keyReload ++;
    this.getEmpleadosSustityen();
  },
  methods:{
    sustituirEmpleado: function(){
      var thisInstance = this;
      //conteo=table_pendientes.rows().count();
      vt_nombramiento=$('#id_viatico').val();
      empleado_actual=$('#id_persona').val();
      empleado_sustituye=$('#id_empleado_sustituye').val();
      id_renglon=$('#id_renglon').val();

      Swal.fire({
        title: '<strong></strong>',
        text: '¿Desea sustituir este empleado?',
        type: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '!Si, sustituir!'
      }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              //dataType: "json",
              url: "viaticos/php/back/viatico/sustituir_empleado.php",
              data:
              {
                vt_nombramiento,
                empleado_actual,
                empleado_sustituye,
                id_renglon
              },
              beforeSend:function(){},
              success:function(data){
                if(data=='1'){
                  Swal.fire({
                    type: 'error',
                    title: 'El empleado no puede ser el mismo',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }else if(data=='2'){
                  Swal.fire({
                    type: 'error',
                    title: '¡Este empleado ya se encuentra en la lista ',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }else
                if(data=='ok'){
                  Swal.fire({
                    type: 'success',
                    title: '¡Sustitución generada!',
                    showConfirmButton: false,
                    timer: 2000
                  });

                  thisInstance.evento.$emit('recargarEmpleadosList');
                  thisInstance.evento.$emit('mostrarModal',false);


                }

                //alert(conteo);
              }
            }).done( function() {
            }).fail( function( jqXHR, textSttus, errorThrown){
            });

          }
        });


      //alert(conteo);
    },
    getEmpleadosSustityen: function(){
      axios.get('viaticos/php/back/listados/get_empleados_sustituye.php',{
        params:{
          vt_nombramiento: this.viatico.nombramiento
        }
      })
      .then(function (response) {
          this.empleados = response.data;
          $('#id_empleado_sustituye').select2({});
      }.bind(this))
      .catch(function (error) {
          console.log(error);
      });
    }


  }
}

</script>
