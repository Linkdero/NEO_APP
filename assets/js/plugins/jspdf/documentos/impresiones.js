function justificacion_reporte(docto_id){
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/hoja_justificacion.php",
    data: {
      docto_id
    },
    dataType:'json', //f de fecha y u de estado.
    beforeSend:function(){

    },
    success:function(data){
      var documento;
      var registros = data.length;
      suma=0;

      var paginas = 1;
      var punto=100;

      var doc = new jsPDF('p','mm');
      var letras_x_pagina=0;

      //for(var i = 0; i<paginas; i++){


        function cabecera(x, total){
          doc.setFontType('normal');
          doc.addImage(baner, 'png', 40, -5, 135, 30);
          doc.setTextColor(5, 83, 142);
          doc.setFontSize(8);
          //doc.addImage(escudo_agua_2020,'png',15,140,200,110);
          /*doc.writeText(0, 253 ,'Reporte Generado Herramientas Administrativas - Módulo control de Documentos',{align:'center',width:215});
          doc.setFontType('bold');
          doc.setFontSize(10);
          doc.writeText(5,261,'6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA',{align:'center',width:209});
          doc.writeText(5,265,'PBX: 2327 - 6000 FAX: 2327 - 6090',{align:'center',width:209});
          doc.writeText(5,269,'https://www.saas.gob.gt',{align:'center',width:209});*/

        }

        //doc.line(0, 255, 220, 255);

        cabecera();

        doc.setFontType("bold");

            //punto-=20;
        doc.setFontType("normal");
        doc.setTextColor(68, 68, 68);
        doc.setFontSize(9);
            //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
            //documento = data.data[i].solicitud;

        doc.setFontType("bold");


        doc.setDrawColor(215,215,215);
        doc.setFontSize(8);
            //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
            //doc.line(75, 10, 75,50);

        doc.setFontType("bold");
        doc.setFontSize(11);

        letras_x_pagina+=data.direccion.length;

        doc.writeText(5,27,data.direccion,{align:'center',width:205});
        doc.setFontSize(9);

        //multilineaConNegrita(5,55,150,`JUSTIFICACION:**${data.correlativo}**`,9.5,'right');
        doc.setFontType('normal');
        letras_x_pagina+=data.fecha.length;
        letras_x_pagina+=data.correlativo.length;
        letras_x_pagina+=data.pedido_num.length;

        doc.writeText(120,35,'Fecha: ',{align:'left',width:175});
        doc.writeText(120,40,'Justificación No.: ',{align:'left',width:175});
        doc.writeText(120,45,'Pedido No.: ',{align:'left',width:175});
        doc.setFontSize(9);

        doc.setFontType("bold");
        doc.writeText(5,35,data.fecha,{align:'right',width:195});
        doc.writeText(5,40,data.correlativo,{align:'right',width:195});
        doc.writeText(5,45, data.pedido_num,{align:'right',width:195});

        doc.line(150, 36, 200, 36);
        doc.line(150, 41, 200, 41);
        doc.line(150, 46, 200, 46);
        doc.setFontSize(9);
        doc.setFontType('normal');

            //doc.writeText(5, 55 ,fecha,{align:'right',width:185});

            //doc.line(120, 74, 120, 125);
        doc.setFontSize(11);
        doc.setFontType("bold");
        doc.writeText(15,55,'1. SOLICITUD DEL BIEN O SERVICIO',{align:'left',width:205});

        doc.setFontSize(9);
        doc.setFontType("normal");
        doc.writeText(20,67,'Servicio, mantenimiento y/o reparación',{align:'left',width:205});
        doc.writeText(120,67,' Materiales, insumos o equipo',{align:'left',width:205});

        doc.setFontSize(30);
        if(data.pedido_tipo==1){
          doc.writeText(80,69,'X',{align:'left',width:205});
        }else{
          doc.writeText(167,69,'X',{align:'left',width:205});
        }
        doc.roundedRect(78, 60, 11, 11, 1, 1);
        doc.roundedRect(165, 60, 11, 11, 1, 1);

        doc.setFontSize(11);
        doc.setFontType("bold");
        doc.writeText(15,80,'2. JUSTIFICACIÓN GASTOS POR:',{align:'left',width:205});
        var punto = 85;
        var punto_rec = 80;doc.setFontSize(9);
        doc.setFontType('normal');
        letras_x_pagina+=data.titulo.length;
        doc.writeText(25,90,data.titulo,{align:'centar',width:205});

        var punto_fin=0;
        doc.setFontSize(9);
        doc.roundedRect(20, punto-2, 175, 10, 1, 1);
        doc.setFontType('normal');
        var total=data.insumos.length;
        punto+=15;
        doc.writeText(20,punto,'CANTIDAD:',{align:'left',width:205});
        var x=0;
        punto+=5;
        punto_inicio=punto-2.5;
        for(nombreIndice in data.insumos) {
          x ++;
          letras_x_pagina+=data.insumos[nombreIndice].Ppr_Nom.length;
          letras_x_pagina+=data.insumos[nombreIndice].Pedd_can.length;
          doc.writeText(30, punto+2 ,data.insumos[nombreIndice].Ppr_Nom,{align:'left',width:185});
          //doc.writeText(25, punto ,data.insumos[0].Ppr_Des,{align:'left',width:185});
          doc.writeText(175, punto+2 ,data.insumos[nombreIndice].Pedd_can,{align:'center',width:20});
          if(x<total){
            doc.line(20, punto+4.5, 195, punto+4.5);
          }
          punto +=7;
          punto_fin+=7;

        }

        doc.roundedRect(20, punto_inicio, 175, punto_fin, 1, 1);

        punto+=3;
        doc.writeText(20,punto,'ESPECIFICACIONES:',{align:'left',width:205});
        punto+=3;

        letras_x_pagina+=data.especificaciones.length;

        var lineas_e=parseInt(data.lineas_e);
        var r_lineas1 = doc.splitTextToSize(data.especificaciones.toUpperCase(), 165);
        doc.text(25, punto+4, r_lineas1);

        //if(data.especificaciones.length<= 150){
          doc.roundedRect(20, punto, 175, lineas_e-5, 1, 1);
          punto+=lineas_e;
        /*}else if(data.especificaciones.length> 150 && data.especificaciones.length <= 250){
          doc.roundedRect(20, punto+lineas_e, 175, 15, 1, 1);
          punto+=20+lineas_e;
        }else if(data.especificaciones.length> 250 && data.especificaciones.length <= 350){
          doc.roundedRect(20, punto+lineas_e, 175, 20, 1, 1);
          punto+=25+lineas_e;
        }else if(data.especificaciones.length> 350 && data.especificaciones.length <= 450){
          doc.roundedRect(20, punto+lineas_e, 175, 20, 1, 1);
          punto+=30+lineas_e;
        }*/


        doc.writeText(20,punto,'NECESIDAD:',{align:'left',width:205});
        punto+=3;
        letras_x_pagina+=data.necesidad.length;
        var r_lineas1 = doc.splitTextToSize(data.necesidad, 165);
        doc.text(25, punto+4, r_lineas1);

        if(data.necesidad.length<= 150){
          doc.roundedRect(20, punto, 175, 10, 1, 1);
          punto+=15;
        }else if(data.necesidad.length> 150 && data.necesidad.length <= 250){
          doc.roundedRect(20, punto, 175, 15, 1, 1);
          punto+=25;
        }else if(data.necesidad.length> 250 && data.necesidad.length <= 350){
          doc.roundedRect(20, punto, 175, 20, 1, 1);
          punto+=30;
        }
        verificar_saltos_pagina(punto);
        doc.writeText(20,punto,'TEMPORALIDAD DE USO DE LA ADQUISICIÓN:',{align:'left',width:205});
        punto+=3;
        doc.roundedRect(20, punto, 175, 10, 1, 1);
        letras_x_pagina+=data.temporalidad.length;
        var r_lineas1 = doc.splitTextToSize(data.temporalidad, 165);
        doc.text(25, punto+4, r_lineas1);

        verificar_saltos_pagina(punto);

        punto+=15;
        doc.writeText(20,punto,'FINALIDAD:',{align:'left',width:205});
        punto+=3;
        doc.roundedRect(20, punto, 175, 10, 1, 1);
        letras_x_pagina+=data.finalidad.length;
        var r_lineas1 = doc.splitTextToSize(data.finalidad, 165);
        doc.text(25, punto+4, r_lineas1);

        punto+=15;
        doc.writeText(20,punto,'RESULTADO DE LA COMPRA:',{align:'left',width:205});
        punto+=3;
        doc.roundedRect(20, punto, 175, 10, 1, 1);
        letras_x_pagina+=data.resultado.length;
        var r_lineas1 = doc.splitTextToSize(data.resultado, 165);
        doc.text(25, punto+4, r_lineas1);
        punto+=17;

        verificar_saltos_pagina(punto);

        doc.setFontSize(11);
        doc.setFontType("bold");
        doc.writeText(15,punto,'3. DIAGNÓSTICO TÉCNICO:',{align:'left',width:205});
        doc.setFontSize(9);
        punto+=7;
        doc.setFontType('normal');
        doc.writeText(68,punto,'SI',{align:'left',width:205});
        doc.writeText(110,punto,' NO',{align:'left',width:205});
        doc.setFontSize(30);

        if(data.pedido_diagnostico==1){
          doc.writeText(80,punto+3,'X',{align:'left',width:205});
        }else{
          doc.writeText(122,punto+3,'X',{align:'left',width:205});
        }
        doc.roundedRect(78, punto-6, 11, 11, 1, 1);
        doc.roundedRect(120, punto-6, 11, 11, 1, 1);
        punto+=12;
        doc.setFontSize(9);
        verificar_saltos_pagina(punto);
        if(data.pedido_diagnostico==1){
          doc.setFontSize(11);
          doc.writeText(20,punto,'DIAGNÓTICO No.:',{align:'left',width:205});
          doc.setFontType("bold");
          //letras_x_pagina+=data.necesidad.length;
          doc.setFontSize(9);
          var r_lineas1 = doc.splitTextToSize(data.dictamenes, 150);
          doc.text(55, punto, r_lineas1);
          punto+=25;
        }





        if(punto>240){
          //doc.writeText(15,punto+10,''+punto+'',{align:'left',width:205});
          doc.writeText(68,punto+10,'Firma Solicitante',{align:'left',width:205});
          doc.writeText(110,punto+10,'Vo. Bo. Del Director o Subdirector',{align:'left',width:205});
        }else{
          //doc.writeText(15,punto+15,''+punto+'',{align:'left',width:205});
          doc.writeText(68,punto+15,'Firma Solicitante',{align:'left',width:205});
          doc.writeText(110,punto+15,'Vo. Bo. Del Director o Subdirector',{align:'left',width:205});
        }








      //}


      function verificar_saltos_pagina(punto_){
        console.log(punto);
        if(punto_>260){


          letras_x_pagina=0;
          punto=35;
          doc.addPage();
          x+=1;
          cabecera();
          doc.setFontType('normal');
        }
      }
          function multilineaConNegrita (x,y,endx,text,fontSize,aling){
              const isBoldOpen = (arrayLength, valueBefore = false) => {
                  const isEven = arrayLength % 2 === 0;
                  const result = valueBefore !== isEven;
                  return result;
              };

              const lineSpacing = 5;
              //const fontSize = 9;
              //mezcla de normal y negrita multiples lineas
              let startX = x;
              let startY = y;
              const endX = endx;

              //doc.setDrawColor();
              //doc.setTextColor();
              doc.setFontType("normal");
              doc.setFontSize(fontSize);
              doc.setLineWidth(1);
              let textMap = doc.splitTextToSize(text,endX);
              const startXCached = startX;
              let boldOpen = false;
              textMap.map((text, i) => {
                  if (text) {
                      const arrayOfNormalAndBoldText = text.split('**');
                      const boldStr = 'bold';
                      const normalOr = 'normal';
                      arrayOfNormalAndBoldText.map((textItems, j) => {
                          doc.setFontType(boldOpen ? normalOr : boldStr);
                          if (j % 2 === 0) {
                              doc.setFontType(boldOpen ? boldStr : normalOr);
                          }
                          //doc.text(startX, startY,textItems);
                          doc.writeText(startX,startY,textItems,{align:aling,width:205});

                          //doc.text(textItems, startX, startY, {maxWidth: ancho, align: "justify"});
                          //startX = startX + doc.getStringUnitWidth(textItems) * 3.6;
                      });
                      boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                      startX = startXCached;
                      startY += lineSpacing;
                  }
              });
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

function impresion_pedido(ped_tra){
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/hoja_pedido.php",
    data: {
      ped_tra
    },
    dataType:'json', //f de fecha y u de estado.
    beforeSend:function(){
      Swal.fire({
        title: 'Cargando impresión!',
        html: "<div class='spinner-grow text-info'></div>",
        timer: 700,
        timerProgressBar: true,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading()
          timerInterval = setInterval(() => {
            const content = Swal.getHtmlContainer()
            if (content) {
              const b = content.querySelector('b')
              if (b) {
                b.textContent = Swal.getTimerLeft()
              }
            }
          }, 100)
        },
        willClose: () => {
          clearInterval(timerInterval)
        }
      }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
          console.log('I was closed by the timer')
        }
      })
    },
    success:function(data){
      var documento;
      var registros = data.length;
      suma=0;

      var paginas = 1;
      var punto=111;

      var doc = new jsPDF('p','mm');
      var letras_x_pagina=0;


        //doc.writeText(5,27,data.direccion,{align:'center',width:205});
        doc.setFontSize(9);



        doc.setFontType("normal");
        doc.writeText(5,42,data.fecha,{align:'right',width:185});
        doc.writeText(65,52,'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD',{align:'left',width:195});
        doc.writeText(35,60,'13 SEGURIDAD PRESIDENCIAL Y VICEPRESIDENCIAL',{align:'left',width:195});

        doc.writeText(65,68,data.direccion,{align:'left',width:195});
        doc.writeText(40,76,data.departamento,{align:'left',width:195});
        doc.writeText(40,85,'DEPARTAMENTO DE COMPRAS',{align:'left',width:195});
        /*doc.writeText(5,40,data.correlativo,{align:'right',width:195});
        doc.writeText(5,45, data.pedido_num,{align:'right',width:195});*/


        doc.setFontSize(9);
        doc.setFontType('normal');


        var total=data.insumos.length;
        //punto+=15;

        var x=0;
        punto+=5;
        punto_inicio=punto-2.5;
        for(nombreIndice in data.insumos) {
          x ++;
          letras_x_pagina+=data.insumos[nombreIndice].Ppr_Nom.length;
          letras_x_pagina+=data.insumos[nombreIndice].Pedd_can.length;



          //doc.writeText(25, punto ,data.insumos[0].Ppr_Des,{align:'left',width:185});
          doc.setFontSize(7);
          doc.writeText(11, punto ,data.insumos[nombreIndice].correlativo,{align:'center',width:20});
          doc.writeText(128, punto ,data.insumos[nombreIndice].Pedd_can,{align:'center',width:20});
          doc.writeText(146, punto ,data.insumos[nombreIndice].Ppr_Med,{align:'center',width:20});
          doc.writeText(175, punto ,data.insumos[nombreIndice].codificacion,{align:'center',width:20});
          var lineas_e=parseInt(data.insumos[nombreIndice].lineas);
          var r_lineas1 = doc.splitTextToSize(data.insumos[nombreIndice].descripcion, 98);
          doc.text(32, punto, r_lineas1);
          punto+=lineas_e;
        }

        var r_lineas1 = doc.splitTextToSize(data.observaciones, 170);
        doc.text(32, punto+4, r_lineas1);


      function verificar_saltos_pagina(punto_){
        console.log(punto);
        if(punto_>260){


          letras_x_pagina=0;
          punto=35;
          doc.addPage();
          x+=1;
          cabecera();
          doc.setFontType('normal');
        }
      }
          function multilineaConNegrita (x,y,endx,text,fontSize,aling){
              const isBoldOpen = (arrayLength, valueBefore = false) => {
                  const isEven = arrayLength % 2 === 0;
                  const result = valueBefore !== isEven;
                  return result;
              };

              const lineSpacing = 5;
              //const fontSize = 9;
              //mezcla de normal y negrita multiples lineas
              let startX = x;
              let startY = y;
              const endX = endx;

              //doc.setDrawColor();
              //doc.setTextColor();
              doc.setFontType("normal");
              doc.setFontSize(fontSize);
              doc.setLineWidth(1);
              let textMap = doc.splitTextToSize(text,endX);
              const startXCached = startX;
              let boldOpen = false;
              textMap.map((text, i) => {
                  if (text) {
                      const arrayOfNormalAndBoldText = text.split('**');
                      const boldStr = 'bold';
                      const normalOr = 'normal';
                      arrayOfNormalAndBoldText.map((textItems, j) => {
                          doc.setFontType(boldOpen ? normalOr : boldStr);
                          if (j % 2 === 0) {
                              doc.setFontType(boldOpen ? boldStr : normalOr);
                          }
                          //doc.text(startX, startY,textItems);
                          doc.writeText(startX,startY,textItems,{align:aling,width:205});

                          //doc.text(textItems, startX, startY, {maxWidth: ancho, align: "justify"});
                          //startX = startX + doc.getStringUnitWidth(textItems) * 3.6;
                      });
                      boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                      startX = startXCached;
                      startY += lineSpacing;
                  }
              });
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
