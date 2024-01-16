<template>
  <div class="row">
    <div class="col-sm-12 ">
      <div class="table-responsive">
        <!--total chequeados: {{ totalChequeados }}<br>
        {{ tipoS }}<br>
        {{ tipoL }}-->
        <table id="tb_empleados_por_nombramiento" class="table table-actions table-sm table-striped table-bordered " width="1170px">
          <thead>
            <th class="text-center">.</th>
            <th class="text-center">Cod.</th>
            <th class="text-center">Empleado</th>

            <th class="text-center">VA</th>
            <th class="text-center">VC</th>
            <th class="text-center">VL</th>
            <th class="text-center">% P</th>
            <th class="text-center">% R</th>
            <th class="text-center">Mo. P</th>
            <th class="text-center">Mo. R</th>
            <th class="text-center">R. auto.</th>
            <th class="text-center">Re.</th>
            <th class="text-center">Co</th>
            <th class="text-center">Estado</th>
            <!--<th class="text-center">Cheque</th>-->
            <th class="text-center">Acción</th>
            <th class="text-center" @change="eventHeader()">...</th>
          </thead>

        </table>
      </div>
    </div>
    <div class="col-sm-12 ">
      <viaticotransaccion  :viatico="viaticos" :tipos="tipoS" :tipol="tipoL" :evento="evento"></viaticotransaccion>
    </div>
    <div style="position: absolute; z-index:100; max-width:200px; margin-left:-150px">
    <!--<div style="position: absolute; z-index:100; max-width:200px">-->
      <empleados v-if="showModal == true" :key="'xyxy'+keyReload" :viatico="viatico" :tipos="tipoS" :tipol="tipoL" :evento="evento" :personas="personas" :renglones="renglones"></empleados>
    </div>
    <viaticomodal :key="'A'+keyReload" v-if="showModal == true" :viatico="viaticos" :tipos="tipoS" :tipol="tipoL" :evento="evento" :operacion="operacion" :personas="personas" :renglones="renglones" :id_persona="idPersona" :llave="keyReload"></viaticomodal>
  </div>
