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
        $sql = "SELECT distinct c.id_cupon 
        ,nro_cupon
        ,monto
		,id_estado_cupon
        ,cd.descripcion_corta 
        ,dcd.nro_documento 
        ,fecha_procesado 
        FROM [SAAS_APP].[dbo].[dayf_cupones] as c
        inner join tbl_catalogo_detalle as cd on c.id_estado_cupon = cd.id_item
        inner join dayf_cupones_documento as dcd on c.id_entrega_cupon = dcd.id_documento
        union 
        (SELECT distinct c.id_cupon
        ,nro_cupon
        ,monto
		,id_estado_cupon
        ,cd.descripcion_corta
        ,dcd.nro_documento
        ,fecha_procesado
        FROM [SAAS_APP].[dbo].[dayf_cupones] as c
        inner join tbl_catalogo_detalle as cd on c.id_estado_cupon = cd.id_item
        right join dayf_cupones_documento as dcd on c.id_ingreso_cupon = dcd.id_documento
        where id_estado_cupon = 1913)
        order by c.id_cupon desc";
        $p = $pdo->prepare($sql);
        $p->execute();
        $cupones = $p->fetchAll();
        $data = array();
        $bandera = '';
        foreach ($cupones as $c) {
            if ($c["id_estado_cupon"] == 1914) {
                $bandera = 1;
            } else {
                $bandera = '';
            }
            $sub_array = array(
                'id' => $c["id_cupon"],
                'cupon' => $c["nro_cupon"],
                'monto' => $c["monto"],
                'estado' => (!empty($bandera)) ? '<span class="badge badge-success">' . $c["descripcion_corta"] . '</span>' : '<span class="badge badge-danger">' . $c["descripcion_corta"] . '</span>',
                'doc' => $c["nro_documento"],
                'fecha' => (!empty($c["fecha_procesado"])) ? date('d-m-Y H:i', strtotime($c["fecha_procesado"])) : 'Cupon sin entregar'
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
