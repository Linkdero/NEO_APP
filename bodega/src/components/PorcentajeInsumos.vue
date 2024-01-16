<template>
  <div class="text-left">
    Productos autorizados: {{ total }}
    <div style="margin-top:0px; ">
      <span class="badge-sm" :class="porcentaje.texto">Espacio utilizado :{{ porCiento }} %</span> - <strong>Muestra el porcentaje utilizado en el área de impresión disponible de la Requisición electrónica</strong>
      <div class="progress progress-striped skill-bar " style="height:6px">
        <div class="progress-bar animated" :class="bg" role="progressbar" :aria-valuenow="total" aria-valuemin="0" aria-valuemax="108" :style="{width: [percent]}"></div>
      </div>
    </div>
    <br>
  </div>
</template>
<script>
module.exports = {
  props:["total","evento"],
  mounted() {
    console.log('Component mounted.')
  },
  data(){
    return {
      porcentaje:"",
      tempo:10,
      progress: 0,
      completed: false,
      percent:"",
      bg: 'bg-info',
      valor:0,
      porCiento:0,
      totalCiento: 108
    }
  },
  methods:{
    getPorcentaje: function(){

      setTimeout(() => {
        var calculo = Math.round(((this.total) * 100 )/ 28);
        this.timer(this.tempo, calculo);
      }, 100);



    },
    timer: function(tempo, dato) {
      var vm = this;
    	var setIntervalRef = setInterval(function() {
        if(vm.progress < dato){
          vm.progress++;

        }else{
          vm.progress--;
        }
        vm.percent = vm.progress+"%";
        var calculo = Math.round(((dato) * 100 )/ 100);
        vm.porCiento = (calculo > 97.5) ? 100 : calculo;

        if(vm.progress == 108)    {
          this.bg = 'bg-success';
        }else{
          this.bg = 'bg-info';
        }

        if (vm.progress == dato) {
        	clearInterval(setIntervalRef);
        	vm.completed = true;
				}
      }, tempo);
    }
  },
  created: function(){
    this.getPorcentaje();

    this.evento.$on('recargarPorcentajeTotal', (opc) => {
      this.getPorcentaje();
    });
  }
}
</script>
