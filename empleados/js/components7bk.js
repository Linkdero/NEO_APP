Vue.component("menu-perfil", {
  props: ["opcion","privilegio"],
  template: `
  <div class="btn-group btn-group-toggle">
    <a data-tooltip="Detalle" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-01 active" href="#h_perfil" @click="down('','btn-01')" v-if="opcion != 19">
      <input type="radio"><i class="fa fa-user"></i></input>
    </a>
    <a data-tooltip="Dirección" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-02" href="#h_contacto" @click="down('','btn-02')" v-if="opcion != 19">
      <input type="radio"><i class="fa fa-map-marker-alt"></i></input>
    </a>
    <a data-tooltip="Escolaridad" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-03" href="#h_escolaridad" @click="down('','btn-03')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-graduation-cap"></i></input>
    </a>
    <a data-tooltip="Familia" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-04" href="#h_familiares" @click="down('','btn-04')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-users"></i></input>
    </a>
    <a data-tooltip="Teléfonos" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-05" href="#h_telefonos" @click="down('','btn-05')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-phone"></i></input>
    </a>
    <a data-tooltip="Residencia" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-06" href="#h_direcciones" @click="down('','btn-06')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-home"></i></input>
    </a>
    <a data-tooltip="Documentos" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-07" href="#h_documentos" @click="down('','btn-07')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-id-card-alt"></i></input>
    </a>
    <a data-tooltip="Cuentas" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-08" href="#h_cuentas" @click="down('','btn-08')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-credit-card"></i></input>
    </a>
    <a data-tooltip="Vacunas" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-09" href="#h_vacunas" @click="down('','btn-09')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-syringe"></i></input>
    </a>
    <a data-tooltip="Capacitaciones" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-11" href="#h_capacitaciones" @click="down('','btn-11')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-chalkboard"></i></input>
    </a>
    <a data-tooltip="Experiencia" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-12" href="#h_trabajos" @click="down('','btn-12')" v-if="opcion != 19">
      <input type="radio" ><i class="fa fa-briefcase"></i></input>
    </a>
    <label data-tooltip="Editar información" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile btn-10" @click="down('','btn-10',19)" v-if="privilegio.reclu == true || privilegio.acciones == true">
      <input type="radio" ><i class="fa fa-user-edit"></i></input>
    </label>
    <label data-tooltip="Catálogos" class="alerta_no tooltip_noti btn btn-outline-info btn-sm btn-profile" @click="down('','btn-x',20)">
      <input type="radio" ><i class="fa fa-tasks"></i></input>
    </label>
    <label class="btn btn-outline-info btn-sm btn-profile out" @click="down('','btn-x')">
      <input type="radio" ><i class="fa fa-times"></i></input>
    </label>
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
    down: function(div,boton,opc) {
      if(boton != 'btn-x'){
        $('.btn-profile').removeClass('active');
        $('.'+boton).addClass('active');
      }


      if(opc == 19 || opc == 20){
        eventBus.$emit('regresarPrincipal', opc);
      }
      /*var element = document.querySelector("#"+div);*/

      // smooth scroll to element and align it at the bottom
      //element.scrollIntoView({ behavior: 'smooth', block: 'nearest'});
      //$('.scrollable-div-persona').scrollTop($('section.ok').scrollTop());
      //$('.scrollable-div-persona').animate({scrollTop:  $('#'+id).offset().top }, 500);

    },
    setOption: function(opc){
      this.idOpcion = opc;
    },
    emit: function(opc) {
			this.$emit('event_child', opc)
		}

  }
});
Vue.component("fotografia", {
  props:["id_persona","tipo"],
  template:`
  <div  v-if="tipo == 1">
    <div class="userProfileInfo card" style="height:510px">
      <div class="image text-center slide_up_anim">
      <img class='mb-3 ' style="width:100%" :src='datoFoto.foto' >
        <p v-if="datoFoto.cambio" href="" title="image" @click="subirFoto()" class="editImage">
          <i class="fa fa-camera"></i>
        </p>
      </div>
      <div class="box" >
        <!--<div class="name"><strong>{{ persona.nombres }} {{ persona.apellidos }}</strong></div>-->
        <div class="name"><strong>{{ persona.nombres }} {{ persona.apellidos }}</strong></div>
        <div class="info "style="width:100%; margin-left:auto; margin-right:auto;" >

          <!--<span><i class="fa fa-fw fa-envelope"></i> {{ persona.email}}</span>
          <span><i class="fa fa-fw fa-phone"></i> {{ persona.email}}</span>
          <span class="btn btn-sm btn-soft-info"><i class="fa fa-fw fa-user-edit"></i> Editar </span>-->
          <span><i class="">Edad: </i> {{ persona.edad }} años</span>
        </div>

      </div>
    </div>
  </div>
  <div class="col-sm-6" v-else-if="tipo == 2">
    <div class="row">
      <div class="col-sm-3">
        <div class="text-center" >
          <div class="col-md-4  text-center" >
            <div class="img_foto img-contenedor_profile text-center" style="border-radius:50%;">
              <div class='img-fluid mb-3'>
              <img class='img-fluid mb-3 img_foto text-center slide_up_anim ' :src='datoFoto.foto' >
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class="col-sm-9 ">
        <dato-persona icono="fa fa-user" texto="Nombres y Apellidos" :dato="persona.nombres_apellidos"></dato-persona>
        <dato-persona icono="fa fa-id-card" texto="No. de gafete" :dato="persona.id_persona"></dato-persona>
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
    },
    getPersona: function(){
      axios.get('empleados/php/back/persona/get_persona_by_id', {
        params: {
          id_persona:this.id_persona
        }
      }).then(function (response) {
        this.persona = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    subirFoto: async function(){
      const thisInstance = this;
      //alert('Works!!!')
      const { value: file } = await Swal.fire({
        title: 'Seleccionar imagen',
        input: 'file',
        id: 'id_foto',
        inputAttributes: {
          'accept': 'image/jpeg',
          'aria-label': 'Subir foto de perfil'
        }
      })

      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          console.log(e.target);
          //if(this.validarExtension(e.target.result)) {
            Swal.fire({
              title: 'Foto seleccionada',
              imageUrl: e.target.result,
              imageAlt: 'Foto seleccionada',
              showCancelButton: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, Actualizar!'
            }).then((result) => {
              if (result.value) {

                $.ajax({
                  type: "POST",
                  url: "empleados/php/back/persona/subir_foto.php",
                  dataType: 'json',
                  data: {
                    fotografia: e.target.result,
                    id_persona: this.id_persona
                  }, //f de fecha y u de estado.
                  beforeSend:function(){
                    //$('#response').html('<span class="text-info">Loading response...</span>');
                    //alert('message_before')
                  },
                  success:function(data){
                    //alert(data);
                    if(data.msg == 'OK'){
                      $('#id_cambio').val(1);
                      Swal.fire({
                        type: 'success',
                        title: 'Empleado creado',
                        showConfirmButton: false,
                        timer: 1100
                      });

                      thisInstance.getFotografia();
                    }else{
                      Swal.fire({
                        type: 'error',
                        title: data.msg,
                        showConfirmButton: false,
                        timer: 1100
                      });
                    }
                  }
                }).done( function() {
                }).fail( function( jqXHR, textSttus, errorThrown){
                  alert(errorThrown);
                });
              }
            })
          /*}else{
            Swal.fire({
              type: 'error',
              title: 'La extensión no es válida Su fichero tiene de extensión:',
              showConfirmButton: true
            });
          }*/

        }
        reader.readAsDataURL(file)
      }
    },
    validarExtension: function(datos) {
      console.log(datos);
      var extensionesValidas = ".jpg";
    	var ruta = datos;
    	var extension = ruta.substring(ruta.lastIndexOf('.') + 1).toLowerCase();
    	var extensionValida = extensionesValidas.indexOf(extension);

    	if(extensionValida < 0) {

        return false;
      } else {
        return true;
      }
    }
  },
  created: function(){
    this.getFotografia();
    this.getPersona();

    eventBus.$on('recargarPersona', () => {
      this.getPersona();
    });

    eventBus.$on('regresarPadre', (opc) => {
      eventBus.$emit('regresarPrincipal', opc);
    });

  }
});

