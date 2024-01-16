var mod1;
function mostrar_pantallas(modulo){
  mod1=modulo;
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/pantallas.php',
      data:{modulo:modulo},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        document.getElementById('inicio').innerHTML = '<a >Pantalla</a>';
        document.getElementById('privilegio').innerHTML = '<a hidden></a>';
        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}

function mostrar_empleados_por_pantalla(pantalla){
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/empleados_por_pantalla.php',
      data:{pantalla:pantalla},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        document.getElementById('inicio').innerHTML = '<a onclick=" mostrar_pantallas('+mod1+') " class="text-danger" style="cursor:pointer"><i class="fa fa-arrow-left"></i> Regresar</a>';
        document.getElementById('privilegio').innerHTML = '<a style="visibility:visible">Pantalla: '+pantalla+'</a>';
        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}

var mod;
function mostrar_accesos_por_modulo(modulo){
  mod=modulo;
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/accesos_por_modulo.php',
      data:{modulo:modulo},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        document.getElementById('inicio').innerHTML = '<a >Accesos</a>';
        document.getElementById('acceso').innerHTML = '<a hidden></a>';
        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}

var person;
function mostrar_privilegios_por_accesos(acceso,opcion){
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/permisos_por_acceso.php',
      data:{acceso:acceso},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        //var nombre =get_persona_by_acceso(acceso);
        var nombre;
        $.ajax({
            type: "POST",
            url:'configuracion/php/back/settings/get_persona_by_acceso.php',
            dataType: "json",
            data:{acceso:acceso},
            beforeSend:function(){

            },
            success: function(data){
              // alert(data.empleado);
            }
        });
        if(opcion==1){
          document.getElementById('inicio').innerHTML = '<a onclick=" mostrar_accesos_por_modulo('+mod+',1) " class="text-danger" style="cursor:pointer"><i class="fa fa-arrow-left"></i> Regresar</a>';
        }else if(opcion==2){
          document.getElementById('inicio').innerHTML = '<a onclick=" obtener_accesos_por_persona('+person+',2) " class="text-danger" style="cursor:pointer"><i class="fa fa-arrow-left"></i> Regresar</a>';
        }

        document.getElementById('acceso').innerHTML = '<a style="visibility:visible">Acceso: '+acceso+'</a>';

        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}
function obtener_empleados(){
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/empleados.php',
      beforeSend:function(){
        $('#info_modulos').removeClass('slide_up_anim');
        $("#info_modulos").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){

        $('#info_modulos').addClass('slide_up_anim');
        $("#info_modulos").fadeOut(0).html(data).fadeIn(0);
      }
  });
}

function obtener_accesos_por_persona(id_persona){
person=id_persona;
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/accesos_por_persona.php',
      data: {id_persona:id_persona},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        //document.getElementById('inicio').innerHTML = '<a onclick="obtener_empleados() " class="text-danger" style="cursor:pointer"><i class="fa fa-arrow-left"></i> Regresar</a>';
        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}
function obtener_accesos_pendiente_por_persona(id_persona){
person=id_persona;
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/accesos_por_persona_pendiente.php',
      data: {id_persona:id_persona},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        //document.getElementById('inicio').innerHTML = '<a onclick="obtener_empleados() " class="text-danger" style="cursor:pointer"><i class="fa fa-arrow-left"></i> Regresar</a>';
        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}

function cargar_asignar_privilegios(){
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/asignar_privilegios_empleado.php',
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){

        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}
function cargar_copiar_privilegios(acceso_origen,modulo){ //origen= acceso a copiar
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/copiar_privilegios.php',
      data: {acceso_origen:acceso_origen,modulo:modulo},
      beforeSend:function(){
        $('#panel_pantallas').removeClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){
        document.getElementById('inicio').innerHTML = '<a onclick=" obtener_accesos_por_persona('+person+',2) " class="text-danger" style="cursor:pointer"><i class="fa fa-arrow-left"></i> Regresar</a>';
        document.getElementById('acceso').innerHTML = '<a style="visibility:visible">Acceso: '+acceso_origen+'</a>';

        $('#panel_pantallas').addClass('slide_up_anim');
        $("#panel_pantallas").fadeOut(0).html(data).fadeIn(0);
      }
  });
}
function cargar_modulos(){
  $.ajax({
      type: "POST",
      url:'configuracion/php/front/modulos/modulos.php',
      beforeSend:function(){
        $('#info_modulos').removeClass('slide_up_anim');
        $("#info_modulos").fadeOut(0).html('<div class="spinner-grow text-primary"></div><br>').fadeIn(0);
      },
      success: function(data){

        $('#info_modulos').addClass('slide_up_anim');
        $("#info_modulos").fadeOut(0).html(data).fadeIn(0);
      }
  });
}
