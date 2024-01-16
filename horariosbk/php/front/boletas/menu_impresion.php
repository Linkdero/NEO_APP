<!-- cambiar a 512 el memory_limit dentro del archivo php.ini-->
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()) {
  $id_persona = $_POST['id_persona'];
  include_once '../../back/functions.php';
?>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="ISO-8859-1">

  </script>
  <script src="assets/js/plugins/jspdf/jspdf.js"></script>
  <script src="assets/js/plugins/jspdf/vacaciones/impresiones.js"></script>
  <div>
    <ul class="list-unstyled mb-0">
      <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="certificacion_vacaciones(<?php echo $id_persona ?>)">
          <i class="fa fa-print mr-2"></i> Certificación de Vacaciones
        </a>
      </li>
      <!-- <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" ">
              <i class=" fa fa-print mr-2"></i> Estadística de Vacaciones
        </a>
      </li> -->
      <!-- <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="periodos_vacaciones(<?php echo $id_persona ?>)">
          <i class="fa fa-print mr-2"></i> Períodos de Vacaciones
        </a>
      </li>
      <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="">
          <i class="fa fa-print mr-2"></i> Estado de Vacaciones
        </a>
      </li> -->
    </ul>
  </div>
<?php
}
?>