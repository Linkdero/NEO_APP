
<?php
if (function_exists('verificar_session') && verificar_session()){
/*

  $usuario=acreditacion::get_usuario($_SESSION['Usu_id']);
  $procedencias = acreditacion::contar_personas_por_puerta($_SESSION['Evento'],$_SESSION['Punto']);
  $procedencias_ = acreditacion::contar_personas_adentro($_SESSION['Evento']);

  $personas = 0;
  foreach($procedencias_ AS $p){
    if($p['tipo_registro']==0){
      $personas+=1;
    }else
    if($p['tipo_registro']==1)
    {
      $personas-=1;
    }
  }

  $total = 0;
  foreach($procedencias_ AS $p){
    $salida=acreditacion::encontrar_salida_de_invitado($p['Eve_id'],$p['Inv_id'],1);
    $u_entrada=acreditacion::encontrar_salida_de_invitado($p['Eve_id'],$p['Inv_id'],0);


    if($p['tipo_registro']==0 && $p['Pnt_id']==$_SESSION['Punto']){
      echo $p['Tra_num'].' | ';
      echo $total;
      $total+=1;
      echo ' + 1';
    }else
    if($p['tipo_registro']==1 && $salida['Pnt_id']==$u_entrada['Pnt_id'] && $p['Pnt_id']==$_SESSION['Punto']){

      echo $p['Tra_num'].' | ';
      if($total>0){
        echo $total;
        $total-=1;
        echo ' - 1  igual';
      }
    }else
    if($p['tipo_registro']==1 && $salida['Pnt_id']!=$u_entrada['Pnt_id'] && $u_entrada['Pnt_id']==$_SESSION['Punto'])
    {
      echo $p['Tra_num'].' | ';
      if($total>0){
        echo $total;
        $total-=1;
        echo ' - 1 diferente ';
      }
    }
    echo '= '.$total.'<br>';
      /*if($p['tipo_registro']==1)
      {
        echo 'mensaje<br><br>';

      }
    }else{

    }*/


  //}
  //echo $total;
/*
  $data=array(

    'evento'=>$usuario['Eve_nom'],
    'punto'=>$usuario['Pnt_des'],
    'fecha'=>date('d-m-Y'),
    'conteo'=>$total,
    'total'=>$personas
  );

  echo json_encode($data);*/
?>

<script src="inicio/js/funciones.js"></script>
<script src="inicio/js/source_table.js"></script>

<!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap4.min.css">
      <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
-->


<script>
$(document).ready(function(){
  get_usuario();
  //get_last_5_ingresos();


});

</script>
<?php

?>

<div class="u-content">


  <div class="u-body">
    <?php

    ?>
    <!-- Doughnut Chart -->

    <!-- End Overall Income -->

    <!-- Current Projects -->
    <div class="row">
      <!-- Current Projects -->
      <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100 overflow-hidden ">
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Datos del Evento</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <!--<li class="list-inline-item">
                <span class="link-muted h3" onclick="get_usuario()" data-toggle="tooltip" title="Recargar">
                  <i class="fa fa-sync"></i>
                </span>
              </li>-->

            </ul>
            <!-- End Card Header Icon -->
          </header>

          <div class="card-body p-0 animacion_right_to_left">
            <div class="list-group list-group-flush">
              <div class="col-sm-12 text-right" style="position:absolute;z-index:5555">
                <h2 id="cargando" class="fa fa-sync fa-spin text-right text-success" style="z-index:555;margin-top:15px;margin-left:-12px"></h2>
              </div>
               <!-- Notification -->
               <span class="list-group-item list-group-item-action">
                 <div class="media align-items-center">
                   <img class="img-fluid u-avatar--sm mx-auto mb-2" src="./assets/img/brands-sm/location-pin.svg">

                   <div class="media-body">
                     <div class="d-flex align-items-center" style="padding-left:15px">
                       <h4 id="evento" class="mb-0  data_ font-weight-bold"></h4>

                     </div>


                   </div>
                 </div>
               </span>
               <span class="list-group-item list-group-item-action">
                 <div class="media align-items-center">
                   <img class="img-fluid u-avatar--sm mx-auto mb-2" src="./assets/img/brands-sm/exit.svg">

                   <div class="media-body">
                     <div class="d-flex align-items-center" style="padding-left:15px">
                       <h4 id="puerta" class="mb-0  data_ font-weight-bold"></h4>

                     </div>


                   </div>
                 </div>
               </span>
               <span class="list-group-item list-group-item-action">
                 <div class="media align-items-center">
                   <img class="img-fluid u-avatar--sm mx-auto mb-2" src="./assets/img/brands-sm/calendar.svg">

                   <div class="media-body">
                     <div class="d-flex align-items-center" style="padding-left:15px">
                       <h4 id="fecha" class="mb-0  data_ font-weight-bold"></h4>

                     </div>


                   </div>
                 </div>
               </span>
             </div>

          </div>

          <footer class="card-footer">
            <div class="form-group mb-4">
              <label for="email">Código de Barra</label>
              <span class="form-icon-wrapper">
                <span class="form-icon form-icon--left">
                  <i class="fa fa-barcode form-icon__item"></i>
                </span>

              <input id="bar_code" class="form-control form-icon-input-left" autofocus name="bar_code" type="text" placeholder="Código" autocomplete="off" onchange="get_acreditacion();">
            </div>
          </footer>
        </div>
      </div>
      <!-- End Current Projects -->

      <!-- Comments -->
      <div class="col-md-6">
        <div class="card h-100 " style="height:300px">
          <header class="card-header d-flex align-items-center">
            <h2 class="h3 card-header-title">Información Importante</h2>

            <!-- Card Header Icon -->
            <ul class="list-inline ml-auto mb-0">
              <!--<li class="list-inline-item" data-toggle="tooltip" title="Totales">
                <span class="link-muted h3" data-toggle="modal" data-target="#modal-remoto" href="inicio/php/front/get_totales_evento.php<?php echo '?evento='.$_SESSION['Evento'];?>">
                  <i class="fa fa-chart-line"></i>
                </span>
              </li>
              <li class="list-inline-item">
                <span class="link-muted h3" onclick="reload_acreditaciones()" data-toggle="tooltip" title="Recargar">
                  <i class="fa fa-sync"></i>
                </span>
              </li>-->

            </ul>
            <!-- End Card Header Icon -->
          </header>





                  <!-- Comment -->
                  <div id="div_invitado" >
                    <video src="assets/svg/higiene2.mp4" controls style="width:100%" autoplay loop>
  Tu navegador no implementa el elemento <code>video</code>.
</video>
                    <!--<table id="tb_acreditaciones" class="table table-striped" width="100%">
                      <thead>
                        <th>foto</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Hora</th>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>-->

              <!-- End Tabs Content -->

          </div>


        </div>
      </div>
      <!-- End Comments -->
    </div>
    <!-- End Current Projects -->
  </div>
  <?php
  }
  else{
    header("Location: index");
  }

  ?>
