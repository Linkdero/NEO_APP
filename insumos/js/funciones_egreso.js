//EGRESOS
$(document).ready(function () {
    $('#tb_movimientos_empleado_egreso tbody').on('click', '.form-control', function () {

        this_code = ($(this).attr('id'));
        $("#" + this_code).keyup(function () {
            cantidad = $('#' + this_code).val();
            //var str = document.getElementById("demo").innerHTML;
            var str = this_code;
            var res = str.replace("txt", "");

            if (evaluar_cantidad(cantidad)) {
                $.ajax({
                    type: "POST",
                    url: "insumos/php/back/insumos/evaluar_insumo_existencia.php",
                    //dataType:'json',
                    data: {
                        id_producto: res,
                        cantidad: cantidad
                    },
                    beforeSend: function () {
                        $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                    },
                    success: function (data) {
                        //alert(data);
                        if (data == 'true') {
                            $('#' + res).removeClass('danger');
                        } else {
                            $('#' + res).addClass('danger');
                        }

                    }
                });
            } else {
                $('#' + res).addClass('danger');

            }
        });
    });
});


function agregar_insumo() {
    //alert($('#serie').val() );
    $.ajax({
        type: "POST",
        url: "insumos/php/back/insumos/get_insumo.php",
        dataType: 'json',
        data: {
            serie: $('#serie').val()
        },
        beforeSend: function () {
            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
        },
        success: function (data) {
            $('#serie').val('');
            if (!document.getElementById(data.codigo)) {
                if (data.codigo != '') {
                    if (data.estado == 5337) {
                        $('#btn_save_e').show();
                        $('#btn_clear').show();
                        $('#serie').val('');
                        Swal.fire({
                            type: 'success',
                            title: 'Insumo agregado',
                            showConfirmButton: false,
                            timer: 700
                        });

                        var html_code = "<tr id='" + data.codigo + "'>";
                        html_code += "<td style='' class='_marca text-center' id='" + data.marca + "'>" + data.marca + "</td>";
                        html_code += "<td style='' class='text-center'>" + data.modelo + "</td>";
                        html_code += "<td style='' class='text-center'>" + data.serie + "</td>";
                        html_code += "<td style='' class='text-center'>" + data.existencia + "</td>";
                        html_code += "<td style='' class='text-center'>" + data.cantidad + "</td>";
                        html_code += "<td class='text-center' ><span class='remove btn btn-sm btn-danger' data-row='" + data.codigo + "'><i class='fa fa-times'></i></span></td>";
                        html_code += "</tr>";
                        $('#tb_movimientos_empleado_egreso').append(html_code);
                        mostrar_totales();
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Este insumo no se encuentra disponible',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    }
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'No existe este código',
                        showConfirmButton: false,
                        timer: 1100
                    });
                }
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Ya existe este insumo en la lista',
                    showConfirmButton: false,
                    timer: 1100
                });
            }
        }
    }).done(function () {


    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
    });


}


$(document).on('click', '.remove', function () {
    $('#serie').focus();

    var delete_row = $(this).data("row");

    $('#' + delete_row).remove();


    /*for( var i = 0; i < codigos.length; i++){
       if ( codigos[i] == delete_row) {
         codigos.splice(i, 1);*/
    //console.log("Tenemos el arreglo: ", codigos);
    Swal.fire({
        type: 'success',
        title: 'Insumo removido',
        showConfirmButton: false,
        timer: 100
    });
    // }
//}

    var x = document.getElementById("tb_movimientos_empleado_egreso").rows.length;
    if ((x - 1) == 0) {
        $('#btn_save_e').fadeOut("slow");
        $('#btn_clear').fadeOut("slow");
    }
    mostrar_totales();


});


