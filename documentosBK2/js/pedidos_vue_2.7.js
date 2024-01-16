var instancia = '';
var eventBusPE = new Vue();
var eventBusPACD = new Vue();

$(document).ready(function () {
  /*$('.categoryName').select2({
    placeholder: 'Selecciona una categoría',
    ajax: {
      url: 'documentos/php/back/pedido/get_insumo_filtrado.php',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });*/


  viewModelPedido = new Vue({
    el: '#app_pedidos',
    data: {
      pedidos: "",
      pedido: "",
      insumos_list: "",
      opcion_pedido: 0,
      tablaPedidos: "",
      tableFacturas: "",
      insumos: [{
        Ppr_id: '',
        Ppr_can: '',
        Ppr_cod: '',
        Ppr_codPre: '',
        Ppr_Nom: '',
        Ppr_Des: '',
        Ppr_Pres: '',
        Ppr_Ren: '',
        Ppr_Med: "",
        Ppr_lineas: ""
      }],
      unidades: "",
      reporte: "",
      tableReportes: "",
      tipoC: 0,
      pacActivo: true,
      msgPac: 'SI',
      pac_id:0,
      totalCharacter: 500,
      messageCharacter:'',
      totalLineas: 0,
      isUrgente:false,
      msgUrgente:"NO"
    },
    mounted() {
      instancia = this;
    },
    computed: {

    },
    created: function () {
      //this.get_pedidos()

      //this.get_unidades();
      this.insumos.splice(0, 1);

    },
    methods: {
      charCount: function(){
        var total=500;
        var left = total - this.messageCharacter.length;
         this.totalCharacter = left;
       },
      get_pedidos: function () {
        axios.get('documentos/php/back/listados/get_pedidos', {
          params: {
            tipo: 1
          }
        }).then(function (response) {
          viewModelPedido.pedidos = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },
      get_insumos_list: function () {
        axios.get('documentos/php/back/pedido/get_insumos', {
          params: {
            tipo: 1,
            filtro: $('#filtro_insumo').val()
          }
        }).then(function (response) {
          viewModelPedido.insumos_list = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },
      opcionPedido: function (opc) {
        this.opcion_pedido = opc;
      },
      table_pedidos: function () {
        this.tablePedidos = $('#tb_pedidos').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          scrollY: '45vh',
          scrollCollapse: true,
          //paging: false,
          select:true,
          "paging": true,
          "info": true,
          "deferRender": true,
          select:true,
          scrollX: true,
          fixedColumns: true,
          fixedColumns: {
            rightColumns: 1
          },
          pagingType: "full_numbers",
          dom:
            "<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4'f>>" +

            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
          //responsive: true,
          oLanguage: {
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
          },
          language: {
            emptyTable: "No hay Pedidos para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          ajax: {
            url: "documentos/php/back/listados/get_pedidos_listado.php",
            type: "POST",
            data: {
              year: function() { return $('#id_year').val() },
              tipo: function () { return $('#id_tipo_filtro').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },
          "aoColumns": [
            { "class": "text-center", mData: 'pedido_num' },
            //{ "class": "text-center", mData: 'pedido_interno' },
            { "class": "text-center", mData: 'pac' },
            { "class": "text-center", mData: 'fecha' },
            { "class": "text-center", mData: 'ubicacion' },
            { "class": "text-center", mData: 'asignado' },
            { "class": "text-center", mData: 'observaciones' },
            { "class": "text-center", mData: 'estado' },
            { "class": "text-center", mData: 'acciones' }
          ],
          buttons: [
            /*{
              text: 'Nuevos <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function ( e, dt, node, config ) {
                viewModelPedido.recargarTabla(2);
              }
            },
            {
              text: 'En proceso <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function ( e, dt, node, config ) {
                viewModelPedido.recargarTabla(3);
              }
            },
            {
              text: 'Autorizados <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function ( e, dt, node, config ) {
                viewModelPedido.recargarTabla(4);
              }
            },*/
            {
              text: 'Pendientes <i class="fa fa-sync btn-pedientes"></i>',
              className: 'btn btn-personalizado btn-sm btn-f1 active',
              action: function ( e, dt, node, config ) {
                viewModelPedido.recargarTabla(0);
              }
            },
            {
              text: 'Aprobados <i class="fa fa-sync btn-pedientes "></i>',
              className: 'btn btn-personalizado btn-sm btn-f2',
              action: function ( e, dt, node, config ) {
                viewModelPedido.recargarTabla(2);
              }
            },
            {
              text: 'Todos <i class="fa fa-sync btn-todos"></i>',
              className: 'btn btn-personalizado btn-sm btn-f3',
              action: function (e, dt, node, config) {
                viewModelPedido.recargarTabla(1);
              }
            },
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-personalizado'
            }
          ],
          "columnDefs": [
            /*{
              /*responsivePriority: 0,
              targets: [6, 7]
            },*/
            { "width": "15px", "targets": 2 },
            { "width": "15px", "targets": 3 },
            /*{
              'targets': [6],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {

                var menu='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="get_menu_impresion('+row.DT_RowId+',1)"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu'+row.DT_RowId+'"></div></div></div></div>';
              return  '<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'&tipo_filtro='+$('#id_tipo_filtro').val()+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'
                //return  '<div class="btn-group"><span class="btn btn-sm btn-personalizado" onclick="impresion_pedido('+row.DT_RowId+')"><i class="fa fa-print" aria-hidden="true"></i></span><span class="btn btn-sm btn-personalizado" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
              }
            }*/
          ],
          initComplete: function () {
            var column1 = this.api().column(6);
            var select1 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
              .appendTo('#estado')
              .on('change', function () {
                var val1 = $(this).val();
                column1.search(val1).draw()
              });

            var offices1 = ['AUTORIZADO', 'RECIBIDO', 'RECHAZADO', 'ANULADO', 'SIN PROCESAR'];
            offices1.sort();
            offices1.forEach(function (d) {
              select1.append('<option value="' + d + '">' + d + '</option>');
            })
          }
        });
      },
      iComplete: function () {

        viewModelPedido.tableReportes.rows().eq(0).each(function (index) {
          if (index == 0) {
            var row = viewModelPedido.tableReportes.row(index);
            var data = row.data();

            viewModelPedido.tableReportes.columns(3).visible(data.tipo);
            viewModelPedido.tableReportes.columns(4).visible(data.tipo);
            viewModelPedido.tableReportes.columns(5).visible(data.tipo);
            viewModelPedido.tableReportes.columns(6).visible(data.tipo2);
            viewModelPedido.tableReportes.columns(7).visible(data.tipo2);
            viewModelPedido.tableReportes.columns(8).visible(data.tipo2);
          }

        });


      },
      table_reporte: function () {
        this.tableReportes = $('#tb_reporte').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging": true,
          "info": true,
          "deferRender": true,
          dom:
            "<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
          responsive: true,
          oLanguage: {
            sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
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
          },
          language: {
            emptyTable: "No hay Pedidos para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          ajax: {
            url: "documentos/php/back/listados/get_reporte.php",
            type: "POST",
            data: {
              fecha: function () { return $('#fecha_r').val() },
              tipo_filtro: function () { return $('#tipo_filtro').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },
          "aoColumns": [
            { "class": "text-center", mData: 'pedido_num' },
            { "class": "text-center", mData: 'fecha' },
            { "class": "text-center", mData: 'direccion' },
            { "class": "text-center", mData: 'fecha_r' },
            { "class": "text-center", mData: 'fecha_d' },
            { "class": "text-center", mData: 'motivo' },
            { "class": "text-center", mData: 'renglon' },
            { "class": "text-center", mData: 'insumos' },
            { "class": "text-center", mData: 'cantidad' },
            { "class": "text-center my-class", mData: 'estado' },
            { "class": "text-center", mData: 'observaciones' }
          ],
          buttons: [


            {
              text: 'Recargar <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                viewModelPedido.recargarReporte();
              }
            },
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-personalizado'
            }
          ],
          "columnDefs": [
            {
              responsivePriority: 0,
              targets: [4, 5, 6, 7]
            },
            { "width": "15px", "targets": 2 },
            { "width": "15px", "targets": 3 },

            /*{
              'targets': [3,4,5,6,7,8],
              'searchable':false,
              'orderable':false
            },*/
            /*{
              'targets': [0],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function(data, type, row, meta) {
                return  '<div class="btn-group"><span class="btn btn-sm btn-personalizado" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='+row.DT_RowId+'">'+row.pedido_num+'</span></div>';//'+row.DT_RowId+'
              }
            }*/
          ],
          "rowCallback": function (nRow, aData) {
            //alert(mData.tipo);
            $('td.my-class', nRow).css('background-color', aData.color);

            //viewModelPedido.tableReportes.columns(3,4,5).visible(aData.tipo);
            /*viewModelPedido.tableReportes.columns(4).visible(aData.tipo);
            viewModelPedido.tableReportes.columns(5).visible(aData.tipo);
            viewModelPedido.tableReportes.columns(6).visible(aData.tipo2);
            viewModelPedido.tableReportes.columns(7).visible(aData.tipo2);
            viewModelPedido.tableReportes.columns(8).visible(aData.tipo2);*/

          }
        });
      },
      table_facturas: function () {
        this.tableFacturas = $('#tb_facturas').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging": true,
          "info": true,
          "deferRender": true,
          select: true,
          scrollX: true,
          fixedColumns: true,
          fixedColumns: {
            //leftColumns: 1,
            rightColumns: 2
          },
          dom:
            //"<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-8 texte-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
          //responsive: true,
          oLanguage: {
            sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
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
          },
          language: {
            emptyTable: "No hay Pedidos para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          ajax: {
            url: "documentos/php/back/listados/get_facturas.php",
            type: "POST",
            data: {
              tipo: function () { return $('#id_filtro_factura').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },
          "aoColumns": [
            { "class": "text-center", mData: 'nro_orden' },
            { "class": "text-center", mData: 'tipo' },
            { "class": "text-center", mData: 'fecha_recepcion' },
            { "class": "text-center", mData: 'factura_fecha' },
            { "class": "text-center", mData: 'diferencia' },
            { "class": "text-center", mData: 'factura_serie' },
            { "class": "text-center", mData: 'factura_num' },
            { "class": "text-center", mData: 'proveedor' },
            { "class": "text-center", mData: 'factura_total' },
            { "class": "text-center", mData: 'ped_num' },
            { "class": "text-center", mData: 'nog' },
            { "class": "text-center", mData: 'cur' },
            { "class": "text-center", mData: 'cheque' },
            { "class": "text-center", mData: 'asignado' },
            { "class": "text-center", mData: 'accion' },
            { "class": "text-center", mData: 'DT_RowId' }
            /*,
            { "class" : "text-center", mData: 'motivo' },
            { "class" : "text-center", mData: 'renglon' },
            { "class" : "text-center", mData: 'insumos' },
            { "class" : "text-center", mData: 'cantidad' },
            { "class" : "text-center my-class", mData: 'estado' },
            { "class" : "text-center", mData: 'observaciones' }*/
          ],
          buttons: [
            {
              text: 'Agregar <i class="fa fa-plus"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                let imgModal = $('#modal-remoto');
                let imgModalBody = imgModal.find('.modal-content');
                //let id_persona = parseInt($('#bar_code').val());
                $.ajax({
                  type: "POST",
                  url: "documentos/php/front/factura/factura_nueva.php",
                  dataType: 'html',
                  beforeSend: function () {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                  },
                  success: function (data) {

                    imgModalBody.html(data);

                  }
                });
              }
            },
            {
              text: 'Operar <i class="fa fa-receipt"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                let imgModal = $('#modal-remoto');
                let imgModalBody = imgModal.find('.modal-content');
                //let id_persona = parseInt($('#bar_code').val());
                $.ajax({
                  type: "POST",
                  url: "documentos/php/front/factura/factura_operar.php",
                  dataType: 'html',
                  beforeSend: function () {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                  },
                  success: function (data) {

                    imgModalBody.html(data);

                  }
                });
              }
            },
            {
              text: 'Pendientes <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-f1 active',
              action: function (e, dt, node, config) {
                viewModelPedido.recargarFacturas(0,'btn-f1');
              }
            },
            {
              text: 'Deudas <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-f2',
              action: function (e, dt, node, config) {
                viewModelPedido.recargarFacturas(1,'btn-f2');
              }
            },
            {
              text: 'Liquidadas <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-f3',
              action: function (e, dt, node, config) {
                viewModelPedido.recargarFacturas(2,'btn-f3');
              }
            },
            {
              text: 'Anuladas <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-f4',
              action: function (e, dt, node, config) {
                viewModelPedido.recargarFacturas(3,'btn-f4');
              }
            },
            /*{
              text: 'Limpiar <i class="fa fa-eraser btn-f5"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, donfig) {
                //this.tableFacturas.fnClearTable('');
                viewModelPedido.tableFacturas.columns(2).search('2021');
                viewModelPedido.tableFacturas.draw();
              }
            },*/
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-personalizado'
            }
          ],


          "columnDefs": [
            {
              'targets': 15,
              'checkboxes': {
                'selectRow': true
              },
            },
            /*{
              responsivePriority: 0,
              targets: [4, 5, 6, 7, 14,15]
            },*/
            {
              targets: [2],
              //'visible':false
            },
            { "width": "15px", "targets": 2 },
            { "width": "15px", "targets": 3 },
            {
              'targets': [14],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function (data, type, row, meta) {
                return '<div class="btn-group"><span class="btn btn-sm btn-personalizado" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id=' + row.DT_RowId + '&ped_num=' + row.ped_num + '"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
              }
            }

          ],
          'select': {
            'style': 'multi'
          },
        });
      },
      // anular_pedido: function (ped_tra, ped_num, id_persona) {
      //   console.log(ped_tra, ped_num, id_persona);
      // },
      recargarTabla: function (tipo) {
        //alert('recargando');
        $('#id_tipo_filtro').val(tipo);
        //this.tipoC = tipo;
        //alert(this.tipoC);
        this.tablePedidos.ajax.reload(null, false);
      },
      recargarReporte: function () {
        this.tableReportes.ajax.reload(viewModelPedido.iComplete, false);

      },
      recargarFacturas: function (opc,classe) {
        $('#id_filtro_factura').val(opc);
        this.tableFacturas.ajax.reload(null, false);
        this.tableFacturas.cell(0, 0).data('New data').draw();
        switch (classe) {
          case 'btn-f1':
            $(".btn-f1").addClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").removeClass("active");
          break;
          case 'btn-f2':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").addClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").removeClass("active");
          break;
          case 'btn-f3':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").addClass("active");
            $(".btn-f4").removeClass("active");
          break;
          case 'btn-f4':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").addClass("active");
          break;

          default:

        }

      },
      filtarFacturas: function () {
        //startdate=picker.startDate.format('YYYY-MM-DD');
        //enddate=picker.endDate.format('YYYY-MM-DD');
        var startdate = $('#fecha_fac').val();
        var date = new Date(startdate);
        var d = date.getDate() + 1;
        var m = date.getMonth() + 1;
        //var mo = (m < 10)?'0'+m:m
        var y = date.getFullYear();
        var fulldate = '';
        if (m < 10) {
          if (d < 10) {
            fulldate = '0' + d + '-0' + m + '-' + y;
          } else {
            fulldate = d + '-0' + m + '-' + y;
          }

        } else {
          fulldate = d + '-' + m + '-' + y;
        }

        //alert(fulldate);
        this.tableFacturas.columns(2).search(fulldate);
        this.tableFacturas.draw();
      },
      get_unidades: function () {
        axios.get('documentos/php/back/listados/get_unidades', {
          params: {

          }
        }).then(function (response) {
          viewModelPedido.unidades = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },
      guardarPedidoNuevo: function () {
        var thisInstance = this;
        jQuery('.jsValidacionPedidoNuevo').validate({
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
            var insumos = viewModelPedido.insumos;

            if (viewModelPedido.insumos.length >= 1) {
              Swal.fire({
                title: '<strong>¿Desea generar el pedido?</strong>',
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
                    url: "documentos/php/back/pedido/crear_pedido.php",
                    dataType: 'json',
                    data: {
                      ped_num: $('#pedido_nro').val(),
                      fecha: $('#fecha_pedido').val(),
                      unidad: $('#id_unidad').val(),
                      pacName: $('#pacName').val(),
                      urgente: thisInstance.isUrgente,
                      observaciones: $('#id_observaciones').val(),
                      insumos: insumos
                    }, //f de fecha y u de estado.

                    beforeSend: function () {
                    },
                    success: function (data) {
                      //exportHTML(data.id);
                      //recargarDocumentos();
                      if (data.msg == 'OK') {
                        $('#pedido_nro').val('');
                        $('#fecha_pedido').val('');
                        $('#id_unidad').val('');
                        $('#id_observaciones').val('');
                        $('#id_observaciones').text('');
                        thisInstance.totalCharacter = 500;
                        thisInstance.messageCharacter = '';
                        //$('#pacName').val('');
                        $('.categoryName').val('');
                        $('.categoryName').val(null).trigger('change');

                        $('#pacName').val('');
                        //$('#id_renglon').empty();
                        thisInstance.isUrgente = false;
                        thisInstance.validarUrgente();
                        $('#pacName').val(null).trigger('change');

                        viewModelPedido.limpiar_lista();

                        viewModelPedido.recargarTabla();
                        Swal.fire({
                          type: 'success',
                          title: 'Pedido generado',
                          showConfirmButton: false,
                          timer: 1100
                        });
                        //impresion_pedido(data.id);
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
          rules: {
            combo1: { required: true },
            'pedido_nro': {
              remote: {
                url: 'documentos/php/back/pedido/validar_num_pedido.php',
                data: {
                  ped_num: function () { return $('#pedido_nro').val(); }
                }
              }
            }
          },
          messages: {
            'pedido_nro': {
              remote: "El número de pedido ya fue utilizado."
            }
          }

        });
      },
      filtrado: function () {
        $('.categoryName').select2({
          placeholder: 'Selecciona un insumo',
          ajax: {
            url: 'documentos/php/back/pedido/get_insumo_filtrado.php',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          }
        });
      },
      getPlanes: function() {
        $('.pacName').select2({
          placeholder: 'Selecciona un plan de compra',
          ajax: {
            url: 'documentos/php/back/pac/detalle/get_plan_filtrado.php',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          }
        });



      },
      limpiar_lista: function () {
        viewModelPedido.insumos.splice(0, viewModelPedido.insumos.length);
        viewModelPedido.totalLineas = 0;
        eventBusPE.$emit('recargarPorcentajeTotal',1);
      },
      deleteRow(index, d, lineas) {
        var idx = this.insumos.indexOf(d);

        console.log(idx, index);

        if (idx > -1) {
          eventBusPE.$emit('recargarPorcentajeTotal',1);
          viewModelPedido.totalLineas = viewModelPedido.totalLineas - lineas;
          viewModelPedido.insumos.splice(idx, 1);

        }
      },
      addNewRow() {

        if ($('#Ppr_id').val() != null) {
          axios.get('documentos/php/back/pedido/get_insumo_seleccionado', {
            params: {
              Ppr_id: $('#Ppr_id').val()
            }
          }).then(function (response) {

            if (viewModelPedido.insumos.find((item) => item.Ppr_id == response.data.Ppr_id)) {
              Swal.fire({
                type: 'error',
                title: 'El producto ya existe en la lista',
                showConfirmButton: false,
                timer: 1100
              });
            } else {
              viewModelPedido.totalLineas += parseInt(response.data.lineas);
              if(viewModelPedido.totalLineas > 108){
                viewModelPedido.totalLineas = viewModelPedido.totalLineas - parseInt(response.data.lineas);
                eventBusPE.$emit('recargarPorcentajeTotal',1);
                Swal.fire({
                  type: 'error',
                  title: 'El insumo que desea agregar no se agregó porque supera el espacio en el formulario electrónico',
                  showConfirmButton: true,
                  //timer: 1100
                });
              }else{

                $('.categoryName').val('');
                $('.categoryName').val(null).trigger('change');
                //viewModelPedido.insumos = response.data;
                eventBusPE.$emit('recargarPorcentajeTotal',1);
                viewModelPedido.insumos.push({
                  Ppr_id: response.data.Ppr_id,
                  Ppr_can: '',
                  Ppr_cod: response.data.Ppr_cod,
                  Ppr_codPre: response.data.Ppr_codPre,
                  Ppr_Nom: response.data.Ppr_Nom,
                  Ppr_Des: response.data.Ppr_Des,
                  Ppr_Pres: response.data.Ppr_Pres,
                  Ppr_Ren: response.data.Ppr_Ren,
                  Ppr_Med: response.data.Ppr_Med,
                  Ppr_lineas: response.data.lineas,
                })
              }
            }


          }).catch(function (error) {
            console.log(error);
          });
        } else {
          Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un insumo',
            showConfirmButton: false,
            timer: 1100
          });
        }


      },
      mostrar_menu: function (ped_tra, tipo) {
        $.ajax({
          type: "POST",
          url: "documentos/php/back/pedido/menu_impresion.php",
          data: {
            ped_tra,
            tipo

          }, //f de fecha y u de estado.

          beforeSend: function () {
            $('#menu' + ped_tra + tipo).html('Cargando');
          },
          success: function (data) {

            $('#menu' + ped_tra + tipo).html(data);
          }
        }).done(function () {


        }).fail(function (jqXHR, textSttus, errorThrown) {

          alert(errorThrown);

        });
      },
      validarPac: function(){
        console.log(this.pacActivo);
        if(this.pacActivo == false){
          this.pac_id = 0;
          this.msgPac = 'NO';
          $('#pacName').val('');
          //$('#id_renglon').empty();
          $('#pacName').val(null).trigger('change');
        }else{
          this.msgPac = 'SI';
        }
      },
      validarUrgente: function(){
        if(this.isUrgente == false){

          this.msgUrgente = 'NO';

        }else{
          this.msgUrgente = 'SI';
        }
      },
      pacSeleccinado: function(event){
        //alert('message');
        //alert(event.currentTarget.value);
      }

    }
  })


  instancia.filtrado();
  instancia.getPlanes();
  instancia.table_pedidos();
  instancia.table_reporte();
  //instancia.table_facturas();

  function valorSeleccionado(){
    alert($('#pacName').val())
  };

  $('#pacName').on("change",function(e){

    //alert($('#pacName').val());
    instancia.pac_id = $('#pacName').val();
    eventBusPACD.$emit('recargarPlan',$('#pacName').val());
  });


  /*$('#pacName').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data.id);
    instancia.pac_id = data.id;
    eventBusPACD.$emit('recargarPlan');

  });*/

  $('#modal-remoto-lgg2').on('click', '.salida', function () {
    var cambio = $('#id_cambio').val();
    //alert(cambio)
    if (cambio == 1) {
      instancia.recargarTabla();
    }

    $('#modal-remoto-lgg2').modal('hide');
  });

  /*$('#modal-remoto').on('click', '.salida', function () {
    var cambio = $('#cambio').val();
    //alert(cambio)
    if(cambio == 1){
      instancia.recargarFacturas();
      $('#modal-remoto').modal('hide');
    }


  });*/
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable
      .tables({ visible: true, api: true })
      .columns.adjust()
      .fixedColumns().relayout();
  });

  /*$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable.tables({ visible: true, api: true })
      .responsive.recalc()
      .columns.adjust();
  });*/

})

  var dropdownMenu;

