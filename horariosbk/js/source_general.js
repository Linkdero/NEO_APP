
var datableEmpleado = function () {


    var initDataTableEmpleados = function (id_persona) {
        tabla_horario = $('#tb_general').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            ordering: false,
            bFilter: false,
            scrollY: "450px",
            order: [[0, "asc"]],
            language: {
                emptyTable: "No hay horario para el mes",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/empleados/get_horario_general.php",
                type: "POST",
                data: function (d) {
                    d.month = $('select#month option:checked').val();
                    d.year = $('select#year option:checked').val();
                    d.month1 = $('select#month1 option:checked').val();
                    d.year1 = $('select#year1 option:checked').val();
                    d.dir = $('#filter1 option:checked').text();

                    if (d.month > d.month1 && d.year == d.year1) {
                        let a = d.month;
                        d.month = d.month1;
                        d.month1 = a;
                        document.getElementById('month').value = d.month;
                        document.getElementById('month1').value = d.month1;
                    }
                    if (d.year > d.year1) {
                        let a = d.year;
                        d.year = d.year1;
                        d.year1 = a;
                        document.getElementById('year').value = d.year;
                        document.getElementById('year1').value = d.year1;
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-left", mData: 'dia' },
                { "class": "text-center", mData: 'fecha' },
                { "class": "text-center", mData: 'entrada' },
                { "class": "text-center", mData: 'almuerzo' },
                { "class": "text-center", mData: 'salida' },
                { "class": "text-center", mData: 'horas' },
                { "class": "text-center", mData: 'control' },
            ],
            buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                className: 'btn dt-btn-custom',
                filename: 'REPORTE DE HORARIO GENERAL',
                title: 'REPORTE DE HORARIO GENERAL',
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                className: 'btn dt-btn-custom',
                download: ['donwload', 'open'],
                orientation: 'landscape',
                filename: 'REPORTE DE HORARIO GENERAL',
                title: 'REPORTE DE HORARIO GENERAL',
                exportOptions: {
                    // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                },
                customize: function (doc) {
                    doc.styles.tableBodyEven.alignment = 'center';
                    doc.styles.tableBodyOdd.alignment = 'center';
                    doc.content[1].table.widths = ['10%', '10%', '10%', '15%', '10%', '15%', '10%', '20%'];
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
                                    bold: true,
                                    text: 'REPORTE DE HORARIO',
                                    fontSize: 13,
                                    margin: [-88, 20]
                                },
                                {
                                    alignment: 'left',
                                    bold: true,
                                    text: 'DEL: ' + max_Date(String('01/' + $('select#month option:checked').val() + '/' + $('select#year option:checked').val())) + ' AL: ' + min_Date($('select#month1 option:checked').val(), $('select#year1 option:checked').val()),
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
            }],
            "order": false,
            'columnDefs': [
                { 'width': 1, 'targets': [0] },
                { 'max-width': 1, 'targets': [0] },
                { "visible": false, "targets": [0] }
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(0, { page: 'current' }).data().each(function (group, i) {
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
        init: function (id_persona) {

            month = $('select#month_1 option:checked').val();
            year = $('select#year_1 option:checked').val();

            $(`#month option[value="${month}"]`).attr("selected", true);
            $(`#year option[value="${year}"]`).attr("selected", true);
            $(`#month1 option[value="${month}"]`).attr("selected", true);
            $(`#year1 option[value="${year}"]`).attr("selected", true);
            bsDataTables();
            initDataTableEmpleados(id_persona);
            setTimeout(function () {
                tabla_horario.ajax.reload();
            }, 100);

        }
    };
}();



function refresh() {
    tabla_horario.ajax.reload();
}


