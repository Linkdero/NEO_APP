function imprimirCertificacion(bien_id){
  //inicio
  $.ajax({
    type: "POST",
    url: "inventario/model/Inventario",
    data: {
      opcion:2,
      certificacion_id:bien_id,
      tipo:1
    },
    dataType:'json', //f de fecha y u de estado.
    beforeSend:function(){
      //$('#response').html('<span class="text-info">Loading response...</span>');
    },
    success:function(data){
      //alert(data);
      //console.log(data);
      var documento;
      //var hojas = data.data.length;
      var doc = new jsPDF('p','pt');

      let startX = 60;
      let startY = 320;
      let endX = 490;

      var ini = 180;

      /*doc.setFontType("bold");
      doc.addImage(baner, 'png', -35, 7, 450, 90);
      doc.setDrawColor(255, 255, 255);
      doc.setFillColor(255, 255, 255);
      doc.rect(98, 60, 110, 15, "FD");
      //doc.fromHTML(document.getElementById("titulo_card"), 15,15, { 'width': 170 });
      doc.addImage(logo_saas, 'png', 425, 14, 125, 70);
      doc.setFontSize(12);
      doc.setFontType("bold");

      doc.writeText(startX, 130, 'DIRECCIÓN ADMINISTRATIVA Y FINANCIERA', { align: 'center', width: endX });
      doc.writeText(startX, 145, 'CERTIFICACIÓN DE INVENTARIOS', { align: 'center', width: endX });

      doc.setFontSize(10);
      doc.setFontType("normal");


      var header = `DE INVENTARIOS DE LA SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD DE LA PRESIDENCIA `+
      `HACE ENTREGA DE LAS CERTIFICACIONES DE LOS SIGUIENTES BIENES:`;

      doc.text(header, startX, 180, {maxWidth: endX, align: "justify"});

      const fontSize = 25;
      const lineSpacing = 15;
      ini +=40;

      startXX = startX + 45;

      doc.text('No. de Bien', startXX, ini, {maxWidth: endX, align: "left"});
      doc.text('Renglón', startXX+120, ini, {maxWidth: endX, align: "left"});
      doc.text('Monto', startXX+240, ini, {maxWidth: endX, align: "left"});
      doc.text('Fecha', startXX+360, ini, {maxWidth: endX, align: "left"});

      ini +=15;
      var reformattedArray = data.certificaciones.map(function(obj){
        doc.writeText(5, ini, 'No. de Bien: ' + obj.bien_sicoin_code, { align: 'center', width: 580 });
        ini += 15;
      });


      doc.addPage();*/
      var hojas = data.certificaciones.length;
      var reformattedArray = data.certificaciones.map(function(obj){
        doc.setFontType("normal");
        doc.setDrawColor(215, 215, 215);
        doc.setFontSize(9);

        //inicio
        startX = 70;
        startY = 320;
        endX = 490;
        punto=10;

        doc.addImage(baner, 'png', -25, 7, 450, 90);
        doc.setDrawColor(255, 255, 255);
        doc.setFillColor(255, 255, 255);
        doc.rect(108, 60, 110, 15, "FD");
        //doc.fromHTML(document.getElementById("titulo_card"), 15,15, { 'width': 170 });
        doc.addImage(logo_saas, 'png', 425, 14, 125, 70);

        doc.setTextColor(5, 83, 142);
        doc.setFontSize(9);
        doc.writeText(startX, 735, 'Reporte Generado Herramientas Administrativas - Módulo de Inventarios', { align: 'center', width: endX });
        //doc.setFontType('bold');
        doc.setFontSize(9);
        doc.writeText(startX, 747, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: endX });
        doc.writeText(startX, 755, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: endX });
        doc.writeText(startX, 763, 'https://www.saas.gob.gt', { align: 'center', width: endX });
        doc.setDrawColor(0, 171, 255);
        doc.setFillColor(0, 171, 255);
        doc.line(0, 738, 700, 738);

        doc.setTextColor(0, 0, 0);
        doc.setFontSize(12);
        doc.setFontType("bold");

        doc.writeText(startX, 130, 'DIRECCIÓN ADMINISTRATIVA Y FINANCIERA', { align: 'center', width: endX });
        doc.writeText(startX, 145, 'CERTIFICACIÓN DE INVENTARIOS', { align: 'center', width: endX });
        doc.writeText(startX, 160, 'Correlativo: '+obj.correlativo + ' - '+obj.year, { align: 'center', width: endX });

        //const inputValue = "this is **bold and** normal font in just one **line**.";
        doc.setFontSize(11);

        doc.setLineHeightFactor(1.5);
        doc.setFontType('normal');
        var header = `${obj.encargado} DE INVENTARIOS DE LA SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD DE LA PRESIDENCIA `+
        `DE LA REPUBLICA, SAAS |CERTIFICA:| QUE EN EL SISTEMA DE CONTABILIDAD INTEGRADA DE INVENTARIOS DE ACTIVOS FIJOS (SICOIN), APARECE REGISTRADO `+
        `EL BIEN QUE SE DESCRIBE A CONTINUACIÓN:`;

        const fontSize = 25;
        const lineSpacing = 15;

        var footer = `Y PARA LOS USOS QUE AL INTERESADO CONVENGA SE EXTIENDE LA PRESENTE CERTIFICACIÓN A LOS XX DEL MES DE XXXX DEL AÑO XX MIL XXXX`;


        //doc.text(header, startX, 220, {maxWidth: 480, align: "justify"});
        printCharacters(doc, header, 220, startX, 470);

        doc.setLineHeightFactor(1.5);

        doc.setFontType('normal');

        var actual = printCharacters(doc, obj.clean_string, startY, startX, 470);

        //printCharacters(doc, data.texto_, startY+150, startX, endX);

        doc.text(obj.fecha_certificacion, startX, actual+15, {maxWidth: 470, align: "justify"});
        //printCharacters(doc, obj.fecha_certificacion, 520, startX, 480);

        //printCharacters(doc, '**' + obj.anotacionV + '**', 700, startX, 470);


        if(obj.bien_renglon_id == 325){
          //doc.text(obj.anotacionV, startX, 720, {maxWidth: 480, align: "justify"});

        }


        hojas --;

        if(hojas > 0){
          doc.setTextColor(0, 0, 0);
          doc.addPage();
        }

        //fin
      });

      /*doc.writeText(10, punto+15 ,data.bien_sicoin_code,{align:'right',width:194});
      doc.setTextColor(33, 33, 33);
      doc.writeText(25, punto+16 ,data.bien_fecha_adquisicion,{align:'left',width:65});
      doc.writeText(25, punto+20 ,data.bien_monto,{align:'left',width:65});
      var r_d1 = data.bien_descripcion;
      var r_lineas1 = doc.splitTextToSize(r_d1, 30);
      doc.text(75.5, punto+55, r_lineas1);*/
      //finaliza persona nombrada
      //doc.line(108, punto+176, 108, punto+209);
      doc.setFontSize(9);
      doc.setFontType("normal");
      //doc.setTextColor(255, 255, 255);
      //doc.setTextColor(255, 255, 255);
      doc.setTextColor(33, 33, 33);
      //doc.writeText(0, punto+150 ,data.data[i].resolucion,{align:'center',width:215});
      doc.setFontType("normal");
      doc.setFontSize(7.5);


      doc.setFontType("bold");
      doc.setFontSize(9);
      /*doc.writeText(12, punto+262 ,'Original: Tesorería',{align:'center',width:95});
      doc.writeText(108, punto+262 ,'Original: Tesorería',{align:'center',width:95});*/
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
  //fin
}

//inicio

function imprimirSticker(bien_id){
  $.ajax({
    type: "POST",
    url: "inventario/model/Inventario",
    data: {
      opcion:2,
      bien_id:bien_id,
      tipo:2
    },
    dataType:'json', //f de fecha y u de estado.
    beforeSend:function(){
      //$('#response').html('<span class="text-info">Loading response...</span>');
    },
    success:function(data){
      //alert(data);
      //console.log(data);
      var documento;
      //var hojas = data.data.length;
      //var doc = new jsPDF('l', 'pt',[150,70]);// 3 pulgadas por 1 pulgada
      var doc = new jsPDF('l', 'pt',[180,180]);
      let startX = 90;
      let startY = 320;
      const endX = 490;
      punto=10;
      //doc.setTextColor(203, 50, 52);

      //doc.roundedRect(0.1, 0.1, 220, 140, 1, 1);

      var image = new Image();

      var qrcode = new QRious({
        element: image,
        value: data.url_code, // La URL o el texto
        size: 100,
        backgroundAlpha: 0, // 0 para fondo transparente
        foreground: "#000", // Color del QR
        level: "H", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
      });

      doc.addImage(image,50,50,110,110);
      doc.setLineHeightFactor(7);

      //doc.text(footer, startX, 420, {maxWidth: endX, align: "justify"});
      doc.setFontSize(11);


      doc.setFontSize(9);
      doc.setFontType("normal");
      //doc.setTextColor(255, 255, 255);
      //doc.setTextColor(255, 255, 255);
      doc.setTextColor(33, 33, 33);
      //doc.writeText(0, punto+150 ,data.data[i].resolucion,{align:'center',width:215});
      doc.setFontType("normal");
      doc.setFontSize(7.5);


      doc.setFontType("bold");
      doc.setFontSize(9);
      /*doc.writeText(12, punto+262 ,'Original: Tesorería',{align:'center',width:95});
      doc.writeText(108, punto+262 ,'Original: Tesorería',{align:'center',width:95});*/
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

//fin

const printCharacters = (doc, text, startY, startX, width) => {
    const startXCached = startX;
    const boldStr = 'bold';
    const normalStr = 'normal';
    const fontSize = doc.getFontSize();
    //const lineSpacing = doc.getLineHeightFactor() + fontSize;
    const lineSpacing = 15.5;//doc.setLineHeightFactor(1.5);

    let textObject = getTextRows(doc, text, width);

    console.log(textObject);

    textObject.map((row, rowPosition) => {
      //console.log('Fila' + rowPosition);
        Object.entries(row.chars).map(([key, value]) => {
            doc.setFontType(value.bold ? boldStr : normalStr);

            doc.text(value.char, startX, startY);

            if(value.char == ' ' && rowPosition < textObject.length - 1){
                startX += row.blankSpacing;
            } else {
                startX += doc.getStringUnitWidth(value.char) * fontSize;
            }
        });
        startX = startXCached;
        startY += lineSpacing;
    });

    return startY;

};

const getTextRows = (doc, inputValue, width) => {
    const regex = /(\*{2})+/g; // all "**" words
    const textWithoutBoldMarks = inputValue.replace(regex, '');
    const boldStr = 'bold';
    const normalStr = 'normal';
    const fontSize = doc.getFontSize();

    let splitTextWithoutBoldMarks = doc.splitTextToSize(
        textWithoutBoldMarks,
        490
    );

    let charsMapLength = 0;
    let position = 0;
    let isBold = false;

    // <><>><><>><>><><><><><>>><><<><><><><>
    // power algorithm to determine which char is bold
    let textRows = splitTextWithoutBoldMarks.map((row, i) => {

        const charsMap = row.split('');

        const chars = charsMap.map((char, j) => {
           position = charsMapLength + j + i;
           let currentChar = inputValue.charAt(position);

           var firstChar = (j == 0) ? currentChar : '';

           if (currentChar === "|") {
             isBold = !isBold;
             currentChar = currentChar.replace('|', '');
              const spyNextChar = inputValue.charAt(position + 1);
              const spyNextCharr = inputValue.charAt(position + 2);
              if (spyNextChar === "|") {
                // double asterix marker exist on these position's so we toggle the bold state
                isBold = !isBold;
                currentChar = inputValue.charAt(position + 2);

                // now we remove the markers, so loop jumps to the next real printable char
                let removeMarks = inputValue.split('');
                removeMarks.splice(position, 2);
                inputValue = removeMarks.join('');
              }
              else if (spyNextChar === " " && j == 0) {
                // double asterix marker exist on these position's so we toggle the bold state
                isBold = false;
                currentChar = currentChar.replace('|', '');

              }



           }
           return { char: currentChar, bold: isBold };
        });
        charsMapLength += charsMap.length;

        // Calculate the size of the white space to justify the text
        let charsWihoutsSpacing = Object.entries(chars).filter(([key, value]) => value.char != ' ');
        let widthRow = 0;

        charsWihoutsSpacing.forEach(([key, value]) => {
            // Keep in mind that the calculations are affected if the letter is in bold or normal
            doc.setFont(undefined, value.bold ? boldStr : normalStr);
            widthRow += doc.getStringUnitWidth(value.char) * fontSize;
        });

        let totalBlankSpaces = charsMap.length - charsWihoutsSpacing.length;
        let blankSpacing = (width - widthRow) / totalBlankSpaces;

        return {blankSpacing: blankSpacing, chars: { ...chars }};
    });

    return textRows;
}

function showFacturaDetalle(data){
  intanciaD.getDetalleFactura(data);
}
