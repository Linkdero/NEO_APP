<template>
    <div class="col">
        <h5 for="direcciones" class="font-weight-semibold">DIRECCIÓN DEL SOLICITANTE:</h5>
        <select class="form-control form-control-sm" id="direcciones" required>
            <option value="" disabled selected>SELECCIONE UNA DIRECCIÓN</option>
            <option v-for="d in direcciones" v-bind:value="d.id_direccion">
                {{ d.nombre }}
            </option>
            <option value="1000000">APOYO</option>
        </select>
    </div>
</template>

<script>
module.exports = {
    props: ["tipo"],
    data: function () {
        return {
            direcciones: [],
        };
    },

    //Para que se carguen al inicio las direcciones
    mounted: function () {
        this.getDirecciones();
    },
    methods: {
        getDirecciones: function () {
            axios.get("tickets/model/tickets.php", {
                params: {
                    opcion: 2,
                    idDir: 000
                },
            }).then(
                function (response) {
                    this.direcciones = response.data;
                }.bind(this)
            ).catch(function (error) {
                console.log(error);
            });
            if (this.tipo == 1) {
                $('#direcciones').on('change', (event) => {
                    let informacionDireccion = $('#direcciones option:selected').text();
                    $("#informacionDireccion").text(informacionDireccion); // Utiliza .text() en lugar de .val()
                });
            }
        },
    },
};
</script>