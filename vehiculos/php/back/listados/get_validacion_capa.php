
<?php
include_once '../../../../inc/functions.php';
sec_session_start();
if (function_exists('verificar_session') && verificar_session() == true) :

    

    $id_vehiculo = null;
    if(isset($_GET['id_vehiculo']))
        $id_galones = $_GET['id_vehiculo'];

    $id_galones = NULL;
    if(isset($_GET['id_galones']))
        $id_galones = $_GET['id_galones'];
    //$id_galones = $_POST['id_galones'] ?? NULL;
    date_default_timezone_set('America/Guatemala');
    include_once '../functions.php';

    $clase = new vehiculos;
    $capaTanque = array();
    $capaTanque = $clase->get_capa_tanque($id_vehiculo);

    $data = array();
    $data = array(
        'id_vehiculo' => $capaTanque['id_vehiculo'],
        'capaT' => number_format($capaTanque['capaT'],2,'.',',')
    );

    if(!empty($id_vehiculo)){  //  si tiene id_vehiculo
      if ($capaTanque['capaT'] == 0 )   //  si no especificaron capacidad
        echo 'false';
      else     // si tiene una cantidad especifica
        if ($id_galones != NULL && $id_galones <= $capaTanque['capaT'])
          echo 'true';
        else
          echo 'false';
    }else{     // si no tiene id_vehiculo
      echo 'false';
    }
    

else:
  echo "<script type='text/javascript'> window.location='principal'; </script>";
endif;

?>