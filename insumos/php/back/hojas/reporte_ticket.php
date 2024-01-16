<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $transaccion = $_GET['id_doc_insumo'] ?? NULL;
    $insumos = (new insumo)->get_insumos_by_transaccion($transaccion);
    $data = array();

    $empleados = (new insumo)->get_empleados_by_transaccion($transaccion);
    $empleado_secundario = '- Ninguno -';
    $observaciones = NULL;

    foreach ($empleados as $empleado) {
        $persona = (new empleado)->get_empleado_by_id_ficha($empleado['id_persona']);
        if ($empleado['flag_firmante']) {
            $direccion = (new empleado)->get_direcciones_saas_by_id($empleado['id_persona_direccion_recibe']);
            $movimiento = $empleado['tipo_movimiento'];
            $observaciones = $empleado['descripcion'] ?? NULL;
            $t_fecha = $empleado['transaccion_fecha'];
            $transaccion_fecha = date("d/m/Y H:i:s", strtotime($t_fecha));
            $empleado_primario = $persona['primer_nombre'] . ' ' . $persona['segundo_nombre'] . ' ' .
                $persona['tercer_nombre'] . ' ' . $persona['primer_apellido'] . ' ' . $persona['segundo_apellido'] . ' ' .
                $persona['tercer_apellido'] . ' [' . $empleado['id_persona'] . ']';
        } else {
            $empleado_secundario = $persona['primer_nombre'] . ' ' . $persona['segundo_nombre'] . ' ' .
                $persona['tercer_nombre'] . ' ' . $persona['primer_apellido'] . ' ' . $persona['segundo_apellido'] . ' ' .
                $persona['tercer_apellido'] . ' [' . $empleado['id_persona'] . ']';
        }
    }

    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id_persona_entrega,id_bodega_insumo
          FROM inv_movimiento_encabezado
          WHERE id_doc_insumo=?
         ";
    $p = $pdo->prepare($sql);
    $p->execute(array($transaccion)); // 65 es el id de aplicaciones
    $encargado = $p->fetch();

    $sql2 = "SELECT b.descripcion_corta
          FROM inv_movimiento_encabezado a
          INNER JOIN tbl_catalogo_detalle b ON a.id_bodega_insumo=b.id_item
          WHERE a.id_doc_insumo=?
         ";
    $p2 = $pdo->prepare($sql2);
    $p2->execute(array($transaccion)); // 65 es el id de aplicaciones
    $bodega = $p2->fetch();

    Database::disconnect_sqlsrv();

    $encargado_ficha = (new empleado)->get_empleado_by_id_ficha($encargado['id_persona_entrega']);
    $encargado_nombre = $encargado_ficha['primer_nombre'] . ' ' . $encargado_ficha['segundo_nombre'] . ' ' . $encargado_ficha['primer_apellido'] . ' ' . $encargado_ficha['segundo_apellido'];

//echo json_encode($data);

else :

endif;
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>

<body onload="imprimir()">
    <div class="ticket centrado">
        <p style="text-align:left">
            <br><b>Bodega:</b> <?php echo $bodega['descripcion_corta'] ?? ""; ?>
            <br><b>No. Documento:</b> <?php echo $transaccion ?? ""; ?>
            <br><b>Movimiento:</b> <?php echo $movimiento ?? ""; ?>
            <br><b>Fecha:</b> <?php echo $transaccion_fecha; ?>
            <?php if ($movimiento == 'Resguardo') : ?>
                <br><b>Entrega:</b> <?php echo $empleado_primario; ?>
            <?php endif; ?>
            <?php if ($movimiento != 'Resguardo') : ?>
                <br><b>Entrega:</b> <?php echo $encargado_nombre; ?>
            <?php endif; ?>
        </p>

        <table>
            <thead>
                <tr>
                    <th class="insumo">EQUIPO</th>
                    <th class="cantidad">CANT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($insumos as $insumo) {

                    $chk1 = '';
                    $cantidad = 0;

                    if (
                        $insumo['id_tipo_movimiento'] == 1 || $insumo['id_tipo_movimiento'] == 4
                        || $insumo['id_tipo_movimiento'] == 10
                    ) {
                        $cantidad = $insumo['cantidad_devuelta'];
                    } else
                if (
                        $insumo['id_tipo_movimiento'] == 2 || $insumo['id_tipo_movimiento'] == 3
                        || $insumo['id_tipo_movimiento'] == 7
                    ) {
                        $cantidad = $insumo['cantidad_entregada'];
                    }

                    //$chk1.='<label class="css-input switch switch-danger"><input name="check" data-id="" type="checkbox"/><span></span></label>';
                    //$movimiento = insumo::get_tipo_movimiento_by_id($m['id_tipo_movimiento']);
                    $tipo_ = '';
                    if ($bodega['descripcion_corta'] == 'Moviles') {
                        $tipo_ = $insumo['codigo_inventarios'];
                    } else {
                        $tipo_ = $insumo['descripcion_corta'];
                    }
                    echo '<tr>';
                    echo '<td class="insumo" style="text-align: left">';
                    ($insumo['numero_estante']) ? $estante = '<br><b>Casilla:</b> ' . $insumo['numero_estante'] : $estante = '';
                    echo $insumo['modelo'] . '<br><b>Registro:</b> ' . $insumo['numero_serie'] . $estante;
                    echo '</td>';
                    echo '<td class="cantidad">';
                    echo number_format($cantidad);
                    echo '</td>';

                    $sub_array = array(
                        'transaccion' => $insumo['id_doc_insumo'],
                        'fecha' => $insumo['fecha'],
                        'direccion' => $direccion['descripcion'],
                        'encargado' => $encargado_nombre,
                        'bodega' => $bodega['descripcion_corta'],
                        'hora' => '',
                        'empleado' => $empleado_primario,
                        'empleado2' => $empleado_secundario,
                        'movimiento' => $movimiento,
                        'tipo' => $tipo_,
                        'marca' => $insumo['marca'],
                        'modelo' => $insumo['modelo'],
                        'serie' => $insumo['numero_serie'],
                        'cantidad' => number_format($cantidad, 0, ".", ",")

                    );
                }

                ?>
            </tbody>
        </table>

        <p style="text-align: left">
            <?php
            if ($observaciones)
                echo '<b>Observaciones:</b><br>' . $observaciones . '<br><br>';
            ?>
            <b>Firma:</b>
        </p>
        <p class="centrado">
            <br><br>_________________________________
            <br><?php if ($movimiento == 'Resguardo') : ?>
                <?php echo $encargado_nombre; ?>
            <?php endif; ?>
            <?php if ($movimiento != 'Resguardo') : ?>
                <?php echo $empleado_primario; ?>
            <?php endif; ?>
        </p>
        <p class="centrado pie-pagina">
            <br>Reporte Generado SAAS - control de Insumos
            <br>6ta. Avenida 4-18 Zona 1, Callejon "Del Manchen"
            <br>PBX: 2327-6000 FAX: 2327-6090
        </p>
    </div>
</body>

</html>