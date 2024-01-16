
new Vue({
    el: '#listadoAsignados',

    data: {
        titulo: "Listado de Asignados: ",
        tipo: 1,
        tableVehiculosAsignados: '',
        tableAsignados: '',
    },

    created() {
        this.$nextTick(() => {
            this.cargarTablaAsignados()
            this.baseTables()
        });
    },

    methods: {
        cargarTablaAsignados: function () {
            //inicio
            this.tableAsignados = $('#tblListadoAsignados').DataTable({
                "ordering": false,
                "pageLength": 7,
                "bProcessing": true,
                "lengthChange": false, // Agregar esta línea
                "paging": true,
                "info": true,
                "select": true,
                "responsive": true,
                "scrollX": true,
                "scrollY": '50vh',
                "scrollCollapse": true,
                "autoWidth": false,
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
                    url: "vehiculos/php/back/vehiculos/getListadoAsignados.php",
                    type: "GET",
                    data: {
                        opcion: 1,
                    },
                    error: function () {
                        $("#post_list_processing").css("display", "none");
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'id' },
                    { "class": "text-center", mData: 'nombre' },
                    { "class": "text-center", mData: 'direccion' },
                ],
            })
            //fin
            this.tableAsignados.buttons().remove();
        },

        vehiculosAsignados: function () {
            const idPersona = $("#idPersona").val(); // Obtener el valor actualizado del campo

            // Destruir el DataTable existente (si existe)
            if (this.tableVehiculosAsignados) {
                this.tableVehiculosAsignados.destroy();
            }
            // Si this.tableVehiculosAsignados no está definido, inicializar el objeto DataTable
            console.log("Valor de idPersona:", idPersona); // Verificar el valor en la consola
            var thes = this; // Asignar this a una variable local

            this.tableVehiculosAsignados = $('#tblVehiculosAsignados').DataTable({
                "ordering": false,
                "pageLength": 7,
                "bProcessing": true,
                "lengthChange": false, // Agregar esta línea
                "paging": true,
                "info": true,
                "select": true,
                "responsive": true,
                "scrollX": true,
                "scrollY": '50vh',
                "scrollCollapse": true,
                "autoWidth": false,
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
                    url: "vehiculos/php/back/vehiculos/getListadoVehiculosAsignados.php",
                    type: "GET",
                    data: {
                        opcion: 1,
                        id: idPersona // Usar el valor actualizado del campo
                    },
                    error: function () {
                        $("#post_list_processing").css("display", "none");
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'id' },
                    { "class": "text-center", mData: 'placa' },
                    { "class": "text-center", mData: 'marca' },
                    { "class": "text-center", mData: 'linea' },
                    { "class": "text-center", mData: 'modelo' },
                    { "class": "text-center", mData: 'color' },
                    { "class": "text-center", mData: 'autoriza' },
                    { "class": "text-center", mData: 'fecha' },
                ],
                buttons: [
                    {
                        text: 'Regresar <i class="fa-solid fa-repeat"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.tipo = 1;
                        }
                    },

                ],
            })
            //fin
            this.tipo = 2;
        },

        baseTables: function () {
            // DataTables Bootstrap integration
            this.bsDataTables = jQuery.fn.dataTable;
            // Set the defaults for DataTables init
            jQuery.extend(true, this.bsDataTables.defaults, {
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
});