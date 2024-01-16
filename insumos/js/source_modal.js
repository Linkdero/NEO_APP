var table_movimientos_empleado, table_insumos_transaccion, table_insumos_resguardo;
var table_insumos_asignar, table_movimientos_empleado_resguardo, table_totales_insumos, table_totales_direccion;
var table_insumos_solvencia, table_bodegas;
var table_empleados_asignados;
var InsumosTableDatatables_listado_modal = function () {


    var initDataTableMovimientos_empleado_listado = function () {
        table_movimientos_empleado = $('#tb_movimientos_empleado').DataTable({
            'ordering': false,
            'pageLength': 10,
            'bProcessing': true,
            'lengthChange': false,
            'paging': false,
            'info': true,
            'search': true,
            'searching': true,
            sInfo: "Mostrando Insumos del _START_ al _END_ de un total de _TOTAL_ insumos",
            sInfoEmpty: "Mostrando insumos del 0 al 0 de un total de 0 insumos",
            sInfoFiltered: "(filtrado de un total de _MAX_ insumos)",

            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_insumos_asignados_by_empleado_by_bodega.php",
                type: "POST",
                data: {
                    id_persona: function () {
                        return $('#id_persona').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            columns: [
                { name: 'transaccion' },
                { name: 'descripcion' },
                { name: 'numero_serie' },
                { name: 'movimiento' },
                { name: 'cantidad_entregada' },
                { name: 'cantidad_devuelta' },
                { name: 'cantidad' },
                { name: 'accion' }
            ],

            "aoColumns": [
                { "class": "text-center", mData: 'transaccion' },
                { "class": "text-center", mData: 'anotaciones' },
                { "class": "text-center", mData: 'descripcion', "width": "20%" },

                { "class": "text-center", mData: 'numero_serie' },
                { "class": "text-center", mData: 'movimiento' },
                //{ "class" : "text-center", mData: 'propietario' },
                { "class": "text-center", mData: 'cantidad_entregada' },
                { "class": "text-center", mData: 'cantidad_devuelta' },
                { "class": "text-center", mData: 'cantidad', "width": "5%" },
                { "class": "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/

            ],
            buttons: [],
            'rowsGroup': [0, 1]
        });


        $('#tb_movimientos_empleado').on('click', 'tr', function () {
            var id = table_movimientos_empleado.row(this).id();
            //alert( 'Clicked row id '+id );
        });
    };

    var initDataTableMovimientos_empleado_listado_resguardo = function () {
        table_movimientos_empleado_resguardo = $('#tb_movimientos_empleado_resguardo').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "search": true,
            "searching": true,

            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos en resguardo",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_insumos_asignados_by_empleado_by_bodega_resguardo.php",
                type: "POST",
                data: {
                    id_persona: function () {
                        return $('#id_persona').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            columns: [
                { name: 'transaccion' },
                { name: 'descripcion' },
                { name: 'numero_serie' },
                { name: 'movimiento' },

                { name: 'cantidad_devuelta' },
                { name: 'cantidad_entregada' },
                { name: 'cantidad' },
                { name: 'accion' }
            ],

            "aoColumns": [
                { "class": "text-center", mData: 'transaccion' },
                { "class": "text-center", mData: 'anotaciones' },
                { "class": "text-center", mData: 'descripcion', "width": "20%" },

                { "class": "text-center", mData: 'numero_serie' },
                { "class": "text-center", mData: 'movimiento' },
                //{ "class" : "text-center", mData: 'propietario' },

                { "class": "text-center", mData: 'cantidad_devuelta' },
                { "class": "text-center", mData: 'cantidad_entregada' },
                { "class": "text-center", mData: 'cantidad', "width": "5%" },
                { "class": "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/

            ],
            buttons: [],
            'rowsGroup': [0, 1]
        });

        $('#tb_movimientos_empleado_resguardo').on('click', 'tr', function () {
            var id = table_movimientos_empleado_resguardo.row(this).id();
            //alert( 'Clicked row id '+id );
        });
    };

    var initDataTableInsumos_listado = function () {
        $('#tb_insumos thead tr').clone(true).appendTo('#tb_insumos thead');
        $('#tb_insumos thead tr:eq(1) th').each(function (i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control " placeholder="" />');

            $('input', this).on('keyup change', function () {
                if (table_insumos.column(i).search() !== this.value) {
                    table_insumos
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });

        table_insumos = $('#tb_insumos').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "info": true,
            orderCellsTop: true,
            columns: [
                { name: 'sicoin' },
                { name: 'tipo' },
                { name: 'marca' },
                { name: 'modelo' },
                { name: 'serie' },
                { name: 'estante' },
                { name: 'existencia' },
                { name: 'estado' },
                { name: 'gafete' },
                { name: 'empleado' },
                { name: 'accion' }
            ],

            //"dom": '<"">frtip',

            language: {
                emptyTable: "Esta bodega no tiene insumos agregados",
                //loadingRecords: " <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_insumos_by_bodega.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'sicoin', "width": "2%" },
                { "class": "text-center", mData: 'tipo' },
                { "class": "text-center", mData: 'marca' },
                { "class": "text-center", mData: 'modelo' },
                { "class": "text-center", mData: 'serie' },
                { "class": "text-center", mData: 'estante' },
                { "class": "text-center", mData: 'existencia' },
                { "class": "text-center", mData: 'estado' },
                { "class": "text-center", mData: 'gafete' },
                { "class": "text-center", mData: 'empleado' },

                { "class": "text-center", mData: 'accion' }
            ],
            buttons: [
                'excel'
            ],

            'columnDefs': [{
                'targets': [10],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function (data, type, row, meta) {

                    return '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto" href="insumos/php/front/insumos/ver_insumo_detalle.php?id_insumo=' + row.DT_RowId + '"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span>';
                }

            }],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {


                $('td:eq(5)', nRow).html('<a class="" href="#" ">' + aData.estante + '</a>').editable({
                    pk: aData.serie,
                    name: 0,
                    debug: function (value) {
                        console.log(value);
                    },
                    url: 'insumos/php/back/funciones/update_xeditable.php',
                    send: 'always',
                });

                return nRow;

            },


            "initComplete": function (settings, json) {
                //alert( 'DataTables has finished its initialisation.' );
                //swal2-container swal2-center swal2-fade swal2-shown
            }
        });

        $('#select-all').click(function (event) {
            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function () {
                    this.checked = true;
                    var x;
                    var positions = $('input:checkbox[name="pos"]:checked').map(function () {

                        x = this.value;
                        return x;
                    }).get().join('|');

                    //filter in column 1, with an regex, no smart filtering, not case sensitive
                    table_insumos.column(6).search(positions, true, false, false).draw(false);
                    console.log(positions);
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                    var x;
                    var positions = $('input:checkbox[name="pos"]:not(:checked)').map(function () {

                        x = this.value;
                        return x;
                    }).get().join('|');
                    //alert(x);

                    //filter in column 1, with an regex, no smart filtering, not case sensitive
                    table_insumos.column(6).search(positions, true, false, false).draw(false);
                });
            }
        });

        $('input:checkbox').on('change', function () {
            //build a regex filter string with an or(|) condition
            var x;
            var positions = $('input:checkbox[name="pos"]:checked').map(function () {

                x = this.value;
                return x;
            }).get().join('|');

            //filter in column 1, with an regex, no smart filtering, not case sensitive
            table_insumos.column(6).search(positions, true, false, false).draw(false);
            //alert(x);

            //build a filter string with an or(|) condition
            var offices = $('input:checkbox[name="ofc"]:checked').map(function () {
                return this.value;
            }).get().join('|');

            //now filter in column 2, with no regex, no smart filtering, not case sensitive
            table_insumos.column(2).search(offices, true, false, false).draw(false);

        });

        $('#tb_insumos tbody').on('click', 'td.details-control', function () {
            var tr = $(this).parents('tr');
            var currentId = $(this).attr('id');
            var row = table_insumos.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

        /*$('#tb_insumos tbody').on( 'click', 'button', function () {
            var data = table_insumos.row( $(this).parents('tr') ).data();
            //alert( data['marca'] +"'s serie es: "+ data['serie'] );
            $('#myPopupInput').val(data['serie']);
        } );*/
        function format(d) {
            //alert(d.serie);
            var div = $('<div/>');

            var url = 'insumos/php/back/insumos/get_insumo.php'
            $.ajax({
                type: "POST",
                url: url,
                data: { serie: d.serie },
                beforeSend: function () {
                    //$('#response').html('<span class="text-info">Loading response...</span>');


                    $(div).fadeOut(0).html('<br><br><br><div class="loaderr"></div><br>').fadeIn(0);
                },
                success: function (datos) {


                    $(div).fadeOut(0).html('message').fadeIn(0);

                }
            });

            return div;


        }
    };

    var initDataTableInsumos_listado_asignar = function () {
        table_insumos_asignar = $('#tb_insumos_asignar').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_insumos_by_bodega_asignar.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'sicoin', "width": "2%" },
                { "class": "text-center", mData: 'tipo' },
                { "class": "text-center", mData: 'marca' },
                { "class": "text-center", mData: 'modelo' },
                { "class": "text-center", mData: 'serie' },
                { "class": "text-center", mData: 'existencia' },
                { "class": "text-center", mData: 'estado' },

                { "class": "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/


            ],
            buttons: [
                'excel'
            ]
        });

        $('#tb_insumos_asignar tbody').on('click', 'button', function () {
            var data = table_insumos_asignar.row($(this).parents('tr')).data();
            //alert( data['marca'] +"'s serie es: "+ data['serie'] );
            $('#myPopupInput').val(data['serie']);
        });


    };

    var initDataTableInsumos_transaccion_listado = function () {
        table_insumos_transaccion = $('#tb_insumos_transaccion').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "search": false,
            "searching": false,

            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_insumos_por_transaccion.php",
                type: "POST",
                data: {
                    transaccion: function () {
                        return $('#transaccion').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'marca', "width": "20%" },
                { "class": "text-center", mData: 'modelo', "width": "20%" },
                { "class": "text-center", mData: 'serie', "width": "20%" },
                { "class": "text-center", mData: 'cantidad', "width": "20%" }/*,
               { "class" : "text-center", mData: 'estante',"width":"5%"},
               { "class" : "text-center", mData: 'numero_serie' },
               { "class" : "text-center", mData: 'movimiento' },
               { "class" : "text-center", mData: 'propietario' },
               { "class" : "text-center", mData: 'cantidad' },
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/

            ],
            buttons: []
        });


    };

    var initDataTableBodegas = function () {
        table_bodegas = $('#tb_bodegas').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": false,
            "paging": false,
            "ordering": false,
            "info": false,
            "search": false,
            "searching": false,

            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_bodegas.php",
                type: "POST",
                //data:{ transaccion:function() { return $('#transaccion').val() }},
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'id_bodega', "width": "20%" },
                { "class": "text-center", mData: 'bodega', "width": "20%" },
                { "class": "text-center", mData: 'total', "width": "20%" },
                { "class": "text-center", mData: 'activos', "width": "20%" },
                { "class": "text-center", mData: 'inactivos', "width": "5%" },
                { "class": "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'numero_serie' },
               { "class" : "text-center", mData: 'movimiento' },
               { "class" : "text-center", mData: 'propietario' },
               { "class" : "text-center", mData: 'cantidad' },
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/

            ],
            buttons: []
        });


    };

    var initDataTableInsumos_resguardo_listado = function () {
        table_insumos_resguardo = $('#tb_insumos_resguardo').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_insumos_resguardo_by_bodega.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'tipo' },
                { "class": "text-center", mData: 'marca' },
                { "class": "text-center", mData: 'modelo' },
                { "class": "text-center", mData: 'serie' },
                { "class": "text-center", mData: 'estado' },
                { "class": "text-center", mData: 'gafete' },
                { "class": "text-center", mData: 'empleado' },
                { "class": "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/


            ],
            buttons: [
                'excel'
            ]
        });

        $('#tb_insumos tbody').on('click', 'button', function () {
            var data = table_insumos.row($(this).parents('tr')).data();
            //alert( data['marca'] +"'s serie es: "+ data['serie'] );
            $('#myPopupInput').val(data['serie']);
        });

    };

    var initDataTableTotales_Insumos = function () {
        table_totales_insumos = $('#tb_totales_insumos').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/reportes/get_totales_por_status.php",
                type: "POST",
                data: {
                    tipo: function () {
                        return $('#tipo').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-left", mData: 'MARCA' },
                { "class": "text-center", mData: 'DISPONIBLE' },
                { "class": "text-center", mData: 'ASIGNADO' },
                { "class": "text-center", mData: 'ASIGNADO TEMPORAL' },
                { "class": "text-center", mData: 'EXTRAVIADO' },
                { "class": "text-center", mData: 'MAL ESTADO' },
                // {"class": "text-center", mData: 'BAJA'},
                { "class": "text-center", mData: 'TOTAL' }


            ],
            buttons: [
                'excel'
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 1;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return Number(a) + Number(b);
                        }, 0);
                    // Update footer
                    $(api.column(j).footer()).html(pageTotal);
                    j++;
                }
            }

        });


    };

    var initDataTableTotales_Insumos_Movil = function () {
        table_totales_insumos = $('#tb_totales_insumos_movil').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/reportes/get_totales_por_status.php",
                type: "POST",
                data: {
                    tipo: function () {
                        return $('#tipo').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-left", mData: 'ESTADO' },
                { "class": "text-center", mData: 'HUAWEI' },
                { "class": "text-center", mData: 'IPHONE' },
                { "class": "text-center", mData: 'SAMSUNG' },
                //{ "class" : "text-center", mData: 'HYT' },
                { "class": "text-center", mData: 'TOTAL' }/*,
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/


            ],
            buttons: [
                'excel'
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 1;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return Number(a) + Number(b);
                        }, 0);
                    // Update footer
                    $(api.column(j).footer()).html(pageTotal);
                    j++;
                }
            }

        });


    };

    var initDataTableTotales_Insumos_Armas = function () {
        table_totales_insumos = $('#tb_totales_insumos_armas').DataTable({
            "ordering": false,
            "pageLength": 25,
            "bProcessing": true,
            "paging": true,
            "info": true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            fixedColumns: {
                leftColumns: 1
            },

            //"dom": '<"">frtip',

            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/reportes/get_totales_por_status.php",
                type: "POST",
                data: {
                    tipo: function () {
                        return $('#tipo').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-left", mData: 'ESTADO' },
                { "class": "text-center", mData: 'DAEWOO' },
                { "class": "text-center", mData: 'UZI' },
                { "class": "text-center", mData: 'KALASHNIKOV' },
                { "class": "text-center", mData: 'MEPOR21' },
                { "class": "text-center", mData: 'EAGLE' },
                { "class": "text-center", mData: 'ROSSI' },
                { "class": "text-center", mData: 'GENERICO' },
                { "class": "text-center", mData: 'DESANTIS' },
                { "class": "text-center", mData: 'GALIL' },
                { "class": "text-center", mData: 'CAA' },
                { "class": "text-center", mData: 'VALTRO' },
                { "class": "text-center", mData: 'FNHERSTAL' },
                { "class": "text-center", mData: 'JERICHO' },
                { "class": "text-center", mData: 'TAURUS' },
                { "class": "text-center", mData: 'TANFOGLIO' },
                { "class": "text-center", mData: 'FOBUS' },
                { "class": "text-center", mData: 'SM' },
                { "class": "text-center", mData: 'BLACKHAWK' },
                { "class": "text-center", mData: 'STOEGER' },
                { "class": "text-center", mData: 'BERETTA' },
                { "class": "text-center", mData: 'CZ' },
                { "class": "text-center", mData: 'BUSHNELL' },
                { "class": "text-center", mData: 'ANAJMAN' },
                { "class": "text-center", mData: 'SIGPRO' },
                { "class": "text-center", mData: 'TAVOR' },
                { "class": "text-center", mData: 'SEGARMOR' },
                { "class": "text-center", mData: 'COLT' },
                { "class": "text-center", mData: 'GLOCK' },
                { "class": "text-center", mData: 'REMINGTON' },
                { "class": "text-center", mData: 'TOTAL' }

            ],
            buttons: [
                'excel'
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 1;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return Number(a) + Number(b);
                        }, 0);
                    // Update footer
                    $(api.column(j).footer()).html(pageTotal);
                    j++;
                }
            }

        });


    };

    var initDataTableInsumosSolvencia_empleado_listado = function () {
        table_insumos_solvencia = $('#tb_insumos_solvencia').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": false,
            "paging": false,
            "info": true,
            "search": true,
            "searching": true,
            sInfo: "Mostrando Insumos del _START_ al _END_ de un total de _TOTAL_ insumos",
            sInfoEmpty: "Mostrando insumos del 0 al 0 de un total de 0 insumos",
            sInfoFiltered: "(filtrado de un total de _MAX_ insumos)",

            language: {
                emptyTable: "El empleado no tiene insumos asignados.",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },

            "ajax": {
                url: "insumos/php/back/listados/get_insumos_solvencia_by_empleado.php",
                type: "POST",
                data: {
                    id_persona: function () {
                        return $('#id_persona').val()
                    },
                    tipo_movimiento: function () {
                        return $('#tipo_movimiento').val()
                    }
                },
                error: function (xhr, error, thrown) {
                    //console.log('error: '+thrown);
                }
            },
            columns: [
                { name: 'transaccion' },
                { name: 'anotacion' },
                { name: 'descripcion' },
                { name: 'numero_serie' },
                { name: 'movimiento' },
                { name: 'cantidad_entregada' },
                { name: 'cantidad_devuelta' }
            ],
            "aoColumns": [
                { "class": "text-center", mData: 'transaccion' },
                { "class": "text-center", mData: 'anotaciones' },
                { "class": "text-center", mData: 'descripcion', "width": "20%" },
                { "class": "text-center", mData: 'numero_serie' },
                { "class": "text-center", mData: 'movimiento' },
                { "class": "text-center", mData: 'cantidad_entregada' },
                { "class": "text-center", mData: 'cantidad_devuelta' }
            ],
            buttons: [],
            rowsGroup: [0, 1]
        });


        $('#tb_insumos_solvencia').on('click', 'tr', function () {
            var id = table_insumos_solvencia.row(this).id();
        });
    };

    var initDataTableTotales_Direccion = function () {
        table_totales_direccion = $('#tb_totales_por_direccion').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,


            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                //loadingRecords:" <h3 class='text-info'><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> ",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "insumos/php/back/reportes/get_totales_por_direccion.php",
                type: "POST",
                data: {
                    tipo: function () {
                        return $('#tipo').val()
                    }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-left", mData: 'direccion' },
                { "class": "text-center", mData: 'total_ap' },
                { "class": "text-center", mData: 'total_at' }/*,
               { "class" : "text-center", mData: 'temporal'}/*,
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/


            ],
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exportar <i class="fa fa-download"></i>',
                    className: 'btn btn-sm btn-personalizado outline'
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 1;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return Number(a) + Number(b);
                        }, 0);
                    // Update footer
                    $(api.column(j).footer()).html(pageTotal);
                    j++;
                }
            }

        });


    };

    // EMPLEADOS ASIGNADOS

    var initDataTableEmpleados_asignados = function () {
        table_empleados_asignados = $('#tb_empleados_asignados').DataTable({
            "ordering": false,
            "pageLength": 10,
            "bProcessing": true,
            "lengthChange": true,
            "paging": true,
            "ordering": false,
            "info": true,
            "search": true,
            "searching": true,

            //"dom": '<"">frtip',


            language: {
                emptyTable: "El empleado no tiene insumos asignados",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            "ajax": {
                url: "insumos/php/back/listados/get_all_empleados_asignados.php",
                type: "POST",
                //data:{ transaccion:function() { return $('#transaccion').val() }},
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },

            "aoColumns": [
                { "class": "text-center", mData: 'id_persona', "width": "5%" },
                { "class": "text-left", mData: 'nombres', "width": "20%" },
                { "class": "text-left", mData: 'apellidos', "width": "20%" },
                { "class": "text-left", mData: 'puesto', "width": "20%" },
                { "class": "text-center", mData: 'direccion', "width": "20%" },
                { "class": "text-center", mData: 'marca', "width": "5%" },
                { "class": "text-center", mData: 'modelo', "width": "5%" },
                { "class": "text-center", mData: 'serie', "width": "5%" }/*,
               { "class" : "text-center", mData: 'accion'}/*,
               { "class" : "text-center", mData: 'numero_serie' },
               { "class" : "text-center", mData: 'movimiento' },
               { "class" : "text-center", mData: 'propietario' },
               { "class" : "text-center", mData: 'cantidad' },
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/

            ],
            buttons: [
                {
                    extend: 'excel',
                    text: 'Exportar <i class="fa fa-download"></i>',
                    className: 'btn btn-sm btn-personalizado outline'
                }
            ]
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
            initDataTableMovimientos_empleado_listado();
            initDataTableInsumos_listado();
            initDataTableInsumos_transaccion_listado();
            initDataTableInsumos_resguardo_listado();
            initDataTableInsumos_listado_asignar();
            initDataTableMovimientos_empleado_listado_resguardo();
            initDataTableTotales_Insumos();
            initDataTableTotales_Direccion();
            initDataTableInsumosSolvencia_empleado_listado();
            initDataTableBodegas();
            initDataTableEmpleados_asignados();
            initDataTableTotales_Insumos_Movil();
            initDataTableTotales_Insumos_Armas();
        }
    };
}();

function reload_movimientos_empleado_by_bodega() {
    table_movimientos_empleado.ajax.reload(null, false);
}

function reload_movimientos_empleado_by_bodega_resguardo() {
    table_movimientos_empleado_resguardo.ajax.reload(null, false);
}

function reload_insumos_listado() {
    table_insumos.ajax.reload(null, false);
}

function reload_accesos_pendiente() {
    table_accesos_persona_pendiente.ajax.reload(null, false);
}

function reload_totales_insumos() {
    table_totales_insumos.ajax.reload(null, false);
}

function reload_insumos_solvencia() {
    table_insumos_solvencia.ajax.reload(null, false);
}


// Initialize when page loads
jQuery(function () {
    InsumosTableDatatables_listado_modal.init();
});
