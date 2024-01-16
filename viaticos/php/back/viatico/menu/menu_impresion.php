<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  $correlativo=$_GET['correlativo'];
  include_once '../../functions.php';
  $clase = new viaticos;
  $opciones= $clase->get_opciones_menu($correlativo);

  $data = array();
  $response = '';

  $response .='<ul class="list-unstyled mb-0">

      <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="nombramiento_reporte('.$correlativo.')">
          <i class="fa fa-print mr-2"></i> Nombramiento
        </a>
      </li>
      <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_resumen('.$correlativo.')">
          <i class="fa fa-print mr-2"></i> Resumen
        </a>
      </li>';
      if($opciones['id_pais']=='GT'){
        $response.='<li class="mb-1">
          <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_constancia_vacia(1,0)">
            <i class="fa fa-print mr-2"></i> Constancia vacía
          </a>
        </li>';
      } else{

      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_exterior_vacia()">
          <i class="fa fa-print mr-2"></i> Exterior vacía
        </a>
      </li>';
    }
    if($opciones['anticipo']>0){
      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_anticipo('.$correlativo.',1,0,0,0)">
          <i class="fa fa-print mr-2"></i> Anticipo
        </a>
      </li>

      <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_monto_por_nombramiento('.$correlativo.')">
          <i class="fa fa-print mr-2"></i> Monto por Nombramiento
        </a>
      </li>';

    }
    if($opciones['constancia']>0){
      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3"  onclick="imprimir_constancia('.$correlativo.',0)">
          <i class="fa fa-print mr-2"></i> Constancia
        </a>
      </li>';
    }
    if($opciones['exterior']>0){
      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha('.$correlativo.',2)">
          <i class="fa fa-print mr-2" ></i> Exterior
        </a>
      </li>';
    }
    if($opciones['liquidacion']>0){
      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_liquidacion('.$correlativo.',0,0,0,0)">
          <i class="fa fa-print mr-2"></i> Liquidacion
        </a>
      </li>
      <li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha('.$correlativo.',3,0)">
          <i class="fa fa-print mr-2"></i> Informe
        </a>
      </li>';

      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha('.$correlativo.',5,0)">
          <i class="fa fa-print mr-2"></i> Resumen de Gastos
        </a>
      </li>';

      $response.='<li class="mb-1">
        <a class="d-flex align-items-center link-muted py-2 px-3" onclick="imprimir_con_fecha('.$correlativo.',4,0)">
          <i class="fa fa-print mr-2"></i> Nombramiento definitivo
        </a>
      </li>';
    }
      $data = array(
        'response'=>$response
      );

  echo json_encode($data);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
