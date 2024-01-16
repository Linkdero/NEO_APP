var eventBus = new Vue();
var instanciaV;

  //pedido detalle
  viewModelCuponSolicitar = new Vue({
    //el: '#app_cupones_detalle',
    data: {

      documento:"",
      cupones:"",
      opcion:1,
      destino:"",
      placas:"",
      refer:"",
      departamento:"",
      municipio:"",
      cch1:0,
      contarCupones:0

    },

    computed: {

    },
    created: function(){

    },
    methods: {
      cancelarDevol: function(data) {
        console.log('Formulario cancelado', data);
        this.opcion = data;
      },


    guardaCupon: function(){
      jQuery('.validation_cupones_detalle').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
            jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
        let elem = jQuery(e);
        elem.closest('.form-group').removeClass('has-error').addClass('has-error');
        elem.closest('.help-block').remove();
      },
      success: function(e) {
        var elem = jQuery(e);
        elem.closest('.form-group').removeClass('has-error');
        elem.closest('.help-block').remove();
      },
      submitHandler: function(form){
      let cupon = $("#cupon").val();
      let data = {cupon};
          title = "¿Desea solicitar el/los cupones ?";
          btn_text = "¡Si, Actualizar!";

          Swal.fire({
              title: '<strong>¿Desea actualizar cupones ?</strong>',
              text: title,
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: btn_text
          }).then((result) => {
              console.log(result);
              if (result.value) {
                  $.ajax({
                      type: "POST",
                      url: "vehiculos/php/back/cupones/generar_solicitud.php",
                      data: {
                        /*nro_vale:$('#nro_vale').val(),
                        id_despacha:$('#id_despacha').val(),
                        id_recibe:$('#id_recibe').val(),
                        id_bomba:$('#id_bomba').val(),
                        cant_galones:$('#cant_galones').val(),
                        cant_autor:$('#cant_autor').val(),
                        km_actual:$('#km_actual').val(),
                        observa:$('#observa').val(),*/
                        cupones: viewModelEntregaCupon.cupones,
                        id_destino:$('#id_destino').val(),
                        id_vehiculo:$('#id_vehiculo').val(),
                        id_refer:$('#id_refer').val(),
                        km_actual:$('#km_actual').val(),
                        id_departamento:$('#id_departamento').val(),
                        id_municipio :$('#id_municipio').val(),
                        id_observa :$('#id_observa').val(),
                      },
                      //dataType: "json",
                      success:function(data){
                          $('#modal-remoto-lg').modal('hide');
                          reload_movimientos_en();

                          if(data == 'OK'){
                              Swal.fire({
                                  type: 'success',
                                  title: 'Cupon actualizado',
                                  showConfirmButton: false,
                                  timer: 1100
                              });
                              reload();
                          }else{
                              Swal.fire({
                                  type: 'warning',
                                  title: 'Ocurrio un error',
                                  showConfirmButton: false,
                                  timer: 1100
                              });
                          }
                      }
                  }).fail( function( jqXHR, textSttus, errorThrown){
                      Swal.fire({
                          type: 'warning',
                          title: errorThrown,//'Error al actualizar cupon',
                          showConfirmButton: false,
                          timer: 1100
                      });
                  });
              }
          });
      }
    });
    },

    marcarVarios: function(tipo){

        if(tipo == 1){
          this.cch1 += 1;
        }else{
          this.cch1 -= 1;
        }

      }

    }
  })

  viewModelCuponSolicitar.$mount('#app_solicitar_cupones');
  instanciaP = viewModelCuponSolicitar;


  
