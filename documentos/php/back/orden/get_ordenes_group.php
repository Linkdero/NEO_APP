<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    //include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    include_once '../functions/return_privilegios.php';

    $tipo = $_POST['tipo'];
    $year = $_POST['year'];

    $pendiente = $_POST['pendiente'];
    $aprobado = $_POST['aprobado'];
    $compromiso = $_POST['compromiso'];
    $devengado = $_POST['devengado'];
    $liquidacion = $_POST['liquidacion'];
    $sinliquidacion = $_POST['sinliquidacion'];
    $compras = (!empty($_POST['compras'])) ? $_POST['compras'] : NULL;
    $presupuesto = (!empty($_POST['presupuesto'])) ? $_POST['presupuesto'] : NULL;
    $subdirector = (!empty($_POST['subdirector'])) ? $_POST['subdirector'] : NULL;
    $privilegio = retornaPrivilegios();

    $clased = new documento;

    $data = array();

    $ordenes = array();

    $compras_jefe = ($privilegio['compras_au'] == 'true') ? true : false;//(evaluar_flag($_SESSION['id_persona'],8017,305,'flag_autoriza')==1 && evaluar_flag($_SESSION['id_persona'],8017,302,'flag_autoriza')==1) ? true : false;
    $compras_tecnico = $privilegio['compras_tecnico'];//(evaluar_flag($_SESSION['id_persona'],8017,302,'flag_actualizar')==1) ? true : false;
    $presupuesto_au = $privilegio['presupuesto_au']; //(evaluar_flag($_SESSION['id_persona'],8017,323,'flag_autoriza')==1) ? true : false;
    $presupuesto = $privilegio['presupuesto'];//(evaluar_flag($_SESSION['id_persona'],8017,323,'flag_es_menu')==1) ? true : false;
    $subdirectorfinanciero = $privilegio['subdirectorfinanciero'];
    $directorf_au = $privilegio['directorf_au'];

    if($compras_jefe == true || $compras_tecnico == true || $presupuesto_au == true || $presupuesto == true || $subdirectorfinanciero == true){
      $ordenes = $clased->get_ordenes_group_pendientes($tipo,$year,$privilegio,$pendiente,$aprobado,$compromiso,$devengado,$liquidacion,$sinliquidacion,$compras,$presupuesto,$subdirector);
    }

    $type = $tipo;

    foreach ($ordenes as $orden) {
      // code...
      //if(!empty($orden['id_tipo_pago'])){
        $proceso = '';
        if($orden['id_tipo_pago'] == 1){
          $proceso = "Nro. de Orden";
        }else{
          $proceso = "CYD";
        }

        $tipo_pago = $clased->retornaTipoOrden($orden['id_tipo_pago']);


        $tipo = 0;
        /*if(!empty($orden['cur_compromiso']) && empty($orden['cur_devengado'])){
          $tipo = 1;
        }else if(!empty($orden['cur_compromiso']) && !empty($orden['cur_devengado'])){
          $tipo = 2;
        }*/

        $accion = '<div class="btn-group">';
        $accion .= '<span class="btn btn-sm btn-soft-info" onclick="ordenDetalleView('.$orden['id_pago'].')"><i class="fa fa-pencil-alt"></i></span>';
        /*if($presupuesto_au == true){
          $accion .= '<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto" href="documentos/php/front/orden/operar_orden.php?nro_orden=0&tipo='.$tipo.'&clase_proceso='.$orden['id_tipo_pago'].'&id_pago='.$orden['id_pago'].'"><i class="fa fa-user"></i></span>';
        }*/
        if(($compras_jefe == true || $compras_tecnico == true) && ($orden['id_bitacora'] == 1 || ($orden['id_bitacora'] == 3 && $orden['id_seguimiento'] == 13)) && $type == 0){
          $process = "'".$tipo_pago['tipo_pago']."'";
          $accion .= '<span class="btn btn-sm btn-soft-danger" onclick="anularOrdenCompra('.$orden['id_pago'].','.$process.')"><i class="fa fa-times-circle"></i></span>';
        }
        $accion .= '</div>';

        $bitacora = (!empty($orden['id_bitacora'])) ? $orden['id_bitacora'] : 0;

        if($orden['id_status'] == 3){
          $status = '<span class="text-danger"><i class="fa fa-times-circle"></i> Anulado</span>';
        }else{
          $status = '<div>';
          $status .= '<span class="text-'.$orden['bitacora_color'].'"><i class="fa fa-'.$orden['icono'].'"></i> '.$orden['bitacora_nombre'].'</span><br>';
          $status .= '<span class="text-'.$orden['seguimiento_color'].'"><i class="fa fa-'.$orden['seguimiento_icono'].'"></i> '.$orden['seguimiento_nombre'].'</span>';
          $status.= '</div>';
        }

        $facts = '';
        $facturas = array();
        $invoices = '';

        if(!empty($orden['facturas'])){
          $invoices .='<table class="table table-bordered" style="background-color:transparent">
            <thead>
              <th>Fecha</th>
              <th>Serie</th>
              <th>Número</th>
              <th>Monto</th>
            </thead>
            <tbody>
              ';
          $facts = explode("|", $orden['facturas']);
          foreach ($facts as $key => $f) {
            // code...
            $fact = explode(';', $f);
            $sub_f = array(
              'serie'=>$fact[0],
              'fecha'=>$fact[1],
              'numero'=>$fact[2],
              'monto'=>$fact[3],
              'nit'=>$fact[4],
              'proveedor'=>$fact[5],

            );

            $facturas[] = $sub_f;
            $invoices.='<tr><td>'.$fact[0].'</td><td>'.$fact[1].'</td><td>'.$fact[2].'</td><td>'.$fact[3].'</td></tr>';


          }
          $invoices.="
        </tbody>

      </table>";

        }


        $sub_array = array(
          'DT_RowId'=>$orden['id_pago'],
          'id_pago'=>$orden['id_pago'],
          'clase_proceso'=>$tipo_pago['tipo_pago'],//$proceso,
          'registro'=>$orden['nro_registro'],
          'estado'=>$status,
          'creador'=>$orden['creador'],
          /*'nro_orden'=>($orden['id_tipo_pago'] == 1) ? $orden['nro_registro'] : '',
          'comdev'=>($orden['id_tipo_pago'] == 2) ? $orden['nro_registro'] : '',
          'cyd'=>($orden['id_tipo_pago'] == 3) ? $orden['nro_registro'] : '',*/
          'proveedor'=>'<div><span style="width:10px">'.$orden['proveedor'].'</span></div>',
          'cur_c'=>(!empty($orden['cur_compromiso'])) ? $orden['cur_compromiso'] : '',
          'nro_liquidacion'=>(!empty($orden['nro_liquidacion'])) ? $orden['nro_liquidacion'] : '',
          'cur_d'=>(!empty($orden['cur_devengado'])) ? $orden['cur_devengado'] : '',
          'total'=>$orden['total'],
          'id_bitacora'=>$orden['id_bitacora'],
          'id_seguimiento'=>$orden['id_seguimiento'],

          'id_clase_proceso'=>$orden['id_tipo_pago'],
          'tipo'=>$tipo,

          'presupuesto'=>$presupuesto,
          'subdirector'=>$subdirectorfinanciero,
          'directorf_au'=>$directorf_au,
          'compras_tecnico'=>$compras_tecnico,
          'presupuesto_au'=>$presupuesto_au,
          'facturas'=>$invoices,
          'accion'=>$accion

        );
        $data[] = $sub_array;
      //}

    }
    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData"=>$data
    );

    echo json_encode($results);
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
?>
