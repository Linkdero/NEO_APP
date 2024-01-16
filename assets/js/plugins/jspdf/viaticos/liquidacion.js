var intanciaD;
function imprimirRazonamiento(id_nombramiento,id_empleado){

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/nombramiento_definitivo.php",
    data: {
      id_nombramiento:id_nombramiento,
      dia:'',
      mes:'',
      year:'',
      id_empleado:id_empleado
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //inicio
      var info = data.data[0];
      console.log(info.descripcion_lugar);
      var destinos = '';
      var tDestinos = info.destinos.length;
      var incremental = 0;
      if (info.descripcion_lugar == 2) {
        incremental ++;
        var and = (incremental == tDestinos) ? ' y ' : ', ';
        for (nombreIndice in info.destinos) {
          destinos += info.destinos[nombreIndice].dep+ and;
        }
      } else {
        destinos = info.lugar;
      }

      var textoRango = '';

      if(info.nombre_mes_ini == info.nombre_mes_fin){
        if(info.dia_ini == info.dia_fin){
          textoRango = `EL ${info.dia_ini} DE ${info.nombre_mes_ini} DE ${info.year_comision}`;
        }else{
          textoRango = `DEL ${info.dia_ini} AL ${info.dia_fin} DE ${info.nombre_mes_ini} DE ${info.year_comision}`;
        }
      }else{
        textoRango = `DEL ${info.dia_ini}  DE ${info.nombre_mes_ini} AL ${info.dia_fin} DE ${info.nombre_mes_fin} DE ${info.year_comision}`;
      }

      $.ajax({
        type: "POST",
        url: "viaticos/php/back/hojas/razonamiento.php",
        data: { id_nombramiento, id_empleado },
        dataType: 'json', //f de fecha y u de estado.
        beforeSend: function () {

        },
        success: function (data) {
          var doc = new jsPDF('p', 'pt');
          var x = document.getElementById("pdf_preview_v");

          var totalPages = data.data.length;
          console.log(data);
          var p = 0;
          var facturaText = '';
          var facturaTexto = '';
          var facturaTextoE = '';

          const fontSize = 13;
          const lineSpacing = 12;

          let startX = 56;
          let startY = 470;
          const endX = 485;







          var reformattedArray = data.data.map(function(rObjPagina){
            //var rObjPagina = {};
            doc.addImage(baner, 'png', 75, 0, 455, 95);

            doc.setDrawColor(255, 255, 255);
            doc.setFillColor(255, 255, 255);
            doc.rect(210, 58, 110, 15, "FD")

            punto = 200;
            doc.setTextColor(5, 83, 142);
            doc.setFontSize(8);
            doc.writeText(0, 742, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 570 });
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(5, 760, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 570 });
            doc.writeText(5, 770, 'PBX: 2327 - 6000 FAX: 2327 - 6000', { align: 'center', width: 570 });
            doc.writeText(5, 780, 'https://www.saas.gob.gt', { align: 'center', width: 570 });

            doc.setDrawColor(14, 4, 4);
            doc.setLineWidth(0.1);
            doc.line(0, 748, 600, 748);
            doc.setTextColor(68, 68, 68);
            p ++;
            doc.setFontType("normal");
            doc.setFontSize(8);
            //doc.writeText(5, 15, 'Página: ' + p + '/' + totalPages, { align: 'right', width: 570 });
            doc.setFontType("bold");
            doc.setFontSize(11);

            doc.writeText(5, 105, info.direccion, { align: 'center', width: 580 });


            var conceptoViatico = '';



            if (rObjPagina.factura_tipo == 1) {
              conceptoViatico = 'POR CONSUMO DE ALIMENTOS';
            } else if (rObjPagina.factura_tipo == 2){
              conceptoViatico = 'POR SERVICIO DE HOSPEDAJE';
            }

            console.log(rObjPagina.facturas[0].id_pais);
            var idPais = rObjPagina.facturas[0].id_pais;

            facturaText = (rObjPagina.facturas.length == 1 ) ? 'FACTURA ' : 'FACTURAS ','';

            facturaTexto = (idPais == 'GT') ? facturaText : '';

            var reformattedArray2 = rObjPagina.facturas.map(function(obj){

              var tipoDocto = (idPais == 'GT') ? `SERIE: ${obj.factura_serie}` : 'DOCUMENTO';
              var rObj = {};
              if(rObjPagina.dia_id == obj.dia_id  && rObjPagina.factura_tipo == obj.factura_tipo){
                var tipoUtilizado = (rObjPagina.factura_tipo == 1 || rObjPagina.factura_tipo == 2) ? '' : obj.motivo_gastos + ', ';
                facturaTexto += `${tipoDocto} No.: ${obj.factura_numero} DE ${obj.proveedor} DE FECHA: ${obj.fecha},  ${tipoUtilizado} `;
                if(obj.flag_error == 1){
                  facturaTextoE += `${tipoDocto} No.: ${obj.factura_numero} DE ${obj.proveedor} FUE IMPRESA CON FECHA: ${obj.fecha}, `;
                }
               }
            });

            var decimales = (rObjPagina.monto_decimales == 0) ? '00' : rObjPagina.monto_decimales;

            var conc = (conceptoViatico != '') ? `**${conceptoViatico}** `: '';

            var personal = (idPais == 'GT') ? 'PARA PERSONAL ' : '';

            //facturaTexto+= conc;
            facturaTexto+=`${rObjPagina.factura_concepto} ${personal}DE LA ${rObjPagina.dir} DE LA SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD DE LA PRESIDENCIA DE LA REPÚBLICA -SAAS- PARA CUBRIR COMISIÓN OFICIAL A ${destinos}`;
            facturaTexto+=` QUE SE LLEVÓ A CABO ${textoRango} SEGÚN NOMBRAMIENTO ${info.nombramiento} POR VALOR TOTAL DE ${rObjPagina.monto_letras} QUETZALES ${decimales}/100 (Q ${rObjPagina.monto}).`;
            //multilineaConNegrita(10,100,200,facturaTexto,8);



            doc.setFontSize(10.8);
            if(rObjPagina.factura_tipo == 1){
              if(rObjPagina.dia_observaciones_al.length > 0){
                doc.setFontType('bold');
                facturaTexto += ' **NOTA ACLARATORIA:** ';
                facturaTexto += `\n${rObjPagina.dia_observaciones_al}`;
                facturaTexto += ' '+facturaTextoE;
              }
            }

            if(rObjPagina.factura_tipo == 2){
              if(rObjPagina.dia_observaciones_hos.length > 0){
                doc.setFontType('bold');
                facturaTexto += ' **NOTA ACLARATORIA:** ';
                facturaTexto += `\n${rObjPagina.dia_observaciones_hos}`;
                facturaTexto += ' '+facturaTextoE;
              }

            }
            //doc.text(facturaTexto, 14, 170, {maxWidth: 186, align: "justify"});

            //doc.setFont("Arial").setFontSize(fontSize).setFontStyle("normal");
            doc.setLineWidth(1);
            //doc.setFont("arial")
            doc.setFontType("normal");
            printCharacters(doc, facturaTexto, startY, startX, endX);

            doc.line(50, 685, 280, 685);
            doc.line(320, 685, 540, 685);
            doc.text(info.empleado, 160, 700, {maxWidth: 380, align: "center"});

            //var director = (info.usr_autoriza == 8362 || info.usr_autoriza == 8449) ? 'SUBSECRETARIO' : 'DIRECTOR';
            var director = 'APROBADO POR';

            doc.text(director, 430, 700, {maxWidth: 100, align: "center"});
            if(p < totalPages){
              doc.addPage();
            }
          });
          if (x.style.display === "none") {
            x.style.display = "none";
          } else {
            x.style.display = "none";
          }
          doc.autoPrint()
          $("#pdf_preview_v").attr("src", doc.output('datauristring'));

          function multilineaConNegrita(x, y, endx, text) {
              const isBoldOpen = (arrayLength, valueBefore = false) => {
                  const isEven = arrayLength % 2 === 0;
                  const result = valueBefore !== isEven;
                  return result;
              };
              const lineSpacing = 5;
              const fontSize = 10;
              //mezcla de normal y negrita multiples lineas
              let startX = x;
              let startY = y;
              const endX = endx;

              //doc.setDrawColor();
              //doc.setTextColor();
              doc.setFontType("normal");
              doc.setFontSize(fontSize);
              doc.setLineWidth(1);
              let textMap = doc.splitTextToSize(text, endX);
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
                          doc.text(textItems, startX, startY);
                          startX = startX + doc.getStringUnitWidth(textItems) * 3.6;
                      });
                      boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                      startX = startXCached;
                      startY += lineSpacing;
                  }
              });
          }
        }
      }).done(function (data) {

      }).fail(function (jqXHR, textSttus, errorThrown) {
        alert(errorThrown);
      });
      //fin
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
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
