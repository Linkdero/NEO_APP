<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session()){
  if(usuarioPrivilegiado_acceso()->accesoModulo(7847)){
    date_default_timezone_set('America/Guatemala');
    include_once '../../back/functions.php';
    $redes = array();
    $id = null;
    if (! empty($_POST)){
      header("Location: principal?ref=_600");
    }else{
      $redes = noticia::get_redes_sociales();
      $departamentos = noticia::get_departamentos();
    }
    if(array_key_exists("id", $_REQUEST)){
      $id = $_REQUEST["id"];
      $noticia = noticia::get_noticia_by_id($id);
      $update = true;
    }else{
      $update = false;
    }
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <script src="noticias/js/funciones.js"></script>
    </head>
  <body>
    <div class="modal-header">
        <?php if($update): ?>
          <h5 class="modal-title">Modificar Noticia</h5>
        <?php else: ?>
          <h5 class="modal-title">Nueva Noticia</h5>
        <?php endif; ?>
      
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
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <?php if($update): ?>
                  <input type="hidden" class=" form-control " id="id" name="id" value = <?php echo $noticia[0]["id_noticia"];?>> 
                  <input type="hidden" class=" form-control " id="id_municipio" name="id_municipio" value = <?php echo $noticia[0]["id_municipio"];?>>  
              <?php endif; ?>
              <div class="">
                <div class="row">
                  <label for="alias ">Alias*</label>
                  <div class=" input-group  has-personalizado" >
                    <?php if($update): ?>
                      <input type="text" class=" form-control " id="alias" name="alias" placeholder="@alias" required autocomplete="off" value = <?php echo $noticia[0]["noticias_alias"];?>>
                    <?php else: ?>
                      <input type="text" class=" form-control " id="alias" name="alias" placeholder="@alias" required autocomplete="off"/>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="usuario">Usuario*</label>
                  <div class=" input-group  has-personalizado" >
                    <?php if($update): ?>
                      <input type="text" class=" form-control " id="user" name="user" placeholder="@usuario" required autocomplete="off" value = "<?php echo $noticia[0]["noticia_usuario"];?>">
                    <?php else: ?>
                      <input type="text" class=" form-control " id="user" name="user" placeholder="@usuario" required autocomplete="off">
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <div class="">
                <div class="row">
                  <label for="red_social">Red Social*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="form-control" id="red_social" name="red_social" required>
                      <?php
                        foreach($redes AS $r){
                          if($update && $r['id_item'] == $noticia[0]["noticia_fuente"]){
                            echo '<option value="'.$r['id_item'].'" selected>'.$r['descripcion'].'</option>';
                          }else{
                            echo '<option value="'.$r['id_item'].'">'.$r['descripcion'].'</option>';
                          }
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
                      <?php
                        foreach($departamentos AS $departamento){
                          if($update && $departamento['id_departamento'] == $noticia[0]["id_departamento"]){
                            echo '<option value="'.$departamento['id_departamento'].'" selected>'.$departamento['nombre'].'</option>';
                          }else{
                            echo '<option value="'.$departamento['id_departamento'].'">'.$departamento['nombre'].'</option>';
                          }
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
                    <?php if($update): ?>
                      <input type="text" class="form-control " id="url_noticia" name="url_noticia" placeholder="https://www.myred.com" required autocomplete="off" value = <?php echo $noticia[0]["noticia_url"];?> >
                    <?php else: ?>
                      <input type="text" class="form-control " id="url_noticia" name="url_noticia" placeholder="https://www.myred.com" required autocomplete="off"/>
                    <?php endif; ?>
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
                      <?php if($update): ?>
                        <?php if($noticia[0]["noticia_categoria"] == "1"): ?>
                          <option value="1" selected>Positiva</option>
                          <option value="2">Negativa</option>
                        <?php else: ?>
                          <option value="1">Positiva</option>
                          <option value="2" selected>Negativa</option>
                        <?php endif; ?>
                      <?php else: ?>
                        <option value="1">Positiva</option>
                          <option value="2">Negativa</option>
                      <?php endif; ?>
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
                  <label for="observaciones">Observaciones*</label>
                  <div class=" input-group  has-personalizado" >
                    <?php if($update): ?>
                        <textarea rows="5" type="text" class=" form-control " id="observaciones" name="observaciones" placeholder="Observaciones" required autocomplete="off" > <?php echo $noticia[0]["noticia_observaciones"];?> </textarea>
                    <?php else: ?>
                        <textarea rows="5" type="text" class=" form-control " id="observaciones" name="observaciones" placeholder="Observaciones" required autocomplete="off"></textarea>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-block btn-sm btn-info" onclick="save_noticia()"><i class="fa fa-save"></i> Guardar</button>
      </form>
    </div>

    <script>
      get_municipios();
      $("#departamento").change(function() {
        get_municipios();
      });
    </script>
    <?php if($update): ?>
      <script>
        
      </script>
    <?php endif; ?>
    <?php 
  }else{
    include('../inc/401.php');
  }
}else{
  header("Location: index");
}
?>
