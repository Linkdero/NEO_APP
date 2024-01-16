
var medidaFormModel = new Vue({

  data: {
    medId:$('#med_id').val(),
    tipo:$('#tipo_id').val(),
    arrayMedidas: [],
    medida:""

  },
  created: function(){
    if(this.tipo == 2){
      this.getMedidaById();
    }
  },
  computed: {
    total: function(){
      if (!this.criterios) {
        return 0;
      }
      return this.criterios.reduce(function (total, value) {
        return total + Number(value.actividad_valor);
      }, 0);
    }
  },
  methods: {
    getMedidaById: function(){
      var thisInstance = this;
      axios.get('documentos/php/back/functions_insumos', {
        params: {
          opcion:5,
          med_id: this.medId
        }
      }).then(function (response) {
        thisInstance.medida = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    saveMedida: function(){
      //inicio
      var thisInstance = this;
      jQuery('.jsValidacionMedida').validate({
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

              Swal.fire({
              title: '<strong>¿Desea registrar la información de la medida?</strong>',
              text: "",
              type: 'question',
              showCancelButton: true,
              showLoaderOnConfirm: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, registrar!'
              }).then((result) => {
              if (result.value) {
                $.ajax({
                type: "POST",
                url: "documentos/php/back/functions_insumos.php",
                dataType: 'json',
                data: {
                  opcion:4,
                  tipo:thisInstance.tipo,
                  med_id:thisInstance.medId,
                  med_nom:$('#med_nom').val(),
                  med_estado:( $('#med_estado').is(':checked') ) ? 1 : 0,
                }, //f de fecha y u de estado.
                beforeSend:function(){
                },
                success:function(data){
                  if(data.msg == 'OK'){

                    Swal.fire({
                      type: 'success',
                      title: 'Datos registrados.',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: 'Error',
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
        },
        rules: {

        }
      });
      //fin
    }
  }
})

medidaFormModel.$mount('#appMedidaForm');
