<div class="modal-header">
    <h3 class="modal-title">Solicitud de Salón</h5>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>

</div>
<div class="modal-body">
    <form class="validation_solicitud">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row">
                            <label for="salon">Salón</label>
                            <div class="input-group has-personalizado">
                                <select class="form-control" id="salon"></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div id="div_fecha_inicio">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="fechainicio">Fecha Inicio</label>
                                <div class="input-group has-personalizado">
                                    <input type="datetime-local" class="form-control" id="fechainicio" name="fechainicio" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div id="div_fecha_fin">
                        <div class="row">
                            <label for="fechafin">Fecha Fin</label>
                            <div class="input-group has-personalizado">
                                <input type="datetime-local" class="form-control" id="fechafin" name="fechafin" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row">
                            <label for="motivo">Motivo</label>
                            <div class="input-group has-personalizado">
                                <input type="text" class="form-control" id="motivo" name="motivo" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row" id="row_mobiliario">
                            <label class="css-input switch switch-success">
                                <input name="check" id="chk_mobiliario" data-id="" onchange="mostrar_mobiliario()" data-name="" type="checkbox" /><span></span>
                                Mobiliario
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="">
                        <div class="row" id="row_equipo">
                            <label class="css-input switch switch-success">
                                <input name="check" id="chk_equipo" data-id="" onchange="mostrar_equipo()" data-name="" type="checkbox" /><span></span>
                                Audiovisuales
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="btn_form" class="btn btn-block btn-sm btn-info" onclick="save_solicitud()"><i class="fa fa-save"></i> Generar Solicitud</button>
    </form>
    <script>
        $.ajax({
            type: "POST",
            url: "salones/php/back/listados/get_salones.php",
            dataType: 'html',
            data: {
                opcion: 2
            },
            success: function(data) {
                $("#salon").html(data);
            }
        });

        function mostrar_mobiliario() {
            if ($('#chk_mobiliario').is(':checked')) {
                $('#row_mobiliario').append(`<div class="input-group has-personalizado" id="div_mobiliario" style="margin: 10px 0px 10px 0px; ">
                                                <input type="text" class="form-control" id="mobiliario" name="mobiliario" required/>
                                            </div>`);
            } else {
                $('#div_mobiliario').remove();
                $('#mobiliario-error').remove();
            }
        }

        function mostrar_equipo() {
            if ($('#chk_equipo').is(':checked')) {
                $('#row_equipo').append(`<div class="input-group has-personalizado" id="div_equipo" style="margin: 10px 0px 10px 0px; ">
                                            <input type="text" class="form-control" id="equipo" name="equipo" required/>
                                        </div>`);
            } else {
                $('#div_equipo').remove();
                $('#equipo-error').remove();
            }
        }

        $("#fechainicio").change(function() {
            let fecha = $("#fechainicio").val().replace("T", " ");
            let salon = $("#salon option:selected").val();
            $.ajax({
                type: "POST",
                url: "salones/php/back/salones/validate_date.php",
                dataType: 'json',
                data: {
                    fecha,
                    salon
                },
                success: function(response) {
                    $("#warning-inicio").remove();
                    if (response.status == "bussy") {
                        $("#btn_form").attr("disabled", true);
                        $("#div_fecha_inicio").append(`<div id="warning-inicio" class="animated fadeInDown" style="color: #fd5656;">${response.msg}.</div>`);
                    } else {
                        $("#btn_form").attr("disabled", false);
                        $("#warning-inicio").remove();
                    }
                    console.log(response);
                }
            });
        });

        $("#fechafin").change(function() {
            let fecha = $("#fechafin").val().replace("T", " ");
            let salon = $("#salon option:selected").val();
            $.ajax({
                type: "POST",
                url: "salones/php/back/salones/validate_date.php",
                dataType: 'json',
                data: {
                    fecha,
                    salon
                },
                success: function(response) {
                    $("#warning-fin").remove();
                    if (response.status == "bussy") {
                        $("#btn_form").attr("disabled", true);
                        $("#div_fecha_fin").append(`<div id="warning-fin" class="animated fadeInDown" style="color: #fd5656;">${response.msg}.</div>`);
                    } else {
                        $("#btn_form").attr("disabled", false);
                        $("#warning-fin").remove();
                    }
                    console.log(response);
                }
            });
        });
    </script>
</div>