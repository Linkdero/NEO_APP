<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $partida=null;

    if ( !empty($_GET['partida'])) {
      $partida = $_REQUEST['partida'];
    }

    if (!empty($_POST)){
      header("Location: index.php?ref=_101");
    }else{
      //$persona=empleado::get_empleado_by_id($id_persona);


    }


    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/source_modal.js"></script>
      <script src="empleados/js/plaza_vue.js"></script>
      <script src="assets/js/plugins/vue/vue.js"></script>
      <script src='assets/js/plugins/vue/axios-master/dist/axios.min.js'></script>
      <script>


      </script>
    </head>
    <body>
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Historial de la Plaza </h5> " <?php echo $partida; ?> "
        <ul class="list-inline ml-auto mb-0">






          <li class="list-inline-item" data-toggle="tooltip" title="Cerrar">
            <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
              <i class="fa fa-times"></i>
            </span>
          </li>
        </ul>
      </div>
      <div class="modal-body">

          <input type="text" id="partida" value="<?php echo $partida?>" hidden></input>
          <input type="text" id="id_plaza" value="<?php echo $partida?>" hidden></input>

          <div id="plaza_app">

            <div class="timeline animated fadeInUp" id="plaza_detalle" style="margin-left:-35.2rem; width:120rem; ">
              <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
                <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
                  <h3 class=" text-light">Puesto: {{plaza_detalle.cargo}}</h3>
                  <p>
                    <div class="row">
                      <div class="col-sm-8">
                        <div class="col-sm-12">
                          <h5>1. - {{plaza_detalle.secretaria_n}}</h5>
                          <h5>2. - {{plaza_detalle.subsecretaria_n}}</h5>
                          <h5>3. - {{plaza_detalle.direccion_n}}</a></li>
                          <h5>4. - {{plaza_detalle.subdireccion_n}}</h5>
                          <h5>5. - {{plaza_detalle.departamento_n}}</h5>
                          <h5>6. - {{plaza_detalle.seccion_n}}</h5>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="pw_content">
                          <div class="pw_header">
                            <h5 class="text-muted">Codigo Plaza: {{plaza_detalle.cod_plaza}}</h5>
                          </div>
                          <div class="pw_header">
                            <h5 class="text-muted">Codigo Puesto: {{plaza_detalle.cod_puesto}}</h5>
                          </div>

                          <div class="pw_header">
                            <span>{{plaza_detalle.sueldo}}</span>
                            <small class="text-muted">Sueldo + Bonos</small>
                            <small class="text-success">{{plaza_detalle.estado}}</small>
                          </div>
                      </div>
                    </div>



                    </div>
                  </p>
                </div>
              </div>
            </div>

          </div>

					<div class="" id="datos">
            <table id="tb_historial_plaza" class="table table-sm table-bordered table-striped" width="100%">
              <thead>
                <th class="text-center">Fecha Final</th>
                <th class="text-center">Empleado</th>
                <th class="text-center">Fecha Final</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>

      </div>
    </body>

  <?php }
  else{
    include('inc/401.php');
  }
}
else{
  header("Location: index");
}
?>
