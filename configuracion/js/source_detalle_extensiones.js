
var tabla_detalle;
var dataTableDetalle = function() {
    var initDatatableDetalle = function(id_persona) {

        // var options_tipo = [
        //     {value: 92, text: 'CELULAR'},
        //     {value: 95, text: 'TRABAJO'},
        //     {value: 98, text: 'NO DEFINIDO'},
        //     {value: 93, text: 'EMERGENCIAS'},
        //     {value: 96, text: 'OTRA RESIDENCIA'},
        //     {value: 94, text: 'FAX'},
        //     {value: 91, text: 'RESIDENCIAL'},
        //     {value: 97, text: 'OTRO CELULAR'},
        //     {value: 5441, text: 'SERVICIO'}
        // ];

        // var options_ref = [
        //     {value: 836, text: 'TRABAJO'},
        //     {value: 834, text: 'FAMILIAR'},
        //     {value: 1024, text: 'EMPLEADO'},
        //     {value: 835, text: 'PERSONAL'}
        // ];
         

        tabla_detalle = $('#tb_detalle_telefono').DataTable({
            ordering: false,
            pageLength: 10,
            bProcessing: true,
            language: {
                emptyTable: "No hay contactos disponibles",
                loadingRecords: "<div class='spinner-grow text-primary'></div>"
            },
            ajax:{
                url :"configuracion/php/back/herramientas/get_extension_persona.php",
                type: "POST",
                data:{ id_persona: $("#id_persona").val(), extension: $("#extension").val() },
                error: function(){
                    $("#post_list_processing").css("display","none");
                }
            },
            "aoColumns": [
                { "class" : "text-center", mData: 'id' },
                { "class" : "text-center", mData: 'extension' },
                { "class" : "text-center", mData: 'ubicacion' },
                { "class" : "text-center", mData: 'puesto' },
                { "class" : "text-center", mData: 'nombre' },
                { "class" : "text-center", mData: 'correo' },
                { "class" : "text-center", mData: 'id_persona' }
            ],

    
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){

                
                    $('td:eq(1)', nRow).html('<a class="" href="#" ">'+aData.extension+'</a>').editable({
                        pk: {id_persona: aData.id_persona, id: aData.id},
                        name:'extension',
                        url: 'configuracion/php/back/herramientas/update_extension.php',
                        send:'always',
                        success:function(data){
                            Swal.fire({
                                type: 'success',
                                title: 'Extensión Actualizada.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            reload_directorio();
                            reload_detalle();
                        },
                        validate: function(value){
                            if($.trim(value) == ''){
                            Swal.fire({
                                type: 'error',
                                title: 'El valor no puede ser vacío',
                                showConfirmButton: false,
                                timer: 1100
                            });
                            }
                        }
                        
                    });

                    // $('td:eq(2)', nRow).html('<a class="" href="#" ">'+aData.ubicacion+'</a>').editable({
                    //     // pk:aData.id_telefono,
                    //     name:'ubicacion',
                    //     type: 'select',
                    //     url: '',
                    //     send:'always',
                    //     // source: options_tipo,
                    // });
                    // $('td:eq(3)', nRow).html('<a class="" href="#" ">'+aData.puesto+'</a>').editable({
                    //     // pk:aData.id_telefono,
                    //     name:'puesto',
                    //     type: 'select',
                    //     url: '',
                    //     send:'always',
                    //     // source: options_ref,
                    // });

                    $('td:eq(5)', nRow).html('<a class="" href="#" ">'+aData.correo+'</a>').editable({
                        pk:{id_persona: aData.id_persona, id: aData.id},
                        name:'correo',
                        url: 'configuracion/php/back/herramientas/update_extension.php',
                        send:'always',
                        success:function(data){
                            Swal.fire({
                                type: 'success',
                                title: 'Extensión Actualizada.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            reload_directorio();
                            reload_detalle();
                            
                        },

                    });

                    // $('td:eq(6)', nRow).html('<a class="" href="#" ">'+aData.id_persona+'</a>').editable({
                    //     // pk:aData.id_telefono,
                    //     name:'id_persona',
                    //     url: '',
                    //     send:'always',
                    // });
                    
                    
                    return nRow;
            }

            

        });

        // var l1=0;
        // $('#add').click(function(){
        //     l1+=1;
        //     var html = '<tr id="r'+l1+'">';
        //     html += '<td contenteditable="true" class="text-center editable eitable-click" id="datax'+l1+'"><a class href="#"></a></td>';
        //     html += '<td contenteditable="false" class="text-center id="data2"><select class="form-control" id="listx'+l1+'"></select></td>';
        //     html += '<td contenteditable="false" class="text-center id="data3"><select class="form-control" id="listy'+l1+'"></select></td>';
        //     html += '<td contenteditable="true" class="text-center editable eitable-click" id="datay'+l1+'"><a class href="#"></a></td>';
        //     html += '<td  style="text-align:center"><button class="btn btn-sm btn-personalizado outline" name"insert", id="insert"><i class="fa fa-plus-circle"></i></button><button class="btn btn-sm btn-personalizado outline" name"cancel", id="cancel"><i class="fa fa-trash"></i></button></td>';
        //     html += '</tr>';
        //     $('#tb_detalle_telefono').append(html);
        //     $.each(options_tipo, function(i, p) { 
        //         $('#listx'+l1).append($('<option></option>') 
        //                     .val(p.value).html(p.text));
        //     });
        //     $.each(options_ref, function(i, p) { 
        //         $('#listy'+l1).append($('<option></option>') 
        //                     .val(p.value).html(p.text)); 
        //     });
        //    });

        //    $(document).on('click', '#cancel', function(){
        //     var trid = $(this).closest('tr').attr('id');
        //     var row = document.getElementById(trid);
        //     row.parentNode.removeChild(row);
        //    });
        //    $(document).on('click', '#insert', function(){
        //     var id_persona = $("#id_persona").val();
        //     var trid = $(this).closest('tr').attr('id');
        //     var l2 = trid.replace("r","");
        //     var nnum = $('#datax'+l2).text();
        //     var ntipo = $('#listx'+l2).val();
        //     var nref = $('#listy'+l2).val();
        //     var nobs = $('#datay'+l2).text();
        //     if(nnum != ''){
        //         Swal.fire({
        //             title: '<strong></strong>',
        //             text: "¿Desea agregar el número?",
        //             type: 'question',
        //             showCancelButton: true,
        //             confirmButtonColor: '#28a745',
        //             confirmButtonText: '¡Si, Agregar!',
        //             cancelButtonText: 'Cancelar',
            
        //         }).then((result) => {
        //             if (result.value) {
        //                 $.ajax({
        //                     url:'directorio/php/back/telefonos/insert_rows.php',
        //                     method:"POST",
        //                     data:{nro:nnum, tipo:ntipo, ref:nref, obs:nobs, id_persona:id_persona},
        //                     success:function(data){
        //                       Swal.fire({
        //                           type: 'success',
        //                           title: 'Número agregado.',
        //                           showConfirmButton: false,
        //                           timer: 1500
        //                       });
        //                       reload_detalle();
              
        //                     }
        //                    });
        //             }
        //         });
             
        //     }else{
        //         Swal.fire({
        //             type: 'error',
        //             title: 'Ingrese un número para agregar.',
        //             showConfirmButton: false,
        //             timer: 1500
        //         });
        //     }
        //    });
           
         

           
    };


    // DataTables Bootstrap integration
    var bsDataTables = function() {
      var $DataTable = jQuery.fn.dataTable;

      // Set the defaults for DataTables init
      jQuery.extend( true, $DataTable.defaults, {
          dom:
          //"<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
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
              sProcessing:     "<i class='fa fa-sync fa-spin'></i> Procesando...",
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
        init: function(id_dependencia) {
            bsDataTables();
            initDatatableDetalle(id_dependencia);
        }
    };
}();

function reload_detalle(){
  tabla_detalle.ajax.reload(null, false);
}

// Initialize when page loads
jQuery(function(){ dataTableDetalle.init(); });


