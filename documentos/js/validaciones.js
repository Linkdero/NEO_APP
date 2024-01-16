function crearDocumento(){
  /*if( $('#rd_emitido').is(':checked') ){
    alert('cheaquead emi');
  }else{
    alert('deschequeado');
  }*/
  jQuery('.jsValidacionDocumentoNuevo').validate({
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
          titulo=$('#id_titulo_documento').val();
          fecha_documento=$('#id_fecha_documento').val();
          categoria=$('#id_categoria').val();
          correlativo_res=$('#id_correlativo_respuesta').val();
          destinatarios=$('#id_destinatarios').val();
          destinatarios_cc=$('#id_destinatarios_cc').val();
          doc_interno=($('#chk_doc_externo').is(':checked'))?1:0;
          emision_recibido=($('#rd_emitido').is(':checked'))?1:2;
          respuesta=($('#chk_doc_respuesta').is(':checked'))?1:0;
          nombre=$('#id_nombre').val();

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
            url: "documentos/php/back/documento/crear_documento.php",
            data: {
              titulo,
              fecha_documento,
              categoria,
              correlativo_res,
              destinatarios,
              destinatarios_cc,
              doc_interno,
              emision_recibido,
              respuesta,
              nombre
            }, //f de fecha y u de estado.

            beforeSend:function(){
            },
            success:function(data){
              exportHTML(data.id);
              recargarDocumentos();
              Swal.fire({
                type: 'success',
                title: 'Documento generado',
                showConfirmButton: false,
                timer: 1100
              });
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


function crearJustificacion(){
  /*if( $('#rd_emitido').is(':checked') ){
    alert('cheaquead emi');
  }else{
    alert('deschequeado');
  }*/
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
          id_pedido=$('#id_pedido').val();
          fecha_pedido=$('#fecha_pedido').text();
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
                recargarJustificaciones();
                $('#modal-remoto').hide();
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
