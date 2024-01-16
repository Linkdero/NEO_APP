<template>
  <div class="row">
    <div class="col-sm-6">
      <dato-persona icono="fa fa-barcode" texto="Código SICOIN" :dato="bienD.bien_sicoin_code" tipo="1"></dato-persona>
      <dato-persona icono="fa fa-money-bill-wave-alt" texto="Monto" :dato="bienD.bien_monto" tipo="1"></dato-persona>
    </div>
    <div class="col-sm-6">
      <dato-persona icono="far fa-calendar-check" texto="Fecha de adquisición" :dato="bienD.bien_fecha_adquisicion" tipo="1"></dato-persona>
      <dato-persona icono="fa-regular fa-list-ol" texto="Renglón" :dato="bienD.bien_renglon_id" tipo="1"></dato-persona>
    </div>

    <!--<div class="col-sm-12">
      <dato-persona icono="far fa-edit" texto="Descripción" :dato="bienD.bien_descripcion" tipo="1"></dato-persona>
    </div>-->

    <!--<div class="col-sm-12" v-if="tipo == 1">
      <hr>
      <h3>Última verificación</h3>
      <span v-html="bienD.bien_verificacion"></span>
    </div>-->
    <div class="col-sm-12">
      <hr>
      <form class="jsValidacionActualizarDetalle" id="formValidacionActualizarDetalle">
        <div class="row">
          <div class="col-sm-12" v-if="bienD.bien_renglon_id == 325">

            <h3>Datos del vehículo</h3>
            <div class="row">
              <campo tipo="text" row="col-sm-4" :valor="bienD.bien_placa" label="Placa" codigo="cod_bien_placa" requerido="true"></campo>
              <campo tipo="number" row="col-sm-4" :valor="bienD.bien_modelo" label="Modelo" codigo="cod_bien_modelo" requerido="true"></campo>
              <campo tipo="text" row="col-sm-4" :valor="bienD.bien_chasis" label="Chasis" codigo="cod_bien_chasis" requerido="true"></campo>
              <campo tipo="text" row="col-sm-4" :valor="bienD.bien_motor" label="No. motor" codigo="cod_bien_motor" requerido="true"></campo>
              <campo tipo="text" row="col-sm-4" :valor="bienD.bien_marca_t" label="Marca" codigo="cod_bien_marca_t" requerido="true"></campo>
              <campo tipo="text" row="col-sm-4" :valor="bienD.color" label="Color" codigo="cod_color" requerido="true"></campo>
            </div>
            <hr>
          </div>
          <campo tipo="textarea" row="col-sm-12" :valor="bienD.bien_descripcion" label="Descripción para Certificación" codigo="idDescripcionNormal" requerido="true"></campo>
          <campo tipo="textarea" row="col-sm-12" :valor="bienD.bien_descripcion_completa" label="Descripción Completa" codigo="idDescripcionCompleta"></campo>
          <div class="col-sm-12" v-if="tipo == 2">
            <button class="btn btn-sm btn-info btn-block" @click="actualizarDescripcion()"><i class="fa-regular fa-check-circle"></i> Actualizar</button>
          </div>
        </div>
      </form>

    </div>
    <!--{{ bienD }}<br>
    Código de Bien: {{ bienD.bien_sicoin_code }}-->
  </div>
