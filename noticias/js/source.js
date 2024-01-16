



let tabla_noticias;
let noticiasDatatable = function() {
    let initDatatableNoticias = function() {
        tabla_noticias = $('#tb_noticias').DataTable({
            ordering: false,
            pageLength: 10,
            bProcessing: true,
            scrollX: true,
            fixedHeader: true,
            language: {
                emptyTable: "No hay noticias disponibles",
                loadingRecords: "<div class='spinner-grow text-primary'></div>"
            },
            ajax:{
                url :"noticias/php/back/listados/get_noticias.php",
                type: "POST",
                data:{
                  ini:function() { return $('#ini').val() },
                  fin:function() { return $('#fin').val() },
                  red:function() { return $('#redes_').val() },
                  categoria:function() { return $('#categoria_').val() },
                  usuario:function() { return $('#usuario').val() }
                },
                error: function(){
                    $("#post_list_processing").css("display","none");
                }
            },
            "aoColumns": [
                { "class" : "text-center", mData: 'estado' },
                { "class" : "text-center", mData: 'alias' },
                { "class" : "text-center", mData: 'fecha' },
                { "class" : "text-center", mData: 'usuario' },
                { "class" : "text-center", mData: 'fuente' },
                { "class" : "text-center", mData: 'categoria' },
                { "class" : "text-center", mData: 'propietario' },
                { "class" : "text-center", mData: 'aprobado' },
                { "class" : "text-center", mData: 'ubicacion' },
                { "class" : "text-justify", mData: 'observaciones' },
                { "class" : "text-center", mData: 'accion' }
            ],
            buttons: [
              {
                  extend: 'pdfHtml5',
                  text: '<i class="fas fa-file-pdf"></i> Exportar',
                  className: 'btn btn-soft-info',
                  download: ['donwload', 'open'],
                  orientation: 'landscape',
                  filename: 'REPORTE DE NOTICIAS ' + ($('#ini').val()) ,
                  title: 'REPORTE DE NOTICIAS ' + ($('#fin').val()) ,
                  exportOptions: {
                      columns: [ 1, 2, 3, 4, 5, 9],
                  },
                  customize: function (doc) {
                      doc.styles.tableBodyEven.alignment = 'center';
                      doc.styles.tableBodyOdd.alignment = 'center';
                      doc.content[1].table.widths = ['10%', '10%', '10%', '15%', '10%',  '45%'];
                      var iColumns = tabla_noticias.length;
                      var rowCount = tabla_noticias.data().count()+1;
                      for (i = 0; i < rowCount; i++) {

                            doc.content[1].table.body[i][5].alignment = 'justifify';

                    };
                      doc.content.splice(0, 1);
                      doc.pageMargins = [20, 100, 20, 20];
                      doc['header'] = (function () {
                          return {
                              columns: [
                                  {
                                    alignment: 'left',
                                      image: baner,
                                      width: 400,
                                      margin: [-80, -5, 0, 0]
                                  },
                                  {
                                      alignment: 'center',
                                      text: 'REPORTE DE NOTICIAS \n DIRECCIÓN DE INFORMACIÓN \n DEPARTAMENTO DE ESTUDIO DE FUENTES',//'EMPLEADO: ' + $('#gaf').val() + " " + $('#naem').val() + '\n DIRECCION: ' + $('#dirf').val() + "\n PUESTO: " + $('#puesf').val(),
                                      fontSize: 8,
                                      margin: [-110, 5, 0, 0]
                                  },
                                  {
                                      alignment: 'left',
                                      bold: true,
                                      text:'HISTORIAL DE NOTICIAS', //'REPORTE DE NOTICIAS - DIRECCIÓN DE INFORMACIÓN - DEPARTAMENTO DE ESTUDIO DE FUENTES',
                                      fontSize: 13,
                                      margin: [-180, 40]
                                  },
                                  {
                                      alignment: 'left',
                                      bold: true,
                                      text: 'DEL: ' + $('#ini').val().split("-").reverse().join("-") + ' AL: ' + $('#fin').val().split("-").reverse().join("-"),
                                      fontSize: 10,
                                      margin: [-280, 55]
                                  },

                                  {
                                    alignment : 'right',
                                      image: logo_saas,
                                      width: 100,

                                  },

                              ],
                              margin: 20
                          }
                      });

                  }
                }
            ],
            'columnDefs': [
                {
                    "targets": 2,
                    "width": "7%"
                },
                {
                'targets': [0],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                    render: function(data, type, row, meta) {
                        if (data === "1") {
                            return '<span class="badge badge-warning" data-toggle="tooltip" data-placement="top" data-original-title="Feriados y Asuetos">Pendiente</span>';
                        } else if(data === "2"){
                            return '<span class="badge badge-danger">Rechazado</span>';
                        } else if(data === "3"){
                            return '<span class="badge badge-success">Aprobado</span>';
                        }else{
                            return '<span class="badge badge-info">Inactivo</span>';
                        }
                    }
                },{
                    'targets': [5],
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    render: function(data, type, row, meta) {
                        if (data === "2") {
                            return '<span class="badge badge-danger">Negativa</span>';
                        } else {
                            return '<span class="badge badge-success">Positiva</span>';
                        }
                    }
                }
            ]
        });

/*         $('#car_t').on('click', function(){
            table_c.ajax.reload();
        }); */
    };

    // DataTables Bootstrap integration
    let bsDataTables = function() {
      let dataTable = jQuery.fn.dataTable;

      // Set the defaults for DataTables init
        jQuery.extend( true, dataTable.defaults, {
            dom:
                "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            renderer: 'bootstrap',
            oLanguage: {
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
                    sSortAscending:  ": Actilet para ordenar la columna de manera ascendente",
                    sSortDescending: ": Actilet para ordenar la columna de manera descendente"
                }
            }
      });

        // Default class modification
        jQuery.extend(dataTable.ext.classes, {
            sFilterInput: "form-control",
            sLengthSelect: "form-control"
        });

        // Bootstrap paging button renderer
        dataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
            let api = new dataTable.Api(settings);
            let classes = settings.oClasses;
            let lang = settings.oLanguage.oPaginate;
            let btnDisplay, btnClass;

            let attach = function (container, buttons) {
                let i, ien, node, button;
                let clickHandler = function (e) {
                    e.preventDefault();
                    if (!jQuery(e.currentTarget).hasClass('disabled')) {
                        api.page(e.data.action).draw(false);
                    }
                };

                for (i = 0, ien = buttons.length; i < ien; i++) {
                    button = buttons[i];
                    if (jQuery.isArray(button)) {
                        attach(container, button);
                    }else {
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
        if (dataTable.TableTools) {
            // Set the classes that TableTools uses to something suitable for Bootstrap
            jQuery.extend(true, dataTable.TableTools.classes, {
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
            jQuery.extend(true, dataTable.TableTools.DEFAULTS.oTags, {
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
            bsDataTables();
            initDatatableNoticias();
        }
    };
}();

function reload_noticias(){
  tabla_noticias.ajax.reload(null, false);
}

// Initialize when page loads
jQuery(function(){ noticiasDatatable.init(); });
