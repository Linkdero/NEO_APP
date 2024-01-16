<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()== true){
date_default_timezone_set('America/Guatemala');
$data = array();
$i=$_POST['invitado'];



$invitado=acreditacion::get_invitado($_SESSION['Evento'],$i,2);

if($invitado['Inv_nom']!=''){
  if($_SESSION['Evento']==$invitado['Eve_id']){
    $conteo=acreditacion::verificar_entrada_salida($invitado['Inv_id'],1,date('Y-m-d'));
    $encoded_image = base64_encode($invitado['Inv_fotografia']);

    $Hinh='';
    $resultado='';
    $resultado.='
    <div class="card-body p-0 m-0 ">
    <div class="row">

    <div class="col-sm-12 ">
    <br>
    <div class=" ">
    <div class="" >';
    if($invitado['Inv_fotografia']!=''){
      $resultado.= "
      <div class=' col-sm-12 text-center'>
      <div class='img-contenedor_profile border-md-right border-light' style='border-radius:50%'>
      <img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' style='width:100%;margin-right:auto;'>
      </div>
      </div>";
    }else{
      $resultado.="
      <div class=' col-sm-12 text-center'>
      <div class='img-contenedor_profile border-md-right border-light' style='border-radius:50%'>
      <img class='img-fluid mb-3' src='assets/svg/mockups/LOGO_SAAS.png' style='width:100%;margin-right:auto;margin-top:17px'>
      </div>
      </div>";
    }

    $resultado.='
    </div>
    </div>
    </div>
    <div class="col-sm-12  ">

    <div class="col-sm-12">
    <div class="text-center  ">
    <br>
    <div class="form-group">
    <input type="text" id="nombre" class="form-control" value="'.$invitado['Inv_nom'].'"></input>
    </div>
    <div class="form-group">
    <input type="text" id="institucion" class="form-control" value="'.$invitado['Inv_pro'].'"></input>
    </div>

    <div class="text-muted"><small></small></div>

    </div>
    </div>
    </div>
    </div>
    </div>
    <footer class="card-footer ">
    <div class="row">

    <div class="col-sm-12 text-right">
    ';
      $resultado.='<span class="btn  btn-info" onclick="update_nuevo_acreditado('.$invitado['Inv_id'].')"><i class="fa fa-check-circle" ></i> Guardar</span> ';
    if($invitado['Inv_activo']==1){
      $resultado.='<span class="btn  btn-danger" onclick="inactivar_invitado('.$invitado['Inv_id'].',0)"><i class="fa fa-times" ></i> Inactivar</span> ';
    }



    $resultado.='<span class="btn  btn-danger" onclick="cancelar_solucion_conflictos()"><i class="fa fa-times"></i> Cancelar</span>


    </div>
    </div>
    </div>

    ';
        /*$resultado.='	<div class="col-md-12 border-md-right border-light text-center " >';
        $resultado.='<div class="bg-soft-info">';
                    $resultado.='<div class="img-contenedor_profile" style="border-radius:50%;margin-left:-30%">';

        $resultado.='</div></div>';
        $resultado.=$Hinh;
        $resultado.='<hr>';
     $resultado.='<h2 class="mb-2">'.$invitado['Inv_nom'].'</h2>';
     $resultado.='<h3 class="text-muted mb-4">'.$invitado['Inv_pro'].'</h3>';
     $resultado.='<h3 class="text-danger">'.$conteo.'</h3>';*/
    echo $resultado;

  }
  else{
    echo '<div class="col-sm-12><h3 class="text-danger">No se encuentra</h3></div>';
  }

}else{
  echo '<div class="col-sm-12><h3 class="text-succes">No se encuentra acreditado a este Evento</h3></div>';
}
//echo json_encode($data);
}
else{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}

?>
