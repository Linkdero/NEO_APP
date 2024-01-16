<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';

    $totales = array();
    $totales = Directorio::get_personas_control();
    $data = array();


    foreach ($totales as $t) {
      $button = "<div class='btn-group btn-group-sm' role='group'>
                      <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' href='directorio/php/front/control/detalle_accion.php?id_persona={$t['usr_mod']}' data-toggle='tooltip' data-placement='top' title='Mostrar contactos'>
                      <i class='fa fa-eye'></i>
                      </span>
                      </div>";

      $str = "arr[]=CREACION&arr[]=LECTURA&arr[]=ACTUALIZACION&arr[]=ELIMINACION";
      parse_str($str, $output);

      $newDate = date("H:i\nd/m/Y", strtotime($t['fecha_mod']));


      $sub_array = array(
        'Foto' => $t['usr_mod'],
        'Nombre' => $t['nombre'],
        'Dirección' => $t['dir_funcional'],
        'Puesto' => $t['p_funcional'],
        'Ultima' => $newDate,
        'TipoTrans' => $output['arr'][$t['tipo_trans'] - 1],
        'Detalle' => $button
      );
      $data[] = $sub_array;
    }

    $results = array(
      "sEcho" => 1,
      "iTotalRecords" => count($data),
      "iTotalDisplayRecords" => count($data),
      "aaData" => $data
    );

    echo json_encode($results);

  else :
    echo "<script type='text/javascript'> window.location='principal'; </script>";
  endif;

    ?>

