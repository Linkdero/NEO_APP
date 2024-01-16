import { EventBus } from './EventBus.js';
export const eBus = EventBus;

const viewModelTickets = new Vue({
    el: '#AppTickets',

    data: {
        titulo: 'Sistema de Tickets',
        tituloConfirmar: 'Crear Ticket',
        ticket: true,
        tareas: false,
        proyecto: false,
        reportes: false,
        crear: false,
        tableTickets: '',
        direcciones: "",
        direccion_options: [],
        componentKey: 0,
        identificadorIntervaloDeTiempo: ""
    },
    //Para que se ejecute al inicar el modulo
    created: function () {
        this.$nextTick(() => {
            this.baseTables();
            this.cargarTablaTickets();

            // if ($("#anderson").val() == 8865) {
            //     setInterval(this.recargarTablaTickets, 60000);
            // }
        });

    },

    //Asi generamos funciones en VUE
    methods: {
        recargarTickets: function (opc, clase) {
            var thisInsantace = this;
            $('.btn-solt').removeClass('active');
            $('.' + clase).addClass('active');
            $('#id_filtro').val(opc);   //tenes que crear un input con ese id
            thisInsantace.tableTickets.ajax.reload(null, false);
            console.log("Actualizado")
        },

        cargarTablaTickets: function () {
            //inicio
            var thisInsantace = this;
            this.tableTickets = $("#tb_tickets").DataTable({
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
                    emptyTable: "No hay solicitudes de Tickets para mostrar",
                    sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
                    //loadingRecords: " <div class='loaderr'></div> "
                },
                ajax: {
                    url: "tickets/model/tickets.php",
                    type: "POST",
                    data: {
                        opcion: 1,
                        filtro: function () { return $('#id_filtro').val() }
                    }
                },
                "aoColumns": [
                    { "class": "text-center", mData: 'id' },
                    { "class": "text-center", mData: 'solicitante' },
                    { "class": "text-center", mData: 'estado' },
                    { "class": "text-center", mData: 'detalle' },
                    { "class": "text-center", mData: 'responsable' },
                    { "class": "text-center", mData: 'fecha' },
                    { "class": "text-center", mData: 'accion' }
                ],
                buttons: [
                    {
                        text: 'Todos <i class="fa fa-server" aria-hidden="true"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-1 btn-solt ',
                        action: function (e, dt, node, config) {
                            thisInsantace.recargarTickets(400, "btn-st-1")
                        }
                    },
                    {
                        text: 'Pendientes <i class="fa fa-sync"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-2 btn-solt active',
                        action: function (e, dt, node, config) {
                            thisInsantace.recargarTickets(0, 'btn-st-2')
                        },
                    },
                    {
                        text: 'Finalizados <i class="fa fa-check-circle"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-3 btn-solt',
                        action: function (e, dt, node, config) {
                            thisInsantace.recargarTickets(3, 'btn-st-3')
                        }
                    },
                    {
                        text: 'Anulados <i class="fa fa-times-circle"></i>',
                        className: 'btn btn-personalizado btn-sm btn-st-4 btn-solt',
                        action: function (e, dt, node, config) {
                            thisInsantace.recargarTickets(4, 'btn-st-4')
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

        recargarTablaTickets: function () {
            //inicio
            $('#tb_tickets').DataTable().ajax.reload();
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

        nuevoTicket: function (tipo) {
            let imgModal
            if (tipo == 1) {
                imgModal = $('#modal-remoto-lg');
            } else if (tipo == 2) {
                imgModal = $('#modal-remoto-lgg2');
            }
            let imgModalBody = imgModal.find('.modal-content');
            //let id_persona = parseInt($('#bar_code').val());
            let thisUrl = 'tickets/views/ticketNuevo.php';
            $.ajax({
                type: "POST",
                url: thisUrl,
                dataType: 'html',
                data: {
                    tipo: tipo,
                },
                beforeSend: function () {
                    imgModal.modal('show');
                    imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                },
                success: function (data) {
                    imgModalBody.html(data);
                    $('#modal-remoto-lg').modal('hide');
                }
            });
        },
    },
})

export default function reload() {
    viewModelTickets.recargarTablaTickets()
}