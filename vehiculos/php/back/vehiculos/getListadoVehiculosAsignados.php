<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once './../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class VehiculosAsignados
{
    static function getAllVehiculos()
    {
        $id = $_GET["id"];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT  va.id_vehiculo
        ,nro_placa
        ,nombre_marca
        ,nombre_linea
        ,modelo
        ,nombre_color
        ,(p.primer_nombre + ' ' + p.segundo_nombre+ ' ' + p.primer_apellido + ' '+ p.segundo_apellido) as autoriza
        ,fecha_entrega
        FROM dayf_vehiculo_asignacion as va
        left join xxx_dayf_vehiculos as v on va.id_vehiculo = v.id_vehiculo
        left join rrhh_persona as p on va.id_persona_autoriza = p.id_persona
        where va.id_persona_asignado = ?
        order by fecha_entrega desc";
        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $vehiculos = $p->fetchAll();
        $data = array();

        foreach ($vehiculos as $v) {

            $sub_array = array(
                'id' => $v["id_vehiculo"],
                'placa' => $v["nro_placa"],
                'marca' => $v["nombre_marca"],
                'linea' => $v["nombre_linea"],
                'modelo' => $v["modelo"],
                'color' => $v["nombre_color"],
                'autoriza' => $v["autoriza"],
                'fecha' => date("d/m/Y", strtotime($v["fecha_entrega"])),
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
            VehiculosAsignados::getAllVehiculos();
            break;
    }
}