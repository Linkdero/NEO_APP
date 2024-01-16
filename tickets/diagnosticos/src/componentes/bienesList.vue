<template>
    <div class="col">
        <h5 for="bienes" class="font-weight-semibold">#BIEN:</h5>
        <select class="form-control form-control-sm" id="bienes" required>
            <option></option>
            <option v-for="b in bienes" v-bind:value="b.bien_id">
                {{ b.bien_sicoin_code }}
            </option>
        </select>
    </div>
</template>

<script>
module.exports = {
    props: ["tipo","evento"],
    data: function () {
        return {
            bienes: [],
        };
    },

    //Para que se carguen al inicio las bienes
    mounted: function () {
        this.getbienes();
    },
    methods: {
        getbienes: function () {
            axios.get("tickets/diagnosticos/model/bienesList.php", {
                params: {
                    opcion: 1,
                },
            }).then(
                function (response) {
                    this.bienes = response.data;
                    console.log(this.bienes)
                    setTimeout(() => {
                        $('#bienes').select2({
                            placeholder: 'Bienes',
                            allowClear: true,
                            width: '100%',
                            create: true,
                            sortField: 'text',
                        });
                    }, 100);
                }.bind(this)
            ).catch(function (error) {
                console.log(error);
            });
            if (this.tipo == 1) {
                $('#bienes').on('change', (event) => {
                    let informacionEmpleado = $('#bienes option:selected').text();
                    $("#informacionNumeroBien").text(informacionEmpleado);
                    
                    let i = $(event.target).val();
                    this.evento.$emit('id-bien', i);

                    let found = false;

                    for (let j = 0; j < this.bienes.length; j++) {
                        if (i === this.bienes[j].bien_id) {
                            $('#informacionBien').text(this.bienes[j].bien_descripcion);
                            console.log('encontrado');
                            found = true;
                            break; // Si encontramos una coincidencia, no necesitamos seguir buscando
                        }
                    }
                });
            }
        },
    },
};
</script>