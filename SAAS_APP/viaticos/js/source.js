
var table_solicitudes,table_reporte, table_pendientes;
var ViaticosTableDatatables_listado = function() {




    var initDatatableSolicitudes = function() {
      table_solicitudes = $('#tb_solicitudes').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging":true,
        "info":true,
        responsive:true,
        /*scrollX:        true,
        scrollY: false,
        scrollCollapse: true,

        fixedColumns:   true,
        fixedColumns: {
          leftColumns: 1
        },*/
          language: {
              emptyTable: "No hay nombramientos para mostrar",
              sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
              //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
              url :"viaticos/php/back/listados/get_solicitudes.php",
              type: "POST",
              data:{
                tipo:function() { return $('#id_tipo_filtro').val() }
              },
              error: function(){
                  $("#post_list_processing").css("display","none");
              }
          },
          "aoColumns": [
              { "class" : "text-center", mData: 'nombramiento' },
              { "class" : "text-center", mData: 'fecha' },
              { "class" : "text-center", mData: 'direccion_solicitante' },
              { "class" : "text-center", mData: 'tipo' },
              { "class" : "text-center", mData: 'destino' },
              //{ "class" : "text-center", mData: 'motivo' },
              { "class" : "text-center", mData: 'fecha_ini' },
              { "class" : "text-center", mData: 'fecha_fin' },
              //{ "class" : "text-center", mData: 'personas' },
              { "class" : "text-center", mData: 'estado' },
              //{ "class" : "text-center", mData: 'progress' },
              { "class" : "text-center", mData: 'accion' }/**,
              { "class" : "text-center", mData: 'fecha_ini' }/*,
              { "class" : "text-center", mData: 'fecha_ini' }/*,
              { "class" : "text-center", mData: 'fuente' },
              { "class" : "text-center", mData: 'categoria' },
              { "class" : "text-center", mData: 'propietario' },
              { "class" : "text-center", mData: 'departamento' },
              { "class" : "text-center", mData: 'municipio' },
              { "class" : "text-center", mData: 'observaciones' },
              { "class" : "text-center", mData: 'accion' }¨*/
          ],
          buttons: [

            {
              text: 'Pendientes <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info',
              action: function ( e, dt, node, config ) {
                recargar_nombramientos(2);
              }
            },
            {
              text: '1 año <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info',
              action: function ( e, dt, node, config ) {
                recargar_nombramientos(3);
              }
            },
            {
              text: 'Todos <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info',
              action: function ( e, dt, node, config ) {
                recargar_nombramientos(1);
              }
            }

          ],

          "columnDefs": [
            {responsivePriority:0, targets: 7},
            {responsivePriority:1, targets: 8},
            {
                "targets": 3,
                "width": "30px"
            },
            {
              'targets': [8],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
                //turn row.id();
                //return format(row.serie);//format(row.serie);
                var menu='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion('+row.DT_RowId+',1)"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu'+row.DT_RowId+'"></div></div></div></div>';
              return  '<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'&tipo_filtro='+$('#id_tipo_filtro').val()+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'
              /*if (data === "5338" || data ==="5339" || data ==="5491") {
                //return '<span class="badge badge-success">Activo.</span>'+ meta.row;

                 //return format(row.serie);//format(row.serie);
                 return '--';
              }
              else{
                return '';
              }*/

            }

          }/*,

            {
            'targets': [8],
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
                render: function(data, type, row, meta) {
                    if (data === "ANULADO EN DIRECCION" || data === "ANULACION EN CALCULO" || data === "ANULACION EN IMPRESION CHEQUE" || data === "ANULACION CHEQUE IMPRESO" || data === "ANULACION NOMBRAMIENTO POR COMPLETO") {
                        return '<span class="badge badge-danger">'+data+'</span>';
                    } else if(data === "2"){
                        return '<span class="badge badge-info">Activo</span>';
                    } else if(data === "3"){
                        return '<span class="badge badge-success">Aprobado</span>';
                    }else{
                        return '<span class="badge badge-danger">Inactivo</span>';
                    }
                }
            }*/
          ]
      });

      $('#tb_empleados_asignar tbody').on( 'click', 'button', function () {
          var data = table_empleados_asignar.row( $(this).parents('tr') ).data();
          //alert( data['marca'] +"'s serie es: "+ data['serie'] );
          $('#myPopupInput1').val(data['id_persona']);
      } );




    };

    var initDatatablePendientes = function() {
      table_pendientes = $('#tb_pendientes').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging":true,
        "info":true,
        scrollX:        true,
        scrollY: false,
        scrollCollapse: true,

        fixedColumns:   true,
        fixedColumns: {
          leftColumns: 1
        },
          language: {
              emptyTable: "No hay noticias disponibles",
              sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
              //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
              url :"viaticos/php/back/listados/get_solicitudes.php",
              type: "POST",
              data:{
                tipo:2
              },
              error: function(){
                  $("#post_list_processing").css("display","none");
              }
          },
          "aoColumns": [
              { "class" : "text-center", mData: 'nombramiento' },
              { "class" : "text-center", mData: 'fecha' },
              { "class" : "text-center", mData: 'direccion_solicitante' },

              { "class" : "text-center", mData: 'destino' },
              //{ "class" : "text-center", mData: 'motivo' },
              { "class" : "text-center", mData: 'fecha_ini' },
              { "class" : "text-center", mData: 'fecha_fin' },
              //{ "class" : "text-center", mData: 'personas' },
              { "class" : "text-center", mData: 'estado' },
              //{ "class" : "text-center", mData: 'progress' },
              { "class" : "text-center", mData: 'accion' }/**,
              { "class" : "text-center", mData: 'fecha_ini' }/*,
              { "class" : "text-center", mData: 'fecha_ini' }/*,
              { "class" : "text-center", mData: 'fuente' },
              { "class" : "text-center", mData: 'categoria' },
              { "class" : "text-center", mData: 'propietario' },
              { "class" : "text-center", mData: 'departamento' },
              { "class" : "text-center", mData: 'municipio' },
              { "class" : "text-center", mData: 'observaciones' },
              { "class" : "text-center", mData: 'accion' }¨*/
          ],
          buttons: [

          ],
          "columnDefs": [
            {
                "targets": 3,
                "width": "30px"
            },
            {
              'targets': [7],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
                //turn row.id();
                //return format(row.serie);//format(row.serie);
                var menu='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion('+row.DT_RowId+',2)"><i class="fa fa-sliders-h"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu2'+row.DT_RowId+'"></div></div></div></div>';
              return  '<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lg" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'


            }

          }
          ]
      });

      $('#tb_empleados_asignar tbody').on( 'click', 'button', function () {
          var data = table_empleados_asignar.row( $(this).parents('tr') ).data();
          //alert( data['marca'] +"'s serie es: "+ data['serie'] );
          $('#myPopupInput1').val(data['id_persona']);
      } );




    };

    function get_fotografia(tabla, columna){
      $('#'+tabla+' tr').each(function(index, element){
         var id_persona = $(element).find("td").eq(columna).html();
         //console.log(id_persona);
         $.ajax({
           type: "POST",
           url: "empleados/php/back/empleados/get_fotografia.php",
           dataType:'json',
           data: {
             id_persona:id_persona
           },
           beforeSend:function(){
             //$(element).find("td").eq(columna).html('Cargando...');
           },
           success:function(data){

             $(element).find("td").eq(columna).html(data.fotografia);

               }
             });
       });

    }

    var initDataTableInsumos_reporte = function() {
      table_reporte = $('#tb_reporte').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging":true,
        "info":true,
        responsive:true,/*scrollX:        true,
    scrollCollapse: true,

    /*fixedColumns:   true,
    fixedColumns: {
leftColumns: 1
},*/

        //"dom": '<"">frtip',


        language: {
            emptyTable: "No hay nombramientos para mostrar",
            loadingRecords: " <div class='spinner-grow text-info'></div> "
        },
        "ajax":{
              url :"viaticos/php/back/listados/get_viaticos_por_pais.php",
              type: "POST",
              data:{
                tipo:function() { return $('#id_tipo').val() },
                mes:function() { return $('#id_mes').val() },
                year:function() { return $('#id_year').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               { "class" : "text-center", mData: 'empleado', "width":"20%"},
               { "class" : "text-center", mData: 'nombramiento', "width":"20%"},

               { "class" : "text-center", mData: 'direccion', "width":"20%"},
               { "class" : "text-center", mData: 'fecha_salida', "width":"20%"},
               { "class" : "text-center", mData: 'fecha_regreso', "width":"20%"},
               { "class" : "text-center", mData: 'pais',"width":"5%"},
               { "class" : "text-center", mData: 'departamento' },
               { "class" : "text-center", mData: 'municipio' },
               { "class" : "text-right", mData: 'total_real' },
               { "class" : "text-right", mData: 'total_mes' }/*,
               { "class" : "text-center", mData: 'cantidad_devuelta' },
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accion' }/*,
               { "class" : "text-center", mData: 'accesos' },
               { "class" : "text-center", mData: 'accion' }*/

             ],
            buttons: [
              'excel'
            ],
            "columnDefs": [
              {responsivePriority:0, targets: 6},
              {responsivePriority:0, targets: 8},
              {responsivePriority:1, targets: 9}
            ],
            'rowsGroup': [0]
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
            initDataTableInsumos_reporte();
            initDatatableSolicitudes();
            initDatatablePendientes();

                    }
    };
}();

function recargar_reporte(){
  table_reporte.ajax.reload(null,false);
}

function recargar_nombramientos(tipo){
  $('#id_tipo_filtro').val(tipo);
  table_solicitudes.ajax.reload(null,false);
}

// Initialize when page loads
jQuery(function(){ ViaticosTableDatatables_listado.init(); });
