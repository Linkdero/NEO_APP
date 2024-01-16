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

    $id_persona=$_SESSION['id_persona'];
    $tipo = $_POST['tipo'];
    $year = $_POST['year'];
    $tipo_pago = (!empty($_POST['id_tipo_pago'])) ? $_POST['id_tipo_pago'] : 0;
    //$privilegio = $_POST['privilegio'];

    $clased = new documento;
    $facturas = array();
    $data = array();
    //$privilegio = evaluar_flags_by_sistema($_SESSION['id_persona'],8017);

    //$tecnico = ($privilegio[9]['flag_actualizar'] == 1) ? $id_persona : 0;
    $privilegio = retornaPrivilegios();


    $compras_jefe = $privilegio['compras_au'];//(evaluar_flag($_SESSION['id_persona'],8017,305,'flag_autoriza')==1 && evaluar_flag($_SESSION['id_persona'],8017,302,'flag_autoriza')==1) ? true : false;
    $compras_tecnico = ($privilegio['compras_tecnico'] == true) ? $_SESSION['id_persona'] : 0;//(evaluar_flag($_SESSION['id_persona'],8017,302,'flag_actualizar')==1) ? true : false;
    $compras_tecnico_anulacion = $privilegio['compras_tecnico_anulacion'];
    $presupuesto_au = $privilegio['presupuesto_au']; //(evaluar_flag($_SESSION['id_persona'],8017,323,'flag_autoriza')==1) ? true : false;
    $presupuesto = $privilegio['presupuesto'];//(evaluar_flag($_SESSION['id_persona'],8017,323,'flag_es_menu')==1) ? true : false;
    $subdirectorfinanciero = $privilegio['subdirectorfinanciero'];
    $directorf_au = $privilegio['directorf_au'];
    $facturas = $privilegio['facturas'];
    $tesoreria = $privilegio['tesoreria'];

    $cheque = ($tesoreria == true) ? 1 : $tipo_pago;
    //echo $compras_tecnico;

    $compras_t = ($compras_jefe == true) ? 0 : $compras_tecnico;
    if($facturas == true){
      $facturas = $clased->get_facturas($tipo,$compras_t,$cheque,$year);
    }

    if($tipo == 0){
      $fecha_i=strtotime(date('Y-m-d'));
      foreach($facturas as $f){

        $type = 'No definido';
        if($f['tipo_pago']==1){
          $type = 'Baja Cuantía';
        }else if($f['tipo_pago']==2){
          $type = 'Acreditamiento';
        }else if($f['tipo_pago']==3){
          $type = 'Servicio';
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
          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
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
          if(date('Y-m-d',$fecha1) == '2022-09-15' || date('Y-m-d',$fecha1) == '2022-09-16'){
            $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
          }
          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
          if((strcmp(date('D',$fecha1),'Sun')!=0) && (strcmp(date('D',$fecha1),'Sat')!=0)){
            $inc++;
            $f_final=date('Y-m-d',$fecha1);
          }
        }while($inc<$total_days); //validación para mostrar la fecha última para publicar

  //echo $f_final;
        //if(date('Y-m-d')<=$f_final){
        $fecha_ini = date('Y-m-d',$fecha_i);
          do{
            if(date('Y-m-d',$fecha_i) == '2022-09-15' || date('Y-m-d',$fecha_i) == '2022-09-16'){
              //$fecha_i=strtotime('+1 day ' . date('Y-m-d',$fecha_i));
            }
            $fecha_i=strtotime('+1 day ' . date('Y-m-d',$fecha_i));
            if((strcmp(date('D',$fecha_i),'Sun')!=0) && (strcmp(date('D',$fecha_i),'Sat')!=0)){
              $i++;
              $f_actual=date('Y-m-d',$fecha_i);

            }

          }while($f_actual<=$f_final); // validación para calcular la cantidad de días
          $i -=1;
        //}

        if(date('Y-m-d') > $f_final){
          $i = 0;
        }else {
          //$i =99;
        }
        $d= ($i==1) ? 'día' : 'días';

        $class='badge badge-soft-info';
        $class2 = 'text-success';
        if($i <= 2){
          $class="badge badge-soft-danger";
          $class2 = 'text-danger';
        }
        $diferencia = $i.' '.$d;
        $clase_proceso = (!empty($f['clase_proceso'])) ? $clased->retornaTipoOrden($f['clase_proceso']) : '';

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
          if($compras_tecnico_anulacion == true && $cheque == 0 && $f['modalidad_pago'] == 0) {
            $publicacion.='<span class="btn btn-sm btn-soft-danger" onclick="anularFactura('.$f['orden_compra_id'].')"><i class="fa fa-times-circle"></i></span>';
          }
          if($cheque == 1) {
            $publicacion.='<span class="btn btn-sm btn-soft-danger" onclick="anularFactura('.$f['orden_compra_id'].')"><i class="fa fa-times-circle"></i></span>';
          }

          $publicacion.='
          </div><br>';
          $publicacion.=' Último dia para publicar: <br><strong>'.fecha_dmy($f_final).'</strong> ';//'<br> Le quedan: <span class="'.$class.'">'.$diferencia.'</span> para publicar<br>';
          if($f['id_tipo_ingreso'] == 1 || $f['id_tipo_ingreso'] == 2){
            //inicio
            if($f['factura_publicada'] == 0 && empty($f['factura_num']) && empty($f['factura_serie'])){
              $publicacion.='<h3 class="text-danger"> <strong>Cotización ingresada</strong>, tiene que llenar los datos de la factura para publicar.</h3>';
            }else if($cheque == 1 && !empty($f['factura_num'] )){//&& !empty($f['factura_serie'])){
              $publicacion.=/*($diferencia > 0) ?*/'<span class="btn btn-sm btn-info" onclick="publicarFactura('.$f['orden_compra_id'].','.$nog.')"><i class="fa fa-check-circle"></i> Publicar</span>';// : '';
            } if($cheque == 0 && !empty($f['factura_num']) && !empty($f['factura_serie'])){
              $publicacion.=/*($diferencia > 0) ?*/'<span class="btn btn-sm btn-info" onclick="publicarFactura('.$f['orden_compra_id'].','.$nog.')"><i class="fa fa-check-circle"></i> Publicar</span>';// : '';
            }
            //fin
          }


          $publicacion.='</div>';
        }

        if(!empty($f['id_status']) && !empty($f['factura_num']) && !empty($f['factura_serie'])){
          $publicacion .= $f['estado_factura'];
        }

        /*if($cheque == 1 && !empty($f['factura_num']) && !empty($f['factura_serie'])){
          $publicacion.='<span class="btn btn-sm btn-info" onclick="publicarFactura('.$f['orden_compra_id'].','.$nog.')"><i class="fa fa-check-circle"></i> Publicar</span>';
        }*/


        $chk = 0;

        /*if(empty($f['factura_num']) && empty($f['factura_serie'])){
          $chk = 1;
        }*/
        if(($f['factura_publicada'] == 1 && empty($f['nro_orden']))){
          $chk = 1;
        }else if($f['factura_publicada'] == 1 && !empty($f['nro_orden'])){ //|| $f['factura_publicada'] == 0){
          $chk = 2;
        }

        if($f['factura_publicada'] == 1 && $f['modalidad_pago'] == 1){
          $chk = 2;
        }

        if(empty($f['factura_num']) && $cheque == 1 && empty($f['cheque'])){
          $chk = 1;
        }else if(empty($f['factura_num']) && $cheque == 0){
          $chk = 2;
        }

        if(!empty($f['cheque'])){
          $chk = 2;
        }



        $pay = $clased->retornaTipoPago($f['modalidad_pago']);//(!empty($f['modalidad_pago'])) ? ' - '.$clased->retornaTipoPago($f['modalidad_pago']) : NULL;
        //$pay = ($f['modalidad_pago'] == 1) ? ' - Cheque' : ' - Transferencia';

        $buttonCheque = ($cheque == 1 && $f['id_tipo_ingreso'] == 2) ? '<span class="btn btn-sm btn-soft-info" onclick="agregarDatosFactura(2,'.$f['orden_compra_id'].',0)"><i class="fa fa-file-invoice"></i> Ingresar datos<br> de la factura</span>' : 'No tiene privilegios para agregar los datos de la factura';
        $tIngreso = $clased->retornaTipoIngreso($f['id_tipo_ingreso']);
        $buttonCheque = (($f['id_tipo_ingreso'] == 3 || $f['id_tipo_ingreso'] == 4) && $cheque == 1) ? $tIngreso['id_tipo_ingreso'] : $buttonCheque;
        $sub_array = array(
          'DT_RowId'=>$f['orden_compra_id'],
          'orden_id'=>$f['orden_compra_id'],
          'nro_orden'=>($f['clase_proceso'] == 1) ? $f['nro_orden'] : ' ',
          'tipo_pago'=>$type.$pay['id_tipo_pago'],
          'fecha_recepcion'=>date('d-m-Y', strtotime($f['asignado_en'])).'<br>'.date('H:i:s', strtotime($f['asignado_en'])),
          'factura_fecha'=>fecha_dmy($f['factura_fecha']),
          'factura_serie'=>(!empty($f['factura_serie'])) ? $f['factura_serie'] : $buttonCheque,
          'factura_num'=>$f['factura_num'],
          'proveedor'=>wordwrap($f['Prov_nom'], 20, '<br />'),
          'factura_total'=>$f['factura_total'],
          'ped_numero'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : NULL,
          'ped_num'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : '<span> Falta PYR</span>',
          'nog'=>$f['nog'],//.' || '.$f['id_tipo_ingreso'],
          'npg'=>$f['npg'],
          'cur_c'=>$f['cur'],
          'cur_d'=>$f['cur_devengado'],
          'clase_proceso'=>$clase_proceso,
          'cyd'=>($f['clase_proceso'] == 2) ? $f['nro_orden'] : ' ',
          'comdev'=>($f['clase_proceso'] == 3) ? $f['nro_orden'] : '  ',
          //'cheque'=>($f['modalidad_pago'] == 1 && empty($f['cheque']) && $cheque == 1 && $f['id_tipo_ingreso'] == 2) ? '<span class="btn btn-sm btn-info" onclick="agregarCheque('.$f['orden_compra_id'].')"><i class="fa fa-plus-circle"></i> Agregar cheque</span>' : $f['cheque'],//$f['modalidad_pago'].' || '.$f['cheque'],
          'cheque'=>($f['modalidad_pago'] == 1 && empty($f['cheque']) && $cheque == 1 && $f['id_tipo_ingreso'] == 2) ? 'Falta agregar el cheque' : $f['cheque'],//$f['modalidad_pago'].' || '.$f['cheque'],
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
          'class'=>($i <= 2) ? 'yellow' : '',
          'dias'=>'<div style="width:100px"><strong><h2 class="'.$class2.'">'.$i.'</h2></strong></div>',//. $fecha_ini .' | | '.$f_final
          'tipo_ingreso'=>$clased->retornaTipoIngreso($f['id_tipo_ingreso'])
        );
        $data[] = $sub_array;
      }
      createLog(318, 8017,  'docto_ped_pago','Visualizando facturas pendientes de publicar','', '');
    }else{
      foreach($facturas as $f){

        $type = 'No definido';
        if($f['tipo_pago']==1){
          $type = 'Baja Cuantía';
        }else if($f['tipo_pago']==2){
          $type = 'Acreditamiento';
        }
        $estado = '';

        if($f['status']==5){
          $estado = 'Anulada';
        }

        $clase_proceso = (!empty($f['clase_proceso'])) ? $clased->retornaTipoOrden($f['clase_proceso']) : '';

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

        if($f['factura_publicada'] == 1 && $f['modalidad_pago'] == 1){
          $chk = 2;
        }

        if($tipo == 4 && !empty($f['nro_orden'])){
          $chk = 1;
        }

        if($f['factura_publicada'] == 1 && empty($f['cheque'])){
          $chk = 1;
        }


        $pay = $clased->retornaTipoPago($f['modalidad_pago']);

        $tIngreso = $clased->retornaTipoIngreso($f['id_tipo_ingreso']);
        $serieF = (($f['id_tipo_ingreso'] == 3 || $f['id_tipo_ingreso'] == 4)) ? $tIngreso['id_tipo_ingreso'] : $f['factura_serie'];
        $sub_array = array(
          'DT_RowId'=>$f['orden_compra_id'],
          'orden_id'=>$f['orden_compra_id'],
          'nro_orden'=>($f['clase_proceso'] == 1) ? $f['nro_orden'] : ' ',
          'tipo_pago'=>$type.$pay['id_tipo_pago'],
          'fecha_recepcion'=>date('d-m-Y', strtotime($f['asignado_en'])).'<br>'.date('H:i:s', strtotime($f['asignado_en'])),
          'factura_fecha'=>fecha_dmy($f['factura_fecha']),
          'factura_serie'=>$serieF,//$f['factura_serie'],
          'factura_num'=>$f['factura_num'],
          'proveedor'=>wordwrap($f['Prov_nom'], 20, '<br />'),
          'factura_total'=>$f['factura_total'],
          'ped_numero'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : NULL,
          'ped_num'=>(!empty($f['Ped_num'])) ? $f['Ped_num'] : '<span> Falta PYR</span>',
          'nog'=>$f['nog'],
          'npg'=>$f['npg'],
          'cur_c'=>(!empty($f['cur_compromiso_pre'])) ? $f['cur_compromiso_pre'] : '',//$f['cur'],
          'cur_d'=>(!empty($f['cur_devengado_pre'])) ? $f['cur_devengado_pre'] : '',//$f['cur_devengado'],
          'clase_proceso'=>(!empty($clase_proceso['tipo_pago'])) ? $clase_proceso['tipo_pago'] : '',
          'cyd'=>($f['clase_proceso'] == 2 || $f['clase_proceso'] == 3) ? $f['nro_orden'] : $f['nro_orden'],
          'comdev'=>($f['clase_proceso'] == 3) ? $f['nro_orden'] : '  ',
          'cheque'=>($f['modalidad_pago'] == 1 && empty($f['cheque']) && $cheque == 1) ? '<span class="btn btn-sm btn-info" onclick="agregarCheque('.$f['orden_compra_id'].')"><i class="fa fa-plus-circle"></i> Agregar cheque</span>' : $f['cheque'],//$f['modalidad_pago'].' || '.$f['cheque'],
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
          'dias'=>'',
          'tipo_ingreso'=>$clased->retornaTipoIngreso($f['id_tipo_ingreso'])
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
