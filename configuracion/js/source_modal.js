
var table_p,table_empspantalla,table_accesos,table_permisosaccesos,
table_accesos_persona,table_accesos_persona_pendiente;
var PantallasTableDatatables = function() {



    // Datatables listado de usuarios
    var initDataTablePantallas = function() {
      table_p = $('#tb_pantallas').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"configuracion/php/back/listados/get_pantallas.php",
              type: "POST",
              data: {modulo:function() { return $('#id_modulo').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-center", mData: 'id_pantalla' },
               { "class" : "text-left", mData: 'titulo' },
               { "class" : "text-left", mData: 'pantalla_padre' },
               { "class" : "text-left", mData: 'id_modulo' },
               { "class" : "text-center", mData: 'id_activo' },
               { "class" : "text-center", mData: 'accion' }
             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        table_m.ajax.reload(null, false);
      }, 100000 );*/



    };

    var initDataTableEmpsPantalla = function() {
      table_empspantalla = $('#tb_empspantalla').DataTable({
        //"scrollX": true,
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,
        //"scrollX" : true,


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"configuracion/php/back/listados/get_empspantalla.php",
              type: "POST",
              data: {pantalla:function() { return $('#id_pantalla').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-center", mData: 'id_acceso' },
               { "class" : "text-left", mData: 'empleado' },
               { "class" : "text-center", mData: 'menu' },
               { "class" : "text-center", mData: 'insertar' },
               { "class" : "text-center", mData: 'eliminar' },
               { "class" : "text-center", mData: 'actualizar' },
               { "class" : "text-center", mData: 'imprimir' },
               { "class" : "text-center", mData: 'acceso' },
               { "class" : "text-center", mData: 'autoriza' },
               { "class" : "text-center", mData: 'descarga' },

             ],
/*             buttons: [
              'excel',
              {
                text: 'Asignar privilegios <i class="fa fa-check"></i>',
                className: 'btn btn-sm btn-personalizado outline',
                action: function ( e, dt, node, config ) {
                  upd_per_pri_pan()
                }
              },
              {
                text: 'Recargar <i class="fa fa-sync"></i>',
                className: 'btn btn-sm btn-personalizado outline',
                action: function ( e, dt, node, config ) {
                  reload_empspantallas()
                }
              }
            ] */
      });

      $('#tb_empspantalla tbody').on( 'click', 'td', function () {
        var idx = table_empspantalla.cell( this ).index().column;
        var title = table_empspantalla.column( idx ).header();

        //alert( 'Column title clicked on: '+$(title).html() );
        $("input:checkbox").change(function() {
          flag= $(this).data('id');
          pantalla_acceso=$(this).data('tipe');
          //var combo='#vehiculo_id'+id;
          if ($(this).is(':checked') ) {
            //alert("seleccionado" + id+ ' '+pantalla_acceso);
            $.ajax({
              type: "POST",
              url: "configuracion/php/back/settings/update_singular_privilegio.php",
              data: {pantalla_acceso:pantalla_acceso, flag:flag,valor:1},
              beforeSend:function(){

              },
              success:function(data){
                //alert(data);
              }

            }).done( function() {

            }).fail( function( jqXHR, textSttus, errorThrown){


            });
          }else{
            $.ajax({
              type: "POST",
              url: "configuracion/php/back/settings/update_singular_privilegio.php",
              data: {pantalla_acceso:pantalla_acceso, flag:flag,valor:0},
              beforeSend:function(){

              },
              success:function(data){
                //alert(data);
              }

            }).done( function() {

            }).fail( function( jqXHR, textSttus, errorThrown){


            });
          }
        });
      } );



      /*setInterval( function () {
        table_empspantalla.ajax.reload(null, false);
      }, 100000 );*/



    };

    var initDataTableAccesos = function() {
      table_accesos = $('#tb_accesos').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"configuracion/php/back/listados/get_accesos.php",
              type: "POST",
              data: {modulo:function() { return $('#id_modulo').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [

               { "class" : "text-center", mData: 'id_modulo' },
               { "class" : "text-center", mData: 'id_acceso' },
               { "class" : "text-center", mData: 'id_persona' },
               { "class" : "text-center", mData: 'empleado' },
               { "class" : "text-center", mData: 'estado' },
               { "class" : "text-center", mData: 'accion' }
             ],
            buttons: [
              'excel'
            ],
            'columnDefs': [{
              'targets': [4],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
              if (data === "Activo") {
                return '<span class="badge badge-success">Activo.</span>';
              }
              else{
                return '<span class="badge badge-danger">Inactivo</span>';
              }
            }

          }]
      });



      /*setInterval( function () {
        table_accesos.ajax.reload(null, false);
      }, 100000 );*/



    };

    var initDataTablePermisosAcceso = function() {
      table_permisosaccesos = $('#tb_permisosaccesos').DataTable({
        //"scrollX": true,
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,
        //"scrollX" : true,


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
          url :"configuracion/php/back/listados/get_permisos_por_acceso.php",
          type: "POST",
          data: {acceso:function() { return $('#id_acceso').val() }},
          error: function(){
            $("#post_list_processing").css("display","none");
          }
        },
        columns: [
        { name: 'col-acceso' },
        { name: 'flag_es_menu' },
        { name: 'flag_insertar' },
        { name: 'flag_eliminar' },
        { name: 'flag_actualizar' },
        { name: 'flag_imprimir' },
        { name: 'flag_acceso' },
        { name: 'flag_autoriza' },
        { name: 'flag_descarga' }
    ],

        "aoColumns": [
          { "class" : "text-center", mData: 'pantalla' },
          { "class" : "text-center", mData: 'menu' },
          { "class" : "text-center", mData: 'insertar' },
          { "class" : "text-center", mData: 'eliminar' },
          { "class" : "text-center", mData: 'actualizar' },
          { "class" : "text-center", mData: 'imprimir' },
          { "class" : "text-center", mData: 'acceso' },
          { "class" : "text-center", mData: 'autoriza' },
          { "class" : "text-center", mData: 'descarga' },
        ],
        buttons: [
          /*{
            extend: 'excel',
            text:'Exportar <i class="fa fa-download"></i>',
            className: 'btn btn-sm btn-personalizado outline'
          },*/
          {
            text: 'Asignar privilegios <i class="fa fa-check"></i>',
            className: 'btn btn-sm btn-personalizado outline',
            action: function ( e, dt, node, config ) {
              upd_per_pri_pan()
            }
          },
          {
            text: 'Recargar <i class="fa fa-sync"></i>',
            className: 'btn btn-sm btn-personalizado outline',
            action: function ( e, dt, node, config ) {
              reload_permisos_por_acceso()
            }
          }
        ]/*,
        'columnDefs': [{
          'targets': [1,2,3,4,5,6,7,8],
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          render: function(data, type, row, meta) {

          var input = $("<input/>", {
            "data-type": meta,
            "data-id": row.id_acceso + '-' + row.id_pantalla,
            "type": "checkbox",
          });
          if (data === "1") {
            input.attr("checked", "checked");
          }
          return input.prop("outerHTML");
        }

      }]*/
          });



          $('#tb_permisosaccesos tbody').on( 'click', 'td', function () {
            var idx = table_permisosaccesos.cell( this ).index().column;
            var title = table_permisosaccesos.column( idx ).header();

            //alert( 'Column title clicked on: '+$(title).html() );
            $("input:checkbox").change(function() {
              flag= $(this).data('id');
              pantalla_acceso=$(this).data('tipe');
              //var combo='#vehiculo_id'+id;
              if ($(this).is(':checked') ) {
                //alert("seleccionado" + id+ ' '+pantalla_acceso);
                $.ajax({
                  type: "POST",
                  url: "configuracion/php/back/settings/update_singular_privilegio.php",
                  data: {pantalla_acceso:pantalla_acceso, flag:flag,valor:1},
                  beforeSend:function(){

                  },
                  success:function(data){
                    //alert(data);
                  }

                }).done( function() {

                }).fail( function( jqXHR, textSttus, errorThrown){


                });
              }else{
                $.ajax({
                  type: "POST",
                  url: "configuracion/php/back/settings/update_singular_privilegio.php",
                  data: {pantalla_acceso:pantalla_acceso, flag:flag,valor:0},
                  beforeSend:function(){

                  },
                  success:function(data){
                    //alert(data);
                  }

                }).done( function() {

                }).fail( function( jqXHR, textSttus, errorThrown){


                });
              }
            });
          } );



      /*setInterval( function () {
        table_permisosaccesos.ajax.reload(null, false);
      }, 100000 );*/



    };


    var initDataTableAccesosPersona = function() {
      table_accesos_persona = $('#tb_accesos_persona').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay accesos asignadas",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"configuracion/php/back/listados/get_accesos_por_persona.php",
              type: "POST",
              data: {id_persona:function() { return $('#id_persona').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [

               { "class" : "text-center", mData: 'id_acceso' },
               { "class" : "text-center", mData: 'empleado' },
               { "class" : "text-center", mData: 'modulo' },
               { "class" : "text-center", mData: 'estado' },
               { "class" : "text-center", mData: 'accion' }
             ],
            buttons: [


            ],
            'columnDefs': [{
              'targets': [3],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
              if (data === "Activo") {
                return '<span class="badge badge-success">Activo.</span>';
              }
              else{
                return '<span class="badge badge-danger">Inactivo</span>';
              }
            }

          }]
      });



      /*setInterval( function () {
        table_accesos_persona.ajax.reload(null, false);
      }, 100000 );*/



    };

    var initDataTableAccesosPersonaPendiente = function() {
      table_accesos_persona_pendiente = $('#tb_accesos_persona_pendiente').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay accesos asignadas",
            loadingRecords: " <div class='spinner-grow text-primary'></div> "
        },
        "ajax":{
              url :"configuracion/php/back/listados/get_accesos_por_persona_pendiente.php",
              type: "POST",
              data: {id_persona:function() { return $('#id_persona').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [

               { "class" : "text-center", mData: 'id_modulo' },
               { "class" : "text-left", mData: 'modulo' },
               { "class" : "text-center", mData: 'estado' },
               { "class" : "text-center", mData: 'accion' }
             ],
            buttons: [
              {
                text: 'Recargar <i class="fa fa-sync"></i>',
                className: 'btn btn-sm btn-personalizado outline',
                action: function ( e, dt, node, config ) {
                  reload_accesos_pendiente()
                }
              }

            ],
            'columnDefs': [{
              'targets': [2],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
              if (data === "1") {
                return '<span class="badge badge-success">Activo.</span>';
              }
              else{
                return '<span class="badge badge-danger">Inactivo</span>';
              }
            }

          }]
      });



      /*setInterval( function () {
        table_accesos_persona.ajax.reload(null, false);
      }, 100000 );*/



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
            initDataTablePantallas();
            initDataTableEmpsPantalla();
            initDataTableAccesos();
            initDataTablePermisosAcceso();
            initDataTableAccesosPersona();
            initDataTableAccesosPersonaPendiente();


        }
    };
}();

function reload_pantallas(){
  table_p.ajax.reload(null, false);
}

function reload_empspantallas(){
  table_empspantalla.ajax.reload(null, false);
}

function reload_permisos_por_acceso(){
  table_permisosaccesos.ajax.reload(null, false);
}

function reload_accesos_pendiente(){
  table_accesos_persona_pendiente.ajax.reload(null,false);
}


// Initialize when page loads
jQuery(function(){ PantallasTableDatatables.init(); });
