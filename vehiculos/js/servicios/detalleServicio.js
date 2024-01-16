const viewModeDetalleServicio = new Vue({
    el: '#detalleServicio',
    data() {
        return {
            mecanicos: [],
            id_vehiculo: "",
            fotoVehiculo: "",
            detalle: [],
            idVehiculo: "",
            detalleVehiculo: [],
            servicio: "",
            plainText: "",
            obs: 1,
            idMecanico: "",
            desenfoque: "",
            marca: "",
            noServi: "",
        }
    },

    created: function () {
        this.noServi = $("#noServi").val()
        this.getInformacion()
        this.getServicioById(this.noServi)
    },

    //Asi generamos funciones en VUE
    methods: {
        addBounceEffect() {
            Vue.nextTick(() => {
                const animation = this.$refs.animation;
                const icon = this.$refs.icon;

                if (animation && icon) {
                    icon.classList.add('fa-bounce');
                }
            });
        },
        removeBounceEffect() {
            Vue.nextTick(() => {
                const animation = this.$refs.animation;
                const icon = this.$refs.icon;

                if (animation && icon) {
                    icon.classList.remove("fa-bounce");

                }
            });
        },

        getMecanicos: function (idMecanico) {
            axios.get('vehiculos/php/back/servicios/detalle/getServicioDetalle.php', {
                params: {
                    opcion: 3,
                    idMecanico: idMecanico
                }
            }).then(function (response) {
                this.mecanicos = response.data;
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        },
        pantallas: function (pantalla) {
            this.obs = pantalla
        },
        getServicioById: function (id_servicio) {
            axios.get('vehiculos/php/back/servicios/detalle/get_servicio_by_id.php', {
                params: {
                    id_servicio: id_servicio
                }
            })
                .then(function (response) {
                    this.servicio = response.data;
                }.bind(this)).catch(function (error) {
                    console.log(error);
                });
        },

        // getFotografiaById: function (id_vehiculo) {
        //     axios.get('vehiculos/php/back/vehiculos/get_vehiculo_fotografia', {
        //         params: {
        //             id_vehiculo: id_vehiculo
        //         }
        //     }).then(function (response) {
        //         this.fotoVehiculo = response.data;
        //     }.bind(this)).catch(function (error) {
        //         console.log(error);
        //     });
        // },

        getInformacion: function () {
            axios.get('vehiculos/php/back/servicios/detalle/getServicioDetalle.php', {
                params: {
                    opcion: 1,
                    noServi: $("#noServi").val()
                }
            }).then(function (response) {
                this.detalle = response.data;
                console.log(this.detalle);
                this.idVehiculo = this.detalle.id_vehiculo
                this.getDetalleVehiculo(this.idVehiculo);
                // this.getFotografiaById(this.idVehiculo);
                if (this.detalle.id_mecanico_asignado != null) {
                    this.getMecanicos(this.detalle.id_mecanico_asignado)
                }
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        },

        getDetalleVehiculo: function (idVehiculo) {
            axios.get('vehiculos/php/back/servicios/detalle/getServicioDetalle.php', {
                params: {
                    opcion: 2,
                    idVehiculo: idVehiculo,
                    noServi: this.noServi
                }
            }).then(function (response) {
                this.detalleVehiculo = response.data;
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        },
    }
})
