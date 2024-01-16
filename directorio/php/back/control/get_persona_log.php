<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';

    $id_persona = $_POST["id_persona"];
    $month = $_POST["month"];
    $year = $_POST["year"];
    $totales = array();
    $totales = Directorio::get_log_by_id($id_persona, $month, $year);
    $data = array();

    $str = "arr[]=Creación&arr[]=Lectura&arr[]=Actualización&arr[]=Eliminación";
    parse_str($str, $output);

    foreach ($totales as $t) {
      $vn1 = '';
      $va1 = '';
      $newDate = date("H:i d/m/Y", strtotime($t['fecha_mod']));

      if ($t['val_anterior'] != 'No aplica') {
        $va = json_decode($t['val_anterior'], true);
        $keys = array_keys($va);
        $i = 0;
        foreach ($va as $x) {
          if ($keys[$i] == 'id_persona') {
            $va1 = Directorio::get_name($x) . "\n";
            $i++;
          } elseif ($keys[$i] == 'id_telefono') {
            // $ids=$x;
            $i++;
          } elseif (preg_match("/^flag/", $keys[$i])) {
            $i++;
          } elseif (preg_match("/tipo/", $keys[$i])) {
            $va1 = $va1 . Directorio::get_name_catalogo($x) . "\n";
            $i++;
          } else {
            // $va1="(".$va1.$keys[$i].") ".$x."\n";
            $va1 = $va1 . "\n" . $x . "\n";
            $i++;
          }
        }
      }

      $vn = json_decode($t['val_nuevo'], true);
      $keys = array_keys($vn);

      $i = 0;
      $ids = '';
      foreach ($vn as $x) {
        if ($keys[$i] == 'id_persona') {
          $ids = Directorio::get_name($x) . "\n";
          $i++;
        } elseif ($keys[$i] == 'id_telefono') {
          $ids = Directorio::get_name_id_tel($x) . "\n";
          $i++;
          // }elseif($keys[$i]=='flag_activo'|$keys[$i]=='flag_principal'){
        } elseif (preg_match("/^flag/", $keys[$i])) {
          $i++;
        } elseif (preg_match("/tipo/", $keys[$i])) {
          $vn1 = $vn1 . Directorio::get_name_catalogo($x) . "\n";
          $i++;
        } else {
          // $vn1=$vn1."(".$keys[$i].") ".$x."\n";
          $vn1 = $vn1 . "\n" . $x . "\n";
          $i++;
        }
      }

      $data[] = array(
        // 'descripcion_corta'=>$t['descripcion_corta'],
        'descripcion_corta' => $ids,
        'descrip_corta' => $t['descrip_corta'],
        'tipo_trans' => $output['arr'][$t['tipo_trans'] - 1],
        'val_anterior' => $va1,
        'val_nuevo' => $vn1,
        'fecha_mod' => $newDate
      );
    }

    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data,
      "data" => $totales
    );

    echo json_encode($results);


  else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
  endif;

    ?>

