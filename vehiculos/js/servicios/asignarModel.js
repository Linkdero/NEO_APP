const asignarmecanico = httpVueLoader('./vehiculos/js/components/asignarMecanico.vue');

const viewModelAsignarMecanico = new Vue({
    data: {
        mecanicos: []
    },
    components:
    {
        asignarmecanico
    },

    //Asi generamos funciones en VUE
    methods: {
        asignar: function (noServi) {

            Swal.fire({
                title: '<strong>¿Asignar mecanico al servicio #' + noServi + '?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Asignar!'
            }).then((result) => {
                if (result.value) {
                    //alert(vt_nombramiento);
                    $.ajax({
                        type: "Post",
                        url: "vehiculos/php/back/servicios/action/asignarMecanico.php",
                        dataType: 'json',
                        data: {
                            opcion: 2,
                            mecanico: $("#mecanicos").val(),
                            noServi: noServi
                        },
                        success: function (data) {
                            if (data.msg == 'OK') {
                                Swal.fire({
                                    type: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                $('#modal-remoto-lgg').modal('hide');
                                $('#tb_servicios').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: data.id,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        }
                    }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {
                        alert(errorThrown);
                    });
                }
            })
        }
    }
})

viewModelAsignarMecanico.$mount('#asignarMecanico');