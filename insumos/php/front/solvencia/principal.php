<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
if (function_exists('verificar_session') && verificar_session()){
    if (usuarioPrivilegiado_acceso()->accesoModulo(3549)){
?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>

<script src="insumos/js/cargar.js"></script>

<div class="u-content">
    <div class="u-body">
        <!-- Area Solvencia -->
        <div class="card mb-4">
            <!-- Card Header -->
            <header class="card-header d-md-flex align-items-center">
                <h2 class="h3 card-header-title">Solvencia</h2>
                <!-- Nav Tabs -->
                <ul id="overallIncomeTabsControl" class="nav nav-tabs card-header-tabs ml-md-auto mt-3 mt-md-0">
                    <li class="nav-item mr-4">
                        <a class="nav-link active" href="#nuevaSolvencia" onclick="solvencia_nueva()" role="tab"
                           aria-selected="true" data-toggle="tab">
                            <span class="d-none d-md-inline">Nueva Solvencia</span>
                        </a>
                    </li>
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="#historialSolvenciasDireccion" onclick="get_all_movimientos_solvencia()"
                           role="tab" aria-selected="true" data-toggle="tab">
                            <span class="d-none d-md-inline">Historial de Solvencias</span>
                        </a>
                    </li>
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="#estadisticasSolvenciasDireccion" onclick="get_all_totales_por_direccion()"
                           role="tab" aria-selected="true" data-toggle="tab">
                            <span class="d-none d-md-inline">Estadisticas de Solvencias</span>
                        </a>
                    </li>

                </ul>
                <!-- End Nav Tabs -->
            </header>
            <!-- End Card Header -->
            <!-- Card Body -->
            <iframe id="pdf_preview_constancia" hidden></iframe>
            <div class="card-body card-body-slide">
                <div id="__data">
                </div>
            </div>
            <!-- End Card Body -->
        </div>
    </div>
    <script>
        solvencia_nueva();
    </script>
    <!-- Fin Area Solvencia -->
    <?php }
        else {
            include('inc/401.php');
        }
    }
    else {
        header("Location: index.php");
    }
    ?>
