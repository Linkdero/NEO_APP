
var table_acreditaciones;
var table_invitados;
var table_dashboard_;
var table_horarios;

var AcreditacionesTableDatatables = function() {

  var initDataTableDashboard_ = function() {
    table_dashboard_ = $('#tb_conteo').DataTable({
      "ordering": false,
      "pageLength": 10,
      "bProcessing": true,
      "searching":false,
      "pagination":false,
      "info":false,
      "paging":false,


      language: {
          emptyTable: "No hay datos para mostrar",
          loadingRecords: " <div class='spinner-grow text-primary'></div> "
      },
      "ajax":{
            url :"inicio/php/back/get_totales_evento_dashboard.php",
            type: "POST",
            data: {punto:0},
            error: function(){
              $("#post_list_processing").css("display","none");
            }
        },

           "aoColumns": [
             { "class" : "text-center", mData: 'day' },
             { "class" : "text-center", mData: 'punto' },
             { "class" : "text-center", mData: 'fecha' },
             { "class" : "text-center", mData: 'total' },
             { "class" : "text-center", mData: 'conteo1' },
             { "class" : "text-center", mData: 'conteo2' },
             { "class" : "text-center", mData: 'conteo3' }
           ],
          buttons: [
          ]
    });



    /*setInterval( function () {
      table_m.ajax.reload(null, false);
    }, 120000 );*/



  };

    // Datatables listado de usuarios
    var initDataTableAcreditaciones= function() {
      table_acreditaciones = $('#tb_acreditaciones').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,
        "searching":false,
        "pagination":false,
        "info":false,
        "paging":false,
        //scrollX:        true,
        //scrollCollapse: true,

        //fixedColumns:   true,
        "scrollY": "300px",
  "scrollCollapse": true,
  "paging": false,


        language: {
            emptyTable: "No hay datos para mostrar",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"inicio/php/back/get_last_ingresos.php",
              type: "POST",

              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },
          "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 1, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 1 ).footer() ).html(
                'Total: '+pageTotal
            );
        },

             "aoColumns": [
               { "class" : "text-left", mData: 'foto' },
               { "class" : "text-left", mData: 'invitado' },
               { "class" : "text-center", mData: 'conteo' },
               { "class" : "text-center", mData: 'hora' }
             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        table_m.ajax.reload(null, false);
      }, 120000 );*/



    };

    // Datatables listado de usuarios
    var initDataTableInvitados = function() {
      table_invitados = $('#tb_invitados').DataTable({

        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging":true,
        "info":true,
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
              url :"inicio/php/back/get_invitados_evento.php",
              type: "POST",

              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },



             "aoColumns": [

               { "class" : "text-left", mData: 'foto' },
               { "class" : "text-left", mData: 'codigo' },
               { "class" : "text-center", mData: 'invitado' },
               { "class" : "text-center", mData: 'institucion' },
               { "class" : "text-center", mData: 'status' }
             ]
      });



      /*setInterval( function () {
        table_m.ajax.reload(null, false);
      }, 120000 );*/



      };

      // Datatables listado de horarios
      var initDataTableHorarios = function() {
        table_horarios = $('#tb_horarios').DataTable({

          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          "info":true,
          "lengthChange": false,



          buttons: [
            {
         extend: 'excel',
         text:'Exportar <i class="fa fa-download"></i>',
         className: 'btn btn-sm btn-personalizado outline'
       }
           ],


          language: {
              emptyTable: "No hay datos para mostrar",
              loadingRecords: " <div class='spinner-grow text-primary'></div> "
          },
          "ajax":{
                url :"inicio/php/back/get_horarios_empleado.php",
                type: "POST",
                data: {
                  fecha:function() { return $('#ini').val() }
                },
                error: function(){
                  $("#post_list_processing").css("display","none");
                }
            },



               "aoColumns": [

                 { "class" : "text-left", mData: 'gafete' },
                 { "class" : "text-left", mData: 'direccion' },
                 { "class" : "text-left", mData: 'empleado' },
                 { "class" : "text-center", mData: 'fecha' },
                 { "class" : "text-center", mData: 'entrada' },
                 { "class" : "text-center", mData: 'entra_alm' },
                 { "class" : "text-center", mData: 'sale_alm' },
                 { "class" : "text-center", mData: 'salida' }

             /*,
                 { "class" : "text-center", mData: 'institucion' },
                 { "class" : "text-center", mData: 'status' }*/
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
                 { "class" : "text-left", mData: 'invitado' },
                 { "class" : "text-center", mData: 'conteo' }/*,

                 { "class" : "text-center", mData: 'novedades' },
                 { "class" : "text-center", mData: 'total' },
                 { "class" : "text-center", mData: 'operador' },
                 { "class" : "text-center", mData: 'operado' }*/

               ],
               buttons: [
                   'print'
               ]/*,
               "columnDefs": [
                 { "width": "10%", "targets":0},
                 { "width": "10%", "targets":1},
                 { "width": "25%", "targets": 2 }
               ]*/
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









    // DataTables Bootstrap integration

    return {
        init: function() {
            //Init Datatables
            bsDataTables();
            initDataTableAcreditaciones();
            initDataTableInvitados();
            initDataTableDashboard_();
            initDataTableHorarios();
        }
    };
}();

function reload_acreditaciones(){
  table_acreditaciones.ajax.reload(null, false);
}

function reload_totales_dashboard(){
  table_dashboard_.ajax.reload(null, false);
}

function reload_invitados(){
  table_invitados.ajax.reload(null, false);
}

function reload_horarios(){
  table_horarios.ajax.reload(null, false);
}

// Initialize when page loads
jQuery(function(){ AcreditacionesTableDatatables.init(); });
