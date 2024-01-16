window.addEventListener("keyup",function(e){
  if(e.keyCode==13) {
    tipo = 4353;
    subtipo = 0;
    $.ajax({
      type: "POST",
      url: "quinta/php/back/empleado/get_empleado.php",
      //dataType:'json',
      data: {id_persona:function() { return $('#id_persona').val() },
             tipo:tipo,subtipo:subtipo},
      beforeSend:function(){
        $('#datos_diferente').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
      },
      success:function(data){
        //alert(data);
        $('#id_persona').val('');
        $('#datos').html(data);


      }
    }).done( function() {


    }).fail( function( jqXHR, textSttus, errorThrown){

    });

  }

});

function get_datos_empleado_(tipo,subtipo){
    $.ajax({
      type: "POST",
      url: "quinta/php/back/empleado/get_empleado.php",
      data: {id_persona:function() { return $('#id_persona').val() },
             tipo:tipo,subtipo:subtipo},
      beforeSend:function(){
        $('#datos_diferente').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
      },
      success:function(data){
        $('#datos').html(data);
      }
    }).done( function() {


    }).fail( function( jqXHR, textSttus, errorThrown){

    });
}
