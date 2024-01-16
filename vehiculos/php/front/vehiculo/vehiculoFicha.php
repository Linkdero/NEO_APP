<input type="hidden" value=" <?php echo $_GET["idVehiculo"] ?>" id="idVehiculoLlamar">

<div id="vehiculoFicha">
    <div class="modal-header bg-info">
        <h3 class="modal-title text-white"><i class="fa-regular fa-book mr-3"></i><strong>{{titulo}} #
                <?php echo $_GET["idVehiculo"] ?>
            </strong></h2>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body bg-white">
        <div class="row">
            <div class="col-4 justify-content-center text-center">
                <h2>
                    <span class="badge text-info"><i class="fa-solid fa-image"></i></span>
                    <span class="badge">Foto del Vehículo</span>
                </h2>
                <img v-bind:src="foto" class="img-thumbnail rounded-circle" id="previa"
                    style="width: 250px; height: 250px;">
            </div>
            <div class="col-8 justify-content-center text-center">
                <h2>
                    <span class="badge text-info"><i class="fa-solid fa-circle-info"></i></span>
                    <span class="badge">Información General</span>
                </h2>
                <div class="row">
                    <div class="col">
                        <h2> <span class="badge badge-info">No.Placa</span></h2>
                        <span class="fa-light fa-credit-card"> </span>  {{ficha.nro_placa}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Tipo</span></h2>
                        <span class="fa-solid fa-list-timeline"> </span>  {{ficha.nombre_tipo}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Color</span></h2>
                        <span class="fa-sharp fa-solid fa-palette"> </span>  {{ficha.nombre_color}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Marca</span></h2>
                        <span class="fa-solid fa-copyright"> </span>  {{ficha.nombre_marca}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Linea</span></h2>
                        <span class="fa fa-solid fa-car"> </span>  {{ficha.nombre_linea}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Modelo</span></h2>
                        <span class="fa-solid fa-calendar-days"> </span>  {{ficha.modelo}}
                    </div>

                    <div class="col">
                        <h2><span :class="clase">Estado</span></h2>
                        <span :class="texto"> </span>  {{ ficha.nombre_estado }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <h2> <span class="badge badge-info">No.Chasis</span></h2>
                        <span class="fa-solid fa-truck-pickup"> </span>  {{ficha.chasis}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Motor</span></h2>
                        <span class="fa-solid fa-engine"> </span>  {{ficha.motor}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Combustible</span></h2>
                        <span class="fa-solid fa-gas-pump"> </span>  {{ficha.nombre_tipo_combustible}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Km x Galon</span></h2>
                        <span class="fa-solid fa-gauge-low"> </span>  {{ficha.kilometros_x_galon}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Km Actual</span></h2>
                        <span class="fa-solid fa-truck-bolt"> </span>  {{ficha.km_actual}} KM
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col">
                        <h2> <span class="badge badge-info">Persona Asignada</span></h2>
                        <span class="fa-solid fa-user"> </span>  {{ficha.nombre_persona_asignado}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Persona Autoriza</span></h2>
                        <span class="fa-solid fa-circle-user"> </span>  {{ficha.nombre_persona_autoriza}}
                    </div>

                    <div class="col">
                        <h2> <span class="badge badge-info">Aseguradora</span></h2>
                        <span class="fa-solid fa-unlock"> </span>  {{ficha.nombre_empresa_seguros}}
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="row justify-content-center">
            <div class="col text-center">
                <h2>
                    <span class="badge text-info"><i class="fa-solid fa-users"></i></span>
                    <span class="badge">Listado de Asignación en Personas</span>
                </h2>
            </div>
        </div>
    </div>

    <table id="tblFichaVehiculo" class="table table-actions table-striped table-bordered mb-0 w-100">
        <thead>
            <tr>
                <th class="text-center">Asignados</th>
                <th class="text-center">Autorizado Por</th>
                <th class="text-center">Tipo Aignación</th>
                <th class="text-center">Fecha</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script type="module" src="vehiculos/js/vehiculos/vehiculoFicha.js?t=<?php echo time(); ?>"></script>