<?php
$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(7851))://7684 Administrador
  switch($url)
  {
    case '_500':
      include('configuracion/php/front/modulos/principal.php');
      break;
    case '_501':
      include('configuracion/php/front/herramientas/catalogo.php');
      break;
    case '_502':
      include('configuracion/php/front/herramientas/usuarios.php');
      break;
    case '_503':
      include('configuracion/php/front/herramientas/listadoppr.php');
      break;
    case '_504':
      include('configuracion/php/front/herramientas/extensiones.php');
      break;  


  }
else:
  include('./inc/401.php');
endif;
?>
