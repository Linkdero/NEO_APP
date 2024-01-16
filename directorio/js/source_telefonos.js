
var tabla_directorio;
var tabla_telefonos;

function findWithAttr(array, attr, value) {
    for (var i = 0; i < array.length; i += 1) {
        if (array[i][attr] === value) {
            return i;
        }
    }
    return -1;
}

function get_fotografia(tabla, columna) {
    $('#' + tabla + ' tr').each(function (index, element) {
        var id_persona = $(element).find("td").eq(columna).html();
        $.ajax({
            type: "POST",
            url: "directorio/php/back/telefonos/get_fotografia.php",
            dataType: 'json',
            data: {
                id_persona: id_persona
            },
            beforeSend: function () {
                //$(element).find("td").eq(columna).html('Cargando...');
            },
            success: function (data) {
                // console.log(data);

                $(element).find("td").eq(columna).html(data.Foto);

            }
        });
    });

}

var EmpleadoDirectorio = function () {



    // Datatables listado de usuarios
    var initDatatableEmpleadoDirectorio = function () {
        tabla_directorio = $('#tb_directorio').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "searching": true,
            responsive: true,
            // "scrollX": true,
            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "directorio/php/back/telefonos/get_telefonos.php",
                type: "POST",
                data: { tipo: function () { return $('#tipo').val() } },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'Foto' },
                { "class": "text-center", mData: 'ID' },
                { "class": "text-center", mData: 'Nombre' },
                { "class": "text-center", mData: 'Dirección' },
                { "class": "text-center", mData: 'Puesto' },
                { "class": "text-center", mData: 'Grupo' },
                { "class": "text-center", mData: 'Promocion' },
                { "class": "text-center", mData: 'Estado' },
                { "class": "text-center", mData: 'TipoServicio' },
                { "class": "text-center", mData: 'Detalle' },
                { "class": "text-center", mData: 'estado_persona' }
            ],

            "columnDefs": [
                { responsivePriority: 0, targets: 9 },
                { visible: false, targets: 10 },
            ],

            drawCallback: function (settings) {
                get_fotografia('tb_directorio', 0);
            },

            initComplete: function () {
                var column = this.api().column(3);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter1')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });

                var offices = column.data().toArray();
                offices = offices.filter(function (el) {
                    return el != null;
                });
                offices = [...new Set(offices)]
                offices.forEach(function (s) {
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

                var column1 = this.api().column(5);
                var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter2')
                    .on('change', function () {
                        var val1 = $(this).val();
                        column1.search(val1).draw()
                    });

                var offices1 = ['GRUPO A', 'GRUPO B'];
                offices1.sort();
                offices1.forEach(function (d) {
                    select1.append('<option value="' + d + '">' + d + '</option>');
                })

                var column2 = this.api().column(10);
                var select2 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter3')
                    .on('change', function () {
                        var val2 = $(this).val();
                        column2.search(val2).draw()
                    });

                var offices3 = ['INACTIVO', 'ACTIVO'];
                var offices2 = [1, 0];
                // offices2.sort();
                offices2.forEach(function (d) {
                    select2.append('<option value="' + d + '">' + offices3[d] + '</option>');
                })

                // var column3 = this.api().column(3);
                // var select3 = $('<select class="form-control"><option value="">TODOS</option></select>')
                //     .appendTo('#filter3')
                //     .on('change', function () {
                //         var val3 = $(this).val();
                //         column3.search(val3).draw()
                //     });
                // var offices3 = column3.data().toArray();
                // var filtered = offices3.filter(function (el) {
                //     return el != null;
                // });
                // filtered.forEach(function (s) {
                //     s = s.split(',');
                //     s.forEach(function (d) {
                //         if (!~filtered.indexOf(d)) {
                //             filtered.push(d);
                //         }
                //     })
                // })
                // filtered = [...new Set(filtered)]
                // console.log(filtered);
                // filtered.sort();
                // filtered.forEach(function (d) {
                //     select3.append('<option value="' + d + '">' + d + '</option>');
                // })

            }
        });
    };

    var initDatatableTelefonos = function () {
        $('#tb_tels thead tr').clone(true).appendTo('#tb_tels thead');
        $('#tb_tels thead tr:eq(1) th').each(function (i) {
            $(this).html('<input type="text" class="form-control form-corto_2 form-control-sm" placeholder="" />');
            $('input', this).on('keyup change', function () {
                if (tabla_telefonos.column(i).search() !== this.value) {
                    tabla_telefonos
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        tabla_telefonos = $('#tb_tels').DataTable({
            "ordering": false,
            "pageLength": 100,
            "bProcessing": true,
            "searching": true,
            responsive: true,
            // "scrollX": true,
            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "directorio/php/back/telefonos/get_telefonos_all.php",
                type: "POST",
                data: {},
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'numero' },
                { "class": "text-center", mData: 'nombre' },
                { "class": "text-center", mData: 'gafete' },
                { "class": "text-center", mData: 'referencia' },
                { "class": "text-center", mData: 'tipo' },
                { "class": "text-center", mData: 'observaciones' },
                { "class": "text-center", mData: 'flag_activo' },
            ],



        });
    };

    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {

            renderer: 'bootstrap',
            oLanguage: {
                /*sLengthMenu: "_MENU_",
                 sInfo: "Showing <strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                 oPaginate: {
                 sPrevious: '<i class="fa fa-angle-left"></i>',
                 sNext: '<i class="fa fa-angle-right"></i>'
                 }*/
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando empleados del _START_ al _END_ de un total de _TOTAL_ empleados",
                sInfoEmpty: "Mostrando empleados del 0 al 0 de un total de 0 empleados",
                sInfoFiltered: "(filtrado de un total de _MAX_ empleados)",
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
        init: function () {
            //Init Datatables
            bsDataTables();
            initDatatableEmpleadoDirectorio();
            initDatatableTelefonos();
        }
    };
}();




// Initialize when page loads
jQuery(function () { EmpleadoDirectorio.init(); });
