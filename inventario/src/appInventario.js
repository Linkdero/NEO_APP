import { EventBus } from './eventBus.js';
//import { viaticoheaders } from './components/GlobalComponents.js';
//import { viewModelInventarioDetalle } from './appInventarios.js';

$(document).ready(function(){
  var instancia;
  const viewModelInventario = new Vue({
    el: '#appInventario',
    data: {
      tableInventario:"",
      tableCertificaciones:"",
      bsDataTables:"",
      codigos:"",
      totalChequeados:0,
      api:"",
      codigos:"",
    },
    components:{
      //viaticoheaders
    },
    computed:{

    },
    created: function(){
      this.$nextTick(() => {
        this.baseTables();
        this.getInventario();
        this.getCertificaciones();
        //this.getInventarioList();
      });

      EventBus.$on('recargarInventarioTable', () => {
        this.recargarInventario();
      });

      EventBus.$on('recargarCertificacionesTable', (data) => {
        this.rercargarCertificaciones(data.clase, data.opcion);
      });
    },
    methods: {
      getInventarioList: function(){
        axios.get('http://127.0.0.1:8181/wsinventario/code', {

          params:
          {
            bien_sicoin_code:'000EBD00'
          }
        })
        .then(function (response) {
          console.log('DATOS'+response.data);
        }.bind(this))
        .catch(function (error) {
          console.log(error);
        });



      },
      generarQR: function(){
        //inicio
        const $imagen = document.querySelector("#codigo"),
    			$boton = document.querySelector("#btnDescargar");
    		var qrcode = new QRious({
    			element: $imagen,
    			value: "https://parzibyte.me/blog", // La URL o el texto
    			size: 500,
    			backgroundAlpha: 0, // 0 para fondo transparente
    			foreground: "#8bc34a", // Color del QR
    			level: "H", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
    		});
        //fin
      },
      getInventario: function(){
        var thisInstance = this;
        this.tableInventario = $('#tb_inventario').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          //"paging":true,
          "info":true,
          'processing': true,
          'serverSide': true,
          'serverMethod': 'post',
          select:true,
          //responsive:true,
          /*scrollX:        true,
          scrollY: false,
          scrollCollapse: true,

          fixedColumns:   true,
          fixedColumns: {
            leftColumns: 1
          },*/
          language: {
            emptyTable: "No hay bienes para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
            url :"inventario/model/inventario.php",
            type: "POST",
            data:{
              opcion:1,
              //tipo:function() { return $('#id_tipo_filtro').val() }
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "columns": [
            { "class" : "text-center", data: 'bien_sicoin_code' },
            { "class" : "text-justify", data: 'bien_descripcion' },
            { "class" : "text-center", data: 'bien_monto' },
            { "class" : "text-center", data: 'bien_fecha_adquisicion' },
            { "class" : "text-center", data: 'bien_renglon_id' },
            { "class" : "text-center", data: 'bien_verificacion' },
            { "class" : "text-center", data: 'bien_estado' },
            { "class" : "text-center", data: 'bien_id' },
            { "class" : "text-center", data: 'accion' }
          ],
          buttons: [
            /*{
              text: 'Certificación <i class="fa fa-plus-circle"></i>',
              className: 'btn btn-soft-info btn-sm btn-v1 btn-via',
              action: function ( e, dt, node, config ) {
                viewModelInventario.sendBienes();
              }
            },*/
            {
              text: 'Recargar <i class="fa fa-sync"></i>',
              className: 'btn btn-soft-info btn-sm btn-v1 btn-via',
              action: function ( e, dt, node, config ) {
                viewModelInventario.recargarInventario();
              }
            },
          ]
        });

        $(document).on('click','#infoInventarioD', function(){
          let id = $(this).data('id');
          let sicoin_code = $(this).data('type');
          let tipo = 1;
          thisInstance.showModal(id,sicoin_code,1,tipo);
        })

        $(document).on('click','#infoInventarioEdit', function(){
          let id = $(this).data('id');
          let sicoin_code = $(this).data('type');
          let tipo = 2;
          thisInstance.showModal(id,sicoin_code,1,tipo);
        })

        //inicio checkboxes
        $('#tb_inventario tbody').on('change', 'td label input', function () {
          var array = [];
          var tr = $(this).parents('tr');
          var row = thisInstance.tableInventario.row( tr );
          var d = row.data();
          var uniques;

          var codigos = '';
          var rengs = '';
          if ($(this).is(':checked')) {
            thisInstance.codigos +=','+d.DT_RowId;
          }
          else {
            thisInstance.codigos = thisInstance.codigos.replace(','+d.DT_RowId, '');
          }

          var rows_selected = thisInstance.tableInventario.column(7).checkboxes.selected();
          var total_ = thisInstance.tableInventario.data().count();
          if(rows_selected.length == total_){
            thisInstance.totalChequeados = 1;
          }else{
            thisInstance.totalChequeados = 0;
          }
        });
      },
      getCertificaciones: function(){
        var thisInstance = this;
        this.tableCertificaciones = $('#tb_certificaciones').DataTable({
          "ordering": false,
          "pageLength": 25,
          "bProcessing": true,
          "paging":true,
          "info":true,
          select:true,
          //responsive:true,
          /*scrollX:        true,
          scrollY: false,
          scrollCollapse: true,

          fixedColumns:   true,
          fixedColumns: {
            leftColumns: 1
          },*/
          language: {
            emptyTable: "No hay certificaciones para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
            //loadingRecords: " <div class='loaderr'></div> "
          },
          ajax:{
            url :"inventario/model/inventario.php",
            type: "POST",
            data:{
              opcion:9,
              year:2023,
              tipo:function() { return $('#id_filtro_c').val() }
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "aoColumns": [
            { "class" : "text-center", mData: 'certificacion_id' },
            { "class" : "text-justify", mData: 'bienes' },
            { "class" : "text-center", mData: 'fecha_certificacion' },
            { "class" : "text-center", mData: 'direccion' },
            { "class" : "text-center", mData: 'departamento' },
            { "class" : "text-center", mData: 'solicitante' },
            { "class" : "text-center", mData: 'certificacion_status' },
            { "class" : "text-center", mData: 'generador' },
            { "class" : "text-center", mData: 'generador_tipo' },
            { "class" : "text-center", mData: 'accion' },
            /*{ "class" : "text-center", mData: 'accion' }*/
          ],
          buttons: [
            {
              text: 'Nueva <i class="fa fa-plus-circle"></i>',
              className: 'btn btn-personalizado btn-sm btn-v1 btn-via',
              action: function ( e, dt, node, config ) {
                viewModelInventario.sendBienes();
              }
            },
            {
              text: 'Generadas <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-ce1 btn-cert active',
              action: function ( e, dt, node, config ) {
                viewModelInventario.rercargarCertificaciones('btn-ce1',1);
              }
            },
            {
              text: 'Entregadas <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-ce2 btn-cert',
              action: function ( e, dt, node, config ) {
                viewModelInventario.rercargarCertificaciones('btn-ce2',2);
              }
            },
          ],
          'select':{
            'style':'multi'
          },
        });

        $(document).on('click','#idEntregar', function(){
          let id = $(this).data('id');
          let direccion_id = $(this).data('type');

          thisInstance.showModal(id,direccion_id,2);
        })

        //inicio checkboxes
        $('#tb_inventario tbody').on('change', 'td label input', function () {
          var array = [];
          var tr = $(this).parents('tr');
          var row = thisInstance.tableInventario.row( tr );
          var d = row.data();
          var uniques;

          var codigos = '';
          var rengs = '';
          if ($(this).is(':checked')) {
            thisInstance.codigos +=','+d.DT_RowId;
          }
          else {
            thisInstance.codigos = thisInstance.codigos.replace(','+d.DT_RowId, '');
          }

          var rows_selected = thisInstance.tableInventario.column(7).checkboxes.selected();
          var total_ = thisInstance.tableInventario.data().count();
          if(rows_selected.length == total_){
            thisInstance.totalChequeados = 1;
          }else{
            thisInstance.totalChequeados = 0;
          }
        });
      },
      eventHeader: function(){
        //console.log('conteo: '+instanciaI.tableFacturas.column(0).checkboxes.selected());
        var instanciaI = this;

        var total_ = instanciaI.tableInventario.data().count();
        var rows_selected1 = instanciaI.tableInventario.rows({ selected: true }).data();
        var rows_selected = instanciaI.tableInventario.column(7).checkboxes.selected();
        //console.log(rows_selected1);

        //var rows_selected = viewModelFactura.tableFacturas.column(0).checkboxes.selected();

        if(rows_selected.length == 0){
          instanciaI.totalChequeados = 0;
        }

        if(this.totalChequeados == 0){

          $.each(rows_selected, function(index, rowId){
            var rows = instanciaI.tableInventario.row( '#' + rowId  ).data();
            var data = instanciaI.tableInventario.row( this ).data();
            instanciaI.codigos +=','+data.DT_RowId;
          })

          if(rows_selected.length == total_){
            instanciaI.totalChequeados = 1;
          }else{
            instanciaI.totalChequeados = 0;
          }
        }else{
          instanciaI.totalChequeados = 0;
        }
      },
      sendBienes: function(tipo, arreglo){
        var imgModal = $('#modal-remoto-lg');
        var imgModalBody = imgModal.find('.modal-content');
        $.ajax({
          type: "GET",
          url: "inventario/views/BienCertificacion.php",
          data:{
            /*tipo:tipo,
            arreglo: codigos,*/
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
                   sNext: '<i class="fa fa-angle-right"></i>'
                   }*/
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
      recargarInventario: function(){
        this.tableInventario.ajax.reload(null,false);
      },
      rercargarCertificaciones: function(classe,opc){
        $('#id_filtro_c').val(opc);
        $('.btn-cert').removeClass('active');
        $('.'+classe).addClass('active');
        this.tableCertificaciones.ajax.reload(null,false);
      },
      showModal: function(id,sicoin_code,tipo,type){
        var params, url;
        if(tipo == 1){
          url = 'BienDetalle';
          params = { bien_id:id,sicoin_code:sicoin_code,tipo:type };
        }else if(tipo == 2){
          params = { certificacion_id: id, direccion_id:sicoin_code };
          url = 'BienCertificacionEntrega';
        }
        var imgModal = $('#modal-remoto');
        var imgModalBody = imgModal.find('.modal-content');
        //let id_persona = parseInt($('#bar_code').val());
        $.ajax({
          type: "GET",
          url: "inventario/views/"+url+".php",
          dataType: 'html',
          data: params,
          beforeSend: function () {
            imgModal.modal('show');
            imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
          },
          success: function (data) {
            imgModalBody.html(data);
          }
        });

      }
    }
  })
  instancia = viewModelInventario;
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
})
