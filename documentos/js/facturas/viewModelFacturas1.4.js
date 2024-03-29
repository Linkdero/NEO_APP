var instanciaF = '';
//import { EventBus } from '../components/eventBus.js';
var eventBusPACD = new Vue();

$(document).ready(function () {
  viewModelFactura = new Vue({
    el: '#app_facturas',
    data: {
      tableFacturas: "",
      privilegio:"",
      buttons:[],
      buttonss:[],
      buttonsO:[],
      tipoBajaCuantia:0,
      tipoNog:0,
      uniqs:[],
      tableOrdenes:"",
      scrollX:0,
      ancho:0,
      tipoFiltroFa:0,
      buttonActual:'btn-f1',
      chartFactura:'',
      chartFacturaN:'',
      chartFacturaC:'',
      dataChart:[],
      dataChartN:[],
      dataChartC:[],
      totalChequeados: 0,
      opcion: $('#idopcion').val(),
      opcOrdenes:0,
      classOrdenes:'btn-fo1',
      tableOficios: "",
      tableCheques: "",
      registroDatos: 0,

      arregloOrdenes:0,
      arregloCurC:0,
      arregloCurD:0,
      totalChequeadosO:0,
      uniquesOrders:[],
      uniques:[],
      tableFacturasReporte:"",

      cPendientePublicar:0,
      cPendienteAsignarT:0,
      cPendienteAsignarCP:0
    },
    mounted() {
      instanciaF = this;
    },
    computed: {

    },
    created: function () {
      //this.$nextTick(() => {

      //});



      this.showButtons();
      this.showButtonsO();
      setTimeout(() => {
        if(this.privilegio.presupuesto_au==true){
          this.opcion = 2;
          //this.setEditable();
        }else{
          this.opcion = 1;

        }
      }, 390);


      /*EventBus.$on('reloadTableInvoices', (data) => {
        this.recargarFacturas(data.opcion, data.clase)
      });*/
    },
    methods: {
      showOpcion: function(opc){
        this.opcion = opc;

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
             $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
         });


      },
      getPermisosUserF: function(data){
        this.privilegio = data;
        console.log('privi :'+this.privilegio);
      },
      addInvoice: function(tipo,arreglo,tipoOperacion){

        var imgModal = $('#modal-remoto');
        var imgModalBody = imgModal.find('.modal-content');
        //let id_persona = parseInt($('#bar_code').val());
        $.ajax({
          type: "GET",
          url: "documentos/php/front/factura/factura_nueva.php",
          dataType: 'html',
          data:{
            tipo:tipo,
            arreglo:arreglo,
            tipoOpe:tipoOperacion
          },
          beforeSend: function () {
            imgModal.modal('show');
            imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
          },
          success: function (data) {
            $(".btn-add-fact").removeClass("active");
            imgModalBody.html(data);
          }
        });
      },
      showButtons: function(){

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
        if(viewModelFactura.tipoFiltroFa == 0){
          viewModelFactura.tableFacturas.columns(2).visible(true);


          if( $('#filterdays').is(':empty') ) {
            //inicio
            var column = viewModelFactura.tableFacturas.data();
            var columnDays = viewModelFactura.tableFacturas.column(2);
            var days = [];
            column.data().toArray().forEach(function (da) {
              if (!~days.indexOf(da.days)) {
                days.push(da.days);
              }
            })

            //fin
            var select2 = $('<select id="id_filter_days" class="form-control  form-control-sm"><option value="">TODOS</option></select>')
            .appendTo('#filterdays')
            .on('change', function () {
              var val2 = $(this).val();
              columnDays.search(val2).draw();
            });

            setTimeout(() => {
              days.map(function(v) {
                var x = document.getElementById("id_filter_days");
                var option = document.createElement("option");
                option.value = v;
                console.log(option.value);
                option.text = v;
                x.add(option);
                //select2.append('<option value="'+x+'>' + x + '</option>');
              });
            }, 900);
          }
        }else{
          $('#filterdays').html('');
          viewModelFactura.tableFacturas.columns(2).visible(false);
        }
        viewModelFactura.tableFacturas.cells(
          viewModelFactura.tableFacturas.rows(function (idx, data, node) {
            var validacion = (data.factura_publicada === 2) ? true : false;
            return validacion;
          }).indexes(),
        0).checkboxes.disable();

        if(this.privilegio.tesoreria == true){
          viewModelFactura.tableFacturas.columns(13).visible(false);
          viewModelFactura.tableFacturas.columns(14).visible(false);
          viewModelFactura.tableFacturas.columns(15).visible(false);
          viewModelFactura.tableFacturas.columns(16).visible(false);
          viewModelFactura.tableFacturas.columns(17).visible(false);
          viewModelFactura.tableFacturas.columns(18).visible(false);

          viewModelFactura.tableFacturas.columns(20).visible(false);
          viewModelFactura.tableFacturas.columns(21).visible(false);
        }

      },
      table_facturas: function () {
        var thisInstance = this;
        return new Promise((resolve) => {
          console.log(name, "start");
          resolve(console.log(name, "middle"));
        }).then(() => {
          console.log(name, "end");
          setTimeout(() => {
            //inicio
            var x1 = document.getElementById("botonesF");//.style.visibility = 'visible';
            var x2 = document.getElementById("botonesO");//.style.visibility = 'visible';

            x1.removeAttribute("hidden");
            x2.removeAttribute("hidden");
            //inicio
            if(this.privilegio.facturas == true){
              this.tableFacturas = $('#tb_facturas').DataTable({
                'initComplete': function (settings, json) {
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
                dom:
                  "<'row'<'col-sm-2'><'col-sm-6 text-center'B><'text-right col-sm-4'f>>" +

                  "<'row'<'col-sm-12'tr>>" +
                  "<'row'<'col-sm-6'i><'col-sm-6'p>>",
                //responsive: true,
                oLanguage: {
                  sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
                },
                language: {
                  emptyTable: "No hay Pedidos para mostrar",
                  sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                },
                ajax: {
                  url: "documentos/php/back/listados/get_facturas.php",
                  type: "POST",
                  data: {
                    tipo: function () { return $('#id_filtro_factura').val() },
                    year: function () { return $('#id_year').val() },
                    id_tipo_pago: function () { return $('#id_tipo_pago_filter').val() },
                    //privilegio: thisInstance.privilegio
                  },
                  error: function () {
                    $("#post_list_processing").css("display", "none");
                  }
                },
                "aoColumns": [
                  //{ "class": "text-center", mData: 'tipo_pago' },
                  { "class": "text-center sticky-col first-col", mData: 'orden_id' },
                  { "class": "text-center sticky-col second-col", mData: 'diferencia' },
                  { "class": "text-center", mData: 'dias' },
                  { "class": "text-center", mData: 'tipo_pago' },
                  { "class": "text-center ancho-fecha", mData: 'fecha_recepcion' },
                  { "class": "text-center ancho-fecha", mData: 'factura_fecha' },

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
                buttons: instanciaF.buttonss,
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
                    "targets": [ 14 ], "visible": false,
                    searchable: false,
                  },//,
                ],
                'select': {
                  'style': 'multi',
                  selector: 'td:first-child'
                },
                render: function (data, type, row) {
                  $(row).addClass('danger');
                },
                drawCallback: function () {

                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                  //$(nRow).addClass(aData.class);
                  //$(nRow).css("background-color", "red");
                  $('td:eq(1)', nRow).css('background-color', aData.class);
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

                  if(d.nro_orden > 0){
                    instanciaF.registroDatos += 1;
                  }
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
                  if(d.nro_orden > 0){
                    instanciaF.registroDatos -= 1;
                  }
                  if (d.tipo == 1) {
                    instanciaF.tipoBajaCuantia -= 1;
                    instanciaF.totalChequeados = 0;
                  } else {
                    instanciaF.tipoNog -= 1;
                    instanciaF.totalChequeados = 0;
                  }
                  var idx = instanciaF.uniqs.indexOf(d.orden_id);
                  //if(viewModelViaticoDetalle.uniqs.length > ){
                  instanciaF.uniqs.splice(idx, 1);
                  //}
                }

                //var rows_selected1 = viewModelFactura.tableFacturas.rows({ selected: true }).data();
                var rows_selected = instanciaF.tableFacturas.column(0).checkboxes.selected();
                var total_ = instanciaF.tableFacturas.data().count();
                if(rows_selected.length == total_){
                  instanciaF.totalChequeados = 1;
                }else{
                  instanciaF.totalChequeados = 0;
                }
              });

            }

            var buttons = new $.fn.dataTable.Buttons(instanciaF.tableFacturas, {
              buttons: [
                /*'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'*/
                { extend: 'excel', title: 'Listado de facturas',text: '<i class="fa fa-file-excel"></i>', className: 'alerta_no tooltip_noti btn btn-sm btn-outline-info btn-fact', 'data-tooltip': 'Exportar a excel'}
              ]
            }).container().appendTo($('#botonesF'));
            //fin
          }, 300);


        });
      },
      eventHeader: function(){
        //console.log('conteo: '+instanciaF.tableFacturas.column(0).checkboxes.selected());

        var total_ = instanciaF.tableFacturas.data().count();
        var rows_selected1 = instanciaF.tableFacturas.rows({ selected: true }).data();
        var rows_selected = instanciaF.tableFacturas.column(0).checkboxes.selected();
        //console.log(rows_selected1);

        instanciaF.tipoBajaCuantia = 0;
        instanciaF.tipoNog = 0;

        //var rows_selected = viewModelFactura.tableFacturas.column(0).checkboxes.selected();

        if(rows_selected.length == 0){
          instanciaF.registroDatos = 0;
        }

        if(this.totalChequeados == 0){

          $.each(rows_selected, function(index, rowId){
            var rows = instanciaF.tableFacturas.row( '#' + rowId  ).data();
            var data = instanciaF.tableFacturas.row( this ).data();
            if(rows.nro_orden > 0){
              instanciaF.registroDatos += 1;
            }

            if (rows.tipo == 1) {
              instanciaF.tipoBajaCuantia += 1;
            } else {
              instanciaF.tipoNog += 1;
            }
          })

          if(rows_selected.length == total_){
            instanciaF.totalChequeados = 1;
          }else{
            instanciaF.totalChequeados = 0;
          }
        }else{
          this.totalChequeados = 0;
          instanciaF.tipoBajaCuantia = 0;
          instanciaF.tipoNog = 0;

          instanciaF.registroDatos = 0;
        }
      },
      table_ordenes: function () {
        var thisInstance = this;
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
            scrollX: true,
            /*fixedColumns: true,
            fixedColumns: {
              //leftColumns:1,
              rightColumns: 1,
            },*/
            dom:
              "<'row'<'col-sm-4'><'col-sm-4 texte-center'><'col-sm-4'f>>" +
              //"<'row'<'col-sm-12 text-center'B><'col-sm-12 text-center'f>>" +
              "<'row'<'col-sm-12'tr>>" +
              "<'row'<'col-sm-6'i><'col-sm-6'p>>",
            //responsive: true,
            oLanguage: {
              sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
            },
            language: {
              emptyTable: "No hay Pedidos para mostrar",
              sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            },
            ajax: {
              url: "documentos/php/back/orden/listados/get_ordenes_group.php",
              type: "POST",
              data: {
                tipo: function () { return $('#id_filtro_orden').val() },
                year: function () { return $('#id_year_registro').val() },
                privilegio: thisInstance.privilegio
              },
              error: function () {
                $("#post_list_processing").css("display", "none");
              }
            },
            "aoColumns": [
              { "class": "text-left", mData: 'estado' },
              { "class": "text-center", mData: 'clase_proceso' },
              { "class": "text-center", mData: 'registro' },

              /*{ "class": "text-center", mData: 'comdev' },
              { "class": "text-center", mData: 'cyd' },*/
              { "class": "text-center", mData: 'cur_c' },
              { "class": "text-center", mData: 'nro_liquidacion' },
              { "class": "text-center", mData: 'cur_d' },
              { "class": "text-justify", mData: 'proveedor' },
              { "class": "text-justify", mData: 'facturas' },
              { "class": "text-right", mData: 'total' },
              { "class": "text-justify", mData: 'creador' },

              { "class": "text-center", mData: 'accion' },
              { "class": "text-center", mData: 'id_pago' },

            ],
            "columnDefs": [
              {
                responsivePriority: 1, targets: [0,1,2,3,4,5,6,7,8,9,10],
              },
              {
                //responsivePriority: 1, targets: [0,1,2,3,4,5,6,7,8],
                'targets': 11,
                'checkboxes': {
                  'selectRow': true
                },
              },
              {
                "targets": 3,
                "render": function ( data, type, row, meta ) {
                  var editable = '';
                  if(row.id_bitacora  == 2 && row.cur_c == '' && row.presupuesto == 'true'){
                    editable += '<span class="cur_dato" data-pk="'+row.id_pago+'-1-'+row.id_clase_proceso+'" data-name="1"></span>';
                    establecerEditable('Ingresar CUR-C');
                  }else{
                    editable += row.cur_c;
                  }
                  return editable;
                },
              },
              {
                "targets": 4,
                "render": function ( data, type, row, meta ) {
                  var editable = '';
                  if((row.compras_au == 'true' || row.compras_tecnico == 'true') && row.id_bitacora  == 2 && row.id_seguimiento == 11 && row.nro_liquidacion == ''){
                    editable += '<span class="cur_dato" data-pk="'+row.id_pago+'-3-'+row.id_clase_proceso+'" data-name="1"></span>';
                    establecerEditable('Ingresar liquidación');
                  }else{
                    editable += row.nro_liquidacion;
                  }
                  return editable;
                },
              },
              {
                "targets": 5,
                "render": function ( data, type, row, meta ) {
                  var editable = '';
                  if(row.id_seguimiento == 14 && row.cur_d == '' && row.subdirector == 'true'){
                    editable += '<span class="cur_dato" data-pk="'+row.id_pago+'-2-'+row.id_clase_proceso+'" data-name="1"></span>';
                    establecerEditable('Ingresar CUR-D');
                  }else{
                    editable += row.cur_d;
                  }
                  return editable;
                },
              }
            ],
            'select': {
              'style': 'multi',
              selector: 'td:first-child'
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

          function establecerEditable(texto){
            setTimeout(() => {
              $('.cur_dato').editable({
                  url: 'documentos/php/back/orden/operar_orden.php',
                  mode: 'inline',
                  emptytext: texto,
                  tpl: "<input type='text' style='width: 80px' class='form-control-sm'>",
                  ajaxOptions: { dataType: 'json' },
                  type: 'number',
                  display: function(value, response) {
                    return false;   //disable this method
                  },
                  success: function(response, newValue) {
                    if(response.msg=='Done'){
                      $(this).text(response.valor_nuevo);
                      thisInstance.recargarOrdenes(0,'btn-fo1');
                    }
                  }
                });
            }, 400);
          }

          $('#tb_ordenes tbody').on('click', 'td label input', function () {
            var array = [];
            var tr = $(this).parents('tr');
            var row = instanciaF.tableOrdenes.row(tr);
            var d = row.data();
            var uniques;
            if ($(this).is(':checked')) {
              if(d.cur_c > 0){
                instanciaF.arregloCurC += 1;
              }
              instanciaF.arregloOrdenes +=1;
            }else{
              if(d.cur_c > 0){
                instanciaF.arregloCurC -= 1;
              }
              instanciaF.arregloOrdenes -=1;
            }
            var rows_selected = instanciaF.tableOrdenes.column(11).checkboxes.selected();
            var total_ = instanciaF.tableOrdenes.data().count();
            if(rows_selected.length == total_){
              instanciaF.totalChequeadosO = 1;
            }else{
              instanciaF.totalChequeadosO = 0;
            }
          });
          /*$("#toolbar").html('<div class="float-right">'+
                        '<form class="form-inline">' +
                            '<div class= "form-group" > ' +
                                '<div class="input-group">' +
                                    '<input type="text" class="form-control form-control-light" id="dash-daterange">' +
                                    '<div class="input-group-append">' +
                                        '<span class="input-group-text bg-primary border-primary text-white">' +
                                            '<i class="mdi mdi-calendar-range font-13"></i>' +
                                        '</span>' +
                                    '</div >' +
                                '</div >' +
                            '</div >' +
                            '<a class="btn btn-primary ml-2">' +
                                '<i class="mdi mdi-filter-variant"></i>' +
                            '</a>'+
                        '</form>' +
                    '</div>');
                    $('#example-select-all').on('click', function(){
     // Get all rows with search applied
     var rows = table.rows({ 'search': 'applied' }).nodes();
     // Check/uncheck checkboxes for all rows in the table
     $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });*/
          //fin
          var buttons = new $.fn.dataTable.Buttons(instanciaF.tableOrdenes, {
            buttons: [
              /*'copyHtml5',
              'excelHtml5',
              'csvHtml5',
              'pdfHtml5'*/
              { extend: 'excel', text: '<i class="fa fa-download"></i> Exportar Excel', className: 'btn btn-sm btn-outline-info btn-fact'}
            ]
          }).container().appendTo($('#botonesO'));
        }, 150);


      },
      eventHeaderO: function(){
        //console.log('conteo: '+instanciaF.tableFacturas.column(0).checkboxes.selected());

        var total_ = instanciaF.tableOrdenes.data().count();
        var rows_selected1 = instanciaF.tableOrdenes.rows({ selected: true }).data();
        var rows_selected = instanciaF.tableOrdenes.column(11).checkboxes.selected();

        if(rows_selected.length == 0){
          //instanciaF.registro = 0;
        }

        this.arregloCurC = 0;
        this.arregloOrdenes = 0;

        if(this.totalChequeadosO == 0){

          $.each(rows_selected, function(index, rowId){
            var rows = instanciaF.tableOrdenes.row( '#' + rowId  ).data();
            var data = instanciaF.tableOrdenes.row( this ).data();
            if(rows.cur_c > 0){
              instanciaF.arregloCurC += 1;
            }
            //alert('chequeado');
            instanciaF.arregloOrdenes +=1;
          })

          if(rows_selected.length == total_){
            instanciaF.totalChequeadosO = 1;
          }else{
            instanciaF.totalChequeadosO = 0;
          }
        }else{
          this.totalChequeadosO = 0;
          instanciaF.arregloCurC = 0;
          instanciaF.arregloOrdenes = 0;
        }
      },

      getTableOficios: function () {

        setTimeout(() => {
          //inicio
          if(this.privilegio.facturas == true && this.privilegio.compras_recepcion == true){
            //inicio
            this.tableOficios = $('#tb_oficios').DataTable({
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
                "<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4 text-right'f>>" +
                //"<'row'<'col-sm-12 text-center'B><'col-sm-12 text-center'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
              //responsive: true,
              oLanguage: {
                sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
              },
              language: {
                emptyTable: "No hay Pedidos para mostrar",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
              },
              ajax: {
                url: "documentos/php/back/factura/listados/get_oficios.php",
                type: "POST",
                data: {
                  /*tipo: function () { return $('#id_filtro_orden').val() },
                  year: function () { return $('#id_year_registro').val() }*/
                },
                error: function () {
                  $("#post_list_processing").css("display", "none");
                }
              },

              "aoColumns": [
                { "class": "text-center", mData: 'oficionNum' },
                { "class": "text-center", mData: 'oficioFecha' },
                { "class": "text-center", mData: 'direccion' },
                { "class": "text-center", mData: 'estado' },
                { "class": "text-center", mData: 'persona_recibe' },


              ],
              buttons: [
                {
                  text: '<i class="fa fa-sync"></i> Recargar',
                  className: "btn btn-soft-info btn-sm",
                  action: function ( e, dt, node, config ) {
                    instanciaF.recargarOficios();
                  }
                }
              ],
              /*"columnDefs": [
                {
                  'targets': 0,
                  'checkboxes': {
                    'selectRow': true
                  },
                }
              ],
              'select': {
                'style': 'multi',
                selector: 'td:first-child'
              },*/
              "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

              }
            });
            //fin
          }

        }, 150);


      },
      getTableCheques: function () {

        setTimeout(() => {
          //inicio
          if(this.privilegio.tesoreria_recepcion == true){
            //inicio
            this.tableCheques = $('#tb_cheques').DataTable({
              "ordering": false,
              "pageLength": 25,
              "bProcessing": true,
              scrollY: '50vh',
              scrollCollapse: true,
              //paging: false,
              //select:true,
              "paging": true,
              "info": true,
              "deferRender": true,
              //select:true,
              /*scrollX: true,
              fixedColumns: true,
              fixedColumns: {
                rightColumns: 2
              },*/
              dom:
                "<'row'<'col-sm-4'><'col-sm-4 texte-center'B><'col-sm-4 text-right'f>>" +
                //"<'row'<'col-sm-12 text-center'B><'col-sm-12 text-center'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-6'i><'col-sm-6'p>>",
              //responsive: true,
              oLanguage: {
                sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
              },
              language: {
                emptyTable: "No hay Pedidos para mostrar",
                sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
              },
              ajax: {
                url: "documentos/php/back/voucher/listados/get_cheques_listado.php",
                type: "POST",
                data: {
                  /*tipo: function () { return $('#id_filtro_orden').val() },
                  year: function () { return $('#id_year_registro').val() }*/
                },
                error: function () {
                  $("#post_list_processing").css("display", "none");
                }
              },

              "aoColumns": [
                { "class": "text-center", mData: 'chequeNum' },
                { "class": "text-center", mData: 'facturas' },
                //{ "class": "text-center", mData: 'factura_serie' },
                /*{ "class": "text-center", mData: 'factura_fecha' },
                { "class": "text-center", mData: 'factura_num' },
                { "class": "text-center", mData: 'Prov_nom' },*/


              ],
              buttons: [
                {
                  text: '<i class="fa fa-sync"></i> Recargar',
                  className: "btn btn-soft-info btn-sm",
                  action: function ( e, dt, node, config ) {
                    instanciaF.recargarCheques();
                  }
                }
              ],
              "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

              }
            });
            //fin
          }

        }, 150);


      },
      cargarInput: function (tipo) {

        var rows_selected = instanciaF.tableFacturas.column(0).checkboxes.selected();
        var rows_selected1 = instanciaF.tableFacturas.rows({ selected: true }).data();
        var filtro = $('#id_filtro_factura').val();

        var codigos = '';
        var renglones = '';

        if (rows_selected.length == 0) {
          //mostrar mensaje
          var jsonTableData = '';
          Swal.fire(
            'Atención!',
            "Debe seleccionar al menos una factura",
            'error'
          );
        } else if (rows_selected.length > 0) {
          var x = [];
          $.each(rows_selected, function(index, rowId){
            //x.push(rowId);
            codigos += ',' + rowId;
          });

          //codigos = x;

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
                $('.btn-facts-proceso').removeClass('active');
                imgModalBody.html(data);
              }
            });
          }else if(instanciaF.tipoFiltroFa == 4 && tipo == 2){
            var imgModal = $('#modal-remoto');
            var imgModalBody = imgModal.find('.modal-content');
            if(instanciaF.registroDatos > 0){ //validar que ya tengan orden de compra
              //inicio
              Swal.fire(
                'Atención!',
                "Está seleccionado facturas que ya tienen asignado Clase de Proceso.",
                'error'
              );
              //fin
            }else{
              //inicio
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
                    $('.btn-facts-asig-tecnico').removeClass('active');
                    imgModalBody.html(data);
                  }
                });
              }
              //fin
            }
          }else
          // enviar a dirección y recibir de dirección
          if((tipo == 4 || tipo == 5) && (instanciaF.tipoFiltroFa == 0 || instanciaF.tipoFiltroFa == 4)){
            //let id_persona = parseInt($('#bar_code').val());
            var imgModal = $('#modal-remoto-lg');
            var imgModalBody = imgModal.find('.modal-content');
            $.ajax({
              type: "GET",
              url: "documentos/php/front/factura/entregar_global.php",
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
                $('.btn-facts-env-direccion').removeClass('active');
                imgModalBody.html(data);
              }
            });
          }else if(tipo == 6 && (instanciaF.tipoFiltroFa == 0 || instanciaF.tipoFiltroFa == 4)){
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
          }
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
      agregarChequeVue: function(orden_id){
        // asignar cheque
        //let id_persona = parseInt($('#bar_code').val());
        var imgModal = $('#modal-remoto');
        var imgModalBody = imgModal.find('.modal-content');
        $.ajax({
          type: "GET",
          url: "documentos/php/front/factura/asignar_cheque.php",
          data:{
            arreglo: orden_id
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
      },
      showOrdenDetalle: function(id_pago){
        var imgModal = $('#modal-remoto');
        var imgModalBody = imgModal.find('.modal-content');
        $.ajax({
          type: "GET",
          url: "documentos/php/front/orden/orden_detalle.php",
          data:{
            id_pago:id_pago
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
      },
      anularOrdenCompra: function(id_pago, tipo){
        var t_tipo = (tipo == "Orden de Compra") ? 'esta' : 'este';
        var thisInstance = this;
        Swal.fire({
          title: '<strong>¿Desea anular '+t_tipo +' '+tipo+'?</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Anular!'
        }).then((result) => {
          if (result.value) {
            //alert(vt_nombramiento);
            $.ajax({
              type: "POST",
              url: "documentos/php/back/orden/establecer_estado_orden.php",
              dataType: 'json',
              data: {
                id_pago:id_pago,
                estado:3
              }, //f de fecha y u de estado.

              beforeSend:function(){
              },
              success:function(data){
                //exportHTML(data.id);
                //recargarDocumentos();
                if(data.msg == 'OK'){
                  instanciaF.recargarOrdenes(0,thisInstance.classOrdenes);
                  Swal.fire({
                    type: data.icono,
                    title: data.message,
                    showConfirmButton: true,
                    //timer: 1100
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
        $('#id_filtro_factura').val(opc);
        $(".btn-fact").removeClass("active");
        this.tipoFiltroFa = $('#id_filtro_factura').val();

        instanciaF.tableFacturas.ajax.reload(viewModelFactura.iComplete, false);


        this.buttonActual = classe;
        $("."+classe).addClass("active");

        instanciaF.tipoBajaCuantia = 0;
        instanciaF.tipoNog = 0;

        //console.log(classe);
      },
      recargarOrdenes: function (opc,classe) {
        $('#id_filtro_orden').val(opc);
        this.tableOrdenes.ajax.reload(null, false);
        this.opcOrdenes = opc;
        this.classOrdenes = classe;
        $(".btn-for").removeClass("active");
        $("."+classe).addClass("active");
      },
      recargarOficios: function () {
        this.tableOficios.ajax.reload(null, false);
      },
      recargarCheques: function(){
        this.tableCheques.ajax.reload(null, false);
      },
      cargarInputO: function(type){
        var thisInstance = this;
        var rows_selected = instanciaF.tableOrdenes.column(11).checkboxes.selected();
        var rows_selected1 = instanciaF.tableOrdenes.rows({ selected: true }).data();
        //var filtro = $('#id_filtro_factura').val();
        //instanciaF.uniquesOrders = [];
        instanciaF.uniquesOrders.splice(0, instanciaF.uniquesOrders.length);
        var codigos = '';
        var renglones = '';

        if (rows_selected.length == 0) {
          //mostrar mensaje
          var jsonTableData = '';
          Swal.fire(
            'Atención!',
            "Debe seleccionar al menos un registro",
            'error'
          );
        } else if (rows_selected.length > 0) {

          $.each(rows_selected, function(index, rowId){
            //x.push(rowId);
            codigos += ',' + rowId;
            var rows = instanciaF.tableOrdenes.row( '#' + rowId  ).data();
            instanciaF.uniquesOrders.push(rows.id_bitacora + rows.id_seguimiento);
          });

          let unique = instanciaF.uniquesOrders.filter((item, i, ar) => ar.indexOf(item) === i);

          instanciaF.uniques = unique;

          if(unique.length == 1){
            //inicio
            var imgModal = $('#modal-remoto');
            var imgModalBody = imgModal.find('.modal-content');
            $.ajax({
              type: "GET",
              url: "documentos/php/front/orden/ordenes_seguimiento.php",
              data:{
                opcion: this.opcOrdenes,
                arreglo: codigos,
                privilegio:thisInstance.privilegio,
                estado:instanciaF.uniques,
                type:type
              },
              dataType: 'html',
              beforeSend: function () {
                imgModal.modal('show');
                imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
              },
              success: function (data) {
                $('.btn-seg-reg').removeClass('active');
                imgModalBody.html(data);
              }
            });
            //fin
          }else{
            Swal.fire(
              'Atención!',
              "Debe seleccionar registros que estén en la misma fase.",
              'error'
            );
          }
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
        var thisInstance = this;
        setTimeout(() => {
          if(this.privilegio.facturas == true){
            //inicio
            axios.get('documentos/php/back/graficas/get_chart_facturas', {
              params: {
                //year : $('#id_year_chart').val(),//function () { return $('#id_year_chart').val() }
              }
            }).then(function (response) {
              instanciaF.dataChart = response.data.dias;
              instanciaF.dataChartN = response.data.dias_n;
              instanciaF.dataChartC = response.data.dias_cheque;
              setTimeout(() => {
                if(instanciaF.privilegio.compras == true){
                  //inicio amchart
                  am4core.ready(function() {
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    instanciaF.chartFactura = am4core.create("chart_facturas", am4charts.PieChart);
                    // Add data
                    instanciaF.chartFactura.data = instanciaF.dataChart;//[{"cantidad":6,"series":"1 d\u00edas"},{"cantidad":41,"series":"2 dias"},{"cantidad":5,"series":"3dias"},{"cantidad":3,"series":"4 dias"},{"cantidad":4,"series":"5 dias"}];
                    // Add and configure Series
                    let pieSeries = instanciaF.chartFactura.series.push(new am4charts.PieSeries());
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
                  am4core.ready(function() {
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    instanciaF.chartFacturaN = am4core.create("chart_facturas_n", am4charts.PieChart);
                    // Add data
                    instanciaF.chartFacturaN.data = instanciaF.dataChartN;//[{"cantidad":6,"series":"1 d\u00edas"},{"cantidad":41,"series":"2 dias"},{"cantidad":5,"series":"3dias"},{"cantidad":3,"series":"4 dias"},{"cantidad":4,"series":"5 dias"}];
                    // Add and configure Series
                    let pieSeriesN = instanciaF.chartFacturaN.series.push(new am4charts.PieSeries());

                    pieSeriesN.dataFields.value = 'cantidad';
                    pieSeriesN.dataFields.category = 'series';
                    instanciaF.chartFacturaN.logo.height = -15000;
                    // Let's cut a hole in our Pie chart the size of 30% the radius
                    instanciaF.chartFacturaN.innerRadius = am4core.percent(30);

                    // Put a thick white border around each Slice
                    pieSeriesN.slices.template.stroke = am4core.color("#fff");
                    pieSeriesN.slices.template.strokeWidth = 2;
                    pieSeriesN.slices.template.strokeOpacity = 1;
                    //pieSeries.slices.template.propertyFields.fill = "color";
                    pieSeriesN.slices.template
                      // change the cursor on hover to make it apparent the object can be interacted with
                      .cursorOverStyle = [
                        {
                          "property": "cursor",
                          "value": "pointer"
                        }
                      ];

                    pieSeriesN.alignLabels = false;
                    pieSeriesN.labels.template.wrap = true;
                    pieSeriesN.labels.template.maxWidth = 80;
                    pieSeriesN.labels.template.text = "{series}: {value}.";//"{value.percent.formatNumber('#.0')}%";

                    let shadowN = pieSeriesN.slices.template.filters.push(new am4core.DropShadowFilter);
                    shadowN.opacity = 0;

                    // Create hover state
                    let hoverStateN = pieSeriesN.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

                    // Slightly shift the shadow and make it more prominent on hover
                    let hoverShadowN = hoverStateN.filters.push(new am4core.DropShadowFilter);
                    hoverShadowN.opacity = 0.7;
                    hoverShadowN.blur = 5;

                    // Add a legend
                    instanciaF.chartFacturaN.legend = new am4charts.Legend();

                  });
                }
                if(instanciaF.privilegio.tesoreria == true){
                  //inicio
                  am4core.ready(function() {
                    // Themes begin
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    instanciaF.chartFacturaC = am4core.create("chart_facturas_c", am4charts.PieChart);
                    // Add data
                    instanciaF.chartFacturaC.data = instanciaF.dataChartC;//[{"cantidad":6,"series":"1 d\u00edas"},{"cantidad":41,"series":"2 dias"},{"cantidad":5,"series":"3dias"},{"cantidad":3,"series":"4 dias"},{"cantidad":4,"series":"5 dias"}];
                    // Add and configure Series
                    let pieSeries = instanciaF.chartFacturaC.series.push(new am4charts.PieSeries());

                    pieSeries.events.on("hit", function(ev){
                      console.log(ev.target.dataItem);
                    })
                    pieSeries.dataFields.value = 'cantidad';
                    pieSeries.dataFields.category = 'series';
                    instanciaF.chartFacturaC.logo.height = -15000;
                    // Let's cut a hole in our Pie chart the size of 30% the radius
                    instanciaF.chartFacturaC.innerRadius = am4core.percent(30);

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
                    instanciaF.chartFacturaC.legend = new am4charts.Legend();
                  });
                  //fin
                }
                //fin amchart
              }, 900);


              //alert(response.data.validacion);
            }).catch(function (error) {
              console.log(error);
            });
            //fin
          }
        }, 150);



      },
      recargarGraficas: function(){
        $.ajax({
          type: "GET",
          url: "documentos/php/back/graficas/get_chart_facturas",
          data: {

          },
          dataType:'json',
          success:function(data){
            instanciaF.dataChart.dataProvider = data.dias;
            instanciaF.dataChartN.dataProvider = data.dias_n;

            }
          }).done( function() {
          }).fail( function( jqXHR, textSttus, errorThrown){
            alert(errorThrown);
          });
      },
      anularFactura: function(id){
        Swal.fire({
          title: '<strong>¿Desea Anular esta factura?</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Anular!'
        }).then((result) => {
          if (result.value) {
            //alert(vt_nombramiento);
            $.ajax({
              type: "POST",
              url: "documentos/php/back/factura/action/anular_factura.php",
              dataType: 'json',
              data: {
                id:id
              }, //f de fecha y u de estado.
              beforeSend:function(){
              },
              success:function(data){
                //exportHTML(data.id);
                //recargarDocumentos();
                if(data.msg == 'OK'){
                  instanciaF.recargarFacturas(0);
                  Swal.fire({
                    type: 'success',
                    title: 'Factura anulada',
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
      iCompleteTableReporte: function () {
        var thisInstance = this;
        var valueToFind1 = '<div style="width:150px"> <h5 class="text-danger"><i class="fa fa-check-circle"></i> Factura pendiente de publicar</h5></div>';
        var valueToFind2 = '<h5 class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Pendiente de asingar a técnico</h5>';
        var valueToFind3 = '<h5 class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Pendiente de asignar clase de proceso</h5>';



        this.cPendientePublicar = thisInstance.tableFacturasReporte.column( 2 )
        .data()
        .filter( function ( value, index ) {
            return value == valueToFind1 ? true : false;
        } ).count();

        this.cPendienteAsignarT = thisInstance.tableFacturasReporte.column( 3 )
        .data()
        .filter( function ( value, index ) {
            return value == valueToFind2 ? true : false;
        } ).count();

        this.cPendienteAsignarCP = thisInstance.tableFacturasReporte.column( 4 )
        .data()
        .filter( function ( value, index ) {
            return value == valueToFind3 ? true : false;
        } ).count();


        /*this.cPendientePublicar = thisInstance.tableFacturasReporte
       .column(2)
       .data()
       .filter( function ( value, index ) {
           return value == 'Factura pendiente de publica' ? true : false;
       } );*/
      },

      table_facturas_reporte: function () {
        var thisInstance = this;
        return new Promise((resolve) => {
          console.log(name, "start");
          resolve(console.log(name, "middle"));
        }).then(() => {

          setTimeout(() => {
            //inicio

            //inicio
            if(this.privilegio.facturas == true){
              this.tableFacturasReporte = $('#tb_reporte_facturas').DataTable({
                'initComplete': function (settings, json) {
                  viewModelFactura.iCompleteTableReporte();
                },
                "ordering": false,
                "pageLength": 10,
                "bProcessing": true,
                //scrollY: '50vh',
                scrollCollapse: true,
                //paging: false,
                select:true,
                "paging": true,
                "info": true,
                "deferRender": true,
                select:true,
                scrollX: true,
                dom:
                  "<'row'<'col-sm-2'><'col-sm-6 text-center'B><'text-right col-sm-4'f>>" +

                  "<'row'<'col-sm-12'tr>>" +
                  "<'row'<'col-sm-6'i><'col-sm-6'p>>",
                //responsive: true,
                oLanguage: {
                  sProcessing: "<i class='fa fa-sync fa-spin'></i> Procesando...",
                },
                language: {
                  emptyTable: "No hay Pedidos para mostrar",
                  sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                },
                ajax: {
                  url: "documentos/php/back/listados/get_facturas_reporte.php",
                  type: "POST",
                  data: {
                    //tipo: function () { return $('#id_filtro_factura').val() },
                    year: function () { return $('#id_year_re').val() },
                    //id_tipo_pago: function () { return $('#id_tipo_pago_filter').val() },
                    //privilegio: thisInstance.privilegio
                  },
                  error: function () {
                    $("#post_list_processing").css("display", "none");
                  }
                },
                "aoColumns": [
                  //{ "class": "text-center", mData: 'tipo_pago' },
                  //{ "class": "text-center sticky-col first-col", mData: 'orden_id' },
                  { "class": "text-center", mData: 'action' },
                  //{ "class": "text-left sticky-col second-col", mData: 'diferencia' },
                  { "class": "text-center", mData: 'dias' },
                  { "class": "text-center", mData: 'p_publicar' },
                  { "class": "text-center", mData: 'p_asignart' },
                  { "class": "text-center", mData: 'p_asignarr' },
                  { "class": "text-center", mData: 'fecha_estimada' },
                  { "class": "text-center", mData: 'tipo_pago' },
                  { "class": "text-center ancho-fecha", mData: 'fecha_recepcion' },
                  { "class": "text-center ancho-fecha", mData: 'factura_fecha' },

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
                  { "class": "text-center", mData: 'opciones' },

                ],
                buttons: [],//instanciaF.buttonss,
                columnDefs:
                [
                  {
                    target: 26,
                    visible: false,
                  },
                ],
                /*"columnDefs": [
                  {
                    'targets': 0,
                    'checkboxes': {
                      'selectRow': true
                    },
                  },
                  { "width": "15px", "targets": 2 },
                  { "width": "25%", "targets": 4 },
                  {
                    "targets": [ 14 ], "visible": false,
                    searchable: false,
                  },//,
                ],
                'select': {
                  'style': 'multi',
                  selector: 'td:first-child'
                },*/
                render: function (data, type, row) {
                  $(row).addClass('danger');
                },
                drawCallback: function (row, data) {
                  console.log(row.p_publicar + ' || || '+ data);

                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                  //$(nRow).addClass(aData.class);
                  //$(nRow).css("background-color", "red");

                  $('td:eq(1)', nRow).css('background-color', aData.class);
                }

              });
              //fin


            }

            var buttons = new $.fn.dataTable.Buttons(instanciaF.tableFacturasReporte, {
              buttons: [
                /*'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'*/
                { extend: 'excel', title: 'Listado de facturas',text: '<i class="fa fa-file-excel"></i>', className: 'alerta_no tooltip_noti btn btn-sm btn-outline-info btn-factr', 'data-tooltip': 'Exportar a excel'}
              ]
            }).container().appendTo($('#botonesFR'));


            //fin
          }, 300);


        });
      },
      changeReporteFactura: function(event){
        var thisInstance = this;
        console.log(event.currentTarget.value);
        /*alert(this.value)*/
        thisInstance.tableFacturasReporte.columns(26)
        .search( event.currentTarget.value )
        .draw();
      },
      reloadTableReporte: function(){
        instanciaF.tableFacturasReporte.ajax.reload(viewModelFactura.iCompleteTableReporte, false);
        //this.contarColumnas();
      },
      contarColumnas: function(){
        var thisInstance = this;
        this.cPendientePublicar = thisInstance.tableFacturasReporte
       .column(2)
       .data()
       .filter( function ( value, index ) {
           return value == 'Factura pendiente de publica' ? true : false;
       } );
      },
      setEditable: function(){
        setTimeout(() => {
          $('.cur_dato').editable({
              url: 'viaticos/php/back/viatico/update_motivo.php',
              mode: 'inline',
              ajaxOptions: { dataType: 'json' },
              type: 'number',
              display: function(value, response) {
                return false;   //disable this method
              },
              success: function(response, newValue) {
                if(response.msg=='Done'){
                  $(this).text(response.valor_nuevo);

                }
              }
            });
        }, 400);

      },
      addGlobalDir: function(){

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
      exportarTabla: function(clase){
        alert(clase);

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
      }

    }
  })
  instanciaF.baseTables();
  instanciaF.table_facturas();


  setTimeout(() => {
    instanciaF.table_ordenes();
    instanciaF.getGrafica();
    instanciaF.getTableOficios();
    instanciaF.getTableCheques();
    instanciaF.table_facturas_reporte();
  }, 900);
  $('#modal-remoto').on('click', '.close_fac_nueva', function () {
    $("#modal-remoto .modal-dialog").removeClass("modal-lg2g");
  });

  $('#modal-remoto-lgg2').on('click', '.salida', function () {
    var cambio = $('#id_cambio').val();
    //alert(cambio)
    if (cambio == 1) {
      instanciaF.recargarTabla();
    }

    $('#modal-remoto-lgg2').modal('hide');
  });
  $('#modal-remoto').on('click', '.salida', function () {
    var cambio = $('#cambio').val();
    //alert(cambio)
    if(cambio == 1){
      instanciaF.recargarFacturas();
      $('#modal-remoto').modal('hide');
    }
  });
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
       $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
   });
  $("#printButton").on("click", function() {
    alert('alkdfjñakdjfl')
      instanciaF.tableFacturas.button( '.buttons-print' ).trigger();
  });
})
function anularFactura(id){
  instanciaF.anularFactura(id);
}

function publicarFactura(orden_id,nog){
  instanciaF.publicarFacturaVue(orden_id,nog);
}

function agregarCheque(orden_id){
  instanciaF.agregarChequeVue(orden_id);
}

function ordenDetalleView(idPago){
  instanciaF.showOrdenDetalle(idPago)
}

function anularOrdenCompra(idPago,titulo){
  instanciaF.anularOrdenCompra(idPago,titulo);
}

function agregarDatosFactura(tipo,arreglo,operacion){
  instanciaF.addInvoice(tipo,arreglo,operacion);
}
