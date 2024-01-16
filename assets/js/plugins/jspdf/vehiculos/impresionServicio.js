function impresionServicio(idServicio, estado) {
    $.ajax({
        type: "GET",
        url: "vehiculos/php/back/servicios/action/impresionServicio.php",
        dataType: 'json',
        data: {
            opcion: 1,
            idServicio: idServicio
        },
        beforeSend: function () {
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
                }
            })
        },
        success: function (data) {
            var revision = 78
            var revision2 = 83
            var servicio = data[0];
            var estadoVehiculo;
            if (servicio.descripcion_solicitado.charAt(0) == '>' || servicio.descripcion_solicitado.charAt(1) == '>' || servicio.descripcion_solicitado.charAt(2) == '>') {
                servicio.descripcion_solicitado = '<' + servicio.descripcion_solicitado
            }
            if (estado == 5489) {
                estadoVehiculo = 'VEHÍCULO DEVUELTO'
            } else {
                estadoVehiculo = 'REVISIÓN EN TALLER'
            }
            linxPag = 35;
            console.log(linxPag);

            var doc = new jsPDF('p', 'mm');
            var today = new Date();
            var wFecha = String(today.getDate()).padStart(2, '0') + '/' + String(today.getMonth() + 1).padStart(2, '0') + '/' + today.getFullYear();
            var wHora = String(today.getHours()).padStart(2, '0') + ':' + String(today.getMinutes()).padStart(2, '0') + ':' + String(today.getSeconds()).padStart(2, '0');

            //Dibujando enmarcado
            doc.setLineWidth(0.3);
            doc.roundedRect(5, 5, 205, 259, 2, 2); // Dibuja un cuadro de 140 mm de ancho y 280 mm de alto

            //Encabezado
            doc.addImage(logo_saas, 'png', 9, 8, 40, 30);
            //cuadro global
            doc.setLineWidth(0.2);
            doc.line(115, 5, 115, 40);
            doc.line(155, 5, 155, 40);

            doc.setFontType("bold");
            doc.setFontSize(8);
            doc.writeText(35, 12, 'SECRETARIA DE ASUNTOS', { align: 'center', width: 90 });
            doc.writeText(35, 16, 'ADMINISTRATIVOS Y DE SEGURIDAD DE LA', { align: 'center', width: 90 });
            doc.writeText(35, 20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 90 });
            doc.setFontType("normal");
            doc.writeText(35, 24, 'GUATEMALA, C.A.', { align: 'center', width: 90 });

            doc.setFontType("bold");
            doc.setFontSize(11);

            var numeroServicio = doc.splitTextToSize('NRO. ORDEN:', 65);
            var numeroServicio2 = doc.splitTextToSize(servicio.id_servicio, 65);

            doc.text(68, 31, numeroServicio, { align: 'center' });
            doc.setFontType('normal');
            doc.writeText(66, 36, servicio.nro_orden, { align: 'center', width: 30 });

            doc.setFontType("bold");

            doc.setFontSize(8);
            doc.writeText(120, 11, 'FECHA DE IMPRESIÓN:', { align: 'center', width: 30 });
            doc.writeText(120, 21, 'HORA DE IMPRESIÓN:', { align: 'center', width: 30 });
            doc.writeText(120, 31, 'FECHA DE RECEPCIÓN:', { align: 'center', width: 30 });

            doc.setFontSize(11);
            doc.writeText(167, 13, 'IMPRESIÓN DEL SERVICIO:', { align: 'center', width: 30 });
            doc.writeText(167, 28, 'ESTADO:', { align: 'center', width: 30 });

            doc.setFontType('normal');
            doc.writeText(120, 15, wFecha, { align: 'center', width: 30 });
            doc.writeText(120, 25, wHora, { align: 'center', width: 30 });
            doc.writeText(120, 35, servicio.fecha_recepcion, { align: 'center', width: 30 });

            doc.setFontSize(12);
            doc.setFontType('bold');
            doc.text(176, 18, '#' + numeroServicio2, { align: 'center' });
            doc.setFontType('normal');
            doc.writeText(167, 33, estadoVehiculo, { align: 'center', width: 30 });

            doc.setFontSize(13);

            // Separador horizontal
            doc.setLineWidth(1);
            doc.line(5, 40, 210, 40);

            //Datos del servicio vehiculo1
            doc.setFontSize(8);
            doc.setFontType('normal');
            doc.writeText(9, 45, 'TIPO DE VEHÍCULO:', { align: 'left', width: 14 });
            doc.writeText(9, 50, 'TIPO DE SERVICIO:', { align: 'left', width: 14 });
            doc.writeText(9, 55, 'ENTREGA EN TALLER:', { align: 'left', width: 14 });
            doc.writeText(9, 60, 'GENERADO POR:', { align: 'left', width: 14 });
            doc.writeText(9, 65, 'DIRECCIÓN ASIGNADA:', { align: 'left', width: 14 });
            doc.writeText(9, 70, 'KM ACTUAL:', { align: 'left', width: 14 });


            doc.setFontType('bold');
            doc.writeText(50, 45, servicio.nro_placa + ' ' + servicio.nombre_tipo + ' ' + servicio.nombre_marca + ' ' + servicio.modelo, { align: 'left', width: 40 });
            doc.writeText(50, 50, servicio.tipo_servicio, { align: 'left', width: 40 });
            doc.writeText(50, 55, servicio.nombre_recepcion_persona_entrega, { align: 'left', width: 40 });
            doc.writeText(50, 60, servicio.nombre_recepcion_persona_recibe, { align: 'left', width: 40 });
            doc.writeText(50, 65, servicio.dir_nominal, { align: 'left', width: 40 });
            doc.writeText(50, 70, servicio.km_actual + " Km", { align: 'left', width: 40 });
            doc.setLineWidth(0.3);
            doc.line(5, 73, 210, 73);
            if (estado == 5489) {
                var revision = 138
                var revision2 = 143

                //Datos reparar1
                doc.setFontType('bold');
                doc.setFontSize(10);
                doc.writeText(9, 79, 'LUGAR DE SERVICIO: ', { align: 'left', width: 30 });
                doc.setFontSize(8);
                doc.setFontType('normal');
                doc.writeText(9, 84, 'LUGAR DEL TALLER:', { align: 'left', width: 14 });
                doc.writeText(9, 89, 'MECÁNICO ASIGNADO:', { align: 'left', width: 14 });

                doc.setFontType('bold');
                doc.writeText(50, 84, servicio.nombre_taller, { align: 'left', width: 40 });
                doc.writeText(50, 89, servicio.nombre_mecanico_asignado, { align: 'left', width: 40 });

                //Datos reparar2
                doc.setFontType('normal');
                doc.writeText(9, 94, 'FECHA ASIGNACIÓN:', { align: 'left', width: 20 });
                doc.writeText(9, 99, 'HORA ASIGNACIÓN:', { align: 'left', width: 20 });

                doc.setFontType('bold');
                doc.writeText(50, 94, servicio.fecha_asignacion_mecanico, { align: 'left', width: 15 });
                doc.writeText(50, 99, servicio.hora_asignacion_mecanico, { align: 'left', width: 15 });

                // Separador horizontal
                doc.line(5, 103, 210, 103);

                //Devolución de vehiculo1
                doc.setFontType('bold');
                doc.setFontSize(10);
                doc.writeText(9, 109, 'DEVOLUCIÓN DEL VEHÍCULO: ', { align: 'left', width: 30 });
                doc.setFontSize(8);
                doc.setFontType('normal');
                doc.writeText(9, 114, 'DEVUELTO POR:', { align: 'left', width: 14 });
                doc.writeText(9, 119, 'ACEPTADO POR:', { align: 'left', width: 14 });

                doc.setFontType('bold');
                doc.writeText(50, 114, servicio.nombre_entrega_persona_entrega, { align: 'left', width: 40 });
                doc.writeText(50, 119, servicio.nombre_entrega_persona_recibe, { align: 'left', width: 40 });

                //Devolución de vehiculo2
                doc.setFontType('normal');
                doc.writeText(9, 124, 'FECHA ENTREGADO:', { align: 'left', width: 20 });
                doc.writeText(9, 129, 'HORA ENTREGADO:', { align: 'left', width: 20 });

                doc.setFontType('bold');
                doc.writeText(50, 124, servicio.fecha_entregado, { align: 'left', width: 15 });
                doc.writeText(50, 129, servicio.hora_entregado, { align: 'left', width: 15 });

                // Separador horizontal
                doc.setLineWidth(1);
                doc.line(5, 132, 210, 132);

                //Reparación 
                doc.setFontType('bold');
                doc.setFontSize(10);
                doc.writeText(9, 180, 'MODIFICACIÓN REALIZADA: ', { align: 'left', width: 30 });
                doc.setFontSize(8);
                doc.setFontType('normal');
                var descripcionRealizadoFragments = doc.splitTextToSize(stripHtmlTags(servicio.descripcion_realizado), 197);
                doc.text(9, 185, descripcionRealizadoFragments, { align: 'justify' });
            }

            //Revisión 
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(9, revision, 'REVISIÓN SOLICITADA: ', { align: 'left', width: 30 });
            doc.setFontSize(8);
            doc.setFontType('normal');
            var descripcionSolicitadoFragments = doc.splitTextToSize(stripHtmlTags(servicio.descripcion_solicitado), 197);
            doc.text(9, revision2, descripcionSolicitadoFragments, { align: 'justify' });


            // Función para eliminar etiquetas HTML
            function stripHtmlTags(html) {
                var temporalDivElement = document.createElement("div");
                temporalDivElement.innerHTML = html;
                return temporalDivElement.textContent || temporalDivElement.innerText || "";
            }

            //Firmas
            doc.setFontSize(10);
            doc.setLineWidth(0.5);
            doc.writeText(50, 258, 'Taller');
            doc.line(30, 253, 80, 253); // Línea horizontal para la firma del taller
            doc.writeText(150, 258, 'Empleado');
            doc.line(133, 253, 183, 253); // Línea horizontal para la firma del empleadov

            //Informacion extra
            doc.setFontSize(7);
            doc.writeText(90, 262, 'Reporte Generado Herramientas Administrativas - Módulo Control de Vehículos', { align: 'center', width: 30 });
            doc.writeText(90, 268, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN". GUATEMALA, GUATEMALA', { align: 'center', width: 30 });
            doc.writeText(90, 271, 'PBX: 2327-6000 FAX: 2327-6090', { align: 'center', width: 30 });
            doc.writeText(90, 274, 'https://www.saas.gob.gt', { align: 'center', width: 30 });

            doc.autoPrint();
            $("#pdf_preview_v").attr("src", doc.output('datauristring'));
            $('#re_load').hide();
            //
        }

    });
}