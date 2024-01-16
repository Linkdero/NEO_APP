<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';



    $id_persona=$_SESSION['id_persona'];
    $tipo = $_POST['tipo'];

    $clased = new documento;
    $facturas = array();
    $data = array();

    if($u->hasPrivilege(302) || $u2->accesoModulo(7851)){
      $facturas = $clased->get_facturas($tipo);
    }



$fecha_i=strtotime(date('Y-m-d'));
    foreach($facturas as $f){

      $tipo = 'No definido';
      if($f['tipo_pago']==1){
        $tipo = 'Baja Cuantía';
      }else if($f['tipo_pago']==2){
        $tipo = 'Acreditamiento';
      }
      $estado = '';

      if($f['status']==5){
        $estado = 'Anulada';
      }
      $inc = 1;
      $f_final='';
      $fecha_f='';
      $f_actual='';

      $fecha1=strtotime($f['factura_fecha']);
      $fecha_i = strtotime(date('Y-m-d'));

      do{
        $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
        if((strcmp(date('D',$fecha1),'Sun')!=0) && (strcmp(date('D',$fecha1),'Sat')!=0)){
          $inc++;
          $f_final=date('Y-m-d',$fecha1);
        }

      }while($inc<4);

      $i=0;
      if(date('Y-m-d')<$f_final){
        do{
          $fecha_i=strtotime('+1 day ' . date('Y-m-d',$fecha_i));
          if((strcmp(date('D',$fecha_i),'Sun')!=0) && (strcmp(date('D',$fecha_i),'Sat')!=0)){
            $i++;
            $f_actual=date('Y-m-d',$fecha_i);

          }

        }while($f_actual<$f_final);
      }

      $d='días';

      if($i==1){
        $d='día';
      }
      $class='badge badge-soft-info';
      if($i < 5){

        $class="badge badge-soft-danger";
      }
      $diferencia = $i.' '.$d;
      $clase_proceso = '';
      if($f['clase_proceso'] == 1){
        $clase_proceso = 'Orden de compra';
      }else if($f['clase_proceso'] == 2){
        $clase_proceso = 'CYD';
      }else if($f['clase_proceso'] == 3){
        $clase_proceso = 'COM-DEV';
      }

      $publicacion = '';
      if(!empty($f['factura_publicada'])){
        $publicacion = '<div style="width:150px">Factura publicada</div>';
      }else{
        $publicacion='<div style="width:150px">Publicar antes del: <br><strong>'. fecha_dmy($f_final).'</strong> <br> Le quedan: <br><span class="badge badge-soft-danger">'.$diferencia.'</span> para publicar</div>';
      }


      $sub_array = array(
        'DT_RowId'=>$f['orden_compra_id'],
        'orden_id'=>$f['orden_compra_id'],
        'nro_orden'=>($f['clase_proceso'] == 1) ? $f['nro_orden'] : ' ',
        'tipo_pago'=>$tipo,
        'fecha_recepcion'=>date('d-m-Y', strtotime($f['asignado_en'])).'<br>'.date('H:i:s', strtotime($f['asignado_en'])),
        'factura_fecha'=>fecha_dmy($f['factura_fecha']),
        'factura_serie'=>$f['factura_serie'],
        'factura_num'=>$f['factura_num'],
        'proveedor'=>wordwrap($f['Prov_nom'], 20, '<br />'),
        'factura_total'=>$f['factura_total'],
        'ped_num'=>$f['Ped_num'],
        'nog'=>$f['nog'],
        'cur_c'=>$f['cur'],
        'cur_d'=>$f['cur_devengado'],
        'clase_proceso'=>$clase_proceso,
        'cyd'=>($f['clase_proceso'] == 2) ? $f['nro_orden'] : ' ',
        'comdev'=>($f['clase_proceso'] == 3) ? $f['nro_orden'] : '  ',
        'cheque'=>$f['cheque'],
        'estado'=>$estado,
        'asignado'=>($estado == 'Anulada')?$estado:$f['empleado'],
        'factura'=>$f['factura_serie'].'<br>'.$f['factura_num'].'<br>'.$f['factura_total'],
        'acreditamiento'=>$f['cur'].'<br>'.$f['cur_devengado'],
        'diferencia'=>$publicacion,
        'factura_publicada'=>$f['factura_publicada'],
        'accion'=>'<span class="btn btn-sm btn-soft-info"><i class="fa fa-pen"></i></span>',
        'tipo'=>(!empty($f['tipo_pago'])) ? $f['tipo_pago'] : NULL
      );
      $data[] = $sub_array;
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
