<template>
  <!--inicio-->
  <!--inicio tabla-->
  <div class="col-sm-12">
    <label for="">Insumos*</label>
    <!--Bodega::::{{ bodega }} --- --estado:::: {{ req.requisicionStatus }} ---- crud::: {{ crud }}
    <!--Insumos agregados: {{ insumos.length }}-->
    <!--requisicion::: {{ req }} <br>
    permisooo:::: {{ permisoedicion }}-->
    <porcentajeinsumos :total="linesTotal" :evento="evento"></porcentajeinsumos>
    <table class="table table-sm table-bordered table-striped">
      <thead>
        <th class="text-center">Renglón</th>
        <th class="text-center">Cod.</th>
        <th class="text-center">Descripción</th>
        <th class="text-center">Medida</th>
        <th class="text-center">Solicitado</th>
        <td class="text-center" v-if="(req.requisicionStatus == 3 || req.requisicionStatus == 4) && crud == 'u'">Autorizado</td>
        <td class="text-center" v-if="(req.requisicionStatus == 3 || req.requisicionStatus == 4) && crud == 'u'">Despachado</td>
        <th class="text-center" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 2 || req.requisicionStatus == 7 ) && crud == 'u' && permisoedicion == true  || (req.requisicionStatus == 9 && crud == 'u' && permisoedicion == true && req.requisicionBodegaId == '01')">Existencias</th>
        <th class="text-center" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 2 || req.requisicionStatus == 7 ) && crud == 'u' && permisoedicion == true  || (req.requisicionStatus == 9 && crud == 'u' && permisoedicion == true && req.requisicionBodegaId == '01')">Apartado</th>
        <th class="text-center" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 2 || req.requisicionStatus == 7 ) && crud == 'u' && permisoedicion == true  || (req.requisicionStatus == 9 && crud == 'u' && permisoedicion == true && req.requisicionBodegaId == '01')">Disponibilidad</th>
        <th class="text-center" v-if="(crud == 'u' && permisoedicion == true && req.requisicionStatus != 8) || req.requisicionStatus == 9">Permitido</th>
        <th class="text-center" width="120px" v-if="crud == 'c'">Acción <span class="btn btn-sm btn-danger" @click="limpiarLista()"><i class="fa fa-trash-alt"></i></span></th>
      </thead>
      <tbody>

        <tr v-for='(i, index) in insumos' :key="index">
          <td class="text-center">{{ i.renglon }}</td>
          <td class="text-center">{{ i.Pro_idint }}</td>
          <td class="text-center">{{ i.Pro_des }}</td>
          <td class="text-justify">{{ i.Med_nom }} </td>
          <td class="text-center" v-if="crud == 'u'"><h3><strong class="text-info cant_solicitada editar_cantidad" :data-id="requisicion_id" :data-pk="i.requisicion_id" :data-name="i.Pro_idint">{{ i.cantidadSolicitada }}</strong></h3></td>
          <td class="text-center" v-if="(req.requisicionStatus == 3 || req.requisicionStatus == 4) && crud == 'u'">{{ i.cantidadAutorizada }}</td>
          <td class="text-center" v-if="(req.requisicionStatus == 3 || req.requisicionStatus == 4) && crud == 'u'">{{ i.cantidadDespachada }}</td>
          <td class="text-center" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 2 || req.requisicionStatus == 7 ) && crud == 'u' && permisoedicion == true || (req.requisicionStatus == 9 && crud == 'u' && permisoedicion == true && req.requisicionBodegaId == '01')">{{ i.existencia }}</td>
          <td class="text-center" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 2 || req.requisicionStatus == 7 ) && crud == 'u' && permisoedicion == true || (req.requisicionStatus == 9 && crud == 'u' && permisoedicion == true && req.requisicionBodegaId == '01')">{{ i.cantidad_apartada }}</td>
          <td class="text-center" v-if="(req.requisicionStatus == 1 || req.requisicionStatus == 2 || req.requisicionStatus == 7 ) && crud == 'u' && permisoedicion == true || (req.requisicionStatus == 9 && crud == 'u' && permisoedicion == true && req.requisicionBodegaId == '01')"><h3><strong class="text-info">{{ i.cantidad_disponible }}</strong></h3></td>
          <td width="120px" v-if="crud == 'c'" >
            <div class="form-group" style="margin-bottom:0rem">
              <div class="">
                <div class="">
                  <input :name="'i'+index" :id="'i'+index" class="form-control input-sm" v-model="i.can_solicitada" type="number" min="1" autocomplete="off" required></input>
                </div>
              </div>
            </div>
          </td>
          <td width="120px" v-if="req.requisicionStatus == 2 && crud == 'u' && permisoedicion == true" >
            <div class="form-group" style="margin-bottom:0rem" :class="'input'+index">
              <div class="">
                <div class="" v-if="req.requisicionBodegaId == '01' || req.requisicionBodegaId == '04'">
                  <input :disabled="i.checked == false" :name="'iau'+index" :id="'iau'+index" class="form-control input-sm" v-model="i.cant_au" type="number" min="1" autocomplete="off" :required="i.checked == true" ></input>
                </div>
                <div class="" v-else>
                  <input disabled :name="'iau'+index" :id="'iau'+index" class="form-control input-sm" v-model="i.cant_au" type="number" min="1" autocomplete="off" :required="i.checked == true" ></input>
                </div>
              </div>
            </div>
          </td>
          <td class="text-center" v-if="req.requisicionStatus == 2 && crud == 'u' && permisoedicion == true"><span :id="'st'+index" v-model="i.cant_autorizada_dsp">{{ i.cant_autorizada_dsp }}</span></td>
          <td width="120px" v-if="(crud == 'u' && permisoedicion == true && req.requisicionStatus != 8) || ((crud == 'u' && req.requisicionStatus != 8) && (req.requisicionStatus == 9 || req.requisicionStatus == 3 || req.requisicionStatus == 4))">
            <div class="form-group" style="margin-bottom:0rem">
              <div class="">
                <div class="">
                  <input :disabled="(req.requisicionBodegaId == '09' || req.requisicionBodegaId == '06' || req.requisicionBodegaId == '10' || req.requisicionBodegaId == '11' || req.requisicionStatus == 1 || req.requisicionStatus == 3 || req.requisicionStatus == 4 || req.requisicionStatus == 5 || req.requisicionStatus == 9)" class="tgl tgl-flip text-center" :id="i.id_chk_req" :name="i.id_chk_req" type="checkbox" @change="reloadPorcentaje(index, i.checked)"  v-model="i.checked" checked/>
                  <label style="margin-left:auto; margin-right:auto" class="tgl-btn text-center" data-tg-on="SI" data-tg-off="No" :for="i.id_chk_req" ></label>
                </div>
              </div>
            </div>
          </td>

          <td scope="row" class="trashIconContainer text-center" v-if="( req.requisicionStatus == 8) || crud == 'c'">
            <span class="btn btn-sm btn-danger" @click="deleteRow(index, i, i.lineas)"><i class="far fa-trash-alt"></i></span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- fin tabla -->
  <!-- fin -->
