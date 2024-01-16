function reporte_estado(){
  $.ajax({
    type: "POST",
    url: "reportes/hoja_reporte_estado.php",
    dataType:'json',
    beforeSend:function(){
      //$('.data_').html("<div class='spinner-grow-sm ' style='margin-left:0%'></div> <h6>Vacío</h6>");
    },
    success:function(data){

      var doc = new jsPDF('landscape','mm',[334,216]);
      doc.addImage(logo, 'png', 55, 10, 50, 40);
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
      doc.writeText(55, 20 ,"ESTADO DE FUERZA",{align:'center',width:270});
      doc.writeText(55, 27 ,""+d+"/"+mo+"/"+y+"",{align:'center',width:270});

      p1 = 70;
      p2 = 62;
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

      doc.setTextColor(0, 136, 176);

      doc.setTextColor(255, 255, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(55, 62, 270, 2, 0, 0, 'FD');
      doc.roundedRect(55, 52, 270, 12, 1, 1, 'FD');
      doc.setDrawColor(204, 204, 204);
      doc.setFillColor(204, 204, 204);

      doc.writeText(60, p2-2 ,"Direccion",{align:'center',width:17});
      doc.setFontSize(7);
      doc.text(153, p2, 'Servicio', null, grados);
      doc.text(168, p2 ,"Descanso", null, grados);
      doc.setFontSize(7);
      doc.text(183, p2 ,"Vacaciones", null, grados);
      doc.setFontSize(7);
      doc.text(198, p2 ,"IGSS", null, grados);
      doc.setFontSize(6);
      doc.text(213, p2 ,"Hospitalizado", null, grados);
      doc.setFontSize(7);
      doc.text(228, p2 ,"Permiso", null, grados);
      doc.text(243, p2 ,"Faltan", null, grados);
      doc.setFontSize(6);
      doc.text(258, p2 ,"Capacitacion", null, grados);
      doc.setFontSize(7);
      doc.text(273, p2 ,"Otros", null, grados);
      doc.text(288, p2 ,"Especiales", null, grados);
      doc.text(303, p2 ,"Asesores", null, grados);
      doc.setFontSize(10);
      doc.writeText(314, p2-2 ,"Total",{align:'center',width:5});

      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");
      var t1=0,t2=0,t3=0,t4=0,t5=0,t6=0,t7=0,t8=0,t9=0,t10=0,t11=0,t12=0;
      //alert(t4);
      for(var i in data){
        doc.setFontSize(8);
        doc.line(55, p1+2, 325, p1+2);
        doc.setFontType("bold");
        doc.writeText(60, p1 ,data[i].direccion,{align:'left',width:17});
        doc.setFontType("normal");
        doc.writeText(150, p1 ,""+data[i].servicio+"",{align:'center',width:5});
        doc.writeText(165, p1 ,""+data[i].descanso+"",{align:'center',width:5});
        doc.writeText(180, p1 ,""+data[i].vacaciones+"",{align:'center',width:5});
        doc.writeText(195, p1 ,""+data[i].igss+"",{align:'center',width:5});
        doc.writeText(210, p1 ,""+data[i].hospitalizado+"",{align:'center',width:5});
        doc.writeText(225, p1 ,""+data[i].permiso+"",{align:'center',width:5});
        doc.writeText(240, p1 ,""+data[i].faltan+"",{align:'center',width:5});
        doc.writeText(255, p1 ,""+data[i].capacitacion+"",{align:'center',width:5});
        doc.writeText(270, p1 ,""+data[i].otros+"",{align:'center',width:5});
        doc.writeText(285, p1 ,""+data[i].especiales+"",{align:'center',width:5});
        doc.writeText(300, p1 ,""+data[i].asesores+"",{align:'center',width:5});
        doc.writeText(315, p1 ,""+data[i].total+"",{align:'center',width:5});
        t1+=parseInt(data[i].servicio);
        t2+=parseInt(data[i].descanso);
        t3+=parseInt(data[i].vacaciones);
        t4+=parseInt(data[i].igss);
        t5+=parseInt(data[i].hospitalizado);
        t6+=parseInt(data[i].permiso);
        t7+=parseInt(data[i].faltan);
        t8+=parseInt(data[i].capacitacion);
        t9+=parseInt(data[i].otros);
        t10+=parseInt(data[i].especiales);
        t11+=parseInt(data[i].asesores);
        t12+=parseInt(data[i].total);
        console.log(t4);

        p1+=6;
      }
      doc.setTextColor(0, 136, 176);
      doc.setFontType("bold");
      doc.line(55, p_linea, 325, p_linea);
      doc.line(55, p_linea, 55, p1+3);
      doc.line(145, p_linea, 145, p1+3);
      doc.line(160, p_linea, 160, p1+3);
      doc.line(175, p_linea, 175, p1+3);
      doc.line(190, p_linea, 190, p1+3);
      doc.line(205, p_linea, 205, p1+3);
      doc.line(220, p_linea, 220, p1+3);
      doc.line(235, p_linea, 235, p1+3);
      doc.line(250, p_linea, 250, p1+3);
      doc.line(265, p_linea, 265, p1+3);
      doc.line(280, p_linea, 280, p1+3);
      doc.line(295, p_linea, 295, p1+3);
      doc.line(310, p_linea, 310, p1+3);
      doc.line(325, p_linea, 325, p1+3);
      doc.line(55, p1+3, 325, p1+3);




      doc.writeText(60, p1 ,"TOTALES",{align:'center',width:17});
      doc.writeText(150, p1 ,""+t1+"",{align:'center',width:5});
      doc.writeText(165, p1 ,""+t2+"",{align:'center',width:5});
      doc.writeText(180, p1 ,""+t3+"",{align:'center',width:5});
      doc.writeText(195, p1 ,""+t4+"",{align:'center',width:5});
      doc.writeText(210, p1 ,""+t5+"",{align:'center',width:5});
      doc.writeText(225, p1 ,""+t6+"",{align:'center',width:5});
      doc.writeText(240, p1 ,""+t7+"",{align:'center',width:5});
      doc.writeText(255, p1 ,""+t8+"",{align:'center',width:5});
      doc.writeText(270, p1 ,""+t9+"",{align:'center',width:5});
      doc.writeText(285, p1 ,""+t10+"",{align:'center',width:5});
      doc.writeText(300, p1 ,""+t11+"",{align:'center',width:5});
      doc.writeText(315, p1 ,""+t12+"",{align:'center',width:5});



      doc.setFontSize(8);
      doc.setTextColor(5, 83, 142);
      doc.writeText(55, 192 ,'Reporte Generado SAAS - Estado de Fuerza',{align:'center',width:270});

      doc.addImage(footer, 'png', 55, 195, 270, 15);
      doc.writeText(55, 196 ,'6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"',{align:'center',width:270});
      doc.writeText(55, 200 ,'PBX: 2327-600 FAX: 2327-6090',{align:'center',width:270});
      doc.setTextColor(255,255,255);
      doc.writeText(55, 207 ,""+d+"/"+mo+"/"+y+"  "+h+":"+mi+":"+m+"",{align:'right',width:267});
      //doc.writeText(55, 207,'https://saas.gob.gt/',{align:'right',width:265});
      /*doc.autoPrint()
      doc.save('vacaciones - '+data.emp+' - '+data.date +'.pdf');*/
      var x = document.getElementById("pdf_preview_estado");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      //doc.save();

      $("#pdf_preview_estado").attr("src", doc.output('datauristring'));
      $('#re_load').hide();
    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}
