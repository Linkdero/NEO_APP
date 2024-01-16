
var viewModelEmpNuevo = new Vue ({
  el: '#emp_nuevo',
  data:{
    departamentos:"",
    municipios:"",
    aldeas:"",
    tipoPersonas:"",
    tipoServicio:"",
    estadoCivil:"",
    profesiones:"",
    genero:"",
    procedencia:"",
    tipoCurso:"",
    promociones:"",
    religion:"",
    opcion:1,
    catalogo:""

  },
  created: function(){
    this.get_departamentos(),
    this.getTipoPersona(),
    this.getTipoServicio(),
    this.getEstadoCivil(),
    this.getProfesiones(),
    this.getGenero(),
    this.getProcedencia(),
    this.getTipoCurso(),
    this.getPromociones(),
    this.getTipoReligion()
  },
  methods: {
    getOpcion: function(opc){
      this.opcion = opc;
      if(opc == 2){
        this.getCatalogo();
      }else if(opc == 1){
        $("#profesion").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });

        $("#tipo_curso").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      }
    },
    crearEmpleado: function(){

      jQuery('.jsValidationEmpleadoNuevo').validate({
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
            title: '<strong>¿Desea crear a este empleado?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Crear!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              var form = $('#nuevoEmpleadoForm');
              $.ajax({
                type: "POST",
                url: "empleados/php/back/empleado/crear_empleado.php",
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
                      title: 'Empleado creado',
                      text: 'Favor verificar en opción de Aspirantes en Proceso',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    // abrir modal de empleado creado
                    $('#modal-remoto-lg').modal('hide');
                    setTimeout(() => {
                      let imgModal = $('#modal-remoto-lgg2');
                      let imgModalBody = imgModal.find('.modal-content');
                      //let id_persona = parseInt($('#bar_code').val());
                      $.ajax({
                        type: "GET",
                        url: "empleados/php/front/empleados/empleado.php",
                        data:{ id_persona: data.id},
                        dataType: 'html',
                        beforeSend: function () {
                          imgModal.modal('show');
                          imgModalBody.html("<br><br><div class='loaderr'></div><br><br>");
                        },
                        success: function (data) {
                          imgModalBody.html(data);
                        }
                      });
                    },500);
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
          cui: {
            remote: {
              url: 'empleados/php/back/persona/action/validar_cui.php',
              data: {
                cui: function(){ return $('#cui').val();}
              }
            }
          }
        },
        messages: {
          cui: {
            remote: "Esta persona ya existe."
          }
        }

      });
    },
    crearItem: function(){
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
            title: '<strong>¿Desea crear a este empleado?</strong>',
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
                      title: 'Empleado creado',
                      showConfirmButton: false,
                      timer: 1100
                    });
                    this.getProfesiones();
                    this.getProcedencia();
                    this.getTipoCurso();
                    this.getPromociones();
                    this.getTipoReligion();
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
    },
    get_departamentos: function(){
      //
      axios.get('viaticos/php/back/listados/destinos/get_departamento.php',{
        params:{
          pais:'GT'
        }
      })
      .then(function (response) {
          viewModelEmpNuevo.departamentos = response.data;

      })
      .catch(function (error) {
          console.log(error);
      });
    },
    get_municipios: function(event){
      valor=event.currentTarget.value;
      axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
        params:{
          departamento:valor
        }
      })
      .then(function (response) {
        viewModelEmpNuevo.municipios = response.data;
      })
      .catch(function (error) {
          console.log(error);
      });
    },
    getTipoPersona: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:51,
          tipo:0
        }
      })
      .then(function (response) {
        viewModelEmpNuevo.tipoPersonas = response.data;
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
        viewModelEmpNuevo.tipoServicio = response.data;
      })
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
        viewModelEmpNuevo.estadoCivil = response.data;
      })
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
        viewModelEmpNuevo.profesiones = response.data;
        $("#profesion").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      })
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
        viewModelEmpNuevo.genero = response.data;
        $("#profesion").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      })
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
        viewModelEmpNuevo.procedencia = response.data;
        $("#profesion").select2({
          placeholder: "Seleccionar profesión",
          allowClear: true
        });
      })
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
        viewModelEmpNuevo.tipoCurso = response.data;
        $("#tipo_curso").select2({
          placeholder: "Seleccionar el tipo",
          allowClear: true
        });
      })
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
        viewModelEmpNuevo.promociones = response.data;
      })
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
        viewModelEmpNuevo.religion = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getCatalogo: function(){
      axios.get('empleados/php/back/listados/get_catalogo.php', {
        params: {
          tipo:1
        }
      })
      .then(function (response) {
        viewModelEmpNuevo.catalogo = response.data;
        $("#id_catalogo").select2({
          placeholder: "Seleccionar catalogo",
          allowClear: true
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    }
  }
})
