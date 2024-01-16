<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';

date_default_timezone_set('America/Guatemala');
$data = array();
$fecha=date('Y-m-d');
$result= array();


sec_session_start();
$total = acreditacion::get_all_invitados($_SESSION['Evento']);
$procedencias = acreditacion::contar_personas_adentro($_SESSION['Evento']);

$personas = 0;
foreach($procedencias AS $p){
  if($p['tipo_registro']==0){
    $personas+=1;
  }else
  if($p['tipo_registro']==1)
  {
    $personas-=1;
  }
}


$por_total=  acreditacion::get_porcentaje($total['conteo'],$total['conteo']);
$por_personas =acreditacion::get_porcentaje($personas,$total['conteo']);

$graph1="[".$por_personas.",".($por_total-$por_personas)."]";
$graph2="[".$por_total.",0]";

$sub_array = array(
  'total'=>$total['conteo'],
  'personas'=>$personas,
  'graph1'=>$graph1,
  'graph2'=>$graph2,
  'por_total'=>$por_total,
  'por_personas'=>$por_personas
);

echo json_encode($sub_array);
?>
