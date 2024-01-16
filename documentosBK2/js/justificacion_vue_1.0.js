 app_vue_jus = new Vue({
    el: '#app_justificacion',
    data: {
      ped_tra:$('#id_ped_tra').val(),
      pedidos:"",
      pedido:"",
      insumos:"",
      pedido_seleccionado:0,
      message3: "",
      totalcharacter3: 450,
      message4: "",
      totalcharacter4: 450,
      dictamenes:[{dictamen:'',fecha:''}],
      mostrarDictamen:0
    },
    computed: {},
    created: function(){
      //this.get_pedidos(),
      //this.get_pedido_detalle()
    },
    methods: {
      charCount3: function(){
        var total=450;
        var left = total - this.message3.length;
         this.totalcharacter3 = left;

       },
       charCount4: function(){
         var total=450;
         var left = total - this.message4.length;
          this.totalcharacter4 = left;

        },
      validaciones: function(){
        this.validar1=false;
        this.validar2=false;
        this.emitido=true;
        this.recibido=false;
      },
      /*get_pedidos: function(){
        axios.get('documentos/php/back/listados/get_pedidos', {
          params: {
            tipo:1
          }
        }).then(function (response) {
          app_vue_jus.pedidos = response.data;
          setTimeout(() => {
            $("#id_pedido").select2({});
            $( "#id_pedido" ).change(function(){
              var id=$(this).attr('id');
              var valor =$('#'+id).val();
              app_vue_jus.get_pedido_detalle(valor);
            });
          }, 200);

        }).catch(function (error) {
          console.log(error);
        });
      },
      get_pedido_detalle: function(){
        //var ped_tra=event.currentTarget.value;
        //alert(ped_tra);
        axios.get('documentos/php/back/pedido/get_pedido_by_id', {
          params: {
            ped_tra:this.ped_tra
          }
        }).then(function (response) {
          app_vue_jus.pedido = response.data;
        }).catch(function (error) {
          console.log(error);
        });

        axios.get('documentos/php/back/pedido/get_insumos_by_pedido', {
          params: {
            ped_tra:this.ped_tra
          }
        }).then(function (response) {
          app_vue_jus.insumos = response.data;

        }).catch(function (error) {
          console.log(error);
        });
      },
      crear_justificacion: function(){

      },*/
      validacionMostrarConfirma: function(){
        if( $('#rd_dg_si').is(':checked') )
        {
          this.mostrarDictamen=1;
        }else{
          this.mostrarDictamen=0;
        }
      },
      deleteRow(index, d) {
        var idx = this.dictamenes.indexOf(d);
        console.log(idx, index);
        if (idx > -1) {
          this.dictamenes.splice(idx, 1);
        }
      },
      addNewRow() {
        console.log(this.dictamenes.length);
        this.dictamenes.push({dictamen:'',fecha:''});
      },
      nuevaJustificacion: function(){
        jQuery('.jsValidacionJustificacionNueva').validate({
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
                id_justificacion=$('#id_justificacion').val();
                id_pedido=$('#id_ped_tra').val();
                fecha_pedido=$('#id_fecha_tra').val();
                id_especificaciones=$('#id_especificaciones').val();
                id_necesidad=$('#id_necesidad').val();
                id_temporalidad=$('#id_temporalidad').val();
                id_finalidad=$('#id_finalidad').val();
                id_resultado=$('#id_resultado').val();
                tipo_compra=($('#rd_servicio').is(':checked'))?1:0;
                tipo_diagnostico=($('#rd_dg_si').is(':checked'))?1:0;


                Swal.fire({
                title: '<strong>¿Desea generar el documento?</strong>',
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
                  dataType: 'json',
                  url: "documentos/php/back/justificacion/crear_justificacion.php",
                  data: {
                    id_justificacion,
                    id_pedido,
                    fecha_pedido,
                    id_especificaciones,
                    id_necesidad,
                    id_temporalidad,
                    id_finalidad,
                    id_resultado,
                    tipo_compra,
                    tipo_diagnostico
                  }, //f de fecha y u de estado.

                  beforeSend:function(){
                  },
                  success:function(data){
                    //exportHTML(data);
                    //alert(data.msg);
                    if(data.msg=='OK'){
                      justificacion_reporte(data.id);
                      $('#modal-remoto').modal('hide');
                      instancia.recargarTabla();

                      Swal.fire({
                        type: 'success',
                        title: 'Justificación generada',
                        showConfirmButton: false,
                        timer: 1100
                      });
                      $("#tb_dictamenes tbody tr").each(function(index, element){
                          id_row = ($(this).attr('id'));

                          $.ajax({
                            type: "POST",
                            url: "documentos/php/back/justificacion/agregar_dictamen.php",

                            data:
                            {
                              docto_id:data.id,
                              dictamen:$('#txt'+index).val(),
                              fecha:$('#f'+index).val()
                            },
                            beforeSend:function(){
                              //$('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                            },
                            success:function(data){

                            }
                          }).done( function() {


                          }).fail( function( jqXHR, textSttus, errorThrown){

                          });

                        });

                    }else{
                      Swal.fire({
                        type: 'error',
                        title: 'error: '+data.id,
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
            combo1:{ required: true}
           }

        });

      }
    }

  })
