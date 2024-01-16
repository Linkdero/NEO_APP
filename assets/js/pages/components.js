Vue.component("menu-opcion", {
  props: ["image", "title", "option", "col"],
  template: `
  <div :class="col" class="block block-link-hover3" v-on:click="emit(option)">
    <a class="u-apps d-flex flex-column">
      <img class="animated fadeInDown img-fluid u-avatar mx-auto mb-2" v-bind:src="image" alt=""></img>
      <h3 class="text-center">{{ title }}</h3>
    </a>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      idOpcion:0
    }
  },
  mounted: function() {

  },
  methods:{
    setOption: function(opc){
      this.idOpcion = opc;
    },
    emit: function(opc) {
			this.$emit('event_child', opc)
		}

  }
});

Vue.component("accion",{
  props:["confirmacion","cancelar"],
  template:`
  <div class="col-sm-12 text-right">
    <button class="btn btn-sm btn-info text-right" v-on:click="emit(confirmacion)"><i class="fa fa-check"></i> Guardar</button>
    <span class="btn btn-sm btn-danger" v-on:click="emit(cancelar)"><i class="fa fa-times"></i> Cancelar</span>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {

    }
  },
  mounted: function() {
  },
  methods:{
    emit: function(opc) {
			this.$emit('event_child', opc);
		}
  },
  created: function(){

  }

})

var datoPeronsa = Vue.component("dato-persona", {
  name: 'dato',
  props: ["icono", "texto", "dato", "tipo"],
  template: `

  <div class="media align-items-center item-persona">
    <i v-if="tipo != 0" class="u-icon u-icon--sm bg-soft-info text-info rounded-circle mr-3" :class="icono"></i>
    <div class="media-body">
      <small class="mb-0">{{ texto }}</small>
      <h5 class="font-weight-semibold">{{ dato }}</h5>
    </div>
  </div>
  `,
});

Vue.component("campo", {
  name:'campo',
  props: ["row", "label", "codigo", "tipo", "requerido","valor"],
  template: `

  <div :class="row">
    <div class="form-group">
      <div class="">
        <div class="">
          <label :for="codigo"> {{ label }}</label>
          <div class=" input-group  has-personalizado" v-if="requerido == 'true'">
          <textarea v-if='tipo == "textarea"' :id="codigo" :name="codigo" class='form-control form-control-sm' :type="tipo" required rows="3" autocomplete="off">{{ valor }}</textarea>
          <input v-else required :id="codigo" :name="codigo" v-bind:value="valor" class='form-control form-control-sm' :type="tipo" autocomplete="off"></input>
          </div>
          <div class=" input-group  has-personalizado" v-else>
          <textarea v-if='tipo == "textarea"' :id="codigo" :name="codigo" class='form-control form-control-sm' :type="tipo" rows="3" autocomplete="off">{{ valor }}</textarea>
          <input v-else :name="codigo" v-bind:value="valor" class='form-control form-control-sm' :type="tipo" autocomplete="off" ></input>
          </div>
        </div>
      </div>
    </div>
  </div>
  `
});

Vue.component("combo", {
  name:'campo',
  props: ["row", "label", "codigo", "arreglo", "tipo", "requerido", "valor"],
  template: `

  <div :class="row">
    <div class="form-group">
      <div class="">
        <div class="">
          <label :for="codigo"> {{ label }}</label>
          <div class=" input-group  has-personalizado"  v-if="requerido == 'true'">
            <select class="js-select2 form-control form-control-sm form-control-alternative" :id="codigo" :name="codigo" v-model="idValor" required autocomplete="off" style="width:100%">
              <option v-if="tipo==1" v-for="a in arreglo" v-bind:value="a.id_plaza" >{{ a.cod_plaza }}</option>
              <option v-if="tipo==2" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
              <option v-if="tipo==3" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
            </select>
          </div>
          <div class=" input-group  has-personalizado"  v-else>
            <select class="js-select2 form-control form-control-sm form-control-alternative" :id="codigo" :name="codigo" v-model="idValor" autocomplete="off" style="width:100%">
              <option v-if="tipo==1" v-for="a in arreglo" v-bind:value="a.id_plaza" >{{ a.cod_plaza }}</option>
              <option v-if="tipo==2" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
              <option v-if="tipo==3" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      idValor:0
    }
  },
  mounted: function() {

  },
  methods:{

  },
  created: function(){
    this.idValor = this.valor;
  }
});

Vue.component("combo-change", {
  name:'campo',
  props: ["row", "label", "codigo", "arreglo", "tipo", "requerido", "valor"],
  template: `

  <div :class="row">
    <div class="form-group">
      <div class="">
        <div class="">
          <label :for="codigo"> {{ label }}</label>
          <div class=" input-group  has-personalizado"  v-if="requerido == 'true'">
            <select class="js-select2 form-control form-control-sm form-control-alternative" :id="codigo" :name="codigo" v-model="idValor" @change="retornaValor($event)" required autocomplete="off" style="width:100%">
              <option v-if="tipo==1" v-for="a in arreglo" v-bind:value="a.id_plaza" >{{ a.cod_plaza }}</option>
              <option v-if="tipo==2" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
              <option v-if="tipo==3" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
            </select>
          </div>
          <div class=" input-group  has-personalizado"  v-else>
            <select class="js-select2 form-control form-control-sm form-control-alternative" :id="codigo" :name="codigo" v-model="idValor" @change="retornaValor($event)" autocomplete="off" style="width:100%">
              <option v-if="tipo==1" v-for="a in arreglo" v-bind:value="a.id_plaza" >{{ a.cod_plaza }}</option>
              <option v-if="tipo==2" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
              <option v-if="tipo==3" v-for="i in arreglo" v-bind:value="i.id_item" >{{ i.item_string }}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      idValor:0
    }
  },
  methods:{
    retornaValor: function(event){
      eventBus.$emit('valorSeleccionado', event.currentTarget.value);
    }

  },
  created: function(){
    this.idValor = this.valor;
  }
});

