const viewModelVehiculosL = new Vue({
  el: '#appVehiculos',

  data: {
    tableVehiculos: ""
  },
  //Para que se ejecute al inicar el modulo
  created: function () {
    this.$nextTick(() => {
      this.baseTables();
      this.getVehiculosList();
    });
  },

  //Asi generamos funciones en VUE
  methods: {
    getVehiculosList: function () {
      let thes = this;
      this.tableVehiculos = $('#tb_vehiculos').DataTable({
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
          url: "vehiculos/php/back/listados/get_vehiculos.php",
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
          { "class": "text-center", mData: 'id_vehiculo' },
          { "class": "text-center", mData: 'nombre_estado' },
          { "class": "text-center", mData: 'nro_placa' },
          { "class": "text-center", mData: 'chasis' },
          { "class": "text-center", mData: 'motor' },
          { "class": "text-center", mData: 'nombre_tipo' },
          { "class": "text-center", mData: 'nombre_marca' },
          { "class": "text-center", mData: 'nombre_linea' },
          { "class": "text-center", mData: 'nombre_color' },
          { "class": "text-center", mData: 'detalle_franjas' },
          { "class": "text-center", mData: 'modelo' },
          { "class": "text-center", mData: 'observaciones' },
          { "class": "text-center", mData: 'capacidad_tanque' },
          { "class": "text-center", mData: 'nombre_tipo_combustible' },
          { "class": "text-center", mData: 'km_actual' },
          { "class": "text-center", mData: 'propietario' },
          { "class": "text-center", mData: 'nombre_persona_asignado' }
        ],
        buttons: [
          {
            text: 'Agregar <i class="fa fa-plus"></i>',
            className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt',
            action: function (e, dt, node, config) {
              let imgModal = $('#modal-remoto-lgg2');
              let imgModalBody = imgModal.find('.modal-content');

              let thisUrl = '';
              thisUrl = 'vehiculos/php/front/vehiculo/vehiculosFormulario.php';

              $.ajax({
                type: "GET",
                url: thisUrl,
                dataType: 'html',
                data: {
                  id: 0,
                  tipo: 1
                },
                beforeSend: function (data) {
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
            text: 'Listado <i class="fa-solid fa-user-group"></i>',
            className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt',
            action: function (e, dt, node, config) {
              let imgModal = $('#modal-remoto-lgg3');
              let imgModalBody = imgModal.find('.modal-content');
              let thisUrl = 'vehiculos/php/front/vehiculo/listadoAsignados.php';

              $.ajax({
                type: "GET",
                url: thisUrl,
                dataType: 'html',

                beforeSend: function (data) {
                  imgModal.modal('show');
                  imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                },
                success: function (data) {
                  imgModalBody.html(data);
                }
              });
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