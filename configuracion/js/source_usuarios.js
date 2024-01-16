
var tabla_catalogo;

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
         url: "directorio/php/back/telefonos/get_fotografia.php",
         dataType:'json',
         data: {
           id_persona:id_persona
         },
         beforeSend:function(){
         },
         success:function(data){
           $(element).find("td").eq(columna).html(data.Foto);

             }
           });
     });

  }

  function check_checkbox(id_persona, column){
    let tipo = (column==5 ? 'status' : 'valida_ldap');
    let y = ''+column+id_persona+'';
    let set = (!document.getElementById(y).checked==true ? 0 : 1);
    // alert('id_persona '+id_persona+'\n flag '+set+'\n tipo '+tipo);
       $.ajax({
         type: "POST",
         url: "configuracion/php/back/empleado/update_usuario.php",
         dataType:'json',
         data: {
           id_persona:id_persona, flag:set, tipo:tipo
         }
           });        
  }



var Catalogo = function() {


    

    // Datatables listado de usuarios
    var initDatatableCatalogo = function() {
      tabla_catalogo = $('#tb_catalogo').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,
        "searching":true,
        // "scrollX": true,
        "select":true,
        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
            url :"configuracion/php/back/herramientas/get_usuarios.php",
            type: "POST",
            error: function(){
              $("#post_list_processing").css("display","none");
            }
        },

           "aoColumns": [
             { "class" : "text-center", mData: 'id_persona'},
             { "class" : "text-center", mData: 'nombre'},
             { "class" : "text-center", mData: 'dir'},
             { "class" : "text-center", mData: 'puesto' },
             { "class" : "text-center", mData: 'user' },
             { "class" : "text-center", mData: 'status' },
             { "class" : "text-center", mData: 'valid' },
             { "class" : "text-center", mData: 'accion' }
           ],

           
           "columnDefs":[
            {
              'targets':[5,6],
              'className': 'dt-body-center',
              'render': function (data, type, full, meta){
                cb = '<label class="css-input switch switch-success switch-sm"><input type="checkbox" class="dt-checkboxes" autocomplete="off" id="'+meta.col+full.id_persona+'" value="' + $('<div/>').text(data).html() + '" onclick="check_checkbox('+full.id_persona+','+meta.col+')"';
                  if(data==1){
                      return cb+'checked><span></span></label>';
                  }
                      return cb+'><span></span></label>';
              } 
            }
          ],

          'select':{             
            'style':'multi'
          },

        //   "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
            // $('td:eq(4)', nRow).html('<a class="" href="#" ">'+aData.user+'</a>').editable({
            //     pk:aData.id_persona,
            //     name:1,
            //     url: '',
            //     send:'always',
            //     validate: function(value){
            //         if($.trim(value) == ''){
            //         Swal.fire({
            //             type: 'error',
            //             title: 'El valor no puede ser vacío',
            //             showConfirmButton: false,
            //             timer: 1100
            //         });
            //         }
            //     }
            // });
        //     return nRow;
        // },

          

        drawCallback: function ( settings ) {
            get_fotografia('tb_catalogo',0);
        },

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
              });
              


          },

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
            initDatatableCatalogo();

                    }
    };
}();


function reload_usuarios(){
    tabla_catalogo.ajax.reload(null, false);
  }

// Initialize when page loads
jQuery(function(){ Catalogo.init(); });
