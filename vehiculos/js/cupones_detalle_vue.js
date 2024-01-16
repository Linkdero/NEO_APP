var eventBus = new Vue();
var instanciaV;

  //pedido detalle
  viewModelEntregaCupon = new Vue({
    //el: '#app_cupones_detalle',
    data: {
      id_documento:$('#id_documento').val(),
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
      this.getDocumento();
      this.getCuponesbyDocumento();
      this.get_destino_combustible();
      this.get_placas();
      this.get_refer();
      this.get_departamento();
      eventBus.$on('recargarDocumento', () => {
        this.getDocumento();
      });
    },
    methods: {
      cancelarDevol: function(data) {
        console.log('Formulario cancelado', data);
        this.opcion = data;
      },
      getDocumento: function(){
        if(this.id_documento > 0){
          axios.get('vehiculos/php/back/cupones/get_documento.php', {
            params: {
              id_documento:this.id_documento
            }
          }).then(function (response) {
            viewModelEntregaCupon.documento = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      getCuponesbyDocumento: function(){
        if(this.id_documento > 0){
          axios.get('vehiculos/php/back/listados/get_cupones_by_documento.php', {
            params: {
              id_documento:this.id_documento
            }
          }).then(function (response) {
            viewModelEntregaCupon.cupones = response.data;

            var i = 0;
            $.each(this.cupones,function(pos, elemento){
              if(elemento.checked1){
                i += parseInt(1);
              }
              if(elemento.checked2){
                i += parseInt(1);
              }

            })

            this.contador = i;
            this.contarCupones = this.contador;
            console.log(this.cch1);
          }).catch(function (error) {
            console.log(error);
          });
        }
      },

      setOpcion: function(opc){
        this.opcion = opc;
      },

      get_destino_combustible: function(){
        axios.get('vehiculos/php/back/listados/get_destino_combustible.php', {
          params: {
          //   id_persona: this.id_persona
          }
        }).then(function (response) {
          viewModelEntregaCupon.destino = response.data;
          setTimeout(() => {

            $("#id_destino").select2({});
            $('#id_destino').on("change",function(){
              viewModelEntregaCupon.get_placas($('#id_destino').val());
              //viewModelEntregaCupon.getTipoCombustible();
              // $("chk_Tanque").val()=false;


          });
          }, 400);
        }).catch(function (error) {
          console.log(error);
        });

      },

      get_placas: function(id_des){
        axios.get('vehiculos/php/back/listados/get_placas.php', {
          params: {
            id_destino:id_des
          }
        }).then(function (response) {

         // if(id_des==1144 || id_des==1147){
            $('#div_vehiculo').show();
            viewModelEntregaCupon.placas = response.data;
          setTimeout(() => {
            $("#id_vehiculo").select2({});


          }, 400);
        //  }else{
        //    document.getElementById("id_vehiculo").selectedIndex = "";
        //    $('#div_vehiculo').hide();
        //  }

          viewModelEntregaCupon.getTipoCombustible(id_des);

        }).catch(function (error) {
          console.log(error);
        });
      },

      get_refer: function(){
        axios.get('vehiculos/php/back/listados/get_refer.php', {
          params: {
          //   id_persona: this.id_persona
          }
        }).then(function (response) {
          viewModelEntregaCupon.refer = response.data;

        }).catch(function (error) {
          console.log(error);
        });

    },

    get_departamento: function(){
      //
      axios.get('vehiculos/php/back/listados/get_departamento.php',{
        params:{
          // pais:'GT'
        }
      })
      .then(function (response) {
        viewModelEntregaCupon.departamento = response.data;
        setTimeout(() => {
          $('#id_departamento').select2({});
          $('#id_departamento').on("change",function(){
            viewModelEntregaCupon.get_municipio($('#id_departamento').val());
            });

        }, 400);

      })
      .catch(function (error) {
          console.log(error);
      });

    },

    get_municipio: function(id_departamento){

      axios.get('vehiculos/php/back/listados/get_municipio.php',{
        params:{
           id_departamento
        }
      })
      .then(function (response) {
        viewModelEntregaCupon.municipio = response.data;
      })
      .catch(function (error) {
          console.log(error);
      });
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
          title = "¿Desea actualizar cupon +++ ?";
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
                      url: "vehiculos/php/back/cupones/actualiza_cupon.php",
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

    procesacupon__: function(){
      if(this.id_documento > 0){
        axios.get('vehiculos/php/back/cupones/procesa_cupon.php', {
          params: {
            id_documento:this.id_documento
          }
        }).then(function (response) {
          viewModelEntregaCupon.cupones = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      }
    },

    procesacupon: function(){
      jQuery('.validation_cupones_detalle').validate({
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
          title = "¿Desea actualizar cupon ?";
          btn_text = "¡Si, Actualizar!";
          Swal.fire({
              title: '<strong>¿Desea procesar este documento ?</strong>',
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
                      url: "vehiculos/php/back/cupones/procesa_cupon.php",
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
                        id_documento:$('#id_documento').val(),
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
                                  title: 'Documento procesado',
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

  viewModelEntregaCupon.$mount('#app_cupones_detalle');
  instanciaP = viewModelEntregaCupon;
