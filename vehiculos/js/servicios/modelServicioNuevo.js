var eventBus = new Vue();
var instanciaSN;

viewModelServicioNuevo = new Vue({
  //el: '#app_nuevo_vale',
  data: {
    idVehiculo: 0
  },
  created: function () {
    eventBus.$on('getIdVehiculo', (valor) => {
      this.idVehiculo = valor;
    });
  },
  methods: {
    generarOrdenServicio: function () {
      const thisInstance = this;
      jQuery('.jsValidacionNuevoServicio').validate({
        //jQuery('.jsValidationAsignarCupon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          let elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {

          var form = $('#formValidacionNuevoServicio');

          Swal.fire({
            title: '<strong>¿Desea generar la orden de servicio ?</strong>',
            //text: title,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Si generar'
          }).then((result) => {
            console.log(result);
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "vehiculos/php/back/servicios/action/generar_orden.php",
                data: form.serialize(), //f de fecha y u de estado.
                dataType: "json",
                success: function (data) {
                  //reload_movimientos_en();

                  if (data.msg == 'OK') {
                    Swal.fire({
                      type: 'success',
                      title: 'Orden Generada',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    $('#modal-remoto').modal('hide');
                    instancia.reloadTableServicios(5487);
                    //reload();
                  } else {
                    Swal.fire({
                      type: 'warning',
                      title: 'Ocurrio un error',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).fail(function (jqXHR, textSttus, errorThrown) {
                Swal.fire({
                  type: 'warning',
                  title: errorThrown, //'Error al actualizar cupon',
                  showConfirmButton: false,
                  timer: 1100
                });
              });
            }
          });
        }
      });
    }
  },
})
viewModelServicioNuevo.$mount('#app_orden_servicio');
instanciaSN = viewModelServicioNuevo;
//})
CKEDITOR.replace("id_descripcion", {
  language: 'es',
  width: '100%',
  height: '170',
  removeButtons: 'Source,Save,Preview,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CopyFormatting,BidiLtr,BidiRtl,Language,Unlink,Anchor,Link,Flash,Smiley,Iframe,BGColor,Maximize,ShowBlocks,About,Cut,Copy,Paste,PasteText,PasteFromWord,FontSize,Image,Table,Format,Styles,CreateDiv,Print,NewPage,Templates,HorizontalRule,PageBreak'
});
