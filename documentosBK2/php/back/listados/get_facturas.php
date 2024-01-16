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
    $privilegio = evaluar_flags_by_sistema($_SESSION['id_persona'],8017);

    $tecnico = ($privilegio[9]['flag_acceso'] == 1) ? $id_persona : 0;
    //echo $tecnico;

    if($u->hasPrivilege(302) || $u2->accesoModulo(7851) || $u->hasPrivilege(318)){
      $facturas = $clased->get_facturas($tipo,$tecnico);
    }

    if($tipo == 0){
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
        $inc = 0;
        $f_final='';
        $fecha_f='';
        $f_actual='';

        $fecha1=strtotime($f['factura_fecha']);

        $fecha_i = strtotime(date('Y-m-d'));

        $day = 0;
        $i=0;
        $total_days = (!empty($f['nog'])) ? 10 : 5;
        if(User::get_nombre_dia(date('Y-m-d', strtotime($f['factura_fecha']))) == 'Viernes'){
          //$fecha1=strtotime('+2 day ' . date('Y-m-d',$fecha1));
          //$i+=1;
        }else
        if(User::get_nombre_dia(date('Y-m-d', strtotime($f['factura_fecha']))) == 'Sabado'){
          $fecha1=strtotime('+2 day ' . date('Y-m-d',$fecha1));
        }else if(User::get_nombre_dia(date('Y-m-d', strtotime($f['factura_fecha']))) == 'Domingo'){
          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
        }
        if(date('Y-m-d',$fecha1) == '2022-05-02'){
          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
          //$i+=1;
        }else{
          $i+=0;
        }
        do{

          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
          if(date('Y-m-d',$fecha1) == '2022-09-15' || date('Y-m-d',$fecha1) == '2022-09-16'){
            $fecha1=strtotime('+2 day ' . date('Y-m-d',$fecha1));
          }
          if((strcmp(date('D',$fecha1),'Sun')!=0) && (strcmp(date('D',$fecha1),'Sat')!=0)){
            $inc++;
            $f_final=date('Y-m-d',$fecha1);
          }
        }while($inc<$total_days);

  //echo $f_final;
        //if(date('Y-m-d')<=$f_final){
        $fecha_ini = date('Y-m-d',$fecha_i);
          do{

            $fecha_i=strtotime('+1 day ' . date('Y-m-d',$fecha_i));
            if(date('Y-m-d',$fecha1) == '2022-09-15' || date('Y-m-d',$fecha1) == '2022-09-16'){
              $fecha1=strtotime('+2 day ' . date('Y-m-d',$fecha1));
            }
            if((strcmp(date('D',$fecha_i),'Sun')!=0) && (strcmp(date('D',$fecha_i),'Sat')!=0)){
              $i++;
              $f_actual=date('Y-m-d',$fecha_i);

            }

          }while($f_actual<$f_final);
        //}

        if(date('Y-m-d') > $f_final){
          $i = 0;
        }else {
          //$i =99;
        }

        $d='días';
        if($i==1){
          $d='día';
        }
        $class='badge badge-soft-info';
        $class2 = 'text-success';
        if($i <= 2){
          $class="badge badge-soft-danger";
          $class2 = 'text-danger';
        }
        $diferencia = $i.' '.$d;
        $clase_proceso = '';
        if($f['clase_proceso'] == 1){
          $clase_proceso = 'Orden de compra';
        }else if($f['clase_proceso'] == 2){
          $clase_proceso = 'COM-DEV';
        }else if($f['clase_proceso'] == 3){
          $clase_proceso = 'CYD';
        }

        $publicacion = '';
        if(!empty($f['factura_publicada']) && empty($f['tipo'])){
          $publicacion.='<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id='.$f['orden_compra_id']. '&ped_num=' .$f['Ped_num'].  '"><i class="fa fa-pencil-alt"></i></span><br><br>';
          $publicacion = '<div style="width:150px"> <i class="fa fa-check-circle text-info"></i> Factura publicada</div><br>
          <div style="width:150px"> <i class="fa fa-check-circle text-light"></i> Clase de Proceso</div>';
        }else if(!empty($f['factura_publicada']) && !empty($f['tipo'])){
          $publicacion = '<div style="width:150px"> <i class="fa fa-check-circle text-info"></i> Factura publicada</div>';
        }
        else{
          $nog = (!empty($f['nog'])) ? $f['nog'] : 0;
          $publicacion='<div style="width:150px">';

$publicacion.='<div class="btn-group"><span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id='.$f['orden_compra_id']. '&ped_num=' .$f['Ped_num'].  '"><i class="fa fa-pencil-alt"></i></span>';
if($privilegio[9]['flag_imprimir'] == 1) {
  $publicacion.='
  <span class="btn btn-sm btn-soft-danger" onclick="anularFactura('.$f['orden_compra_id'].')"><i class="fa fa-times-circle"></i></span>';
}

$publicacion.='
</div><br>';
          $publicacion.=' Publicar antes del: <br><strong>'.fecha_dmy($f_final).'</strong> ';//'<br> Le quedan: <span class="'.$class.'">'.$diferencia.'</span> para publicar<br>';

          $publicacion.=/*($diferencia > 0) ?*/'<span class="btn btn-sm btn-info" onclick="publicarFactura('.$f['orden_compra_id'].','.$nog.')"><i class="fa fa-check-circle"></i> Publicar</span>' /*: ''*/;
          $publicacion.='</div>';
        }

        if(!empty($f['id_status'])){
          $publicacion .= $f['estado_factura'];
        }

        $chk = 0;

        if($f['factura_publicada'] == 1 && empty($f['nro_orden'])){
          $chk = 1;
        }else if($f['factura_publicada'] == 1 && !empty($f['nro_orden'])){ //|| $f['factura_publicada'] == 0){
          $chk = 2;
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
          'ped_numero'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : NULL,
          'ped_num'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : '<span> Falta PYR</span>',
          'nog'=>$f['nog'],
          'npg'=>$f['npg'],
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
          'factura_publicada'=>$chk,
          'id_renglon'=>$f['id_renglon'],
          'direccion'=>$f['direccion'],
          'accion'=>'',
          'tipo'=>(!empty($f['tipo_pago'])) ? $f['tipo_pago'] : NULL,
          'days'=>$i,
          'class'=>($i <= 2) ? 'warning' : '',
          'dias'=>'<h2 class="'.$class2.'">'.$i.'</h2>'//. $fecha_ini .' | | '.$f_final
        );
        $data[] = $sub_array;
      }
      createLog(318, 8017,  'docto_ped_pago','Visualizando facturas pendientes de publicar','', '');
    }else{
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

        $clase_proceso = '';
        if($f['clase_proceso'] == 1){
          $clase_proceso = 'Orden de compra';
        }else if($f['clase_proceso'] == 2){
          $clase_proceso = 'COM-DEV';
        }else if($f['clase_proceso'] == 3){
          $clase_proceso = 'COM-DEV';
        }

        $publicacion = '';
        if(!empty($f['factura_publicada']) && empty($f['tipo'])){

          $publicacion = '<div style="width:150px"> <i class="fa fa-check-circle text-info"></i> Factura publicada</div><br>
          <div style="width:150px"> <i class="fa fa-check-circle text-light"></i> Clase de Proceso</div>';
        }else if(!empty($f['factura_publicada']) && !empty($f['tipo'])){

          $publicacion = '<div style="width:150px"> <i class="fa fa-check-circle text-info"></i> Factura publicada</div>';
        }

        if(!empty($f['id_status'])){
          $publicacion.='<span class="btn btn-sm btn-soft-info" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/factura/factura_detalle.php?orden_id='.$f['orden_compra_id']. '&ped_num=' .$f['Ped_num']. '"><i class="fa fa-pencil-alt"></i></span><br>';
          $publicacion .= $f['estado_factura'];
        }

        $chk = 0;

        if($f['factura_publicada'] == 1 && empty($f['nro_orden'])){
          $chk = 1;
        }else if($f['factura_publicada'] == 1 && !empty($f['nro_orden'])){ //|| $f['factura_publicada'] == 0){
          $chk = 2;
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
          'ped_numero'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : NULL,
          'ped_num'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : '<span> Falta PYR</span>',
          'nog'=>$f['nog'],
          'npg'=>$f['npg'],
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
          'factura_publicada'=>$chk,
          'id_renglon'=>$f['id_renglon'],
          'direccion'=>$f['direccion'],
          'accion'=>'',
          'tipo'=>(!empty($f['tipo_pago'])) ? $f['tipo_pago'] : NULL,
          'class'=>'',
          'dias'=>''
        );
        $data[] = $sub_array;
      }

      createLog(318, 8017, 'docto_ped_pago','Visualizando facturas publicadas','', '');
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