</template>
<script>
module.exports = {
  props:["bien","bien_id","privilegio", "evento","tipo","vista"],
  data: function(){
    return {
      bienD:""
    }
  },
  mounted(){

  },
  created: function(){
    this.getBienById()
  },
  methods:{
    getBienById: function(){
      //inicio
      axios.get('inventario/model/Inventario', {
        params:
        {
          bien_id: this.bien_id,
          opcion:3
        }
      })
      .then(function (response) {
        this.bienD = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });

      //fin
    },
    actualizarDescripcion: function(){
      //inicio
      var thisInstance = this;
      jQuery('.jsValidacionActualizarDetalle').validate({
          ignore: [],
          errorClass: 'help-block animated fadeInDown',
          errorElement: 'div',
          errorPlacement: function(error, e) {
              jQuery(e).parents('.form-group > div').append(error);
          },
          highlight: function(e) {
              var elem = jQuery(e);

              elem.closest('.form-group').removeClass('has-error').addClass('has-error');
              elem.closest('.help-block').remove();
          },
          success: function(e) {
              var elem = jQuery(e);

              elem.closest('.form-group').removeClass('has-error');
              elem.closest('.help-block').remove();
          },
          submitHandler: function(form){
            var form = $('#formValidacionActualizarDetalle');
            Swal.fire({
              title: '<strong>¿Desea actualizar la información del Bien?</strong>',
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
                    url: "inventario/model/Inventario.php",
                    dataType: 'json',
                    data: {
                      opcion:13,
                      bien_id:thisInstance.bien_id,
                      descripcion:$('#idDescripcionNormal').val(),
                      bien_placa:$('#cod_bien_placa').val(),
                      bien_modelo:$('#cod_bien_modelo').val(),
                      bien_chasis:$('#cod_bien_chasis').val(),
                      bien_motor:$('#cod_bien_motor').val(),
                      bien_marca_t:$('#cod_bien_marca_t').val(),
                      color:$('#cod_color').val(),
                    }, //f de fecha y u de estado.f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    if(data.msg == 'OK'){
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                      if(thisInstance.vista == 'bienesseleccionados'){
                        thisInstance.evento.$emit('recargarBienesSeleccionados');
                        thisInstance.evento.$emit('changeOpcionBienesSeleccionados',1);
                      }else{
                        //$('#modal-remoto-lg').modal('hide');
                        //data = { 'clase' : 'btn-ce2', 'opcion': 1}
                        EventBus.$emit('recargarCertificacionesTable',data);
                      }


                    }else{
                      Swal.fire({
                        type: 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }

                  }

                }).done( function() {


                }).fail( function( jqXHR, textSttus, errorThrown){

                  alert(errorThrown);

                });

              }

            })


        }

      });
      //fin
    },
    setEditable: function(){
      if (x == 1) {
        //inicio
        $('.descripcion').editable({
          url: 'viaticos/php/back/viatico/update_fecha_general.php',
          mode: 'popup',
          ajaxOptions: { dataType: 'json' },
          format: 'dd-mm-yyyy',
          viewformat: 'dd-mm-yyyy',
          datepicker: {
            weekStart: 1
          },
          type: 'date',
          display: function (value, response) {
            return false;   //disable this method
          },
          success: function (response, newValue) {
            if (response.msg == 'Done') {
              $(this).text(response.valor_nuevo);
              viewModelViaticoDetalle.getViatico();
              viewModelViaticoDetalle.calcular_viaticos();
            }
          }
        });
        $('.horas_').editable({
          url: 'viaticos/php/back/viatico/update_hora_general.php',
          mode: 'popup',
          ajaxOptions: { dataType: 'json' },
          type: 'select',
          source: this.horas,
          display: function (value, response) {
            return false;   //disable this method
          },
          success: function (response, newValue) {
            if (response.msg == 'Done') {
              $(this).text(response.valor_nuevo);
              viewModelViaticoDetalle.getViatico();
              viewModelViaticoDetalle.calcular_viaticos();

            }
          }
        });

        $('.motivo_').editable({
          url: 'viaticos/php/back/viatico/update_motivo.php',
          mode: 'popup',
          ajaxOptions: { dataType: 'json' },
          type: 'textarea',
          display: function (value, response) {
            return false;   //disable this method
          },
          success: function (response, newValue) {
            if (response.msg == 'Done') {
              $(this).text(response.valor_nuevo);

            }
          }
        });
        //fin
      }
    },
    changeOpcion: function(opc){
      if(this.vista == 'bienesseleccionados'){
        this.evento.$emit('changeOpcionBienesSeleccionados',opc);
      }
    }
  }


}

</script>
