<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once './../../../../inc/functions.php';
sec_session_start();
date_default_timezone_set('America/Guatemala');
$emp = get_empleado_by_session_direccion($_SESSION['id_persona']);

class CuponesIngresados
{
    static function getAllCupones()
    {
        $pdo = Database::connect_sqlsrv();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $id_documento = $_POST['documento'];
        $estado = $_POST["filtro"];
        $bandera = '';
        if ($estado == 3) {
            $sql = "SELECT distinct c.id_cupon 
            ,nro_cupon
            ,monto
            ,id_estado_cupon
            ,cd.descripcion_corta 
            ,dcd.nro_documento 
            ,fecha_procesado 
            ,dcd.descripcion
            FROM [SAAS_APP].[dbo].[dayf_cupones] as c
            inner join tbl_catalogo_detalle as cd on c.id_estado_cupon = cd.id_item
            inner join dayf_cupones_documento as dcd on c.id_entrega_cupon = dcd.id_documento
            where id_ingreso_cupon = ?
            union 
            (SELECT distinct c.id_cupon
            ,nro_cupon
            ,monto
            ,id_estado_cupon
            ,cd.descripcion_corta
            ,dcd.nro_documento
            ,fecha_procesado
            ,dcd.descripcion
            FROM [SAAS_APP].[dbo].[dayf_cupones] as c
            inner join tbl_catalogo_detalle as cd on c.id_estado_cupon = cd.id_item
            right join dayf_cupones_documento as dcd on c.id_ingreso_cupon = dcd.id_documento
            where id_estado_cupon in ( 1913,5562,4711 ) and id_ingreso_cupon = ?)
            order by c.id_cupon desc";
            $p = $pdo->prepare($sql);
            $p->execute(array($id_documento, $id_documento));
        } else {
            $sql = "SELECT distinct c.id_cupon
            ,nro_cupon
            ,monto
            ,id_estado_cupon
            ,cd.descripcion_corta
            ,dcd.nro_documento
            ,fecha_procesado
            ,dcd.descripcion
            FROM [SAAS_APP].[dbo].[dayf_cupones] as c
            inner join tbl_catalogo_detalle as cd on c.id_estado_cupon = cd.id_item";
            $sql .= ' ';
            if ($estado == 0) {
                $sql .= " right join dayf_cupones_documento as dcd on c.id_ingreso_cupon = dcd.id_documento
                WHERE id_estado_cupon = 1913 and id_ingreso_cupon = ?
                order by c.id_cupon desc";
            } else if ($estado == 1) {
                $sql .= "inner join dayf_cupones_documento as dcd on c.id_entrega_cupon = dcd.id_documento
                WHERE id_estado_cupon = 1914 and id_ingreso_cupon = ?
                order by c.id_cupon desc";
            } else if ($estado == 2) {
                $sql .= " right join dayf_cupones_documento as dcd on c.id_ingreso_cupon = dcd.id_documento
                WHERE id_estado_cupon = 5562 and id_ingreso_cupon = ?
                order by c.id_cupon desc";
            } else if ($estado == 4) {
                $sql .="right join dayf_cupones_documento as dcd on c.id_ingreso_cupon = dcd.id_documento
                WHERE id_estado_cupon = 4711 and id_ingreso_cupon = ?
                order by c.id_cupon desc";
            }
            $p = $pdo->prepare($sql);
            $p->execute(array($id_documento));
        }
        $cupones = $p->fetchAll(PDO::FETCH_ASSOC);
        $data = array();
        foreach ($cupones as $c) {
            if ($c["id_estado_cupon"] == 1913) {
                $bandera = '<span class="badge badge-danger">';
            } else if ($c["id_estado_cupon"] == 1914) {
                $bandera = '<span class="badge badge-success">';
            } else if ($c["id_estado_cupon"] == 5562) {
                $bandera = '<span class="badge badge-warning">';
            } else if ($c["id_estado_cupon"] == 4711) {
                $bandera = '<span class="badge badge-dark">';
            }

            $sub_array = array(
                'doc' => $c["nro_documento"],
                'id' => $c["id_cupon"],
                'cupon' => $c["nro_cupon"],
                'monto' => $c["monto"],
                'estado' =>   $bandera . $c["descripcion_corta"] . '</span>',
                'descripcion' => $c["descripcion"],
                'fecha' => (!empty($c["fecha_procesado"])) ? date('d-m-Y H:i', strtotime($c["fecha_procesado"])) : 'Cupon sin utilizar'
            );
            $data[] = $sub_array;
        }

        $result = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($result);
    }
}

//case
if (isset($_POST['opcion']) || isset($_GET['opcion'])) {
    $opcion = (!empty($_POST['opcion'])) ? $_POST['opcion'] : $_GET['opcion'];

    switch ($opcion) {
        case 1:
            CuponesIngresados::getAllCupones();
            break;
    }
}
