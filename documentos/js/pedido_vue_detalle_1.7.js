var eventBusPD = new Vue();
// seguimiento
//$(document).ready(function(){
var instanciaPD;

  // seguimiento
    var viewModelBitacora = new Vue({
      el:'#app_bitacora',
      data:{
        bitacora:"",
        cch1:0
      },
      methods:{
        getBitacoraPorPedido: function(ped_tra){
          axios.get('documentos/php/back/listados/get_bitacora_por_pedido', {
            params: {
              ped_tra:ped_tra
            }
          }).then(function (response) {
            viewModelBitacora.bitacora = response.data;


          }).catch(function (error) {
            console.log(error);
          });
        }
      }
    })



//pedido detalle
  viewModelPedidoDetalle = new Vue({
      //el: '#app_pedido_detalle',
      data: {
        ped_tra:$('#id_pedido').val(),
        id_verificacion:$('#id_verificacion').val(),
        pedido:"",
        insumos:"",
        seguimiento_list:"",
        opcion:1,
        estado:"",
        empleados:"",
        cch:[],
        cch1:0,
        contador:0,
        verificacion:0,
        bitacora:"",
        accesos:"",
        opc_ini:0,
        cambio:0,
        persona:"",
        sopcion:0,
        facturas:"",
        privilegio:""
      },
      mounted(){
        instanciaPD = this;
      },

      computed: {
        itemsWithSubTotal() {
          return this.insumos.map(item => ({
            item,
            subtotal: this.computeSubTotal(item)
          }))
        }
      },
      created: function(){
        this.get_pedido_detalle();
        this.getPrivilegio();
        this.validar_estado_pedido();
        this.recargarOpcion(0);
        this.get_bitacora();
        this.getPersonaAsginada();
      },

      methods: {
        getPedidoD: function(data) {
    			//console.log('Data from component Detalle Pedido', data);
          viewModelPedidoDetalle.pedido = data;
    		},
        get_pedido_detalle: function(){
          eventBusPD.$emit('recargarPedido', 1);
          axios.get('documentos/php/back/listados/get_facturas_by_pedido', {
            params: {
              ped_tra:this.ped_tra
            }
          }).then(function (response) {
            viewModelPedidoDetalle.facturas = response.data;

          }).catch(function (error) {
            console.log(error);
          });
        },
        getsOpcion: function(opc){
          this.sopcion = opc;
        },
        getOpcion: function(opc){
          this.opcion=opc;

          if(this.opcion==2){
            if(this.opc_ini == 0){
              viewModelPedidoDetalle.recargarOpcion(2);
            }

          }
          if(this.opcion == 4){
            if(this.sopcion == 0){
              this.sopcion = 1;
            }

          }else{
            this.sopcion =1;
          }
        },
        validar_estado_pedido: function(){
          axios.get('documentos/php/back/pedido/get_estado_pedido', {
            params: {
              ped_tra:this.ped_tra
            }
          }).then(function (response) {
            viewModelPedidoDetalle.estado = response.data;
            this.verificacion = response.data.verificacion;
            eventBusPD.$emit('recargarPedido', 1);
            //alert(this.verificacion);
          }).catch(function (error) {
            console.log(error);
          });
        },
        get_bitacora: function(){
          viewModelBitacora.getBitacoraPorPedido(this.ped_tra);
          setTimeout(() => {
            viewModelPedidoDetalle.bitacora=viewModelBitacora.bitacora;
          }, 1000);
        },

        asignar_estado_pedido: function(estado_id, tipo){
          //if(estado_id==8156 || estado_id == 8140 || estado_id == 8141 || estado_id == 8160){
            //inicion
            var titulo ='';
            var color = "28a745";
            var result = '';
            var boton = '';
            if(estado_id == 8156 || estado_id == 8160 || estado_id == 8164){
              titulo = '¿Desea recibir el pedido?';
              boton = '¡Si, recibir!';
              result = 'Pedido reibido';
            }else
            if(estado_id == 8140 || estado_id == 8143 || estado_id == 8146){
              titulo = '¿Desea aprobar el pedido?';
              boton = '¡Si, aprobar!';
              result = 'Pedido aprobado';
            }else
            if(estado_id == 8144 || estado_id==8147 || estado_id == 8170){
              titulo = '¿Desea anular el pedido?';
              boton = '¡Si, anular!';
              color = 'd33';
              result = 'Pedido anulado';
            }else if(estado_id == 8141){
              titulo = '¿Desea rechazar el pedido?';
              boton = '¡Si, rechazar!';
              color = 'd33';
              result = 'Pedido rechazado';
            }
            else if(estado_id == 8145){
              titulo = '¿Desea revisar y asignar el pedido?';
              boton = '¡Si, asignar!';

              result = 'Pedido asignado y Cotizando';
            }
            else if(estado_id == 8148){
              titulo = '¿Desea cotizar el pedido?';
              boton = '¡Si, cotizar!';

              result = 'Cotizando';
            }
            else if(estado_id == 8149){
              titulo = '¿Desea comprar el pedido?';
              boton = '¡Si, comprar!';

              result = 'Pedido comprado';
            }else if(estado_id == 9108){
              titulo = '¿Desea rechazar el pedido para corrección?';
              boton = '¡Si, rechazar!';
              color = 'd33';
              result = 'Pedido rechazado';
            }
            else if(estado_id == 9109){
              titulo = '¿Desea solicitar al SAA rechazar el pedido para corrección?';
              boton = '¡Si, solicitar!';
              color = 'd33';
              result = 'Token de solicitud generado';
            }
            if(estado_id == 8157 || estado_id == 8161){
              titulo = '¿Desea devolver el pedido?';
              boton = '¡Si, devolver!';
              result = 'Pedido devuelto';
            }
            if(tipo == 1){
              Swal.fire({
                title: '<strong>'+titulo+'</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#'+color,
                cancelButtonText: 'Cancelar',
                confirmButtonText: boton
              }).then((result) => {
                if (result.value) {
                  //alert(vt_nombramiento);
                  $.ajax({
                    type: "POST",
                    url: "documentos/php/back/pedido/establecer_estado.php",
                    dataType: 'json',
                    data: {
                      ped_tra:$('#id_pedido').val(),
                      estado_id:estado_id,
                      id_persona:$('#id_empleados_list').val(),
                      motivo:''
                    }, //f de fecha y u de estado.

                    beforeSend:function(){
                      this.verificacion = 0;
                      //$('#id_empleados_list').remove();
                      $('.selection').remove();
                      viewModelPedidoDetalle.cambio = 1;
                    },
                    success:function(data){
                      if(data.id == 1){
                        sendMail(data.mail.emisor, data.mail.receptor, data.mail.body, data.mail.subject)
                      }
                      viewModelPedidoDetalle.recargarOpcion(1)
                    }
                  }).done( function() {

                  }).fail( function( jqXHR, textSttus, errorThrown){
                    alert(errorThrown);

                  });
                }
              })
            }else if(tipo ==2){
              motivo = '';
              Swal.fire({
                title: '<strong>'+titulo+'</strong>',
                text: "Este proceso no se puede revertir",
                type: 'question',
                input: 'textarea',
                inputPlaceholder: 'Especifique el motivo',
                showCancelButton: true,
                confirmButtonColor: '#'+color,
                cancelButtonText: 'Cancelar',
                confirmButtonText: boton,
                inputValidator: function(inputValue) {
                return new Promise(function(resolve, reject) {
                  if (inputValue && inputValue.length > 0) {
                    resolve();
                    motivo=inputValue;
                  } else {
                    Swal.fire({
                      type: 'error',
                      title: 'Debe especificar el motivo de la cancelación',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                });
              }
                }).then((result) => {
                if (result.value) {
                  $.ajax({
                    type: "POST",
                    url: "documentos/php/back/pedido/establecer_estado.php",
                    dataType: 'json',
                    data: {
                      ped_tra:$('#id_pedido').val(),
                      estado_id:estado_id,
                      id_persona:$('#id_empleados_list').val(),
                      motivo:motivo
                    }, //f de fecha y u de estado.

                    beforeSend:function(){
                      this.verificacion = 0;
                      viewModelPedidoDetalle.cambio = 1;
                      $('.selection'). remove();

                    },
                    success:function(data){
                      if(data.id == 1){
                        sendMail(data.mail.emisor, data.mail.receptor, data.mail.body, data.mail.subject)
                      }

                      viewModelPedidoDetalle.recargarOpcion(1);

                    }
                  }).done( function() {

                  }).fail( function( jqXHR, textSttus, errorThrown){
                    alert(errorThrown);

                  });
                }
              })
            }

            //fin
          //}

        },
        recargarOpcion(tipo){

          if(tipo == 1){
            Swal.fire({
              title: 'Actualizando información!',
              html: "<div class='spinner-grow text-info'></div>",
              timer: 1000,
              timerProgressBar: true,
              showConfirmButton: false,
              didOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                  const content = Swal.getHtmlContainer()
                  if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                      b.textContent = Swal.getTimerLeft()
                    }
                  }
                }, 100)
              },
              willClose: () => {
                clearInterval(timerInterval)
              }
            }).then((result) => {
              /* Read more about handling dismissals below */
              if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
              }
            })
          }

          if(tipo==2 && this.opc_ini == 0 || tipo==3 && this.opc_ini == 0){
            this.opc_ini =1;
          }

          if(tipo==2 && this.opc_ini==1 || tipo==1  && this.opc_ini==1){
            setTimeout(() => {
              viewModelPedidoDetalle.validar_estado_pedido();
              viewModelPedidoDetalle.getPersonaAsginada();
              viewModelPedidoDetalle.getOpcion(2);
              viewModelPedidoDetalle.get_bitacora();
              //viewModelPedidoDetalle.seguimiento_listado();
              eventBusPD.$emit('recargarSeguimientoList', 1);
              eventBusPD.$emit('recargarEmpleadosList', 1);
              eventBusPD.$emit('recargarPorcentaje', 1);
            }, 200);

          }

        },
        establecerPedidoVerificacion: function(estado_ve, event){
          //alert(estado_ve+ ' |-| '+ event.currentTarget.value)
          var id = event.currentTarget.value;
          var chequeado = ( $('#'+id).is(':checked') )?1:0;
          if( $('#'+id).is(':checked') ){
            this.cch1 += 1;
          }else{
            this.cch1 -= 1;
          }

          if(estado_ve==8139 || estado_ve == 8142){
            //inicion
            $.ajax({
              type: "POST",
              url: "documentos/php/back/pedido/establecer_estado_verificacion.php",
              data: {
                ped_tra:$('#id_pedido').val(),
                estado_id:estado_ve,
                tipo_verificacion:id,
                chequeado:chequeado
                //id_persona:$('#id_empleados_list').val()
              }, //f de fecha y u de estado.

              beforeSend:function(){
              },
              success:function(data){
                viewModelPedidoDetalle.cambio = 1;
                viewModelPedidoDetalle.validar_estado_pedido();
                viewModelPedidoDetalle.get_bitacora();
              }
            }).done( function() {

            }).fail( function( jqXHR, textSttus, errorThrown){
              alert(errorThrown);

            });
            //fin
          }

        },
        enviar_correo: function(){
          $.ajax({
            type: "POST",
            url: "documentos/php/back/pedido/enviar_correo.php",
            dataType: 'json',
            data: {
              destinatarios:$('#destinatarios').val(),
              ped_tra:this.ped_tra
            }, //f de fecha y u de estado.

            beforeSend:function(){
            },
            success:function(data){
              if(data.msg == 'OK'){
                Swal.fire({
                  type: 'success',
                  title: 'Correo enviado',
                  showConfirmButton: false,
                  timer: 1100
                });
              }else{
                Swal.fire({
                  type: data.msg,
                  title: data.id,
                  showConfirmButton: false,
                  timer: 1100
                });
              }
              console.log(data);
            }
          }).done( function() {

          }).fail( function( jqXHR, textSttus, errorThrown){
            alert(errorThrown);

          });
        },
        getPersonaAsginada: function(ped_tra){
          axios.get('documentos/php/back/pedido/get_persona_asignada', {
            params: {
              ped_tra:this.ped_tra
            }
          }).then(function (response) {
            viewModelPedidoDetalle.persona = response.data;
            //this.verificacion = response.data.verificacion;
            //alert(this.verificacion);
          }).catch(function (error) {
            console.log(error);
          });
        },
        getPrivilegio: function(){
          axios.get('documentos/php/back/functions/evaluar_privilegio', {
          }).then(function (response) {
            this.privilegio  = response.data;
            console.log(this.privilegio);
          }.bind(this)).catch(function (error) {
            console.log(error);
          });
        },

      }
    })

    viewModelPedidoDetalle.$mount('#app_pedido_detalle');

//instanciaPD = viewModelPedidoDetalle;

//instanciaPD.proveedorFiltrado();

//})
