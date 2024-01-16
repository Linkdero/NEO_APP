var viewModelPlazaNueva = new Vue({
  //el: '#archivo_app',
  data: {
    pdf:'',
    key:0,
    arrayPuestos:[],
    arraySueldos:[],
    idPlaza:$('#id_plaza').val(),
    opcion:$('#opcion').val(),
    plazaDetalle:"",
    idPuestoN:""
  },
  destroyed: function(){
    this.viewModelPlazaNueva;
  },
  created: function(){
    this.getPuestos();
    this.getSueldos();
    this.getPlazaById();
  },
  components: {

  },
  methods: {
    getPuestos: function(){
      var thisInstance = this;
      axios.get('empleados/php/back/listados/get_puestos_plaza.php')
      .then(function (response) {
        thisInstance.arrayPuestos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getSueldos: function(){
      if(this.opcion == 1){
        var thisInstance = this;
        axios.get('empleados/php/back/listados/get_sueldos_para_plaza.php')
        .then(function (response) {
          thisInstance.arraySueldos = response.data;

          console.log(response.data);
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    },
    savePlaza: function(tipo){
      var thisInstance = this;
      var message = (tipo == 1) ? 'crear' : 'actualizar';
      var respuesta = (tipo == 1) ? 'creada' : 'actualizada';
      jQuery('.jsValidacionPlazaNueva').validate({
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
          var form = $('#formValidacionPlazaNueva');
          Swal.fire({
            title: '<strong>¿Desea '+message+' la plaza?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, '+message+'!'
          }).then((result) => {
            if (result.value) {
              //alert(vt_nombramiento);
              $.ajax({
                type: "POST",
                url: "empleados/php/back/plazas/agregar_plaza.php",
                dataType: "json",
                data: {
                  tipo:tipo,
                  id_plaza:$('#id_plaza').val(),
                  id_cod_plaza:$('#id_cod_plaza').val(),
                  id_partida:$('#id_partida').val(),
                  arraySueldos:thisInstance.arraySueldos,
                  descripcionPlaza:$('#descripcionPlaza').val(),
                  id_puesto_n:$('#id_puesto_n').val(),
                  idNivelNo:$('#idNivelNo').val(),
                  idSecretariaNo:$('#idSecretariaNo').val(),
                  idSubSecretariaNo:$('#idSubSecretariaNo').val(),
                  idDireccionNo:$('#idDireccionNo').val(),
                  idSubDireccionNo:$('#idSubDireccionNo').val(),
                  idDepartamentoNo:$('#idDepartamentoNo').val(),
                  idSeccionNo:$('#idSeccionNo').val(),
                  idSecretariaF:$('#idSecretariaF').val(),
                  idSubSecretariaF:$('#idSubSecretariaF').val(),
                  idDireccionF:$('#idDireccionF').val(),
                  idSubDireccionF:$('#idSubDireccionF').val(),
                  idDepartamentoF:$('#idDepartamentoF').val(),
                  idSeccionF:$('#idSeccionF').val(),
                  idPuestoF:$('#idPuestoF').val(),
                  idNivelF:$('#idNivelF').val(),

                },
                beforeSend:function(){
                  //$('#response').html('<span class="text-info">Loading response...</span>');
                  //alert('message_before')
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    //inicio
                    $('#id_cambio').val(1);
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    //viewModelDatosEmp.get_empleado();
                    eventBus.$emit('recargarPuesto', 1);
                    eventBus.$emit('recargarAsignacionDetalle', 1);
                    eventBus.$emit('showDetalleUbicaciones', 5);
                    //fin
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
          id_cod_plaza: {
            remote: {
              url: 'empleados/php/back/plazas/validar_cod_plaza.php',
              data: {
                id_cod_plaza: function(){ return $('#id_cod_plaza').val();},
                tipo:thisInstance.opcion
              }
            }
          }
        },
        messages: {
          id_cod_plaza: {
            remote: "Este código de plaza ya existe."
          }
        }
      });
    },
    clearClass: function(idx,bln){
      if(bln == false){
        $('#txtMontoS'+idx).val('');
        $('.txtMontoS'+idx).removeClass('has-error');
        $('#txtMontoS'+idx+'-error').hide();
      }

    },
    getPlazaById: function(){
      //inicio
      if(this.opcion == 2){
        var thisInstance = this;
        axios.get('empleados/php/back/plazas/get_plaza_para_editar.php', {
          params: {
            id_plaza:thisInstance.idPlaza
          }
        })
        .then(function (response) {
          thisInstance.plazaDetalle = response.data;
          thisInstance.arraySueldos = response.data.sueldos;
          thisInstance.idPuestoN = response.data.puesto_n

        })
        .catch(function (error) {
          console.log(error);
        });
      }

      //fin
    }
  }

})

viewModelPlazaNueva.$mount('#plaza_nueva_app');
