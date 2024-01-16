function licitacion_reporte(docto_id){

  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/hoja_licitacion.php",
    data: {docto_id},
    dataType:'json', //f de fecha y u de estado.
    beforeSend:function(){

    },
    success:function(data){
      //alert(data);
      //console.log(data);
      var documento;

      var doc = new jsPDF('p','mm');

      marge_i = 18;
      ancho= 190;
      var punto_=40;
      var punto=30;
      var correlativo,direccion, funcionario='', motivo='', fecha, fecha_ini, fecha_fin,duracion,funcionario;
      punto +=0;
      //console.log(data);
      doc.setFontType("normal");
      doc.setFontSize(9.5);
      i=0;
      //cabeceras_nombramiento(doc,punto,direccion,correlativo,nombramiento);
      incremental=1;
      doc.addImage(baner, 'png', 40, 0, 135, 30);
      //doc.writeText(5,8,'BASES DE '+ data.correlativo,{align:'center',width:200});
      //doc.writeText(5,25, data.titulo,{align:'center',width:200});
      doc.addPage();
      //doc.writeText(marge_i,8,'ÍNDICE',{align:'left',width:200});
      //doc.writeText(marge_i,16,'CAPÍTULO ÚNICO',{align:'left',width:200});
      for (nombreIndice in data.indice) {
        //console.log(data.indice[nombreIndice]);
        doc.addImage(baner, 'png', 40, 0, 135, 30);
        espacio=(i<9)?'  ':'';
        doc.text(marge_i, punto_, (i+1) + '. '+espacio+data.indice[nombreIndice].indice_string);
        punto_+=4;
        i ++;
      }
      doc.addPage();

      //inicio del capítulo 1
      punto_ =30;
      incremental=0;
      letras_x_pagina=0;
      f_validacion=false;
      totally= data.indice.length;
      for (nombreIndice in data.indice) {
        doc.addImage(baner, 'png', 40, 0, 135, 30);
        punto_+=5;
        punto+=10;

        doc.setFontSize(9);
        //punto=0;
        //console.log(data.indice[nombreIndice]);
        espacio=(incremental<9)?'  ':'';
        var tit=data.indice[nombreIndice].indice_string;
        letras_x_pagina+= tit.length;
        doc.text(marge_i, punto_, (incremental+1) + '. '+espacio+data.indice[nombreIndice].indice_string);
        if(incremental==0){
          objeto = `La Secretaría de Asuntos Administrativos y de Seguridad de la Presidencia de la República, la que de aquí en adelante se le podrá denominar la SAAS, para `+
          `cumplir con sus objetivos, requiere ofertas firmes para el **${data.titulo}**, por lo que se convoca a las personas individuales o jurícase,`+
          `nacionales o extranjeras, que se encuentren legalmente establecidas en el país, para presentar ofertas que se ajusten a los requisiots de las presentes bases, especificaciones técnicas`+
          `y disposiciones especiales que componen los documentos de ${data.categoria}`;



          multilineaConNegrita(marge_i,punto_+5,ancho,objeto,9.5);
          devuelve_saltos_parrafo(letras_x_pagina,objeto.length);

        }else
        // 2. inicio cronograma
        if(incremental==1){
          punto_+=4;
          ini_round=punto_;
          total=data.cronograma.length;

          inc=0;

            for (nombreIndice in data.cronograma) {
              actividad = `${data.cronograma[nombreIndice].actividad_string}`;
              fecha_plazo = `${data.cronograma[nombreIndice].actividad_fecha}`;
              var string = data.cronograma[nombreIndice].actividad_string;
              var string_f = data.cronograma[nombreIndice].actividad_fecha;
              var stringf=(string_f!=null)?data.cronograma[nombreIndice].actividad_fecha:'h';

              multilineaConNegrita(marge_i+4,punto_+5,90,actividad,9.5);
              multilineaConNegrita(112,punto_+5,90,fecha_plazo,9.5);

              conteo = string.length + stringf.length
              letras_x_pagina+=conteo;


              doc.setLineWidth(0);
              salto = devuelve_saltos(conteo);
              lineas = devuleve_lineas(conteo)
              //doc.line(marge_i, punto_+lineas+5, 203, punto_+lineas+5);


              inc ++;
              i ++;

              if(inc<total){
                punto_+=salto;

              }else{

                punto_+=20;
              }
              doc.text(5,punto_,'"'+letras_x_pagina+'"');

            }
            doc.roundedRect(marge_i, ini_round, 185, punto_, 1, 1);


            objeto2 = `Las ofertas se deben presentar en el Salón Oro de la Secretaría de Asuntos Administrativos y de Seguridad de la Presidencia de la República, `+
                      `ubicado en el segundo nivel del Anexo de Casa Presidencial, situada en la sexta (6a) Avenida "A" cuatro guion dieciocho (4-18), Callejón del Manchén, zona (1), `+
                      `ciudad de Guatemala el día ${data.cronograma[0].actividad_fecha}, a las string_horas, después de ésta hora no se aceptarán ofertas. La recepción y apertura de plicas se iniciará `+
                      `posteriormente a la hora límite para recibir ofertas. Las ofertas que no sean presentadas en el lugar y hora indicados no serán consideradas por la Junta de ${data.categoria} para `+
                      `su calificación y adjudicación.`;

            multilineaConNegrita(marge_i,punto_,ancho,objeto2+ ' -- '+conteo + ' -- '+ ' -- '+letras_x_pagina,9.5);
            devuelve_saltos_parrafo(letras_x_pagina,objeto2.length);

            //console.log('conteo 2: '+count_objeto2);
            objeto3 = `La junta de ${data.categoria}, en adelante denominada LA JUNTA, tendrá diez (10) días hábiles, posteriores a la fecha en que se reciban las ofertas para efectuar la adjudicación. `+
                      `El plazo para la adjudicación, podrá variar si LA JUNTA solicita prórroga por diez 810) días hábiles más a la Autoridad Administrativa Superior de la DAAS, cuando necesite contar con más `+
                      `tiempo para el análisis y calificación de las ofertas. **${`LA JUNTA deberá dedicar su jornada completa de trabajo al proceso de calificación de las ofertas, salvo caso fortuito o de fuerza mayor debidamente comprobado y justificado por el jefe inmediato superior.`}`;

            multilineaConNegrita(marge_i,punto_,ancho,objeto3+ ' -- '+conteo + ' -- '+ ' -- '+letras_x_pagina,9.5);
            devuelve_saltos_parrafo(letras_x_pagina,objeto3.length);
            verificar_saltos_pagina(letras_x_pagina,1);
            objeto4 = `Competencia de la Junta: "La Junta de ${data.categoria} es el único órgano competente para recibir, calificar ofertas y adjudicar el negocio; las decisiones las tomarán por mayoría simple de votos `+
                      `entre susu miembros. Los miembros de la junta pueden razonar su voto. Los miembres de las juntas no podrán abstenerse de votar ni ausentarse o retirarse del lugar en donde se encuentren constituidos durante la jornada `+
                      `de trabajo en el proceso de la adjudicación. La Junta de ${data.categoria} debe dejar constancia de todo lo actuado en las actas respectivas." Artículo 10, Decreto 57-92, de la Ley de Contrataciones del `+
                      `Estado, la que en el curso de las presentes bases se denominará LA LEY.`;

            multilineaConNegrita(marge_i,punto_,ancho,objeto4+ ' -- '+conteo + ' -- '+ ' -- '+letras_x_pagina,9.5);
            devuelve_saltos_parrafo(letras_x_pagina,objeto4.length);

            objeto5 = `LA JUNTA estará integrada por tres (3) miembros titulares y dos (2) miembros suplentes, entre los cuales se tomará en cuenta al personal de "PERSONAL NOMBRADO" de la SAAS, los cuales serán nombrados por la autoridad superior de la SAAS, previo informe `+
                      `sobre propuestas que remitirá la Dirección de Recursos Humanos a la Máxima Autoridad, de personal que cumpla con los requisitos establecidos en la ley, debiendo verificar su idoneidad, por su experiencia o conocimiento sufificente en los ámbitos legales, financieros y técnicos. `+
                      `(Artículo 11 de LA LEY) y (12 del Reglamento de la Ley de Contrataciones del Estado, Acuerdo Gubernativo no. 122-2016, que en curso de las presentes bases se denominará EL REGLAMENTO).`;

            multilineaConNegrita(marge_i,punto_,ancho,objeto5+ ' -- '+conteo + ' -- '+ ' -- '+letras_x_pagina,9.5);
            devuelve_saltos_parrafo(letras_x_pagina,objeto5.length);

            objeto6 = `Para las áreas legal y financiera los miembros de LA JUNTA, deberán de contar, indistintamente, con diplomas, títulos, certificaciones de cursos aprobados extendidos por universidades nacionales o extranjeras `+
                      `constancias de capacitación, constancias de trabajo u otras similares que acrediten conocimientos básicos en dichas áreas. En el ámbito técnico los miembros de LA JUNTA, deberán poseer, indistintamente diplomas `+
                      `o títulos técnicos, constancias de cursos o capacitaciones, constancias de trabajo que acrediten conocimientos en el área de telecomunicaciones.`;

            multilineaConNegrita(marge_i,punto_,ancho,objeto6+ ' -- '+conteo + ' -- '+ ' -- '+letras_x_pagina,9.5);
            devuelve_saltos_parrafo(letras_x_pagina,objeto6.length);
            verificar_saltos_pagina(letras_x_pagina,1);
            doc.text(5,punto_,'"'+letras_x_pagina+'"');


        }


        // fin 2. Cronograma


        //inicio 6. Listado de documentos
        if(incremental==5){
          punto_+=5;
          total=data.listadoDoctos.length;
          inc=0;
          ancho=180;

          for(nombreIndice in data.listadoDoctos) {
            literal = `${data.listadoDoctos[nombreIndice].base_literal_nom}`;
            descripcion = `**${data.listadoDoctos[nombreIndice].base_literal_titulo}** ${data.listadoDoctos[nombreIndice].base_literal_descripcion}`;

            conteo = literal.length + descripcion.length
            letras_x_pagina+=conteo;

            doc.text(marge_i,punto_,literal);
            multilineaConNegrita(marge_i+10,punto_,ancho-10,descripcion+ ' -- '+conteo + ' -- '+letras_x_pagina,9.5);
            devuelve_saltos_parrafo(letras_x_pagina,conteo);
            verificar_saltos_pagina(letras_x_pagina,5);

            doc.text(5,punto_,'"'+letras_x_pagina+'"');

          }
        }
        //fin 6. Listado de documentos
        //punto+=4;
        //punto_+=punto;
        console.log(punto_);
        if(incremental<totally){
          doc.text(5,punto_,'"'+letras_x_pagina+'"');
        }

        incremental ++;



      }




      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }

      function verificar_saltos_pagina(letras_x_pagina_,indice){
        console.log(letras_x_pagina);
        if(letras_x_pagina_>3000 && indice == 1){
          doc.addImage(baner, 'png', 40, 0, 135, 30);
          letras_x_pagina=0;
          punto_=40;
          doc.addPage();
        }
        if(letras_x_pagina_>5000){
          doc.addImage(baner, 'png', 40, 0, 135, 30);
          letras_x_pagina=0;
          punto_=40;
          doc.addPage();
        }
        console.log(letras_x_pagina);
      }

      function devuelve_saltos_parrafo(letras_x_pagina_,conteo){
        letras_x_pagina=(letras_x_pagina_+conteo);
        if(conteo>100 && conteo<=200){
          punto_+=5;
        }
        if(conteo>200 && conteo<=300){
          punto_+=10;
        }
        if(conteo>300 && conteo<=400){
          punto_+=15;
        }
        if(conteo>400 && conteo<=500){
          punto_+=20
        }
        if(conteo>500 && conteo<=600){
          punto_+= 25;
        }
        if(conteo>600 && conteo<=700){
          punto_+= 30;
        }if(conteo>700 && conteo<=800){
          punto_+= 35;
        }
        if(conteo>800 && conteo<=900){
          punto_+= 45;
        }
        if(conteo>900 && conteo<=1000){
          punto_+= 50;
        }
        if(conteo>1000 && conteo<=1100){
          punto_+= 55;
        }
        if(conteo>1100 && conteo<=1200){
          punto_+= 60;
        }
        if(conteo>1200 && conteo<=1300){
          punto_+= 65;
        }
        if(conteo>1300 && conteo<=1400){
          punto_+= 80;
        }
        else{
          punto_+= 5;
        }



      }


      function verificar_caracteres(letras_x_pagina){

        if(letras_x_pagina>2300){
          return true;
        }
      }

      function multilineaConNegrita (x,y,endx,text,fontSize){
          const isBoldOpen = (arrayLength, valueBefore = false) => {
              const isEven = arrayLength % 2 === 0;
              const result = valueBefore !== isEven;
              return result;
          };
          text = text.replace("b_line_", \r\n");
          doc.text(text, x, y, {maxWidth: endx, align: "justify"});
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
                      //doc.setFontType(boldOpen ? normalOr : boldStr);
                      /*if (j % 2 === 0) {
                          doc.setFontType(boldOpen ? boldStr : normalOr);
                      }*/
                      //doc.text(startX, startY,textItems);
                      //doc.writeText(startX,startY,textItems,{align:'justify',width:ancho});
                      //doc.text(textItems, startX, startY, {maxWidth: ancho, align: "justify"});
                      //startX = startX + doc.getStringUnitWidth(textItems) * 3.6;
                  });
                  //boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                  //startX = startXCached;
                  startY += lineSpacing;
              }
          });
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done( function(data) {

  }).fail( function( jqXHR, textSttus, errorThrown){
    alert(errorThrown);
  });
}

