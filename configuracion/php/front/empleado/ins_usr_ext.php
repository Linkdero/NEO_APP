<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7851)){
    date_default_timezone_set('America/Guatemala');
    include_once '../../back/functions.php';
  ?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="noticias/js/funciones.js"></script>
  </head>
  <body>
  <div class="modal-header">
    <h5 class="modal-title">Nuevo usuario extensión</h5>
    <ul class="list-inline ml-auto mb-0">
      <li class="list-inline-item">
        <span class="link-muted h3" class="close" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i>
        </span>
      </li>
    </ul>

  </div>
  <div class="modal-body">

    <form class="validation_nueva_noticia">
      <!-- <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="alias ">Alias*</label>
                <div class=" input-group  has-personalizado" >
                  <input type="text" class=" form-control " id="alias" name="alias" placeholder="@alias" required autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="usuario">Usuario*</label>
                <div class=" input-group  has-personalizado" >
                  <input type="text" class=" form-control " id="usuario" name="usuario" placeholder="@usuario" required autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="red_social">Red Social*</label>
                <div class=" input-group  has-personalizado" >
                  <select class="form-control" id="red_social" name="red_social" required>
                    <?php
                      foreach($redes AS $r){
                        echo '<option value="'.$r['id_item'].'">'.$r['descripcion'].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="departamento">Departamento*</label>
                <div class=" input-group  has-personalizado" >
                    <select class="form-control" id="departamento">
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="municipio">Municipio*</label>
                <div class=" input-group  has-personalizado" >
                    <select class="form-control" id="municipio">
                    </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="url_noticia">URL*</label>
                <div class=" input-group  has-personalizado" >
                  <input type="text" class=" form-control " id="url_noticia" name="url_noticia" placeholder="url de la noticia" required autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="categoria">Categoría*</label>
                <div class=" input-group  has-personalizado" >
                  <select class="form-control" id="categoria" name="categoria" required>
                    <option value="1">Positiva</option>
                    <option value="2">Negativa</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div> -->

        <!-- <div class="col-sm-12">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="usuario">Observaciones*</label>
                <div class=" input-group  has-personalizado" >
                  <textarea rows="5" type="text" class=" form-control " id="observaciones" name="observaciones" placeholder="Observaciones" required autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      <button class="btn btn-block btn-sm btn-info" onclick=""><i class="fa fa-save"></i> Guardar</button>
    </form>
  </div>

  <script>
    $.ajax({
      type: "POST",
      url: "",
      dataType: 'html',
      data: { },
      success:function(data) {

      }
    });

  </script>
  <?php 
  }else{
    include('../inc/401.php');
  }
}else{
  header("Location: index");
}
?>
