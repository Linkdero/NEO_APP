function detalleServicio(id, estado, servicio, taller) {
    let imgModal = $('#modal-remoto-lgg2');
    let imgModalBody = imgModal.find('.modal-content');

    let thisUrl = '';
    thisUrl = 'vehiculos/php/front/servicios/servicioDetalle.php';

    $.ajax({
        type: "GET",
        url: thisUrl,
        dataType: 'html',
        data: {
            id_servicio: id,
            estado: estado,
            servicio: servicio,
            taller: taller
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

function asignarMecanico(id) {
    let imgModal = $('#modal-remoto-lgg');
    let imgModalBody = imgModal.find('.modal-content');

    let thisUrl = '';
    thisUrl = 'vehiculos/php/front/servicios/asignarMecanico.php';

    $.ajax({
        type: "GET",
        url: thisUrl,
        dataType: 'html',
        data: {
            id: id
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

function finalizarservicio(id) {
    let imgModal = $('#modal-remoto-lgg');
    let imgModalBody = imgModal.find('.modal-content');

    let thisUrl = '';
    thisUrl = 'vehiculos/php/front/servicios/finalizarServicio.php';

    $.ajax({
        type: "GET",
        url: thisUrl,
        dataType: 'html',
        data: {
            id: id
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