</template>
<script>
const viaticotransaccion = httpVueLoader('./ViaticoTransaccion.vue');
const viaticomodal = httpVueLoader('./ViaticoModal.vue');
const empleados = httpVueLoader('./EmpleadosSeleccionados.vue');
module.exports = {
  //
  props:["id_viatico","privilegio","estado_nombramiento", "evento","viatico"],
  data: function(){
    return {
      viaticos:"",
      isLoaded:false,
      tableEmpleados:"",
      emitirViatico:0,
      cambio:0,
      tipoS:0,
      tipoL:0,
      totalChequeados:0,
      uniqs:[],
      showModal:false,
      keyReload:0,
      operacion:0,
      personas:"",
      renglones:"",
      idPersona:"",
      sumaChequeados:0
      //rowsSelected: 0//this.tableEmpleados.column(15).checkboxes.selected()
      //subtotal:0
    }
  },
  components:{
    viaticotransaccion, viaticomodal, empleados
  },
  mounted(){

  },
  created: function(){

    this.$nextTick(() => {
      this.getTableEmpleados();
      this.viaticos = this.viatico;
      this.contarFilas();
    });
    this.evento.$on('mostrarModal', (data) => {
      this.keyReload ++;
      this.showModal = data;
      if(this.operacion == 4 && data == false){
        this.personas = '';
        this.renglones = '';
      }
    })

    this.evento.$on('destruirTabla', () => {
      //this.tableEmpleados.destroy();
      this.tipoS = 0;
      this.tipoL = 0;
      //alert('message');
    })

    this.evento.$on('mostrarFacturaDetalle', (data) => {
      console.log('datos 1: '+data);
      this.showInvoicesDetail(data);
    })

    this.evento.$on('setOperacion', (data) => {
      this.operacion = data;
    })

    this.evento.$on('recargarEmpleadosList', () =>{
      this.recargarTableEmps();
    })
    /*this.evento.$on('recargarViatico', () => {
      this.getViatico();
    });*/
  },
  methods:{
    iComplete: function(){
      //alert('Recargando');
      this.tableEmpleados.cells(
        this.tableEmpleados.rows(function(idx, data, node){
          //alert(idx);
          return (data.bln_confirma == 0 || data.liquidado == 0) ? true : false;
          //alert(data.bln_confirma)
        }).indexes(),15).checkboxes.disable();
    },
    getTableEmpleados: function(){
      var thisInstance = this;
      thisInstance.tableEmpleados= $('#tb_empleados_por_nombramiento').DataTable({
        'initComplete': function(settings, json){
          thisInstance.tableEmpleados.cells(
            thisInstance.tableEmpleados.rows(function(idx, data, node){
              //alert(idx);
              return (data.bln_confirma == 0 || data.liquidado == 0) ? true : false;
              //alert(data.bln_confirma)
            }).indexes(),15).checkboxes.disable();
          },
          dom:
          "<'row'<'col-sm-6'i><'col-sm-3 text-center'B><'col-sm-3'f>>" +
          "<'row'<'col-sm-12'tr>>",/* +
          "<'row'<'col-sm-6'i><'col-sm-6'p>>",*/
          "ordering": false,
          //"pageLength": 25,
          "bLengthChange":true,
          "bProcessing": true,
          select:true,
          "scrollY":"60vh",
          //"paging": false,
          "ordering": false,
          "info":     true,
          orderCellsTop: true,
          responsive: false,
          paging:false,
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
                thisInstance.recargarTableEmps();
              }
            },
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
              thisInstance.tableEmpleados.columns(15).visible(false);
            }
            var rowId = aData[15];
          }
        });
        /*$(document).on('click','#infoLiquidacion', function(){


        })*/

        $(document).on('click','#infoSustitucion', function(){

          thisInstance.personas = '';
          thisInstance.renglones = '';
          let id = $(this).data('id');
          let reng_num = $(this).data('title');
          thisInstance.evento.$emit('mostrarModal',true);
          thisInstance.evento.$emit('setOperacion',3);

          thisInstance.personas +=','+id;
          thisInstance.idPersona = id;
          thisInstance.renglones += reng_num;
          //thisInstance.evento.$emit('recibirEmpleados', this.empleados);
        })

        $(document).on('click','#infoAusenciaS', function(){

          thisInstance.personas = '';
          thisInstance.renglones = '';
          let id = $(this).data('id');
          let reng_num = $(this).data('title');

          thisInstance.personas +=','+id;
          thisInstance.idPersona = id;
          thisInstance.renglones += reng_num;
          thisInstance.confirmarAusenciaSingular($(this).data('type'),id,reng_num);
          //thisInstance.evento.$emit('recibirEmpleados', this.empleados);
        })

        $('#tb_empleados_por_nombramiento tbody').on('click', 'td.details-control', function () {
          var tr = $(this).parents('tr');
          var currentId = $(this).attr('id');
          var row = thisInstance.tableEmpleados.row( tr );

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
         var row = thisInstance.tableEmpleados.row( tr );
         var d = row.data();
         var uniques;

         var codigos = '';
         var rengs = '';
         if ($(this).is(':checked')) {
          //alert('chequeado');
          if(d.tipo == 1){
            thisInstance.tipoS += 1;
          }else{
            thisInstance.tipoL += 1;
          }

          thisInstance.personas +=','+d.DT_RowId;
          thisInstance.renglones += d.reng_num+',';
        }
        else {
          if(d.tipo == 1){
            thisInstance.tipoS -= 1;
          }else{
            thisInstance.tipoL -= 1;
          }
          thisInstance.personas = thisInstance.personas.replace(','+d.DT_RowId, '');
          thisInstance.renglones = thisInstance.renglones.replace(d.reng_num+',', '');
          var idx = thisInstance.uniqs.indexOf(d.id_persona);
          //if(thisInstance.uniqs.length > ){
            thisInstance.uniqs.splice(idx, 1);
          //}
        }

        var total_ = thisInstance.tableEmpleados.data().count();
        var rows_selected1 = thisInstance.tableEmpleados.rows({ selected: true }).data();
        var rows_selected = thisInstance.tableEmpleados.column(15).checkboxes.selected();

        if(rows_selected.length == total_){
          thisInstance.totalChequeados = 1;
        }else{
          thisInstance.totalChequeados = 0;
        }

        console.log(thisInstance.personas + ' || ' + thisInstance.renglones);

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
    showInvoicesDetail: function(data){
      var thisInstance = this;
      thisInstance.sumaChequeados = thisInstance.tipoS + thisInstance.tipoL;

      var rows_selected = '';
      /*rows_selected = thisInstance.tableEmpleados.column(15).checkboxes.selected();
      // hay que deseleccionar*/
      console.log('Valores seleccionados: '+thisInstance.sumaChequeados);
      if(thisInstance.sumaChequeados > 0){
        Swal.fire({
          type: 'error',
          title: 'Tiene que desmarcar a los empleados para esta operación. ',
          showConfirmButton: true,
          //timer: 1100
        });
      }
      else{
        thisInstance.personas = '';
        thisInstance.renglones = '';
        var id = data.id;//$(this).data('id');
        var reng_num = data.reng_num;// $(this).data('title');
        thisInstance.evento.$emit('mostrarModal',true);
        thisInstance.evento.$emit('setOperacion',4);

        thisInstance.personas +=','+id;
        thisInstance.renglones += reng_num+',';
        //thisInstance.evento.$emit('recibirEmpleados', this.empleados);
      }
    },
    eventHeader: function(){
      var thisInstance = this;
      thisInstance.tipoS = 0;
      thisInstance.tipoL = 0;
      var total_ = thisInstance.tableEmpleados.data().count();
      var rows_selected1 = thisInstance.tableEmpleados.rows({ selected: true }).data();
      var rows_selected = thisInstance.tableEmpleados.column(15).checkboxes.selected();

      var codigos = '';
      var rengs = '';
      var total__ = 0;

      if(this.totalChequeados == 0){

        var x = 0;
        rows_selected.map(function(obj){
          var data = thisInstance.tableEmpleados.row( '#'+obj ).data();
           console.log('codigos: '+data.DT_RowId);
           if (data.tipo == 1) {
             thisInstance.tipoS += 1;
           } else {
             thisInstance.tipoL += 1;
           }

            codigos +=','+data.DT_RowId;
            rengs += data.reng_num+',';
            total__ += 1;

        });
        thisInstance.personas = codigos;
        thisInstance.renglones = rengs;

        /*$.each(rows_selected, function(index, rowId){
          x ++;
          var data = thisInstance.tableEmpleados.row( this ).data();

          console.log('id : '+ x +' :: '+data.tipo+' -- '+data.DT_RowId);
          //console.log(data);;
          if (data.tipo == 1) {
            thisInstance.tipoS += 1;
          } else {
            thisInstance.tipoL += 1;
          }

           codigos +=','+data.DT_RowId;
           rengs += data.reng_num+',';

          thisInstance.personas = codigos;
          thisInstance.renglones = rengs;
        })*/

        if(rows_selected.length == total__){
            thisInstance.totalChequeados = 1;
          }else{
            thisInstance.totalChequeados = 0;
            thisInstance.personas = '';
            thisInstance.renglones = '';
          }
      }else{
        thisInstance.totalChequeados = 0;
        thisInstance.tipoS = 0;
        thisInstance.tipoL = 0;
        thisInstance.personas = '';
        thisInstance.renglones = '';
      }

      console.log(thisInstance.personas + ' || ' + thisInstance.renglones);

    },
    recargarTableEmps: function(){
      this.totalChequeados = 0;
      this.tipoS = 0;
      this.tipoL = 0;
      this.personas = '';
      this.renglones = '';
      this.tableEmpleados.ajax.reload(this.iComplete, false);

    },
    contarFilas: function(){
      var thisInstance = this;

    },
    confirmarAusenciaSingular: function(vt_nombramiento,empleado_actual,id_renglon){
      var thisInstance = this;
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
                thisInstance.recargarTableEmps();
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
    }
    //fin metodos
  }

}
</script>
<style>
table .dropdown-menu .menu-custom {
    position: fixed !important;
    top: 50% !important;
    left: 92% !important;
    /*transform: translate(-92%, -50%) !important;*/
}
</style>
