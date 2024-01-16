<?php
include_once '../inc/functions.php';
include_once '../reportes/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_Nivel()->accesoNivel(4)){
    date_default_timezone_set('America/Guatemala');
    if ( !empty($_POST)){
      header("Location: principal");
    }else{
      $director=reporte::get_last_director_by_date(date('Y-m-d'));
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
    <h5 class="modal-title">Nuevo Director</h5>
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
    <form class="validation_nuevo_director">
      <input id="director_id" value="<?php echo $director['Gen_idint']?>" hidden></input>
      <div class="form-group mb-4">
        <div>
          <label for="defaultInput">Director en Turno</label>
          <span class="form-icon-wrapper">
            <span class="form-icon form-icon--left">
              <i class="fa fa-user-circle form-icon__item"></i>
            </span>
            <input id="director" value="<?php echo $director['Gen_dirtur']?>" class="form-control form-icon-input-left" type="text" placeholder="Director en Funciones" aria-describedby="emailHelp" required  autocomplete="off"></input>
          </span>
        </div>
      </div>

      <div class="form-group mb-4">
        <div>
          <label for="inputLeftIcon">Novedades</label>
          <span class="form-icon-wrapper">
            <span class="form-icon form-icon--left">
              <i class="fa fa-edit form-icon__item"></i>
            </span>
            <textarea id="novedad" class="form-control form-icon-input-left" type="text" placeholder="Escriba las novedades del día" aria-describedby="emailHelp" rows="3" required  autocomplete="off"><?php echo $director['Gen_sitact']?></textarea>
          </span>
        </div>
      </div>

        <button class="btn btn-block btn-sm btn-primary" onclick="nuevo_director()"><i class="fa fa-save"></i> Guardar</button>

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
