function get_empleado_datos(){
  id_persona=$("#id_persona").val();
  $.ajax({
    type: "POST",
    url: "empleados/php/back/empleados/get_persona_by_id.php",
    dataType:'json',
    data: {id_persona:id_persona},
    beforeSend:function(){
      $('.data_').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success:function(data){
      //alert(data.empleado);

      $("#nombres").text(data.nombres);
      $("#apellidos").text(data.apellidos);
      $("#profesion").text(data.profesion);
      $("#tipo_contrato").text(data.tipo_contrato);
      $("#observaciones").text(data.observaciones);

      $("#nit").text(data.nit);
      $("#nisp").text(data.nisp);
      $("#igss").text(data.igss);
      $("#religion").text(data.religion);
      $("#fecha_nacimiento").text(data.fecha_nacimiento);


      $("#email").text(data.email);

      $("#procedencia").text(data.procedencia);
      $("#estado_civil").text(data.estado_civil);
      $("#genero").text(data.genero);

      $("#tipo_personal").text(data.tipo_personal);
      $("#municipio").text(data.municipio);
      $("#departamento").text(data.departamento);
      //$("#").text(data.);
      //var mainContainer = document.getElementById("myData");
    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}


function get_empleado_perfil(){
  id_persona=$("#id_persona").val();
  $.ajax({
    type: "POST",
    url: "empleados/php/front/empleados/empleado_perfil.php",
    data: {id_persona:id_persona},
    beforeSend:function(){
      $('#datos').html("<div class='spinner-grow text-primary'></div> ");
    },
    success:function(data){
      //alert(data.empleado);
      $('#datos').html(data);
      //var mainContainer = document.getElementById("myData");
    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}

function get_direcciones(){
  id_persona=$("#id_persona").val();
  $.ajax({
    type: "POST",
    url: "empleados/php/front/empleados/listado_direcciones.php",
    data: {id_persona:id_persona},
    beforeSend:function(){
      $('#datos').html("<div class='spinner-grow text-primary'></div> ");
    },
    success:function(data){
      //alert(data.empleado);
      $('#datos').html(data);
      //var mainContainer = document.getElementById("myData");
    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}
function get_telefonos(){
  id_persona=$("#id_persona").val();
  $.ajax({
    type: "POST",
    url: "empleados/php/front/empleados/listado_telefonos.php",
    data: {id_persona:id_persona},
    beforeSend:function(){
      $('#datos').html("<div class='spinner-grow text-primary'></div> ");
    },
    success:function(data){
      //alert(data.empleado);
      $('#datos').html(data);
      //var mainContainer = document.getElementById("myData");
    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}

function cargar_puestos_url(url,opc){
  url_='';
  if(opc==1){
    url_="empleados/php/front/puestos/"+url+".php";
  }else if(opc==2){
    url_="empleados/php/front/plazas/"+url+".php";
  }else if(opc==3){
    url_="empleados/php/front/contratos/"+url+".php";
  }
  $.ajax({
    type: "POST",
    url: url_,
    data:{
      id_persona:$('#id_gafete').val()
    },
    dataType: 'html',
    beforeSend:function(){
      $("#datos_puesto").removeClass('slide_up_anim');
      $("#datos_puesto").html('<div class="loaderr"></div>');
    },
    success:function(data) {
      $("#datos_puesto").addClass('slide_up_anim');
      $("#datos_puesto").fadeIn('slow').html(data).fadeIn('slow');
    }
  });
}

function cargar_remocion_empleado(id_plaza,id_asignacion,tipo){
  url='';
  if(tipo==1){
    url='remocion_plaza';
  }else if(tipo==2){
    url='plaza_detalle_por_asignacion';
  }
  $.ajax({
    type: "POST",
    url: 'empleados/php/front/plazas/'+url+'.php',
    data:{
      id_plaza,
      id_asignacion
    },
    dataType: 'html',
    beforeSend:function(){
      $("#datos_puesto").removeClass('slide_up_anim');
      $("#datos_puesto").html('<div class="loaderr"></div>');
    },
    success:function(data) {
      $("#datos_puesto").addClass('slide_up_anim');
      $("#datos_puesto").fadeIn('slow').html(data).fadeIn('slow');
    }
  });
}

function cargar_asignar_plaza_empleado(){
  $.ajax({
    type: "POST",
    url: 'empleados/php/front/plazas/asignar_empleado_a_plaza.php',
    data:{

    },
    dataType: 'html',
    beforeSend:function(){
      $("#datos_puesto").removeClass('slide_up_anim');
      $("#datos_puesto").html('<div class="loaderr"></div>');
    },
    success:function(data) {
      $("#datos_puesto").addClass('slide_up_anim');
      $("#datos_puesto").fadeIn('slow').html(data).fadeIn('slow');

    }
  });
}

function cargar_menu_opciones(){
  $.ajax({
    type: "POST",
    url: 'empleados/php/front/empleados/menu_opciones.php',
    data:{

    },
    dataType: 'html',
    beforeSend:function(){
      $("#menu").removeClass('slide_up_anim');
      $("#menu").html('<div class="loaderr"></div>');
    },
    success:function(data) {
      $("#menu").addClass('slide_up_anim');
      $("#menu").fadeIn('slow').html(data).fadeIn('slow');

    }
  });
}
