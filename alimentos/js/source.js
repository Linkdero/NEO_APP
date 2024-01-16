
var tabla_emps_listado,tabla_plazas_listado,tabla_emps_listado_exc ;

function findWithAttr(array, attr, value) {
    for(var i = 0; i < array.length; i += 1) {
        if(array[i][attr] === value) {
            return i;
        }
    }
    return -1;
}

function asignar(id_chk){
    tipo_comida=$('#'+id_chk).data('id');
    id_persona=$('#'+id_chk).data('tipe');
    
    if ($('#'+id_chk).is(':checked') ) {

        
        //console.log("seleccionado"+ id_persona + ' -- '+ id_comida);
        $.ajax({
          type: "POST",
          url: "alimentos/php/back/alimento/update_singular_comida.php",
          data: {id_persona, tipo_comida, valor:1},
          beforeSend:function(){

          },
          success:function(data){
            //alert(data);
            console.log(data);
          }

        }).done( function() {

        }).fail( function( jqXHR, textSttus, errorThrown){


        });
      }else{
        //console.log("des-seleccionado"+ id_persona + ' -- '+ id_comida);
        $.ajax({
          type: "POST",
          url: "alimentos/php/back/alimento/update_singular_comida.php",
          data: {id_persona, tipo_comida,valor:0},
          beforeSend:function(){

          },
          success:function(data){
            //alert(data);
            console.log(data);
          }

        }).done( function() {

        }).fail( function( jqXHR, textSttus, errorThrown){


        });
      }
}

