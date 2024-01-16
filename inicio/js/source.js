
var table_dashboard, table_estado,table_novedades;
var DashboardTableDatatables = function() {



    // Datatables listado de usuarios
    var initDataTableDashboard = function() {
      table_dashboard = $('#tb_conteo').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,
        "searching":false,
        "pagination":false,
        "info":false,
        "paging":false,
        scrollX:        true,
        scrollCollapse: true,

        fixedColumns:   true,


        language: {
            emptyTable: "No hay datos para mostrar",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"inicio/php/back/get_totales_evento_dashboard.php",
              type: "POST",

              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [

               { "class" : "text-left", mData: 'fecha' },
               { "class" : "text-right", mData: 'conteo1' }/*,
               { "class" : "text-center", mData: 'percent' },
               { "class" : "text-center", mData: 'progress' }*/
             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        table_m.ajax.reload(null, false);
      }, 120000 );*/



    };

    // Datatables listado de usuarios
    var initDataTableEstado = function() {
      table_estado = $('#tb_estado_fuerza').DataTable({

        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging":true,
        "info":false,
        scrollX:        true,
        scrollCollapse: true,

        fixedColumns:   true,
        fixedColumns: {
    leftColumns: 1
  },


        buttons: [
             'print'
         ],


        language: {
            emptyTable: "No hay datos para mostrar",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"reportes/get_estado_de_fuerza.php",
              type: "POST",

              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },



             "aoColumns": [

               { "class" : "text-left", mData: 'direccion' },
               { "class" : "text-center", mData: 'servicio' },
               { "class" : "text-center", mData: 'descanso' },
               { "class" : "text-center", mData: 'vacaciones' },
               { "class" : "text-center", mData: 'igss' },
               { "class" : "text-center", mData: 'hospitalizado' },
               { "class" : "text-center", mData: 'permiso' },
               { "class" : "text-center", mData: 'faltan' },
               { "class" : "text-center", mData: 'capacitacion' },
               { "class" : "text-center", mData: 'otros' },
               { "class" : "text-center", mData: 'especiales' },
               { "class" : "text-center", mData: 'asesores' },
               { "class" : "text-center", mData: 'total' },
               { "class" : "text-center", mData: 'operador' },
               { "class" : "text-center", mData: 'operado' }

             ],
             "columnDefs": [
               { "width": "25%", "targets": 0 },
               { "width": "2%", "targets": 12 },
               { "width": "300px", "targets": 14 }
  ]
      });



      /*setInterval( function () {
        table_m.ajax.reload(null, false);
      }, 120000 );*/



      };

      var initDataTableNovedades = function() {
        table_novedades = $('#tb_novedades').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          scrollX:        true,
          scrollCollapse: true,
          "info":false,

          fixedColumns:   true,


         buttons: [
           'print','excel'
         ],


          language: {
              emptyTable: "No hay datos para mostrar",
              loadingRecords: " <div class='spinner-grow text-primary'></div> "
          },
          "ajax":{
                url :"reportes/get_novedades.php",
                type: "POST",

                error: function(){
                  $("#post_list_processing").css("display","none");
                }
            },


               "aoColumns": [
                 { "class" : "text-left", mData: 'direccion' },
                 { "class" : "text-center", mData: 'fecha' },

                 { "class" : "text-center", mData: 'novedades' },
                 { "class" : "text-center", mData: 'total' },
                 { "class" : "text-center", mData: 'operador' },
                 { "class" : "text-center", mData: 'operado' }

               ],
               buttons: [
                   'print'
               ],
               "columnDefs": [
                 { "width": "10%", "targets":0},
                 { "width": "10%", "targets":1},
                 { "width": "25%", "targets": 2 }
               ]
        });



        /*setInterval( function () {
          table_m.ajax.reload(null, false);
        }, 120000 );*/

        };

        // DataTables Bootstrap integration
var bsDataTables = function() {
  var $DataTable = jQuery.fn.dataTable;

  // Set the defaults for DataTables init
  jQuery.extend( true, $DataTable.defaults, {
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
          sProcessing:     "<i class='fa fa-sync fa-spin'></i> Procesando...",
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
          sLoadingRecords: "",
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

















    // DataTables Bootstrap integration

    return {
        init: function() {
            //Init Datatables
            bsDataTables();
            initDataTableDashboard();
            initDataTableNovedades();
            initDataTableEstado();


        }
    };
}();

function reload_dashboard(){
  table_dashboard.ajax.reload(null, false);
}

function reload_estado_fuerza(){
  table_estado.ajax.reload(null, false);
}

function reload_novedades(){
  table_novedades.ajax.reload(null, false);
}


// Initialize when page loads
jQuery(function(){ DashboardTableDatatables.init(); });
