<template>
  <div class="row">

    <div class="col-sm-12" v-if="tipo == 'solicitud' || tipo == 'asignacion'">
      <div class="el-wrapper" v-for="(v, index) in vehiculos">
        <span class="u-icon u-icon--sm bg-secondary text-white rounded-circle mr-3 " style="position:absolute;margin-top:-2.5rem; margin-left:-1.5rem">{{ index + 1}}</span>
        <div class="box-up" style="height:auto">
          <div class="row">
            <div class="text-left"  v-bind:class="[tipo == 'asignacion' ? 'col-sm-3' : 'col-sm-4']">
              <dato-persona icono="fa fa-car-side" texto="Vehículo:" :dato="v.marca + ' ' + v.linea + ' ' + v.modelo" tipo="1"></dato-persona>
              <dato-persona icono="fa-brands fa-cc-jcb" texto="Placa:" :dato="v.nro_placa" tipo="1"></dato-persona>
            </div>
            <div class="text-left"  v-bind:class="[tipo == 'asignacion' ? 'col-sm-3' : 'col-sm-4']">
              <dato-persona icono="fa-regular fa-user" texto="Conductor:" :dato="v.conductor" tipo="1"></dato-persona>
              <dato-persona icono="fa-regular fa-route" texto="Tipo de asignación:" :dato="v.tipo_asignacion" tipo="1"></dato-persona>
            </div>
            <div class="text-left"  v-bind:class="[tipo == 'asignacion' ? 'col-sm-3' : 'col-sm-4']">
              <dato-persona icono="fa-regular fa-calendar-clock" texto="Fecha salida:" :dato="v.fecha_salida" tipo="1"></dato-persona>
              <dato-persona icono="fa-regular fa-calendar-clock" texto="Hora regreso:" :dato="v.fecha_regreso" tipo="1"></dato-persona>
            </div>

            <div class="text-left"  v-bind:class="[tipo == 'asignacion' ? 'col-sm-3' : 'col-sm-4']" v-if="tipo == 'asignacion'">
              <dato-persona  v-if="tipo == 'asignacion'" icono="fa-regular fa-gauge-min" texto="Kilometraje inicial:" :dato="v.Kilometraje_ini" tipo="1"></dato-persona>
              <dato-persona  v-if="tipo == 'asignacion'" icono="fa-regular fa-gauge-max" texto="Kilometraje final:" :dato="v.Kilometraje_fin" tipo="1"></dato-persona>
            </div>

          </div>
        </div>
        <div class="box-down" style="height:auto">
          <hr>
          <div class="row">
            <div class="col-sm-4">

              <h3 v-html="v.correlativo_a"></h3>
            </div>
            <div class="col-sm-2">
                <small>Estado:</small>
              <h3 :class="v.estado_asignacion"></h3>
            </div>
            <div class="col-sm-2">
              <small>Seguimiento:</small>
              <h3 v-if="v.asignacion_status == 1" :class="v.estado_seguimiento"></h3>
            </div>
            <div class="col-sm-4 text-right">
              <div class="btn-group">
                <span class="btn btn-sm btn-soft-info" @click="getVehiculoById(v.arreglo)"><i class="fa fa-pencil-alt"></i></span>
                <span v-if="v.seguimiento_id == 0 && v.asignacion_status == 1" class="btn btn-sm btn-soft-info" @click="setStatusVehiculo(v.solicitud_id,v.asignacion_id,v.vehiculo_id,v.reng_num,1,'¿El vehículo saldrá de comisión?','Vehículo en comisión.')"><i class="fa fa-arrow-right"></i></span>
                <span v-if="v.seguimiento_id == 0 && v.asignacion_status == 1" class="btn btn-sm btn-soft-danger" @click="setStatusVehiculo(v.solicitud_id,v.asignacion_id,v.vehiculo_id,v.reng_num,3,'¿Desea eliminar este vehículo de esta comisión?','Vehículo eliminado de la comisión.')"><i class="fa fa-times-circle"></i></span>
                <span v-if="v.seguimiento_id == 1 && v.asignacion_status == 1" class="btn btn-sm btn-soft-info" @click="setStatusVehiculo(v.solicitud_id,v.asignacion_id,v.vehiculo_id,v.reng_num,2,'¿El vehículo regresó de comisión?','Vehículo finalizó la comisión.')"><i class="fa fa-arrow-left"></i></span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="col-sm-12" v-if=" (asignacion.asignacion_status == 1  || asignacion.asignacion_status == 2 ) && tipo == 'asignacion' && (privilegio.encargado_transporte == 'true' || privilegio.jefe_transporte == 'true' )">
        <span class="btn btn-sm btn-soft-info" @click="setOption(3,'¿Desea agregar este vehículo?')"><i class="fa fa-plus-circle"></i> Agregar</span>
      </div>
      <!--<table class="table table-sm table-striped table-bordered table-perfil">
        <thead>
          <th style="border-top: none;" class="text-left" :colspan="colSpan"><i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-car-side"></i> Vehículos asignados</th>
        </thead>
        <thead>
          <th class="text-center">Estado</th>
          <th class="text-center">En curso</th>
          <th class="text-center">Placa</th>
          <th class="text-center">Vehículo</th>
          <th class="text-center">Conductor</th>
          <th class="text-center">Tipo</th>
          <th class="text-center" v-if="tipo == 'asignacion'">Km. Inicial</th>
          <th class="text-center" v-if="tipo == 'asignacion'">Km. Final</th>
          <th class="text-center">Acción</th>
        </thead>
        <tbody>
          <tr v-for="v in vehiculos">
            <td class="text-center"><h3 :class="v.estado_asignacion"></h3></td>
            <td class="text-center"><h3 v-if="v.asignacion_status == 1" :class="v.estado_seguimiento"></h3></td>
            <td class="text-center">{{ v.nro_placa }} - {{ v.reng_num }}</td>
            <td class="text-left">{{ v.marca }} {{ v.linea }} {{ v.modelo }}</td>
            <td class="text-center">{{ v.conductor }}</td>
            <td class="text-center">{{ v.tipo_asignacion }}</td>
            <td class="text-center" v-if="tipo == 'asignacion'">{{ v.Kilometraje_ini }}</td>
            <td class="text-center" v-if="tipo == 'asignacion'">{{ v.Kilometraje_fin }}</td>
            <td class="text-center">

            </td>
          </tr>
        </tbody>
        <tfoot v-if="tipo == 'asignacion' && asignacion.asignacion_status == 2 && (privilegio.encargado_transporte == 'true' || privilegio.jefe_transporte == 'true' )">
          <tr>
            <td class="text-right" :colspan="colSpan">
              <span class="btn btn-sm btn-soft-info" @click="setOption(3,'¿Desea agregar este vehículo?')"><i class="fa fa-plus-circle"></i> Agregar</span>
            </td>
          </tr>
        </tfoot>
      </table>-->
    </div>
    <div class="col-sm-12 text-right" v-if="asignacion.asignacion_status == 2 && vehiculosAprobados == vehiculosRetornados && tipo == 'asignacion' && vehiculosAprobados > 0">
      <span class="btn btn-sm btn-info" @click="guardarEstado(3)"><i class="fa fa-check-circle"></i> Finalizar comisión</span><!-- finalizar asignacion -->
    </div>
    <!--<div class="card-body">-->

    <div v-if="opc == 2 || opc == 3">
    <div id="myModal" class="modal-vue">

      <!-- Modal content -->
      <div class="modal-vue-content" style="width:800px">
        <div class="card shadow-card">
          <header class="header-color">
            <h4 class="card-header-title" >
              <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-mobile-alt">
              </i><span v-if="opc == 3" class="text-white"> Agregar Vehículo</span><span class="text-white" v-else> Editar Vehículo </span>
            </h4>
            <span class="close-icon"  @click="setOption(1)">
              <i class="fa fa-times"></i>
            </span>
          </header>
          <div class="card-body">
            <form class="jsValidacionCrearTelefono" id="formNuevoTelefono" >
              <div class="row">
                <vehiculoform :evento="evento" :tipo="opc" :arreglo="dVehiculo" :solicitud_id="solicitud_id" :vista="tipo"></vehiculoform>

                <!--<combo row="col-sm-4" label="Tipo de Referencia*" codigo="id_tipo_referencia" :arreglo="tipoReferencia" tipo="2" requerido="true" :valor="dVehiculo.tipo"></combo>
                <combo row="col-sm-4" label="Tipo de Teléfono*" codigo="id_tipo_telefono" :arreglo="tipoTelefono" tipo="2" requerido="true" :valor="dVehiculo.id_tipo_telefono"></combo>
                <campo row="col-sm-4" label="Nro. de Teléfono" codigo="nro_telefono" tipo="number" requerido="true" :valor="dVehiculo.nro_telefono"></campo>
                <campo row="col-sm-12" label="Observaciones" codigo="tel_observaciones" tipo="textarea" requerido="true" :valor="dVehiculo.observaciones" ></campo>
                <checkbox row="col-sm-4" label="Privado" codigo="flag_privado" :valor="dVehiculo.flag_privado"></checkbox>
                <checkbox row="col-sm-4" label="Activo" codigo="flag_activo" :valor="dVehiculo.flag_activo"></checkbox>
                <checkbox row="col-sm-4" label="Principal" codigo="flag_principal" :valor="dVehiculo.flag_principal"></checkbox>-->
              </div>
              <div class="row" v-if="privilegio.encargado_transporte == 'true' || privilegio.jefe_transporte == 'true' ">
                <!--<accion confirmacion="1" cancelar="2" v-on:event_child="eventAccion"></accion>-->
              </div>
            </form>
          </div>
        </div>
      </div>
    <!--</div>-->
    </div>
  </div>
