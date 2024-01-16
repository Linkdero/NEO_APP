<template>
    <div class="col">
        <h5 for="empleados" class="font-weight-semibold">USUARIO QUE SOLICITA:</h5>
        <select class="form-control form-control-sm" id="empleados" required>
            <option value="" disabled selected>SELECCIONE USUARIO</option>
            <option v-for="e in empleados" v-bind:value="e.idNombre">
                {{ e.nombre }}
            </option>
        </select>
    </div>
</template>
  
<script>
module.exports = {
    props: ["tipo", "evento"],
    data: function () {
        return {
            empleados: [],
            idDireccion: 0
        };
    },
    mounted: function () {
        $('#direcciones').on('change', (event) => {
            const valorSeleccionado = $(event.target).val();
            this.getEmpleados(valorSeleccionado)
        });
    },
    methods: {
        getEmpleados: function (id) {
            var thes = this;
            axios.get("tickets/model/tickets.php", {
                params: {
                    opcion: 23,
                    dir: id,
                },
            }).then(function (response) {
                this.empleados = response.data;
                setTimeout(() => {
                    $('#empleados').select2({
                        placeholder: 'Empleados',
                        allowClear: true,
                        width: '100%',
                        create: true,
                        sortField: 'text',
                    });
                }, 200);
            }.bind(this)
            ).catch(function (error) {
                console.log(error);
            });
            if (this.tipo == 1) {
                $('#empleados').on('change', (event) => {
                    const nuevoValor = $(event.target).val();
                    this.evento.$emit('id-empleado', nuevoValor);
                    let informacionEmpleado = $('#empleados option:selected').text();
                    $("#informacionEmpleado").text(informacionEmpleado); // Utiliza .text() en lugar de .val()
                });
            }
        },
    },
};
</script>