const EventBus = new Vue();
const FotoEmpleado = httpVueLoader('./tickets/diagnosticos/src/componentes/fotografiaEmpleado.vue');
import diagnosticosList from './diagnosticosList.js';

let diagnosticoDetalle = new Vue({
    el: '#appDetalleDiagnostico',
    data: {
        tituloModulo: 'Detalle Diagnostico',
        detalleDiagnostico: '',
        idDiagnostico: 0,
        idPersona: '',
        evento: ''
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
        getGenerarImpresion() {
            // Función para eliminar etiquetas HTML
            function stripHtmlTags(html) {
                var temporalDivElement = document.createElement("div");
                temporalDivElement.innerHTML = html;
                return temporalDivElement.textContent || temporalDivElement.innerText || "";
            }

            var today = new Date();
            var wFecha = String(today.getDate()).padStart(2, '0') + '/' + String(today.getMonth() + 1).padStart(2, '0') + '/' + today.getFullYear();
            var wHora = String(today.getHours()).padStart(2, '0') + ':' + String(today.getMinutes()).padStart(2, '0') + ':' + String(today.getSeconds()).padStart(2, '0');
            var doc = new jsPDF('p', 'mm');

            // Encabezado
            doc.setFillColor(230, 230, 230);
            doc.rect(5, 5, 205, 33, 'F');
            doc.addImage(logo_saas, 'png', 10, 10, 24, 20);
            doc.setFontSize(12);
            doc.setFontType("bold");
            doc.text(35, 15, 'SECRETARIA DE ASUNTOS', { align: 'center' });
            doc.text(35, 20, 'ADMINISTRATIVOS Y DE SEGURIDAD DE LA', { align: 'center' });
            doc.text(35, 25, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center' });
            doc.setFontSize(8);
            doc.setFontType("normal");
            doc.text(35, 30, 'GUATEMALA, C.A.', { align: 'center' });
            doc.setLineWidth(0.5);
            doc.line(5, 40, 210, 40);

            // NRO.DIAGNOSTICO
            doc.setFontSize(10.5);
            doc.setFontType("bold");
            doc.text(62, 31, 'NRO.DIAGNOSTICO:', { align: 'center' });
            doc.setFontType('normal');
            doc.writeText(66, 36, `DIAG-SSS-DCI-${this.detalleDiagnostico.id_correlativo}-2023`, { align: 'center', width: 30 });

            // HOJA DE DIAGNÓSTICO EVALUACIÓN DE EQUIPO
            doc.setFontType("bold");
            doc.setFontSize(10.5);
            doc.text(145, 13, 'EVALUACIÓN DE EQUIPO', { align: 'center' });
            doc.text(146, 17, 'HOJA DE DIAGNÓSTICO', { align: 'center' });
            doc.setFontType('normal');
            doc.writeText(155, 22.5, 'DIRECCCIÓN DE COMUNICACIONES ', { align: 'center', width: 30 });
            doc.writeText(155, 26.5, 'E INFORMATICA', { align: 'center', width: 30 });
            doc.setFontType("bold");
            doc.writeText(155, 31.5, 'FECHA DE IMPRESIÓN:', { align: 'center', width: 30 });
            doc.setFontType('normal');
            doc.writeText(155, 36, wFecha, { align: 'center', width: 30 });

            // Separador horizontal
            doc.setLineWidth(0.5);
            doc.line(5, 40, 210, 40);

            // Datos del servicio vehiculo1
            doc.setFontSize(8);
            doc.setFontType('normal');
            doc.writeText(9, 45, 'TÉCNICO RESPONSABLE:', { align: 'left', width: 14 });
            doc.writeText(9, 50, 'ENCARGADO DEL EQUIPO:', { align: 'left', width: 14 });
            doc.writeText(9, 55, 'DIRECCIÓN ENCARGADO:', { align: 'left', width: 14 });
            doc.writeText(9, 60, 'NRO.BIEN:', { align: 'left', width: 14 });
            doc.writeText(9, 65, 'DESCRIPCIÓN BIEN:', { align: 'left', width: 14 });

            doc.setFontType('bold');
            doc.writeText(50, 45, this.detalleDiagnostico.tecnico, { align: 'left', width: 40 });
            doc.writeText(50, 50, this.detalleDiagnostico.nombre, { align: 'left', width: 40 });
            doc.writeText(50, 55, this.detalleDiagnostico.dir_funcional, { align: 'left', width: 40 });
            doc.writeText(50, 60, this.detalleDiagnostico.bien_sicoin_code, { align: 'left', width: 40 });

            var descripcionFragments = doc.splitTextToSize(this.detalleDiagnostico.bien_descripcion_completa, 160);
            doc.text(50, 65, descripcionFragments, {
                align: 'justify',
                lineHeightFactor: 1.5
            });

            doc.setLineWidth(0.3);
            doc.line(5, 73, 210, 73);

            // DESCRIPCIÓN DEL PROBLEMA 
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(9, 78, 'DESCRIPCIÓN DEL PROBLEMA: ', { align: 'left', width: 30 });
            doc.setFontSize(8);
            doc.setFontType('normal');
            descripcionFragments = doc.splitTextToSize(this.detalleDiagnostico.descripcion, 200);

            doc.text(9, 82, descripcionFragments, {
                align: 'justify',
                lineHeightFactor: 1.5
            });

            // EVALUACIÓN 
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(9, 115, 'EVALUACIÓN: ', { align: 'left', width: 30 });
            doc.setFontSize(8);
            doc.setFontType('normal');
            descripcionFragments = doc.splitTextToSize(this.detalleDiagnostico.evaluacion, 197);

            doc.text(9, 119, descripcionFragments, {
                align: 'justify',
                lineHeightFactor: 1.5
            });

            // RECOMENDACIÓN 
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(9, 170, 'RECOMENDACIÓN: ', { align: 'left', width: 30 });
            doc.setFontSize(8);
            doc.setFontType('normal');
            descripcionFragments = doc.splitTextToSize(this.detalleDiagnostico.recomendacion, 197);

            doc.text(9, 174, descripcionFragments, {
                align: 'justify',
                lineHeightFactor: 1.5
            });

            // Firmas
            doc.setFontSize(10);
            doc.setLineWidth(0.5);
            doc.writeText(45, 223, 'Técnico');
            doc.line(30, 218, 80, 218); // Línea horizontal para la firma del Técnico
            doc.writeText(155, 223, 'Vo.Bo');
            doc.line(133, 218, 183, 218); // Línea horizontal para la firma del empleado
            doc.writeText(45, 253, 'Recibido Por');
            doc.line(30, 248, 80, 248); // Línea horizontal para la firma del taller


            //Informacion extra
            doc.setFontSize(7);
            doc.writeText(90, 262, 'Reporte Generado Herramientas Administrativas - Módulo Control de Diagnósticos', { align: 'center', width: 30 });
            doc.writeText(90, 268, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN". GUATEMALA, GUATEMALA', { align: 'center', width: 30 });
            doc.writeText(90, 271, 'PBX: 2327-6000 FAX: 2327-6090', { align: 'center', width: 30 });
            doc.writeText(90, 274, 'https://www.saas.gob.gt', { align: 'center', width: 30 });
            var pdfDataUri = doc.output('datauristring');

            // Mostrar el PDF en un visor usando PDF.js
            var pdfContainer = document.getElementById('pdfContainer');
            pdfContainer.innerHTML = '<iframe src="' + pdfDataUri + '" width="100%" height="600px"></iframe>';
        },
        getDetalleDiagnostico: function (id) {
            axios.get(`tickets/diagnosticos/model/getDetalleDiagnostico.php`, {
                params: {
                    opcion: 1,
                    id: id
                }
            }).then(response => {
                console.log(response.data);
                this.detalleDiagnostico = response.data;
                this.idPersona = this.detalleDiagnostico.id_persona_solicita
                this.evento.$emit('cambiar-foto', this.idPersona);

            }).catch(error => {
                console.error(error);
            });
        },
        setAnularDiagnostico: function () {
            Swal.fire({
                title: `<strong>¿Anular Diagnostico?</strong>`,
                text: `Se Anulara el diagnostico #:${this.idDiagnostico} con la información agregada`,
                html: `
                <div class="form-group">
                    <label for="motivoAnulacion">Motivo de anulación</label>
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
        }
    },

});