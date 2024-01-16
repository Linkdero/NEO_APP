Vue.component("fotografia", {
  props:["id_persona","tipo"],
  template:`
  <div  v-if="tipo == 1">
    <div class="userProfileInfo card" style="height:510px">
      <div class="image text-center slide_up_anim">
      <img class='mb-3 ' style="width:100%" :src='datoFoto.foto' >

      </div> 
      <div class="box" >
        <!--<div class="name"><strong>{{ persona.nombres }} {{ persona.apellidos }}</strong></div>-->
        <div class="name"><strong>{{ persona.nombres }} {{ persona.apellidos }}</strong></div>
        <div class="info "style="width:100%; margin-left:auto; margin-right:auto;" >
          <!--<span><i class="fa fa-fw fa-envelope"></i> {{ persona.email}}</span>
          <span><i class="fa fa-fw fa-phone"></i> {{ persona.email}}</span>
          <span class="btn btn-sm btn-soft-info"><i class="fa fa-fw fa-user-edit"></i> Editar </span>-->
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

Vue.component('detalle-excepciones', {
    props:["id_persona","privilegio"],
    template:`
    <div>
      <div class="card-body">
        <table class="table table-sm table-striped table-bordered" width="100%">
          <thead>
            <th style="border-top: none;" class="text-left" colspan="6"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-user"></i> Excepciones por fecha</th>
          </thead>
          <thead>
            <th class="text-center">Del</th>
            <th class="text-center">Al</th>
            <th class="text-center">Desayuno</th>
            <th class="text-center">Almuerzo</th>
            <th class="text-center">Cena</th>
            <th class="text-center">Acción</th>
          </thead>
          <tbody>
            <tr v-for="ex in excepciones">
              <td class="text-center">{{ ex.fecha1 }}</td>
              <td class="text-center">{{ ex.fecha2 }}</td>
              <td class="text-center"><h3 :class="ex.desayuno"></h3></td>
              <td class="text-center"><h3 :class="ex.almuerzo"></h3></td>
              <td class="text-center"><h3 :class="ex.cena"></h3></td>
              <td class="text-center"><span class="btn btn-sm btn-soft-info" @click="getExcepxEmpleado(ex.id_persona)"><i class="fa fa-pencil-alt"></i></span></td>
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
        excepciones:[],
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
          this.getGenero();
        }
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

      getExcepxEmpleado: function(){
        if(this.id_persona > 0){
          axios.get('alimentos/php/back/listados/get_excep_by_empleado.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            this.excepciones = response.data;
            console.log(this.excepciones);
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
                      thisInstance.getExcepxEmpleado();
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
        this.getExcepxEmpleado();
      },500);
  
    },
  });
  