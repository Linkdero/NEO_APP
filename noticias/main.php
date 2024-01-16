<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(7847)){
  switch($url)
  {
    case '_600':
      include('noticias/php/front/noticias/noticias.php');
      break;



  }
}
else{
  //include('../inc/401.php');
}
?>
