
var table_solicitudes, table_reporte, table_pendientes, table_empleados_solvencia, table_formulario, table_reporte_1,table_formularios_utilizados,table_formularios_utilizados_tipo;


function findWithAttr(array, attr, value) {
  for (var i = 0; i < array.length; i += 1) {
    if (array[i][attr] === value) {
      return i;
    }
  }
  return -1;
}
var ViaticosTableDatatables_listado = function () {




  var initDatatableSolicitudes = function () {
    table_solicitudes = $('#tb_solicitudes').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging": true,
      "info": true,
      responsive: true,
      /*scrollX:        true,
      scrollY: false,
      scrollCollapse: true,

      fixedColumns:   true,
      fixedColumns: {
        leftColumns: 1
      },*/
      language: {
        emptyTable: "No hay nombramientos para mostrar",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
        //loadingRecords: " <div class='loaderr'></div> "
      },
      ajax: {
        url: "viaticos/php/back/listados/get_solicitudes.php",
        type: "POST",
        data: {
          tipo: function () { return $('#id_tipo_filtro').val() }
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },
      "aoColumns": [
        { "class": "text-center", mData: 'nombramiento' },
        { "class": "text-center", mData: 'fecha' },
        { "class": "text-center", mData: 'direccion_solicitante' },
        { "class": "text-center", mData: 'tipo' },
        { "class": "text-center", mData: 'destino' },
        //{ "class" : "text-center", mData: 'motivo' },
        { "class": "text-center", mData: 'fecha_ini' },
        { "class": "text-center", mData: 'fecha_fin' },
        //{ "class" : "text-center", mData: 'personas' },
        { "class": "text-center", mData: 'estado' },
        //{ "class" : "text-center", mData: 'progress' },
        { "class": "text-center", mData: 'accion' }/**,
            { "class" : "text-center", mData: 'fecha_ini' }/*,
            { "class" : "text-center", mData: 'fecha_ini' }/*,
            { "class" : "text-center", mData: 'fuente' },
            { "class" : "text-center", mData: 'categoria' },
            { "class" : "text-center", mData: 'propietario' },
            { "class" : "text-center", mData: 'departamento' },
            { "class" : "text-center", mData: 'municipio' },
            { "class" : "text-center", mData: 'observaciones' },
            { "class" : "text-center", mData: 'accion' }¨*/
      ],
      buttons: [

        {
          text: 'Pendientes <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function (e, dt, node, config) {
            recargar_nombramientos(2);
          }
        },
        {
          text: '1 año <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function (e, dt, node, config) {
            recargar_nombramientos(3);
          }
        },
        {
          text: 'Todos <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function (e, dt, node, config) {
            recargar_nombramientos(1);
          }
        },
        {
          text: 'Liquidados <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function (e, dt, node, config) {
            recargar_nombramientos(4);
          }
        }

      ],

      "columnDefs": [
        { responsivePriority: 0, targets: 7 },
        { responsivePriority: 1, targets: 8 },
        {
          "targets": 3,
          "width": "30px"
        },
        {
          'targets': [8],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function (data, type, row, meta) {
            //turn row.id();
            //return format(row.serie);//format(row.serie);
            var menu = '<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion(' + row.DT_RowId + ',1)"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu' + row.DT_RowId + '"></div></div></div></div>';
            return '<div class="btn-group">' + menu + '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico=' + row.DT_RowId + '&tipo_filtro=' + $('#id_tipo_filtro').val() + '"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'
            /*if (data === "5338" || data ==="5339" || data ==="5491") {
              //return '<span class="badge badge-success">Activo.</span>'+ meta.row;

               //return format(row.serie);//format(row.serie);
               return '--';
            }
            else{
              return '';
            }*/

          }

        }/*,

          {
          'targets': [8],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
              render: function(data, type, row, meta) {
                  if (data === "ANULADO EN DIRECCION" || data === "ANULACION EN CALCULO" || data === "ANULACION EN IMPRESION CHEQUE" || data === "ANULACION CHEQUE IMPRESO" || data === "ANULACION NOMBRAMIENTO POR COMPLETO") {
                      return '<span class="badge badge-danger">'+data+'</span>';
                  } else if(data === "2"){
                      return '<span class="badge badge-info">Activo</span>';
                  } else if(data === "3"){
                      return '<span class="badge badge-success">Aprobado</span>';
                  }else{
                      return '<span class="badge badge-danger">Inactivo</span>';
                  }
              }
          }*/
      ]
    });

    $('#tb_empleados_asignar tbody').on('click', 'button', function () {
      var data = table_empleados_asignar.row($(this).parents('tr')).data();
      //alert( data['marca'] +"'s serie es: "+ data['serie'] );
      $('#myPopupInput1').val(data['id_persona']);
    });




  };

  var initDatatablePendientes = function () {
    table_pendientes = $('#tb_pendientes').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging": true,
      "info": true,
      scrollX: true,
      scrollY: false,
      scrollCollapse: true,

      fixedColumns: true,
      fixedColumns: {
        leftColumns: 1
      },
      language: {
        emptyTable: "No hay noticias disponibles",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
        //loadingRecords: " <div class='loaderr'></div> "
      },
      ajax: {
        url: "viaticos/php/back/listados/get_solicitudes.php",
        type: "POST",
        data: {
          tipo: 2
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },
      "aoColumns": [
        { "class": "text-center", mData: 'nombramiento' },
        { "class": "text-center", mData: 'fecha' },
        { "class": "text-center", mData: 'direccion_solicitante' },

        { "class": "text-center", mData: 'destino' },
        //{ "class" : "text-center", mData: 'motivo' },
        { "class": "text-center", mData: 'fecha_ini' },
        { "class": "text-center", mData: 'fecha_fin' },
        //{ "class" : "text-center", mData: 'personas' },
        { "class": "text-center", mData: 'estado' },
        //{ "class" : "text-center", mData: 'progress' },
        { "class": "text-center", mData: 'accion' }/**,
            { "class" : "text-center", mData: 'fecha_ini' }/*,
            { "class" : "text-center", mData: 'fecha_ini' }/*,
            { "class" : "text-center", mData: 'fuente' },
            { "class" : "text-center", mData: 'categoria' },
            { "class" : "text-center", mData: 'propietario' },
            { "class" : "text-center", mData: 'departamento' },
            { "class" : "text-center", mData: 'municipio' },
            { "class" : "text-center", mData: 'observaciones' },
            { "class" : "text-center", mData: 'accion' }¨*/
      ],
      buttons: [

      ],
      "columnDefs": [
        {
          "targets": 3,
          "width": "30px"
        },
        {
          'targets': [7],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function (data, type, row, meta) {
            //turn row.id();
            //return format(row.serie);//format(row.serie);
            var menu = '<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion(' + row.DT_RowId + ',2)"><i class="fa fa-sliders-h"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu2' + row.DT_RowId + '"></div></div></div></div>';
            return '<div class="btn-group">' + menu + '<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico=' + row.DT_RowId + '"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'


          }

        }
      ]
    });

    $('#tb_empleados_asignar tbody').on('click', 'button', function () {
      var data = table_empleados_asignar.row($(this).parents('tr')).data();
      //alert( data['marca'] +"'s serie es: "+ data['serie'] );
      $('#myPopupInput1').val(data['id_persona']);
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

  var initDataTableInsumos_reporte = function () {
    table_reporte = $('#tb_reporte').DataTable({
      "ordering": true,
      "pageLength": 25,
      "bProcessing": true,
      "paging": true,
      "info": true,
      "order": [[10, 'asc']],
      "responsive": true,
      // scrollX: true,
      // scrollCollapse: true,

      // fixedColumns: true,
      // fixedColumns: {
      //   leftColumns: 1
      // },

      //"dom": '<"">frtip',


      language: {
        emptyTable: "No hay nombramientos para mostrar",
        loadingRecords: " <div class='spinner-grow text-info'></div> "
      },
      "ajax": {
        url: "viaticos/php/back/listados/get_viaticos_por_pais.php",
        type: "POST",
        data: {
          tipo: function () { return $('#id_tipo').val() },
          mes: function () { return $('#id_mes').val() },
          year: function () { return $('#id_year').val() }
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'nombramiento', "width": "20%" },
        { "class": "text-left", mData: 'empleado', "width": "20%" },
        { "class": "text-center", mData: 'direccion', "width": "20%" },
        { "class": "text-center", mData: 'fecha_salida', "width": "20%" },
        { "class": "text-center", mData: 'fecha_regreso', "width": "20%" },
        { "class": "text-center", mData: 'pais', "width": "5%" },
        { "class": "text-center", mData: 'departamento' },
        { "class": "text-center", mData: 'municipio' },
        { "class": "text-right", mData: 'total_real' },
        { "class": "text-right", mData: 'total_mes' },
        { "class": "text-center", mData: 'id_pais' },
      ],
      buttons: [
        {
          extend: 'excel',
          text: '<i class="fa fa-file-excel"></i> Exportar',
          className: 'btn btn-personalizado',
          title: 'Reporte de Viaticos - ' + $('#id_mes option:selected').text(),
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
          }
        },
      ],
      "columnDefs": [
        { responsivePriority: 0, targets: 7 },
        { responsivePriority: 0, targets: 8 },
        { responsivePriority: 1, targets: 9 },
        { visible: false, targets: 10 },
      ],
      drawCallback: function (settings) {
        let api = this.api();

        let sum = api.column(8, { search: 'applied' }).data().reduce(function (a, b) {
          return (parseFloat(a) + parseFloat(b)).toFixed(2);
        }, 0);
        $('#totalqv').html('Total Q.' + sum);

        if ($('#id_tipo').val() == 0) {
          let rows = api.rows({ page: 'current' }).nodes();
          let last = null;
          let arrayDirecciones = [];
          let posicion = 0;
          let groupColumn = 10;
          api.rows({ search: 'applied' }).data().each(function (element) {
            posicion = findWithAttr(arrayDirecciones, "id_pais", element.id_pais);
            if (posicion >= 0) {
              arrayDirecciones[posicion].total = (parseFloat(arrayDirecciones[posicion].total) + parseFloat(element.total_real)).toFixed(2);
            } else {
              arrayDirecciones.push({
                "id_pais": element.id_pais,
                "total": element.total_real
              });
            }

          });
          api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
            if (last !== group) {
              $(rows).eq(i).before(
                '<tr style="background: #76a4fb;"><td colspan="11" style="color:black;font-size:18px;">' + group + ' (Subtotal: Q. ' + arrayDirecciones[findWithAttr(arrayDirecciones, "id_pais", group)].total.toString() + ')</td></tr>'
              );
              last = group;
            }
          });
        }
      }
    });


  };

  var initDataTableV_reporte = function () {
    table_reporte_1 = $('#tb_vtotales').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging": true,
      "info": true,
      responsive: true,

      language: {
        emptyTable: "No hay nombramientos para mostrar",
        loadingRecords: " <div class='spinner-grow text-info'></div> "
      },
      "ajax": {
        url: "viaticos/php/back/listados/get_viaticos_todos.php",
        type: "POST",
        data: {
          mes: function () { return $('#vmonth').val() },
          year: function () { return $('#vyear').val() }
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'direccion' },
        { "class": "text-center", mData: 'departamento' },
        { "class": "text-center", mData: 'municipio' },
        { "class": "text-center", mData: 'mes' },
        { "class": "text-center", mData: 'año' },
        { "class": "text-center", mData: 'total' }
      ],
      buttons: [
        'excel'
      ],
      "columnDefs": [
        // { responsivePriority: 0, targets: 6 },
        // { responsivePriority: 0, targets: 8 },
        // { responsivePriority: 1, targets: 9 }
      ]
    });


  };

  var initDataTableFormularios_reporte = function () {
    table_formularios = $('#tb_formularios').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging": true,
      "info": true,
      responsive: true,
      language: {
        emptyTable: "No hay nombramientos para mostrar",
        loadingRecords: " <div class='spinner-grow text-info'></div> "
      },
      "ajax": {
        url: "viaticos/php/back/listados/get_formularios.php",
        type: "POST",

        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-left", mData: 'tipo_formulario', "width": "20%" },
        { "class": "text-center", mData: 'v_inicial', "width": "20%" },
        { "class": "text-center", mData: 'v_final', "width": "20%" },
        { "class": "text-center", mData: 'v_actual', "width": "20%" },
        { "class": "text-center", mData: 'v_restante', "width": "20%" },
        { "class": "text-center", mData: 'accion', "width": "20%" }
      ]
    });


  };


  // inicio
  var initDataTableEmpleados_solvencia = function () {
    table_empleados_solvencia = $('#tb_empleados_solvencia').DataTable({
      /*'initComplete': function(settings){
        var api = this.api();
        api.cells(
          api.rows(function(idx, data, node){
            //alert(idx);
            return (data.bln_confirma == 0) ? true : false;
            //alert(data.bln_confirma)
          }).indexes(),14).checkboxes.disable();
      },*/
      //

      "ordering": false,
      "pageLength": 25,
      "bLengthChange": true,
      "bProcessing": true,
      select: true,
      //"paging": false,
      "ordering": false,
      //"info":     true,
      orderCellsTop: true,
      language: {
        emptyTable: "No hay solicitudes",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      "ajax": {
        url: "viaticos/php/back/listados/get_empleados_solvencia.php",
        type: "POST",
        data: {
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'vt_nombramiento' },
        { "class": "text-center", mData: 'id_persona' },
        { "class": "text-center", mData: 'empleado' },
        { "class": "text-center", mData: 'nro_nombramiento' },
        { "class": "text-center", mData: 'DT_RowId' }
      ],
      buttons: [
        {
          text: 'Guardar <i class="fa fa-check"></i>',
          className: 'btn btn-soft-info btn-sm',
          action: function (e, dt, node, config) {
            //recargar_nombramientos(2);
            guardarSolvencia();
          }
        },
        {
          text: 'Recargar <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info btn-sm',
          action: function (e, dt, node, config) {
            //recargar_nombramientos(2);
            recargarSolvencia();
          }
        }
      ]/*,
            'columnDefs': [{
         'targets': 3,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
           return '<label class="css-input input-sm switch switch-sm switch-success" style="padding-bottom:0px"><input name="id[]" type="checkbox" value="'
              + $('<div/>').text(data).html() + '"/><span></span></label>'

         }
      }]*/,
      drawCallback: function (settings) {
        get_fotografia('tb_empleados_solvencia', 1);
      },
      'columnDefs': [
        {
          'targets': 4,
          'checkboxes': {
            'selectRow': true
          },
        }
      ],
      'select': {
        'style': 'multi'
      },
      'order': [1, 'asc']

    });






    function guardarSolvencia() {
      var rows_selected = table_empleados_solvencia.column(4).checkboxes.selected();

      //console.log(arreglo);
      //alert(TableData.vt_nombramiento.toString());
      if (rows_selected.length == 0) {
        //mostrar mensaje
        jsonTableData = '';
        Swal.fire(
          'Atención!',
          "Debe seleccionar al menos un empleado",
          'error'
        );
      } else if (rows_selected.length > 0) {
        Swal.fire({
          title: '<strong></strong>',
          text: "¿Está seguro de guardar la información?",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Guardar!'
        }).then((result) => {
          if (result.value) {
            var x = 0;
            $.each(rows_selected, function (index, rowId) {
              var data = table_empleados_solvencia.row(rowId - 1).data();
              $.ajax({
                type: "POST",
                url: "viaticos/php/back/viatico/establecer_solvencia.php",
                data: {
                  vt_nombramiento: data.vt_nombramiento,
                  id_persona: data.id_persona
                },
                //dataType: 'html',
                beforeSend: function () {
                  //$("#datos_nombramiento").html('<div class="loaderr"></div>');
                },
                success: function (data) {

                }
              });
              console.log(data.vt_nombramiento + ' |_| ' + data.id_persona + ' |_|==  ' + rowId);
              x += 1;
            });

            if (x == rows_selected.length) {
              recargarSolvencia();
            }

            console.log(x + ' || || ' + rows_selected.length)


          }
        });


      }
    }


  };

  function removeA(arr, what) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
      what = a[--L];
      while ((ax = arr.indexOf(what)) !== -1) {
        arr.splice(ax, 1);
      }
    }
    return arr;
  }
  //fin

  // inicio
  var initDataTableFormulariosUtilizados= function () {
    table_formularios_utilizados = $('#tb_formularios_utilizados').DataTable({
      "ordering": true,
      "pageLength": 25,
      "bLengthChange": true,
      "bProcessing": true,
      //"paging": false,

      //"info":     true,
      orderCellsTop: true,
      language: {
        emptyTable: "No hay solicitudes",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      "ajax": {
        url: "viaticos/php/back/listados/get_formularios_utilizados.php",
        type: "POST",
        data: {
          ini: function () { return $('#ini').val() },
          fin: function () { return $('#fin').val() }
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [

        { "class": "text-center", mData: 'nro_frm_vt_ant' },
        { "class": "text-center", mData: 'estado_frm' },
        { "class": "text-center", mData: 'nro_frm_vt_cons' },
        { "class": "text-center", mData: 'nro_frm_vt_ext' },
        { "class": "text-center", mData: 'nro_frm_vt_liq' },

        { "class": "text-center", mData: 'vt_nombramiento' },
        { "class": "text-center", mData: 'estado' },


      ],
      buttons: [
        {
          extend: 'excel',
          text: '<i class="fa fa-file-excel"></i> Exportar',
          className: 'btn btn-personalizado btn-sm',
          title: 'Reporte de Viaticos - ' + $('#id_mes option:selected').text(),

        },

        {
          text: 'Recargar <i class="fa fa-sync"></i>',
          className: 'btn btn-personalizado btn-sm',
          action: function (e, dt, node, config) {
            //recargar_nombramientos(2);
            recargarFormsUtilizados();
          }
        }
      ]/*,
            'columnDefs': [{
         'targets': 3,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
           return '<label class="css-input input-sm switch switch-sm switch-success" style="padding-bottom:0px"><input name="id[]" type="checkbox" value="'
              + $('<div/>').text(data).html() + '"/><span></span></label>'

         }
      }]*/,

      'order': [0, 'asc']
    });
  };
  // fin
  //inicio
  var initDataTableFormulariosUtilizadosTipo= function () {
    table_formularios_utilizados_tipo = $('#tb_formularios_utilizados_tipo').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bLengthChange": true,
      "bProcessing": true,
      scrollY: '50vh',
      scrollX:        true,
        scrollCollapse: true,
      fixedColumns:   {
            left: 1
        },
      //"paging": false,

      //"info":     true,
      orderCellsTop: true,
      language: {
        emptyTable: "No hay solicitudes",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      "ajax": {
        url: "viaticos/php/back/listados/get_formularios_utilizados_tipo.php",
        type: "POST",
        data: {
          ini: function () { return $('#ini').val() },
          fin: function () { return $('#fin').val() },
          tipo: function () { return $('#tipo').val() }
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'correlativo' },
        { "class": "text-center", mData: 'estado_frm' },
        { "class": "text-center", mData: 'verificador' },
        { "class": "text-center", mData: 'fecha' },
        { "class": "text-center", mData: 'vt_nombramiento' },
        { "class": "text-center", mData: 'estado' },
        { "class": "text-center", mData: 'empleado' },
        { "class": "text-center", mData: 'direccion' },
        { "class": "text-center", mData: 'id_pais' },



      ],
      buttons: [
        {
          extend: 'excel',
          text: '<i class="fa fa-file-excel"></i> Exportar',
          className: 'btn btn-personalizado btn-sm',
          title: 'Reporte de Viaticos - ' + $('#id_mes option:selected').text(),

        },

        {
          text: 'Recargar <i class="fa fa-sync"></i>',
          className: 'btn btn-personalizado btn-sm',
          action: function (e, dt, node, config) {
            //recargar_nombramientos(2);
            recargarFormsUtilizadosTipo();
          }
        },
        {
          text: 'Imprimir <i class="fa fa-print"></i>',
          className: 'btn btn-personalizado btn-sm',
          action: function (e, dt, node, config) {
            //recargar_nombramientos(2);
            imprimir_liquidacion_global();
          }
        }
      ]/*,
            'columnDefs': [{
         'targets': 3,
         'searchable':false,
         'orderable':false,
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
           return '<label class="css-input input-sm switch switch-sm switch-success" style="padding-bottom:0px"><input name="id[]" type="checkbox" value="'
              + $('<div/>').text(data).html() + '"/><span></span></label>'

         }
      }]*/,

      //'order': [0, 'asc']
    });
    $('#modal-remoto-lgg2').on('click', '.salida', function () {

      valord = $('#id_cambiodv').val();
      if(valord == 1){
        tipo = $('#id_tipo_filtro').val()
        instancia.recargarViaticos(tipo);
      }
      $('#modal-remoto-lgg2').modal('hide');
    });
  };
  // fin





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
      sFilterInput: "form-control form-control-sm",
      sLengthSelect: "form-control form-control-sm"
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
      initDataTableInsumos_reporte();
      // initDataTableV_reporte();
      initDataTableEmpleados_solvencia();
      initDataTableFormularios_reporte();
      initDataTableFormulariosUtilizados();
      initDataTableFormulariosUtilizadosTipo();

    }
  };
}();

function recargar_reporte() {
  table_reporte.ajax.reload(null, false);
}
function recargar_reporte_1() {
  // table_reporte_1.ajax.reload(null, false);
}

function recargar_nombramientos(tipo) {
  $('#id_tipo_filtro').val(tipo);
  table_solicitudes.ajax.reload(null, false);
}

function recargarSolvencia() {
  table_empleados_solvencia.ajax.reload(null, false);
}

function recargarFormsUtilizados(){
  table_formularios_utilizados.ajax.reload(null, false);
}

function recargarFormsUtilizadosTipo(){
  table_formularios_utilizados_tipo.ajax.reload(null, false);
}

// Initialize when page loads
jQuery(function () { ViaticosTableDatatables_listado.init(); });
