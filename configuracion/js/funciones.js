
function add_usuario(persona){
  if($('#correo').val()==''){
    Swal.fire({
      type: 'error',
      title: 'Debe ingresar el correo electrónico',
      showConfirmButton: false,
      timer: 700
    });
  }else{
    Swal.fire({
      title: '<strong>¿Desea crear el usuario?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, Crear!'
      }).then((result) => {
      if (result.value) {
        //funcionamiento
        correo = $('#correo').val();
        $.ajax({
          type: "POST",
          url: "configuracion/php/back/empleado/add_usuario.php",
          data: {correo:correo, persona:persona},
          success:function(data){
            //alert(data);
            obtener_accesos_por_persona(persona);
            Swal.fire({
              type: 'success',
              title: 'Usuario creado',
              showConfirmButton: false,
              timer: 700
            });


          }

        }).done( function() {

        }).fail( function( jqXHR, textSttus, errorThrown){


        });


      }
    })
  }

}

function cambiar_password(persona){
  if($('#clave').val()==''){
    Swal.fire({
      type: 'error',
      title: 'Debe ingresar el password',
      showConfirmButton: false,
      timer: 700
    });
  }else{
    Swal.fire({
      title: '<strong>¿Desea cambiar su password?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, cambiar!'
      }).then((result) => {
      if (result.value) {
        //funcionamiento
        password = $('#clave').val();
        $.ajax({
          type: "POST",
          url: "configuracion/php/back/empleado/cambiar_password.php",
          data: {password:password, persona:persona},
          success:function(data){
            //alert(data);
            //obtener_accesos_por_persona(persona);
            Swal.fire({
              type: 'success',
              title: 'Password cambiado',
              showConfirmButton: false,
              timer: 700
            });
            window.location = 'principal.php';


          }

        }).done( function() {

        }).fail( function( jqXHR, textSttus, errorThrown){


        });


      }
    })
  }

}

function cambiar_username(persona){
  if($('#username').val()==''){
    Swal.fire({
      type: 'error',
      title: 'Debe ingresar nuevo usuario',
      showConfirmButton: false,
      timer: 700
    });
  }else{
    Swal.fire({
      title: '<strong>¿Desea cambiar su usuario?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, cambiar!'
      }).then((result) => {
      if (result.value) {
        //funcionamiento
        password = $('#username').val();
        $.ajax({
          type: "POST",
          url: "configuracion/php/back/empleado/cambiar_usuario.php",
          data: {password:password, persona:persona},
          success:function(data){
            //alert(data);
            //obtener_accesos_por_persona(persona);
            Swal.fire({
              type: 'success',
              title: 'Usuario cambiado',
              showConfirmButton: false,
              timer: 700
            });
            reload_usuarios();
          }

        }).done( function() {
        }).fail( function( jqXHR, textSttus, errorThrown){
        });


      }
    })
  }

}

function del_ext_usr(persona){
    Swal.fire({
      title: '<strong>¿Desea eliminar el usuario?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, eliminar!'
      }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "POST",
          url: "configuracion/php/back/empleado/del_ext_usr.php",
          data: {persona:persona},
          success:function(data){
            Swal.fire({
              type: 'success',
              title: 'Usuario eliminado',
              showConfirmButton: false,
              timer: 700
            });
            reload_directorio();
          }

        }).done( function() {
        }).fail( function( jqXHR, textSttus, errorThrown){
        });
      }
    })
}

function reset_password(persona){
    Swal.fire({
      title: '<strong>¿Desea resetear la contraseña?</strong>',
      text: "",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, resetear!'
      }).then((result) => {
      if (result.value) {
        //funcionamiento
        password = 'Secreto123**';
        $.ajax({
          type: "POST",
          url: "configuracion/php/back/empleado/cambiar_password.php",
          data: {password:password, persona:persona},
          success:function(data){
            //alert(data);
            //obtener_accesos_por_persona(persona);
            Swal.fire({
              type: 'success',
              title: 'Password cambiado',
              showConfirmButton: false,
              timer: 700
            });
          }

        }).done( function() {
        }).fail( function( jqXHR, textSttus, errorThrown){
        });
      }
    })
}

function inactivar_acceso(acceso){
  Swal.fire({
    title: '<strong>¿Desea inactivar el acceso: '+acceso+'?</strong>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Inactivar!'
    }).then((result) => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/empleado/update_acceso.php",
        data: {acceso:acceso, tipo: 0 },
        success:function(data){
            $('#modal-remoto').modal('hide');
            Swal.fire({
              type: 'success',
              title: 'Acceso actualizado',
              showConfirmButton: false,
              timer: 1100
            });
            // reload_noticias();
            reload_accesos_persona();
            reload_accesos();
        }
        }).fail( function( jqXHR, textSttus, errorThrown){
          alert(errorThrown);
        });
    }
  })
}

function activar_acceso(acceso){
  Swal.fire({
    title: '<strong>¿Desea Activar el acceso: '+acceso+'?</strong>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Activar!'
    }).then((result) => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/empleado/update_acceso.php",
        data: {acceso:acceso, tipo: 1 },
        success:function(data){
            $('#modal-remoto').modal('hide');
            Swal.fire({
              type: 'success',
              title: 'Acceso actualizado',
              showConfirmButton: false,
              timer: 1100
            });
            reload_accesos_persona();
            reload_accesos();
        }
        }).fail( function( jqXHR, textSttus, errorThrown){
          alert(errorThrown);
        });
    }
  })
}

