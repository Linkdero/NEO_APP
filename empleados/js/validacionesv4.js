Vue.component("editar-persona",{
  props:["id_persona","persona", 'testProp'],
  template:`
  <div class="col-sm-12">
    <br>
    <h3>Actualizar informacion</h3>
    <form class="jsValidacionActualizarInformacion" id="formActualizarPersona">
    <input type="text" :value="persona.id_persona" id="id_persona" name="id_persona" hidden></input>
      <div class="row">
        <campo row="col-sm-4" label="Primer Nombre*" codigo="p_nombre" tipo="text" requerido="true" :valor="persona.primer_nombre"></campo>
        <campo row="col-sm-4" label="Segundo Nombre" codigo="s_nombre" tipo="text" requerido="false" :valor="persona.segundo_nombre"></campo>
        <campo row="col-sm-4" label="Tercer Nombre" codigo="t_nombre" tipo="text" requerido="false" :valor="persona.tercer_nombre"></campo>
        <campo row="col-sm-4" label="Primer Apellido*" codigo="p_apellido" tipo="text" requerido="true" :valor="persona.primer_apellido"></campo>
        <campo row="col-sm-4" label="Segundo Apellido" codigo="s_apellido" tipo="text" requerido="false" :valor="persona.segundo_apellido"></campo>
        <campo row="col-sm-4" label="Tercer Apellido" codigo="t_apellido" tipo="text" requerido="false" :valor="persona.tercer_apellido"></campo>
        <campo row="col-sm-4" label="Fecha de nacimiento" codigo="fecha_nac" tipo="date" requerido="true" :valor="persona.fecha_denacimiento"></campo>

        <campo row="col-sm-4" label="Correo electrónico" codigo="email" tipo="text" requerido="false" :valor="persona.email"></campo>
        <campo row="col-sm-4" label="Empadronamiento" codigo="empadronamiento" tipo="text" requerido="false" :valor="persona.empadronamiento"></campo>
        <lugar-seleccion row1="col-sm-12" row2="col-sm-4" :depto="persona.id_departamento" :muni="persona.id_municipio" :lugar="persona.id_lugar" tipo="0"></lugar-seleccion>
        <campo row="col-sm-4" label="NIT" codigo="nit" tipo="text" requerido="false" :valor="persona.nit"></campo>
        <campo row="col-sm-4" label="NISP" codigo="nisp" tipo="text" requerido="false" :valor="persona.nisp"></campo>
        <campo row="col-sm-4" label="IGSS" codigo="igss" tipo="text" requerido="false" :valor="persona.igss"></campo>

        <combo row="col-sm-4" label="Procedencia" codigo="procedencia" :arreglo="procedencia" tipo="2" requerido="false" :valor="persona.id_procedencia"></combo>
        <combo row="col-sm-4" label="Estado Civil" codigo="estado_civil" :arreglo="estadoCivil" tipo="2" requerido="false" :valor="persona.id_estado_civil"></combo>
        <combo row="col-sm-4" label="Género" codigo="genero" :arreglo="genero" tipo="2" requerido="false" :valor="persona.id_genero"></combo>
        <combo row="col-sm-4" label="Tipo de Servicio" codigo="tipo_servicio" :arreglo="tipoServicio" tipo="2" requerido="false" :valor="persona.id_tipo_servicio"></combo>

        <combo row="col-sm-4" label="Religión" codigo="religion" :arreglo="religion" tipo="2" requerido="false" :valor="persona.id_religion"></combo>
        <combo row="col-sm-4" label="Profesión" codigo="profesion" :arreglo="profesiones" tipo="2" requerido="false" :valor="persona.id_profesion"></combo>
        <combo row="col-sm-4" label="Tipo Curso" codigo="tipo_curso" :arreglo="tipoCurso" tipo="2" requerido="false" :valor="persona.id_tipo_curso"></combo>
        <combo row="col-sm-4" label="Promoción" codigo="promocion" :arreglo="promociones" tipo="2" requerido="false" :valor="persona.ïd_promocion"></combo>
        <campo row="col-sm-4" label="Fecha curso" codigo="fecha_cur" tipo="date" requerido="false" :valor="persona.fecha_curso"></campo>

        <combo-items codigo="id_tipo_sangre" label="Tipo de sangre" id_catalogo="15" col="col-sm-4" :valor="persona.id_tipo_sangre"></combo-items>
        <campo row="col-sm-4" label="CUI" codigo="cui" tipo="number" requerido="true" :valor="persona.cui"></campo>
        <campo row="col-sm-4" label="Vencimiento del DPI" codigo="cui_ven" tipo="date" requerido="true" :valor="persona.cui_ven"></campo>
        <campo row="col-sm-12" label="observaciones" codigo="observaciones" tipo="textarea" requerido="false" :valor="persona.observaciones"></campo>
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
      </div>
      <br>
    </form>

  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      tipoPersonas:[],
      tipoServicio:[],
      estadoCivil:[],
      profesiones:[],
      genero:[],
      procedencia:[],
      tipoCurso:[],
      promociones:[],
      religion:[]
    }
  },
  mounted: function() {
  },
  watch: {
    testProp: function(newVal, oldVal) {
      this.actualizarInformacion(1)
    }
  },
  methods:{
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      if(id == 1){
        thisInstance.actualizarInformacion(id);
      }else{
        eventBus.$emit('regresarPadre', 1);
        $('.btn-10').removeClass('active');
      }
    },
    respuesta: function(opc) {
			this.$emit('eventAccion', opc);
		},
    emitGlobalClickEvent() {
      //this.clickCount++;
      eventBus.$emit('regresarPadre', 1);
    },
    actualizarInformacion: function(opc){
      //this.$emit('eventFormulario', opc);
      jQuery('.jsValidacionActualizarInformacion').validate({
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
          var form = $('#formActualizarPersona');
          Swal.fire({
            title: '<strong>¿Desea actualizar la información del empleado?</strong>',
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
                url: "empleados/php/back/persona/actualizar_persona.php",
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  $('#id_cambio').val(1);
                  eventBus.$emit('recargarPersona', 1);
                  eventBus.$emit('regresarPadre', 1);
                  $('.btn-10').removeClass('active');
                  viewModelPersona.get_persona_by_id();
                  Swal.fire({
                    type: 'success',
                    title: 'Información Actualizada',
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
    getTipoPersona: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:51,
          tipo:0
        }
      }.bind(this))
      .then(function (response) {
        this.tipoPersonas = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getTipoServicio: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:6,
          tipo:0
        }
      })
      .then(function (response) {
        this.tipoServicio = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getEstadoCivil: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:1,
          tipo:0
        }
      })
      .then(function (response) {
        this.estadoCivil = response.data;
      }.bind(this))
      .catch(function (error) {
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
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getGenero: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:19,
          tipo:0
        }
      })
      .then(function (response) {
        this.genero = response.data;
        $("#profesion").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getProcedencia: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:30,
          tipo:0
        }
      })
      .then(function (response) {
        this.procedencia = response.data;
        $("#profesion").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getTipoCurso: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:11,
          tipo:0
        }
      })
      .then(function (response) {
        this.tipoCurso = response.data;
        $("#tipo_curso").select2({
          placeholder: "Seleccionar el tipo",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getPromociones: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:73,
          tipo:0
        }
      })
      .then(function (response) {
        this.promociones = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getTipoReligion: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:79,
          tipo:0
        }
      })
      .then(function (response) {
        this.religion = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getTipoServicio(),
    this.getEstadoCivil(),
    this.getProfesiones(),
    this.getGenero(),
    this.getProcedencia(),
    this.getTipoCurso(),
    this.getPromociones(),
    this.getTipoReligion()
  }

})

Vue.component("form-asignacion-plaza",{
  props:["id_persona","id_empleado","id_asignacion","tipo_accion", "tipoEstado"],
  template:`
  <div>

    <h3 v-if="tipo_accion==1">Asignar Plaza</h3><h3 v-else>Actualizar Plaza</h3><br>
    <form class="jsValidationAsignacionPlaza" id="formAsignacionPlaza">
      <input id="id_gafete" name="id_gafete" :value="id_persona" hidden></input>
      <input id="id_empleado" name="id_empleado" :value="id_empleado" hidden></input>
      <input id="id_plaza_pl" name="id_plaza_pl" v-if="tipo_accion == 2" v-bind:value="asignacion.id_plaza" hidden></input>
      <input id="id_asignacion" name="id_asignacion" v-if="tipo_accion == 2" v-bind:value="asignacion.id_asignacion" hidden></input>
      <input id="tipo_de_accion" name="tipo_de_accion" v-bind:value="tipo_accion" hidden></input>
      <div class="row">
        <plazas-disponibles row='col' v-if="tipo_accion==1"></plazas-disponibles>
        <div class="col-sm-12">
          <span class="numberr">1</span><strong class=""> Datos del Acuerdo</strong><br>
        </div>
        <campo row="col-sm-4" label="No. Acuerdo*" codigo="id_nro_acuerdo_p_pl" tipo="text" requerido="true" :valor="asignacion.nro_acuerdo"></campo>
        <campo row="col-sm-4" label="Fecha del Acuerdo*" codigo="id_fecha_acuerdo_asignacion_p_pl" tipo="date" requerido="true":valor="asignacion.fecha_acuerdo"></campo>
        <campo row="col-sm-4" label="Fecha de Toma de Posesión*" codigo="id_fecha_toma_posesion_p_pl" tipo="date" requerido="true":valor="asignacion.fecha_toma_posesion"></campo>
        <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_asignacio_p_pl" tipo="textarea" requerido="true":valor="asignacion.descripcion" ></campo>
        <!-- fin -->
        <div class="col-sm-12" v-if="tipo_accion == 1">
          <div class="form-group">
            <div class="">
              <div class="row">
                <label for="id_es_extension">Datos funcionales no igual al Nominal*</label>
                <div class=" input-group  has-personalizado" >
                    <label class="css-input switch switch-success"><input class="chequeado" v-model="validarFuncional" id="chkFuncional" name="chkFuncional" @change="datosFuncionales()" type="checkbox"/><span></span></label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- inicio -->
        <div id="formulario_funcional" v-if="validarFuncional" class="row slide_up_anim">
          <div class="col-sm-12">
            <span class="numberr">2</span><strong class=""> Datos del Funcionales</strong><br>
          </div>
            <!-- inicio component -->
          <div>
            <formulario-funcional row1='col-sm-6' row2='col-sm-4':arrelgo="asignacion" :tipo_accion="tipo_accion"></formulario-funcional>
          </div>
          <!-- fin component -->
        </div>
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
      </div>
      <!-- fin -->
    </form>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      validarFuncional:false,
      asignacion:""
    }
  },
  mounted: function() {
  },
  methods:{
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      if(id == 1){
        thisInstance.asignarPlaza(id);
      }else{
        if(this.tipoEstado > 0){
          eventBus.$emit('regresarPrincipal', 2);
        }
        else{
          eventBus.$emit('showDetallePlazas', 1);
        }
      }
    },
    datosFuncionales: function() {
      if( $('#chkFuncional').is(':checked') )
      {
        this.validarFuncional = true;
      }else{
        this.validarFuncional = false;
      }
    },
    getAsignacionPlaza: function(){
      if(this.tipo_accion == 2){
        axios.get('empleados/php/back/plazas/plaza_por_asignacion.php', {
          params: {
            id_asignacion:this.id_asignacion
          }
        }).then(function (response) {
          this.asignacion = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    asignarPlaza: function(opc) {
      jQuery('.jsValidationAsignacionPlaza').validate({
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

          //inicio
          var form = $('#formAsignacionPlaza');
          Swal.fire({
            title: '<strong>¿Quiere asignar la plaza a este empleado?</strong>',
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
                url: "empleados/php/back/plazas/asignacion_empleado_plaza.php",
                data: form.serialize(),
                beforeSend:function(){

                },
                success:function(data){
                  //alert(data);
                  $('#id_cambio').val(1);
                  eventBus.$emit('recargarPuesto', 1);
                  eventBus.$emit('recargarAsignacionDetalle', 1);
                  eventBus.$emit('showDetallePlazas', 5);
                  if(data > 0){
                    viewModelDatosEmp.get_datos_empleado();
                    eventBus.$emit('regresarPrincipal', 1);
                  }


                  Swal.fire({
                    type: 'success',
                    title: 'Asignación de plaza generada',
                    showConfirmButton: false,
                    timer: 1100
                  });
                  //alert(data);
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });

            }

          })
        },
        rules: {
          id_nro_acuerdo_p_pl: {
            remote: {
              url: 'empleados/php/back/contratos/validar_contrato_activo.php',
              data: {
                id_persona: function(){ return $('#id_gafete').val();}
              }
            }
          }
        },
        messages: {
          id_nro_acuerdo_p_pl: {
            remote: "El empleado tiene un contrato activo, por favor finalizar contrato."
          }
        }
      });
		}
  },
  created: function(){
    this.getAsignacionPlaza();
  }
})

