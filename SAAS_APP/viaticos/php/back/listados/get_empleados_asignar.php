<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $empleados = array();
    /*$u=usuarioPrivilegiado_acceso();
    if (isset($u) && $u->accesoModulo(7851))*/

      /*if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){ // 1163 Módulo Empleados
        $empleados = viaticos::get_all_empleados_asignar(0);
      }else{*/
        $emp=get_empleado_by_session_direccion($_SESSION['id_persona']);
        $clase = new viaticos();
        $empleados = $clase->get_empleados_por_direccion($emp['id_dirf']);
      /*}

      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT id_rrhh_direccion FROM vt_nombramiento WHERE vt_nombramiento=?";*/

      /*$stmt = $pdo->prepare($sql);
      $stmt->execute(array($nombramiento));
      $direccion = $stmt->fetch();



    /*}else{
      $empleados = viaticos::get_all_empleados_asignar_by_direccion();
    }*/


    $data = array();
    foreach ($empleados as $e){

        $sub_array = array(
          'DT_RowId'=>$e['id_persona'],
          'id_persona'=>($clase->get_liquidacion_pendiente_por_empleado($e['id_persona']))?$e['id_persona'].'<span class="stado_danger" style="margin-left:0px"></span>':$e['id_persona'],
          //'foto'=>$e['id_persona'],
          'empleado'=>$e['nombre_completo'],
          'status' =>'Activo'/*$e['estado']/*,
          'destino'=>$s['pais'].', '.$s['departamento'].', '.$s['municipio'],
          'motivo'=>'',
          'fecha_ini'=>fecha_dmy($s['fecha_salida']),
          'fecha_fin'=>fecha_dmy($s['fecha_regreso']),
          'estado' => $s['estado']/*,
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
