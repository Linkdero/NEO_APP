<template>
  <div class="row">
    <!--form: {{ formLiquidacion.formulario }}<br>
    facturas: {{ verificarFacturas }}<br>
    totalesA : {{ totalReintegroA }}
    totalesH : {{ totalReintegroH }}-->
    <div class="col-sm-6">
      NIT DE LA SAAS:  23714859
    </div>
    <div class="col-sm-3">
      <span v-if="opcion == 2"> Fecha de liquidación: {{ filaDetalle.fecha }} </span>
    </div>
    <div class="col-sm-3 text-right">
      <search-invoice></search-invoice>
    </div>
    <br><br>
    <div class="col-sm-12" v-show="opcion == 1">
      <form id="formValidacionLiquidarNombramiento">
        <input id="id_persona" :value="personas" hidden></input>
        <input id="id_renglon" :value="renglones" hidden></input>
        <table class="table table-sm table-bordered table-striped" style="border:3px solid #addaff;">
          <thead v-if="viatico.id_pais == 'GT'">
            <tr>
              <th rowspan="2" class="text-center">Fecha</th>
              <th rowspan="2" class="text-center">Día</th>
              <th colspan="4" class="text-center" style="border-left:3px solid #addaff;">Alimentación</th>
              <th colspan="4" class="text-center" style="border-left:3px solid #addaff;">Hospedaje</th>
              <th rowspan="2" class="text-center" style="border-left:3px solid #addaff;">Acción</th>
            </tr>
            <tr>
              <th class=" text-center font-weight-bold" style="border-left:3px solid #addaff;">Monto</th>
              <th class=" text-center font-weight-bold">Gasto</th>
              <th class=" text-center font-weight-bold">Permitido</th>
              <th class=" text-center font-weight-bold">Reintegro</th>
              <th class=" text-center font-weight-bold" style="border-left:3px solid #addaff;">Monto</th>
              <th class=" text-center font-weight-bold">Gasto</th>
              <th class=" text-center font-weight-bold">Permitido</th>
              <th class=" text-center font-weight-bold"style="border-right:3px solid #addaff;">Reintegro</th>
            </tr>
          </thead>
          <tbody v-if="viatico.id_pais == 'GT'">
            <!--{{ dias.length }}-->
            <tr v-for="(d, index) in dias" v-if="d.tipo == 'v'">

              <td class="text-center"> {{ d.fecha }}</td>
              <td class="text-center" > {{ d.dia }} </td>
              <td class="text-right" style="border-left:3px solid #addaff;"><span  v-if="d.tomar_en_cuenta == true" > {{ d.monto_diario_a }}</span></td>
              <td class="text-right" ><span  v-if="d.tomar_en_cuenta == true" > {{ d.monto_alimentacion }} </span></td>
              <td class="text-right" ><span  v-if="d.tomar_en_cuenta == true" > {{ d.monto_alimentacion_real }} </span></td>
              <td class="text-right" ><span  v-if="d.tomar_en_cuenta == true" > {{ (d.monto_diario_a - d.montoalirestar).toFixed(2) }} </span></td>
              <td class="text-right" style="border-left:3px solid #addaff;"> <span v-if="index < totalFilas">{{ d.monto_diario_h }} </span></td>
              <td class="text-right"> <span v-if="index < totalFilas"> {{ d.monto_hospedaje }}  </span></td>
              <td class="text-right"> <span v-if="index < totalFilas"> {{ d.monto_hospedaje_real }}  </span></td>
              <td class="text-right"> <span v-if="index < totalFilas">{{ (d.monto_diario_h - d.monto_hospedaje_real).toFixed(2) }} </span></td>
              <td class="text-center" style="border-left:3px solid #addaff;"><!--{{ d.validacionfila }} - {{ d.fila }} - {{ d.filas }}-->
                <span class="btn btn-info btn-sm" @click="getFacturasByDay(2,d,index)"><i class="fa fa-pen"></i></span>
                <span class="btn btn-info btn-sm" @click="getFacturasByDay(4,d,index)"><i class="fa fa-utensils"></i></span>
              </td>
            </tr>
          </tbody>
          <tfoot style="border-top:3px solid #addaff;">
            <tr>
              <td class="text-right" colspan="6"> <strong>{{ (totalReintegroA - 0).toFixed(2) }}</strong></td>
              <td class="text-right"  colspan="4"> <strong>{{ (totalReintegroH - 0).toFixed(2) }} </strong></td>
              <td class="text-right"> </td>
            </tr>
            <tr>
              <td colspan="11" class="text-right">
                <span class="btn btn-sm btn-soft-info" @click="getFacturasByDay(3,0,0)"><i class="fa fa-plus-circle"></i> Agregar gastos</span>
              </td>
            </tr>

          </foot>
          <tfoot v-if="totalFacturasGastos > 0 && formLiquidacion.formulario > 0 || viatico.id_pais != 'GT'" style="border-top:3px solid #addaff;">
            <td colspan="11" class="text-right">
              <span class="btn btn-sm btn-soft-info" @click="getFacturasByDay(3,0,0)"><i class="fa fa-plus-circle"></i> Gastos extras</span>
            </td>
          </foot>
        </table>

        <div class="row" v-if="totalFacturasGastos > 0" class="col-sm-12">
          <div class="col-sm-8">
          </div>
          <div class="col-sm-4">
            <h3 class="font-semi-bold">Gastos extras</h3>
            Gastado: {{totalGastos }} <br> Facturas:
            {{totalFacturasGastos }}
          </div>


        </div>
        <input id="id_reintegro_hospedaje" name="id_reintegro_hospedaje" v-bind:value="totalReintegroH" hidden></input>
        <div class="row" v-show="viatico.id_pais != 'GT'">
          <div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_otros_gastos">Reintegro*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" class=" form-control input-sm" id="id_reintegro_alimentacion" name="id_reintegro_alimentacion" placeholder="@Reintegro" v-bind:value="totalReintegroA" :required="viatico.id_pais != 'GT'"  autocomplete="off"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!--<input id="id_reintegro_alimentacion" name="id_reintegro_alimentacion" v-bind:value="totalReintegroA" v-show="viatico.id_pais != 'GT'"></input>-->
        <div class="row" v-if="formLiquidacion.formulario == 0">

          <!--<div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_otros_gastos">Otros Gastos*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="text" class=" form-control input-sm" id="id_otros_gastos" name="id_otros_gastos" placeholder="@Otros gastos" value="0.00" required  autocomplete="off"/>
                  </div>
                </div>
              </div>
            </div>
          </div>-->
          <!--<div class="col-sm-6">
            <div class="form-group">
              <div class="">
                <div class="">
                  <label for="id_fecha_liquidacion">Fecha Liquidación*</label>
                  <div class=" input-group  has-personalizado" >
                    <input type="date" class=" form-control input-sm" id="id_fecha_liquidacion" name="id_fecha_liquidacion" placeholder="@fecha liquidación" required  autocomplete="off"/>
                  </div>
                </div>
              </div>
            </div>
          </div>-->
          <div class="col-sm-12">
            <button class="btn btn-sm btn-info" @click="liquidarNombramiento()"><i class="fa fa-check"></i> Liquidar Nombramiento</button>
            <span v-if="verificarFacturas.msg == 'OK'" class="btn btn-soft-info btn-sm" @click="printRazonamiento(viatico.nombramiento, personas)"><i class="fa fa-print"></i> Imprimir razonamiento</span>
          </div>

        </div>
        <div class="row" v-if="verificarFacturas.msg == 'ERROR' && formLiquidacion.formulario == 0">
          <div class="col-sm-12">
            <div class="alert alert-soft-danger" >
              <i class="fa fa-minus-circle alert-icon mr-3"></i>
              <span>{{ verificarFacturas.message }}</span>
            </div>
          </div>
        </div>
        <div v-if="formLiquidacion.formulario > 0">
          <span class="btn btn-soft-info btn-sm" @click="printRazonamiento(viatico.nombramiento, personas)"><i class="fa fa-print"></i> Imprimir razonamiento</span>
        </div>
      </form>
    </div>
    <div v-show="opcion == 4">
      <span class="btn btn-soft-info btn-sm" @click="cargarForm(1,'')"><i class="fa fa-arrow-left"></i> Regresar</span><br><br>
      <div  id="jsValidacionFacturasLiquidacionAprobadas">
        <!--{{ this.filaDetalle }}-->

        <input id="id_persona" :value="personas" hidden></input>
        <input id="id_renglon" :value="renglones" hidden></input>

        <table class="table table-sm table-bordered responsive table-striped" width="100%">
          <thead>
            <th class=" text-center">Tiempo</th>
            <th class=" text-center">Monto</th>
            <th class=" text-center">Seleccionar</th>
          </thead>
          <tbody>
            <tr v-for="(f, index) in facturas">
              <td>{{ f.tiempo_t}}</td>
              <td>{{ f.monto }}</td>
              <td><label class="css-input switch switch-success switch-sm"><input class="chequeado" :id="'chke'+index" v-model="f.error" :name="'chke'+index" type="checkbox"/><span></span> </label></td>
            </tr>
          </tbody>
        </table>
        <button v-if="formLiquidacion.formulario == 0" class="btn btn-info btn-sm" @click="guardarFacturasAprobadas($event)">
          <i class="fa fa-check-circle"></i>
          Guardar
        </button>
      </div>

    </div>
    <div class="col-sm-12" v-show="opcion == 2 || opcion == 3">
      <form  id="jsValidacionFacturasLiquidacion" class="jsValidacionFacturasLiquidacion">
        <span class="btn btn-soft-info btn-sm" @click="cargarForm(1,'')"><i class="fa fa-arrow-left"></i> Regresar</span><br><br>
        <!--{{ this.filaDetalle }}-->

        <input id="id_persona" :value="personas" hidden></input>
        <input id="id_renglon" :value="renglones" hidden></input>

        <input id="id_pais" name="id_pais" :value="viatico.id_pais" hidden></input>
        <!-- {{ facturas }} -->
        <span v-if="opcion == 3" class="btn btn-sm btn-info" @click="addNewRow()" style="margin-bottom:10px"><i class="fa fa-plus-circle"></i> Agregar</span>
        <table class="table table-sm table-bordered responsive table-striped" >
          <thead>
            <th class=" text-center">CONCEPTO</th>
            <th class=" text-center" width="100px" v-if="viatico.id_pais == 'GT'">NIT</th>
            <th class=" text-center" width="30px">FECHA</th>
            <th class=" text-center" v-if="viatico.id_pais == 'GT'">SERIE</th>
            <th class=" text-center" :style="{ width: ancho }">NUMERO</th>
            <th class=" text-center" :style="{ width: anchoM }">MONTO</th>
            <th class=" text-center" width="30px" v-if="opcion == 2">PROPINA</th>
            <th class=" text-center" width="300px"><span class="btn btn-sm btn-soft-info" v-if="formLiquidacion.formulario == 0" @click="saveProveedor()"><i class="fa fa-plus-circle"></i></span>RESTAURANTE -<br> HOTEL</th>
            <th class=" text-center" width="" v-if="opcion == 3">MOTIVO</th>
            <th class=" text-center" width="30px" v-if="opcion == 2"></th>
          </thead>
          <tbody>
            <tr v-for="(f, index) in facturas">

              <td>
                <input :id="'factura'+index" :name="'factura'+index" v-model="f.factura_id" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off" hidden></input>
                <input :id="'txtnitt'+index" :name="'txtnitt'+index" v-model="f.nitt" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off" hidden></input>
                <h6><strong>{{ f.tiempo_t }}</strong></h6>
                <input class="tgl tgl-flip text-center" :id="'chk'+index" :name="'chk'+index" type="checkbox" v-model="f.bln_confirma" :value="1" v-bind:disabled="f.deshabilitado" @change="clearClass(index,f.bln_confirma)"/>
                <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="'chk'+index" ></label>
              </td>
              <td v-if="viatico.id_pais == 'GT'">
                <div class="form-group" :class="'cf1'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input :required="f.bln_confirma == true && viatico.id_pais == 'GT'" :id="'txtnit'+index" :name="'txtnit'+index" v-model="f.nit" class='mainInput form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div class="form-group" :class="'cf2'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input type="date" :required="f.bln_confirma == true" :id="'txtfecha'+index" :name="'txtfecha'+index" v-model="f.fecha" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td v-if="viatico.id_pais == 'GT'">
                <div class="form-group" :class="'cf3'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input :required="f.bln_confirma == true && viatico.id_pais == 'GT'" :id="'txtserie'+index" :name="'txtserie'+index" v-model="f.serie" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false || viatico.id_pais != 'GT')" autocomplete="off"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div class="form-group" :class="'cf4'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input :required="f.bln_confirma == true" :id="'txtnumero'+index" :name="'txtnumero'+index" v-model="f.numero" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div class="form-group" :class="'cf5'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input :required="f.bln_confirma == true" :id="'txtmonto'+index" :name="'txtmonto'+index" v-model="f.monto" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off" type="number"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td v-if="opcion == 2">
                <div class="form-group" :class="'cf6'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input :required="f.bln_confirma == true" :id="'txtpropina'+index" :name="'txtpropina'+index" v-model="f.propina" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off" type="number"></input>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <input :required="f.bln_confirma == true" :id="'txtproveedor'+index" :name="'txtproveedor'+index" v-model="f.proveedor" class='' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off" hidden></input>
                <div class="form-group" :class="'cf7'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <select :class="'filter'+index" :required="f.bln_confirma == true && f.proveedor == 0" :id="'txtfiltroproveedor'+index" v-model="f.lugar_nombre" :name="'txtfiltroproveedor'+index"  class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off"></select>
                      </div>
                    </div>
                  </div>
                </div>


              </td>
              <td v-if="opcion == 2">
                <label class="css-input switch switch-success switch-sm"><input class="chequeado" :id="'chke'+index" v-model="f.error" :name="'chke'+index" type="checkbox" v-bind:disabled="(f.bln_confirma == false || formLiquidacion.formulario > 0)"/><span></span> </label>
              </td>

              <td v-if="opcion == 3">
                <div class="form-group" :class="'cf8'+index">
                  <div class="">
                    <div class="">
                      <div class="input-group  has-personalizado" >
                        <input :required="f.bln_confirma == true" :id="'txtmotivogastos'+index" :name="'txtmotivogastos'+index" maxlength="70" v-model="f.motivo_gastos" class='form-control form-control-sm' v-bind:disabled="(f.bln_confirma == false)" autocomplete="off"></input>
                      </div>
                    </div>
                  </div>
                </div>


              </td>


            </tr>
          </tbody>
        </table>
        <div class="row" v-if="opcion == 2">
          <campo row="col-sm-6" tipo="textarea" requerido="false" codigo="anotaciones_alimentacion" label="Observaciones de alimentación" :valor="diaAli"></campo>
          <campo v-if="facturas.length == 4" row="col-sm-6" tipo="textarea" requerido="false" codigo="anotaciones_hospedaje" label="Observaciones de hospedaje" :valor="diaHos"></campo>
        </div>

        <button v-if="formLiquidacion.formulario == 0" class="btn btn-info btn-sm" @click="guardarFacturas('',$event)">
          <i class="fa fa-check-circle"></i>
          Guardar
        </button>
      </form>

    </div>
    <div class="col-sm-12" v-show="opcion == 5">
      <!-- inicio -->
      <span class="btn btn-soft-info btn-sm" @click="getFacturasByDay(2, dDetalle, arrayFila)"><i class="fa fa-arrow-left"></i> Regresar</span><br><br>
      <form  id="jsValidacionFacturasProveedor" class="jsValidacionFacturasProveedor">
        <div class="row">
          <campo row="col-sm-3" tipo="texto" requerido="true" codigo="cod_proveedor" label="Nit del proveedor"></campo>
          <campo row="col-sm-9" tipo="texto" requerido="true" codigo="nombre_proveedor" label="Razón social"></campo>
          <div class="col-sm-12">
            <button class="btn btn-info btn-sm btn-block" @click="guardarProveedor()">
              <i class="fa fa-check-circle"></i>
              Guardar dd
            </button>
          </div>

        </div>
      </form>
      <!-- fin -->
    </div>




  </div>
