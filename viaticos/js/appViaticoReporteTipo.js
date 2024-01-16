import { EventBus } from './eventBus.js';
//import { viaticoheaders } from './components/GlobalComponents.js';
//import { viewModelViaticoTipoDetalle } from './appViaticos.js';

$(document).ready(function(){
  var instancia;
  const viewModelViaticoTipo = new Vue({
    el: '#appViaticoTipo',
    tableViaticosReporteTipo:"",
    bsDataTables:"",
    api:"",
    data: {
      iniVA:[{'val':'41319', 'text':'41319'},{'val':'45000', 'text':'45000'},{'val':'50000', 'text':'50000'}],
      iniVC:[{'val':'40702', 'text':'40702'},{'val':'45000', 'text':'45000'},{'val':'50000', 'text':'50000'}],
      iniVL:[{'val':'40671', 'text':'40671'},{'val':'45000', 'text':'45000'},{'val':'50000', 'text':'50000'}],
      tipoFiltro:1,
      minVA:41319,
      minVC:40702,
      minVL:40671,
      minValue:0,
      iniValue:0,
      finValue:0
    },
    components:{
      //viaticoheaders
    },
    computed:{

    },
    created: function(){
      this.$nextTick(() => {
        this.baseTables();
        this.getViaticosReporteTipo();
        this.minValue = this.minVA;
        this.iniValue = this.minValue;
        this.finValue = 50000;
      });
      EventBus.$on('recargarViaticosTable', () => {
        this.recargarViaticos(2,'btn-v1')
      });
    },
    methods: {
      cambiarFiltros: function(event){
        this.tipoFiltro=event.currentTarget.value;

        if(this.tipoFiltro == 1){
          this.minValue= this.minVA;
        }else if(this.tipoFiltro == 2){
          this.minValue= this.minVC;
        }else if(this.tipoFiltro == 3){
          this.minValue= this.minVL;
        }

        this.iniValue = this.minValue;
        //this.finValue = this.minValue + 120;

      },
      getViaticosReporteTipo: function(){
        var thisInstance = this;
        this.tableViaticosReporteTipo = $('#tb_formularios_utilizados_tipo').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bLengthChange": true,
          "bProcessing": true,
          scrollY: '50vh',
          scrollX:        true,
            scrollCollapse: true,
          fixedColumns:   {
                left: 1
            },
          //"paging": false,

          //"info":     true,
          orderCellsTop: true,
          language: {
            emptyTable: "No hay solicitudes",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          "ajax": {
            url: "viaticos/php/back/listados/get_formularios_utilizados_tipo.php",
            type: "POST",
            data: {
              ini: function () { return $('#ini').val() },
              fin: function () { return $('#fin').val() },
              tipo: function () { return $('#tipo').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },

          "aoColumns": [
            { "class": "text-center", mData: 'correlativo' },
            { "class": "text-center", mData: 'estado_frm' },
            { "class": "text-center", mData: 'verificador' },
            { "class": "text-center", mData: 'fecha' },
            { "class": "text-center", mData: 'vt_nombramiento' },
            { "class": "text-center", mData: 'estado' },
            { "class": "text-center", mData: 'empleado' },
            { "class": "text-center", mData: 'direccion' },
            { "class": "text-center", mData: 'id_pais' },



          ],
          buttons: [
            {
              extend: 'excel',
              text: '<i class="fa fa-file-excel"></i> Exportar',
              className: 'btn btn-personalizado btn-sm',
              title: 'Reporte de Viaticos - ' + $('#id_mes option:selected').text(),

            },

            {
              text: 'Recargar <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                //recargar_nombramientos(2);
                thisInstance.recargarFormsUtilizadosTipo();
              }
            },
            {
              text: 'Imprimir <i class="fa fa-print"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                //recargar_nombramientos(2);
                imprimir_liquidacion_global();
              }
            }
          ]/*,
                'columnDefs': [{
             'targets': 3,
             'searchable':false,
             'orderable':false,
             'className': 'dt-body-center',
             'render': function (data, type, full, meta){
               return '<label class="css-input input-sm switch switch-sm switch-success" style="padding-bottom:0px"><input name="id[]" type="checkbox" value="'
                  + $('<div/>').text(data).html() + '"/><span></span></label>'

             }
          }]*/,

          //'order': [0, 'asc']
        });
        $('#modal-remoto-lgg2').on('click', '.salida', function () {

          valord = $('#id_cambiodv').val();
          if(valord == 1){
            tipo = $('#id_tipo_filtro').val()
            instancia.recargarViaticos(tipo);
          }
          $('#modal-remoto-lgg2').modal('hide');
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
      destruirTablaEmpleados: function(){
        EventBus.$emit('destruirTabla');
        EventBus.$emit('destroyInstance');
      },

      recargarFormsUtilizadosTipo: function(tipo,classe){
        if(this.iniValue > this.finValue){
          Swal.fire({
            type: 'error',
            title: 'El formulario inicial no debe ser mayor al final.',
            showConfirmButton: false,
            timer: 1100
          });
        }else{
          if(this.minValue > this.iniValue){
            Swal.fire({
              type: 'error',
              title: 'El formulario inicial no debe ser menor al ' + this.minValue + '.',
              /*showConfirmButton: false,
              timer: 1100*/
            });
          }else{
            if(this.finValue < this.minValue){
              Swal.fire({
                type: 'error',
                title: 'El formulario inicial no debe ser mayor al final .',
                /*showConfirmButton: false,
                timer: 1100*/
              });
            }else{
              $('#id_tipo_filtro').val(tipo);
              this.tableViaticosReporteTipo.ajax.reload(null,false);
              $('.btn-via').removeClass('active');
              $('.'+ classe).addClass('active');
            }
          }
        }
      },
      showModal: function(id){
        var imgModal = $('#modal-remoto-lgg2');
        var imgModalBody = imgModal.find('.modal-content');
        //let id_persona = parseInt($('#bar_code').val());
        $.ajax({
          type: "GET",
          url: "viaticos/php/front/viaticos/viatico_detail.php",
          dataType: 'html',
          data:{
            id_viatico:id
          },
          beforeSend: function () {
            imgModal.modal('show');
            imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
          },
          success: function (data) {
            imgModalBody.html(data);
          }
        });

      }
    }
  })
  instancia = viewModelViaticoTipo;
  $('#modal-remoto-lgg2').on('click', '.salida', function () {
      //modelViaticoDetalle.$destroy();
    /*$('#modal-remoto-lgg2').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');*/
    $('#modal-remoto-lgg2').modal('hide');
    $(this).find('form').trigger('reset');
    instancia.destruirTablaEmpleados();

//})
    /*
    valord = $('#id_cambiodv').val();
    if(valord == 1){
      tipo = $('#id_tipo_filtro').val()
      instancia.recargarViaticos(tipo);
    }
    $('#modal-remoto-lgg2').modal('hide');*/
  });
  $('#modal-remoto-lg').on('hidden.bs.modal', function (e) {
    // do something...
    var valor = $('#id_cambiov').val();
    if(valor == 2){
      var tipo = $('#id_tipo_filtro').val();
      viewModelViaticoTipo.recargarViaticos(tipo,'btn-v1');
    }

  })

  $('body').on('hidden.bs.modal', function (e) {
    $(this).removeData('bs.modal');
  // do something...
  /*valor = $('#id_cambiov').val();
  if(valor == 1){
    tipo = $('#id_tipo_filtro').val();
    instancia.recargarViaticos(tipo,'btn-v1');
  }*/

})
})
