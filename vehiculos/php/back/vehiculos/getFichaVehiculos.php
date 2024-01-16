<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);
class Ficha
{
    static function getDatos()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_GET["id"];
        $data = array(
            "ficha" => array(),
            "foto" => array(),
        );

        // Obtener Ficha
        $sql = "SELECT propietario
              ,nro_placa
              ,chasis
              ,motor
              ,modelo
              ,detalle_franjas
              ,observaciones
              ,capacidad_tanque
              ,kilometros_x_galon
              ,nombre_tipo_combustible
              ,poliza_seguro
              ,nombre_empresa_seguros
              ,nombre_linea
              ,nombre_color
              ,nombre_marca
              ,nombre_tipo
              ,nombre_uso
              ,id_status
              ,nombre_estado
              ,km_servicio_proyectado
              ,km_actual
              ,nombre_tipo_asignacion
              ,nombre_persona_asignado
              ,nombre_direccion
              ,nombre_persona_autoriza
              FROM xxx_dayf_vehiculos
              WHERE id_vehiculo = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data['ficha'][] = array(
                "propietario" => $t["propietario"],
                "nro_placa" => $t["nro_placa"],
                "chasis" => $t["chasis"],
                "motor" => $t["motor"],
                "modelo" => $t["modelo"],
                "detalle_franjas" => $t["detalle_franjas"],
                "observaciones" => $t["observaciones"],
                "capacidad_tanque" => $t["capacidad_tanque"],
                "kilometros_x_galon" => $t["kilometros_x_galon"],
                "nombre_tipo_combustible" => $t["nombre_tipo_combustible"],
                "poliza_seguro" => $t["poliza_seguro"],
                "nombre_empresa_seguros" => $t["nombre_empresa_seguros"],
                "nombre_linea" => $t["nombre_linea"],
                "nombre_color" => $t["nombre_color"],
                "nombre_marca" => $t["nombre_marca"],
                "nombre_tipo" => $t["nombre_tipo"],
                "nombre_uso" => $t["nombre_uso"],
                "id_status" => $t["id_status"],
                "nombre_estado" => $t["nombre_estado"],
                "km_servicio_proyectado" => $t["km_servicio_proyectado"],
                "km_actual" => $t["km_actual"],
                "nombre_tipo_asignacion" => $t["nombre_tipo_asignacion"],
                "nombre_persona_asignado" => $t["nombre_persona_asignado"],
                "nombre_direccion" => $t["nombre_direccion"],
                "nombre_persona_autoriza" => $t["nombre_persona_autoriza"],
            );
        }

        // Obtener Foto
        $sql = "SELECT TOP (1) foto 
        FROM dayf_vehiculo_fotografias
        WHERE id_vehiculo = ?
        ORDER BY id_vehiculo DESC";

        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data['foto'][] = array(
                "foto" => base64_encode($t['foto']),
            );
        }
        echo json_encode($data);
    }

    static function getAsignados()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_GET["id"];

        $data = array();

        // Obtener Ficha
        $sql = "SELECT tcd.descripcion
        ,(p.primer_nombre + ' ' + p.segundo_nombre+ ' ' + p.primer_apellido + ' '+ p.segundo_apellido) as asignado
        ,(pp.primer_nombre + ' '+ pp.segundo_nombre+ ' ' + pp.primer_apellido + ' '+ pp.segundo_apellido) as autorizado
        ,fecha_entrega
        FROM dayf_vehiculo_asignacion as dva
        left join tbl_catalogo_detalle as tcd on dva.tipo_asignacion = tcd.id_item
        left join rrhh_persona as p on dva.id_persona_asignado = p.id_persona
        left join rrhh_persona as pp on dva.id_persona_autoriza = pp.id_persona
        WHERE id_vehiculo = ?
        ORDER BY fecha_entrega DESC";

        $p = $pdo->prepare($sql);
        $p->execute(array($id));
        $tipos = $p->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as $t) {
            $data[] = array(
                "descripcion" => $t["descripcion"],
                "asignado" => $t["asignado"],
                "autorizado" => empty($t["autorizado"]) ? 'Sin Datos' : $t["autorizado"],
                "fecha_entrega" => date("d/m/Y", strtotime($t["fecha_entrega"]))

            );
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

// case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Ficha::getDatos();
            break;

        case 2:
            Ficha::getAsignados();
            break;
    }
}