</template>
<style>
.borde-izquierdo{
  border-left:4px solid #addaff;
}
</style>

<script>
module.exports = {
  props:["viatico","id_viatico","privilegio","estado_nombramiento", "evento", "tipos", "tipol", "personas", "renglones"],
  data: function(){
    return {
      opcion:1,
      filaDetalle:"",
      dias:[],
      tableFacturas:[{dia_id:"",tiempo:"",tipo:"",nit:"",fecha:"",serie:"",numero:"",monto:"",descuento:"",propina:"",proveedor:"",concepto:""}],
      totalFilas:0,
      mValue:0,
      conceptoArray:[{id_item:1,item_string:"CONSUMO DE ALIMENTOS"},{item_id:2,item_string:"HOSPEDAJE"}],
      validacion:true,
      facturas:[],
      chequeados:0,
      verificarFacturas:"",
      formLiquidacion:0,
      diaAli:"",
      diaHos:"",
      gastosFacturas:0,
      gastosTotal:0,
      ancho: 'auto',
      anchoM: '120px',
      vDetalle: '',
      incrementalR:0,
      arrayFila:"",
      dDetalle:""
    }
  },
  computed:{
    totalReintegroA: function(){
      if(this.viatico.id_pais == 'GT'){
        //inicio
        var operacion;
        return this.dias.reduce(function(total, item){
          operacion = (item.tipo == "v" && item.tomar_en_cuenta == true) ? item.monto_diario_a - item.monto_alimentacion_real : 0;
          return total + operacion;
        },0);
        //fin
      }else{
        return this.vDetalle.reintegro_alimentacion
      }

    },
    totalReintegroH: function(){

      if(this.viatico.id_pais == 'GT'){
        //inicio
        var operacion;
        return this.dias.reduce(function(total, item){
          operacion = (item.tipo == "v") ? item.monto_diario_h - item.monto_hospedaje_real : 0 ;
          return total + operacion;
        },0);
        //fin
      }else{
        return 0;
      }

    },
    totalGastos: function(){
      var operacion = 0;
      return this.dias.reduce(function(total, item){
        operacion = (item.tipo == "g") ? item.monto_diario_a - 0 : 0 ;
        return total + operacion;
      },0);
    },
    totalFacturasGastos: function(){
      //if(this.viatico.id_pais == 'GT'){
        var operacion = 0;
        return this.dias.reduce(function(total, item){
          operacion = (item.tipo == "g") ? item.monto_diario_h - 0 : 0 ;
          return total + operacion;
        },0);
      /*}else{
        return 0;
      }*/

    }
  },
  created: function(){
    if(this.viatico.id_pais != 'GT'){
      this.getViaticoDetalle();
      this.ancho = '150px';
      this.anchoM = '150px';
    }
    this.getDiasComision();
    this.tableFacturas.splice(0, 1);
    this.getFormLiquidacion();
    this.verificarFacturasFaltantes();
  },
  methods:{
    cargarInput: function(id){
      this.evento.$emit('mostrarModal',true);
    },
    seguimientoViatico: function(codigo){
      //this.evento.$emit('seguimientoViatico',codigo);
    },
    setOption:function(opcion){
      this.opcion = opcion;
    },
    getViaticoDetalle: function(){
      axios.get('viaticos/php/back/viatico/detalle/get_detalle_viatico', {
        params: {
          id_viatico:this.viatico.nombramiento,
          id_persona:this.personas
        }
      })
      .then(function (response) {
        this.vDetalle = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    getDiasComision: function(){
      axios.get('viaticos/php/back/viatico/liquidacion/generar_dias', {
        params: {
          id_viatico:this.viatico.nombramiento,
          id_persona:this.personas
        }
      })
      .then(function (response) {
        this.dias = response.data;
        var contador = 0;
        if(response.data.find((item) => item.tipo == 'g')){
          contador = 1;
        }
        this.totalFilas = (this.dias.length - 1 - contador);
        console.log(this.totalFilas);
        this.opcion = 1;


      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    cargarForm: function(opcion, d, index){
      console.log(this.totalFilas);
      this.tableFacturas.splice(0, 4);
      console.log(d)
      this.setOption(opcion);

      var conepto = '';
      this.filaDetalle = d;
      for(var x = 0; x < 4; x++){
        var tipo = '';
        if(index == this.totalFilas && x == 3){
        }else{
          concepto = (x == 3) ? 'HOSPEDAJE' : 'ALIMENTACIÓN';
          tipo = (x == 3) ? 2 : 1;
          this.tableFacturas.push({dia_id:"",tiempo:(x+1),tipo:tipo,nit:"",fecha:"",serie:"",numero:"",monto:"",descuento:"",propina:0,proveedor:"",concepto:concepto});
        }

      }

    },
    addNewRow: function(){
      var thisInstance = this;
      var iRow = this.facturas.push({dia_id:"",factura_id:"",tiempo:"5",tiempo_t:"GASTOS",tipo:"",nit:"",nitt:"",fecha:"",serie:"",numero:"",monto:"",descuento:"",propina:"0",proveedor:"",
      concepto:"",bln_confirma:true,guardado:false,deshabilitado:false,dia_observaciones_al:"",dia_observaciones_hos:"",}) - 1;

      console.log('indexxxx :: :: :: ' + iRow);

      setTimeout(() => {

        $('.filter'+iRow).select2({
          placeholder: 'Selecciona un insumo',
          ajax: {
            url: 'viaticos/php/back/viatico/detalle/get_restaurantes.php',
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

        $('.filter'+iRow).on('select2:select', function (e) {
          var id = $(this).attr('id');
          var idTextoProveedor = id.replace("filtro", ""); // "_xxx"
          var idx = id.replace("txtfiltroproveedor","");
          var idFactura = $('#factura'+idx).val();
          console.log("Valor seleccionado: "+ $('#factura'+idx).val());

          var data = e.params.data;
          //$('#'+idTextoProveedor).val(data.id);

          var elementIndex = thisInstance.facturas.findIndex((obj => obj.factura_id == idFactura));

          console.log("Before update: ", thisInstance.facturas[elementIndex].proveedor);
          thisInstance.facturas[idx].proveedor = data.id;
          console.log("After update: ", thisInstance.facturas[elementIndex].proveedor);
          console.log(data);



        });
      }, 900);

      /*setTimeout(() => {
        var thisInstance = this;
        var x = 0;
        thisInstance.facturas.map(function(obj){
          console.log(obj);
          var $option = $("<option selected></option>").val(obj.lugar_id).text(obj.lugar_nit + " - "+ obj.lugar_nombre);
          $('#txtfiltroproveedor'+ x).append($option).trigger('change');
           x ++;
        });
      },900)*/

      this.incrementalR += 1;
    },
    deleteRow: function(){

    },
    getFacturasByDay: function(opcion, d, index){
      this.arrayFila = index;
      this.dDetalle = d;
      var thisInstance = this;
      console.log($('#id_persona').val());
      console.log(this.totalFilas);
      this.setOption(opcion);
      this.facturas.splice(0, this.facturas.length);
      axios.get('viaticos/php/back/viatico/liquidacion/get_facturas_by_dia', {
        params: {
          id_viatico:this.viatico.nombramiento,
          id_persona:this.personas,
          fecha:d.fecha,
          fila:index,
          filas:this.totalFilas,
          formulario:this.formLiquidacion.formulario,
          tipo:opcion
        }
      })
      .then(function (response) {
        this.facturas = response.data;
        this.filaDetalle = d;
        if(opcion == 2){
          this.diaAli= response.data[0].dia_observaciones_al;
          this.diaHos= response.data[0].dia_observaciones_hos;
        }

        setTimeout(() => {

          $('.filter0, .filter1, .filter2, .filter3, .filter4,.filter5,.filter6, .filter7, .filter8').select2({
            placeholder: 'Selecciona un insumo',
            ajax: {
              url: 'viaticos/php/back/viatico/detalle/get_restaurantes.php',
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

          $('.filter0, .filter1, .filter2, .filter3, .filter4,.filter5,.filter6, .filter7, .filter8').on('select2:select', function (e) {
            //Instancia.getCapacidadTanque();
            //Instancia.getTipoCombustible();

            /*alert($(this).val());
            alert($(this).attr('id'));*/
            var id = $(this).attr('id');
            var idTextoProveedor = id.replace("filtro", ""); // "_xxx"
            var idx = id.replace("txtfiltroproveedor","");
            var idFactura = $('#factura'+idx).val();
            console.log("Valor seleccionado: "+ $('#factura'+idx).val());

            var data = e.params.data;
            //$('#'+idTextoProveedor).val(data.id);

            var elementIndex = thisInstance.facturas.findIndex((obj => obj.factura_id == idFactura));

            console.log("Before update: ", thisInstance.facturas[elementIndex].proveedor);
            thisInstance.facturas[idx].proveedor = data.id;
            console.log("After update: ", thisInstance.facturas[elementIndex].proveedor);
            console.log(data);



          });
        }, 900);

        setTimeout(() => {
          var thisInstance = this;
          var x = 0;
          thisInstance.facturas.map(function(obj){
            console.log(obj);
            var $option = $("<option selected></option>").val(obj.lugar_id).text(obj.lugar_nit + " - "+ obj.lugar_nombre);
            $('#txtfiltroproveedor'+ x).append($option).trigger('change');
             x ++;
          });
        },1500)


      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    clearClass: function(idx,bln){
      console.log(bln);
      if(bln == false){
        $('#txtnit'+idx).val('adfadf');
        $('#txtfecha'+idx).val('');
        $('#txtserie'+idx).val('');
        $('#txtnumero'+idx).val('');
        $('#txtmonto'+idx).val('');
        $('#txtpropina'+idx).val('');
        $('#txtproveedor'+idx).val('');

        $('.cf1'+idx).removeClass('has-error');
        $('.cf2'+idx).removeClass('has-error');
        $('.cf3'+idx).removeClass('has-error');
        $('.cf4'+idx).removeClass('has-error');
        $('.cf5'+idx).removeClass('has-error');
        $('.cf6'+idx).removeClass('has-error');
        $('.cf7'+idx).removeClass('has-error');

        $('#txtnit'+idx+'-error').hide();
        $('#txtfecha'+idx+'-error').hide();
        $('#txtserie'+idx+'-error').hide();
        $('#txtnumero'+idx+'-error').hide();
        $('#txtmonto'+idx+'-error').hide();
        $('#txtpropina'+idx+'-error').hide();
        $('#txtproveedor'+idx+'-error').hide();


      }

    },
    getFormLiquidacion: function(){
      axios.get('viaticos/php/back/viatico/liquidacion/get_formulario_liquidacion', {
        params: {
          id_viatico:this.viatico.nombramiento,
          id_persona:this.personas,
        }
      })
      .then(function (response) {
        this.formLiquidacion = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    verificarFacturasFaltantes: function(){
      axios.get('viaticos/php/back/viatico/liquidacion/verificar_facturas_por_dia', {
        params: {
          id_viatico:this.viatico.nombramiento,
          id_persona:this.personas,
        }
      })
      .then(function (response) {
        this.verificarFacturas = response.data;
      }.bind(this))
      .catch(function (error) {
        console.log(error);
      });
    },
    printRazonamiento: function(nombramiento, id_persona){
      var gafete = id_persona.replace(',', '')
      imprimirRazonamiento(nombramiento,gafete)
    },
    validarFacturasCant: function(){
      var validacion = false;
      if(this.opcion == 2){
        validacion = true;
      }else if(this.opcion == 3){
        if(this.facturas.length > 0){
          validacion = true;
        }else{
          validacion = false;
        }
      }
      return validacion;
    },
    guardarFacturasAprobadas: function(event){
      var thisInstance = this;
      //inicio
      Swal.fire({
        title: '<strong>¿Desea agregar esta información?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: 'btn btn-success',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Liquidar día!'
      }).then((result) => {
        if (result.value) {
          //inicio
          $.ajax({
            url:'viaticos/php/back/viatico/liquidacion/liquidar_factura',
            method:"POST",
            dataType: 'json',
            data:{
              opcion:thisInstance.opcion,
              id_persona: $('#id_persona').val(),
              id_renglon: $('#id_renglon').val(),
              id_pais : thisInstance.viatico.id_pais,
              id_viatico : thisInstance.viatico.nombramiento,
              facturas:thisInstance.facturas,
              fecha:thisInstance.filaDetalle.fecha,
            },
            beforeSend:function(){
              $('#loading').show();
            },
            success:function(data){
              //alert(data.msg);
              if(data.msg == 'OK'){
                Swal.fire({
                  type: 'success',
                  title: data.message,
                  showConfirmButton: false,
                  timer: 1100
                });
                thisInstance.getDiasComision();
                thisInstance.verificarFacturasFaltantes();
                // fin
                //alert(data.msg);
              }else{
                Swal.fire({
                  type: 'error',
                  title: data.message,
                  /*showConfirmButton: false,
                  timer: 1100*/
                });
              }
           }
         })
       }
          //fin
      })
      //fin
    },
    guardarFacturas: function(fecha, event){

      var formFacturasLiquidacion = $('#jsValidacionFacturasLiquidacion');
      var message = this.message;
      var thisInstance = this;
      //console.log(thisInstance.filaDetalle);
      //var tipoForm = this.tipo_formulario;
      // jQuery Validation
      // --------------------------------------------------------------------

      var confirmados = 0; this.facturas.map( item => { if(item.bln_confirma == true) { confirmados ++; } })
      console.log(confirmados);
      if(this.validarFacturasCant() == false){
        Swal.fire({
          type: 'error',
          title: 'Debe ingresar al menos una factura',
          showConfirmButton: false,
          timer: 1100
        });
        event.preventDefault();
      }else{
        //inicio
        //alert('ajsdlfkjañlksdjfñlkajsdf')
        jQuery('.jsValidacionFacturasLiquidacion').validate({
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
            //alert('Works!!!')
            Swal.fire({
              title: '<strong>¿Desea agregar esta información?</strong>',
              text: "",
              type: 'question',
              showCancelButton: true,
              confirmButtonColor: 'btn btn-success',
              cancelButtonText: 'Cancelar',
              confirmButtonText: '¡Si, Liquidar día!'
            }).then((result) => {
              if (result.value) {
                //inicio
                $.ajax({
                  url:'viaticos/php/back/viatico/liquidacion/liquidar_factura',
                  method:"POST",
                  dataType: 'json',
                  data:{
                    opcion:thisInstance.opcion,
                    id_persona: $('#id_persona').val(),
                    id_renglon: $('#id_renglon').val(),
                    id_pais : thisInstance.viatico.id_pais,
                    id_viatico : thisInstance.viatico.nombramiento,
                    facturas:thisInstance.facturas,
                    fecha:thisInstance.filaDetalle.fecha,
                    anotaciones_alimentacion:$('#anotaciones_alimentacion').val(),
                    anotaciones_hospedaje:$('#anotaciones_hospedaje').val(),
                  },
                  beforeSend:function(){
                    $('#loading').show();
                  },
                  success:function(data){
                    //alert(data.msg);
                    if(data.msg == 'OK'){
                      Swal.fire({
                        type: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1100
                      });
                      thisInstance.getDiasComision();
                      thisInstance.verificarFacturasFaltantes();


                      //thisInstance.evento.$emit('recargarEmpleadosList');

                      // inicion
                      if(thisInstance.opcion == 2){
                        //inicio
                        $.ajax({
                          url:'viaticos/php/back/viatico/liquidacion/actualizar_reintegros',
                          method:"POST",
                          dataType: 'json',
                          data:{
                            id_persona: $('#id_persona').val(),
                            id_renglon: $('#id_renglon').val(),

                            id_viatico : thisInstance.viatico.nombramiento,

                            id_reintegro_hospedaje:$('#id_reintegro_hospedaje').val(),
                            id_reintegro_alimentacion:$('#id_reintegro_alimentacion').val(),
                          },
                          beforeSend:function(){
                            $('#loading').show();
                          },
                          success:function(data){
                            //alert(data.msg);
                            if(data.msg == 'OK'){
                              Swal.fire({
                                type: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1100
                              });
                              //thisInstance.evento.$emit('recargarEmpleadosList');
                              //alert(data.msg);
                            }else{
                              Swal.fire({
                                type: 'error',
                                title: data.message,
                                //showConfirmButton: false,
                                //timer: 1100
                              });
                            }
                         }
                       })
                        //fin
                      }else if(thisInstance.opcion == 3){
                        //inicio
                        $.ajax({
                          url:'viaticos/php/back/viatico/liquidacion/actualizar_gastos',
                          method:"POST",
                          dataType: 'json',
                          data:{
                            id_persona: $('#id_persona').val(),
                            id_renglon: $('#id_renglon').val(),

                            id_viatico : thisInstance.viatico.nombramiento,


                          },
                          beforeSend:function(){
                            $('#loading').show();
                          },
                          success:function(data){
                            //alert(data.msg);
                            if(data.msg == 'OK'){
                              Swal.fire({
                                type: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1100
                              });
                              //thisInstance.evento.$emit('recargarEmpleadosList');
                              //alert(data.msg);
                            }else{
                              Swal.fire({
                                type: 'error',
                                title: data.message,
                                //showConfirmButton: false,
                                //timer: 1100
                              });
                            }
                         }
                       })
                        //fin
                      }
                      thisInstance.setOption(1);

                      // fin
                      //alert(data.msg);
                    }else{
                      Swal.fire({
                        type: 'error',
                        title: data.message,
                        /*showConfirmButton: false,
                        timer: 1100*/
                      });
                    }
                 }
               })
                //fin
              }
            })
          },
          rules: {
            'txtnit0': { remote: { url: 'viaticos/php/back/viatico/validarnitsaas.php', data: { nit: function(){ return $('#txtnit0').val();},id_pais: function(){ return $('#id_pais').val();} } } },
            'txtnit1': { remote: { url: 'viaticos/php/back/viatico/validarnitsaas.php', data: { nit: function(){ return $('#txtnit1').val();},id_pais: function(){ return $('#id_pais').val();} } } },
            'txtnit2': { remote: { url: 'viaticos/php/back/viatico/validarnitsaas.php', data: { nit: function(){ return $('#txtnit2').val();},id_pais: function(){ return $('#id_pais').val();} } } },
            'txtnit3': { remote: { url: 'viaticos/php/back/viatico/validarnitsaas.php', data: { nit: function(){ return $('#txtnit3').val();},id_pais: function(){ return $('#id_pais').val();} } } },

            'txtnumero0': {
              remote: {
                url: 'viaticos/php/back/viatico/validarfacturarepetida.php',
                data: {
                  nit0: function(){ return $('#txtnitt0').val();},
                  serie0: function(){ return $('#txtserie0').val();},
                  numero0: function(){ return $('#txtnumero0').val();},
                  id_pais: function(){ return $('#id_pais').val();}
                }
              }
            },
            'txtnumero1': {
              remote: {
                url: 'viaticos/php/back/viatico/validarfacturarepetida.php',
                data: {
                  nit1: function(){ return $('#txtnitt1').val();},
                  serie1: function(){ return $('#txtserie1').val();},
                  numero1: function(){ return $('#txtnumero1').val();},
                  id_pais: function(){ return $('#id_pais').val();}
                }
              }
            },
            'txtnumero2': {
              remote: {
                url: 'viaticos/php/back/viatico/validarfacturarepetida.php',
                data: {
                  nit2: function(){ return $('#txtnitt2').val();},
                  serie2: function(){ return $('#txtserie2').val();},
                  numero2: function(){ return $('#txtnumero2').val();},
                  id_pais: function(){ return $('#id_pais').val();}
                }
              }
            },
            'txtnumero3': {
              remote: {
                url: 'viaticos/php/back/viatico/validarfacturarepetida.php',
                data: {
                  nit3: function(){ return $('#txtnitt3').val();},
                  serie3: function(){ return $('#txtserie3').val();},
                  numero3: function(){ return $('#txtnumero3').val();},
                  id_pais: function(){ return $('#id_pais').val();}
                }
              }
            },
          },
          messages: {
            'txtnit0': { remote: 'Debe ingresar el nit de la SAAS'},
            'txtnit1': { remote: 'Debe ingresar el nit de la SAAS'},
            'txtnit2': { remote: 'Debe ingresar el nit de la SAAS'},
            'txtnit3': { remote: 'Debe ingresar el nit de la SAAS'},

            'txtnumero0': { remote: 'Esta factura ya fue ingresada.'},
            'txtnumero1': { remote: 'Esta factura ya fue ingresada.'},
            'txtnumero2': { remote: 'Esta factura ya fue ingresada.'},
            'txtnumero3': { remote: 'Esta factura ya fue ingresada.'},
          }
        });
        //fin
      }
    },
    saveProveedor: function(){
      Swal.fire({
        title: 'Agregar Hotel o Restaurante',
        html: `<input type="text" id="cod_proveedor" class="swal2-input" placeholder="Nit">
        <input type="text" id="nombre_proveedor" class="swal2-input" placeholder="Razón Social">`,
        confirmButtonColor: 'btn btn-success',
        confirmButtonText: '<i class="fa fa-check-circle"></i> Guardar',
        showCancelButton: true,

        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
          const cod_proveedor = Swal.getPopup().querySelector('#cod_proveedor').value
          const nombre_proveedor = Swal.getPopup().querySelector('#nombre_proveedor').value
          if (!cod_proveedor || !nombre_proveedor) {
            Swal.showValidationMessage(`Por favor ingrese nit y razón social.`)
          }
          return { cod_proveedor: cod_proveedor, nombre_proveedor: nombre_proveedor }
        }
      }).then((result) => {
        //inicio
        $.ajax({
          url:'viaticos/php/back/viatico/liquidacion/agregarProveedor.php',
          method:"POST",
          dataType: 'json',
          data: {
            cod_proveedor:result.value.cod_proveedor,
            nombre_proveedor:result.value.nombre_proveedor
          },
          beforeSend:function(){
            $('#loading').show();
          },
          success:function(data){
            //alert(data.msg);
            if(data.msg == 'OK'){
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
       })
        //fin
      })
    },
    guardarProveedor: function(){
      var formProveedor = $('#jsValidacionFacturasProveedor');
      var message = this.message;
      var thisInstance = this;
      //console.log(thisInstance.filaDetalle);
      //var tipoForm = this.tipo_formulario;
      // jQuery Validation
      // --------------------------------------------------------------------
      //inicio
      jQuery('#jsValidacionFacturasProveedor').validate({
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
          Swal.fire({
            title: '<strong>¿Desea agregar este lugar?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: 'btn btn-success',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, agregar!'
          }).then((result) => {
            if (result.value) {
              //inicio
              $.ajax({
                url:'viaticos/php/back/viatico/liquidacion/agregarProveedor.php',
                method:"POST",
                dataType: 'json',
                data: formProveedor.serialize(),
                beforeSend:function(){
                  $('#loading').show();
                },
                success:function(data){
                  //alert(data.msg);
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });

                    /*thisInstance.evento.$emit('recargarEmpleadosList');
                    thisInstance.evento.$emit('mostrarModal',false);
                    thisInstance.evento.$emit('recargarViatico');
                    thisInstance.getFormLiquidacion();*/
                    //thisInstance.getDiasComision();
                    //thisInstance.verificarFacturasFaltantes();
                    $('#cod_proveedor').val('');
                    $('#nombre_proveedor').val('');
                    thisInstance.getFacturasByDay(2, thisInstance.dDetalle, thisInstance.arrayFila);

                    //alert(data.msg);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
               }
             })
              //fin
            }
          })
        }
      });
    },
    liquidarNombramiento: function(){
      var formFacturasLiquidacion = $('#formValidacionLiquidarNombramiento');
      var message = this.message;
      var thisInstance = this;
      //console.log(thisInstance.filaDetalle);
      //var tipoForm = this.tipo_formulario;
      // jQuery Validation
      // --------------------------------------------------------------------
      //inicio
      jQuery('#formValidacionLiquidarNombramiento').validate({
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
          Swal.fire({
            title: '<strong>¿Desea liquidar el nombramiento de este empleado?</strong>',
            text: "",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: 'btn btn-success',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Liquidar el nombramiento!'
          }).then((result) => {
            if (result.value) {
              //inicio
              $.ajax({
                url:'viaticos/php/back/viatico/liquidacion/liquidar_nombramiento',
                method:"POST",
                dataType: 'json',
                data:{
                  id_persona: $('#id_persona').val(),
                  id_renglon: $('#id_renglon').val(),
                  id_pais : thisInstance.viatico.id_pais,
                  vt_nombramiento : thisInstance.viatico.nombramiento,
                  id_reintegro_hospedaje:$('#id_reintegro_hospedaje').val(),
                  id_reintegro_alimentacion:$('#id_reintegro_alimentacion').val(),
                  id_otros_gastos:$('#id_otros_gastos').val(),
                  id_fecha_liquidacion:$('#id_fecha_liquidacion').val(),
                },
                beforeSend:function(){
                  $('#loading').show();
                },
                success:function(data){
                  //alert(data.msg);
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });

                    thisInstance.evento.$emit('recargarEmpleadosList');
                    thisInstance.evento.$emit('mostrarModal',false);
                    thisInstance.evento.$emit('recargarViatico');
                    thisInstance.getFormLiquidacion();
                    //thisInstance.getDiasComision();
                    //thisInstance.verificarFacturasFaltantes();
                    thisInstance.setOption(1);

                    //alert(data.msg);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
               }
             })
              //fin
            }
          })
        }
      });
      //fin
    }
  }


}

</script>
<style>
.mainInput {
     /*width: 100%;
     border: none;
     border-bottom: 1px solid #474747;
     color: rgba(0, 0, 0, 0.4);
     background: transparent;
     font-size: 16px;
     padding-bottom: 1.25rem;
     outline: none;*/
   }
</style>
