
var insumoFormModel = new Vue({

  data: {
    pprId:$('#ppr_id').val(),
    tipo:$('#tipo_id').val(),
    arrayMedidas: [],
    insumo:""

  },
  created: function(){
    this.getMedidas();
    if(this.tipo == 2){
      this.getInsumoById();
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
    getMedidas: function(){
      var thisInstance = this;
      axios.get('documentos/php/back/functions_insumos', {
        params: {
          opcion: 2
        }
      }).then(function (response) {
        thisInstance.arrayMedidas = response.data;
        setTimeout(() => {

          $('#cmb_medida_id').select2({});

          $('#cmb_medida_id').on('select2:select', function (e) {
          });
        }, 900);


      }).catch(function (error) {
        console.log(error);
      });
    },
    getInsumoById: function(){
      var thisInstance = this;
      axios.get('documentos/php/back/pedido/get_insumo_seleccionado', {
        params: {
          Ppr_id: this.pprId
        }
      }).then(function (response) {
        thisInstance.insumo = response.data;
        setTimeout(() => {
          $('#cmb_medida_id').val(thisInstance.insumo.Med_id).trigger('change');
        },900)
      }).catch(function (error) {
        console.log(error);
      });
    },
    saveInsumo: function(){
      //inicio
      var thisInstance = this;
      jQuery('.jsValidacionInsumo').validate({
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
              title: '<strong>¿Desea registrar la información del insumo?</strong>',
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
                  opcion:3,
                  tipo:thisInstance.tipo,
                  Ppr_id:thisInstance.pprId,
                  ppr_cod:$('#ppr_cod').val(),
                  ppr_nom:$('#ppr_nom').val(),
                  cmb_medida_id:$('#cmb_medida_id').val(),
                  ppr_presentacion:$('#ppr_presentacion').val(),
                  ppr_cod_presentacion:$('#ppr_cod_presentacion').val(),
                  ppr_descripcion:$('#ppr_descripcion').val(),
                  id_renglon:$('#id_renglon').val(),
                  Ppr_estado:( $('#Ppr_estado').is(':checked') ) ? 1 : 0,
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

insumoFormModel.$mount('#appInsumoForm');
