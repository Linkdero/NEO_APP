export const retornadireccion = Vue.component("retorna-direccion",{
  props: [],
  template: `

  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      direccion:""
    }
  },
  methods:{
    getDireccionEmpleado: function() {
      axios.get('transportes/model/Transporte', {
        params: {
          opcion:5
        }
      }).then(function (response) {
        this.direccion = response.data;
        this.$emit('enviadireccion', response.data);
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
      axios.get('transportes/model/Transporte', {
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

export const empleadosdireccion = Vue.component("empleados-listado",{
  props: ["arreglo","columna","verificacion","seleccionado", "evento","etiqueta","requerido"],
  template: `
    <div :class="columna">
      <div class="form-group">
        <div class="">
          <div class="">
            <label for="id_ejercicio_ant">{{ label }}</label>
            <i v-if="loading == true" class="fa fa-sync fa-spin"></i>
            <div class="input-group  has-personalizado" >
              <select :id="id" :name="id" class="grupo_empleados js-select2 form-control form-control-sm input-sm" :required="requerido == true" style="width:100%">
                <option v-for="e in empleados" :value="e.id_persona">{{ e.empleado }}</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
  mounted() {
    //console.log('Component mounted.')
  },
  data(){
    return {
      empleados:[],
      label:'',
      id:'',
      loading:false
    }
  },
  methods:{
    getEmpleadosPorDireccion: function() {
      this.loading = true;
      setTimeout(() => {
        console.log('nueva info: : '+this.arreglo.id_direccion);
        this.empleados.length = 0;
        var url = '';
        var parametro = '';
        if(this.verificacion == 7998){
          //directores para actas
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 7998;
          this.label = 'Seleccionar Director: ';
          this.id = 'id_director_financiero';
        }else
        if(this.verificacion == 7999){
          //jefe para actas
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 7999;
          this.label = 'Seleccionar jefe o jefa: ';
          this.id = 'id_jefes_compras';
        }else
        if(this.verificacion == 1010){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 311;
          this.label = 'Asignar empleado para revisión: ';
          this.id = 'id_empleados_list';
        }
        else if(this.verificacion == 9 || this.verificacion == 10 || this.verificacion == 11 || this.verificacion == 12){
          url = 'documentos/php/back/listados/get_accesos';
          parametro = 302;
          this.label = 'Asignar técnico: ';
          this.id = 'id_empleados_list';
        }else{
          this.label = this.etiqueta;
          this.empleados = [];
          //this.empleados = this.arreglo.id_direccion;
          console.log('aldfjñl: : '+this.arreglo.id_direccion);
          url = 'documentos/php/back/listados/get_empleados_por_direccion';
          parametro = this.arreglo.id_direccion;//this.empleados;
          this.id = 'id_empleados_list';
        }
        axios.get(url, {
          params: {
            id_direccion:parametro
          }
        }).then(function (response) {
          this.empleados = response.data;
          this.loading = false;

          $("#id_empleados_list").select2({
            placeholder: "-Seleccionar-",
            allowClear: true
          });
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }, 1100);
    }
  },
  created: function(){

    this.getEmpleadosPorDireccion();

    /*this.evento.$on('recargarListadoDeEmpleados', (valor) => {
      this.getEmpleadosPorDireccion();
    });*/
  }
})
