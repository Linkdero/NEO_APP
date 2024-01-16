<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(8931)) {
  switch ($url) {
    case '_3040':
      include('tickets/views/ticketsList.php');
      break;

    case '_3041':
      include('tickets/views/ticketsGrafi.php');
      break;

    case '_3042':
      include('tickets/views/reporteria.php');
      break;
    case '_3043':
      include('tickets/diagnosticos/views/diagnosticosList.php');
      break;
  }
} else {
  //include('../inc/401.php');
}