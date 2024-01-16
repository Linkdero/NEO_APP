$(document).ready(function(){
  var source_h=[];


  const app_vue = new Vue({
    el: '#myapp',
    data: {
      message: 'Hello Vue!',
      viaticos: "",
      empleados:"",
      empleados_pro:"",
      empleado:"",
      calculos:"",
      departamentos:"",
      horas:"",
      horas_:"",
      transportes:"",
      empresas:"",
      total_price:0,
      estado_nombramiento:"",
      totales_liquidados:"",
      vt_nombramiento: $('#id_viatico').val(),
      id_persona:$('#id_persona').val(),
      id_pais:$('#id_pais').val(),
      confirma_place:0,
      mostrarConfirma:0,
      destinos:[{departamento:'',municipio:'',aldea:'',tipo:'',f_ini:'',f_fin:'',h_ini:'',h_fin:''}],
      departamentos:"",
      munis1:"",
      munis2:"",
      munis3:"",
      munis4:"",
      aldeas:"",
      tipo_muni:"",
      invoice_products: [{
            product_no: '',
            product_name: '',
            product_price: '',
            product_qty: '',
            line_total: 0
        }]

    },
    computed: {

    },
    created: function(){
      this.viatico_by_id(),
      //this.allEmpleados();
      this.Empleados_sustituyen();
      this.get_empleado_by_viatico(),
      this.get_hora_id(),
      this.get_transportes_id(),
      this.get_empresas_id(),
      this.empleados_para_procesar(),
      this.get_totales_liquidacion(),
      this.estado_viatico(),
      this.getLoadEditTable(),
      this.getLoadEditTableDestinos(),
      this.get_departamentos()

    },
    methods: {
      allEmpleados: function(){

        axios.get('viaticos/php/back/listados/get_empleados_asistieron.php',{
          params:{
            vt_nombramiento: this.vt_nombramiento
          }
        })
        .then(function (response) {
            app_vue.empleados = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      calcular_viaticos:function(){
        if(this.vt_nombramiento > 0){

          axios.get('viaticos/php/back/viatico/calcular_viaticos_by_nombramiento.php', {
              params: {
                  vt_nombramiento: this.vt_nombramiento
              }
          })
            .then(function (response) {
              app_vue.calculos = response.data;
              total=0;
              $.each(response.data,function(pos, elemento){
                total+=parseFloat(elemento.cuota_diaria);
              })
              $('#sub_total').text(total+'.00');
            })
            .catch(function (error) {
              console.log(error);
            });
        }
      },

      myFunctionOnLoad: function() {
          //console.log('call on load...');
          alert('prueba');
      },
      get_hora_id: function(){
        axios.get('viaticos/php/back/listados/get_horas.php', {
            params: {
              tipo:37
            }
        })
          .then(function (response) {
            app_vue.horas = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      get_horas_editable: function(){
        axios.get('viaticos/php/back/listados/get_horas_editable.php', {
            params: {
              tipo:37
            }
        })
          .then(function (response) {
            app_vue.horas_ = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      get_transportes_id: function(){
        axios.get('viaticos/php/back/listados/get_horas.php', {
            params: {
              tipo:35
            }
        })
          .then(function (response) {
            app_vue.transportes = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      get_empresas_id: function(){
        axios.get('viaticos/php/back/listados/get_horas.php', {
            params: {
              tipo:56
            }
        })
          .then(function (response) {
            app_vue.empresas = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      viatico_by_id: function(){
        if(this.vt_nombramiento > 0){

          axios.get('viaticos/php/back/viatico/ajaxfile.php', {
              params: {
                  vt_nombramiento: this.vt_nombramiento
              }
          })
            .then(function (response) {
              app_vue.viaticos = response.data;
            })
            .catch(function (error) {
              console.log(error);
            });
        }

      },
      get_empleado_by_viatico:function(){
        axios.get('viaticos/php/back/viatico/get_empleado_by_viatico.php', {
          params: {
            vt_nombramiento: this.vt_nombramiento,
            id_persona:this.id_persona
          }
        }).then(function (response) {
          app_vue.empleado = response.data;
        }).catch(function (error) {
          console.log(error);
        });
      },
      estado_viatico: function(){
        if(this.vt_nombramiento > 0){

          axios.get('viaticos/php/back/viatico/get_estado_nombramiento.php', {
              params: {
                  vt_nombramiento: this.vt_nombramiento
              }
          })
            .then(function (response) {
              app_vue.estado_nombramiento = response.data;
            })
            .catch(function (error) {
              console.log(error);
            });
        }

      },
      Empleados_sustituyen: function(){

        axios.get('viaticos/php/back/listados/get_empleados_sustituye.php',{
          params:{
            vt_nombramiento: this.vt_nombramiento
          }
        })
        .then(function (response) {
            app_vue.empleados = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      get_empleados: function(){
        axios.get('viaticos/php/back/listados/get_vdepto.php',{
          params:{
            id_pais:this.id_pais
          }
        })
        .then(function (response) {
            app_vue.departamentos = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      subtotal:function(id,valor,tipo){
        var subtotal = 0;
        var total_col2=0;
        if(tipo==1){
          total_col2 = parseFloat($('#sub_total').text());
          var total = 0;
          if ($('#ac'+id).is(':checked') ) {
            total_col2+=parseFloat(valor);
          }else{
            total_col2-=parseFloat(valor);
          }
        }else
        if(tipo==2){
          total_col2=0;
          this.calculos.forEach(function(c) {
            if(c.checked){
              total_col2+=parseFloat(c.cuota_diaria);
            }else{
              total_col2=0;
            }

          });
        }

        $('#sub_total').text(total_col2+'.00');
      },
      empleados_para_procesar: function(){

        axios.get('viaticos/php/back/viatico/get_empleados_para_procesar.php',{
          params:{
            id_persona: this.id_persona
          }
        })
        .then(function (response) {
            app_vue.empleados_pro = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      get_totales_liquidacion: function(){
        axios.get('viaticos/php/back/viatico/get_total_liquidado.php',{
          params:{
            vt_nombramiento: this.vt_nombramiento
          }
        })
        .then(function (response) {
            app_vue.totales_liquidados = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      toggleSelect: function() {
        var select = this.selectAll;
        this.calculos.forEach(function(c) {
          c.checked = !select;
          app_vue.subtotal(c.id, c.cuota_diaria,2);
        });
        this.selectAll = !select;
      },
      selectAll: function() {


      },
      getLoadEditTable: function() {
        setTimeout(() => {
          $('.f_fecha').editable({
            url: 'viaticos/php/back/viatico/update_fecha_general.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
                app_vue.viatico_by_id();
                app_vue.calcular_viaticos();
              }
            }
          });
          $('.horas_').editable({
            url: 'viaticos/php/back/viatico/update_hora_general.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'select',
            source:source2,
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
                app_vue.viatico_by_id();
                app_vue.calcular_viaticos();

              }
            }
          });

          $('.motivo_').editable({
            url: 'viaticos/php/back/viatico/update_motivo.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'textarea',
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);

              }
            }
          });

        }, 400);

      },
      validaciondConfirmacionPlace: function(event){
        this.confirma_place=event.currentTarget.value;
      },
      validacionMostrarConfirma: function(){
        if( $('#chk_confirma').is(':checked') )
        {
          this.mostrarConfirma=1;
        }else{
          this.mostrarConfirma=0;
          this.confirma_place=0;

        }
      },
      /*addRow: function() {
        this.destinos.push({departamento:'',municipio:'',aldea:'',tipo:'',f_ini:'',f_fin:'',h_ini:'',h_fin:''});
        app_vue.getLoadEditTableDestinos();
        app_vue.get_departamentos();
      },
      deleteRow(index, d){
        this.destinos.splice(index,1);
        console.log(d);
        var idx = this.destinos.indexOf(d);
            console.log(idx, index);
            if (idx > -1) {
                this.destinos.splice(idx, 1);
            }
      },*/
      deleteRow(index, d) {
            var idx = this.destinos.indexOf(d);
            console.log(idx, index);
            if (idx > -1) {
                this.destinos.splice(idx, 1);
            }
            //this.calculateTotal();
        },
        addNewRow() {
          //alert(this.destinos.length);
          console.log(this.destinos.length);
          if(this.destinos.length<=3){
            this.destinos.push({departamento:'',municipio:'',aldea:'',tipo:'',f_ini:'',f_fin:'',h_ini:'',h_fin:''});
            app_vue.getLoadEditTableDestinos();
            app_vue.get_departamentos();
          }else{
            console.log('Arreglo lleno');
          }

        },
      get_departamentos: function(event){
        //
        axios.get('viaticos/php/back/listados/destinos/get_departamento.php',{
          params:{
            pais:'GT'
          }
        })
        .then(function (response) {
            app_vue.departamentos = response.data;

        })
        .catch(function (error) {
            console.log(error);
        });
      },
      get_municipios: function(event,index){
        this.tipo_muni=index;
        valor=event.currentTarget.value;
        if(index==0){
          axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
            params:{
              departamento:valor
            }
          })
          .then(function (response) {
            app_vue.munis1 = response.data;
          })
          .catch(function (error) {
              console.log(error);
          });

        }
        if(index==1){
          axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
            params:{
              departamento:valor
            }
          })
          .then(function (response) {
            app_vue.munis2 = response.data;
          })
          .catch(function (error) {
              console.log(error);
          });
        }
        if(index==2){
          axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
            params:{
              departamento:valor
            }
          })
          .then(function (response) {
            app_vue.munis3 = response.data;
          })
          .catch(function (error) {
              console.log(error);
          });
        }
        if(index==3){
          axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
            params:{
              departamento:valor
            }
          })
          .then(function (response) {
            app_vue.munis4 = response.data;
          })
          .catch(function (error) {
              console.log(error);
          });
        }
        console.log(this.tipo_muni);



      },
      getLoadEditTableDestinos: function() {
        setTimeout(() => {
          $('.f_fecha_d').editable({
            //url: 'viaticos/php/back/viatico/update_fecha_table.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            format: 'dd-mm-yyyy',
            viewformat: 'dd-mm-yyyy',
            datepicker: {
              weekStart: 1
            },
            type: 'date',
            /*display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              /*if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
              }
            }*/
          });
          $('.horas_d').editable({
            url: 'viaticos/php/back/viatico/update_hora_table.php',
            mode: 'popup',
            ajaxOptions: { dataType: 'json' },
            type: 'select',
            source:source2,/*
            display: function(value, response) {
              return false;   //disable this method
            },
            success: function(response, newValue) {
              if(response.msg=='Done'){
                $(this).text(response.valor_nuevo);
                app_vue.viatico_by_id();
                app_vue.calcular_viaticos();

              }
            }*/
          });


        }, 400);

      }


    }
  })

  function get_data(){
    app_vue.$refs.viatico_by_id();
  }

  var source2=[
    {value: "", text: "- Seleccionar -"},
    {value: "946", text: "00:00 HORAS"},
    {value: "947", text: "00:30 HORAS"},
    {value: "948", text: "01:00 HORAS"},
    {value: "949", text: "01:30 HORAS"},
    {value: "950", text: "02:00 HORAS"},
    {value: "951", text: "02:30 HORAS"},
    {value: "952", text: "03:00 HORAS"},
    {value: "954", text: "04:00 HORAS"},
    {value: "953", text: "03:30 HORAS"},
    {value: "955", text: "04:30 HORAS"},
    {value: "956", text: "05:00 HORAS"},
    {value: "957", text: "05:30 HORAS"},
    {value: "958", text: "06:00 HORAS"},
    {value: "959", text: "06:30 HORAS"},
    {value: "960", text: "07:00 HORAS"},
    {value: "961", text: "07:30 HORAS"},
    {value: "962", text: "08:00 HORAS"},
    {value: "963", text: "08:30 HORAS"},
    {value: "964", text: "09:00 HORAS"},
    {value: "965", text: "09:30 HORAS"},
    {value: "966", text: "10:00 HORAS"},
    {value: "967", text: "10:30 HORAS"},
    {value: "968", text: "11:00 HORAS"},
    {value: "969", text: "11:30 HORAS"},
    {value: "970", text: "12:00 HORAS"},
    {value: "971", text: "12:30 HORAS"},
    {value: "972", text: "13:00 HORAS"},
    {value: "973", text: "13:30 HORAS"},
    {value: "974", text: "14:00 HORAS"},
    {value: "975", text: "14:30 HORAS"},
    {value: "976", text: "15:00 HORAS"},
    {value: "977", text: "15:30 HORAS"},
    {value: "978", text: "16:00 HORAS"},
    {value: "979", text: "16:30 HORAS"},
    {value: "980", text: "17:00 HORAS"},
    {value: "981", text: "17:30 HORAS"},
    {value: "982", text: "18:00 HORAS"},
    {value: "983", text: "18:30 HORAS"},
    {value: "984", text: "19:00 HORAS"},
    {value: "985", text: "19:30 HORAS"},
    {value: "986", text: "20:00 HORAS"},
    {value: "987", text: "20:30 HORAS"},
    {value: "988", text: "21:00 HORAS"},
    {value: "989", text: "21:30 HORAS"},
    {value: "990", text: "22:00 HORAS"},
    {value: "991", text: "22:30 HORAS"},
    {value: "992", text: "23:00 HORAS"},
    {value: "993", text: "23:30 HORAS"}
  ];

})
