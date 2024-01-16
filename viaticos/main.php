<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(1121) || usuarioPrivilegiado_acceso()->accesoModulo(8085)){
  switch($url)
  {
    case '_700':
    include('viaticos/php/front/viaticos/viaticos_listado.php');
    break;
    case '_701':
    include('viaticos/php/front/viaticos/viaticos_por_pais.php');
    break;
    case '_702':
    include('viaticos/php/front/viaticos/solvencia_listado.php');
    break;
    case '_703':
    include('viaticos/php/front/viaticos/formularios_listado.php');
    break;
    case '_704':
    include('viaticos/php/front/viaticos/forms_utilizados.php');
    break;
    case '_705':
    include('viaticos/php/front/viaticos/forms_utilizados_tipo.php');
    break;

  }
}
else{
  //include('../inc/401.php');
}
?>
