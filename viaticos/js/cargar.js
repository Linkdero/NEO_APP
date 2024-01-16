function get_viatico_detalle_encabezado(url){
    if(url=='constancia_global'){
    var rows_selected = table_empleados_por_viaticos.column(15).checkboxes.selected().length;
    if(rows_selected>0){
      parametro='';
      var valArray = [];
      $.each(table_empleados_por_viaticos.$('input[type=checkbox]:checked'), function(){
        var data = table_empleados_por_viaticos.row($(this).parents('tr')).data();
        //parametro+=''+data['DT_RowId']+',';
        value=data['DT_RowId'];
        renglon=data['reng_num'];
        valArray.push(renglon);
        id_personas.push(value);
      });
      $.each(table_empleados_por_viaticos.$('input[type=checkbox]:not(:checked)'), function(){
        var data = table_empleados_por_viaticos.row($(this).parents('tr')).data();
        //parametro+=''+data['DT_RowId']+',';

        renglon=data['reng_num'];
        id_persona=data['DT_RowId'];
        removeA(valArray, renglon);
        removeA(id_personas, id_persona);
      });
      $.each()
      param=valArray.toString();
      param2=id_personas.toString();
      verificar_url(url,param2,param);
    }else{
      Swal.fire(
        'Atención!',
        "Debe seleccionar al menos un empleado",
        'error'
      );
    }
  }else if(url=='liquidacion_global'){
    var rows_selected = table_empleados_por_viaticos.column(15).checkboxes.selected().length;
    if(rows_selected>0){
      parametro='';
      var valArray = [];
      $.each(table_empleados_por_viaticos.$('input[type=checkbox]:checked'), function(){
        var data = table_empleados_por_viaticos.row($(this).parents('tr')).data();
        //parametro+=''+data['DT_RowId']+',';
        value=data['DT_RowId'];
        renglon=data['reng_num'];
        valArray.push(renglon);
        id_personas.push(value);

      });
      $.each(table_empleados_por_viaticos.$('input[type=checkbox]:not(:checked)'), function(){
        var data = table_empleados_por_viaticos.row($(this).parents('tr')).data();
        //parametro+=''+data['DT_RowId']+',';

        renglon=data['reng_num'];
        removeA(valArray, renglon);
        id_persona=data['DT_RowId'];
        removeA(id_personas, id_persona);
      });
      $.each()
      param=valArray.toString();
      param2=id_personas.toString();
      verificar_url(url,param2,param);
    }else{
      Swal.fire(
        'Atención!',
        "Debe seleccionar al menos un empleado",
        'error'
      );
    }
  }
  else{
    verificar_url(url,'','');
  }


}
function verificar_url(url,parametro,paramentro2){
  $.ajax({
    type: "POST",
    url: "viaticos/php/front/viaticos/"+url+".php",
    data:{
      id_viatico:$('#id_viatico').val(),
      id_persona:parametro,
      id_renglon:paramentro2
    },
    dataType: 'html',
    beforeSend:function(){
      /*var sweet_loader = "<div class='loaderr'></div>"
      Swal.fire({
        title:'loader',
        html: 'I will close in <b></b> miliseconds',
        showConfirmButton:false,
        onRender:function(){
          $('.swal2-content').prepend(sweet_loader);
        }
      })*/
      $("#datos_nombramiento").removeClass('slide_up_anim');
      $("#datos_nombramiento").html('<div class="loaderr"></div>');
    },
    success:function(data) {
      $("#datos_nombramiento").addClass('slide_up_anim');
      $("#datos_nombramiento").fadeIn('slow').html(data).fadeIn('slow');
    }
  });
}

function get_viatico_detalle_por_persona_encabezado(url,id_persona,id_renglon){
  $.ajax({
    type: "POST",
    url: "viaticos/php/front/viaticos/"+url+".php",
    data:{
      id_viatico:$('#id_viatico').val(),
      id_persona:id_persona,
      id_renglon:id_renglon
    },
    dataType: 'html',
    beforeSend:function(){
      $("#datos_nombramiento").html('<div class="loaderr"></div>');
    },
    success:function(data) {
        $("#datos_nombramiento").html(data);
    }
  });
}


function cargar_menu_nombramientos_pendiente(){
  $.ajax({
    type: "POST",
    url: "viaticos/php/front/viaticos/nombramientos_menu.php",
    //dataType: 'html',
    data: {
    },
    beforeSend:function(){
      $('#menu_nombramientos').html('Cargando');
    },
    success:function(data) {
        $('#menu_nombramientos').html(data);

    }
  });
}

function toggleB() {
  if( $('#id_confirma').text()=='1' )
  {
    $("#chk_confirma").attr('checked', true);
    mostar_formulario_confirma();
  }else{
    //alert('deschequeado');
  }


}
