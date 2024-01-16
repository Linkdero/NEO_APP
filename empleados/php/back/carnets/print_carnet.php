<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if(function_exists('verificar_session') && verificar_session() == true) :
  include_once '../functions.php';
  $id_gafete = null;
  if ( !empty($_GET['id_gafete'])) {
    $id_gafete = $_REQUEST['id_gafete'];
  }

  if ( !empty($_POST)){
    //User::usuarioNuevo();
    //User::update_empleado_datos_id($id);
    header("Location: index.php?ref=_100");
  }else{
    $pdo = Database::connect_sqlsrv();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT  a.id_gafete,a.id_empleado,a.id_contrato,a.id_version,a.puesto,a.fecha_generado,a.fecha_vencimiento,
                    a.fecha_validado,a.fecha_baja,a.fecha_impreso,a.creado_por,a.baja_por,a.validado_por,a.impreso_por,
                    a.id_status,a.id_tipo_carnet,a.id_arma,a.id_fotografia,
            		ISNULL(c.primer_nombre,'')+' '+ISNULL(c.segundo_nombre,'')+' '+ISNULL(c.tercer_nombre,'')+' '+
            		ISNULL(c.primer_apellido,'')+' '+ISNULL(c.segundo_apellido,'')+' '+ISNULL(c.tercer_apellido,'') AS empleado,
            		e.nro_registro AS cui, h.descripcion AS tipo_sangre, i.descripcion AS tipo_carnet, d.fotografia
            		FROM rrhh_empleado_gafete AS a
            		LEFT JOIN rrhh_empleado AS b ON a.id_empleado = b.id_empleado
            		LEFT JOIN rrhh_persona AS c ON b.id_persona = c.id_persona
            		LEFT JOIN rrhh_persona_fotografia d ON  a.id_fotografia = d.id_fotografia
            		LEFT JOIN rrhh_persona_documentos e ON c.id_persona = e.id_persona AND e.id_tipo_documento = 101
            		LEFT JOIN rrhh_persona_complemento f ON c.id_persona = f.id_persona
            		LEFT JOIN rrhh_persona_condicion_fisica_medica g ON c.id_persona = g.id_persona AND g.id_categoria = 10
            		LEFT JOIN tbl_catalogo_detalle h ON g.id_valor = h.id_item
            		LEFT JOIN tbl_catalogo_detalle i ON a.id_tipo_carnet=i.id_item

            		WHERE a.id_gafete IN (?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($id_gafete));
    $carnets=$stmt->fetchAll();

    Database::disconnect_sqlsrv();
  }
?>
<html>
<head>
  <link rel="stylesheet" href="../../../../assets/css/theme.css">
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

    .titulo_car_n{
      font-size: 28px;
      font-weight: bold;
    }
    .outer {
      background-color: pink;
      position: absolute;
      display: inline-block;

    }

    .inner {
      font-size: 13px;
      font-color: #878787;
        position: relative;

        margin-top: -7px;
    }

    .rotate {
      writing-mode: vertical-rl;

      -webkit-transform: rotate(-180deg);
      -ff-transform: rotate(-180deg);
      transform: rotate(-180deg);
      width: 16px;  /* transform: rotate() does not rotate the bounding box. */
    }
  }
</style>
</head>
<body>
  <?php
  $conteo = count($carnets);
  $c2= $conteo;
  if($conteo > 0)
  {
    foreach ($carnets as $carnet){

      $encoded_image = base64_encode($carnet['fotografia']);
      $Hinh = "<img class='img-fluid mb-3' src='data:image/jpeg;base64,{$encoded_image}' > ";
  ?>
  <div class="carnet">
    <!-- inicio -->
    <table width="1000" border="0" style=" color:#000;z-index:5555" cellspacing="0" cellpadding="0">
      <tr>
        <th align="center">
          <img src="img/pagina01.jpg" style="height:605px;margin-left:0px"></img>
        </th>
      </tr>
      <tr>
        <th align="center">
          <div class="img-contenedor_profile_carnet" style="margin-top:-400px;margin-left:80px;width:320px;height:365px;box-shadow: 0 0 0 0.3rem rgb(0, 108, 135);">
            <?php
            if($carnet['id_fotografia']!='')
            {
              echo $Hinh;
            }
            else{
              //echo '<img class="img_foto_user_profile2_carnet" src="../usuarios/fotos/user.png"></img>';
            }?>
          </div>
        </th>
        <th align="center">
          <div style="margin-top:-400px;margin-left:-630px">
            <h1 class="text-primary titulo_car_n" style="font-size:34px"><?php echo $carnet['empleado']; ?></h1>
            <br>
            <br>
          </div>
        </th>
      </tr>
      <tr>
        <th align="center text-center" style="width:100%">
          <div style="margin-top:-100px; z-index:555">
            <h1 class="text-white text-center"><?php echo ($carnet['id_tipo_carnet']==8191)?$carnet['puesto']:$carnet['tipo_carnet']?></h1>
          </div>
        </th>
      </tr>
    </table>
  </div>

  <div style="margin-top:1px">
    <img src="img/pagina02.jpg" style="height:635px;margin-left:0px"></img>

    <div class="outer" style="margin-left:-300px" >
      <div class="inner rotate" style="height: :500px"><h1 style="" class=" text-danger text-center "><?php echo 'Válido hasta: '. fechaCastellano($carnet['fecha_vencimiento'])?></h1></div>
    </div>

    <div class="csstransforms text-center" style="margin-left:30px;margin-top:20px">
      <div class="col-sm-12 text-center" style="">

      </div>
      <!--<div class="col-xs-12" >
        <div class="col-sm-6" style="float:left;width: 20%;">
          <h6 class="text-primary text-left titulo_car_n" style="font-size:42px" ><?php echo $carnet['year'].''.$carnet['renglon'].''.$carnet['u_i'].''.$carnet['ver']?></h6>
        </div>
        <div class="col-sm-6 " style="float:left;width: 80%;text-align:right">
          <h6  class="text-primary titulo_car_n text-right" ><?php echo $persona['user_nm1']. ' '.  $persona['user_nm2'];?> <?php echo $persona['user_ap1']. ' '.  $persona['user_ap2'];?></h6>
        </div>

        <br>
      </div>-->
    </div>
  </div>
            <?php
            $c2--;

            if($c2>0){
              echo '<div class="pagebreak"></div>';
              echo '<br>';
            }?>


            <?php

          }
        }
        else{
          echo '<br><br>No hay carnets generados';
        }
        ?>
    <!-- fin -->
  </div>
</body>
</html>
<script type="text/javascript">
window.print();
setTimeout(window.close, 500);
</script>
<?php
else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;
?>
