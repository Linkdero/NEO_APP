<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(8326)){
  switch($url)
  {
    case '_4040':
    include('bodega/views/RequisicionesList.php');
    break;
    case '_4041':
    include('bodega/views/RequisicionesReporteList.php');
    break;

  }
}
else{
  //include('../inc/401.php');
}
?>
