export const retornaprivilegios = Vue.component("privilegios",{
  props: [],
  template: `

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      privilegios:""
    }
  },
  methods:{
    getDireccionEmpleado: function() {
      axios.get('bodega/model/Requisicion', {
        params: {
          opcion:0,
          tipo:1
        }
      }).then(function (response) {
        this.privilegios = response.data;
        this.$emit('enviaprivilegios', response.data);
        //console.log(this.privilegio);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getDireccionEmpleado();
  }
})
