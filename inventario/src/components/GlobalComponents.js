export const privilegios = Vue.component("privilegios",{
  //props: ["id_persona"],
  template: `

  `,
  mounted() {
  },
  data(){
    return {
      privilegio:""
    }
  },
  methods:{
    getPrivilegio: function(){
      axios.get('Inventario/model/Inventario', {
        params:{
          opcion:0
        }
      }).then(function (response) {
        this.privilegio  = response.data;
        this.$emit('sendprivilegio', this.privilegio)
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
  },
  created: function(){
    this.getPrivilegio();
  }
})