function crear_egreso() {
    var total = 0;
    var nFilas_total = $("#tb_movimientos_empleado_egreso tbody tr").length;

    var nFilas = $("#tb_movimientos_empleado_egreso tbody tr.danger").length;
    if (nFilas_total <= 32) {
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
                            //showConfirmButton: false,
                            //timer: 1000
                        });
                    } else if (data == 'error1') {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: "Debe seleccionar un empleado activo",
                            //showConfirmButton: false,
                            //timer: 1000
                        });
                    } else {
                        if ($('#id_persona_diferente').val() == '') {

                        } else {
                            if ($('#id_persona_diferente').val() == $('#id_persona').val()) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: "Debe seleccionar un empleado diferente al primero",
                                    //showConfirmButton: false,
                                    //timer: 1000
                                });
                            } else {
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

                                        if (data == 'error3') {
                                            Swal.fire({
                                                type: 'error',
                                                title: 'Error',
                                                text: "Debe seleccionar un empleado 2",
                                                //showConfirmButton: false,
                                                //timer: 1000
                                            });
                                        } else if (data == 'error1') {
                                            Swal.fire({
                                                type: 'error',
                                                title: 'Error',
                                                text: "Debe seleccionar un empleado activo al empleado 2",
                                                //showConfirmButton: false,
                                                //timer: 1000
                                            });
                                        }
                                    }

                                });
                            }
                        }
                        if ($('#id_persona_diferente').val() == $('#id_persona').val()) {
                            Swal.fire({
                                type: 'error',
                                title: 'Seleccione un empleado diferente al primero',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        } else if ($('#id_persona_diferente').val() != $('#id_persona').val()) {
                            //alert(data);
                            Swal.fire({
                                title: '<strong>¿Quiere generar este Egreso?</strong>',
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
                                    $('#tb_movimientos_empleado_egreso tbody tr').each(function(row, tr){
                                        insumo = $(tr).attr('id');
                                        insumoIds = insumo.split('-');
                                        cantidad = $('#txt' + insumoIds[1]).val();
                                        TableData[row]={
                                            "id_prod_insumo" : insumoIds[0],
                                            "id_prod_insumo_detalle": insumoIds[1],
                                            "cantidad" : cantidad
                                        }
                                    });
                                    var jsonTableData = JSON.stringify(TableData);
                                    //console.log(jsonTableData);

                                    $.ajax({
                                        type: "POST",
                                        url: "insumos/php/back/insumos/create_movimiento.php",
                                        //dataType:'json',
                                        data: {
                                            id_persona: function () {
                                                return $('#id_persona').val()
                                            },
                                            id_persona_diferente: function () {
                                                return $('#id_persona_diferente').val()
                                            },
                                            descripcion: function () {
                                                return $('#descripcion').val()
                                            },
                                            tipo_movimiento: function () {
                                                return $('#tipo_movimiento').val()
                                            },
                                            id_persona_autoriza : function () {
                                                return $('#id_persona_autoriza').val()
                                            },
                                            nro_documento : function () {
                                                return $('#nro_documento').val()
                                            },
                                            movimientoDetalle : jsonTableData

                                        },
                                        beforeSend: function () {
                                            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                                        },
                                        success: function (data) {
                                            egresoID = data;
                                        }
                                    }).done(function () {
                                        Swal.fire({
                                            type: 'success',
                                            title: 'Egreso generado',
                                            showConfirmButton: false,
                                            timer: 1000
                                        });
                                        reporte_movimiento(egresoID);
                                        clear_data();
                                        get_empleado_insumos(4353, 0);
                                    }).fail(function (jqXHR, textStatus, errorThrown) {
                                        console.log(errorThrown);
                                    });
                                    //fin funcionamiento
                                }
                            })

                        }
                    }
                }
            }).done(function () {


            }).fail(function (jqXHR, textStatus, errorThrown) {

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

    /**/
}

function clear_data() {
    $('#id_persona').val('');
    $('#id_persona_diferente').val('');
    $('#id_persona_diferente').keyup();
    $('#id_persona_autoriza').val('');
    $('#id_persona_autoriza').keyup();
    $('#nro_documento').val('');
    $('#id_persona').focus();
    //$("#tb_movimientos_empleado_egreso tbody tr").remove();
    $('#tb_movimientos_empleado_egreso').find('tbody').empty();
    $('#btn_save_e').hide();
    $('#btn_clear').hide();
    mostrar_totales();
    //get_empleado_insumos(4353, 0);
    //get_empleado_insumos(5555, 0);
}

function evaluar_cantidad(cantidad) {
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

function mostrar_totales() {
    var nFilas_total = $("#tb_movimientos_empleado_egreso tbody tr").length;
    if (nFilas_total == 0) {
        $('#desc_').text('');
    } else {
        var t = '';
        if (nFilas_total == 1) {
            t = 'insumo asginado';
        } else {
            t = 'insumos asignados';
        }
        $('#desc_').text('Tiene ' + nFilas_total + ' ' + t + ' a esta Transacción');
    }
}

$(function () {


});
//INGRESOS
