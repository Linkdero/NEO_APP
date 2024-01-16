var eventBusOrdenO = new Vue();
var eventBus = new Vue();
var viewModelOrdenCompraDetalle = new Vue({

  //el:'#app_factura',
  data:{
    arreglo:$('#arreglo').val(),
    //id_pago:$('#id_pago').val(),
    titulo:'',
    label:'',
    tag:'',
    privilegio:JSON.parse($('#privilegio').val()),
    estado:$('#estado').val(),
    ordenDetalle:"",
    facturas:"",
    visible:false,
    type:$('#type').val()
  },
  created: function(){
    //this.getOrdenDetalleById();
  },
  components: {
    //insumosc
  },
  methods:{
    getOrdenDetalleById: function(){
      axios.get('documentos/php/back/orden/get_orden_detalle', {
        params: {
          id_pago:this.id_pago
        }
      }).then(function (response) {
        this.ordenDetalle = response.data;
        setTimeout(() => {
          this.facturas = response.data.facturas;
          this.visible = response.data.visible;
        }, 900);

      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    setEstado(id_bitacora,id_seguimiento,mensaje,boton,respuesta){
      //inicio
      var thisInstance = this;
      var html='';
      var observaciones = '';
      if(id_bitacora == 3 && id_seguimiento == 0){
        html += '<textarea id="swal-input1" rows="5" class="form-control"></textarea>';
      }

      const el1 = document.getElementById('id_empleados_list');
      var validation = false;
      if (el1 !== null) {
        // 👇️ this runs
        if($('#id_empleados_list').val() == ''){
          validation = true;
        }else{
          validation = false;
        }
      } else {
        validation = false;
      }

      if(validation == true){
        Swal.fire({
          type: 'error',
          title: 'Debe seleccionar a un empleado.',
          showConfirmButton: true,
          //timer: 1100
        });
      }else{
        //inicio
        Swal.fire({
          title: '<strong>'+mensaje+'</strong>',
          text: "",
          type: 'question',
          html:html,
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: boton
        }).then((result) => {
          if (result.value) {
            //alert(vt_nombramiento);
            observaciones = (id_bitacora == 3 && id_seguimiento == 0) ? document.getElementById('swal-input1').value : '';

            $.ajax({
              type: "POST",
              url: "documentos/php/back/orden/action/seguimiento_orden.php",
              dataType: 'json',
              data: {
                arreglo:thisInstance.arreglo,
                id_bitacora:id_bitacora,
                id_seguimiento:id_seguimiento,
                respuesta:respuesta,
                observaciones:observaciones,
                id_empleados_list:$('#id_empleados_list').val()
              },
              beforeSend: function () {
              },
              success: function (data) {
                //exportHTML(data.id);
                //recargarDocumentos();
                if (data.msg == 'OK') {
                  $('#modal-remoto').modal('hide');
                  instanciaF.recargarOrdenes();
                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });
                  //impresion_pedido(data.id);
                } else {
                  Swal.fire({
                    type: 'error',
                    title: 'Error',
                    showConfirmButton: false,
                    timer: 1100
                  });
                }
              }
            }).done(function () {
            }).fail(function (jqXHR, textSttus, errorThrown) {
              alert(errorThrown);
            });
          }
        })
        //fin
      }

      //fin
    }
  }
})

viewModelOrdenCompraDetalle.$mount('#appOrdenDetalle');