Vue.component("form-asignacion-contrato",{
  props:["id_persona","id_empleado","id_reng_num","tipo_accion", "tipoEstado"],
  template:`
    <form class="jsValidationAsignacionContrato" id="formAsignacionContrato">
    <h3 v-if="tipo_accion==1">Asignar Contrato</h3><h3 v-else>Actualizar Contrato</h3><br>
    <input id="id_gafete" name="id_gafete" :value="id_persona" hidden></input>
    <input id="id_empleado" name="id_empleado" :value="id_empleado" hidden></input>
    <input id="reng_num" name="reng_num" v-if="tipo_accion == 2" v-bind:value="id_reng_num" hidden></input>
    <input id="tipo_de_accion" name="tipo_de_accion" v-bind:value="tipo_accion" hidden></input>
      <div class="row">
        <!-- fin-->
        <div class="col-sm-12">
          <span class="numberr">1</span><strong class=""> Datos del Contrato</strong><br>
        </div>
        <campo row="col-sm-3" label="No. Acuerdo*" codigo="id_nro_acuerdo_ct" tipo="text" requerido="true" :valor="contrato.acuerdo"></campo>
        <campo row="col-sm-3" label="Fecha de Aprobación*" codigo="id_fecha_aprobacion_ct" tipo="date" requerido="true" :valor="contrato.fecha_acuerdo_aprobacion"></campo>
        <campo row="col-sm-3" label="Monto total*" codigo="id_monto_ct" tipo="number" requerido="true" :valor="contrato.monto"></campo>
        <!-- inicio -->
        <div class="col-sm-3">
          <div class="form-group">
            <div class="">
              <div class="">
                <label for="id_tipo_contrato_ct">Tipo de contrato*</label>
                <div class=" input-group  has-personalizado" >
                  <select id="id_tipo_contrato_ct" name="id_tipo_contrato_ct" class="form-control form-control-sm form-control-alternative" required v-model="contrato.tipo_contrato">
                    <option value="">-- Seleccionar --</option>
                    <option value="8">JORNALES</option>
                    <option value="9">CONTRATO 029</option>
                    <option value="906">OTROS ESTUDIOS Y/O SERVICIOS</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- fin -->
        <!-- inicio datos del contrato -->
        <campo row="col-sm-3" label="No. de contrato*" codigo="id_nro_contrato_ct" tipo="text" requerido="true" :valor="contrato.nro_contrato"></campo>
        <campo row="col-sm-3" label="Fecha Contrato*" codigo="id_fecha_contrato_ct" tipo="date" requerido="true" :valor="contrato.fecha_contrato"></campo>
        <campo row="col-sm-3" label="Fecha inicio*" codigo="id_fecha_inicio_ct" tipo="date" requerido="true" :valor="contrato.fecha_inicio"></campo>
        <campo row="col-sm-3" label="Fecha fin*" codigo="id_fecha_fin_ct" tipo="date" requerido="true" :valor="contrato.fecha_fin"></campo>
        <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_asignacio_p_ct" :valor="contrato.observaciones" tipo="textarea" requerido="true"></campo>
        <!-- fin datos del contrato -->
        <!-- inicio -->
        <div class="row slide_up_anim">
          <div class="col-sm-12">
            <span class="numberr">2</span><strong class=""> Datos del Funcionales</strong><br>
          </div>
          <!-- inicio component -->
          <div>
            <formulario-funcional row1='col-sm-6' row2='col-sm-4' tipo="contrato" :arreglo="contrato" :tipo_accion="tipo_accion"></formulario-funcional>
          </div>
          <!-- fin component -->
        </div>
        <!-- fin -->
        <!-- inicio -->
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
        <!-- fin -->
      </div>
        <!-- fin -->
    </form>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      validarFuncional:false,
      contrato:""
    }
  },
  mounted: function() {
  },
  methods:{
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      if(id == 1){
        thisInstance.asignarContrato(id);
      }else{
        if(this.tipoEstado > 0){
          console.log('1');
          eventBus.$emit('regresarPrincipal', 3);
        }
        else{
          console.log('2');
          eventBus.$emit('showDetalleContratos', 1);
        }

      }
    },
    getAsignacionContrato: function(){
      if(this.tipo_accion == 2){
        axios.get('empleados/php/back/contratos/contrato_por_asignacion.php', {
          params: {
            id_reng_num:this.id_reng_num
          }
        }).then(function (response) {
          this.contrato = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    asignarContrato: function(opc) {
      jQuery('.jsValidationAsignacionContrato').validate({
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
          var form = $('#formAsignacionContrato');
          //inicio
          Swal.fire({
            title: '<strong>¿Quiere asignar el contrato a este empleado?</strong>',
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
                url: "empleados/php/back/contratos/asignar_contrato_empleado.php",
                data: form.serialize(),
                beforeSend:function(){
                },
                success:function(data){
                  //alert(data);
                  $('#id_cambio').val(1);
                  eventBus.$emit('recargarPuesto', 1);
                  eventBus.$emit('recargarAsignacionDetalle', 1);
                  eventBus.$emit('showDetalleContratos', 5);
                  if(data > 0){
                    viewModelDatosEmp.get_datos_empleado();
                    eventBus.$emit('regresarPrincipal', 1);
                  }
                  //viewModelDatosEmp.get_empleado();

                  //viewModelDatosEmp.getOpcion(3);
                  Swal.fire({
                    type: 'success',
                    title: 'Asignación de contrato generado',
                    showConfirmButton: false,
                    timer: 1100
                  });
                  //alert(data);
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }

          })
        },
        rules: {
          id_monto:{ required: true},
          id_tipo_contrato:{required:true},
          id_categoria:{required:true},
          id_nro_acuerdo_ct: {
            remote: {
              url: 'empleados/php/back/plazas/validar_plaza_activa.php',
              data: {
                id_persona: function(){ return $('#id_gafete').val();}
              }
            }
          }
        },
        messages: {
          id_nro_acuerdo_ct: {
            remote: "El empleado tiene una plaza activa, por favor finalizar la asignación."
          }
        }

      });
		}
  },
  created: function(){
    this.getAsignacionContrato();
  }
})

Vue.component("form-asignacion-apoyo",{
  props:["id_persona","id_empleado","id_reng_num","tipo_accion"],
  template:`
    <form class="jsValidationAsignacionApoyo" id="formAsignacionApoyo">
    <h3 v-if="tipo_accion==1">Asignar personal de apoyo</h3><h3 v-else>Actualizar Asignación</h3><br>
    <input id="id_gafete" name="id_gafete" :value="id_persona" hidden></input>
    <input id="id_empleado" name="id_empleado" :value="id_empleado" hidden></input>
    <input id="reng_num" name="reng_num" v-if="tipo_accion == 2" v-bind:value="id_reng_num" hidden></input>
    <input id="tipo_de_accion" name="tipo_de_accion" v-bind:value="tipo_accion" hidden></input>
      <div class="row">
        <!-- fin-->
        <div class="col-sm-12">
          <span class="numberr">1</span><strong class=""> Datos del Contrato</strong><br>
        </div>
        <!-- inicio datos del contrato -->

        <campo row="col-sm-3" label="Fecha inicio*" codigo="id_fecha_inicio_apy" tipo="date" requerido="true" :valor="apoyo.fecha_inicio"></campo>
        <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_asignacio_p_apy" tipo="textarea" requerido="true"></campo>
        <!-- fin datos del contrato -->
        <!-- inicio -->
        <div class="row slide_up_anim">
          <div class="col-sm-12">
            <span class="numberr">2</span><strong class=""> Datos del Funcionales</strong><br>
          </div>
          <!-- inicio component -->
          <div>
            <formulario-funcional row1='col-sm-4' row2='col-sm-4' :arreglo="apoyo" tipo="contrato" :tipo_accion="tipo_accion"></formulario-funcional>
          </div>
          <!-- fin component -->
        </div>
        <!-- fin -->
        <!-- inicio -->
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
        <!-- fin -->
      </div>
        <!-- fin -->
    </form>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      validarFuncional:false,
      apoyo:""
    }
  },
  mounted: function() {
  },
  methods:{
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      if(id == 1){
        thisInstance.asignarApoyo(id);
      }else{
        eventBus.$emit('regresarPrincipal', 3);
      }
    },
    getAsignacionApoyo: function(){
      if(this.tipo_accion == 2){
        axios.get('empleados/php/back/contratos/apoyo_por_asignacion.php', {
          params: {
            id_reng_num:this.id_reng_num
          }
        }).then(function (response) {
          this.contrato = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    asignarApoyo: function(opc) {
      jQuery('.jsValidationAsignacionApoyo').validate({
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
          var form = $('#formAsignacionApoyo');
          //inicio
          Swal.fire({
            title: '<strong>¿Quiere crear al empleado de apoyo?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/apoyo/asignar_apoyo_empleado.php",
                data: form.serialize(),
                beforeSend:function(){
                },
                success:function(data){
                  //alert(data);
                  $('#id_cambio').val(1);
                  eventBus.$emit('recargarPuesto', 1);
                  eventBus.$emit('recargarAsignacionDetalle', 1);
                  Swal.fire({
                    type: 'success',
                    title: 'Asignación de contrato generado',
                    showConfirmButton: false,
                    timer: 1100
                  });
                  //alert(data);
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }

          })
        },
        rules: {
          id_monto:{ required: true},
          id_tipo_contrato:{required:true},
          id_categoria:{required:true},
          id_nro_acuerdo_ct: {
            remote: {
              url: 'empleados/php/back/plazas/validar_plaza_activa.php',
              data: {
                id_persona: function(){ return $('#id_gafete').val();}
              }
            }
          }
        },
        messages: {
          id_nro_acuerdo_ct: {
            remote: "El empleado tiene una plaza activa, por favor finalizar la asignación."
          }
        }

      });
		}
  },
  created: function(){
    this.getAsignacionApoyo();
  }
})

