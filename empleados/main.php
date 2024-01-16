<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(1163) || $u != NULL && $u->accesoModulo(8085)):
  switch($url)
  {
    case '_100':
      include('empleados/php/front/empleados/empleados_listado.php');
      break;
      case '_101':
        include('empleados/php/front/plazas/plazas_listado.php');
        break;
        case '_102':
          include('empleados/php/front/empleados/empleados_funcionales.php');
          break;
          case '_103':
            include('empleados/php/front/contratos/contratos_listados.php');
            break;
  }
else:
  include('./inc/401.php');
endif;
?>
