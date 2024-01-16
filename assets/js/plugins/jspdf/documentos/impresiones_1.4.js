function justificacion_reporte(docto_id) {
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/hoja_justificacion.php",
    data: {
      docto_id
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      var documento;
      var registros = data.length;
      suma = 0;

      var paginas = 1;
      var punto = 100;

      var doc = new jsPDF('p', 'mm');
      var letras_x_pagina = 0;

      //for(var i = 0; i<paginas; i++){


      function cabecera(x, total) {
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


      doc.setDrawColor(215, 215, 215);
      doc.setFontSize(8);
      //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
      //doc.line(75, 10, 75,50);

      doc.setFontType("bold");
      doc.setFontSize(11);

      letras_x_pagina += data.direccion.length;

      doc.writeText(5, 27, data.direccion, { align: 'center', width: 205 });
      doc.setFontSize(9);

      //multilineaConNegrita(5,55,150,`JUSTIFICACION:`+data.correlativo+``,9.5,'right');
      doc.setFontType('normal');
      letras_x_pagina += data.fecha.length;
      letras_x_pagina += data.correlativo.length;
      letras_x_pagina += data.pedido_num.length;

      doc.writeText(120, 35, 'Fecha: ', { align: 'left', width: 175 });
      doc.writeText(120, 40, 'Justificación No.: ', { align: 'left', width: 175 });
      doc.writeText(120, 45, 'Pedido No.: ', { align: 'left', width: 175 });
      doc.setFontSize(9);

      doc.setFontType("bold");
      doc.writeText(5, 35, data.fecha, { align: 'right', width: 195 });
      doc.writeText(5, 40, data.correlativo, { align: 'right', width: 195 });
      doc.writeText(5, 45, data.pedido_num, { align: 'right', width: 195 });

      doc.line(150, 36, 200, 36);
      doc.line(150, 41, 200, 41);
      doc.line(150, 46, 200, 46);
      doc.setFontSize(9);
      doc.setFontType('normal');

      //doc.writeText(5, 55 ,fecha,{align:'right',width:185});

      //doc.line(120, 74, 120, 125);
      doc.setFontSize(11);
      doc.setFontType("bold");
      doc.writeText(15, 55, '1. SOLICITUD DEL BIEN O SERVICIO', { align: 'left', width: 205 });

      doc.setFontSize(9);
      doc.setFontType("normal");
      doc.writeText(20, 67, 'Servicio, mantenimiento y/o reparación', { align: 'left', width: 205 });
      doc.writeText(120, 67, ' Materiales, insumos o equipo', { align: 'left', width: 205 });

      doc.setFontSize(30);
      if (data.pedido_tipo == 1) {
        doc.writeText(80, 69, 'X', { align: 'left', width: 205 });
      } else {
        doc.writeText(167, 69, 'X', { align: 'left', width: 205 });
      }
      doc.roundedRect(78, 60, 11, 11, 1, 1);
      doc.roundedRect(165, 60, 11, 11, 1, 1);

      doc.setFontSize(11);
      doc.setFontType("bold");
      doc.writeText(15, 80, '2. JUSTIFICACIÓN GASTOS POR:', { align: 'left', width: 205 });
      var punto = 85;
      var punto_rec = 80; doc.setFontSize(9);
      doc.setFontType('normal');
      letras_x_pagina += data.titulo.length;
      doc.writeText(25, 90, data.titulo, { align: 'centar', width: 205 });

      var punto_fin = 0;
      doc.setFontSize(9);
      doc.roundedRect(20, punto - 2, 175, 10, 1, 1);
      doc.setFontType('normal');
      var total = data.insumos.length;
      punto += 15;
      doc.writeText(20, punto, 'CANTIDAD:', { align: 'left', width: 205 });
      var x = 0;
      punto += 5;
      punto_inicio = punto - 2.5;
      for (nombreIndice in data.insumos) {
        x++;
        letras_x_pagina += data.insumos[nombreIndice].Ppr_Nom.length;
        letras_x_pagina += data.insumos[nombreIndice].Pedd_can.length;
        doc.writeText(30, punto + 2, data.insumos[nombreIndice].Ppr_Nom, { align: 'left', width: 185 });
        //doc.writeText(25, punto ,data.insumos[0].Ppr_Des,{align:'left',width:185});
        doc.writeText(175, punto + 2, data.insumos[nombreIndice].Pedd_can, { align: 'center', width: 20 });
        if (x < total) {
          doc.line(20, punto + 4.5, 195, punto + 4.5);
        }
        punto += 7;
        punto_fin += 7;

      }

      doc.roundedRect(20, punto_inicio, 175, punto_fin, 1, 1);

      punto += 3;
      doc.writeText(20, punto, 'ESPECIFICACIONES:', { align: 'left', width: 205 });
      punto += 3;

      letras_x_pagina += data.especificaciones.length;

      var lineas_e = parseInt(data.lineas_e);
      var r_lineas1 = doc.splitTextToSize(data.especificaciones.toUpperCase(), 165);
      doc.text(25, punto + 4, r_lineas1);

      //if(data.especificaciones.length<= 150){
      doc.roundedRect(20, punto, 175, lineas_e - 5, 1, 1);
      punto += lineas_e;
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


      doc.writeText(20, punto, 'NECESIDAD:', { align: 'left', width: 205 });
      punto += 3;
      letras_x_pagina += data.necesidad.length;
      var r_lineas1 = doc.splitTextToSize(data.necesidad, 165);
      doc.text(25, punto + 4, r_lineas1);

      if (data.necesidad.length <= 150) {
        doc.roundedRect(20, punto, 175, 10, 1, 1);
        punto += 15;
      } else if (data.necesidad.length > 150 && data.necesidad.length <= 250) {
        doc.roundedRect(20, punto, 175, 15, 1, 1);
        punto += 25;
      } else if (data.necesidad.length > 250 && data.necesidad.length <= 350) {
        doc.roundedRect(20, punto, 175, 20, 1, 1);
        punto += 30;
      }
      verificar_saltos_pagina(punto);
      doc.writeText(20, punto, 'TEMPORALIDAD DE USO DE LA ADQUISICIÓN:', { align: 'left', width: 205 });
      punto += 3;
      doc.roundedRect(20, punto, 175, 10, 1, 1);
      letras_x_pagina += data.temporalidad.length;
      var r_lineas1 = doc.splitTextToSize(data.temporalidad, 165);
      doc.text(25, punto + 4, r_lineas1);

      verificar_saltos_pagina(punto);

      punto += 15;
      doc.writeText(20, punto, 'FINALIDAD:', { align: 'left', width: 205 });
      punto += 3;
      doc.roundedRect(20, punto, 175, 10, 1, 1);
      letras_x_pagina += data.finalidad.length;
      var r_lineas1 = doc.splitTextToSize(data.finalidad, 165);
      doc.text(25, punto + 4, r_lineas1);

      punto += 15;
      doc.writeText(20, punto, 'RESULTADO DE LA COMPRA:', { align: 'left', width: 205 });
      punto += 3;
      doc.roundedRect(20, punto, 175, 10, 1, 1);
      letras_x_pagina += data.resultado.length;
      var r_lineas1 = doc.splitTextToSize(data.resultado, 165);
      doc.text(25, punto + 4, r_lineas1);
      punto += 17;

      verificar_saltos_pagina(punto);

      doc.setFontSize(11);
      doc.setFontType("bold");
      doc.writeText(15, punto, '3. DIAGNÓSTICO TÉCNICO:', { align: 'left', width: 205 });
      doc.setFontSize(9);
      punto += 7;
      doc.setFontType('normal');
      doc.writeText(68, punto, 'SI', { align: 'left', width: 205 });
      doc.writeText(110, punto, ' NO', { align: 'left', width: 205 });
      doc.setFontSize(30);

      if (data.pedido_diagnostico == 1) {
        doc.writeText(80, punto + 3, 'X', { align: 'left', width: 205 });
      } else {
        doc.writeText(122, punto + 3, 'X', { align: 'left', width: 205 });
      }
      doc.roundedRect(78, punto - 6, 11, 11, 1, 1);
      doc.roundedRect(120, punto - 6, 11, 11, 1, 1);
      punto += 12;
      doc.setFontSize(9);
      verificar_saltos_pagina(punto);
      if (data.pedido_diagnostico == 1) {
        doc.setFontSize(11);
        doc.writeText(20, punto, 'DIAGNÓTICO No.:', { align: 'left', width: 205 });
        doc.setFontType("bold");
        //letras_x_pagina+=data.necesidad.length;
        doc.setFontSize(9);
        var r_lineas1 = doc.splitTextToSize(data.dictamenes, 150);
        doc.text(55, punto, r_lineas1);
        punto += 25;
      }





      if (punto > 240) {
        //doc.writeText(15,punto+10,''+punto+'',{align:'left',width:205});
        doc.writeText(68, punto + 10, 'Firma Solicitante', { align: 'left', width: 205 });
        doc.writeText(110, punto + 10, 'Vo. Bo. Del Director o Subdirector', { align: 'left', width: 205 });
      } else {
        //doc.writeText(15,punto+15,''+punto+'',{align:'left',width:205});
        doc.writeText(68, punto + 15, 'Firma Solicitante', { align: 'left', width: 205 });
        doc.writeText(110, punto + 15, 'Vo. Bo. Del Director o Subdirector', { align: 'left', width: 205 });
      }








      //}


      function verificar_saltos_pagina(punto_) {
        console.log(punto);
        if (punto_ > 260) {


          letras_x_pagina = 0;
          punto = 35;
          doc.addPage();
          x += 1;
          cabecera();
          doc.setFontType('normal');
        }
      }
      function multilineaConNegrita(x, y, endx, text, fontSize, aling) {
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
              //doc.text(startX, startY,textItems);
              doc.writeText(startX, startY, textItems, { align: aling, width: 205 });

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
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function impresion_pedido(ped_tra, tipo) {
  var punto = 0;
  imprimirPedidoF(ped_tra, punto, tipo);
  /*Swal.fire({
    title: '<strong>¿Quiere imprimir el Pedio ?</strong>',
    text: "El valor que ingrese sirve para subir o bajar el margen de la impresión.",
    type: 'question',
    input: 'number',
    inputPlaceholder: 'Agregar',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Imprimir!',
    inputValidator: function (inputValue) {
      return new Promise(function (resolve, reject) {
        if (inputValue && inputValue.length > 0) {
          resolve();
          punto = inputValue;

        } else {
          Swal.fire({
            type: 'info',
            title: 'Se imprimirá normal',
            showConfirmButton: false,
            timer: 1100
          });
          imprimirPedidoF(ped_tra, 0);
        }
      });
    }
  }).then((result) => {
    if (result.value) {

      imprimirPedidoF(ped_tra, punto);



    }
  })*/



}

function imprimirPedidoF(ped_tra, p, tipo_impresion) {
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/hoja_pedido.php",
    data: {
      ped_tra,
      tipo_impresion
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {
      if(tipo_impresion == 1){
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
      }

    },
    success: function (data) {
      var punto = parseInt(p)
      var documento;
      var registros = data.length;
      suma = 0;

      var paginas = 1;


      var doc = new jsPDF("p", "mm", [792,612], true);
      var letras_x_pagina = 0;


      //doc.writeText(5,27,data.direccion,{align:'center',width:205});
      if(data.msg == 'ERROR'){
        Swal.fire({
          type: 'error',
          title: 'El PYR no ha sido aprobado en la Unidad de Planificación',
          showConfirmButton: true,
          //timer: 1100
        });
      }else{
        //inicio de impresión
        if(data.tipo == 3){
          doc.addImage(logo_saas_gray, 'png', 15, 13, 35, 25);
          doc.addImage(logo_cgc, 'png', 180, 13, 17, 17);
          doc.setFontType("bold");
          doc.setFontSize(9);
          doc.writeText(5, 20, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
          doc.writeText(5, 25, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 205 });

          doc.setFontSize(14);
          doc.writeText(5, 35, 'PEDIDO Y REMESA', { align: 'center', width: 205 });

          doc.setTextColor(242, 38, 19);
          doc.writeText(5, 35, '000000', { align: 'right', width: 200 });

          doc.setTextColor(0, 0, 0);
          doc.line(70, punto +53, 205,punto +53);
          doc.line(34, punto +61, 205,punto +61);
          doc.line(61, punto +69, 205,punto +69);
          doc.line(35, punto +77, 205,punto +77);

          doc.line(40, punto +86, 205,punto +86);

          doc.setFontSize(8);
          doc.setFontType("normal");
          doc.text(15, punto + 52, 'NOMBRE DE LA UNIDAD SOLICITANTE');
          doc.text(15, punto + 60, 'PROGRAMA');
          doc.text(15, punto + 68, 'DEPARTAMENTO QUE SOLICITA');
          doc.text(15, punto + 76, 'DIRECCION');
          doc.text(15, punto + 85, 'SE SOLICITA A');
          doc.line(70, punto +53, 205,punto +53);
          //encabezado tabla
          doc.setFontSize(9);
          doc.setFontType("bold");
          doc.writeText(5, punto + 94, 'ARTÍCULOS Y/O SERVICIO QUE SE SOLICITA', { align: 'center', width: 205 });

          //vertical lines
          doc.setLineWidth(0.3);
          doc.line(30, 96, 30,195);
          doc.line(122, 96, 122,195);
          doc.line(145, 96, 145,195);
          doc.line(168, 96, 168,195);
          //horizontal lines
          doc.line(10, punto +96, 205,punto +96);
          doc.line(10, punto +100, 205,punto +100);
          doc.line(10, punto +111, 205,punto +111);

          doc.setFontSize(7);
          doc.writeText(10, 105, 'CORRELATIVO', { align: 'center', width: 20 });
          doc.writeText(70, 105, 'DESCRIPCION DEL ARTÍCULO Y/O SERVICIO', { align: 'center', width: 20 });
          doc.writeText(125, 105, 'CANTIDAD', { align: 'center', width: 20 });
          doc.writeText(146, 105, 'UNIDAD', { align: 'center', width: 20 });
          doc.writeText(175, 105, 'OBSERVACIONES', { align: 'center', width: 20 });
          doc.roundedRect(10, 90, 195, 105, 1, 1);

          doc.setFontSize(5.6);
          doc.setFontType("normal");
          punto_f = 197;
          doc.writeText(4.3, punto_f, 'Base Legal: Acuerdo Gubernativo 10-56-92 de fecha 22 de diciembre de 1992, Reglamento de la Ley de Contrataciones del Estado Artículo 15 y 16; y Artículo 39 número 18 y 20 Ley de la Contraloría General de Cuentas', { align: 'center', width: 205 });

          doc.line(20, punto_f + 20, 90, punto_f + 20);
          doc.line(120, punto_f + 20, 190, punto_f + 20);
          doc.setFontSize(9);
          doc.writeText(24, punto_f+24, 'Nombre y Firma del Solicitante', { align: 'center', width: 60 });
          doc.writeText(125, punto_f+24, 'Nombre y Firma del Jefe Inmediato del Solicitante', { align: 'center', width: 60 });

          doc.line(20, punto_f + 40, 90, punto_f + 40);
          doc.line(120, punto_f + 40, 190, punto_f + 40);
          doc.writeText(24, punto_f+44, 'Encargado de Bodega', { align: 'center', width: 60 });
          doc.writeText(125, punto_f+44, 'Director', { align: 'center', width: 60 });

          doc.line(70, punto_f + 60, 140, punto_f + 60);
        }else
        if(data.tipo == 2){
          //cuadro global
          doc.line(115, 7, 115,67);
          doc.line(145, 7, 145,30);
          //doc.setLineWidth(1);
          doc.roundedRect(7, 7, 202, 253, 1, 1);
          doc.setLineWidth(0.3);
          doc.addImage(logo_gris, 'png', 9, 10, 36, 26);
          doc.addImage(logo_cgc, 'png', 190, 10, 17, 17);
          //doc.line(7, 36, 209,36);
          doc.setFontType("bold");
          doc.setFontSize(8);
          doc.writeText(35, 12, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS', { align: 'center', width: 90 });
          doc.writeText(35, 16, 'Y DE SEGURIDAD', { align: 'center', width: 90 });
          doc.writeText(35,  20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 90 });
          doc.setFontType("normal");
          doc.writeText(35, 24, 'GUATEMALA, C.A.', { align: 'center', width: 90 });

          doc.setFontType("bold");
          doc.writeText(35, 30, 'NIT: 2371485-9', { align: 'center', width: 90 });
          doc.setFontSize(12);
          doc.writeText(35, 35, 'PEDIDO Y REMESA', { align: 'center', width: 90 });
          /*doc.writeText(5, 20, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
          doc.writeText(5, 25, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 205 });*/

          doc.setFontSize(14);
          //doc.writeText(5, 35, 'PEDIDO Y REMESA', { align: 'center', width: 205 });

          //doc.setTextColor(242, 38, 19);
          doc.setFontSize(8);
          doc.writeText(115, 17, 'CORRELATIVO CGC No.', { align: 'center', width: 103 });
          doc.writeText(115, 17, 'FECHA', { align: 'center', width: 30 });
          doc.setFontSize(11);
          doc.writeText(115, 22, data.pedido_num, { align: 'center', width: 103 });
          doc.writeText(115, 22, data.fecha, { align: 'center', width: 30 });
          /*doc.writeText(115, 22, '00000', { align: 'center', width: 103 });
          doc.writeText(115, 22, '00-00-0000', { align: 'center', width: 30 });*/
          doc.line(115, 30, 209,30);
          doc.setFontType("bold");
          doc.setFontSize(8);
          doc.setTextColor(0, 0, 0);
          doc.writeText(115, 34, 'JUSTIFICACIÓN (necesidad, finalidad y temporalidad)', { align: 'center', width: 95 });
          doc.line(115, 36, 209,36);
          doc.setFontType("normal");
          doc.setFontSize(7);
          var r_lineas1 = doc.splitTextToSize(data.observaciones.toUpperCase(), 90);
          //doc.text(116.5, 40, r_lineas1);
          doc.text(r_lineas1, 116.5, 40, {maxWidth: 90, align: "justify"});

          doc.setTextColor(0, 0, 0);
          /*doc.line(70, punto +53, 205,punto +53);
          doc.line(34, punto +61, 205,punto +61);
          doc.line(61, punto +69, 205,punto +69);
          doc.line(35, punto +77, 205,punto +77);

          doc.line(40, punto +86, 205,punto +86);*/

          doc.setFontSize(6.5);
          doc.setFontType("normal");
          doc.text(10, punto + 44, 'UNIDAD SOLICITANTE:');
          doc.text(10, punto + 48, 'PROGRAMA:');
          doc.text(10, punto + 52, 'DIRECCION:');
          doc.text(10, punto + 56, 'DEPARTAMENTO:');
          doc.text(10, punto + 60, 'SE SOLICITA A:');

          doc.setFontSize(7);
          doc.setFontType("normal");
          //doc.writeText(5, punto + 42, data.fecha, { align: 'right', width: 185 });

          doc.writeText(36, punto + 44, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 195 });
          doc.writeText(36, punto + 48, '13 SEGURIDAD PRESIDENCIAL Y VICEPRESIDENCIAL', { align: 'left', width: 195 });
          doc.writeText(36, punto + 52, data.direccion, { align: 'left', width: 195 });
          doc.writeText(36, punto + 56, data.departamento, { align: 'left', width: 195 });
          doc.writeText(36, punto + 60, 'DEPARTAMENTO DE COMPRAS', { align: 'left', width: 195 });

          doc.text('SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', 36, punto + 44);
          doc.text('13 SEGURIDAD PRESIDENCIAL Y VICEPRESIDENCIAL', 36, punto + 48);
          doc.text(data.direccion, 36, punto + 52);
          doc.text(data.departamento, 36, punto + 56);
          doc.text('DEPARTAMENTO DE COMPRAS', 36, punto + 60);

          //encabezado tabla
          doc.setFontSize(9);
          doc.setFontType("bold");
          doc.writeText(5, punto + 71, 'ARTÍCULOS Y/O SERVICIO QUE SE SOLICITA', { align: 'center', width: 205 });

          //vertical lines
          doc.line(16, 73, 16,195);
          doc.line(32, 73, 32,195);
          doc.line(48, 73, 48,195);
          doc.line(64, 73, 64,195);
          doc.line(80, 73, 80,195);
          doc.line(96, 73, 96,195);
          //doc.line(112, 73, 112,195);
          //horizontal lines
          doc.setLineWidth(1);
          doc.line(7, punto +67, 209,punto +67);
          doc.setLineWidth(0.3);
          doc.line(7, punto +73, 209,punto +73);
          doc.line(7, punto +77, 209,punto +77);
          doc.line(7, punto +87, 209,punto +87);

          doc.setFontSize(5.4);
          doc.writeText(7, 82, 'No.', { align: 'center', width: 10 });
          doc.writeText(16, 82, 'RENGLON', { align: 'center', width: 16 });
          //doc.writeText(26, 108, 'INSUMO', { align: 'center', width: 20 });
          doc.writeText(32, 81, 'CÓDIGO DE', { align: 'center', width: 16 });
          doc.writeText(32, 84, 'INSUMO', { align: 'center', width: 16 });
          doc.writeText(48, 81, 'CÓDIGO DE', { align: 'center', width: 16 });
          doc.setFontSize(5.4);
          doc.writeText(48, 84, 'PRESENTACIÓN', { align: 'center', width: 16 });
          //doc.setFontSize(7);
          doc.writeText(64, 82, 'CANTIDAD', { align: 'center', width: 16 });
          doc.writeText(80, 81, 'UNIDAD DE', { align: 'center', width: 16 });
          doc.writeText(80, 84, 'MEDIDA', { align: 'center', width: 16 });

          doc.writeText(143, 82, 'DESCRIPCION DEL ARTÍCULO Y/O SERVICIO', { align: 'center', width: 20 });


          //rectangulo de insumos
          doc.setLineWidth(1);
          doc.line(7, punto +195, 209,punto +195);
          doc.setLineWidth(0.3);
          //doc.roundedRect(7, 67, 196, 125, 0, 0);

          doc.setFontSize(5);
          doc.setFontType("normal");
          punto_f = 193;
          //doc.writeText(4.3, punto_f, 'Base Legal: Acuerdo Gubernativo 10-56-92 de fecha 22 de diciembre de 1992, Reglamento de la Ley de Contrataciones del Estado Artículo 15 y 16; y Artículo 39 número 18 y 20 Ley de la Contraloría General de Cuentas', { align: 'center', width: 205 });

          doc.line(20, punto_f + 20, 90, punto_f + 20);
          doc.line(120, punto_f + 20, 190, punto_f + 20);
          doc.setFontSize(9);
          doc.writeText(10, punto_f+6, 'Nombre y Firma de:', { align: 'left', width: 60 });
          doc.writeText(24, punto_f+24, 'Solicitante', { align: 'center', width: 60 });
          doc.writeText(125, punto_f+24, 'Jefe Inmediato del Solicitante', { align: 'center', width: 60 });

          doc.line(20, punto_f + 40, 90, punto_f + 40);
          doc.line(120, punto_f + 40, 190, punto_f + 40);
          doc.writeText(24, punto_f+44, 'Encargado de bodega', { align: 'center', width: 60 });
          doc.writeText(125, punto_f+44, 'Director o Subdirector', { align: 'center', width: 60 });

          doc.line(70, punto_f + 60, 140, punto_f + 60);
          doc.writeText(70, punto_f+64, 'Aprobado Subsecretaría Administrativa', { align: 'center', width: 70 });

          //pie de paginas
          doc.setFontType("normal");
          doc.setFontSize(5.5);
          var r_d1 = 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. F.O.-JO-237-2021 GESTIÓN NÚMERO: 618857 DE FECHA 13-12-2021 '+
  'DE CUENTA S1-22 · 15000 Formulario de Pedido y Remesa en Forma Electrónica DEL No. 1 AL 15000 SIN SERIE No. CORRELATIVO Y FECHA DE '+
  'AUTORIZACION DE IMPRESION 14-2022 DEL 21-01-2022 · ENVIO FISCAL 4-ASCC 18994 DEL 21-01-2022 LIBRO 4-ASCC FOLIO 131';
          var r_lineas1 = doc.splitTextToSize(r_d1, 190);
          //doc.text(10, punto + 263, r_lineas1);
          doc.text(r_lineas1, 10, punto + 263, {maxWidth: 190, align: "justify"});

          doc.setFontType("bold");
          doc.setFontSize(7);
          doc.writeText(12, punto + 272, 'Original: Documento de pago', { align: 'center', width: 95 });
          doc.writeText(108, punto + 272, 'Duplicado: Archivo', { align: 'center', width: 95 });
        }
        else{
          doc.setFontType("normal");
          doc.setFontSize(9);
          doc.setFontType("normal");
          doc.writeText(5, punto + 42, data.fecha, { align: 'right', width: 185 });

          doc.writeText(75, punto + 52, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 195 });
          doc.writeText(40, punto + 60, '13 SEGURIDAD PRESIDENCIAL Y VICEPRESIDENCIAL', { align: 'left', width: 195 });
          doc.writeText(40, punto + 76, data.direccion, { align: 'left', width: 195 });
          doc.writeText(65, punto + 68, data.departamento, { align: 'left', width: 195 });
          doc.writeText(45, punto + 85, 'DEPARTAMENTO DE COMPRAS', { align: 'left', width: 195 });

        }


        /*doc.writeText(5,40,data.correlativo,{align:'right',width:195});
        doc.writeText(5,45, data.pedido_num,{align:'right',width:195});*/


        doc.setFontSize(9);
        doc.setFontType('normal');
        punto += 87;
        punto_i = 90;
        punto_n = 0;


        var total = data.insumos.length;
        //punto+=15;

        var x = 0;
        punto += 5;
        punto_inicio = punto - 2.5;
        console.log(data);

        console.log(punto);
        if(data.tipo == 1){
          punto+=25;
          for (nombreIndice in data.insumos) {
            x++;
            letras_x_pagina += data.insumos[nombreIndice].Ppr_Nom.length;
            letras_x_pagina += data.insumos[nombreIndice].Pedd_can.length;

            //doc.writeText(25, punto ,data.insumos[0].Ppr_Des,{align:'left',width:185});
            doc.setFontSize(7);
            doc.writeText(11, punto, data.insumos[nombreIndice].correlativo, { align: 'center', width: 20 });
            doc.writeText(128, punto, data.insumos[nombreIndice].Pedd_can, { align: 'center', width: 20 });
            doc.writeText(146, punto, data.insumos[nombreIndice].Ppr_Med, { align: 'center', width: 20 });
            doc.writeText(175, punto, data.insumos[nombreIndice].codificacion, { align: 'center', width: 20 });
            var lineas_e = parseInt(data.insumos[nombreIndice].lineas);
            var r_lineas1 = doc.splitTextToSize(data.insumos[nombreIndice].descripcion.toUpperCase(), 98);
            doc.text(32, punto, r_lineas1);
            punto += lineas_e;
            punto_n += lineas_e;
          }

          var r_lineas1 = doc.splitTextToSize(data.observaciones.toUpperCase(), 170);
          doc.text(32, punto + 4, r_lineas1);
          punto_n += 35;
          var r_lineas1 = doc.splitTextToSize(data.observaciones.toUpperCase(), 170);
          doc.text(32, punto + 4, r_lineas1);
          punto_n += 35;
        }else{
          doc.setFontSize(7);
          //doc.writeText(11, punto, data.insumos[nombreIndice].correlativo, { align: 'center', width: 20 });
          doc.writeText(7, 76, '1', { align: 'center', width: 9 });
          doc.writeText(16, 76,'2', { align: 'center', width: 16 });
          doc.writeText(32, 76,'3', { align: 'center', width: 16 });
          doc.writeText(48, 76,'4', { align: 'center', width: 16 });
          doc.writeText(64, 76,'5', { align: 'center', width: 16 });
          doc.writeText(80, 76, '6', { align: 'center', width: 16 });
            doc.writeText(96, 76, '7', { align: 'center', width: 110 });
          for (nombreIndice in data.insumos) {
            x++;
            letras_x_pagina += data.insumos[nombreIndice].Ppr_Nom.length;
            letras_x_pagina += data.insumos[nombreIndice].Pedd_can.length;

            //doc.writeText(25, punto ,data.insumos[0].Ppr_Des,{align:'left',width:185});
            doc.setFontSize(6);
            doc.writeText(7, punto, data.insumos[nombreIndice].correlativo, { align: 'center', width: 9 });
            doc.writeText(16, punto, data.insumos[nombreIndice].Ppr_Ren, { align: 'center', width: 16 });
            doc.writeText(32, punto, data.insumos[nombreIndice].Ppr_cod, { align: 'center', width: 16 });
            doc.writeText(48, punto, data.insumos[nombreIndice].Ppr_codPre, { align: 'center', width: 16 });
            doc.writeText(64, punto, data.insumos[nombreIndice].Pedd_can, { align: 'center', width: 16 });
            doc.writeText(80, punto, data.insumos[nombreIndice].Ppr_Med, { align: 'center', width: 16 });
            //doc.writeText(175, punto, data.insumos[nombreIndice].codificacion, { align: 'center', width: 20 });
            doc.setFontSize(7);
            var lineas_e = parseInt(data.insumos[nombreIndice].lineas);
            var r_lineas1 = doc.splitTextToSize(data.insumos[nombreIndice].descripcion.toUpperCase(), 110);
            //var r_lineas1 = doc.splitTextToSize(data.observaciones.toUpperCase(), 90);
            //doc.text(116.5, 40, r_lineas1);
            //doc.text(r_lineas1, 98, punto, {maxWidth: 110, align: "justify"});
            doc.text(98, punto, r_lineas1);
            punto += lineas_e;
            punto_n += lineas_e;
          }

          //doc.writeText(80, punto, ''+data.lines+'', { align: 'center', width: 16 });


          /*8
  31
  46
  56
  72
  150*/
        }

        //punto += 20;




        function verificar_saltos_pagina(punto_) {
          console.log(punto);
          if (punto_ > 260) {


            letras_x_pagina = 0;
            punto = 35;
            doc.addPage();
            x += 1;
            cabecera();
            doc.setFontType('normal');
          }
        }
        function multilineaConNegrita(x, y, endx, text, fontSize, aling) {
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
                //doc.text(startX, startY,textItems);
                doc.writeText(startX, startY, textItems, { align: aling, width: 205 });

                //doc.text(textItems, startX, startY, {maxWidth: ancho, align: "justify"});
                //startX = startX + doc.getStringUnitWidth(textItems) * 3.6;
              });
              boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
              startX = startXCached;
              startY += lineSpacing;
            }
          });
        }

        if(tipo_impresion == 1){
          var x = document.getElementById("pdf_preview_v");
          if (x.style.display === "none") {
            x.style.display = "none";
          } else {
            x.style.display = "none";
          }
          doc.autoPrint()
          $("#pdf_preview_v").attr("src", doc.output('datauristring'));
        }else{
          var x = document.getElementById("pdf_preview_vd");
          /*if (x.style.display === "none") {
            x.style.display = "none";
          } else {
            x.style.display = "none";
          }*/
          //doc.autoPrint()
          $("#pdf_preview_vd").attr("src", doc.output('datauristring'));
        }

        //final de impresión
      }



    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });

}

