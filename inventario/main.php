<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(8687) ){
  switch($url)
  {
    case '_2020':
    include('inventario/views/InventarioList.php');
    break;

  }
}
else{
  //include('../inc/401.php');
}
?>
