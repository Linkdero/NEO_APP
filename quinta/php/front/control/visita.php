
<?php

if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7846)){
    include_once 'quinta/php/back/functions.php';
    $visita = new visita;
    $row_visita = $visita->get_data_by_ip($_SERVER["REMOTE_ADDR"]);
?>


    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <script src="quinta/js/cargar.js"></script>
    <script src="quinta/js/functions.js"></script>

    <script>

    </script>



    <input id="ruta" type="text" value="<?php echo $row_visita["ruta_puerta"]?>" hidden>
    <input id="ip_camara" type="text" value="<?php echo $row_visita["ip_camara"]?>" hidden>
    <input id="codigo_barra" type="text" autofocus hidden>
    <div class="u-content">
        <div class="u-body">
            <div class="card mb-4">
                <header class="card-header d-md-flex align-items-center">
                    <h2 class="h3 card-header-title" >Control de Ingreso Peatonal (<?php echo $row_visita['nombre_puerta']?>)</h2>
                    <ul class="list-inline ml-auto mb-0">
                      <li class="list-inline-item">
                        <form id="formulario_salida">
                            <div class="input-group" >
                                <input type="text" id="carneSalida" class="form-control"  style="margin-right:10px;" placeholder="No. Carné"/>
                                <button type="submit" class="btn btn-danger" onclick='save_salida()'>Salida</button>
                            </div>
                        </form>
                      </li>
                    </ul>
                </header>
                <div class="card-body card-body-slide">
                    <form id="formulario_visita" class="validation_nueva_visita">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="">
                                                <div class="row">
                                                    <label for="lugar ">Dependencia*</label>
                                                    <div class=" input-group  has-personalizado" >
                                                        <input type="text" class=" form-control " id="lugar" name="lugar" placeholder="Dependencia" required autocomplete="off"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="">
                                                    <label for="oficina_visita">Oficina que Visita</label>
                                                    <select id="oficina_visita" class="form-control" required></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="">
                                                        <div class="">
                                                            <label id="label_otro" for="oficina_otro" hidden>Especificar oficina</label>
                                                            <div class="input-group has-personalizado">
                                                                <input id="oficina_otro" class="form-control " name="oficina_otro" autofocus placeholder="Oficina" hidden>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="">
                                                    <div class="has-personalizado">
                                                    <label for="autorizacion">Persona que autoriza*</label>
                                                    <div class="input-group has-personalizado">
                                                        <input id="autorizacion" name="autorizacion" class=" form-control r"  autofocus placeholder="Persona que autoriza" required>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="">
                                                    <div class="has-personalizado">
                                                        <label for="carne">Carné*</label>
                                                        <div class="input-group has-personalizado">
                                                            <input id="carne" type="text" name="carne" class="form-control r" placeholder="Asignar carné"  onkeypress="return onKeyDownHandler(event);" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="">
                                                    <div class="has-personalizado">
                                                        <label for="objeto">Objeto en resguardo</label>
                                                        <div class="input-group has-personalizado">
                                                        <input id="objeto" name="objeto" class=" form-control r"  autofocus placeholder="Describa los objetos en resguardo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <button id="btn_save" class="btn btn-info btn-sm btn-block" onclick="save_visita(<?php echo $row_visita['id_puerta']?>)">Guardar</button>
                            </div>
                        </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="row">
                                <div id="div_preload" class="col-sm-12" style="margin-top:10%;">
                                    <div class="loaderr"></div>
                                </div>
                                <div id="imagenes_camara" class="col-sm-12" hidden>
                                    <div class="row justify-content-between">
                                        <div class="col-sm-5 offset-sm-1">
                                            <div id="doc_frontal">
                                                <div style="text-align:center;">
                                                    <button id="btn_1" type="button" class="btn btn-info">Tomar foto</button>
                                                </div>
                                                <img id="img_1" src="./assets/img/photo.jpeg" style="width:100%;height:100%;margin-top:10px;">
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div id="doc_atras">
                                                <div style="text-align:center;">
                                                    <button id="btn_2" type="button" class="btn btn-info">Tomar foto</button>
                                                </div>
                                                <img id="img_2" src="./assets/img/photo.jpeg" style="width:100%;height:100%;margin-top:10px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-11 offset-sm-1" style="margin-top:20px;">
                                        <div id="div_camara" class="embed-responsive embed-responsive-16by9">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="./assets/js/plugins/chosen/chosen.jquery.js"></script>
        <script src="./assets/js/plugins/chosen/docsupport/prism.js"></script>
        <script src="./assets/js/plugins/chosen/docsupport/init.js"></script>
        <link rel="stylesheet" href="./assets/js/plugins/chosen/chosen.css">
        <script>
            $("#formulario_salida").submit(function(e) {
                e.preventDefault();
            });

            $.ajax({
                type: "POST",
                url: "quinta/php/back/empleado/get_oficina.php",
                dataType: 'html',
                data: { id_puerta: 0 },
                success:function(data) {
                    $("#oficina_visita").html(data);
                }
            });

            $("#oficina_visita").change(function() {
                if(this.value == 0){
                    $("#label_otro").prop("hidden",false);
                    $("#oficina_otro").prop("hidden",false);
                    $("#oficina_otro").val("")
                }else{
                    let text_input = $("#oficina_visita option:selected").text();
                    $("#label_otro").prop("hidden",true);
                    $("#oficina_otro").prop("hidden",true);
                    $("#oficina_otro").val(text_input);
                }
            });

            $("#btn_1").click(() => {
                let timestamp = Math.floor( Date.now() );
                $.ajax({
                    type: "POST",
                    url: "quinta/php/front/control/foto.php",
                    dataType: 'json',
                    data: { camara: 1, ip_camara: $("#ip_camara").val(), ruta: $("#ruta").val()},
                    success:function(data) {
                        let response = JSON.parse(data);
                        if(response.code == "200"){
                           $("#img_1").prop("src", './img/<?php echo $row_visita["ruta_puerta"]?>/temp1.jpg?ver='+timestamp);
                        }
                    }
                });
            });

            $("#btn_2").click(() => {
                let timestamp = Math.floor( Date.now() );
                $.ajax({
                    type: "POST",
                    url: "quinta/php/front/control/foto.php",
                    dataType: 'json',
                    data: { camara: 2, ip_camara: $("#ip_camara").val(), ruta: $("#ruta").val() },
                    success:function(data) {
                        let response = JSON.parse(data);
                        if(response.code == "200"){
                            $("#img_2").prop("src", './img/<?php echo $row_visita["ruta_puerta"]?>/temp2.jpg?ver='+timestamp);
                        }
                    }
                });
            });

            setTimeout(() => {
                $("#div_preload").remove();
                $("#div_camara").html("<iframe id='iframe_camara' class='embed-responsive-item' src='http://<?php echo $row_visita["ip_camara"] ?>/ISAPI/Streaming/channels/102/httpPreview'></iframe>");
                $("#imagenes_camara").prop("hidden",false);
            }, 3000);


            function onKeyDownHandler(event) {
                let codigo = event.which || event.keyCode;
                if($("#carne").val().length >= 3){
                    return false;
                }else{
                    return (codigo >= 48 && codigo <= 57);
                }
            }
        
        </script>
    <?php
  }
}else{
    header("Location: index.php");
}
?>
