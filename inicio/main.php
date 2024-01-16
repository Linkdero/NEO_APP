<?php

if (verificar_session() == true){
  switch($url)
  {
    case '_001':
    include('inicio/php/front/nueva_acreditacion.php');
    break;
    case '_002':
    include('inicio/php/front/editar_acreditacion.php');
    break;
    case '_003':
    include('inicio/php/front/invitados.php');
    break;
    case '_033':
    include('inicio/php/front/control_horarios.php');
    break;

  }
}
else{
  include('./inc/401.php');
}

?>
