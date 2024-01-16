function reporte_solvencia_() {
    var total = 0;
    var doc = new jsPDF('p', 'mm');
    p3 = 60;

    doc.setFontSize(8);
    doc.setTextColor(5, 83, 142);
    doc.writeText(10, p3 + 5, 'Reporte Generado SAAS - Control de Insumos', {align: 'center', width: 190});

    //doc.addImage(footer, 'png', 5, p3+6, 205, 15);
    doc.writeText(10, p3 + 8, '6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"', {align: 'center', width: 190});
    doc.writeText(10, p3 + 11, 'PBX: 2327-600 FAX: 2327-6090', {align: 'center', width: 190});
    doc.setTextColor(255, 255, 255);

    doc.line(5, p3 + 18, 210, p3 + 18);
    doc.save('test.pdf');
}

function reporte_solvencia(id_doc_solvencia) {
    $.ajax({
        type: "POST",
        url: "insumos/php/back/hojas/reporte_solvencia.php",
        dataType: 'json',
        data: {
            id_doc_solvencia: id_doc_solvencia
        },
        beforeSend: function () {

        },
        success: function (data) {
            //alert(data);
            var movimiento, transaccion, fecha, hora, empleado, gafete, direccion, direccion_abreviatura, encargado, bodega,
                bodega_abreviatura, correlativo, anio, estado, copia, copia_tipo;
            var bodegaId, observaciones, area, solvenciaTexto;
            var doc;
            var punto_ = 65;
            var punto = punto_ + 4;
            punto += 0;
            i = 0;
            movimiento = data[0].solvencia_tipo;
            transaccion = data[0].transaccion;
            fecha = data[0].fecha;
            hora = data[0].hora;
            empleado = data[0].empleado;
            gafete = data[0].gafete;
            direccion = data[0].direccion;
            direccion_abreviatura = data[0].direccion_abreviatura;
            encargado = data[0].encargado;
            bodegaId = data[0].bodega_id;
            bodega = data[0].bodega;
            bodega_abreviatura = data[0].bodega_abreviatura;
            correlativo = data[0].correlativo;
            anio = data[0].anio;
            estado = data[0].solvente;
            area = data[0].area;
            observaciones = data[0].observaciones;
            copia = 0;
            copia_tipo = 'Original';

            if (bodegaId == 3552 || bodegaId == 5907) {
                doc = new jsPDF('l', 'mm', 'letter');
                var i, x;
                x = 0;

                for (i = 0; i < 2; i++) {
                    doc.setDrawColor();
                    doc.setTextColor();
                    doc.setFontType("normal");
                    doc.setFontSize(9);
                    doc.setLineWidth();

                    //encabezado logo
                    doc.addImage(baner, 'png', x, 8, 139.7, 28);
                    doc.writeText(x - 10, 10, copia_tipo, {align: 'right', width: 139.7});
                    doc.setFontType("bold");
                    doc.setDrawColor(215, 215, 215);
                    doc.setFontSize(8);
                    doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255, 255);
                    doc.rect(x+40, 25, 35, 2, "FD") 

                    //encabezado correlativo y fecha
                    doc.setFontSize(9);
                    doc.writeText(x - 15, 37, 'SAAS-' + direccion_abreviatura + '-SOLV-' + bodega_abreviatura + '-' + correlativo + '/' + anio, {
                        align: 'right',
                        width: 139.7
                    });
                    doc.setFontType('normal');
                    doc.writeText(x - 15, 41, fecha, {align: 'right', width: 139.7});

                    //area solvencia
                    doc.setFontType("bold");
                    doc.writeText(x + 15, 50, 'POR TANTO:');

                    //tabla
                    doc.setFontSize(9);
                    doc.setFontType('normal');
                    doc.setDrawColor(50, 50, 50);
                    doc.roundedRect(x + 15, 90, 109.7, 70, 1, 1);
                    if (movimiento == 'VACACIONES') {
                        doc.line(x + 15, 100, x + 124.7, 100);
                        doc.line(x + 15, 130, x + 124.7, 130);
                    } else {
                        doc.line(x + 15, 100, x + 124.7, 100);
                        doc.line(x + 15, 120, x + 124.7, 120);
                        doc.line(x + 15, 140, x + 124.7, 140);
                    }
                    doc.line(x + 56.35, 90, x + 56.35, 160);
                    doc.setFontSize(11);
                    doc.setFontType("bold");
                    doc.writeText(x + 15, 96, 'AREA', {align: 'center', width: 41.35});
                    doc.writeText(x + 56.35, 96, 'FIRMA Y SELLO', {align: 'center', width: 68.35});
                    doc.setFontSize(9);
                    if (movimiento == 'VACACIONES') {
                        doc.writeText(x + 15, 116, `Encargado de ${bodega}`, {align: 'center', width: 41.35});
                        doc.writeText(x + 15, 146, 'Jefe Departamento', {align: 'center', width: 41.35});
                    } else {
                        doc.writeText(x + 15, 111, `Encargado de ${bodega}`, {align: 'center', width: 41.35});
                        doc.writeText(x + 15, 131, 'Jefe Departamento', {align: 'center', width: 41.35});
                        doc.writeText(x + 15, 147, 'Director o Sub Director', {align: 'center', width: 41.35});
                        doc.writeText(x + 15, 151, 'de Comunicaciones e', {align: 'center', width: 41.35});
                        doc.writeText(x + 15, 155, 'Informática', {align: 'center', width: 41.35});
                    }


                    //area firma
                    if (copia_tipo == 'Copia') {
                        doc.setFontSize(11);
                        doc.writeText(x + 15, 185, 'Firma quien recibe:', {align: 'left', width: 50});
                        doc.line(x + 56.35, 185, x + 124.7, 185);
                    }

                    //Pie de Pagina
                    doc.setFontType('normal');
                    doc.setTextColor(5, 83, 142);
                    doc.setFontSize(8);
                    doc.writeText(x + 0, 197, 'Reporte Generado Herramientas Administrativas - Módulo control de Insumos', {
                        align: 'center',
                        width: 139.7
                    });
                    doc.setFontType('bold');
                    doc.setFontSize(8);
                    doc.writeText(x + 0, 202, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', {
                        align: 'center',
                        width: 139.7
                    });
                    doc.writeText(x + 0, 206, 'PBX: 2327 - 6000 FAX: 2327 - 6090', {align: 'center', width: 139.7});
                    doc.writeText(x + 0, 210, 'https://www.saas.gob.gt', {align: 'center', width: 139.7});

                    //area solvencia
                    solvenciaTexto = `Se hace constar que **${empleado} ${gafete}**. Se encuentra **${estado}** en la sección de **${area}**. Para los usos administrativos de la SAAS se extiende la presente.`;
                    multilineaConNegrita(x + 15, 60, 110, solvenciaTexto);
                    solvenciaTexto = `Solvencia para trámite de:  **${movimiento}**`;
                    multilineaConNegrita(x + 15, 85, 115, solvenciaTexto);

                    //area observaciones
                    if (observaciones != '') {
                        solvenciaTexto = `**Observaciones:**  ${observaciones}`;
                        multilineaConNegrita(x + 15, 170, 115, solvenciaTexto);
                    }

                    //configuracion copia
                    copia_tipo = 'Copia';
                    x = 139.7;
                }
                //linea division media pagina
                doc.setFontSize(9);
                doc.setFontType('normal');
                doc.setDrawColor(50, 50, 50);
                doc.setLineWidth();
                doc.setDrawColor(50, 50, 50);
                dottedLine(doc,139.7,0,139.7,215.9,3);
            }else if (bodegaId == 5066) {
                doc= new jsPDF('l', 'mm','letter');
                var i,x;
                x = 0;

                for (i = 0; i < 2; i++) {
                    doc.setDrawColor();
                    doc.setTextColor();
                    doc.setFontType("normal");
                    doc.setFontSize(9);
                    doc.setLineWidth();

                    //encabezado logo
                    doc.addImage(baner, 'png', x, 8, 139.7, 28);
                    doc.writeText(x - 10, 10, copia_tipo, {align: 'right', width: 139.7});
                    doc.setFontType("bold");
                    doc.setDrawColor(215, 215, 215);
                    doc.setFontSize(8);

                                        doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255, 255);
                    doc.rect(x+40, 25, 35, 2, "FD") 

                    //encabezado correlativo y fecha
                    doc.setFontSize(9);
                    doc.writeText(x - 15, 37, 'SAAS-' + direccion_abreviatura + '-SOLV-' + bodega_abreviatura + '-' + correlativo + '/' + anio, {
                        align: 'right',
                        width: 139.7
                    });
                    doc.setFontType('normal');
                    doc.writeText(x - 15, 41, fecha, {align: 'right', width: 139.7});

                    //area solvencia
                    doc.setFontType("bold");
                    //doc.writeText(x + 15, 50, 'POR TANTO:');
                    doc.writeText(x + 15, 50, `SOLVENCIA DE ${bodega}`, {align: 'center', width: 110});
                    doc.writeText(x + 15, 55, `${direccion}`, {align: 'center', width: 110});

                    doc.setFontType("bold");
                    doc.writeText(x + 15, 70, `NOMBRE: `);
                    doc.writeText(x + 15, 80, `MOTIVO: `);
                    doc.writeText(x + 15, 90, `FECHA: `);
                    doc.writeText(x + 15, 100, `HORA:`);
                    doc.writeText(x + 15, 110, `OBSERVACION: `);

                    doc.setFontType("normal");

                    doc.writeText(x + 33, 70, `${empleado} ${gafete}`);
                    doc.writeText(x + 33, 80, `${movimiento} `);
                    doc.writeText(x + 33, 90, `${fecha} `);
                    doc.writeText(x + 33, 100, `${hora} Hrs.`);
                    doc.writeText(x + 40, 110, `${observaciones} `);


                    //solvenciaTexto =
                    //multilineaConNegrita(x + 15, 120, 115, solvenciaTexto);


                    //area firma
                    doc.setFontSize(11);
                    doc.setFontType("bold");
                    doc.line(x + 40, 170, x + 100, 170);
                    doc.writeText(x + 15, 175, `SELLO Y FIRMA`, {align: 'center', width: 110});
                    doc.writeText(x + 15, 185, 'ARMERO DE TURNO:', {align: 'left', width: 50});
                    doc.setFontType("normal");
                    doc.writeText(x + 60, 185, `${encargado}`);
                    //doc.line(x + 56.35, 185, x + 124.7, 185);


                    //Pie de Pagina
                    doc.setFontType('normal');
                    doc.setTextColor(5, 83, 142);
                    doc.setFontSize(8);
                    doc.writeText(x + 0, 197, 'Reporte Generado Herramientas Administrativas - Módulo control de Insumos', {
                        align: 'center',
                        width: 139.7
                    });
                    doc.setFontType('bold');
                    doc.setFontSize(8);
                    doc.writeText(x + 0, 202, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', {
                        align: 'center',
                        width: 139.7
                    });
                    doc.writeText(x + 0, 206, 'PBX: 2327 - 6000 FAX: 2327 - 6090', {align: 'center', width: 139.7});
                    doc.writeText(x + 0, 210, 'https://www.saas.gob.gt', {align: 'center', width: 139.7});

                    //area solvencia
                    solvenciaTexto = `Se hace constar que **${empleado} ${gafete}**. Se encuentra **${estado}** en la sección de **${area}**. Para los usos administrativos de la SAAS se extiende la presente. ${observaciones}`;
                    multilineaConNegrita(x+15,120,110,solvenciaTexto);
                    solvenciaTexto = `Solvencia para trámite de:  **${movimiento}**`;
                    //multilineaConNegrita(x+15,85,115,solvenciaTexto);


                    //area observaciones
                    if (observaciones != '') {
                        solvenciaTexto = `**Observaciones:**  `;
                        //multilineaConNegrita(x + 15, 170, x + 115, solvenciaTexto);
                    }

                    //configuracion copia
                    copia_tipo = 'Copia';
                    x = 139.7;
                }
                //linea division media pagina
                doc.setFontSize(9);
                doc.setFontType('normal');
                doc.setDrawColor(50, 50, 50);
                doc.setLineWidth();
                doc.setDrawColor(50, 50, 50);
                dottedLine(doc, 139.7, 0, 139.7, 215.9, 3);

            } else {
                doc = new jsPDF('p', 'mm');
                do {
                    doc.setDrawColor();
                    doc.setTextColor();
                    doc.setFontType("normal");
                    doc.setFontSize(9);

                    //encabezado logo
                    doc.writeText(0, 10, copia_tipo, {align: 'right', width: 205});
                    doc.setFontType("bold");
                    doc.addImage(baner, 'png', 40, 10, 135, 30);
                    doc.setDrawColor(215, 215, 215);
                    doc.setFontSize(8);

                    doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255, 255);
                    doc.rect(180.7, 25, 33, 2, "FD") 
                
                    //encabezado correlativo y fecha
                    doc.setFontSize(9);
                    doc.writeText(0, 50, 'SAAS-' + direccion_abreviatura + '-SOLV-' + bodega_abreviatura + '-' + correlativo + '/' + anio, {
                        align: 'right',
                        width: 185
                    });
                    doc.setFontType('normal');
                    doc.writeText(0, 55, fecha, {align: 'right', width: 185});

                    //area solvencia
                    doc.setFontType("normal");
                    doc.writeText(25, 65, 'No tiene insumo cargado en bodega de ');
                    doc.writeText(25, 85, 'Se hace constar que el Señor (a): ');
                    //doc.writeText(25, 90, 'Se encuentra');
                    //doc.writeText(68, 90, ', en la sección de');
                    //doc.writeText(130, 90, ' de la');
                    //doc.writeText(25, 101, 'para trámite de:');
                    doc.writeText(25, 115, 'Para usos administrativos en SAAS se extiende la presente.');
                    solvenciaTexto = `Se encuentra **${estado}**, en la sección de **${area}** de la dirección de **${direccion}** para trámite de:`;
                    //var textoLineas = doc.splitTextToSize(solvenciaTexto,165);
                    //doc.text(25,90,textoLineas);

                    //texto negrita
                    doc.setFontType("bold");
                    doc.writeText(82, 65, bodega);
                    doc.writeText(25, 75, 'POR TANTO:');
                    doc.writeText(75, 85, empleado + ' ' + gafete);
                    //doc.writeText(48, 90, estado);
                    //doc.writeText(95, 90, area);
                    //doc.writeText(0, 96, direccion,{align: 'center', width: 209});
                    doc.setFontSize(12);
                    doc.writeText(0, 106, movimiento, {align: 'center', width: 209});
                    doc.writeText(25, 126, 'AREA', {align: 'center', width: 60});
                    doc.writeText(85, 126, 'FIRMA Y SELLO', {align: 'center', width: 105});

                    //area firma
                    doc.writeText(25, 215, 'Nombre quien Recibe:', {align: 'left', width: 40});
                    doc.writeText(25, 225, 'Fecha:', {align: 'left', width: 40});
                    doc.writeText(25, 240, 'Firma:', {align: 'left', width: 40});
                    doc.setFontSize(9);
                    doc.writeText(65, 215, empleado, {align: 'center', width: 125});
                    doc.writeText(65, 225, fecha, {align: 'center', width: 125});

                    //tabla
                    doc.setDrawColor(50, 50, 50);
                    doc.roundedRect(25, 120, 165, 85, 1, 1);
                    doc.line(25, 130, 190, 130);
                    doc.line(25, 155, 190, 155);
                    doc.line(25, 180, 190, 180);
                    doc.line(90, 120, 90, 205);
                    doc.line(90, 240, 170, 240);

                    doc.setFontType("bold");
                    doc.setFontSize(12);
                    //doc.writeText(25, 145, encargado, {align: 'center', width: 60});
                    doc.setFontSize(10);
                    doc.writeText(25, 145, area, {align: 'center', width: 60});
                    doc.writeText(25, 170, 'Jefe Departamento', {align: 'center', width: 60});
                    doc.writeText(25, 190, 'Director o Sub Director de', {align: 'center', width: 60});
                    doc.writeText(25, 195, 'Comunicaciones e Informática', {align: 'center', width: 60});

                    //Pie de Pagina
                    doc.setFontType('normal');
                    doc.setTextColor(5, 83, 142);
                    doc.setFontSize(8);
                    doc.writeText(0, 253, 'Reporte Generado Herramientas Administrativas - Módulo control de Insumos', {
                        align: 'center',
                        width: 215
                    });
                    doc.setFontType('bold');
                    doc.setFontSize(10);
                    doc.writeText(5, 261, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', {
                        align: 'center',
                        width: 209
                    });
                    doc.writeText(5, 265, 'PBX: 2327 - 6000 FAX: 2327 - 6090', {align: 'center', width: 209});
                    doc.writeText(5, 269, 'https://www.saas.gob.gt', {align: 'center', width: 209});

                    multilineaConNegrita(25, 95, 169, solvenciaTexto);

                    copia_tipo = 'Copia';
                    copia += 1;
                    if (copia <= 1)
                        doc.addPage();

                } while (copia <= 1);
            }


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

                doc.setDrawColor();
                doc.setTextColor();
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

            doc.autoPrint();
            //doc.save();

            $("#pdf_preview_estado").attr("src", doc.output('datauristring'));
            $('#re_load').hide();
        }

    }).done(function () {

    }).fail(function (jqXHR, textStatus, errorThrown) {

    });
}

