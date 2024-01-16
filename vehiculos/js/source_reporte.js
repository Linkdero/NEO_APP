var table_reporte_alimentacion_por_fecha,table_reporte_alimentacion_por_direccion,table_reporte_alimentacion_por_colaborador;
var AlimentacionTableDatatables_modal = function() {

    var initDataTableReporte_comidas_por_fecha = function() {
      table_reporte_alimentacion_por_fecha = $('#tb_alimentos_por_fecha').DataTable({
        "ordering": false,
        "pageLength":50,
        "bProcessing": true,   
        "paging":true,
        "info":true,
        scrollX:        true,
        scrollCollapse: true,
        fixedColumns:   true,
        fixedColumns: {
          leftColumns: 1
          },

        language: {
            emptyTable: "No hay solicitudes",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
        },
        "ajax":{
              url :"alimentos/php/back/listados/reporte_alimentos_por_fecha.php",
              type: "POST",
              data:{
                  ini:function() { return $('#ini').val() },
                  fin:function() { return $('#fin').val() },
                  direccion:function() { return $('#direccion_rrhh').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               //{ "class" : "text-center", mData: 'foto', "width":"2%"},
               { "class" : "text-center", mData: 'fecha'},
               { "class" : "text-center", mData: 'desayuno'},
               { "class" : "text-center", mData: 'almuerzo'},
               { "class" : "text-center", mData: 'cena'}

             ],

             buttons: [{
                    text: 'Imprimir <i class="fa fa-print"></i>',
                    className: 'btn btn-sm btn-personalizado outline',
                    action: function ( e, dt, node, config ) {
                      imprimir_reportexFecha()
                    }
                  }],

            "footerCallback": function ( row, data, start, end, display ) {
				var api = this.api();
				nb_cols = api.columns().nodes().length;
				var j = 1;
				while(j < nb_cols){
					var pageTotal = api
                    .column( j, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return Number(a) + Number(b);
                    }, 0 );
                    // Update footer
                    $( api.column( j ).footer() ).html(pageTotal);
					j++;
				}
            }
        

      });
    };

    var initDataTableReporte_comidas_por_direccion = function() {  
      table_reporte_alimentacion_por_direccion = $('#tb_alimentos_por_direccion').DataTable({
        "ordering": false,
        "pageLength":25,
        "bProcessing": true,   
        "paging":true,
        "info":true,
        scrollX:        true,
        scrollCollapse: true,
        fixedColumns:   true,
        fixedColumns: {
          leftColumns: 1
          },

        language: {
            emptyTable: "No hay solicitudes",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
        },
        "ajax":{
              url :"alimentos/php/back/listados/reporte_alimentos_por_direccion.php",
              type: "POST",
              data:{
                  ini:function() { return $('#ini').val() },
                  fin:function() { return $('#fin').val() },
                  direccion:function() { return $('#direccion_rrhh').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               //{ "class" : "text-center", mData: 'foto', "width":"2%"},
               { "class" : "text-left", mData: 'direccion'},
               { "class" : "text-center", mData: 'desayuno'},
               { "class" : "text-center", mData: 'almuerzo'},
               { "class" : "text-center", mData: 'cena'}

             ],
             buttons: [{
                text: 'Imprimir <i class="fa fa-print"></i>',
                className: 'btn btn-sm btn-personalizado outline',
                action: function ( e, dt, node, config ) {
                  imprimir_reportexDireccion()
                }
              }],

            "footerCallback": function ( row, data, start, end, display ) {
				var api = this.api();
				nb_cols = api.columns().nodes().length;
				var j = 1;
				while(j < nb_cols){
					var pageTotal = api
                    .column( j, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return Number(a) + Number(b);
                    }, 0 );
                    // Update footer
                    $( api.column( j ).footer() ).html(pageTotal);
					j++;
				}
			}

      });
    };

    var initDataTableReporte_comidas_por_colaborador = function() { 
      table_reporte_alimentacion_por_colaborador = $('#tb_alimentos_por_colaborador').DataTable({
        "ordering": false,
        "pageLength":25,
        "bProcessing": true,   
        "paging":true,
        "info":true,
        scrollX:        true,
        scrollCollapse: true,
        fixedColumns:   true,
        fixedColumns: {
          leftColumns: 1
          },

        language: {
            emptyTable: "No hay solicitudes",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
        },
        "ajax":{
              url :"alimentos/php/back/listados/reporte_alimentos_por_colaborador.php",
              type: "POST",
              data:{
                  ini:function() { return $('#ini').val() },
                  fin:function() { return $('#fin').val() },
                  direccion:function() { return $('#direccion_rrhh').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               //{ "class" : "text-center", mData: 'foto', "width":"2%"},
               { "class" : "text-left", mData: 'direccion'},
               { "class" : "text-left", mData: 'nombre'},
               { "class" : "text-center", mData: 'desayuno'},
               { "class" : "text-center", mData: 'almuerzo'},
               { "class" : "text-center", mData: 'cena'}
             ],

                buttons: [
                    {
                        text: 'Imprimir <i class="fa fa-print"></i>',
                        className: 'btn btn-sm btn-personalizado outline',
                        action: function ( e, dt, node, config ) {
                          imprimir_reportexColab()
                        }
                      }],
    
            "footerCallback": function ( row, data, start, end, display ) {
				var api = this.api();
				nb_cols = api.columns().nodes().length;
				var j = 1;
				while(j < nb_cols){
					var pageTotal = api
                    .column( j, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return Number(a) + Number(b);
                    }, 0 );
                    // Update footer
                    $( api.column( j ).footer() ).html(pageTotal);
					j++;
				}
			}

      });

    //   $('#tb_empleados_asignar tbody').on( 'click', 'button', function () {
    //       var data = table_empleados_asignar.row( $(this).parents('tr') ).data();
    //       //alert( data['marca'] +"'s serie es: "+ data['serie'] );
    //       $('#myPopupInput1').val(data['id_persona']);
    //   } );




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

    return {
        init: function() {
            //Init Datatables
            bsDataTables();

            initDataTableReporte_comidas_por_fecha();
            initDataTableReporte_comidas_por_direccion();
            initDataTableReporte_comidas_por_colaborador();
        }
    };
}();

function reload_reporte_alimentacion(){
    table_reporte_alimentacion_por_fecha.ajax.reload(null, false);
    table_reporte_alimentacion_por_direccion.ajax.reload(null,false);
    table_reporte_alimentacion_por_colaborador.ajax.reload(null,false);
}




// Initialize when page loads
jQuery(function(){ AlimentacionTableDatatables_modal.init(); });
