
$(document).ready(function(){
  var instancia;
  const viewModelInsumosFrac = new Vue({
    el: '#appInsumosFrac',
    data: {
      tableInsumos:"",
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
        this.getInsumos();

      });
    },
    methods: {
      getInsumos: function(){
        var thisInstance = this;
        this.tableInsumos = $('#tb_insumos_frac').DataTable({
          "ordering": false,
          //"pageLength": 25,
          "bProcessing": true,
          "paging":false,
          "info":true,
          'processing': true,
          /*'serverSide': true,
          'serverMethod': 'post',*/
          select:true,

          language: {
            emptyTable: "No hay bienes para mostrar",
            sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          ajax:{
            url :"documentos/php/back/functions_insumos.php",
            type: "POST",
            data:{
              opcion:6,
              id_cuatrimestre:function() { return $('#id_cuatrimestre').val() },
              id_year_c:function() { return $('#id_year_c').val() }
            },
            error: function(){
              $("#post_list_processing").css("display","none");
            }
          },
          "columns": [
            //{ "class" : "text-center", data: 'Ppr_id' },

            { "class" : "text-justify", data: 'Ppr_cod' },
            { "class" : "text-center", data: 'Ppr_codPre' },
            { "class" : "text-center", data: 'Ppr_Nom' },
            { "class" : "text-center", data: 'Ppr_Des' },
            { "class" : "text-center", data: 'Med_nom' },
            { "class" : "text-center", data: 'Ppr_Pres' },
            { "class" : "text-center", data: 'Ppr_Ren' },
            //{ "class" : "text-center", data: 'Ppr_est' },

            { "class" : "text-center", data: 'cuatrimestre' },
            { "class" : "text-center", data: 'year' },
            { "class" : "text-center", data: 'gastado' },

            { "class" : "text-center", data: 'accion' }
          ],
          buttons: [
            /*{
              text: 'Nuevo <i class="fa fa-plus"></i>',
              className: 'btn btn-personalizado btn-sm btn-v1 btn-via',
              action: function ( e, dt, node, config ) {
                var imgModal = $('#modal-remoto');
                var imgModalBody = imgModal.find('.modal-content');
                //let id_persona = parseInt($('#bar_code').val());
                $.ajax({
                  type: "GET",
                  url: "documentos/php/front/insumos/insumo_formulario.php",
                  dataType: 'html',
                  data:{
                    medida_id:'',
                    tipo:1,
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
              }
            },
            {
              text: 'Activos <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-i1 btn-ia active',
              action: function ( e, dt, node, config ) {
                viewModelInsumosFrac.recargarInsumos(1,'btn-i1');
              }
            },
            {
              text: 'Inactivos <i class="fa fa-sync"></i>',
              className: 'btn btn-personalizado btn-sm btn-i2 btn-ia',
              action: function ( e, dt, node, config ) {
                viewModelInsumosFrac.recargarInsumos(0,'btn-i2');
              }
            },*/
          ]
        });

        $(document).on('click','#infoInsumoD', function(){
          let id = $(this).data('id');
          let sicoin_code = $(this).data('type');
          let tipo = 1;
          thisInstance.showModal(id,sicoin_code,1,tipo);
        })

        $(document).on('click','#infoInsumoEdit', function(){
          let id = $(this).data('id');
          let sicoin_code = $(this).data('type');
          let tipo = 2;
          thisInstance.showModal(id,sicoin_code,1,tipo);
        })

        //inicio checkboxes
        $('#tb_insumos tbody').on('change', 'td label input', function () {
          var array = [];
          var tr = $(this).parents('tr');
          var row = thisInstance.tableInsumos.row( tr );
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

          var rows_selected = thisInstance.tableInsumos.column(7).checkboxes.selected();
          var total_ = thisInstance.tableInsumos.data().count();
          if(rows_selected.length == total_){
            thisInstance.totalChequeados = 1;
          }else{
            thisInstance.totalChequeados = 0;
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
      recargarFraccionamiento: function(){

        this.tableInsumos.ajax.reload(null,false);
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
  instancia = viewModelInsumosFrac;
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
