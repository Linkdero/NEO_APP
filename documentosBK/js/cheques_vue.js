var instance = '';
$(document).ready(function () {

  vueCheques = new Vue({
    //el: '#app_pedidos',
    data: {
      id_cheque: "",
      resolucion: "",
      nro_cheque: "",
      monto: 0,
      fecha_res: ""
    },
    mounted() {
      instance = this;
    },
    computed: {},
    created: function () {

      // this.get_unidades();
      // this.insumos.splice(0, 1);

    },
    methods: {
      // get_pedidos: function () {
      //   axios.get('documentos/php/back/listados/get_pedidos', {
      //     params: {
      //       tipo: 1
      //     }
      //   }).then(function (response) {
      //     vueCheques.pedidos = response.data;
      //   }).catch(function (error) {
      //     console.log(error);
      //   });
      // },
      // get_insumos_list: function () {
      //   axios.get('documentos/php/back/pedido/get_insumos', {
      //     params: {
      //       tipo: 1,
      //       filtro: $('#filtro_insumo').val()
      //     }
      //   }).then(function (response) {
      //     vueCheques.insumos_list = response.data;
      //   }).catch(function (error) {
      //     console.log(error);
      //   });
      // },
      // opcionPedido: function (opc) {
      //   this.opcion_pedido = opc;
      // },
      tableCheques: function () {
        this.tbcheques = $('#tb_cheques').DataTable({
          "ordering": false,
          "pageLength": 50,
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
            emptyTable: "No hay cheques para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          ajax: {
            url: "documentos/php/back/cheques/get_cheques_listado.php",
            type: "POST",
            data: {
              // tipo: function () { return $('#id_tipo_filtro').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },
          "aoColumns": [
            { "class": "text-center", mData: 'id_cheque' },
            { "class": "text-center", mData: 'resolucion' },
            { "class": "text-center", mData: 'nro_cheque' },
            { "class": "text-center", mData: 'monto' },
            { "class": "text-center", mData: 'fecha_res' },
            { "class": "text-center", mData: 'accion' }
          ],
          buttons: [
            /*{
              text: 'Nuevos <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                vueCheques.recargarTabla(2);
              }
            },
            {
              text: 'En proceso <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                vueCheques.recargarTabla(3);
              }
            },
            {
              text: 'Autorizados <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                vueCheques.recargarTabla(4);
              }
            },
            {
              text: 'Anulados <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function ( e, dt, node, config ) {
                vueCheques.recargarTabla(5);
              }
            },*/
            // {
            //   text: 'Todos <i class="fa fa-sync"></i>',
            //   className: 'btn btn-soft-info btn-sm',
            //   action: function (e, dt, node, config) {
            //     vueCheques.recargarTabla(0);
            //   }
            // },
            // {
            //   extend: 'excel',
            //   text: 'Exportar <i class="fa fa-download"></i>',
            //   className: 'btn btn-sm btn-soft-info'
            // }
          ],
          "columnDefs": [
            //   {
            //     responsivePriority: 0,
            //     targets: [6, 10]
            //   },
            //   { "width": "15px", "targets": 2 },
            //   { "width": "15px", "targets": 3 },
            //   /*{
            //     'targets': [6],
            //     'searchable': false,
            //     'orderable': false,
            //     'className': 'dt-body-center',
            //     render: function(data, type, row, meta) {

            //       var menu='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown" onclick="get_menu_impresion('+row.DT_RowId+',1)"><i class="fa fa-print"></i></a><div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div id="menu'+row.DT_RowId+'"></div></div></div></div>';
            //     return  '<div class="btn-group">'+menu+'<span class="btn btn-sm btn-personalizado outline" data-toggle="modal" data-target="#modal-remoto-lgg2" href="viaticos/php/front/viaticos/viatico_detalle.php?id_viatico='+row.DT_RowId+'&tipo_filtro='+$('#id_tipo_filtro').val()+'"><i class="fa fa-pencil-alt" aria-hidden="true"></i></span></div>';//'+row.DT_RowId+'
            //       //return  '<div class="btn-group"><span class="btn btn-sm btn-soft-info" onclick="impresion_pedido('+row.DT_RowId+')"><i class="fa fa-print" aria-hidden="true"></i></span><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pedidos/pedido_detalle.php?ped_tra='+row.DT_RowId+'"><i class="fa fa-pencil-alt"></i></span></div>';//'+row.DT_RowId+'
            //     }
            //   }*/
          ],
          initComplete: function () {
            // var column1 = this.api().column(6);
            // var select1 = $('<select class="form-control"><option value="">TODOS</option></select>')
            //   .appendTo('#estado')
            //   .on('change', function () {
            //     var val1 = $(this).val();
            //     column1.search(val1).draw()
            //   });

            // var offices1 = ['AUTORIZADO', 'RECIBIDO', 'RECHAZADO', 'ANULADO', 'SIN PROCESAR'];
            // offices1.sort();
            // offices1.forEach(function (d) {
            //   select1.append('<option value="' + d + '">' + d + '</option>');
            // });
          }
        });
      },
      iComplete: function () {

        // vueCheques.tableReportes.rows().eq(0).each(function (index) {
        //   if (index == 0) {
        //     var row = vueCheques.tableReportes.row(index);
        //     var data = row.data();

        //     vueCheques.tableReportes.columns(3).visible(data.tipo);
        //     vueCheques.tableReportes.columns(4).visible(data.tipo);
        //     vueCheques.tableReportes.columns(5).visible(data.tipo);
        //     vueCheques.tableReportes.columns(6).visible(data.tipo2);
        //     vueCheques.tableReportes.columns(7).visible(data.tipo2);
        //     vueCheques.tableReportes.columns(8).visible(data.tipo2);
        //   }

        // });


      },
      recargarTabla: function (tipo) {
        //alert('recargando');
        $('#id_tipo_filtro').val(tipo);
        //this.tipoC = tipo;
        //alert(this.tipoC);
        this.tbcheques.ajax.reload(null, false);
      },
      // recargarReporte: function () {
      //   this.tableReportes.ajax.reload(vueCheques.iComplete, false);
      // },
      // recargarFacturas: function () {
      //   this.tableFacturas.ajax.reload(null, false);
      // },
      // filtarFacturas: function () {
      //   //startdate=picker.startDate.format('YYYY-MM-DD');
      //   //enddate=picker.endDate.format('YYYY-MM-DD');
      //   var startdate = $('#fecha_fac').val();
      //   var date = new Date(startdate);
      //   var d = date.getDate() + 1;
      //   var m = date.getMonth() + 1;
      //   //var mo = (m < 10)?'0'+m:m
      //   var y = date.getFullYear();
      //   var fulldate = '';
      //   if (m < 10) {
      //     if (d < 10) {
      //       fulldate = '0' + d + '-0' + m + '-' + y;
      //     } else {
      //       fulldate = d + '-0' + m + '-' + y;
      //     }

      //   } else {
      //     fulldate = d + '-' + m + '-' + y;
      //   }

      //   //alert(fulldate);
      //   this.tableFacturas.columns(2).search(fulldate);
      //   this.tableFacturas.draw();
      // },
      get_unidades: function () {
        // axios.get('documentos/php/back/listados/get_unidades', {
        //   params: {

        //   }
        // }).then(function (response) {
        //   vueCheques.unidades = response.data;
        // }).catch(function (error) {
        //   console.log(error);
        // });
      },
      guardarPedidoNuevo: function () {
        // jQuery('.jsValidacionPedidoNuevo').validate({
        //   ignore: [],
        //   errorClass: 'help-block animated fadeInDown',
        //   errorElement: 'div',
        //   errorPlacement: function (error, e) {
        //     jQuery(e).parents('.form-group > div').append(error);
        //   },
        //   highlight: function (e) {
        //     var elem = jQuery(e);

        //     elem.closest('.form-group').removeClass('has-error').addClass('has-error');
        //     elem.closest('.help-block').remove();
        //   },
        //   success: function (e) {
        //     var elem = jQuery(e);

        //     elem.closest('.form-group').removeClass('has-error');
        //     elem.closest('.help-block').remove();
        //   },
        //   submitHandler: function (form) {
        //     //regformhash(form,form.password,form.confirmpwd);
        //     var insumos = vueCheques.insumos;

        //     if (vueCheques.insumos.length >= 1) {
        //       Swal.fire({
        //         title: '<strong>¿Desea generar el pedido?</strong>',
        //         text: "",
        //         type: 'question',
        //         showCancelButton: true,
        //         showLoaderOnConfirm: true,
        //         confirmButtonColor: '#28a745',
        //         cancelButtonText: 'Cancelar',
        //         confirmButtonText: '¡Si, Generar!'
        //       }).then((result) => {
        //         if (result.value) {
        //           //alert(vt_nombramiento);
        //           $.ajax({
        //             type: "POST",
        //             url: "documentos/php/back/pedido/crear_pedido.php",
        //             dataType: 'json',
        //             data: {
        //               ped_num: $('#pedido_nro').val(),
        //               fecha: $('#fecha_pedido').val(),
        //               unidad: $('#id_unidad').val(),
        //               observaciones: $('#id_observaciones').val(),
        //               insumos: insumos
        //             }, //f de fecha y u de estado.

        //             beforeSend: function () {
        //             },
        //             success: function (data) {
        //               //exportHTML(data.id);
        //               //recargarDocumentos();
        //               if (data.msg == 'OK') {
        //                 $('#pedido_nro').val('');
        //                 $('#fecha_pedido').val('');
        //                 $('#id_unidad').val('');
        //                 $('#id_observaciones').val('');

        //                 vueCheques.limpiar_lista();

        //                 vueCheques.recargarTabla();
        //                 Swal.fire({
        //                   type: 'success',
        //                   title: 'Pedido generado',
        //                   showConfirmButton: false,
        //                   timer: 1100
        //                 });
        //                 impresion_pedido(data.id);
        //               } else {
        //                 Swal.fire({
        //                   type: 'error',
        //                   title: 'Error',
        //                   showConfirmButton: false,
        //                   timer: 1100
        //                 });
        //               }

        //             }

        //           }).done(function () {


        //           }).fail(function (jqXHR, textSttus, errorThrown) {

        //             alert(errorThrown);

        //           });

        //         }

        //       })
        //     } else {
        //       Swal.fire({
        //         type: 'error',
        //         title: 'Debe seleccionar al menos un insumo',
        //         showConfirmButton: false,
        //         timer: 1100
        //       });
        //     }


        //   },
        //   rules: {
        //     combo1: { required: true },
        //     'pedido_nro': {
        //       remote: {
        //         url: 'documentos/php/back/pedido/validar_num_pedido.php',
        //         data: {
        //           ped_num: function () { return $('#pedido_nro').val(); }
        //         }
        //       }
        //     }
        //   },
        //   messages: {
        //     'pedido_nro': {
        //       remote: "El número de pedido ya fue utilizado."
        //     }
        //   }
        // });
      },
      filtrado: function () {
        // $('.categoryName').select2({
        //   placeholder: 'Selecciona un insumo',
        //   ajax: {
        //     url: 'documentos/php/back/pedido/get_insumo_filtrado.php',
        //     dataType: 'json',
        //     delay: 250,
        //     processResults: function (data) {
        //       return {
        //         results: data
        //       };
        //     },
        //     cache: true
        //   }
        // });
      },
      limpiar_lista: function () {
        // vueCheques.insumos.splice(0, vueCheques.insumos.length);
      },
      deleteRow(index, d) {
        // var idx = this.insumos.indexOf(d);
        // console.log(idx, index);
        // if (idx > -1) {
        //   vueCheques.insumos.splice(idx, 1);
        // }
      },
      addNewRow() {

        // if ($('#Ppr_id').val() != null) {
        //   axios.get('documentos/php/back/pedido/get_insumo_seleccionado', {
        //     params: {
        //       Ppr_id: $('#Ppr_id').val()
        //     }
        //   }).then(function (response) {

        //     if (vueCheques.insumos.find((item) => item.Ppr_id == response.data.Ppr_id)) {
        //       Swal.fire({
        //         type: 'error',
        //         title: 'El producto ya existe en la lista',
        //         showConfirmButton: false,
        //         timer: 1100
        //       });
        //     } else {
        //       //vueCheques.insumos = response.data;
        //       vueCheques.insumos.push({
        //         Ppr_id: response.data.Ppr_id,
        //         Ppr_can: '',
        //         Ppr_cod: response.data.Ppr_cod,
        //         Ppr_codPre: response.data.Ppr_codPre,
        //         Ppr_Nom: response.data.Ppr_Nom,
        //         Ppr_Des: response.data.Ppr_Des,
        //         Ppr_Pres: response.data.Ppr_Pres,
        //         Ppr_Ren: response.data.Ppr_Ren,
        //         Ppr_Med: response.data.Ppr_Med
        //       })
        //     }


        //   }).catch(function (error) {
        //     console.log(error);
        //   });
        // } else {
        //   // Swal.fire({
        //   //   type: 'error',
        //   //   title: 'Debe seleccionar un insumo',
        //   //   showConfirmButton: false,
        //   //   timer: 1100
        //   // });
        // }


      },
      // mostrar_menu: function (ped_tra, tipo) {
      //   $.ajax({
      //     type: "POST",
      //     url: "documentos/php/back/pedido/menu_impresion.php",
      //     data: {
      //       ped_tra,
      //       tipo

      //     },

      //     beforeSend: function () {
      //       $('#menu' + ped_tra + tipo).html('Cargando');
      //     },
      //     success: function (data) {
      //       $('#menu' + ped_tra + tipo).html(data);
      //     }

      //   }).done(function () {


      //   }).fail(function (jqXHR, textSttus, errorThrown) {

      //     alert(errorThrown);

      //   });
      // }

    }
  })

  vueCheques.$mount('#app_vuecheques');
  instance.tableCheques();


  // $('#modal-remoto-lgg2').on('click', '.salida', function () {
  //   var cambio = $('#id_cambio').val();
  //   //alert(cambio)
  //   if (cambio == 1) {
  //     instance.recargarTabla();
  //   }

  //   $('#modal-remoto-lgg2').modal('hide');
  // });

  /*$('#modal-remoto').on('click', '.salida', function () {
    var cambio = $('#cambio').val();
    //alert(cambio)
    if(cambio == 1){
      instance.recargarFacturas();
      $('#modal-remoto').modal('hide');
    }


  });*/

  // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
  //   $.fn.dataTable.tables({ visible: true, api: true })
  //     .responsive.recalc()
  //     .columns.adjust();
  // });

})

// function get_menu_impresion(ped_tra, tipo) {
//   instance.mostrar_menu(ped_tra, tipo);
// }
