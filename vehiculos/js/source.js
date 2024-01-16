
var tabla_tipo_insumos_listado;
var table_movimientos, table_movimientos_en;
// var table_cupones_entregados;
var table_cupones_ingresados;
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
        { "class": "text-left", mData: 'codigo' },
        { "class": "text-center", mData: 'descripcion' }
      ],
      buttons: [
      ]
    });
  };

  // Datatables listado de usuarios
  var initDataTableMovimientos_listado = function () {

    table_movimientos = $('#tb_movimientos').DataTable({
      "ordering": false,
      "pageLength": 10,
      "bProcessing": true,

      language: {
        emptyTable: "No hay pantallas asignadas",
        loadingRecords: ''
      },
      "ajax": {
        url: "insumos/php/back/listados/get_all_movimientos.php",
        type: "POST",
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'fecha' },
        { "class": "text-left", mData: 'movimiento' },
        { "class": "text-center", mData: 'producto' }
      ],
      buttons: [
      ],
      "initComplete": function (settings, json) {
        alert('DataTables has finished its initialisation.');
      }
    });
  };

  var initDataTableMovimientos_en_listado = function () {
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
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "

      },
      "ajax": {
        url: "vehiculos/php/back/listados/get_all_valesxFecha.php",
        type: "POST",
        data: {
          ini: function () { return $('#fin').val() },
          fin: function () { return $('#fin').val() }
        },
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'fecha', "width": "13%" },
        { "class": "text-center", mData: 'nro_vale', "width": "4%" },
        { "class": "text-center", mData: 'nro_placa', "width": "4%" },
        { "class": "text-center", mData: 'estado', "width": "12%" },
        { "class": "text-center", mData: 'uso', "width": "9%" },
        //    { "class" : "text-center", mData: 'evento',"width":"14%"},
        { "class": "text-center", mData: 'recibe', "width": "28%" },
        { "class": "text-center", mData: 'accion', "width": "4%" },

      ],
      buttons: [
      ],
      "initComplete": function (settings, json) {

      }

    });
  };


  // var initDataTableCuponesEntregados = function () {

  //   table_cupones_entregados = $('#tb_cupones_entregados').DataTable({
  //     dom:
  //       "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
  //       "<'row'<'col-sm-12'tr>>" +
  //       "<'row'<'col-sm-6'i><'col-sm-6'p>>",
  //     "ordering": false,
  //     "pageLength": 10,
  //     "bProcessing": true,
  //     "lengthChange": false,
  //     orderCellsTop: true,
  //     fixedHeader: true,

  //     //"dom": '<"">frtip',


  //     language: {
  //       emptyTable: "No hay registros para mostrar",
  //       sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
  //     },
  //     "ajax": {
  //       url: "vehiculos/php/back/listados/get_all_cupones_entregados.php",
  //       type: "POST",
  //       data: {
  //         estado: function () { return $('#estado_cupon').val() }
  //       },
  //       error: function () {
  //         $("#post_list_processing").css("display", "none");
  //       }
  //     },

  //     "aoColumns": [
  //       { "class": "text-center", mData: 'id_documento', "width": "5%" },
  //       { "class": "text-center", mData: 'estado', "width": "12%" },
  //       { "class": "text-center", mData: 'fecha', "width": "8%" },
  //       { "class": "text-center", mData: 'nro_documento', "width": "10%" },
  //       { "class": "text-center", mData: 'autorizo', "width": "25%" },
  //       { "class": "text-center", mData: 'recibe', "width": "26%" },
  //       { "class": "text-center", mData: 'cupones', "width": "5%" },
  //       { "class": "text-center", mData: 'total', "width": "5%" },
  //       { "class": "text-center", mData: 'pendiente', "width": "5%" },
  //       { "class": "text-center", mData: 'accion', "width": "4%" },
  //     ],

  //     buttons: [{
  //       extend: 'excel',
  //       text: '<i class="fa fa-file-excel"></i> Exportar',
  //       className: 'btn btn-personalizado',
  //       title: 'Listado de solicitudes',
  //       exportOptions: {
  //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
  //       }
  //     },
  //     {
  //       text: 'Pendientes <i class="far fa-check-square"></i>',
  //       className: 'btn btn-personalizado btn-pendientes active',
  //       action: function (e, dt, node, config) {
  //         reload_cupones_entregados(4348);
  //         setActiveButton('btn-pendientes');
  //       }
  //     },
  //     {
  //       text: 'Procesados <i class="fa fa-check-square"></i>',
  //       className: 'btn  btn-personalizado btn-procesados',
  //       action: function (e, dt, node, config) {
  //         reload_cupones_entregados(4347);
  //         setActiveButton('btn-procesados');
  //       }
  //     },
  //     ],
  //   });
  //   $('#modal-remoto-lgg2').on('click', '.out', function () {
  //     valord = $('#id_cambio').val();
  //     if (valord == 1) {
  //       reload_cupones_entregados(4347);
  //     }
  //     $('#modal-remoto-lgg2').modal('hide');
  //   });
  // };

  var initDataTableCuponesIngresados = function () {
    table_cupones_ingresados = $('#tb_cupones_ingresados').DataTable({
      "dom":
        "<'row'<'col-sm-6'B><'col-sm-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-6'i><'col-sm-6'p>>",
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
        url: "vehiculos/php/back/listados/get_all_cupones_ingresados.php",
        type: "POST",
        data: {},
        error: function () {
          $("#post_list_processing").css("display", "none");
        }
      },

      "aoColumns": [
        { "class": "text-center", mData: 'id_documento'},
        { "class": "text-center", mData: 'fecha'},
        { "class": "text-center", mData: 'nro_documento'},
        { "class": "text-center", mData: 'opero'},
        { "class": "text-center", mData: 'total'},
        { "class": "text-center", mData: 'accion'},
      ],
      buttons: [{
        extend: 'excel',
        text: '<i class="fa fa-file-excel"></i> Exportar',
        className: 'btn btn-personalizado',
        title: 'Listado de solicitudes',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
        }
      }
      ]
    });
    $('#modal-remoto-lgg2').on('click', '.out', function () {
      valord = $('#id_cambio').val();
      if (valord == 1) {
        reload_cupones_ingresados(4347);
      }
      $('#modal-remoto-lgg2').modal('hide');
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
    init: function () {
      bsDataTables();
      initDataTableTipoInsumos_listado();
      initDataTableMovimientos_listado();
      initDataTableMovimientos_en_listado();
      // initDataTableCuponesEntregados();
      initDataTableCuponesIngresados();
    }
  };
}();

function reload_movimientos_en() {
  table_movimientos_en.ajax.reload(null, false);
}

// function reload_cupones_entregados(tipo) {
//   $('#estado_cupon').val(tipo)
//   table_cupones_entregados.ajax.reload(null, false);
// }

function reload_cupones_ingresados(tipo) {
  $('#estado_cupon').val(tipo)
  table_cupones_ingresados.ajax.reload(null, false);
}

function reload_porCupones(tipo) {
  $('#estado_cupon').val(tipo)
  tablePorCupones.ajax.reload(null, false);
}

function setActiveButton(classe) {
  switch (classe) {
    case 'btn-pendientes':
      $(".btn-pendientes").addClass("active");
      $(".btn-procesados").removeClass("active");
      $(".btn-cupones").removeClass("active");
      break;
    case 'btn-procesados':
      $(".btn-pendientes").removeClass("active");
      $(".btn-procesados").addClass("active");
      $(".btn-cupones").removeClass("active");
      break;
    case 'btn-cupones':
      $(".btn-pendientes").removeClass("active");
      $(".btn-procesados").removeClass("active");
      $(".btn-cupones").addClass("active");
      break;

    default:

  }
}

// Initialize when page loads
jQuery(function () { InsumosTableDatatables_listado.init(); });
