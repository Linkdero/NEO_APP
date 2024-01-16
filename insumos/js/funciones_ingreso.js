$(document).ready(function () {

    $('#tb_movimientos_empleado tbody').on('click', 'td', function () {
        $('input:checkbox').change(function () {
            verificar_chequeados();
            flag = $(this).data('id');
            codigo_insumo = ($(this).data('id'));
            codigo_doc_insumo = ($(this).attr('id'));
            res = codigo_doc_insumo + '-' + codigo_insumo;
            if ($(this).is(':checked')) {
                //alert('chequeado');
            } else {
                var data = table_movimientos_empleado.row($(this).parents('tr')).data();
                var cantidad_a_entregar = parseInt(data['cantidad_entregada']) - parseInt(data['cantidad_devuelta']);
                //console.log(cantidad_a_entregar);
                $('#' + res).removeClass('danger');
                $('#' + res).removeClass('danger_2');
                $('#txt_' + res).val(cantidad_a_entregar);
            }
        });
    });


    $('#tb_movimientos_empleado tbody').on('click', '.form-control', function () {
        this_code = ($(this).attr('id'));
        $("#" + this_code).keyup(function () {
            cantidad = $('#' + this_code).val();
            var str = this_code;
            var res = str.replace("txt_", "");
            var data = table_movimientos_empleado.row($(this).parents('tr')).data();
            var cantidad_a_entregar = parseInt(data['cantidad_entregada']) - parseInt(data['cantidad_devuelta']);
            var estado = data['movimiento'];
            var tipo_movimiento = 0;
            if (estado == 'Asignación temporal') {
                tipo_movimiento = 3;
            }
            if (evaluate_cantidad_ingreso(cantidad)) {
                if (cantidad <= cantidad_a_entregar) {
                    if ($('#tipo_movimiento').val() == 10 && evaluate_estado(tipo_movimiento) || $('#tipo_movimiento').val() == 4) {
                        $('#' + res).removeClass('danger');
                        $('#' + res).removeClass('danger_2');
                    } else {
                        $('#' + res).addClass('danger');
                    }
                } else {
                    $('#' + res).addClass('danger');
                }
            } else {
                $('#' + res).addClass('danger');
            }
        });
    });
});

