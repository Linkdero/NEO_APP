<template>
  <div :class="columna">
    {{ titulo }}
    <div class="row">
      <div :class="ancho" v-for="a in asignaciones" style="margin-bottom: 25px">
        <div  class="card card-info cAsignacion" style="width:100%">
          <div class="card-header">
            <div class="row">
              <div class="col-sm-6">
                <dato-persona icono="fa fa-hashtag" texto="Correlativo:" :dato="a.correlativo" tipo="1"></dato-persona>
              </div>
              <div class="col-sm-6">
                <dato-persona icono="fa fa-calendar-check" texto="Fecha:" :dato="a.fecha" tipo="1"></dato-persona>
              </div>
            </div>


          </div>
          <div class="card-body bg-muted text-light">
            <div class="row" v-for="v in a.vehiculos">
              <div class="col-sm-1"><h3 :class="v.estado"></h3> </div>
              <div class="col-sm-3">{{ v.tipo_asignacion}} </div>
              <div class="col-sm-7">{{ v.marca }} - {{ v.linea }} <br> {{ v.modelo }} - {{ v.nro_placa }} <br> Fecha: {{ v.fecha_salida }}</div>
              <div class="col-sm-1" v-if="v.asignacion_status == 1" :class="v.estado_seguimiento"></div>
              <br>
              <!--span :class="v.estado">{{  }} {{  }} {{  }} {{ }}</span>-->
            </div>


          </div>
          <div class="card-footer bg-muted text-light">
            <div class="row" v-for="s in a.solicitudes">
              <div class="col-sm-4"><span v-html="s.solicitud_status_p"></span> </div>
              <div class="col-sm-8">{{ s.direccion }} </div>
              <br>
              <!--span :class="v.estado">{{  }} {{  }} {{  }} {{ }}</span>-->
            </div>


          </div>
          <div class="card-footer text-right">
            <span class="btn btn-sm btn-info"> Detalle</span>
          </div>
        </div>

      </div>
    </div>


  </div>
</template>
<style>
.cAsignacion {
  background: #fff;
  border: 1px solid #19B5FE;
	position: relative;
	z-index: 15;
	margin: 0 auto;
	box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
  transition: all 0.25s ease-in;
}

.cAsignacion:hover {
  border: 1px solid #6b15b6;
  -webkit-transform: translateY(-4px);
  -ms-transform: translateY(-4px);
  transform: translateY(-2px);
  -webkit-box-shadow: 10px 15px 12px rgba(31, 71, 136, 0.3);
  box-shadow: 10px 15px 12px rgba(31, 71, 136, 0.3);
  z-index: 5;
}

.bg-dark-A{
  background: #6b15b6;
  transition: all 0.25s ease-in;
}
.cAsignacion:hover .bg-dark-A{
  background: #1b1e24;
}

</style>
<script>
//const vehiculoslist = httpVueLoader('././VehiculosList.vue');
module.exports = {
  props:["columna","tipo","fase","evento","filtro","titulo","ancho"],
  data() {
    return {
      asignaciones:[]
    }
  },
  mounted(){

  },
  components:{
    //vehiculoslist
  },
  created: function(){
    this.getAsignacionesByEstado();
  },
  methods:{
    getAsignacionesByEstado: function(){
      //inicio
      axios.get('transportes/model/Asignaciones', {
        params: {
          opcion:2,
          tipo:0,
          year:2023,
          filtro:this.filtro
        }
      }).then(function (response) {
        this.asignaciones = response.data;
      }.bind(this)).catch(function (error) {
        console.log(error);
      });
      //fin
    }
  }
}
</script>
