var table_empleados_asignar, table_empleados_autoriza;
var EmpleadosTableDatatables_listado_modal = function () {

    var initDataTableEmpleados_asignar = function () {
        table_empleados_asignar = $('#tb_empleados_asignar').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            language: {
                emptyTable: "No hay empleados",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "empleados/php/back/listados/get_empleados_rrhh.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            "aoColumns": [
                {"class": "text-center", mData: 'id_persona', "width": "2%"},
                {"class": "text-center", mData: 'empleado'},
                {"class": "text-center", mData: 'status'},
                {"class": "text-center", mData: 'estado'}
            ],
            buttons: [
                //'excel'
            ],
            'columnDefs': [{
                'targets': [3],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function (data, type, row, meta) {
                    if (data === "1") {
                        return '<button class="btn btn-sm btn-personalizado outline button-check empleado-egreso" id="' + row.id_persona + '"><i class="fa fa-check"></i></button>';
                    } else {
                        return '';
                    }
                }

            }]
        });

        $('#tb_empleados_asignar tbody').on('click', 'button', function () {
            var data = table_empleados_asignar.row($(this).parents('tr')).data();
            $('#myPopupInput1').val(data['id_persona']);
        });
    };

    var initDataTableEmpleados_autoriza = function () {
        table_empleados_autoriza = $('#tb_empleados_autoriza').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            language: {
                emptyTable: "No hay empleados",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "empleados/php/back/listados/get_empleados_autoriza.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            "aoColumns": [
                {"class": "text-center", mData: 'id_persona', "width": "2%"},
                {"class": "text-center", mData: 'empleado'},
                {"class": "text-center", mData: 'status'},
                {"class": "text-center", mData: 'estado'}
            ],
            buttons: [
                //'excel'
            ],
            'columnDefs': [{
                'targets': [3],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function (data, type, row, meta) {
                    return '<button class="btn btn-sm btn-personalizado outline button-check empleado-autoriza" id="' + row.id_persona + '"><i class="fa fa-check"></i></button>';
                }

            }]
        });

        $('#tb_empleados_autoriza tbody').on('click', 'button', function () {
            var data = table_empleados_autoriza.row($(this).parents('tr')).data();
            $('#myPopupInput1').val(data['id_persona']);
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
                //'csv', 'excel', 'pdf'
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
                sLoadingRecords: "",
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
            //sWrapper: " dt-bootstrap",
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
                    } else {
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
                                node, {action: button}, clickHandler
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
            //Init Datatables
            bsDataTables();
            initDataTableEmpleados_asignar();
            initDataTableEmpleados_autoriza();
        }
    };
}();


// Initialize when page loads
jQuery(function () {
   EmpleadosTableDatatables_listado_modal.init();
});
