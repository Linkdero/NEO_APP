function save_salon(opcion) {
    jQuery('.validation_nuevo_salon').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
            jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
            let elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error').addClass('has-error');
            elem.closest('.help-block').remove();
        },
        success: function (e) {
            let elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error');
            elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
            let nombre = $("#nombre").val();
            let ubicacion = $("#ubicacion").val();
            let capacidad = $("#capacidad").val();
            let title = "";
            let data = {};
            if (opcion == 1) {
                let id = $("#id").val();
                if (!$('#chk_estado').is(':checked')) {
                    let motivo = $("#motivo").val();
                    data = { id, nombre, ubicacion, capacidad, estado: 0, motivo, opcion };
                } else {
                    data = { id, nombre, ubicacion, capacidad, estado: 1, opcion };
                }
                title = "¿Desea actualizar el salón?";
                btn_text = "¡Si, Actualizar!";
            } else {
                data = { nombre, ubicacion, capacidad, opcion: 0 };
                title = "¿Desea ingresar el salón?";
                btn_text = "¡Si, Ingresar!";
            }

            Swal.fire({
                title: '<strong></strong>',
                text: title,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: btn_text
            }).then((result) => {
                console.log(result);
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "salones/php/back/salones/save_salon.php",
                        data: data,
                        dataType: "json",
                        success: function (data) {
                            $('#modal-remoto').modal('hide');
                            if (data.status) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Salon agregado',
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                reload();
                            } else {
                                Swal.fire({
                                    type: 'warning',
                                    title: 'Ocurrio un error',
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        }
                    }).fail(function (jqXHR, textSttus, errorThrown) {
                        Swal.fire({
                            type: 'warning',
                            title: 'Error al guardar',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    });
                }
            });
        }
    });
}



function save_solicitud() {
    jQuery('.validation_solicitud').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
            jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
            let elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error').addClass('has-error');
            elem.closest('.help-block').remove();
        },
        success: function (e) {
            let elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error');
            elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {

            let id_salon = $("#salon option:selected").val();
            let fecha_inicio = $("#fechainicio").val();
            let fecha_fin = $("#fechafin").val();
            let motivo = $("#motivo").val();
            let mobiliario = ($('#chk_mobiliario').is(':checked')) ? $('#mobiliario').val() : null;
            let audiovisuales = ($('#chk_equipo').is(':checked')) ? $('#equipo').val() : null;
            let data = { id_salon, fecha_inicio, fecha_fin, motivo, mobiliario, audiovisuales };
            Swal.fire({
                title: '<strong></strong>',
                text: "¿Desea enviar la solicitud?",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Ingresar!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "salones/php/back/salones/save_solicitud.php",
                        data: data,
                        dataType: "json",
                        success: function (data) {
                            $('#modal-remoto').modal('hide');
                            if (data.status) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Solicitud enviada',
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                cargar_calendario(2);
                            } else {
                                Swal.fire({
                                    type: 'warning',
                                    title: 'Ocurrio un error',
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        }
                    }).fail(function (jqXHR, textSttus, errorThrown) {
                        Swal.fire({
                            type: 'warning',
                            title: 'Error al guardar',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    });
                }
            });
        }
    });
}

function update_estado_solicitud(estado, id_solicitud) {
    let data = { estado: estado, id_solicitud: id_solicitud };
    let text, btnCol, btnOK, title;
    switch (estado) {
        case 0:
            text = "¿Desea rechazar la solicitud?";
            btnCol = "#dd3333";
            btnOK = "¡Si, Rechazar!";
            title = "'Solicitud Rechazada'";
            break;
        case 1:
            text = "";
            btnCol = "";
            btnOK = "";
            title = "";
            break;
        case 2:
            text = "¿Desea aprobar la solicitud?";
            btnCol = "#28a745";
            btnOK = "Si, Aprobar!";
            title = "Solicitud Aceptada'";
            break;
        case 3:
            text = "¿Desea finalizar el evento?";
            btnCol = "#2972fa";
            btnOK = "Si, Finalziar!";
            title = "Evento Finalizado'";
            break;

    }
    Swal.fire({
        title: '<strong></strong>',
        text: text,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: btnCol,
        cancelButtonText: 'Cancelar',
        confirmButtonText: btnOK,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "salones/php/back/salones/update_estado_solicitud.php",
                data: data,
                dataType: "json",
                success: function (data) {
                    $('#modal-remoto').modal('hide');
                    if (data.status) {
                        Swal.fire({
                            type: 'success',
                            title: title,
                            showConfirmButton: false,
                            timer: 1100
                        });
                        cargar_calendario(2);
                    } else {
                        Swal.fire({
                            type: 'warning',
                            title: 'Ocurrio un error',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    }
                }
            }).fail(function (jqXHR, textSttus, errorThrown) {
                Swal.fire({
                    type: 'warning',
                    title: 'Error al guardar',
                    showConfirmButton: false,
                    timer: 1100
                });
            });
        }
    });
}