function mod_usuario(flag, id_persona, tipo){
  Swal.fire({
    title: (flag ? '<strong>¿Desea inactivar el usuario?</strong>' : '<strong>¿Desea activar el usuario?</strong>'),
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: (flag ? '¡Si, Inactivar!':'¡Si, Activar!')
    }).then((result) => {
    if (result.value) {
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/empleado/update_usuario.php",
        data: {id_persona:id_persona, flag: flag, tipo:tipo},
        success:function(data){
            $('#modal-remoto').modal('hide');
            Swal.fire({
              type: 'success',
              title: 'Usuario actualizado',
              showConfirmButton: false,
              timer: 1100
            });
            reload_accesos_persona();
            reload_accesos();
        }
        }).fail( function( jqXHR, textSttus, errorThrown){
          alert(errorThrown);
        });
    }
  })
}


function get_persona_by_acceso(acceso){

  $.ajax({
      type: "POST",
      url:'configuracion/php/back/settings/get_persona_by_acceso.php',
      dataType: "json",
      data:{acceso:acceso},

      beforeSend:function(){

      },
      success: function(data){
        return data.empleado;
      }
  });

}

function upd_per_pri_pan(){ //Actualiza los privilegios de una persona por pantalla

  $.each($('input[type=checkbox]:checked'), function(){
    var pantalla_acceso;
    var flag;
    pantalla_acceso = ($(this).data('tipe'));
    flag = ($(this).data('id'));



    if(pantalla_acceso != '')
    {
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/settings/insert_permisos_p.php",
        data: {pantalla_acceso:pantalla_acceso, flag:flag},
        beforeSend:function(){

        },
        success:function(data){
          //alert(data);
        }

      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){


      });
    } //fin if*/
  });
  $.each($('input[type=checkbox]:not(:checked)'), function(){

    var pantalla_acceso;
    var flag;
    pantalla_acceso = ($(this).data('tipe'));
    flag = ($(this).data('id'));





    if(pantalla_acceso != '')
    {
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/settings/delete_permisos_p.php",
        data: {pantalla_acceso:pantalla_acceso, flag:flag},
        beforeSend:function(){

        },
        success:function(data){
          //alert(data);
        }
      }).done( function() {


      }).fail( function( jqXHR, textSttus, errorThrown){

      });
    } //fin if*/
  });

  //inicio checkeados



}

function get_personas_select(){

  $("#cmb_personas").select2({
    ajax: {
     url: "empleados/php/back/listados/get_personas.php",
     type: "post",
     dataType: 'json',
     delay: 250,
     data: function (params) {
      return {
        searchTerm: params.term // search term
      };
     },
     processResults: function (response) {
       return {
          results: response
       };
     },
     cache: true
    }
   });


    /*$.getJSON('empleados/php/back/listados/get_personas.php',function(data){
      $.each(data, function(id_persona,id_persona){
        $("#cmb_personas").append('<option value="'+id_persona+'">'+id_persona+'</option>');
      });
    });*/
    /*$.ajax({
    type : 'GET',
    url : 'empleados/php/back/listados/get_personas.php',
    dataType: 'json',
    quietMillis: 50,
        data: function (term) {
            return {
                term: term
            };
        },
        results: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.empleado,
                        id: item.id_persona
                    }
                })
            };
        }

    });*/
}
function establecer_modulo_a_empleado(modulo,persona){
  Swal.fire({
    title: '<strong>¿Desea asignar este modulo al empleado?</strong>',
    text: "",
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Asignar!'
    }).then((result) => {
    if (result.value) {
      //funcionamiento
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/settings/asignar_modulo_empleado.php",
        data: {modulo:modulo,persona:persona},
        beforeSend:function(){

        },
        success:function(data){
          //alert(data);
          reload_accesos_pendiente();
          reload_empleados();
        }

      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){


      });
    }
  })
}

function save_modulo(){
  jQuery('.validation_nuevo_modulo').validate({
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

        let nombre = $('#nombre').val();
        let descripcion= $('#descripcion').val();
        let data = {nombre, descripcion};
        Swal.fire({
          title: '<strong></strong>',
          text: "¿Desea crear el módulo?",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Crear!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "POST",
              url: "configuracion/php/back/settings/create_item.php",
              data: data,
              success:function(data){
                  $('#modal-remoto').modal('hide');
                  Swal.fire({
                    type: 'success',
                    title: 'Módulo creado',
                    showConfirmButton: false,
                    timer: 1100
                  });
                  cargar_modulos();
              }
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
          }
        });
    }
  });
}

function trasladar_privilegios_de_acceso_acceso(acceso_origen,modulo){
  Swal.fire({
    title: '<strong>¿Desea asignar los mismos privilegios?</strong>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Copiar!'
    }).then((result) => {
    if (result.value) {
      //funcionamiento
      var acceso_destino =$('#cmb_personas_modulo').val();
      $.ajax({
        type: "POST",
        url: "configuracion/php/back/settings/update_copiar_privilegios.php",
        data: {modulo:modulo,acceso_origen:acceso_origen,acceso_destino:acceso_destino},
        beforeSend:function(){

        },
        success:function(data){
          //reload_accesos_pendiente();
          //alert(data);
        }

      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){


      });
    }
  })
}
