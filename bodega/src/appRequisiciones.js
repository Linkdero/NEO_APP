import { EventBus } from './EventBus.js';
export const eBus = EventBus;
import { retornaprivilegios } from './components/GlobalComponents.js';
import { empleados, retornadireccion, departamentos } from '../../assets/js/pages/GlobalComponents.js';
const insumosfiltrado = httpVueLoader('bodega/src/components/InsumosFiltrado.vue');

const insumos = httpVueLoader('bodega/src/components/InsumosSeleccionados.vue');
const unidadesl = httpVueLoader('bodega/src/components/UnidadesList.vue');
/*const vehiculoslist = httpVueLoader('./transportes/src/components/VehiculosList.vue');*/
//import { viewModelRequisicionDetalle } from './appRequisicions.js';

//$(document).ready(function () {
var instancia;
const viewModelRequisicion = new Vue({
  el: '#appRequisicion',


  data: {
    titulo: 'Control de Requisiciones',
    destinos: [],
    arrayT: [
      { "id_item": "", "item_string": "-- Seleccionar --" },
      { "id_item": "1", "item_string": "Minuto (s)" },
      { "id_item": "2", "item_string": "Hora (s)" },
      { "id_item": "3", "item_string": "Día (s)" }
    ],
    insumos: [],
    tableRequisiciones: "",
    tableAsignaciones: "",
    bsDataTables: "",
    codigos: "",
    chequeados: 0,
    totalChequeados: 0,
    totalCharacter: 300,
    messageCharacter: '',
    evento: '',
    req: "",
    privilegios: "",
    tableFamilias: "",
    idDireccion: "",
    columnae: "col-sm-12",
    showUnidad: true
  },
  components: {
    retornaprivilegios, insumosfiltrado, insumos, unidadesl, empleados, retornadireccion, departamentos//, empleadosdireccion, asignacioneslist, vehiculoslist
  },
  computed: {

  },
  mounted: function () {
    EventBus.$on('recargarTablaRequisiciones', (data) => {
      //alert('Works!!!!!!!!!')
      this.recargarRequisiciones(1, 'btn-st-a-1');
    });

    this.$nextTick(() => {
      this.evento = EventBus;
      this.baseTables();
      this.getTableRequisiciones();

      setTimeout(() => {
        if (this.privilegios.inventarios == true) {
          this.getTableFamilias();
        }
        if (this.privilegios.residencias_solicita_recursos == true || this.privilegios.bodega_armeria_rev == true || this.privilegios.bodega_talleres_rev == true) {
          this.showUnidad = false;
          this.columnae = 'col-sm-4';
        }
      }, 900);
    });
  },

  methods: {
    getInsumosL: function (data) {
      console.log(data);
      this.insumos = data;
    },
    setTitle: function (titulo) {
      this.titulo = titulo;
    },
    getDireccionFromComponent: function (data) {
      this.idDireccion = data;
    },
    getPrivilegiosFromComponent: function (data) {
      this.privilegios = data;
    },
    charCount: function () {
      var total = 300;
      var left = total - this.messageCharacter.length;
      this.totalCharacter = left;
    },
    iComplete: function () {
      var thisInstance = this;
      //alert('Recargando');
      thisInstance.tableTransporte.cells(
        thisInstance.tableTransporte.rows(function (idx, data, node) {
          //setTimeout(() => {
          //console.log('info::  '+thisInstance.privilegios.dir_director+ ' -- '+data.solicitud_status +' -- -- '+data.solicitud_status);
          if (thisInstance.privilegios.dir_director == true) {
            return ((data.solicitud_tiempo_finalizado == 1 || data.solicitud_tiempo_finalizado == 3) && data.solicitud_status == 1) ? false : true;
          }

          if (thisInstance.privilegios.encargado_transporte == true || thisInstance.privilegios.columna_check == true) {
            return ((data.solicitud_status == 2 && data.solicitud_tiempo_finalizado == 1)) ? false : true;
          }

          //}, 900);
        }
        ).indexes(), 1).checkboxes.disable();
      thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.dir_director);
      thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.encargado_transporte);
      thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.columna_check);
    },
    getTableRequisiciones: function () {
      var thisInstance = this;
      this.tableRequisiciones = $('#tb_requisiciones').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging": true,
        "info": true,
        //select:true,
        //responsive:true,
        scrollX: true,
        //scrollY: '48vh',
        scrollCollapse: true,

        fixedColumns: true,
        fixedColumns: {
          leftColumns: 1,
          rightColumns: 1
        },
        language: {
          emptyTable: "No hay requisiciones generadas para mostrar",
          sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          //loadingRecords: " <div class='loaderr'></div> "
        },
        ajax: {
          url: "bodega/model/Requisicion.php",
          type: "POST",
          data: {
            opcion: 1,
            tipo: 1,
            year: function () { return $('#id_year').val() },
            filtro: function () { return $('#id_tipo_filtro_requisiciones').val() }
          },
          error: function () {
            $("#post_list_processing").css("display", "none");
          }
        },
        "aoColumns": [

          //{ "class" : "text-center", mData: 'requisicionResolucionId' },
          { "class": "text-center", mData: 'estado' },
          { "class": "text-center", mData: 'requisicionNum' },
          { "class": "text-center", mData: 'fecha' },
          { "class": "text-center", mData: 'direccion' },
          { "class": "text-center", mData: 'unidad' },
          { "class": "text-center", mData: 'solicitante' },
          { "class": "text-center", mData: 'bodega' },
          { "class": "text-justify", mData: 'requisicionObservaciones' },
          { "class": "text-center", mData: 'accion' },
        ],
        buttons: [
          {
            text: 'Pendientes <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-req-1 btn-reqs active',
            action: function (e, dt, node, config) {
              thisInstance.recargarRequisiciones(1, 'btn-req-1');
            }
          },
          {
            text: 'Despachadas <i class="fa fa-check-circle"></i>',
            className: 'btn btn-personalizado btn-sm btn-req-2 btn-reqs',
            action: function (e, dt, node, config) {
              thisInstance.recargarRequisiciones(2, 'btn-req-2');
            }
          },
          {
            text: 'Anuladas <i class="fa fa-times-circle"></i>',
            className: 'btn btn-personalizado btn-sm btn-req-3 btn-reqs',
            action: function (e, dt, node, config) {
              thisInstance.recargarRequisiciones(3, 'btn-req-3');
            }
          },
        ],
        "columnDefs": [
          /*{
            'targets':1,
            'checkboxes':{
              'selectRow':true
            },
          },
          {
            'targets': [10],
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            render: function(data, type, row, meta) {
              return '<span class="btn btn-sm btn-personalizado outline" id="idSolicitudDetalle" data-id="'+row.DT_RowId+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span>';//'+row.DT_RowId+'
            }
          }*/
        ],
        'select': {
          'style': 'multi'
        },


      });
      $('#tb_requisiciones tbody').on('change', 'td label input', function () {

        var array = [];
        var tr = $(this).parents('tr');
        var row = thisInstance.tableTransporte.row(tr);
        var d = row.data();
        var uniques;

        var codigos = '';
        var rengs = '';
        if ($(this).is(':checked')) {
          //alert('chequeado');
          thisInstance.chequeados += 1;
          thisInstance.codigos += ',' + d.DT_RowId;

        }
        else {
          thisInstance.chequeados -= 1;
          thisInstance.codigos = thisInstance.codigos.replace(',' + d.DT_RowId, '');
        }
        console.log(thisInstance.codigos);

      });
      $(document).on("click", "#reqDetalleInfo", function () {
        thisInstance.showModal($(this).attr("data-id"), $(this).attr("data-type"), 1, 2);
      })
    },
    getTableFamilias: function () {
      var thisInstance = this;
      this.tableFamilias = $('#tb_familias').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging": true,
        "info": true,
        //select:true,
        //responsive:true,
        scrollX: true,
        //scrollY: '48vh',
        //scrollCollapse: true,

        //fixedColumns:   true,
        /*fixedColumns: {
          leftColumns: 1,
          rightColumns:1
        },*/
        language: {
          emptyTable: "No hay requisiciones generadas para mostrar",
          sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          //loadingRecords: " <div class='loaderr'></div> "
        },
        ajax: {
          url: "bodega/model/Requisicion.php",
          type: "POST",
          data: {
            opcion: 13,

          },
          error: function () {
            $("#post_list_processing").css("display", "none");
          }
        },
        "aoColumns": [

          //{ "class" : "text-center", mData: 'requisicionResolucionId' },
          //{ "class" : "text-center", mData: 'fam_id' },
          { "class": "text-center", mData: 'DT_RowId' },
          { "class": "text-center", mData: 'familia' },
          //{ "class" : "text-center", mData: 'fam_est' },
          { "class": "text-center", mData: 'residencias' },
          { "class": "text-center", mData: 'financiero' },
          { "class": "text-center", mData: 'talleres' },
          { "class": "text-center", mData: 'edificios' },
          { "class": "text-center", mData: 'academia' },
          { "class": "text-center", mData: 'armeria' },
        ],
        buttons: [
          {
            text: 'Recargar <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-req-1 btn-reqs active',
            action: function (e, dt, node, config) {
              thisInstance.recargarRequisiciones(1, 'btn-req-1');
            }
          }
        ],
        "columnDefs": [
          /*{
            'targets':1,
            'checkboxes':{
              'selectRow':true
            },
          },*/
          {
            'targets': [2, 3, 4, 5, 6, 7],
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            render: function (data, type, row, meta) {
              var checkbox = '';
              var bodega = '';
              checkbox += '<div class="custom-control custom-checkbox text-center">' +
                '<input id="' + meta.row + '-' + meta.col + '" class="custom-control-input" ';
              if (meta.col == 2) {
                bodega = '01';
                if (row.residencias == 1) {
                  checkbox += 'checked';
                }
              } else if (meta.col == 3) {
                bodega = '04';
                if (row.financiero == 1) {
                  checkbox += 'checked';
                }
              } else if (meta.col == 4) {
                bodega = '06';
                if (row.talleres == 1) {
                  checkbox += 'checked';
                }
              } else if (meta.col == 5) {
                bodega = '09';
                if (row.edificios == 1) {
                  checkbox += 'checked';
                }
              } else if (meta.col == 6) {
                bodega = '10';
                if (row.academia == 1) {
                  checkbox += 'checked';
                }
              } else if (meta.col == 7) {
                bodega = '11';
                if (row.armeria == 1) {
                  checkbox += 'checked';
                }
              }

              checkbox += ' data-type="' + bodega + '" data-id="' + row.DT_RowId + '" ' +
                ' type="checkbox"> ' +
                '<label class="custom-control-label" for="' + meta.row + '-' + meta.col + '"></label>' +
                '</div>';

              return checkbox;
            }
          }

        ],
        'select': {
          'style': 'multi'
        },


      });

      $('#tb_familias tbody').on('click', 'tr > td > div > input', function () {
        console.log('change ::: ' + $(this).attr('data-id') + ' -- ' + $(this).attr('data-type'));

        $.ajax({
          type: "POST",
          url: "bodega/model/Requisicion.php",
          //dataType: 'html',
          data: {
            bodega: $(this).attr('data-type'),
            familia: $(this).attr('data-id'),
            opcion: 14
          },
          beforeSend: function () {

          },
          success: function (data) {
            //imgModalBody.html(data);
          }
        });
      });

    },
    baseTables: function () {
      // DataTables Bootstrap integration
      this.bsDataTables = jQuery.fn.dataTable;
      // Set the defaults for DataTables init
      jQuery.extend(true, this.bsDataTables.defaults, {
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
          sNext: '<i class="fa fa-angle-right"></i>'}*/
          sProcessing: "Procesando...",
          sLengthMenu: "Mostrar _MENU_ registros",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
          sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
          sInfoPostFix: "",
          sSearch: "Buscar:",
          sUrl: "",
          sInfoThousands: ",",
          sLoadingRecords: "Cargando...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "<i class='fa fa-chevron-right'></i>",
            sPrevious: "<i class='fa fa-chevron-left'></i>"
          },
          oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
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
    recargarRequisiciones: function (opcion, classe) {
      var thisInstance = this;
      thisInstance.chequeados = 0;
      thisInstance.codigos = '';
      $('.btn-reqs').removeClass('active');
      $('.' + classe).addClass('active');
      $('#id_tipo_filtro_requisiciones').val(opcion);
      this.tableRequisiciones.ajax.reload(null, false);

    },
    generarRequisicion: function () {
      //recibe los insumos del componente Insumos seleccionados

    },
    saveRequisicion: function () {
      //inicio
      var thisInstance = this;
      jQuery('.jsValidacionRequisicionNueva').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function (e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
          //regformhash(form,form.password,form.confirmpwd);
          thisInstance.evento.$emit('sendInsumosToParent')
          var insumos = thisInstance.insumos;
          if (viewModelRequisicion.insumos.length >= 1) {
            Swal.fire({
              title: '<strong>¿Desea generar la Requisición?</strong>',
              text: "",
              type: 'question',
              showCancelButton: true,
              showLoaderOnConfirm: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, Generar!'
            }).then((result) => {
              if (result.value) {
                //alert(vt_nombramiento);
                $.ajax({
                  type: "POST",
                  url: "bodega/model/Requisicion.php",
                  dataType: 'json',
                  data: {
                    opcion: 4,
                    id_direccion: $('#id_direccion').val(),
                    unidad: $('#id_unidad').val(),
                    id_bodega: $('#id_bodega').val(),
                    id_observaciones: $('#id_observaciones').val(),
                    id_solicitante: $('#solicitante_c_id').val(),
                    insumos: insumos
                  }, //f de fecha y u de estado.
                  beforeSend: function () {
                  },
                  success: function (data) {
                    //exportHTML(data.id);
                    //recargarDocumentos();
                    if (data.msg == 'OK') {

                      $('#filtro_form_nuevo').val('');
                      $('#filtro_form_nuevo').val(null).trigger('change');
                      $('#id_direccion').val('');
                      $('#id_unidad').val('');
                      $('#id_bodega').val('');
                      $('#id_observaciones').val('');
                      $('#solicitante_c_id').val('');
                      $('#solicitante_c_id').val(null).trigger('change');

                      thisInstance.evento.$emit('recargarPorcentajeTotal', 1);
                      thisInstance.insumos.splice(0, thisInstance.insumos.length);
                      thisInstance.evento.$emit('cleanListInsumos');
                      thisInstance.recargarRequisiciones(1, 'btn-req-1');
                      Swal.fire({
                        type: 'success',
                        title: 'Requisición generada',
                        showConfirmButton: false,
                        timer: 1100
                      });//impresion_pedido(data.id);

                    } else {
                      Swal.fire({
                        type: 'error',
                        title: 'Error',
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                  }
                }).done(function () {
                }).fail(function (jqXHR, textSttus, errorThrown) {
                  alert(errorThrown);
                });
              }
            })
          } else {
            Swal.fire({
              type: 'error',
              title: 'Debe seleccionar al menos un insumo',
              showConfirmButton: false,
              timer: 1100
            });
          }
        },
      });
      //fin
    },
    eventHeader: function () {
      var thisInstance = this;
      thisInstance.chequeados = 0;
      thisInstance.codigos = '';
      var total_ = thisInstance.tableTransporte.data().count();
      var rows_selected1 = thisInstance.tableTransporte.rows({ selected: true }).data();
      var rows_selected = thisInstance.tableTransporte.column(1).checkboxes.selected();

      var codigos = '';

      rows_selected.map(function (obj) {
        var data = thisInstance.tableTransporte.row('#' + obj).data();
        console.log('codigos: ' + data.DT_RowId);
        thisInstance.chequeados += 1;
        codigos += ',' + data.DT_RowId;
        thisInstance.codigos = codigos;
      });
      console.log(thisInstance.codigos);

    },
    showModal: function (id, bodega, tipo, vista) {
      var thisInstance = this;
      var imgModal = '';
      var url = '';
      let data;
      var validacion = '';
      var message = '';
      if (tipo == 1) {
        imgModal = $('#modal-remoto-lgg2');
        url = 'requisicionDetalle';
        data = { requisicion_id: id, bodega: bodega };
        validacion = 'OK';
      } else if (tipo == 2 || tipo == 3) {
        imgModal = $('#modal-remoto-lgg2');
        url = 'AsignarVehiculoGlobal';
        data = { codigos: thisInstance.codigos, tipo: tipo, nombremodal: 'modal-remoto-lgg2', vista: vista };
        if (thisInstance.chequeados > 0) {
          validacion = 'OK';
        } else {
          validacion = 'ERROR';
          message = 'Debe seleccionar al menos una solicitud';
        }
      } else if (tipo == 4) {
        imgModal = $('#modal-remoto-lgg2');
        url = 'Asignacion/AsignacionDetalle';
        data = { asignacion_id_: id, nombremodal: 'modal-remoto-lgg2', vista: vista, privilegio: thisInstance.privilegios };
        validacion = 'OK';
      }

      var imgModalBody = imgModal.find('.modal-content');
      //let id_persona = parseInt($('#bar_code').val());
      if (validacion == 'OK') {
        $.ajax({
          type: "GET",
          url: "bodega/views/" + url + ".php",
          dataType: 'html',
          data: data,
          beforeSend: function () {
            imgModal.modal('show');
            imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
          },
          success: function (data) {
            imgModalBody.html(data);
          }
        });
      } else {
        Swal.fire({
          type: 'error',
          title: validacion,
          showConfirmButton: false,
          timer: 1100
        });
      }

    }
  }
})

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  $.fn.dataTable
    .tables({ visible: true, api: true })
    .columns.adjust()
    .fixedColumns().relayout();
});

//viewModelRequisicion.$mount('#appRequisicion');
//instancia = viewModelRequisicion;
//instancia.getTransporte();
$('#modal-remoto-lgg2').on('click', '.salida', function () {
  //modelViaticoDetalle.$destroy();
  /*$('#modal-remoto-lgg2').on('hidden.bs.modal', function () {
  $(this).find('form').trigger('reset');*/
  $('#modal-remoto-lgg2').modal('hide');
  $(this).find('form').trigger('reset');
  //})
  /*
  valord = $('#id_cambiodv').val();
  if(valord == 1){
    tipo = $('#id_tipo_filtro').val()
    instancia.recargarViaticos(tipo);
  }
  $('#modal-remoto-lgg2').modal('hide');*/
});

$('body').on('hidden.bs.modal', function (e) {
  $(this).removeData('bs.modal');
  // do something...
  /*valor = $('#id_cambiov').val();
  if(valor == 1){
    tipo = $('#id_tipo_filtro').val();
    instancia.recargarViaticos(tipo,'btn-v1');
  }*/

})
//})
