const EventBus = new Vue();
const FotoEmpleado = httpVueLoader('./tickets/diagnosticos/src/componentes/fotografiaEmpleado.vue');
var saas = new Image();

// Establecer la ruta de la imagen
saas.src = './tickets/diagnosticos/img/logo_saas.jpg';  // Reemplaza 'ruta/de/tu/imagen.jpg' con la ruta correcta de tu imagen

import diagnosticosList from './diagnosticosList.js';

let diagnosticoDetalle = new Vue({
    el: '#appDetalleDiagnostico',
    data: {
        tituloModulo: 'Detalle Diagnostico',
        detalleDiagnostico: '',
        idDiagnostico: 0,
        idPersona: '',
        evento: '',
        pdf: '',
        idBien: '',
        bitacoraImpresiones: '',
        tipoTabla: 1
    },
    components: {
        'foto-empleado': FotoEmpleado,
    },

    mounted: function () {
        this.evento = EventBus
        this.idDiagnostico = $("#idDiagnostico").val()
        this.getDetalleDiagnostico(this.idDiagnostico)
    },
    methods: {
        getGenerarImpresion(fecha) {
            function addSquareJustifiedText(doc, text, x, y, maxWidth, maxHeight, fontSize) {
                const words = text.split(' ');
                let line = '';
                let lines = [];
                let currentLineHeight = 0;

                for (let i = 0; i < words.length; i++) {
                    const testLine = line + words[i] + ' ';
                    const testWidth = doc.getStringUnitWidth(testLine) * fontSize;

                    if (testWidth < maxWidth) {
                        line = testLine;
                    } else {
                        lines.push(line);
                        line = words[i] + ' ';
                        currentLineHeight += 1;
                    }
                }

                lines.push(line);
                currentLineHeight += 1;

                if (currentLineHeight > maxHeight) {
                    currentLineHeight = maxHeight;
                }

                const totalHeight = currentLineHeight * lineHeight;

                let newY = y;
                for (let i = 0; i < lines.length; i++) {
                    doc.text(lines[i], x, newY);
                    newY += lineHeight;
                }

                return totalHeight;
            }

            // Función para quitar etiquetas HTML
            function stripHtml(html) {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                return doc.body.textContent || "";
            }
            const maxWidth = 690;
            const maxHeight = 200;
            const lineHeight = 3;
            const fontSize = 12;

            let startX = 60;
            let startY = 320;
            let endX = 490;
            let descripcionFragments;

            var today = new Date();
            var wFecha;

            if (fecha) {
                wFecha = fecha;
            } else {
                // Obteniendo la fecha actual en formato de mes
                wFecha = today.toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' });
            }
// Crear un nuevo objeto Date
var fechaActual = new Date();

// Obtener el año actual
var añoActual = fechaActual.getFullYear();
            var wHora = String(today.getHours()).padStart(2, '0') + ':' + String(today.getMinutes()).padStart(2, '0') + ':' + String(today.getSeconds()).padStart(2, '0');

            var doc = new jsPDF('p', 'pt');

            let estilos = '<div style="text-align: justify; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">';
            // Encabezado
            doc.addImage(saas, 'png', 315, 6, 263.2, 70);
            doc.addImage(logo_saas, 'png', 25, 6, 40 * 2.5, 30 * 2.5);

            // NRO.DIAGNOSTICO
            doc.setFontSize(12);
            doc.setFontType("bold");
            doc.text(220, 85, `DIAGNOSTICO DIAG-SSS-DCI-${this.detalleDiagnostico.id_correlativo}-${añoActual}`, { align: 'center' });

            // Separador horizontal
            doc.setLineWidth(0.5);
            doc.line(25, 90, 575, 90);

            // Datos del servicio vehiculo1
            doc.setFontSize(9);
            doc.setFontType('normal');
            doc.writeText(450, 105, wFecha, { align: 'left', width: 40 });
            doc.writeText(30, 105, 'Estimado(a):', { align: 'left', width: 14 });
            doc.setFontType('bold');
            doc.writeText(30, 115, `${this.detalleDiagnostico.nombre}`, { align: 'left', width: 14 });
            doc.writeText(30, 125, `${this.detalleDiagnostico.dir_funcional}`, { align: 'left', width: 14 });
            doc.writeText(30, 135, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 14 });
            doc.writeText(30, 145, 'PRESIDENCIA DE LA REPUBLICA', { align: 'left', width: 14 });

            const textoCompleto = `Por este medio se le da a conocer que el técnico |${this.detalleDiagnostico.tecnico}|, perteneciente a la DIRECCIÓN DE COMUNICACIONES E INFORMÁTICA, ha evaluado el bien: |${this.detalleDiagnostico.bien_descripcion_completa}|.`;
            var actual = this.printCharacters(doc, textoCompleto, 160, 30, 540);

            // Establecer el ancho de línea más delgado
            doc.setLineWidth(0.01);

            // // Dibujar el rectángulo alrededor de los datos
            doc.setFillColor(255, 0, 0);  // Relleno rojo (valores RGB)
            doc.rect(75, actual, 450, 60);

            // // Dibujar líneas entre secciones
            doc.line(75, actual + 15, 525, actual + 15); // línea después de 'TÉCNICO RESPONSABLE'
            doc.line(75, actual + 30, 525, actual + 30); // línea después de 'ENCARGADO DEL EQUIPO'
            doc.line(75, actual + 45, 525, actual + 45); // línea después de 'FECHA DE IMPRESIÓN'

            // // Resto de tu código para agregar texto
            doc.setFontType('normal');
            doc.writeText(105, actual + 11, 'TÉCNICO RESPONSABLE:', { align: 'left', width: 14 });
            doc.writeText(105, actual + 26, 'ENCARGADO DEL EQUIPO:', { align: 'left', width: 14 });
            doc.writeText(105, actual + 41, 'FECHA DE IMPRESIÓN:', { align: 'left', width: 14 });
            doc.writeText(105, actual + 56, 'NÚMERO DE BIEN:', { align: 'left', width: 14 });

            doc.setFontType('bold');
            doc.writeText(235, actual + 11, this.detalleDiagnostico.tecnico + ' - ' + this.detalleDiagnostico.id_tecnico, { align: 'left', width: 40 });
            doc.writeText(235, actual + 26, this.detalleDiagnostico.nombre + ' - ' + this.detalleDiagnostico.id_persona_solicita, { align: 'left', width: 40 });
            doc.writeText(235, actual + 41, wFecha, { align: 'left', width: 40 });
            doc.writeText(235, actual + 56, this.detalleDiagnostico.bien_sicoin_code, { align: 'left', width: 40 });

            // // Restaurar el ancho de línea predeterminado
            doc.setLineWidth(0.5);
            doc.line(25, actual + 75, 575, actual + 75);

            //DESCRIPCIÓN DEL PROBLEMA
            doc.text('DESCRIPCIÓN DEL PROBLEMA: ', 30, actual + 90);
            doc.setFontSize(9);
            doc.setFontType('normal');
            let descripcionSinHtml = stripHtml(this.detalleDiagnostico.descripcion);
            descripcionFragments = this.printCharacters(doc, descripcionSinHtml, actual + 100, 30, 540);

            // EVALUACIÓN
            doc.setFontType('bold');
            doc.text('EVALUACIÓN DEL TÉCNICO: ', 30, actual + 160);
            doc.setFontSize(5);
            doc.setFontType('normal');
            doc.fromHTML(`${estilos}${this.detalleDiagnostico.evaluacion}</div>`, 30, actual + 160, {
                'width': 535,
                align: "justify"
            });

            // RECOMENDACIÓN
            doc.setFontType('bold');
            doc.setFontSize(9);
            doc.text('RECOMENDACIÓN DEL TÉCNICO: ', 30, actual + 250);
            doc.setFontSize(5);
            doc.setFontType('normal');
            doc.fromHTML(`${estilos}${this.detalleDiagnostico.recomendacion}</div>`, 30, actual + 250, {
                'width': 535,
                align: "justify"
            });

            // // Firmas
            doc.setFontSize(10);
            doc.setLineWidth(0.5);
            doc.writeText(130, 655, 'Técnico');
            doc.line(60, 640, 240, 640); // Línea horizontal para la firma del Técnico
            doc.writeText(425, 655, 'Vo.Bo');
            doc.line(350, 640, 530, 640); // Línea horizontal para la firma del empleado
            doc.writeText(125, 715, 'Recibido Por');
            doc.line(60, 700, 240, 700); // Línea horizontal para la firma del taller

            // //Informacion extra
            doc.setFontSize(7);
            doc.writeText(300, 750, 'Reporte Generado Herramientas Administrativas - Módulo Control de Tareas. Sección Diagnósticos', { align: 'center', width: 30 });
            doc.writeText(300, 760, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN". GUATEMALA, GUATEMALA', { align: 'center', width: 30 });
            doc.writeText(300, 770, 'PBX: 2327-6000 FAX: 2327-6090', { align: 'center', width: 30 });
            doc.writeText(300, 780, 'https://www.saas.gob.gt', { align: 'center', width: 30 });

            // Obtener el iframe
            var pdfFrame = document.getElementById('pdfContainer');

            // Resto de tu código
            var pdfContent = doc.output('blob'); // Obtener el contenido como un Blob
            var pdfUrl = URL.createObjectURL(pdfContent);
            pdfFrame.src = pdfUrl;

            pdfFrame.onload = function () {
                pdfFrame.onbeforeprint = () => {
                    console.log('Iniciando impresión');
                }

                pdfFrame.onafterprint = () => {
                    console.log('Impresión finalizada');
                }
                pdfFrame.contentWindow.print();
            };
            setTimeout(() => {
                if (!fecha) {
                    this.afirmarImpresion()
                }
            }, 1000);
        },
        printCharacters: function (doc, text, startY, startX, width) {
            const startXCached = startX;
            const boldStr = 'bold';
            const normalStr = 'normal';
            const fontSize = 9;//doc.getFontSize();
            //const lineSpacing = doc.getLineHeightFactor() + fontSize;
            const lineSpacing = 10.5;//doc.setLineHeightFactor(1.5);

            let textObject = this.getTextRows(doc, text, width);

            console.log(textObject);

            textObject.map((row, rowPosition) => {
                //console.log('Fila' + rowPosition);
                Object.entries(row.chars).map(([key, value]) => {
                    doc.setFontType(value.bold ? boldStr : normalStr);

                    doc.text(value.char, startX, startY);

                    if (value.char == ' ' && rowPosition < textObject.length - 1) {
                        startX += row.blankSpacing;
                    } else {
                        startX += doc.getStringUnitWidth(value.char) * fontSize;
                    }
                });
                startX = startXCached;
                startY += lineSpacing;
            });

            return startY;

        },
        getTextRows: function (doc, inputValue, width) {
            const regex = /(\*{2})+/g; // all "**" words
            const textWithoutBoldMarks = inputValue.replace(regex, '');
            const boldStr = 'bold';
            const normalStr = 'normal';
            const fontSize = 9;//doc.getFontSize();

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

                return { blankSpacing: blankSpacing, chars: { ...chars } };
            });

            return textRows;
        },

        afirmarImpresion: function () {
            Swal.fire({
                title: `<strong>¿Afirma impresión?</strong>`,
                text: `Si usted imprimio el diagóstico #:${this.idDiagnostico} afirme la impresión`,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0097FF',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Afirma!'
            }).then((result) => {
                if (result.value) {
                    const formData = new FormData();
                    formData.append('opcion', 4);
                    formData.append('bien', this.idBien);
                    formData.append('id', this.idDiagnostico);

                    axios.post('tickets/diagnosticos/model/detalleDiagnostico.php', formData)
                        .then((response) => {
                            console.log(response.data);
                            if (response.data.id == 1) {
                                Swal.fire({
                                    type: 'success',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                this.getDetalleDiagnostico(this.idDiagnostico);
                                diagnosticosList.cargarTablaDiagnosticos(6);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        })
                        .catch((error) => {
                            // Manejar errores si los hay
                            console.error('Error al subir el archivo', error);
                        });

                }
            })
        },
        getDetalleDiagnostico: function (id) {
            axios.get(`tickets/diagnosticos/model/detalleDiagnostico.php`, {
                params: {
                    opcion: 1,
                    id: id
                }
            }).then(response => {
                console.log(response.data);
                this.tipoTabla = 1
                this.detalleDiagnostico = response.data;
                this.idPersona = this.detalleDiagnostico.id_persona_solicita
                this.idBien = this.detalleDiagnostico.bien_id
                this.evento.$emit('cambiar-foto', this.idPersona);
            }).catch(error => {
                console.error(error);
            });

            axios.get(`tickets/diagnosticos/model/detalleDiagnostico.php`, {
                params: {
                    opcion: 3,
                    id: id
                }
            }).then(response => {
                if (response.data.pdf) {
                    this.pdf = `data:application/pdf;base64,${response.data.pdf}`
                    console.log(this.pdf);
                } else {
                    this.pdf = ''
                }
            }).catch(error => {
                console.error(error);
            });
        },
        setAnularDiagnostico: function () {
            Swal.fire({
                title: `<strong>¿Anular Diagnostico?</strong>`,
                html: `
                <div class="form-group">
                    <label for="motivoAnulacion">Se Anulara el diagnostico #:${this.idDiagnostico} con la información agregada</br>
                    Motivo de anulación:</label>
                    <textarea id="motivoAnulacion" class="form-control" required></textarea>
                </div>
            `,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF003E',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Anular!'
            }).then((result) => {
                if (result.value) {
                    const motivoAnulacion = document.getElementById('motivoAnulacion').value;
                    if (motivoAnulacion.trim() !== '') { // Verificar que el campo no esté vacío
                        axios.post('tickets/diagnosticos/model/diagnosticosList.php', {
                            opcion: 3,
                            motivoAnulacion: motivoAnulacion,
                            id: this.idDiagnostico
                        })
                            .then(function (response) {
                                console.log(response.data);
                                if (response.data.id == 1) {
                                    Swal.fire({
                                        type: 'success',
                                        title: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                    $('#modal-remoto-lgg2').modal('hide');
                                    diagnosticosList.cargarTablaDiagnosticos(6)

                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                }

                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Campo obligatorio',
                            text: 'Por favor ingrese el motivo de anulación.',
                            confirmButtonColor: '#28a745'
                        });
                    }
                }
            })
        },
        subirArchivo(id) {
            Swal.fire({
                title: 'Subir archivo PDF',
                input: 'file',
                inputAttributes: {
                    accept: 'application/pdf',
                },
                showCancelButton: true,
                confirmButtonText: 'Subir',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: (file) => {
                    if (!file) {
                        Swal.showValidationMessage('Debes seleccionar un archivo');
                        return false;
                    }
                    return new Promise((resolve) => {
                        const reader = new FileReader();

                        reader.onload = () => {
                            const base64String = reader.result.split(',')[1];
                            resolve(base64String);
                        };

                        reader.readAsDataURL(file);
                    });
                },
            }).then((result) => {
                if (result.value) {
                    const formData = new FormData();
                    formData.append('opcion', 2);
                    formData.append('archivo', result.value);
                    formData.append('id', id);

                    axios.post('tickets/diagnosticos/model/detalleDiagnostico.php', formData)
                        .then((response) => {
                            console.log(response.data);
                            if (response.data.id == 1) {
                                Swal.fire({
                                    type: 'success',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                $('#modal-remoto-lgg2').modal('hide');
                                diagnosticosList.cargarTablaDiagnosticos(6);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        })
                        .catch((error) => {
                            // Manejar errores si los hay
                            console.error('Error al subir el archivo', error);
                        });
                }
            });
        },
        getBitacoraImpresiones: function (id) {
            axios.get(`tickets/diagnosticos/model/detalleDiagnostico.php`, {
                params: {
                    opcion: 5,
                    id: id
                }
            }).then(response => {
                console.log(response.data);
                this.bitacoraImpresiones = response.data;
                if (this.bitacoraImpresiones) {
                    this.tipoTabla = 2
                } else {
                    this.tipoTabla = 1
                }
            }).catch(error => {
                console.error(error);
            });
        },

    },

});