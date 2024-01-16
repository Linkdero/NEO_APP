<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163)){//1163 módulo recursos humanos
    $id_persona=null;
    include_once '../../back/functions.php';
    //$permiso=array();
    $id_persona = $_POST['id_persona'];

    $foto = empleado::get_empleado_fotografia($id_persona);
    $encoded_image = base64_encode($foto['fotografia']);
    $Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";

    ?>

    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="empleados/js/cargar.js"></script>
      <script>
      $(function(){
        get_empleado_datos();
      });
      </script>
    </head>
    <body>


					<div class=" mb-4">
						<div class="">
							<div class="row">
								<div class="col-md-4 border-md-right border-light text-center" style="margin-left:-15px">
                  <div class="img-contenedor_profile" style="border-radius:50%">
                    <?php echo $Hinh;?>
                  </div>

									<h4 class="mb-2 data_ font-weight-bold" id="nombres"></h4>
                  <h4 class="mb-2 data_ font-weight-bold" id="apellidos" style="margin-top:-8px"></h4><br>
									<h5 class="text-muted mb-4 data_" id="profesion"></h5>

                  <div class="row">
                    <div class="col-sm-12">
                      <small class="text-muted">Tipo de Contrato: </small>
                      <h4 id="tipo_contrato" class="data_"></h4>
                    </div>
                    <div class="col-sm-12">
                      <small class="text-muted">Observaciones: </small>
                      <h4 id="observaciones" class="data_"></h4>
                    </div>
                  </div>






								</div>

								<div class="col-md-8" >
                  <div class="row">
                    <div class="col-sm-4"><h3 class="card-title"  style="margin-left:10px"><strong>Acerca de mi</strong></h3>
                    </div>
                    <div class="col-sm-6">
                      <span class="text-muted">
                        <i class="fa fa-envelope mr-2"></i><span id="email"></span>
                      </span>
                    </div>
                    <div class="col-sm-2 text-right">




                    </div>
                  </div>


                  <div class="row">
                    <div class="col-sm-5">
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-user-friends form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Estado Civil: </small>
                          <h4 id="estado_civil" class="data_"></h4>
                        </div>

                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-venus-mars form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Género: </small>
                          <h4 id="genero" class="data_"></h4>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-notes-medical form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">IGGS: </small>
                          <h4 id="igss" class="data_"></h4>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-church form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Religión: </small>
                          <h4 id="religion" class="data_"></h4>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-calendar-check form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Fecha Nacimiento: </small>
                          <h4 id="fecha_nacimiento" class="data_"></h4>
                        </div>
                      </div>


                    </div>

                    <div class="col-sm-7">
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-address-card form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">NIT: </small>
                          <h4 id="nit" class="data_"></h4>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-address-card form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">NISP: </small>
                          <h4 id="nisp" class="data_"></h4>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-arrow-circle-right form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Procedencia: </small>
                          <h4 id="procedencia" class="data_"></h4>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-user-tie form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Tipo Servicio: </small>
                          <h4 id="tipo_personal" class="data_"></h4>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-map-marker-alt form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Lugar de Nacimiento: </small>
                          <h4 id="municipio" class="data_"></h4>
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <span class="form-icon form-icon--left">
                          <i class="fa fa-map-marker-alt form-icon__item icon_perfil_dato"></i>
                        </span>
                        <div class="perfil_dato">
                          <small class="text-muted">Departamento: </small>
                          <h4 id="departamento" class="data_"></h4>
                        </div>
                      </div>
                    </div>


                  </div>
                  <br><br><br>


									<div class="row">
										<div class="col-lg-4 mb-5 mb-lg-0">
											<h4 class="h3 mb-3">Profile Rating</h4>

											<div class="mb-1">
												<span class="font-size-44 text-dark">4.8</span>
												<span class="h1 font-weight-light text-muted">/ 5.0</span>
											</div>

											<p class="text-muted mb-0">245 Reviews</p>
										</div>

										<div class="col-lg-8">
											<h4 class="h3 mb-3">Módulos asignados</h4>

											<div class="d-flex flex-wrap align-items-center">
												<span class="bg-light text-muted rounded py-2 px-3 mb-2 mr-2">Tag</span>
												<span class="bg-light text-muted rounded py-2 px-3 mb-2 mr-2">Web Design</span>
												<span class="bg-light text-muted rounded py-2 px-3 mb-2 mr-2">HTML5</span>
												<span class="bg-light text-muted rounded py-2 px-3 mb-2 mr-2">CSS</span>
												<span class="bg-light text-muted rounded py-2 px-3 mb-2 mr-2">Marketing</span>
												<span class="bg-light text-muted rounded py-2 px-3 mb-2 mr-2">JavaScript</span>
											</div>
										</div>
									</div>
								</div>
							</div>
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
