
var tabla_detalle;
var dataTableDetalle = function () {
    var initDatatableDetalle = function (id_dependencia) {
        tabla_detalle = $('#tb_detalle_directorio').DataTable({
            ordering: false,
            pageLength: 10,
            bProcessing: true,
            language: {
                emptyTable: "No hay contactos disponibles",
                loadingRecords: "<div class='spinner-grow text-primary'></div>"
            },
            ajax: {
                url: "directorio/php/back/dependencias/get_detalle.php",
                type: "POST",
                data: { id_dependencia: $("#id_dependencia").val() },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            "aoColumns": [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'numero' },
                { "class": "text-center", mData: 'nombre' },
                { "class": "text-center", mData: 'puesto' },
                { "class": "text-center", mData: 'fecha' },
                { "class": "text-center", mData: 'accion' }
            ],
            'columnDefs': [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
            ],
            "bSortCellsTop": true,
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('td:eq(0)', nRow).html('<a data-name="1" data-pk="' + aData.id + '" class="" href="#" data-type="text" data-title="Actualizar Sicoin">' + aData.numero + '</a>').editable({
                    pk: aData.id,
                    type: 'number',
                    url: 'directorio/php/back/contacto/update_number.php',
                    title: 'Enter public name',
                    send: 'always',
                    validate: function (value) {
                        if ($.trim(value) == '') {
                            Swal.fire({
                                type: 'error',
                                title: 'El valor no puede ser vacío',
                                showConfirmButton: false,
                                timer: 1100
                            });
                        }
                    }
                });

                $('td:eq(1)', nRow).html('<a data-name="2" data-pk="' + aData.id + '" class="" href="#" data-type="text" data-title="Actualizar Sicoin">' + aData.nombre + '</a>').editable({
                    pk: aData.id,
                    type: 'text',
                    url: 'directorio/php/back/contacto/update_name.php',
                    title: 'Enter public name',
                    send: 'always',
                });

                $('td:eq(2)', nRow).html('<a data-name="3" data-pk="' + aData.id + '" class="" href="#" data-type="text" data-title="Actualizar Sicoin">' + aData.puesto + '</a>').editable({
                    pk: aData.id,
                    type: 'text',
                    url: 'directorio/php/back/contacto/update_puesto.php',
                    title: 'Enter public name',
                    send: 'always',
                });

                return nRow;
            }
        });
    };


    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom:
                //"<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            renderer: 'bootstrap',
            oLanguage: {
                /*sLengthMenu: "_MENU_",
                 sInfo: "Showing <strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                 oPaginate: {
                 sPrevious: '<i class="fa fa-angle-left"></i>',
                 sNext: '<i class="fa fa-angle-right"></i>'
                 }*/
                sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
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
        init: function (id_dependencia) {
            bsDataTables();
            initDatatableDetalle(id_dependencia);
        }
    };
}();

function reload_detalle() {
    tabla_detalle.ajax.reload(null, false);
}

// Initialize when page loads
jQuery(function () { dataTableDetalle.init(); });