function imprimirActa(acta_id){
  //inicio
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/hoja_acta.php",
    data: {
      acta_id
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {

      console.log(data);
      var documento;
      var registros = data.length;
      suma = 0;

      var paginas = 1;
      var punto = 100;

      var doc = new jsPDF("p", "mm", [792,612], true);
      var letras_x_pagina = 0;

      let startX = 12;

      doc.addImage(baner, 'png', 10, 5, 140, 30);
      doc.setDrawColor(255, 255, 255);
      doc.setFillColor(255, 255, 255);
      //doc.roundedRect(100, 10, 45, 20, 1, 1,'FD');
      doc.addImage(logo_saas, 'png', 148, 8, 30, 20);

      doc.setFontType('bold');
      doc.writeText(0, 45, 'ACTA DE NEGOCIACIÓN', { align: 'center', width: 215 });

      //const inputValue = "this is **bold and** normal font in just one **line**.";
      doc.setFontSize(11);

      doc.setLineHeightFactor(2);
      doc.setFontType('normal');

      var inputValue = `En la ciudad de Guatemala, siendo las: `+data.i_hora+` horas con `+data.i_minuto+` minutos del día `+data.i_fecha+`, `+
        `reunidos en la sede de la Secretaría de Asuntos Administrativos y de Seguridad de la Presidencia de la República, la que en adelante podrá denominarse SAAS, `+
        `ubicada en la 6a. avenida "A" 4-18 Callejón Manchén, zona 1 del municipio de Guatemala, departamento de Guatemala, nosotros: `+data.director+` `+
        `quien actúa en su calidad de Subdirector Administrativo y Financiero de la SAAS, `+data.jefe+` quien actúa en su calidad de Jefe de compras y `+data.tecnico+` `+
        `quien actúa en su calidad de Técnico de Compras, procedemos a dejar constancia de: PRIMERO: la adquisición se efectuará por medio de la modalidad de baja cuantía por concepto de ${data.acta_justificacion}`+
        ` por medio del pedido y remesa número ${data.pyr} de fecha ${data.f_fecha}. SEGUNDO: La presente Acta se fundamenta en el artículo 50 del Decreto 57-92 del Congreso de la Repúlica de Guatemala Ley de Contrataciones del Estado; `+
        `y artículo 42 Bis del Acuerdo Gubernativo Número 122-2016, Reglamento de la Ley de Contrataciones del Estado, Omisión del contrato escrito. TERCERO: Se adjudica `+
        `${data.id_tipo_compra} a: ${data.proveedor} por un monto total: ${data.monto}, por ${data.tipo_adjudicacion}, motivos por el cual favorece a los intereses del Estado. `+
        `CUARTO: El pago se realizará por medio de ${data.tipo_pago}. Sin más que hacer constar se da por finalizada la presente acta de negociación, en el mismo lugar y fecha de su inicio, siendo las `+
        `${data.f_hora} horas con ${data.f_minuto} minutos la cual está contenida en una hoja de papel bond. Para los efectos legales que correspondan, la ratifican, aceptan y firman.`
        ;
      doc.text(inputValue, 14, 55, {maxWidth: 186, align: "justify"});

      var x = 0;
      punto_f = 193;
      /*doc.setDrawColor(204, 209, 217);
      doc.roundedRect(x + 14, 180, 187, 85, 1, 1);
      doc.line(x + 56.35, 180, x + 56.35, 265);
      doc.line(14, 188, 201, 188);
      doc.setFontSize(11);
      doc.setFontType("bold");
      doc.writeText(x + 15, 185, 'AREA', {align: 'center', width: 41.35});
      doc.writeText(x + 90, 185, 'FIRMA Y SELLO', {align: 'center', width: 68.35});
      punto_f = 193;


      doc.line(14, punto_f + 20, 201, punto_f + 20);
      doc.line(14, punto_f + 45, 201, punto_f + 45);*/
      doc.setFontSize(11);
      doc.setLineHeightFactor(1);
      doc.text('Técnico de Compras', 34, punto_f+15, {maxWidth: 25, align: "center"});
      doc.text('Jefe de Compras', 100, punto_f+35, {maxWidth: 25, align: "center"});
      doc.text('Subdirector Administrativo y Financiero', 170, punto_f+55, {maxWidth: 40, align: "center"});

      function verificar_saltos_pagina(punto_) {
        console.log(punto);
        if (punto_ > 260) {


          letras_x_pagina = 0;
          punto = 35;
          doc.addPage();
          x += 1;
          cabecera();
          doc.setFontType('normal');
        }
      }
      function multilineaConNegrita(x, y, endx, text, fontSize, aling) {
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
        let textMap = text; //doc.splitTextToSize(text, endX);
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
              doc.text(textItems,startX, startY, {maxWidth: 190, align: "center" });

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
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
  //fin
}
