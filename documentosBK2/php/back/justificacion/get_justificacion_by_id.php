<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $docto_id='';
  if(isset($_GET['docto_id'])){
    $docto_id=$_GET['docto_id'];
  }

  $clased = new documento;
  $d = $clased->generar_documento_word($docto_id);

  $insumos = array();

  /*$ins= $clased->get_insumos_by_pedido($d['pedido_tra']);
  $p=$clased->get_pedido_by_id($d['pedido_tra']);
  foreach($ins as $i){
    $sub_array = array(
      'Ppr_id'=>$i['Ppr_id'],
      'Pedd_can'=>number_format($i['Pedd_can'],0,'',''),
      'Ppr_cod'=>$i['Ppr_cod'],
      'Ppr_codPre'=>$i['Ppr_codPre'],
      'Ppr_Nom'=>$i['Ppr_Nom'],
      'Ppr_Des'=>$i['Ppr_Des'],
      'Ppr_Pres'=>$i['Ppr_Pres'],
      'Ppr_Ren'=>$i['Ppr_Ren']
    );
    $insumos[] = $sub_array;
  }*/
  $data = array();

  $data = array(
    'docto_id'=>$d['docto_id'],
    'titulo'=>$d['docto_titulo'],
    'direccion'=>$d['direccion'],

    /*'indice'=>$array_bases,
    'cronograma'=>$array_cronograma,
    'listadoDoctos'=>$array_literales,*/
    'tipo'=>$d['tipo'],
    'fecha'=>fecha_dmy($d['docto_fecha']),
    'alineacion'=>$d['alineacion'],
    'correlativo'=>$d['correlativo'],
    'fecha_docto'=>fechaCastellano($d['docto_fecha']),
    'categoria'=>$d['tipo'],
    //'pedido_num'=>$d['ped_num'],
    'pedido_tra'=>$d['pedido_tra'],
    //'insumos'=>$insumos,
    'especificaciones'=>$d['docto_descripcion'],
    'necesidad'=>$d['docto_nombre'],
    'temporalidad'=>$d['docto_temporalidad'],
    'finalidad'=>$d['docto_finalidad'],
    'resultado'=>$d['docto_resultados'],
    'pedido_tipo'=>$d['pedido_tipo'],
    'pedido_diagnostico'=>$d['pedido_diagnostico']
  );

//echo $output;
echo json_encode($data);



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
