var instancia = '';

$(document).ready(function () {

  viewModel1H = new Vue({
    //el: '#app_pedidos',
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
        Ppr_Med: ""
      }],
      unidades: "",
      reporte: "",
      tableReportes: "",
      tipoC: 0
    },
    mounted() {
      instancia = this;
    },
    computed: {},
    created: function () {

      this.get_unidades();
      this.insumos.splice(0, 1);

    },
    methods: {
      get_pedidos: function () {
        axios.get('documentos/php/back/listados/get_pedidos', {
          params: {
            tipo: 1
          }
        }).then(function (response) {
          viewModel1H.pedidos = response.data;
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
          viewModel1H.insumos_list = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },
      opcionPedido: function (opc) {
        this.opcion_pedido = opc;
      },
      table1H: function () {
        this.tablePedidos = $('#tb_formularios1h').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging": true,
          "info": true,
          "deferRender": true,
          dom:
            "<'row'<'col-sm-4'l><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
          responsive: true,
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
            url: "documentos/php/back/listados/get_formularios1h.php",
            type: "POST",
            data: {
              tipo: function () { return $('#id_tipo_filtro').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },
          "aoColumns": [
            //{ "class": "text-center", mData: 'Ent_id' },
            { "class": "text-center", mData: 'Fh_nro' },
            { "class": "text-center", mData: 'Fh_fec' },
            { "class": "text-center", mData: 'Env_tra' },
            { "class": "text-center", mData: 'Env_num' },
            { "class": "text-center", mData: 'Ser_ser' },
            { "class": "text-center", mData: 'Env_fec' },
            { "class": "text-center", mData: 'Prov_id' },
            { "class": "text-center", mData: 'Prov_nom' },
            //{ "class": "text-center", mData: 'Bod_id' },
            { "class": "text-center", mData: 'Env_tot' },
            { "class": "text-center", mData: 'Bod_nom' },

            //{ "class": "text-center", mData: 'Fh_ser' },

            { "class": "text-center", mData: 'accion' }

          ],
          buttons: [
            /*{
              text: 'Nuevos <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                viewModel1H.recargarTabla(2);
              }
            },
            {
              text: 'En proceso <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                viewModel1H.recargarTabla(3);
              }
            },
            {
              text: 'Autorizados <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                viewModel1H.recargarTabla(4);
              }
            },
            {
              text: 'Anulados <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                viewModel1H.recargarTabla(5);
              }
            },*/
            {
              text: 'Todos <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function (e, dt, node, config) {
                viewModel1H.recargarTabla(0);
              }
            },
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-soft-info'
            }
          ],
          "columnDefs": [
            {
              responsivePriority: 0,
              targets: [6, 10]
            },
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
                //return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="impresion_pedido('+row.DT_RowId+')"><i class="fa fa-print" aria-hidden="true"></i></span><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
              }
            }*/
          ],
          initComplete: function () {
            var column1 = this.api().column(6);
            var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
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

        viewModel1H.tableReportes.rows().eq(0).each(function (index) {
          if (index == 0) {
            var row = viewModel1H.tableReportes.row(index);
            var data = row.data();

            viewModel1H.tableReportes.columns(3).visible(data.tipo);
            viewModel1H.tableReportes.columns(4).visible(data.tipo);
            viewModel1H.tableReportes.columns(5).visible(data.tipo);
            viewModel1H.tableReportes.columns(6).visible(data.tipo2);
            viewModel1H.tableReportes.columns(7).visible(data.tipo2);
            viewModel1H.tableReportes.columns(8).visible(data.tipo2);
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
              className: 'btn btn-soft-info btn-sm',
              action: function (e, dt, node, config) {
                viewModel1H.recargarReporte();
              }
            },
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-soft-info'
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
                return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='+row.DT_RowId+'">'+row.pedido_num+'</span></div>';//'+row.DT_RowId+'
              }
            }*/
          ],
          "rowCallback": function (nRow, aData) {
            //alert(mData.tipo);
            $('td.my-class', nRow).css('background-color', aData.color);

            //viewModel1H.tableReportes.columns(3,4,5).visible(aData.tipo);
            /*viewModel1H.tableReportes.columns(4).visible(aData.tipo);
            viewModel1H.tableReportes.columns(5).visible(aData.tipo);
            viewModel1H.tableReportes.columns(6).visible(aData.tipo2);
            viewModel1H.tableReportes.columns(7).visible(aData.tipo2);
            viewModel1H.tableReportes.columns(8).visible(aData.tipo2);*/

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
            url: "documentos/php/back/listados/get_facturas.php",
            type: "POST",
            data: {

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
            { "class": "text-center", mData: 'factura_serie' },
            { "class": "text-center", mData: 'factura_num' },
            { "class": "text-center", mData: 'proveedor' },
            { "class": "text-center", mData: 'factura_total' },
            { "class": "text-center", mData: 'ped_num' },
            { "class": "text-center", mData: 'nog' },
            { "class": "text-center", mData: 'cur' },
            { "class": "text-center", mData: 'cheque' },
            { "class": "text-center", mData: 'asignado' },
            { "class": "text-center", mData: 'accion' }
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
              className: 'btn btn-soft-info btn-sm',
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
              text: 'Recargar <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function (e, dt, node, config) {
                viewModel1H.recargarFacturas();
              }
            },
            {
              text: 'Limpiar <i class="fa fa-eraser"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function (e, dt, node, donfig) {
                //this.tableFacturas.fnClearTable('');
                viewModel1H.tableFacturas.columns(2).search('2021');
                viewModel1H.tableFacturas.draw();
              }
            },
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-soft-info'
            }
          ],
          "columnDefs": [
            {
              responsivePriority: 0,
              targets: [4, 5, 6, 7, 13]
            },
            {
              targets: [2],
              //'visible':false
            },
            { "width": "15px", "targets": 2 },
            { "width": "15px", "targets": 3 },
            {
              'targets': [13],
              'searchable': false,
              'orderable': false,
              'className': 'dt-body-center',
              render: function (data, type, row, meta) {
                return '<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id=' + row.DT_RowId + '&ped_num=' + row.ped_num + '"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
              }
            }

          ]
        });
      },
      recargarTabla: function (tipo) {
        //alert('recargando');
        $('#id_tipo_filtro').val(tipo);
        //this.tipoC = tipo;
        //alert(this.tipoC);
        this.tablePedidos.ajax.reload(null, false);
      },
      recargarReporte: function () {
        this.tableReportes.ajax.reload(viewModel1H.iComplete, false);
      },
      recargarFacturas: function () {
        this.tableFacturas.ajax.reload(null, false);
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
          viewModel1H.unidades = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },
      guardarPedidoNuevo: function () {
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
            var insumos = viewModel1H.insumos;

            if (viewModel1H.insumos.length >= 1) {
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

                        viewModel1H.limpiar_lista();

                        viewModel1H.recargarTabla();
                        Swal.fire({
                          type: 'success',
                          title: 'Pedido generado',
                          showConfirmButton: false,
                          timer: 1100
                        });
                        impresion_pedido(data.id);
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
      limpiar_lista: function () {
        viewModel1H.insumos.splice(0, viewModel1H.insumos.length);
      },
      deleteRow(index, d) {
        var idx = this.insumos.indexOf(d);
        console.log(idx, index);
        if (idx > -1) {
          viewModel1H.insumos.splice(idx, 1);
        }
      },
      addNewRow() {

        if ($('#Ppr_id').val() != null) {
          axios.get('documentos/php/back/pedido/get_insumo_seleccionado', {
            params: {
              Ppr_id: $('#Ppr_id').val()
            }
          }).then(function (response) {

            if (viewModel1H.insumos.find((item) => item.Ppr_id == response.data.Ppr_id)) {
              Swal.fire({
                type: 'error',
                title: 'El producto ya existe en la lista',
                showConfirmButton: false,
                timer: 1100
              });
            } else {
              //viewModel1H.insumos = response.data;
              viewModel1H.insumos.push({
                Ppr_id: response.data.Ppr_id,
                Ppr_can: '',
                Ppr_cod: response.data.Ppr_cod,
                Ppr_codPre: response.data.Ppr_codPre,
                Ppr_Nom: response.data.Ppr_Nom,
                Ppr_Des: response.data.Ppr_Des,
                Ppr_Pres: response.data.Ppr_Pres,
                Ppr_Ren: response.data.Ppr_Ren,
                Ppr_Med: response.data.Ppr_Med
              })
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
      }

    }
  })

  viewModel1H.$mount('#app_1h');
  instancia.table1H();


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
    $.fn.dataTable.tables({ visible: true, api: true })
      .responsive.recalc()
      .columns.adjust();
  });

})

function get_menu_impresion(ped_tra, tipo) {
  instancia.mostrar_menu(ped_tra, tipo);
}
