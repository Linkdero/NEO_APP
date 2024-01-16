
function get_fotografia(data, row, column) {
    let id_persona = data.id;
    let fecha = "1/1/1990 " + data.entrada;
    let limite = "1/1/1990 7:00:00";
    let limitet = "1/1/1990 11:00:00";

    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_fotografia.php",
        dataType: 'html',
        data: { id_persona },
        success: function (foto) {
            $(`td:eq(0)`, row).html(foto);
            if (data.tarde == 'Tarde') {
                $(`td:eq(4)`, row).css("color", "red");
            }
        }
    });
}

let tabla_diario;


var datableDiario = function () {

    var initDataTableDiario = function () {
        tabla_diario = $('#tb_reporte_diario').DataTable({
            pageLength: 10,
            bProcessing: true,
            paging: true,
            scrollY: '50vh',
            scrollCollapse: true,
            order: [[1, "asc"]],
            ordering:false,
            language: {
                emptyTable: "No hay personas para la fecha seleccionada",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/empleados/get_reporte_diario.php",
                type: "POST",
                data: function (d) {
                    d.fecha = $('#fecha').val();
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [

                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'foto' },
                { "class": "text-center", mData: 'empleado' },
                { "class": "text-center", mData: 'dfuncional' },
                { "class": "text-center", mData: 'pfuncional' },
                { "class": "text-center", mData: 'entrada' },
                { "class": "text-center", mData: 'almuerzo' },
                { "class": "text-center", mData: 'salida' },
                { "class": "text-center", mData: 'tarde' },
                { "class": "text-center", mData: 'cuenta' },
                { "class": "text-center", mData: 'correlativo' },
                { "class": "text-center", mData: 'horas' },

            ],


            rowCallback: function (row, data) {
                get_fotografia(data, row, 0);
            },

            initComplete: function () {
              $('body').on("click", ".dropdown-menu-", function (e) {
                $(this).parent().is(".show") && e.stopPropagation();
              });
                var column = this.api().column(3);
                var select = $('<select class=" form-control form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#fdir')
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


                var column1 = this.api().column(8);
                var select1 = $('<select class=" form-control form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#ftar')
                    .on('change', function () {
                        var val1 = $(this).val();
                        column1.search(val1).draw()
                    });

                var offices1 = ['Tarde'];
                offices1.sort();
                offices1.forEach(function (d) {
                    select1.append('<option value="' + d + '">' + d + '</option>');
                })

                var column2 = this.api().column(9);
                var select2 = $('<select class=" form-control form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#fmar')
                    .on('change', function () {
                        var val2 = $(this).val();
                        column2.search(val2).draw()
                    });

                var offices2 = ['Solo uno'];
                offices2.sort();
                offices2.forEach(function (d) {
                    select2.append('<option value="' + d + '">' + d + '</option>');
                })
            },


            buttons: [{
                extend: 'excel',
                text: '<i class="far fa-file-excel"></i> Exportar',
                className: 'btn btn-sm btn-soft-info',
                title: 'Reporte-Horarios-' + ($("#fecha").val()),
                exportOptions: {
                    columns: [10, 2, 3, 4, 5, 6, 7, 8,11]
                },
                customize: function (xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var numrows = 1;
                    var clR = $('row', sheet);

                    //update Row
                    clR.each(function () {
                        var attr = $(this).attr('r');
                        var ind = parseInt(attr);
                        ind = ind + numrows;
                        $(this).attr("r", ind);
                    });

                    // Create row before data
                    $('row c ', sheet).each(function () {
                        var attr = $(this).attr('r');
                        var pre = attr.substring(0, 1);
                        var ind = parseInt(attr.substring(1, attr.length));
                        ind = ind + numrows;
                        $(this).attr("r", pre + ind);
                    });

                    function Addrow(index, data) {
                        msg = '<row r="' + index + '">'
                        for (i = 0; i < data.length; i++) {
                            var key = data[i].key;
                            var value = data[i].value;
                            msg += '<c t="inlineStr" r="' + key + index + '">';
                            msg += '<is>';
                            msg += '<t>' + value + '</t>';
                            msg += '</is>';
                            msg += '</c>';
                        }
                        msg += '</row>';
                        return msg;
                    }


                    //insert
                    var r1 = Addrow(1, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }]);
                    // var r2 = Addrow(2, [{ key: 'A', value: '' }, { key: 'B', value: '' }]);
                    // var r3 = Addrow(3, [{ key: 'A', value: '' }, { key: 'B', value: '' }]);

                    sheet.childNodes[0].childNodes[1].innerHTML = r1 + sheet.childNodes[0].childNodes[1].innerHTML;
                    $('c[r=A1] t', sheet).text($("#fecha").val());
                }
            }
            ],
            columnDefs: [
                {
                    "targets": [0, 8, 9, 10],
                    "visible": false,
                    "searchable": true
                },
                {
                    "width": "12%",
                    "targets": 1
                }
            ]
        });
    };

    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom: "<'row'<'col-sm-4'l><'col-sm-3'B><'col-sm-5'f>>" +
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
            sFilterInput: " form-control form-control-sm",
            sLengthSelect: " form-control form-control-sm"
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
            initDataTableDiario();

        }
    };
}();



function refresh_diario() {
    tabla_diario.ajax.reload();
}


// Initialize when page loads
jQuery(function () { datableDiario.init(); });
