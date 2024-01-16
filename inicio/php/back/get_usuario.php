<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
date_default_timezone_set('America/Guatemala');
$data = array();
$fecha = date('Y-m-d');

sec_session_start();

$usuario=acreditacion::get_usuario($_SESSION['Usu_id']);
//$procedencias = acreditacion::contar_personas_por_puerta($_SESSION['Evento'],$_SESSION['Punto']);
//$procedencias_ = acreditacion::contar_personas_adentro($_SESSION['Evento']);

$personas = 0;
/*foreach($procedencias_ AS $p){
  if($p['tipo_registro']==0){
    $personas+=1;
  }else
  if($p['tipo_registro']==1)
  {
    $personas-=1;
  }
}*/

$total = 0;
/*foreach($procedencias_ AS $p){
  $salida=acreditacion::encontrar_salida_de_invitado($p['Eve_id'],$p['Inv_id'],1);
  $u_entrada=acreditacion::encontrar_salida_de_invitado($p['Eve_id'],$p['Inv_id'],0);


  if($p['tipo_registro']==0 && $p['Pnt_id']==$_SESSION['Punto']){

    $total+=1;

  }else
  if($p['tipo_registro']==1 && $salida['Pnt_id']==$u_entrada['Pnt_id'] && $p['Pnt_id']==$_SESSION['Punto']){


    if($total>0){

      $total-=1;

    }
  }else
  if($p['tipo_registro']==1 && $salida['Pnt_id']!=$u_entrada['Pnt_id'] && $u_entrada['Pnt_id']==$_SESSION['Punto'])
  {

    if($total>0){

      $total-=1;

    }
  }


}*/
//echo $total;

$data=array(

  'evento'=>$usuario['Eve_nom'],
  'punto'=>$usuario['Pnt_des'],
  'fecha'=>date('d-m-Y'),
  'conteo'=>$total,
  'total'=>$personas
);

echo json_encode($data);
?>
