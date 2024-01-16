function reporte_novedades(){
  $.ajax({
    type: "POST",
    url: "reportes/hoja_reporte_novedades.php",
    dataType:'json',
    beforeSend:function(){
      //$('.data_').html("<div class='spinner-grow-sm ' style='margin-left:0%'></div> <h6>Vacío</h6>");
    },
    success:function(data){

      var doc = new jsPDF('p','mm',[216,334]);
      doc.addImage(logo, 'png', 10, 10, 50, 40);
      doc.setTextColor(0, 136, 176);
      doc.setFontType("bold");
      doc.setFontSize(18);
      n = new Date();
      y = n.getFullYear();
      m = n.getMonth()+1;
      var mo;
      if(m<10){
        mo="0"+m;
      }
      else{
        mo=m;
      }
      d = n.getDate();

      h=n.getHours()         // Get the hour (0-23)h=n.getMilliseconds()  // Get the milliseconds (0-999)
      var mi;
      i=n.getMinutes()       // Get the minutes (0-59)
      if(i<10){
        mi="0"+i;
      }
      else{
        mi=i;
      }
      s=n.getSeconds()
      //m =
      doc.writeText(10, 20 ,"NOVEDADES",{align:'center',width:190});
      doc.writeText(10, 27 ,""+d+"/"+mo+"/"+y+"",{align:'center',width:190});

      p1 = 70;
      p2 = 60;
      p_linea=65;
      grados=40;
      p_i=50;

      doc.setFontType("bold");
      doc.setFontSize(14);
      doc.setTextColor(68, 68, 68);



      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(10);
      var empleado;
      var departamento;
      var titulo;
      var total;
      var listado = data.length;

      doc.setTextColor(255, 255, 255);


      doc.setTextColor(255, 255, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(10, 62, 193, 2, 0, 0, 'FD');
      doc.roundedRect(10, 52, 193, 12, 1, 1, 'FD');
      doc.writeText(15, p2 ,'Dirección',{align:'center',width:17});
      doc.writeText(105, p2 ,"Total",{align:'center',width:5});
      doc.writeText(120, p2 ,"Novedades",{align:'center',width:74});


      doc.writeText(314, p2-2 ,"Total",{align:'center',width:5});
      doc.setDrawColor(204, 204, 204);
      doc.setFillColor(204, 204, 204);
      doc.setTextColor(0, 136, 176);

      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");

      for(var i in data){
        doc.setFontSize(8);

        doc.setFontType("bold");
        doc.writeText(15, p1 ,""+data[i].direccion+"",{align:'left',width:17});
        doc.setFontType("normal");

        doc.writeText(105, p1 ,""+data[i].total+"",{align:'center',width:5});


        var r_d1 = data[i].novedades;
        var r_lineas1 = doc.splitTextToSize(r_d1,80);
        doc.text(120, p1, r_lineas1);

        if(data[i].novedades.length>38&&data[i].novedades.length<=80)
        {
          p1+=10;
          //doc.line(10, p1+2, 202, p1+2);
        }
        if(data[i].novedades.length>80&&data[i].novedades.length<=190)
        {
          //doc.line(10, p1+2, 202, p1+2);
          p1+=15;
        }
        else if(data[i].novedades.length>190 && data[i].novedades.length<=250){
          p1+=25;
          //doc.line(10, p1+2, 202, p1+2);
        }
        else if(data[i].novedades.length>250 && data[i].novedades.length<=290){
          //doc.line(10, p1+2, 202, p1+2);
          p1+=30;
        }
        else if(data[i].novedades.length>290){
          p1+=40;
          //doc.line(10, p1+2, 202, p1+2);
        }
        else {
          //doc.line(10, p1+2, 202, p1+2);
          p1+=6;
        }



      }
      doc.setTextColor(0, 136, 176);
      doc.setFontType("bold");




      doc.setFontSize(8);
      doc.setTextColor(5, 83, 142);
      doc.writeText(10, 287 ,'Reporte Generado SAAS - Estado de Fuerza',{align:'center',width:190});

      doc.addImage(footer, 'png', 5, 290, 205, 15);
      doc.writeText(10, 291 ,'6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"',{align:'center',width:190});
      doc.writeText(10, 295 ,'PBX: 2327-600 FAX: 2327-6090',{align:'center',width:190});
      doc.setTextColor(255,255,255);
      doc.writeText(10, 302 ,""+d+"/"+mo+"/"+y+"  "+h+":"+mi+":"+m+"",{align:'right',width:193});
      //doc.writeText(55, 207,'https://saas.gob.gt/',{align:'right',width:265});
      /*doc.autoPrint()
      doc.save('vacaciones - '+data.emp+' - '+data.date +'.pdf');*/
      var x = document.getElementById("pdf_preview_novedades");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      //doc.save();

      $("#pdf_preview_novedades").attr("src", doc.output('datauristring'));
      $('#re_load').hide();
    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}
