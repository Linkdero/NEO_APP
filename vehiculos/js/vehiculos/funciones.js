let vehiculosAsignadosModalOpen = false;
function detalleVehiculo(id) {
    let imgModal = $('#modal-remoto-lgg3');
    let imgModalBody = imgModal.find('.modal-content');
    let thisUrl = 'vehiculos/php/front/vehiculo/vehiculoFicha.php';

    $.ajax({
        type: "GET",
        url: thisUrl,
        dataType: 'html',
        data: {
            idVehiculo: id
        },
        beforeSend: function (data) {
            imgModal.modal('show');
            imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
        },
        success: function (data) {
            imgModalBody.html(data);
        }
    });
}

function formularioVehiculo(tipo, id) {
    let imgModal = $('#modal-remoto-lgg3');
    let imgModalBody = imgModal.find('.modal-content');
    let thisUrl = 'vehiculos/php/front/vehiculo/vehiculosFormulario.php';

    $.ajax({
        type: "GET",
        url: thisUrl,
        dataType: 'html',
        data: {
            id: id,
            tipo: tipo
        },
        beforeSend: function (data) {
            imgModal.modal('show');
            imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
        },
        success: function (data) {
            imgModalBody.html(data);
        }
    });
}

function vehiculosAsignados(id) {
    $("#idPersona").val(id)
    $("#click").trigger("click");
}