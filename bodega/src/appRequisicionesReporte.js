import { EventBus } from './EventBus.js';
export const eBus = EventBus;
import { retornaprivilegios } from './components/GlobalComponents.js';

/*const vehiculoslist = httpVueLoader('./transportes/src/components/VehiculosList.vue');*/
//import { viewModelRequisicionReporteDetalle } from './appRequisicions.js';

//$(document).ready(function () {
  var instancia;
  const viewModelRequisicionReporte = new Vue({
    el: '#appRequisicion',


    data: {
      titulo:'Control de Requisiciones',
      destinos:[],
      evento:"",
      insumos:[],
      tableRequisiciones:"",

    },
    components:{
      retornaprivilegios
    },
    computed:{

    },
    created: function(){



      this.$nextTick(() => {
        this.evento = EventBus;
        this.baseTables();
        this.getTableRequisicionesReporte();
      });
    },
    methods: {
      getDireccionFromComponent: function(data){
        this.idDireccion = data;
      },
      getPrivilegiosFromComponent: function(data)
      {
        this.privilegios = data;
      },
      iComplete: function(){
        var thisInstance = this;
        //alert('Recargando');
        thisInstance.tableTransporte.cells(
          thisInstance.tableTransporte.rows(function(idx, data, node){
            //setTimeout(() => {
            //console.log('info::  '+thisInstance.privilegios.dir_director+ ' -- '+data.solicitud_status +' -- -- '+data.solicitud_status);
            if(thisInstance.privilegios.dir_director == true){
              return ((data.solicitud_tiempo_finalizado == 1 || data.solicitud_tiempo_finalizado == 3) && data.solicitud_status == 1) ? false : true;
            }

            if(thisInstance.privilegios.encargado_transporte == true || thisInstance.privilegios.columna_check == true){
              return ((data.solicitud_status == 2 && data.solicitud_tiempo_finalizado == 1)) ? false : true;
            }

            //}, 900);
          }
        ).indexes(),1).checkboxes.disable();
        thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.dir_director);
        thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.encargado_transporte);
        thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.columna_check);
      },
      getTableRequisicionesReporte: function(){
        var thisInstance = this;
        this.getTableRequisicionesReporte = $('#tb_reporte_requisiciones').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          "info":true,
          //select:true,
          //responsive:true,
          scrollX:        true,
          //scrollY: '48vh',
          scrollCollapse: true,

          fixedColumns:   true,
          fixedColumns: {
            leftColumns: 1,
            rightColumns:1
          },
          language: {
            emptyTable: "No hay requisiciones generadas para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
            url :"bodega/model/Requisicion.php",
            type: "POST",
            data:{
              opcion:1,
              tipo:3,
              year:function() { return $('#id_year').val() },
              mes:function() { return $('#id_mes').val() },
              filtro:9999
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "aoColumns": [

            //{ "class" : "text-center", mData: 'requisicionResolucionId' },
            //{ "class" : "text-center", mData: 'estado' },
            { "class" : "text-center", mData: 'requisicionNum' },
            { "class" : "text-center", mData: 'fecha' },
            { "class" : "text-center", mData: 'direccion' },
            { "class" : "text-center", mData: 'unidad' },
            { "class" : "text-center", mData: 'solicitante' },
            { "class" : "text-center", mData: 'bodega' },
            { "class" : "text-justify", mData: 'requisicionObservaciones' },
            { "class" : "text-center", mData: 'accion' },
          ],
          buttons: [
            {
              text: 'Recargar <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarRequisicionesReporte();
              }
            },
            {
              extend: 'excel',
              text: '<i class="fa fa-file-excel"></i> Exportar',
              className: 'btn btn-personalizado btn-sm',
              title: 'Reporte de Requisiciones - ' + $('#id_mes option:selected').text() + ' - '+ $('#id_year option:selected').text(),
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
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
          'select':{
            'style':'multi'
          },


        });
        $('#tb_requisiciones_reporte tbody').on('change', 'td label input', function () {

           var array = [];
           var tr = $(this).parents('tr');
           var row = thisInstance.tableTransporte.row( tr );
           var d = row.data();
           var uniques;

           var codigos = '';
           var rengs = '';
           if ($(this).is(':checked')) {
            //alert('chequeado');
            thisInstance.chequeados += 1;
            thisInstance.codigos +=','+d.DT_RowId;

          }
          else {
            thisInstance.chequeados -= 1;
            thisInstance.codigos = thisInstance.codigos.replace(','+d.DT_RowId, '');
          }
          console.log(thisInstance.codigos );

        });
        $(document).on("click", "#reqDetalleInfo", function(){
          thisInstance.showModal($(this).attr("data-id"),$(this).attr("data-type"),1,2);
        })
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
            sNext: '<i class="fa fa-angle-right"></i>'}*/
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
      recargarRequisicionesReporte: function(){

        this.getTableRequisicionesReporte.ajax.reload(null,false);

      },
      generarRequisicion: function(){
        //recibe los insumos del componente Insumos seleccionados

      },
      showModal: function(id,bodega,tipo,vista){
        var thisInstance = this;
        var imgModal = '';
        var url = '';
        let data;
        var validacion = '';
        var message = '';
        if(tipo == 1){
          imgModal = $('#modal-remoto-lgg2');
          url = 'requisicionDetalle';
          data = { requisicion_id:id, bodega:bodega };
          validacion = 'OK';
        }else if(tipo == 2 || tipo == 3){
          imgModal = $('#modal-remoto-lgg2');
          url = 'AsignarVehiculoGlobal';
          data = { codigos:thisInstance.codigos, tipo:tipo, nombremodal:'modal-remoto-lgg2', vista:vista };
          if(thisInstance.chequeados > 0){
            validacion = 'OK';
          }else{
            validacion = 'ERROR';
            message = 'Debe seleccionar al menos una solicitud';
          }
        }else if(tipo == 4){
          imgModal = $('#modal-remoto-lgg2');
          url = 'Asignacion/AsignacionDetalle';
          data = { asignacion_id_:id, nombremodal:'modal-remoto-lgg2', vista:vista, privilegio:thisInstance.privilegios };
          validacion = 'OK';
        }

        var imgModalBody = imgModal.find('.modal-content');
        //let id_persona = parseInt($('#bar_code').val());
        if(validacion == 'OK'){
          $.ajax({
            type: "GET",
            url: "bodega/views/"+url+".php",
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
        }else{
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

  //viewModelRequisicionReporte.$mount('#appRequisicion');
  //instancia = viewModelRequisicionReporte;
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
