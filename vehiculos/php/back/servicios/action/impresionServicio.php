<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once '../../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class Impresion
{
    public $impresion;
    //public $opcion = 1;
    protected function __construct()
    {
        $this->impresion = array();
    }

    //Opcion 1
    static function getInfoServicio()
    {
        $idServicio = $_GET["idServicio"];
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT id_servicio
        ,id_vehiculo
        ,id_vehiculo_externo
        ,nro_placa
        ,nombre_taller
        ,tipo_servicio
        ,recepcion_id_persona_recibe
        ,nombre_recepcion_persona_recibe
        ,recepcion_id_persona_entrega
        ,nombre_recepcion_persona_entrega
        ,entrega_id_persona_recibe
        ,nombre_entrega_persona_recibe
        ,entrega_id_persona_entrega
        ,nombre_entrega_persona_entrega
        ,id_mecanico_asignado
        ,nombre_mecanico_asignado
        ,nro_orden
        ,fecha_recepcion
        ,fecha_entregado
        ,fecha_asignacion_mecanico
        , [dbo].[RTF2Text](descripcion_solicitado) AS descripcion_solicitado
        , [dbo].[RTF2Text](descripcion_realizado) AS descripcion_realizado
        ,nombre_estado_servicio
        ,modelo
        ,nombre_linea
        ,nombre_color
        ,nombre_marca
        ,nombre_tipo
        ,nombre_estado_vehiculo
    FROM xxx_dayf_vehiculo_servicios
    where id_servicio = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($idServicio));
        $s = $p->fetch(PDO::FETCH_ASSOC);



        $sql = "SELECT km_actual
        FROM dayf_vehiculo_servicios
        where id_servicio = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($idServicio));
        $s2 = $p->fetch(PDO::FETCH_ASSOC);




        $sql = "SELECT dir_nominal
        FROM xxx_rrhh_Ficha
        where id_persona = ?";

        $p = $pdo->prepare($sql);
        $p->execute(array($s["recepcion_id_persona_entrega"]));
        $d = $p->fetch(PDO::FETCH_ASSOC);

        $data = array();
        $null = 'Sin datos';

        $sub_array = array(
            "id_servicio" => $s["id_servicio"],
            "id_vehiculo" => $s["id_vehiculo"],
            "id_vehiculo_externo" => empty($s["id_vehiculo_externo"]) ? $null : $s["id_vehiculo_externo"],
            "nro_placa" => $s["nro_placa"],
            "nombre_taller" => $s["nombre_taller"],
            "tipo_servicio" => $s["tipo_servicio"],
            "recepcion_id_persona_recibe" => $s["recepcion_id_persona_recibe"],
            "nombre_recepcion_persona_recibe" => $s["nombre_recepcion_persona_recibe"],
            "recepcion_id_persona_entrega" => $s["recepcion_id_persona_entrega"],
            "nombre_recepcion_persona_entrega" => $s["nombre_recepcion_persona_entrega"],
            "entrega_id_persona_recibe" => $s["entrega_id_persona_recibe"],
            "nombre_entrega_persona_recibe" => $s["nombre_entrega_persona_recibe"],
            "entrega_id_persona_entrega" => $s["entrega_id_persona_entrega"],
            "nombre_entrega_persona_entrega" => $s["nombre_entrega_persona_entrega"],
            "id_mecanico_asignado" => $s["id_mecanico_asignado"],
            "nombre_mecanico_asignado" => $s["nombre_mecanico_asignado"],
            "nro_orden" => $s["nro_orden"],
            "fecha_recepcion" => date("d/m/Y", strtotime($s["fecha_recepcion"])),
            "fecha_entregado" => date("d/m/Y", strtotime($s["fecha_entregado"])),
            "hora_entregado" => date("H:m:s", strtotime($s["fecha_entregado"])),
            "fecha_asignacion_mecanico" => date("d/m/Y", strtotime($s["fecha_asignacion_mecanico"])),
            "hora_asignacion_mecanico" => date("H:m:s", strtotime($s["fecha_asignacion_mecanico"])),
            "descripcion_solicitado" => substr($s["descripcion_solicitado"], 1, 1) == '>' ? '<' . $s["descripcion_solicitado"] : $s["descripcion_solicitado"],
            "descripcion_realizado" => substr($s["descripcion_realizado"], 1, 1) == '>' ? '<' . $s["descripcion_realizado"] : $s["descripcion_realizado"],
            "nombre_estado_servicio" => $s["nombre_estado_servicio"],
            "modelo" => $s["modelo"],
            "nombre_linea" => $s["nombre_linea"],
            "nombre_color" => $s["nombre_color"],
            "nombre_marca" => $s["nombre_marca"],
            "nombre_tipo" => $s["nombre_tipo"],
            "nombre_estado_vehiculo" => $s["nombre_estado_vehiculo"],
            "dir_nominal" => $d["dir_nominal"],
            "km_actual" => $s2["km_actual"],
        );
        $data[] = $sub_array;
        echo json_encode($data);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            Impresion::getInfoServicio();
            break;
    }
}