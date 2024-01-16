function nuevo_director()
{

  jQuery('.validation_nuevo_director').validate({
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

          director=$('#director').val();
          novedad=$('#novedad').val();
          director_id=$('#director_id').val();

          swal({
          title: '<strong></strong>',
          text: "¿Desea Actualizar?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Actualizar!'
          }).then((result) => {
          if (result.value) {


          $.ajax({
            type: "POST",
            url: "inicio/php/nuevo_director.php",
            data: {director:director,novedad:novedad,director_id:director_id}, //f de fecha y u de estado.

            beforeSend:function(){
                          //$('#response').html('<span class="text-info">Loading response...</span>');

                          $('#loading').fadeIn("slow");
                  },
                  success:function(data){
                    //alert(data);
                    get_last_5_directors();
                    //$('#modal-remoto').modal('hide');
                    swal({
                      type: 'success',
                      title: 'Director Actualizado'+data,
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



              }

  });
}


function nueva_novedad()
{

  jQuery('.validation_nueva_novedad').validate({
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
        id_nueva_novedad=$('#id_nueva_novedad').val();
        id_servicio=$('#id_servicio').val();
        id_descanso=$('#id_descanso').val();
        id_vacaciones=$('#id_vacaciones').val();
        id_igss=$('#id_igss').val();
        id_hospitalizados=$('#id_hospitalizados').val();
        id_permiso=$('#id_permiso').val();
        id_faltando=$('#id_faltando').val();
        id_capacitacion=$('#id_capacitacion').val();
        id_otros=$('#id_otros').val();
        id_casos_especiales=$('#id_casos_especiales').val();
        id_asesores=$('#id_asesores').val();
        id_novedades=$('#id_novedades').val();

          swal({
          title: '<strong></strong>',
          text: "¿Desea Actualizar?",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Actualizar!'
          }).then((result) => {
          if (result.value) {


          $.ajax({
            type: "POST",
            url: "inicio/php/nueva_novedad.php",
            data: {
              id_nueva_novedad:id_nueva_novedad,
              id_servicio:id_servicio,
              id_descanso:id_descanso,
              id_vacaciones:id_vacaciones,
              id_igss:id_igss,
              id_hospitalizados:id_hospitalizados,
              id_permiso:id_permiso,
              id_faltando:id_faltando,
              id_capacitacion:id_capacitacion,
              id_otros:id_otros,
              id_casos_especiales:id_casos_especiales,
              id_asesores:id_asesores,
              id_novedades:id_novedades
            }, //f de fecha y u de estado.

            beforeSend:function(){
                          //$('#response').html('<span class="text-info">Loading response...</span>');

                          $('#loading').fadeIn("slow");
                  },
                  success:function(data){
                    //alert(data);
                    reload_dashboard();
                    get_chart();
                    load_totales();
                    //$('#modal-remoto').modal('hide');
                    swal({
                      type: 'success',
                      title: 'Información Actualizado',
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



              }

  });
}
