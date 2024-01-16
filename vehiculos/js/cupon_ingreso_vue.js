var eventBus = new Vue();
var instanciaV;

//pedido detalle
viewModelCuponIngreso = new Vue({
  //el: '#app_cupones_detalle',
  data: {

    documento: "",
    cupones: "",
    opcion: 1,
    destino: "",
    placas: "",
    refer: "",
    departamento: "",
    municipio: "",
    cch1: 0,
    contarCupones: [],
    cuponInicial: 0,
    cuponFinal: 0,
    bandera: 0
  },

  computed: {

  },
  methods: {
    ingresar: function () {
      let validar = this.validarForm()
      if (validar == 0) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        Toast.fire({
          icon: 'error',
          type: 'error',
          title: 'Rellenar todos los campos'
        })
      } else {
        let inicial = parseInt($("#inicial").val())
        let final = parseInt($("#final").val())
        let monto = parseInt($("#monto").val())
        let documento = $("#documento").val()
        let observaciones = $("#observaciones").val()
        Swal.fire({
          title: '¿Ingresar nuevo lote de cupones?',
          text: 'Ingreso del cupon ' + inicial + ' al ' + final,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ingresar nuevo lote',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "GET",
              url: "vehiculos/php/back/cupones/ingresoCupones.php",
              dataType: 'json',
              data: {
                lote1: inicial,
                lote2: final,
                opcion: 1
              },
              success:
                function (data) {
                  if (data.cupon == 0) {
                    let rango = final - inicial
                    $.ajax({
                      type: "GET",
                      url: "vehiculos/php/back/cupones/ingresoCupones.php",
                      dataType: 'json',
                      data: {
                        lote: inicial,
                        rango: rango,
                        monto: monto,
                        documento: documento,
                        observaciones: observaciones,
                        opcion: 2
                      },
                      success:
                        function (data) {
                          if (data.msg == 'OK') {
                            Swal.fire({
                              type: 'success',
                              title: data.message,
                              showConfirmButton: false,
                              timer: 1100
                            });
                            $('#tb_cupones_ingresados').DataTable().ajax.reload();
                            $('#modal-remoto-lgg2').modal('hide');
                          } else {
                            Swal.fire({
                              type: 'error',
                              title: data.id,
                              showConfirmButton: false,
                              timer: 1100
                            });
                          }
                          $('#modal-remoto-lgg2').modal('hide');
                        }
                    }).done(function () {
                    }).fail(function (jqXHR, textSttus, errorThrown) {
                      alert(errorThrown);
                    });
                  } else {
                    Swal.fire({
                      type: 'error',
                      title: 'Lote ya ingresado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                  $('#modal-remoto-lgg2').modal('hide');
                }
            }).done(function () {
            }).fail(function (jqXHR, textSttus, errorThrown) {
              alert(errorThrown);
            });
          }
        })

      }
    },
    procesar: function (inicial, final, monto) {
      this.contarCupones.splice(0, this.contarCupones.length);
      let rango = final - inicial
      for (let i = 0; i <= (rango); i++) {
        let cupones = new Object();
        cupones.id = i + 1
        cupones.cupon = inicial
        cupones.monto = monto
        this.contarCupones.push(cupones)
        inicial++
      }
      this.bandera = 1
      console.log(this.contarCupones)
    },
    validarForm: function () {
      this.cuponInicial = parseInt($("#inicial").val())
      this.cuponFinal = parseInt($("#final").val())

      if (parseInt(this.cuponInicial) > parseInt(this.cuponFinal)) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        Toast.fire({
          icon: 'error',
          type: 'error',
          title: 'Cupon inicial mayor que el final'
        })
        return 0
      } else if ($("#inicial").val() == "" || $("#final").val() == "" || $("#documento").val() == "" || $("#monto").val() == "" || ($("#observaciones").val() == "")) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })

        Toast.fire({
          icon: 'error',
          type: 'error',
          title: 'Rellenar todos los campos'
        })
        return 0
      } else {

        this.procesar(parseInt($("#inicial").val()), parseInt($("#final").val()), parseInt($("#monto").val()))

      }
    },

    cancelarDevol: function (data) {
      console.log('Formulario cancelado', data);
      this.opcion = data;
    },
    guardaCupon: function () {
      jQuery('.validation_cupones_detalle').validate({
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
          let cupon = $("#cupon").val();
          let data = { cupon };
          title = "¿Desea ingresar los cupones ?";
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
                  /*nro_docto:$('#nro_docto').val(),
                  id_despacha:$('#id_despacha').val(),
                  id_recibe:$('#id_recibe').val(),
                  id_bomba:$('#id_bomba').val(),
                  cant_galones:$('#cant_galones').val(),
                  cant_autor:$('#cant_autor').val(),
                  km_actual:$('#km_actual').val(),
                  observa:$('#observa').val(),*/
                  cupones: viewModelIngresoCupon.cupones,
                  nro_docto: $('#nro_docto').val(),
                  id_observa: $('#observa').val(),
                  cupon1: $('#cupon1').val(),
                  cupon2: $('#cupon2').val(),
                  monto: $('#monto').val(),

                },
                //dataType: "json",
                success: function (data) {
                  $('#modal-remoto-lg').modal('hide');
                  reload_movimientos_en();

                  if (data == 'OK') {
                    Swal.fire({
                      type: 'success',
                      title: 'Cupon actualizado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    reload();
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

    marcarVarios: function (tipo) {
      if (tipo == 1) {
        this.cch1 += 1;
      } else {
        this.cch1 -= 1;
      }

    }
  }
})

viewModelCuponIngreso.$mount('#app_ingreso_cupones');
instanciaP = viewModelCuponIngreso;