function devuelve_saltos(conteo){
  if(conteo>120 && conteo<=170){
    return 11;
  }else
  if(conteo>170 && conteo<=200){
    return 18;
  }else
  if(conteo>200 && conteo<=240){
    return 21;
  }else
  if(conteo>240 && conteo<=290){
    return 19;
  }else
  if(conteo>290 && conteo<=320){
    return 32;
  }else
  if(conteo>320 && conteo<=350){
    return 42;
  }else
  if(conteo>350 && conteo<=380){
    return 47;
  }else
  if(conteo>380 && conteo<=410){
    return 50;
  }else
  if(conteo>410 && conteo<=440){
    return 60;
  }else
  if(conteo>440 && conteo<=480){
    return 70;
  }else
  if(conteo>480 && conteo<=510){
    return 80;
  }else
  if(conteo>510 && conteo<=540){
    return 80;
  }else
  if(conteo>540 && conteo<=590){
    return 50;
  }
  else
  if (conteo<=60){
    return 5;
  }

}

function devuleve_lineas(conteo){
  if(conteo>120 && conteo<=170){
    return 7;
  }else
  if(conteo>170 && conteo<=200){
    return 7;
  }else
  if(conteo>200 && conteo<=240){
    return 17;

  }else
  if(conteo>240 && conteo<=290){
    return 14;
  }else
  if(conteo>290 && conteo<=320){
    return 35;
  }else
  if (conteo<=60){
    return 1;
  }

}
