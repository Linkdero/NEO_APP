<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    include_once '../functions.php';
    $modulos = array();
    $modulos=configuracion::get_modulos(65);
    $data = array();

    foreach ($modulos as $m){
      $status='';
      if($m['id_status']==0){
        $status.='<span class="badge badge-danger">Inactivo</span>';
      }else{
        $status.='<span class="badge badge-success">Activo.</span>';
      }

      $accion='';
      $accion='<a id="actions1Invoker" class=" btn btn-personalizado outline btn-sm" href="#!" aria-haspopup="true" aria-expanded="false"
         data-toggle="dropdown">
        <i class="fa fa-sliders-h"></i>
      </a>

      <div class="dropdown-menu dropdown-menu-right border-0 py-0 mt-3 slide-up-fade-in" style="width: 260px;" aria-labelledby="actions1Invoker" style="margin-right:20px">
        <div class="card overflow-hidden" style="margin-top:-20px;">
        <div class="card-header kt-head d-flex align-items-center py-3">
          <h2 class="h4 card-header-title">Opciones: '.$m['sub_modulo'].'</h2>

        </div>

        <div  class="card-body animacion_right_to_left" style="padding: 0rem;">
          <ul class="list-unstyled mb-0">
            <li class="mb-1">
              <a class="d-flex align-items-center link-muted py-2 px-3" data-toggle="modal" data-target="#modal-remoto-lgg2" href="configuracion/php/front/modulos/sub_modulos.php?modulo='.$m['id_item'].'">
                <i class="fa fa-tv mr-2"></i> Pantallas
              </a>
            </li>
            <li class="mb-1">
              <a class="d-flex align-items-center link-muted py-2 px-3"  data-toggle="modal" data-target="#modal-remoto-lgg2" href="configuracion/php/front/modulos/accesos.php?modulo='.$m['id_item'].'">
                <i class="fa fa-key mr-2"></i> Accesos
              </a>
            </li>
          </ul>
        </div>
      </div>
      </div>';



      $sub_array = array(
        'id_catalogo'=>$m['id_catalogo'],
        'id_modulo'=>$m['id_item'],
        'id_status'=>$status,

        'modulo'=>$m['sub_modulo'],
        'comentario_modulo'=>$m['comentario_submodulo'],
        'id_ref_tipo'=>$m['id_ref_tipo'],
        'accion'=>$accion
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
//c6d098d4f8780c6209c7a5407db10fd121708ff5c41cd1a747981128ddb94c3fcaad57784825c1c6ede99a071eeef49515e1e0d7f73ac344848fc9a182ae6a7e
//319287e5f398a9c215587f112f55bee6e69303e087a3a7fe61b35f6f4dd09cae49fb66ce7d62eaa691fbd08c02d10ec220ce1efea97308e80fe3a01ab0287e48

?>
