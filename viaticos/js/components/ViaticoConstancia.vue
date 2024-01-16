<template>
  <div>
    <div v-if="viatico.status==938 || viatico.status==7959 || tipos >= 1 && tipol == 0">
      <div class="row">
        <div class="col-sm-3">
          <dato-persona texto="Destino:" :dato="viatico.destino" tipo="0"></dato-persona>
        </div>
        <div class="col-sm-5">
          <div class="row" style="margin-left:15px">
            <div class="col-sm-4">
              <dato-persona texto="Fecha Salida:" :dato="viatico.fecha_ini" tipo="0"></dato-persona>
              <dato-persona texto="" :dato="viatico.hora_ini" tipo="0"></dato-persona>
             </div>

             <div class="col-sm-4">
               <dato-persona texto="Fecha Regreso: " :dato="viatico.fecha_fin" tipo="0"></dato-persona>
               <dato-persona texto="" :dato="viatico.hora_fin" tipo="0"></dato-persona>
            </div>

            <div class="col-sm-4">
              <dato-persona texto="Duración" :dato="viatico.duracion" tipo="0"></dato-persona>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <!-- inicio -->
          <div class="row" v-if="viatico.confirma_lugar == 0 || viatico.confirma_lugar == 2">
            <div class="col-sm-6">
              <div class="form-group">
                <div class="">
                  <div class="row">
                    <label for="id_confirma">Confirma lugar*</label>
                    <div class=" input-group  has-personalizado" >
                      <label class="css-input input-sm switch switch-danger"><input id="chk_confirma" @change="validacionMostrarConfirma()" type="checkbox" /><span></span> <span id="lbl_chk">SI</span></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6" id="formulario_confirma" v-if="mostrarConfirma==1" class="slide_up_anim">
              <div class="form-group">
                <div class="">
                  <label for="">Tipo de confirmación</label>
                  <select class="form-control form-control-sm" @change="validaciondConfirmacionPlace($event)">
                    <option value="">-- Seleccionar --</option>
                    <!--<option value="1">Sustituir lugar</option>-->
                    <option value="2">Agregar destinos</option>
                  </select>
                </div>
              </div>
            </div>

          </div>
        </div>
          <!-- fin -->
           <div v-if="viatico.confirma_lugar=='1'">
             <dato-persona texto="Destino Nuevo:" :dato="viatico.historial" tipo="0"></dato-persona>
           </div>
           <div v-if="viatico.confirma_lugar=='2'">
             <dato-persona texto="Recorrido de la Comisión:" :dato="viatico.historial" tipo="0"></dato-persona>
           </div>
        </div>
      </div>
      <hr>
      <span id="id_country" hidden >{{viatico.id_pais}}</span>
      <span id="id_confirma" hidden>{{viatico.confirma_lugar}}</span>
      <form class="jsValidacionConstanciaViatico">
        <input id="id_persona" :value="personas" hidden></input>
        <input id="id_renglon" :value="renglones" hidden></input>
        <input id="id_viatico" name="id_viatico" v-bind:value="viatico.nombramiento" hidden></input>
        <input id="confirmar_lugar" name="confirmar_lugar" v-bind:value="confirmaPlace" hidden></input>
        <div  v-if="confirmaPlace==1 && tipos >= 1 && tipol == 0 && viatico.confirma_lugar == 0">
          <p>Este sección del formulario se mostrará solo una vez</p>
          <div class="row">
            <lugar-seleccion row1="col-sm-12" row2="col-sm-4" tipo="0"></lugar-seleccion>
          </div>
        </div>
        <div class="row">

          <div :class="claseEstiloG">

            <div class="row">
              <span class="numberr">1</span><strong class=""> Salida de SAAS</strong><br><br>
              <campo :row="claseEstilo" label="Fecha:*" codigo="txtFechaSalida" tipo="date" requerido="true"></campo>
              <combo-items :col="claseEstilo" label="Hora:*" codigo="cmbHoraSalida" :id_catalogo="37" requerido="true"></combo-items>
            </div>

          </div>


          <div v-if="confirmaPlace==2 && tipos >= 1 && tipol == 0">
            <span class="numberr">2</span><strong class=""> Agregar el o los destino (s)</strong><br><br>
            <span id="destinosfal" hidden>{{ viatico.total_destinos }}</span>
            <table class="table  table-striped table-sm" id="tb_lugares" width="100%">
              <thead class="text text-success">
                <tr>
                  <th class="text-center">
                    <button type='button'class="btn btn-sm btn-soft-info text-right" @click="addNewRow">
                      <i class="fas fa-plus-circle"></i>
                    </button>
                    Departamento
                  </th>
                  <th class="text-center">Municipio</th>
                  <th class="text-center">Aldea</th>
                  <th class="text-center">Llegada lugar</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Salida Lugar</th>
                  <th class="text-center">Hora</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for='(d, index) in destinos' v-if="index < viatico.total_destinos" :key="index">
                  <td width="200">
                    <div class="form-group" style="margin-bottom:0rem">
                      <div class="">
                        <div class="">
                          <select :name="'combo_dep'+index" :id="'combo_dep'+index" class="form-control form-control-sm" v-model="d.departamento" v-on:change="getMunicipios($event,index)" required>
                            <option v-for="dep in departamentos"v-bind:value="dep.dep_id" >{{ dep.dep_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="200">
                    <div class="form-group" style="margin-bottom:0rem">
                      <div class="">
                        <div class="">
                          <select :name="'combo_mun'+index" :id="'combo_mun'+index" class="form-control form-control-sm" v-model="d.municipio" required v-on:change="getAldeas($event,index)">
                            <option v-if="index==0" v-for="muni in munis1"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                            <option v-if="index==1" v-for="muni in munis2"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                            <option v-if="index==2" v-for="muni in munis3"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                            <option v-if="index==3" v-for="muni in munis4"v-bind:value="muni.muni_id" >{{ muni.muni_string }}</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td width="200">
                    <select :name="'combo_ald'+index" :id="'combo_ald'+index" class="form-control form-control-sm" v-model="d.aldea">
                      <option v-if="index==0" v-for="aldea in aldeas1"v-bind:value="aldea.lugar_id" >{{ aldea.lugar_string }}</option>
                      <option v-if="index==1" v-for="aldea in aldeas2"v-bind:value="aldea.lugar_id" >{{ aldea.lugar_string }}</option>
                      <option v-if="index==2" v-for="aldea in aldeas3"v-bind:value="aldea.lugar_id" >{{ aldea.lugar_string }}</option>
                      <option v-if="index==3" v-for="aldea in aldeas4"v-bind:value="aldea.lugar_id" >{{ aldea.lugar_string }}</option>
                    </select>
                  </td>
                  <td class="text-center" width="150">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label></label>
                          <div class=" input-group  has-personalizado">
                            <input required :id="'f_ini'+index" :name="'f_ini'+index" class='form-control form-control-sm' type="date" v-model="d.f_ini" autocomplete="off"></input>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center" width="150">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label></label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm  form-control_ chosen-select-width" :id="'h_ini'+index" :name="'h_ini'+index" v-model="d.h_ini" required>
                              <option v-for="t in horas" v-bind:value="t.id_item">{{ t.item_string }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center" width="150">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label></label>
                          <div class=" input-group  has-personalizado">
                            <input required :id="'f_fin'+index" :name="'f_fin'+index"  class='form-control form-control-sm' type="date" v-model="d.f_fin" autocomplete="off"></input>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center" width="150">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label></label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm  form-control_ chosen-select-width" :id="'h_fin'+index" :name="'h_fin'+index" v-model="d.h_fin" required>
                              <option v-for="t in horas" v-bind:value="t.id_item">{{ t.item_string }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td scope="row" class="trashIconContainer text-center">
                    <span class="btn btn-sm btn-danger" @click="deleteRow(index, d)"><i class="far fa-trash-alt" ></i></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div :class="claseEstiloG" v-if="confirmaPlace != 2 && tipos >= 1 && tipol == 0">
            <div class="row">
              <span class="numberr">2</span><strong class=""> Llegada al lugar</strong><br><br>
              <campo :row="claseEstilo" label="Fecha:*" codigo="txtLlegadaLugar" tipo="date" requerido="true"></campo>
              <combo-items :col="claseEstilo" label="Hora:*" codigo="cmbHoraLlegadaLugar" :id_catalogo="37" requerido="true"></combo-items>
            </div>
          </div>
          <div :class="claseEstiloG" v-if="confirmaPlace != 2 && tipos >= 1 && tipol == 0">
            <div class="row">
              <span class="numberr">3</span><strong class=""> Salida del lugar</strong><br><br>
              <campo :row="claseEstilo" label="Fecha:*" codigo="txtSalidaLugar" tipo="date" requerido="true"></campo>
              <combo-items :col="claseEstilo" label="Hora:*" codigo="cmbHoraSalidaLugar" :id_catalogo="37" requerido="true"></combo-items>
            </div>
          </div>

          <div :class="claseEstiloG">
            <div class="row">
              <span class="numberr" v-if="confirmaPlace != 2 && tipos >= 1 && tipol == 0">4</span>
              <span class="numberr" v-if="confirmaPlace==2 && tipos >= 1 && tipol == 0">3</span>
              <strong class=""> Regreso a SAAS</strong>
              <br><br>
              <campo :row="claseEstilo" label="Fecha:*" codigo="txtFechaRegreso" tipo="date" requerido="true"></campo>
              <combo-items :col="claseEstilo" label="Hora:*" codigo="cmbHoraRegreso" :id_catalogo="37" requerido="true"></combo-items>
            </div>
          </div>
          <div class="col-sm-12" v-if="viatico.id_pais != 'GT'">
            <span id="id_country_" hidden >{{viatico.id_pais}}</span>
            <div class="row">
              <div class="col-sm-6">
                <div class="row">
                  <div class="col-sm-6 ">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_tipo_salida">Tipo de salida*</label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_tipo_salida" @change="getEmpresa1($event,1)"name="id_tipo_salida">
                              <option v-for="t in transportes" v-bind:value="t.id_item">{{ t.item_string }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <combo-items col="col-sm-12" label="Empresa salida:*" codigo="cmbEmpresaSalida:*" id_catalogo="56" requerido="true"></combo-items>
                  <campo v-if="cEmpresa1 == true" row="col-sm-12" label="No. de asiento:*" codigo="txtNumeroVSalida" tipo="number" requerido="true"></campo>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="row">
                  <div class="col-sm-6 ">
                    <div class="form-group">
                      <div class="">
                        <div class="">
                          <label for="id_tipo_regreso">Tipo de salida*</label>
                          <div class=" input-group  has-personalizado" >
                            <select class="form-control form-control-sm  form-control_ chosen-select-width" id="id_tipo_regreso" @change="getEmpresa2($event,2)"name="id_tipo_regreso">
                              <option v-for="t in transportes" v-bind:value="t.id_item">{{ t.item_string }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <combo-items col="col-sm-12" label="Empresa regreso:*" codigo="cmbEmpresaRegreso:*" id_catalogo="56" requerido="true"></combo-items>
                  <campo v-if="cEmpresa2 == true" row="col-sm-12" label="No. de asiento:*" codigo="txtNumeroVEntrada" tipo="number" requerido="true"></campo>
                </div>
              </div>
            </div>
          </div>

          <button class="btn btn-sm btn-info btn-block" @click="generarConstancia()"><i class="fa fa-check"></i> Guardar</button>
        </div>
      </form>
    </div>

  </div>
</template>
<script>
module.exports = {
  props:["viatico","id_viatico","privilegio","estado_nombramiento", "evento", "tipos", "tipol", "personas", "renglones"],
  data: function(){
    return {
      departamentos:[],
      munis:[],
      mostrarConfirma:"",
      confirmaPlace:0,
      destinos:[],
      destino:[],
      munis1:[],
      munis2:[],
      munis3:[],
      munis4:[],

      aldeas1:[],
      aldeas2:[],
      aldeas3:[],
      aldeas4:[],

      horas:[],
      claseEstiloG:"col-sm-3",
      claseEstilo:"col-sm-12",
      transportes:[],
      cEmpresa1:false,
      cEmpresa2:false,
      empleados:""
    }
  },
  mounted(){
  },
  created: function(){
    this.getTransportes();
    this.evento.$on('recibirEmpleados', (data) => {
      this.empleados = data;
    })
  },
  methods:{
    cargarInput: function(id){
      //this.evento.$emit('mostrarModal',true);
    },
    seguimientoViatico: function(codigo){
      //this.evento.$emit('seguimientoViatico',codigo);
    },
    validacionMostrarConfirma: function(){
      if( $('#chk_confirma').is(':checked') )
      {
        this.mostrarConfirma=1;
      }else{
        this.mostrarConfirma=0;
        this.confirmaPlace=0;
        this.claseEstiloG = "col-sm-3";
        this.claseEstilo = 'col-sm-12';
        this.getDepartamentos();
      }
    },
    validaciondConfirmacionPlace: function(){
      this.confirmaPlace=event.currentTarget.value;
      if(this.confirmaPlace == 2){
        this.getHoras();
        this.claseEstiloG = "col-sm-12";
        this.claseEstilo = 'col-sm-3';
      }else{
        this.destinos.splice(0, 1);
        this.claseEstiloG = "col-sm-3";
        this.claseEstilo = 'col-sm-12';
      }
    },
    getMunicipios: function(event,index){
      this.tipo_muni=index;
      valor=event.currentTarget.value;
      axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
        params:{
          departamento:valor
        }
      })
      .then(function (response) {
        if(index == 0){ this.munis1 = response.data; }
        else if(index == 1){ this.munis2 = response.data; }
        else if(index == 2){ this.munis3 = response.data; }
        else if(index == 3){ this.munis4 = response.data; }

      }.bind(this))
      .catch(function (error) {
          console.log(error);
      });
    },
    getAldeas: function(event,index){
      this.tipo_muni=index;
      valor=event.currentTarget.value;
      //alert(valor);
      axios.get('viaticos/php/back/listados/destinos/get_aldea.php',{
        params:{
          municipio:valor
        }
      })
      .then(function (response) {
        if(index == 0){ this.aldeas1 = response.data; }
        else if(index == 1){ this.aldeas2 = response.data; }
        else if(index == 2){ this.aldeas3 = response.data; }
        else if(index == 3){ this.aldeas4 = response.data; }

      }.bind(this))
      .catch(function (error) {
          console.log(error);
      });
    },

    addNewRow: function(){
      if(this.destinos.length < $('#destinosfal').text()){
        var pais = this.viatico.id_pais
        this.destinos.push({pais_id:pais,departamento:'',municipio:'',aldea:'',tipo:'',f_ini:'',f_fin:'',h_ini:'',h_fin:''});
        this.getLoadEditTableDestinos();
        this.getDepartamentos();
      }else{
        console.log('Arreglo lleno');
      }
    },
    deleteRow(index, d) {
      var idx = this.destinos.indexOf(d);
      if (idx > -1) {
        this.destinos.splice(idx, 1);
      }
    },
    getDepartamentos: function()    {
      axios.get('viaticos/php/back/listados/destinos/get_departamento.php',{
        params:{
          pais:'GT'
        }
      })
      .then(function (response) {
        this.departamentos = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getLoadEditTableDestinos: function() {
      var thisInstance = this;
      setTimeout(() => {
        $('.f_fecha_d').editable({
          mode: 'popup',
          ajaxOptions: { dataType: 'json' },
          format: 'dd-mm-yyyy',
          viewformat: 'dd-mm-yyyy',
          datepicker: {
            weekStart: 1
          },
          type: 'date'
        });
        $('.horas_d').editable({
          mode: 'popup',
          ajaxOptions: { dataType: 'json' },
          type: 'select',
          source:this.horas
        });
      }, 600);
    },
    getHoras: function(){
      /*axios.get('viaticos/php/back/listados/get_horas_editable', {
        params: {
          tipo:37
        }
      }).then(function (response) {
        this.horas = response.data;

      }.bind(this)).catch(function (error) {
        console.log(error);
      });*/
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:37,
          tipo:0
        }
      })
      .then(function (response) {
        this.horas = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    generarConstancia: function(){
      var thisInstance = this;
      jQuery('.jsValidacionConstanciaViatico').validate({
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
          //inicio de form
          var error = false;
          var message = '';
          if(thisInstance.confirmaPlace == 2){
            if(thisInstance.destinos.length == 0){

              error = true;
              message = 'Debe agregar destinos a la lista';
            }else{
              if(thisInstance.contarEmptys()){
                error = false;
              }else{
                error = true;
                message = 'Debe ingresar correctamente las fechas y horas';
              }
            }
          }else{
            error = false;
          }

          if(error == false){
            //inicio
            Swal.fire({
              title: '<strong>¿Desea generar la constancia?</strong>',
              text: "",
              type: 'info',
              showCancelButton: true,
              showLoaderOnConfirm: true,
              confirmButtonColor: '#28a745',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, Generar!'
            }).then((result) => {
              if (result.value) {
                //alert(vt_nombramiento);
                $.ajax({
                  type: "POST",
                  url: "viaticos/php/back/viatico/constancia/generar_constancia.php",
                  data: {
                    id_persona: $('#id_persona').val(),
                    id_renglon: $('#id_renglon').val(),
                    id_pais : thisInstance.viatico.id_pais,
                    id_viatico : thisInstance.viatico.nombramiento,//$('#id_viatico').val(),
                    confirmar_lugar : $('#confirmar_lugar').val(),
                    txtFechaSalida : $('#txtFechaSalida').val(),
                    cmbHoraSalida : $('#cmbHoraSalida').val(),
                    txtLlegadaLugar : $('#txtLlegadaLugar').val(),
                    cmbHoraLlegadaLugar : $('#cmbHoraLlegadaLugar').val(),
                    txtSalidaLugar : $('#txtSalidaLugar').val(),
                    cmbHoraSalidaLugar : $('#cmbHoraSalidaLugar').val(),
                    txtFechaRegreso : $('#txtFechaRegreso').val(),
                    cmbHoraRegreso : $('#cmbHoraRegreso').val(),

                    cmbHoraSalidaT : $( "#cmbHoraSalida option:selected" ).text(),
                    cmbHoraRegresoT : $( "#cmbHoraRegreso option:selected" ).text(),

                    id_tipo_salida : $('#id_tipo_salida').val(),
                    cmbEmpresaSalida : $('#cmbEmpresaSalida').val(),
                    txtNumeroVSalida : $('#txtNumeroVSalida').val(),
                    id_tipo_regreso : $('#id_tipo_regreso').val(),
                    cmbEmpresaRegreso : $('#cmbEmpresaRegreso').val(),
                    txtNumeroVEntrada : $('#txtNumeroVEntrada').val(),

                    departamento : $('#departamento').val(),
                    municipio : $('#municipio').val(),
                    aldea : $('#aldea').val(),
                    destinos : thisInstance.destinos


                  }, //f de fecha y u de estado.
                  beforeSend:function(){

                  },
                  success:function(data){
                    //alert(data);

                    thisInstance.evento.$emit('recargarViatico');
                    thisInstance.evento.$emit('recargarEmpleadosList');
                    thisInstance.evento.$emit('mostrarModal',false);

                    //document.app_vue.get_empleado_by_viatico();
                    Swal.fire({
                      type: 'success',
                      title: 'Lugar confirmado',
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
          }else{
            Swal.fire({
              type: 'error',
              title: message,
              showConfirmButton: false,
              timer: 1100
            });
          }
          //final de form
        }
      });
    },
    contarEmptys: function(){
      var count=0;
      $("#tb_lugares tbody tr").each(function(index, element){
        id_row = ($(this).attr('id'));
        if($('#f_ini'+index).text()=='Empty' || $('#f_fin'+index).text()=='Empty' || $('#h_ini'+index).text()=='Empty' || $('#h_fin'+index).text()=='Empty'){
          count++;
        }
        console.log(count);
      });
      if(count==0){
        return true;
      }else{
        return false;
      }
    },
    getEmpresa1: function(event, tipo){
      if(event.currentTarget.value == 941 && tipo == 1){
        this.cEmpresa1 = true;
      }else{
        this.cEmpresa1 = false;
      }
    },
    getEmpresa2: function(event, tipo){
      if(event.currentTarget.value == 941 && tipo == 2){
        this.cEmpresa2 = true;
      }else{
        this.cEmpresa2 = false;
      }
    },
    getTransportes: function(){
      axios.get('empleados/php/back/listados/get_items.php', {
        params: {
          id_catalogo:35,
          tipo:0
        }
      })
      .then(function (response) {
        this.transportes = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    }

  }


}

</script>
