<?php
include_once '../../../inc/functions.php';
include_once 'functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()== true){
date_default_timezone_set('America/Guatemala');
$data = array();
$i=$_POST['invitado'];


?>
<script>
$(document).ready(function(){
  $('#agregar_button').addClass('border');

});

</script>
<?php
$invitado=acreditacion::get_invitado($_SESSION['Evento'],$i,1);

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
    <div class="card">
    <header class="card-header bg-light">
    <div class="row">
    <div class="col-sm-4">
    <div class="media align-items-center">
      '.$conteo.'
    </div>
    </div>
    <div class="col-sm-8 text-center">
    <h3 class="pt-sm-2 pb-1 mb-0 text-nowrap text-dark font-weight-bold">'.$invitado['Inv_nom'].'</h3>
    <p class="mb-0 text-dark">'.$invitado['Inv_pro'].'</p>
    </div>
    </div>


    </header>'; /*'




										<div class="card-body">
                    <div class="col-sm-12" >
                    <div class="text-center  ">

                    <h3 class="pt-sm-2 pb-1 mb-0 text-nowrap text-dark font-weight-bold">'.$invitado['Inv_nom'].'</h3>
                    <p class="mb-0 text-dark">'.$invitado['Inv_pro'].'</p>
                    </div></div>

											<!--<div class="progress" style="height: 6px; border-radius: 3px;">
												<div class="progress-bar bg-secondary" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
											</div>-->
										</div>
									</div>
    ';

    /*$resultado.='
    </div>
    </div>
    </div>
    <div class="col-sm-12 bg-soft-info" >
    <div class="text-center  ">
    <br>
    <h3 class="pt-sm-2 pb-1 mb-0 text-nowrap text-dark font-weight-bold">'.$invitado['Inv_nom'].'</h3>
    <p class="mb-0 text-dark">'.$invitado['Inv_pro'].'</p>
    <div class="text-muted"><small></small></div>
    <div class="mt-2">
    '.$conteo.'
    </div><br>
    </div>
    </div>
    </div>
    </div>*/

    $resultado.='
    <footer class="card-footer ">
    <div class="form-group">
    <span class="form-icon-wrapper">
      <span class="form-icon form-icon--left">
        <i class="fa fa-edit form-icon__item"></i>
      </span>
    <textarea id="observaciones" class="form-control form-icon-input-left" rows="2" autofocus></textarea>
    </div>
    <div class="row">
    <div class="col-sm-6 text-left">
    <div class="btn-group btn-group-sm" role="group">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <div class="btn-group mr-2" role="group" aria-label="Second group">


    </div>
    </div>
    </div>
    </div>
    <div class="col-sm-6 text-right">
    <button id="agregar_button" class="btn  btn-info" onclick="agregar_asistencia('.$invitado['Inv_id'].')" autofocus><i class="fa fa-save"></i> Guardar</button>';
    //<button class="btn  btn-danger" onclick="cerrar_modal()"><i class="fa fa-times"></i> Cancelar</button>
    echo '</div>
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
  ?>
  <script>

  setTimeout(function(){
//do what you need here
cerrar_modal();
}, 1000);
  </script>
  <?php
  echo '
  <div class="card-body  ">

  <div class="row">
  <div class="col-sm-8">
  <h4 class="text-succes text-danger font-weight-bold">No se encuentra acreditado a este Evento</h4>
  </div>
  <div class="col-sm-4 text-right">
  <button id="cancel_button" class="btn  btn-danger" onclick="cerrar_modal()" autofocus><i class="fa fa-times"></i> Cancelar</button>
  </div>
  </div>

  </div>


  ';

}
//echo json_encode($data);
}
else{
  echo "<script type='text/javascript'> window.location='principal.php'; </script>";
}

?>
