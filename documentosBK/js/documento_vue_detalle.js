$(document).ready(function(){

  const appVueDocDetalle = new Vue({
    el: '#appdocDetalle',
    data: {
      items:'',
      doctoDetalle:'',
      cronograma:'',
      docto_id:$('#docto_id').val(),
      validacion:1,
      categoria:$('#categoria_id').val(),
      criterios:'',
      condiciones_contrato:'',
      condiciones_econo:'',
      listado_literales:'',
      suma_:'',
      respuesta:false
    },
    created: function(){
      this.getDoctoById(),
      this.getCronograma(),
      this.getCriterios(),
      this.getCondicionesContratos(),
      this.getCondicionesEconomicas(),
      //this.getOpcion(1),
      this.getLoadEditTable(),
      this.getListadoLiterales()
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
      getDoctoById: function(){
        //if(this.docto_id>0){
          axios.get('documentos/php/back/documento/get_documento_by_id', {
            params: {
              docto_id:this.docto_id
            }
          }).then(function (response) {

            appVueDocDetalle.doctoDetalle = response.data;
            this.respuesta=true;
            //alert(response.data.validacion);
          }).catch(function (error) {
            console.log(error);
          });
        //}
      },
      getOpcion: function(valor){
        this.validacion=valor;
      },
      getCronograma: function(){
        setTimeout(() => {
          
            //if(this.respuesta){
              axios.get('documentos/php/back/listados/get_cronograma', {
                params: {
                  docto_id:this.docto_id,
                  categoria:$('#categoria_id').val(),
                  base_id:144
                }
              }).then(function (response) {
                appVueDocDetalle.cronograma = response.data;
              }).catch(function (error) {
                console.log(error);
              });
            //}


        }, 200);

      },
      getCriterios: function(){
        setTimeout(() => {
          //if(this.respuesta){
            axios.get('documentos/php/back/listados/get_base_detalle.php', {
              params: {
                docto_id:this.docto_id,
                categoria:$('#categoria_id').val(),
                base_id:145
              }
            }).then(function (response) {
              appVueDocDetalle.criterios = response.data;
            }).catch(function (error) {
              console.log(error);
            });
          //}
        }, 200);

      },
      getCondicionesContratos: function(){
        setTimeout(() => {
          //if(this.respuesta){
            axios.get('documentos/php/back/listados/get_base_detalle.php', {
              params: {
                docto_id:this.docto_id,
                categoria:$('#categoria_id').val(),
                base_id:146
              }
            }).then(function (response) {
              appVueDocDetalle.condiciones_contrato = response.data;
            }).catch(function (error) {
              console.log(error);
            });
          //}
        }, 200);

      },
      getCondicionesEconomicas: function(){
        setTimeout(() => {
          //if(this.respuesta){
            axios.get('documentos/php/back/listados/get_base_detalle.php', {
              params: {
                docto_id:this.docto_id,
                categoria:$('#categoria_id').val(),
                base_id:147
              }
            }).then(function (response) {
              appVueDocDetalle.condiciones_econo = response.data;
            }).catch(function (error) {
              console.log(error);
            });
          //}
        }, 200);

      },
      getListadoLiterales: function(){
        setTimeout(() => {
          //if(this.respuesta){
            axios.get('documentos/php/back/listados/get_base_literales.php', {
              params: {
                docto_id:this.docto_id,
                categoria:$('#categoria_id').val(),
                tipo:8058
              }
            }).then(function (response) {
              appVueDocDetalle.listado_literales = response.data;
            }).catch(function (error) {
              console.log(error);
            });
          //}
        }, 200);


      },
      saveNewLiteral: function(){
        //inicio
        $('.jsValidacionNuevaLiteral').validate({
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
                //regformhash(form,form.password,form.confirmpwd);

                Swal.fire({
                title: '<strong>¿Desea generar la nueva literal para este documento?</strong>',
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
                  url: "documentos/php/back/documento/crear_nueva_literal.php",
                  data: {
                    docto_id:$('#docto_id').val(),
                    literal:$('#id_literal').val(),
                    titulo:$('#id_titulo_literal').val(),
                    descripcion:$('#id_descripcion_literal').val()
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    Swal.fire({
                      type: 'success',
                      title: 'Literal generada',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    $('#id_literal').val('');
                    $('#id_titulo_literal').val('');
                    $('#id_descripcion_literal').val('');
                    appVueDocDetalle.getListadoLiterales();
                    appVueDocDetalle.getOpcion(4);
                  }

                }).done( function() {


                }).fail( function( jqXHR, textSttus, errorThrown){

                  alert(errorThrown);

                });

              }

            })
          },
          rules: {
            'id_literal': {
              remote: {
                url: 'documentos/php/back/documento/validar_nueva_literal.php',
                data: {
                  docto_id: function(){ return $('#docto_id').val();},
                  id_literal: function(){ return $('#id_literal').val();}
                }
              }
            }
          },
          messages: {
            'id_literal': {
              remote: "Ya fue ingresada estaliteral."
            }
          }


        });

        //fin
      },
      getLoadEditTable: function() {
        setTimeout(() => {
          //fecha cronograma
          $('.fecha_cronograma').editable({
            url: 'documentos/php/back/documento/update_datos_base_detalle.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);


              }
            }
          });
          //valor criterio
          $('.valor_criterio').editable({
            url: 'documentos/php/back/documento/update_datos_base_detalle.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'number',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
                appVueDocDetalle.getCriterios()
              }
            }
          });

        }, 400);

      }

    }
  })
})
