<template>
    <div class="card-body card-body-slide">
        <div class="row justify-content-center">
            <a class="btn btn-default btn-personalizado btn-sm btn-st-3 btn-solt" href="#"
                @click="pantallaDescripcion()"><span>Descripción <i class="fa fa-bars"></i></span></a>
            <a class="btn btn-default btn-personalizado btn-sm btn-st-3 btn-solt" href="#"
                @click="pantallaTecnicos()"><span>Técnicos <i class="fa fa-address-card"></i></span></a>
        </div>
        <hr>
        <div v-if="pantallas == 1" class="card-body-slide">
            <div class="row">
                <div class="col-sm-6">
                    <label>Persona Solicita:</label>
                    <div class="input-group has-personalizado">
                        <i class="fa fa-home"></i>
                        <strong> {{ detalle.persona_solicita }}</strong>
                    </div>
                </div>

                <div class="col-sm-6">
                    <label>Dirección:</label>
                    <div class="input-group has-personalizado">
                        <i class="fa fa-user-check"></i>
                        <strong> {{ detalle.direccion_persona_solicita }}</strong>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-3">
                    <small class="text-muted">Fue Generado: </small>
                    <h5> {{ detalle.fecha }}</h5>
                </div>

                <div class="col-sm-3">
                    <small class="text-muted">Fue Terminado: </small>
                    <h5> {{ detalle.fecha_terminado }}</h5>
                </div>

                <div class="col-sm-3">
                    <small class="text-muted">Correo Persona Solicita: </small>
                    <h5> {{ detalle.persona_user }}</h5>
                </div>

                <div class="col-sm-3">
                    <small class="text-muted">Departamento Persona Solicita: </small>
                    <h5>
                        {{ detalle.departamento_persona_solicita }}
                    </h5>
                </div>
            </div>
            <hr>
            <div class="row">

                <table id="tablRequeri" class="table responsive table-sm table-bordered table-striped" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Requerimiento</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Detalle</th>
                            <th class="text-center">Calificación</th>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in requerimientos" :key="r.nombre">

                            <td class="text-center">{{ r.nombre }}</td>
                            <td v-if="r.estado == 1" class="text-center">
                                Pendiente
                                <div class="progress progress-striped skill-bar " style="height:6px">
                                    <div class="progress-bar progress-bar-striped bg-info" role="progressbar"
                                        aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 50%">
                                    </div>
                                </div>
                            </td>
                            <td v-if="r.estado == 2" class="text-center">
                                Finalizado
                                <div class="progress progress-striped skill-bar " style="height:6px">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                        aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 100%">
                                    </div>
                                </div>
                            </td>

                            <!-- <td>{{ detalleTecnico[r.correlativo].descripcion_tecnico }}</td> -->
                            <td class="text-center"> {{ detalleTecnico[r.correlativo - 1].descripcion_tecnico }}</td>

                            <td v-if="calificacion[0].id_calificacion == 1" class="text-center">
                                <i class="fa fa-star mr-2 text-warning"></i>
                            </td>
                            <td v-else-if="calificacion[0].id_calificacion == 2" class="text-center">
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                            </td>
                            <td v-else-if="calificacion[0].id_calificacion == 3" class="text-center">
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                            </td>
                            <td v-else-if="calificacion[0].id_calificacion == 4" class="text-center">
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                            </td>
                            <td v-else-if="calificacion[0].id_calificacion == 5" class="text-center">
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                                <i class="fa fa-star mr-2 text-warning"></i>
                            </td>
                            <td v-else-if="calificacion[0].length == null" class="text-center">
                                Pendiente
                            </td>

                            <td class="text-center"> {{ r.fecha_terminado }} </td>
                            <td class="text-center"> {{ detalleTecnico[r.correlativo - 1].primer_nombre }} {{
                                detalleTecnico[r.correlativo -
                                    1].primer_apellido
                            }}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <table id="tablDesc" class="table responsive table-sm table-bordered table-striped" width="100%">
                        <thead>
                            <th>
                                Descripción Ticket:
                            </th>
                        </thead>

                        <tbody>
                            <tr>
                                <td> <span v-html="detalle.detalle"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-else-if="this.pantallas == 2">
            <div class="row card-body-slide">
                <div class="col-sm-12">
                    <table id="tablDesc" class="table responsive table-sm table-bordered table-striped" width="100%">
                        <thead>
                            <th>
                                Encargados Ticket:
                            </th>
                            <th>
                                Estado Técnico:
                            </th>
                            <th>
                                Descripción
                            </th>
                        </thead>

                        <tbody>
                            <tr v-for="t in tecnicos" class="text-center">
                                <td> {{ t.primer_nombre }} {{ t.segundo_nombre }} {{
                                    t.tercer_nombre
                                }}
                                    {{ t.primer_apellido }} {{ t.segundo_apellido }} {{ t.tercer_apellido }}</td>
                                <td v-if="t.estado == 0">
                                    Anulado
                                    <div class="progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-danger" aria-valuenow="30"
                                            aria-valuemin="30" aria-valuemax="100" style="width: 100%">
                                        </div>
                                    </div>
                                </td>
                                <td v-if="t.estado == 1">
                                    Asignado
                                    <div class="progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                            aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 100%">
                                        </div>
                                    </div>
                                </td>
                                <td v-if="t.estado == 2">
                                    Re-Asignado
                                    <div class="progress progress-striped skill-bar " style="height:6px">
                                        <div class="progress-bar progress-bar-striped bg-warning" role="progressbar"
                                            aria-valuenow="30" aria-valuemin="30" aria-valuemax="100" style="width: 100%">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ t.descripcion }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

module.exports = {
    props: ["idticket", "parametro2"],
    data: function () {
        return {
            detalle: [],
            detalleTecnico: [],
            requerimientos: [],
            tecnicos: [],
            calificacion: [],
            cali: "",
            correlativo: 0,
            pantallas: 1
        }
    },
    created: function () {
        this.informacion()
    },

    methods: {
        pantallaDescripcion: function () {
            this.pantallas = 1
        },
        pantallaTecnicos: function () {
            this.pantallas = 2
        },
        informacion: function () {
            axios.get('tickets/model/tickets.php', {
                params: {
                    opcion: 6,
                    id: this.idticket
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                this.detalle = response.data;

                //Si falla da mensaje de error
            }.bind(this)).catch(function (error) {
                console.log(error);
            });

            axios.get('tickets/model/tickets.php', {
                params: {
                    opcion: 12,
                    id: this.idticket
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                this.requerimientos = response.data;

                //Si falla da mensaje de error
            }.bind(this)).catch(function (error) {
                console.log(error);
            });

            axios.get('tickets/model/tickets.php', {
                params: {
                    opcion: 14,
                    id: this.idticket,
                    tipo: 1
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                this.tecnicos = response.data;

                //Si falla da mensaje de error
            }.bind(this)).catch(function (error) {
                console.log(error);
            });

            axios.get('tickets/model/tickets.php', {
                params: {
                    opcion: 17,
                    id: this.idticket
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                this.calificacion = response.data;

                //Si falla da mensaje de error
            }.bind(this)).catch(function (error) {
                console.log(error);
            });

            axios.get('tickets/model/tickets.php', {
                params: {
                    opcion: 21,
                    id: this.idticket
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                this.detalleTecnico = response.data;

                //Si falla da mensaje de error
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        }
    }
}
</script>