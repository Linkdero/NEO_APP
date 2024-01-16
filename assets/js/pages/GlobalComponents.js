export const direcciones = Vue.component("direcciones",{
  props: ["columna","tipo","evento","filtro","codigo"],
  template: `
  <!--inicio-->

  <combo :row="columna" label="Seleccionar dirección" :codigo="codigo" :arreglo="direcciones" tipo="3" requerido="true"></combo>


  <!-- fin -->
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      direcciones:[],
      direccion:""
    }
  },
  methods:{
    getDirecciones: function() {
      var thisInstance = this;
      axios.get('documentos/php/back/listados/get_direcciones', {
        params: {

        }
      }).then(function (response) {
        this.direcciones = response.data;
        setTimeout(() => {
          $('#id_direccion').on('change', function (e) {
            var id = $(this).attr('id');
            //console.log(e.para);
            thisInstance.evento.$emit('sendDireccion', $('#id_direccion').val());
            if(thisInstance.filtro == 2){
              thisInstance.evento.$emit('sendDireccionDepartamentos', $('#id_direccion').val());
            }
          });
        }, 1000);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getDireccionById: function(valor) {
      axios.get('documentos/php/back/functions/get_direccion_by_id', {
        params: {
          id_direccion:valor
        }
      }).then(function (response) {
        this.direccion = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });



    }
  },
  created: function(){
    this.getDirecciones();
    this.evento.$on('valorSeleccionado', (valor) => {
      console.log(valor);
      if(this.tipo == 1){
        this.evento.$emit('obtenerDireccion', valor);
      }
      //this.getDireccionById(valor);
    });
  }
})

export const empleados = Vue.component("empleados-listado",{
  props: ["arreglo","columna","verificacion","seleccionado","direccion","evento","codigo"],
  template: `
    <div :class="columna">
      <div class="form-group">
        <div class="">
          <div class="">
            <label for="id_ejercicio_ant">{{ label }}</label>
            <i v-if="loading == true" class="fa fa-sync fa-spin"></i>
            <div class="input-group  has-personalizado" >
              <select :id="codigo" :name="codigo" class="grupo_empleados js-select2 form-control form-control-sm input-sm" required style="width:100%">
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
    getEmpleadosPorDireccion: function(direccion) {
      this.loading = true;
      setTimeout(() => {
        //this.empleados.length = 0;
        var url = '';
        var parametro = direccion;
        this.label = 'Seleccionar empleado: ';
        this.empleados = [];
        //this.empleados = this.arreglo.id_direccion;
        url = 'inc/functions/get_empleados_direccion';
        //parametro = this.empleados;
        this.id = 'id_empleados_list';


        axios.get(url, {
          params: {
            id_direccion:parametro
          }
        }).then(function (response) {
          this.empleados = response.data;
          this.loading = false;

          $("#"+this.codigo).select2({
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
    this.getEmpleadosPorDireccion(this.direccion);

    this.evento.$on('sendDireccion', (direccion) => {
      this.getEmpleadosPorDireccion(direccion);
    });
  }
})


export const departamentos = Vue.component("unidades",{
  props: ["columna","evento","codigo"],
  template: `
  <!--inicio-->
  <div :class="columna">
    <div class="form-group">
      <div class="">
        <div class="">
          <label for="id_unidad">Unidad*</label>
          <div class=" input-group  has-personalizado">
            <select class="js-select2 form-control form-control-sm" :id="codigo" :name="codigo">
              <option v-for="u in unidades" v-bind:value="u.id_departamento"><strong>{{ u.id_departamento }}</strong> - {{ u.nombre}}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- fin -->
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      unidades:[]
    }
  },
  methods:{
    getUnidades: function(direccion) {
      var parametro = direccion;
      axios.get('inc/functions/get_departamentos', {
        params: {
          id_direccion:parametro
        }
      }).then(function (response) {
        this.unidades = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getUnidades();
    this.evento.$on('sendDireccionDepartamentos', (direccion) => {
      this.getUnidades(direccion);
    });
  }
})

export const retornadireccion = Vue.component("direccionemleado",{
  props: [],
  template: `
  `,
  mounted() {
  },
  data(){
    return {
      direccion:""
    }
  },
  methods:{
    getDireccionEmpleado: function() {
      axios.get('inc/functions/get_direccion_empleado', {
        params: {
        }
      }).then(function (response) {
        this.direccion = response.data;
        this.$emit('enviardireccion', response.data);
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
