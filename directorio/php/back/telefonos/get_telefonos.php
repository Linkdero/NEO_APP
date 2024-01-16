<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';

    $totales = array();
    if (usuarioPrivilegiado()->hasPrivilege(321)) {
      $priv = 1;
    } else {
      $priv = 0;
    }
    $totales = Directorio::get_personas_dir($priv);
    $data = array();


    foreach ($totales as $t) {
      $button = "<div class='btn-group btn-group-sm' role='group'>
                      <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' href='directorio/php/front/telefonos/detalle_telefonos.php?id_persona={$t['id_persona']}' data-toggle='tooltip' data-placement='top' title='Mostrar contactos'>
                      <i class='fa fa-eye'></i>
                      </span>
                      </div>";


      $direccion = ($t['dir_funcional'] != 'S/D') ? $t['dir_funcional'] : $t['dir_general'];

      $sub_array = array(
        'Foto' => $t['id_persona'],
        'ID' => "<b>" . $t['id_persona'] . "</b>",
        'Nombre' => $t['primer_nombre'] . ' ' . $t['segundo_nombre'] . ' ' . $t['tercer_nombre'] . ' ' . $t['primer_apellido'] . ' ' . $t['segundo_apellido'] . ' ' . $t['tercer_apellido'],
        'Dirección' => $direccion,
        'Puesto' => $t['p_funcional'],
        'Grupo' => $t['grupo'],
        'Promocion' => $t['nombre_promocion'],
        'Estado' => ($t['estado_persona']) ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>',
        'TipoServicio' => $t['tipo_servicio'],
        'Detalle' => $button,
        'estado_persona' => ($t['estado_persona'])
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

