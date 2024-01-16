const EventBus = new Vue();

let diagnosticosList = new Vue({
    el: '#appDiagnosticos',
    data: {
        tituloModulo: 'Listado Diagnosticos',
        tablaDiagnosticos: '',
        estado: 6,
        horaActual: ''
    },

    mounted: function () {
        this.cargarTablaDiagnosticos(this.estado);
        this.baseTables();
    },
    methods: {
        recargarClasesDiagnosticos: function (clase) {
            console.log("Actualizado con estado: " + this.estado)
            this.recargarDiagnosticos()
            setTimeout(() => {
                $('.btn-solt').removeClass('active');
                $(`.${clase}`).addClass('active');
            }, "100");

        },

        recargarDiagnosticos: function () {
            this.cargarTablaDiagnosticos(this.estado)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                type: 'success',
                title: 'Actualizado con exito'
            })
        },

        cargarTablaDiagnosticos: function (id) {
            let thes = this;

            axios.get(`tickets/diagnosticos/model/diagnosticosList.php`, {
                params: {
                    opcion: 1,
                    filtro: id
                }
            }).then(response => {
                console.log(response.data);
                this.tablaDiagnosticosData = response.data;
                // Clear any previous DataTable instance
                if ($.fn.DataTable.isDataTable("#tblDiagnosticos")) {
                    $("#tblDiagnosticos").DataTable().destroy();
                }

                // Initialize DataTables only if data is available
                if (response.data) {
                    // DataTable initialization
                    this.tablaDiagnosticos = $("#tblDiagnosticos").DataTable({
                        "ordering": false,
                        "pageLength": 10,
                        "bProcessing": true,
                        "lengthChange": true,
                        "paging": true,
                        "info": true,
                        select: false,
                        scrollX: true,
                        scrollY: '50vh',
                        language: {
                            emptyTable: "No hay solicitudes de Diagnosticos para mostrar",
                            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                        },
                        "aoColumns": [
                            {
                                "class": "text-center",
                                data: 'id_diagnostico',
                                render: function (data, type, row) {
                                    let encabezado;
                                    if (row.id_estado == 3) {
                                        return `<button class="btn btn-sm btn-secondary detalle" data-id="${data}">
                                        <i class="fa-solid fa-hashtag"></i> ${data}
                                    </button>`;
                                    } else if (row.id_estado == 6) {
                                        return `<button class="btn btn-sm btn-info detalle" data-id="${data}">
                                        <i class="fa-solid fa-hashtag"></i> ${data}
                                    </button>`;
                                    } else if (row.id_estado == 7) {
                                        return `<button class="btn btn-sm btn-success detalle" data-id="${data}">
                                        <i class="fa-solid fa-hashtag"></i> ${data}
                                    </button>`;
                                    } else if (row.id_estado == 4) {
                                        return `<button class="btn btn-sm btn-danger detalle" data-id="${data}">
                                        <i class="fa-solid fa-hashtag"></i> ${data}
                                    </button>`;
                                    }
                                },
                            },
                            {
                                "class": "text-center",
                                data: 'descripcion',
                                render: function (data, type, row) {
                                    return `<div class="scrollmenu">${data}</div>`;
                                },
                            },

                            { "class": "text-center", mData: 'solicitante' },
                            {
                                "class": "text-center",
                                data: 'bien_descripcion',
                                render: function (data, type, row) {
                                    return `<div class="scrollmenu">${data}</div>`;
                                },
                            },
                            {
                                "class": "text-center",
                                data: 'bien_sicoin_code',
                                render: function (data, type, row) {
                                    return data;
                                },
                            },
                            { "class": "text-center", mData: 'tecnico' },
                            { "class": "text-center", mData: 'fecha_finalizado' },
                            {
                                "class": "text-center",
                                data: 'id_estado',
                                render: function (data, type, row) {
                                    if (data == 3) {
                                        return `<a href="#" class="badge badge-secondary text-white py-1" data-id="${row.id_diagnostico}"">${row.estado} <i class="fa-solid fa-check-to-slot mx-1"></i></a>
                                        <div class="my-1 progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-secondary progress-bar-animated progress-bar-sm" role="progressbar" aria-valuenow="85" aria-valuemax="100" style="width: 85%"></div>
                                        </div>`;
                                    } else if (data == 6) {
                                        return `<a href="#" class="badge badge-info text-white py-1" data-id="${row.id_diagnostico}"">${row.estado}<i class="fa-solid fa-screwdriver-wrench mx-1"></i></a>
                                        <div class="my-1 progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress-bar-sm" role="progressbar" aria-valuenow="50" aria-valuemax="100" style="width: 50%"></div>
                                        </div>`;
                                    } else if (data == 7) {
                                        return `<a href="#" class="badge badge-success text-white py-1" data-id="${row.id_diagnostico}"">${row.estado}<i class="fa-solid fa-file-check mx-1"></i></a>
                                        <div class="my-1 progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-success progress-bar-animated progress-bar-sm" role="progressbar" aria-valuenow="100" aria-valuemax="100" style="width: 100%"></div>
                                        </div>`;
                                    } else if (data == 4) {
                                        return `<a href="#" class="badge badge-danger text-white py-1" data-id="${row.id_diagnostico}"">${row.estado} <i class="fa-sharp fa-solid fa-circle-x mx-1"></i></a>
                                        <div class="my-1 progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-danger progress-bar-animated progress-bar-sm" role="progressbar" aria-valuenow="100" aria-valuemax="100" style="width: 100%"></div>
                                        </div>`;
                                    }
                                },
                            },
                        ],
                        buttons: [
                            {
                                text: 'Todos <i class="fa fa-server" aria-hidden="true"></i>',
                                className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt ',
                                action: function (e, dt, node, config) {
                                    thes.estado = 1
                                    thes.recargarClasesDiagnosticos('btn-st-1')
                                }
                            },
                            {
                                text: 'Pendientes <i class="fa fa-sync"></i>',
                                className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt active',
                                action: function (e, dt, node, config) {
                                    thes.estado = 6
                                    thes.recargarClasesDiagnosticos('btn-st-2')
                                },
                            },
                            {
                                text: 'Finalizados <i class="fa fa-check-circle"></i>',
                                className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
                                action: function (e, dt, node, config) {
                                    thes.estado = 3
                                    thes.recargarClasesDiagnosticos('btn-st-3')
                                }
                            },
                            {
                                text: 'Anulados <i class="fa fa-times-circle"></i>',
                                className: 'btn btn-personalizado btn-sm btn-st-4 btn-solt',
                                action: function (e, dt, node, config) {
                                    thes.estado = 4
                                    thes.recargarClasesDiagnosticos('btn-st-4')
                                }
                            },
                        ],
                        data: response.data,
                    });
                }
            }).catch(error => {
                console.error(error);
            });
        },

        baseTables: function () {
            let thes = this;
            // DataTables Bootstrap integration
            this.bsDataTables = jQuery.fn.dataTable;
            // Set the defaults for DataTables init
            jQuery.extend(true, this.bsDataTables.defaults, {
                dom:
                    "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-6'i><'col-sm-6'p>>",
                buttons: [
                    'csv', 'excel', 'pdf'
                ],
                renderer: 'bootstrap',
                oLanguage: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar:",
                    sUrl: "",
                    sInfoThousands: ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "<i class='fa fa-chevron-right'></i>",
                        sPrevious: "<i class='fa fa-chevron-left'></i>"
                    },
                    oAria: {
                        sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
            // Default class modification
            jQuery.extend(this.bsDataTables.ext.classes, {
                //sWrapper: " dt-bootstrap",
                sFilterInput: "form-control form-control-sm",
                sLengthSelect: "form-control form-control-sm"
            });
            // TableTools Bootstrap compatibility - Required TableTools 2.1+
            if (this.bsDataTables.TableTools) {
                // Set the classes that TableTools uses to something suitable for Bootstrap
                jQuery.extend(true, this.bsDataTables.TableTools.classes, {
                    "container": "DTTT btn-group",
                    "buttons": {
                        "normal": "btn btn-default",
                        "disabled": "disabled"
                    },
                    "collection": {
                        "container": "DTTT_dropdown dropdown-menu",
                        "buttons": {
                            "normal": "",
                            "disabled": "disabled"
                        }
                    },
                    "print": {
                        "info": "DTTT_print_info"
                    },
                    "select": {

                        "row": "active"
                    }
                });
                // Have the collection use a bootstrap compatible drop down
                jQuery.extend(true, this.bsDataTables.TableTools.DEFAULTS.oTags, {
                    "collection": {
                        "container": "ul",
                        "button": "li",
                        "liner": "a"
                    }
                });
            }

            $('#tblDiagnosticos').on('click', '.badge-info', function () {
                let id = $(this).data('id');
                thes.setFinalizarDiagnostico(id)
            });

            $('#tblDiagnosticos').on('click', '.detalle', function () {
                let id = $(this).data('id');
                thes.getDetalleDiagnostico(id)
            });

            $('#tblDiagnosticos').on('click', '.badge-secondary', function () {
                let id = $(this).data('id');
                thes.setEntregaDiagnostico(id);
            });
        },
        getDetalleDiagnostico: function (id) {
            let imgModal = $('#modal-remoto-lgg2');
            let imgModalBody = imgModal.find('.modal-content');
            let thisUrl = 'tickets/diagnosticos/views/modalDetalleDiagnostico.php';
            $.ajax({
                type: "POST",
                url: thisUrl,
                dataType: 'html',
                data: {
                    id: id,
                },
                beforeSend: function () {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                },
                success: function (data) {
                    imgModalBody.html(data);
                    $('#modal-remoto-lgg2').modal('hide');
                }
            });
        },
        setNuevoDiagnostico: function () {
            let imgModal = $('#modal-remoto-lgg2');
            let imgModalBody = imgModal.find('.modal-content');
            let thisUrl = 'tickets/diagnosticos/views/modalNuevoDiagnostico.php';
            $.ajax({
                type: "POST",
                url: thisUrl,
                dataType: 'html',
                beforeSend: function () {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                },
                success: function (data) {
                    imgModalBody.html(data);
                    $('#modal-remoto-lg').modal('hide');
                }
            });
        },

        setFinalizarDiagnostico: function (id) {
            let imgModal = $('#modal-remoto-lg');
            let imgModalBody = imgModal.find('.modal-content');
            let thisUrl = 'tickets/diagnosticos/views/modalFinalizarDiagnostico.php';
            $.ajax({
                type: "POST",
                url: thisUrl,
                dataType: 'html',
                data: {
                    id: id,
                },
                beforeSend: function () {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                },
                success: function (data) {
                    imgModalBody.html(data);
                    $('#modal-remoto-lg').modal('hide');
                }
            });
        },

        setEntregaDiagnostico: function (id) {
            let thes = this
            Swal.fire({
                title: '<strong>¿Se entrego el Diagnostico?</strong>',
                text: `Se actualizara en el sistema que el diagnostico ya fue entregado`,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Entregar!'
            }).then((result) => {
                if (result.value) {
                    axios.post('tickets/diagnosticos/model/diagnosticosList.php', {
                        opcion: 5,
                        id: id
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
                                thes.cargarTablaDiagnosticos(thes.estado)
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
        },
    }
});

export default diagnosticosList;
