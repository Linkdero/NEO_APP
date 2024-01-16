const tbsCupones = new Vue({
    el: '#tbsCupones',

    data: {
        tablePorCupones: '',
        tableCupones: '',
        contador: 0,
        tablaContent: 1
    },
    //Para que se ejecute al inicar el modulo
    mounted() {
            this.baseTables();
            this.cargarTablaCupones();
    },

    //Asi generamos funciones en VUE
    methods: {

        cargarTablaPorCupones: function () {
            //inicio
            if (this.tablePorCupones) {
                // Si la tabla ya se ha creado previamente, destrúyela antes de crearla nuevamente
                this.tablePorCupones.destroy();
            }
            let thes = this
            this.tablePorCupones = $("#tbPorCupon").DataTable({
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
                    emptyTable: "No hay solicitudes de Cupones para mostrar",
                    sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                },
                ajax: {
                    url: "vehiculos/php/back/listados/getAllPorCupon.php",
                    type: "POST",
                    data: {
                        opcion: 1
                    },
                    error: function () {
                        $("#post_list_processing").css("display", "none");
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'id' },
                    { "class": "text-center", mData: 'cupon' },
                    { "class": "text-center", mData: 'monto' },
                    { "class": "text-center", mData: 'estado' },
                    { "class": "text-center", mData: 'doc' },
                    { "class": "text-center", mData: 'fecha' },
                ],
                buttons: [
                    {
                        text: 'Pendientes <i class="far fa-check-square"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(4348, "btn-st-2");
                            thes.tablaContent = 1

                        }
                    },
                    {
                        text: 'Procesados <i class="fa fa-check-square"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(4347, "btn-st-3");
                            thes.tablaContent = 1

                        }
                    },
                    {
                        text: 'Cupones <i class="fa fa-search"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-4 btn-solt active',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(4349, "btn-st-4");
                            thes.tablaContent = 2

                        }
                    },
                ],
            })
        },

        cargarTablaCupones: function () {
            if (this.tableCupones) {
                // Si la tabla ya se ha creado previamente, destrúyela antes de crearla nuevamente
                this.tableCupones.destroy();
            }
            let thes = this;
            this.tableCupones = $("#tb_cupones_entregados").DataTable({
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
                    emptyTable: "No hay solicitudes de Cupones para mostrar",
                    sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                },
                "ajax": {
                    url: "vehiculos/php/back/listados/get_all_cupones_entregados.php",
                    type: "POST",
                    data: {
                        estado: function () { return $('#estado_cupon').val() }
                    },
                    error: function () {
                        $("#post_list_processing").css("display", "none");
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'id_documento' },
                    { "class": "text-center", mData: 'estado' },
                    { "class": "text-center", mData: 'fecha' },
                    { "class": "text-center", mData: 'nro_documento' },
                    { "class": "text-center", mData: 'autorizo' },
                    { "class": "text-center", mData: 'recibe' },
                    { "class": "text-center", mData: 'cupones' },
                    { "class": "text-center", mData: 'total' },
                    { "class": "text-center", mData: 'pendiente' },
                    { "class": "text-center", mData: 'accion' },
                ],

                buttons: [{
                    extend: 'excel',
                    text: '<i class="fa fa-server" aria-hidden="true"></i> Exportar',
                    className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt',
                    title: 'Listado de solicitudes',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                },
                {
                    text: 'Pendientes <i class="far fa-check-square"></i>',
                    className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt active',
                    action: function (e, dt, node, config) {
                        thes.recargarTablaCupones(4348, "btn-st-2");
                        thes.tablaContent = 1
                    }
                },
                {
                    text: 'Procesados <i class="fa fa-check-square"></i>',
                    className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
                    action: function (e, dt, node, config) {
                        thes.recargarTablaCupones(4347, "btn-st-3");
                        thes.tablaContent = 1
                    }
                },
                {
                    text: 'Cupones <i class="fa fa-search"></i>',
                    className: 'btn btn-personalizado btn-sm btn-st-4 btn-solt',
                    action: function (e, dt, node, config) {
                        thes.recargarTablaCupones(4349, "btn-st-4");
                        thes.tablaContent =2
                    }
                },
                ],
            })
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

        recargarTablaCupones: function (tipo, clase) {
            //inicio
            if (tipo == 4349) {
                $('.btn-solt').removeClass('active');
                $('.' + clase).addClass('active');
                $('#estado_cupon').val(tipo);
                if (this.contador == 0) {
                    $('#tabla2').removeClass('invisible');
                    this.cargarTablaPorCupones(null, false);
                    this.contador++
                }
            } else {
                $('.btn-solt').removeClass('active');
                $('.' + clase).addClass('active');
                $('#estado_cupon').val(tipo);
                this.tableCupones.ajax.reload(null, false);
            }
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