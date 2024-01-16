<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';

    $totales = array();
    // if (usuarioPrivilegiado()->hasPrivilege(321)) {
    //   $priv = 1;
    // } else {
    //   $priv = 0;
    // }
    $totales = Directorio::get_telefonos();
    $data = array();


    foreach ($totales as $t) {

      $sub_array = array(
        'numero' => $t['nro_telefono'],
        'nombre' => $t['Nombre_Completo'],
        'referencia' => $t['nombre_tipo_referencia'],
        'tipo' => $t['nombre_tipo_telefono'],
        'observaciones' => $t['observaciones'],
        'gafete' => $t['id_persona'],
        'flag_activo' => ($t['Telefono_Activo']==1)?'ACTIVO':'INACTIVO',
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