</template>
<script>
  const vehiculoform = httpVueLoader('././VehiculoFormulario.vue');
  module.exports = {
    props:["solicitud","solicitud_id","privilegio","estado", "evento","tipo","asignacion"],
    data: function(){
      return {
        vehiculos:[],
        empleados:"",
        opc:1,
        dVehiculo:"",
        colSpan:7
      }
    },
    components: {
      vehiculoform
    },
    computed:{
      vehiculosAprobados: function(){
        if(this.tipo == 'asignacion'){
          return this.vehiculos.reduce(function(total, item){
            operacion = (item.asignacion_status == 1) ? 1 : 0 ;
            return total + operacion;
          },0);
        }else{
          return 0;
        }
      },
      vehiculosRetornados: function(){
        if(this.tipo == 'asignacion'){
          return this.vehiculos.reduce(function(total, item){
            operacion = (item.asignacion_status == 1 && item.seguimiento_id == 2) ? 1 : 0 ;
            return total + operacion;
          },0);
        }else{
          return 0;
        }
      },
    },
    mounted(){

    },
    created: function(){
      this.getVehiculosBySolicitud();
      this.colSpan = (this.tipo == 'asignacion') ? 9 : 7;
      this.evento.$on('recargarVehiculosAsignacion', (data) => {
        this.getVehiculosBySolicitud();
      });
    },
    methods:{
      setOption: function(opc){
        this.opc = opc;
        this.dVehiculo = "";
        //this.evento.$emit('getOpcion', opc);
      },
      getVehiculosBySolicitud(){
        if(this.tipo == 'asignacion'){
          //inicio
          axios.get('transportes/model/Asignaciones', {
            params: {
              opcion:3,
              asignacion_id: this.solicitud_id
              /*tipo:0,
              year:2023,
              filtro:''//this.filtro*/
            }
          }).then(function (response) {
            this.vehiculos = response.data;
          }.bind(this)).catch(function (error) {
            console.log(error);
          });
          //fin
        }else{
          axios.get('transportes/model/Transporte.php',{
            params:{
              opcion:8,
              filtro:0,
              tipo:this.tipo,
              solicitud_id: this.solicitud_id
            }
          })
          .then(function (response) {
            this.vehiculos = response.data;
            /*const uniques = [...new Set(this.solicitudes.map(item => item.solicitud_fecha))]
            this.evento.$emit('getFechasConteo',uniques);*/
          }.bind(this))
          .catch(function (error) {
              console.log(error);
          });
        }


      },
      guardarEstado: function(estado,mensaje){
        //inicio
        var thisInstance = this;
        Swal.fire({
          title: '<strong>'+mensaje+'</strong>',
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value) {
            //inicio
            $.ajax({
              type: "POST",
              url: "transportes/model/Asignaciones.php",
              dataType: 'json',
              data: {
                opcion:4,
                //filtro:0,
                asignacion_id:thisInstance.solicitud_id,
                estado:estado
              }, //f de fecha y u de estado.
              beforeSend: function () {
                Swal.fire({
                  title: 'Espere..!',
                  text: 'Guardando información',
                  onBeforeOpen () {
                    Swal.showLoading ()
                  },

                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                })
                function myFunc(){
                  Swal.close()
                }
              },
              success: function (data) {
                Swal.close();

                //exportHTML(data.id);
                //recargarDocumentos();
                if (data.msg == 'OK') {
                  thisInstance.setOption(1);
                  thisInstance.getVehiculosBySolicitud();
                  $('#modal-remoto-lgg2').modal('hide');
                  //thisInstance.getVehiculosBySolicitud();
                  if(thisInstance.tipo == 'asignacion'){
                    thisInstance.evento.$emit('recargarTablaAsigancion');
                    thisInstance.evento.$emit('recargarAsignacion');
                  }else{
                    thisInstance.evento.$emit('recargarTablaTransporte');
                  }


                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });//impresion_pedido(data.id);
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
            //fin
          }
        })
        //fin
      },
      getVehiculoById: function(arreglo){
        this.setOption(2);
        this.dVehiculo = arreglo;
      },
      setStatusVehiculo: function(solicitud_id,asignacion,vehiculo,reng_num,estado,mensaje, mresponse){
        console.log('state: '+ estado);
        var thisInstance = this;
        let html = "";

        var id_anulacion_asignacion;
        var id_kilometraje;
        var id_fecha;
        var validacion1=0, validacion2 = 0;
        if(estado == 3){
          html = `<textarea name="swal-input1" id="swal-input1" class="swal2-input" placeholder="Observaciones"></textarea>`;
          validacion1 = 1;
        }else if(estado == 1 || estado == 2){
          if(estado == 1){
            html = `<div class="row">
            <div class="col-sm-6">
              <input type="datetime-local" name="swal-input2" id="swal-input2" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-sm-6">
              <input type="number" name="swal-input1" id="swal-input1" class="form-control form-control-sm" placeholder="Kilometraje incial">
            </div>
            <br><br>
            `;
          }
          if(estado == 2){
            html = `<div class="row">
            <div class="col-sm-6">
              <input type="datetime-local" name="swal-input2" id="swal-input2" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-sm-6">
              <input type="number" name="swal-input1" id="swal-input1" class="form-control form-control-sm" placeholder="Kilometraje final">
            </div>
            <br><br>
            `;
          }


          validacion2 = 1;
          //id_kilometraje=document.getElementById('swal-input2').value;

        }

        Swal.fire({
          title: '<strong>'+mensaje+'</strong>',
          type: 'question',
          html:html,
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Si!',
          focusConfirm: false,
          preConfirm: () => {
            if(estado == 3){

              const desc = Swal.getPopup().querySelector('#swal-input1').value
              if ( !desc) {
                Swal.showValidationMessage(`Por favor ingrese el motivo de la cancelación.`)
              }
              return {  desc: desc }
            }else{
              const login = Swal.getPopup().querySelector('#swal-input2').value
              const password = Swal.getPopup().querySelector('#swal-input1').value
              if (!login || !password) {
                Swal.showValidationMessage(`Por favor ingrese la hora y kilometraje.`)
              }
              return { login: login, password: password }
            }

          }
        }).then((result) => {
          if (result.value) {
            id_anulacion_asignacion = (validacion1 == 1) ? document.getElementById('swal-input1').value : '';
            id_kilometraje = (validacion2 == 1) ? document.getElementById('swal-input1').value : '';
            id_fecha = (validacion2 == 1) ? document.getElementById('swal-input2').value : '';
            console.log('valor: '+id_kilometraje);
            //inicio
            $.ajax({
              type: "POST",
              url: "transportes/model/Transporte.php",
              dataType: 'json',
              data: {
                opcion:9,
                //filtro:0,
                solicitud_id:solicitud_id,
                asignacion_id:asignacion,
                vehiculo_id:vehiculo,
                reng_num:reng_num,
                estado:estado,
                mensaje:mensaje,
                id_anulacion_asignacion:id_anulacion_asignacion,
                id_kilometraje:id_kilometraje,
                id_fecha:id_fecha
              }, //f de fecha y u de estado.
              beforeSend: function () {
                Swal.fire({
                  title: 'Espere..!',
                  text: 'Guardando información',
                  onBeforeOpen () {
                    Swal.showLoading ()
                  },

                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                })
                function myFunc(){
                  Swal.close()
                }
              },
              success: function (data) {
                Swal.close();

                //exportHTML(data.id);
                //recargarDocumentos();
                if (data.msg == 'OK') {
                  thisInstance.setOption(1);
                  thisInstance.getVehiculosBySolicitud();
                  if(thisInstance.tipo == 'asignacion'){
                    thisInstance.evento.$emit('recargarTablaAsigancion');
                  }else{
                    thisInstance.evento.$emit('recargarTablaTransporte');
                  }


                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });//impresion_pedido(data.id);
                } else {
                  Swal.fire({
                    type: 'error',
                    title: data.message,
                    showConfirmButton: true,
                    //timer: 1100
                  });
                }
              }
            }).done(function () {
            }).fail(function (jqXHR, textSttus, errorThrown) {
              alert(errorThrown);
            });
            //fin
          }
        })
        /*Swal.fire({
          title: '<strong>'+mensaje+'</strong>',
          text: "",
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si!',
          html:html
          /*input: 'textarea',
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
          }*/
        /*}).then((result) => {
          if (result.value) {
            //alert(vt_nombramiento);
            var id_anulacion_asignacion=document.getElementById('swal-input1').value;
            var id_kilometraje=document.getElementById('swal-input2').value;

            console.log('valor: '+ id_kilometraje)
            /*$.ajax({
              type: "POST",
              url: "transportes/model/Transporte.php",
              dataType: 'json',
              data: {
                opcion:9,
                //filtro:0,
                solicitud_id:solicitud_id,
                asignacion_id:asignacion,
                vehiculo_id:vehiculo,
                reng_num:reng_num,
                estado:estado,
                mensaje:mensaje,
                id_anulacion_asignacion:'',
                id_kilometraje:5555
              }, //f de fecha y u de estado.
              beforeSend: function () {
                Swal.fire({
                  title: 'Espere..!',
                  text: 'Guardando información',
                  onBeforeOpen () {
                    Swal.showLoading ()
                  },

                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false
                })
                function myFunc(){
                  Swal.close()
                }
              },
              success: function (data) {
                Swal.close();

                //exportHTML(data.id);
                //recargarDocumentos();
                if (data.msg == 'OK') {
                  thisInstance.getVehiculosBySolicitud();
                  thisInstance.evento.$emit('recargarTablaTransporte');
                  Swal.fire({
                    type: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 1100
                  });//impresion_pedido(data.id);
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
            });*/
          /*}
        })*/

      }
    }
  }
</script>
