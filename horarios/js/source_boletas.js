
let tabla_empleado, tabla_horario;

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
    var initDataTableEmpleados = function () {
        tabla_empleado = $('#tb_pendientes').DataTable({
            pageLength: 10,
            bProcessing: true,
            language: {
                emptyTable: "No hay empleados",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_empleados.php",
                type: "POST",
                data: {
                    tipo: function () { return $('#id_1_0').val() },
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'empleado' },
                { "class": "text-center", mData: 'dfuncional' },
                { "class": "text-center", mData: 'pfuncional' },
                { "class": "text-center", mData: 'pendientes' },
                { "class": "text-center", mData: 'utilizados' },
                { "class": "text-center", mData: 'accion' }
            ],
            buttons: [
                // {
                //     //   text: '<span data-toggle="modal" data-target="#modal-remoto-lg">Generar Horarios <i class="fa fa-check"></i></span>',
                //     //text: '<span data-toggle="modal" data-target="#modal-remoto-lg" >Generar Horarios</span>',
                //     text: 'Generar Reporte <i class="fa fa-clock"></i>',
                //     className: 'btn btn-soft-info',
                //     action: function () {
                //         //let id_persona = 8437;
                //         // let month = $('select#month_1 option:checked').val();
                //         let year = $('select#year_1 option:checked').val();
                //         let dir = $('#filter1 option:checked').text();
                //         $.ajax({
                //             type: "POST",
                //             url: "horarios/php/front/empleados/empleado_general.php",
                //             dataType: 'html',
                //             data: { year: year, dir:dir},
                //             success: function (response) {
                //                 // Add response in Modal body
                //                 $('.modal-content').html(response);
                //                 // Display Modal
                //                 $('#modal-remoto').modal('show');
                //             }
                //         });
                //     }
                // }
            ],
            initComplete: function () {
                var column = this.api().column(4);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#periodopendiente')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });

                var offices = ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023'];
                // column.data().toArray().forEach(function (s) {
                //     s = s.split(',');
                //     s.forEach(function (d) {
                //         if (!~offices.indexOf(d)) {
                //             offices.push(d);
                //         }
                //     })
                // })
                offices.sort();
                offices.forEach(function (d) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                })


                var column1 = this.api().column(2);
                var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#periododir')
                    .on('change', function () {
                        var val1 = $(this).val();
                        column1.search(val1).draw()
                    });

                var offices1 = [];
                column1.data().toArray().forEach(function (s) {
                    s = s.split(',');
                    s.forEach(function (d) {
                        if (!~offices1.indexOf(d)) {
                            offices1.push(d);
                        }
                    })
                })
                // console.log(offices1);
                offices1.sort();
                offices1.forEach(function (d) {
                    select1.append('<option value="' + d + '">' + d + '</option>');
                })
            },
            buttons: [
                {
                    text: 'Activos <i class="fa fa-sync"></i>',
                    className: 'btn btn-soft-success',
                    action: function (e, dt, node, config) {
                        reload_certs(1);
                    }
                },
                {
                    text: 'Inactivos <i class="fa fa-sync"></i>',
                    className: 'btn btn-soft-danger',
                    action: function (e, dt, node, config) {
                        reload_certs(0);
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                    className: 'btn btn-personalizado outline',
                    filename: 'Reporte de Vacaciones',
                    title: 'Reporte Boletas de Vacaciones',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                    },
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                    className: 'btn btn-personalizado outline',
                    download: ['donwload', 'open'],
                    orientation: 'landscape',
                    filename: 'Reporte de Vacaciones',
                    title: 'Reporte Boletas de Vacaciones',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4],
                    },
                    customize: function (doc) {
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';
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
                                        alignment: 'left',
                                        text: 'SAAS APP - MODULO DE CONTROL DE VACACIONES',
                                        fontSize: 6,
                                        margin: [-295, 50, 0, 0]
                                    },
                                    {
                                        alignment: 'left',
                                        bold: true,
                                        text: 'REPORTE DE VACACIONES',
                                        fontSize: 13,
                                        margin: [-100, 20]
                                    },
                                    {
                                        alignment: 'left',
                                        bold: true,
                                        text: '',
                                        fontSize: 10,
                                        margin: [-220, 40]
                                    },
                                    {
                                        alignment: 'left',
                                        text: 'FECHA: ' + formatDate1(Date()) + '\n HORA: ' + formatDate2(Date()),
                                        fontSize: 8,
                                        margin: [24, 0]
                                    },
                                ],
                                margin: 20
                            }
                        });

                    }
                }

            ],
            rowCallback: function (row, data) {
                get_fotografia(data, row, 0);
            },
            "columnDefs": [{
                "targets": 4,
                "width": "15%"
            }]
        });
    };

    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom:
                "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            buttons: [
                'excel'
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
        init: function () {
            bsDataTables();
            initDataTableEmpleados();

        }
    };
}();

function reload_detalle() {
    tabla_empleado.ajax.reload(null, false);
}
function reload_certs(tipo) {
    $('#id_1_0').val(tipo);
    tabla_empleado.ajax.reload(null, false);
}
// Initialize when page loads
jQuery(function () { datableEmpleado.init(); });
