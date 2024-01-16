function actulizar_puesto(){
  jQuery('.js-validation-puesto').validate({
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
        title: '<strong>¿Desea actualizar el puesto del empleado?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Actualizar!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/puestos/actualizar_puesto.php",
            data: {
              id_persona:$('#id_gafete').val(),
              id_empleado:$('#id_empleado').val(),
              id_secretaria_f:$('#id_secretaria_f').val(),
              id_subsecretaria_f:$('#id_subsecretaria_f').val(),
              id_direccion_f:$('#id_direccion_f').val(),
              id_subdireccion_f:$('#id_subdireccion_f').val(),
              id_departamento_f:$('#id_departamento_f').val(),
              id_seccion_f:$('#id_seccion_f').val(),
              id_puesto_f:$('#id_puesto_f').val(),
              id_nivel_f:$('#id_nivel_f').val()
            }, //f de fecha y u de estado.
            beforeSend:function(){
              //$('#response').html('<span class="text-info">Loading response...</span>');
              //alert('message_before')
            },
            success:function(data){
              //alert(data);
              //get_viatico_detalle_encabezado('empleados_por_viatico');
              //document.app_vue.get_empleado_by_viatico();
              cargar_puestos_url('puesto_por_persona_detalle',1);
              recargar_puestos();
              Swal.fire({
                type: 'success',
                title: 'Puesto actualizado',
                showConfirmButton: false,
                timer: 1100
              });
              //alert(data);
            }
          }).done( function() {


          }).fail( function( jqXHR, textSttus, errorThrown){

            alert(errorThrown);

          });

        }

      })
    },
    rules: {
      combo1:{ required: true},
      //combo2:{ required: true},
      combo3:{ required: true},
      combo4:{ required: true},
      combo5:{ required: true},
      //combo6:{ required: true},
      combo7:{ required: true},
      combo8:{ required: true},
     }

  });
}

function crear_remocion(){
  jQuery('.js-validation-remocion').validate({
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
        title: '<strong>¿Desea generar la remoción?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Remover!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/plazas/remocion_plaza_empleado.php",
            data: {
              id_persona:$('#id_gafete').val(),
              id_plaza:$('#id_plaza').val(),
              fecha:$('#id_fecha_remocion').val(),
              nro_acuerdo:$('#id_nro_acuerdo').val(),
              observaciones:$('#id_detalle_remocion').val(),
              tipo_baja:$('#id_tipo_baja').val(),
              asignacion:$('#id_asignacion').val()
            }, //f de fecha y u de estado.
            beforeSend:function(){
              //$('#response').html('<span class="text-info">Loading response...</span>');
              //alert('message_before')
            },
            success:function(data){
              //alert(data);
              //get_viatico_detalle_encabezado('empleados_por_viatico');
              //document.app_vue.get_empleado_by_viatico();
              //cargar_puestos_url('plazas_historial_empleado',2);¿
              cargar_puestos_url('plazas_historial_empleado',2);
              recargar_puestos();
              Swal.fire({
                type: 'success',
                title: 'Baja generada',
                showConfirmButton: false,
                timer: 1100
              });
              //alert(data);
            }
          }).done( function() {


          }).fail( function( jqXHR, textSttus, errorThrown){

            alert(errorThrown);

          });

        }

      })
    },
    rules: {
      detalle_remocion:{ required: true},
      combo1:{ required: true}
     }

  });
}

