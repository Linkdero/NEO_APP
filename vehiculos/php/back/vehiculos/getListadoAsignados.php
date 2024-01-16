<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once './../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class Asignados
{
    static function getAllAsignados()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT DISTINCT id_persona_asignado
        ,(p.primer_nombre + ' ' + p.segundo_nombre+ ' ' + p.primer_apellido + ' '+ p.segundo_apellido) as asignado,
		d.descripcion as direccion
        FROM dayf_vehiculo_asignacion AS dva
        left join rrhh_persona as p on dva.id_persona_asignado = p.id_persona
		left join rrhh_direcciones as d on dva.id_persona_asignado_direccion = d.id_direccion";
        $p = $pdo->prepare($sql);
        $p->execute();
        $asignados = $p->fetchAll();
        $data = array();

        foreach ($asignados as $a) {
            $accion = '<button type="button" class="btn btn-outline-dark btn-lg btn-floating btn-sm" onclick="vehiculosAsignados(\'' . $a["id_persona_asignado"] . '\')"><i class="fa-solid fa-hashtag">' . $a["id_persona_asignado"] . '</i></button>';
            $accion2 = '<h3>' . $a['asignado'] . '</h3>';
            $accion3 = '<h3>' . $a['direccion'] . '</h3>';

            $sub_array = array(
                'id' => $accion,
                'nombre' => $accion2,
                'direccion' => $accion3
            );
            $data[] = $sub_array;
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
    }

}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Asignados::getAllAsignados();
            break;
    }
}