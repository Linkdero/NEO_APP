const viewModelTickets = new Vue({
    el: '#AppReporteria',

    data: {
        fecha1: "",
        fecha2: "",
        valor: 0,
        encabezado: "ENCARGADO"
    },
    //Para que se ejecute al inicar el modulo
    created: function () {
        this.$nextTick(() => {
            this.baseTables();
            this.cargarTablaRequerimientos();
        });
    },

    //Asi generamos funciones en VUE
    methods: {
        filtrarFecha: function () {
            this.fecha1 = $('#limiteInferior').val()
            this.fecha2 = $('#limitesuperior').val()
            this.recargarTablaRequerimientos()
        },
        recargarReporteria: function (opc, clase) {
            var thisInsantace = this;
            if (opc == 0) {
                this.encabezado = "ENCARGADO"
            } else if (opc == 1) {
                this.encabezado = "DIRECCIÓN SOLICITA"
            } else if (opc == 2) {
                this.encabezado = "INCIDENCIA SOLICITADA"
            };
            $('.btn-solt').removeClass('active');
            $('.' + clase).addClass('active');
            $('#id_filtro').val(opc);   //tenes que crear un input con ese id
            thisInsantace.tableTickets.ajax.reload(null, false);
        },

        selectedOption: function (value) {
            console.log("value : " + value);
        },

        cargarTablaRequerimientos: function () {
            //inicio
            var thisInsantace = this;
            this.tableTickets = $("#tbl_reporteria").DataTable({
                "ordering": false,
                "pageLength": 25,
                "bProcessing": true,
                "paging": true,
                "info": true,
                select: true,
                responsive: true,
                scrollX: true,
                scrollY: '50vh',
                language: {
                    emptyTable: "No hay solicitudes de Reporteria para mostrar",
                    sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                },
                ajax: {
                    url: "tickets/model/reporteria.php",
                    type: "POST",
                    data: {
                        fecha1: function () {
                            return $('#limiteInferior').val()
                        },
                        fecha2: function () {
                            return $('#limitesuperior').val()
                        },
                        opcion: 1,
                        filtro: function () { return $('#id_filtro').val() },
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'nombre' },
                    { "class": "text-center", mData: 'departamento' },
                    { "class": "text-center", mData: 'tickets' },
                ],
                buttons: [
                    {
                        text: 'Direcciones <i class="fa fa-id-badge" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt ',
                        action: function (e, dt, node, config) {
                            this.valor = 1
                            thisInsantace.recargarReporteria(this.valor, "btn-st-1",)
                        }
                    },
                    {
                        text: 'Técnicos <i class="fa fa-id-card"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt active',
                        action: function (e, dt, node, config) {
                            this.valor = 0
                            thisInsantace.recargarReporteria(this.valor, 'btn-st-2')
                        },
                    },
                    {
                        text: 'Incidencias <i class="fa fa-life-ring"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
                        action: function (e, dt, node, config) {
                            this.valor = 2
                            thisInsantace.recargarReporteria(this.valor, 'btn-st-3')
                        }
                    },
                ]
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

        recargarTablaRequerimientos: function () {
            //inicio
            $('#tbl_reporteria').DataTable().ajax.reload();
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                type: 'success',
                title: 'Actualizado con exito'
            })
            //fin
        },
    },
})