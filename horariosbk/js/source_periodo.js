
var datableEmpleado = function () {

    var initDataTableBoletas = function (id_persona) {
        tabla_boletas = $('#tb_boletas').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            order: [[0, "desc"]],
            language: {
                emptyTable: "No hay boletas disponibles.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.id = 2;
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'nro_boleta' },
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
                }]
        });
    };

    var initDataTablePermisos = function () {
        tabla_permisos = $('#tb_permisos').DataTable({
            pageLength: 20,
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

            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {


                $('td:eq(0)', nRow).html('<a class="" href="#" ">' + aData.nro_boleta + '</a>').editable({
                    pk: aData.id,
                    name: 1,
                    url: 'horarios/php/back/boletas/update_rows.php',
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

                $('td:eq(5)', nRow).html('<a class="" href="#" ">' + aData.observaciones + '</a>').editable({
                    pk: aData.id,
                    name: 2,
                    url: 'horarios/php/back/boletas/update_rows.php',
                    send: 'always',
                });

                // $('td:eq(1)', nRow).html('<a class="" href="#" ">'+aData.tipo+'</a>').editable({
                //     pk:aData.id_telefono,
                //     name:3,
                //     type: 'select',
                //     url: 'directorio/php/back/telefonos/update_rows.php',
                //     send:'always',
                //     source: options_tipo,
                // });
                // $('td:eq(2)', nRow).html('<a class="" href="#" ">'+aData.referencia+'</a>').editable({
                //     pk:aData.id_telefono,
                //     name:4,
                //     type: 'select',
                //     url: 'directorio/php/back/telefonos/update_rows.php',
                //     send:'always',
                //     source: options_ref,
                // });

                // $('td:eq(3)', nRow).html('<a class="" href="#" ">'+aData.observaciones+'</a>').editable({
                //     pk:aData.id_telefono,
                //     name:2,
                //     url: 'directorio/php/back/telefonos/update_rows.php',
                //     send:'always',
                // });

                return nRow;

            },
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
                    customize: function (doc) {
                        // doc.styles.tableBodyEven.alignment = 'center';
                        // doc.styles.tableBodyOdd.alignment = 'center';
                        // doc.content[1].table.widths = ['10%', '10%', '10%', '15%', '10%', '15%', '10%', '20%'];
                        // doc.content.splice(0, 1);
                        // doc.pageMargins = [20, 100, 20, 20];
                        // doc['header'] = (function () {
                        //     return {
                        //         columns: [
                        //             {
                        //                 image: baner,
                        //                 width: 300,
                        //                 margin: [-50, -10]
                        //             },
                        //             {
                        //                 alignment: 'left',
                        //                 text: 'EMPLEADO: ' + $('#naem').val() + '\n DIRECCION: ' + $('#dirf').val() + "\n PUESTO: " + $('#puesf').val(),
                        //                 fontSize: 8,
                        //                 margin: [-295, 50, 0, 0]
                        //             },
                        //             {
                        //                 alignment: 'left',
                        //                 bold: true,
                        //                 text: 'REPORTE DE HORARIO',
                        //                 fontSize: 13,
                        //                 margin: [-88, 20]
                        //             },
                        //             {
                        //                 alignment: 'left',
                        //                 bold: true,
                        //                 text: 'DEL: ' + max_Date(String('01/' + $('select#month option:checked').val() + '/' + $('select#year option:checked').val())) + ' AL: ' + min_Date($('select#month1 option:checked').val(), $('select#year1 option:checked').val()),
                        //                 fontSize: 10,
                        //                 margin: [-220, 40]
                        //             },
                        //             {
                        //                 alignment: 'left',
                        //                 text: 'FECHA: ' + formatDate1(Date()) + '\n HORA: ' + formatDate2(Date()),
                        //                 fontSize: 8,
                        //                 margin: [24, 0]
                        //             },

                        //         ],
                        //         margin: 20
                        //     }
                        // });

                    }
                }
            ],

            initComplete: function () {
                // var column = this.api().column(0);
                // var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                //     .appendTo('#periodo1')
                //     .on('change', function () {
                //         var val = $(this).val();
                //         column.search(val).draw()
                //     });

                // var offices = [];
                // column.data().toArray().forEach(function (s) {
                //     s = s.split(',');
                //     s.forEach(function (d) {
                //         if (!~offices.indexOf(d)) {
                //             offices.push(d);
                //         }
                //     })
                // })
                // offices.sort();
                // offices.forEach(function (d) {
                //     select.append('<option value="' + d + '">' + d + '</option>');
                // })


                // var column1 = this.api().column(4);
                // var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                //   .appendTo('#filter2')
                //   .on('change', function() {
                //     var val1 = $(this).val();
                //     column1.search(val1).draw()
                //   });

                //  var offices1 = ['GRUPO A', 'GRUPO B'];
                //  offices1.sort();
                //  offices1.forEach(function(d) {
                //     select1.append('<option value="' + d + '">' + d + '</option>');
                //   })
            },
            // buttons: [{
            //     extend: 'excel',
            //     text: '<i class="far fa-file-excel" style="color:green;"></i> Exportar',
            //     className: 'btn dt-btn-custom',
            //     title: 'Horario Empleado - ' + ($('#naem').val()) + ' - ' + ($('select#month option:checked').text()),
            //     sheetName: $('select#month option:checked').text(),
            //     exportOptions: {
            //         columns: [ 0,1,2,3,4,5,6 ]
            //     },
            //     customize: function (xlsx) {
            //         var sheet = xlsx.xl.worksheets['sheet1.xml'];
            //         var numrows = 1;
            //         var clR = $('row', sheet);

            //         //update Row
            //         clR.each(function () {
            //             var attr = $(this).attr('r');
            //             var ind = parseInt(attr);
            //             ind = ind + numrows;
            //             $(this).attr("r",ind);
            //         });

            //         // Create row before data
            //         $('row c ', sheet).each(function () {
            //             var attr = $(this).attr('r');
            //             var pre = attr.substring(0, 1);
            //             var ind = parseInt(attr.substring(1, attr.length));
            //             ind = ind + numrows;
            //             $(this).attr("r", pre + ind);
            //         });

            //         function Addrow(index,data) {
            //             msg='<row r="'+index+'">'
            //             for(i=0;i<data.length;i++){
            //                 var key=data[i].key;
            //                 var value=data[i].value;
            //                 msg += '<c t="inlineStr" r="' + key + index + '">';
            //                 msg += '<is>';
            //                 msg +=  '<t>'+value+'</t>';
            //                 msg+=  '</is>';
            //                 msg+='</c>';
            //             }
            //             msg += '</row>';
            //             return msg;
            //         }


            //         //insert
            //         var r1 = Addrow(1, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }]);
            //         // var r2 = Addrow(2, [{ key: 'A', value: '' }, { key: 'B', value: '' }]);
            //         // var r3 = Addrow(3, [{ key: 'A', value: '' }, { key: 'B', value: '' }]);

            //         sheet.childNodes[0].childNodes[1].innerHTML = r1 + sheet.childNodes[0].childNodes[1].innerHTML;
            //         $('c[r=A1] t', sheet).text(($('#gaf').val()));
            //         $('c[r=B1] t', sheet).text(($('#naem').val()));
            //         $('c[r=C1] t', sheet).text(($('#dirf').val()));
            //         $('c[r=D1] t', sheet).text(($('#puesf').val()));
            //         $('c[r=E1] t', sheet).text($('select#month option:checked').text()+' '+$('select#year option:checked').text());
            //     }
            // }]
        });
    };

    var initDataTableVacaciones = function (id_persona) {
        tabla_vacaciones = $('#tb_vacaciones').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            order: [[0, "desc"]],
            language: {
                emptyTable: "No hay vacaciones disponibles.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.id = 1;
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'year' },
                { "class": "text-center", mData: 'dia_asi' },
                { "class": "text-center", mData: 'dia_goz' },
                { "class": "text-center", mData: 'dia_pen' },
                { "class": "text-center", mData: 'dia_est' },
            ],
            initComplete: function () {
                var column = this.api().column(0);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#periodo1')
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
            },
            buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                className: 'btn btn-sm btn-personalizado outline',
                filename: 'PERIODOS VACACIONALES',
                title: 'PERIODOS VACACIONALES',
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
                filename: 'PERIODOS VACACIONALES',
                title: 'PERIODOS VACACIONALES',
                exportOptions: {
                    // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                },
            }]
        });
    };

    var initDataTableEmpleados = function (id_persona) {
        tabla_horario = $('#tb_horario').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            order: [[4, "desc"]],
            language: {
                emptyTable: "No hay registro de vacaciones.",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_periodos.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.id = 0;
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id' },
                { "class": "text-center", mData: 'fini' },
                { "class": "text-center", mData: 'ffin' },
                { "class": "text-center", mData: 'fpre' },
                { "class": "text-center", mData: 'year' },
                { "class": "text-center", mData: 'vac_sol' },
                { "class": "text-center", mData: 'vac_pen' },
                { "class": "text-center", mData: 'est_des' },

            ],
            initComplete: function () {
                var column = this.api().column(4);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#periodo')
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
            },
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    filename: 'PERIODO VACACIONALES',
                    title: 'PERIODO VACACIONALES',
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
                    filename: 'PERIODO VACACIONALES',
                    title: 'PERIODO VACACIONALES',
                    exportOptions: {
                        // columns: [0, 1, 2, 3, 4, 5, 6, 8],
                    },
                }
            ],
            'columnDefs': [
                { 'width': 1, 'targets': [4] },
                { 'max-width': 1, 'targets': [4] },
                { "visible": false, "targets": [4] }
            ],
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
                api.column(4, { page: 'current' }).data().each(function (group, i) {
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


            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1); //January is 0!
            var yyyy = today.getFullYear();
            $(`#month_p option[value="${mm}"]`).attr("selected", true);
            $(`#year_p option[value="${yyyy}"]`).attr("selected", true);

            bsDataTables();
            initDataTableEmpleados(id_persona);
            initDataTableVacaciones(id_persona);
            initDataTableBoletas(id_persona);
            initDataTablePermisos();


            // setTimeout(function () {
            //     tabla_horario.ajax.reload();
            // }, 100);
            // setTimeout(function () {
            //     tabla_vacaciones.ajax.reload();
            // }, 100);
            // setTimeout(function () {
            //     tabla_boletas.ajax.reload();
            // }, 100);
            // setTimeout(function () {
            //     tabla_permisos.ajax.reload();
            // }, 100);

        }
    };
}();



function refresh() {
    tabla_horario.ajax.reload();
    tabla_vacaciones.ajax.reload();
    tabla_boletas.ajax.reload();
}

function refresh_boletas() {
    tabla_permisos.ajax.reload();
}
