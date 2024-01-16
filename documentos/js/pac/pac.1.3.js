//var instancia = '';



var eventBusPAC = new Vue();
$(document).ready(function () {

  var viewModelPac = new Vue({
    //el: '#app_pedidos',
    data: {
      pedidos: "",
      pedido: "",
      insumos_list: "",
      opcion_pedido: 0,
      tablePlan: "",
      unidades: "",
      reporte: "",
      tableReportes: "",
      tipoC: 0,
      months: [],
      direcciones: [],
      ejercicioAnterior: false,
      privilegio:"",
      renglones:"",
      arrayPlanes:[],
      uniquesRenglones:[],
      recargarP:1
    },
    //components: { 'unidades': unidades },
    mounted() {
      instancia = this;
    },
    computed: {},
    created: function () {
      this.getDirecciones();
      eventBusPAC.$on('recargarTabla', (opc) => {
        //alert('Works!!!');
        this.reloadTable();
      });
    },
    methods: {
      recibirMeses: function (data) {
        this.months = data;
        console.log(this.months);
      },
      getPermisosUserF: function(data){
        this.privilegio = data;
      },
      getDirecciones: function () {
        axios.get('documentos/php/back/pac/listados/get_direcciones', {
          params: {
          }
        }).then(function (response) {
          this.direcciones = response.data;

        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      },
      iPacComplete: function () {
        viewModelPac.tablePlan.cells(
          viewModelPac.tablePlan.rows(function (idx, data, node) {
            var validacion = (data.status_chk === 2) ? true : false;
            //console.log(data.status);
            return validacion;
          }).indexes(),
        0).checkboxes.disable();

        if(this.privilegio.compras == true){
          viewModelPac.tablePlan.columns(0).visible(true);
        }else{
          viewModelPac.tablePlan.columns(0).visible(false);
        }

        if(this.recargarP == 1){
          //inicio
          var columna = 1;
          var column = viewModelPac.tablePlan.column(3 + columna);
          var select = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
            .appendTo('#fren')
            .on('change', function () {
              var val = $(this).val();
              column.search(val).draw()
            });

          var offices = [];
          var reformattedArray = column.data().toArray().map(function(obj){
             var rObj = {};
             //console.log(obj.inicio);
             //console.log(obj);
             if (!~offices.indexOf(obj)) {
               if(obj != null){
                 offices.push(obj);
               }
             }
          });
          offices.sort();
          offices.forEach(function (d) {
            select.append('<option value="' + d + '">' + d + '</option>');
          });

          var column1 = viewModelPac.tablePlan.column(2 + columna);
          var select1 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
            .appendTo('#fdir')
            .on('change', function () {
              var val1 = $(this).val();
              column1.search(val1).draw()
            });
          var offices1 = [
            'ASESORIA JURIDICA'
            , 'UNIDAD DE PLANIFICACION'
            , 'DIRECCION ADMINISTRATIVA Y FINANCIERA'
            , 'DIRECCION DE ASUNTOS INTERNOS'
            , 'DIRECCION DE COMUNICACIONES E INFORMATICA'
            , 'DIRECCION DE INFORMACION'
            , 'DIRECCION DE RECURSOS HUMANOS'
            , 'DIRECCION DE RESIDENCIAS'
            , 'DIRECCION DE SEGURIDAD'
            , 'SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES'
          ];
          offices1.forEach(function (d) {
            select1.append('<option value="' + d + '">' + d + '</option>');
          });
          //fin
        }
      },
      tablePac: function () {
        //alert('message');
        var columna = 1;
        var thisInstance
        this.tablePlan = $('#tb_pac').DataTable({
          'initComplete': function (settings, json) {
            viewModelPac.iPacComplete();
          },
          "ordering": false,
          //"pageLength": 25,
          "bProcessing": true,
          scrollY: '50vh',
          scrollCollapse: true,
          paging: false,
          //"paging": true,
          "info": true,
          "deferRender": true,
          select:true,
          scrollX: true,
          /*fixedColumns: true,
          fixedColumns: {
            leftColumns: 1
          },*/

          dom:
            "<'row'<'col-sm-4'l><'col-sm-4 texte-center'B><'col-sm-4'f>>" +
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
            url: "documentos/php/back/listados/get_pac_list.php",
            type: "POST",
            data: {
              year: function () { return $('#year').val() }
            },
            error: function () {
              $("#post_list_processing").css("display", "none");
            }
          },
          "aoColumns": [
            //{ "class": "text-center", mData: 'Ent_id' },
            { "class": "text-center sticky-col first-col", mData: 'DT_RowId' },
            { "class": "text-left sticky-col second-col", mData: 'pac_nombre' },
            { "class": "text-center", mData: 'pac_detalle' },
            { "class": "text-center", mData: 'ubicacion' },
            { "class": "text-center", mData: 'pac_renglon' },

            { "class": "text-center", mData: 'ejercicio_ant' },
            { "class": "text-center my-class", mData: 'c_ene' },
            { "class": "text-right", mData: 'm_ene' },
            { "class": "text-center my-class", mData: 'c_feb' },
            { "class": "text-right", mData: 'm_feb' },
            { "class": "text-center my-class", mData: 'c_mar' },
            { "class": "text-right", mData: 'm_mar' },
            { "class": "text-center my-class", mData: 'c_abr' },
            { "class": "text-right", mData: 'm_abr' },
            { "class": "text-center my-class", mData: 'c_may' },

            { "class": "text-right", mData: 'm_may' },

            { "class": "text-center my-class", mData: 'c_jun' },
            { "class": "text-right", mData: 'm_jun' },
            { "class": "text-center my-class", mData: 'c_jul' },
            { "class": "text-right", mData: 'm_jul' },
            { "class": "text-center my-class", mData: 'c_ago' },
            { "class": "text-right", mData: 'm_ago' },
            { "class": "text-center my-class", mData: 'c_sep' },
            { "class": "text-right", mData: 'm_sep' },
            { "class": "text-center my-class", mData: 'c_oct' },
            { "class": "text-right", mData: 'm_oct' },

            { "class": "text-center my-class", mData: 'c_nov' },
            { "class": "text-right", mData: 'm_nov' },
            { "class": "text-center my-class", mData: 'c_dic' },
            { "class": "text-right", mData: 'm_dic' },
            { "class": "text-right my-class", mData: 'total' },
            { "class": "text-center", mData: 'estado' },


          ],
          buttons: [
            {
              text: 'Recargar <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm',
              action: function (e, dt, node, config) {
                viewModelPac.reloadTable();
                //viewModelPac.tablePlan.ajax.reload(null, false);
              }
            },
            {
              extend: 'excelHtml5',
              footer: true,
              text: 'Exportar <i class="fa fa-download"></i>',
              className: 'btn btn-sm btn-soft-info',
              exportOptions: {
                columns: [0, 1, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 3, 2, 29, 30, 31]
              },
              customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];

                var numrows = 1;
                var clR = $('row', sheet);

                //update Row
                clR.each(function () {
                  var attr = $(this).attr('r');
                  var ind = parseInt(attr);
                  ind = ind + numrows;
                  $(this).attr("r", ind);
                });

                // Create row before data
                $('row c ', sheet).each(function () {
                  var attr = $(this).attr('r');
                  var pre = attr.substring(0, 1);
                  var ind = parseInt(attr.substring(1, attr.length));
                  ind = ind + numrows;
                  $(this).attr("r", pre + ind);
                });

                function Addrow(index, data) {
                  var msg = '<row r="' + index + '">'
                  for (var i = 0; i < data.length; i++) {
                    var key = data[i].key;
                    var value = data[i].value;
                    msg += '<c t="inlineStr" r="' + key + index + '">';
                    msg += '<is>';
                    msg += '<t>' + value + '</t>';
                    msg += '</is>';
                    msg += '</c>';
                  }
                  msg += '</row>';
                  return msg;
                }

                var r1 = Addrow(1, [{ key: 'D', value: 'ENERO' }, { key: 'F', value: 'FEBRERO' }]);

                sheet.childNodes[0].childNodes[1].innerHTML = r1 + sheet.childNodes[0].childNodes[1].innerHTML;

                $('row c[r^="C"]', sheet).attr('s', '2');
                $('row c[r^="D"]', sheet).attr('s', '25');
                // jQuery selector to add a border
                //$('row c[r*="10"]', sheet).attr( 's', '25' );
              }
            }
          ],

          "columnDefs": [
            {
              //responsivePriority: 0,
              //targets: [6, 10]
            },
            {
              'targets': 0,
              'checkboxes': {
                'selectRow': true
              },
            },
            { "width": "15px", "targets": 2 },
            //{ "width": "15px", "targets": 3 },
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
          'select': {
            'style': 'multi',
            selector: 'td:first-child'
          },
          "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // converting to interger to find total


            // Update footer by showing the total with the reference of the column index
            $(api.column(0 + columna).footer()).html('Total');
            $(api.column(6 + columna).footer()).html(sumarColumna(api, 6 + columna));
            $(api.column(8 + columna).footer()).html(sumarColumna(api, 8 + columna));
            $(api.column(10 + columna).footer()).html(sumarColumna(api, 10 + columna));
            $(api.column(12 + columna).footer()).html(sumarColumna(api, 12 + columna));
            $(api.column(14 + columna).footer()).html(sumarColumna(api, 14 + columna));
            $(api.column(16 + columna).footer()).html(sumarColumna(api, 16 + columna));
            $(api.column(18 + columna).footer()).html(sumarColumna(api, 18 + columna));
            $(api.column(20 + columna).footer()).html(sumarColumna(api, 20 + columna));
            $(api.column(22 + columna).footer()).html(sumarColumna(api, 22 + columna));
            $(api.column(24 + columna).footer()).html(sumarColumna(api, 24 + columna));
            $(api.column(26 + columna).footer()).html(sumarColumna(api, 26 + columna));
            $(api.column(28 + columna).footer()).html(sumarColumna(api, 28 + columna));
            $(api.column(29 + columna).footer()).html('<strong>' + sumarColumna(api, 29 + columna) + '</strong>');
          },
          "rowCallback": function (nRow, aData) {
            //alert(mData.tipo);
            $('td.my-class', nRow).css('border-left', aData.border);
          },

        });

        function sumarColumna(api, column) {

          var intVal = function (i) {
            return typeof i === 'string' ?
              i.replace(/[\$,]/g, '') * 1 :
              typeof i === 'number' ?
                i : 0;
          };
          var friTotal = api
            .column(column, { page: 'current' })
            .data()
            .reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);
          return friTotal;
        }
      },
      iComplete: function () {





        viewModelPac.tableReportes.rows().eq(0).each(function (index) {
          if (index == 0) {
            var row = viewModelPac.tableReportes.row(index);
            var data = row.data();

            viewModelPac.tableReportes.columns(3).visible(data.tipo);
            viewModelPac.tableReportes.columns(4).visible(data.tipo);
            viewModelPac.tableReportes.columns(5).visible(data.tipo);
            viewModelPac.tableReportes.columns(6).visible(data.tipo2);
            viewModelPac.tableReportes.columns(7).visible(data.tipo2);
            viewModelPac.tableReportes.columns(8).visible(data.tipo2);
          }

        });


      },
      reloadTable: function () {
        this.recargarP = 2;
        viewModelPac.tablePlan.ajax.reload(viewModelPac.iPacComplete, false);
        viewModelPac.tablePlan.cell(0, 0).data('New data').draw();
        //viewModelPac.tablePlan.fixedColumns().update();
      },
      cargarInput: function (tipo) {
        viewModelPac.uniquesRenglones.splice(0, viewModelPac.uniquesRenglones.length);
        viewModelPac.arrayPlanes.splice(0, viewModelPac.arrayPlanes.length);
        var planes = '';

        var rows_selected = viewModelPac.tablePlan.column(0).checkboxes.selected();
        var rows_selected1 = viewModelPac.tablePlan.rows({ selected: true }).data();
        var filtro = $('#id_filtro_factura').val();

        var codigos = '';
        var renglones = '';

        if (rows_selected.length == 0) {
          //mostrar mensaje
          var jsonTableData = '';
          Swal.fire(
            'Atención!',
            "Debe seleccionar al menos un plan de compras",
            'error'
          );
        } else if (rows_selected.length > 0) {
          var x = [];
          $.each(rows_selected, function(index, rowId){
            var rows = viewModelPac.tablePlan.row( '#' + rowId  ).data();
            var data = viewModelPac.tablePlan.row( this ).data();
            viewModelPac.arrayPlanes.push(rows.DT_RowId);
            viewModelPac.uniquesRenglones.push(rows.pac_renglon);

            planes += ',' + rows.DT_RowId;
          })

          let unique = viewModelPac.uniquesRenglones.filter((item, i, ar) => ar.indexOf(item) === i);

          console.log(unique);
          if(unique.length == 1){
            let imgModal = $('#modal-remoto-lgg2');
            let imgModalBody = imgModal.find('.modal-content');
            $.ajax({
              type: "GET",
              url: "documentos/php/front/pac/pac_consolidar.php",
              data:{
                planes: planes
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
          }else{
            Swal.fire(
              'Atención!',
              "¡No puede consolidar Planes de compra de diferentes renglones presupuestarios!",
              'error'
            );
          }

          //codigos = x;

          /*if(tipo == 6){
            // inicio
            var imgModal = $('#modal-remoto');
            var imgModalBody = imgModal.find('.modal-content');
            $.ajax({
              type: "GET",
              url: "documentos/php/front/factura/asignar_cheque.php",
              data:{
                arreglo: codigos
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
            // fin
          }
          else{
            Swal.fire(
              'Atención!',
              "Esta opción no está disponible en esta fase!",
              'error'
            );
          }*/
        }

      },
      guardarNuevoPac: function () {
        var total = 0;
        /*f (this.months.find((item) => item.checked == true)) {

        } else {
          Swal.fire({
            type: 'error',
            title: 'El producto ya existe en la lista',
            showConfirmButton: false,
            timer: 1100
          });
        }*/

        jQuery('.jsValidacionPacNuevo').validate({
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
            var months = viewModelPac.months;
            //alert(total);

            if (viewModelPac.months.find((item) => item.checked == true)) {
              Swal.fire({
                title: '<strong>¿Desea generar el plan de compra?</strong>',
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
                  //eventBusPAC.$emit('recargarMeses',1);
                  $.ajax({
                    type: "POST",
                    url: "documentos/php/back/pac/action/crear_pac.php",
                    dataType: 'json',
                    data: {
                      id_nombre: $('#id_nombre').val(),
                      id_unidad: $('#id_unidad').val(),
                      id_renglon: $('#id_renglon').val(),
                      id_descripcion: $('#id_descripcion').val(),
                      id_ejercicio_ant: $('#id_ejercicio_ant').val(),
                      id_year_anterior: $('#id_year_anterior').val(),
                      id_descripcion_year: $('#id_descripcion_year').val(),
                      months: months
                    }, //f de fecha y u de estado.

                    beforeSend: function () {
                    },
                    success: function (data) {
                      //exportHTML(data.id);
                      //recargarDocumentos();
                      if (data.msg == 'OK') {
                        $('#id_nombre').val('');
                        $('#id_unidad').val('');
                        $('#id_renglon').val('');
                        //$('#id_renglon').empty();
                        $('#id_renglon').val(null).trigger('change');
                        $('#id_descripcion').val('');
                        $('#id_ejercicio_ant').val('');
                        $('#id_year_anterior').val('');
                        $('#id_descripcion_year').val('');

                        eventBusPAC.$emit('recargarMeses', 1);
                        viewModelPac.tablePlan.ajax.reload(null, false);
                        viewModelPac.ejercicioAnterior = false;
                        Swal.fire({
                          type: 'success',
                          title: 'Plan generado',
                          showConfirmButton: false,
                          timer: 1100
                        });
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
                title: 'Debe seleccionar al menos un mes',
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
    },
    setEjercicioAnterior: function () {
      this.ejercicioAnterior = true;
    },

  })

  viewModelPac.$mount('#app_pac');
  instancia.tablePac();


  $('#modal-remoto-lgg2').on('click', '.salida', function () {
    var cambio = $('#id_cambio').val();
    //alert(cambio)
    if (cambio == 1) {
      instancia.recargarTabla();
    }

    $('#modal-remoto-lgg2').modal('hide');
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $.fn.dataTable
      .tables({ visible: true, api: true })
      .columns.adjust()
      .fixedColumns().relayout();
  });

})
