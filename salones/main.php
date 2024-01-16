<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(7845)) {
  switch ($url) {
    case '_300':
      include('salones/php/front/salones/principal.php');
      break;

    case '_301':
      include('salones/php/front/solicitar/principal.php');
      break;

    case '_302':
      include('salones/php/front/control/principal.php');
      break;
  }
} else {
  //include('../inc/401.php');
}
