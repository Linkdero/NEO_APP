<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';
  $tipo=$_POST['tipo'];

  $response = array();


  $documentos = documento::get_documentos_listado($tipo);
  $data = array();
  foreach ($documentos as $d){
    $respuesta='';

    $sub_array = array(
      'DT_RowId'=>$d['docto_id'],
      'div_doc'=>'_'.$d['docto_id'],
      'docto_id'=>$d['docto_id'],
      'titulo'=>$d['docto_titulo'],
      'correlativo'=>($d['docto_tipo_emision']==1)?$d['correlativo']:$d['docto_correlativo_externo'],
      'categoria'=>$d['doc_categoria'],
      'categoria_id'=>$d['docto_categoria'],
      'fecha_docto'=>fechaCastellano($d['docto_fecha']),
      'destinatarios'=>'1',
      'nombre'=>(!empty($d['docto_nombre']))?$d['docto_nombre']:'',
      'respuesta'=>$respuesta,
      'respondido'=>$d['docto_respondido'],
      'accion'=>''
    );
    $data[] = $sub_array;

  }

  $results = array(
    "sEcho" => 1,
    "iTotalRecords" => count($data),
    "iTotalDisplayRecords" => count($data),
    "aaData"=>$data
  );

  echo json_encode($results);

else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
