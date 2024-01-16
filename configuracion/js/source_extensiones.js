
var tabla_directorio;

function findWithAttr(array, attr, value) {
    for(var i = 0; i < array.length; i += 1) {
        if(array[i][attr] === value) {
            return i;
        }
    }
    return -1;
}

function get_fotografia(tabla, columna){
    $('#'+tabla+' tr').each(function(index, element){
       var id_persona = $(element).find("td").eq(columna).html();
       $.ajax({
         type: "POST",
         url: "configuracion/php/back/herramientas/get_fotografia.php",
         dataType:'json',
         data: {
           id_persona:id_persona
         },
         beforeSend:function(){
           //$(element).find("td").eq(columna).html('Cargando...');
         },
         success:function(data){
        //    console.log(data);

           $(element).find("td").eq(columna).html(data.Foto);

             }
           });
     });

  }

var EmpleadoDirectorio = function() {



    // Datatables listado de usuarios
    var initDatatableEmpleadoDirectorio = function() {
      tabla_directorio = $('#tb_directorio').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,
        "searching":true,
        // "scrollX": true,
        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
            url :"configuracion/php/back/herramientas/get_extensiones.php",
            type: "POST",
            data:{ tipo:function() { return $('#tipo').val() }},
            error: function(){
              $("#post_list_processing").css("display","none");
            }
        },

        "aoColumns": [
            { "class" : "text-center", mData: 'Extensión'},
            { "class" : "text-center", mData: 'Ubicación'},
            { "class" : "text-center", mData: 'Puesto'},
            { "class" : "text-center", mData: 'Empleado' },
            { "class" : "text-center", mData: 'Correo' },
            { "class" : "text-center", mData: 'Foto' },
            { "class" : "text-center", mData: 'Acción' }
          ],

           
           
          drawCallback: function ( settings ) {
            get_fotografia('tb_directorio',5);
          },

        //   initComplete: function() {
        //     var column = this.api().column(2);
        //     var select = $('<select class="form-control"><option value="">TODOS</option></select>')
        //       .appendTo('#filter1')
        //       .on('change', function() {
        //         var val = $(this).val();
        //         column.search(val).draw()
        //       });
      
        //      var offices = []; 
        //      column.data().toArray().forEach(function(s){
        //              s = s.split(',');
        //         s.forEach(function(d) {
        //           if (!~offices.indexOf(d)) {
        //               offices.push(d);
        //             }
        //         })
        //      })
        //      offices.sort();
        //      offices.forEach(function(d) {
        //         select.append('<option value="' + d + '">' + d + '</option>');
        //       })


        //   }
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
              sInfo:           "Mostrando empleados del _START_ al _END_ de un total de _TOTAL_ empleados",
              sInfoEmpty:      "Mostrando empleados del 0 al 0 de un total de 0 empleados",
              sInfoFiltered:   "(filtrado de un total de _MAX_ empleados)",
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
            initDatatableEmpleadoDirectorio();

                    }
    };
}();

function reload_directorio(){
    tabla_directorio.ajax.reload(null, false);
  }



// Initialize when page loads
jQuery(function(){ EmpleadoDirectorio.init(); });
