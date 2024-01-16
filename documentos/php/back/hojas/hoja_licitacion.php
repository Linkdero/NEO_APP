<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

  include_once '../functions.php';


  $response = array();
  $docto_id=$_POST['docto_id'];

  $clased = new documento;
  $d = $clased->generar_documento_word($docto_id);
  $bases = get_items(143);

  $array_bases=array();
  $i=0;
  if($bases["status"] == 200){
    foreach($bases["data"] as $bd){
      $i+=1;
      $sub_array= array(
        'indice_id'=>$i,
        'indice_string'=>$bd['descripcion']
      );
      $array_bases[] = $sub_array;
    }
  }

  $cronograma = $clased->get_cronograma($docto_id,144);
  $array_cronograma = array();
  $listado_literales = $clased->get_base_literales_by_docto($docto_id,8058);
  $array_literales = array();

  if($cronograma["status"] == 200){
      $response = "";
      //$data[] = $sub_array;
      $sub_array = array(
        'actividad_id'=>0,
        'actividad_string_c'=>'',
        'actividad_string'=>'ACTIVIDAD',
        'actividad_fecha'=>'FECHA O PLAZO',
        'actividad_valor'=>'',
        'actividad_parametros'=>''
      );
      $array_cronograma[] = $sub_array;

      foreach($cronograma["data"] as $c){
          //$response .="<option value=".$hora['id_item'].">".$hora['descripcion_corta']."</option>";

          $sub_array = array(
            'actividad_id'=>$c['id_item'],
            'actividad_string_c'=>$c['descripcion_corta'],
            'actividad_string'=>$c['descripcion'],
            'actividad_fecha'=>(!empty($c['fecha']))?fecha_dmy($c['fecha']):$c['base_literal_descripcion'],
            'actividad_valor'=>(!empty($c['valor']))?number_format($c['valor'],0,'',''):'0',
            'actividad_parametros'=>$c['id_item']
          );
          $array_cronograma[] = $sub_array;
      }

  }



  if($listado_literales["status"] == 200){
      $response = "";
      //$data[] = $sub_array;
      foreach($listado_literales["data"] as $l){
        $sub_array = array(
          'docto_id'=>$l['docto_id'],
          'base_id'=>$l['base_id'],
          'base_literal_id'=>$l['base_literal_id'],
          'base_literal_nom'=>$l['base_literal_nom'],
          'base_literal_titulo'=>$l['base_literal_titulo'],
          'base_literal_descripcion'=>$l['base_literal_descripcion'],
        );
        $array_literales[] = $sub_array;
      }

  }

  $data = array();

  $data = array(
    'docto_id'=>$d['docto_id'],
    'titulo'=>$d['docto_titulo'],
    'indice'=>$array_bases,
    'cronograma'=>$array_cronograma,
    'listadoDoctos'=>$array_literales,
    'tipo'=>$d['tipo'],
    'alineacion'=>$d['alineacion'],
    'correlativo'=>$d['correlativo'],
    'fecha_docto'=>fechaCastellano($d['docto_fecha']),
    'categoria'=>$d['tipo']
  );

//echo $output;
echo json_encode($data);



else:
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
endif;


?>