Vue.component("plazas-disponibles", {
  name:"plazasDisponibles",
  props:["row"],
  template:`
  <div class="col-sm-12">
    <div class="form-group">

      <div class="">
        <div class="">
          <label for="id_plaza_pl">Plaza*</label>
          <div class=" input-group  has-personalizado" >
            <select class="js-select2 form-control form-control-sm chosen-select-width" v-model="idPlaza" @change="getPlazaSeleccionada()" id="id_plaza_pl" name="id_plaza_pl" style="width:100%" required>
              <option v-for="p in plazasDisponibles" v-bind:value="p.id_plaza" >{{ p.plaza_string}}</option>
            </select>
          </div>
        </div>
      </div>

      <div class="timeline animated fadeInUp" v-if="idPlaza > 0" style="z-index:10;position:absolute; margin-top:-18rem;margin-left:0rem; width:100rem;">
        <div class="row no-gutters justify-content-end justify-content-md-around align-items-start  timeline-nodes">
          <div class="col-10 col-md-5 order-3 order-md-1 timeline-content">
            <h3 class=" text-light">Puesto: {{plazaDetalle.cargo}}</h3>
            <p>
              <div class="row">
                <div class="col-sm-8">
                  <div class="col-sm-12">
                    <h5>1. - {{plazaDetalle.secretaria_n}}</h5>
                    <h5>2. - {{plazaDetalle.subsecretaria_n}}</h5>
                    <h5>3. - {{plazaDetalle.direccion_n}}</h5>
                    <h5>4. - {{plazaDetalle.subdireccion_n}}</h5>
                    <h5>5. - {{plazaDetalle.departamento_n}}</h5>
                    <h5>6. - {{plazaDetalle.seccion_n}}</h5>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="pw_content">
                    <div class="pw_header">
                      <h5 class="text-muted">Codigo Plaza: {{plazaDetalle.cod_plaza}}</h5>
                    </div>
                    <div class="pw_header">
                      <h5 class="text-muted">Codigo Puesto: {{plazaDetalle.cod_puesto}}</h5>
                    </div>

                    <div class="pw_header">
                      <span>{{plazaDetalle.sueldo}}</span>
                      <small class="text-muted">Sueldo + Bonos</small>
                      <small class="text-success">{{plazaDetalle.estado}}</small>
                    </div>
                  </div>
                </div>
              </div>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>`,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      plazasDisponibles:[],
      idPlaza:0,
      plazaDetalle:""
    }
  },
  mounted: function() {

  },
  methods:{
    getPlazasDisponibles: function(){
      axios.get('empleados/php/back/listados/get_plazas_disponibles.php', {
        params: {
          }
        }).then(function (response) {
          this.plazasDisponibles = response.data;
          $("#id_plaza_pl").select2({
          });
          const thisInstance = this;
          $('#id_plaza_pl').on("change",function(){
            this.idPlaza = 1;
            thisInstance.getPlazaSeleccionada($(this).val());
            //console.log('Plaza : '+$(this).val());
          });
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
    },
    getPlazaSeleccionada:function(id){
      this.idPlaza = id;
      axios.get('empleados/php/back/plazas/get_plaza_by_id.php', {
        params: {
          id_plaza: id
        }
      }).then(function (response) {
        if(response.data.id_plaza!=null){
          //$('#plazaDetalle').show();
          this.plazaDetalle = response.data;
        }else{
          //$('#plazaDetalle').hide();
        }
        //alert(response.data);
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getPlazasDisponibles();
  }
});

Vue.component("formulario-funcional", {
  name:'formulario',
  props: ["row1","row2", "arreglo","tipo_accion","tipo"],
  template: `
  <div class='row'>
  <!-- inicio -->
  <div class="col-sm-4" v-if="tipo == 'contrato1'">
    <div class="form-group">
      <div class="">
        <div class="">
          <label for="id_categoria_ct">Categoria*</label>
          <select id="id_categoria_ct" name="id_categoria_ct" v-model="idCategoria" class="form-control form-control-sm form-control-alternative" required>
            <option value="">-- Seleccionar --</option>
            <option value="1053">CATEGORIA I</option>
            <option value="1054">CATEGORIA II</option>
            <option value="1055">CATEGORIA III</option>
            <option value="1056">CATEGORIA IV</option>
            <option value="1057">CATEGORIA V</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <!-- fin -->
  <div :class="row1">
    <div class="form-group">
      <div class="">
        <div class="">
          <label> Secretaría:</label>
          <div class=" input-group  has-personalizado" >
          <select id="idSecretariaF" name="idSecretariaF" v-model='idSecretaria' class='form-control form-control-sm' disabled>
            <option v-for='data in secretarias' :value='data.id_direccion'>{{ data.direccion_string }}</option>
          </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div :class="row1">
    <div class="form-group">
      <div class="">
        <div class="">
          <label>Sub Secretaría:*</label>
          <div class=" input-group  has-personalizado" >
            <select id="idSubSecretariaF" name="idSubSecretariaF" class='form-control form-control-sm' v-model='idSubSecretaria' @change='getDirecciones()' required>
              <option v-for='data in subSecretarias' :value='data.id_direccion'>{{ data.direccion_string }}</option>
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
          <label>Dirección:</label>
          <div class=" input-group  has-personalizado" >
            <select id="idDireccionF" name="idDireccionF" class='form-control form-control-sm' v-model='idDireccion' @change='getSubDirecciones()'>
              <option v-for='data in direcciones' :value='data.id_direccion'>{{ data.direccion_string }}</option>
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
          <label>Sub dirección:</label>
          <div class=" input-group  has-personalizado" >
          <select id="idSubDireccionF" name="idSubDireccionF" class='form-control form-control-sm' v-model='idSubDireccion' @change='getDepartamentos()'>
            <option v-for='data in subDirecciones' :value='data.id_direccion'>{{ data.direccion_string }}</option>
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
          <label>Departamento:</label>
          <div class=" input-group  has-personalizado" >
          <select id="idDepartamentoF" name="idDepartamentoF" class='form-control form-control-sm' v-model='idDepartamento' @change='getSecciones()'>
            <option v-for='data in departamentos' :value='data.id_direccion'>{{ data.direccion_string }}</option>
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
          <label>Sección:</label>
          <div class=" input-group  has-personalizado" >
          <select id="idSeccionF" name="idSeccionF" class='form-control form-control-sm' v-model='idSeccion'>
            <option value='0' >Seleccionar Seccion</option>
            <option v-for='data in secciones' :value='data.id_direccion'>{{ data.direccion_string }}</option>
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
          <label>Puesto:</label>
          <div class=" input-group  has-personalizado" >
          <select id="idPuestoF"  name="idPuestoF" class='form-control form-control-sm' v-model="puestoF" required width="100%" style="width:100%">
            <option v-for='data in puestos' :value='data.id_item'>{{ data.item_string }}</option>
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
          <label>Nivel:</label>
          <div class=" input-group  has-personalizado">
          <select id="idNivelF" name="idNivelF" class='form-control form-control-sm' v-model="nivelF" required>
            <option v-for='data in niveles' :value='data.id_direccion'>{{ data.direccion_string }}</option>
          </select>
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
      idCategoria:0,
      idSecretaria:4,
      secretarias:[],
      idSubSecretaria: 0,
      subSecretarias: [],
      idDireccion: 0,
      direcciones: [],
      idSubDireccion:0,
      subDirecciones:[],
      idDepartamento:0,
      departamentos:[],
      idSeccion:0,
      puestoF:"",
      nivelF:"",
      secciones:[],
      puestos:[],
      niveles:[],
    }
  },
  methods:{
    getSecretarias: function(){
      axios.get('empleados/php/back/listados/get_direcciones',{
        params: {
          nivel:2,
          tipo:887,
          superior:0,
          opcion:1
        }
      })
      .then(function (response) {
        this.secretarias = response.data;
      }.bind(this));
    },
    getSubSecretarias: function(){
      axios.get('empleados/php/back/listados/get_direcciones',{
        params: {
          nivel:3,
          tipo:887,
          superior:4,
          opcion:1
        }
      })
      .then(function (response) {
        this.subSecretarias = response.data;
      }.bind(this));
    },
    getDirecciones: function() {
      axios.get('empleados/php/back/listados/get_direcciones',{
        params: {
          nivel:4,
          tipo:887,
          superior:this.idSubSecretaria,//$('#id_subsecretaria_f'+fin).val(),
          opcion:1
        }
      }).then(function(response){
        this.direcciones = response.data;
      }.bind(this));
    },
    getSubDirecciones: function() {
      axios.get('empleados/php/back/listados/get_direcciones.php', {
        params: {
          nivel:this.idDireccion,//$('#id_direccion_f'+fin).val(),
          tipo:887,
          superior:0,
          opcion:2
        }
      })
      .then(function (response) {
        this.subDirecciones = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getDepartamentos: function(){
      axios.get('empleados/php/back/listados/get_direcciones.php', {
        params: {
          nivel:this.idSubDireccion,//$('#id_direccion_f'+fin).val(),
          tipo:887,
          superior:0,
          opcion:3
        }
      }).then(function (response) {
        this.departamentos = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getSecciones: function(){
      axios.get('empleados/php/back/listados/get_direcciones.php', {
        params: {
          nivel:this.idDepartamento,//$('#id_direccion_f'+fin).val(),
          tipo:887,
          superior:0,
          opcion:4
        }
      })
      .then(function (response) {
        this.secciones = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    get_puestos: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:31,
          tipo:0
        }
      })
      .then(function (response) {
        this.puestos = response.data;
        setTimeout(() => {
          $("#idPuestoF").select2({
          });

        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    get_niveles_f: function(){
      axios.get('empleados/php/back/listados/get_direcciones.php', {
        params: {
          nivel:0,
          tipo:887,
          superior:0,
          opcion:5
        }
      })
      .then(function (response) {
        this.niveles = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    establecerCodigos: function(){

      if(this.tipo_accion == 2){
        this.getDirecciones();

        setTimeout(() => {
          console.log(this.arreglo.puesto_f);
          this.idCategoria = this.arreglo.categoria_f;
          this.idSubSecretaria = this.arreglo.subsecretaria_f;
          this.idDireccion = this.arreglo.direccion_f;
          this.idSubDireccion  = this.arreglo.subdireccion_f;
          this.idDepartamento  = this.arreglo.departamento_f;
          this.idSeccion = this.arreglo.seccion_f;
          this.puestoF = this.arreglo.puesto_f;
          this.nivelF = this.arreglo.nivel_f;

          this.getSubDirecciones();
          this.getDepartamentos();
          this.getSecciones();
          this.get_puestos();
        },500);

      }else{
        this.get_puestos();
      }
    }
  },
  created: function(){

    this.getSecretarias();
    this.getSubSecretarias();
    this.get_niveles_f();
    this.establecerCodigos();
    eventBus.$on('recargarPuestosFuncional', () => {
      this.get_puestos();
    });
  }
});


Vue.component("detalle-puesto", {
  props:["id_persona","apy"],
  template:`
  <div class="col-sm-6" v-if="tipoPlaza==7">
    <div class="row">
    <dato-persona row="col-sm-12" icono="fa fa-hotel" texto="Partida presupuestaria" :dato="datosPlaza.partida"></dato-persona>
    <dato-persona icono="fa fa-file" texto="Código de la plaza" :dato="datosPlaza.cod_plaza"></dato-persona>
    <dato-persona icono="far fa-calendar-check" texto="Fecha de toma de posesión" :dato="datosPlaza.fecha_toma_posesion"></dato-persona>
    </div>
  </div>
  <div class="col-sm-6" v-else-if="tipoPlaza==1075">
    <div class="row">
    <dato-persona row="col-sm-12" icono="fa fa-hotel" texto="No. de contrato" :dato="datosPlaza.cod_plaza"></dato-persona>
    <dato-persona icono="fa fa-file" texto="Renglón" :dato="datosPlaza.renglon"></dato-persona>
    <!--<dato-persona icono="far fa-calendar-check" texto="Fecha inicio" :dato="datosPlaza.fecha_inicio"></dato-persona>
    <dato-persona icono="far fa-calendar-times" texto="Fecha de finalización" :dato="datosPlaza.fecha_fin"></dato-persona>-->
    </div>
  </div>
  <div v-else-if="apy == 2312">
    <br>
    <h1>Personal de apoyo</h1>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      datosPlaza:"",
      tipoPlaza:0,
      childMessage: ''
    }
  },
  mounted: function() {

  },
  methods:{
    getDatosPlaza: function(){
      if(this.id_persona > 0 && this.apy != 2312){
        axios.get('empleados/php/back/plazas/plaza_detalle_empleado.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.datosPlaza  = response.data;
          this.tipoPlaza = response.data.tipo;
          this.$emit('event_child', response.data)
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },

    getTipoPlaza: function(){
      return this.tipoPlaza;
    }
  },
  created: function(){
    this.getDatosPlaza();
    eventBus.$on('recargarPuesto', () => {
      this.getDatosPlaza();
    });
  }
});

Vue.component("detalle-asignacion-plaza", {
  props:["id_persona"],
  template:`

  <div class="row" v-if="empleado.secretarian !='Sin asignación' || empleado.estado == 2312">
    <!--<span class="col-sm-1 letra" ><span class="fa fa-user text-muted"></span></span>-->
    <div class="col-sm-6" v-if="empleado.tipo_contrato==7 && empleado.estado != 2312">
      <!--<hr>-->
      <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-user-tie"></i>Datos Nominales
      <dato-persona icono="fa fa-hotel" texto="Secretaría" :dato="empleado.secretarian"></dato-persona>
      <dato-persona icono="fa fa-building" texto="Subsecretaría" :dato="empleado.subsecretarian"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Dirección" :dato="empleado.direccionn"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Subdirección" :dato="empleado.subdireccionn"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Departamento" :dato="empleado.departamenton"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Seccion" :dato="empleado.seccionn"></dato-persona>
      <dato-persona icono="fa fa-user" texto="Puesto Nominal" :dato="empleado.pueston"></dato-persona>
      <!--<hr>-->
    </div>
    <div class="col-sm-6">
      <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-user"></i>Datos Funcionales<!--<button class="btn-info btn-sm" @click="recargaListadoEmpleados()">Recargar</button>-->
      <dato-persona icono="fa fa-hotel" texto="Secretaría" :dato="empleado.secretariaf"></dato-persona>
      <dato-persona icono="fa fa-building" texto="Subsecretaría" :dato="empleado.subsecretariaf"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Dirección" :dato="empleado.direccionf"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Subdirección" :dato="empleado.subdireccionf"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Departamento" :dato="empleado.departamentof"></dato-persona>
      <dato-persona icono="fa fa-home" texto="Seccion" :dato="empleado.seccionf"></dato-persona>
      <dato-persona icono="fa fa-user" texto="Puesto Funcional" :dato="empleado.puestof"></dato-persona>
    </div>
    <div class="col-sm-6" v-if="empleado.tipo_contrato==1075">
      <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-user"></i>Datos del contrato
      <dato-persona icono="far fa-calendar-check" texto="Fecha del contrato" :dato="empleado.fecha_contrato"></dato-persona>
      <dato-persona icono="far fa-calendar-check" texto="Fecha de inicio" :dato="empleado.fecha_inicio"></dato-persona>
      <dato-persona icono="far fa-calendar-times" texto="Fecha de finalización" :dato="empleado.fecha_finalizacion"></dato-persona>
      <dato-persona icono="fa fa-file" texto="No. de Acuerdo" :dato="empleado.nro_acuerdo_aprobacion"></dato-persona>
      <dato-persona icono="far fa-calendar-check" texto="Fecha del Acuerdo" :dato="empleado.fecha_acuerdo_aprobacion"></dato-persona>
      <dato-persona icono="fa fa-money-bill-wave-alt" texto="Total" :dato="empleado.monto_contrato"></dato-persona>
      <dato-persona icono="fa fa-money-bill-wave-alt" texto="Monto mensual" :dato="empleado.monto_mensual"></dato-persona>
    </div>
    <div class="col-sm-6" v-if="empleado.tipo_persona == 1052">
      <button class="btn btn-sm btn-danger" @click="finalizarApoyo()"><i class="fa fa-times-circle"></i> Finalizar</button>
    </div>
  </div>
  <div v-else class="text-center">
    <h1>Empleado inactivo</h1>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      empleado:"",
      componentKey: 0
    }
  },
  mounted: function() {

  },
  methods:{
    getAsignacionDetalle: function(){
      if(this.id_persona > 0){

        axios.get('empleados/php/back/puestos/puesto_detalle.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.empleado = response.data;
          this.$emit('event_child', response.data);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    finalizarApoyo: function(){
      Swal.fire({
        title: '<strong>¿Desea finalizar a la persona de apoyo?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Finalizar!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/apoyo/finalizar_apoyo.php",
            data: {
              id_persona:this.id_persona
            },
            beforeSend:function(){
              //$('#response').html('<span class="text-info">Loading response...</span>');
              //alert('message_before')
            },
            success:function(data){
              Swal.fire({
                type: 'success',
                title: 'Apoyo finalizado',
                showConfirmButton: false,
                timer: 1100
              });

              eventBus.$emit('recargarPuesto', 1);
              eventBus.$emit('recargarAsignacionDetalle', 1);
            }
          }).done( function() {
          }).fail( function( jqXHR, textSttus, errorThrown){
            alert(errorThrown);
          });
        }
      })
    },
    reload() {
      this.$forceUpdate();
    }
  },
  created: function(){
    this.getAsignacionDetalle();
    eventBus.$on('recargarAsignacionDetalle', () => {
      this.getAsignacionDetalle();
    });
  }
});

Vue.component("detalle-direcciones", {
  props:["id_persona","privilegio"],
  template:`
  <div >

    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil">
        <thead>
          <th class="text-left" colspan="12"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-home"></i> Direcciones</th>
        </thead>
        <thead>
          <th class="text-center">Referencia</th>
          <th class="text-center">Reside</th>
          <th class="text-center">Nro.</th>
          <th class="text-center">Tope</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">No. casa</th>
          <th class="text-center">Zona</th>
          <th class="text-center">Departamento</th>
          <th class="text-center">Municipio</th>
          <th class="text-center">Lugar</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="d in direcciones">
            <td class="text-center">{{ d.referencia }}</td>
            <td class="text-center">{{ d.reside }}</td>
            <td class="text-center">{{ d.nro_calle_avenida }}</td>
            <td class="text-center">{{ d.calle_tope }}</td>
            <td class="text-center">{{ d.nombre_tipo_calle }}</td>
            <td class="text-center">{{ d.nro_casa }}</td>
            <td class="text-center">{{ d.zona }}</td>
            <td class="text-center">{{ d.departamento }}</td>
            <td class="text-center">{{ d.municipio }}</td>
            <td class="text-center">{{ d.lugar }}</td>
            <td class="text-center">{{ d.tipo_lugar }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getDireccionById(d.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true">
          <tr>
            <td class="text-right" colspan="12">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
    <div id="myModal" class="modal-vue">

      <!-- Modal content -->
      <div class="modal-vue-content">
        <div class="card shadow-card">
          <header class="header-color">
            <h4 class="card-header-title" >
              <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-home">
              </i><span v-if="opc == 2" class="text-white"> Agregar Dirección</span><span class="text-white" v-else> Editar Dirección </span>
            </h4>
            <span class="close-icon" @click="getOpc(1)">
              <i class="fa fa-times"></i>
            </span>
          </header>
          <div class="card-body">
            <form class="jsValidacionCrearDireccion" id="formNuevaDireccion" :class="isDisabled">
              <div class="row">
                <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                <input id="id_direccion" name="id_direccion" :value="idDireccion" hidden></input>

                <combo row="col-sm-3" label="Tipo de Referencia:" codigo="id_tipo_referencia" :arreglo="tipoReferencias" tipo="3" requerido="true" :valor="direccionD.id_tipo_referencia"></combo>
                <combo row="col-sm-3" label="Tipo de calle/avenida:" codigo="id_tipo_calle" :arreglo="tipoCalles" tipo="3" requerido="true" :valor="direccionD.id_tipo_calle"></combo>

                <campo row="col-sm-3" label="Calle / Avenida" codigo="nro_calle" tipo="text" requerido="true" :valor="direccionD.nro_calle_avenida"></campo>
                <campo row="col-sm-3" label="Nro. casa:" codigo="nro_casa" tipo="text" requerido="true" :valor="direccionD.nro_casa"></campo>
                <campo row="col-sm-3" label="Calle tope:" codigo="calle_tope" tipo="text" requerido="false" :valor="direccionD.calle_tope"></campo>
                <campo row="col-sm-3" label="Zona:" codigo="id_zona" tipo="number" requerido="true" :valor="direccionD.id_zona"></campo>
                <campo row="col-sm-3" label="Apartamento:" codigo="nro_apto_oficina" tipo="text" requerido="false" :valor="direccionD.nro_apto_oficina"></campo>
                <combo-items row="col-sm-3" label="Tipo de lugar*" codigo="id_tipo_lugar" id_catalogo="61" :valor="direccionD.tipo_lugar"></combo-items>

                <lugar-seleccion row1="col-sm-12" row2="col-sm-4" :depto="direccionD.id_departamento" :muni="direccionD.id_municipio" :lugar="direccionD.id_lugar" tipo="0"></lugar-seleccion>
                <campo row="col-sm-12" label="Observaciones:" codigo="observaciones" tipo="textarea" requerido="true" :valor="direccionD.observaciones"></campo>

              </div>
              <div class="row" v-if="privilegio.reclu == true">
                <checkbox style="margin-top: -35px" row="col-sm-3" label="Actual" codigo="flag_actual" :valor="direccionD.flag_actual"></checkbox>

                <div class="col-sm-9">
                  <div class="row">
                    <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                  </div>
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>

    </div>

    </div>
  </div>
  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc:1,
      tipoReferencias:[],
      tipoCalles:[],
      idDireccion:"",
      direcciones:"",
      direccionD:""
    }
  },
  mounted: function() {
  },
  methods:{
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 2){
        this.direccionD = "";
        this.getTipoCalles();
        this.getTipoReferencia();
      }

    },
    getDireccionesPersona: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/persona/listados/get_direcciones', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.direcciones  = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    getDireccionById: function(arreglo){
      this.opc = 3;
      this.idDireccion = arreglo.id_direccion;
      this.direccionD = arreglo;
      this.getTipoCalles();
      this.getTipoReferencia();
    },
    getTipoReferencia: function(tipo){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:108,
          tipo:0
        }
      })
      .then(function (response) {
        this.tipoReferencias = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getTipoCalles: function(tipo){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:5,
          tipo:0
        }
      })
      .then(function (response) {
        this.tipoCalles = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    eventAccion: function(id) {
      const thisInstance = this;

      if(id == 1){
        thisInstance.agregarDireccion(id);
      }else{
        this.idDireccion = "";
        this.opc = 1;
        this.direccionD = "";
      }
    },
    agregarDireccion: function(id){
      var instancia = this;
      jQuery('.jsValidacionCrearDireccion').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formNuevaDireccion');
          Swal.fire({
            title: '<strong>¿Desea crear la dirección?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Actualizar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_direccion.php",
                data: form.serialize(), //f de fecha y u de estado.
                dataType: 'json',
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    eventBus.$emit('recargarPersona', 1);
                    instancia.getDireccionesPersona();
                    instancia.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    eventBus.$emit('recargarPersona', 1);
                    instancia.getDireccionesPersona();
                    instancia.getOpc(1);
                  }
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }
          })
        },
        rules: {
         }
      });
    }
  },
  created: function(){
    setTimeout(() => {
      this.getDireccionesPersona();
    },500);

  }
});

