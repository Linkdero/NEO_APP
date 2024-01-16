<?php
    include_once '../../../../inc/functions.php';
    sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true): ?>
    <?php
        include_once '../functions.php';

        $totales = array();
        $totales = configuracion::get_directorio();
        $data = array();


        foreach ($totales as $t){
          $button = "<div class='btn-group btn-group-sm' role='group'>
                      <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' href='configuracion/php/front/herramientas/detalle_extension.php?extension={$t['extension']}&id_persona={$t['id_persona']}' data-toggle='tooltip' data-placement='top' title='Editar extensión'>
                        <i class='fas fa-edit'></i>
                      </span>
                        <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' onclick='del_ext_usr({$t['id_persona']})' data-toggle='tooltip' data-placement='top' title='Eliminar usuario'>
                          <i class='fas fa-trash'></i>
                        </span>
                      </div>";

          if($t['id_persona']!=''){
            $sub_array = array(
              'Extensión'=>'<h1><strong>'.$t['extension'].'</strong></h1>',
              'Ubicación'=>$t['ubicacion'],
              'Puesto'=>$t['puesto'],
              'Correo'=>$t['correo'],
              'Empleado'=>$t['primer_nombre']." ".$t['segundo_nombre']." ".$t['tercer_nombre']." ".$t['primer_apellido']." ".$t['segundo_apellido']." ".$t['tercer_apellido'],
              'Foto'=>$t['id_persona'],
              'Acción'=>$button
              );
              $data[]=$sub_array;
            }else{
              $sub_array = array(
                'Extensión'=>'<h1><strong>'.$t['extension'].'</strong></h1>',
                'Ubicación'=>$t['ubicacion'],
                'Puesto'=>$t['puesto'],
                'Correo'=>$t['correo'],
                'Empleado'=>$t['de_persona'],
                'Foto'=>$t['id_persona'],
                'Acción'=>$button
                );
                $data[]=$sub_array;
            }
          }

        $results = array(
          "sEcho" => 1,
          "iTotalRecords" => count($data),
          "iTotalDisplayRecords" => count($data),
          "aaData"=>$data);

          echo json_encode($results);

        else:
          echo "<script type='text/javascript'> window.location='principal'; </script>";
        endif;
  
     ?>

