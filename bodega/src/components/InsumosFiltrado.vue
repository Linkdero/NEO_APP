<template>
  <!--inicio-->
  <div :class="columna_global">
    <div class="row">
      <div :class="columna" v-if="tipo == 1">
        <div class="form-group">
          <div class="">
            <div class="">
              <label for="id_bodega">Bodega</label>
              <div class=" input-group  has-personalizado">
                <select class=" form-control form-control-sm" style="width:100%" id="id_bodega" name="id_bodega" required :disabled="insumos.length > 0" @change="setBodega()">
                  <option v-for="b in bodegasArray" :value="b.id_item">{{ b.item_string }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else-if="tipo == 2" class="col-sm-12">
        <br>
        <h3>Agregar insumos a la requisición existente</h3>
        <input :value="id_bodega" id="id_bodega_in" name="id_bodega_in" hidden></input>

      </div>
      <div :class="columna">
        <div class="form-group">
          <div class="">
            <div class="">
              <label :for="id_pro">Insumo</label>
              <div class=" input-group  has-personalizado">
                <select class="categoryInsumos form-control" style="width:100%" :id="id_pro" :name="id_pro" ></select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--inicio-->
      <div class="col-sm-2">
        <div class="form-group">
          <div class="form-material">
            <label>Agregar insumo</label>
            <span class="btn btn-sm btn-soft-info btn-block" @click="addNewRow()"><i class="fa fa-plus-circle"></i> Agregar</span>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- fin -->
</template>
<script>
module.exports = {
  props:["columna_global","columna","evento", "datos_tabla","tipo","id_bodega", "id_pro"],
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      habilitado: false,
      bodegasArray: [],
      insumos:[],
      cod_bodega:""
    }
  },
  methods:{
    getBodegasList: function(){
      axios.get('bodega/model/Requisicion', {
        params: {
          opcion:6,
          tipo:1
        }
      }).then(function (response) {
        this.bodegasArray = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    setBodega: function(){
      this.cod_bodega = $('#id_bodega').val();
    },
    insumosFilter: function(){
      var thisInstance = this;
      $('#'+thisInstance.id_pro).select2({
        placeholder: 'Selecciona un insumo',
        ajax: {
          url: 'bodega/model/InsumosFiltrado.php',
          dataType: 'json',
          delay: 250,
          data: function (params) {
      			return {
      				q: params.term, // search term
      				bodega_id: thisInstance.cod_bodega
      			};
      		},
          processResults: function (data) {
            thisInstance.habilitado = true;
            return {
              results: data
            };
          },
          cache: true
        }
      });
    },
    addNewRow: function(){
      //inicio}
      var thisInstance = this;
      if ($('#'+thisInstance.id_pro).val() != null) {
        axios.get('bodega/model/Requisicion', {
          params: {
            opcion:7,
            bodega_id: thisInstance.cod_bodega,//$('#id_bodega').val(),
            Pro_idi: $('#'+thisInstance.id_pro).val()
          }
        }).then(function (response) {

          if(response.data.msg == 'ERROR'){
            //inicio
            Swal.fire({
              type: 'error',
              title: response.data.message,
              showConfirmButton: true,
              //timer: 1100
            });
            //fin
          }else{
            //inicio

            if (thisInstance.insumos.find((item) => item.Pro_idint == response.data.Pro_idint)) {
              Swal.fire({
                type: 'error',
                title: 'El producto ya existe en la lista',
                showConfirmButton: false,
                timer: 1100
              });
            } else {
              thisInstance.totalLineas += parseInt(response.data.lineas);
              if(thisInstance.totalLineas > 108){
                thisInstance.totalLineas = thisInstance.totalLineas - parseInt(response.data.lineas);
                thisInstance.evento.$emit('recargarPorcentajeTotal',1);
                Swal.fire({
                  type: 'error',
                  title: 'El insumo que desea agregar no se agregó porque supera el espacio en el formulario electrónico',
                  showConfirmButton: true,
                  //timer: 1100
                });
              }else{
                if(thisInstance.insumos.length == 0){
                  $('#'+thisInstance.id_pro).val('');
                  $('#'+thisInstance.id_pro).val(null).trigger('change');
                  //thisInstance.insumos = response.data;
                  thisInstance.evento.$emit('getLines',response.data.lineas);
                  thisInstance.evento.$emit('recargarPorcentajeTotal',1);
                  var insumo = {
                    renglon:response.data.renglon,
                    Pro_idint: response.data.Pro_idint,
                    can_solicitada: '',
                    Ent_id: response.data.Ent_id,
                    Pro_des: response.data.Pro_des,
                    Med_id: response.data.Med_id,
                    Med_nom: response.data.Med_nom,
                    Bod_id: response.data.Bod_id,
                    lineas: response.data.lineas,
                  };

                  if(thisInstance.tipo == 1){
                    thisInstance.insumos.push(insumo);
                    thisInstance.evento.$emit('getInsumo', thisInstance.insumos);
                  }else if(thisInstance.tipo == 2){
                    thisInstance.evento.$emit('addInsumoListaExistente', insumo);
                  }
                }else{
                  console.log(thisInstance.insumos[0].renglon.charAt(0) + ' | - | '+ response.data.renglon.charAt(0));
                  if(thisInstance.insumos[0].renglon.charAt(0) == response.data.renglon.charAt(0) || response.data.renglon == 122 || response.data.renglon == 241 ){
                    //inicio
                    $('#'+thisInstance.id_pro).val('');
                    $('#'+thisInstance.id_pro).val(null).trigger('change');
                    //thisInstance.insumos = response.data;
                    thisInstance.evento.$emit('getLines',response.data.lineas);
                    thisInstance.evento.$emit('recargarPorcentajeTotal',1);
                    var insumo = {
                      renglon:response.data.renglon,
                      Pro_idint: response.data.Pro_idint,
                      can_solicitada: '',
                      Ent_id: response.data.Ent_id,
                      Pro_des: response.data.Pro_des,
                      Med_id: response.data.Med_id,
                      Med_nom: response.data.Med_nom,
                      Bod_id: response.data.Bod_id,
                      lineas: response.data.lineas,
                    };

                    if(thisInstance.tipo == 1){
                      thisInstance.insumos.push(insumo);
                      thisInstance.evento.$emit('getInsumo', thisInstance.insumos);
                    }else if(thisInstance.tipo == 2){
                      thisInstance.evento.$emit('addInsumoListaExistente', insumo);
                    }
                    //fin
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: 'El insumo que desea agregar pertenece a otro grupo.',
                      showConfirmButton: true,
                      //timer: 1100
                    });
                  }
                }



              }
            }
            //fin
          }
        }).catch(function (error) {
          console.log(error);
        });
      } else {
        Swal.fire({
          type: 'error',
          title: 'Debe seleccionar un insumo',
          showConfirmButton: false,
          timer: 1100
        });
      }
      //fin
    }
  },
  created: function(){
    this.getBodegasList();
    setTimeout(() => {
      this.insumosFilter();
      if(this.tipo == 1){
        this.cod_bodega = $('#id_bodega').val();
      }else{
        this.cod_bodega = $('#id_bodega_in').val();
      }
    }, 900);

    this.evento.$on('setEstadoBodega', (data) => {
      this.habilitado = data;
    });

  }
}
</script>
