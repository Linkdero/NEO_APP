<?php
//$u=usuarioPrivilegiado_acceso();
// if (isset($u) && $u->accesoModulo(1785)):
if (true) :
  switch ($url) {
    case '_1000':
      include('directorio/php/front/dependencias/dependencias.php');
      break;
    case '_1001':
      include('directorio/php/front/telefonos/telefonos_personales.php');
      break;
    case '_1002':
      include('directorio/php/front/control/acciones_personas.php');
      break;
  }
else :
  include('./inc/401.php');
endif;