function dottedLine(doc, xFrom, yFrom, xTo, yTo, segmentLength) {
    // Calculate line length (c)
    var a = Math.abs(xTo - xFrom);
    var b = Math.abs(yTo - yFrom);
    var c = Math.sqrt(Math.pow(a, 2) + Math.pow(b, 2));

    // Make sure we have an odd number of line segments (drawn or blank)
    // to fit it nicely
    var fractions = c / segmentLength;
    var adjustedSegmentLength = (Math.floor(fractions) % 2 === 0) ? (c / Math.ceil(fractions)) : (c / Math.floor(fractions));

    // Calculate x, y deltas per segment
    var deltaX = adjustedSegmentLength * (a / c);
    var deltaY = adjustedSegmentLength * (b / c);

    var curX = xFrom, curY = yFrom;
    while (curX <= xTo && curY <= yTo) {
        doc.line(curX, curY, curX + deltaX, curY + deltaY);
        curX += 2 * deltaX;
        curY += 2 * deltaY;
    }
}


function reporte_solvencia_anterior(id_doc_solvencia) {
    $.ajax({
        type: "POST",
        url: "insumos/php/back/hojas/reporte_solvencia.php",
        dataType: 'json',
        data: {
            id_doc_solvencia: id_doc_solvencia
        },
        beforeSend: function () {

        },
        success: function (data) {
            //alert(data);
            var movimiento, transaccion, fecha, empleado, direccion, encargado, bodega;
            var doc = new jsPDF('p', 'mm');
            var total = 0;
            for (var i in data) {
                total += 1;
            }
            console.log('Solvencia TEST');
            console.log(data);


            movimiento = data[0].solvencia_tipo;
            transaccion = data[0].transaccion;
            //alert(transaccion);
            fecha = data[0].fecha;
            empleado = data[0].empleado;
            direccion = data[0].direccion;
            encargado = data[0].encargado;
            bodega = data[0].bodega;


            console.log('Transaccion: ' + transaccion);
            console.log('Fecha: ' + fecha);
            console.log('Empleado: ' + empleado);
            console.log('Direccion: ' + direccion);
            console.log('Encargado: ' + encargado);
            console.log('Bodega: ' + bodega);

            //console.log(total);


            if (total <= 6) {
                p1 = 72;
                p2 = 60;
                p3 = 120;
                p4 = 15;

                doc.addImage(logo, 'png', 10, p2 - 50, 50, 40);
                doc.writeText(185, p2 - 45, 'Original', {align: 'right', width: 17});
                doc.setTextColor(255, 255, 255);
                doc.setDrawColor(0, 136, 176);
                doc.setFillColor(0, 136, 176);
                doc.roundedRect(10, p2 + 2, 193, 2, 0, 0, 'FD');
                doc.roundedRect(10, p2 - 8, 193, 12, 1, 1, 'FD');
                doc.setFontSize(14);
                doc.writeText(15, p2, 'Tipo', {align: 'center', width: 17});
                doc.writeText(50, p2, "Marca", {align: 'center', width: 17});
                doc.writeText(110, p2, "Modelo", {align: 'center', width: 5});
                doc.writeText(160, p2, "Serie", {align: 'center', width: 5});
                doc.writeText(190, p2, "Total", {align: 'center', width: 5});
                doc.setDrawColor(204, 204, 204);
                doc.setFillColor(204, 204, 204);
                doc.setTextColor(0, 136, 176);
                doc.setFontSize(10);
            }

            doc.setFontType("bold");

            doc.setTextColor(0, 136, 176);


            doc.setTextColor(68, 68, 68);
            //doc.setFontSize(10);
            doc.setFontType("bold");
            doc.writeText(100, p4, movimiento, {align: 'left', width: 5});
            doc.setFontType("normal");
            doc.writeText(68, p4, "Movimiento:", {align: 'left', width: 5});
            doc.writeText(68, p4 + 5, "Fecha:", {align: 'left', width: 5});
            doc.writeText(68, p4 + 10, "Transacción No.:", {align: 'left', width: 5});
            doc.writeText(68, p4 + 15, "Responsable:", {align: 'left', width: 5});
            doc.writeText(68, p4 + 20, "Dirección:", {align: 'left', width: 5});
            doc.writeText(68, p4 + 25, "Encargado:", {align: 'left', width: 5});
            doc.writeText(68, p4 + 30, "Bodega:", {align: 'left', width: 5});

            doc.writeText(100, p4 + 5, fecha, {align: 'left', width: 5});
            doc.writeText(100, p4 + 10, transaccion, {align: 'left', width: 5});
            doc.writeText(100, p4 + 15, empleado, {align: 'left', width: 5});
            doc.writeText(100, p4 + 20, direccion, {align: 'left', width: 5});
            doc.writeText(100, p4 + 25, encargado, {align: 'left', width: 5});
            doc.writeText(100, p4 + 30, bodega, {align: 'left', width: 5});

            doc.line(25, p3 - 5, 100, p3 - 5);
            doc.line(110, p3 - 5, 185, p3 - 5);
            doc.writeText(60, p3, empleado, {align: 'center', width: 5});
            doc.writeText(125, p3, encargado, {align: 'left', width: 5});

            doc.setFontSize(8);
            doc.setTextColor(5, 83, 142);
            doc.writeText(10, p3 + 5, 'Reporte Generado SAAS - Control de Insumos', {align: 'center', width: 190});

            //doc.addImage(footer, 'png', 5, p3+6, 205, 15);
            doc.writeText(10, p3 + 8, '6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"', {
                align: 'center',
                width: 190
            });
            doc.writeText(10, p3 + 11, 'PBX: 2327-600 FAX: 2327-6090', {align: 'center', width: 190});
            doc.setTextColor(255, 255, 255);

            doc.line(5, p3 + 18, 210, p3 + 18);

            // segunda mitad
            p1 = 72;
            suma = 134;
            p1 += suma;
            p2 += suma;
            p3 += suma;
            p4 += suma;

            doc.setTextColor(0, 136, 176);
            doc.setFontSize(12);
            doc.setFontType("normal");
            doc.addImage(logo, 'png', 10, p2 - 50, 50, 40);
            doc.writeText(185, p2 - 45, 'Copia', {align: 'right', width: 17});

            doc.setTextColor(255, 255, 255);
            doc.setDrawColor(0, 136, 176);
            doc.setFillColor(0, 136, 176);
            doc.roundedRect(10, p2 + 2, 193, 2, 0, 0, 'FD');
            doc.roundedRect(10, p2 - 8, 193, 12, 1, 1, 'FD');
            doc.setFontSize(14);
            doc.writeText(15, p2, 'Tipo', {align: 'center', width: 17});
            doc.writeText(50, p2, "Marca", {align: 'center', width: 17});
            doc.writeText(110, p2, "Modelo", {align: 'center', width: 5});
            doc.writeText(160, p2, "Serie", {align: 'center', width: 5});


            doc.writeText(190, p2, "Total", {align: 'center', width: 5});
            doc.setDrawColor(204, 204, 204);
            doc.setFillColor(204, 204, 204);
            doc.setTextColor(0, 136, 176);
            doc.setFontSize(10);


            doc.autoPrint();
            //doc.save();

            $("#pdf_preview_estado").attr("src", doc.output('datauristring'));
            $('#re_load').hide();
        }

    }).done(function () {

    }).fail(function (jqXHR, textStatus, errorThrown) {


    });
}
