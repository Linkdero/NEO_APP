
<?php
include_once '../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){

  if ( !empty($_POST)){
        header("Location: principal.php");
      }else{
        $evento=$_GET['evento'];
      }


?>

<script src="inicio/js/funciones.js"></script>
<script>
$(document).ready(function(){
  get_totales_por_evento(<?php echo $evento?>);
});
</script>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <script type="text/javascript">
  </script>
  <script src="inicio/js/validaciones.js"></script>
</head>
<body>
  <div class="modal-header">
    <h3 class="modal-title">Totales del Evento</h3>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item">
        <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>

  </div>
  <div class="modal-body">

    <div id="totales">
      <span class=" list-group-item-action">
        <div class="media align-items-center">
          <img class="img-fluid u-avatar--sm mx-auto mb-2" src="./assets/img/brands-sm/exit.svg">

          <div class="media-body">
            <div class="align-items-center" style="padding-left:15px">
              <h4 id="puerta_2" class="mb-0  data_ font-weight-bold"></h4>

            </div>


          </div>
        </div>
      </span>
    </div>



  </div>
  <?php
  }
  else{
    header("Location: index.php");
  }

  ?>
