<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    date_default_timezone_set('America/Guatemala');
    include_once '../../../../empleados/php/back/functions.php';
    include_once '../functions.php';

    $empleados = array();
    $id_persona=$_POST['id_persona'];
    /*$u=usuarioPrivilegiado_acceso();
    if (isset($u) && $u->accesoModulo(7851))*/

    $clase = new empleado;
    //$e = $clase->get_empleados_in_id_ficha($id_persona);
    $parametros=str_replace("'","()",$id_persona);

    $str = ltrim($parametros, ',');
    if(!empty($str)){
      $pdo = Database::connect_sqlsrv();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT *
              FROM xxx_rrhh_Ficha
              WHERE id_persona in ($str)
              ORDER BY id_persona ASC";
              //echo $sql;
      $p = $pdo->prepare($sql);
      $p->execute(array());


      $empleados = $p->fetchAll();
      Database::disconnect_sqlsrv();
      $data = array();
      echo '<ul class="list-unstyled mb-0">';
      foreach ($empleados as $e){
        /*$sub_array = array(
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['nombre']
        );
        $data[] = $sub_array;*/

        echo '<li class="mb-1">
          <a class="d-flex align-items-center link-muted py-2 px-3">
          <table>
          <tbody>
          <tr>
          <td style="width:30px;">
          <label class="css-input switch switch-success switch-sm"><input type="radio" id="rd_'.$e['id_persona'].'" name="empleoactual" value="'.$e['id_persona'].'"></input><span></span></label>

          </td>
          <td style="width:300px">
          <small class="text-muted">Gafete: '.$e['id_persona'].' '.$e['nombre'].'</small>
          </td>
          <td>
          <span class="btn btn-sm btn-danger" onclick="remover_empleado_lista('.$e['id_persona'].')"><i class="fa fa-trash  text-center"></i></span>
          </td>
          </tr>
          </tbody>

          </table>

          </a>
        </li>';
        /*echo '';
         echo '<h5>'.$e['nombre'].'</h5>';
         echo '<span class="fa fa-trash" ></span>';*/
      }
      echo '</ul>';
    }else{
      
    }


  //echo json_encode($data);
  //echo $sql;

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;


?>
