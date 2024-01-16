let tabla_empleado, tabla_horario, tabla_permisosf;
var offices = ['DESPACHO DEL SECRETARIO'
    , 'SUBSECRETARIA ADMINISTRATIVA'
    , 'SUBSECRETARIA DE SEGURIDAD'
    , 'ASESORIA JURIDICA'
    , 'AUDITORIA INTERNA'
    , 'UNIDAD DE INSPECTORIA'
    , 'DIRECCION ADMINISTRATIVA Y FINANCIERA'
    , 'DIRECCION DE ASUNTOS INTERNOS'
    , 'DIRECCION DE COMUNICACIONES E INFORMATICA'
    , 'DIRECCION DE INFORMACION'
    , 'DIRECCION DE RECURSOS HUMANOS'
    , 'DIRECCION DE RESIDENCIAS'
    , 'DIRECCION DE SEGURIDAD'
    , 'SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES'
    , 'S/D'
];
function get_fotografia(data, row, column) {
    let id_persona = data.id;
    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_fotografia.php",
        dataType: 'html',
        data: { id_persona },
        success: function (foto) {
            $(`td:eq(0)`, row).html(foto);
        }
    });
}

var datableEmpleado = function () {
    // Datatables listado de usuarios

    var initDataTablePermisos = function () {
        tabla_permisosf = $('#tb_permisos').DataTable({
            pageLength: 10,
            bProcessing: true,
            // paging: true,
            order: [[1, "desc"]],
            language: {
                emptyTable: "No hay boletas disponibles.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id = 3;
                    d.month = $('select#month_p option:checked').val();
                    d.year = $('select#year_p option:checked').val();
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'nro_boleta' },
                { "class": "text-center", mData: 'persona' },
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
                }
            ],

            initComplete: function () {
            },
        });
    };

    var initDataTableEmpleados = function () {
        tabla_empleado = $('#tb_empleados').DataTable({
            pageLength: 10,
            destroy: true,
            bProcessing: true,
            ordering: true,
            order: [[6, "desc"]],
            language: {
                emptyTable: "No hay empleados",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/empleados/get_empleados.php",
                type: "POST",
                data: function (d) {
                    d.month = $('select#month_1 option:checked').val();
                    d.year = $('select#year_1 option:checked').val();
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-left", mData: 'id' },
                { "class": "text-center", mData: 'empleado' },
                { "class": "text-center", mData: 'dnominal' },
                { "class": "text-center", mData: 'pnominal' },
                { "class": "text-center", mData: 'dfuncional' },
                { "class": "text-center", mData: 'pfuncional' },
                { "class": "text-center", mData: 'horario' },
                { "class": "text-center", mData: 'tarde' },
                { "class": "text-center", mData: 'ttarde' },
                { "class": "text-center", mData: 'accion' }
            ],
            buttons: [
                {
                    text: '<span class="btn btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/general_horario.php?month=' + $('select#month_1 option:checked').val() + '&year=' + $('select#year_1 option:checked').val() + '&dir=' + $('select#filter1 option:checked').val() + '"><i class="far fa-clock" style="color:black;"></i> Reporte General </span>',
                },
                {
                    extend: 'excel',
                    text: '<span class="btn btn-soft-info"><i class="far fa-file-excel" style="color:green;"></i> Exportar Listado </span>',
                    title: 'Listado General - ' + $('select#month_1 option:checked').text(),
                    filename: 'Listado General - ' + $('select#month_1 option:checked').text(),
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
                    },
                },
                {
                    extend: 'pdfHtml5',
                    text: '<span class="btn btn-soft-info"><i class="far fa-file-pdf" style="color:red;"></i> Exportar Listado </span>',
                    download: ['donwload', 'open'],
                    orientation: 'landscape',
                    title: 'Listado General - ' + $('select#month_1 option:checked').text(),
                    filename: 'Listado General - ' + $('select#month_1 option:checked').text(),
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7],
                    },
                    customize: function (doc) {
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';
                        // doc.content[1].table.widths = ['10%', '10%', '10%', '15%', '10%', '15%', '10%', '20%'];
                        doc.content.splice(0, 1);
                        doc.pageMargins = [20, 100, 20, 20];
                        doc['header'] = (function () {
                            return {
                                columns: [
                                    {
                                        image: baner,
                                        width: 300,
                                        margin: [-50, -10]
                                    },
                                    {
                                        alignment: 'right',
                                        bold: true,
                                        text: 'LISTADO GENERAL',
                                        fontSize: 13,
                                        margin: [10, 20]
                                    },
                                    {
                                        alignment: 'left',
                                        bold: true,
                                        text: 'DEL: ' + max_Date(String('01/' + $('select#month_1 option:checked').val() + '/' + $('select#year_1 option:checked').val())) + ' AL: ' + min_Date($('select#month_1 option:checked').val(), $('select#year_1 option:checked').val()),
                                        fontSize: 10,
                                        margin: [-140, 40]
                                    },
                                    {
                                        alignment: 'left',
                                        text: 'FECHA: ' + formatDate1(Date()) + '\n HORA: ' + formatDate2(Date()),
                                        fontSize: 8,
                                        margin: [40, 0]
                                    },

                                ],
                                margin: 20
                            }
                        });

                    }
                }
            ],
            initComplete: function () {
                $("#filter1").html("");
                $("#filter2").html("");
                var column = this.api().column(4);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter1')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });
                offices.forEach(function (d) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                })
                var column1 = this.api().column(8);
                var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter2')
                    .on('change', function () {
                        var val1 = $(this).val();
                        column1.search(val1).draw()
                    });
                var offices1 = [1, 2, 3];
                var labels = ["", "1 Día Tarde", "2 Días Tarde", "3 o más Días Tarde"];
                offices1.sort();
                offices1.forEach(function (d) {
                    select1.append('<option value="' + d + '">' + labels[d] + '</option>');
                })
            },
            rowCallback: function (row, data) {
                get_fotografia(data, row, 0);
            },
            // "fnRowCallback": function (row, data) {
            //     get_tardes(data, row, 0);
            // },
            columnDefs: [
                { targets: [8], visible: false },
                // {
                //     'targets': [6],
                //     'searchable': false,
                //     'orderable': false,
                //     'className': 'dt-body-center',
                //     render: function (data, type, row, meta) {
                //         return get_tardes(row.id);
                //     }
                // }
            ]
        });
    };
    var initDataTableAllEmpleados = function () {
        tabla_empleado = $('#tb_empleados').DataTable({
            pageLength: 10,
            destroy: true,
            bProcessing: true,
            ordering: true,
            order: [[0, "asc"]],
            language: {
                emptyTable: "No hay empleados",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/empleados/get_empleados_all.php",
                type: "POST",
                data: function (d) {
                    d.month = $('select#month_1 option:checked').val();
                    d.year = $('select#year_1 option:checked').val();
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-left", mData: 'id' },
                { "class": "text-center", mData: 'empleado' },
                { "class": "text-center", mData: 'dnominal' },
                { "class": "text-center", mData: 'pnominal' },
                { "class": "text-center", mData: 'dfuncional' },
                { "class": "text-center", mData: 'pfuncional' },
                { "class": "text-center", mData: 'horario' },
                { "class": "text-center", mData: 'tarde' },
                { "class": "text-center", mData: 'ttarde' },
                { "class": "text-center", mData: 'accion' }
            ],
            buttons: [
                // {
                //     text: '<span class="btn btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="horarios/php/front/empleados/general_horario.php?month=' + $('select#month_1 option:checked').val() + '&year=' + $('select#year_1 option:checked').val() + '&dir=' + $('select#filter1 option:checked').val() + '"><i class="far fa-clock"></i> Generar Reporte </span>',
                // },
                // {
                //     extend: 'excel',
                //     text: '<span class="btn btn-soft-info"><i class="far fa-file-excel"></i> Exportar Listado </span>',
                //     title: 'Horario General - ' + $('#filter1 option:checked').text(),
                // },
            ],
            initComplete: function () {
                $("#filter1").html("");
                $("#filter2").html("");
                var column = this.api().column(4);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter1')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });

                offices.forEach(function (d) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                })

            },
            rowCallback: function (row, data) {
                get_fotografia(data, row, 0);
            },
            // "fnRowCallback": function (row, data) {
            //     get_tardes(data, row, 0);
            // },
            columnDefs: [
                { targets: [7, 8], visible: false },
                // {
                //     'targets': [6],
                //     'searchable': false,
                //     'orderable': false,
                //     'className': 'dt-body-center',
                //     render: function (data, type, row, meta) {
                //         return get_tardes(row.id);
                //     }
                // }
            ]
        });
    };
    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;
        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom: "<'row'<'col-sm-3'l><'col-sm-5'B><'col-sm-4'f>>" +
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
        init: function (opt) {
            var a = new Date();
            var month = a.getMonth() + 1;
            $("#month_1").val(month);
            $(`#month_1 option[value="${month}"]`).attr("selected", true);
            $(`#year_1 option[value="${year}"]`).attr("selected", true);
            bsDataTables();

            if (opt == 1) {
                initDataTableEmpleados();
            } else if (opt == 0) {
                initDataTableAllEmpleados();
            } else {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1); //January is 0!
                var yyyy = today.getFullYear();
                $(`#month_p option[value="${mm}"]`).attr("selected", true);
                $(`#year_p option[value="${yyyy}"]`).attr("selected", true);
                initDataTablePermisos();
            }
        }
    };
}();
function reload_feriados() {
    datableEmpleado.init(2);
}
function reload_detalle() {
    datableEmpleado.init(1);
}
function reload_empleados() {
    datableEmpleado.init(0);
}
function reload_detalle_1() {
    tabla_empleado.ajax.reload();;
}
function refresh_boletas() {
    tabla_permisosf.ajax.reload();
}
// Initialize when page loads
jQuery(function () { datableEmpleado.init(0); });