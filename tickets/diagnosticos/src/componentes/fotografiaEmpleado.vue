<template>
    <div class="col">
        <div class="text-center">
            <div class="col-md-3  text-center">
                <div class="img_foto img-contenedor_profile text-center" style="width:100px; height:100px;border-radius:50%;">
                    <div class='img-fluid mb-3'>
                        <img class='img-fluid mb-3 img_foto text-center slide_up_anim ' :src='datoFoto.foto'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
module.exports = {
    props: ["tipo", "id_persona", "evento"],
    data: function () {
        return {
            datoFoto: ''
        };
    },
    mounted: function () {
        this.evento.$on('cambiar-foto', (nuevoValor) => {
            this.getEmpleados(nuevoValor)
        });
    },
    methods: {
        getEmpleados: function (id) {
            axios.get('empleados/php/back/empleado/get_empleado_fotografia.php', {
                params: {
                    id_persona: id
                }
            }).then(function (response) {
                this.datoFoto = response.data;
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        },
    },
};
</script>