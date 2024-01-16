
var tabla_emps_listado, tabla_plazas_listado,tabla_emps_listado_,tabla_contratos_listado;
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();
today = mm + '-' + dd + '-' + yyyy;
console.log(today);
function findWithAttr(array, attr, value) {
    for (var i = 0; i < array.length; i += 1) {
        if (array[i][attr] === value) {
            return i;
        }
    }
    return -1;
}

var EmpleadosTableDatatables_listado = function () {
    var initDataTableEmpleados_listado = function () {
      $('#tb_empleados_listado thead tr').clone(true).appendTo('#tb_empleados_listado thead');
      $('#tb_empleados_listado thead tr:eq(1) th').each(function (i) {
          $(this).html('<input type="text" class="form-control form-corto_2 form-control-sm" placeholder="" />');
          $('input', this).on('keyup change', function () {
              if (tabla_emps_listado_.column(i).search() !== this.value) {
                  tabla_emps_listado_
                      .column(i)
                      .search(this.value)
                      .draw();
              }
          });
      });
        tabla_emps_listado_ = $('#tb_empleados_listado').DataTable({
          dom:
              "<'row'<'col-sm-8'B><'col-sm-4'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            ordering: false,
            pageLength: 10,
            responsive:true,
            "bProcessing": true,
            scrollY: '50vh',
            scrollX:        false,
            language: {
              emptyTable: "No hay empleados para mostrar",
              sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "empleados/php/back/listados/get_empleados_rrhh.php",
                type: "POST",
                data:{
                  tipo: function () { return $('#tipo_reload').val() }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            "aoColumns": [
                { "class": "text-center", mData: 'id_persona' },
                { "class": "text-center", mData: 'id_persona' },
                { "class": "text-left", mData: 'empleado' },
                { "class": "text-center", mData: 'f_nac' },
                { "class": "text-center", mData: 'nit' },
                { "class": "text-center", mData: 'igss' },
                { "class": "text-center", mData: 'descripcion' },
                { "class": "text-center", mData: 'status' },
                { "class": "text-center", mData: 'nisp' },
                { "class": "text-center", mData: 'f_ingreso' },
                { "class": "text-center", mData: 'f_baja' },
                { "class": "text-center", mData: 'accion' },
                { "class": "text-center", mData: 'dir_funcional' },

            ],
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Exportar',
                className: 'btn btn-personalizado',
                title: 'Listado de Empleados ' + today,
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9,10,12]
                }
            },
            {
              text: 'Aspirantes <i class="fa fa-user-plus"></i>',
              className: 'btn  btn-personalizado btn-aspirante',
              action: function ( e, dt, node, config ) {
                recargarEmpleados(5)
                setActiveButton('btn-aspirante');
              }
            },
            {
              text: 'Activos <i class="fa fa-user-check"></i>',
              className: 'btn  btn-personalizado btn-activo active',
              action: function ( e, dt, node, config ) {
                recargarEmpleados(1);
                setActiveButton('btn-activo');
              }
            },
            {
              text: 'Apoyo <i class="fa fa-user-secret"></i>',
              className: 'btn  btn-personalizado btn-apoyo',
              action: function ( e, dt, node, config ) {
                recargarEmpleados(4);
                setActiveButton('btn-apoyo');

              }
            },
            {
              text: 'Bajas <i class="fa fa-user-slash"></i>',
              className: 'btn  btn-personalizado btn-baja',
              action: function ( e, dt, node, config ) {
                recargarEmpleados(2);
                setActiveButton('btn-baja');
              }
            },
            {
              text: 'Denegados <i class="fa fa-user-times"></i>',
              className: 'btn  btn-personalizado btn-denegado',
              action: function ( e, dt, node, config ) {
                recargarEmpleados(3);
                setActiveButton('btn-denegado');
              }
            }
            ],
            // "order": false,
            'columnDefs': [
                // { 'width': 1, 'targets': [0] },
                // { 'max-width': 1, 'targets': [0] },
                { responsivePriority: 1, targets: [0,1,2,3,11] },

                { "visible": false, "targets": [8,12] }
            ],
            drawCallback: function (settings) {
                get_fotografia('tb_empleados_listado', 0);
            },
            initComplete: function () {
              $('body').on("click", ".dropdown-menu-", function (e) {
                $(this).parent().is(".show") && e.stopPropagation();
              });
                var column = this.api().column(6);
                var select = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#filter1')
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


                var column2 = this.api().column(3);
                var column3 = this.api().column(6);
                var select2 = $('<select class="form-control  form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#filter2')
                    .on('change', function () {
                        var val2 = $(this).val();
                        column2.search(val2).draw();

                    });

                var offices2 = ['Enero'
                    , 'Febrero'
                    , 'Marzo'
                    , 'Abril'
                    , 'Mayo'
                    , 'Junio'
                    , 'Julio'
                    , 'Agosto'
                    , 'Septiembre'
                    , 'Octubre'
                    , 'Noviembre'
                    , 'Diciembre'];

                offices2.forEach(function (d) {
                    select2.append('<option value="' + d + '">' + d + '</option>');
                })


            }
        });


        $('#modal-remoto-lgg2').on('click', '.out', function () {
          valord = $('#id_cambio').val();
          if(valord == 1){
            tipo = $('#id_tipo_filtro').val()
            recargarEmpleados(tipo);
          }
          $('#modal-remoto-lgg2').modal('hide');
        });



        /*setInterval( function () {
          tabla_emps_listado.ajax.reload(null, false);
        }, 100000 );*/



    };

    var initDataTableEmpleados_funcional_listado = function () {
        $('#tb_empleados_listado_funcionales thead tr').clone(true).appendTo('#tb_empleados_listado_funcionales thead');
        $('#tb_empleados_listado_funcionales thead tr:eq(1) th').each(function (i) {
            $(this).html('<input type="text" class="form-control form-corto_2" placeholder="" />');
            $('input', this).on('keyup change', function () {
                if (tabla_emps_listado.column(i).search() !== this.value) {
                    tabla_emps_listado
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
        tabla_emps_listado = $('#tb_empleados_listado_funcionales').DataTable({
            ordering: true,
            pageLength: 25,
            //"responsive": true,
            /*"columnDefs": [
                        { responsivePriority: 1, targets: 0 },
                        { responsivePriority: 2, targets: 4 },
                    { responsivePriority: 1, targets: 3 },
                    ],*/
            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "empleados/php/back/listados/get_empleados_por_direccion_funcional.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'id_persona' },
                { "class": "text-left", mData: 'empleado' },
                { "class": "text-center", mData: 'dir_nominal' },
                { "class": "text-center", mData: 'dir_funcional' },
                { "class": "text-center", mData: 'renglon' },
                { "class": "text-center", mData: 'direccion' },
                { "class": "text-center", mData: 'fecha_i' },
                { "class": "text-center", mData: 'puesto_n' },
                { "class": "text-center", mData: 'puesto_f' },
                { "class": "text-right", mData: 'sueldo' },
                { "class": "text-center", mData: 'accion' }
            ],
            buttons: [
                'excelHtml5'
            ],
            order: [[3, 'desc']],
            columnDefs: [
                { 'width': 1, 'targets': [2] },
                { 'max-width': 1, 'targets': [2] },
                { 'visible': true, "targets": 3 }
            ],
            drawCallback: function (settings) {
                get_fotografia('tb_empleados_listado_funcionales', 0);
                let api = this.api();
                let rows = api.rows({ page: 'current' }).nodes();
                let last = null;
                let arrayDirecciones = [];
                let posicion = 0;
                api.rows({ search: 'applied' }).data().each(function (element) {
                    if (element.dir_funcional != null) {
                        posicion = findWithAttr(arrayDirecciones, "direccion", element.dir_funcional);
                        if (posicion >= 0) {
                            arrayDirecciones[posicion].total = (parseFloat(arrayDirecciones[posicion].total) + parseFloat(element.sueldo));
                        } else {
                            arrayDirecciones.push({
                                "direccion": element.dir_funcional,
                                "total": parseFloat(element.sueldo)
                            });
                        }
                    }
                });
                api.column(3, { page: 'current' }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr style="background: #3BAFDA;"><td colspan="11" style="color:#F5F7FA;font-size:18px;">' + group + ' (Total: Q. ' + arrayDirecciones[findWithAttr(arrayDirecciones, "direccion", group)].total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + ')</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });

    };



    var initDataTablePlazas_listado = function () {
        let groupColumn = 4;
        $('#tb_plazas_listado thead tr').clone(true).appendTo('#tb_plazas_listado thead');
        $('#tb_plazas_listado thead tr:eq(1) th').each(function (i) {
            $(this).html('<input type="text" class="form-control form-corto_2" placeholder="" />');
            $('input', this).on('keyup change', function () {
                if (tabla_plazas_listado.column(i).search() !== this.value) {
                    tabla_plazas_listado
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });

        tabla_plazas_listado = $('#tb_plazas_listado').DataTable({
            ordering: true,
            pageLength: 25,
            language: {
                emptyTable: "No hay partidas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax: {
                url: "empleados/php/back/listados/get_plazas.php",
                type: "POST",
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            aoColumns: [
                { "class": "text-center", mData: 'gafete' },
                { "class": "text-left", mData: 'partida' },
                { "class": "text-center", mData: 'cod_plaza' },
                { "class": "text-left", mData: 'puesto' },
                { "class": "text-left", mData: 'direccion' },
                { "class": "text-center", mData: 'estado' },
                { "class": "text-center", mData: 'renglon' },
                { "class": "text-center", mData: 'empleado' },
                { "class": "text-center", mData: 'fecha_efectiva' },
                { "class": "text-right", mData: 'sueldo' },
                { "class": "text-center", mData: 'accion' }

            ],
            buttons: [
              {
                  extend: 'excel',
                  text: '<i class="fa fa-file-excel"></i> Exportar',
                  className: 'btn btn-personalizado',
                  title: 'Listado de Plazas ' + today,
                  exportOptions: {
                      columns: [1, 2, 3, 4, 5, 6, 7, 8, 9,10,12]
                  }
              },
              {
                text: 'Recargar <i class="fa fa-sync"></i>',
                className: 'btn  btn-personalizado',
                action: function ( e, dt, node, config ) {
                  recargarPlazas()
                }
              },
              {
                text: 'Agregar <i class="fa fa-plus-circle"></i>',
                className: 'btn  btn-personalizado',
                action: function ( e, dt, node, config ) {
                  var imgModal = $('#modal-remoto-lgg2');
                  var imgModalBody = imgModal.find('.modal-content');
                  //let id_persona = parseInt($('#bar_code').val());
                  $.ajax({
                    type: "GET",
                    url: "empleados/php/front/plazas/plaza_nueva.php",
                    dataType: 'html',
                    data:{
                      opcion:1
                    },
                    beforeSend: function () {
                      imgModal.modal('show');
                      imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                    },
                    success: function (data) {
                      $(".btn-add-fact").removeClass("active");
                      imgModalBody.html(data);
                    }
                  });
                }
              },
            ],
            order: [[groupColumn, 'desc']],
            columnDefs: [
                { 'width': 1, 'targets': [2] },
                { 'max-width': 1, 'targets': [2] },
                { 'visible': true, "targets": groupColumn }
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
              if(aData.flag==1){
                $('td:eq(1)', nRow).html('<a class="" href="#" ">'+aData.partida+'</a>').editable({
                  url: 'empleados/php/back/plazas/update_partida.php',
                  pk:aData.id_plaza,
                  name:1,
                  type:'textarea',
                  rows:'3',
                  mode: 'popup',
                  send:'always',
                  ajaxOptions: { dataType: 'json' },
                  type: 'textarea',
                  display: function(value, response) {
                    return false;   //disable this method
                  },
                  success: function(response, newValue) {
                    if(response.msg=='Done'){
                      $(this).text(response.valor_nuevo);
                      //appVueJusDetalle.getJustificacionById()
                    }
                  }
                });
              }
            },
            drawCallback: function (settings) {
                get_fotografia('tb_plazas_listado', 0);
                let api = this.api();
                let rows = api.rows({ page: 'current' }).nodes();
                let last = null;
                let arrayDirecciones = [];
                let posicion = 0;
                api.rows({ search: 'applied' }).data().each(function (element) {
                    if (element.direccion != null) {
                        posicion = findWithAttr(arrayDirecciones, "direccion", element.direccion);
                        if (posicion >= 0) {
                            arrayDirecciones[posicion].total = (parseFloat(arrayDirecciones[posicion].total) + parseFloat(element.sueldo));
                        } else {
                            arrayDirecciones.push({
                                "direccion": element.direccion,
                                "total": element.sueldo
                            });
                        }
                    }
                });
                api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr style="background: #76a4fb;"><td colspan="11" style="color:black;font-size:18px;">' + group + ' (Total: Q. ' + arrayDirecciones[findWithAttr(arrayDirecciones, "direccion", group)].total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') + ')</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    };

    function get_fotografia(tabla, columna) {
        $('#' + tabla + ' tr').each(function (index, element) {
            var id_persona = $(element).find("td").eq(columna).html();
            //console.log(id_persona);
            $.ajax({
                type: "POST",
                url: "empleados/php/back/empleados/get_fotografia.php",
                dataType: 'json',
                data: {
                    id_persona: id_persona
                },
                beforeSend: function () {
                    //$(element).find("td").eq(columna).html('Cargando...');
                },
                success: function (data) {

                    $(element).find("td").eq(columna).html(data.fotografia);

                }
            });
        });

    }
    //inicio contratos
    var initDataTableContratosListado = function () {
      /*$('#tb_contratos_listado thead tr').clone(true).appendTo('#tb_contratos_listado thead');
      $('#tb_contratos_listado thead tr:eq(1) th').each(function (i) {
          $(this).html('<input type="text" class="form-control form-corto_2 form-control-sm" placeholder="" />');
          $('input', this).on('keyup change', function () {
              if (tabla_emps_listado_.column(i).search() !== this.value) {
                  tabla_emps_listado_
                      .column(i)
                      .search(this.value)
                      .draw();
              }
          });
      });*/
        tabla_contratos_listado = $('#tb_contratos_listado').DataTable({
          dom:
              "<'row'<'col-sm-8'B><'col-sm-4'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            ordering: false,
            pageLength: 10,
            responsive:true,
            scrollY: '50vh',
            scrollX:        false,
            "bProcessing": true,
            language: {
              emptyTable: "No hay empleados para mostrar",
              sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            "ajax": {
                url: "empleados/php/back/listados/get_contratos_listado.php",
                type: "POST",
                data:{
                  tipo: function () { return $('#tipo_reload_c').val() }
                },
                error: function () {
                    $("#post_list_processing").css("display", "none");
                }
            },
            "aoColumns": [
                { "class": "text-center", mData: 'id_persona' },
                { "class": "text-center", mData: 'id_persona' },
                { "class": "text-left", mData: 'empleado' },
                { "class": "text-center", mData: 'f_nac' },
                { "class": "text-center", mData: 'nit' },
                { "class": "text-center", mData: 'igss' },
                { "class": "text-center", mData: 'descripcion' },
                { "class": "text-center", mData: 'status' },
                { "class": "text-center", mData: 'archivo' },
                { "class": "text-center", mData: 'nisp' },
                { "class": "text-center", mData: 'f_ingreso' },
                { "class": "text-center", mData: 'f_baja' },
                { "class": "text-center", mData: 'accion' },
                { "class": "text-center", mData: 'dir_funcional' },

            ],
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Exportar',
                className: 'btn btn-personalizado',
                title: 'Listado de Empleados ' + today,
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9,10,13]
                }
            },
            {
              text: 'Aspirantes <i class="fa fa-user-plus"></i>',
              className: 'btn  btn-personalizado btn-aspirante',
              action: function ( e, dt, node, config ) {
                recargarEmpleadosC(5)
                setActiveButton('btn-aspirante');
              }
            },
            {
              text: 'Activos <i class="fa fa-user-check"></i>',
              className: 'btn  btn-personalizado btn-activo active',
              action: function ( e, dt, node, config ) {
                recargarEmpleadosC(1);
                setActiveButton('btn-activo');
              }
            },
            {
              text: 'Apoyo <i class="fa fa-user-secret"></i>',
              className: 'btn  btn-personalizado btn-apoyo',
              action: function ( e, dt, node, config ) {
                recargarEmpleadosC(4);
                setActiveButton('btn-apoyo');

              }
            },
            {
              text: 'Bajas <i class="fa fa-user-slash"></i>',
              className: 'btn  btn-personalizado btn-baja',
              action: function ( e, dt, node, config ) {
                recargarEmpleadosC(2);
                setActiveButton('btn-baja');
              }
            },
            {
              text: 'Denegados <i class="fa fa-user-times"></i>',
              className: 'btn  btn-personalizado btn-denegado',
              action: function ( e, dt, node, config ) {
                recargarEmpleadosC(3);
                setActiveButton('btn-denegado');
              }
            }
            ],
            // "order": false,
            'columnDefs': [
                // { 'width': 1, 'targets': [0] },
                // { 'max-width': 1, 'targets': [0] },
                { responsivePriority: 1, targets: [0,1,2,3,11] },

                { "visible": false, "targets": [9,13] }
            ],
            drawCallback: function (settings) {
                get_fotografia('tb_contratos_listado', 0);
            },
            initComplete: function () {
              $('body').on("click", ".dropdown-menu-", function (e) {
                $(this).parent().is(".show") && e.stopPropagation();
              });
                var column = this.api().column(6);
                var select = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#filter1')
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


                var column2 = this.api().column(3);
                var column3 = this.api().column(6);
                var select2 = $('<select class="form-control  form-control-sm"><option value="">TODOS</option></select>')
                    .appendTo('#filter2')
                    .on('change', function () {
                        var val2 = $(this).val();
                        column2.search(val2).draw();

                    });

                var offices2 = ['Enero'
                    , 'Febrero'
                    , 'Marzo'
                    , 'Abril'
                    , 'Mayo'
                    , 'Junio'
                    , 'Julio'
                    , 'Agosto'
                    , 'Septiembre'
                    , 'Octubre'
                    , 'Noviembre'
                    , 'Diciembre'];

                offices2.forEach(function (d) {
                    select2.append('<option value="' + d + '">' + d + '</option>');
                })


            }
        });


        $('#modal-remoto-lgg2').on('click', '.out', function () {
          valord = $('#id_cambio').val();
          if(valord == 1){
            tipo = $('#id_tipo_filtro').val()
            recargarEmpleados(tipo);
          }
          $('#modal-remoto-lgg2').modal('hide');
        });
        function get_fotografia(tabla, columna) {
            $('#' + tabla + ' tr').each(function (index, element) {
                var id_persona = $(element).find("td").eq(columna).html();
                //console.log(id_persona);
                $.ajax({
                    type: "POST",
                    url: "empleados/php/back/empleados/get_fotografia.php",
                    dataType: 'json',
                    data: {
                        id_persona: id_persona
                    },
                    beforeSend: function () {
                        //$(element).find("td").eq(columna).html('Cargando...');
                    },
                    success: function (data) {

                        $(element).find("td").eq(columna).html(data.fotografia);

                    }
                });
            });
          }
    };
    //fin contratos

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
            initDataTableEmpleados_listado();
            initDataTablePlazas_listado();
            initDataTableEmpleados_funcional_listado();
            initDataTableContratosListado();

        }
    };
}();

function recargar_puestos() {
    tabla_emps_listado.ajax.reload(null, false)
}

function recargarEmpleados(tipo){
  //alert('Si está recargando');
  $('#tipo_reload').val(tipo)
  tabla_emps_listado_.ajax.reload(null, false)
}

function recargarPlazas(){
  //alert('Si está recargando');
  tabla_plazas_listado.ajax.reload(null, false)
}

function recargarEmpleadosC(tipo){
  //alert('Si está recargando');
  this._target.storageAgent().clearDataForOrigin(this._securityOrigin, storageTypes.join(','));
  $('#tipo_reload_c').val(tipo)
  tabla_contratos_listado.ajax.reload(null, false)
}

function setActiveButton(classe){
  switch (classe) {
    case 'btn-aspirante':
      $(".btn-aspirante").addClass("active");
      $(".btn-activo").removeClass("active");
      $(".btn-apoyo").removeClass("active");
      $(".btn-baja").removeClass("active");
      $(".btn-denegado").removeClass("active");
    break;
    case 'btn-activo':
      $(".btn-aspirante").removeClass("active");
      $(".btn-activo").addClass("active");
      $(".btn-apoyo").removeClass("active");
      $(".btn-baja").removeClass("active");
      $(".btn-denegado").removeClass("active");
    break;
    case 'btn-apoyo':
      $(".btn-aspirante").removeClass("active");
      $(".btn-activo").removeClass("active");
      $(".btn-apoyo").addClass("active");
      $(".btn-baja").removeClass("active");
      $(".btn-denegado").removeClass("active");
    break;
    case 'btn-baja':
      $(".btn-aspirante").removeClass("active");
      $(".btn-activo").removeClass("active");
      $(".btn-apoyo").removeClass("active");
      $(".btn-baja").addClass("active");
      $(".btn-denegado").removeClass("active");
    break;
    case 'btn-denegado':
      $(".btn-aspirante").removeClass("active");
      $(".btn-activo").removeClass("active");
      $(".btn-apoyo").removeClass("active");
      $(".btn-baja").removeClass("active");
      $(".btn-denegado").addClass("active");
    break;
    default:

  }

}




// Initialize when page loads
jQuery(function () { EmpleadosTableDatatables_listado.init(); });





$('#modal-remoto').on('click', '.salida', function () {
  var cambio = $('#id_cambio').val();
  alert(cambio)
  if (cambio == 77) { //Aspirantes
    recargarEmpleados(0);
  }else if (cambio == 88) { //Todos
    recargarEmpleados(1);
  }

  $('#modal-remoto').modal('hide');
});
