var instanciaD;
//$(document).ready(function(){

  var viewModelViaticoDetalle = new Vue({
    el:"#appViaticoDetalle",
    data:{
      vt_nombramiento:$('#id_viatico').val(),
      id_persona:$('#id_persona').val(),
      id_pais:$('#id_pais').val(),
      confirma_place:0,
      mostrarConfirma:0,
      destinos:[{departamento:'',municipio:'',aldea:'',tipo:'',f_ini:'',f_fin:'',h_ini:'',h_fin:''}],
      departamentos:"",
      munis0:"",
      munis1:"",
      munis2:"",
      munis3:"",
      munis4:"",
      aldeas:"",
      tipo_muni:"",
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
      opcion:"",
      tableEmpleados:"",
      codigos:"",
      destinoModificado:false,
      cambio:0,
      tipoS:0,
      tipoL:0,
      arrayA:"",
      uniqs:[{id_persona:'',tipo:''}],
      isLoaded:false,
      emitirViatico: 0,
      privilegio:""
    },
    computed:{
    },
    created: function(){
      this.uniqs.splice(0, 1);
      this.getOpcion(1);
      this.viatico_by_id();
      this.getPrivilegio();
      //this.allEmpleados();
      setTimeout(() => {

        this.get_hora_id();
        this.get_transportes_id();
        this.get_empresas_id();

        this.get_totales_liquidacion();
        this.estado_viatico();
        this.getLoadEditTable();
        this.getLoadEditTableDestinos();
        this.get_departamentos();
        this.changeCheck();
      }, 1000);

      setTimeout(() => {
        this.Empleados_sustituyen();
        this.get_empleado_by_viatico();
      }, 2500);

    },
    methods: {
      getOpcion: function(opc){
        this.opcion=opc;
      },
      //inicio
      allEmpleados: function(){

        axios.get('viaticos/php/back/listados/get_empleados_asistieron.php',{
          params:{
            vt_nombramiento: this.vt_nombramiento
          }
        })
        .then(function (response) {
            viewModelViaticoDetalle.empleados = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      getPrivilegio: function(){
        axios.get('viaticos/php/back/viatico/evaluar_privilegio', {
        }).then(function (response) {
          this.privilegio  = response.data;
        }.bind(this)).catch(function (error) {
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
              viewModelViaticoDetalle.calculos = response.data;
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
            viewModelViaticoDetalle.horas = response.data;
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
            viewModelViaticoDetalle.horas_ = response.data;
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
            viewModelViaticoDetalle.transportes = response.data;
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
            viewModelViaticoDetalle.empresas = response.data;
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
              viewModelViaticoDetalle.viaticos = response.data;
              //viewModelViaticoDetalle.isLoaded = true;

              setTimeout(() => {
                if(response.data.emitir_cheque == 3){
                  viewModelViaticoDetalle.emitirViatico = 1;
                }
                else{
                  viewModelViaticoDetalle.emitirViatico = response.data.emitir_cheque;
                }
                //if(response.data.vt_nombramiento == viewModelViaticoDetalle.vt_nombramiento){
                  viewModelViaticoDetalle.isLoaded = true;
                //}
              }, 300);

              //viewModelViaticoDetalle.destinoModificado = (response.data.confirma_lugar == 1) ? true : false;
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
          viewModelViaticoDetalle.empleado = response.data;
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
              viewModelViaticoDetalle.estado_nombramiento = response.data;
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
            viewModelViaticoDetalle.empleados = response.data;
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
            viewModelViaticoDetalle.departamentos = response.data;
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
            id_persona: $('#id_persona').val()
          }
        })
        .then(function (response) {
            viewModelViaticoDetalle.empleados_pro = response.data;
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
            viewModelViaticoDetalle.totales_liquidados = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      toggleSelect: function() {
        var select = this.selectAll;
        this.calculos.forEach(function(c) {
          c.checked = !select;
          viewModelViaticoDetalle.subtotal(c.id, c.cuota_diaria,2);
        });
        this.selectAll = !select;
      },
      selectAll: function() {


      },
      iComplete: function(){
        //alert('Recargando');
        viewModelViaticoDetalle.tableEmpleados.cells(
          viewModelViaticoDetalle.tableEmpleados.rows(function(idx, data, node){
            //alert(idx);
            return (data.bln_confirma == 0 || data.liquidado == 0) ? true : false;
            //alert(data.bln_confirma)
          }).indexes(),15).checkboxes.disable();
      },
      getTableEmpleados: function(){
        this.tableEmpleados= $('#tb_empleados_por_nombramiento').DataTable({
          'initComplete': function(settings, json){
            //alert('message1');
            //var api = new $.fn.dataTable.Api(settings);
            //alert('cargando');
            viewModelViaticoDetalle.tableEmpleados.cells(
              viewModelViaticoDetalle.tableEmpleados.rows(function(idx, data, node){
                //alert(idx);
                return (data.bln_confirma == 0 || data.liquidado == 0) ? true : false;
                //alert(data.bln_confirma)
              }).indexes(),15).checkboxes.disable();
          },
          //dom:
          //"<'row'<'col-sm-12'f>>",
          //'initComplete': viewModelViaticoDetalle.iComplete(settings, json),
          dom:
          "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-6'i><'col-sm-6'p>>",
          "ordering": false,
          "pageLength": 25,
          "bLengthChange":true,
          "bProcessing": true,
          select:true,
          //"paging": false,
          "ordering": false,
          "info":     true,
          orderCellsTop: true,
          responsive: true,


          //"dom": '<"">frtip',


          language: {
              emptyTable: "No hay solicitudes",
              sProcessing: " <h3 class=''><i class='fa fa-sync fa-spin'></i> Cargando la información, por favor espere</h3> "
          },
          "ajax":{
                url :"viaticos/php/back/listados/get_empleados_por_viatico.php",
                type: "POST",
                data:{
                  vt_nombramiento:function() { return $('#id_viatico').val() }
                },
                error: function(){
                  $("#post_list_processing").css("display","none");
                }
            },

               "aoColumns": [
                 //{ "class" : "text-center", mData: 'id_persona', "width":"2%"},
                 { "class" : "text-center details-control sin_contorno", mData: 'codigo'},
                 { "class" : "text-center ", mData: 'id_persona'},
                 { "class" : "text-left", mData: 'empleado'},
                 { "class" : "text-center", mData: 'va'},
                 { "class" : "text-center", mData: 'vc'},
                 { "class" : "text-center", mData: 'vl'},
                 { "class" : "text-right", mData: 'p_p'},
                 { "class" : "text-right", mData: 'p_r'},
                 { "class" : "text-right", mData: 'm_p'},
                 { "class" : "text-right", mData: 'm_r'},
                 { "class" : "text-right", mData: 'reintegro_'},
                 { "class" : "text-right", mData: 'reintegro'},
                 { "class" : "text-right", mData: 'complemento'},
                 { "class" : "text-center", mData: 'estado'},
                 //{ "class" : "text-center", mData: 'cheque'},
                 { "class" : "text-center", mData: 'accion'},
                 { "class" : "text-center ", mData: 'id_persona'}
               ],
              buttons: [
                {
                  extend: 'excel',
                  text: 'Excel <i class="fa fa-download"></i>',
                  className: 'btn btn-soft-info',
                  exportOptions: {
                    columns: [1,2,8]
                  }
                },
                {
                  text: 'Recargar <i class="fa fa-sync"></i>',
                  className: 'btn btn-soft-info',
                  action: function ( e, dt, node, config ) {
                    viewModelViaticoDetalle.recargarTableEmps();
                  }
                },/*,
                {
                  extend: 'print',
                  text: 'Montos <i class="fa fa-print"></i>',
                  className: 'btn btn-soft-info',
                  exportOptions: {
                    columns: [1,2,8]
                  },
                  customize: function ( win ) {
                      $(win.document.body)
                          .css( 'font-size', '10pt' )
                          .prepend(
                              '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                          );

                      $(win.document.body).find( 'table' )
                          .addClass( 'compact' )
                          .css( 'font-size', 'inherit' );
                  }
                }*/
              ],

              'columnDefs':[
                {
                  'targets':15,
                  'checkboxes':{
                    'selectRow':true
                  },
                }
              ],
              'select':{
                'style':'multi'
              },
              "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull){
                if(aData.dato==0){
                  viewModelViaticoDetalle.tableEmpleados.columns(15).visible(false);
                }
                var rowId = aData[15];


              }

        });
        $('#tb_empleados_por_nombramiento tbody').on('click', 'td.details-control', function () {
          var tr = $(this).parents('tr');
          var currentId = $(this).attr('id');
           var row = viewModelViaticoDetalle.tableEmpleados.row( tr );

           if ( row.child.isShown() ) {
               // This row is already open - close it
               row.child.hide();
               tr.removeClass('shown');
           }
           else {
               // Open this row
               row.child( format(row.data()) ).show();
               tr.addClass('shown');
           }
         });

         var x = [];
         //var uniqs;

         $('#tb_empleados_por_nombramiento tbody').on('change', 'td label input', function () {

           var array = [];
           var tr = $(this).parents('tr');
           var row = viewModelViaticoDetalle.tableEmpleados.row( tr );
           var d = row.data();
           var uniques;
           if ($(this).is(':checked')) {
            //alert('chequeado');
            if(d.tipo == 1){
              viewModelViaticoDetalle.tipoS += 1;
            }else{
              viewModelViaticoDetalle.tipoL += 1;
            }
            //app_vue_ped.insumos = response.data;
            viewModelViaticoDetalle.uniqs.push({
              id_persona: d.id_persona,
              tipo: d.tipo
            })
          }
          else {
            if(d.tipo == 1){
              viewModelViaticoDetalle.tipoS -= 1;
            }else{
              viewModelViaticoDetalle.tipoL -= 1;
            }
            var idx = viewModelViaticoDetalle.uniqs.indexOf(d.id_persona);
            //if(viewModelViaticoDetalle.uniqs.length > ){
              viewModelViaticoDetalle.uniqs.splice(idx, 1);
            //}
          }

        });


       $('#tb_empleados_por_nombramiento thead').on('change', 'th label input', function () {
         //$('#myTable').on('click', '.toggle-all', function (e) {
         viewModelViaticoDetalle.tipoS = 0;
         viewModelViaticoDetalle.tipoL = 0;
         var rows_selected = viewModelViaticoDetalle.tableEmpleados.column(15).checkboxes.selected();
         if ($(this).is(':checked')) {
           $.each(viewModelViaticoDetalle.tableEmpleados.$('input[type=checkbox]:checked'), function(){
             var d = viewModelViaticoDetalle.tableEmpleados.row($(this).parents('tr')).data();
             //alert('chequeado')
             if(d.tipo == 1){
               viewModelViaticoDetalle.tipoS += 1;
             }else{
               viewModelViaticoDetalle.tipoL += 1;
             }
           });
         }
         else{
           viewModelViaticoDetalle.tipoS = 0;
           viewModelViaticoDetalle.tipoL = 0;
         }
       });

         function format(d) {
           console.log();(d.id_persona + ' - '+ d.reng_num)
           //alert(d.serie);

           var div = $('<div/>');
           var url='viaticos/php/front/viaticos/viatico_detalle_row.php'
           $.ajax({
             type: "POST",
             url:url,
             data:{
               id_viatico:$('#id_viatico').val(),
               id_persona:d.id_persona,
               id_renglon:d.reng_num
             },
             beforeSend:function(){
               //$('#response').html('<span class="text-info">Loading response...</span>');
               $(div).fadeOut(0).html('<div class="spinner-grow text-info"></div>').fadeIn(0);
             },
             success: function(datos){
               $(div).fadeOut(0).html(datos).fadeIn(0);
             }
           });
           return div;
         }
      },
      verificarDuplicados: function(){
        return viewModelViaticoDetalle.uniqs.reduce((seed, current) => {
          return Object.assign(seed, {
            [current.tipo]: current
          });
        }, {});
      },
      recargarTableEmps: function(){
        viewModelViaticoDetalle.tableEmpleados.ajax.reload(viewModelViaticoDetalle.iComplete, false);
      },
      cargarCambio: function(id_persona, reng_num){
        $('#id_persona').val(id_persona);
        $('#id_renglon').val(reng_num);
        viewModelViaticoDetalle.getOpcion(5);
      },
      cargarInput: function(tipo){
        var rows_selected = viewModelViaticoDetalle.tableEmpleados.column(15).checkboxes.selected();

        var codigos='';
        var renglones = '';
        if(rows_selected.length == 0){
          //mostrar mensaje
          jsonTableData = '';
          Swal.fire(
            'Atención!',
            "Debe seleccionar al menos un empleado",
            'error'
          );
        }else if(rows_selected.length>0){
          var x = [];


          $.each(viewModelViaticoDetalle.tableEmpleados.$('input[type=checkbox]:checked'), function(){
            var data = viewModelViaticoDetalle.tableEmpleados.row($(this).parents('tr')).data();
            codigos +=','+data['DT_RowId'];
            renglones += data['reng_num']+',';
            $('#id_persona').val(codigos);
            $('#id_renglon').val(renglones);
            x.push(data['tipo']);
          });

          if(tipo == 1){
            viewModelViaticoDetalle.estado_viatico();
            viewModelViaticoDetalle.viatico_by_id();
            viewModelViaticoDetalle.getOpcion(3);
            viewModelViaticoDetalle.empleados_para_procesar();
          }else if (tipo == 2){
            viewModelViaticoDetalle.estado_viatico();
            viewModelViaticoDetalle.viatico_by_id();
            viewModelViaticoDetalle.getOpcion(4);
            viewModelViaticoDetalle.empleados_para_procesar();
          }


        }

      },
      changeCheck: function(){
        //alert(this.arrayA.length)
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
                viewModelViaticoDetalle.viatico_by_id();
                viewModelViaticoDetalle.calcular_viaticos();
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
                viewModelViaticoDetalle.viatico_by_id();
                viewModelViaticoDetalle.calcular_viaticos();

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
        viewModelViaticoDetalle.getLoadEditTableDestinos();
        viewModelViaticoDetalle.get_departamentos();
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
          if(this.destinos.length < $('#destinosfal').text()){
            this.destinos.push({departamento:'',municipio:'',aldea:'',tipo:'',f_ini:'',f_fin:'',h_ini:'',h_fin:''});
            viewModelViaticoDetalle.getLoadEditTableDestinos();
            viewModelViaticoDetalle.get_departamentos();
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
            viewModelViaticoDetalle.departamentos = response.data;

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
            viewModelViaticoDetalle.munis1 = response.data;
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
            viewModelViaticoDetalle.munis2 = response.data;
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
            viewModelViaticoDetalle.munis3 = response.data;
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
            viewModelViaticoDetalle.munis4 = response.data;
          })
          .catch(function (error) {
              console.log(error);
          });
        }
        if(index==77){
          axios.get('viaticos/php/back/listados/destinos/get_municipio.php',{
            params:{
              departamento:valor
            }
          })
          .then(function (response) {
            viewModelViaticoDetalle.munis0 = response.data;
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
                viewModelViaticoDetalle.viatico_by_id();
                viewModelViaticoDetalle.calcular_viaticos();

              }
            }*/
          });


        }, 1000);

      },// inicio funciones procesamiento
      au_solicitud: function(estado){
        var mensaje, color, validacion, mensaje_success;
        if(estado==933){
          //Autorizado
          mensaje='¿Quiere autorizar este nombramiento';
          color='#28a745';
          validacion='¡Si, Autorizar!';
          mensaje_success='Nombramiento Autorizado';
        }else if(estado==934){
          //Anulado en dirección
          mensaje='¿Quiere anular este nombramiento';
          color='#d33';
          validacion='¡Si, Anular!';
          mensaje_success='Nombramiento Anulado';
        }else if(estado==935){
          //procesado
          mensaje='¿Quiere procesar este nombramiento';
          color='#28a745';
          validacion='¡Si, Procesar!';
          mensaje_success='Nombramiento Procesado';
        }else if(estado==1635){
          //procesado
          mensaje='¿Quiere anular este nombramiento';
          color='#d33';
          validacion='¡Si, Anular!';
          mensaje_success='Nombramiento Anulado en cálculo';
        }else if(estado==936){
          //procesado
          mensaje='¿Quiere elaborar el cheque a este nombramiento';
          color='#28a745';
          validacion='¡Si, elaborar!';
          mensaje_success='Cheque elaborado';
        }else if(estado==1636){
          //procesado
          mensaje='¿Quiere anular la impresión del cheque?';
          color='#d33';
          validacion='¡Si, Anular!';
          mensaje_success='¡Impresión anulada!';
        }else if(estado==938){
          //procesado
          mensaje='¿Quiere entregar este cheque?';
          color='#28a745';
          validacion='¡Si, entregar!';
          mensaje_success='Cheque entregado';
        }else if(estado==939){
          //procesado
          mensaje='¿Quiere generar constancia de los empleados?';
          color='#28a745';
          validacion='¡Si, generar!';
          mensaje_success='Constancia generada';
        }else if(estado==940){
          //procesado
          mensaje='¿Quiere liquidar el nombramiento?';
          color='#28a745';
          validacion='¡Si, liquidar!';
          mensaje_success='Nombramiento liquidado';
        }
        else if(estado==1643){
          //procesado
          mensaje='¿Quiere anular el cheque impreso?';
          color='#d33';
          validacion='¡Si, Anular!';
          mensaje_success='Cheque anulado';
        }else if(estado==7972){
          mensaje='¿Quiere anular el nombraiento?';
          color='#d33';
          validacion='¡Si, Anular!';
          mensaje_success='Nombramiento anulado';
        }else if(estado==8194){
          mensaje='¿Quiere entregar el efectivo?';
          color='#28a745';
          validacion='¡Si, Entregar!';
          mensaje_success='Entregar efectivo';
        }

        cambio=1;


        Swal.fire({
          title: '<strong></strong>',
          text: mensaje,
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: color,
          cancelButtonText: 'Cancelar',
          confirmButtonText: validacion
        }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "viaticos/php/back/viatico/au_nombramiento.php",
                //dataType: 'html',
                data: {
                  vt_nombramiento: $('#id_viatico').val(),
                  estado:estado,
                  cambio:cambio
                },
                success:function(data) {
                    //$("#aldea").html(data);
                    console.log(data);
                    //$('#empleados_asignados_datos').html(data)
                    if(estado==933 || estado==934 || estado==935 || estado==1635 || estado==936 || estado==163 || estado==938 || estado==1643 || estado==7972 || estado==8194){
                      viewModelViaticoDetalle.viatico_by_id();
                      viewModelViaticoDetalle.estado_viatico();
                      viewModelViaticoDetalle.recargarTableEmps();
                      viewModelViaticoDetalle.getOpcion(1);
                      //recargar_nombramientos($('#id_filtro_detalle').val());
                    }else if(estado==939 || estado==940){
                      viewModelViaticoDetalle.viatico_by_id();
                      viewModelViaticoDetalle.estado_viatico();
                      viewModelViaticoDetalle.recargarTableEmps();
                      viewModelViaticoDetalle.getOpcion(2);
                      //recargar_nombramientos($('#id_filtro_detalle').val());
                    }
                    viewModelViaticoDetalle.cambio = 1;
                    Swal.fire({
                      type: 'success',
                      title: mensaje_success,
                      showConfirmButton: false,
                      timer: 2000
                    });

                }
              });
        }

        });

      },
      sustituirEmpleado: function(){
        //conteo=table_pendientes.rows().count();
        vt_nombramiento=$('#id_viatico').val();
        empleado_actual=$('#id_persona').val();
        empleado_sustituye=$('#id_empleado_sustituye').val();
        id_renglon=$('#id_renglon').val();

        Swal.fire({
          title: '<strong></strong>',
          text: '¿Desea sustituir este empleado?',
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '!Si, sustituir!'
        }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "POST",
                //dataType: "json",
                url: "viaticos/php/back/viatico/sustituir_empleado.php",
                data:
                {
                  vt_nombramiento,
                  empleado_actual,
                  empleado_sustituye,
                  id_renglon
                },
                beforeSend:function(){},
                success:function(data){
                  if(data=='1'){
                    Swal.fire({
                      type: 'error',
                      title: 'El empleado no puede ser el mismo',
                      showConfirmButton: false,
                      timer: 2000
                    });
                  }else if(data=='2'){
                    Swal.fire({
                      type: 'error',
                      title: '¡Este empleado ya se encuentra en la lista ',
                      showConfirmButton: false,
                      timer: 2000
                    });
                  }else
                  if(data=='ok'){
                    Swal.fire({
                      type: 'success',
                      title: '¡Sustitución generada!',
                      showConfirmButton: false,
                      timer: 2000
                    });
                    viewModelViaticoDetalle.recargarTableEmps();
                    viewModelViaticoDetalle.getOpcion(2);

                  }

                  //alert(conteo);
                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
              });

            }
          });


        //alert(conteo);
      },
       confirmar_ausencia: function(){
        //conteo=table_pendientes.rows().count();
        vt_nombramiento=$('#id_viatico').val();
        empleado_actual=$('#id_persona').val();
        id_renglon=$('#id_renglon').val();

        Swal.fire({
          title: '<strong></strong>',
          text: '¿Desea confirmar ausencia?',
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '!Si, confirmar ausencia!'
        }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "POST",
                //dataType: "json",
                url: "viaticos/php/back/viatico/confirmar_ausencia.php",
                data:
                {
                  vt_nombramiento,
                  empleado_actual,
                  id_renglon
                },
                beforeSend:function(){},
                success:function(data){
                  viewModelViaticoDetalle.recargarTableEmps();
                  viewModelViaticoDetalle.getOpcion(2);

                    Swal.fire({
                      type: 'success',
                      title: '¡Ausencia confirmada!',
                      showConfirmButton: false,
                      timer: 2000
                    });

                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
              });

            }
          });


        //alert(conteo);
      },
      confirmar_ausencia_singular: function(vt_nombramiento,empleado_actual,id_renglon){
          Swal.fire({
          title: '<strong></strong>',
          text: '¿Desea confirmar ausencia?',
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#d33',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '!Si, confirmar ausencia!'
        }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "POST",
                //dataType: "json",
                url: "viaticos/php/back/viatico/confirmar_ausencia.php",
                data:
                {
                  vt_nombramiento,
                  empleado_actual,
                  id_renglon
                },
                beforeSend:function(){},
                success:function(data){
                  viewModelViaticoDetalle.recargarTableEmps();
                  viewModelViaticoDetalle.getOpcion(2);
                    Swal.fire({
                      type: 'success',
                      title: '¡Ausencia confirmada!',
                      showConfirmButton: false,
                      timer: 2000
                    });

                }
              }).done( function() {
              }).fail( function( jqXHR, textSttus, errorThrown){
              });

            }
          });


        //alert(conteo);
      },
      precesar_nombramiento: function(estado, $event){

        //procesa_formulario_accion(formulario,estado,cambio);

        formulario=$('#id_viatico').val();
        var nFilas_total = $("#tb_montos tbody tr").length;
        if(nFilas_total>0){
          //alert('message');
          if($('#pais_tipo').text()=='Extranjero' || $('#pais_tipo').text()=='EXTERIOR'){
            if($('#tasa_cambiaria').val()!='' && $('#tasa_cambiaria').val()>0){
              //alert('message');
              var cambio=$('#tasa_cambiaria').val();
              viewModelViaticoDetalle.procesa_formulario_accion(formulario,estado,cambio)
            }else{
              $event.preventDefault();
              Swal.fire({
                type: 'error',
                title: '¡Debe ingresar correctamente el tipo de cambio!',
                showConfirmButton: false,
                timer: 2000
              });
            }
          }else{

            if($('#sub_total').text()==0){
              estado=7959;
              $event.preventDefault();
              viewModelViaticoDetalle.generarNombramiento(formulario,estado,cambio);


            }else{
              //estado=936;
              viewModelViaticoDetalle.procesa_formulario_accion(formulario,estado,1);
            }

          }
        }
        else{
          event.preventDefault();
          Swal.fire({
            type: 'error',
            title: '¡Debe validar los montos a asignar!',
            showConfirmButton: false,
            timer: 2000
          });
        }
      },
      procesa_formulario_accion: function(formulario,estado,cambio){
        jQuery('.jsValidacionProcesarNombramiento').validate({
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
              viewModelViaticoDetalle.generarNombramiento(formulario,estado,cambio);

          }

        });





      },
      generarNombramiento: function(formulario,estado,cambio){

        Swal.fire({
          title: '<strong></strong>',
          text: '¿Desea procesar este nombramiento?',
          type: 'question',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: 'Procesar Nombramiento'
        }).then((result) => {
            if (result.value) {
              $.ajax({
                type: "POST",
                url: "viaticos/php/back/viatico/au_nombramiento.php",
                //dataType: 'html',
                data: {
                  vt_nombramiento:formulario,
                  estado:estado,
                  cambio:cambio
                },
                success:function(data) {
                  //alert(data);
                    //$("#aldea").html(data);
                    console.log(data);
                    //$('#empleados_asignados_datos').html(data)
                    //recargar_nombramientos($('#id_filtro_detalle').val());
                    viewModelViaticoDetalle.cambio = 1;

                    $("#tb_montos tbody tr").each(function(index, element){

                       id_row = ($(this).attr('id'));
                       porcentaje=$(element).find("td").eq(3).html();
                       monto=$(element).find("td").eq(5).html();
                       var cheque=0;

                       anticipo=0;
                       if($('#ac'+id_row).prop('checked')){
                         anticipo=1;
                         cheque = $('#ch'+id_row).val();
                       }

                       //alert(porcentaje+' - '+monto)
                       //anotacion = $('#text_'+codigo_insumo).val();
                      $.ajax({
                        type: "POST",
                        url: "viaticos/php/back/viatico/procesar_nombramiento_detalle.php",

                        data:
                        {
                          vt_nombramiento:formulario,
                          reng_num:id_row,
                          porcentaje:porcentaje,
                          monto:monto,
                          anticipo:anticipo,
                          cheque:cheque
                        },
                        beforeSend:function(){
                          $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                        },
                        success:function(data){
                          //alert(data);

                        }
                      }).done( function() {


                      }).fail( function( jqXHR, textSttus, errorThrown){

                      });

                    });

                    viewModelViaticoDetalle.viatico_by_id();
                    viewModelViaticoDetalle.estado_viatico();
                    viewModelViaticoDetalle.recargarTableEmps();
                    viewModelViaticoDetalle.getOpcion(1);
                    Swal.fire({
                      type: 'success',
                      title: '¡Nombramiento procesado!',
                      showConfirmButton: false,
                      timer: 2000
                    });

                }
              });
        }

      });
    },
    elaborar_cheque:function(tipo)
    {
      if(tipo==0){


        Swal.fire({
        title: '<strong>¿Quiere generar sin cheque?</strong>',
        text: "",
        type: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Generar sin cheque!'
        }).then((result) => {
        if (result.value) {

          $.ajax({
            type: "POST",
            url: "viaticos/php/back/viatico/cheque_nombramiento.php",
            //dataType: 'html',
            data: {
              vt_nombramiento: $('#id_viatico').val(),
              estado:7959,
              cheque:0,
              tipo:tipo
            },
            success:function(data) {
                //$("#aldea").html(data);
                console.log(data);
                viewModelViaticoDetalle.cambio = 1;
                //$('#empleados_asignados_datos').html(data)
                viewModelViaticoDetalle.viatico_by_id();
                viewModelViaticoDetalle.estado_viatico();
                viewModelViaticoDetalle.getOpcion(1);
                if(data!='ok'){
                  Swal.fire({
                    type: 'error',
                    title: data,
                    showConfirmButton: false,
                    timer: 2000
                  });
                }
                else{
                  Swal.fire({
                    type: 'success',
                    title: '¡Guardado sin cheque!',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }

            }
          });

        }
      })
      }else if(tipo==1){
        var form='';

        Swal.fire({
        title: '<strong>¿Quiere elaborar el cheque a este nombramiento?</strong>',
        text: "",
        type: 'question',
        //input: 'number',
        //inputPlaceholder: 'Agregar',
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Elaborar!'/*,
        inputValidator: function(inputValue) {
        return new Promise(function(resolve, reject) {
          if (inputValue && inputValue.length > 0) {
            resolve();
            form=inputValue;
          } else {
            Swal.fire({
              type: 'error',
              title: 'Debe ingresar el correlativo del cheque',
              showConfirmButton: false,
              timer: 1100
            });

          }
        });
      }*/
        }).then((result) => {
        if (result.value) {

          $.ajax({
            type: "POST",
            url: "viaticos/php/back/viatico/cheque_nombramiento.php",
            //dataType: 'html',
            data: {
              vt_nombramiento: $('#id_viatico').val(),
              estado:936,
              cheque:form,
              tipo:tipo
            },
            success:function(data) {
                //$("#aldea").html(data);
                console.log(data);
                //$('#empleados_asignados_datos').html(data)
                viewModelViaticoDetalle.viatico_by_id();
                viewModelViaticoDetalle.estado_viatico();
                viewModelViaticoDetalle.getOpcion(1);
                viewModelViaticoDetalle.cambio = 1;
                if(data!='ok'){
                  Swal.fire({
                    type: 'error',
                    title: data,
                    showConfirmButton: false,
                    timer: 2000
                  });
                }
                else{
                  Swal.fire({
                    type: 'success',
                    title: '¡Cheque elaborado!',
                    showConfirmButton: false,
                    timer: 2000
                  });
                }

            }
          });

        }
      })
      }

    },
    generarConstancia: function(){

          jQuery('.js-validation-constancia').validate({
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
                  //regformhash(form,form.password,form.confirmpwd);
                  vt_nombramiento=$('#id_viatico').val();
                  id_persona=$('#id_persona').val();
                  id_renglon=$('#id_renglon').val();

                  fecha_salida_saas=$('#id_fecha_salida_saas').val();
                  hora_salida_saas=$('#id_hora_salida_saas').val();
                  fecha_llegada_lugar=$('#id_fecha_llegada_lugar').val();
                  hora_llegada_lugar=$('#id_hora_llegada_lugar').val();
                  fecha_salida_lugar=$('#id_fecha_salida_lugar').val();
                  hora_salida_lugar=$('#id_hora_salida_lugar').val();
                  fecha_regreso_saas=$('#id_fecha_regreso_saas').val();
                  hora_regreso_saas=$('#id_hora_regreso_saas').val();

                  transporte_salida=0;
                  empresa_salida=0;
                  nro_vuelo_salida=0;
                  transporte_regreso=0;
                  empresa_regreso=0;
                  nro_vuelo_regreso=0;
                  if($('#id_country_').text()!='GT'){
                    transporte_salida=$('#id_tipo_salida').val();
                    empresa_salida=$('#id_empresa_salida').val();

                    transporte_regreso=$('#id_tipo_entrada').val();
                    empresa_regreso=$('#id_empresa_entrada').val();

                    if($('#id_tipo_salida').val()==941){
                      nro_vuelo_salida=$('#id_num_vuelo_salida').val();
                    }
                    if($('#id_tipo_entrada').val()==941){
                      nro_vuelo_regreso=$('#id_num_vuelo_entrada').val();
                    }
                  }

                  Swal.fire({
                  title: '<strong>¿Desea generar la Constancia?</strong>',
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
                    url: "viaticos/php/back/viatico/constancia_nombramiento_detalle.php",
                    data: {
                      vt_nombramiento,
                      id_persona,
                      id_renglon,
                      fecha_salida_saas,
                      hora_salida_saas,
                      fecha_llegada_lugar,
                      hora_llegada_lugar,
                      fecha_salida_lugar,
                      hora_salida_lugar,
                      fecha_regreso_saas,
                      hora_regreso_saas,

                      transporte_salida,
                      empresa_salida,
                      nro_vuelo_salida,
                      transporte_regreso,
                      empresa_regreso,
                      nro_vuelo_regreso

                    }, //f de fecha y u de estado.

                    beforeSend:function(){
                                  //$('#response').html('<span class="text-info">Loading response...</span>');
                                  //alert('message_before')

                          },
                          success:function(data){
                            //alert(data);
                            viewModelViaticoDetalle.tipoS = 0;
                            viewModelViaticoDetalle.tipoL = 0;
                            viewModelViaticoDetalle.cambio = 1;
                            viewModelViaticoDetalle.viatico_by_id();
                            viewModelViaticoDetalle.estado_viatico();
                            viewModelViaticoDetalle.recargarTableEmps();
                            viewModelViaticoDetalle.getOpcion(2);
                            Swal.fire({
                              type: 'success',
                              title: 'Constancia generada',
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
            },
            rules: {
              combo1:{ required: true},
              combo2:{ required: true},
              combo3:{ required: true},
              combo4:{ required: true},
              combo5:{ required: true},
              combo6:{ required: true},
              combo7:{ required: true},
              combo8:{ required: true},
             }

          });

    },
    generar_liquidacion: function(){
          jQuery('.js-validation-liquidacion').validate({
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
                  //regformhash(form,form.password,form.confirmpwd);
                  vt_nombramiento=$('#id_viatico').val();
                  id_persona=$('#id_persona').val();
                  id_renglon=$('#id_renglon').val();

                  id_reintegro_hospedaje=$('#id_reintegro_hospedaje').val();
                  id_reintegro_alimentacion=$('#id_reintegro_alimentacion').val();
                  id_otros_gastos=$('#id_otros_gastos').val();
                  id_fecha_liquidacion=$('#id_fecha_liquidacion').val();

                  Swal.fire({
                  title: '<strong>¿Desea actualizar liquidación?</strong>',
                  text: "",
                  type: 'question',
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
                    url: "viaticos/php/back/viatico/liquidacion_reintegro.php",
                    data: {
                      vt_nombramiento,
                      id_persona,
                      id_renglon,
                      id_reintegro_hospedaje,
                      id_reintegro_alimentacion,
                      id_otros_gastos,
                      id_fecha_liquidacion
                    }, //f de fecha y u de estado.

                    beforeSend:function(){
                                  //$('#response').html('<span class="text-info">Loading response...</span>');
                                  //alert('message_before')

                          },
                          success:function(data){
                            viewModelViaticoDetalle.tipoS = 0;
                            viewModelViaticoDetalle.tipoL = 0;
                            viewModelViaticoDetalle.cambio = 1;
                            viewModelViaticoDetalle.viatico_by_id();
                            viewModelViaticoDetalle.estado_viatico();
                            //setTimeout(() =>
                            viewModelViaticoDetalle.recargarTableEmps();
                              viewModelViaticoDetalle.getOpcion(2);
                            //}, 600);


                            //alert(data);
                            //document.app_vue.get_empleado_by_viatico();
                            Swal.fire({
                              type: 'success',
                              title: 'Datos generados',
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
            }

          });

    },
    agregarLugares: function(){


            jQuery('.js-validation-agregar-lugares').validate({
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
                  if(contar_emptys()){
                    //inicio
                    Swal.fire({
                    title: '<strong>¿Desea agregar lugares?</strong>',
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

                      $("#tb_lugares tbody tr").each(function(index, element){
                        id_row = ($(this).attr('id'));

                        $.ajax({
                          type: "POST",
                          url: "viaticos/php/back/viatico/agregar_lugares.php",

                          data:
                          {
                            vt_nombramiento:$('#id_viatico').val(),
                            pais_id:$('#id_country').text(),
                            dep_id:$('#combo_dep'+index).val(),
                            muni_id:$('#combo_mun'+index).val(),
                            ald_id:$('#combo_ald'+index).val(),
                            f_ini:$('#f_ini'+index).text(),
                            f_fin:$('#f_fin'+index).text(),
                            h_ini:$('#h_ini'+index).text(),
                            h_fin:$('#h_fin'+index).text()
                          },
                          beforeSend:function(){
                            $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
                          },
                          success:function(data){
                            if(data=='OK'){
                              Swal.fire({
                                type: 'success',
                                title: 'Información ingresada correctamente',
                                showConfirmButton: false,
                                timer: 1100
                              });
                              viewModelViaticoDetalle.destinos.splice(0, viewModelViaticoDetalle.destinos.length);
                              viewModelViaticoDetalle.viatico_by_id();
                              viewModelViaticoDetalle.getOpcion(2);
                              viewModelViaticoDetalle.cambio = 1;
                            }else{
                              Swal.fire({
                                type: 'error',
                                title: 'Ingrese la correctamente los datos',
                                showConfirmButton: false,
                                timer: 1100
                              });
                            }

                          }
                        }).done( function() {


                        }).fail( function( jqXHR, textSttus, errorThrown){

                        });

                      });

                  }

                })
                    //fin
                  }else{
                    Swal.fire({
                      type: 'error',
                      title: 'Ingrese la correctamente los datos',
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }

              },
              rules: {
                combo_dep0:{ required: true},
                combo_mun0:{ required: true}
               }

            });
    },
    cambiarEmision: function(event){
      this.emitirViatico = event.currentTarget.value;
    },
    confirmar_lugar: function(){

            jQuery('.js-validation-confirma-lugar').validate({
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
                    //regformhash(form,form.password,form.confirmpwd);
                    vt_nombramiento=$('#id_viatico').val();
                    departamento=$('#departamento').val();
                    municipio=$('#municipio').val();
                    aldea=$('#aldea').val();

                    Swal.fire({
                    title: '<strong>¿Desea confirmar el lugar?</strong>',
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
                      url: "viaticos/php/back/viatico/confirmar_lugar.php",
                      data: {
                        vt_nombramiento,
                        departamento,
                        municipio,
                        aldea
                      }, //f de fecha y u de estado.

                      beforeSend:function(){
                                    //$('#response').html('<span class="text-info">Loading response...</span>');
                                    //alert('message_before')

                            },
                            success:function(data){
                              //alert(data);
                              viewModelViaticoDetalle.cambio = 1;
                              viewModelViaticoDetalle.viatico_by_id();
                              viewModelViaticoDetalle.getOpcion(2);
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
              },
              rules: {
                combo_dep:{ required: true},
                combo_mun:{ required: true}
               }

            });
    }

      //fin
    }
  })

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

  instanciaD = viewModelViaticoDetalle;

  instanciaD.getTableEmpleados();



//})

function cargarCambioEmp(p,r){
  instanciaD.cargarCambio(p,r);
}

function sustituirEmpleado(){
  instanciaD.sustituir_empleado;
}
function confirmarAusenciaSingular(n,p,r){
  instanciaD.confirmar_ausencia_singular(n,p,r);
}