Vue.component("form-asignacion-ubicacion",{
  props:["id_persona","id_asignacion","reng_num","plazas","tipo_accion"],
  template:`
  <div>
  <h3>Actualizar ubicación</h3><br>
  <form class="jsValidationPuesto form-material" id="formValidationPuesto">
  <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>

  <input id="id_asignacion" name="id_asignacion" :value="id_asignacion" hidden></input>
  <input id="reng_num" name="reng_num" :value="reng_num" hidden></input>
  <input id="tipo_de_accion" name="tipo_de_accion" :value="tipo_accion" hidden></input>
    <div class="row">
      <!-- inicio -->
      <campo row="col-sm-3" label="No. del Acuerdo*" codigo="id_nro_acuerdo_f_u" tipo="text" requerido="true" :valor="puesto.acuerdo"></campo>
      <campo row="col-sm-3" label="Fecha del Acuerdo*" codigo="id_fecha_acuerdo_f_u" tipo="date" requerido="true" :valor="puesto.fecha_toma_posesion"></campo>
      <campo row="col-sm-3" label="Fecha Inicio*" codigo="id_fecha_inicio_f_u" tipo="date" requerido="true" :valor="puesto.fecha_inicio"></campo>
      <campo row="col-sm-3" label="Fecha Fin*" codigo="id_fecha_fin_f_u" tipo="date" requerido="false" :valor="puesto.fecha_fin"></campo>
      <formulario-funcional row1='col-sm-3' row2='col-sm-3' tipo_accion="2" :arreglo="puesto"></formulario-funcional>
      <campo row="col-sm-12" label="Detalle*" codigo="id_detalle_asignacio_f_u" tipo="textarea" requerido="true" :valor="puesto.observaciones"></campo>

      <div class="col-sm-3 text-right" v-if="tipo_accion == 1">
        <div class=" input-group  has-personalizado" >
          <label class="css-input switch switch-success"><input class="chequeado" id="chk_retroactivo" type="checkbox" @click="validar_aplicacion_funcional()"/><span></span> Aplicar cambios</label>
        </div>
      </div>
      <div class="col-sm-6 text-right" v-if="tipo_accion == 1">
        <div class="" v-if="aplica_cambios==0">
          <combo row="col-sm-6" label="Seleccionar plaza retroactiva" codigo="cod_plaza" :arreglo="plazas" tipo="1" requerido="true"></combo>
        </div>
        <div v-else>
          Los cambios se aplicarán a la plaza actual del empleado.
        </div>
      </div>
      <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>

    </div>
  </form>
  </div>


  `,
  data() {
    return {
      aplica_cambios:0,
      puesto:""
    }
  },
  methods: {
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      if(id == 1){
        thisInstance.actualizarUbicacion(id);
      }else{
        eventBus.$emit('showDetalleUbicaciones', 1);
      }
    },
    validar_aplicacion_funcional: function(){
      if($('#chk_retroactivo').is(':checked')){
        this.aplica_cambios=1;
      }else{
        this.aplica_cambios=0;
      }
    },
    getAsignacion: function(){

      if(this.tipo_accion == 2){
        axios.get('empleados/php/back/puestos/get_puesto_by_id.php', {
          params: {
            id_persona:this.id_persona,
            id_asignacion:this.id_asignacion,
            reng_num:this.reng_num
          }
        }).then(function (response) {
          this.puesto = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      }
    },
    actualizarUbicacion: function(){
      jQuery('.jsValidationPuesto').validate({
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
          var form = $('#formValidationPuesto');
          Swal.fire({
            title: '<strong>¿Desea actualizar el puesto del empleado?</strong>',
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
                url: "empleados/php/back/puestos/actualizar_puesto.php",
                data: form.serialize(),
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  $('#id_cambio').val(1);
                  Swal.fire({
                    type: 'success',
                    title: 'Puesto actualizado',
                    showConfirmButton: false,
                    timer: 1100
                  });
                  //viewModelDatosEmp.get_empleado();
                  eventBus.$emit('recargarPuesto', 1);
                  eventBus.$emit('recargarAsignacionDetalle', 1);
                  eventBus.$emit('showDetalleUbicaciones', 5);
                }
              }).done( function() {


              }).fail( function( jqXHR, textSttus, errorThrown){

                alert(errorThrown);

              });

            }

          })
        },
        rules: {
          combo1:{ required: true},
          //combo2:{ required: true},
          combo3:{ required: true},
          combo4:{ required: true},
          combo5:{ required: true},
          //combo6:{ required: true},
          combo7:{ required: true},
          combo8:{ required: true},
         }

      });
    }
  },
  created() {
    this.getAsignacion();
  }
})