Vue.component("detalle-documentos", {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil">
        <thead>
          <th class="text-left" colspan="8"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-id-card-alt"></i> Documentos</th>
        </thead>
        <thead>
          <th class="text-center">Ident.</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Número</th>
          <th class="text-center">Fecha</th>
          <th class="text-center">Departamento</th>
          <th class="text-center">Municipio</th>
          <th class="text-center">Lugar</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="d in documentos">
            <td class="text-left">{{ d.nombre_tipo_identificacion }}</td>
            <td class="text-center">{{ d.nombre_tipo_documento }}</td>
            <td class="text-center">{{ d.nro_registro }}</td>
            <td class="text-center">{{ d.fecha_vencimiento }}</td>
            <td class="text-center">{{ d.departamento }}</td>
            <td class="text-center">{{ d.municipio }}</td>
            <td class="text-center">{{ d.lugar }}</td>
            <td class="text-center">
              <span class="btn btn-sm btn-soft-info" @click="getDocumentoById(d.arreglo)"><i class="fa fa-pen"></i></span>
            </td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true">
          <tr>
            <td class="text-right" colspan="8">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
    <div id="myModal" class="modal-vue">

      <!-- Modal content -->
      <div class="modal-vue-content">
        <div class="card shadow-card">
          <header class="header-color">
            <h4 class="card-header-title" >
              <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-id-card">
              </i><span v-if="opc == 2" class="text-white"> Agregar Documento</span><span class="text-white" v-else> Editar Documento </span>
            </h4>
            <span class="close-icon" @click="getOpc(1)">
              <i class="fa fa-times"></i>
            </span>
          </header>
          <div class="card-body">

            <form class="jsValidacionCrearDocumento" id="formNuevoDocumentoP" :class="isDisabled">
              <div class="row">
                <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                <input id="id_docto" name="id_docto" :value="idDocto" hidden></input>
                <combo-change row="col-sm-4" label="Tipo de documento" codigo="id_tipo_doc" :arreglo="tipoDocs" tipo="3" requerido="true" :valor="idTipoDoc"></combo-change>
                <campo row="col-sm-4" label="Nro. de Registro" codigo="nro_registro" tipo="text" requerido="true" :valor="doctoP.nro_registro"></campo>
                <campo row="col-sm-4" label="Fecha de vencimiento" codigo="fecha_vec" tipo="date" requerido="true" :valor="doctoP.fecha_vencimiento" ></campo>
                <combo v-if="idTipoDoc == 1152 || idTipoDoc == 1155" row="col-sm-12" label="Seleccionar tipo:" codigo="id_subtipo_doc" :arreglo="subTipos" tipo="3" requerido="true" :valor="doctoP.id_tipo_documento"></combo>
                <lugar-seleccion v-else-if="idTipoDoc == 1153 || idTipoDoc == 1238" row1="col-sm-12" row2="col-sm-4" :depto="doctoP.id_departamento" :muni="doctoP.id_municipio" :lugar="doctoP.id_lugar" tipo="0"></lugar-seleccion>

              </div>
              <div class="row" v-if="privilegio.reclu == true">
                <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
    </div>
  </div>
  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc:1,
      idDocto:"",
      documentos:"",
      idTipoDoc:"",
      tipoDocs:[],
      subTipos:[],
      doctoP:""
    }
  },
  mounted: function() {
  },
  methods:{
    getDocumentosPersona: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/persona/listados/get_documentos', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.documentos = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    getDocumentoById: function(arreglo){
      this.opc = 3;
      this.idDocto = arreglo.id_documento;
      this.idTipoDoc = arreglo.id_tipo_identificacion;
      this.doctoP  = arreglo;

      this.getTipoDocs();
      this.getSubTipos(this.idTipoDoc);

    },
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 2){
        this.getTipoDocs();
      }else{
        this.doctoP = "";
        this.opc = 1;
        this.idTipoDoc = 0;
        this.subTipos = []
      }
    },
    getTipoDocs: function(tipo){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:71,
          tipo:0
        }
      })
      .then(function (response) {
        this.tipoDocs = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getSubTipos: function(valorS){
      var vS = (valorS == 1152)?8:22;
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:vS,
          tipo:0
        }
      })
      .then(function (response) {
        this.subTipos = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    eventAccion: function(id) {
      const thisInstance = this;

      if(id == 1){
        thisInstance.agregarDocumento(id);
      }else{
        this.doctoP = "";
        this.opc = 1;
        this.idTipoDoc = 0;
        this.subTipos = []
      }
    },
    agregarDocumento: function(){
      var instancia = this;
      jQuery('.jsValidacionCrearDocumento').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formNuevoDocumentoP');
          Swal.fire({
            title: '<strong>¿Desea crear el documento?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Actualizar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_documento.php",
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  eventBus.$emit('recargarPersona', 1);
                  instancia.getDocumentosPersona();
                  instancia.getOpc(1);
                  //eventBus.$emit('regresarPadre', 6);
                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });
                }
              }).done( function() {


              }).fail( function( jqXHR, textSttus, errorThrown){

                alert(errorThrown);

              });

            }

          })
        },
        rules: {

         }

      });

    },
    valorSeleccionado: function(){
      eventBus.$on('valorSeleccionado', (valor) => {
        this.idTipoDoc = valor;
        if(valor == 1152 || valor == 1155){
          this.getSubTipos(valor);
        }
      });
    }
  },

  created: function(){
    setTimeout(() => {
      this.getDocumentosPersona();
    },500);

    this.valorSeleccionado();
  }
});

