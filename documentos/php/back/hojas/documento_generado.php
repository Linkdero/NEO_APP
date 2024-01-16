<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $docto_id=$_POST['docto_id'];



  $d = documento::generar_documento_word($docto_id);
  $data = array();

  $data = array(
    'docto_id'=>$d['docto_id'],
    'titulo'=>$d['docto_titulo'],
    'tipo'=>$d['tipo'],
    'alineacion'=>$d['alineacion'],
    'correlativo'=>$d['correlativo'],
    'fecha_docto'=>fechaCastellano($d['docto_fecha'])
  );

//echo $output;
echo json_encode($data);



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
