<?php
include_once '../../../../inc/functions.php';
sec_session_start();
?>
<?php if (function_exists('verificar_session') && verificar_session() == true) : ?>
    <?php
    include_once '../functions.php';
    $data = array();
    $response = array();
    $departamento = $_POST['departamento'];
    $dependencia = $_POST['dependencia'];
    $DIRECTORIO = new Directorio();
    $dependencias = $DIRECTORIO::get_dependencias($departamento, $dependencia);
    $telefonos = array();
    $posicion = -1;
    foreach ($dependencias as $dependencia) {
        $params = "id={$dependencia['id_dependencia']}&dependencia='{$dependencia['dependencia']}'&departamento='{$dependencia['departamento']}'&municipio='{$dependencia['municipio']}'";
        $button = "<div class='btn-group btn-group-sm' role='group'>
                            <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto-lg' href='directorio/php/front/dependencias/detalle_dependencia.php?{$params}' data-toggle='tooltip' data-placement='top' title='Tooltip on top'>
                                <i class='fa fa-eye'></i>
                            </span>
                            <span class='btn btn-sm btn-personalizado outline' data-toggle='modal' data-target='#modal-remoto' href='directorio/php/front/dependencias/modificar_dependencia.php?id={$dependencia['id_dependencia']}'>
                                <i class='fa fa-user-edit'></i>
                            </span>
                        </div>";
        $data[$dependencia['id_dependencia']][] = array(
            'id' => $dependencia['id_dependencia'],
            'ubicacion' => $dependencia['departamento'] . ", " . $dependencia['municipio'],
            'nombre' => $dependencia['dependencia'],
            'cargo' => $dependencia['cargo'],
            'funcionario' => $dependencia['persona'],
            'telefono' => $dependencia['numero'],
            'direccion' => $dependencia['direccion'],
            'detalle' => $button
        );
    }
    foreach ($data as $key => $value) {
        //if(count($value) >= 2){
        /*for($i=0; $i < count($value); $i++){
                    $telefonos[] = $value[$i]['telefono'];
                }*/
        $response[] = array(
            'id' => $value[0]['id'],
            'ubicacion' => $value[0]['ubicacion'],
            'nombre' => $value[0]['nombre'],
            'cargo' => $value[0]['cargo'],
            'funcionario' => $value[0]['funcionario'],
            'telefono' => $value[0]['telefono'], //implode(' ', array_unique($telefonos)),
            'direccion' => $value[0]['direccion'],
            'detalle' => $value[0]['detalle'],
        );
        $telefonos = array();
        /*}else{
                $response[] = array(
                    'id' => $value[0]['id'],
                    'ubicacion' =>$value[0]['ubicacion'],
                    'nombre' => $value[0]['nombre'],
                    'cargo' => $value[0]['cargo'],
                    'funcionario' => $value[0]['funcionario'],
                    'telefono' => $value[0]['telefono'],
                    'direccion' => $value[0]['direccion'],
                    'detalle' => $value[0]['detalle'],
                );
            }*/
    }
    $results = array(
        "sEcho" => 1,
        "iTotalRecords" => count($response),
        "iTotalDisplayRecords" => count($response),
        "aaData" => $response
    );
    echo json_encode($results);
    ?>
<?php else : ?>
    <script type='text/javascript'>
        window.location = 'principal.php';
    </script>";
<?php endif; ?>