function get_menu_impresion(ped_tra, tipo) {

  $(window).on('shown.bs.dropdown', function(e) {
console.log('oko')
     // grab the menu
     dropdownMenu = $(e.target).find('.dropdown-menu');


     // detach it and append it to the body
     $('body').append(dropdownMenu.detach());

     // grab the new offset position
     var eOffset = $(e.target).offset();

     // make sure to place it where it would normally go (this could be improved)
     dropdownMenu.css({
       //'display': 'block',
       'top': eOffset.top + $(e.target).outerHeight(),
       'left': eOffset.left - 50
     });
     instancia.mostrar_menu(ped_tra, tipo);
   });

   // and when you hide it, reattach the drop down, and hide it normally
   $(window).on('hide.bs.dropdown', function(e) {
     $(e.target).append(dropdownMenu.detach());
     dropdownMenu.hide();
   });
}

function anular_pedido(ped_tra, ped_num, id_persona) {
  // console.log(ped_tra, ped_num, id_persona);
  Swal.fire({
    title: '<strong>¿Desea anular el pedido?</strong>',
    text: "",
    type: 'question',
    input: 'text',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Anular!',
    inputPlaceholder: 'Agrege una observación'
  }).then((result) => {
    if (result.value == '' || result.value) {
      $.ajax({
        type: "POST",
        url: "documentos/php/back/pedido/anular_pedido.php",
        data: { ped_tra: ped_tra, ped_num: ped_num, id_persona: id_persona, obs: result.value },
        success: function (data) {
          let jason = JSON.parse(data);
          if (jason.status == "201") {
            Swal.fire({
              type: 'success',
              title: 'Pedido anulado',
              showConfirmButton: false,
              timer: 1100,
            });
          } else {
            Swal.fire({
              type: 'error',
              title: jason.msg,
              showConfirmButton: false,
              timer: 1100
            });
          }
          instancia.recargarTabla();
        }

      }).fail(function (jqXHR, textSttus, errorThrown) {
        alert(errorThrown);
      });
    }
  });
}
