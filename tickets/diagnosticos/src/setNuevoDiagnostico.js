const ListadoDirecciones = httpVueLoader('./tickets/diagnosticos/src/componentes/direccionesList.vue');
const ListadoEmpleados = httpVueLoader('./tickets/diagnosticos/src/componentes/empleadosList.vue');
const ListadoBienes = httpVueLoader('./tickets/diagnosticos/src/componentes/bienesList.vue');
import diagnosticosList from './diagnosticosList.js';
const EventBus = new Vue();
let setNuevoDiagnostico = new Vue({
    el: '#appDiagnosticoNuevo',
    data: {
        problema: '',
        idBien: '',
        idEmpleado: '',
        evento: '',

    },
    computed: {
        camposCompletos() {
            return (
                this.problema !== '' &&
                this.idBien !== '' &&
                this.idEmpleado !== ''
            );
        },
    },
    mounted: function () {
        this.evento = EventBus;
        this.evento.$on('id-empleado', (nuevoValor) => {
            this.idEmpleado = nuevoValor
        });
        this.evento.$on('id-bien', (nuevoValor) => {
            this.idBien = nuevoValor
        })
    },
    components: {
        'listado-direcciones': ListadoDirecciones,
        'listado-empleados': ListadoEmpleados,
        'listado-bienes': ListadoBienes,
    },

    methods: {
        generarDiagnostico: function () {
            let idEmpleado = $("#empleados").val()
            let idBien = $("#bienes").val()
            let nroBien = $("#informacionNumeroBien").text();

            Swal.fire({
                title: '<strong>¿Desea generar un Nuevo Diagnostico?</strong>',
                text: `Se generar un diagnostico para el bien: ${nroBien}`,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Generar!'
            }).then((result) => {
                if (result.value) {
                    axios.post('tickets/diagnosticos/model/diagnosticosList.php', {
                        opcion: 2,
                        idEmpleado: parseInt(idEmpleado),
                        idBien: parseInt(idBien),
                        problema: this.problema,
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
                }
            })
        }
    }
});