<?php
include_once '../../../../inc/functions.php';
set_time_limit(0);
sec_session_start();

$id_persona=$_POST['id_persona'];
$tipo_comida=$_POST['tipo_comida'];
$valor=$_POST['valor'];
$parts = explode("-",$id_persona);
$id_per = $parts['0'];


$pdo = Database::connect_sqlsrv();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "SELECT id_persona, id_tipo_comida, id_activo
        FROM app_alimentos.dbo.Asignacion_Alimentos
        WHERE id_persona=? AND id_tipo_comida=?";
$q = $pdo->prepare($sql);
$q->execute(array($id_per,$tipo_comida));
$datos = $q->fetch();

$time = array('1'=>'Desayuno','2'=>'Almuerzo', '3'=>'Cena');
$timefood = $time[$tipo_comida];
if($datos==0) {
    //echo 'insertó nuevo';
    $sql = "INSERT INTO app_alimentos.dbo.Asignacion_Alimentos (id_persona, id_tipo_comida, id_activo, fecha) values(?,?,?,getdate())";
    $q = $pdo->prepare($sql);
    $q->execute(array($id_per, $tipo_comida, $valor));
    createLog(269, 1237, 'APP_ALIMENTOS.dbo.Asignacion_Alimentos','Creando tiempo de comida: '.$timefood,'', '');
 }else{
    //echo 'actualizó existente '.$valor;
    $sql = "UPDATE app_alimentos.dbo.Asignacion_Alimentos SET id_activo = ?, fecha=getdate() WHERE id_persona=? AND id_tipo_comida=?";
    $q = $pdo->prepare($sql);
    $q->execute(array($valor,$id_per,$tipo_comida));
    if($valor == 0){
      createLog(269, 1237, 'APP_ALIMENTOS.dbo.Asignacion_Alimentos','Quitando tiempo de comida: '.$timefood.' - empleado=id_persona:'.$id_per,'', '');
    }else{
      createLog(269, 1237, 'APP_ALIMENTOS.dbo.Asignacion_Alimentos','Asignando tiempo de comida: '.$timefood.' - empleado=id_persona:'.$id_per,'', '');
    }

 }

/*$sql = "UPDATE app_alimentos.dbo.Asignacion_Alimentos SET id_activo = ? WHERE id_persona=? AND id_tipo_comida=?";
$q = $pdo->prepare($sql);
$q->execute(array($valor,$id_per,$tipo_comida));*/

Database::disconnect_sqlsrv();

 ?>
