<?php
    include_once '../../../../inc/functions.php';
    sec_session_start();
?>

<?php if (function_exists('verificar_session') && verificar_session() == true): 
    
    ?>
    <?php
        include_once '../../back/functions.php';
        $extension = $_GET["extension"];
        $id_persona = $_GET["id_persona"];
        if(true){
            $data = array();
            $table = "
            <link href='assets/js/plugins/x-editable/bootstrap-editable.css' rel='stylesheet'/>
            <script src='assets/js/plugins/x-editable/bootstrap-editable.min.js'></script>
            <script src='assets/js/plugins/x-editable/bootstrap-editable.js'></script>
            <script src='configuracion/js/source_detalle_extensiones.js'></script>
            <script src='configuracion/js/funciones.js'></script>
            <div class='modal-header'>
                            <h3 class='modal-title'>Detalle de contactos para: <b>{$id_persona}</b></h5>
                            <input type='hidden' value={$id_persona} id='id_persona'>
                            <input type='hidden' value={$extension} id='extension'>
                            <ul class='list-inline ml-auto mb-0'>
                            
                                <li class='list-inline-item'>
                                    <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                                        <i class='fa fa-times'></i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class='modal-body'>

                            <table id='tb_detalle_telefono' class='table table-sm table-bordered table-striped' width='100%'>
                                <thead>

                                    <tr>
                                        <th class='text-center'>Id</th> 
                                        <th class='text-center'>Extensión</th>  
                                        <th class='text-center'>Ubicación</th>
                                        <th class='text-center'>Puesto</th>
                                        <th class='text-center'>Empleado</th>
                                        <th class='text-center'>Correo</th>
                                        <th class='text-center'>Gafete</th>
                                    </tr>
                                </thead>
                            </table>
                            <br/>
                            <div align='right'>";
                        //    if(evaluar_flag($_SESSION['id_persona'],1875,228,'flag_insertar')==1){
                        //     $table.="<button type='button' name='add' id='add' class='btn btn-info'>Nuevo número</button>";
                        //    }
                           
                           
                        $table.="</div>
                        </div>
                        ";

            echo $table;
        }else{
            echo "  <div class='modal-header'>
                    <h3 class='modal-title'>Detalle de contactos para: <b>{$extension}</b></h5>
                                <ul class='list-inline ml-auto mb-0'>
                                    <li class='list-inline-item'>
                                        <span class='link-muted h3' class='close' data-dismiss='modal' aria-label='Close'>
                                            <i class='fa fa-times'></i>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class='modal-body'>
                                <h1>No cuenta con permisos para ver el detalle!</h1>
                            </div>";
        }

    ?>
<?php else: ?>
    <script type='text/javascript'> window.location='principal.php'; </script>";
<?php endif; ?>
