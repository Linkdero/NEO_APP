
let tabla_salones, tabla_reservaciones;
let salonesDatatable = function () {
    let initDatatableSalones = function () {
        tabla_salones = $('#tb_salones').DataTable({
            ordering: false,
            pageLength: 25,
            bProcessing: true,
            scrollX: true,
            language: {
                emptyTable: "No hay salones disponibles",
                loadingRecords: "<div class='spinner-grow text-primary'></div>"
            },
            ajax: {
                url: "salones/php/back/listados/get_salones.php",
                type: "POST",
                data: { opcion: 1 },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'nombre' },
                { "class": "text-center", mData: 'capacidad' },
                { "class": "text-center", mData: 'ubicacion' },
                // { "class": "text-center", mData: 'usuario' },
                // { "class": "text-center", mData: 'fecha' },
                { "class": "text-center", mData: 'estado' },
                { "class": "text-center", mData: 'accion' },
            ],
            buttons: [

            ],
            columnDefs: [
                {
                    'targets': [3],
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    render: function (data, type, row, meta) {
                        if (data === "0") {
                            return '<span class="badge badge-danger">No disponible</span>';
                        } else if (data === "1") {
                            return '<span class="badge badge-success">Disponible</span>';
                        }
                    }
                }]
        });
    };

    // DataTables Bootstrap integration
    let bsDataTables = function () {
        let dataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, dataTable.defaults, {
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
                    sSortAscending: ": Actilet para ordenar la columna de manera ascendente",
                    sSortDescending: ": Actilet para ordenar la columna de manera descendente"
                }
            }
        });

        // Default class modification
        jQuery.extend(dataTable.ext.classes, {
            sFilterInput: "form-control",
            sLengthSelect: "form-control"
        });

        // Bootstrap paging button renderer
        dataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
            let api = new dataTable.Api(settings);
            let classes = settings.oClasses;
            let lang = settings.oLanguage.oPaginate;
            let btnDisplay, btnClass;

            let attach = function (container, buttons) {
                let i, ien, node, button;
                let clickHandler = function (e) {
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
        if (dataTable.TableTools) {
            // Set the classes that TableTools uses to something suitable for Bootstrap
            jQuery.extend(true, dataTable.TableTools.classes, {
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
            jQuery.extend(true, dataTable.TableTools.DEFAULTS.oTags, {
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
            initDatatableSalones();
        }
    };
}();


function reload() {
    tabla_salones.ajax.reload(null, false);
}
jQuery(function () {
    salonesDatatable.init();
});


