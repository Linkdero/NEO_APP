<?php
//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(8017)) :
  switch ($url) {
    case '_900':
      include('documentos/php/front/documento/documentos_listado.php');
      break;
    case '_901':
      include('documentos/php/front/justificacion/justificaciones_listado.php');
      break;
      case '_905':
        include('documentos/php/front/pac/pac_listado.php');
        break;
    case '_902':
      include('documentos/php/front/pedidos/pedidos_listado.php');
      break;
    case '_903':
      include('documentos/php/front/formularios1H/formularios1h_listado.php');
      break;
    case '_904':
      include('documentos/php/front/cheques/cheques_listado.php');
    break;
    case '_906':
      include('documentos/php/front/factura/factura_listado.php');
    break;
    case '_907':
      include('documentos/php/front/insumos/insumos_listado.php');
    break;
    case '_908':
      include('documentos/php/front/insumos/fraccionamiento/fraccionamiento_listado.php');
    break;

  }
else :
  //include('../inc/401.php');
endif;
