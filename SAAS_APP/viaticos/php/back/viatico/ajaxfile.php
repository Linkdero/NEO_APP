<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';


$response = array();
$id_solicitud='';
if(isset($_GET['vt_nombramiento'])){
	$id_solicitud=$_GET['vt_nombramiento'];
}

$clase = new viaticos;

$s = $clase->get_solicitud_by_id($id_solicitud);

//$response[] = $nombramientos;

$fecha1 = new DateTime(date('Y-m-d', strtotime($s['fecha_salida'])).' '.$s['hora_i']);//fecha inicial
$fecha2 = new DateTime(date('Y-m-d', strtotime($s['fecha_regreso'])).' '.$s['hora_f']);//fecha de cierre

$intervalo = $fecha1->diff($fecha2);

$estado='';
$estado=$s['estado'];

$d='días';
$d_extranjero;
$dia = $intervalo->format('%d');
$hora = $intervalo->format('%H');

if($dia==1){
	$d='día';
}
if($s['id_pais']!='GT'){
	if($dia==0){
		$dia=1;
	}else{
		if($s['hora_regreso']>945){
			$d_extranjero=1;
			$decimal_hora=($hora/24)*10;
			if($decimal_hora>0){
				$dia=$dia+0.5;
			}
		}
	}

}

$diferencia = $intervalo->format('%d '.$d.' %H horas %i minutos ');
$historial='';
$dep='';
if($s['descripcion_lugar']==1)
{
	$valor = $clase->get_historial_viatico($id_solicitud);
	//$historial = $valor['val_anterior'];
	$va = json_decode($valor['val_nuevo'], true);
	$keys = array_keys($va);

	$deptos=$clase->get_departamentos($s['id_pais']);
	$munis=$clase->get_municipios($va['id_departamento']);
	$aldeas=$clase->get_aldeas($va['id_municipio']);

	foreach($deptos["data"] as $d){
		$dep.=($va['id_departamento']==$d['id_departamento'])?$d['nombre']:'';
	}
	foreach($munis["data"] as $m){
		$dep.=($va['id_municipio']==$m['id_municipio'])?', '.$m['nombre']:'';
	}
	foreach($aldeas["data"] as $a){
		$dep.=($va['id_aldea']==$a['id_aldea'])?', '.$a['nombre']:'';
	}

}




$decimal_=(($dia+($hora/24))*420);
$response = array(
	'DT_RowId'=>$s['vt_nombramiento'],
	'nombramiento' => $s['vt_nombramiento'],
	'fecha' => fecha_dmy($s['fecha']),
	'direccion_solicitante' => $s['direccion'],
	'destino'=>$s['pais'].', '.$s['departamento'].', '.$s['municipio'],
	'motivo'=>$s['motivo'],
	'fecha_ini'=>fecha_dmy($s['fecha_salida']),
	'id_fi'=>1,
	'id_ff'=>2,
	'fecha_fin'=>fecha_dmy($s['fecha_regreso']),
	'hora_ini'=>$s['hora_ini'],
	'hora_fin'=>$s['hora_fin'],
	'duracion'=>/*$diferencia = $s['fecha_salida']->diff($s['fecha_regreso']);*/$diferencia,
	'monto_sugerido'=>$decimal_,
	'autorizado_por'=>$s['primer_nombre'].' '.$s['segundo_nombre'].' '.$s['primer_apellido'].' '.$s['segundo_apellido'],
	'estado' => $estado,
	'status'=> $s['id_status'],
	'progress'=>'',
	'personas'=>$s['personas'],
	'id_pais'=>$s['id_pais'],
	'id_grupo'=>$s['id_grupo'],
	'dias_extranjero'=>$dia,
	'funcionario'=>(!empty($s['f_pn']))?$s['f_pn'].' '.$s['f_sn'].' '.$s['f_pa'].' '.$s['f_sa']:'Ninguno',
	'tipo_cambio'=>$s['tipo_cambio'],
	'monto_asignado'=>'',//$s['monto_asignado'],
	'personas_c'=>$s['personas_c'],
	'personas_l'=>$s['personas_l'],
	'liquidado_gt'=>(empty($s['liquidado_gt']))?'0.00':number_format($s['liquidado_gt'],2,'.',','),
	'confirma_lugar'=>(($s['descripcion_lugar'])!='1')?'0':'1',
	'historial'=>$dep,
	'hospedaje'=>($s['bln_hospedaje']==1)?'Hospedaje':'',
	'alimentacion'=>($s['bln_alimentacion']==1)?'Alimentación':'',
	'accion'=>''

);

echo json_encode($response);
exit;
