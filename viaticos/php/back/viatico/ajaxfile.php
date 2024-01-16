<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';
sec_session_start();


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

if($s['fecha_regreso']>'2021-06-30'){

	$porcentaje = $clase->porcentaje($s['id_pais'],$s['fecha_salida'],$s['fecha_regreso'], $s['hora_i'],$s['hora_f']);

	if($porcentaje < 1){
		$diferencia_ = '1 día';
	}else{
		$whole = floor($porcentaje);      // 1
		$fraction = $porcentaje - $whole;

		$dias = ($whole == 1)?' día ': ' días ';
		$restante = ($fraction > 0 )?' y medio ':'';
		$diferencia_ = $whole . $dias. $restante	;
	}




}else{
	$diferencia_ = $intervalo->format('%d '.$d.' %H horas %i minutos ');
}

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
$total_destinos=0;

if($s['descripcion_lugar']==2)
{
	$valores = $clase->get_historial_viatico_destinos($id_solicitud);
	//$historial = $valor['val_anterior'];
	$x=0;
	$total_destinos=count($valores);
	foreach($valores as $valor){
		$x++;

		$dep.=' '.$x.'.- ';
		$dep.=$valor['muni'].'-'.$valor['depto'];

	}


}
$inc=1;
$fecha_inicio=$s['fecha_regreso'];
$fecha1=strtotime($s['fecha_regreso']);
$f_final='';
$fecha_i=strtotime(date('Y-m-d'));
$fecha_f='';
$i=0;
$f_actual='';
if($s['tipo_status']==1){


	do{
		$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1));
		if((strcmp(date('D',$fecha1),'Sun')!=0) && (strcmp(date('D',$fecha1),'Sat')!=0)){
			$inc++;
			$f_final=date('Y-m-d',$fecha1);
		}

	}while($inc<6);


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

}
$decimal_=(($dia+($hora/24))*420);

$emitir_cheque = 0;
if(usuarioPrivilegiado()->hasPrivilege(4)){
	if(usuarioPrivilegiado()->hasPrivilege(316)){
		$emitir_cheque = 3;
	}
	else{
		$emitir_cheque = 1;
	}
}else{
	$emitir_cheque = 2;
}

$response = array(
	'DT_RowId'=>$s['vt_nombramiento'],
	'id_pais'=>$s['id_pais'],
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
	'duracion'=>/*$diferencia = $s['fecha_salida']->diff($s['fecha_regreso']);*/$diferencia_,
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
	'confirma_lugar'=>($s['descripcion_lugar']=='1' || $s['descripcion_lugar']=='2')?$s['descripcion_lugar']:'0',
	'historial'=>$dep,
	'hospedaje'=>($s['bln_hospedaje']==1)?'Hospedaje':'',
	'alimentacion'=>($s['bln_alimentacion']==1)?'Alimentación':'',
	'accion'=>'',
	'total_destinos'=>4-$total_destinos,
	'diferencia'=>($i>0)?'Le quedan: <span class="'.$class.'">'.$diferencia.'</span> para liquidar':'',
	'dias_pendiente'=>$i,
	'emitir_cheque'=>$emitir_cheque

);

echo json_encode($response);
exit;
