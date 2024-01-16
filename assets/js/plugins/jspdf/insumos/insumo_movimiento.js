function reporte_movimiento(id_doc_insumo) {
    $.ajax({
        type: "POST",
        url: "insumos/php/back/hojas/reporte_movimiento.php",
        dataType: 'json',
        data: {
            id_doc_insumo: id_doc_insumo,
        },
        beforeSend: function () {
        },
        success: function (data) {
            console.log('reporte' + data);
            var total = 0;
            var doc = new jsPDF('p', 'mm');
            for (var i in data) {
                total += 1;
            }
            //console.log(total);
            n = new Date();
            y = n.getFullYear();
            m = n.getMonth() + 1;
            var mo;
            if (m < 10) {
                mo = "0" + m;
            } else {
                mo = m;
            }

            d = n.getDate();
            //Get the hour (0-23)h=n.getMilliseconds()
            //Get the milliseconds (0-999)
            h = n.getHours();
            var mi;
            i = n.getMinutes(); // Get the minutes (0-59)
            if (i < 10) {
                mi = '0' + i;
            } else {
                mi = i;
            }
            s = n.getSeconds();
            //m =
            var bodega_;
            var pages = 1;
            var condicion1 = 'CONDICIONES DE ENTREGA';
            var condicion2 = 'PRIMERO: Dejo constancia que recibo la terminal móvil asumiendo los compromisos siguientes: En caso de exceder la cuota asignada, de ser necesario adquiriré recargas única y exclusivamente a través del sistema prepago. Prohibiciones. El receptor de la terminal móvil que por ese medio se le asigna, tiene prohibido: a) Hacer recargas automáticas o en línea; únicamente podrá hacerla en sistema prepago; b) El uso de internet y mensajitos si estos no están contemplados en el plan; c) Efectuar llamadas a cualquier servicio con el símbolo * (asterisco) por ejemplo: *DAR, *7171 LINEA AMIGOS; d) Efectuar llamadas para sorteos, promociones, entre otros; e) Generar exceso adicional al plan establecido. f) Intercambiar el aparato telefónico o la tarjeta SIM, que por este acto se le asigna, entre usuarios, terceros u otros celulares.  SEGUNDO: El teléfono celular es asignado al PUESTO y no a la PERSONA en PARTICULAR, por lo que, al momento de abandonar el puesto, ser trasladado a otro Departamento o Dirección, el teléfono celular debe ser devuelto con todos los accesorios descritos en la presente hoja al encargado de terminales móviles de la Dirección de Comunicaciones e Informática. Al momento de salir de vacaciones deberá entregar el teléfono a la Dirección de Comunicaciones e Informática, quien a solicitud de la Dirección a cargo hará la solicitud para asignar la terminal móvil a la persona que cubra el servicio durante su ausencia. Salvo autorización expresa y por escrito del Despacho Superior TERCERO: En caso de pérdida o robo de la terminal móvil, el usuario deberá presentar a la Dirección de Comunicaciones e Informática, fotocopia de la denuncia y avisos respectivos Y pagar el costo del deducible que corresponda según el proveedor del servicio de datos móviles o telefonía celular. Dicha Dirección remitirá copia de la documentación a la Dirección de Asuntos Internos, para la verificación respectiva CUARTO: Todas las llamadas entre los números de celulares TIGO de servicio de SAAS son gratuitas, es decir que el tiempo de aire según el plan establecido es para llamadas a números que no son de servicio de SAAS. SEXTO: Queda prohibido agregar o quitar aplicaciones establecidas y predeterminadas a cada terminal móvil. SÉPTIMO: El uso del celular es para uso exclusivo de la institución.';
            var condicion3 = 'El incumplimiento en cualquiera de las condiciones aquí establecidas, sujeta al usuario a la aplicación de las sanciones disciplinarias que correspondan.';
            var movimiento, transaccion, fecha, empleado, empleado2, direccion, encargado, bodega;

            console.log(data);

            for (var i in data) {
                bodega_ = data[i].bodega;
                transaccion = data[i].transaccion;
                fecha = data[i].fecha;
                movimiento = data[i].movimiento;
                empleado = data[i].empleado;
                empleado2 = data[i].empleado2;
                direccion = data[i].direccion;
                encargado = data[i].encargado;
                bodega = data[i].bodega;
                observaciones = data[i].observaciones;
                descripcion = data[i].descripcion;
                personaAutoriza = data[i].persona_autoriza;
                nroDocumento = data[i].nro_documento_autoriza;
            }
            console.log(observaciones);

            console.log('Movimiento:' + movimiento);
            if (bodega_ == 'Moviles') {
                for (var x = 1; x <= 2; x++) {
                    p1 = 75;
                    suma = 134;
                    p2 = 65;
                    p3 = 110 + suma;
                    p4 = 15;
                    doc.setFontSize(9);
                    doc.addImage(logo, 'png', 10, p2 - 55, 50, 40);
                    doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255, 255);
                    doc.rect(23, 36, 27, 1, "FD")

                    doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255,255);
                    doc.rect(23, 170, 27, 1, "FD")

                    for (var i in data) {
                        doc.setFontType("bold");
                        doc.setFontType("normal");
                        doc.setTextColor(68, 68, 68);
                        doc.writeText(18.5, p1, "" + data[i].tipo + "", { align: 'center', width: 17 });
                        doc.writeText(62, p1, "" + data[i].marca + "", { align: 'center', width: 17 });
                        doc.writeText(117.5, p1, "" + data[i].modelo + "", { align: 'center', width: 5 });
                        doc.writeText(163, p1, "" + data[i].serie + "", { align: 'center', width: 5 });
                        doc.writeText(191, p1, "" + data[i].cantidad + "", { align: 'center', width: 5 });
                        //console.log(data[i].empleado)
                        p1 += 4;
                    }

                    //doc.setTextColor(255, 255, 255);
                    doc.setDrawColor(0, 136, 176);
                    doc.setDrawColor(204, 204, 204);
                    doc.setFillColor(0, 136, 176);
                    //doc.roundedRect(10, p2+2, 193, 2, 0, 0);
                    doc.roundedRect(10, p2 - 8, 193, 87, 1, 1);
                    doc.line(10, p2 + 2, 203, p2 + 2);
                    doc.line(45, p2 - 8, 45, p2 + 79);
                    doc.line(95, p2 - 8, 95, p2 + 79);
                    doc.line(145, p2 - 8, 145, p2 + 79);
                    doc.line(185, p2 - 8, 185, p2 + 79);
                    doc.setFontSize(8);
                    doc.writeText(18.5, p2 - 2, 'IMEI', { align: 'center', width: 17 });
                    doc.writeText(62, p2 - 2, "Marca", { align: 'center', width: 17 });
                    doc.writeText(117.5, p2 - 2, "Modelo", { align: 'center', width: 5 });
                    doc.writeText(163, p2 - 2, "Número", { align: 'center', width: 5 });

                    doc.setTextColor(68, 68, 68);
                    doc.setFontSize(9);
                    doc.setFontType("bold");
                    doc.writeText(100, p4, movimiento, { align: 'left', width: 5 });
                    doc.setFontType("normal");
                    doc.writeText(68, p4, "Movimiento:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 5, "Fecha:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 10, "Transacción No.:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 15, "Responsable 1:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 20, "Responsable 2:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 25, "Dirección:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 30, "Encargado:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 35, "Bodega:", { align: 'left', width: 5 });

                    doc.writeText(100, p4 + 5, fecha, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 10, transaccion, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 15, empleado, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 20, empleado2, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 25, "" + direccion + "", { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 35, encargado, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 30, bodega, { align: 'left', width: 5 });

                    doc.writeText(191, p2 - 2, "Total", { align: 'center', width: 5 });
                    doc.setDrawColor(204, 204, 204);
                    doc.setFillColor(204, 204, 204);
                    doc.setTextColor(0, 136, 176);
                    doc.setFontSize(8);
                    doc.setTextColor(68, 68, 68);

                    doc.roundedRect(10, p3 - 95, 193, 60, 1, 1);
                    var r_d = condicion2;
                    doc.setFontSize(7);
                    doc.setFontType("bold");
                    doc.writeText(13, p3 - 90, condicion1 + ':', { align: 'left', width: 5 });
                    doc.setFontType("normal");
                    var r_lineas = doc.splitTextToSize(r_d, 185);
                    doc.text(13, p3 - 85, r_lineas);

                    doc.writeText(13, p3 - 40, condicion3, { align: 'left', width: 5 });
                    doc.setFontSize(14);
                    if (movimiento != 'Devolucion') {
                        //doc.writeText(15, p3-25 ,'Debe de devolver el telefono anterior a más tardar el viernes 08 de julio de 2020',{align:'left',width:5});
                    }

                    doc.line(25, p3 - 15, 100, p3 - 15);
                    doc.line(110, p3 - 15, 185, p3 - 15);
                    doc.line(60, p3, 140, p3);
                    doc.setFontSize(8);
                    doc.roundedRect(10, p3 - 30, 193, 40, 1, 1);
                    doc.writeText(60, p3 - 10, empleado, { align: 'center', width: 5 });
                    doc.writeText(145, p3 - 10, empleado2, { align: 'center', width: 5 });
                    doc.writeText(100, p3 + 5, encargado, { align: 'center', width: 5 });

                    doc.setFontSize(8);
                    doc.setTextColor(5, 83, 142);
                    doc.writeText(10, p3 + 15, 'Reporte Generado - Control de Insumos', { align: 'center', width: 190 });

                    //doc.addImage(footer, 'png', 5, p3+6, 205, 15);
                    doc.writeText(10, p3 + 18, '6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"', {
                        align: 'center',
                        width: 190
                    });
                    doc.writeText(10, p3 + 21, 'PBX: 2327-600 FAX: 2327-6090', { align: 'center', width: 190 });
                    doc.setTextColor(68, 68, 68);

                    if (x == 1) {
                        doc.writeText(185, p2 - 55, 'Original', { align: 'right', width: 17 });
                        doc.addPage();
                    } else {
                        doc.writeText(185, p2 - 55, 'Copia', { align: 'right', width: 17 });

                    }
                }
            } else if ((bodega_ == 'Armeria' && movimiento == 'Resguardo') || (bodega_ == 'Armeria' && movimiento == 'Entrega')) {
                myHTML = 'impresion test';
                url = window.location.href;
                console.log(url);
                var base_url = window.location.origin;
                var ticket_url = base_url + '/saas_app/insumos/php/back/hojas/reporte_ticket.php?id_doc_insumo=' + transaccion;
                myWin = window.open(ticket_url, "_blank");
            } else {
                if (total <= 6) {
                    for (var x = 1; x <= 2; x++) {
                        var p1, p2, p3, p4;
                        doc.setFontSize(9);
                        if (x == 1) {
                            p1 = 72;
                            p2 = 60;
                            p3 = 120;
                            p4 = 15;
                            doc.setDrawColor(204, 204, 204);
                            doc.line(5, p3 + 18, 210, p3 + 18);
                            doc.setTextColor(68, 68, 68);
                            doc.writeText(185, p2 - 45, 'Original', { align: 'right', width: 17 });
                        } else {
                            p1 = 72;
                            suma = 134;
                            p1 += suma;
                            p2 += suma;
                            p3 += suma;
                            p4 += suma;
                            doc.setTextColor(68, 68, 68);
                            doc.writeText(185, p2 - 45, 'Copia', { align: 'right', width: 17 });
                        }

                        var movimiento, transaccion, fecha, empleado, direccion, encargado, bodega;
                        doc.addImage(logo, 'png', 10, p2 - 50, 50, 40);
                        doc.setDrawColor(255, 255, 255);
                        doc.setFillColor(255, 255, 255);
                        doc.rect(23, 36, 27, 1, "FD")

                        doc.setDrawColor(255, 255, 255);
                        doc.setFillColor(255, 255, 255);
                        doc.rect(23, 170, 27, 1, "FD")

                        for (var i in data) {
                            doc.setFontType("bold");

                            doc.setFontType("normal");
                            doc.setTextColor(68, 68, 68);

                            doc.writeText(25, p1 - 5, "" + data[i].tipo + "", { align: 'center', width: 17 });
                            doc.writeText(62, p1 - 5, "" + data[i].marca + "", { align: 'center', width: 17 });

                            doc.writeText(116, p1 - 5, "" + data[i].modelo + "", { align: 'center', width: 5 });
                            doc.writeText(164, p1 - 5, "" + data[i].serie + "", { align: 'center', width: 5 });
                            doc.writeText(191, p1 - 5, "" + data[i].cantidad + "", { align: 'center', width: 5 });
                            //console.log(data[i].empleado)
                            doc.line(10, p1 - 3, 203, p1 - 3);
                            p1 += 6;
                        }

                        //doc.setTextColor(255, 255, 255);
                        doc.setDrawColor(0, 136, 176);
                        doc.setDrawColor(204, 204, 204);
                        doc.setFillColor(0, 136, 176);
                        //doc.roundedRect(10, p2+2, 193, 2, 0, 0);
                        doc.roundedRect(10, p2 - 8, 193, 47, 1, 1);
                        doc.line(10, p2 + 2, 203, p2 + 2);
                        /*doc.line(45, p2-8, 45, p2+39);
                        doc.line(95, p2-8, 95, p2+39);
                        doc.line(145, p2-8, 145, p2+39);
                        doc.line(185, p2-8, 185, p2+39);*/

                        doc.setFontSize(8);
                        doc.writeText(26, p2 - 2, 'Tipo', { align: 'center', width: 17 });
                        doc.writeText(62, p2 - 2, "Marca", { align: 'center', width: 17 });
                        doc.writeText(117.5, p2 - 2, "Modelo", { align: 'center', width: 5 });
                        doc.writeText(163, p2 - 2, "Serie", { align: 'center', width: 5 });

                        doc.setTextColor(68, 68, 68);
                        doc.setFontSize(9);
                        doc.setFontType("bold");
                        doc.writeText(100, p4, movimiento, { align: 'left', width: 5 });
                        doc.setFontType("normal");
                        doc.writeText(68, p4, "Movimiento:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 5, "Fecha:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 10, "Transacción No.:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 15, "Responsable 1:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 20, "Responsable 2:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 25, "Dirección:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 30, "Encargado:", { align: 'left', width: 5 });
                        doc.writeText(68, p4 + 35, "Bodega:", { align: 'left', width: 5 });

                        doc.writeText(100, p4 + 5, fecha, { align: 'left', width: 5 });
                        doc.writeText(100, p4 + 10, transaccion, { align: 'left', width: 5 });
                        doc.writeText(100, p4 + 15, empleado, { align: 'left', width: 5 });
                        doc.writeText(100, p4 + 20, empleado2, { align: 'left', width: 5 });
                        doc.writeText(100, p4 + 25, "" + direccion + "", { align: 'left', width: 5 });
                        doc.writeText(100, p4 + 30, encargado, { align: 'left', width: 5 });
                        doc.writeText(100, p4 + 35, bodega, { align: 'left', width: 5 });

                        doc.writeText(191, p2 - 2, "Total", { align: 'center', width: 5 });
                        doc.setDrawColor(204, 204, 204);
                        doc.setFillColor(204, 204, 204);
                        doc.setTextColor(0, 136, 176);
                        doc.setFontSize(8);
                        doc.setTextColor(68, 68, 68);


                        doc.line(10, p3 - 7, 65, p3 - 7);
                        doc.line(85, p3 - 7, 135, p3 - 7);
                        doc.line(155, p3 - 7, 205, p3 - 7);
                        doc.setFontSize(8);

                        doc.writeText(35, p3 - 2, empleado, { align: 'center', width: 5 });
                        doc.writeText(105, p3 - 2, empleado2, { align: 'center', width: 5 });
                        doc.writeText(178, p3 - 2, 'Entregado por: ' + encargado, { align: 'center', width: 5 });

                        doc.setFontSize(8);
                        doc.setTextColor(5, 83, 142);
                        doc.writeText(10, p3 + 5, 'Reporte Generado SAAS - Control de Insumos', {
                            align: 'center',
                            width: 190
                        });

                        //doc.addImage(footer, 'png', 5, p3+6, 205, 15);
                        doc.writeText(10, p3 + 8, '6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"', {
                            align: 'center',
                            width: 190
                        });
                        doc.writeText(10, p3 + 11, 'PBX: 2327-600 FAX: 2327-6090', { align: 'center', width: 190 });

                        if (observaciones) {
                            transaccion_observaciones = '**Observaciones:** ';
                            if (descripcion)
                                transaccion_observaciones += descripcion;
                            if (personaAutoriza)
                                transaccion_observaciones += '**Persona Autoriza** ' + personaAutoriza;
                            if (nroDocumento)
                                transaccion_observaciones += '**No. Documento Autorización** ' + nroDocumento
                            multilineaConNegrita(10, p3 - 17, 200, transaccion_observaciones);
                        }
                        //doc.writeText(200,p3-18,observaciones);

                        //doc.setTextColor(255, 255, 255);
                        doc.setLineWidth();
                        doc.setFontSize();
                        doc.setFontType();
                        doc.setTextColor(68, 68, 68);
                        doc.setDrawColor(204, 204, 204);




                    }

                }
                else {
                    p1 = 72;
                    suma = 134;
                    p2 = 60;
                    p3 = 120 + suma;
                    p4 = 15;

                    doc.addImage(logo, 'png', 10, p2 - 50, 50, 40);
                    doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255, 255);
                    doc.rect(23, 36, 27, 1, "FD")

                    doc.setDrawColor(255, 255, 255);
                    doc.setFillColor(255, 255, 255);
                    doc.rect(23, 170, 27, 1, "FD")

                    doc.writeText(185, p2 - 45, 'Original', { align: 'right', width: 17 });

                    doc.setTextColor(255, 255, 255);
                    doc.setDrawColor(0, 136, 176);
                    doc.setFillColor(0, 136, 176);
                    doc.roundedRect(10, p2 + 2, 193, 2, 0, 0, 'FD');
                    doc.roundedRect(10, p2 - 8, 193, 12, 1, 1, 'FD');
                    doc.setFontSize(14);
                    doc.writeText(15, p2, 'Tipo', { align: 'center', width: 17 });
                    doc.writeText(50, p2, "Marca", { align: 'center', width: 17 });
                    doc.writeText(110, p2, "Modelo", { align: 'center', width: 5 });
                    doc.writeText(160, p2, "Serie", { align: 'center', width: 5 });


                    doc.writeText(190, p2, "Total", { align: 'center', width: 5 });
                    doc.setDrawColor(204, 204, 204);
                    doc.setFillColor(204, 204, 204);
                    doc.setTextColor(0, 136, 176);
                    doc.setFontSize(10);
                    for (var i in data) {
                        transaccion = data[i].transaccion;
                        fecha = data[i].fecha;
                        movimiento = data[i].movimiento;
                        empleado = data[i].empleado;
                        direccion = data[i].direccion;
                        encargado = data[i].encargado;
                        bodega = data[i].bodega;

                        doc.setFontType("bold");

                        doc.setFontType("normal");
                        doc.writeText(15, p1, "" + data[i].tipo + "", { align: 'center', width: 17 });
                        doc.writeText(50, p1, "" + data[i].marca + "", { align: 'center', width: 17 });

                        doc.writeText(110, p1, "" + data[i].modelo + "", { align: 'center', width: 5 });
                        doc.writeText(160, p1, "" + data[i].serie + "", { align: 'center', width: 5 });
                        doc.writeText(190, p1, "" + data[i].cantidad + "", { align: 'center', width: 5 });
                        //console.log(data[i].empleado)
                        p1 += 5;
                    }
                    doc.setTextColor(0, 136, 176);

                    //oc.writeText(10, 27 ,""+d+"/"+mo+"/"+y+"",{align:'center',width:190});

                    doc.setTextColor(68, 68, 68);
                    doc.setFontSize(10);
                    doc.setFontType("bold");
                    doc.writeText(100, p4, movimiento, { align: 'left', width: 5 });
                    doc.setFontType("normal");
                    doc.writeText(68, p4, "Movimiento:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 5, "Fecha:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 10, "Transacción No.:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 15, "Responsable:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 20, "Dirección:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 25, "Encargado:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 30, "Bodega:", { align: 'left', width: 5 });

                    doc.writeText(100, p4 + 5, fecha, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 10, transaccion, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 15, empleado, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 20, "" + direccion + "", { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 25, encargado, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 30, bodega, { align: 'left', width: 5 });

                    doc.line(25, p3 - 5, 100, p3 - 5);
                    doc.line(110, p3 - 5, 185, p3 - 5);
                    doc.writeText(60, p3, empleado, { align: 'center', width: 5 });
                    doc.writeText(125, p3, encargado, { align: 'left', width: 5 });

                    doc.setFontSize(8);
                    doc.setTextColor(5, 83, 142);
                    doc.writeText(10, p3 + 5, 'Reporte Generado - Control de Insumos', { align: 'center', width: 190 });

                    //doc.addImage(footer, 'png', 5, p3+6, 205, 15);
                    doc.writeText(10, p3 + 8, '6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"', {
                        align: 'center',
                        width: 190
                    });
                    doc.writeText(10, p3 + 11, 'PBX: 2327-600 FAX: 2327-6090', { align: 'center', width: 190 });

                    doc.addPage();

                    p1 = 72;
                    suma = 134;
                    p2 = 60;
                    p3 = 120 + suma;
                    p4 = 15;

                    //doc.addImage(logo, 'png', 10, p2-50, 50, 40);
                    doc.writeText(185, p2 - 45, 'Copia', { align: 'right', width: 17 });

                    doc.setTextColor(255, 255, 255);
                    doc.setDrawColor(0, 136, 176);
                    doc.setFillColor(0, 136, 176);
                    doc.roundedRect(10, p2 + 2, 193, 2, 0, 0, 'FD');
                    doc.roundedRect(10, p2 - 8, 193, 12, 1, 1, 'FD');
                    doc.setFontSize(14);
                    doc.writeText(15, p2, 'Tipo', { align: 'center', width: 17 });
                    doc.writeText(50, p2, "Marca", { align: 'center', width: 17 });
                    doc.writeText(110, p2, "Modelo", { align: 'center', width: 5 });
                    doc.writeText(160, p2, "Serie", { align: 'center', width: 5 });


                    doc.writeText(190, p2, "Total", { align: 'center', width: 5 });
                    doc.setDrawColor(204, 204, 204);
                    doc.setFillColor(204, 204, 204);
                    doc.setTextColor(0, 136, 176);
                    doc.setFontSize(10);
                    for (var i in data) {
                        transaccion = data[i].transaccion;
                        fecha = data[i].fecha;
                        movimiento = data[i].movimiento;
                        empleado = data[i].empleado;
                        direccion = data[i].direccion;
                        encargado = data[i].encargado;
                        bodega = data[i].bodega;

                        doc.setFontType("bold");

                        doc.setFontType("normal");
                        doc.writeText(15, p1, "" + data[i].tipo + "", { align: 'center', width: 17 });
                        doc.writeText(50, p1, "" + data[i].marca + "", { align: 'center', width: 17 });

                        doc.writeText(110, p1, "" + data[i].modelo + "", { align: 'center', width: 5 });
                        doc.writeText(160, p1, "" + data[i].serie + "", { align: 'center', width: 5 });
                        doc.writeText(190, p1, "" + data[i].cantidad + "", { align: 'center', width: 5 });
                        //console.log(data[i].empleado)
                        p1 += 5;
                    }
                    doc.setTextColor(0, 136, 176);

                    //oc.writeText(10, 27 ,""+d+"/"+mo+"/"+y+"",{align:'center',width:190});

                    doc.setTextColor(68, 68, 68);
                    doc.setFontSize(10);
                    doc.setFontType("bold");
                    doc.writeText(100, p4, movimiento, { align: 'left', width: 5 });
                    doc.setFontType("normal");
                    doc.writeText(68, p4, "Movimiento:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 5, "Fecha:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 10, "Transacción No.:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 15, "Responsable:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 20, "Dirección:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 25, "Encargado:", { align: 'left', width: 5 });
                    doc.writeText(68, p4 + 30, "Bodega:", { align: 'left', width: 5 });

                    doc.writeText(100, p4 + 5, fecha, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 10, transaccion, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 15, empleado, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 20, "" + direccion + "", { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 25, encargado, { align: 'left', width: 5 });
                    doc.writeText(100, p4 + 30, bodega, { align: 'left', width: 5 });

                    doc.line(25, p3 - 5, 100, p3 - 5);
                    doc.line(110, p3 - 5, 185, p3 - 5);
                    doc.writeText(60, p3, empleado, { align: 'center', width: 5 });
                    doc.writeText(125, p3, encargado, { align: 'left', width: 5 });

                    doc.setFontSize(8);
                    doc.setTextColor(5, 83, 142);
                    doc.writeText(10, p3 + 5, 'Reporte Generado SAAS - Control de Insumos', {
                        align: 'center',
                        width: 190
                    });

                    //doc.addImage(footer, 'png', 5, p3+6, 205, 15);
                    doc.writeText(10, p3 + 8, '6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"', {
                        align: 'center',
                        width: 190
                    });
                    doc.writeText(10, p3 + 11, 'PBX: 2327-600 FAX: 2327-6090', { align: 'center', width: 190 });
                }
            }

            var x = document.getElementById("pdf_preview_estado");
            if (x.style.display === "none") {
                x.style.display = "none";
            } else {
                x.style.display = "none";
            }

            function multilineaConNegrita(x, y, endx, text) {
                const isBoldOpen = (arrayLength, valueBefore = false) => {
                    const isEven = arrayLength % 2 === 0;
                    const result = valueBefore !== isEven;
                    return result;
                };
                const lineSpacing = 3;
                const fontSize = 8;
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
                            startX = startX + doc.getStringUnitWidth(textItems) * 3;
                        });
                        boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                        startX = startXCached;
                        startY += lineSpacing;
                    }
                });
            }

            if (!((bodega_ == 'Armeria' && movimiento == 'Resguardo') || (bodega_ == 'Armeria' && movimiento == 'Entrega'))) {
                doc.autoPrint()
            }

            //doc.save();

            $("#pdf_preview_estado").attr("src", doc.output('datauristring'));
            $('#re_load').hide();
        }
    }).done(function () {

    }).fail(function (jqXHR, textStatus, errorThrown) {

    });
}
