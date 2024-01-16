function imprimirSticker(bien_id){
  $.ajax({
    type: "POST",
    url: "inventario/model/Inventario",
    data: {
      opcion:2,
      bien_id:bien_id
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
      const endX = 490;
      punto=10;
      //doc.setTextColor(203, 50, 52);

      var image = new Image();

      var qrcode = new QRious({
        element: image,
        value: data.bien_sicoin_code, // La URL o el texto
        size: 100,
        backgroundAlpha: 0, // 0 para fondo transparente
        foreground: "#000", // Color del QR
        level: "H", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
      });

      doc.addImage(image,420,600,100,100)
      //doc.fromHTML(document.getElementById("titulo_card"), 15,15, { 'width': 170 });
      doc.addImage(logo_saas, 'png', 230, 30, 150, 95);

      doc.writeText(startX, 160, 'CERTIFICACIÓN DE INVENTARIOS', { align: 'center', width: endX });

      //const inputValue = "this is **bold and** normal font in just one **line**.";
      doc.setFontSize(10.1);

      doc.setLineHeightFactor(1.5);
      doc.setFontType('normal');
      var header = `EL INFRASCRITO ASISTENTE DE INVENTARIOS DE LA SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD DE LA PRESIDENCIA `+
      `DE LA REPUBLICA, SAAS CERTIFICA: QUE EN EL SISTEMA DE CONTABILIDAD INTEGRADA DE INVENTARIOS DE ACTIVOS FIJOS (SICOIN), APARECE REGISTRADO `+
      `EL BIEN QUE SE DESCRIBE A CONTINUACIÓN:`;

      const fontSize = 25;
      const lineSpacing = 15;

      let descripcionBien = data.bien_descripcion.replace('\r\n', data.bien_descripcion);

      console.log(data.bien_descripcion);

      var textoDetalle = `${descripcionBien}, MARCA **${data.marca}**, MODELO **${data.modelo}**, NUMERO DE SERIE **${data.serie}**, NUMERO DE BIEN **${data.bien_sicoin_code}**`;
      console.log(textoDetalle);


      //var body = `DESCRIPCIÓN DEL BIEN ${data.bien_descripcion}, NUMERO DE INVENTARIO ${data.bien_sicoin_code}.`;

      var footer = `Y PARA LOS USOS QUE AL INTERESADO CONVENGA SE EXTIENDE LA PRESENTE CERTIFICACIÓN A LOS XX DEL MES DE XXXX DEL AÑO XX MIL XXXX`;


      doc.text(header, startX, 190, {maxWidth: endX, align: "justify"});

      //doc.text(body, startX, 300, {maxWidth: endX, align: "justify"});
      doc.setLineHeightFactor(7);
      printCharacters(doc, data.clean_string, startY, startX, endX);

      printCharacters(doc, data.texto_, startY+150, startX, endX);
      doc.setLineHeightFactor(1.5);

      //doc.text(footer, startX, 420, {maxWidth: endX, align: "justify"});
      doc.setFontSize(11);


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
}

const printCharacters = (doc, text, startY, startX, width) => {
    const startXCached = startX;
    const boldStr = 'bold';
    const normalStr = 'normal';
    const fontSize = doc.getFontSize();
    const lineSpacing = doc.getLineHeightFactor() + fontSize;

    let textObject = getTextRows(doc, text, width);

    textObject.map((row, rowPosition) => {

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
};

const getTextRows = (doc, inputValue, width) => {
    const regex = /(\*{2})+/g; // all "**" words
    const textWithoutBoldMarks = inputValue.replace(regex, '');
    const boldStr = 'bold';
    const normalStr = 'normal';
    const fontSize = doc.getFontSize();

    let splitTextWithoutBoldMarks = doc.splitTextToSize(
        textWithoutBoldMarks,
        485
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

           if (currentChar === "*") {
              const spyNextChar = inputValue.charAt(position + 1);
              if (spyNextChar === "*") {
                 // double asterix marker exist on these position's so we toggle the bold state
                 isBold = !isBold;
                 currentChar = inputValue.charAt(position + 2);

                  // now we remove the markers, so loop jumps to the next real printable char
                  let removeMarks = inputValue.split('');
                  removeMarks.splice(position, 2);
                  inputValue = removeMarks.join('');
                }
                else if(spyNextChar === " "){
                  //console.log('espacio');
                  isBold = false;
                  currentChar = inputValue.charAt(position + 2);

                   // now we remove the markers, so loop jumps to the next real printable char
                   let removeMarks = inputValue.split('');
                   removeMarks.splice(position, 2);
                   inputValue = removeMarks.join('');
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
