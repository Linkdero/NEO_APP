const viewFichaVehiculos = new Vue({
    el: '#vehiculoFicha',

    data: {
        titulo: "Ficha del Vehículo: ",
        ficha: '',
        foto: '',
        clase: '',
        texto: '',
        tableVehiculos: ''
    },

    mounted() {
        this.datosVehiculo()
        this.$nextTick(() => {
            this.cargarTablaAsignados()
            this.baseTables()
        });
    },

    methods: {
        datosVehiculo() {
            axios
                .get("vehiculos/php/back/vehiculos/getFichaVehiculos.php", {
                    params: {
                        opcion: 1,
                        id: $('#idVehiculoLlamar').val()
                    }
                })
                .then((response) => {
                    const data = response.data;
                    this.ficha = data['ficha'][0];
                    if (data['foto'][0]?.foto !== undefined) {
                        this.foto = data['foto'][0].foto;
                    }

                    if (this.foto.substring(0, 20) == 'dataimage/jpegbase64') {
                        this.foto = this.foto.replace("dataimage/jpegbase64", "");
                        this.foto = 'data:image/jpeg;base64,' + this.foto;
                    } else {
                        this.foto = 'data:image/jpeg;base64,' + this.foto;
                    }

                    if (this.ficha.id_status == 1010 || this.ficha.id_status == 8034) {
                        this.clase = 'badge badge-success';
                        this.texto = 'text-success fa-solid fa-toggle-on';
                    } else if (this.ficha.id_status == 1011 || this.ficha.id_status == 8033 || this.ficha.id_status == 8035 || this.ficha.id_status == 8036) {
                        this.clase = 'badge badge-danger';
                        this.texto = 'text-danger fa-solid fa-solid fa-toggle-off';
                    } else {
                        this.clase = 'badge badge-warning';
                        this.texto = 'text-warning fa-solid fa-toggle-off';
                    }
                    console.log(this.ficha);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        cargarTablaAsignados: function () {
            //inicio
            this.tableAsignados = $('#tblFichaVehiculo').DataTable({
                "ordering": false,
                "pageLength": 3,
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
                    url: "vehiculos/php/back/vehiculos/getFichaVehiculos.php",
                    type: "GET",
                    data: {
                        opcion: 2,
                        id: function () { return $('#idVehiculoLlamar').val() }
                    },
                    error: function () {
                        $("#post_list_processing").css("display", "none");
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'asignado' },
                    { "class": "text-center", mData: 'autorizado' },
                    { "class": "text-center", mData: 'descripcion' },
                    { "class": "text-center", mData: 'fecha_entrega' }
                ],
            })
            //fin
            this.tableAsignados.buttons().remove();
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