<?php
include_once '../../../../inc/functions.php';
sec_session_start();
$u = usuarioPrivilegiado();
$u2 = usuarioPrivilegiado_acceso();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');

    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $id_direccion = 0;//(!empty($_POST['id_direccion'])) ? $_POST['id_direccion'] : 0;
    $id_persona=$_SESSION['id_persona'];

    $clased = new documento;
    $pac_list = array();
    $data = array();
    $depto = '';
    $dir = '';

    if ($u->hasPrivilege(302) && $u->hasPrivilege(325) || $u->hasPrivilege(301) && $u->hasPrivilege(325)) {
      $pac_list = $clased->get_pac_by_ejercicio($id_direccion);
    } else if( $u->hasPrivilege(325)) {
      $clase = new empleado;
      $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);
      if (!empty($e['id_dirf'])) {
        if ($e['id_subdireccion_funcional'] == 37) {
          $depto = 207; //subdireccion de mantenimiento y servicios generales
        } else {
          $depto = $e['id_dirf'];
        }
      } else {
        if (!empty($e['id_subsecre'])) {
          $depto = $e['id_subsecre'];
        } else {
          $depto = $e['id_secre'];
        }
      }

      $dir = $clased->devuelve_direccion_app_pos($depto);
      if ($dir == 6) {
        $dir = 207;
      }
      //echo $depto;
      $pac_list = $clased->get_pac_by_ejercicio($depto);
      //echo $dir;
    }


    /*if($u->hasPrivilege(302) || $u2->accesoModulo(7851)){
      $facturas = $clased->get_facturas();
    }*/




    foreach($pac_list as $p){
      $estado = '';
      if($p['id_status']==1){
        $estado = 'REGISTRADO';
      }else if($p['id_status']==3){
        $estado = 'AUTORIZADO';
      }else if($p['id_status']==4){
        $estado = 'ANULADO';
      }


      $grandtotal = ($p['mEnero']+$p['mFebrero']+$p['mMarzo']+$p['mAbril']+$p['mMayo']+$p['mJunio']+$p['mJulio']+$p['mAgosto']+$p['mSeptiembre']+$p['mOctubre']+$p['mNoviembre']+$p['mDiciembre']);
      $sub_array = array(
        'DT_RowId'=>$p['pac_id'],
        'pac_nombre'=>'<a class="" data-toggle="modal" data-target="#modal-remoto-lgg2" href="documentos/php/front/pac/pac_detalle.php?pac_id='.$p['pac_id'].'"><strong>'.$p['pac_nombre'].'</strong></a>',//$p['pac_nombre'],
        'pac_detalle'=>$p['pac_detalle'],
        'ejercicio_ant'=>(!empty($p['pac_ejercicio_ant'])) ? 'SI' : '--',
        'ejercicio_ant_desc'=>$p['pac_ejercicio_ant_desc'],
        'pac_renglon'=>$p['pac_renglon'],
        'c_ene'=>$p['cEnero'],
        'm_ene'=>$p['mEnero'],
        'c_feb'=>$p['cFebrero'],
        'm_feb'=>$p['mFebrero'],
        'c_mar'=>$p['cMarzo'],
        'm_mar'=>$p['mMarzo'],
        'c_abr'=>$p['cAbril'],
        'm_abr'=>$p['mAbril'],
        'c_may'=>$p['cMayo'],
        'm_may'=>$p['mMayo'],

        'c_jun'=>$p['cJunio'],
        'm_jun'=>$p['mJunio'],
        'c_jul'=>$p['cJulio'],
        'm_jul'=>$p['mJulio'],
        'c_ago'=>$p['cAgosto'],
        'm_ago'=>$p['mAgosto'],
        'c_sep'=>$p['cSeptiembre'],
        'm_sep'=>$p['mSeptiembre'],
        'c_oct'=>$p['cOctubre'],
        'm_oct'=>$p['mOctubre'],

        'c_nov'=>$p['cNoviembre'],
        'm_nov'=>$p['mNoviembre'],

        'c_dic'=>$p['cDiciembre'],
        'm_dic'=>$p['mDiciembre'],

        'total'=>$grandtotal,
        'border'=>'1px solid #addaff',
        'ubicacion' => $p['direccion'] . ' <br> ' . $p['departamento'],
        'estado'=>$estado
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
