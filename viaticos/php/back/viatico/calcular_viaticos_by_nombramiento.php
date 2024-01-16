<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';


$response = array();
$id_solicitud='';
if(isset($_GET['vt_nombramiento'])){
	$id_solicitud=$_GET['vt_nombramiento'];
}
$clase = new viaticos;
$empleados = $clase->get_datos_para_calculo($id_solicitud);
$viatico = $clase->get_solicitud_by_id($id_solicitud);

foreach ($empleados as $e){
	if($clase->get_bln_confirma($id_solicitud,$e['reng_num'])==1){
		$correlativo=$e["vt_nombramiento"];
	  $dias=$e["dias"];
	  $hs1=$e["hora_salida_e"];
	  $hr1=$e["hora_regreso_e"];
	  $hsp=$e["bln_hospedaje"];
	  $alm=$e["bln_alimentacion"];
	  $ext=$e["id_pais"];
	  $tipo=$e["id_tipo_nombramiento"];
	  $dia_inicio=$e["dia_inicio"];

	  if ($ext=="GT")  //porcentaje locales
	  {
	    $pdo = Database::connect_sqlsrv();
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = "EXEC sp_sel_devuelve_cuota_local ?";

	    $stmt = $pdo->prepare($sql);
	    $stmt->execute(array($e['sueldo']));
	    $quote = $stmt->fetch();
	    Database::disconnect_sqlsrv();


	      $cuota= $quote["monto"];
	      $desayuno= $quote["porcentaje_1"];
	      $almuerzo= $quote["porcentaje_2"];
	      $cena    = $quote["porcentaje_3"];
	      $hospedaje=$quote["porcentaje_4"];
	      $moneda="Q.";

	  }
	  else   // valores para viatico fuera del pais
	  {
	    $pdo = Database::connect_sqlsrv();
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $sql = "EXEC sp_sel_devuelve_cuota_exter ?, ?";

	    $stmt = $pdo->prepare($sql);
	    $stmt->execute(array($e['id_grupo'],$e['id_categoria']));
	    $quote = $stmt->fetch();
	    Database::disconnect_sqlsrv();


	      $cuota=$quote["monto"];
	      $desayuno=15;
	      $almuerzo=20;
	      $cena=15;
	      $hospedaje=50;
	      $moneda="USD$";

	  }
	  $porcentaje_entregar=$e["porcentaje_entregar"];
	  //$porcentaje=$clase->devuelve_porcentaje($dias,$hs1,$hr1,$hsp,$desayuno,$almuerzo,$cena,$hospedaje,$alm,$tipo,$dia_inicio);
		$porcentaje = $clase->porcentaje($e['id_pais'],$viatico['fecha_salida'],$viatico['fecha_regreso'],$viatico['hora_i'],$viatico['hora_f']);
		$sueldo_=0;

		$sueldo_=($e['sueldo']<10)?(count_days(date('Y'),date('m')))*$e['sueldo']:$e['sueldo'];

	    $sub_array = array(
	      'id'=>$e['reng_num'],
				'id_anticipo'=>'ac'.$e['reng_num'],
				'id_cheque'=>'ch'.$e['reng_num'],
	      'vt_nombramiento'=>$correlativo,
	      'reng_num'=>$e['reng_num'],
	      'dias'=>$dias,
	      'porcentaje'=>$porcentaje,
	      'moneda'=>$moneda,
	      'cuota_diaria'=>number_format(($cuota*$porcentaje), 2, ".", ""),
	      'empleado'=>$e['nombre'],
	      'sueldo'=>number_format($sueldo_,2,".",","),
				'verificar'=>$clase->get_liquidacion_pendiente_por_empleado_anterior($e['id_empleado'],$correlativo),//($e['sueldo']<10)?number_format(dias_de_un_mes(date('Y'),date('m'))*$e['sueldo']), 2, ".", ""):number_format($e['sueldo'], 2, ".", "")
				'cheque'=>$e['bln_cheque'],
				'checked'=>true,
	    );
	    $data[] = $sub_array;
	    //$data[]=$e;

	}

}



echo json_encode($data);
exit;
