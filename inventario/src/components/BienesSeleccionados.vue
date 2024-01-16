<template>
  <div class="col-sm-12">
    <div class="form-group">
      <div class="">
        <div class="">
          <label :for="id">{{ label }}</label>
          <div class="input-group  has-personalizado" >
            <select :id="id" :name="id+'[]'" class="grupo_insumos js-select2 form-control form-control-sm input-sm" multiple required style="width:100%" v-on:change="getValues">
              <!--<option v-for="p in pedidos" :value="p.ped_tra">{{ p.ped_num  }}</option>-->
            </select>

          </div>
        </div>
      </div>
    </div>
    <span v-if="bienesList.length > 0" class="text-info ">Bienes seleccionados: {{ bienesList.length }} </span>
    <div v-if="bienesList.length > 0" style="max-height:300px; overflow-y:auto; overflow-x:hidden" class="card">
      <div class="row">
        <div v-for="b in bienesList" class="col-sm-12 ">
          <div class="card">
            <div class="row">
              <div class="col-sm-12">
                <span class="fa-regular fa-gear opcion-edit" style="margin-left:97.5%;" @click="editBien(2,b.bien_id)"></span>
                <h4>Bien Nro. <strong>{{ b.bien_sicoin_code }}</strong> - Renglón: <strong>{{ b.bien_renglon_id }}</strong></h4>
                <h6 style="text-align:justify" v-html="b.bien_descripcion"></h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="opc == 2">
        <div id="myModal" class="modal-vue" >

          <!-- Modal content -->
          <div class="modal-vue-content">
            <div class="card shadow-card">
              <header class="header-color">
                <h4 class="card-header-title" >
                  <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-user-plus">
                  </i><span class="text-white" v-if="opc == 2"> Detalle del Bien</span>
                </h4>
                <span style="margin-top:-2.2rem; margin-right:11rem"class="close-icon" @click="getOpc(1)">
                  <i class="fa fa-times"></i>
                </span>
              </header>
              <div class="card-body">
                <biendetalle :bien_id="bien_id" tipo="2" vista="bienesseleccionados" :evento="evento"></biendetalle>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>
<style>
.opcion-edit{
   cursor: pointer;
}
.opcion-edit:hover{
  animation-name: spin;
  animation-duration: 1000ms;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes spin {
    from {
        transform:rotate(0deg);
    }
    to {
        transform:rotate(360deg);
    }
}
</style>
<script>
const biendetalle = httpVueLoader('./BienDetalle.vue');
module.exports = {

  props:["id","label","arreglo","evento"],
  data: function(){
    return {
      bienes:[],
      bienesList:[],
      opc:1,
      bien_id:"",
      codigos:"",
      sicoin:[]
    }
  },
  components:{
  },
  components:{
    biendetalle
  },
  mounted(){
  },
  created: function(){
    this.filtrado();

    this.evento.$on('recargarBienesSeleccionados',() => {
      this.getBienesSeleccionados();
    })

    this.evento.$on('changeOpcionBienesSeleccionados', (data) => {
      this.opc = data;
    })
  },
  methods:{
    filtrado: function () {
      var thisInstance = this;
      setTimeout(() => {
        $('.grupo_insumos').select2({
          placeholder: 'Selecciona un bien',
          ajax: {
            url: 'inventario/model/Inventario.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                opcion:10
              };
            },
            processResults: function (data) {
              return {
                results: data
              };
            },
            cache: true
          }
        });

        $('.grupo_insumos').on('change', function (e) {
          var id = $(this).attr('id');
          //console.log(e.para);
          thisInstance.getBienesSeleccionados();
        });

      }, 1000);

      /*setTimeout(() => {
        var id = localStorage.getItem('codigos');
        var texto = JSON.stringify(localStorage.getItem('sicoin'));
        var $option = $("<option selected></option>").val(id).text(texto);
        $('.grupo_insumos').append($option).trigger();

      }, 2000);*/
    },
    getBienesSeleccionados: function(){
      axios.get('inventario/model/Inventario', {
        params: {
          opcion:11,
          bienes:JSON.stringify($('#'+this.id).val())
        }
      }).then(function (response) {
        //localStorage.setItem('bienes', JSON.stringify('#'+this.id).val()));
        if(response.data.length > 0){
          this.codigos = JSON.stringify($('#'+this.id).val());
          localStorage.bienes = this.codigos;

          /*var codes = $('#'+this.id).select2('data');
          this.sicoin = codes.map(x => x.text);
          localStorage.sicoin = JSON.stringify(this.sicoin);*/
          this.bienesList = response.data;
        }

        //alert('Works!!!');
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
    },
    getValues: function(){

    },
    getOpc: function(opc){
      this.opc = opc;
    },
    editBien: function(opc,bien_id){
      this.opc = opc;
      this.bien_id = bien_id;
      this.getBienesSeleccionados();
    }
  }
}

</script>
<style>
.emps_select{
  max-height:300px; overflow-y: auto; overflow-x: hidden
}
</style>