var EmpleadosTableDatatables_listado = function() {



    // Datatables listado de usuarios
    // var initDataTableEmpleados_listado = function() {
    //   tabla_emps_listado = $('#tb_empleados_listado').DataTable({
    //     "ordering": false,
    //     "pageLength": 10,
    //     "bProcessing": true,
    //     language: {
    //         emptyTable: "No hay pantallas asignadas",
    //         loadingRecords: " <div class='spinner-grow text-info'></div> "
    //     },
    //     "ajax":{
    //           url :"alimentos/php/back/listados/get_empleados_rrhh.php",
    //           type: "POST",
    //           error: function(){
    //             $("#post_list_processing").css("display","none");
    //           }
    //       },

    //          "aoColumns": [
    //            { "class" : "text-left", mData: 'empleado' },
    //            { "class" : "text-center", mData: 'email' },
    //            { "class" : "text-center", mData: 'nit' },
    //            { "class" : "text-center", mData: 'igss' },
    //            { "class" : "text-center", mData: 'descripcion' },
    //            { "class" : "text-center", mData: 'status' },
    //            { "class" : "text-center", mData: 'nisp' },
    //            { "class" : "text-center", mData: 'accion' }
    //          ],
    //         buttons: [
    //           'excel'
    //         ]
    //   });
    //   /*setInterval( function () {
    //     tabla_emps_listado.ajax.reload(null, false);
    //   }, 100000 );*/
    // };

    var initDataTableEmpleados_listado = function() {    // por de pronto usar esta funcion
        tabla_emps_listado = $('#tb_empleados_listado').DataTable({
            ordering: false,
            pageLength: 10,
            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='loaderr'></div> "
            },
            "ajax":{
                url :"alimentos/php/back/listados/get_empleados_rrhh.php",
                type: "POST",
                error: function(){
                    $("#post_list_processing").css("display","none");
                }
            },
            "aoColumns": [
              { "class" : "text-center", mData: 'id_persona' },
                { "class" : "text-left", mData: 'empleado' },
                { "class" : "text-center", mData: 'desayuno' },
                { "class" : "text-center", mData: 'almuerzo' },
                { "class" : "text-center", mData: 'cena' }
            ],
            buttons: [
                //  'excel'
            ], 
            drawCallback: function ( settings ) {
              get_fotografia('tb_empleados_listado',0);
            },

      });

    };

   
    var initDataTableEmpleados_listado_exc = function() {    // por de pronto usar esta funcion
        tabla_emps_listado_exc = $('#tb_empleados_listado_exc').DataTable({
            ordering: false,
            pageLength: 10,
            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='loaderr'></div> "
            },
            "ajax":{
                url :"alimentos/php/back/listados/get_empleados_exc.php",
                type: "POST",
                error: function(){
                    $("#post_list_processing").css("display","none");
                }
            },
            "aoColumns": [
              { "class" : "text-center", mData: 'id_persona' },
                { "class" : "text-left", mData: 'empleado' },
                { "class" : "text-left", mData: 'del' },
                { "class" : "text-left", mData: 'al' },
                { "class" : "text-left", mData: 'tieneex' },
                { "class" : "text-center", mData: 'accion' }
            ],
            buttons: [
                 'excel'
            ], 
             drawCallback: function ( settings ) {
               get_fotografia('tb_empleados_listado_exc',0);
             },
            /*'columnDefs': [{
                'targets': [3,4],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function(data, type, row, meta) {
                    
                var input = $("<input/>", {
                  "data-type": meta,
                  "data-id": row.id_persona + '-' + row,
                  "type": "checkbox",
                });
                // if (data === "1") {
                //   input.attr("checked", "checked");
                // }
                return input.prop("outerHTML");
              }
      
            }]*/
      });


      /*$('#tb_empleados_listado tbody').on( 'click', 'td', function () {
        /*var idx = tabla_emps_listado.cell( this ).index().column;
        var title = tabla_emps_listado.column( idx ).header();

        //alert( 'Column title clicked on: '+$(title).html() );
        $("input:checkbox").change(function() {
            console.log('cambio');
          tipo_comida= $(this).data('id');
          id_persona=$(this).data('tipe');
          //var combo='#vehiculo_id'+id;
          if ($(this).is(':checked') ) {
            console.log("seleccionado" + id_persona+ ' - '+tipo_comida);
            /*$.ajax({
              type: "POST",
              url: "alimentos/php/back/alimento/update_singular_comida.php",
              data: {id_persona, tipo_comida,valor:1},
              beforeSend:function(){

              },
              success:function(data){
                //alert(data);
                console.log(data);
              }

            }).done( function() {

            }).fail( function( jqXHR, textSttus, errorThrown){


            });
          }else{
            console.log("des-seleccionado" + id_persona+ ' - '+tipo_comida);
            /*$.ajax({
              type: "POST",
              url: "alimentos/php/back/alimento/update_singular_comida.php",
              data: {id_persona, tipo_comida,valor:0},
              beforeSend:function(){

              },
              success:function(data){
                //alert(data);
                console.log(data);
              }

            }).done( function() {

            }).fail( function( jqXHR, textSttus, errorThrown){


            });
          }
        });
      } );*/




      /*setInterval( function () {
        tabla_emps_listado.ajax.reload(null, false);
      }, 100000 );*/



    };




    var initDataTableEmpleados_funcional_listado = function() {
        $('#tb_empleados_listado_funcionales thead tr').clone(true).appendTo( '#tb_empleados_listado_funcionales thead' );
        $('#tb_empleados_listado_funcionales thead tr:eq(1) th').each( function (i) {
            $(this).html( '<input type="text" class="form-control form-corto_2" placeholder="" />' );
            $( 'input', this ).on( 'keyup change', function () {
                if ( tabla_emps_listado.column(i).search() !== this.value ) {
                    tabla_emps_listado
                    .column(i)
                    .search( this.value )
                    .draw();
                }
            });
        });
        tabla_emps_listado = $('#tb_empleados_listado_funcionales').DataTable({
            ordering: true,
            pageLength: 25,
            //"responsive": true,
		/*"columnDefs": [
		            { responsivePriority: 1, targets: 0 },
		            { responsivePriority: 2, targets: 4 },
                { responsivePriority: 1, targets: 3 },
		        ],*/
            language: {
                emptyTable: "No hay pantallas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax:{
                url :"alimentos/php/back/listados/get_empleados_por_direccion_funcional.php",
                type: "POST",
                error: function(){
                    $("#post_list_processing").css("display","none");
                }
            },
            aoColumns: [
                { "class" : "text-center", mData: 'id_persona' },
                { "class" : "text-left", mData: 'empleado' },
                { "class" : "text-center", mData: 'dir_nominal' },
                { "class" : "text-center", mData: 'dir_funcional' },
                { "class" : "text-center", mData: 'renglon' },
                { "class" : "text-center", mData: 'direccion' },
                { "class" : "text-center", mData: 'fecha_i' },
                { "class" : "text-center", mData: 'puesto_f' },
                { "class" : "text-center", mData: 'puesto_n' },
                { "class" : "text-right", mData: 'sueldo' },
                { "class" : "text-center", mData: 'accion' }
            ],
            buttons: [
                'excelHtml5'
            ],
            order: [[ 3, 'desc' ]],
            columnDefs: [
                {'width': 1, 'targets': [2]},
                {'max-width': 1, 'targets': [2]},
                {'visible': true, "targets": 3 }
            ],
            drawCallback: function ( settings ) {
              get_fotografia('tb_empleados_listado_funcionales',0);
                let api = this.api();
                let rows = api.rows( {page:'current'} ).nodes();
                let last = null;
                let arrayDirecciones = [];
                let posicion = 0;
                api.rows({ search: 'applied' }).data().each(function(element){
                    if(element.dir_funcional != null){
                        posicion = findWithAttr(arrayDirecciones, "direccion", element.dir_funcional);
                        if(posicion >= 0){
                            arrayDirecciones[posicion].total = (parseFloat(arrayDirecciones[posicion].total) + parseFloat(element.sueldo));
                        }else{
                            arrayDirecciones.push({
                                "direccion": element.dir_funcional,
                                "total": parseFloat(element.sueldo)
                            });
                        }
                    }
                });
                api.column(3, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr style="background: #3BAFDA;"><td colspan="11" style="color:#F5F7FA;font-size:18px;">'+group+' (Total: Q. '+ arrayDirecciones[findWithAttr(arrayDirecciones, "direccion", group)].total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+')</td></tr>'
                        );
                        last = group;
                    }
                } );
            }
      });

    };



    var initDataTablePlazas_listado = function() {
        let groupColumn = 4;
        $('#tb_plazas_listado thead tr').clone(true).appendTo( '#tb_plazas_listado thead' );
        $('#tb_plazas_listado thead tr:eq(1) th').each( function (i) {
            $(this).html( '<input type="text" class="form-control form-corto_2" placeholder="" />' );
            $( 'input', this ).on( 'keyup change', function () {
                if ( tabla_plazas_listado.column(i).search() !== this.value ) {
                    tabla_plazas_listado
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } );

        tabla_plazas_listado = $('#tb_plazas_listado').DataTable({
            ordering: true,
            pageLength: 25,
            language: {
                emptyTable: "No hay partidas asignadas",
                loadingRecords: " <div class='spinner-grow text-info'></div> "
            },
            ajax:{
                url :"alimentos/php/back/listados/get_plazas.php",
                type: "POST",
                error: function(){
                    $("#post_list_processing").css("display","none");
                }
            },
            aoColumns: [
              { "class" : "text-center", mData: 'gafete' },
                { "class" : "text-left", mData: 'partida' },
                { "class" : "text-center", mData: 'cod_plaza' },
                { "class" : "text-left", mData: 'puesto' },
                { "class" : "text-left", mData: 'direccion' },
                { "class" : "text-center", mData: 'estado' },
                { "class" : "text-center", mData: 'renglon' },
                { "class" : "text-center", mData: 'empleado' },
                { "class" : "text-center", mData: 'fecha_efectiva' },
                { "class" : "text-right", mData: 'sueldo' },
                { "class" : "text-center", mData: 'accion' }

            ],
            buttons: [
                'excel'
            ],
            order: [[ groupColumn, 'desc' ]],
            columnDefs: [
                {'width': 1, 'targets': [2]},
                {'max-width': 1, 'targets': [2]},
                {'visible': true, "targets": groupColumn }
            ],
            drawCallback: function ( settings ) {
              get_fotografia('tb_plazas_listado',0);
                let api = this.api();
                let rows = api.rows( {page:'current'} ).nodes();
                let last = null;
                let arrayDirecciones = [];
                let posicion = 0;
                api.rows({ search: 'applied' }).data().each(function(element){
                    if(element.direccion != null){
                        posicion = findWithAttr(arrayDirecciones, "direccion", element.direccion);
                        if(posicion >= 0){
                            arrayDirecciones[posicion].total = (parseFloat(arrayDirecciones[posicion].total) + parseFloat(element.sueldo));
                        }else{
                            arrayDirecciones.push({
                                "direccion": element.direccion,
                                "total": element.sueldo
                            });
                        }
                    }
                });
                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr style="background: #76a4fb;"><td colspan="11" style="color:black;font-size:18px;">'+group+' (Total: Q. '+ arrayDirecciones[findWithAttr(arrayDirecciones, "direccion", group)].total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+')</td></tr>'
                        );
                        last = group;
                    }
                } );
            }
        });
    };

    function get_fotografia(tabla, columna){
      $('#'+tabla+' tr').each(function(index, element){
         var id_persona = $(element).find("td").eq(columna).html();
         //console.log(id_persona);
         $.ajax({
           type: "POST",
           url: "alimentos/php/back/empleados/get_fotografia.php",
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
            initDataTableEmpleados_listado();
            initDataTablePlazas_listado();
            initDataTableEmpleados_funcional_listado();
            initDataTableEmpleados_listado_exc();
                    }
    };
}();


// Initialize when page loads
jQuery(function(){ EmpleadosTableDatatables_listado.init(); });
