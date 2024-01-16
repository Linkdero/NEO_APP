<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(1121) || usuarioPrivilegiado_acceso()->accesoModulo(8085)){
  switch($url)
  {
    case '_3030':
    include('transportes/views/TransporteList.php');
    break;

  }
}
else{
  //include('../inc/401.php');
}
?>
