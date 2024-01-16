$(document).ready(function(){
  var instanciE;

  var viewModelEmpleados = new Vue({
    el:'#appEmpleados',
    data:{
      tableEmpleados:""

    },
    computed:{

    },
    created: function(){

    },
    methods: {
      getEmpleados: function(){

      }
    }
  })

  instanciE = viewModelEmpleados;
})
