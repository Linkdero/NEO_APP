

$.ajax({

  type: "POST",
  url: "transporte/php/get_eventos.php",
  data:{},
  dataType:'json',
   //f de fecha y u de estado.

  beforeSend:function(){
                //$('#response').html('<span class="text-info">Loading response...</span>');

                //$('#loading').fadeIn("slow");
        },
        success:function(data){
          //alert(data);

          var settings = {};
          var element = document.getElementById('caleandar');
          var events;

          for(var i = 0; i < data.data.length; i++) {

            events = [{'Date': new Date(data.data[i].year, data.data[i].mes-1, data.data[i].dia), 'Title': data.data[i].destino + ' - ' +data.data[i].motivo}];
            console.log(events);
          }


          caleandar(element, events, settings);

          //caleandar(element, events, settings);


          //alert(events[0]);


        }


}).done( function() {

}).fail( function( jqXHR, textSttus, errorThrown){

  alert(errorThrown);

});
