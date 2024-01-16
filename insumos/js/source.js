var tabla_tipo_insumos_listado;
var table_movimientos, table_movimientos_en, table_movmientos_solvencia;
var InsumosTableDatatables_listado = function () {


    // Datatables listado de usuarios
    var initDataTableTipoInsumos_listado = function () {
        tabla_tipo_insumos_listado = $('#tb_tipo_insumos_listado').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_tipos.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                {"class": "text-left", mData: 'codigo'},
                {"class": "text-center", mData: 'descripcion'}/*,
               { "class" : "text-center", mData: 'nit' },
               { "class" : "text-center", mData: 'igss' },
               { "class" : "text-center", mData: 'descripcion' },
               { "class" : "text-center", mData: 'status' },
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/
            ],
            buttons: []
        });


        /*setInterval( function () {
          tabla_emps_listado.ajax.reload(null, false);
        }, 100000 );*/


    };

    // Datatables listado de usuarios
    var initDataTableMovimientos_listado = function () {

        table_movimientos = $('#tb_movimientos').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: ''/*Swal.fire({
                title: 'Espere..!',
                text: 'Se está obteniendo la información',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                //timer: 1000,
                onOpen: () => {
                swal.showLoading()
                }
                })*/
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_movimientos.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                {"class": "text-center", mData: 'fecha'},
                {"class": "text-left", mData: 'movimiento'},
                {"class": "text-center", mData: 'producto'}/*,
               { "class" : "text-center", mData: 'nit' },
               { "class" : "text-center", mData: 'igss' },
               { "class" : "text-center", mData: 'descripcion' },
               { "class" : "text-center", mData: 'status' },
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/
            ],
            buttons: [],
            "initComplete": function (settings, json) {
                alert('DataTables has finished its initialisation.');
                //swal2-container swal2-center swal2-fade swal2-shown
                //$('.swal2-container').remove();
            }
        });


        /*setInterval( function () {
          tabla_emps_listado.ajax.reload(null, false);
        }, 100000 );*/


    };

    var initDataTableMovimientos_en_listado = function () {
        $('#tb_movimientos_en thead tr').clone(true).appendTo('#tb_movimientos_en thead');
        $('#tb_movimientos_en thead tr:eq(1) th').each(function (i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-corto_2" placeholder="" />');

            $('input', this).on('keyup change', function () {
                if (table_movimientos_en.column(i).search() !== this.value) {
                    table_movimientos_en
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        table_movimientos_en = $('#tb_movimientos_en').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": false,
            orderCellsTop: true,
            fixedHeader: true,

            //"dom": '<"">frtip',


            language: {
                emptyTable: "No hay registros para mostrar",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "/*
                Swal.fire({
                title: 'Espere..!',
                text: 'Se está obteniendo la información',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                //timer: 1000,
                onOpen: () => {
                swal.showLoading()
                }
                })*///" <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_movimientos_encabezado.php",
                type: "POST",
                data: {
                    ini: function () {
                        return $('#ini').val()
                    },
                    fin: function () {
                        return $('#fin').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                {"class": "text-center", mData: 'transaccion', "width": "2%"},
                {"class": "text-center", mData: 'fecha', "width": "8%"},
                {"class": "text-center", mData: 'movimiento', "width": "2%"},
                {"class": "text-center", mData: 'total', "width": "2%"},
                {"class": "text-center", mData: 'responsable'},
                {"class": "text-center", mData: 'gafete', "width": "3%"},
                {"class": "text-center", mData: 'empleado'},
                {"class": "text-center", mData: 'bodega', "width": "2%"},
                {"class": "text-center", mData: 'accion', "width": "2%"}/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/
            ],
            buttons: [],
            "initComplete": function (settings, json) {
                //alert( 'DataTables has finished its initialisation.' );
                //swal2-container swal2-center swal2-fade swal2-shown
                //$('div.swal2-container .swal2-center .swal2-fade .swal2-shown').hide();
            }

        });
        //$("div.toolbar").html('');
        /*setInterval( function () {
          tabla_emps_listado.ajax.reload(null, false);
        }, 100000 );*/
    };

    var initDataTableMovimientosSolvencia = function () {
        $('#tb_movimientos_solvencia thead tr').clone(true).appendTo('#tb_movimientos_solvencia thead');
        $('#tb_movimientos_solvencia thead tr:eq(1) th').each(function (i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-corto_2" placeholder="" />');

            $('input', this).on('keyup change', function () {
                if (table_movimientos_solvencia.column(i).search() !== this.value) {
                    table_movimientos_solvencia
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        table_movimientos_solvencia = $('#tb_movimientos_solvencia').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": false,
            orderCellsTop: true,
            fixedHeader: true,
            language: {
                emptyTable: "No hay registros para mostrar",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_movimientos_solvencia.php",
                type: "POST",
                data: {
                    ini: function () {
                        return $('#ini').val()
                    },
                    fin: function () {
                        return $('#fin').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                {"class": "text-center", mData: 'transaccion', "width": "2%"},
                {"class": "text-center", mData: 'fecha', "width": "8%"},
                {"class": "text-center", mData: 'movimiento', "width": "2%"},
                {"class": "text-center", mData: 'total', "width": "2%"},
                {"class": "text-center", mData: 'responsable'},
                {"class": "text-center", mData: 'gafete', "width": "3%"},
                {"class": "text-center", mData: 'empleado'},
                {"class": "text-center", mData: 'bodega', "width": "2%"},
                {"class": "text-center", mData: 'accion', "width": "2%"}
            ],
            buttons: [],
            "initComplete": function (settings, json) {
            }
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
            initDataTableTipoInsumos_listado();
            initDataTableMovimientos_listado();
            initDataTableMovimientos_en_listado();
            initDataTableMovimientosSolvencia();
        }
    };
}();

function reload_movimientos_en() {
    /*swal({
  title: 'Espere..!',
  text: 'Se está obtiendo la información',
  allowOutsideClick: false,
  allowEscapeKey: false,
  allowEnterKey: false,
  timer: 2000,
  onOpen: () => {
  swal.showLoading()
  }
  })*/
    table_movimientos_en.ajax.reload(null, false);
}

function reload_movimientos_solvencia() {
    table_movimientos_solvencia.ajax.reload(null, false);
}


// Initialize when page loads
jQuery(function () {
    InsumosTableDatatables_listado.init();
});
