
var datableEmpleado = function () {

    var initDataTableBoletas = function (id_persona) {
        tabla_boletas = $('#tb_boletas').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            order: [[0, "desc"]],
            language: {
                emptyTable: "No hay boletas disponibles.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.id = 2;
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'nro_boleta' },
                { "class": "text-center", mData: 'fini' },
                { "class": "text-center", mData: 'ffin' },
                { "class": "text-center", mData: 'motivo' },
                { "class": "text-center", mData: 'observaciones' },
                { "class": "text-center", mData: 'autoriza' },
                { "class": "text-center", mData: 'est_des' },
                { "class": "text-center", mData: 'accion' },

            ],
            'columnDefs': [
                { "visible": false, "targets": [0] }
            ],

            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    filename: 'REPORTE BOLETAS',
                    title: 'REPORTE BOLETAS',
                    exportOptions: {
                        // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    download: ['donwload', 'open'],
                    orientation: 'landscape',
                    filename: 'REPORTE BOLETAS',
                    title: 'REPORTE BOLETAS',
                    exportOptions: {
                        // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                    },
                }]
        });
    };

    var initDataTableVacaciones = function (id_persona) {
        tabla_vacaciones = $('#tb_vacaciones1').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            order: [[0, "desc"]],
            language: {
                emptyTable: "No hay vacaciones disponibles.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.id = 1;
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'year' },
                { "class": "text-center", mData: 'dia_asi' },
                { "class": "text-center", mData: 'dia_goz' },
                { "class": "text-center", mData: 'dia_pen' },
                { "class": "text-center", mData: 'dia_est' },
            ],
            initComplete: function () {
                var column = this.api().column(0);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#periodo1')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });

                var offices = [];
                column.data().toArray().forEach(function (s) {
                    s = s.split(',');
                    s.forEach(function (d) {
                        if (!~offices.indexOf(d)) {
                            offices.push(d);
                        }
                    })
                })
                offices.sort();
                offices.forEach(function (d) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                })
            },
            buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                className: 'btn btn-sm btn-personalizado outline',
                filename: 'PERIODOS VACACIONALES',
                title: 'PERIODOS VACACIONALES',
                exportOptions: {
                    // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                className: 'btn btn-sm btn-personalizado outline',
                download: ['donwload', 'open'],
                orientation: 'landscape',
                filename: 'PERIODOS VACACIONALES',
                title: 'PERIODOS VACACIONALES',
                exportOptions: {
                    // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                },
            }]
        });
    };

    var initDataTableEmpleados = function (id_persona) {
        tabla_horario = $('#tb_horario').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            order: [[4, "desc"]],
            language: {
                emptyTable: "No hay registro de vacaciones.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.id = 0;
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'fini' },
                { "class": "text-center", mData: 'ffin' },
                { "class": "text-center", mData: 'fpre' },
                { "class": "text-center", mData: 'year' },
                { "class": "text-center", mData: 'vac_sol' },
                { "class": "text-center", mData: 'vac_pen' },
                { "class": "text-center", mData: 'est_des' },

            ],
            initComplete: function () {
                var column = this.api().column(4);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#periodo')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });
                var offices = [];
                column.data().toArray().forEach(function (s) {
                    s = s.split(',');
                    s.forEach(function (d) {
                        if (!~offices.indexOf(d)) {
                            offices.push(d);
                        }
                    })
                })
                offices.sort();
                offices.forEach(function (d) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                })
            },
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    filename: 'PERIODO VACACIONALES',
                    title: 'PERIODO VACACIONALES',
                    exportOptions: {
                        // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    download: ['donwload', 'open'],
                    orientation: 'landscape',
                    filename: 'PERIODO VACACIONALES',
                    title: 'PERIODO VACACIONALES',
                    exportOptions: {
                        // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                    },
                }
            ],
            'columnDefs': [
                { 'width': 1, 'targets': [4] },
                { 'max-width': 1, 'targets': [4] },
                { "visible": false, "targets": [4] }
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
                api.column(4, { page: 'current' }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr style="background: #3BAFDA;"><td colspan="11" style="color:#F5F7FA;font-size:18px;">' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    };
    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom: "<'row'<'col-sm-4'B><'col-sm-8'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",

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
        jQuery.extend($DataTable.ext.classes, {
            sFilterInput: "form-control",
            sLengthSelect: "form-control"
        });

        // Bootstrap paging button renderer
        $DataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
            var api = new $DataTable.Api(settings);
            var classes = settings.oClasses;
            var lang = settings.oLanguage.oPaginate;
            var btnDisplay, btnClass;

            var attach = function (container, buttons) {
                var i, ien, node, button;
                var clickHandler = function (e) {
                    e.preventDefault();
                    if (!jQuery(e.currentTarget).hasClass('disabled')) {
                        api.page(e.data.action).draw(false);
                    }
                };

                for (i = 0, ien = buttons.length; i < ien; i++) {
                    button = buttons[i];

                    if (jQuery.isArray(button)) {
                        attach(container, button);
                    }
                    else {
                        btnDisplay = '';
                        btnClass = '';

                        switch (button) {
                            case 'ellipsis':
                                btnDisplay = '&hellip;';
                                btnClass = 'disabled';
                                break;

                            case 'first':
                                btnDisplay = lang.sFirst;
                                btnClass = button + (page > 0 ? '' : ' disabled');
                                break;

                            case 'previous':
                                btnDisplay = lang.sPrevious;
                                btnClass = button + (page > 0 ? '' : ' disabled');
                                break;

                            case 'next':
                                btnDisplay = lang.sNext;
                                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                break;

                            case 'last':
                                btnDisplay = lang.sLast;
                                btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                break;

                            default:
                                btnDisplay = button + 1;
                                btnClass = page === button ?
                                    'active' : '';
                                break;
                        }

                        if (btnDisplay) {
                            node = jQuery('<li>', {
                                'class': classes.sPageButton + ' ' + btnClass,
                                'aria-controls': settings.sTableId,
                                'tabindex': settings.iTabIndex,
                                'id': idx === 0 && typeof button === 'string' ?
                                    settings.sTableId + '_' + button :
                                    null
                            })
                                .append(jQuery('<a>', {
                                    'href': '#'
                                })
                                    .html(btnDisplay)
                                )
                                .appendTo(container);

                            settings.oApi._fnBindAction(
                                node, { action: button }, clickHandler
                            );
                        }
                    }
                }
            };

            attach(
                jQuery(host).empty().html('<ul class="pagination"/>').children('ul'),
                buttons
            );
        };

        // TableTools Bootstrap compatibility - Required TableTools 2.1+
        if ($DataTable.TableTools) {
            // Set the classes that TableTools uses to something suitable for Bootstrap
            jQuery.extend(true, $DataTable.TableTools.classes, {
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
            jQuery.extend(true, $DataTable.TableTools.DEFAULTS.oTags, {
                "collection": {
                    "container": "ul",
                    "button": "li",
                    "liner": "a"
                }
            });
        }
    };

    return {
        init: function (id_persona) {




            bsDataTables();

            setTimeout(function () {
              initDataTableEmpleados(id_persona);
              initDataTableVacaciones(id_persona);
            }, 3000);
            setTimeout(function () {
              initDataTableBoletas(id_persona);
            }, 1000);




            // setTimeout(function () {
            //     tabla_horario.ajax.reload();
            // }, 100);
            // setTimeout(function () {
            //     tabla_vacaciones.ajax.reload();
            // }, 100);
            // setTimeout(function () {
            //     tabla_boletas.ajax.reload();
            // }, 100);
            // setTimeout(function () {
            //     tabla_permisos.ajax.reload();
            // }, 100);

        }
    };
}();



function refresh() {
    tabla_horario.ajax.reload();
    tabla_vacaciones.ajax.reload();
    tabla_boletas.ajax.reload();
}
