<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';
    ?>
    <script src="assets/js/plugins/chosen/chosen.jquery.js"></script>
    <script src="assets/js/plugins/chosen/docsupport/prism.js"></script>
    <script src="assets/js/plugins/chosen/docsupport/init.js"></script>
    <link rel="stylesheet" href="assets/js/plugins/chosen/chosen.css">
    <?php

    $id_persona = $_POST['id_persona'];
    $transaccion_tipo = $_POST['transaccionTipo'];
    $transaccion_filtro = $_POST['transaccionFiltro'];

    if (is_numeric($id_persona)) {
        $empleado = array();
        $clase = new empleado;
        $empleado = $clase->get_empleado_by_id_ficha($id_persona);


        if (!empty($empleado) and $empleado['primer_nombre'] != '') {
            $estado = $clase->get_empleado_estado_by_id($id_persona);
            $data = array();
            $direccion = $empleado['dir_funcional'];

            if ($empleado['id_tipo'] == 2) {
                $direccion = $empleado['dir_nominal'];
            } else if ($empleado['id_tipo'] == 4) {
                $dir_ = $clase->get_direcciones_saas_by_id($empleado['id_dirapy']);
                $direccion = $dir_['descripcion'];
            }

            $accion = "";
            $status = '';
            $igss = '--';
            $nisp = '--';
            $observaciones = '--';
            $tipo_contrato = '--';
            $nombre = $empleado['primer_nombre'] . ' ' . $empleado['segundo_nombre'] . ' ' . $empleado['tercer_nombre'] . ' ' .
                $empleado['primer_apellido'] . ' ' . $empleado['segundo_apellido'] . ' ' . $empleado['tercer_apellido'];
            $foto = empleado::get_empleado_fotografia($id_persona);
            $encoded_image = null;
            if (!empty($foto))
                $encoded_image = base64_encode($foto['fotografia']);
            $Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";
            $resultado = '';
            $status = '';

            if ($estado['estado_persona'] == 1) {
                $status .= '<span class="badge badge-success">Activo</span>';
            } else {
                $status .= '<span class="badge badge-danger">Inactivo</span>';
            }

            $resultado .= '<div class="row card-body-slide"><div class="col-sm-3 ">';
            $resultado .= '<div class="img-contenedor_profile border-md-right border-light" style="border-radius:50%">';
            $resultado .= $Hinh;
            $resultado .= '</div>';
            $resultado .= '</div><div class="col-sm-9">';
            $resultado .= '<h3>' . $nombre . ' ' . $status . '</h3>';
            $resultado .= '<h5 class="text-muted">' . $direccion . '</h5>';
            $resultado .= '<input id="persona_id" value="'.$id_persona.'" disabled hidden>';

            if ($transaccion_tipo != 5555) {
                $resultado .= '<br>
                            <div class="row"><div class="mt-2 mb-2 col-sm-12 col-md-12 col-lg-6">
                            <select id="tipo_movimiento" data-placeholder="Seleccione el tipo de ingreso" class="form-control col-xs-12" tabindex="2" style="height:35px" required>';

                if ($transaccion_filtro != 3) {
                    $resultado .= '<option> -- Tipo de Movimiento -- </option>';
                    $tipos = insumo::get_movimiento_tipos($transaccion_tipo, $transaccion_filtro);//Ingreso a Bodega
                    foreach ($tipos as $tipo) {
                        $resultado .= '<option value="' . $tipo['id_tipo_movimiento'] . '">' . $tipo['descripcion_corta'] . '</option>';
                    }
                } else {
                    $resultado .= '<option value="1">Vacaciones</option>';
                    $resultado .= '<option value="2">Baja</option>';
                    $resultado .= '<option value="3">Remoción</option>';
                    $resultado .= '<option value="4">Traslado</option>';
                    $resultado .= '<option value="5">Fallecimiento</option>';
                    $resultado .= '<option value="6">Resición de contrato</option>';
                    $resultado .= '<option value="7">Finalización de contrato</option>';
                    $resultado .= '<option value="8">Renuncia</option>';
                    $resultado .= '<option value="9">Suspencion IGSS</option>';                    
                }

                $resultado .= '</select></div><div class="mt-2 mb-2 col-sm-12 col-md-12 col-lg-6">';
                $resultado .= '<input type="text" id="descripcion" class="form-control input-sm" style="height:35px" placeholder="Descripción" rows="1">
                                </div></div>';
                $resultado .= '</div></div>';
            }

            echo $resultado;
        } else {
            echo 'Este empleado no existe';
        }
    } else {
        echo 'Debe ingresar un valor numérico';
    }
else:
    echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
