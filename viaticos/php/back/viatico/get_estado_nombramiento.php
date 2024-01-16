<?php

include_once '../../../../inc/functions.php';
include_once '../functions.php';


$response = array();
$id_solicitud='';
if(isset($_GET['vt_nombramiento'])){
	$id_solicitud=$_GET['vt_nombramiento'];
}


$s = viaticos::get_estado_by_id($id_solicitud);


$response = array(
	'status'=>$s['id_status']

);


echo json_encode($response);
exit;
