function save_vale_combustible() {
    jQuery('.validation_nuevo_vale').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
            jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
            var elem = jQuery(e);

            elem.closest('.form-group').removeClass('has-error').addClass('has-error');
            elem.closest('.help-block').remove();
        },
        success: function (e) {
            var elem = jQuery(e);

            elem.closest('.form-group').removeClass('has-error');
            elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
            //inicio

            // let id_entrega = $('#id_entrega').val();
            // let id_destino = $('#id_destino').val();
            // let id_vehiculo = $('#id_vehiculo').val();
            // let txt_caracter = $('#txt_caracter').val();
            // let id_conductor = $('#id_conductor').val();
            // let id_evento = $('#id_evento').val();
            // let id_galones = $('#id_galones').val();
            // let id_combustible = $('#id_combustible').val();
            // let observa = $('#observa').val();
            let xlleno = ($('#chk_Tanque').is(':checked')) ? 1 : 0;
            // let data = {};

            // data = {id_entrega, id_destino, id_vehiculo, txt_caracter, id_conductor, id_evento, id_galones, id_combustible, observa, tlleno};

            Swal.fire({
                title: '<strong>¿Desea grabar este vale ?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then((result) => {
                console.log(result);
                if (result.value) {
                    //alert(vt_nombramiento);
                    $.ajax({
                        type: "POST",
                        url: "vehiculos/php/back/save/save_vale_combustible.php",
                        data: {
                            id_entrega: $('#id_entrega').val(),
                            id_destino: $('#id_destino').val(),
                            id_vehiculo: ($('#id_vehiculo').val() != 0) ? $('#id_vehiculo').val() : 0,
                            txt_caracter: $('#txt_caracter').val(),
                            id_conductor: $('#id_conductor').val(),
                            id_evento: $('#id_evento').val(),
                            id_galones: $('#id_galones').val(),
                            id_combustible: $('#id_combustible').val(),
                            observa: $('#observa').val(),
                            tlleno: xlleno
                        },
                        beforeSend: function () {
                            //$('#response').html('<span class="text-info">Loading response...</span>');
                            //alert($('#response'));
                        },
                        success: function (data) {
                            $('#modal-remoto-lg').modal('hide');
                            reload_movimientos_en();
                            Swal.fire({
                                type: 'success',
                                title: 'Vale grabado',
                                showConfirmButton: false,
                                timer: 1100
                            });

                            //alert(data);
                        }
                    }).done(function () {


                    }).fail(function (jqXHR, textSttus, errorThrown) {

                        alert(errorThrown);

                    });
                }

            })

        },
        rules: {
            id_destino: { required: true },
            placa: { required: true },
            id_conductor: { required: true },
            //id_evento: {required:true},
            id_galones: {

                required: true,
                number: true,
                min: 1,
                remote: {
                    url: 'vehiculos/php/back/listados/get_validacion_capa.php',
                    data: {
                        id_vehiculo: $('#id_vehiculo').val(),
                        id_galones: $('#id_galones').val()
                    }
                }
            },

            tipoCombus: { required: true }

        },

        //  messages: {
        //    id_galones: {
        //      remote: "No puede ingresar cantidad mayor a capacidad del tanque."
        //    }
        //  }

    });
}

function anulaVale() {

    jQuery('.validation_anula_vale').validate({
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
            let nro_vale = $("#nro_vale").val();
            let data = { nro_vale };
            title = "¿Desea anular Vale ?";
            btn_text = "¡Si, Anular!";

            Swal.fire({
                title: '<strong></strong>',
                text: title,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f66',
                cancelButtonText: 'Cancelar',
                confirmButtonText: btn_text
            }).then((result) => {
                console.log(result);
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "vehiculos/php/back/vehiculos/anula_vale.php",
                        data: data,
                        dataType: "json",
                        success: function (data) {
                            $('#modal-remoto').modal('hide');
                            reload_movimientos_en();

                            if (data.status) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Vale anulado',
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
                            title: 'Error al anular',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    });
                }
            });
        }
    });
}

function despachaVale() {

    jQuery('.validation_desp_vale').validate({
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
            var elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error');
            elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
            let nro_vale = $("#nro_vale").val();
            let data = { nro_vale };
            title = "¿Desea despachar Vale ?";
            btn_text = "¡Si, Despachar!";

            Swal.fire({
                title: '<strong>¿Desea despachar este vale ?</strong>',
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
                        url: "vehiculos/php/back/vehiculos/despacha_vale.php",
                        data: {
                            nro_vale: $('#nro_vale').val(),
                            id_despacha: $('#id_despacha').val(),
                            id_recibe: $('#id_recibe').val(),
                            id_bomba: $('#id_bomba').val(),
                            cant_galones: $('#cant_galones').val(),
                            cant_autor: $('#cant_autor').val(),
                            km_actual: $('#km_actual').val(),
                            observa: $('#observa').val(),
                            id_tipo_combustible: $('#id_tipo_combustible').val()
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#modal-remoto-lg').modal('hide');
                            reload_movimientos_en();

                            if (data.status) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Vale despachado',
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
                            title: 'Error al despachar',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    });
                }
            });
        }
    });
}

function despachaVale() {

    jQuery('.validation_desp_vale').validate({
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
            var elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error');
            elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
            let nro_vale = $("#nro_vale").val();
            let data = { nro_vale };
            title = "¿Desea despachar Vale ?";
            btn_text = "¡Si, Despachar!";

            Swal.fire({
                title: '<strong>¿Desea despachar este vale ?</strong>',
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
                        url: "vehiculos/php/back/vehiculos/despacha_vale.php",
                        data: {
                            nro_vale: $('#nro_vale').val(),
                            id_despacha: $('#id_despacha').val(),
                            id_recibe: $('#id_recibe').val(),
                            id_bomba: $('#id_bomba').val(),
                            cant_galones: $('#cant_galones').val(),
                            cant_autor: $('#cant_autor').val(),
                            km_actual: $('#km_actual').val(),
                            observa: $('#observa').val(),
                            id_tipo_combustible: $('#id_tipo_combustible').val()
                        },
                        dataType: "json",
                        success: function (data) {
                            $('#modal-remoto-lg').modal('hide');
                            reload_movimientos_en();

                            if (data.status) {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Vale despachado',
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
                            title: 'Error al despachar',
                            showConfirmButton: false,
                            timer: 1100
                        });
                    });
                }
            });
        }
    });
}