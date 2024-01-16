<?php
$destinos = $_POST['destinos'];
$personas=explode(',',$_POST['id_persona']);
foreach ($personas AS $value) {

  if(is_numeric($value)){
    echo $value.' || ';

    foreach ($destinos as $key => $d) {
      echo ' @ '.$d['departamento'];
    }
  }
  echo ' -- ';

}

?>
