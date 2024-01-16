<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../../back/functions.php';

sec_session_start();
if (function_exists('verificar_session') && verificar_session()):
  if(usuarioPrivilegiado_acceso()->accesoModulo(1163))://1163 módulo recursos humanos
        $id = null;
        $year = null;
        $version = null;

        if ( !empty($_GET['id'])) {
          $id = $_REQUEST['id'];
        }
        if ( !empty($_GET['year'])) {
          $year = $_REQUEST['year'];
        }
        if ( !empty($_GET['version'])) {
          $version = $_REQUEST['version'];
        }

        if ( !empty($_POST)){
            //User::usuarioNuevo();
            //User::update_empleado_datos_id($id);
            header("Location: index.php?ref=_35");
        }else{
            $persona = User::get_empleado_datos_id($id);
            $carnet = User::get_carnet_by($id,$year,$version);
            $firma = User::get_firma_by_user($id);


        }
        ?>
        <link rel="stylesheet" href="../assets/css/oneui.css">
        <style>
            @page {
              size:  landscape;
              margin: 0mm 0mm 0mm 0mm;

            }
            @media print {
                html, body {

                }

                .pagebreak {
                    page-break-before: always;
                }
                body{
                  margin-top: -98px;
                  margin-left: -160px;
                  background-color: #fff;
                  transform: scale(.37);

                }
                .firma{
                  display:block;
                  margin:auto;
                }

                .titulo_car1{
                  font-size: 40px;

                  color:#AAB2BD;

                }
                .titulo_car{
                  font-size: 32px;

                  color:#AAB2BD;

                }
                /*@font-face {
                    font-family: 'Gotham-LightIta';
                    src: url(../assets/fonts/fuentes/Gotham-LightIta.otf) format("opentype");
                    font-weight: normal;
                }*/

                .titulo_car_n{
                  font-size: 28px;
                  font-weight: bold;

                }


            }
        </style>

        <div class="carnet">


          <table width="1000" border="0" style=" color:#000;z-index:5555" cellspacing="0" cellpadding="0">
            <tr>
              <th align="center">
                <img src="../usuarios/fotos/carnet/fondo_carnet.png" style="height:605px;margin-left:-32px"></img>
              </th>
            </tr>
              <tr >
                <th align="center">
                  <div class="img-contenedor_profile_carnet" style="margin-top:-400px;margin-left:80px;width:320px;height:365px;box-shadow: 0 0 0 0.3rem rgb(0, 108, 135);">
                  <?php

                  if($carnet['fotografia']!='')
                  {
                    echo '<img class="" src="../usuarios/fotos/'.$carnet['fotografia'].'"></img>';
                  }
                  else{
                    echo '<img class="img_foto_user_profile2_carnet" src="../usuarios/fotos/user.png"></img>';
                  }?>
                </div>
                </th>
                <th align="center">
                  <div style="margin-top:-400px;margin-left:-630px">
                    <h1 class="text-primary titulo_car_n" style="font-size:34px"><?php echo $persona['user_pref'].' '.$persona['user_nm1']. ' '.  $persona['user_nm2'];?><br> <?php echo $persona['user_ap1']. ' '.  $persona['user_ap2'];?></h1>
                    <br>
                    <h1 style="max-width:500px" class="text-black-op titulo_car_n" style="font-size:28px"><?php echo $carnet['user_puesto']?></h1>
                    <br>
                    <h1 class="text-black-op titulo_car_n" style="font-size:28px">CUI - <?php echo $persona['user_cui']?></h1>
                    <br>
                    <?php
                    //echo '<img src="data:image/jpg;base64,'.base64_encode($firma['user_firma']).'" height="60" width="75" class="img-thumbnail" />';
                    if($firma!=''){
                      if($id==70 || $id==194 || $id==195){

                        echo "<img src='data:image/jpg;base64,".$firma."' class='firma' style='position:absolute;display:block;margin:auto;margin-left:11em;margin-top:-50px'/>";
                      }
                      else{
                        echo "<img src='data:image/jpg;base64,".$firma."' class='firma' style='position:absolute;display:block;margin:auto;margin-left:11em'/>";
                      }

                    }else{
                      echo 'No se ha subido la firma';
                    }


                    ?>

                  </div>
                </th>

              </tr>
              <tr>
                <th align="center">
                  <h1 class="text-white text-center titulo_car_n font-w900" style="margin-top:-52px;margin-left:100px;font-weight:900;font-size:40px"><?php echo $carnet['year'].''.$carnet['renglon'].''.$carnet['u_i'].''.$carnet['ver']?></h1>
                </th>
              </tr>
          </table>


        </div>
        <div class="pagebreak"></div>
        <div style="margin-top:-169px">
          <img src="../usuarios/fotos/carnet/fondo_carnet_b.png" style="height:610px;margin-left:14px;"></img>
          <div style="margin-top:-120px;margin-left:28px;position:absolute;width:70em">
            <div class="col-xs-12" >
              <div class="col-sm-6" style="float:left;width: 20%;">
                <h6 class="text-primary text-left titulo_car_n" style="font-size:42px" ><?php echo $carnet['year'].''.$carnet['renglon'].''.$carnet['u_i'].''.$carnet['ver']?></h6>
              </div>
              <div class="col-sm-6 " style="float:left;width: 80%;text-align:right">
                <h6  class="text-primary titulo_car_n text-right" ><?php echo $persona['user_nm1']. ' '.  $persona['user_nm2'];?> <?php echo $persona['user_ap1']. ' '.  $persona['user_ap2'];?></h6>
              </div>
              <div class="col-sm-6 " style="float:left;width: 100%;text-align:right;margin-top:-20px">
                <h6 style="margin-left: 10em " class="text-danger titulo_car_n text-right"><?php echo 'Vence: '. fechaCastellano($carnet['fecha_vencimiento'])?></h6>
              </div>



            <br>

          </div>
        </div>






        <script type="text/javascript">
        window.print();
        setTimeout(window.close, 500);
        </script>
        <?php
    else :
        echo include(unauthorized());
    endif;
else:
    header("Location: index.php");
endif;
?>
