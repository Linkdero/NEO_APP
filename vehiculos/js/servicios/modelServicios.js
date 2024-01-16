const viewModelServicios = new Vue({
  el: '#appServicios',

  data: {
    tableServicios: "",
    className: '',
    textName: ''
  },
  //Para que se ejecute al inicar el modulo
  created: function () {
    this.$nextTick(() => {
      if ($("#servicios").val() == 1) {
        this.className = 'btn btn-personalizado btn-sm btn-st-1 btn-solt agregarServicio',
          this.textName = 'Agregar <i class="fa fa-plus"></i>'
      }
      this.baseTables();
      this.cargarTablaServicios();
    });
  },

  //Asi generamos funciones en VUE
  methods: {
    recargarServicios: function (opc, clase) {
      $('.btn-solt').removeClass('active');
      $('.' + clase).addClass('active');
      $('#id_filtro').val(opc);   //tenes que crear un input con ese id
      this.tableServicios.ajax.reload(null, false);
    },

    cargarTablaServicios: function () {
      //inicio
      let thes = this;
      this.tableServicios = $('#tb_servicios').DataTable({
        "ordering": false,
        "pageLength": 25,
        "bProcessing": true,
        "paging": true,
        "info": true,
        select: true,
        responsive: true,
        scrollX: true,
        scrollY: '50vh',

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
          url: "vehiculos/php/back/listados/get_servicios.php",
          type: "POST",
          data: {
            opcion: 1,
            estado: function () { return $('#id_filtro').val() }
          },
          error: function () {
            $("#post_list_processing").css("display", "none");
          }
        },
        "aoColumns": [
          { "class": "text-center", mData: 'id_servicio' },
          { "class": "text-center", mData: 'estado' },
          { "class": "text-center", mData: 'nro_orden' },
          { "class": "text-center", mData: 'servicio' },
          { "class": "text-center", mData: 'nro_placa' },
          { "class": "text-center", mData: 'nombre_marca' },
          { "class": "text-center", mData: 'nombre_color' },
          { "class": "text-center", mData: 'modelo' },
          { "class": "text-center", mData: 'taller' },
          { "class": "text-center", mData: 'km_actual' },
          { "class": "text-center", mData: 'nombre_recibe' },
          { "class": "text-justify", mData: 'nombre_entrega' },
          { "class": "text-justify", mData: 'fecha_recepcion' },
          { "class": "text-justify", mData: 'descripcion_solicitado', "width": "200px !important;" }
        ],
        buttons: [
          {
            text: this.textName,
            className: this.className,
            action: function (e, dt, node, config) {
              if ($("#servicios").val() == 1) {
                let imgModal = $('#modal-remoto-lg');
                let imgModalBody = imgModal.find('.modal-content');
                let thisUrl = '';
                thisUrl = 'vehiculos/php/front/servicios/nuevoServicio.php';
                $.ajax({
                  type: "GET",
                  url: thisUrl,
                  dataType: 'html',
                  data: {
                  },
                  beforeSend: function (data) {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                  },
                  success: function (data) {
                    imgModalBody.html(data);
                  }
                });
              } else {
                $('.agregarServicio').removeClass('btn btn-personalizado btn-sm btn-st-1 btn-solt');
              }
            }
          },
          {
            text: 'En cola <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt active',
            action: function (e, dt, node, config) {
              thes.recargarServicios(5487, 'btn-st-2');
            }
          },
          {
            text: 'En proceso <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
            action: function (e, dt, node, config) {
              thes.recargarServicios(5488, 'btn-st-3');
            }
          },
          {
            text: 'Finalizados <i class="fa fa-sync"></i>',
            className: 'btn btn-personalizado btn-sm btn-st-4 btn-solt',
            action: function (e, dt, node, config) {
              thes.recargarServicios(5489, 'btn-st-4');
            }
          },
        ],
      })
      //fin
    },

    baseTables: function () {
      // DataTables Bootstrap integration
      this.bsDataTables = jQuery.fn.dataTable;
      // Set the defaults for DataTables init
      jQuery.extend(true, this.bsDataTables.defaults, {
        dom:
          "<'row'<'col-sm-4'l><'col-sm-4'B><'col-sm-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        buttons: [
          'csv', 'excel', 'pdf'
        ],
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
  },
})