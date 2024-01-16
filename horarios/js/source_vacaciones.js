var table_solicitudes, table_reporte, table_pendientes;
var VacacionesTableDatatables_listado = function () {
    var initDatatableSolicitudes = function () {
        table_solicitudes = $('#tb_vacaciones').DataTable({
            ordering: true,
            order: [[1, "desc"]],
            pageLength: 25,
            bProcessing: true,
            paging: true,
            info: true,
            responsive: true,
            language: {
                emptyTable: "No hay boletas para mostrar",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            ajax: {
                url: "horarios/php/back/boletas/get_solicitudes.php",
                type: "POST",
                data: {
                    tipo: function () { return $('#id_tipo_filtro').val() },
                    fff1: function () { return $('#fff1').val() },
                    fff2: function () { return $('#fff2').val() },
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            "aoColumns": [
                { "class": "text-center", mData: 'estado' },
                { "class": "text-center", mData: 'vac_id' },
                { "class": "text-center", mData: 'persona' },
                { "class": "text-center", mData: 'sol' },
                { "class": "text-center", mData: 'inicio' },
                { "class": "text-center", mData: 'fin' },
                { "class": "text-center", mData: 'dias' },
                { "class": "text-center", mData: 'pre' },
                { "class": "text-center", mData: 'diares' },
                { "class": "text-center", mData: 'periodo' },
                { "class": "text-center", mData: 'dir' },
                { "class": "text-center", mData: 'accion' },
                { "class": "text-center", mData: 'mesini' },
                { "class": "text-center", mData: 'mesfin' },
            ],
            buttons: [
                {
                    text: 'Pendientes <i class="fa fa-sync"></i>',
                    className: 'btn btn-soft-info',
                    action: function (e, dt, node, config) {
                        recargar_solicitudes(1);
                        table_solicitudes.columns(8).visible(false);
                        document.getElementById("hidfechas").hidden = true;
                    }
                },
                {
                    text: 'En proceso <i class="fa fa-sync"></i>',
                    className: 'btn btn-soft-info',
                    action: function (e, dt, node, config) {
                        recargar_solicitudes(4);
                        table_solicitudes.columns(8).visible(true);
                        table_solicitudes.column(8).order('asc');
                        document.getElementById("hidfechas").hidden = true;
                    }
                },
                {
                    text: '1 año <i class="fa fa-sync"></i>',
                    className: 'btn btn-soft-info',
                    action: function (e, dt, node, config) {
                        recargar_solicitudes(2);
                        table_solicitudes.columns(8).visible(false);
                        document.getElementById("hidfechas").hidden = true;
                    }
                },
                {
                    text: 'Todos <i class="fa fa-sync"></i>',
                    className: 'btn btn-soft-info',
                    action: function (e, dt, node, config) {
                        recargar_solicitudes(3);
                        table_solicitudes.columns(8).visible(false);
                        // document.getElementById("hidfechas").hidden = false;
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                    className: 'btn btn-personalizado outline',
                    filename: 'Reporte Boletas de Vacaciones',
                    title: 'Reporte Boletas de Vacaciones',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    },
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                    className: 'btn btn-personalizado outline',
                    download: ['donwload', 'open'],
                    orientation: 'landscape',
                    filename: 'Reporte Boletas de Vacaciones',
                    title: 'Reporte Boletas de Vacaciones',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    },
                    customize: function (doc) {
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';
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
                                        text: 'SAAS APP - MODULO DE CONTROL DE VACACIONES',
                                        fontSize: 6,
                                        margin: [-295, 50, 0, 0]
                                    },
                                    {
                                        alignment: 'left',
                                        bold: true,
                                        text: 'REPORTE DE BOLETAS DE VACACIONES',
                                        fontSize: 13,
                                        margin: [-150, 20]
                                    },
                                    {
                                        alignment: 'left',
                                        bold: true,
                                        text: '',
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
            initComplete: function () {
                table_solicitudes.columns(8).visible(false);
                var column = this.api().column(10);
                var select = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter2')
                    .on('change', function () {
                        var val = $(this).val();
                        column.search(val).draw()
                    });
                var offices = ['DESPACHO DEL SECRETARIO'
                    , 'SUBSECRETARIA ADMINISTRATIVA'
                    , 'SUBSECRETARIA DE SEGURIDAD'
                    , 'ASESORIA JURIDICA'
                    , 'AUDITORIA INTERNA'
                    , 'UNIDAD DE INSPECTORIA'
                    , 'DIRECCION ADMINISTRATIVA Y FINANCIERA'
                    , 'DIRECCION DE ASUNTOS INTERNOS'
                    , 'DIRECCION DE COMUNICACIONES E INFORMATICA'
                    , 'DIRECCION DE INFORMACION'
                    , 'DIRECCION DE RECURSOS HUMANOS'
                    , 'DIRECCION DE RESIDENCIAS'
                    , 'DIRECCION DE SEGURIDAD'
                    , 'SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES'
                    , 'S/D'
                ];
                offices.forEach(function (d) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                })
                var column4 = this.api().column(12);
                var select4 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter4')
                    .on('change', function () {
                        var val4 = $(this).val();
                        column4.search(val4).draw()
                    });
                var offices4 = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                offices4.forEach(function (d) {
                    select4.append('<option value="' + d + '">' + d + '</option>');
                })
                var column5 = this.api().column(13);
                var select5 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter5')
                    .on('change', function () {
                        var val5 = $(this).val();
                        column5.search(val5).draw()
                    });
                offices4.forEach(function (d) {
                    select5.append('<option value="' + d + '">' + d + '</option>');
                })
                var column3 = this.api().column(3);
                var select3 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter3')
                    .on('change', function () {
                        var val3 = $(this).val();
                        column3.search(val3).draw()
                    });
                var offices3 = ['2021', '2020', '2019', '2018', '2017', '2016'];
                offices3.forEach(function (d) {
                    select3.append('<option value="' + d + '">' + d + '</option>');
                })
                var column1 = this.api().column(0);
                var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
                    .appendTo('#filter1')
                    .on('change', function () {
                        var val1 = $(this).val();
                        column1.search(val1).draw()
                    });
                var offices1 = ['Solicitado', 'Autorizado en dirección', 'Anulado en dirección', 'Autorizado por RRHH', 'Anulado por RRHH'];
                offices1.forEach(function (d) {
                    select1.append('<option value="' + d + '">' + d + '</option>');
                })
            },
            columnDefs: [
                { responsivePriority: 0, targets: 11 },
                { visible: false, targets: [12, 13] }
            ]
        });
    };

    // var initDatatablePendientes = function() {
    //   table_pendientes = $('#tb_pendientes').DataTable({
    //     "ordering": false,
    //     "pageLength": 25,
    //     "bProcessing": true,
    //     "paging":true,
    //     "info":true,
    //     scrollX:        true,
    //     scrollY: false,
    //     scrollCollapse: true,

    //     fixedColumns:   true,
    //     fixedColumns: {
    //       leftColumns: 1
    //     },
    //       language: {
    //           emptyTable: "No hay noticias disponibles",
    //           sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
    //           //loadingRecords: " <div class='loaderr'></div> "
    //       },
    //       ajax:{
    //           url :"viaticos/php/back/listados/get_solicitudes.php",
    //           type: "POST",
    //           data:{
    //             tipo:2
    //           },
    //           error: function(){
    //               $("#post_list_processing").css("display","none");
    //           }
    //       },
    //       "aoColumns": [
    //           { "class" : "text-center", mData: 'nombramiento' },
    //           { "class" : "text-center", mData: 'fecha' },
    //           { "class" : "text-center", mData: 'direccion_solicitante' },

    //           { "class" : "text-center", mData: 'destino' },
    //           //{ "class" : "text-center", mData: 'motivo' },
    //           { "class" : "text-center", mData: 'fecha_ini' },
    //           { "class" : "text-center", mData: 'fecha_fin' },
    //           //{ "class" : "text-center", mData: 'personas' },
    //           { "class" : "text-center", mData: 'estado' },
    //           //{ "class" : "text-center", mData: 'progress' },
    //           { "class" : "text-center", mData: 'accion' }/**,
    //           { "class" : "text-center", mData: 'fecha_ini' }/*,
    //           { "class" : "text-center", mData: 'fecha_ini' }/*,
    //           { "class" : "text-center", mData: 'fuente' },
    //           { "class" : "text-center", mData: 'categoria' },
    //           { "class" : "text-center", mData: 'propietario' },
    //           { "class" : "text-center", mData: 'departamento' },
    //           { "class" : "text-center", mData: 'municipio' },
    //           { "class" : "text-center", mData: 'observaciones' },
    //           { "class" : "text-center", mData: 'accion' }¨*/
    //       ],
    //       buttons: [

    //       ],
    //       "columnDefs": [
    //         {
    //             "targets": 3,
    //             "width": "30px"
    //         },
    //         {
    //           'targets': [7],
    //           'searchable': false,
    //           'orderable': false,
    //           'className': 'dt-body-center',
    //           render: function(data, type, row, meta) {
    //             //turn row.id();
    //             //return format(row.serie);//format(row.serie);
    //             var menu='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion('+row.DT_RowId+',2)"><i class="fa fa-sliders-h"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu2'+row.DT_RowId+'"></div></div></div></div>';
    //           return  '<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'


    //         }

    //       }
    //       ]
    //   });

    //   $('#tb_empleados_asignar tbody').on( 'click', 'button', function () {
    //       var data = table_empleados_asignar.row( $(this).parents('tr') ).data();
    //       //alert( data['marca'] +"'s serie es: "+ data['serie'] );
    //       $('#myPopupInput1').val(data['id_persona']);
    //   } );




    // };

    // function get_fotografia(tabla, columna){
    //   $('#'+tabla+' tr').each(function(index, element){
    //      var id_persona = $(element).find("td").eq(columna).html();
    //      //console.log(id_persona);
    //      $.ajax({
    //        type: "POST",
    //        url: "empleados/php/back/empleados/get_fotografia.php",
    //        dataType:'json',
    //        data: {
    //          id_persona:id_persona
    //        },
    //        beforeSend:function(){
    //          //$(element).find("td").eq(columna).html('Cargando...');
    //        },
    //        success:function(data){

    //          $(element).find("td").eq(columna).html(data.fotografia);

    //            }
    //          });
    //    });

    // }

    // var initDataTableInsumos_reporte = function() {
    //   table_reporte = $('#tb_reporte').DataTable({
    //     "ordering": false,
    //     "pageLength": 25,
    //     "bProcessing": true,
    //     "paging":true,
    //     "info":true,
    //     responsive:true,


    //     language: {
    //         emptyTable: "No hay nombramientos para mostrar",
    //         loadingRecords: " <div class='spinner-grow text-info'></div> "
    //     },
    //     "ajax":{
    //           url :"viaticos/php/back/listados/get_viaticos_por_pais.php",
    //           type: "POST",
    //           data:{
    //             tipo:function() { return $('#id_tipo').val() },
    //             mes:function() { return $('#id_mes').val() },
    //             year:function() { return $('#id_year').val() }
    //           },
    //           error: function(){
    //             $("#post_list_processing").css("display","none");
    //           }
    //       },

    //          "aoColumns": [
    //            { "class" : "text-center", mData: 'empleado', "width":"20%"},
    //            { "class" : "text-center", mData: 'nombramiento', "width":"20%"},

    //            { "class" : "text-center", mData: 'direccion', "width":"20%"},
    //            { "class" : "text-center", mData: 'fecha_salida', "width":"20%"},
    //            { "class" : "text-center", mData: 'fecha_regreso', "width":"20%"},
    //            { "class" : "text-center", mData: 'pais',"width":"5%"},
    //            { "class" : "text-center", mData: 'departamento' },
    //            { "class" : "text-center", mData: 'municipio' },
    //            { "class" : "text-right", mData: 'total_real' },
    //            { "class" : "text-right", mData: 'total_mes' }/*,
    //            { "class" : "text-center", mData: 'cantidad_devuelta' },
    //            { "class" : "text-center", mData: 'accion' }/*,
    //            { "class" : "text-center", mData: 'accion' }/*,
    //            { "class" : "text-center", mData: 'accesos' },
    //            { "class" : "text-center", mData: 'accion' }*/

    //          ],
    //         buttons: [
    //           'excel'
    //         ],
    //         "columnDefs": [
    //           {responsivePriority:0, targets: 6},
    //           {responsivePriority:0, targets: 8},
    //           {responsivePriority:1, targets: 9}
    //         ],
    //         'rowsGroup': [0]
    //   });


    // };





    // DataTables Bootstrap integration
    var bsDataTables = function () {
        var $DataTable = jQuery.fn.dataTable;

        // Set the defaults for DataTables init
        jQuery.extend(true, $DataTable.defaults, {
            dom:
                "<'row'<'col-sm-3'l><'col-sm-6'B><'col-sm-3'f>>" +
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
            // initDataTableInsumos_reporte();
            initDatatableSolicitudes();
            // initDatatablePendientes();
            document.getElementById("hidfechas").hidden = true;
        }
    };
}();

function recargar_fechas(date1, date2) {
    $('#id_tipo_filtro').val(5);
    $('#fff1').val(date1);
    $('#fff2').val(date2);
    table_solicitudes.ajax.reload(null, false);
    table_solicitudes.columns(8).visible(false);
    table_solicitudes.column(1).order('desc');

}

function recargar_solicitudes(tipo) {
    $('#id_tipo_filtro').val(tipo);
    table_solicitudes.ajax.reload(null, false);
    table_solicitudes.columns(8).visible(false);
    table_solicitudes.column(1).order('desc');
}

// Initialize when page loads
jQuery(function () { VacacionesTableDatatables_listado.init(); });
