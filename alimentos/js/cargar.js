
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

//Reportes
function get_alimentos_por_fecha(){
  document.getElementById('direccion_rrhh').disabled = false;
  $.ajax({
    type: "POST",
    url: "alimentos/php/front/reportes/get_alimentos_por_fecha.php",
    //dataType:'json',

    beforeSend:function(){
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success:function(data){
      //alert(data);
      $('#titulo').text('Alimentos por Fecha');
      $('#__data').html(data);

    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}

function get_alimentos_por_direccion(){
  document.getElementById('direccion_rrhh').disabled = true;
  $.ajax({
    type: "POST",
    url: "alimentos/php/front/reportes/get_alimentos_por_direccion.php",
    //dataType:'json',

    beforeSend:function(){
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success:function(data){
      //alert(data);
      $('#titulo').text('Alimentos por Direccion');
      $('#__data').html(data);

    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}

function get_alimentos_por_colaborador(){
  document.getElementById('direccion_rrhh').disabled = false;
  $.ajax({
    type: "POST",
    url: "alimentos/php/front/reportes/get_alimentos_por_colaborador.php",
    //dataType:'json',

    beforeSend:function(){
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success:function(data){
      //alert(data);
      $('#titulo').text('Alimentos por Colaborador');
      $('#__data').html(data);

    }
  }).done( function() {


  }).fail( function( jqXHR, textSttus, errorThrown){

  });
}



