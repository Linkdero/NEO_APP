function imprimir_liquidacion(nombramiento){
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_viatico_constancia.php",
    data: {nombramiento:nombramiento},
    dataType:'json', //f de fecha y u de estado.




    beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');


          },
          success:function(data){

            //alert(data);
            //console.log(data);
            var documento;
            var hojas = data.data.length;
            var doc = new jsPDF('p','mm');

            for(var i = 0; i < data.data.length; i++) {
              punto=10;
              //doc.setTextColor(203, 50, 52);


              doc.setFontSize(11);
              doc.writeText(10, punto+15 ,data.data[i].formulario,{align:'right',width:194});
              doc.setTextColor(33, 33, 33);
              doc.writeText(25, punto+16 ,data.data[i].emp,{align:'left',width:65});
              doc.writeText(25, punto+20 ,data.data[i].cargo,{align:'left',width:65});
              var r_d1 = data.data[i].destino;
              var r_lineas1 = doc.splitTextToSize(r_d1, 30);
              doc.text(75.5, punto2+55, r_lineas1);





              //finaliza persona nombrada
              doc.line(108, punto2+176, 108, punto2+209);
              doc.setFontSize(9);
              doc.setFontType("normal");
              //doc.setTextColor(255, 255, 255);


              //doc.setTextColor(255, 255, 255);

              doc.setTextColor(33, 33, 33);




              //doc.writeText(0, punto+150 ,data.data[i].resolucion,{align:'center',width:215});

              doc.setFontType("normal");
              doc.setFontSize(7.5);
              var r_d1 = data.data[i].resolucion;
              var r_lineas1 = doc.splitTextToSize(r_d1, 190);
              doc.text(10, punto+250, r_lineas1);

              doc.setFontType("bold");
              doc.setFontSize(9);
              doc.writeText(12, punto+262 ,'Original: Tesorería',{align:'center',width:95});
              doc.writeText(108, punto+262 ,'Original: Tesorería',{align:'center',width:95});




            hojas--;
            if(hojas!=0)
            {
              doc.addPage();
            }





          }
          var x = document.getElementById("pdf_preview_v");
    if (x.style.display === "none") {
        x.style.display = "none";
    } else {
        x.style.display = "none";
    }
    doc.autoPrint()
           $("#pdf_preview_v").attr("src", doc.output('datauristring'));
          }


  }).done( function(data) {
  }).fail( function( jqXHR, textSttus, errorThrown){

    alert(errorThrown);

  });


}
