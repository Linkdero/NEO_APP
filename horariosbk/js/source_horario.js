
var datableEmpleado = function () {

    var initDataTableCambio = function (id_persona) {
        tabla_cambio = $('#tb_cambio').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            ordering: false,
            language: {
                emptyTable: "No hay horario para el mes",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/empleados/get_cambios.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    // d.month = $('select#month option:checked').val();
                    // d.year = $('select#year option:checked').val();
                    // d.month1 = $('select#month1 option:checked').val();
                    // d.year1 = $('select#year1 option:checked').val();

                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id_control' },
                { "class": "text-center", mData: 'horario' },
                { "class": "text-center", mData: 'entrada' },
                { "class": "text-center", mData: 'salida' },
                { "class": "text-center", mData: 'desde' },
                { "class": "text-center", mData: 'hasta' },
                { "class": "text-center", mData: 'dias' },
                { "class": "text-center", mData: 'accion' }
            ],

            'columnDefs': [
                { "visible": false, "targets": [0] }
            ],
            initComplete: function () {

                // var column = this.api().column(6);
                // var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                //     .appendTo('#ftam')
                //     .on('change', function () {
                //         var val = $(this).val();
                //         column.search(val).draw()
                //     });

                // var offices = ['A tiempo', 'Tarde'];
                // // column.data().toArray().forEach(function (s) {
                // //     s = s.split(',');
                // //     s.forEach(function (d) {
                // //         if (!~offices.indexOf(d)) {
                // //             offices.push(d);
                // //         }
                // //     })
                // // })
                // offices.sort();
                // offices.forEach(function (d) {
                //     select.append('<option value="' + d + '">' + d + '</option>');
                // })


                // var column1 = this.api().column(2);
                // var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                //     .appendTo('#filter2')
                //     .on('change', function () {
                //         var val1 = $(this).val();
                //         column1.search(val1).draw()
                //     });

                // var offices1 = [];
                // column1.data().toArray().forEach(function (s) {
                //     s = s.split(',');
                //     s.forEach(function (d) {
                //         if (!~offices1.indexOf(d)) {
                //             offices1.push(d);
                //         }
                //     })
                // })
                // offices1.sort();
                // offices1.forEach(function (d) {
                //     select1.append('<option value="' + d + '">' + d + '</option>');
                // })
            },
            buttons: [{

            },

            {
                extend: 'excel',
                text: '<i class="far fa-file-excel" style="color:green;"></i> Exportar',
                className: 'btn dt-btn-custom',
                title: 'Horario Empleado - ' + ($('#naem').val()) + ' - ' + ($('select#month option:checked').text()),
                sheetName: $('select#month option:checked').text(),
                exportOptions: {
                    // columns: [ 0,1,2,3,4,5,6 ]
                },
                // customize: function (xlsx) {
                //     var sheet = xlsx.xl.worksheets['sheet1.xml'];
                //     var numrows = 1;
                //     var clR = $('row', sheet);

                //update Row
                // clR.each(function () {
                //     var attr = $(this).attr('r');
                //     var ind = parseInt(attr);
                //     ind = ind + numrows;
                //     $(this).attr("r",ind);
                // });

                // Create row before data
                // $('row c ', sheet).each(function () {
                //     var attr = $(this).attr('r');
                //     var pre = attr.substring(0, 1);
                //     var ind = parseInt(attr.substring(1, attr.length));
                //     ind = ind + numrows;
                //     $(this).attr("r", pre + ind);
                // });

                // function Addrow(index,data) {
                //     msg='<row r="'+index+'">'
                //     for(i=0;i<data.length;i++){
                //         var key=data[i].key;
                //         var value=data[i].value;
                //         msg += '<c t="inlineStr" r="' + key + index + '">';
                //         msg += '<is>';
                //         msg +=  '<t>'+value+'</t>';
                //         msg+=  '</is>';
                //         msg+='</c>';
                //     }
                //     msg += '</row>';
                //     return msg;
                // }


                //insert
                // var r1 = Addrow(1, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }]);
                // var r2 = Addrow(2, [{ key: 'A', value: '' }, { key: 'B', value: '' }]);
                // var r3 = Addrow(3, [{ key: 'A', value: '' }, { key: 'B', value: '' }]);

                // sheet.childNodes[0].childNodes[1].innerHTML = r1 + sheet.childNodes[0].childNodes[1].innerHTML;
                // $('c[r=A1] t', sheet).text(($('#gaf').val()));
                // $('c[r=B1] t', sheet).text(($('#naem').val()));
                // $('c[r=C1] t', sheet).text(($('#dirf').val()));
                // $('c[r=D1] t', sheet).text(($('#puesf').val()));
                // $('c[r=E1] t', sheet).text($('select#month option:checked').text()+' '+$('select#year option:checked').text());
                // }
            }],

        });
    };

    var initDataTableEmpleados = function (id_persona) {
        tabla_horario = $('#tb_horario').DataTable({
            pageLength: 25,
            bProcessing: true,
            paging: false,
            scrollY: "450px",
            ordering: false,
            language: {
                emptyTable: "No hay horario para el mes",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "horarios/php/back/empleados/get_horarios.php",
                type: "POST",
                data: function (d) {
                    d.id_persona = id_persona;
                    d.month = $('select#month option:checked').val();
                    d.year = $('select#year option:checked').val();
                    d.month1 = $('select#month1 option:checked').val();
                    d.year1 = $('select#year1 option:checked').val();
                    if (parseInt(d.month) > parseInt(d.month1) && d.year == d.year1) {
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
                { "class": "text-left", mData: 'dia' },
                { "class": "text-center", mData: 'fecha' },
                { "class": "text-center", mData: 'entrada' },
                { "class": "text-center", mData: 'almuerzo' },
                { "class": "text-center", mData: 'salida' },
                { "class": "text-center", mData: 'horas' },
                { "class": "text-center", mData: 'control' },
                { "class": "text-center", mData: 'permisos' },
                { "class": "text-center", mData: 'observaciones' },
                { "class": "text-center", mData: 'dechoras' },
                { "class": "text-center", mData: 'horarios' },

            ],
            initComplete: function () {
                var column = this.api().column(6);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#ftam')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });

                var offices = ['A tiempo', 'Tarde'];
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


                // var column1 = this.api().column(2);
                // var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                //     .appendTo('#filter2')
                //     .on('change', function () {
                //         var val1 = $(this).val();
                //         column1.search(val1).draw()
                //     });

                // var offices1 = [];
                // column1.data().toArray().forEach(function (s) {
                //     s = s.split(',');
                //     s.forEach(function (d) {
                //         if (!~offices1.indexOf(d)) {
                //             offices1.push(d);
                //         }
                //     })
                // })
                // offices1.sort();
                // offices1.forEach(function (d) {
                //     select1.append('<option value="' + d + '">' + d + '</option>');
                // })
            },
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;

                arr = api.column(9).data();
                tar = api.column(6).data();
                // Remove the formatting to get integer data for summation
                // var intVal = function ( i ) {
                //     return typeof i === 'string' ?
                //         i.replace(/[\$,]/g, '')*1 :
                //         typeof i === 'number' ?
                //             i : 0;
                // };

                let count = 0;
                for (i = 0; i < tar.length; i++) {
                    console.log();
                    if (tar[i].includes("Tarde")) {
                        count += 1;
                    }
                }

                let promh = [];
                for (i = 0; i < arr.length; i++) {
                    if (arr[i] != 0) {
                        promh.push(arr[i]);
                    }
                }
                // console.log(promh);
                // console.log(promh.length);
                // console.log(promh.reduce((a, b) => parseFloat(a) + parseFloat(b), 0));
                // Total over all pages
                // total = api
                //     .column( 5 )
                //     .data()
                //     .reduce( function (a, b) {
                //         return intVal(a) + intVal(b);
                //     }, 0 );

                let totalh = 0;
                if (promh.length == 0) {
                    total = 'Sin datos';
                    totalh = 'Sin datos';
                } else {
                    total = promh.reduce((a, b) => parseFloat(a) + parseFloat(b), 0) / promh.length;
                    totalh = promh.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

                }
                ph = Number(total).toFixed(2);
                th = Number(totalh).toFixed(2);

                // Update footer
                $(api.column(1).footer()).html(
                    Math.floor(ph) + " horas " + Math.floor((ph - Math.floor(ph)).toFixed(2) * 60) + " minutos"
                );
                $(api.column(4).footer()).html(
                    Math.floor(th) + " horas " + Math.floor((th - Math.floor(th)).toFixed(2) * 60) + " minutos"
                );
                $(api.column(8).footer()).html(
                    count
                );
                // $( api.column( 6 ).footer() ).html(
                //     total
                // );
            },
            columnDefs: [
                { targets: [7, 9], visible: false }
            ],
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    filename: 'Horario Empleado - ' + ($('#naem').val()),
                    title: 'Horario Empleado - ' + ($('#naem').val()),
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 8],
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                    className: 'btn btn-sm btn-personalizado outline',
                    download: ['donwload', 'open'],
                    orientation: 'landscape',
                    filename: 'Horario Empleado - ' + ($('#naem').val()),
                    title: 'Horario Empleado - ' + ($('#naem').val()),
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 8],
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
                                        text: 'EMPLEADO: ' + $('#gaf').val() + " " + $('#naem').val() + '\n DIRECCION: ' + $('#dirf').val() + "\n PUESTO: " + $('#puesf').val(),
                                        fontSize: 8,
                                        margin: [-295, 50, 0, 0]
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
                }
            ],

        });
    };

    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom: "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            buttons: [
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
            initDataTableCambio(id_persona);
            setTimeout(function () {
                tabla_cambio.ajax.reload();
            }, 100);

        }
    };
}();

function refresh_cambio() {
    tabla_cambio.ajax.reload();
}

function refresh() {
    tabla_horario.ajax.reload();
}


