<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $tipo=$_POST['tipo'] ?? 1;
    $empleados = (new empleado)->get_contratos_listados($tipo);
    $data = array();

    $moduloF = usuarioPrivilegiado()->hasPrivilege(211);
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $expand = "'expand'";
    $x = 0;
    if($moduloF){
      //inicio
      foreach ($empleados as $e){
        $email = "email".$e['id_persona'];
        $email_id = "'.email".$e['id_persona']."'";
        $link='';
        //$conteo=verificar_modulos_asignados_persona($e['id_persona']);
        $accion='';

        $accion = '<div class="btn btn-group"><div class="">';

        $accion .='<a id="actions1Invoker2" class=" btn btn-soft-info btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"data-toggle="dropdown"><i class="fa fa-cog"></i></a>
        <div class="dropdown-menu dpm2 dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker2" style="margin-right:20px"><div class="card overflow-hidden" style="margin-top:-20px;"><div class="card-header d-flex align-items-center py-3"><h2 class="h4 card-header-title">Opciones:</h2></div><div  class="card-body animacion_right_to_left" style="padding: 0rem;"><div >
          <ul class="list-unstyled mb-0">
            <li class="mb-1">
              <a class="d-flex align-items-center link-muted py-2 px-3 text-info"  data-toggle="modal" data-target="#modal-remoto-lgg2" href="empleados/php/front/empleados/empleado.php?id_persona='.$e['id_persona'].'&tipo='.$tipo.'">
                <i class="fa fa-user mr-2"></i> Perfil
              </a>
            </li>';

            $accion .='
            <li class="mb-1">
              <a class="d-flex align-items-center link-muted py-2 px-3 text-info"  data-toggle="modal" data-target="#modal-remoto-lgg2" href="empleados/php/front/puestos/detalle_puesto?id_persona='.$e['id_persona'].'&tipo='.$tipo.'">
                <i class="fa fa-user-tie mr-2"></i> Datos laborales
              </a>
            </li>
            <li class="mb-1">
              <a class="d-flex align-items-center link-muted py-2 px-3 text-info" onclick="imprimirFicha('.$e['id_persona'].')">
                <i class="fa fa-print mr-2"></i> Ficha
              </a>
            </li>
          </ul>
        </div></div></div></div>';
        $accion.='</div></div>';

        $status='';
        if($e['estado_persona']==1){
          $status.='<span class="text-success"><i class="fa fa-check-circle"></i> Activo</span>';
        }else if($e['estado_persona']==0){
          $status.='<span class="text-danger"><i class="fa fa-times-circle"></i> Inactivo</span>';
        }

        $string = date_format(date_create($e['fecha_nacimiento']), "d/m/Y");
        $date = DateTime::createFromFormat("d/m/Y", $string);
        $anio = strftime("%d de %B de %Y",$date->getTimestamp());
        $direccion = ($e['dir_funcional'] != 'S/D') ? $e['dir_funcional'] : $e['dir_general'];
        $msg = '';
        $a1 = '';
        $a2 = '';
        $tipo = 0;
        if(!empty($e['archivo']) && !empty($e['archivor'])){
          $msg = 'Contrato y Resolución';
          $a1 = $e['archivo'];
          $a2 = $e['archivor'];
          $tipo = 3;
        }else if(!empty($e['archivo']) && empty($e['archivor'])){
          $msg = 'Solo Contrato';
          $a1 = $e['archivo'];
          $tipo = 1;
        }else if(empty($e['archivo']) && !empty($e['archivor'])){
          $msg = 'Solo Resolución';
          $a2 = $e['archivor'];
          $tipo = 2;
        }
        $sub_array = array(
          'id_persona'=>$e['id_persona'],
          'empleado'=>$e['primer_nombre'].' '.$e['segundo_nombre'].' '.$e['tercer_nombre'].' '.$e['primer_apellido'].' '.$e['segundo_apellido'].' '.$e['tercer_apellido'],
          'nit'=>$e['nit'],
          'igss'=>$e['afiliacion_igss'] ?: '',
          'email'=>$e['correo_electronico'],
          'descripcion'=>(!empty($e['estado_emple']))?$e['estado_emple']:$e['estado_per'],
          'status'=>$status,
          'estado'=>$e['estado_persona'],
          'nisp'=>$e['nisp'] ?: '',
          'f_ingreso'=>$e['fecha_inicio'],
          'f_baja'=>$e['fecha_baja'],
          'f_nac'=>$anio,
          'accion'=>$accion,
          'dir_funcional'=> $e['direccion'],
          'archivo'=>'<a class=""  data-toggle="modal" data-target="#modal-remoto-lgg2" href="empleados/php/front/contratos/contrato_empleado?archivo='.$a1.'&archivor='.$a2.'&tipo='.$tipo.'">'.$msg.'</a>',
        );

        $data[]=$sub_array;
      }
      //fin
    }else{
      
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
