$(document).ready(function(){
  var instancia;

  var viewModelViatico = new Vue({
    el: '#appViatico',
    tableViaticos:"",
    bsDataTables:"",
    api:"",
    data: {
    },
    computed:{

    },
    created: function(){

    },
    methods: {
      getViaticos: function(){
        this.tableViaticos = $('#tb_viaticos').DataTable({
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

                { "class" : "text-center", mData: 'fecha_ini' },
                { "class" : "text-center", mData: 'fecha_fin' },

                { "class" : "text-center", mData: 'estado' },

                { "class" : "text-center", mData: 'accion' }
            ],
            buttons: [

              {
                text: 'Pendientes <i class="fa fa-sync"></i>',
                className: 'btn btn-personalizado btn-sm btn-v1 btn-via active',
                action: function ( e, dt, node, config ) {
                  viewModelViatico.recargarViaticos(2,'btn-v1');
                  console.log(this.buttons);
                }
              },
              {
                text: '1 año <i class="fa fa-sync"></i>',
                className: 'btn btn-personalizado btn-sm btn-v2 btn-via',
                action: function ( e, dt, node, config ) {
                  viewModelViatico.recargarViaticos(3,'btn-v2');
                }
              },
              {
                text: 'Todos <i class="fa fa-sync"></i>',
                className: 'btn btn-personalizado btn-sm btn-v3 btn-via',
                action: function ( e, dt, node, config ) {
                  viewModelViatico.recargarViaticos(1,'btn-v3');
                }
              },
              {
                text: 'Liquidados <i class="fa fa-sync"></i>',
                className: 'btn btn-personalizado btn-sm btn-v4 btn-via',
                action: function ( e, dt, node, config ) {
                  viewModelViatico.recargarViaticos(4,'btn-v4');
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
                  var menu='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="cargar_menu_impresion('+row.DT_RowId+',1)"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu'+row.DT_RowId+'"></div></div></div></div>';
                  return  '<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'&tipo_filtro='+$('#id_tipo_filtro').val()+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'
              }
            }
          ]
        });

      },
      baseTables: function(){
        // DataTables Bootstrap integration

          this.bsDataTables = jQuery.fn.dataTable;

          // Set the defaults for DataTables init
          jQuery.extend( true, this.bsDataTables.defaults, {
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
          jQuery.extend(this.bsDataTables.ext.classes, {
              //sWrapper: " dt-bootstrap",
              sFilterInput: "form-control form-control-sm",
              sLengthSelect: "form-control form-control-sm"
          });

            // TableTools Bootstrap compatibility - Required TableTools 2.1+
            if (this.bsDataTables.TableTools) {
                // Set the classes that TableTools uses to something suitable for Bootstrap
                jQuery.extend(true, this.bsDataTables.TableTools.classes, {
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
                jQuery.extend(true, this.bsDataTables.TableTools.DEFAULTS.oTags, {
                    "collection": {
                        "container": "ul",
                        "button": "li",
                        "liner": "a"
                    }
                });
            }




      },
      recargarViaticos: function(tipo,classe){
        $('#id_tipo_filtro').val(tipo);
        this.tableViaticos.ajax.reload(null,false);
        $('.btn-via').removeClass('active');
        $('.'+ classe).addClass('active');
      }
    }
  })
  instancia = viewModelViatico;

  instancia.baseTables();
  instancia.getViaticos();

  $('#modal-remoto-lgg2').on('click', '.salida', function () {

    valord = $('#id_cambiodv').val();
    if(valord == 1){
      tipo = $('#id_tipo_filtro').val()
      instancia.recargarViaticos(tipo);
    }
    $('#modal-remoto-lgg2').modal('hide');
  });

  $('#modal-remoto-lg').on('hidden.bs.modal', function (e) {
  // do something...
  valor = $('#id_cambiov').val();
  if(valor == 1){
    tipo = $('#id_tipo_filtro').val();
    instancia.recargarViaticos(tipo,'btn-v1');
  }

})
})