</template>
<script>
const porcentajeinsumos = httpVueLoader('././PorcentajeInsumos.vue');
module.exports = {
  props:["columna_global","columna","evento","tipo","crud", "requisicion_id","fase","req", "bodega","id_bodega","permisoedicion"],
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      insumos:[],
      totalLineas:0
    }
  },
  watch: {
    'insumos': {
      handler (newValue, oldValue) {
        if(this.req.requisicionBodegaId == '01' || this.req.requisicionBodegaId == '04'){
          newValue.forEach((item) => {
            if(parseFloat(item.cant_au) <= parseFloat(item.cantidad_disponible) || item.Pro_idint == 765){
              if(parseFloat(item.cantidadSolicitada) >= parseFloat(item.cant_au) ){
                item.cant_autorizada_dsp = (item.cant_au != '') ? parseFloat(item.cant_au) : '';
              }else{
                item.cant_autorizada_dsp = 'ERROR';
              }
            }else{
              item.cant_autorizada_dsp = 'ERROR';
            }

            if(item.checked == false){
              item.cant_au = '';
            }
          })
        }else{
          newValue.forEach((item) => {
            item.cant_autorizada_dsp = '';
            item.cant_au = item.cantidadSolicitada;
          })
        }

      },
      deep: true
    }
  },
  computed: {
    linesTotal: function() {
      if(this.crud == "c"){
        if (!this.insumos) {
          return 0;
        }
        return this.insumos.reduce(function (total, value) {
          //return total + Number(value.lineas);
          return total + 1;
        }, 0);
      }else if(this.crud == "u"){
        if (!this.insumos) {
          return 0;
        }
        return this.insumos.reduce(function (total, value) {
          var totally;
          if(value.checked == true){
            //total = total + Number(value.lineas);
            total = total + 1;
          }
          return total;
        }, 0);
      }

    }
  },
  components:{
    porcentajeinsumos
  },
  methods:{
    deleteRow: function(index, d, lineas){
      if(this.crud == 'c'){
        var idx = this.insumos.indexOf(d);
        if (idx > -1) {
          //eventBusPE.$emit('recargarPorcentajeTotal',1);
          this.totalLineas = this.totalLineas - lineas;
          this.insumos.splice(idx, 1);
          this.evento.$emit('recargarPorcentajeTotal',1);
          this.$emit('send_insumos_to_parent', this.insumos)
        }
      }else{
        //inicio
        console.log(d);
        if(this.insumos.length > 1){
          var thisInstance = this;
          Swal.fire({
            title: '<strong>¿Quiere eliminar este insumo de la Requisición ?</strong>',
            text: "Ingrese la cantidad.",
            type: 'question',
            inputPlaceholder: 'Eliminar',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, eliminar!',
          }).then((result) => {
            //alert('2222');
            if (result.value) {
              //alert('alkdjflaksdf');
              $.ajax({
                type: "POST",
                url: 'bodega/model/Requisicion',
                dataType: 'json',
                data: {
                  opcion:17,
                  requisicion_id:thisInstance.requisicion_id,
                  Pro_idint:d.Pro_idint,
                  //id_persona:$('#id_empleados_list').val()
                }, //f de fecha y u de estado.

                beforeSend:function(){
                  //alert('p: '+punto);
                },
                success:function(data){
                  if(data.msg == 'OK'){
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    thisInstance.getInsumosRequiscion();
                    thisInstance.evento.$emit('recargarPorcentajeTotal',1);
                    thisInstance.$emit('send_insumos_to_parent', this.insumos);
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: data.message,
                      showConfirmButton: true,
                      //timer: 1100
                    });
                  }

                }
              }).done( function() {

              }).fail( function( jqXHR, textSttus, errorThrown){
                alert(errorThrown);
              });
            }
          })
        }else{
          //inicio
          Swal.fire({
            type: 'error',
            title: 'No puede eliminar este Insumo, porque la requisición debe contener al menos un insumo',
            showConfirmButton: true,
            //timer: 1100
          });
          //fin
        }
        //fin
      }

    },
    limpiarLista: function(){
      this.insumos.splice(0, this.insumos.length);
      this.totalLineas = 0;
      this.evento.$emit('recargarPorcentajeTotal',1);
      this.$emit('send_insumos_to_parent', this.insumos)
    },
    getInsumosRequiscion: function(){
      //inicio
      axios.get('bodega/model/Requisicion.php',{
        params:{
          opcion:3,
          tipo:1,
          fase:this.fase,
          requisicion_id:this.requisicion_id
        }
      })
      .then(function (response) {
        this.insumos = response.data
      }.bind(this))
      .catch(function (error) {
          console.log(error);
      });
      //fin
    },
    reloadPorcentaje: function(index,bln){
      this.evento.$emit('recargarPorcentajeTotal',1);
      if(bln == false){
        $('.input'+index).removeClass('has-error');
        $('#iau'+index+'-error').hide();
      }
    },
    addNewInsumoListaExistente(insumo) {
      var thisInstance = this;
      Swal.fire({
        title: '<strong>¿Quiere agregar este insumo a la Requisición ?</strong>',
        text: "Ingrese la cantidad.",
        type: 'question',
        input: 'number',
        inputPlaceholder: 'Agregar',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Agregar!',
        inputValidator: function (inputValue) {
          return new Promise(function (resolve, reject) {
            if (inputValue && inputValue.length > 0) {
              resolve();
              punto = inputValue;
            } else {
              Swal.fire({
                type: 'error',
                title: 'Debe ingresar la cantidad',
                showConfirmButton: false,
                timer: 1100
              });

            }
          });
        }
      }).then((result) => {
        //alert('2222');
        if (result.value) {
          //alert('alkdjflaksdf');
          $.ajax({
            type: "POST",
            url: 'bodega/model/Requisicion',
            dataType: 'json',
            data: {
              opcion:16,
              requisicion_id:thisInstance.requisicion_id,
              Pro_idint:insumo.Pro_idint,
              cantidad:punto
              //id_persona:$('#id_empleados_list').val()
            }, //f de fecha y u de estado.

            beforeSend:function(){
              //alert('p: '+punto);
            },
            success:function(data){
              if(data.msg == 'OK'){
                Swal.fire({
                  type: 'success',
                  title: data.message,
                  showConfirmButton: false,
                  timer: 1100
                });
                thisInstance.getInsumosRequiscion();
                thisInstance.evento.$emit('recargarPorcentajeTotal',1);
                thisInstance.$emit('send_insumos_to_parent', this.insumos)
              }else{
                Swal.fire({
                  type: 'error',
                  title: data.message,
                  showConfirmButton: true,
                  //timer: 1100
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
    setEditable: function(){
      //inicio
      setTimeout(() => {
        if(this.req.requisicionStatus == 9){
          $('.editar_cantidad').editable({
            url: 'bodega/model/updateCantRequisicion',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'number',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
              }else if(response.msg == 'Error'){
                Swal.fire({
                  type: 'error',
                  title: response.message,
                  showConfirmButton: true,
                  //timer: 1100
                });
              }
            }
          });
        }else{
          $('.editar_cantidad').editable('option', 'disabled', true);
        }
      }, 900);
      //fin
    }
  },
  created: function(){
    this.setEditable();
    if(this.crud == 'u'){
      this.getInsumosRequiscion();
    }
    this.evento.$on('getLines', (data) =>{
      this.totalLineas = data;
    });
    this.evento.$on('getInsumo', (data) =>{
      this.insumos = data;

    });

    this.evento.$on('recargarInsumos', (data) => {
      this.getInsumosRequiscion();
    });

    this.evento.$on('setEditableFase', ()=>{
      this.setEditable();
    })

    this.evento.$on('addInsumoListaExistente', (data) => {
      if (this.insumos.find((item) => item.Pro_idint == data.Pro_idint)) {
        Swal.fire({
          type: 'error',
          title: 'El producto ya existe en la lista',
          showConfirmButton: false,
          timer: 1100
        });
      } else {
        this.addNewInsumoListaExistente(data);
        //this.insumos.push(data);
      }

    });

    this.evento.$on('sendInsumosToParent', () => {
      this.$emit('send_insumos_to_parent', this.insumos);
    })

    this.evento.$on('cleanListInsumos'), () => {
      this.limpiarLista()
    }

  }
}
</script>