Vue.component("listado-catalogo", {
  props:["tipo","tipoc","opciona"],
  template:`
  <div >
  <!--{{tipoc}}-->
    <form class="jsValidationCatalogoNuevo" id="nuevoCatalogo">
      <div class="row">
        <combo row="col-sm-6" label="Seleccionar Catalogo" codigo="id_catalogo" :arreglo="catalogo" tipo="3" requerido="true" :valor="dCatalogo.id_catalogo"></combo>
        <campo row="col-sm-6" label="Nombre del catálogo*" codigo="item_name" tipo="text" requerido="true" :valor="dCatalogo.catalogo_name"></campo>
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
      </div>
    </form>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      catalogo:"",
      dCatalogo:""
    }
  },
  mounted: function() {
  },
  methods:{
    getCatalogo: function(){
      axios.get('empleados/php/back/listados/get_catalogo.php', {
        params: {
          tipo:this.tipo
        }
      })
      .then(function (response) {
        this.catalogo = response.data;
        $("#id_catalogo").select2({
          placeholder: "Seleccionar catalogo",
          allowClear: true
        });
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      var tipoC = this.tipoc;
      if(id == 1){
        thisInstance.crearItem();
      }else{
        eventBus.$emit('regresarListaCatalogo', 1);
      }
    },
    crearItem: function(){
      var opcionActual = this.opciona;
      jQuery('.jsValidationCatalogoNuevo').validate({
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
          Swal.fire({
            title: '<strong>¿Desea crear este nuevo catálogo?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              var form = $('#nuevoCatalogo');
              $.ajax({
                type: "POST",
                url: "empleados/php/back/catalogo/crear_item.php",
                dataType: 'json',
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  //alert(data);
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: 'Catálogo creado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    eventBus.$emit('regresarPrincipal', opcionActual);

                    eventBus.$emit('recargarCatalogo', 1);
                    eventBus.$emit('recargarCatalogoEscolaridad');
                    eventBus.$emit('recargarCatalogoCuentas');
                    eventBus.$emit('recargarPuestosFuncional');


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
        },
        rules: {
          combo1:{ required: true},
          //combo2:{ required: true},
          combo3:{ required: true},
          combo4:{ required: true},
          combo5:{ required: true},
          //combo6:{ required: true},
          combo7:{ required: true},
          combo8:{ required: true},
         }
      });
    }

  },
  created: function(){
    this.getCatalogo();
  }
});


Vue.component("nuevo-lugar", {
  props:["tipo"],
  template:`
  <div >
    <form class="jsValidationNuevoLugarGeografico" id="formNuevoLugarGeografico">
      <div class="row">
        <lugar-seleccion row1="col-sm-12" row2="col-sm-4" :depto="lugar.id_departamento" :muni="lugar.id_municipio" :lugar="lugar.id_lugar" tipo="1"></lugar-seleccion>
        <combo row="col-sm-12" label="Tipo de lugar*" codigo="id_tipo_lugar" :arreglo="tipoLugares" tipo="3" requerido="true" :valor="lugar.id_tipo_lugar"></combo>
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
      </div>
    </form>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      lugar:"",
      tipoLugares:[]
    }
  },
  mounted: function() {
  },
  methods:{
    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      if(id == 1){
        thisInstance.crearLugar();
      }else{
        eventBus.$emit('regresarListaCatalogo', 1);
      }
    },
    crearLugar: function(){
      jQuery('.jsValidationNuevoLugarGeografico').validate({
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
          Swal.fire({
            title: '<strong>¿Desea crear este nuevo lugar?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              var form = $('#nuevoLugarGeografico');
              $.ajax({
                type: "POST",
                url: "empleados/php/back/catalogo/crear_lugar.php",
                dataType: 'json',
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  //alert(data);
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: 'Lugar creado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    eventBus.$emit('regresarPrincipal', 1);
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
        }
      });
    },
    getTipoLugares: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:61,
          tipo:0
        }
      })
      .then(function (response) {
        this.tipoLugares = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    }
  },
  created: function(){
    this.getTipoLugares();
  }
});

