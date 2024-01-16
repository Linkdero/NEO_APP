<?php

//$u=usuarioPrivilegiado_acceso();
if (isset($u) && $u->accesoModulo(1162)):
  switch ($url) { // agregar carpeta a principal.php
    case '_1200':
      include('vehiculos/php/front/vehiculos/principal.php');
      break;
    case '_1201':
      include('vehiculos/php/front/cupones/cupones_ingresados.php');
      break;
    case '_1202':
      include('vehiculos/php/front/cupones/cupones_entregados.php');
      break;
    case '_1203':
      include('vehiculos/php/front/vehiculo/vehiculos_listado.php');
      break;

    case '_1204':
      include('vehiculos/php/front/servicios/servicios_listado.php');
      break;
    case '_1205':
      include('vehiculos/php/front/reportes/reporteSaldoCupones.php');
      break;
  }
else:
  //include('../inc/401.php');
endif;