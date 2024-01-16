import { EventBus } from './EventBus.js';
export const eBus = EventBus;
import { retornadireccion, retornaprivilegios, empleadosdireccion } from './components/GlobalComponents.js';
const asignacioneslist = httpVueLoader('transportes/src/components/AsignacionBoard.vue');
const vehiculoslist = httpVueLoader('./transportes/src/components/VehiculosList.vue');
//import { viewModelTransporteDetalle } from './appTransportes.js';

//$(document).ready(function () {
  var instancia;
  const viewModelTransporte = new Vue({
    el: '#appTransporte',


    data: {
      titulo:'Control de Transportes',
      destinos:[],
      arrayT:[
        {"id_item":"","item_string":"-- Seleccionar --"},
        {"id_item":"1","item_string":"Minuto (s)"},
        {"id_item":"2","item_string":"Hora (s)"},
        {"id_item":"3","item_string":"Día (s)"}
      ],
      tableTransporte:"",
      tableAsignaciones:"",
      tableVehiculosComision:"",
      bsDataTables:"",
      codigos:"",
      chequeados:0,
      totalChequeados:0,
      api:"",
      arreglo:[],
      codigos:"",
      direccion:"",
      evento:"",
      privilegios:"",

      kmIda:'',
      kmImprevisto:'',
      kmPorGalon:'',
      capacidadTanque:'',
      precioGalon:'',
      totalReal:'',
      picked:'',
      dataVehiculo:""
    },
    components:{
      retornadireccion, retornaprivilegios, empleadosdireccion, asignacioneslist, vehiculoslist
    },
    computed:{
      kmIdaVuelta(){
        return this.kmIda * 2;
      },
      //kmImprevisto
      subtotalRecorrido(){
        return parseFloat(this.kmIdaVuelta) + parseFloat(this.kmImprevisto);
      },
      //capacidadTanque
      consumoGalones(){
        return parseFloat(this.subtotalRecorrido) / parseFloat(this.kmPorGalon);
      },
      diferencia(){
        return parseFloat(this.consumoGalones) - parseFloat(this.capacidadTanque);
      },
      //precioGalon
      subtTotal(){
        return parseFloat(this.diferencia) * parseFloat(this.precioGalon);
      },
      totalCupon(){
        return Math.round(this.subtTotal / 100) * 100;
      }

    },
    created: function(){


      EventBus.$on('recargarTablaAsigancion', () => {
        //alert('Works!!!!!!!!!')
        this.recargarAsignaciones(1,'btn-st-a-1');

      });

      EventBus.$on('recargarTablaTransporte', () => {
        //alert('Works!!!!!!!!!')
        this.recargarTransporte(1,'btn-st-1');

      });

      EventBus.$on('clearSeleccionSolicitudes', (data) => {
        this.chequeados = 0;
        this.codigos = '';
      });

      EventBus.$on('getDataVehiculo', (data) => {
        this.dataVehiculo = data;
        this.getInfoVehiculo(data);
      });

      this.$nextTick(() => {
        this.evento = EventBus;
        this.baseTables();
        this.getTransporte();
        setTimeout(() => {
          this.iComplete();
          this.getTableAsignaciones();
          this.getTableVehiculosComision();
        }, 900);
      });
    },
    methods: {
      onChange(event) {
        var data = event.target.value;
        console.log(data);
        this.evento.$emit('getDestinoVehiculo', data);
      },
      getDireccionFromComponent: function(data){
        this.direccion = data;
      },
      getPrivilegiosFromComponent: function(data)
      {
        this.privilegios = data;
      },
      setTitle: function(titulo){
        this.titulo = titulo;
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
              return ((data.solicitud_status == 2 && (data.solicitud_tiempo_finalizado == 1 || data.solicitud_tiempo_finalizado == 2))) ? false : true;
            }

            //}, 900);
          }
        ).indexes(),1).checkboxes.disable();
        thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.dir_director);
        thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.encargado_transporte);
        thisInstance.tableTransporte.columns(1).visible(thisInstance.privilegios.columna_check);
      },
      getTransporte: function(){
        var thisInstance = this;
        this.tableTransporte = $('#tb_transporte').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          "info":true,
          select:true,
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
            emptyTable: "No hay solicitudes de transporte para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
            url :"transportes/model/Transporte.php",
            type: "POST",
            data:{
              opcion:1,
              tipo:1,
              year:function() { return $('#id_year').val() },
              filtro:function() { return $('#id_tipo_filtro_transporte').val() }
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "aoColumns": [
            { "class" : "text-center", mData: 'correlativo' },
            { "class" : "text-center", mData: 'solicitud_id' },
            { "class" : "text-justify", mData: 'solicitud_fecha' },
            { "class" : "text-center", mData: 'solicitud_hora' },
            { "class" : "text-center", mData: 'duracion' },
            { "class" : "text-center", mData: 'direccion' },
            { "class" : "text-center", mData: 'solicitante' },
            { "class" : "text-center", mData: 'destinos' },
            { "class" : "text-center", mData: 'motivo' },
            { "class" : "text-center", mData: 'solicitud_status_p' },
            //{ "class" : "text-center", mData: 'solicitud_tiempo_finalizado_p' },
            { "class" : "text-center", mData: 'accion' }
          ],
          buttons: [
            {
              text: 'Agregar <i class="fa fa-plus-check"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function ( e, dt, node, config ) {
                thisInstance.showModal("",0,1);
              }
            },
            {
              text: 'Pendientes <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt active',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarTransporte(1,'btn-st-1');
              }
            },
            {
              text: 'Finalizadas <i class="fa fa-check-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarTransporte(2,'btn-st-2');
              }
            },
            {
              text: 'Canceladas <i class="fa fa-times-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarTransporte(3,'btn-st-3');
              }
            },
          ],
          "columnDefs": [
            {
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
            }
          ],
          'select':{
            'style':'multi'
          },


        });
        $('#tb_transporte tbody').on('change', 'td label input', function () {

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
        $(document).on("click", "#idSolicitudDetalle", function(){
          thisInstance.showModal($(this).attr("data-id"),1,2);
        })
      },
      getTableAsignaciones: function(){
        var thisInstance = this;
        var groupColumn = 0;
        this.tableAsignaciones = $('#tb_asignaciones').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          "info":true,
          select:true,
          //responsive:true,
          scrollX:        true,
          //scrollY: '48vh',
          scrollCollapse: true,


          language: {
            emptyTable: "No hay solicitudes de transporte para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
            url :"transportes/model/Asignaciones.php",
            type: "POST",
            data:{
              opcion:2,
              tipo:1,
              year:function() { return $('#id_year_a').val() },
              filtro:function() { return $('#id_tipo_filtro_asignacion').val() }
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "aoColumns": [
            { "class" : "text-center", mData: 'correlativo_a' },
            { "class" : "text-center", mData: 'estado' },
            /*{ "class" : "text-center", mData: 'asignado_por_id' },
            { "class" : "text-justify", mData: 'asignacion_year' },
            { "class" : "text-center", mData: 'correlativo' },*/

            { "class" : "text-center", mData: 'fecha' },
            { "class" : "text-center", mData: 'vehiculos' },
            { "class" : "text-center", mData: 'solicitudes' },
            { "class" : "text-center", mData: 'destinos' },
            { "class" : "text-center", mData: 'accion' },
          ],
          buttons: [
            {
              text: 'Pendientes <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-a-1 btn-solt-a active',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarAsignaciones(1,'btn-st-a-1');
              }
            },
            {
              text: 'Finalizadas <i class="fa fa-check-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-a-2 btn-solt-a',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarAsignaciones(2,'btn-st-a-2');
              }
            },
            {
              text: 'Canceladas <i class="fa fa-times-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-a-3 btn-solt-a',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarAsignaciones(3,'btn-st-a-3');
              }
            },
          ],
          "columnDefs": [
            //{ visible: false, targets: groupColumn },
            {'max-width': '100px', 'targets': 4},
            {
              "targets": 3,
              "data": "vehiculos",
              "render": function ( data, type, row, meta ) {
                //return '<a href="'+data+'">Download</a>';
                var vehiculos = '';
                data.map(function(v){
                  vehiculos += `<div class="row">` ;
                  vehiculos += `<div class="col-sm-1"><h3 class="${v.estado}"></h3> </div>`;
                  vehiculos += `<div class="col-sm-3 text-left">${v.tipo_asignacion} </div>`;
                  //vehiculos += `<div class="col-sm-7 text-left">${v.marca } - ${ v.linea } <br> ${ v.modelo } - ${ v.nro_placa } <br> <strong>Fecha: ${v.fecha_salida }</strong></div>`;
                  vehiculos += `<div class="col-sm-7 text-left">${v.vehiculo } <br> <strong>Fecha: ${v.fecha_salida }</strong></div>`;
                  vehiculos +=  (v.asignacion_status == 1) ? `<div class="col-sm-1" class="${v.estado_seguimiento}"></div>` : `<div class="col-sm-1"></div>`;
                  vehiculos += `</div><br>`;
                });

                return vehiculos;
              }
            },
            {
              "targets": 4,
              "data": "solicitudes",
              "render": function ( data, type, row, meta ) {
                //return '<a href="'+data+'">Download</a>';
                var direcciones = '';

                data.map(function(s){
                  direcciones += `<div class="row" style="max-width:400px">`;
                  direcciones += `<div class="col-sm-4">${s.solicitud_status_p}</div><div class="col-sm-8 text-left">${s.direccion }</div>` ;
                  direcciones += `</div><br>`;
                });



                return direcciones;
              }
            },
            /*{
              'targets':1,
              'checkboxes':{
                'selectRow':true
              },
            },*/
            {
              'targets': [6],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
                return '<span class="btn btn-sm btn-personalizado outline" id="idAsignacionDetalle" data-id="'+row.DT_RowId+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span>';//'+row.DT_RowId+'
              }
            }
          ],
          'select':{
            'style':'multi'
          }

          /*drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            api
                .column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before('<tr class="groupAsi"><td colspan="7">' + group + '</td></tr>');

                        last = group;
                    }
                });*/
      //  },


        });

        $(document).on("click", "#idAsignacionDetalle", function(){
          thisInstance.showModal($(this).attr("data-id"),4,2);
        })

      },
      getTableVehiculosComision: function(){
        var thisInstance = this;
        var groupColumn = 0;
        this.tableVehiculosComision = $('#tb_vehiculos_comision').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          "info":true,
          select:true,
          //responsive:true,
          //scrollX:        true,
          //scrollY: '48vh',
          scrollCollapse: true,


          language: {
            emptyTable: "No hay solicitudes de transporte para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
            url :"transportes/model/Asignaciones.php",
            type: "POST",
            data:{
              opcion:5,
              /*tipo:1,
              year:function() { return $('#id_year_a').val() },
              filtro:function() { return $('#id_tipo_filtro_asignacion').val() }*/
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "aoColumns": [
            { "class" : "text-center", mData: 'estado_asignacion' },
            { "class" : "text-center", mData: 'conductor' },


            /*{ "class" : "text-center", mData: 'asignado_por_id' },
            { "class" : "text-justify", mData: 'asignacion_year' },
            { "class" : "text-center", mData: 'correlativo' },*/

            { "class" : "text-center", mData: 'tipo_asignacion' },
            { "class" : "text-center", mData: 'nro_placa' },
            { "class" : "text-center", mData: 'marca' },
            { "class" : "text-center", mData: 'modelo' },
            { "class" : "text-center", mData: 'linea' },
          ],
          buttons: [
            {
              text: 'Pendientes <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-a-1 btn-solt-a active',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarAsignaciones(1,'btn-st-a-1');
              }
            },
            {
              text: 'Finalizadas <i class="fa fa-check-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-a-2 btn-solt-a',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarAsignaciones(2,'btn-st-a-2');
              }
            },
            {
              text: 'Canceladas <i class="fa fa-times-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-st-a-3 btn-solt-a',
              action: function ( e, dt, node, config ) {
                thisInstance.recargarAsignaciones(3,'btn-st-a-3');
              }
            },
          ],
          "columnDefs": [
            //{ visible: false, targets: groupColumn },
            {
              "targets": 0,
              "data": "vehiculos",
              "render": function ( data, type, row, meta ) {
                //return '<a href="'+data+'">Download</a>';
                console.log(row);
                var vehiculos = '';
                //data.map(function(v){

                  vehiculos += `<div style="margin-top:0px; "><span class="badge-sm text-info">
                                  <i class="fa fa-spinner fa-spin"></i> En curso</span>
                                  <br>
                                  <div class="progress progress-striped skill-bar " style="height:6px">
                                    <div class="progress-bar progress-bar-striped bg-info" role="progressbar" aria-valuenow="50" aria-valuemin="50" aria-valuemax="100" style="width: 50%">
                                    </div>
                                  </div>
                                </div>`;



                return vehiculos;
              }
            },
            {
              'targets': [6],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
                return '<span class="btn btn-sm btn-personalizado outline" id="idVehiculoComision" data-id="'+row.asignacion_id+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span>';//'+row.DT_RowId+'
              }
            }
          ],
          'select':{
            'style':'multi'
          }

          /*drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            api
                .column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before('<tr class="groupAsi"><td colspan="7">' + group + '</td></tr>');

                        last = group;
                    }
                });*/
      //  },


        });

        $(document).on("click", "#idVehiculoComision", function(){
          thisInstance.showModal($(this).attr("data-id"),4,2);
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
      recargarTransporte: function(opcion, classe){
        var thisInstance = this;
        thisInstance.chequeados = 0;
        thisInstance.codigos = '';
        $('.btn-solt').removeClass('active');
        $('.'+classe).addClass('active');
        $('#id_tipo_filtro_transporte').val(opcion);
        this.tableTransporte.ajax.reload(thisInstance.iComplete,false);

      },
      recargarAsignaciones: function(opcion, classe){
        $('.btn-solt-a').removeClass('active');
        $('.'+classe).addClass('active');
        $('#id_tipo_filtro_asignacion').val(opcion);
        this.tableAsignaciones.ajax.reload();

      },
      showModal: function(id,tipo,vista){
        var thisInstance = this;
        var imgModal = '';
        var url = '';
        let data;
        var validacion = '';
        var message = '';
        if(tipo == 0){
          imgModal = $('#modal-remoto-lg');
          url = 'SolicitudNueva';
          //data = { solicitud_id:id, nombremodal:'modal-remoto-lgg2', vista:vista, privilegio:thisInstance.privilegios };
          validacion = 'OK';
        }else if(tipo == 1){
          imgModal = $('#modal-remoto-lgg2');
          url = 'SolicitudDetalle';
          data = { solicitud_id:id, nombremodal:'modal-remoto-lgg2', vista:vista, privilegio:thisInstance.privilegios };
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
            url: "transportes/views/"+url+".php",
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

      },
      deleteRow(index, d) {
        var idx = this.destinos.indexOf(d);

        console.log(idx, index);
        if (idx > -1) {
          this.destinos.splice(idx, 1);

        }
      },
      addNewRow: function(){
        if ($('#departamento').val() != null) {
          //viewModelPedido.insumos = response.data;
          this.destinos.push({
            departamento_id:$('#departamento').val(),
            departamento:$("#departamento option:selected").text(),
            municipio_id:$('#municipio').val(),
            municipio:$("#municipio option:selected").text(),
            lugar_id:$('#poblado').val(),
            lugar:$("#poblado option:selected").text(),
          })
        } else {
          Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un destino',
            showConfirmButton: false,
            timer: 1100
          });
        }
      },
      saveSolicitud: function(){
        //inicio
        var thisInstance = this;
        jQuery('.jsValidacionSolicitudTransporteNueva').validate({
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
            var destinos = viewModelTransporte.destinos;
            if (viewModelTransporte.destinos.length >= 1) {
              Swal.fire({
                title: '<strong>¿Desea generar la solicitud de Transporte?</strong>',
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
                    url: "transportes/model/Transporte.php",
                    dataType: 'json',
                    data: {
                      opcion:3,
                      fecha_salida: $('#fecha_salida').val(),
                      duracion: $('#duracion').val(),
                      cant_personas:$('#cant_personas').val(),
                      id_tipo_seleccion: $('#id_tipo_seleccion').val(),
                      cant_codigos: $('#cant_codigos').val(),
                      motivo_solicitud: $('#motivo_solicitud').val(),
                      observaciones:$('#observaciones').val(),
                      fecha_regreso:$('#fecha_regreso').val(),
                      responsable:$('#id_empleados_list').val(),
                      destinos: destinos
                    }, //f de fecha y u de estado.
                    beforeSend: function () {
                    },
                    success: function (data) {
                      //exportHTML(data.id);
                      //recargarDocumentos();
                      if (data.msg == 'OK') {
                        $('#fecha_salida').val();
                        $('#duracion').val();
                        $('#cant_personas').val();
                        $('#id_tipo_seleccion').val();
                        $('#cant_codigos').val();
                        $('#motivo_solicitud').val();
                        $('#observaciones').val();
                        $('#fecha_regreso').val();
                        $('#id_empleados_list').val();
                        thisInstance.destinos.splice(0, thisInstance.destinos.length);
                        /*$('#pedido_nro').val('');
                        $('#fecha_pedido').val('');
                        $('#id_unidad').val('');
                        $('#id_observaciones').val('');
                        $('#id_observaciones').text('');
                        thisInstance.totalCharacter = 500;
                        thisInstance.messageCharacter = '';
                        $('.categoryName').val('');
                        $('.categoryName').val(null).trigger('change');
                        $('#pacName').val('');
                        thisInstance.isUrgente = false;
                        thisInstance.validarUrgente();
                        $('#pacName').val(null).trigger('change');
                        viewModelPedido.limpiar_lista();
                        viewModelPedido.recargarTabla();*/
                        Swal.fire({
                          type: 'success',
                          title: 'Solicitud generada',
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
                title: 'Debe ingresar al menos un destino',
                showConfirmButton: false,
                timer: 1100
              });
            }
          },
        });
        //fin
      },
      eventHeader: function(){
        var thisInstance = this;
        thisInstance.chequeados = 0;
        thisInstance.codigos = '';
        var total_ = thisInstance.tableTransporte.data().count();
        var rows_selected1 = thisInstance.tableTransporte.rows({ selected: true }).data();
        var rows_selected = thisInstance.tableTransporte.column(1).checkboxes.selected();

        var codigos = '';

        rows_selected.map(function(obj){
          var data = thisInstance.tableTransporte.row( '#'+obj ).data();
          console.log('codigos: '+data.DT_RowId);
           thisInstance.chequeados += 1;
           codigos +=','+data.DT_RowId;
           thisInstance.codigos = codigos;
        });
        console.log(thisInstance.codigos);

      },
      getInfoVehiculo: function(id){
        axios.get('transportes/model/Vehiculo.php',{
          params:{
            opcion:1,
            id:id
          }
        })
        .then(function (response) {
          this.dataVehiculo = response.data;
          this.kmPorGalon = response.data.kmPorGalon;
          this.capacidadTanque = response.data.capacidadTanque;

        }.bind(this))
        .catch(function (error) {
            console.log(error);
        });
      }

    }
  })

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable
      .tables({ visible: true, api: true })
      .columns.adjust()
      .fixedColumns().relayout();
  });

  //viewModelTransporte.$mount('#appTransporte');
  //instancia = viewModelTransporte;
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
