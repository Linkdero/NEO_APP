<?php
include_once '../inc/functions.php';
include_once '../reportes/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_Nivel()->accesoNivel(3)){
    date_default_timezone_set('America/Guatemala');
    if ( !empty($_POST)){
      header("Location: principal");
    }else{
      $novedad=reporte::get_last_novedad_by_direccion_by_date(date('Y-m-d'),$_SESSION['direccion']);
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script type="text/javascript">
  </script>
  <script src="inicio/js/validaciones.js"></script>
</head>
<body>
  <div class="modal-header">
    <h5 class="modal-title">Nueva Novedad</h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item">
        <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>

  </div>
  <div class="modal-body">
    <?php
    if(date('H:i:s')>'10:00:00'){
      echo '<h4 class="text-danger">Horario fuera de rango</h4>';
    }
  else{

    ?>
    <input id="id_nueva_novedad" type="text" value="<?php echo $novedad['Est_idint']?>" hidden></input>
    <form class="validation_nueva_novedad">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Servicio</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_servicio" value="<?php echo $novedad['Est_ser']?>" class="form-control form-icon-input-left" type="number" placeholder="Servicio" aria-describedby="emailHelp" required>
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Descanso</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_descanso" value="<?php echo $novedad['Est_des']?>" class="form-control form-icon-input-left" type="number" placeholder="Descanso" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Vacaciones</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_vacaciones" value="<?php echo $novedad['Est_vac']?>" class="form-control form-icon-input-left" type="number" placeholder="Vacaciones" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Suspenciones IGSS</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_igss" value="<?php echo $novedad['Est_sus']?>" class="form-control form-icon-input-left" type="number" placeholder="Suspenciones IGSS" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Hospitalizados</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_hospitalizados" value="<?php echo $novedad['Est_hos']?>" class="form-control form-icon-input-left" type="number" placeholder="Hospitalizados" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Permiso</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_permiso" value="<?php echo $novedad['Est_per']?>" class="form-control form-icon-input-left" type="number" placeholder="Permiso" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Faltando</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_faltando" value="<?php echo $novedad['Est_fal']?>" class="form-control form-icon-input-left" type="number" placeholder="Faltando" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Capacitación</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_capacitacion" value="<?php echo $novedad['Est_cap']?>" class="form-control form-icon-input-left" type="number" placeholder="Capacitación" aria-describedby="emailHelp">
            </span>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Otros Destinos</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_otros" value="<?php echo $novedad['Est_otr']?>" class="form-control form-icon-input-left" type="number" placeholder="Otros Destinos" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Casos Especiales</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_casos_especiales" value="<?php echo $novedad['Est_cas']?>" class="form-control form-icon-input-left" type="number" placeholder="Casos Especiales" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="defaultInput">Asesores</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-user-circle form-icon__item"></i>
              </span>
              <input id="id_asesores" value="<?php echo $novedad['Est_ase']?>" class="form-control form-icon-input-left" type="number" placeholder="Asesores" aria-describedby="emailHelp">
            </span>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group mb-4">
            <label for="inputLeftIcon">Novedades</label>
            <span class="form-icon-wrapper">
              <span class="form-icon form-icon--left">
                <i class="fa fa-edit form-icon__item"></i>
              </span>
              <textarea id="id_novedades" class="form-control form-icon-input-left" type="text" placeholder="Novedades del día" aria-describedby="emailHelp" rows="3"><?php echo $novedad['Est_nov']?></textarea>
            </span>
          </div>
        </div>
      </div><!--fin row-->



        <button class="btn btn-block btn-sm btn-primary" onclick="nueva_novedad()"><i class="fa fa-save"></i> Guardar</button>

    </form>
    <?php
  }
  ?>

  </div>
<?php }
else{
  include('../inc/401.php');
}
}
else{
  header("Location: index");
}
?>