Vue.component("lugar-seleccion", {
  name:'formulario',
  props: ["row1","row2","depto","muni","lugar","tipo"],
  template: `
  <div :class="row1">
    <div class="row">
      <div :class="row2">
        <div class="form-group">
          <div class="">
            <div class="">
              <label> Departamento:</label>
              <div class=" input-group  has-personalizado" >
              <select id="departamento" name="departamento" class='form-control form-control-sm' v-model="idDepartamento" @change="getMunicipios" required>
                <option v-for="dep in departamentos"v-bind:value="dep.dep_id" >{{ dep.dep_string }}</option>
              </select>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div :class="row2">
        <div class="form-group">
          <div class="">
            <div class="">
              <label>Muncipio:</label>
              <div class=" input-group  has-personalizado" >
                <select id="municipio" name="municipio" v-model="idMunicipio" class='form-control form-control-sm' required @change="getLugares">
                  <option v-for="muni in municipios" v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div :class="row2" v-if="tipo == 0">
        <div class="form-group">
          <div class="">
            <div class="">
              <label>Lugar:</label>
              <div class=" input-group  has-personalizado" >
                <select id="poblado" name="poblado" v-model="idLugar" class='form-control form-control-sm' >
                  <option v-for="lugar in lugares" v-bind:value="lugar.lugar_id" >{{ lugar.lugar_string }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div :class="row2" v-if="tipo == 1">
        <div class="form-group">
          <div class="">
            <div class="">
              <label>Lugar:</label>
              <div class=" input-group  has-personalizado" >
                <input class="form-control form-control-sm" type="text" id="lpoblado" name="lpoblado" required></input>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      idDepartamento:0,
      idMunicipio:0,
      idLugares:0,
      departamentos:[],
      municipios:[],
      lugares:[]
    }
  },
  methods:{
    getDepartamentos: function(){
      axios.get('viaticos/php/back/listados/destinos/get_departamento',{
        params: {
          pais:'GT'
        }
      })
      .then(function (response) {
        this.departamentos = response.data;
      }.bind(this));
    },
    getMunicipios: function(){
      axios.get('viaticos/php/back/listados/destinos/get_municipio',{
        params: {
          departamento:this.idDepartamento
        }
      })
      .then(function (response) {
        this.municipios = response.data;
      }.bind(this));
    },
    getLugares: function(){
      axios.get('viaticos/php/back/listados/destinos/get_aldea',{
        params: {
          municipio:this.idMunicipio
        }
      })
      .then(function (response) {
        this.lugares = response.data;
        $("#poblado").select2({
          placeholder: "Seleccionar lugar",
          allowClear: true
        });
      }.bind(this));
    }
  },
  created: function(){
    this.idDepartamento = this.depto;
    this.idMunicipio = this.muni;
    this.idLugar = this.lugar
    this.getDepartamentos();
    this.getMunicipios();
    this.getLugares();
  }
});

Vue.component("checkbox",{
  props:["row", "label","codigo", "valor"],
  template:`
    <div class="text-right" :class="row">
      <label class="text-white">..</label>
      <div class=" input-group  has-personalizado" >
        <label class="css-input switch switch-success"><input class="chequeado" :id="codigo" v-model="valor" :name="codigo" type="checkbox"/><span></span> {{ label }}</label>
      </div>
    </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {

    }
  },
  mounted: function() {
  },
  methods:{
    emit: function(opc) {
			this.$emit('event_child', opc);
		}
  },
  created: function(){

  }

})
//foto_empleado
Vue.component("fotografia-empleado", {
  props:["id_persona","tipo"],
  template:`
  <div class="col-sm-6" v-if="tipo == 1" >
    <div class="row">
      <div class="col-sm-3">
        <div class="text-center" >
          <div class="col-md-4  text-center" >
            <div class="img_foto img-contenedor_profile text-center" style="width:80px; height:80px;border-radius:50%;">
              <div class='img-fluid mb-3'>
              <img class='img-fluid mb-3 img_foto text-center slide_up_anim ' :src='datoFoto.foto' >
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  `,
  mounted() {
    console.log('Component mounted.');
  },
  data(){
    return {
      datoFoto:"",
      persona:""
    }
  },
  mounted: function() {

  },
  methods:{
    getFotografia: function(){
      setTimeout(() => {
        if(this.id_persona > 0){
          axios.get('empleados/php/back/empleado/get_empleado_fotografia.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            this.datoFoto = response.data;
          }.bind(this)).catch(function (error) {
            console.log(error);
          });
        }
      }, 700);
    },
  },
  created: function(){
    this.getFotografia();
  }
});

Vue.component("combo-items", {
  props: ["label", "col","id_catalogo","codigo", "valor"],
  template: `
    <combo :row="col" :label="label" :codigo="codigo" :arreglo="items" tipo="2" requerido="true" :valor="valor"></combo>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      items:[]
    }
  },
  mounted: function() {
  },
  methods:{
    getItems: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:this.id_catalogo,
          tipo:0
        }
      })
      .then(function (response) {
        this.items = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getItems();
  }
});
