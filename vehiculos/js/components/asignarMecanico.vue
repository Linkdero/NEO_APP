<template>
    <div class="card-body card-body-slide">

        <form class="needs-validation" novalidate>
            <div class="form-row">
                <div class="col mb-3">
                    <label>Asignar Mecánico</label>
                    <select class="js-select2 form-control form-control-sm form-control-alternative jsMecanicos"
                        id="mecanicos" required>
                        <option value="" disabled selected>SELECCIONE UN MECÁNICO</option>
                        <option v-for="m in mecanicos" v-bind:value="m.id_persona">
                            {{ m.nombre }}
                        </option>
                    </select>
                </div>

            </div>
        </form>
    </div>
</template>

<script>

module.exports = {
    props: [],
    data: function () {
        return {
            mecanicos: []
        }
    },

    created: function () {
        this.asignarMecanico()
    },

    //Asi generamos funciones en VUE
    methods: {
        asignarMecanico: function () {
            axios.get('vehiculos/php/back/servicios/action/asignarMecanico.php', {
                params: {
                    opcion: 1,
                    mecanico: $("mecanicos").val()
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                this.mecanicos = response.data;
                console.log(this.mecanicos)
                $('.jsMecanicos').select2();
                //Si falla da mensaje de error
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        }
    }
}
</script>