function tramiteDeSolvencia(){
  jQuery('.js-validation-tramite-solvencia').validate({
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

      //inicio
      Swal.fire({
        title: '<strong>¿Desea establecer en trámite de solvencia al empleado?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, establecer!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/plazas/establecer_tramite_de_solvencia.php",
            data: {
              id_persona:$('#id_gafete').val(),
              id_plaza:$('#id_plaza').val(),
              fecha_acuerdo:$('#id_fecha_acuerdo').val(),
              fecha:$('#id_fecha_remocion').val(),
              nro_acuerdo:$('#id_nro_acuerdo').val(),
              observaciones:$('#id_detalle_remocion').val(),
              tipo_baja:$('#id_tipo_baja').val(),
              asignacion:$('#id_asignacion').val()
            }, //f de fecha y u de estado.
            beforeSend:function(){
              //$('#response').html('<span class="text-info">Loading response...</span>');
              //alert('message_before')
            },
            success:function(data){
              //alert(data);
              //get_viatico_detalle_encabezado('empleados_por_viatico');
              //document.app_vue.get_empleado_by_viatico();
              //id_plaza=$('#id_plaza').val();
              //cargar_remocion_empleado(id_plaza);
              //recargar_puestos();
              cargar_puestos_url('plazas_historial_empleado',2);
              cargar_remocion_empleado($('#id_plaza').val(),$('#id_asignacion').val());
              recargar_puestos();
              Swal.fire({
                type: 'success',
                title: 'Trámite generado',
                showConfirmButton: false,
                timer: 1100
              });
              //alert(data);
            }
          }).done( function() {


          }).fail( function( jqXHR, textSttus, errorThrown){

            alert(errorThrown);

          });

        }

      })
      //fin


    },
    rules: {
      detalle_remocion:{ required: true},
      combo1:{ required: true}
     }

  });

}

function crearBaja(){
  jQuery('.js-validation-crear-baja').validate({
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

      //inicio
      Swal.fire({
        title: '<strong>¿Desea dar de baja al empleado?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, establecer!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/plazas/crear_baja_empleado.php",
            data: {
              id_persona:$('#id_gafete').val(),
              id_plaza:$('#id_plaza').val(),
              asignacion:$('#id_asignacion').val(),
              id_tipo_baja:$('#id_tipo_baja').val(),
              id_fecha_baja:$('#id_fecha_baja').val(),
              id_detalle_baja:$('#id_detalle_baja').val()
            }, //f de fecha y u de estado.
            beforeSend:function(){
              //$('#response').html('<span class="text-info">Loading response...</span>');
              //alert('message_before')
            },
            success:function(data){
              cargar_puestos_url('plazas_historial_empleado',2);
              recargar_puestos();
              Swal.fire({
                type: 'success',
                title: 'Baja generada',
                showConfirmButton: false,
                timer: 1100
              });
              //alert(data);
            }
          }).done( function() {


          }).fail( function( jqXHR, textSttus, errorThrown){

            alert(errorThrown);

          });

        }

      })
      //fin


    },
    rules: {
      combo_1:{ required: true}
     }

  });
}

function asignarEmpleadPlaza(){
  jQuery('.js-validation-crear-asignacion').validate({
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

      //inicio
      Swal.fire({
        title: '<strong>¿Quiere asignar la plaza a este empleado?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, establecer!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/plazas/asignacion_empleado_plaza.php",
            data: {
              id_persona:$('#id_gafete').val(),
              id_empleado:$('#id_empleado_asignar').val(),
              id_plaza:$('#id_plaza').val(),
              id_nro_acuerdo:$('#id_nro_acuerdo_p').val(),
              id_fecha_acuerdo_asignacion:$('#id_fecha_acuerdo_asignacion_p').val(),
              id_fecha_toma_posesion:$('#id_fecha_toma_posesion_p').val(),
              observaciones:$('#id_detalle_asignacio_p').val(),
              id_secretaria_f:$('#id_secretaria_f').val(),
              id_subsecretaria_f:$('#id_subsecretaria_f').val(),
              id_direccion_f:$('#id_direccion_f').val(),
              id_subdireccion_f:$('#id_subdireccion_f').val(),
              id_departamento_f:$('#id_departamento_f').val(),
              id_seccion_f:$('#id_seccion_f').val(),
              id_puesto_f:$('#id_puesto_f').val(),
              id_nivel_f:$('#id_nivel_f').val(),
              check:$('#chk_funcional').is(':checked')?1:0

            }, //f de fecha y u de estado.
            beforeSend:function(){

            },
            success:function(data){
              cargar_puestos_url('plazas_historial_empleado',2);
              recargar_puestos();
              Swal.fire({
                type: 'success',
                title: 'Asignación de plaza generada',
                showConfirmButton: false,
                timer: 1100
              });
              //alert(data);
            }
          }).done( function() {


          }).fail( function( jqXHR, textSttus, errorThrown){

            alert(errorThrown);

          });

        }

      })
    },
    rules: {
      combo_plaza:{ required: true},
      //combo2:{required: true}
     }

  });
}
