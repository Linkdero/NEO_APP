<template>
  <div class="col-sm-12">
    <table class="table table-sm table-bordered table-striped">
      <thead>
        <th class="text-center">Vehículo</th>
        <th class="text-center">Conductor</th>
        <th class="text-center">Tipo</th>
        <th class="text-center">Fecha</th>
        <th class="text-center"><span class="text-danger"><i class="fa fa-trash-alt"></i></span></th>
      </thead>
      <tbody>
        <tr v-for="(v, index) in vehiculos">
          <td class="text-center">{{ v.vehiculo_text }}</td>
          <td class="text-center">{{ v.conductor_text }}</td>
          <td class="text-center">{{ v.tipo_transporte_text }}</td>
          <td class="text-center">{{ v.fecha_salida }}</td>
          <td class="text-center"><span class="btn btn-sm btn-danger" @click="deleteRow(index, v)"><i class="fa fa-trash-alt"></i></span></td>
        </tr>
      </tbody>
    </table>
    <button v-if="vehiculos.length > 0" class="btn btn-sm btn-info" @click="asignarVehiculo()"><i class="fa fa-save"></i> Asignar Vehículo</button>
  </div>


</template>
<script>
  module.exports = {
    props:["codigo","columna","solicitud","solicitud_id","privilegio","estado", "evento","nombremodal"],
    data() {
      return {
        vehiculos:[],
        idDestino:1144,
        incremental:0
      }
    },
    mounted(){

    },
    created: function(){

      this.evento.$on('getVehiculoSeleccionado', (valor) => {
        this.addNewRow(valor);
      });
    },
    methods:{
      setOption: function(opc){
        //this.option = opc;
        this.evento.$emit('getOpcion', opc);
      },
      addNewRow: function(data){
        console.log(data);
        this.vehiculos.push({
          tipo_vehiculo:data.tipo_vehiculo,
          conductor_id:data.conductor_id,
          conductor_text:data.conductor_text,
          vehiculo_id:data.vehiculo_id,
          vehiculo_text:data.vehiculo_text,
          tipo_transporte_id:data.tipo_transporte_id,
          tipo_transporte_text:data.tipo_transporte_text,
          fecha_salida:data.fecha_salida
          /*id_vehiculo:data.id_vehiculo,
          vehiculo:data.vehiculo*/
        })
      },
      deleteRow: function(index, d){
        var idx = this.vehiculos.indexOf(d);
        console.log(idx, index);
        if (idx > -1) {
          this.vehiculos.splice(idx, 1);
        }
      },
      limpiarLista: function(){
        this.vehiculos.splice(0, this.vehiculos.length);
      },
      asignarVehiculo: function(){
        var thisInstance = this;
        if (thisInstance.vehiculos.length >= 1) {
          Swal.fire({
            title: '<strong>¿Desea guardar la información?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "transportes/model/Transporte.php",
                dataType: 'json',
                data: {
                  opcion:6,
                  solicitud_id:thisInstance.solicitud_id,
                  vehiculos: thisInstance.vehiculos
                }, //f de fecha y u de estado.
                beforeSend: function () {
                },
                success: function (data) {
                  //exportHTML(data.id);
                  //recargarDocumentos();
                  if (data.msg == 'OK') {
                    $('#'+thisInstance.nombremodal).modal('hide');
                    thisInstance.evento.$emit('recargarTablaTransporte');
                    thisInstance.evento.$emit('clearSeleccionSolicitudes');
                    Swal.fire({
                      type: 'success',
                      title: 'Vehículo y conductor asignados.',
                      showConfirmButton: false,
                      timer: 1100
                    });//impresion_pedido(data.id);
                  } else {
                    Swal.fire({
                      type: 'error',
                      title: 'Error',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).done(function () {
              }).fail(function (jqXHR, textSttus, errorThrown) {
                alert(errorThrown);
              });
            }
          })
        } else {
          Swal.fire({
            type: 'error',
            title: 'Debe seleccionar al menos un vehículo',
            showConfirmButton: false,
            timer: 1100
          });
        }
      }
    }
  }
</script>
