<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';



    $id_persona=$_SESSION['id_persona'];
    $tipo=$_POST['tipo'];
    $clase = new empleado;
    $clase1 = new viaticos;
    $e = $clase->get_empleado_by_id_ficha($id_persona);
    set_time_limit(0);

    $solicitudes = array();
    $direccion=$e['id_dirf'];
    if($e['id_subdireccion_funcional']==34){
      $direccion=207;
    }
    if(usuarioPrivilegiado_acceso()->accesoModulo(7851) || usuarioPrivilegiado()->hasPrivilege(4)){
      $solicitudes = $clase1->get_all_solicitudes($tipo);
    }else{
      $solicitudes = $clase1->get_all_solicitudes_by_direccion($direccion,$tipo);
    }


    $data = array();
    if($tipo==2){
      foreach ($solicitudes as $s){
        $inc=1;
        $fecha_inicio=$s['fecha_regreso'];
        $fecha1=strtotime($s['fecha_regreso']);
        $f_final='';
        $fecha_i=strtotime(date('Y-m-d'));
        $fecha_f='';
        $f_actual='';



        do{
          $fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
          if((strcmp(date('D',$fecha1),'Sun')!=0) && (strcmp(date('D',$fecha1),'Sat')!=0)){
            $inc++;
            $f_final=date('Y-m-d',$fecha1);
          }

        }while($inc<6);

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


        //$intervalo = $fecha_i->diff($fecha_f);

        $d='días';

        if($i==1){
        	$d='día';
        }
        $class='badge badge-soft-info';
        if($i < 5){

          $class="badge badge-soft-danger";
        }
        $diferencia = $i.' '.$d;



        $estado='';
        $dep='';
        $lugar=$s['pais'].', <br>'.$s['departamento'].', <br>'.$s['municipio'];
        if($s['descripcion_lugar']==1){
          $valor = $clase1->get_historial_viatico($s['vt_nombramiento']);
        	//$historial = $valor['val_anterior'];
        	$va = json_decode($valor['val_nuevo'], true);
        	$keys = array_keys($va);

        	$deptos=$clase1->get_departamentos($s['id_pais']);
        	$munis=$clase1->get_municipios($va['id_departamento']);
        	//$aldeas=$clase1->get_aldeas($va['id_municipio']);

        	foreach($deptos["data"] as $d){
        		$dep.=($va['id_departamento']==$d['id_departamento'])?$d['nombre']:'';
        	}
        	foreach($munis["data"] as $m){
        		$dep.=($va['id_municipio']==$m['id_municipio'])?', <br>'.$m['nombre']:'';
        	}
        	/*foreach($aldeas["data"] as $a){
        		$dep.=($va['id_aldea']==$a['id_aldea'])?', '.$a['nombre']:'';
        	}*/
          $lugar='<strong>Cambio: </strong><span class="text-info">'.$s['pais'].='<br>'.$dep.'</span>';
        }

        if($s['descripcion_lugar']==2)
        {
        	$valores = $clase1->get_historial_viatico_destinos($s['vt_nombramiento']);
        	//$historial = $valor['val_anterior'];
        	$x=0;
        	$total_destinos=count($valores);
        	foreach($valores as $valor){
        		$x++;

        		$dep.=' '.$x.'.- ';

        		$dep.=$valor['depto'].'-'.$valor['muni'].'<br>';
        	}
          $lugar='<strong>Recorrido: </strong><br><span class="text-info">'.$s['pais'].='<br>'.$dep.'</span>';


        }

          $sub_array = array(
            'DT_RowId'=>$s['vt_nombramiento'],
            'nombramiento' => $s['vt_nombramiento'],
            'fecha' => fecha_dmy($s['fecha']),
            'direccion_solicitante' => $s['direccion'],
            'destino'=>$lugar,
            'motivo'=>$s['motivo'],
            'fecha_ini'=>fecha_dmy($s['fecha_salida']),
            'fecha_fin'=>fecha_dmy($s['fecha_regreso']),
            'estado' => $s['estado'].' <br> Liquidar antes del: '. fecha_dmy($f_final).' <br> Le quedan: <span class="'.$class.'">'.$diferencia.'</span> para liquidar',
            'tipo'=>($s['id_pais']=='GT')?'NACIONAL':'EXTERIOR',
            'progress'=>'',
            'personas'=>'',
            'accion'=>''

          );
          $data[] = $sub_array;


      }
    }else{
      foreach ($solicitudes as $s){

        $estado='';
        $dep='';
        $lugar=$s['pais'].', <br>'.$s['departamento'].', <br>'.$s['municipio'];
        if($s['descripcion_lugar']==1){
          $valor = $clase1->get_historial_viatico($s['vt_nombramiento']);
        	//$historial = $valor['val_anterior'];
        	$va = json_decode($valor['val_nuevo'], true);
        	$keys = array_keys($va);

        	$deptos=$clase1->get_departamentos($s['id_pais']);
        	$munis=$clase1->get_municipios($va['id_departamento']);
        	//$aldeas=$clase1->get_aldeas($va['id_municipio']);

        	foreach($deptos["data"] as $d){
        		$dep.=($va['id_departamento']==$d['id_departamento'])?$d['nombre']:'';
        	}
        	foreach($munis["data"] as $m){
        		$dep.=($va['id_municipio']==$m['id_municipio'])?', <br>'.$m['nombre']:'';
        	}
        	/*foreach($aldeas["data"] as $a){
        		$dep.=($va['id_aldea']==$a['id_aldea'])?', '.$a['nombre']:'';
        	}*/
          $lugar='<strong>Cambio: </strong><span class="text-info">'.$s['pais'].='<br>'.$dep.'</span>';
        }

        if($s['descripcion_lugar']==2)
        {
        	$valores = $clase1->get_historial_viatico_destinos($s['vt_nombramiento']);
        	//$historial = $valor['val_anterior'];
        	$x=0;
        	$total_destinos=count($valores);
        	foreach($valores as $valor){
        		$x++;

        		$dep.=' '.$x.'.- ';

        		$dep.=$valor['depto'].'-'.$valor['muni'].'<br>';
        	}
          $lugar='<strong>Recorrido: </strong><br><span class="text-info">'.$s['pais'].='<br>'.$dep.'</span>';


        }


        /*($s['id_status']==934 || $s['id_status']==1072 ||$s['id_status']==1635 ||$s['id_status']==1636 || $s['id_status']==1643 || $s['id_status']==6167   ){
          $estado.='<span class="badge badge-danger">'.$s['estado'].'</span>';
        }else
        if($s['id_status']==932)
        {
          $estado.='<span class="badge badge-warning">'.$s['estado'].'</span>';
        }else
        if($s['id_status']==933 || $s['id_status']==935 ||$s['id_status']==936 ||$s['id_status']==937 || $s['id_status']==938 || $s['id_status']==939 )
        {
          $estado.='<span class="badge badge-info">'.$s['estado'].'</span>';
        }else
        if($s['id_status']==940)
        {
          $estado.='<span class="badge badge-success">'.$s['estado'].'</span>';
        }*/


          $sub_array = array(
            'DT_RowId'=>$s['vt_nombramiento'],
            'nombramiento' => $s['vt_nombramiento'],
            'fecha' => fecha_dmy($s['fecha']),
            'direccion_solicitante' => $s['direccion'],
            'destino'=>$lugar,
            'motivo'=>$s['motivo'],
            'fecha_ini'=>fecha_dmy($s['fecha_salida']),
            'fecha_fin'=>fecha_dmy($s['fecha_regreso']),
            'estado' => ($s['estado'] == 'LIQUIDADO') ? '<span class="text-success"><i class="fa fa-check-circle"></i> '.$s['estado'].'</span>' : $s['estado'],
            'tipo'=>($s['id_pais']=='GT')?'NACIONAL':'EXTERIOR',
            'progress'=>'',
            'personas'=>'',
            'accion'=>''
            /*,
            'autoriza' => $visita['autoriza'],
            'fecha' => date_format(new DateTime($visita['fecha']), 'd-m-Y'),
            'entrada' => date_format(new DateTime($visita['hora_entra']), 'H:i:s'),
            'salida' => $salida,
            'puerta' => $visita['nombre_puerta'],
            'gafete' => $visita['no_gafete'],
            'img' => "<button type='button' onclick='drawImg(this.value);' value=".$url_1." class='btn btn-info'>Foto</button>",*/
          );
          $data[] = $sub_array;


      }
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
