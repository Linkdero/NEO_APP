const tbsCupones = new Vue({
    el: '#ingresoCupones',

    data: {
        tableCupones: '',
    },
    //Para que se ejecute al inicar el modulo
    created: function () {
        this.$nextTick(() => {
            $( "#tabla2" ).hide()
            this.baseTables();
            this.cargarTablaCupones();
        });
    },

    //Asi generamos funciones en VUE
    methods: {
        cargarTablaCupones: function () {
            //inicio
            let thes = this
            this.tableCupones = $("#tbsCuponesIngresados").DataTable({
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
                    url: "vehiculos/php/back/listados/get_all_cupones.php",
                    type: "POST",
                    data: {
                        documento: $('#id_documento').val(),
                        filtro: function () { return $('#id_filtro').val() },
                        opcion: 1
                    },
                    error: function () {
                        $("#post_list_processing").css("display", "none");
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'doc' },
                    { "class": "text-center", mData: 'id' },
                    { "class": "text-center", mData: 'cupon' },
                    { "class": "text-center", mData: 'monto' },
                    { "class": "text-center", mData: 'estado' },
                    { "class": "text-center", mData: 'descripcion' },
                    { "class": "text-center", mData: 'fecha' },
                ],
                buttons: [
                  {
                      text: 'Excel <i class="fa fa-file-excel" aria-hidden="true"></i>',
                      className: 'btn btn-personalizado btn-sm',
                      extend: 'excel',
                  },
                    {
                        text: 'Todos <i class="fa fa-bars" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt active',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(3, "btn-st-2");
                        }
                    },
                    {
                        text: 'Utilizados <i class="fa fa-check-square" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(1, "btn-st-3");
                        }
                    },
                    {
                        text: 'Entregados <i class="fa fa-car" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-4 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(2, "btn-st-4");
                        }
                    },
                    {
                        text: 'Devueltos <i class="fa fa-undo" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-6 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(4, "btn-st-6");
                        }
                    },
                    {
                        text: 'Sin Utilizar <i class="fa fa-window-close" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-5 btn-solt',
                        action: function (e, dt, node, config) {
                            thes.recargarTablaCupones(0, "btn-st-5");
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
                    "<'row'<'col-sm-3'l><'col-sm-6'B><'col-sm-3'f>>" +
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
            $('.btn-solt').removeClass('active');
            $('.' + clase).addClass('active');
            $("#id_filtro").val(tipo);
            console.log( $("#id_filtro").val(tipo))
            $('#tbsCuponesIngresados').DataTable().ajax.reload();

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
