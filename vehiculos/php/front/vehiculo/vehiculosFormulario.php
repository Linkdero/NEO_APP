<input type="hidden" value=" <?php echo $_GET["tipo"] ?>" id="tipo">
<input type="hidden" value=" <?php echo $_GET["id"] ?>" id="idVehiculo">

<div id="formularioVehiculos">
    <div class="modal-header bg-info">
        <h3 class="modal-title text-white"><i
                class="bg-info text-white far fa-edit mr-3"></i><strong>{{titulo}}</strong></h3>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body bg-white">
        <div class="form-row d-flex justify-content-center">
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button type="button" class="btn btn-outline-info btn-sm btn-floating" data-toggle="tooltip"
                        data-placement="bottom" title="DATOS PRINCIPALES" @click="formulario(1)">
                        <i class="fa-solid fa-clipboard"></i> </button>
                    <button type="button" class="btn btn-outline-info btn-sm btn-floating" data-toggle="tooltip"
                        data-placement="bottom" title="RENDIMIENTO" @click="formulario(2)">
                        <i class="fa-solid fa-truck-fast"></i> </button>

                    <button type="button" class="btn btn-outline-info btn-sm btn-floating" data-toggle="tooltip"
                        data-placement="bottom" title="EXTRAS" @click="formulario(3)">
                        <i class="fa-solid fa-layer-plus"></i> </button>
                </div>
            </div>
        </div>
        <hr>
        <form class="needs-validation" novalidate>
            <div class="form-row">
                <div class="form-group col-6  justify-content-center text-center">
                    <button type="button" class="btn btn-outline-primary" id="uploadButton"
                        @click="openImageUploader">Subir Imagen</button>
                    <h2>
                        <span class="badge text-info"><i class="fa-solid fa-image"></i></span>
                        <span class="badge">Imagen Previa</span>
                    </h2>
                    <img v-bind:src="foto" class="img-thumbnail rounded" id="previa" style="width: 350px; height: 350px;">
                </div>

                <div v-show="parteFormulario == 1" class="form-group col-md-6 card-body-slide">
                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-sharp fa-solid fa-fingerprint"></i> </span>
                            <span class="badge">Datos Únicos</span>
                        </h2>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-4">
                            <h3> <span class="badge badge-secondary">No.Placas</span></h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-xmarks-lines"></i></div>
                                </div>
                                <input type="text" class="form-control" id="placa" placeholder="No.Placas"
                                    aria-describedby="inputGroupPrepend" required>
                            </div>
                        </div>

                        <div class="form-group col-4">
                            <h3> <span class="badge badge-secondary">No.Chasis</span></h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-car"></i></div>
                                </div>
                                <input type="text" class="form-control" id="chasis" placeholder="No.Chasis">
                            </div>
                        </div>

                        <div class="form-group col-4">
                            <h3> <span class="badge badge-secondary">No.Motor</span></h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-engine"></i></div>
                                </div>
                                <input type="text" class="form-control" id="motor" placeholder="No.Motor">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-sharp fa-solid fa-car-wrench"></i> </span><span
                                class="badge">Datos Físicos</span></h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Color</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-droplet"></i></div>
                                </div>
                                <select class="custom-select" id="color">
                                    <option selected disabled>Color</option>
                                    <option v-for="c in colores" v-bind:value='c.id'>{{c.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Estado</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-engine"></i></div>
                                </div>
                                <select class="custom-select" id="estado">
                                    <option selected disabled>Estado</option>
                                    <option v-for="e in estados" v-bind:value='e.id'>{{e.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Tipo</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-sharp fa-solid fa-quote-left"></i></div>
                                </div>
                                <select class="custom-select" id="tipoVehiculo">
                                    <option selected disabled>Tipo</option>
                                    <option v-for="t in tipos" v-bind:value='t.id'>{{t.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Marca</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-truck-clock"></i></div>
                                </div>
                                <select class="custom-select" id="marca">
                                    <option selected disabled>Marca</option>
                                    <option v-for="m in marcas" :value="m.id">{{m.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Linea</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-grip-lines"></i></div>
                                </div>
                                <select class="custom-select" id="linea">
                                    <option selected disabled>Linea</option>
                                    <option v-for="l in lineasObtenidas" :value="l.id">{{l.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Modelo</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa-sharp fa-regular fa-gauge-simple-max"></i>
                                    </div>
                                </div>
                                <input class="form-control" id="modelo" placeholder="Modelo" data-provide="datepicker"
                                    readonly>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Franjas</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa-sharp fa-regular fa-grip-lines"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="franjas" placeholder="Franjas">
                            </div>
                        </div>
                    </div>
                </div>

                <div v-show="parteFormulario==2" class="form-group col-md-6 card-body-slide">
                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-solid fa-chart-simple"></i></span><span
                                class="badge">Rendimiento Promedio</span></h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Cap.Galones</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-sharp fa-solid fa-tank-water"></i></div>
                                </div>
                                <input type="number" class="form-control" id="galones" placeholder="Cap.Galones">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Km. por Galón</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i
                                            class="fa-sharp fa-regular fa-gauge-simple-max"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" id="kmGalon" placeholder="No.Chasis">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Combustible</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-gas-pump"></i></div>
                                </div>
                                <select class="custom-select" id="combustible">
                                    <option selected disabled>Combustibles</option>
                                    <option v-for="c in combustibles" v-bind:value='c.id'>{{c.nombre}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-sharp fa-solid fa-gear fa-spin"></i></span><span
                                class="badge">Kilometraje y Servicios</span></h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Km. Para Servicios</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-gears"></i></div>
                                </div>
                                <input type="number" class="form-control" id="kmServicios"
                                    placeholder="Km. Para Servicios">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Km.Actual</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-slider"></i></div>
                                </div>
                                <input type="number" class="form-control" id="kmActual" placeholder="Km.Actual">
                            </div>
                        </div>
                    </div>
                </div>

                <div v-show="parteFormulario == 3" class="form-group col-md-6 card-body-slide">
                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-solid fa-user"></i> </span>
                            <span class="badge">Asignación</span>
                        </h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Persona Autoriza</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-user-check"></i></div>
                                </div>
                                <select class="custom-select" id="perAutoriza">
                                    <option selected disabled>Autoriza</option>
                                    <option v-for="p in personas" v-bind:value='p.id'>{{p.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Persona Asignada</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-user-unlock"></i></div>
                                </div>
                                <select class="custom-select" id="perAsignada">
                                    <option selected disabled>Asignada</option>
                                    <option v-for="p in personas" v-bind:value='p.id'>{{p.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <h3> <span class="badge badge-secondary">Tipo Asignación</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-regular fa-list-radio"></i></div>
                                </div>
                                <select class="custom-select" id="asignacion">
                                    <option selected disabled>Tipo Asignación</option>
                                    <option v-for="a in asignaciones" v-bind:value='a.id'>{{a.nombre}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-solid fa-money-check-dollar-pen"></i></span><span
                                class="badge">Aseguradora</span></h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Aseguradora</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-building-columns"></i></div>
                                </div>
                                <select class="custom-select" id="aseguradora">
                                    <option selected disabled>Aseguradora</option>
                                    <option v-for="p in proveedores" v-bind:value='p.id'>{{p.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">No.Poliza</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-money-check-pen"></i></div>
                                </div>
                                <input type="text" class="form-control" id="poliza" placeholder="No.Poliza">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <h2><span class="badge text-info"><i class="fa-solid fa-plus"></i></span>
                            <span class="badge">Extras</span>
                        </h2>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Dependencia</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-sharp fa-solid fa-shield-check"></i>
                                    </div>
                                </div>
                                <select class="custom-select" id="dependencia">
                                    <option selected disabled>Dependencia</option>
                                    <option v-for="d in dependencias" v-bind:value='d.id'>{{d.nombre}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <h3> <span class="badge badge-secondary">Observaciones</span></h3>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa-solid fa-car"></i></div>
                                </div>
                                <textarea class="form-control" id="observaciones" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer bg-white">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-info text-right"
            @click="nuevoVehiculo(<?php echo $_GET["id"] ?>)"><i class="fa fa-check"></i> Guardar</button>
    </div>
</div>
<script type="module" src="vehiculos/js/vehiculos/formularios.js?t=<?php echo time(); ?>"></script>
<style>
    .custom-select {
        width: 100% !important;
    }

    hr {
        height: 1px;
    }

    .input-invalid {
        box-shadow: 0 0 0 2px #ff0000;
        /* Aplica un borde rojo llamativo alrededor del input */
    }
</style>