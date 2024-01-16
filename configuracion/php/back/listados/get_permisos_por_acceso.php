<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $acceso=$_POST['acceso'];
    $permisos = array();
    $permisos=configuracion::get_permisos_por_acceso($acceso);
    $data = array();

    foreach ($permisos as $e){
      $status='';
      if($e['id_activo']==0){
        $status.='<span class="badge badge-danger">Inactivo</span>';
      }else{
        $status.='<span class="badge badge-success">Activo.</span>';
      }
      $chk1='';$chk2='';$chk3='';$chk4='';$chk5='';$chk6='';$chk7='';$chk8='';
      if($e['estado_acceso']==1119){
        //inicio
        $chk1.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'1" class="custom-control-input"';
        $chk1.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_es_menu"';

        if($e['flag_es_menu']==1){
          $chk1.='checked';
        }
        $chk1.=' name="'.$e['id_persona'].'1" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'1"></label>
        </div>';
        //fin

        //inicio
        $chk2.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'2" class="custom-control-input"';
        $chk2.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_insertar"';
        if($e['flag_insertar']==1){
          $chk2.='checked';
        }
        $chk2.=' name="'.$e['id_persona'].'2" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'2"></label>
        </div>';
        //fin

        //inicio
        $chk3.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'3" class="custom-control-input"';
        $chk3.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_eliminar"';
        if($e['flag_eliminar']==1){
          $chk3.='checked';
        }
        $chk3.=' name="'.$e['id_persona'].'3" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'3"></label>
        </div>';
        //fin

        //inicio
        $chk4.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'4" class="custom-control-input"';
        $chk4.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_actualizar"';
        if($e['flag_actualizar']==1){
          $chk4.='checked';
        }
        $chk4.=' name="'.$e['id_persona'].'4" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'4"></label>
        </div>';
        //fin

        //inicio
        $chk5.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'5" class="custom-control-input"';
        $chk5.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_imprimir"';
        if($e['flag_imprimir']==1){
          $chk5.='checked';
        }
        $chk5.=' name="'.$e['id_persona'].'5" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'5"></label>
        </div>';
        //fin

        //inicio
        $chk6.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'6" class="custom-control-input"';
        $chk6.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_acceso"';
        if($e['flag_acceso']==1){
          $chk6.='checked';
        }
        $chk6.=' name="'.$e['id_persona'].'6" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'6"></label>
        </div>';
        //fin

        //inicio
        $chk7.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'7" class="custom-control-input"';
        $chk7.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_autoriza"';
        if($e['flag_autoriza']==1){
          $chk7.='checked';
        }
        $chk7.=' name="'.$e['id_persona'].'7" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'7"></label>
        </div>';
        //fin

        //inicio
        $chk8.='<div class="custom-control custom-checkbox text-center">
        <input id="'.$e['id_pantalla'].'8" class="custom-control-input"';
        $chk8.='data-tipe="'.$e['id_acceso'].'-'.$e['id_pantalla'].'" data-id="flag_descarga"';
        if($e['flag_descarga']==1){
          $chk8.='checked';
        }
        $chk8.=' name="'.$e['id_persona'].'8" type="checkbox">
        <label class="custom-control-label" for="'.$e['id_pantalla'].'8"></label>
        </div>';
        //fin
      }else if($e['estado_acceso']==1120){
        $chk1.='<i class="';if($e['flag_es_menu']==1){$chk1.='fa fa-check text-success';}else{$chk1.='fa fa-times text-danger';}$chk1.='"></i>';
        $chk2.='<i class="';if($e['flag_insertar']==1){$chk2.='fa fa-check text-success';}else{$chk2.='fa fa-times text-danger';}$chk2.='"></i>';
        $chk3.='<i class="';if($e['flag_eliminar']==1){$chk3.='fa fa-check text-success';}else{$chk3.='fa fa-times text-danger';}$chk3.='"></i>';
        $chk4.='<i class="';if($e['flag_actualizar']==1){$chk4.='fa fa-check text-success';}else{$chk4.='fa fa-times text-danger';}$chk4.='"></i>';
        $chk5.='<i class="';if($e['flag_imprimir']==1){$chk5.='fa fa-check text-success';}else{$chk5.='fa fa-times text-danger';}$chk5.='"></i>';
        $chk6.='<i class="';if($e['flag_acceso']==1){$chk6.='fa fa-check text-success';}else{$chk6.='fa fa-times text-danger';}$chk6.='"></i>';
        $chk7.='<i class="';if($e['flag_autoriza']==1){$chk7.='fa fa-check text-success';}else{$chk7.='fa fa-times text-danger';}$chk7.='"></i>';
        $chk8.='<i class="';if($e['flag_descarga']==1){$chk8.='fa fa-check text-success';}else{$chk8.='fa fa-times text-danger';}$chk8.='"></i>';

      }



      $sub_array = array(
        'id_pantalla'=>$e['id_pantalla'],
        'pantalla'=>$e['descrip_corta'],
        'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
        'menu'=>$chk1,
        'insertar'=>$chk2,
        'eliminar'=>$chk3,
        'actualizar'=>$chk4,
        'imprimir'=>$chk5,
        'acceso'=>$chk6,
        'autoriza'=>$chk7,
        'descarga'=>$chk8



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
