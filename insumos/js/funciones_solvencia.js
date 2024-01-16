$('#modal-remoto-lg').on('click', '.button-check', function () {
    var value = $('#myPopupInput1').val();
    $('#id_persona').val(value);
    get_empleado_insumos(0, 3)
    $('#modal-remoto-lg').modal('hide');
    Swal.fire({
        type: 'success',
        title: 'Empleado agregado',
        showConfirmButton: false,
        timer: 700
    });
    //agregar_insumo();
    //$('#myModal').modal('hide');
});

function solvencia_crear() {
    total = $("input[type=checkbox]").length;
    totalChequeados = $("input[type=checkbox]:checked").length;

    if (total === totalChequeados) {
        $.ajax({
            type: 'POST',
            url: 'insumos/php/back/insumos/evaluar_empleado.php',
            //dataType:'json',
            data: {
                id_persona: function () {
                    return $('#persona_id').val();
                },
                descripcion: function () {
                    return $('#descripcion').val();
                },
                tipo_movimiento: function () {
                    return $('#tipo_movimiento').val();
                }
            },
            beforeSend: function () {
                $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
            },
            success: function (data) {
                if (data === 'error3') {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar un empleado',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else if (data === 'error1') {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar un empleado activo',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    if (data === 'solvente') {
                        crear_datos_solvencia(1);
                    } else if (data === 'vacaciones-insolvente') {
                        if ($('#descripcion').val() == '') {
                            Swal.fire({
                                title: '<strong>Empleado [' + $('#persona_id').val() + '] insolvente, si quiere generar solvencia de vacaciones debe ingresar una descripción.</strong>',
                                text: '',
                                type: 'error',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonText: 'Cancelar'
                            });
                        } else {
                            crear_solvencia_vacaciones_radios(1);
                        }
                    } else {
                        Swal.fire({
                            title: '<strong>Empleado [' + $('#persona_id').val() + '] Insolvente, debe descargar insumos para poder generar solvencia.</strong>',
                            text: '',
                            type: 'error',
                            showConfirmButton: false,
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar'
                        });
                    }
                }
            }
        }).done(function () {

        }).fail(function (jqXHR, textStatus, errorThrown) {

        });
    } else {
        Swal.fire(
            'Atención!',
            "Debe seleccionar los insumos que se quedarán en caliente, de lo contrario devolverlos o resguardarlos",
            'error'
        );
    }
}

function verificar_chequeados() {
    $.each($('input[type=checkbox]:checked'), function () {
        estado = ($(this).data('pk'));
        codigo_insumo = ($(this).data('id'));
        codigo_doc_insumo = ($(this).attr('id'));
        res = codigo_doc_insumo + '-' + codigo_insumo;
        var data = table_movimientos_empleado.row($(this).parents('tr')).data();

        var cantidad_a_entregar = parseInt(data['cantidad_entregada']) - parseInt(data['cantidad_devuelta']);
        cantidad = $('#txt_' + res).val();
        //console.log(estado);
        //console.log(evaluate_estado(estado));
        if ($('#tipo_movimiento').val() == 10 &&
            evaluate_estado(estado) &&
            evaluate_cantidad_ingreso($('#txt_' + res).val())
        ) {
            // RESGUARDO
            //alert('message_');

            if (cantidad <= cantidad_a_entregar) {
                $('#' + res).removeClass('danger');
                $('#' + res).removeClass('danger_2');

            } else {

                $('#' + res).addClass('danger');


                //alert('ERROR');
            }

        } else if ($('#tipo_movimiento').val() == 4 && evaluate_cantidad_ingreso($('#txt_' + res).val()) && cantidad <= cantidad_a_entregar) {
            if (cantidad <= cantidad_a_entregar) {
                $('#' + res).removeClass('danger');
                $('#' + res).removeClass('danger_2');
                //alert('OK');
            } else {
                $('#' + res).addClass('danger');
                $('#' + res).removeClass('danger_2');
                //$('#'+res).addClass('danger_2');
                //alert('ERROR');
            }
        } else {
            $('#' + res).addClass('danger');
            if ($('#tipo_movimiento').val() == 10 && estado == 3) {
                $('#' + res).addClass('danger_2');
            }
            //
        }
    });
}


function crear_datos_solvencia(solvente) {
    Swal.fire({
        title: '<strong>Empleado Solvente ¿Quiere generar esta solvencia?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Generar!'
    }).then((result) => {
        if (result.value) {
            crear_solvencia_confirmacion(solvente);
        }
    });
}

function crear_solvencia_vacaciones_radios(solvente) {
    Swal.fire({
        title: '<strong>Empleado Insolvente ¿Quiere generar solvencia de vacaciones?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#FF3333',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Generar!'
    }).then((result) => {
        if (result.value) {
            crear_solvencia_confirmacion(solvente);
        }
    });
}

function crear_solvencia_confirmacion(solvente) {
    $.ajax({
        type: "POST",
        url: "insumos/php/back/solvencia/create_solvencia.php",
        //dataType:'json',
        data: {
            id_persona: function () {
                return $('#persona_id').val();
            },
            descripcion: function () {
                return $('#descripcion').val();
            },
            tipo_movimiento: function () {
                return $('#tipo_movimiento').val();
            },
            solvente: solvente
        },
        beforeSend: function () {
            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
        },
        success: function (data) {
            if (data === 'insolvente') {
                Swal.fire({
                    title: '<strong>Empleado Insolvente, debe descargar insumos para poder generar solvencia.</strong>',
                    text: "",
                    type: 'error',
                    showConfirmButton: false,
                    showCancelButton: true,
                    //cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    //confirmButtonText: '¡Si, Generar!'
                });
            } else {
                if ($('#tipo_movimiento').val() == 1) {
                    $("#tb_insumos_solvencia tbody tr").each(function () {
                        var tableRow = $(this);
                        if (tableRow.attr('id') != null) {
                            codigo_doc_insumo = ($(this).attr('id'));
                            var entregado = 0, devuelto = 0;
                            if (tableRow[0].cells[5] != null) {
                                entregado = tableRow[0].cells[5].innerHTML;
                            }
                            if (tableRow[0].cells[6] != null) {
                                devuelto = tableRow[0].cells[6].innerHTML;
                            }
                            var cantidad = entregado - devuelto;
                            var descripcion = $('#descripcion').val();
                            $.ajax({
                                type: "POST",
                                url: "insumos/php/back/solvencia/create_solvencia_detalle.php",
                                //dataType:'json',
                                data:
                                {
                                    id_persona: $('#persona_id').val(),
                                    id_doc_solvencia: data,
                                    id_prod_insumo_detalle: codigo_doc_insumo,
                                    cantidad: cantidad,
                                    descripcion: descripcion
                                },
                                beforeSend: function () {
                                    $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                                },
                                success: function (data) {

                                }
                            }).done(function () {

                            }).fail(function (jqXHR, textStatus, errorThrown) {

                            });
                        }
                    });
                }
                solvenciaId = data;
            }
        }
    }).done(function () {
        Swal.fire({
            type: 'success',
            title: 'Solvencia generada',
            showConfirmButton: false,
            timer: 1000
        });
        reporte_solvencia(solvenciaId);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
    });


    //fin funcionamiento
}
