
var tabla_catalogo;

function findWithAttr(array, attr, value) {
    for(var i = 0; i < array.length; i += 1) {
        if(array[i][attr] === value) {
            return i;
        }
    }
    return -1;
}



var Catalogo = function() {



    // Datatables listado de usuarios
    var initDatatableCatalogo = function() {
      tabla_catalogo = $('#tb_catalogo').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "searching":true,
        "scrollX": true,
        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
            url :"configuracion/php/back/herramientas/get_listado.php",
            type: "POST",
            error: function(){
              $("#post_list_processing").css("display","none");
            }
        },

           "aoColumns": [
             { "class" : "text-center", mData: 'renglon'},
             { "class" : "text-center", mData: 'codigo_ins'},
             { "class" : "text-center", mData: 'nombre'},
             { "class" : "text-center", mData: 'caracteristicas' },
             { "class" : "text-center", mData: 'presentacion' },
             { "class" : "text-center", mData: 'cantidad' },
             { "class" : "text-center", mData: 'codigo_pre' }
           ],

          

          initComplete: function() {
            var column = this.api().column(2);
            var select = $('<select class="form-control"><option value="">TODOS</option></select>')
              .appendTo('#filter1')
              .on('change', function() {
                var val = $(this).val();
                column.search(val).draw()
              });
      
             var offices = []; 
             column.data().toArray().forEach(function(s){
                     s = s.split(',');
                s.forEach(function(d) {
                  if (!~offices.indexOf(d)) {
                      offices.push(d);
                    }
                })
             })
             offices.sort();
             offices.forEach(function(d) {
                select.append('<option value="' + d + '">' + d + '</option>');
              })


          },

          "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
            
        //     $('td:eq(3)', nRow).html('<a class="" href="#" ">'+aData.ddescripcion+'</a>').editable({
        //         pk:aData.id_item,
        //         name:1,
        //         url: 'configuracion/php/back/herramientas/update_rows.php',
        //         send:'always',
        //         validate: function(value){
        //             if($.trim(value) == ''){
        //             Swal.fire({
        //                 type: 'error',
        //                 title: 'El valor no puede ser vacío',
        //                 showConfirmButton: false,
        //                 timer: 1100
        //             });
        //             }
        //         }
        //     });

            // $('td:eq(1)', nRow).html('<a class="" href="#" ">'+aData.tipo+'</a>').editable({
            //     pk:aData.id_telefono,
            //     name:3,
            //     type: 'select',
            //     url: 'directorio/php/back/telefonos/update_rows.php',
            //     send:'always',
            //     source: options_tipo,
            // });
            // $('td:eq(2)', nRow).html('<a class="" href="#" ">'+aData.referencia+'</a>').editable({
            //     pk:aData.id_telefono,
            //     name:4,
            //     type: 'select',
            //     url: 'directorio/php/back/telefonos/update_rows.php',
            //     send:'always',
            //     source: options_ref,
            // });

            // $('td:eq(3)', nRow).html('<a class="" href="#" ">'+aData.observaciones+'</a>').editable({
            //     pk:aData.id_telefono,
            //     name:2,
            //     url: 'directorio/php/back/telefonos/update_rows.php',
            //     send:'always',
            // });

            return nRow;
        
        }


      });

      
    };

    var bsDataTables = function() {
      var $DataTable = jQuery.fn.dataTable;

      // Set the defaults for DataTables init
      jQuery.extend( true, $DataTable.defaults, {

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
              sInfo:           "Mostrando insumos del _START_ al _END_ de un total de _TOTAL_insumos",
              sInfoEmpty:      "Mostrando insumos del 0 al 0 de un total de 0 insumos",
              sInfoFiltered:   "(filtrado de un total de _MAX_ insumos)",
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
            initDatatableCatalogo();

                    }
    };
}();




// Initialize when page loads
jQuery(function(){ Catalogo.init(); });
