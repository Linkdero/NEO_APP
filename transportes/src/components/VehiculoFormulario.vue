<template>
  <form class="jsValidacionAsignarVehiculoGlobal">
    <div class="row">

      <div class="col-sm-3">
          <div class="row">
            <div class="text-right" class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="">
                    <label class="text-white">..</label>
                    <div class=" input-group  has-personalizado" >
                      <label class="css-input switch switch-success"><input class="chequeado" id="rd_propios" v-model="picked" name="pago_opcional" type="radio" value="1144" @change="onChange($event)" checked/><span></span> Propios</label>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="text-right" class="col-sm-12">
              <div class="form-group">
                <div class="">
                  <div class="">
                    <label class="text-white">..</label>
                    <div class=" input-group  has-personalizado" >
                      <label class="css-input switch switch-success"><input class="chequeado" id="rd_externo" v-model="picked" name="pago_opcional" type="radio" value="1147" @change="onChange($event)"/><span></span> Arrendados</label>
                    </div>
                  </div>
                </div>
              </div>
              </div>
          </div>
        <!--<input type="radio" id="rd_propios" value="1144" v-model="picked" @change="onChange($event)" checked/>
        <label for="one">Propios</label>

        <input type="radio" id="rd_externo" value="1147" v-model="picked" @change="onChange($event)"/>
        <label for="two">Arrendados</label>-->
        <!--<i style="" class="fa fa-sync fa-spin text-center"></i>-->
      </div>
      <div class="col-sm-9">
        <div class="row">
          <conductores row="col-sm-6" label="Conductor asignado" codigo="id_quien_lleva" requerido="true" :valor="arreglo.conductor_id"></conductores>
          <vehiculoslist  codigo="id_vehiculo" columna="col-sm-6" :evento="evento" requerido="true" :valor="arreglo.vehiculo_id"></vehiculoslist>
          <combo v-bind:class="[tipo == 2 || tipo == 3 ? 'col-sm-6' : 'col-sm-6']" codigo="id_tipo_transporte" label="Tipo de Transporte" :arreglo="tipoTransporte" tipo="3" requerido="true" :valor="arreglo.tipo_transporte"></combo>
          <campo row="col-sm-6" label="Fecha de salida: *" requerido="true" codigo="fecha_salida_a" tipo="datetime-local" :valor="arreglo.fecha_salida"></campo>
        </div>
      </div>


      <!--{{ fechasCount }}-->

      <div v-bind:class="[tipo == 2 || tipo == 3 ? 'col-sm-12' : 'col-sm-1']">
        <br>
        <button v-if="tipo == 1" class="btn btn-sm btn-info btn-block" @click="addNewRow()"><i class="fa fa-plus-circle"></i></button>
        <button v-if="tipo == 2 && arreglo.seguimiento_id == 0 && vista == 'asignacion'" class="btn btn-sm btn-info" @click="addNewRow()"><i class="fa fa-plus-circle"></i> Editar asignación</button>
        <button v-if="tipo == 3" class="btn btn-sm btn-info" @click="addNewRow()"><i class="fa fa-plus-circle"></i> Asignar vehículo</button>
      </div>
    </div>
  </form>
</template>
<script>
const vehiculoslist = httpVueLoader('././VehiculosList.vue');
module.exports = {
  props:["solicitud","solicitud_id","privilegio","estado", "evento","tipo", "seleccionado", "arreglo","vista"],
  data() {
    return {
      picked:1144,
      solicitudes: [],
      msg:"",
      fecha:"",
      seguimientoId:5,
      tipoTransporte:[{"id_item":"","item_string":"-- Seleccionar --"},{"id_item":"1","item_string":"Llevar y Traer"},{"id_item":"2","item_string":"Llevar"},{"id_item":"3","item_string":"Traer"}],

    }
  },
  mounted(){

  },
  components:{
    vehiculoslist
  },
  created: function(){

  },
  methods:{
    onChange(event) {
      var data = event.target.value;
      console.log(data);
      this.evento.$emit('getDestinoVehiculo', data);
    },
    setOption: function(opc){
      //this.option = opc;
      this.evento.$emit('getOpcion', opc);
    },
    addNewRow: function(){
      //inicio

      var thisInstance = this;
      console.log(thisInstance.arreglo);
      jQuery('.jsValidacionAsignarVehiculoGlobal').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          //regformhash(form,form.password,form.confirmpwd);
          Swal.fire({
            title: '<strong>¿Desea asignar este vehículo y el conductor?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, asignar!'
          }).then((result) => {
            if (result.value) {
              var tipo_vehiculo = ($('#rd_propios').is(':checked')) ? 1144: 1147;
              thisInstance.arreglo = {
                tipo_vehiculo:tipo_vehiculo,
                conductor_id:$('#id_quien_lleva').val(),
                conductor_text:$("#id_quien_lleva option:selected").text(),
                vehiculo_id:$('#id_vehiculo').val(),
                vehiculo_text:$("#id_vehiculo option:selected").text(),
                tipo_transporte_id:$('#id_tipo_transporte').val(),
                tipo_transporte_text:$("#id_tipo_transporte option:selected").text(),
                fecha_salida:$('#fecha_salida_a').val(),
                reng_num:thisInstance.arreglo.reng_num
              };
              if(thisInstance.vista == 'asignacion'){
                //inicio
                $.ajax({
                  type: "POST",
                  url: "transportes/model/Transporte.php",
                  dataType: 'json',
                  data: {
                    opcion:11,
                    asignacion_id:thisInstance.solicitud_id,
                    tipo:thisInstance.tipo,
                    datos:thisInstance.arreglo
                  }, //f de fecha y u de estado.
                  beforeSend: function () {
                  },
                  success: function (data) {
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if (data.msg == 'OK') {
                      thisInstance.setOption(1);
                      thisInstance.evento.$emit('recargarVehiculosAsignacion');
                      Swal.fire({
                        type: 'success',
                        title: data.message,
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
                //fin
              }else{
                //inicio
                thisInstance.evento.$emit('getVehiculoSeleccionado',thisInstance.arreglo);
                //fin
              }

              thisInstance.arreglo = '';

            }
          })
        }
      });
      //fin
    }
  }
}
</script>
