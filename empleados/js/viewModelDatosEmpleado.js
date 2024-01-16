var eventBus = new Vue();
  var instanciaEmpD;

  //componente remoción

   var viewModelDatosEmp = new Vue({
    //el: '#emp_datos_app',
    data: {
      isLoaded: false,
      id_persona:$('#id_gafete').val(),
      id_plaza:"",
      id_asignacion:"",
      id_reng_num:"",
      datos_persona:"",
      datos_foto:"",
      datos_plaza:"",
      empleado:"",
      opcion:1,
      plazas:"",
      contratos:"",
      page: 1,
			perPage: 9,
			pages: [],
      id_superior:$('#id_subsecretaria_f').val(),
      ubicaciones:"",
      aplica_cambios:0,
      emp_plaza:"",
      reng_num:"",
      tipos_baja_contrato:"",
      tableCarnets:"",
      tipoCarnets:"",
      cambio:0,
      isLoaded:false,
      tipoEstado:0,
      tipoPlaza:0,
      arregloVacio:"",
      id_asignacion_plaza:"",
      id_asignacion_u:"",
      id_reng_num_u:"",
      dPuesto:"",
      datosPersona:"",
      privilegio:"",
      showCatalogo:false,
      cargarCatalogo:0,
      pdf:'',
      config: {
        toolbar: false
      },

      /*,
      recargar:[recargarEmpleados]*/
    },
    components: {
      //VuePdfApp: window["vue-pdf-app"]
    },
    destroyed: function(){
      this.viewModelDatosEmp;
    },
    beforeCreate: function(){
      //alert('cargando');
      $('#cargando').html('<div class="loaderr"></div>');
    },
    computed: {

      displayedPosts () {
        return this.paginate(this.contratos);
      }
    },
    watch: {
      contratos () {
        this.setPages();
      }
    },
    created: function(){
      //alert(viewModelDatosEmp.$refs.comp.getTipoPlaza);
      $('#cargando').hide();
      this.get_datos_empleado();
      //this.retardo()
      this.get_items();
      //this.get_status_baja();
      /*this.table_plazas();
      this.table_contratos();
      this.get_ubicaciones_funcionales();
      this.get_items_finalizacion_contrato();*/
      this.getTiposCarnets();
      this.getPrivilegio();
      eventBus.$on('regresarPrincipal', (opc) => {
        this.getOpcion(opc);
      });

    },
    methods: {
      getEmpEstadoChild: function(data) {
  			console.log('Data from component Detalle puesto', data);
        viewModelDatosEmp.datos_plaza = data;
        setTimeout(() => {
          viewModelDatosEmp.isLoaded = true;
        }, 500);
  		},
      getDetallePuestoChild: function(data){
        viewModelDatosEmp.dPuesto = data;
        viewModelDatosEmp.datos_persona = data;
        console.log('dPuesto', data.estado);


      },
      getOpcion: function(opc){
        if(opc==2){
          this.editableDatos();
        }
        if(opc==20){
          this.showCatalogo = true;
          this.cargarCatalogo = 1;
        }else{
          this.showCatalogo = false;
          this.cargarCatalogo = 0;
          this.opcion = opc;
        }
        /*if(opc == 20){
          this.showCatalogo = true;
        }else{
          this.showCatalogo = false;
        }*/

      },
      getOpcionRemocion: function(plaza, asignacion, opc){
        this.opcion=opc;
        this.id_plaza=plaza;
        this.id_asignacion=asignacion;
      },
      get_datos_empleado: function(){
        if(this.id_persona > 0){
          axios.get('empleados/php/back/empleado/get_datos_empleado.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelDatosEmp.datosPersona = response.data;



          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      getPrivilegio: function(){
        axios.get('empleados/php/back/functions/evaluar_privilegio_asignaciones', {
        }).then(function (response) {
          this.privilegio  = response.data;
        }.bind(this)).catch(function (error) {
          console.log(error);
        });
      },
      get_items: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_items.php', {
            params: {
              id_catalogo:29,
              tipo:1
            }
          })
          .then(function (response) {
            viewModelDatosEmp.items = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },

    get_status_baja: function(){
      //setTimeout(() => {
        //console.log($('#id_subsecretaria_f').val());
        axios.get('empleados/php/back/listados/get_items.php', {
          params: {
            id_catalogo:29,
            tipo:2
          }
        })
        .then(function (response) {
          viewModelDatosEmp.items = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      //}, 200);
    },
      table_plazas: function(){
        if(this.id_persona > 0){

          axios.get('empleados/php/back/listados/get_empleado_historial_plazas.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelDatosEmp.plazas = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      table_contratos: function(){
        if(this.id_persona > 0){

          axios.get('empleados/php/back/listados/contratos/get_contratos_by_persona.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelDatosEmp.contratos = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      get_ubicaciones_funcionales: function(){
        if(this.id_persona > 0){

          axios.get('empleados/php/back/listados/get_ubicaciones_por_asignacion.php', {
            params: {
              id_persona: this.id_persona
            }
          }).then(function (response) {
            viewModelDatosEmp.ubicaciones = response.data;
          }).catch(function (error) {
            console.log(error);
          });
        }
      },
      validar_aplicacion_funcional: function(){
        if($('#chk_retroactivo').is(':checked')){
          this.aplica_cambios=1;
        }else{
          this.aplica_cambios=0;
        }
      },
      tramiteDeSolvencia: function(){

        jQuery('.js-validation-tramite-solvencia').validate({
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
            Swal.fire({
              title: '<strong>¿Desea establecer en trámite de solvencia al empleado?</strong>',
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
                  url: "empleados/php/back/plazas/establecer_tramite_de_solvencia.php",
                  data: {
                    id_persona:$('#id_gafete').val(),
                    id_plaza:$('#id_plaza_re').val(),
                    fecha_acuerdo:$('#id_fecha_acuerdo_re').val(),
                    fecha:$('#id_fecha_remocion_re').val(),
                    nro_acuerdo:$('#id_nro_acuerdo_re').val(),
                    observaciones:$('#id_detalle_remocion_re').val(),
                    //tipo_baja:$('#id_tipo_baja_re').val(),
                    remocion_reingreso:$('#remocion_reingreso').is(':checked') ? true : false,
                    tipo_baja:$('#id_tipo_baja_bj').val(),
                    asignacion:$('#id_asignacion_re').val()
                  }, //f de fecha y u de estado.
                  beforeSend:function(){
                    //$('#response').html('<span class="text-info">Loading response...</span>');
                    //alert('message_before')
                  },
                  success:function(data){
                    $('#id_cambio').val(1);
                    eventBus.$emit('recargarPuesto', 1);
                    eventBus.$emit('recargarAsignacionDetalle', 1);
                    //viewModelDatosEmp.get_plaza_empleado();
                    //viewModelDatosEmp.get_empleado();
                    eventBus.$emit('showDetallePlazas', 5);
                    Swal.fire({
                      type: 'success',
                      title: 'Trámite generado',
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
            //fin


          },
          rules: {
            detalle_remocion:{ required: true},
            combo1:{ required: true}
           }

        });
      },
      crearBaja: function(){
        jQuery('.js-validation-crear-baja').validate({
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
            Swal.fire({
              title: '<strong>¿Desea dar de baja al empleado?</strong>',
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
                  url: "empleados/php/back/plazas/crear_baja_empleado.php",
                  data: {
                    id_persona:$('#id_gafete').val(),
                    id_plaza:$('#id_plaza_re').val(),
                    asignacion:$('#id_asignacion_re').val(),
                    id_tipo_baja:$('#id_tipo_baja_bj').val(),
                    id_fecha_baja:$('#id_fecha_baja_bj').val(),
                    id_detalle_baja:$('#id_detalle_baja_bj').val()
                  }, //f de fecha y u de estado.
                  beforeSend:function(){
                    //$('#response').html('<span class="text-info">Loading response...</span>');
                    //alert('message_before')
                  },
                  success:function(data){

                    //viewModelDatosEmp.get_plaza_empleado();
                    eventBus.$emit('recargarPuesto', 1);
                    eventBus.$emit('recargarAsignacionDetalle', 1);
                    //viewModelDatosEmp.get_empleado();
                    eventBus.$emit('showDetallePlazas', 5);
                    $('#id_cambio').val(1);
                    Swal.fire({
                      type: 'success',
                      title: 'Baja generada',
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
            //fin


          },
          rules: {
            combo_1:{ required: true}
           }

        });
      },
      asignarContrato: function(){
        jQuery('.js-validation-crear-contrato').validate({
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
                  url: "empleados/php/back/contratos/asignar_contrato_empleado.php",
                  data: {

                    id_persona:$('#id_gafete').val(),
                    id_empleado:$('#id_empleado').val(),

                    id_nro_acuerdo:$('#id_nro_acuerdo_ct').val(),
                    id_fecha_aprobacion:$('#id_fecha_aprobacion_ct').val(),
                    id_monto:$('#id_monto_ct').val(),
                    id_tipo_contrato:$('#id_tipo_contrato_ct').val(),
                    id_nro_contrato:$('#id_nro_contrato_ct').val(),
                    id_fecha_contrato:$('#id_fecha_contrato_ct').val(),
                    id_fecha_inicio:$('#id_fecha_inicio_ct').val(),
                    id_fecha_fin:$('#id_fecha_fin_ct').val(),

                    observaciones:$('#id_detalle_asignacio_p_ct').val(),

                    id_categoria:$('#id_categoria_ct').val(),

                    id_secretaria_f:$('#idSecretariaF').val(),
                    id_subsecretaria_f:$('#idSubSecretariaF').val(),
                    id_direccion_f:$('#idDireccionF').val(),
                    id_subdireccion_f:$('#idSubDireccionF').val(),
                    id_departamento_f:$('#idDepartamentoF').val(),
                    id_seccion_f:$('#idSeccionF').val(),
                    id_puesto_f:$('#idPuestoF').val(),
                    id_nivel_f:$('#idNivelF').val()


                  }, //f de fecha y u de estado.
                  beforeSend:function(){

                  },
                  success:function(data){
                    //alert(data);
                    eventBus.$emit('recargarPuesto', 1);
                    eventBus.$emit('recargarAsignacionDetalle', 1);
                    //viewModelDatosEmp.table_contratos();
                    $('#id_cambio').val(1);
                    eventBus.$emit('showDetalleContratos', 5);
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
      },
      recargaListadoEmpleados: function(){
        //recargarEmpleados()
      },
      valida_ubicacion(id_asignacion,reng_num,opc){
        if(opc == 14){

          this.opcion = opc;
          this.id_asignacion_u = id_asignacion;
          this.id_reng_num_u = reng_num;
        }else{
          var title='¿Desea validar esta ubicación?', msg='¡Si validar!', color='#28a745';
          if(opc==3){
            title='¿Desea anular esta ubicación?', msg='¡Si anular!', color='#d33';
          }
          //inicio
          Swal.fire({
            title: '<strong>'+title+'</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonText: 'Cancelar',
            confirmButtonText: msg
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/puestos/validar_ubicacion.php",
                data: {
                  id_asignacion,
                  reng_num,
                  opc
                }, //f de fecha y u de estado.
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  Swal.fire({
                    type: 'success',
                    title: 'Ubicación actualizada',
                    showConfirmButton: false,
                    timer: 1100
                  });
                  $('#id_cambio').val(1);
                  eventBus.$emit('showDetalleUbicaciones', 5);
                  //alert(data);
                }
              }).done( function() {


              }).fail( function( jqXHR, textSttus, errorThrown){

                alert(errorThrown);

              });

            }

          })
          //fin
        }

      },
      getContratoById: function(reng_num,opc){
        this.id_reng_num = reng_num;
        this.opcion = opc;
      },
      get_empleado_plaza: function(id_plaza,id_asignacion,opc){
        this.id_asignacion_plaza=id_asignacion;
        this.opcion=opc;
        axios.get('empleados/php/back/plazas/get_plaza_by_id.php', {
          params: {
            id_plaza: id_plaza
          }
        }).then(function (response) {
          viewModelDatosEmp.emp_plaza = response.data;
          setTimeout(() => {
            $("#id_puesto_f").select2({
            });

          });
          //alert(response.data.id_plaza)
        }).catch(function (error) {
          console.log(error);
        });
      },
      setPages () {
        let numberOfPages = Math.ceil(viewModelDatosEmp.contratos.length / this.perPage);
        for (let index = 1; index <= numberOfPages; index++) {
          this.pages.push(index);
        }
      },
      paginate (posts) {
  			let page = this.page;
  			let perPage = this.perPage;
  			let from = (page * perPage) - perPage;
  			let to = (page * perPage);
  			return  posts.slice(from, to);
  		},
      getOpcionFinContrato: function(reng_num, opc){
        this.opcion=opc;
        this.reng_num=reng_num;
      },
      finalizarContrato: function(){
        jQuery('.js-validation-finalizacion-contrato').validate({
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
            Swal.fire({
              title: '<strong>¿Desea establecer en trámite de solvencia al empleado?</strong>',
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
                  url: "empleados/php/back/contratos/finalizar_contrato.php",
                  data: {
                    id_persona:$('#id_gafete').val(),
                    reng_num:$('#reng_num').val(),
                    fecha_acuerdo:$('#id_fecha_acuerdo_fc').val(),
                    fecha_finalizacion:$('#id_fecha_finalizacion_fc').val(),
                    nro_acuerdo:$('#id_nro_acuerdo_fc').val(),
                    observaciones:$('#id_detalle_remocion_fc').val(),
                    tipo_baja:$('#id_tipo_fc').val(),
                    id_tipo_renglon:$('#id_tipo_renglon').val()
                  }, //f de fecha y u de estado.
                  beforeSend:function(){
                    //$('#response').html('<span class="text-info">Loading response...</span>');
                    //alert('message_before')
                  },
                  success:function(data){
                    eventBus.$emit('recargarPuesto', 1);
                    eventBus.$emit('recargarAsignacionDetalle', 1);
                    //viewModelDatosEmp.get_plaza_empleado();
                    //viewModelDatosEmp.get_empleado();
                    $('#id_cambio').val(1);
                    eventBus.$emit('showDetalleContratos', 5);
                    Swal.fire({
                      type: 'success',
                      title: 'Contrato finalizado',
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
            //fin


          },
          rules: {
            detalle_remocion:{ required: true},
            combo1:{ required: true}
           }

        });
      },
      get_items_finalizacion_contrato: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_items.php', {
            params: {
              id_catalogo:29,
              tipo:3
            }
          })
          .then(function (response) {
            viewModelDatosEmp.tipos_baja_contrato = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      retardo: function(){
        setTimeout(() => {
          this.get_items();
          this.get_status_baja();
          this.table_plazas();
          this.table_contratos();
          this.get_plazas_disponibles();
          this.get_ubicaciones_funcionales();
          this.get_items_finalizacion_contrato();

        }, 500);

      },
      editableDatos: function(){
        setTimeout(()=>{
          //alert('aldjflkasd');
          $('.fechaP').editable({
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){

              }
            }
          },300);
        })
      },
      getCarnets: function(){

        this.tableCarnets = $('#tb_carnets').DataTable({
          "ordering": false,
          "pageLength": 10,
          "bProcessing": true,


          language: {
              emptyTable: "No hay carnets generados",
              loadingRecords: " <div class='spinner-grow text-info'></div> "
          },
          "ajax":{
                url :"empleados/php/back/listados/get_carnets_by_empleado.php",
                type: "POST",
                data:{
                  id_empleado:function() { return $('#id_empleado').val() }
                },
                error: function(){
                  $("#post_list_processing").css("display","none");
                }
            },

               "aoColumns": [
                 //{ "class" : "text-center", mData: 'plaza' },
                 { "class" : "text-center", mData: 'id_gafete' },
                 { "class" : "text-center", mData: 'id_empleado' },
                 { "class" : "text-center", mData: 'id_contrato' },
                 { "class" : "text-center", mData: 'id_version' },
                 { "class" : "text-center", mData: 'puesto' },
                 { "class" : "text-center", mData: 'fecha_generado' },
                 { "class" : "text-right", mData: 'fecha_vencimiento' },
                 { "class" : "text-center", mData: 'fecha_validado' },
                 { "class" : "text-center", mData: 'accion' }
               ],
               buttons: [

                 {
                   text: 'Generar carnet <i class="fa fa-plus"></i>',
                   className: 'btn btn-sm btn-soft-info',
                   action: function ( e, dt, node, config ) {
                     //cargar_asignar_plaza_empleado();
                     viewModelDatosEmp.getOpcion(12);
                   }
                 },
                 {
                   text: 'Recargar <i class="fa fa-sync"></i>',
                   className: 'btn btn-sm btn-soft-info',
                   action: function ( e, dt, node, config ) {
                     viewModelDatosEmp.recargarCarnets();
                   }
                 }
               ]
        });
      },
      recargarCarnets: function(){
        this.tableCarnets.ajax.reload(null, false);
      },
      getTiposCarnets: function(){
        //setTimeout(() => {
          //console.log($('#id_subsecretaria_f').val());
          axios.get('empleados/php/back/listados/get_items.php', {
            params: {
              id_catalogo:150,
              tipo:0
            }
          })
          .then(function (response) {
            viewModelDatosEmp.tipoCarnets = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
        //}, 200);
      },
      generarCarnet: function(){
        jQuery('.jsValidationCarnetNuevo').validate({
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
              title: '<strong>¿Desea generar carnet al empleado?</strong>',
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
                  dataType: 'json',
                  url: "empleados/php/back/carnets/generar_carnet.php",
                  data: {
                    id_plaza:$('#cod_plaza').val(),
                    id_persona:$('#id_gafete').val(),
                    id_empleado:$('#id_empleado').val(),
                    id_tipo_carnet:$('#id_tipo_carnet').val(),
                    id_posee_arma:$('#id_posee_arma').val()
                  }, //f de fecha y u de estado.
                  beforeSend:function(){
                    //$('#response').html('<span class="text-info">Loading response...</span>');
                    //alert('message_before')
                  },
                  success:function(data){

                    if(data.msg == 'OK'){
                      Swal.fire({
                        type: 'success',
                        title: 'Carnet generado',
                        showConfirmButton: false,
                        timer: 1100
                      });
                      viewModelDatosEmp.recargarCarnets();
                      viewModelDatosEmp.getOpcion(11);
                    }else{
                      Swal.fire({
                        type: 'error',
                        title: 'ERROR',
                        text: data.msg,
                        showConfirmButton: false,
                        timer: 1500
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
      },
      setTipoEstado: function(event){
        this.tipoEstado = event.currentTarget.value;
      },
      changeItem (event) {
        console.log('onChange')
        console.log(event.target.value)
      },
      setEstadoProceso: function(opc){
        this.tipoEstado = opc;
      },
      mostrarDocumento: function(){
        this.pdf = "empleados/php/front/contratos/files/8P2LRW1.pdf";
        /*axios.get('empleados/php/front/contratos/files/get_docto_actual', {
          params: {
            ped_tra:this.ped_tra
          }
        }).then(function (response) {
          this.doctoActual = response.data;
          setTimeout(() => {
            $("#targetDiv").attr("src", 'documentos/php/front/pedidos/files/'+this.doctoActual.archivo);
            $("#targetDiv").show();
          }, 600);
        }.bind(this)).catch(function (error) {
          console.log(error);
        });*/
      },
      //fin metodos

    }

  })

  viewModelDatosEmp.$mount('#emp_datos_app');
  instanciaEmpD = viewModelDatosEmp;

  instanciaEmpD.getCarnets();


//})
