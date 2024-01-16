<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $id_insumo='';
  if(isset($_GET['Ppr_id'])){
    $id_insumo=$_GET['Ppr_id'];
  }

  $i = documento::get_insumos_by_id($id_insumo);

  $original = str_replace("\n","",$i['caracteres']);

  //$my_wrapped_string = wordwrap($original, 100, );
  $letras = strlen($original);
  $lineas = round(($letras/70)*3.4);
  $lines = 0;
  $puntos = ($lineas == 0 || $lineas < 5.5)?5:$lineas;
  $lines += $puntos;

  $data = array();
  $data = array(
    'Ppr_id'=>$i['Ppr_id'],
    'Pedd_can'=>'',//number_format($i['Pedd_can'],0,'',''),
    'Ppr_cod'=>$i['Ppr_cod'],
    'Ppr_codPre'=>$i['Ppr_codPre'],
    'Ppr_Nom'=>$i['Ppr_Nom'],
    'Ppr_Des'=>$i['Ppr_Des'],
    'Ppr_Pres'=>$i['Ppr_Pres'],
    'Ppr_Ren'=>$i['Ppr_Ren'],
    'Ppr_Med'=>$i['Med_nom'],
    'caracteres'=>$i['caracteres'],
    'lineas'=>$lines
  );

//echo $output;
echo json_encode($data);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
