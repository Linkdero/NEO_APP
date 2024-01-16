
var table_empleados_asignar, table_empleados_por_viaticos;
var EmpleadosTableDatatables_listado_modal = function() {
  // inicio
  var initDataTableEmpleados_asignar = function() {
    table_empleados_asignar= $('#tb_empleados_asignar').DataTable({
      /*'initComplete': function(settings){
        var api = this.api();
        api.cells(
          api.rows(function(idx, data, node){
            //alert(idx);
            return (data.bln_confirma == 0) ? true : false;
            //alert(data.bln_confirma)
          }).indexes(),14).checkboxes.disable();
      },*/
      //
      dom:"<'row'<'col-sm-12'f>>"+
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-12'p>>",
      "ordering": false,
      "pageLength": 10,
      "bLengthChange":true,
      "bProcessing": true,
      select:true,
      //"paging": false,
      "ordering": false,
      //"info":     true,
      orderCellsTop: true,


      //"dom": '<"">frtip',


      language: {
          emptyTable: "No hay solicitudes",
          sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
      },
      "ajax":{
            url :"viaticos/php/back/listados/get_empleados_asignar.php",
            type: "POST",
            data:{
              vt_nombramiento:function() { return $('#id_viatico').val() }
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
        },

        "aoColumns": [
          { "class" : "text-center", mData: 'id_persona'},
          { "class" : "text-center", mData: 'empleado'},
          { "class" : "text-center", mData: 'status'},
          { "class" : "text-center", mData: 'id_persona'}
           ],
          buttons: [

          ],

          'columnDefs':[
            {
              'targets':3,
              'checkboxes':{
                'selectRow':true
              },
            }
          ],
          'select':{
            'style':'multi'
          },
          "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
            if(aData.status==0){
              table_empleados_por_viaticos.columns(3).visible(false);
            }
            var rowId = aData[3];


          }

    });
    $('#tb_empleados_asignar').on('change', 'input[type="checkbox"]', function() {

      $.each(table_empleados_asignar.$('input[type=checkbox]:checked'), function(){
        var data = table_empleados_asignar.row($(this).parents('tr')).data();
        //parametro+=''+data['DT_RowId']+',';
        value=data['DT_RowId'];
        //renglon=data['reng_num'];
        //alert(value);
        removeA(valArray, value);
        valArray.push(value);

      });
      $.each(table_empleados_asignar.$('input[type=checkbox]:not(:checked)'), function(){
        var data = table_empleados_asignar.row($(this).parents('tr')).data();
        //parametro+=''+data['DT_RowId']+',';
        if(valArray.length == 0){
          //alert('vacio')
          $('#empleados_asignados_datos').html(valArray+ ' || '+data)
        }
        value=data['DT_RowId'];
        removeA(valArray, value);
      });

      if(valArray.length == 0){
        $('#empleados_asignados').hide();

      }else if(valArray.length>0){
          $('#empleados_asignados').show();
          //get_empleado_
          $.ajax({
            type: "POST",
            url: "viaticos/php/back/listados/get_empleado_asignado.php",
            //dataType: 'html',
            data: { id_persona: valArray.toString()},
            beforeSend:function(){
              $("#empleados_asignados_datos").removeClass('slide_up_anim');
              $("#empleados_asignados_datos").html('<div class="loaderr"></div>');
            },
            success:function(data) {
                //$("#aldea").html(data);
                console.log(data);
                $('#empleados_asignados_datos').html(data)
            }
          });

      }
      });

  };
//fin

    var initDataTableEmpleados_por_viaticos = function() {
      table_empleados_por_viaticos= $('#tb_empleados_por_nombramiento').DataTable({
        'initComplete': function(settings){
          var api = this.api();
          api.cells(
            api.rows(function(idx, data, node){
              //alert(idx);
              return (data.bln_confirma == 0) ? true : false;
              //alert(data.bln_confirma)
            }).indexes(),14).checkboxes.disable();
        },
        //dom:
        //"<'row'<'col-sm-12'f>>",
        "ordering": false,
        "pageLength": 10,
        "bLengthChange":true,
        "bProcessing": true,
        select:true,
        //"paging": false,
        "ordering": false,
        "info":     true,
        orderCellsTop: true,


        //"dom": '<"">frtip',


        language: {
            emptyTable: "No hay solicitudes",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
        },
        "ajax":{
              url :"viaticos/php/back/listados/get_empleados_por_viatico.php",
              type: "POST",
              data:{
                vt_nombramiento:function() { return $('#id_viatico').val() }
              },
              error: function(){
                $("#post_list_processing").css("display","none");
              }
          },

             "aoColumns": [
               //{ "class" : "text-center", mData: 'id_persona', "width":"2%"},
               { "class" : "text-center details-control sin_contorno", mData: 'codigo'},
               { "class" : "text-center ", mData: 'id_persona'},
               { "class" : "text-left", mData: 'empleado'},
               { "class" : "text-center", mData: 'va'},
               { "class" : "text-center", mData: 'vc'},
               { "class" : "text-center", mData: 'vl'},
               { "class" : "text-right", mData: 'p_p'},
               { "class" : "text-right", mData: 'p_r'},
               { "class" : "text-right", mData: 'm_p'},
               { "class" : "text-right", mData: 'm_r'},
               { "class" : "text-right", mData: 'reintegro'},
               { "class" : "text-right", mData: 'complemento'},
               { "class" : "text-center", mData: 'estado'},
               //{ "class" : "text-center", mData: 'cheque'},
               { "class" : "text-center", mData: 'accion'},
               { "class" : "text-center ", mData: 'id_persona'}
             ],
            buttons: [

            ],

            'columnDefs':[
              {
                'targets':14,
                'checkboxes':{
                  'selectRow':true
                },
              }
            ],
            'select':{
              'style':'multi'
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
              if(aData.dato==0){
                table_empleados_por_viaticos.columns(14).visible(false);
              }
              var rowId = aData[14];


            }

      });
      $('#tb_empleados_por_nombramiento tbody').on('click', 'td.details-control', function () {
        var tr = $(this).parents('tr');
        var currentId = $(this).attr('id');
         var row = table_empleados_por_viaticos.row( tr );

         if ( row.child.isShown() ) {
             // This row is already open - close it
             row.child.hide();
             tr.removeClass('shown');
         }
         else {
             // Open this row
             row.child( format(row.data()) ).show();
             tr.addClass('shown');
         }
       });
    };



     function format(d) {
       console.log();(d.id_persona + ' - '+ d.reng_num)
       //alert(d.serie);

       var div = $('<div/>');
       var url='viaticos/php/front/viaticos/viatico_detalle_row.php'
       $.ajax({
         type: "POST",
         url:url,
         data:{
           id_viatico:$('#id_viatico').val(),
           id_persona:d.id_persona,
           id_renglon:d.reng_num
         },
         beforeSend:function(){
           //$('#response').html('<span class="text-info">Loading response...</span>');
           $(div).fadeOut(0).html('<div class="spinner-grow text-info"></div>').fadeIn(0);
         },
         success: function(datos){
           $(div).fadeOut(0).html(datos).fadeIn(0);
         }
       });
       return div;
     }

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





    // DataTables Bootstrap integration
    var bsDataTables = function() {
      var $DataTable = jQuery.fn.dataTable;

      // Set the defaults for DataTables init
      jQuery.extend( true, $DataTable.defaults, {
          dom:
          "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
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

            initDataTableEmpleados_asignar();
            initDataTableEmpleados_por_viaticos();
          }
    };
}();

function recargar_tabla_empleados(){
  table_empleados_por_viaticos.ajax.reload(null,false);
}


// Initialize when page loads
jQuery(function(){ EmpleadosTableDatatables_listado_modal.init(); });
