<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';
    $editar = 0;

    if (evaluar_flag($_SESSION['id_persona'], 1875, 228, 'flag_actualizar') == 1) {
      $editar = 1;
    }

    $id_persona = $_POST["id_persona"];
    $totales = array();
    $totales = Directorio::get_telefono_by_id($id_persona);
    $data = array();
    foreach ($totales as $t) {
      $numero = '"' . $t['nro_telefono'] . '"';
      $accion = '';
      if (evaluar_flag($_SESSION['id_persona'], 1875, 228, 'flag_eliminar') == 1) {
        $accion = "<div class='btn-group' role='group'>
                        <span title='Inactivar Contacto' class='btn btn-sm btn-personalizado outline' onclick='inactivar_tel({$id_persona}, {$numero});'>
                          <i class='fas fa-minus-square' data-toggle='tooltip' data-placement='right'></i>
                        </span>
                      </div>";
      }
      $data[] = array(
        'id_telefono' => $t['id_telefono'],
        'numero' => $t['nro_telefono'],
        'tipo' => $t['nombre_tipo_telefono'],
        'referencia' => $t['nombre_tipo_referencia'],
        'observaciones' => $t['observaciones'],
        'privilegio' => $editar,
        'accion' => $accion
        // 'accion'=>"<div class='btn-group' role='group'>
        //             <span title='Inactivar Contacto' class='btn btn-sm btn-personalizado outline' onclick='inactivar_tel({$id_persona}, {$numero});'>
        //               <i class='fas fa-minus-square' data-toggle='tooltip' data-placement='right'></i>
        //             </span>
        //           </div>",
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

