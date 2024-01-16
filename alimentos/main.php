<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(7844)):
  switch($url)
  {  // agregar carpeta a principal.php
    case '_790':
      include('alimentos/php/front/empleados/asignacion_alimentos.php');
      break;
    // case '_791':
    //   include('alimentos/php/front/empleados/excepcion_alimentos.php');
    //   break;
    case '_792':
      include('alimentos/php/front/Reportes/reporte.php');
      break;
  }
else:
  include('./inc/401.php');
endif;
?>
