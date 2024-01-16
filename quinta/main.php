<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(7846)){
  switch($url)
  {
    case '_400':
      include('quinta/php/front/control/principal.php');
      break;

    case '_401':
      include('quinta/php/front/control/visita.php');
      break;

    case '_402':
      include('quinta/php/front/control/reporte.php');
      break;

      case '_403':
        include('quinta/php/front/control/asignacion.php');
        break;

  }
}
else{
  //include('../inc/401.php');
}
?>
