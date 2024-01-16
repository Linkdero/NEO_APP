<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php

if (function_exists('verificar_session') && verificar_session()){
if (usuarioPrivilegiado_acceso()->accesoModulo(3549)){

$clase = new insumo();
$datos = $clase->get_acceso_bodega_usuario($_SESSION['id_persona']);
$bodega = 0;
foreach ($datos as $d) {
    $bodega = $d['id_bodega_insumo'];
}
$movimiento_tipo = $_GET['mov'] ?? NULL;
?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>

<script src="insumos/js/cargar.js"></script>

<div class="u-content">
    <div class="u-body">
        <!-- Overall Income -->
        <div class="card mb-4">
            <!-- Card Header -->
            <header class="card-header d-md-flex align-items-center">
                <h2 class="h3 card-header-title">Control de Insumos</h2>
                <!-- Nav Tabs -->
                <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                    <li class="nav-item mr-4">
                        <a class="nav-link <?php echo ($movimiento_tipo == NULL) ? 'active' : ''; ?>"
                           href="#movimientos" onclick="get_all_movimientos()" role="tab"
                           aria-selected="<?php echo ($movimiento_tipo == 2) ? 'true' : 'false'; ?>" data-toggle="tab">
                            <span class="d-none d-md-inline">Movimientos</span>
                        </a>
                    </li>
                    <li class="nav-item mr-4">
                        <a class="nav-link <?php echo ($movimiento_tipo == 1) ? 'active' : ''; ?>" href="#insumos" onclick="get_all_insumos_by_bodega()" role="tab"
                           aria-selected="<?php echo ($movimiento_tipo == 1) ? 'true' : 'false'; ?>" data-toggle="tab">
                            <span class="d-none d-md-inline">Insumos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($movimiento_tipo == 2) ? 'active' : ''; ?>"
                           href="#asignacion" onclick="get_egreso_insumo()" role="tab"
                           aria-selected="<?php echo ($movimiento_tipo == 2) ? 'true' : 'false'; ?>" data-toggle="tab">
                            <span class="d-none d-md-inline">Asignaciones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($movimiento_tipo == 4) ? 'active' : ''; ?>" href="#devolucion" onclick="get_ingreso_insumo()" role="tab"
                           aria-selected="<?php echo ($movimiento_tipo == 4) ? 'true' : 'false'; ?>" data-toggle="tab">
                            <span class="d-none d-md-inline">Devoluciones</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($movimiento_tipo == 10) ? 'active' : ''; ?>" href="#resguardo" onclick="get_entrega_insumo()" role="tab"
                           aria-selected="<?php echo ($movimiento_tipo == 10) ? 'true' : 'false'; ?>" data-toggle="tab">
                            <span class="d-none d-md-inline">Resguardo</span>
                        </a>
                    </li>
                </ul>
                <!-- End Nav Tabs -->
            </header>
            <!-- End Card Header -->
            <!-- Card Body -->
            <iframe id="pdf_preview_estado" hidden></iframe>
            <div class="block ">
                <div class="block-header bg-muted">
                    <!-- 5066 = Bodega de Armeria -->
                        <ul class="block-options slide_up_anim" id="b_1">
                        </ul>
                        <ul class="block-options  slide_up_anim" id="b_2">
                            <li>
                                <button type="button" data-toggle="modal" data-target="#modal-remoto"
                                        href="insumos/php/front/insumos/nuevo_insumo.php"><i class="fa fa-plus"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" onclick="reload_insumos_listado()" data-toggle="block-option"
                                        data-action="refresh_toggle" data-action-mode="demo"><i
                                            class="fa fa-sync  text-white"></i></button>
                            </li>

                        </ul>
                        <ul class="block-options  slide_up_anim" id="b_3">
                        </ul>
                        <ul class="block-options  slide_up_anim" id="b_4">
                        </ul>
                        <ul class="block-options  slide_up_anim" id="b_5">
                        </ul>
                    <h3 class="block-title text-white" id="titulo"></h3>
                </div>
            </div>
            <div class="card-body card-body-slide">
                <div id="_data">
                </div>
            </div>
            <!-- End Card Body -->
        </div>
    </div>
    <script>
        const queryCadena = window.location.search;
        const urlParametros = new URLSearchParams(queryCadena);
        const movimiento = urlParametros.get('mov');
        switch (Number(movimiento)) {
            case 1: //Insumos
                get_all_insumos_by_bodega();
                break;
            case 2: //Asignacion
                get_egreso_insumo();
                break;
            case 4: //Devolucion
                get_ingreso_insumo();
                break;
            case 10: //Resguardo
                get_entrega_insumo();
                break;
            default:
                get_all_movimientos();
                break;
        }
    </script>
    <!-- End Overall Income -->
    <?php
        } else {
            include('inc/401.php');
        }
    } else {
        header("Location: index.php");
    }
    ?>
