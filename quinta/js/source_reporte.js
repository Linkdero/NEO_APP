
var table_reporte, table_reporte_sub;
var reporte_datatable = function () {

    function initDataTable_reporte() {
        $('#table_reporte thead tr').clone(true).appendTo('#table_reporte thead');
        $('#table_reporte thead tr:eq(1) th').each(function (i) {
            $(this).html('<input type="text" class="form-control form-corto_2" placeholder="" />');
            $('input', this).on('keyup change', function () {
                if (table_reporte.column(i).search() !== this.value) {
                    table_reporte
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        table_reporte = $('#table_reporte').DataTable({
            pageLength: 10,
            bProcessing: true,
            scrollX: true,

            ordering: false,
            order: [[4, "desc"]],
            language: {
                emptyTable: "No hay visitas registradas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "quinta/php/back/listados/reporte_visitas.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'ID' },
                { "class": "text-center", mData: 'oficina' },
                { "class": "text-center", mData: 'dependencia' },
                { "class": "text-center", mData: 'autoriza' },
                { "class": "text-center", mData: 'fecha' },
                { "class": "text-center", mData: 'entrada' },
                { "class": "text-center", mData: 'salida' },
                { "class": "text-center", mData: 'puerta' },
                { "class": "text-center", mData: 'gafete' },
                { "class": "text-center", mData: 'img' },
            ],
            'columnDefs': [
                { 'width': 1, 'targets': [0] },
                { 'max-width': 1, 'targets': [0] },
                { "visible": false, "targets": [0] }
            ],
        });
    };

    function initDataTable_reporte_sub() {

        table_reporte_sub = $('#table_reporte_sub').DataTable({
            pageLength: 10,
            bProcessing: true,
            scrollX: true,

            ordering: false,
            order: [[4, "desc"]],
            language: {
                emptyTable: "No hay visitas registradas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "quinta/php/back/listados/reporte_visitas_sub.php",
                type: "POST",
                data: {
                    ini: function () { return $('#ini').val() },
                    fin: function () { return $('#fin').val() },
                    oficina: function () { return $('#oficina_visita_').val() },
                    puerta: function () { return $('#puerta_').val() },
                    no_salido: function () { return $('#no_salido').val() }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-left", mData: 'ID' },
                { "class": "text-center", mData: 'oficina' },
                { "class": "text-center", mData: 'dependencia' },
                { "class": "text-center", mData: 'autoriza' },
                { "class": "text-center", mData: 'fecha' },
                { "class": "text-center", mData: 'entrada' },
                { "class": "text-center", mData: 'salida' },
                { "class": "text-center", mData: 'puerta' },
                { "class": "text-center", mData: 'gafete' },
                { "class": "text-center", mData: 'img' },
            ],
            'columnDefs': [
                { 'width': 1, 'targets': [0] },
                { 'max-width': 1, 'targets': [0] },
                { "visible": false, "targets": [0] }
            ],
            buttons: [
                'excel'
            ]
        });
    };

    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;
        jQuery.extend(true, $DataTable.defaults, {
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
        jQuery.extend($DataTable.ext.classes, {
            sFilterInput: "form-control",
            sLengthSelect: "form-control"
        });

        // Bootstrap paging button renderer
        $DataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
            let api = new $DataTable.Api(settings);
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
        if ($DataTable.TableTools) {
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
            initDataTable_reporte();
            initDataTable_reporte_sub();
        }
    };
}();

function reload_visitas_sub() {
    table_reporte_sub.ajax.reload(null, false)
}


// Initialize when page loads
jQuery(function () { reporte_datatable.init(); });