function verificar_chequeados() {
    $.each($('input[type=checkbox]:checked'), function () {
        estado = ($(this).data('pk')); //tipo movimiento insumo
        insumo = ($(this).data('id'));
        insumoIds = insumo.split('-');
        codigo_doc_insumo = ($(this).attr('id'));
        res = codigo_doc_insumo + '-' + insumoIds[1];
        var data = table_movimientos_empleado.row($(this).parents('tr')).data();
        var cantidad_a_entregar = parseInt(data['cantidad_entregada']) - parseInt(data['cantidad_devuelta']);
        cantidad = $('#txt_' + res).val();
        /*
        Tipo movimiento:
        1 - Ingreso a Bodega
        2 - Asignacion Permantente
        3 - Asignacion Temporal
        4 - Devolucion
        10 - Resguardo
        12 - Mal estado
        */
        if ($('#tipo_movimiento').val() == 10 &&
            evaluate_estado(estado) &&
            evaluate_cantidad_ingreso($('#txt_' + res).val())
        )
        {
            // RESGUARDO
            if (cantidad <= cantidad_a_entregar) {
                $('#' + res).removeClass('danger');
                $('#' + res).removeClass('danger_2');
            } else {
                $('#' + res).addClass('danger');
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

function crear_ingreso() {
    verificar_chequeados();
    total = $("input[type=checkbox]:checked").length;
    if (total == 0) {
        Swal.fire(
            'Atención!',
            "Debe seleccionar al menos un insumo",
            'error'
        );
    } else {
        if (total <= 32) {
            var nFilas = $("#tb_movimientos_empleado tbody tr.danger").length;
            var nFilas2 = $("#tb_movimientos_empleado tbody tr.danger_2").length;
            if (nFilas == 0) {
                id_persona_diferente = 0;
                if ($('#chk_otro_empleado').is(':checked')) {
                    id_persona_diferente = $('#id_persona_diferente').val();
                    $.ajax({
                        type: "POST",
                        url: "insumos/php/back/insumos/evaluar_empleado.php",
                        //dataType:'json',
                        data: {
                            id_persona: function () {
                                return $('#id_persona_diferente').val()
                            },
                            descripcion: function () {
                                return $('#descripcion').val()
                            },
                            tipo_movimiento: function () {
                                return $('#tipo_movimiento').val()
                            }
                        },
                        beforeSend: function () {
                            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                        },
                        success: function (data) {

                        }
                    }).done(function (data) {
                        if (data == 'error3') {
                            Swal.fire({
                                type: 'error',
                                title: 'Error',
                                text: "Debe seleccionar un empleado",
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else if (data == 'error1') {
                            Swal.fire({
                                type: 'error',
                                title: 'Error',
                                text: "Debe seleccionar un empleado activo",
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else {
                            insertar_ingreso(id_persona_diferente);
                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    });
                } else {
                    insertar_ingreso(id_persona_diferente);
                }
            } else {
                if (nFilas2 == 0) {
                    var cantidad;
                    if (nFilas == 1) {
                        cantidad = 'cantidad';
                    } else {
                        cantidad = 'cantidades'
                    }
                    Swal.fire(
                        'Atención!',
                        "Tiene " + nFilas + " " + cantidad + " que especificar correctamente",
                        'error'
                    );
                } else {
                    Swal.fire(
                        'Atención!',
                        "No puede resguardar insumos en Estado Temporal",
                        'error'
                    );
                }
            }
        } else {
            Swal.fire(
                'Atención!',
                "El máximo de Insumos por Transacción es de 32",
                'error'
            );
        }
    }
}

function insertar_ingreso(id_persona_diferente) {
    $.ajax({
        type: "POST",
        url: "insumos/php/back/insumos/evaluar_empleado.php",
        //dataType:'json',
        data: {
            id_persona: function () {
                return $('#id_persona').val()
            },
            descripcion: function () {
                return $('#descripcion').val()
            },
            tipo_movimiento: function () {
                return $('#tipo_movimiento').val()
            }
        },
        beforeSend: function () {
            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
        },
        success: function (data) {
            if (data == 'error3') {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: "Debe seleccionar un empleado",
                    showConfirmButton: false,
                    timer: 1000
                });
            } else if (data == 'error1') {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: "Debe seleccionar un empleado activo",
                    showConfirmButton: false,
                    timer: 1000
                });
            } else {
                Swal.fire({
                    title: '<strong>¿Quiere generar este Ingreso a Bodega?</strong>',
                    text: "",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: '¡Si, Generar!'
                }).then((result) => {
                    var TableData = [];
                    $('#tb_movimientos_empleado tbody tr input:checkbox:checked').each(function (row,tr){
                        id_doc_insumo = ($(tr).attr('id'));
                        insumo = ($(tr).data('id'));
                        insumoIds = insumo.split('-');
                        cantidad = $('#txt_' + id_doc_insumo + '-' + insumoIds[1]).val();
                        TableData[row]={
                            "id_doc_insumo_ref" : id_doc_insumo,
                            "id_prod_insumo" : insumoIds[0],
                            "id_prod_insumo_detalle": insumoIds[1],
                            "cantidad" : cantidad
                        }
                    });
                    var jsonTableData = JSON.stringify(TableData);
                    console.log('table: '+jsonTableData)

                    if (result.value) {
                        //funcionamiento
                        $.ajax({
                            type: "POST",
                            url: "insumos/php/back/insumos/create_movimiento.php",
                            //dataType:'json',
                            data: {
                                id_persona: function () {
                                    if(id_persona_diferente > 0)
                                        id = id_persona_diferente;
                                    else
                                        id = $('#id_persona').val();

                                    return id;
                                },
                                descripcion: function () {
                                    return $('#descripcion').val()
                                },
                                tipo_movimiento: function () {
                                    return $('#tipo_movimiento').val()
                                },
                                otroEmpleado: function(){
                                    return $('#chk_otro_empleado').val()
                                },
                                movimientoDetalle : jsonTableData
                            },
                            beforeSend: function () {
                                $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                            },
                            success: function (data) {
                                ingresoID = data;
                            }
                        }).done(function () {
                            Swal.fire({
                                type: 'success',
                                title: 'Ingreso generado',
                                showConfirmButton: false,
                                timer: 1000
                            });
                            reporte_movimiento(ingresoID);
                            clear_data();
                            //get_empleado_insumos(4351, 1);
                            reload_movimientos_empleado_by_bodega();
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            console.log('Ingreso error: ' + errorThrown);
                        });
                        //fin funcionamiento
                    }
                })
            }
        }
    }).done(function () {


    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
    });
}

function clear_data() {
    $('#id_persona').focus();
}

function evaluate_cantidad_ingreso(cantidad) {
    if (cantidad == '' || cantidad < 1) {
        return false;
    } else {
        if (cantidad % 1 == 0) {
            return true;
        } else {
            return false;
        }
    }
}

function evaluate_estado(estado) {
    if (estado == 3) {
        return false;
    } else {
        return true;
    }
}
