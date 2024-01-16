function edit_row(id_detalle, numero, nombre, puesto) {
    (async () => {
        const { value: formValues } = await Swal.fire({
            title: 'Multiple inputs',
            html: `<div class='col-sm-12'>
                    <div class='form-group'>
                        <div class='row'>
                            <label for='numero'>Numero</label>
                            <div class='input-group' >
                                <input id='numero' type='text' class='form-control' value=${numero}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <div class='row'>
                            <label for='nombre'>Nombre</label>
                            <div class='input-group' >
                                <input id='nombre' type='text' class='form-control' value=${nombre}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-sm-12'>
                    <div class='form-group'>
                        <div class='row'>
                            <label for='puesto'>Puesto</label>
                            <div class='input-group' >
                                <input id='puesto' type='text' class='form-control' value=${puesto}>
                            </div>
                        </div>
                    </div>
                </div>`,
            focusConfirm: true,
            preConfirm: () => {
                return [
                    $("#numero").val(),
                    $("#nombre").val(),
                    $("#puesto").val()
                ]
            }
        })

        if (formValues) {
            Swal.fire(JSON.stringify(formValues))
        }

    })()
}

function delete_row(id_detalle) {
    Swal.fire({
        title: '<strong></strong>',
        text: "¿Desea eliminar el registro?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f52c2c',
        confirmButtonText: '¡Si, Eliminar!',
        cancelButtonText: 'Cancelar',

    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "directorio/php/back/dependencias/update_detalle.php",
                dataType: 'json',
                data: { opcion: 0, id_detalle },
                success: function (response) {
                    if (response) {
                        Swal.fire({
                            type: 'success',
                            title: 'Registro eliminado',
                            showConfirmButton: false,
                            timer: 1100
                        });
                        reload_detalle();
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    }
                }
            });
        }
    });
}

function inactivar_tel(id_persona, nro_telefono) {
    Swal.fire({
        title: '<strong></strong>',
        text: "¿Desea eliminar el registro?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f52c2c',
        confirmButtonText: '¡Si, Eliminar!',
        cancelButtonText: 'Cancelar',

    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "directorio/php/back/telefonos/update_telefonos.php",
                dataType: 'json',
                data: { opcion: 0, id_persona: id_persona, nro_telefono: nro_telefono },
                success: function (response) {
                    if (response) {
                        Swal.fire({
                            type: 'success',
                            title: 'Registro eliminado',
                            showConfirmButton: false,
                            timer: 1100
                        });
                        reload_detalle();
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    }
                }
            });
        }
    });
}

function agregar_telefono() {
    let length = ($("input[name=telefonos]").length) + 1;
    $('#div-telefonos').append(`<div class='col-sm-12' id='new-${length}'>
                                    <div class='form-group'>
                                        <label for='telefono'>Teléfono ${length}</label>
                                        <div class='input'>
                                            <input type='text' class='form-control' name='telefonos'/>
                                        </div>
                                    </div>
                                </div>`);
    $("input .form-control:last").focus();

}

function save_dependencia() {
    let id_dependencia = $("#id_dependencia").val();
    let funcionario = $("#funcionario").val();
    let direccion = $("#direccion").val();
    let telefonos = $("#form-dependencia").serializeArray();
    let data = { id_dependencia, funcionario, direccion, telefonos, opcion: 1 };

    $.ajax({
        type: "POST",
        url: "directorio/php/back/dependencias/update_detalle.php",
        dataType: 'json',
        data: data,
        success: function (response) {
            console.log(response);
            if (response == true) {
                Swal.fire({
                    type: 'success',
                    title: 'Registro guardado',
                    showConfirmButton: false,
                    timer: 1100
                });
                reload_dependencias();
                $('#modal-remoto').modal('toggle');
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    showConfirmButton: false,
                    timer: 1100
                });
            }
        }
    });
}

function save_cell(evt) {
    evt.preventDefault();
    let id_dependencia = $("#dependencia").val();
    let numero = $("#numero").val();
    let nombre = $("#nombre").val();
    let puesto = $("#puesto").val();
    let data = { id_dependencia, numero, nombre, puesto };

    $.ajax({
        type: "POST",
        url: "directorio/php/back/dependencias/save_cell.php",
        dataType: 'json',
        data: data,
        success: function (response) {
            if (response) {
                Swal.fire({
                    type: 'success',
                    title: 'Registro guardado',
                    showConfirmButton: false,
                    timer: 1100
                });
                $("form")[0].reset();
                tabla_detalle.ajax.reload(null, false);
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    showConfirmButton: false,
                    timer: 1100
                });
            }
        }
    });
}
