$('#modal-remoto-lg').on('click', '.button-check', function () {
    var value = $('#myPopupInput1').val();
    $('#id_persona').val(value);
    get_empleado_insumos(4353, 2)
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

$(document).ready(function () {
    $('#tb_movimientos_empleado_resguardo tbody').on('click', '.form-control', function () {
        //var data = table_insumos_asignar.row( $(this).parents('tr') ).data();
        //alert( data['marca'] +"'s serie es: "+ data['serie'] );
        //$('#myPopupInput').val(data['serie']);
        this_code = ($(this).attr('id'));

        $("#" + this_code).keyup(function () {
            cantidad = $('#' + this_code).val();
            //var str = document.getElementById("demo").innerHTML;
            var str = this_code;
            var res = str.replace("txt_", "");
            var data = table_movimientos_empleado_resguardo.row($(this).parents('tr')).data();
            var cantidad_a_entregar = parseInt(data['cantidad_devuelta']) - parseInt(data['cantidad_entregada']);
            //alert(cantidad);

            if (evaluate_cantidad_ingreso(cantidad)) {
                if (cantidad <= cantidad_a_entregar) {
                    $('#' + res).removeClass('danger');
                    //alert('ok');
                } else {
                    $('#' + res).addClass('danger');
                    //alert('error');
                }
            } else {
                $('#' + res).addClass('danger');
                //alert('error');
            }
        });


    });
});

function crear_entrega() {
    total = $("input[type=checkbox]:checked").length;

    if (total == 0) {
        Swal.fire(
            'Atención!',
            "Debe seleccionar al menos un insumo",
            'error'
        );
    } else {
        if (total <= 32) {
            var nFilas = $("#tb_movimientos_empleado_resguardo tbody tr.danger").length;

            if (nFilas == 0) {
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
                                title: '<strong>¿Quiere generar esta Entrega?</strong>',
                                text: "",
                                type: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#28a745',
                                cancelButtonText: 'Cancelar',
                                confirmButtonText: '¡Si, Generar!'
                            }).then((result) => {
                                if (result.value) {
                                    //funcionamiento
                                    var TableData = [];
                                    $('#tb_movimientos_empleado_resguardo tbody tr input:checkbox:checked').each(function (row, tr) {
                                        id_doc_insumo = ($(tr).attr('id'));
                                        insumo = ($(tr).data('id'));
                                        insumoIds = insumo.split('-');
                                        cantidad = $('#txt_' + id_doc_insumo + '-' + insumoIds[1]).val();
                                        TableData[row] = {
                                            "id_doc_insumo_ref": id_doc_insumo,
                                            "id_prod_insumo": insumoIds[0],
                                            "id_prod_insumo_detalle": insumoIds[1],
                                            "cantidad": cantidad
                                        }
                                    });
                                    var jsonTableData = JSON.stringify(TableData);
                                    console.log('table: ' + jsonTableData);
                                    //alert(data);
                                    $.ajax({
                                        type: "POST",
                                        url: "insumos/php/back/insumos/create_movimiento.php",
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
                                            },
                                            movimientoDetalle: jsonTableData
                                        },
                                        beforeSend: function () {
                                            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                                        },
                                        success: function (data) {
                                            movimientoID = data;
                                            console.log('transaccion ' + data);
                                        }
                                    }).done(function(){
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Egreso generado',
                                            showConfirmButton: false,
                                            timer: 1000
                                        });
                                        reporte_movimiento(movimientoID);
                                        clear_data();
                                        get_empleado_insumos(4353, 2);
                                        reload_movimientos_empleado_by_bodega_resguardo();
                                    }).fail(function (jqXHR, textStatus, errorThrown) {
                                        console.log('Transaccion error: ' + errorThrown);
                                    });
                                }
                            })
                        }
                    }
                }).done(function () {

                }).fail(function (jqXHR, textSttus, errorThrown) {

                });
            } else {
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

function clear_data() {
    //$('#id_persona').val('');
    $('#id_persona').focus();
    //codigos=[];
    //$("#tb_movimientos_empleado_egreso tbody tr").remove();
    //$('#btn_save_e').hide();
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