Vue.component("detalle-telefonos", {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil">
        <thead>
          <th style="border-top: none;" class="text-left" colspan="7"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-phone"></i> Teléfonos</th>
        </thead>
        <thead>
          <th class="text-center">Privado</th>
          <th class="text-center">Activo</th>
          <th class="text-center">Principal</th>
          <th class="text-center">Referencia</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Número</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="t in telefonos">
            <td class="text-center"><h3 :class="t.cprivado"></h3></td>
            <td class="text-center"><h3 :class="t.cactivo"></h3></td>
            <td class="text-center"><h3 :class="t.cprincipal"></h3></td>
            <td class="text-left">{{ t.nombre_tipo_referencia }}</td>
            <td class="text-center">{{ t.nombre_tipo_telefono }}</td>
            <td class="text-center">{{ t.nro_telefono }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getTelefonoById(t.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true">
          <tr>
            <td class="text-right" colspan="7">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
    <div id="myModal" class="modal-vue">

      <!-- Modal content -->
      <div class="modal-vue-content">
        <div class="card shadow-card">
          <header class="header-color">
            <h4 class="card-header-title" >
              <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-mobile-alt">
              </i><span v-if="opc == 2" class="text-white"> Agregar Teléfono</span><span class="text-white" v-else> Editar Teléfono </span>
            </h4>
            <span class="close-icon"  @click="getOpc(1)">
              <i class="fa fa-times"></i>
            </span>
          </header>
          <div class="card-body">
            <form class="jsValidacionCrearTelefono" id="formNuevoTelefono" :class="isDisabled">
              <div class="row">
                <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                <input id="id_telefono" name="id_telefono" :value="idTelefono" hidden></input>

                <combo row="col-sm-4" label="Tipo de Referencia*" codigo="id_tipo_referencia" :arreglo="tipoReferencia" tipo="2" requerido="true" :valor="dTelefono.tipo"></combo>
                <combo row="col-sm-4" label="Tipo de Teléfono*" codigo="id_tipo_telefono" :arreglo="tipoTelefono" tipo="2" requerido="true" :valor="dTelefono.id_tipo_telefono"></combo>
                <campo row="col-sm-4" label="Nro. de Teléfono" codigo="nro_telefono" tipo="number" requerido="true" :valor="dTelefono.nro_telefono"></campo>
                <campo row="col-sm-12" label="Observaciones" codigo="tel_observaciones" tipo="textarea" requerido="true" :valor="dTelefono.observaciones" ></campo>
                <checkbox row="col-sm-4" label="Privado" codigo="flag_privado" :valor="dTelefono.flag_privado"></checkbox>
                <checkbox row="col-sm-4" label="Activo" codigo="flag_activo" :valor="dTelefono.flag_activo"></checkbox>
                <checkbox row="col-sm-4" label="Principal" codigo="flag_principal" :valor="dTelefono.flag_principal"></checkbox>
              </div>
              <div class="row" v-if="privilegio.reclu == true">
                <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      opc:1,
      idTelefono:"",
      telefonos:"",
      dTelefono:"",
      tipoReferencia:"",
      tipoTelefono:"",
      permiso:false,
      condition:false
    }
  },
  mounted: function() {
  },
  methods:{
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 1){
        this.doctoP = "";
        this.idTipoDoc = 0;
        this.subTipos = [];

        this.idTelefono = "";
        this.dTelefono = "";
        this.tipoReferencia = [];
        this.tipoTelefono = [];
      }else
      if(opc == 2 ){
        this.dTelefono = "";
        this.getTiposReferencia();
        this.getTipoTelefono();
        //this.getTiposReferencia();
        //this.getTipoTelefono();
        //this.getTipoDocs();
      }
    },
    getTelefonosPersona: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/persona/listados/get_telefonos', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.telefonos  = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    getTiposReferencia: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:21,
          tipo:0
        }
      }).then(function (response) {
        this.tipoReferencia  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getTipoTelefono: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:7,
          tipo:0
        }
      }).then(function (response) {
        this.tipoTelefono  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getTelefonoById: function(arreglo){
      this.opc = 3;
      this.idTelefono = arreglo.id_telefono;
      this.dTelefono  = arreglo

      this.getTiposReferencia();
      this.getTipoTelefono();
    },
    eventAccion: function(id) {
      const thisInstance = this;

      if(id == 1){
        thisInstance.agregarTelefono(id, thisInstance);
      }else{
        this.doctoP = "";
        this.opc = 1;
        this.idTipoDoc = 0;
        this.subTipos = [];

        this.idTelefono = "";
        this.dTelefono = "";
        this.tipoReferencia = [];
        this.tipoTelefono = [];
      }
    },
    agregarTelefono: function(id, instancia){
      const thisInstance = this;
      jQuery('.jsValidacionCrearTelefono').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formNuevoTelefono');
          Swal.fire({
            title: '<strong>¿Desea crear el teléfono?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Actualizar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_telefono.php",
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  instancia.getTelefonosPersona();
                  instancia.getOpc(1);
                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });
                }
              }).done( function() {


              }).fail( function( jqXHR, textSttus, errorThrown){

                alert(errorThrown);

              });

            }

          })
        },
        rules: {
         }
      });
    }
  },
  created: function(){
    setTimeout(() => {
      this.getTelefonosPersona();
    },500);

  }
});

