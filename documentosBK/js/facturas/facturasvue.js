var instanciaF = '';
var eventBusPACD = new Vue();

$(document).ready(function () {
  viewModelFactura = new Vue({
    el: '#app_facturas',
    data: {
      tableFacturas: "",
      privilegio:"",
      buttons:[],
      tipoBajaCuantia:0,
      tipoNog:0,
      uniqs:[]

    },
    mounted() {
      instanciaF = this;
    },
    computed: {

    },
    created: function () {
      this.showButtons();
    },
    methods: {
      getPermisosUserF: function(data){
        this.privilegio = data;
      },
      showButtons: function(){
        setTimeout(() => {
          //inicio
          if(this.privilegio.compras_recepcion == true){
            this.buttons.push({
              text: 'Agregar <i class="fa fa-plus"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                let imgModal = $('#modal-remoto');
                let imgModalBody = imgModal.find('.modal-content');
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
          if(this.privilegio.tesoreria_au == true){
            //tesorería
            this.buttons.push({
              text: 'Operar <i class="fa fa-receipt"></i>',
              className: 'btn btn-personalizado btn-sm',
              action: function (e, dt, node, config) {
                let imgModal = $('#modal-remoto');
                let imgModalBody = imgModal.find('.modal-content');
                //let id_persona = parseInt($('#bar_code').val());
                $.ajax({
                  type: "GET",
                  url: "documentos/php/front/factura/factura_operar.php",
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
            })
            //fin
          }
          //,
          this.buttons.push({
            text: 'Pendientes <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-f1 active',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(0,'btn-f1');
            }
          })
          this.buttons.push({
            text: 'Publicadas <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-f5 ',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(4,'btn-f5');
            }
          })
          this.buttons.push({
            text: 'Compromiso <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-f2',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(1,'btn-f2');
            }
          })
          this.buttons.push({
            text: 'Devengado <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-f3',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(2,'btn-f3');
            }
          })
          this.buttons.push({
            text: 'Anuladas <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-f4',
            action: function (e, dt, node, config) {
              instanciaF.recargarFacturas(3,'btn-f4');
            }
          })

          if(this.privilegio.compras_asignar_tecnico == true){
            this.buttons.push({
              text: 'Clase proceso <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-f4',
              action: function (e, dt, node, config) {
                instanciaF.cargarInput(2);
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


          /*{
            text: 'Limpiar <i class="fa fa-eraser btn-f5"></i>',
            className: 'btn btn-personalizado btn-sm',
            action: function (e, dt, node, donfig) {
              //this.tableFacturas.fnClearTable('');
              viewModelFactura.tableFacturas.columns(2).search('2021');
              viewModelFactura.tableFacturas.draw();
            }
          },
          {
            extend: 'excel',
            text: 'Exportar <i class="fa fa-download"></i>',
            className: 'btn btn-sm btn-personalizado'
          }
        ]*/
      },
      table_facturas: function () {
        setTimeout(() => {
          //inicio
          this.tableFacturas = $('#tb_facturas').DataTable({
            'initComplete': function (settings, json) {
              //alert('message1');
              //var api = new $.fn.dataTable.Api(settings);
              //alert('cargando');
              viewModelFactura.tableFacturas.cells(
                viewModelFactura.tableFacturas.rows(function (idx, data, node) {
                  //alert(idx);
                  return (data.factura_publicada == 0) ? true : false;
                  //alert(data.bln_confirma)
                }).indexes(), 18).checkboxes.disable();
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
              rightColumns: 2
            },
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
              { "class": "text-center", mData: 'tipo_pago' },
              { "class": "text-center", mData: 'fecha_recepcion' },
              { "class": "text-center", mData: 'factura_fecha' },
              { "class": "text-center", mData: 'diferencia' },
              { "class": "text-center", mData: 'factura_serie' },
              { "class": "text-center", mData: 'factura_num' },
              { "class": "text-center", mData: 'proveedor' },
              { "class": "text-right", mData: 'factura_total' },
              { "class": "text-center", mData: 'ped_num' },
              { "class": "text-center", mData: 'clase_proceso' },
              { "class": "text-center", mData: 'nro_orden' },
              { "class": "text-center", mData: 'cyd' },

              { "class": "text-center", mData: 'nog' },
              { "class": "text-center", mData: 'cur_c' },
              { "class": "text-center", mData: 'cur_d' },
              { "class": "text-center", mData: 'cheque' },
              { "class": "text-center", mData: 'asignado' },
              { "class": "text-center", mData: 'accion' },
              { "class": "text-center", mData: 'orden_id' },
            ],
            buttons: instanciaF.buttons,
            "columnDefs": [
              {
                'targets': 18,
                'checkboxes': {
                  'selectRow': true
                },
              },
              { "width": "15px", "targets": 2 },
              { "width": "25%", "targets": 4 },
              {
                'targets': [17],
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                render: function (data, type, row, meta) {
                  var buttons = '';
                  buttons += '<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id=' + row.DT_RowId + '&ped_num=' + row.ped_num + '"><i class="fa fa-pencil-alt"></i></span>';
                  if(row.factura_publicada == 0){
                    buttons += '<span class="btn btn-sm btn-soft-info" data-toggle="modal" onclick="publicarFactura('+ row.DT_RowId +')"><i class="fa fa-check-circle"></i> '+row.factura_publicada+'</span>';
                  }
                  buttons += '</div>';//'+row.DT_RowId+'

                  return buttons;
                }
              }
            ],
            'select': {
              'style': 'multi'
            },
            /*"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              if (instanciaF.privilegio.tesoreria_au == true) {
                instanciaF.tableFacturas.columns(18).visible(false);
              }

            }*/

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
            console.log('adlfjlñajdfñlkasdf');
            //$('#myTable').on('click', '.toggle-all', function (e) {
            instanciaF.tipoBajaCuantia = 0;
            instanciaF.tipoNog = 0;
            var rows_selected = instanciaF.tableFacturas.column(18).checkboxes.selected();
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
      cargarInput: function (tipo) {
        var rows_selected = instanciaF.tableFacturas.column(18).checkboxes.selected();

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

          if(instanciaF.tipoBajaCuantia > 0 && instanciaF.tipoNog > 0){
            Swal.fire(
              'Atención!',
              "No puede seleccionar facturas con NOG y Baja Cuantía al mismo tiempo",
              'error'
            );
          }else{
            let imgModal = $('#modal-remoto');
            let imgModalBody = imgModal.find('.modal-content');
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
      publicarFacturaVue: function(orden_id){
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
            $.ajax({
              type: "POST",
              url: "documentos/php/back/factura/action/publicar_factura.php",
              dataType: 'json',
              data:{
                orden_id: orden_id
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
          }
        })
      },


      recargarFacturas: function (opc,classe) {
        $('#id_filtro_factura').val(opc);
        this.tableFacturas.ajax.reload(null, false);
        switch (classe) {
          case 'btn-f1':
            $(".btn-f1").addClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").removeClass("active");
            $(".btn-f5").removeClass("active");
          break;
          case 'btn-f2':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").addClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").removeClass("active");
            $(".btn-f5").removeClass("active");
          break;
          case 'btn-f3':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").addClass("active");
            $(".btn-f4").removeClass("active");
            $(".btn-f5").removeClass("active");
          break;
          case 'btn-f4':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").addClass("active");
            $(".btn-f5").removeClass("active");
          break;
          case 'btn-f5':
            $(".btn-f1").removeClass("active");
            $(".btn-f2").removeClass("active");
            $(".btn-f3").removeClass("active");
            $(".btn-f4").removeClass("active");
            $(".btn-f5").addClass("active");
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


    }
  })



  instanciaF.table_facturas();



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
    $.fn.dataTable.tables({ visible: true, api: true })
      .responsive.recalc()
      .columns.adjust();
  });

})

function publicarFactura(orden_id){
  instanciaF.publicarFacturaVue(orden_id);
}
