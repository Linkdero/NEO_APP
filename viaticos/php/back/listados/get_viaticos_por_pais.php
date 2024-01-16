<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  date_default_timezone_set('America/Guatemala');
  include_once '../../../../empleados/php/back/functions.php';
  include_once '../functions.php';

  $tipo = $_POST['tipo'];
  $mes = $_POST['mes'];
  $year = $_POST['year'];

  $viaticos = array();
  $clase = new empleado;
  $clasev = new viaticos;
  $e = $clase->get_empleado_by_id_ficha($_SESSION['id_persona']);

  $direccion = 0;
  if (usuarioPrivilegiado_acceso()->accesoModulo(7851) || usuarioPrivilegiado()->hasPrivilege(4) || usuarioPrivilegiado_acceso()->accesoModulo(8085)) {
    $viaticos = $clasev->get_all_viaticos_by_pais($direccion, $tipo, $mes, $year);
  } else {
    $viaticos = $clasev->get_all_viaticos_by_pais($e['id_dirf'], $tipo, $mes, $year);
  }



  $data = array();
  $monto = 0;
  $x = 1;
  $suma = 0;
  $incremental = 0;
  $monto_global = 0;
  $valor = 0;
  $conteo = count($viaticos);
  $string = '';
  $monto_ant = 0;
  $monto_act = 0;
  $y = 0;
  $validacion = '';
  foreach ($viaticos as $key => $s) {
    $x = $key;



    if ($y < $key) {
      $y = $key - 1;
    } else {
      $y = 0;
    }
    $id_actual = $viaticos[$key]['id_empleado']; //$s[$key+1]['id_empleado'];
    $id_anterior = $viaticos[$y]['id_empleado'];

    $monto_ant = $viaticos[$y]['gastos'];
    $monto_act = $viaticos[$key]['gastos'];
    //$monto_global=$viaticos[$key]['valor_mayor'];

    //$sumar=$s['gastos'];
    //$incremental+=1;
    if ($id_anterior == $id_actual) {
      $incremental += 1;
      $suma += $s['gastos'];
    } else {
      $incremental = 1;
      $suma = $s['gastos'];
    }

    if ($incremental == $viaticos[$key]['valor_mayor']) {
      $validacion = 't';
    } else {
      $validacion = 'f';
    }
    $monto_global = $suma;
    //$string=$monto_ant.' -- '.$monto_act.' -- '.$incremental.' -- '.$s['valor_mayor'].' -- '.$validacion.' -- '.$suma;

    //$valor2= next($viaticos[1]);

    /*if($valor==$s['id_empleado']){
        $monto+= $s['gastos'];
      }else{
        $monto=0;
      }*/

    $estado = '';
    $dep = '';
    $muni = '';
    $lugar = $s['departamento'];
    $lugar2 = $s['municipio'];
    if ($s['descripcion_lugar'] == 1) {
      $valor = $clasev->get_historial_viatico($s['vt_nombramiento']);
      //$historial = $valor['val_anterior'];
      $va = json_decode($valor['val_nuevo'], true);
      $keys = array_keys($va);

      $deptos = $clasev->get_departamentos($s['id_pais']);
      $munis = $clasev->get_municipios($va['id_departamento']);
      /*$aldeas=$clasev->get_aldeas($va['id_municipio']);*/

      foreach ($deptos["data"] as $d) {
        $dep .= ($va['id_departamento'] == $d['id_departamento']) ? $d['nombre'] : '';
      }
      foreach ($munis["data"] as $m) {
        $muni .= ($va['id_municipio'] == $m['id_municipio']) ? '' . $m['nombre'] : '';
      }
      /*foreach($aldeas["data"] as $a){
      		$dep.=($va['id_aldea']==$a['id_aldea'])?', '.$a['nombre']:'';
      	}*/
      $lugar = '<span class="text-info">' . $dep . '</span>';
      $lugar2 = '<span class="text-info">' . $muni . '</span>';
    }

    if ($s['descripcion_lugar'] == 2) {
      $valores = $clasev->get_historial_viatico_destinos($s['vt_nombramiento']);
      //$historial = $valor['val_anterior'];
      $x = 0;
      $total_destinos = count($valores);
      foreach ($valores as $valor) {
        $x++;

        $dep .= ' ' . $x . '.- ';
        $muni .= ' ' . $x . '.- ';
        $dep .= $valor['depto'];
        $muni .= $valor['muni'];

        $lugar = '<span class="text-info">' . $dep . '</span>';
        $lugar2 = '<span class="text-info">' . $muni . '</span>';
      }
    }

    $sub_array = array(
      'id_empleado' => $s['id_empleado'],
      'nombramiento' => $s['vt_nombramiento'],
      'empleado' => $s['nombre'],
      'direccion' => $s['direccion'],
      'fecha_salida' => fecha_dmy($s['fecha_salida']),
      'fecha_regreso' => fecha_dmy($s['fecha_regreso']),
      'pais' => $s['pais'],
      'departamento' => $lugar,
      'municipio' => $lugar2,
      'total_real' => number_format($s['gastos'], 2, ".", ""),
      'total_mes' => ($validacion == 't') ? number_format($monto_global, 2, ".", "") : '',
      'id_pais' => ($s['id_pais'] == 'GT') ? 'Nacional' : 'Internacional',
      /*number_format($incremental, 2, ".", ",").' - '.$monto_global/*$valor. ' - - '.$valor2/*,
          'fecha' => fecha_dmy($s['fecha']),
          'direccion_solicitante' => $s['direccion'],
          'destino'=>$s['pais'].', '.$s['departamento'].', '.$s['municipio'],
          'motivo'=>$s['motivo'],
          'fecha_ini'=>fecha_dmy($s['fecha_salida']),
          'fecha_fin'=>fecha_dmy($s['fecha_regreso']),
          'estado' => $estado,
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

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData" => $data
  );

  echo json_encode($results);

else :
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
