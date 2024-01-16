
var tabla_emps,tabla_direcciones,tabla_telefonos,table_historial_plaza,table_plazas_por_empleado,table_plazas_por_empleado_h,tabla_contratos_por_persona;
var EmpleadosTableDatatables = function() {



    // Datatables listado de usuarios
    var initDataTableEmpleados = function() {
      tabla_emps = $('#tb_empleados').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_empleados.php",
              type: "POST",
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-left", mData: 'empleado' },
               { "class" : "text-center", mData: 'email' },
               { "class" : "text-center", mData: 'nit' },
               { "class" : "text-center", mData: 'igss' },
               { "class" : "text-center", mData: 'descripcion' },
               { "class" : "text-center", mData: 'status' },
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }
             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        tabla_emps.ajax.reload(null, false);
      }, 100000 );

*/

    };


    var initDataTableEmpleadoDirecciones = function() {
      tabla_direcciones = $('#tb_empleado_listado_direcciones').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,

        scrollX:        true,
        scrollCollapse: true,

        fixedColumns:   {
            leftColumns: 1,

        },


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_direcciones_by_empleado.php",
              type: "POST",
              data: {id_persona:function() { return $('#id_persona_di').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-left", mData: 'id_direccion' },
               { "class" : "text-center", mData: 'referencia' },
               { "class" : "text-center", mData: 'reside' },
               { "class" : "text-center", mData: 'nro_calle_avenida' },
               { "class" : "text-center", mData: 'tope' },
               { "class" : "text-center", mData: 'tipo_calle_desc' },
               { "class" : "text-center", mData: 'nro_casa' },
               { "class" : "text-center", mData: 'zona' },
               { "class" : "text-center", mData: 'departamento' },
               { "class" : "text-center", mData: 'municipio' },
               { "class" : "text-center", mData: 'lugar' },
               { "class" : "text-center", mData: 'tipo_lugar' }

             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        tabla_direcciones.ajax.reload(null, false);
      }, 100000 );*/



    };

    var initDataTableEmpleadoTelefonos = function() {
      tabla_telefonos = $('#tb_empleado_listado_telefonos').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,

        scrollX:        true,
        scrollCollapse: true,

        fixedColumns:   {
            leftColumns: 1,

        },


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_telefonos_by_empleado.php",
              type: "POST",
              data: {id_persona:function() { return $('#id_persona_di').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-left", mData: 'id_telefono' }/*,
               { "class" : "text-center", mData: 'referencia' },
               { "class" : "text-center", mData: 'reside' },
               { "class" : "text-center", mData: 'nro_calle_avenida' },
               { "class" : "text-center", mData: 'tope' },
               { "class" : "text-center", mData: 'tipo_calle_desc' },
               { "class" : "text-center", mData: 'nro_casa' },
               { "class" : "text-center", mData: 'zona' },
               { "class" : "text-center", mData: 'departamento' },
               { "class" : "text-center", mData: 'municipio' },
               { "class" : "text-center", mData: 'lugar' },
               { "class" : "text-center", mData: 'tipo_lugar' }*/

             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        tabla_direcciones.ajax.reload(null, false);
      }, 100000 );*/



    };

    var initDataTablePlazas= function() {
      tabla_plazas = $('#tb_plazas').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,

        scrollX:        true,
        scrollCollapse: true,

        fixedColumns:   {
            leftColumns: 1,

        },


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_plazas.php",
              type: "POST",
              //data: {id_persona:function() { return $('#id_persona_di').val() }},
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-left", mData: 'id_plaza' }/*,
               { "class" : "text-center", mData: 'referencia' },
               { "class" : "text-center", mData: 'reside' },
               { "class" : "text-center", mData: 'nro_calle_avenida' },
               { "class" : "text-center", mData: 'tope' },
               { "class" : "text-center", mData: 'tipo_calle_desc' },
               { "class" : "text-center", mData: 'nro_casa' },
               { "class" : "text-center", mData: 'zona' },
               { "class" : "text-center", mData: 'departamento' },
               { "class" : "text-center", mData: 'municipio' },
               { "class" : "text-center", mData: 'lugar' },
               { "class" : "text-center", mData: 'tipo_lugar' }*/

             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        tabla_direcciones.ajax.reload(null, false);
      }, 100000 );*/



    };

    // Datatables listado de usuarios
    var initDataTableHistorial_plazas= function() {
      table_historial_plaza = $('#tb_historial_plaza').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay pantallas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_historial_plaza.php",
              type: "POST",
              data:{
                partida:function() { return $('#partida').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-left", mData: 'empleado' },
               { "class" : "text-center", mData: 'fecha_toma' },
               { "class" : "text-center", mData: 'fecha_resecion' }
             ],
            buttons: [
            ]
      });



      /*setInterval( function () {
        tabla_emps.ajax.reload(null, false);
      }, 100000 );

*/

    };

    // Datatables listado de plazas por empleado
    var initDataTableHistorial_plazas_por_empleado= function() {
      table_plazas_por_empleado = $('#tb_plazas_por_empleado').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay plazas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_empleado_historial_plazas.php",
              type: "POST",
              data:{
                id_persona:function() { return $('#id_persona').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-left", mData: 'partida' },
               { "class" : "text-center", mData: 'plaza' },
               { "class" : "text-center", mData: 'puesto' },
               { "class" : "text-center", mData: 'inicio' },
               { "class" : "text-center", mData: 'final' },
               { "class" : "text-center", mData: 'sueldo' }

             ],
            buttons: [
            ],
            "columnDefs": [
    { "width": "12%", "targets": 0 }
  ]
      });



      /*setInterval( function () {
        tabla_emps.ajax.reload(null, false);
      }, 100000 );

*/

    };

    var initDataTableHistorial_plazas_por_empleado_h= function() {
      table_plazas_por_empleado_h = $('#tb_plazas_por_empleado_h').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay plazas asignadas",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/get_empleado_historial_plazas.php",
              type: "POST",
              data:{
                id_persona:function() { return $('#id_gafete').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               //{ "class" : "text-center", mData: 'plaza' },
               { "class" : "text-center", mData: 'cod_plaza' },
               { "class" : "text-left", mData: 'partida' },

               { "class" : "text-center", mData: 'puesto' },
               { "class" : "text-center", mData: 'inicio' },
               { "class" : "text-center", mData: 'final' },
               { "class" : "text-center", mData: 'sueldo' },
               { "class" : "text-center", mData: 'estado' },
               { "class" : "text-center", mData: 'accion' }


             ],
             buttons: [

               {
                 text: 'Asignar Plaza <i class="fa fa-plus"></i>',
                 className: 'btn btn-sm btn-soft-info',
                 action: function ( e, dt, node, config ) {
                   //cargar_asignar_plaza_empleado();
                   app_vue_datos_emp.getOpcion(1);
                 }
               },
               {
                 text: 'Recargar <i class="fa fa-sync"></i>',
                 className: 'btn btn-sm btn-soft-info',
                 action: function ( e, dt, node, config ) {
                   reload_historial_plaza_empleado();
                 }
               }
             ],
            "columnDefs": [
              { "width": "300px", "targets": 1 },
              {
                targets:1,
                render: function(data, type, row){
                  return data.substr(0,17)+' ... '+data.substr(40,21);
                }

              }
            ]/*,
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
              if(aData.estado==891);{
                return '<span class="badge badge-success">Activo</span>';
              }
            }*/
      });



      /*setInterval( function () {
        tabla_emps.ajax.reload(null, false);
      }, 100000 );

*/

    };

    //contratos por empleado
    var initDataTableHistorialContratosporPersona= function() {
      tabla_contratos_por_empleado = $('#tb_contratos_por_persona').DataTable({
        "ordering": false,
        "pageLength": 10,
        "bProcessing": true,


        language: {
            emptyTable: "No hay contratos asignados",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"empleados/php/back/listados/contratos/get_contratos_by_persona.php",
              type: "POST",
              data:{
                id_persona:function() { return $('#id_gafete').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               //{ "class" : "text-center", mData: 'plaza' },
               { "class" : "text-center", mData: 'renglon' },
               { "class" : "text-center", mData: 'nro_contrato' },


               { "class" : "text-center", mData: 'nro_acuerdo_aprobacion' },
               { "class" : "text-center", mData: 'fecha_acuerdo_aprobacion' },
               { "class" : "text-center", mData: 'fecha_finalizacion' },
               { "class" : "text-center", mData: 'fecha_fin' },
               { "class" : "text-right", mData: 'monto_mensual' },

               { "class" : "text-center", mData: 'puesto' },
               { "class" : "text-center", mData: 'accion' },


             ],
             buttons: [

               {
                 text: 'Asignar Contrato <i class="fa fa-plus"></i>',
                 className: 'btn btn-sm btn-soft-info',
                 action: function ( e, dt, node, config ) {
                   //cargar_asignar_plaza_empleado();
                 }
               },
               {
                 text: 'Recargar <i class="fa fa-sync"></i>',
                 className: 'btn btn-sm btn-soft-info',
                 action: function ( e, dt, node, config ) {
                   //reload_historial_plaza_empleado();
                 }
               }
             ]
      });
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

    return {
        init: function() {
            //Init Datatables
            bsDataTables();
            initDataTableEmpleados();
            initDataTableEmpleadoDirecciones();
            initDataTableEmpleadoTelefonos();
            initDataTableHistorial_plazas();
            initDataTableHistorial_plazas_por_empleado();
            initDataTableHistorial_plazas_por_empleado_h();
            initDataTableHistorialContratosporPersona();
          }
    };
}();

function reload_empleados(){
  tabla_emps.ajax.reload(null, false);
}

function reload_historial_plaza_empleado(){
  table_plazas_por_empleado_h.ajax.reload(null, false);
}


// Initialize when page loads
jQuery(function(){ EmpleadosTableDatatables.init(); });
