<?php
    include_once '../../../../inc/functions.php';
    sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true): ?>
    <?php
        include_once '../functions.php';

        $totales = array();
        $totales = configuracion::get_usuarios();
        $data = array();


        foreach ($totales as $t){
          $button = "<div class='btn-group btn-group-sm' role='group'>
                        <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto' href='configuracion/php/front/empleado/cambiar_usuario.php?id_persona=".$t['id_persona']."&user=".$t['persona_user']."' data-toggle='tooltip' data-placement='top' title='Editar usuario'>
                            <i class='fa fa-user-edit'></i>
                          </span>
                          <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto' onclick='reset_password(".$t['id_persona'].")' data-toggle='tooltip' data-placement='top' title='Restablecer contraseña'>
                              <i class='fas fa-eraser'></i>
                          </span>
                        </div>";

          $sub_array = array(
            'id_persona'=>$t['id_persona'],
            'nombre'=>$t['nombre'],
            'dir'=>$t['dir_funcional'],
            'puesto'=>$t['p_funcional'],
            'user'=>$t['persona_user'],
            'status'=>$t['status'],
            'valid'=>$t['valida_ldap'],
            'accion'=>$button
            );
            $data[]=$sub_array;
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

