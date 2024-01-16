var eventBusPD = new Vue();
var instancia;
var instanciaBV;
// seguimiento
$(document).ready(function(){



//formulario detalle
  viewModelBoletas = new Vue({
      data: {
        tableBoletas:"",
        tableVacaciones:"",
        tableReporte:""

      },
      mounted(){
        instanciaBV = this;
      },

      computed: {

      },
      created: function(){

      },

      methods: {
        getOpcion: function(opc){
          this.opcion = opc;
        },
        getBoletas: function(){
          this.tableBoletas = $('#tb_vacaciones').DataTable({
              ordering: true,
              order: [[1, "desc"]],
              pageLength: 25,
              bProcessing: true,
              paging: true,
              info: true,
              responsive: true,
              scrollY: '45vh',
              scrollCollapse: true,
              language: {
                  emptyTable: "No hay boletas para mostrar",
                  sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
              },
              ajax: {
                  url: "horarios/php/back/boletas/get_solicitudes.php",
                  type: "POST",
                  data: {
                      tipo: function () { return $('#id_tipo_filtro').val() },
                      fff1: function () { return $('#fff1').val() },
                      fff2: function () { return $('#fff2').val() },
                  },
                  error: function () {
                      $("#post_list_processing").css("display", "none");
                  }
              },
              "aoColumns": [
                  { "class": "text-center", mData: 'estado' },
                  { "class": "text-center", mData: 'vac_id' },
                  { "class": "text-center", mData: 'persona' },
                  { "class": "text-center", mData: 'sol' },
                  { "class": "text-center", mData: 'inicio' },
                  { "class": "text-center", mData: 'fin' },
                  { "class": "text-center", mData: 'dias' },
                  { "class": "text-center", mData: 'pre' },
                  { "class": "text-center", mData: 'diares' },
                  { "class": "text-center", mData: 'periodo' },
                  { "class": "text-center", mData: 'dir' },
                  { "class": "text-center", mData: 'accion' },
                  { "class": "text-center", mData: 'mesini' },
                  { "class": "text-center", mData: 'mesfin' },
              ],
              buttons: [
                  {
                      text: 'Pendientes <i class="fa fa-sync"></i>',
                      className: 'btn btn-personalizado btn-bo btn-bo1 active',
                      action: function (e, dt, node, config) {
                          instanciaBV.recargarBoletas(1);
                          this.tableBoletas.columns(8).visible(false);
                          document.getElementById("hidfechas").hidden = true;
                      }
                  },
                  {
                      text: 'En proceso <i class="fa fa-sync"></i>',
                      className: 'btn btn-personalizado btn-bo btn-bo4',
                      action: function (e, dt, node, config) {
                          instanciaBV.recargarBoletas(4);
                          this.tableBoletas.columns(8).visible(true);
                          this.tableBoletas.column(8).order('asc');
                          document.getElementById("hidfechas").hidden = true;
                      }
                  },
                  {
                      text: '1 año <i class="fa fa-sync"></i>',
                      className: 'btn btn-personalizado btn-bo btn-bo2',
                      action: function (e, dt, node, config) {
                          instanciaBV.recargarBoletas(2);
                          this.tableBoletas.columns(8).visible(false);
                          document.getElementById("hidfechas").hidden = true;
                      }
                  },
                  {
                      text: 'Todos <i class="fa fa-sync"></i>',
                      className: 'btn btn-personalizado btn-bo btn-bo3',
                      action: function (e, dt, node, config) {
                          instanciaBV.recargarBoletas(3);
                          this.tableBoletas.columns(8).visible(false);
                          // document.getElementById("hidfechas").hidden = false;
                      }
                  },
                  {
                      extend: 'excel',
                      text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                      className: 'btn btn-personalizado outline',
                      filename: 'Reporte Boletas de Vacaciones',
                      title: 'Reporte Boletas de Vacaciones',
                      exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                      },
                  },
                  {
                      extend: 'pdfHtml5',
                      text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                      className: 'btn btn-personalizado outline',
                      download: ['donwload', 'open'],
                      orientation: 'landscape',
                      filename: 'Reporte Boletas de Vacaciones',
                      title: 'Reporte Boletas de Vacaciones',
                      exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                      },
                      customize: function (doc) {
                          doc.styles.tableBodyEven.alignment = 'center';
                          doc.styles.tableBodyOdd.alignment = 'center';
                          doc.content.splice(0, 1);
                          doc.pageMargins = [20, 100, 20, 20];
                          doc['header'] = (function () {
                              return {
                                  columns: [
                                      {
                                          image: baner,
                                          width: 300,
                                          margin: [-50, -10]
                                      },
                                      {
                                          alignment: 'left',
                                          text: 'SAAS APP - MODULO DE CONTROL DE VACACIONES',
                                          fontSize: 6,
                                          margin: [-295, 50, 0, 0]
                                      },
                                      {
                                          alignment: 'left',
                                          bold: true,
                                          text: 'REPORTE DE BOLETAS DE VACACIONES',
                                          fontSize: 13,
                                          margin: [-150, 20]
                                      },
                                      {
                                          alignment: 'left',
                                          bold: true,
                                          text: '',
                                          fontSize: 10,
                                          margin: [-220, 40]
                                      },
                                      {
                                          alignment: 'left',
                                          text: 'FECHA: ' + formatDate1(Date()) + '\n HORA: ' + formatDate2(Date()),
                                          fontSize: 8,
                                          margin: [24, 0]
                                      },
                                  ],
                                  margin: 20
                              }
                          });

                      }
                  }

              ],
              initComplete: function () {
                  instanciaBV.tableBoletas.columns(8).visible(false);
                  var column = this.api().column(10);
                  var select = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter2')
                      .on('change', function () {
                          var val = $(this).val();
                          column.search(val).draw()
                      });
                  var offices = ['DESPACHO DEL SECRETARIO'
                      , 'SUBSECRETARIA ADMINISTRATIVA'
                      , 'SUBSECRETARIA DE SEGURIDAD'
                      , 'ASESORIA JURIDICA'
                      , 'AUDITORIA INTERNA'
                      , 'UNIDAD DE INSPECTORIA'
                      , 'DIRECCION ADMINISTRATIVA Y FINANCIERA'
                      , 'DIRECCION DE ASUNTOS INTERNOS'
                      , 'DIRECCION DE COMUNICACIONES E INFORMATICA'
                      , 'DIRECCION DE INFORMACION'
                      , 'DIRECCION DE RECURSOS HUMANOS'
                      , 'DIRECCION DE RESIDENCIAS'
                      , 'DIRECCION DE SEGURIDAD'
                      , 'SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES'
                      , 'S/D'
                  ];
                  offices.forEach(function (d) {
                      select.append('<option value="' + d + '">' + d + '</option>');
                  })
                  var column4 = this.api().column(12);
                  var select4 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter4')
                      .on('change', function () {
                          var val4 = $(this).val();
                          column4.search(val4).draw()
                      });
                  var offices4 = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                  offices4.forEach(function (d) {
                      select4.append('<option value="' + d + '">' + d + '</option>');
                  })
                  var column5 = this.api().column(13);
                  var select5 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter5')
                      .on('change', function () {
                          var val5 = $(this).val();
                          column5.search(val5).draw()
                      });
                  offices4.forEach(function (d) {
                      select5.append('<option value="' + d + '">' + d + '</option>');
                  })
                  var column3 = this.api().column(3);
                  var select3 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter3')
                      .on('change', function () {
                          var val3 = $(this).val();
                          column3.search(val3).draw()
                      });
                  var offices3 = ['2021', '2020', '2019', '2018', '2017', '2016'];
                  offices3.forEach(function (d) {
                      select3.append('<option value="' + d + '">' + d + '</option>');
                  })
                  var column1 = this.api().column(0);
                  var select1 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter1')
                      .on('change', function () {
                          var val1 = $(this).val();
                          column1.search(val1).draw()
                      });
                  var offices1 = ['Solicitado', 'Autorizado en dirección', 'Anulado en dirección', 'Autorizado por RRHH', 'Anulado por RRHH'];
                  offices1.forEach(function (d) {
                      select1.append('<option value="' + d + '">' + d + '</option>');
                  })
              },
              columnDefs: [
                  { responsivePriority: 0, targets: 11 },
                  { visible: false, targets: [12, 13] }
              ]
          });
        },
        get_fotografia: function(data, row, column) {
            let id_persona = data.id;
            $.ajax({
                type: "POST",
                url: "horarios/php/back/empleados/get_fotografia.php",
                dataType: 'html',
                data: { id_persona },
                success: function (foto) {
                    $(`td:eq(0)`, row).html(foto);
                }
            });
        },
        getVacaciones: function(){
          this.tableVacaciones = $('#tb_pendientes').DataTable({
              pageLength: 10,
              bProcessing: true,
              scrollY: '45vh',
              scrollCollapse: true,
              language: {
                  emptyTable: "No hay empleados",
                  loadingRecords: " <div class='spinner-grow text-info'></div> "
              },
              ajax: {
                  url: "horarios/php/back/boletas/get_empleados.php",
                  type: "POST",
                  data: {
                      tipo: function () { return $('#id_1_0').val() },
                  },
                  error: function () {
                      $("#post_list_processing").css("display", "none");
                  }
              },
              aoColumns: [
                  { "class": "text-center", mData: 'id' },
                  { "class": "text-center", mData: 'empleado' },
                  { "class": "text-center", mData: 'dfuncional' },
                  { "class": "text-center", mData: 'pfuncional' },
                  { "class": "text-center", mData: 'pendientes' },
                  { "class": "text-center", mData: 'utilizados' },
                  { "class": "text-center", mData: 'accion' }
              ],
              buttons: [
                  // {
                  //     //   text: '<span data-toggle="modal" data-target="#modal-remoto-lg">Generar Horarios <i class="fa fa-check"></i></span>',
                  //     //text: '<span data-toggle="modal" data-target="#modal-remoto-lg" >Generar Horarios</span>',
                  //     text: 'Generar Reporte <i class="fa fa-clock"></i>',
                  //     className: 'btn btn-soft-info',
                  //     action: function () {
                  //         //let id_persona = 8437;
                  //         // let month = $('select#month_1 option:checked').val();
                  //         let year = $('select#year_1 option:checked').val();
                  //         let dir = $('#filter1 option:checked').text();
                  //         $.ajax({
                  //             type: "POST",
                  //             url: "horarios/php/front/empleados/empleado_general.php",
                  //             dataType: 'html',
                  //             data: { year: year, dir:dir},
                  //             success: function (response) {
                  //                 // Add response in Modal body
                  //                 $('.modal-content').html(response);
                  //                 // Display Modal
                  //                 $('#modal-remoto').modal('show');
                  //             }
                  //         });
                  //     }
                  // }
              ],
              initComplete: function () {
                  var column = this.api().column(4);
                  var select = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#periodopendiente')
                      .on('change', function () {
                          var val = $(this).val();
                          column.search(val).draw()
                      });

                  var offices = ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021'];
                  // column.data().toArray().forEach(function (s) {
                  //     s = s.split(',');
                  //     s.forEach(function (d) {
                  //         if (!~offices.indexOf(d)) {
                  //             offices.push(d);
                  //         }
                  //     })
                  // })
                  offices.sort();
                  offices.forEach(function (d) {
                      select.append('<option value="' + d + '">' + d + '</option>');
                  })


                  var column1 = this.api().column(2);
                  var select1 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#periododir')
                      .on('change', function () {
                          var val1 = $(this).val();
                          column1.search(val1).draw()
                      });

                  var offices1 = [];
                  column1.data().toArray().forEach(function (s) {
                      s = s.split(',');
                      s.forEach(function (d) {
                          if (!~offices1.indexOf(d)) {
                              offices1.push(d);
                          }
                      })
                  })
                  // console.log(offices1);
                  offices1.sort();
                  offices1.forEach(function (d) {
                      select1.append('<option value="' + d + '">' + d + '</option>');
                  })
              },
              buttons: [
                  {
                      text: 'Activos <i class="fa fa-sync"></i>',
                      className: 'btn btn-soft-success',
                      action: function (e, dt, node, config) {
                          reload_certs(1);
                      }
                  },
                  {
                      text: 'Inactivos <i class="fa fa-sync"></i>',
                      className: 'btn btn-soft-danger',
                      action: function (e, dt, node, config) {
                          reload_certs(0);
                      }
                  },
                  {
                      extend: 'excel',
                      text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                      className: 'btn btn-personalizado outline',
                      filename: 'Reporte de Vacaciones',
                      title: 'Reporte Boletas de Vacaciones',
                      exportOptions: {
                          columns: [0, 1, 2, 3, 4],
                      },
                  },
                  {
                      extend: 'pdfHtml5',
                      text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                      className: 'btn btn-personalizado outline',
                      download: ['donwload', 'open'],
                      orientation: 'landscape',
                      filename: 'Reporte de Vacaciones',
                      title: 'Reporte Boletas de Vacaciones',
                      exportOptions: {
                          columns: [0, 1, 2, 3, 4],
                      },
                      customize: function (doc) {
                          doc.styles.tableBodyEven.alignment = 'center';
                          doc.styles.tableBodyOdd.alignment = 'center';
                          doc.content.splice(0, 1);
                          doc.pageMargins = [20, 100, 20, 20];
                          doc['header'] = (function () {
                              return {
                                  columns: [
                                      {
                                          image: baner,
                                          width: 300,
                                          margin: [-50, -10]
                                      },
                                      {
                                          alignment: 'left',
                                          text: 'SAAS APP - MODULO DE CONTROL DE VACACIONES',
                                          fontSize: 6,
                                          margin: [-295, 50, 0, 0]
                                      },
                                      {
                                          alignment: 'left',
                                          bold: true,
                                          text: 'REPORTE DE VACACIONES',
                                          fontSize: 13,
                                          margin: [-100, 20]
                                      },
                                      {
                                          alignment: 'left',
                                          bold: true,
                                          text: '',
                                          fontSize: 10,
                                          margin: [-220, 40]
                                      },
                                      {
                                          alignment: 'left',
                                          text: 'FECHA: ' + formatDate1(Date()) + '\n HORA: ' + formatDate2(Date()),
                                          fontSize: 8,
                                          margin: [24, 0]
                                      },
                                  ],
                                  margin: 20
                              }
                          });

                      }
                  }

              ],
              rowCallback: function (row, data) {
                  instanciaBV.get_fotografia(data, row, 0);
              },
              "columnDefs": [{
                  "targets": 4,
                  "width": "15%"
              }]
          });
        },
        getReporte: function(){
          this.tableReporte = $('#tb_reporte').DataTable({
              ordering: true,
              order: [[1, "desc"]],
              pageLength: 25,
              bProcessing: true,
              paging: true,
              info: true,
              responsive: true,
              scrollY: '45vh',
              
              language: {
                  emptyTable: "No hay boletas para mostrar",
                  sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
              },
              ajax: {
                  url: "horarios/php/back/boletas/get_reporte.php",
                  type: "POST",
                  data: {
                      tipo: 1,//function () { return $('#id_tipo_filtro').val() },
                      f_ini: function () { return $('#fecha_i').val() },
                      f_fin: function () { return $('#fecha_f').val() },
                  },
                  error: function () {
                      $("#post_list_processing").css("display", "none");
                  }
              },
              "aoColumns": [
                  { "class": "text-center", mData: 'estado' },
                  { "class": "text-center", mData: 'vac_id' },
                  { "class": "text-center", mData: 'persona' },
                  { "class": "text-center", mData: 'sol' },
                  { "class": "text-center", mData: 'inicio' },
                  { "class": "text-center", mData: 'fin' },
                  { "class": "text-center", mData: 'dias' },
                  { "class": "text-center", mData: 'pre' },
                  { "class": "text-center", mData: 'diares' },
                  { "class": "text-center", mData: 'periodo' },
                  { "class": "text-center", mData: 'dir' },
                  { "class": "text-center", mData: 'accion' },
                  { "class": "text-center", mData: 'mesini' },
                  { "class": "text-center", mData: 'mesfin' },
              ],
              buttons: [
                  {
                      text: 'Recargar <i class="fa fa-sync"></i>',
                      className: 'btn btn-personalizado btn-bo',
                      action: function (e, dt, node, config) {
                          instanciaBV.RecargarReporte();
                          this.tableBoletas.columns(8).visible(false);
                      }
                  },
                  {
                      extend: 'excel',
                      text: '<i class="fas fa-file-excel" style="color:green;"></i> Exportar',
                      className: 'btn btn-personalizado outline',
                      filename: 'Reporte Boletas de Vacaciones',
                      title: 'Reporte Boletas de Vacaciones',
                      exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                      },
                  },
                  {
                      extend: 'pdfHtml5',
                      text: '<i class="fas fa-file-pdf" style="color:red;"></i> Exportar',
                      className: 'btn btn-personalizado outline',
                      download: ['donwload', 'open'],
                      orientation: 'landscape',
                      filename: 'Reporte Boletas de Vacaciones',
                      title: 'Reporte Boletas de Vacaciones',
                      exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                      },
                      customize: function (doc) {
                          doc.styles.tableBodyEven.alignment = 'center';
                          doc.styles.tableBodyOdd.alignment = 'center';
                          doc.content.splice(0, 1);
                          doc.pageMargins = [20, 100, 20, 20];
                          doc['header'] = (function () {
                              return {
                                  columns: [
                                      {
                                          image: baner,
                                          width: 300,
                                          margin: [-50, -10]
                                      },
                                      {
                                          alignment: 'left',
                                          text: 'SAAS APP - MODULO DE CONTROL DE VACACIONES',
                                          fontSize: 6,
                                          margin: [-295, 50, 0, 0]
                                      },
                                      {
                                          alignment: 'left',
                                          bold: true,
                                          text: 'REPORTE DE BOLETAS DE VACACIONES',
                                          fontSize: 13,
                                          margin: [-150, 20]
                                      },
                                      {
                                          alignment: 'left',
                                          bold: true,
                                          text: '',
                                          fontSize: 10,
                                          margin: [-220, 40]
                                      },
                                      {
                                          alignment: 'left',
                                          text: 'FECHA: ' + formatDate1(Date()) + '\n HORA: ' + formatDate2(Date()),
                                          fontSize: 8,
                                          margin: [24, 0]
                                      },
                                  ],
                                  margin: 20
                              }
                          });

                      }
                  }

              ],
              initComplete: function () {
                  instanciaBV.tableReporte.columns(8).visible(false);
                  var column = this.api().column(10);
                  var select = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter22')
                      .on('change', function () {
                          var val = $(this).val();
                          column.search(val).draw()
                      });
                  var offices = ['DESPACHO DEL SECRETARIO'
                      , 'SUBSECRETARIA ADMINISTRATIVA'
                      , 'SUBSECRETARIA DE SEGURIDAD'
                      , 'ASESORIA JURIDICA'
                      , 'AUDITORIA INTERNA'
                      , 'UNIDAD DE INSPECTORIA'
                      , 'DIRECCION ADMINISTRATIVA Y FINANCIERA'
                      , 'DIRECCION DE ASUNTOS INTERNOS'
                      , 'DIRECCION DE COMUNICACIONES E INFORMATICA'
                      , 'DIRECCION DE INFORMACION'
                      , 'DIRECCION DE RECURSOS HUMANOS'
                      , 'DIRECCION DE RESIDENCIAS'
                      , 'DIRECCION DE SEGURIDAD'
                      , 'SUBDIRECCION DE MANTENIMIENTO Y SERVICIOS GENERALES'
                      , 'S/D'
                  ];
                  offices.forEach(function (d) {
                      select.append('<option value="' + d + '">' + d + '</option>');
                  })
                  var column1 = this.api().column(0);
                  var select1 = $('<select class="form-control form-control-sm"><option value="">TODOS</option></select>')
                      .appendTo('#filter11')
                      .on('change', function () {
                          var val1 = $(this).val();
                          column1.search(val1).draw()
                      });
                  var offices1 = ['Solicitado', 'Autorizado en dirección', 'Anulado en dirección', 'Autorizado por RRHH', 'Anulado por RRHH'];
                  offices1.forEach(function (d) {
                      select1.append('<option value="' + d + '">' + d + '</option>');
                  })
              },
              columnDefs: [
                  { responsivePriority: 0, targets: 11 },
                  { visible: false, targets: [12, 13] }
              ]
          });
        },
        recargarBoletas: function(tipo){
          $('#id_tipo_filtro').val(tipo);
          instanciaBV.tableBoletas.ajax.reload(null, false);
          instanciaBV.tableBoletas.columns(8).visible(false);
          instanciaBV.tableBoletas.column(1).order('desc');
          $(".btn-bo").removeClass("active");

          if(tipo == 1){
            $(".btn-bo1").addClass("active");
          }else if(tipo == 2){
            $(".btn-bo2").addClass("active");
          }else if(tipo == 3){
            $(".btn-bo3").addClass("active");
          }else if (tipo == 4){
            $(".btn-bo4").addClass("active");
          }

        },
        RecargarReporte: function(){
          instanciaBV.tableReporte.ajax.reload(null, false);
          //instanciaBV.tableReporte.columns(8).visible(false);
        },
        recargarVacaciones: function(){
          $('#id_1_0').val(tipo);
          instanciaBV.tableVacaciones.ajax.reload(null, false);
        },
        getBaseTable: function()
        {
          var $DataTable = jQuery.fn.dataTable;

          // Set the defaults for DataTables init
          jQuery.extend(true, $DataTable.defaults, {
              dom: "<'row'<'col-sm-4'B><'col-sm-8'f>>" +
                  "<'row'<'col-sm-12'tr>>" +
                  "<'row'<'col-sm-6'i><'col-sm-6'p>>",

              renderer: 'bootstrap',
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
              }
          });

          // Default class modification
          jQuery.extend($DataTable.ext.classes, {
              sFilterInput: "form-control",
              sLengthSelect: "form-control"
          });

          // Bootstrap paging button renderer
          $DataTable.ext.renderer.pageButton.bootstrap = function (settings, host, idx, buttons, page, pages) {
              var api = new $DataTable.Api(settings);
              var classes = settings.oClasses;
              var lang = settings.oLanguage.oPaginate;
              var btnDisplay, btnClass;

              var attach = function (container, buttons) {
                  var i, ien, node, button;
                  var clickHandler = function (e) {
                      e.preventDefault();
                      if (!jQuery(e.currentTarget).hasClass('disabled')) {
                          api.page(e.data.action).draw(false);
                      }
                  };

                  for (i = 0, ien = buttons.length; i < ien; i++) {
                      button = buttons[i];

                      if (jQuery.isArray(button)) {
                          attach(container, button);
                      }
                      else {
                          btnDisplay = '';
                          btnClass = '';

                          switch (button) {
                              case 'ellipsis':
                                  btnDisplay = '&hellip;';
                                  btnClass = 'disabled';
                                  break;

                              case 'first':
                                  btnDisplay = lang.sFirst;
                                  btnClass = button + (page > 0 ? '' : ' disabled');
                                  break;

                              case 'previous':
                                  btnDisplay = lang.sPrevious;
                                  btnClass = button + (page > 0 ? '' : ' disabled');
                                  break;

                              case 'next':
                                  btnDisplay = lang.sNext;
                                  btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                  break;

                              case 'last':
                                  btnDisplay = lang.sLast;
                                  btnClass = button + (page < pages - 1 ? '' : ' disabled');
                                  break;

                              default:
                                  btnDisplay = button + 1;
                                  btnClass = page === button ?
                                      'active' : '';
                                  break;
                          }

                          if (btnDisplay) {
                              node = jQuery('<li>', {
                                  'class': classes.sPageButton + ' ' + btnClass,
                                  'aria-controls': settings.sTableId,
                                  'tabindex': settings.iTabIndex,
                                  'id': idx === 0 && typeof button === 'string' ?
                                      settings.sTableId + '_' + button :
                                      null
                              })
                                  .append(jQuery('<a>', {
                                      'href': '#'
                                  })
                                      .html(btnDisplay)
                                  )
                                  .appendTo(container);

                              settings.oApi._fnBindAction(
                                  node, { action: button }, clickHandler
                              );
                          }
                      }
                  }
              };

              attach(
                  jQuery(host).empty().html('<ul class="pagination"/>').children('ul'),
                  buttons
              );
          };

          // TableTools Bootstrap compatibility - Required TableTools 2.1+
          if ($DataTable.TableTools) {
              // Set the classes that TableTools uses to something suitable for Bootstrap
              jQuery.extend(true, $DataTable.TableTools.classes, {
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
              jQuery.extend(true, $DataTable.TableTools.DEFAULTS.oTags, {
                  "collection": {
                      "container": "ul",
                      "button": "li",
                      "liner": "a"
                  }
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

    viewModelBoletas.$mount('#app_boletas');
    instanciaBV.getBaseTable();
    instanciaBV.getBoletas();

    instanciaBV.getReporte();

    setTimeout(function(){
      instanciaBV.getVacaciones();
    }, 1000);




//instanciaPD = viewModelFormularioDetalle;

//instanciaPD.proveedorFiltrado();

})

function recargarLasBoletas(tipo){
  instanciaBV.recargarBoletas(tipo);
}
