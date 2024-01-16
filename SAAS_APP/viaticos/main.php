<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(1121)){
  switch($url)
  {
    case '_700':
    include('viaticos/php/front/viaticos/viaticos_listado.php');
    break;
    case '_701':
    include('viaticos/php/front/viaticos/viaticos_por_pais.php');
    break;

  }
}
else{
  //include('../inc/401.php');
}
?>
