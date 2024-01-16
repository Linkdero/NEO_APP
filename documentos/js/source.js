
var tableDocumentos, tableJustificacion, tablePedidos;
var DocumentosTableDatatableslistado = function() {
  var initDataTableDocumentos = function() {
    tableDocumentos = $('#tb_documentos').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging":true,
      "info":true,
      "deferRender": true,
      responsive:true,
      language: {
        emptyTable: "No hay Documentos para mostrar",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      ajax:{
        url :"documentos/php/back/listados/get_documentos.php",
        type: "POST",
        data:{
          tipo:$('#id_tipo_filtro').val()
        },
        error: function(){
          $("#post_list_processing").css("display","none");
        }
      },
      "aoColumns": [
        { "class" : "text-center", mData: 'docto_id' },
        { "class" : "text-center", mData: 'nombre' },
        { "class" : "text-center", mData: 'categoria' },
        { "class" : "text-center", mData: 'correlativo' },
        { "class" : "text-center", mData: 'fecha_docto' },
        { "class" : "text-center", mData: 'respuesta' },
        {"id":'docto_id', "class" : "text-center", mData: 'destinatarios' },
        { "class" : "text-center", mData: 'accion' }
      ],
      buttons: [
        'excel',
        {
          text: 'Recargar <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function ( e, dt, node, config ) {
            recargarDocumentos();
          }
        }
      ],
      "columnDefs": [
        {
          responsivePriority:0,
          targets: 6
        },
        //{responsivePriority:1, targets: 8},
        {
          'targets': [0,1],
          "width": "10px",
          //'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function(data, type, row, meta) {
            //return '<div class="bg-info">lkjadlfkjadf</div>';
            return data.split(" ").join("<br>");
          }
        },
        {
          'targets': [6],
          //'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function(data, type, row, meta) {
            var div='<div class="bg-info" id="'+row.div_doc+'">dkjalkdf</div>';
            return div;
          }
        },
        {
          'targets': [7],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function(data, type, row, meta) {
            if(row.categoria_id == 8048 || row.categoria_id == 8049){
              return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="licitacion_reporte('+row.DT_RowId+')"><i class="fa fa-print" aria-hidden="true"></i></span><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lg" href="documentos/php/front/documento/documento_detalle.php?docto_id='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
            }else{
              return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="generate('+row.DT_RowId+')"><i class="fa fa-download" aria-hidden="true"></i></span><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lg" href="documentos/php/front/documento/documento_detalle.php?docto_id='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
            }
          }
        }
      ]
    });
  };

  //tabla justificaciones
  var initDataTableJustificaciones = function() {
    tableJustificacion = $('#tb_justificaciones').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging":true,
      "info":true,
      "deferRender": true,
      responsive:true,
      language: {
        emptyTable: "No hay Justificaciones para mostrar",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      ajax:{
        url :"documentos/php/back/listados/get_justificaciones.php",
        type: "POST",
        data:{},
        error: function(){
          $("#post_list_processing").css("display","none");
        }
      },
      "aoColumns": [
        { "class" : "text-center", mData: 'docto_id' },
        { "class" : "text-center", mData: 'titulo' },
        { "class" : "text-center", mData: 'categoria' },
        { "class" : "text-center", mData: 'correlativo' },
        { "class" : "text-center", mData: 'fecha_docto' },
        { "class" : "text-center", mData: 'estado' },
        { "class" : "text-center", mData: 'pedido_num'},
        { "class" : "text-center", mData: 'accion' }
      ],
      buttons: [
        'excel',
        {
          text: 'Recargar <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function ( e, dt, node, config ) {
            recargarJustificaciones();
          }
        }
      ],
      "columnDefs": [
        {
          responsivePriority:0,
          targets: [6,7]
        },
        {
          'targets': [7],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function(data, type, row, meta) {
            return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="justificacion_reporte('+row.DT_RowId+')"><i class="fa fa-print" aria-hidden="true"></i></span><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lg" href="documentos/php/front/justificacion/justificacion_detalle.php?docto_id='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
          }
        }
      ]
    });
  };
  //fin tabla justificaciones

  //inicio tabla pedidos
  //tabla justificaciones
  var initDataTablePedidos = function() {
    tablePedidos = $('#tb_pedidos').DataTable({
      "ordering": false,
      "pageLength": 25,
      "bProcessing": true,
      "paging":true,
      "info":true,
      "deferRender": true,
      responsive:true,
      language: {
        emptyTable: "No hay Pedidos para mostrar",
        sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      ajax:{
        url :"documentos/php/back/listados/get_pedidos_listado.php",
        type: "POST",
        data:{
          tipo:2
        },
        error: function(){
          $("#post_list_processing").css("display","none");
        }
      },
      "aoColumns": [
        { "class" : "text-center", mData: 'pedido_num' },
        { "class" : "text-center", mData: 'fecha' },
        { "class" : "text-center", mData: 'observaciones' },
        { "class" : "text-center", mData: 'acciones' }
      ],
      buttons: [
        'excel',
        {
          text: 'Recargar <i class="fa fa-sync"></i>',
          className: 'btn btn-soft-info',
          action: function ( e, dt, node, config ) {
            recargarPedidos();
          }
        }
      ],
      "columnDefs": [
        {
          responsivePriority:0,
          targets: 3
        },
        {
          'targets': [3],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function(data, type, row, meta) {
            return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="justificacion_reporte('+row.DT_RowId+')"><i class="fa fa-print" aria-hidden="true"></i></span><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lg" href="documentos/php/front/justificacion/justificacion_detalle.php?docto_id='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
          }
        }
      ]
    });
  };
  //fin tabla pedidos
    // DataTables Bootstrap integration
    var bsDataTables = function() {
      var $DataTable = jQuery.fn.dataTable;

      // Set the defaults for DataTables init
      jQuery.extend( true, $DataTable.defaults, {
          dom:
          "<'row'<'col-sm-4'l><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
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
              sProcessing:     "Procesando...",
              sLengthMenu:     "Mostrar _MENU_ registros",
              sZeroRecords:    "No se encontraron resultados",
              sEmptyTable:     "Ningún dato disponible en esta tabla",
              sInfo:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              sInfoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
              sInfoFiltered:   "(filtrado de un total de _MAX_ registros)",
              sInfoPostFix:    "",
              sSearch:         "Buscar:",
              sUrl:            "",
              sInfoThousands:  ",",
              sLoadingRecords: "Cargando...",
              oPaginate: {
                  sFirst:    "Primero",
                  sLast:     "Último",
                  sNext:     "<i class='fa fa-chevron-right'></i>",
                  sPrevious: "<i class='fa fa-chevron-left'></i>"
              },
              oAria: {
                  sSortAscending:  ": Activar para ordenar la columna de manera ascendente",
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
            var api     = new $DataTable.Api(settings);
            var classes = settings.oClasses;
            var lang    = settings.oLanguage.oPaginate;
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
        init: function() {
            //Init Datatables
            bsDataTables();
            initDataTableDocumentos();
            initDataTableJustificaciones();
            //initDataTablePedidos();
          }
    };
}();

function recargarDocumentos(){
  tableDocumentos.ajax.reload(null,false);
}

function recargarJustificaciones(){
  tableJustificacion.ajax.reload(null,false);
}

// Initialize when page loads
jQuery(function(){ DocumentosTableDatatableslistado.init(); });