Vue.component('detalle-nivel-academico', {
  props:['id_persona','privilegio'],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil">
        <thead>
          <th class="text-left" colspan="5"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-graduation-cap"></i> Escolaridad</th>
        </thead>
        <thead class="">
          <th class="text-center">Nivel</th>
          <th class="text-center">Nombre</th>
          <th class="text-center">Año</th>
          <th class="text-center">Fecha</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="e in escolaridad">
            <td class="text-center">{{ e.nivel }}</td>
            <td class="text-center">{{ e.titulo }}</td>
            <td class="text-center">{{ e.year }}</td>
            <td class="text-center"> {{ e.fecha_titulo}}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getEscolaridadById(e.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true || privilegio.acciones == true">
          <tr>
            <td class="text-right" colspan="5">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue">
        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-graduation-cap">
                </i><span v-if="opc == 2" class="text-white"> Agregar Escolaridad</span><span class="text-white" v-else> Editar Escolaridad </span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionCrearEscolaridad" id="formNuevoEscolaridad" :class="isDisabled">
                <div class="row">
                  <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                  <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                  <input id="id_escolaridad" name="id_escolaridad" :value="idEscolaridad" hidden></input>

                  <combo row="col-sm-4" label="Grado académico*" codigo="id_grado_academico" :arreglo="gradoAcademico" tipo="2" requerido="true" :valor="dEscolaridad.id_grado_academico"></combo>
                  <!--<combo row="col-sm-4" label="Establecimiento*" codigo="id_establecimiento" :arreglo="establecimiento" tipo="2" requerido="true" :valor="dEscolaridad.id_establecimiento"></combo>-->
                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_establecimiento">Filtrar el establecimiento*</label>
                          <div class=" input-group  has-personalizado">
                            <select class=" form-control form-control-sm" style="width:100%" id="id_establecimiento" name="id_establecimiento" required model="dEscolaridad.establecimiento"></select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <combo row="col-sm-4" label="Título obtenido" codigo="id_profesion" :arreglo="profesiones" tipo="2" requerido="true" :valor="dEscolaridad.id_titulo_obtenido"></combo>
                  <campo row="col-sm-4" label="Año:*" codigo="year" tipo="number" requerido="true" :valor="yearEscolaridad"></campo>

                  <campo row="col-sm-4" label="Fecha del título" codigo="fecha_titulo" tipo="date" requerido="false" :valor="dEscolaridad.fecha_titulo"></campo>
                  <campo row="col-sm-4" label="Nro. colegiado" codigo="nro_colegiado" tipo="text" requerido="false" :valor="dEscolaridad.nro_colegiado"></campo>

                  <campo row="col-sm-4" label="Vencimiento colegiado:" codigo="fecha_vencimiento" tipo="date" requerido="false" :valor="dEscolaridad.fec_venc_colegiado"></campo>

                  <checkbox row="col-sm-4" label="Estudios finalizados" codigo="flag_finalizado" :valor="dEscolaridad.flag_finalizado"></checkbox>
                  <campo row="col-sm-4" label="Observaciones:" codigo="id_observaciones" tipo="textarea" requerido="false" :valor="dEscolaridad.id_observaciones"></campo>
                </div>
                <div class="row" v-if="privilegio.reclu == true || privilegio.acciones == true">
                  <div class="col-sm-6">
                  </div>
                  <div class="col-sm-6">
                    <div class="row">
                      <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  data(){
    return {
      opc:1,
      idNivelAcademico:"",
      gradoAcademico:[],
      establecimiento:[],
      titoloObtenido:[],
      dNivelAcademico:"",
      escolaridad:[],
      profesiones:[],
      genero:[],
      dEscolaridad:"",
      idEscolaridad:"",
      yearEscolaridad:""
    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 2 ){
        this.dEscolaridad = "";
        this.getEstablecimiento();
        this.getGradoAcademico();
        this.getProfesiones();
        this.setYear();
      }
    },
    setYear: function(){
      if(this.opc == 2){
        var dt = new Date();
        this.yearEscolaridad = dt.getFullYear();
      }

    },
    getGradoAcademico: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:16,
          tipo:0
        }
      }).then(function (response) {
        this.gradoAcademico  = response.data;
        $("#id_grado_academico").select2({
          placeholder: "Grado académico",
          allowClear: true
        });
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getEstablecimiento: function(){
      /*axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:13,
          tipo:0
        }
      }).then(function (response) {
        this.establecimiento  = response.data;
        $("#id_establecimiento").select2({
          placeholder: "Grado académico",
          allowClear: true
        });
      }.bind(this)).catch(function (error) {
        console.log(error);
      });*/
      var texto = '';
      var id="";
      if(this.opc == 3){
        texto = this.dEscolaridad.establecimiento;
        id = this.dEscolaridad.id_establecimiento;
      }

      setTimeout(() => {
        //$("#id_establecimiento").select2().val(texto).trigger("change");
        $('#id_establecimiento').select2({
          //tags:[texto],
          /*matcher: function(term, text, option) {
            return option.hasClass('car');
          },*/
          id: id, text: texto,
          placeholder: 'Selecciona un curso',
          language: "es",
          allowClear: true,
          //selected:texto,
          //matcher:'UNIVERSIDAD MARIANO',
          //matcher:id,
          ajax: {
            url: 'empleados/php/back/persona/listados/get_establecimiento_filtrado.php',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          }
        });

        var $option = $("<option selected></option>").val(id).text(texto);
        $('#id_establecimiento').append($option).trigger('change');

        //$('select#id_establecimiento').filterSelect2(texto);
      }, 500);

      setTimeout(() => {
      //$('#id_establecimiento').val(texto).trigger('change');

      }, 900);
    },
    getEscolaridad: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/persona/listados/get_escolaridades.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.escolaridad = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    getProfesiones: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:14,
          tipo:0
        }
      })
      .then(function (response) {
        this.profesiones = response.data;
        $("#id_profesion").select2({
          placeholder: "Grado académico",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getEscolaridadById: function(arreglo){
      this.opc = 3;
      this.idEscolaridad = arreglo.id_escolaridad;
      this.yearEscolaridad = arreglo.ano_grado_academico;
      this.dEscolaridad  = arreglo;
      this.getEstablecimiento();
      this.getGradoAcademico();
      this.getProfesiones();
    },
    eventAccion: function(id) {
      const thisInstance = this;

      if(id == 1){
        thisInstance.agregarEscolaridad(id, thisInstance);
      }else{
        this.doctoP = "";
        this.opc = 1;
        this.idTipoDoc = 0;
        this.subTipos = [];

        this.idTelefono = "";
        this.dTelefono = "";
        this.tipoReferencia = [];
        this.tipoTelefono = [];
      }
    },
    agregarEscolaridad: function(id){
      const thisInstance = this;
      jQuery('.jsValidacionCrearEscolaridad').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formNuevoEscolaridad');
          Swal.fire({
            title: '<strong>¿Desea crear el nivel académico?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_escolaridad.php",
                dataType: 'json',
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getEscolaridad();
                    thisInstance.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getEscolaridad();
                    thisInstance.getOpc(1);
                  }

                }
              }).done( function() {


              }).fail( function( jqXHR, textSttus, errorThrown){

                alert(errorThrown);

              });

            }

          })
        },
        rules: {
         }
      });
    }
  },
  created: function() {
    setTimeout(() => {
      this.getEscolaridad();
    },500);

    eventBus.$on('recargarCatalogoEscolaridad', () => {
      this.getEstablecimiento();
      this.getProfesiones();
    });

  },
});

