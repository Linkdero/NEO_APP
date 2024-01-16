Vue.component("conductores", {
    props: ["row", "label", "codigo"],
    template: `  
        <combo :row="row" label="Conductor" :codigo="codigo" :arreglo="conductores" tipo="2" requerido="true"></combo>
    `,
    mounted() {
      console.log('Component mounted.')
    },
    data(){
      return {
        conductores:[]
      }
    },
    mounted: function() {
  
    },
    methods:{
        getConductores: function(){
            axios.get('vehiculos/php/back/listados/get_conductores.php', {
                params: {
                //   id_persona: this.id_persona
                }
            }).then(function (response) {
                this.conductores = response.data;
                var codigo = this.codigo;
                setTimeout(() => {
                    $("#"+codigo).select2({

                    });
                        
                }, 400);
            }.bind(this)).catch(function (error) {
                console.log(error);
            });
        }
    },
    created: function(){
        this.getConductores();
    }
  });