// Component A
Vue.component('aspirante-proceso', {
  props:['id_persona','id_empleado'],
  template:
  `
  <div>
    <div v-if="id_empleado==0">
      <h2>Aspirante en Proceso</h2>
      <span class="btn btn-sm btn-soft-info" @click="setOpcion(13)"><i class="fa fa-plus-circle"></i> Generar empleado</span>
      <span class="btn btn-sm btn-soft-danger" @click="setOpcion(17)"><i class="fa fa-times-circle"></i> Denegar</span>
    </div>
    <div v-if="opcion==13">
      <br>
      <form class="jsValidationSiguienteEstado" id="formValidationSiguienteEstado">
      <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
      <input id="tipo_accion" name="tipo_accion" :value="opcion" hidden></input>
      <input id="id_tipo_aspirante" name="id_tipo_aspirante" :value="seleccion" hidden></input>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_siguiente_proceso">Tipo de ingreso*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="js-select2 form-control form-control-sm form-control-alternative" id="id_siguiente_proceso" name="id_siguiente_proceso" @change="setTipoEstado($event)">
                      <option>-- Sleccionar --</option>
                      <option value='1'>011 - Presupuestado</option>
                      <option value='2'>029 - Servicios Profesionales</option>
                      <option value='3'>031 - Tallerista</option>
                      <option value='4'>Personal de apoyo</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3" v-if="seleccion != 4">
            <div class="form-group">
              <label for="" class="text-white">*</label>
              <div class=" input-group  has-personalizado" >
                <button type="submit" class="btn btn-sm btn-info" @click="setSeguimiento(1)"><i class="fa fa-check"></i> Generar ingreso</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- denegar empleado -->
    <div v-else-if="opcion==17">
      <br>
      <form class="jsValidationSiguienteEstado" id="formValidationSiguienteEstado">
      <input id="id_persona" name="id_persona" :value="id_persona" hidden></input>
      <input id="tipo_accion" name="tipo_accion" :value="opcion" hidden></input>
      <input id="id_tipo_aspirante" name="id_tipo_aspirante" value="" hidden></input>
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_siguiente_proceso">Denegar aspirante*</label>
                  <div class=" input-group  has-personalizado" >
                    <select class="js-select2 form-control form-control-sm form-control-alternative" id="id_siguiente_proceso" name="id_siguiente_proceso">
                      <option>-- Sleccionar --</option>
                      <option value='1027'>Denegado por Polígrafo</option>
                      <option value='1028'>Denegado por Reclutamiento</option>
                      <option value='1031'>Denegado por academia</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label for="" class="text-white">*</label>
              <div class=" input-group  has-personalizado" >
                <button type="submit" class="btn btn-sm btn-danger" @click="setSeguimiento(2)"><i class="fa fa-check"></i> Generar denegación</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  `
  ,
  data() {
    return {
      opcion:0,
      m1:"",
      m2:"",
      color:"",
      seleccion:0
    }
  },
    methods: {
      setOpcion: function(opc){
        this.opcion = opc;
        this.m1 = (this.opcion == 13) ? ' seguir el proceso' : ' denegar al aspirante';
        this.m2 = (this.opcion == 13) ? ' seguir' : ' denegar';
        this.color = (this.opcion == 13) ? '#28a745' : '#d33';
      },
      setTipoEstado: function(event){
        this.seleccion = event.currentTarget.value;
        this.$emit('siguiente-proceso', event.currentTarget.value);
      },
      setSeguimiento: function(opc){
        const thisInstance = this;
        jQuery('.jsValidationSiguienteEstado').validate({
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
            var form = $('#formValidationSiguienteEstado');

            Swal.fire({
              title: '<strong>¿Desea'+thisInstance.m1+'?</strong>',
              text: "",
              type: 'question',

              showCancelButton: true,
              confirmButtonColor: thisInstance.color,
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si,'+thisInstance.m2+'!',
              input: 'textarea',
              inputPlaceholder: 'Especifique el motivo',
              inputValidator: function(inputValue) {
                return new Promise(function(resolve, reject) {
                  if (inputValue && inputValue.length > 0) {
                    resolve();
                    motivo=inputValue;
                  } else {
                    Swal.fire({
                      type: 'error',
                      title: 'Debe especificar el motivo',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                });
              }
            }).then((result) => {
              if (result.value) {
                //alert(vt_nombramiento);
                $.ajax({
                  type: "POST",
                  url: "empleados/php/back/persona/action/establecer_estado.php",
                  dataType: 'json',
                  //data: form.serialize(), //f de fecha y u de estado.
                  data: {
                    id_persona: $('#id_persona').val(),
                    tipo_accion: $('#tipo_accion').val(),
                    id_siguiente_proceso: $('#id_siguiente_proceso').val(),
                    id_tipo_aspirante: $('#id_tipo_aspirante').val(),
                    motivo:motivo
                  },
                  beforeSend:function(){
                    //$('#response').html('<span class="text-info">Loading response...</span>');
                    //alert('message_before')
                  },
                  success:function(data){
                    if(data.msg == 'OK'){
                      $('#id_cambio').val(1);
                      $('#id_tipo_filtro').val(data.filtro);
                      $('#modal-remoto-lgg2').modal('hide');
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
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
    created: function(){

    }
});

//nuevo curso
Vue.component("form-nuevo-curso", {
  props:["tipo","tipoc","opciona"],
  template:`
  <div >
  <!--{{tipoc}}-->
    <form class="jsValidacionCursoNuevo" id="formValidacionCursoNuevo">
      <div class="row">
        <combo-items row="col-sm-3" label="Tipo de Curso*" codigo="id_tipo_curso" id_catalogo="11" ></combo-items>
        <combo-items row="col-sm-3" label="Centro de Capacitación*" codigo="id_centro_capacitacion" id_catalogo="32"></combo-items>
        <campo tipo="text" row="col-sm-12" codigo="id_nombre_curso"></campo>
        <accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>
      </div>
    </form>
  </div>
  `,
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      catalogo:"",
      dCatalogo:""
    }
  },
  mounted: function() {
  },
  methods:{

    eventAccion: function(id) {
      const thisInstance = this;
      //console.log('Event from action component emitted', id);
      var tipoC = this.tipoc;
      if(id == 1){
        thisInstance.crearCurso();
      }else{
        eventBus.$emit('regresarListaCatalogo', 1);
      }
    },
    crearCurso: function(){
      var opcionActual = this.opciona;
      jQuery('.jsValidacionCursoNuevo').validate({
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
          Swal.fire({
            title: '<strong>¿Desea crear este nuevo curso?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              var form = $('#formValidacionCursoNuevo');
              $.ajax({
                type: "POST",
                url: "empleados/php/back/catalogo/crear_curso.php",
                dataType: 'json',
                data: form.serialize(), //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  //alert(data);
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: 'Curso creado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    //eventBus.$emit('regresarPrincipal', opcionActual);
                    eventBus.$emit('recargarCatalogo', 1);
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
        },
        rules: {
          combo1:{ required: true},
          //combo2:{ required: true},
          combo3:{ required: true},
          combo4:{ required: true},
          combo5:{ required: true},
          //combo6:{ required: true},
          combo7:{ required: true},
          combo8:{ required: true},
         }
      });
    }

  },
  created: function(){
    setTimeout(() => {
      $('#id_tipo_curso,#id_centro_capacitacion').select2({});
    },500);

  }
});

//fin nuevo curso


// Component A
Vue.component('component1', {
  template:
  `
    <button @click="emitGlobalClickEvent()">{{ clickCount }}</button>
  `
  ,
  data() {
    return {
      clickCount: 0
    }
  },
    methods: {
      emitGlobalClickEvent() {
        this.clickCount++;
        eventBus.$emit('clicked', this.clickCount);
      }
    }
});

// Component B
Vue.component('component2', {
  template:`
    <span class="btn btn-sm btn-info" @click="bar()"><i class="fa fa-check-circle"></i> Intentar</span>
  `,
  methods: {
    bar: function() {
      const clickHandler = function(clickCount) {
        console.log(`The button has been clicked ${clickCount} times!!!!`)
      }
      eventBus.$on('clicked', clickHandler);
    }
  },
  created() {
    const clickHandler = function(clickCount) {
      console.log(`The button has been clicked ${clickCount} times!!!!`)
    }
    eventBus.$on('clicked', clickHandler);
  },
});