Vue.component('detalle-familiares', {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil" width="100%">
        <thead>
          <th style="border-top: none;" class="text-left" colspan="4"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-user"></i> Familia</th>
        </thead>
        <thead>
          <th class="text-center">Parentesco</th>
          <th class="text-center">Nombre</th>
          <th class="text-center">Edad</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="f in familiares">
            <td class="text-center"><span v-if="f.flag_fallecido == 1" class="text-danger fa fa-dizzy"></span> {{ f.parentesco }}</td>
            <td class="text-center">{{ f.nombre }}</td>
            <td class="text-center">{{ f.edad }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getFamiliarById(f.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true">
          <tr>
            <td class="text-right" colspan="4">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">

    <div id="myModal" class="modal-vue">
      <div class="modal-vue-content-bur"></div>
      <!-- Modal content -->
      <div class="modal-vue-content">
        <div class="card shadow-card">
          <header class="header-color">
            <h4 class="card-header-title" >
              <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user">
              </i><span v-if="opc == 2" class="text-white"> Agregar Familiar</span><span class="text-white" v-else> Editar Familiar </span>
            </h4>
            <span class="close-icon" @click="getOpc(1)">
              <i class="fa fa-times"></i>
            </span>
          </header>
          <div class="card-body card-body-white">
            <form class="jsValidacionCrearFamiliar" id="formNuevoFamiliar" :class="isDisabled">
              <div class="row">
                <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                <input id="id_familiar" name="id_familiar" :value="idFamiliar" hidden></input>

                <combo row="col-sm-3" label="Referencia*" codigo="id_tipo_referencia" :arreglo="tipoReferencia" tipo="2" requerido="true" :valor="dFamiliar.id_tipo_referencia"></combo>
                <combo row="col-sm-3" label="Parentesco*" codigo="id_tipo_familiar" :arreglo="tipoFamiliar" tipo="2" requerido="true" :valor="dFamiliar.id_parentesco"></combo>
                <campo row="col-sm-3" label="Fecha de nacimiento:*" codigo="fecha_nacimiento" tipo="date" requerido="false" :valor="dFamiliar.fecha_nacimiento"></campo>
                <combo row="col-sm-3" label="Género" codigo="genero" :arreglo="genero" tipo="2" requerido="true" :valor="dFamiliar.id_genero"></combo>
                <campo row="col-sm-3" label="1er. Nombre:" codigo="primer_nombre" tipo="text" requerido="true" :valor="dFamiliar.primer_nombre"></campo>
                <campo row="col-sm-3" label="2do. Nombre:" codigo="segundo_nombre" tipo="text" requerido="false" :valor="dFamiliar.segundo_nombre"></campo>
                <campo row="col-sm-3" label="1er. Apellido:" codigo="primer_apellido" tipo="text" requerido="true" :valor="dFamiliar.primer_apellido"></campo>
                <campo row="col-sm-3" label="2do. Apellido:" codigo="segundo_apellido" tipo="text" requerido="false" :valor="dFamiliar.segundo_apellido"></campo>
                <campo row="col-sm-3" label="Empresa:" codigo="empresa" tipo="text" requerido="false" :valor="dFamiliar.empresa_trabaja"></campo>
                <campo row="col-sm-3" label="Dirección:" codigo="direccion" tipo="text" requerido="false" :valor="dFamiliar.empresa_direccion"></campo>
                <campo row="col-sm-3" label="Teléfono:" codigo="telefono" tipo="text" requerido="false" :valor="dFamiliar.empresa_telefono"></campo>
                <combo row="col-sm-3" label="Ocupación:" codigo="profesion" :arreglo="profesiones" tipo="2" requerido="false" :valor="dFamiliar.id_ocupacion"></combo>
                <!--<campo row="col-sm-12" label="Observaciones" codigo="fam_observaciones" tipo="textarea" requerido="true" :valor="dFamiliar.observaciones" ></campo>-->
              </div>
              <div class="row" v-if="privilegio.bienestar == true && dFamiliar.flag_fallecido == 0">
                <span class="btn btn-sm btn-soft-danger" @click="setDead(dFamiliar.id_referencia)"><i class="fa fa-dizzy"></i> Establecer Facellido</span>
              </div>
              <div class="row" v-if="privilegio.reclu == true">

                <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>

    </div>
  </div>

  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  data(){
    return {
      opc:1,
      idFamiliar:"",
      tipoFamiliar:[],
      tipoReferencia:[],
      dFamiliar:"",
      familiares:[],
      profesiones:[],
      genero:[]
    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 2){
        this.dFamiliar = "";
        this.getTipoFamiliar();
        this.getTipoReferencia();
        this.getProfesiones();
        this.getGenero();
      }
    },
    getFamiliarById: function(arreglo){
      this.opc = 3;
      this.idFamiliar = arreglo.id_referencia;
      this.dFamiliar  = arreglo;
      this.getTipoFamiliar();
      this.getTipoReferencia();
      this.getProfesiones();
      this.getGenero();

    },
    eventAccion: function(id){
      if(id == 1){
        this.agregarFamiliar(id);
      }else{
        this.opc = 1;
        this.idFamiliar = "";
        this.dFamiliar = "";
      }
    },
    getTipoFamiliar: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:20,
          tipo:0
        }
      }).then(function (response) {
        this.tipoFamiliar  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getTipoReferencia: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:21,
          tipo:0
        }
      }).then(function (response) {
        this.tipoReferencia  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getGenero: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:19,
          tipo:0
        }
      }).then(function (response) {
        this.genero  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getProfesiones: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:4,
          tipo:0
        }
      })
      .then(function (response) {
        this.profesiones = response.data;
        $("#profesion").select2({
          placeholder: "Seleccionar ocupación",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    setDead: function(id_referencia){
      var thisInstance = this;
      Swal.fire({
        title: '<strong>¿Desea establecer fallecido a esta persona?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, establecer!'
      }).then((result) => {
        if (result.value) {
          //alert(vt_nombramiento);
          $.ajax({
            type: "POST",
            url: "empleados/php/back/persona/action/establecer_fallecido.php",
            dataType: 'json',
            //data: form.serialize(), //f de fecha y u de estado.
            data:{
              id_referencia
            },
            beforeSend:function(){
              //$('#response').html('<span class="text-info">Loading response...</span>');
              //alert('message_before')
            },
            success:function(data){
              if(data.msg == 'OK'){
                Swal.fire({
                  type: 'success',
                  title: data.message,
                  showConfirmButton: false,
                  timer: 1100
                });
                thisInstance.getFamiliares();
                thisInstance.getOpc(1);
              }else{
                Swal.fire({
                  type: 'error',
                  title: data.message,
                  showConfirmButton: false,
                  timer: 1100
                });
              }
            }
          }).done( function() {
          }).fail( function( jqXHR, textSttus, errorThrown){alert(errorThrown);
          });
        }
      })
    },
    getFamiliares: function(){
      if(this.id_persona > 0){
        axios.get('empleados/php/back/persona/listados/get_familiares.php', {
          params: {
            id_persona: this.id_persona
          }
        }).then(function (response) {
          this.familiares = response.data;
          console.log(this.familiares)
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    agregarFamiliar: function(id){
      const thisInstance = this;
      jQuery('.jsValidacionCrearFamiliar').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formNuevoFamiliar');
          Swal.fire({
            title: '<strong>¿Desea crear el familiar?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_familiar.php",
                dataType: 'json',
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getFamiliares();
                    thisInstance.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){alert(errorThrown);
              });
            }
          })
        },
        rules: {
         }
      });
    }
  },
  created: function() {
    setTimeout(() => {
      this.getFamiliares();
    },900);

  },
});

Vue.component('detalle-cuentas', {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil" width="100%">
        <thead>
          <th style="border-top: none;" class="text-left" colspan="6"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-credit-card"></i> Cuentas Bancarias</th>

        </thead>
        <thead>
          <th class="text-center">Activa</th>
          <th class="text-center">Actual</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Banco</th>
          <th class="text-center">Cuenta</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="c in cuentas">

            <td class="text-center"><h3 :class="c.cactiva"></h3></td>
            <td class="text-center"><h3 :class="c.cprincipal"></h3></td>
            <td class="text-center">{{ c.nombre_tipo_cuenta }}</td>
            <td class="text-center">{{ c.nombre_banco }}</td>
            <td class="text-center">{{ c.nro_cuenta }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getCuentaById(c.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true">
          <tr>
            <td class="text-right" colspan="6">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue">

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-credit-card">
                </i><span v-if="opc == 2" class="text-white"> Agregar Cuenta</span><span class="text-white" v-else> Editar Cuenta </span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionCrearCuenta" id="formNuevoCuenta" :class="isDisabled">
                <div class="row">
                  <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                  <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                  <input id="id_telefono" name="id_cuenta" :value="idCuenta" hidden></input>

                  <combo row="col-sm-4" label="Tipo de cuenta*" codigo="id_tipo_cuenta" :arreglo="tipoCuentas" tipo="2" requerido="true" :valor="cuentaD.id_tipo_cuenta"></combo>
                  <combo row="col-sm-4" label="Banco*" codigo="id_banco" :arreglo="bancos" tipo="2" requerido="true" :valor="cuentaD.id_banco"></combo>
                  <campo row="col-sm-4" label="Nro. cuenta:*" codigo="nro_cuenta" tipo="text" requerido="true" :valor="cuentaD.nro_cuenta"></campo>
                  <campo row="col-sm-4" label="Fecha de apertura" codigo="fecha_apertura" tipo="date" requerido="true" :valor="cuentaD.fecha_apertura"></campo>
                  <checkbox row="col-sm-4" label="Principal" codigo="flag_principal" :valor="cuentaD.flag_principal"></checkbox>
                  <checkbox row="col-sm-4" label="Activo" codigo="flag_activo" :valor="cuentaD.flag_activa"></checkbox>

                </div>
                <div class="row" v-if="privilegio.reclu == true">
                  <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  data(){
    return {
      opc:1,
      idCuenta:"",
      tipoCuentas:[],
      bancos:"",
      cuentas:[],
      cuentaD:""
    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 2){
        this.getTipoCuentas();
        this.getBancos();
      }else{
        this.opc = 1;
        this.idCuenta = "";
        this.cuentaD = "";
      }
    },
    getCuentaById: function(arreglo){
      this.opc = 3;
      this.idCuenta = arreglo.id_cuenta;
      this.cuentaD  = arreglo;
      this.getTipoCuentas();
      this.getBancos();

    },
    eventAccion: function(id){
      if(id == 1){
        this.agregarCuenta(id);
      }else{
        this.opc = 1;
        this.idCuenta = "";
        this.cuentaD = "";
      }
    },
    getCuentas: function(){
      axios.get('empleados/php/back/persona/listados/get_cuentas', {
        params: {
          id_persona: this.id_persona
        }
      }).then(function (response) {
        this.cuentas  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getTipoCuentas: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:107,
          tipo:0
        }
      }).then(function (response) {
        this.tipoCuentas  = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getBancos: function(){
      axios.get('empleados/php/back/listados/get_items', {
        params: {
          id_catalogo:3,
          tipo:0
        }
      }).then(function (response) {
        this.bancos  = response.data;
        $("#id_banco").select2({
          placeholder: "Seleccionar Banco",
          allowClear: true
        });
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    agregarCuenta: function(id){
      const thisInstance = this;
      jQuery('.jsValidacionCrearCuenta').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formNuevoCuenta');
          Swal.fire({
            title: '<strong>¿Desea crear la cuenta?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_cuenta.php",
                dataType: 'json',
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getCuentas();
                    thisInstance.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }

                }
              }).done( function() {


              }).fail( function( jqXHR, textSttus, errorThrown){

                alert(errorThrown);

              });

            }

          })
        },
        rules: {
         }
      });
    }
  },
  created: function() {
    setTimeout(() => {
      this.getCuentas();
    },500);

    eventBus.$on('recargarCatalogoCuentas', () => {
      this.getBancos();
    });
  },
});

