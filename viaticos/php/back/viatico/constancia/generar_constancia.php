<?php
include_once '../../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :
  include_once '../../../../../empleados/php/back/functions.php';
  include_once '../../functions.php';
  date_default_timezone_set('America/Guatemala');
  $clase = new viaticos;

  $vt_nombramiento=$_POST['id_viatico'];
  $id_empleado=$_POST['id_persona'];
  $renglon=$_POST['id_renglon'];
  //$parametros=str_replace("'","()",$renglon);
  //$bln_confirma=$_POST['confirma'];
  $parametros = substr($renglon, 0, -1);

  $fecha_salida_saas=date('Y-m-d', strtotime($_POST['txtFechaSalida']));
  $hora_salida_saas=$_POST['cmbHoraSalida'];
  $fecha_llegada_lugar=(!empty($_POST['txtLlegadaLugar'])) ? date('Y-m-d', strtotime($_POST['txtLlegadaLugar'])) : NULL;
  $hora_llegada_lugar=(!empty($_POST['cmbHoraLlegadaLugar'])) ? $_POST['cmbHoraLlegadaLugar'] : NULL;

  $fecha_salida_lugar= (!empty($_POST['txtSalidaLugar'])) ? date('Y-m-d', strtotime($_POST['txtSalidaLugar'])) : NULL;
  $hora_salida_lugar= (!empty($_POST['cmbHoraSalidaLugar'])) ? $_POST['cmbHoraSalidaLugar'] : NULL;
  $fecha_regreso_saas= (!empty($_POST['txtFechaRegreso'])) ? date('Y-m-d', strtotime($_POST['txtFechaRegreso'])) : NULL;
  $hora_regreso_saas= (!empty($_POST['cmbHoraRegreso'])) ? $_POST['cmbHoraRegreso'] : NULL;

  $cmbHoraSalidaT = $_POST['cmbHoraSalidaT'];
  $cmbHoraRegresoT = $_POST['cmbHoraRegresoT'];

  $transporte_salida=(!empty($_POST['id_tipo_salida'])) ? $_POST['id_tipo_salida'] : NULL;
  $empresa_salida=(!empty($_POST['cmbEmpresaSalida'])) ? $_POST['cmbEmpresaSalida'] : NULL;
  $nro_vuelo_salida=(!empty($_POST['txtNumeroVSalida'])) ? $_POST['txtNumeroVSalida'] : NULL;
  $transporte_regreso=(!empty($_POST['id_tipo_regreso'])) ? $_POST['id_tipo_regreso'] : NULL;
  $empresa_regreso=(!empty($_POST['cmbEmpresaRegreso'])) ? $_POST['cmbEmpresaRegreso'] : NULL;
  $nro_vuelo_regreso=(!empty($_POST['txtNumeroVEntrada'])) ? $_POST['txtNumeroVEntrada'] : NULL;

  $confirmar_lugar = $_POST['confirmar_lugar'];

  $horaText1 = $clase->get_hora_by_id($hora_salida_saas);
  $horaText2 = $clase->get_hora_by_id($hora_regreso_saas);

  $pdo = Database::connect_sqlsrv();
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try{
    $pdo->beginTransaction();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $porcentaje = $clase->porcentaje($_POST['id_pais'],$fecha_salida_saas,$fecha_regreso_saas,$horaText1['descripcion_corta'],$horaText2['descripcion_corta']);
    if($transporte_salida==0){
      $sql55 = "SELECT TOP 1 porcentaje_proyectado FROM vt_nombramiento_detalle
            WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND bln_confirma=? GROUP BY vt_nombramiento, porcentaje_proyectado";

      $q55 = $pdo->prepare($sql55);
      $q55->execute(array(
        $vt_nombramiento,
        1
      ));

      $porcentajeActual = $q55->fetch();
      $num=explode(".",$porcentajeActual['porcentaje_proyectado']);
      $porcentajeNuevo= ($num['1'] == 0) ? $porcentaje - 0.5 : $porcentaje;
      $sql = "UPDATE vt_nombramiento_detalle
      SET
              fecha_salida=?,
              hora_salida=?,
              fecha_llegada_lugar=?,
              hora_llegada_lugar=?,
              fecha_salida_lugar=?,
              hora_salida_lugar=?,
              fecha_regreso=?,
              hora_regreso=?,

              bln_confirma=?,
              porcentaje_real=?
            WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND bln_confirma=?";

      $q = $pdo->prepare($sql);
      $q->execute(array(
        $fecha_salida_saas,
        $hora_salida_saas,
        $fecha_llegada_lugar,
        $hora_llegada_lugar,
        $fecha_salida_lugar,
        $hora_salida_lugar,
        $fecha_regreso_saas,
        $hora_regreso_saas,
        1,
        $porcentajeNuevo,
        $vt_nombramiento,
        1
      ));
    }else{
      $sql55 = "SELECT TOP 1 porcentaje_proyectado FROM vt_nombramiento_detalle
            WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND bln_confirma=? GROUP BY vt_nombramiento, porcentaje_proyectado";

      $q55 = $pdo->prepare($sql55);
      $q55->execute(array(
        $vt_nombramiento,
        1
      ));

      $porcentajeActual = $q55->fetch();
      $num=explode(".",$porcentajeActual['porcentaje_proyectado']);
      $porcentajeNuevo= ($num['1'] == 0) ? $porcentaje - 0.5 : $porcentaje;
      $sql = "UPDATE vt_nombramiento_detalle
            SET
              fecha_salida=?,
              hora_salida=?,
              fecha_llegada_lugar=?,
              hora_llegada_lugar=?,
              fecha_salida_lugar=?,
              hora_salida_lugar=?,
              fecha_regreso=?,
              hora_regreso=?,
              id_tipo_transporte_salida=?,
              id_emp_transporte_salida=?,
              nro_vuelo_salida=?,
              id_tipo_transporte_regreso=?,
              id_emp_transporte_regreso=?,
              nro_vuelo_regreso=?,
              bln_confirma=?,
              porcentaje_real=?
            WHERE vt_nombramiento=? AND reng_num IN ($parametros) AND bln_confirma=?";
      $q = $pdo->prepare($sql);
      $q->execute(array(
        $fecha_salida_saas,
        $hora_salida_saas,
        $fecha_llegada_lugar,
        $hora_llegada_lugar,
        $fecha_salida_lugar,
        $hora_salida_lugar,
        $fecha_regreso_saas,
        $hora_regreso_saas,

        $transporte_salida,
        $empresa_salida,
        $nro_vuelo_salida,
        $transporte_regreso,
        $empresa_regreso,
        $nro_vuelo_regreso,

        1,
        $porcentajeNuevo,
        $vt_nombramiento,
        1

      ));
    }

    // el estado 39 es el estado futuro
    $sql = "EXEC sp_sel_valida_exterior ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($vt_nombramiento));
    $pais = $q->fetch();

    $tipo_documento;
    if($pais['id_pais']<>'GT'){
      $tipo_documento=1016;
      //echo $tipo_nombramiento;
    }else{
      $tipo_documento=1015;
    }




    //explode function breaks an string into array
    $arr=explode(",",$parametros);

    //print_r($arr);
    if(viaticos::validar_fecha_forms_unificados($vt_nombramiento) == true && $_POST['id_pais'] == 'GT'){
  //forms unificados va, vc, vl

      foreach ($arr as $key => $value) {
        //echo $value;
        $sql2 = "UPDATE vt_nombramiento_detalle
                 SET nro_frm_vt_cons = vt_nombramiento_detalle.nro_frm_vt_ant
                 FROM vt_nombramiento
                 INNER JOIN vt_nombramiento_detalle
                 ON (vt_nombramiento.vt_nombramiento = vt_nombramiento_detalle.vt_nombramiento)
                 WHERE vt_nombramiento.vt_nombramiento=? AND vt_nombramiento_detalle.reng_num = ?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($vt_nombramiento,$value));
        echo $value;
      }

      $sql55 = "UPDATE vt_nombramiento SET id_status = ? WHERE vt_nombramiento = ?";
        $q55 = $pdo->prepare($sql55);
        $q55->execute(array(939,$vt_nombramiento));

    }else{
      //forms distinto
      foreach ($arr as $key => $value) {
        //echo $value;
        $sql2 = "EXEC procesa_formulario_liquidacion @correlativo=?, @tipo_documento=?, @renglon=?";
        $q2 = $pdo->prepare($sql2);
        $q2->execute(array($vt_nombramiento,$tipo_documento,$value));
        //echo $value;
        // code...

      }

    }

    $valor_anterior = array(
      'vt_nombramiento'=>'',
      'id_status'=>''
    );

    $valor_nuevo = array(
      'vt_nombramiento'=>$vt_nombramiento,
      'id_status'=>'Se generó constancia',
      'empleados'=>$parametros,
      'fecha'=>date('Y-m-d H:i:s')
    );

    $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";
    $sql = "INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
    $q = $pdo->prepare($sql);
    $q->execute(array());

