
<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once 'inc/functions.php';
include_once 'insumos/php/back/functions.php';
include_once 'empleados/php/back/functions.php';
include_once 'noticias/php/back/functions.php';
include_once 'quinta/php/back/functions.php';
include_once 'directorio/php/back/functions.php';
include_once 'viaticos/php/back/functions.php';

sec_session_start();
if (verificar_session() == true) :
    require 'inc/config.php';
    require 'inc/views/template_head_start.php';
    require 'inc/views/template_head_end.php';
    require 'inc/views/base_head.php';

    if (!isset($_GET['ref'])) {
        //include 'inicio/dashboard.php';
        ?>
        <div class="col-lg-12 d-none d-lg-flex flex-column align-items-center justify-content-center bg-light">
					<img class="img-fluid position-relative u-z-index-3 mx-5 animacion_left_to_right" src="./assets/svg/mockups/LogoGrande2024-bg.png" alt="Image description">
				</div>
        <?php
    } else {
        $url = $_GET['ref'];
        require 'configuracion/main.php';
        require 'alimentos/main.php';
        require 'vehiculos/main.php';
        require 'empleados/main.php';
        require 'insumos/main.php';
        require 'salones/main.php';
        require 'quinta/main.php';
        require 'noticias/main.php';
        require 'horarios/main.php';
        require 'directorio/main.php';
        require 'viaticos/main.php';
        require 'documentos/main.php';
        require 'tickets/main.php';
        require 'bodega/main.php';
        require 'inventario/main.php';
        require 'transportes/main.php';
    }

    require 'inc/views/base_footer.php';
    require 'inc/views/template_footer_start.php';
    require 'inc/views/template_footer_end.php';
else :
    header("Location: index.php");
endif;
?>