Vue.component('form-catalogo', {
  props:["option","tipocarga","opcionactual"],
  template:`
  <div v-if="opc == 1">

      <div id="myModal" class="modal-vue" >

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-tasks">
                </i><span v-if="opc == 2" class="text-white"> Agregar Cuenta</span><span class="text-white" v-else> Agregar Catálogos </span>
              </h4>
              <span class="close-icon" @click="getOpc(2)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <div class="slide_up_anim">
                <div class="row" v-if="opcion == 1">
                  <menu-opcion
                    col="col-4"
                    v-for="(o, index) in opcionesCatalogo"
                    :key="index"
                    :title="o.texto"
                    :image="o.imagen"
                    :option="o.option"
                    v-on:event_child="eventChild">
                  </menu-opcion>
                </div>

                <div  v-else-if="opcion == 101 || opcion == 103">
                  <listado-catalogo :tipo="idTipoCatalogo" :tipoc="tipocarga" :opciona="opcionactual"></listado-catalogo>
                </div>
                <div v-else-if="opcion == 102">
                  <nuevo-lugar :tipo="1"></nuevo-lugar>
                </div>
                <div v-else-if="opcion == 105">
                  <form-nuevo-curso :tipo="1"></form-nuevo-curso>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

  </div>

  `,
  computed:{
    isDisabled() {
      if(this.privilegio.bloqueo == true){
        return 'bloqueado';
      }else{
        return '';
      }

    }
  },
  data(){
    return {
      opc:0,
      opcion:1,
      opcionesCatalogo:[],
      idTipoCatalogo:""

    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){

      this.opcion = opc;
      if(opc==101){
        this.idTipoCatalogo = 4;
      }else if(opc==103){
        this.idTipoCatalogo = 5;
      }

      if(opc == 2){
        eventBus.$emit('regresarPrincipal', this.opcionactual);
      }
    },
    eventChild: function(id) {
      console.log('Event from child component emitted', id),
      this.getOpc(id);
    },
    getOpcionesCatalogo: function() {
      //genera el menu de opciones
      axios.get('empleados/php/back/listados/get_opciones_catalogo.php', {
        params: {
          id_persona: this.id_persona
        }
      }).then(function (response) {
        this.opcionesCatalogo = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function() {
    this.opc = this.option;
    eventBus.$on('regresarListaCatalogo', (opc) => {
      this.getOpc(opc);
    });
    this.getOpcionesCatalogo();
  },
});

Vue.component('detalle-vacunas', {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil" width="100%">
        <thead>
          <th style="border-top: none;" class="text-left" colspan="7"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-syringe"></i> Vacunas</th>
        </thead>
        <thead>
          <th class="text-center">Dosis</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Fecha</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="v in vacunas">
            <td class="text-center">{{ v.id_dosis }}</td>
            <td class="text-center">{{ v.tipo_vacuna }}</td>
            <td class="text-center">{{ v.fecha_vacuna }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getVacunaById(v.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.bienestar == true && totalVacunas < 3">
          <tr>
            <td class="text-right" colspan="6">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue">

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-syringe">
                </i><span v-if="opc == 2" class="text-white"> Agregar Vacuna</span><span class="text-white" v-else> Editar Vacuna </span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionAgregarVacuna" id="formAgregarVacuna" :class="isDisabled">
                <div class="row">
                  <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                  <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                  <input id="id_vacuna" name="id_vacuna" :value="idVacuna" hidden></input>
                  <!-- inicio -->
                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_tipo_vacuna">Tipo de vacuna*</label>
                          <div class=" input-group  has-personalizado" >
                            <select id="id_tipo_vacuna" name="id_tipo_vacuna" class="form-control form-control-sm form-control-alternative" :disabled="lVacuna.validaciontvacuna == false" required v-model="tVacuna">
                              <option value="">-- Seleccionar --</option>
                              <option value="1">ASTRAZENECA</option>
                              <option value="2">J&J</option>
                              <option value="3">JANSSEN</option>
                              <option value="4">MODERNA</option>
                              <option value="5">PFIZER</option>
                              <option value="6">SPUTNIK V</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin -->
                  <!-- inicio -->
                  <div class="col-sm-4">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_tipo_dosis">Tipo de dosis*</label>
                          <div class=" input-group  has-personalizado" >
                            <select id="id_tipo_dosis" name="id_tipo_dosis" class="form-control form-control-sm form-control-alternative" :disabled="lVacuna.validaciontdosis == false" required v-model="iDosis">
                              <option value="">-- Seleccionar --</option>
                              <option value="1">1er. Dosis</option>
                              <option value="2">2da. Dosis</option>
                              <option value="3">Refuerzo</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin -->
                  <campo row="col-sm-4" label="Fecha de vacunación" codigo="fecha_vacuna" tipo="date" requerido="true" :valor="dVacuna.fecha_vacuna"></campo>
                </div>
                <div class="row" v-if="privilegio.bienestar == true">
                  <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  data(){
    return {
      opc:0,
      vacunas:[],
      idVacuna:"",
      dVacuna:"",
      isDisabled:"",
      lVacuna:"",
      tVacuna:"",
      iDosis:"",
      totalVacunas:""

    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){
      this.opc = opc;
      if(opc == 2){
        this.getLastVacuna();
      }else{
        this.lVacuna = "";
      }
    },
    getVacunas: function() {
      //genera el menu de opciones
      axios.get('empleados/php/back/persona/listados/get_vacunas.php', {
        params: {
          id_persona: this.id_persona
        }
      }).then(function (response) {
        this.vacunas = response.data;
        this.totalVacunas = this.vacunas.length;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getLastVacuna: function() {
      //genera el menu de opciones
      axios.get('empleados/php/back/persona/detalle/get_ultima_vacuna.php', {
        params: {
          id_persona: this.id_persona,
          tipo_accion: this.opc
        }
      }).then(function (response) {
        this.lVacuna = response.data;
        this.tVacuna = response.data.id_tipo_vacuna;
        this.iDosis = response.data.siguiente_dosis;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getVacunaById: function(arreglo){
      this.opc = 3;
      console.log(arreglo);
      this.idVacuna = arreglo.id_vacuna;
      this.tVacuna = arreglo.tipo_vacuna;
      this.iDosis = arreglo.id_dosis;
      this.dVacuna  = arreglo
    },
    eventAccion: function(id) {
      const thisInstance = this;

      if(id == 1){
        thisInstance.agregarVacunacion(id);
      }else{
        this.idVacuna = "";
        this.opc = 1;
        this.dVacuna= "";
      }
    },
    agregarVacunacion: function(id){
      var instancia = this;
      jQuery('.jsValidacionAgregarVacuna').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formAgregarVacuna');
          var myform = $('#formAgregarVacuna');

           // Find disabled inputs, and remove the "disabled" attribute
          var disabled = myform.find(':input:disabled').removeAttr('disabled');

           // serialize the form
          var serialized = myform.serialize();

           // re-disabled the set of inputs that you previously enabled
          disabled.attr('disabled','disabled');
          Swal.fire({
            title: '<strong>¿Desea ingresar dosis?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Ingresar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_vacuna.php",
                data: serialized,//form.serialize(), //f de fecha y u de estado.
                dataType: 'json',
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    instancia.getVacunas();
                    instancia.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    instancia.getDireccionesPersona();
                    instancia.getOpc(1);
                  }
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }
          })
        },
        rules: {
         }
      });
    }
  },
  created: function() {
    setTimeout(() => {
      this.getVacunas();
    },500);

  },
});

//capacitaciones
Vue.component('detalle-cursos', {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil" width="100%" style="border-radius:2px">
        <thead class="thead-primary">
          <th style="border-top: none;" class="text-left" colspan="7"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-chalkboard"></i> Cursos de Capacitación</th>
        </thead>
        <thead>
          <th class="text-center" width="25%">Centro de Capacitación</th>
          <th class="text-center">Tipo</th>
          <th class="text-center" width="25%">Curso</th>
          <th class="text-center">Inicio</th>
          <th class="text-center">Fin</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="c in cursos">
            <td class="text-center">{{ c.nombre_centro_capacitacion }}</td>
            <td class="text-center">{{ c.nombre_tipo_curso }}</td>
            <td class="text-center">{{ c.nombre_curso }}</td>
            <td class="text-center">{{ c.fecha_inicio }}</td>
            <td class="text-center">{{ c.fecha_fin }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getCursoById(c.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.desarrollo == true">
          <tr>
            <td class="text-right" colspan="12">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue">
        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-chalkboard">
                </i><span v-if="opc == 2" class="text-white"> Agregar Capacitación</span><span class="text-white" v-else>  Editar Capacitación</span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionCapacitacion" id="formValidacionCapacitacion">
                <div class="row">
                  <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                  <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                  <!--inicio-->
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_curso_p">Filtrar el curso*</label>
                          <div class=" input-group  has-personalizado">
                            <select class="categoryCurso form-control form-control-sm" style="width:100%" id="id_curso_p" name="id_curso_p" required></select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- fin -->
                  <combo row="col-sm-4" label="Patrocinador*" codigo="id_patrocinador" :arreglo="listaPatrocinadores" tipo="2" requerido="true" :valor="dCurso.id_patrocinador"></combo>
                  <campo row="col-sm-4" label="Fecha de inicio" codigo="fecha_ini" tipo="date" requerido="true" :valor="dCurso.fecha_inicio"></campo>
                  <campo row="col-sm-4" label="Fecha de final" codigo="fecha_fin" tipo="date" requerido="true" :valor="dCurso.fecha_fin"></campo>
                  <campo row="col-sm-4" label="Horas del Curso" codigo="horas_curso" tipo="number" requerido="false" :valor="dCurso.horas_curso"></campo>
                  <campo row="col-sm-4" label="Horas Completadas" codigo="horas_completadas" tipo="number" requerido="false" :valor="dCurso.horas_completadas"></campo>
                  <combo row="col-sm-4" label="País de capacitación*" codigo="id_pais" :arreglo="listaPais" tipo="2" requerido="true" :valor="dCurso.id_pais"></combo>

                  <!--{{ dCurso.id_curso }}
                  {{ dCurso.dCurso }}
                  {{ dCurso.horas_completadas }}
                  {{ dCurso.id_pais }}-->
                </div>
                <div class="row" v-if="privilegio.desarrollo == true">
                  <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  data(){
    return {
      opc:0,
      cursos:[],
      idCurso:"",
      dCurso:"",
      isDisabled:"",
      centroCapacitaciones:[],
      listaPatrocinadores:[],
      listaCursos:[],
      listaPais:[]
    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){
      this.opc = opc;
      this.idCurso = '';
      this.dCurso = '';
      this.centroCapacitaciones = [];
      this.listaPatrocinadores = [];
      this.listaCursos = [];
      this.listaPais=[];
      if(opc == 1){

      }else{
        if(opc == 2){
          //this.getCentrosCapacitaciones();
          this.getPatrocinadores();
          this.getListaCursos();
          this.getPaises();
        }
      }
    },
    getCapacitaciones: function() {
      //genera el menu de opciones
      axios.get('empleados/php/back/persona/listados/get_capacitaciones.php', {
        params: {
          id_persona: this.id_persona
        }
      }).then(function (response) {
        this.cursos = response.data;
        //this.totalVacunas = this.vacunas.length;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getCursoById: function(curso){
      console.log(this.dCurso);
      this.opc = 3;
      this.dCurso = curso;
      //this.getCentrosCapacitaciones();
      this.getPatrocinadores();
      this.getListaCursos();
      this.getPaises();
    },
    /*getCentrosCapacitaciones: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:32,
          tipo:0
        }
      })
      .then(function (response) {
        this.centroCapacitaciones = response.data;
        setTimeout(() => {
          $("#id_centro_capacitacion").select2({
          });

        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },*/
    getPatrocinadores: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:80,
          tipo:0
        }
      })
      .then(function (response) {
        this.listaPatrocinadores = response.data;
        setTimeout(() => {
          $("#id_patrocinador").select2({
          });

        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getListaCursos: function(){
      var texto = '';
      var id="";
      if(this.opc == 3){
        texto = this.dCurso.nombre_curso;
        id = this.dCurso.id_curso;
      }
      setTimeout(() => {
        $('.categoryCurso').select2({
          placeholder: 'Selecciona un curso',
          language: "es",
          ajax: {
            url: 'empleados/php/back/persona/listados/get_curso_filtrado.php',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          }
        });
        var $option = $("<option selected></option>").val(id).text(texto);
        $('.categoryCurso').append($option).trigger('change');
      }, 500);
    },
    getPaises: function(){
      axios.get('viaticos/php/back/listados/destinos/get_pais', {
        params: {

        }
      })
      .then(function (response) {
        this.listaPais = response.data;
        setTimeout(() => {
          $("#id_pais").select2({
          });

        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    eventAccion: function(id) {
      const thisInstance = this;

      if(id == 1){
        thisInstance.agregarCapacitacion(id);
      }else{

        this.opc = 1;

      }
    },
    agregarCapacitacion: function(id){
      var instancia = this;
      jQuery('.jsValidacionCapacitacion').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var form = $('#formValidacionCapacitacion');
           // serialize the form

           // re-disabled the set of inputs that you previously enabled
          Swal.fire({
            title: '<strong>¿Desea agregar la capacitación?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Ingresar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_capacitacion.php",
                data: form.serialize(),//form.serialize(), //f de fecha y u de estado.
                dataType: 'json',
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    instancia.getCapacitaciones();
                    instancia.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    //instancia.getDireccionesPersona();
                    instancia.getOpc(1);
                  }
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }
          })
        },
        rules: {
         }
      });
    }
  },
  created: function() {
    setTimeout(() => {
      this.getCapacitaciones();
    },500);

  },
});

// experiencia laboral
Vue.component('detalle-trabajos', {
  props:["id_persona","privilegio"],
  template:`
  <div>
    <div class="card-body">
      <table class="table table-sm table-striped table-bordered table-perfil" width="100%" style="border-radius:2px">
        <thead class="thead-primary">
          <th style="border-top: none;" class="text-left" colspan="7"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-briefcase"></i> Experiencia Laboral</th>
        </thead>
        <thead>
          <th class="text-center" width="25%">Empresa</th>
          <th class="text-center">Dirección</th>
          <th class="text-center" width="25%">Teléfono</th>
          <th class="text-center">Puesto</th>
          <th class="text-center">Inicio</th>
          <th class="text-center">Fin</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="t in trabajos">
            <td class="text-center">{{ t.empresa_nombre }}</td>
            <td class="text-center">{{ t.empresa_direccion }}</td>
            <td class="text-center">{{ t.empresa_telefonos }}</td>
            <td class="text-center">{{ t.nombre_puesto_ocupado }}</td>
            <td class="text-center">{{ t.fecha_inicio }}</td>
            <td class="text-center">{{ t.fecha_fin }}</td>
            <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getTrabajoById(t.arreglo)"><i class="fa fa-pencil-alt"></i></span></td>
          </tr>
        </tbody>
        <tfoot v-if="privilegio.reclu == true">
          <tr>
            <td class="text-right" colspan="12">
              <span class="btn btn-sm btn-soft-info" @click="getOpc(2)"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div v-if="opc == 2 || opc == 3">
      <div id="myModal" class="modal-vue">

        <!-- Modal content -->
        <div class="modal-vue-content">
          <div class="card shadow-card">
            <header class="header-color">
              <h4 class="card-header-title" >
                <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-briefcase">
                </i><span v-if="opc == 2" class="text-white"> Agregar Trabajo</span><span class="text-white" v-else> Editar Trabajo </span>
              </h4>
              <span class="close-icon" @click="getOpc(1)">
                <i class="fa fa-times"></i>
              </span>
            </header>
            <div class="card-body">
              <form class="jsValidacionTrabajo" id="formValidacionTrabajo" :class="isDisabled">
                <div class="row">
                  <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
                  <input id="tipo_accion" name="tipo_accion" :value="opc" hidden></input>
                  <input id="id_experiencia" name="id_experiencia" :value="dTrabajo.id_experiencia" hidden></input>

                  <combo row="col-sm-4" label="Categoría de la empresa*" codigo="id_empresa_rama" :arreglo="listaRamas" tipo="2" requerido="true" :valor="dTrabajo.id_empresa_rama"></combo>
                  <campo row="col-sm-4" label="Nombre de la empresa" codigo="empresa_nombre" tipo="text" requerido="true" :valor="dTrabajo.empresa_nombre"></campo>
                  <campo row="col-sm-4" label="Dirección de la empresa" codigo="empresa_direccion" tipo="text" requerido="true" :valor="dTrabajo.empresa_nombre"></campo>
                  <campo row="col-sm-4" label="Teléfonos" codigo="empresa_telefonos" tipo="text" requerido="false" :valor="dTrabajo.empresa_telefonos"></campo>

                  <combo row="col-sm-4" label="Puesto que desempeñó*" codigo="id_puesto_ocupado" :arreglo="listaPuestos" tipo="2" requerido="true" :valor="dTrabajo.id_puesto_ocupado"></combo>
                  <campo row="col-sm-4" label="Atribuciones*" codigo="puesto_atribuciones" tipo="text" requerido="false" :valor="dTrabajo.puesto_atribuciones"></campo>
                  <campo row="col-sm-4" label="Fecha de inicio" codigo="fecha_ingreso" tipo="date" requerido="true" :valor="dTrabajo.fecha_ingreso"></campo>
                  <campo row="col-sm-4" label="Fecha de inicio" codigo="fecha_salida" tipo="date" requerido="true" :valor="dTrabajo.fecha_salida"></campo>

                  <campo row="col-sm-4" label="Salario inicial" codigo="salario_inicial" tipo="number" requerido="true" :valor="dTrabajo.salario_inicial"></campo>
                  <campo row="col-sm-4" label="Salario final" codigo="salario_final" tipo="number" requerido="true" :valor="dTrabajo.salario_final"></campo>



                  <campo row="col-sm-4" label="Subordinados (cant.)" codigo="personas_subordinadas" tipo="number" requerido="false" :valor="dTrabajo.personas_subordinadas"></campo>
                  <campo row="col-sm-4" label="Subordinados (puestos)" codigo="personas_subordinadas_puesto" tipo="text" requerido="false" :valor="dTrabajo.personas_subordinadas_puesto"></campo>
                  <campo row="col-sm-4" label="Jefe inmediato" codigo="jefe_inmediato" tipo="text" requerido="false" :valor="dTrabajo.jefe_inmediato"></campo>
                  <campo row="col-sm-4" label="Teléfono del jefe" codigo="jefe_inmediato_telefono" tipo="text" requerido="false" :valor="dTrabajo.jefe_inmediato_telefono"></campo>
                  <campo row="col-sm-4" label="Motivo de salida" codigo="motivo" tipo="text" requerido="false" :valor="dTrabajo.motivo_retiro"></campo>

                </div>
                <div class="row" v-if="privilegio.reclu == true">
                  <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  `,
  data(){
    return {
      opc:0,
      trabajos:[],
      idTrabajo:"",
      dTrabajo:"",
      isDisabled:"",
      listaRamas:[],
      listaPuestos:[]
    }
  },
  mounted: function() {
  },
  methods: {
    getOpc: function(opc){
      this.opc = opc;
      this.dTrabajo="";
      this.listaRamas=[];
      this.listaPuestos=[];
      if(opc == 2){
        this.opc = opc;
        this.getListaRamas();
        this.getPuestos();
      }
    },
    getTrabajos: function() {
      //genera el menu de opciones
      axios.get('empleados/php/back/persona/listados/get_trabajos.php', {
        params: {
          id_persona: this.id_persona
        }
      }).then(function (response) {
        this.trabajos = response.data;
        //this.totalVacunas = this.vacunas.length;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getTrabajoById: function(arreglo){
      this.opc = 3;
      this.dTrabajo = arreglo;
      this.getListaRamas();
      this.getPuestos();
    },
    getListaRamas: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:109,
          tipo:0
        }
      })
      .then(function (response) {
        this.listaRamas = response.data;
        $("#id_empresa_rama").select2({
          placeholder: "Seleccionar ocupación",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getPuestos: function(){
      setTimeout(() => {
        axios.get('empleados/php/back/listados/get_items.php', {
          params: {
            //experiencia laboral
            id_catalogo:31,
            tipo:0
          }
        })
        .then(function (response) {
          this.listaPuestos = response.data;
          $("#id_puesto_ocupado").select2({
            placeholder: "Seleccionar ocupación",
            allowClear: true
          });
        }.bind(this))
        .catch(function (error) {
          console.log(error);
        });
      },500);

    },
    eventAccion: function(id) {
      const thisInstance = this;
      if(id == 1){
        thisInstance.agregarTrabajo(id);
      }else{
        this.opc = 1;
      }
    },
    agregarTrabajo: function(id){
      var instancia = this;
      jQuery('.jsValidacionTrabajo').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
          jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error').addClass('has-error');
          elem.closest('.help-block').remove();
        },
        success: function(e) {
          var elem = jQuery(e);
          elem.closest('.form-group').removeClass('has-error');
          elem.closest('.help-block').remove();
        },
        submitHandler: function(form){
          //regformhash(form,form.password,form.confirmpwd);
          var myform = $('#formValidacionTrabajo');
           // serialize the form

          Swal.fire({
            title: '<strong>¿Desea crear el trabajo?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Ingresar!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/persona/action/crear_trabajo.php",
                data: myform.serialize(),//form.serialize(), //f de fecha y u de estado.
                dataType: 'json',
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    instancia.getTrabajos();
                    instancia.getOpc(1);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    //instancia.getDireccionesPersona();
                    instancia.getOpc(1);
                  }
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }
          })
        },
        rules: {
         }
      });
    }
  },
  created: function() {
    setTimeout(() => {
      this.getTrabajos();
    },500);

  },
});
