var instanciaF = '';
var eventBusPACD = new Vue();

$(document).ready(function () {
  viewModelFactura = new Vue({
    el: '#app_facturas',
    data: {
      tableFacturas: "",
      privilegio:"",
      buttons:[],
      buttonsO:[],
      tipoBajaCuantia:0,
      tipoNog:0,
      uniqs:[],
      tableOrdenes:"",
      scrollX:0,
      ancho:0,
      tipoFiltroFa:0,
      chartFactura:'',
      dataChart:[]

    },
    mounted() {
      instanciaF = this;
    },
    computed: {

    },
    created: function () {
      this.showButtons();
      this.showButtonsO();
    },
    methods: {
      getPermisosUserF: function(data){
        this.privilegio = data;
        console.log('privi :'+this.privilegio);
      },
      showButtons: function(){
        setTimeout(() => {
          let width=this.$refs.infoBox.clientWidth;
          let height=this.$refs.infoBox.clientHeight;
          this.ancho = width;
          console.log(this.ancho);

        }, 100);
        setTimeout(() => {
          //inicio
          if(this.privilegio.compras_recepcion == true){
            this.buttons.push({
              text: 'Agregar <i class="fa fa-plus"></i>',
              className: 'btn btn-personalizado btn-sm btn-fact',
              action: function (e, dt, node, config) {
                var imgModal = $('#modal-remoto');
                var imgModalBody = imgModal.find('.modal-content');
                //let id_persona = parseInt($('#bar_code').val());
                $.ajax({
                  type: "GET",
                  url: "documentos/php/front/factura/factura_nueva.php",
                  dataType: 'html',
                  data:{
                    tipo:1,
                    arreglo:'',
                    tipoOpe:''
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
            });
          }
          this.buttons.push({
            text: 'Pendientes <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-fact btn-f1 active',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(0,'btn-f1');
            }
          })
          this.buttons.push({
            text: 'Publicadas <i class="fa fa-upload"></i>',
            className: 'btn btn-personalizado btn-sm btn-fact btn-f5 ',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(4,'btn-f5');
            }
          })
          this.buttons.push({
            text: 'Compromiso <i class="fa fa-handshake"></i>',
            className: 'btn btn-personalizado btn-sm btn-fact btn-f2',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(1,'btn-f2');
            }
          })
          this.buttons.push({
            text: 'Devengado <i class="fa fa-hand-holding-usd"></i>',
            className: 'btn btn-personalizado btn-sm btn-fact btn-f3',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(2,'btn-f3');
            }
          })
          this.buttons.push({
            text: 'Anuladas <i class="fa fa-times-circle"></i>',
            className: 'btn btn-personalizado btn-sm btn-fact btn-f4',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(3,'btn-f4');
            }
          })

          if(this.privilegio.compras_tecnico == true){
            this.buttons.push({
              text: 'Clase proceso <i class="fa fa-edit"></i>',
              className: 'btn btn-personalizado btn-sm btn-fact',
              action: function (e, dt, node, config) {
                instanciaF.cargarInput(2);
              }
            })
          }
          if(this.privilegio.compras_asignar_tecnico == true){
            this.buttons.push({
              text: 'Asignar Técnico <i class="fa fa-user-plus"></i>',
              className: 'btn btn-personalizado btn-sm btn-fact',
              action: function (e, dt, node, config) {
                instanciaF.cargarInput(3);
              }
            })
          }
          this.buttons.push(
            {
              extend: 'excel',
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-personalizado'
            }
          )
          //fin
        }, 100);
      },
      showButtonsO: function(){
        this.buttonsO.push({
          text: 'Pendientes <i class="fa fa-sync"></i>',
          className: 'btn btn-personalizado btn-sm btn-fo1 active',
          action: function (e, dt, node, config) {
            instanciaF.recargarOrdenes(0,'btn-fo1');
          }
        })
        this.buttonsO.push({
          text: 'Compromiso <i class="fa fa-sync"></i>',
          className: 'btn btn-personalizado btn-sm btn-fo2 ',
          action: function (e, dt, node, config) {
            instanciaF.recargarOrdenes(1,'btn-fo2');
          }
        })
        this.buttonsO.push({
          text: 'Devengado <i class="fa fa-sync"></i>',
          className: 'btn btn-personalizado btn-sm btn-fo3',
          action: function (e, dt, node, config) {
            instanciaF.recargarOrdenes(2,'btn-fo3');
          }
        })
      },
      iComplete: function () {
        console.log('trabajando...');
        viewModelFactura.tableFacturas.cells(
          viewModelFactura.tableFacturas.rows(function (idx, data, node) {
            //alert(idx);
            var validacion = (data.factura_publicada === 2) ? true : false;
            console.log('Recargando: '+validacion);
            return validacion;
            //console.log(data.factura_publicada + '| |'+data.orden_compra_id);

          }).indexes(), 0).checkboxes.disable();
      },
      table_facturas: function () {
        setTimeout(() => {
          //inicio
          this.tableFacturas = $('#tb_facturas').DataTable({
            'initComplete': function (settings, json) {
              //var api = this.tableFacturas.api();
              //alert('message1');
              //var api = new $.fn.dataTable.Api(settings);
              //alert('cargando');
              viewModelFactura.iComplete();
            },
            "ordering": false,
            "pageLength": 25,
            "bProcessing": true,
            scrollY: '50vh',
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
              leftColumns: 2
            },
            dom:
              //"<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
              "<'row'<'col-sm-12 text-left'B><'col-sm-12 text-center'f>>" +
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
              //{ "class": "text-center", mData: 'tipo_pago' },
              { "class": "text-center", mData: 'orden_id' },
              { "class": "text-center", mData: 'diferencia' },
              { "class": "text-center", mData: 'tipo_pago' },
              { "class": "text-center", mData: 'fecha_recepcion' },
              { "class": "text-center", mData: 'factura_fecha' },

              { "class": "text-center", mData: 'factura_serie' },
              { "class": "text-center", mData: 'factura_num' },
              { "class": "text-center", mData: 'proveedor' },
              { "class": "text-right", mData: 'factura_total' },
              { "class": "text-center", mData: 'ped_num' },
              { "class": "text-center", mData: 'id_renglon' },
              { "class": "text-center", mData: 'direccion' },
              { "class": "text-center", mData: 'clase_proceso' },
              { "class": "text-center", mData: 'nro_orden' },
              { "class": "text-center", mData: 'cyd' },

              { "class": "text-center", mData: 'nog' },
              { "class": "text-center", mData: 'npg' },
              { "class": "text-center", mData: 'cur_c' },
              { "class": "text-center", mData: 'cur_d' },
              { "class": "text-center", mData: 'cheque' },
              { "class": "text-center", mData: 'asignado' },
              { "class": "text-center", mData: 'accion' },

            ],
            buttons: instanciaF.buttons,
            "columnDefs": [
              {
                'targets': 0,
                'checkboxes': {
                  'selectRow': true
                },
              },
              { "width": "15px", "targets": 2 },
              { "width": "25%", "targets": 4 },
              {
                'targets': [21],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function (data, type, row, meta) {
                  var buttons = '';
                  buttons += '<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id=' + row.DT_RowId + '&ped_num=' + row.ped_numero + '"><i class="fa fa-pencil-alt"></i></span>';
                  /*if(row.factura_publicada == 0){
                    buttons += '<span class="btn btn-sm btn-soft-info" data-toggle="modal" onclick="publicarFactura('+ row.DT_RowId +')"><i class="fa fa-check-circle"></i></span>';
                  }*/
                  buttons += '</div>';//'+row.DT_RowId+'

                  return buttons;
                }
              }
            ],
            'select': {
              'style': 'multi'
            },
            render: function (data, type, row) {
              $(row).addClass('danger');
              console.log(row);
            },
            drawCallback: function () {

               },
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              if(aData.days <= 2){
                $(nRow).addClass('warning');
              }

              //instanciaF.tableFacturas.columns(18).visible(false);
              //console.log(aData.factura_publicada);
              /*viewModelFactura.tableFacturas.cells(
                viewModelFactura.tableFacturas.rows(function (idx, data, node) {
                  //alert(idx);
                  return (data.factura_publicada == 0 || data.factura_publicada == 2) ? true : false;
                  //alert(data.bln_confirma)
                }).indexes(), 18).checkboxes.disable();*/
              /*if (aData.factura_publicada == 0) {
                instanciaF.tableFacturas.columns(18).visible(false);
              }/*else{
                instanciaF.tableFacturas.columns(18).visible(true);
              }*/

            }

          });
          //fin
          $('#tb_facturas tbody').on('click', 'td label input', function () {
            //alert('alkdjflkajdsf')
            var array = [];
            var tr = $(this).parents('tr');
            var row = instanciaF.tableFacturas.row(tr);
            var d = row.data();
            var uniques;
            if ($(this).is(':checked')) {
              //alert('chequeado');
              if (d.tipo == 1) {
                instanciaF.tipoBajaCuantia += 1;
              } else {
                instanciaF.tipoNog += 1;
              }
              //app_vue_ped.insumos = response.data;
              instanciaF.uniqs.push({
                orden_id: d.orden_id,
                tipo: d.tipo
              })
            }
            else {
              if (d.tipo == 1) {
                instanciaF.tipoBajaCuantia -= 1;
              } else {
                instanciaF.tipoNog -= 1;
              }
              var idx = instanciaF.uniqs.indexOf(d.orden_id);
              //if(viewModelViaticoDetalle.uniqs.length > ){
              instanciaF.uniqs.splice(idx, 1);
              //}
            }

          });

          $('#tb_facturas thead').on('click', 'th label input', function () {

            //$('#myTable').on('click', '.toggle-all', function (e) {
            instanciaF.tipoBajaCuantia = 0;
            instanciaF.tipoNog = 0;
            var rows_selected = instanciaF.tableFacturas.column(0).checkboxes.selected();
            if ($(this).is(':checked')) {
              $.each(instanciaF.tableFacturas.$('input[type=checkbox]:checked'), function () {
                var d = instanciaF.tableFacturas.row($(this).parents('tr')).data();
                //alert('chequeado')
                if (d.tipo == 1) {
                  instanciaF.tipoBajaCuantia += 1;
                } else {
                  instanciaF.tipoNog += 1;
                }
              });
            }
            else {
              instanciaF.tipoBajaCuantia = 0;
              instanciaF.tipoNog = 0;
            }
          });
        }, 150);


      },
      table_ordenes: function () {
        setTimeout(() => {
          //inicio
          this.tableOrdenes = $('#tb_ordenes').DataTable({
            "ordering": false,
            "pageLength": 25,
            "bProcessing": true,
            scrollY: '50vh',
            scrollCollapse: true,
            //paging: false,
            select:true,
            "paging": true,
            "info": true,
            "deferRender": true,
            select:true,
            /*scrollX: true,
            fixedColumns: true,
            fixedColumns: {
              rightColumns: 2
            },*/
            dom:
              //"<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
              "<'row'<'col-sm-12 text-center'B><'col-sm-12 text-center'f>>" +
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
              url: "documentos/php/back/factura/listados/get_ordenes_group.php",
              type: "POST",
              data: {
                tipo: function () { return $('#id_filtro_orden').val() }
              },
              error: function () {
                $("#post_list_processing").css("display", "none");
              }
            },
            "aoColumns": [
              { "class": "text-center", mData: 'clase_proceso' },
              { "class": "text-center", mData: 'nro_orden' },
              { "class": "text-center", mData: 'cyd' },
              { "class": "text-center", mData: 'cur_c' },
              { "class": "text-center", mData: 'cur_d' },
              { "class": "text-right", mData: 'total' },
              { "class": "text-center", mData: 'accion' },

            ],
            buttons: instanciaF.buttonsO,
            "columnDefs": [

            ],
            'select': {
              'style': 'multi'
            },
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              //console.log(aData.factura_publicada);
              /*if (aData.factura_publicada == 0) {
                instanciaF.tableFacturas.columns(18).visible(false);
              }/*else{
                instanciaF.tableFacturas.columns(18).visible(true);
              }*/

            }

          });
          //fin
        }, 150);


      },
      cargarInput: function (tipo) {
        var rows_selected = instanciaF.tableFacturas.column(0).checkboxes.selected();
        var filtro = $('#id_filtro_factura').val();

        var codigos = '';
        var renglones = '';

        if (rows_selected.length == 0) {
          //mostrar mensaje
          jsonTableData = '';
          Swal.fire(
            'Atención!',
            "Debe seleccionar al menos una factura",
            'error'
          );
        } else if (rows_selected.length > 0) {
          var x = [];
          $.each(instanciaF.tableFacturas.$('input[type=checkbox]:checked'), function () {
            var data = instanciaF.tableFacturas.row($(this).parents('tr')).data();
            codigos += ',' + data['DT_RowId'];
            //renglones += data['reng_num'] + ',';
          /*  $('#id_persona').val(codigos);
            $('#id_renglon').val(renglones);*/
            x.push(data['tipo']);
            console.log(codigos);

          });

          if(instanciaF.tipoFiltroFa == 0 && tipo == 3 || instanciaF.tipoFiltroFa == 4 && tipo == 3){
            //let id_persona = parseInt($('#bar_code').val());
            var imgModal = $('#modal-remoto-lg');
            var imgModalBody = imgModal.find('.modal-content');
            $.ajax({
              type: "GET",
              url: "documentos/php/front/factura/asignar_tecnico_global.php",
              data:{
                tipo:tipo,
                arreglo: codigos,
              },
              dataType: 'html',
              beforeSend: function () {
                imgModal.modal('show');
                imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
              },
              success: function (data) {
                imgModalBody.html(data);
              }
            });
          }else if(instanciaF.tipoFiltroFa == 4 && tipo == 2){
            var imgModal = $('#modal-remoto');
            var imgModalBody = imgModal.find('.modal-content');
            if(instanciaF.tipoBajaCuantia > 0 && instanciaF.tipoNog > 0){
              Swal.fire(
                'Atención!',
                "No puede seleccionar facturas con NOG y Baja Cuantía al mismo tiempo",
                'error'
              );
            }else{

              //let id_persona = parseInt($('#bar_code').val());
              $.ajax({
                type: "GET",
                url: "documentos/php/front/factura/factura_operar.php",
                data:{
                  tipo:tipo,
                  arreglo: codigos,
                  tipoOpe: (instanciaF.tipoBajaCuantia > 0) ? 1 : 2
                },
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

          }else{
            Swal.fire(
              'Atención!',
              "Esta opción no está disponible en esta fase",
              'error'
            );
          }




          /*if (tipo == 1) {
            viewModelViaticoDetalle.estado_viatico();
            viewModelViaticoDetalle.viatico_by_id();
            viewModelViaticoDetalle.getOpcion(3);
            viewModelViaticoDetalle.empleados_para_procesar();
          } else if (tipo == 2) {
            viewModelViaticoDetalle.estado_viatico();
            viewModelViaticoDetalle.viatico_by_id();
            viewModelViaticoDetalle.getOpcion(4);
            viewModelViaticoDetalle.empleados_para_procesar();
          }*/
        }
      },
      publicarFacturaVue: function(orden_id,nog){
        if(nog > 0){
          Swal.fire({
            title: '<strong>¿Desea publicar la factura?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Publicar!'
            }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              this.procesarPublicacion(orden_id,npg);
            }
          })
        }else{
          //inicio npg
          var npg = '';
          Swal.fire({
            title: '<strong>¿Desea publicar la Factura ?</strong>',
            text: "Digite el NPG (Número de publicación de Guatecompras).",
            type: 'question',
            input: 'text',
            inputPlaceholder: 'NPG',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Publicar!',
            inputValidator: function (inputValue) {
              return new Promise(function (resolve, reject) {
                if (inputValue && inputValue.length > 0) {
                  resolve();
                  npg = inputValue;

                } else {
                  Swal.fire({
                    type: 'error',
                    title: 'Necesita ingregar el NPG',
                    showConfirmButton: true
                  });

                }
              });
            }
          }).then((result) => {
            if (result.value) {
              this.procesarPublicacion(orden_id,npg);
            }
          })
          //fin npg
        }
      },
      procesarPublicacion: function(orden_id, npg){
        $.ajax({
          type: "POST",
          url: "documentos/php/back/factura/action/publicar_factura.php",
          dataType: 'json',
          data:{
            orden_id: orden_id,
            npg:npg
          },
          beforeSend:function(){
          },
          success:function(data){
            //exportHTML(data.id);
            //recargarDocumentos();
            if(data.msg == 'OK'){
              $('#cambio').val('1');
              instanciaF.tipoBajaCuantia = 0;
              instanciaF.tipoNog = 0;
              instanciaF.recargarFacturas(0,'btn-f1');

              Swal.fire({
                type: 'success',
                title: 'Factura publicada',
                showConfirmButton: false,
                timer: 1100
              });

            }else{
              Swal.fire({
                type: 'error',
                title: 'Error',
                showConfirmButton: false,
                timer: 1100
              });
            }
          }
        }).done( function() {
        }).fail( function( jqXHR, textSttus, errorThrown){
          alert(errorThrown);
        });
      },


      recargarFacturas: function (opc,classe) {
        console.log(opc);
        $('#id_filtro_factura').val(opc);
        this.tipoFiltroFa = $('#id_filtro_factura').val();
        console.log($('#id_filtro_factura').val());
        //instanciaF.tableFacturas.destroy();
        //$('#chk-header').click();
        //document.querySelector('input[type=checkbox]').click();
        instanciaF.tableFacturas.ajax.reload(viewModelFactura.iComplete, false);

        //this.tableFacturas.ajax.reload(null, false);

        //this.tableFacturas.rows.add(data).draw(false);
        //this.tableFacturas.cell( 0, 0 ).data( 'New data' ).draw();
        //viewModelFactura.tableFacturas.fixedColumns().update();
        //this.tableFacturas.cell(0, 0).data('New data').draw();
        //const column = this.tableFacturas.column( 2 ); // gets 2nd column (0-indexed)

        $(".btn-fact").removeClass("active");
        $("."+classe).addClass("active");
        instanciaF.tipoBajaCuantia = 0;
        instanciaF.tipoNog = 0;

        //console.log(classe);
      },
      recargarOrdenes: function (opc,classe) {
        $('#id_filtro_orden').val(opc);
        this.tableOrdenes.ajax.reload(null, false);
        //this.tableOrdenes.cell(0, 0).data('New data').draw();
        switch (classe) {
          case 'btn-fo1':
            $(".btn-fo1").addClass("active");
            $(".btn-fo2").removeClass("active");
            $(".btn-fo3").removeClass("active");
          break;
          case 'btn-fo2':
            $(".btn-fo1").removeClass("active");
            $(".btn-fo2").addClass("active");
            $(".btn-fo3").removeClass("active");
          break;
          case 'btn-fo3':
            $(".btn-fo1").removeClass("active");
            $(".btn-fo2").removeClass("active");
            $(".btn-fo3").addClass("active");
          break;
          default:
        }

      },
      move: function(side){
        var container = document.getElementById('tb_facturas');
        if(side == 'left'){
          if(this.scrollX > 0){
            this.scrollX -=100;
          }
          $('div.dataTables_scrollBody').scrollLeft(this.scrollX);
        }else{
          if(this.scrollX < this.ancho){
            this.scrollX +=100;
          }
          $('div.dataTables_scrollBody').scrollLeft(this.scrollX);

        }
        //alert(side);
      },
      getGrafica: function(){
        axios.get('documentos/php/back/graficas/get_chart_facturas', {
          params: {
          }
        }).then(function (response) {

          this.dataChart = response.data.dias;
          console.log(this.dataChart);
          setTimeout(() => {
            //inicio amchart
            am4core.ready(function() {

              // Themes begin
              am4core.useTheme(am4themes_animated);
              // Themes end

              // Create chart instance
              instanciaF.chartFactura = am4core.create("chart_facturas", am4charts.PieChart);
              // Add data
              instanciaF.chartFactura.data = this.dataChart;//[{"cantidad":6,"series":"1 d\u00edas"},{"cantidad":41,"series":"2 dias"},{"cantidad":5,"series":"3dias"},{"cantidad":3,"series":"4 dias"},{"cantidad":4,"series":"5 dias"}];
              // Add and configure Series
              let pieSeries = instanciaF.chartFactura.series.push(new am4charts.PieSeries());
              console.log(pieSeries);
              pieSeries.dataFields.value = 'cantidad';
              pieSeries.dataFields.category = 'series';
              instanciaF.chartFactura.logo.height = -15000;
              // Let's cut a hole in our Pie chart the size of 30% the radius
              instanciaF.chartFactura.innerRadius = am4core.percent(30);

              // Put a thick white border around each Slice
              pieSeries.slices.template.stroke = am4core.color("#fff");
              pieSeries.slices.template.strokeWidth = 2;
              pieSeries.slices.template.strokeOpacity = 1;
              pieSeries.slices.template.propertyFields.fill = "color";
              pieSeries.slices.template
                // change the cursor on hover to make it apparent the object can be interacted with
                .cursorOverStyle = [
                  {
                    "property": "cursor",
                    "value": "pointer"
                  }
                ];

              pieSeries.alignLabels = false;
              pieSeries.labels.template.wrap = true;
              pieSeries.labels.template.maxWidth = 80;
              pieSeries.labels.template.text = "{series}: {value}.";//"{value.percent.formatNumber('#.0')}%";
              /*pieSeries.labels.template.bent = true;
              pieSeries.labels.template.radius = 3;
              pieSeries.labels.template.padding(0,0,0,0);*/

              //pieSeries.ticks.template.disabled = true;

              // Create a base filter effect (as if it's not there) for the hover to return to
              let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
              shadow.opacity = 0;

              // Create hover state
              let hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

              // Slightly shift the shadow and make it more prominent on hover
              let hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
              hoverShadow.opacity = 0.7;
              hoverShadow.blur = 5;

              // Add a legend
              instanciaF.chartFactura.legend = new am4charts.Legend();

            });
            //fin amchart
          }, 500);

          //alert(response.data.validacion);
        }).catch(function (error) {
          console.log(error);
        });

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


    }
  })



  instanciaF.table_facturas();
  instanciaF.table_ordenes();
  instanciaF.getGrafica();


  $('#pacName').on("change",function(e){

    //alert($('#pacName').val());
    instanciaF.pac_id = $('#pacName').val();
    eventBusPACD.$emit('recargarPlan',$('#pacName').val());
  });


  /*$('#pacName').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data.id);
    instanciaF.pac_id = data.id;
    eventBusPACD.$emit('recargarPlan');

  });*/

  $('#modal-remoto-lgg2').on('click', '.salida', function () {
    var cambio = $('#id_cambio').val();
    //alert(cambio)
    if (cambio == 1) {
      instanciaF.recargarTabla();
    }

    $('#modal-remoto-lgg2').modal('hide');
  });

  /*$('#modal-remoto').on('click', '.salida', function () {
    var cambio = $('#cambio').val();
    //alert(cambio)
    if(cambio == 1){
      instanciaF.recargarFacturas();
      $('#modal-remoto').modal('hide');
    }


  });*/

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable
      .tables({ visible: true, api: true })
      .columns.adjust()
      .fixedColumns().relayout();
  });



  $(".tab-content > .tab-pane").each(function () {

    });


})

function publicarFactura(orden_id,nog){
  instanciaF.publicarFacturaVue(orden_id,nog);
}