/// inicio confirmar lugar

    if($confirmar_lugar == 1){
      $departamento=$_POST['departamento'];
      $municipio=$_POST['municipio'];
      $aldea=$_POST['aldea'];

      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql0="SELECT vt_nombramiento,id_departamento, id_municipio, id_aldea FROM vt_nombramiento WHERE vt_nombramiento=?";
      $q0 = $pdo->prepare($sql0);
      $q0->execute(array($vt_nombramiento));
      $a= $q0->fetch();

      $valor_anterior = array(
        'vt_nombramiento'=>$a['vt_nombramiento'],
        'id_departamento'=>$a['id_departamento'],
        'id_municipio'=>$a['id_municipio'],
        'id_aldea'=>$a['id_aldea']
      );

      $valor_nuevo = array(
        'vt_nombramiento'=>$vt_nombramiento,
        'id_departamento'=>$departamento,
        'id_municipio'=>$municipio,
        'id_aldea'=>$aldea
      );

      $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";

      /*$sql1="UPDATE vt_nombramiento SET id_departamento=?, id_municipio=?, id_aldea=?, descripcion_lugar=1 WHERE vt_nombramiento=?;
             INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;*/
      $sql1="UPDATE vt_nombramiento SET descripcion_lugar=? WHERE vt_nombramiento=?;
      INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
      $q1 = $pdo->prepare($sql1);
      $q1->execute(array(NULL,$vt_nombramiento));
    }else if($confirmar_lugar == 2){
      //inicio
      $destinos = (!empty($destinos)) ? $_POST['destinos'] : NULL;
        $log = "VALUES(5, 1121, 'vt_nombramiento', '".json_encode($valor_anterior)."' ,'".json_encode($valor_nuevo)."' , ".$_SESSION["id_persona"].", GETDATE(), 3)";

        $clase = new viaticos;
        //$id_1=$clase->get_id_by_descripcion($h_ini);
        //$id_2=$clase->get_id_by_descripcion($h_fin);
        /*$sql0="SELECT TOP 1 reng_num FROM vt_nombramiento_destino_persona WHERE vt_nombramiento=? AND  ORDER BY reng_num DESC";
        $q0 = $pdo->prepare($sql0);
        $q0->execute(array($vt_nombramiento));
        $reng = $q0->fetch();
        $reng_num=0;
        if(!empty($reng['reng_num'])){
          $reng_num=$reng['reng_num']+1;
        }else{
          $reng_num=1;
        }
        $sql1="UPDATE vt_nombramiento SET descripcion_lugar=? WHERE vt_nombramiento=? ;
        INSERT INTO tbl_log_crud (id_pantalla, id_sistema, nom_tabla, val_anterior, val_nuevo, usr_mod, fecha_mod, tipo_trans) ".$log;
        $q1 = $pdo->prepare($sql1);
        $q1->execute(array(3,$vt_nombramiento));*/




        $personas=explode(',',$_POST['id_persona']);

        $destinos = $_POST['destinos'];
        $personas=explode(',',$_POST['id_persona']);

        $totalDestinos = count($destinos);
        $ultimo = $totalDestinos - 1;

        foreach ($personas AS $value) {


          if(is_numeric($value)){
            $sql1="UPDATE vt_nombramiento_detalle SET destino=? WHERE vt_nombramiento=? AND id_empleado = ?";
            $q1 = $pdo->prepare($sql1);
            $q1->execute(array(3,$vt_nombramiento,$value));

            $x = 0;
            foreach ($destinos as $key => $d) {
              $x ++;
              if($key == 0){
                $sql1="UPDATE vt_nombramiento_detalle SET fecha_llegada_lugar = ?, hora_llegada_lugar = ?
                WHERE vt_nombramiento = ? AND id_empleado = ?";
                $q1 = $pdo->prepare($sql1);
                $q1->execute(array(date('Y-m-d', strtotime($d['f_ini'])),$d['h_ini'],$vt_nombramiento,$value));
              }
              if($ultimo == $key){
                $sql1="UPDATE vt_nombramiento_detalle SET fecha_salida_lugar = ?, hora_salida_lugar = ?
                WHERE vt_nombramiento=? AND id_empleado = ?";
                $q1 = $pdo->prepare($sql1);
                $q1->execute(array(date('Y-m-d', strtotime($d['f_fin'])),$d['h_fin'],$vt_nombramiento,$value));
              }
              $valor_anterior=array(
                'vt_nombramiento'=>$vt_nombramiento
              );
              $aldeaa = (!empty($d['aldea'])) ? $d['aldea'] : NULL;
              $sql2="INSERT INTO vt_nombramiento_destino_persona (vt_nombramiento, id_persona, reng_num, bln_confirma, id_pais, id_departamento, id_municipio, id_aldea, fecha_ini,
                fecha_fin, hora_ini, hora_fin, asignado_por, fecha_ingreso,tipo_registro)
              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
              $q2 = $pdo->prepare($sql2);
              $q2->execute(array($vt_nombramiento,$value,$x,1,$d['pais_id'],$d['departamento'],$d['municipio'],$aldeaa,
              date('Y-m-d', strtotime($d['f_ini'])),date('Y-m-d', strtotime($d['f_fin'])),$d['h_ini'],$d['h_fin'],
              $_SESSION['id_persona'],date('Y-m-d H:i:s'),2));
            }
          }
        }
      }






  //fin


echo 'OK';
$pdo->commit();
}catch (PDOException $e){
echo $e;
try{ $pdo->rollBack();}catch(Exception $e2){
  echo $e2;
}
}

/*



*/


  Database::disconnect_sqlsrv();


else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;

?>
