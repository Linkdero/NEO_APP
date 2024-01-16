function get_departamentos(opc){

  pais=$("#id_country").text();
  if(opc==1){
    pais=$("#pais option:selected").val();
  }
  else{
    pais=$("#id_country").text();
  }
  //alert(pais);
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/listados/get_departamento.php",
    dataType: 'html',
    data: { pais: pais},
    success:function(data) {
      //alert(data);
        $("#departamento").html(data);
        get_municipios();
    }
  });
}


function get_municipios(){
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/listados/get_municipio.php",
    dataType: 'html',
    data: { departamento: $("#departamento option:selected").val()},
    success:function(data) {
        $("#municipio").html(data);
    }
  });
}

function get_aldeas(){
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/listados/get_aldea.php",
    dataType: 'html',
    data: { municipio: $("#municipio option:selected").val()},
    success:function(data) {
        $("#aldea").html(data);
    }
  });
}

function get_direcciones(){
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/listados/get_direcciones.php",
    dataType: 'html',
    data: { },
    success:function(data) {
        $("#direcciones").html(data);
    }
  });
}

function get_opciones_estado_viaticos(combo,opcion){

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/listados/get_estado_viatico.php",
    dataType: 'html',
    data: { opcion:opcion },
    success:function(data) {
        $("#"+combo).html(data);
    }
  });
}

var detached = [];

var valArray = [];
var id_personas=[];

function removeA(arr, what) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function seleccionar_empleados(){
/*  var rows_selected = table_empleados_asignar.column(2).checkboxes.selected().length;
  if(rows_selected>0){
    parametro='';
    var valArray = [];
    $.each(table_empleados_asignar.$('input[type=checkbox]:checked'), function(){
      var data = table_empleados_asignar.row($(this).parents('tr')).data();
      //parametro+=''+data['DT_RowId']+',';
      value=data['DT_RowId'];
      //renglon=data['reng_num'];
      //valArray.push(renglon);
      valArray.push(value);

    });
    $.each(table_empleados_asignar.$('input[type=checkbox]:not(:checked)'), function(){
      var data = table_empleados_asignar.row($(this).parents('tr')).data();
      //parametro+=''+data['DT_RowId']+',';

      value=data['DT_RowId'];
      //renglon=data['reng_num'];
      //valArray.push(renglon);
      removeA(valArray, renglon);
      if(valArray.length == 0){
        //alert('vacio')
        $('#empleados_asignados_datos').html(valArray+ ' || '+data)
      }
    });
    //$.each()
    param=valArray.toString();
    //verificar_url(url,'',param);
    if(valArray.length == 0){
      $('#empleados_asignados').hide();

    }else if(valArray.length>0){
        $('#empleados_asignados').show();
        //get_empleado_
        $.ajax({
          type: "POST",
          url: "viaticos/php/back/listados/get_empleado_asignado.php",
          //dataType: 'html',
          data: { id_persona: valArray.toString()},
          success:function(data) {
              //$("#aldea").html(data);
              console.log(data);
              $('#empleados_asignados_datos').html(data)
          }
        });

    }
  }else{
    Swal.fire(
      'Atención!',
      "Debe seleccionar al menos un empleado",
      'error'
    );
  }*/
  $('#tb_empleados_asignar').on('click', 'input[type="checkbox"]', function() {


        var value = $(this).attr('id');
        //alert(value);
        if(this.checked) {
          valArray.push(value);   // record the value of the checkbox to valArray
        } else {
          if(valArray.length == 0){
            //alert('vacio')
            $('#empleados_asignados_datos').html(valArray+ ' || '+data)
          }
            //valArray.pop(value);    // remove the recorded value of the checkbox
            removeA(valArray, value);

        }
        if(valArray.length == 0){
          $('#empleados_asignados').hide();

        }else if(valArray.length>0){
            $('#empleados_asignados').show();
            //get_empleado_
            $.ajax({
              type: "POST",
              url: "viaticos/php/back/listados/get_empleado_asignado.php",
              //dataType: 'html',
              data: { id_persona: valArray.toString()},
              success:function(data) {
                  //$("#aldea").html(data);
                  console.log(data);
                  $('#empleados_asignados_datos').html(data)
              }
            });

        }


    });
}
function remover_empleado_lista(value){
  /*var i = valArray.indexOf( value );
  valArray.splice( i, 1 );*/
  //removeA(valArray,value);
  //document.getElementById(value).checked = false;
  /*for( var i = 0; i < valArray.length; i++){
    //alert(valArray[i]);
    if ( valArray[i] == value) {
      //alert(valArray[i]);
      valArray.splice(i, 1);
    }
  }*/


  //$("#"+value).attr('checked', false);

  //inicio
  //table_empleados_asignar.$("input[type=checkbox]").prop("checked", false);
  $.each(table_empleados_asignar.$('input[type=checkbox]:checked'), function(){
    var data = table_empleados_asignar.row($(this).parents('tr')).data();
    //parametro+=''+data['DT_RowId']+',';
    valor=data['DT_RowId'];
    if(valor==value){
      //alert('deschequeado: '+ valor)
      removeA(valArray, value);
      $(this).prop("checked", false);

    }

  });
  /*for( var i = 0; i < valArray.length; i++){
    //alert(valArray[i]);
    if ( valArray[i] == value) {
      //alert(valArray[i]);
      valArray.splice(i, 1);
    }
  }*/
  $.each(table_empleados_asignar.$('input[type=checkbox]:not(:checked)'), function(){
    var data = table_empleados_asignar.row($(this).parents('tr')).data();
    //parametro+=''+data['DT_RowId']+',';
    if(valArray.length == 0){
      //alert('vacio')
      //$('#empleados_asignados').html('');
      $('#empleados_asignados_datos').html(valArray+ ' || '+data)
      //$('input[type="checkbox"]', table_empleados_asignar.cells().nodes()).prop('checked',false);
    }
    value=data['DT_RowId'];
    removeA(valArray, value);
  });

  if(valArray.length == 0){
    table_empleados_asignar.columns().checkboxes.deselect(true);
    $('#empleados_asignados').hide();


  }else if(valArray.length>0){
      $('#empleados_asignados').show();
      //get_empleado_
      $.ajax({
        type: "POST",
        url: "viaticos/php/back/listados/get_empleado_asignado.php",
        //dataType: 'html',
        data: { id_persona: valArray.toString()},
        beforeSend:function(){
          $("#empleados_asignados_datos").removeClass('slide_up_anim');
          $("#empleados_asignados_datos").html('<div class="loaderr"></div>');
        },
        success:function(data) {
            //$("#aldea").html(data);
            console.log(data);
            $('#empleados_asignados_datos').html(data)
        }
      });

  }
}
function mostar_formulario_anterior(){
  if( $('#chk_otro_formulario').is(':checked') )
  {
    $('#formulario_anterior').show();
    //alert('cheked')
    //document.getElementById("formulario_anterior").hidden=false
  }else{
    $('#formulario_anterior').hide();
    //alert('uncheck')
    //document.getElementById("formulario_anterior").hidden=true
  }
}

function mostar_formulario_confirma(){
  if( $('#chk_confirma').is(':checked') )
  {
    $('#formulario_confirma').show();
    $('#lbl_chk').text('NO');
    get_departamentos(2);
    //alert('cheked')
    //document.getElementById("formulario_anterior").hidden=false
  }else{
    $('#formulario_confirma').hide();
    $('#lbl_chk').text('SI');
    //alert('uncheck')
    //document.getElementById("formulario_anterior").hidden=true
  }
}

function nuevo_viatico(){
  //primer tab

  fecha_comision=$('#id_fecha_comision').val();
  fecha_salida=$('#id_fecha_salida').val();
  hora_salida=$('#id_hora_salida').val();
  fecha_regreso=$('#id_fecha_regreso').val();
  hora_regreso=$('#id_hora_regreso').val();

  //segundo tab
  //id_tipo_nombramiento=$('#id_tipo_nombramiento').val();
  //id_tipo_evento=$('#id_tipo_evento').val();
  motivo=$('#motivo').val();
  id_fecha_cheque=$('#id_fecha_cheque').val();
  id_hora_cheque=$('#id_hora_cheque').val();

  //tercer tab
  id_funcionario=$('#id_funcionario').val();
  pais=$('#pais').val();
  departamento=$('#departamento').val();
  municipio=$('#municipio').val();
  aldea=$('#aldea').val();
  beneficios=$('#beneficios').val();
  observaciones=$('#observaciones').val();

  extension=0;
  formulario_anterior=0;

  if( $('#chk_otro_formulario').is(':checked') )
  {
    extension=1;
    formulario_anterior=$('#id_formulario_anterior').val();
  }

  // tabla
  //total = $("input[type=checkbox]:checked").length;

  total = table_empleados_asignar.column(3).checkboxes.selected().length;
  var rows_selected = table_empleados_asignar.column(3).checkboxes.selected().length;
  //alert(rows_selected);


  //alert($('#pais').val());

  if($('#pais').val()==0)
  {
    Swal.fire(
      'Atención!',
      "Debe seleccionar correctamente el país",
      'error'
    );
  }else{
    if($('#departamento').val()=='' || $('#departamento').val()==0){
      Swal.fire(
        'Atención!',
        "Debe seleccionar correctamente el departamento",
        'error'
      );
    }else{
      if($('#municipio').val()=='' || $('#municipio').val()==0){
        Swal.fire(
          'Atención!',
          "Debe seleccionar correctamente el municipio",
          'error'
        );
      }else{
        if($('#beneficios').val()==0){
          Swal.fire(
            'Atención!',
            "Debe seleccionar correctamente los beneficios",
            'error'
          );
        }else{
          //inicio
          if(total == 0)
          {

            Swal.fire(
              'Atención!',
              "Debe seleccionar al menos un empleado",
              'error'
            );
          }else {
            if(!document.querySelector('input[name="empleoactual"]:checked')) {
              Swal.fire(
                'Atención!',
                "Debe seleccionar al empleado que se le generará el cheque",
                'error'
              );
            }
            else{
              Swal.fire({
                title: '<strong></strong>',
                text: "¿Desea generar la solicitud del viático?",
                type: 'question',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Guardar!'
              }).then((result) => {
                  if (result.value) {

                        //alert(data);
                          //$("#aldea").html(data);



                          var reng_num=1;
                          var bln_cheque=1;

                          id_check =($('input[type=radio][name=empleoactual]:checked').attr('id'))
                          valor_cheque=$('#id_check').val();
                          valor_cheque=id_check.replace("rd_", "");

                          var total = valArray.length;
                          var inc =0;
                          for(var x =0; x < total; x ++){

                            console.log(valArray[x]);

                            $.ajax({
                              type: "POST",
                              url: "viaticos/php/back/viatico/crear_viatico_global.php",
                              //dataType: 'html',
                              data: { fecha_comision,fecha_salida,hora_salida,fecha_regreso,hora_regreso,motivo,id_fecha_cheque,id_hora_cheque,id_funcionario,pais,departamento,municipio,aldea,
                                      beneficios,observaciones,formulario_anterior,extension,
                                      reng_num, id_empleado:valArray[x], bln_cheque
                                    },
                              success:function(data) {

                              }
                            }).done( function() {

                            }).fail( function( jqXHR, textSttus, errorThrown){
                              alert(errorThrown);
                            });

                          }
                          //if(inc==total){
                            Swal.fire({
                              type: 'success',
                              title: 'Viático generado',
                              showConfirmButton: false,
                              timer: 2000
                            });
                            recargar_nombramientos($('#id_filtro_detalle').val());
                            $('#modal-remoto-lg').modal('hide');
                          //}

                          //$('#empleados_asignados_datos').html(valArray+ ' || '+data)



                  }

              });
            }


          }
      //fin
        }
      }
    }

  }
}

function au_solicitud(estado){
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
              if(estado==933 || estado==934 || estado==935 || estado==1635 || estado==936 || estado==163 || estado==938 || estado==1643 || estado==7972){
                get_viatico_detalle_encabezado('viatico_detalle_encabezado');
                recargar_nombramientos($('#id_filtro_detalle').val());
              }else if(estado==939 || estado==940){
                get_viatico_detalle_encabezado('empleados_por_viatico');
                recargar_nombramientos($('#id_filtro_detalle').val());
              }
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

}

function precesar_nombramiento(estado){
  formulario=$('#id_viatico').val();
  var nFilas_total = $("#tb_montos tbody tr").length;
  if(nFilas_total>0){
    if($('#pais_tipo').text()=='Extranjero' || $('#pais_tipo').text()=='EXTERIOR'){
      if($('#tasa_cambiaria').val()!='' && $('#tasa_cambiaria').val()>0){
        cambio=$('#tasa_cambiaria').val();
        procesa_formulario_accion(formulario,estado,cambio)
      }else{
        Swal.fire({
          type: 'error',
          title: '¡Debe ingresar correctamente el tipo de cambio!',
          showConfirmButton: false,
          timer: 2000
        });
      }
    }else{
      //alert($('#pais_tipo').text());
      if($('#sub_total').text()==0){
        estado=7959;
      }
      procesa_formulario_accion(formulario,estado,1)
    }
  }
  else{
    Swal.fire({
      type: 'error',
      title: '¡Debe validar los montos a asignar!',
      showConfirmButton: false,
      timer: 2000
    });
  }
}

function procesa_formulario_accion(formulario,estado,cambio){
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
              recargar_nombramientos($('#id_filtro_detalle').val());

              $("#tb_montos tbody tr").each(function(index, element){

                 id_row = ($(this).attr('id'));
                 porcentaje=$(element).find("td").eq(3).html();
                 monto=$(element).find("td").eq(5).html();

                 anticipo=0;
                 if($('#ac'+id_row).prop('checked')){
                   anticipo=1;
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
                    anticipo:anticipo
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
              get_viatico_detalle_encabezado('viatico_detalle_encabezado');
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
}


function elaborar_cheque(tipo)
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
            //$('#empleados_asignados_datos').html(data)
            get_viatico_detalle_encabezado('viatico_detalle_encabezado');
            recargar_nombramientos($('#id_filtro_detalle').val());
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
    input: 'number',
    inputPlaceholder: 'Agregar',
    showLoaderOnConfirm: true,
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Elaborar!',
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
  }
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
            get_viatico_detalle_encabezado('viatico_detalle_encabezado');
            recargar_nombramientos($('#id_filtro_detalle').val());
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

}

function cargar_menu_impresion(correlativo,tipo){
  var menu = '';
  if(tipo==1){
    menu='#menu';
  }else
  if(tipo==2){
    menu='#menu2';
  }else{
    menu='#menu3'
  }

  $.ajax({
    type: "POST",
    url: "viaticos/php/front/viaticos/menu_impresion.php",
    //dataType: 'html',
    data: {
      correlativo
    },
    beforeSend:function(){
      $(menu+correlativo).html('Cargando');
    },
    success:function(data) {
        $(menu+correlativo).html(data);

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

//mostrar_numero_de_vuelo
function mostrar_numero_de_vuelo(id){
  if($('#'+id).val()==941){
    if(id=='id_tipo_salida'){
      $('#num_vuelo1').show();
    }else{
      $('#num_vuelo2').show();
    }
  }else{
    if(id=='id_tipo_salida'){
      $('#num_vuelo1').hide();
    }else{
      $('#num_vuelo2').hide();
    }
  }

}

//constancia
function generar_constancia(){

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
                        get_viatico_detalle_encabezado('empleados_por_viatico');
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

}

//liquidacion
function generar_liquidacion(){

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


              Swal.fire({
              title: '<strong>¿Desea actualizar liquidación?</strong>',
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
                url: "viaticos/php/back/viatico/liquidacion_reintegro.php",
                data: {
                  vt_nombramiento,
                  id_persona,
                  id_renglon,
                  id_reintegro_hospedaje,
                  id_reintegro_alimentacion,
                  id_otros_gastos
                }, //f de fecha y u de estado.

                beforeSend:function(){
                              //$('#response').html('<span class="text-info">Loading response...</span>');
                              //alert('message_before')

                      },
                      success:function(data){
                        get_viatico_detalle_encabezado('empleados_por_viatico');
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

}


function generar_constancia_(estado){
  formulario=$('#id_viatico').val();
  var nFilas_total = $("#tb_empleados_asistieron tbody tr").length;
  if(nFilas_total>0){
    if($('#id_fecha_salida_c').val()!='' && $('#id_hora_salida_c').val()!='' && $('#id_fecha_regreso_c').val()!='' && $('#id_hora_regreso_c').val()!=''){
      fecha_ini=$('#id_fecha_salida_c').val();
      hora_ini=$('#id_hora_salida_c').val();
      fecha_fin=$('#id_fecha_regreso_c').val();
      hora_fin=$('#id_hora_regreso_c').val();
      //inicio
      Swal.fire({
        title: '<strong></strong>',
        text: '¿Desea generar constancia a este nombramiento?',
        type: 'question',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Generar constancia!'
      }).then((result) => {
          if (result.value) {

                  //$("#aldea").html(data);
                  //console.log(data);
                  //$('#empleados_asignados_datos').html(data)
                  recargar_reporte();
                  $.each($('input[type=checkbox]:checked'), function(){
                    var gafete=($(this).attr('id'));
                    confirma=1;
                    $.ajax({
                      type: "POST",
                      url: "viaticos/php/back/viatico/constancia_nombramiento_detalle.php",
                      data:
                      {
                        vt_nombramiento:formulario,
                        id_empleado:gafete,
                        confirma:confirma,
                        fecha_ini:fecha_ini,
                        hora_ini:hora_ini,
                        fecha_fin:fecha_fin,
                        hora_fin:hora_fin
                      },
                      beforeSend:function(){},
                      success:function(data){}
                    }).done( function() {
                    }).fail( function( jqXHR, textSttus, errorThrown){
                    });
                  });
                  $.each($('input[type=checkbox]:not(:checked)'), function(){
                    var gafete=($(this).attr('id'));
                    confirma=0;
                    $.ajax({
                      type: "POST",
                      url: "viaticos/php/back/viatico/constancia_nombramiento_detalle.php",
                      data:
                      {
                        vt_nombramiento:formulario,
                        id_empleado:gafete,
                        confirma:confirma,
                        fecha_ini:fecha_ini,
                        hora_ini:hora_ini,
                        fecha_fin:fecha_fin,
                        hora_fin:hora_fin
                      },
                      beforeSend:function(){},
                      success:function(data){}
                    }).done( function() {
                    }).fail( function( jqXHR, textSttus, errorThrown){
                    });
                  });

                  $.ajax({
                    type: "POST",
                    url: "viaticos/php/back/viatico/au_nombramiento.php",
                    //dataType: 'html',
                    data: {
                      vt_nombramiento:formulario,
                      estado:estado,
                      cambio:0
                    },
                    success:function(data) {


                  Swal.fire({
                    type: 'success',
                    title: '¡Constancia generada!',
                    showConfirmButton: false,
                    timer: 2000
                  });

              }
            });
      }

      });
      //fin
    }else{
      Swal.fire({
        type: 'error',
        title: '¡Debe llenar los campos requeridos con asterisco!',
        showConfirmButton: false,
        timer: 2000
      });
    }


  }
  else{
    Swal.fire({
      type: 'error',
      title: '¡Debe seleccionar los empleados que asistieron o no!',
      showConfirmButton: false,
      timer: 2000
    });
  }
}

function show_nombramientos_pendientes_count(){
  //conteo=table_pendientes.rows().count();
  var conteo=0;
  $.ajax({
    type: "POST",
    dataType: "json",
    url: "viaticos/php/back/listados/get_solicitudes.php",
    data:
    {
      tipo:2
    },
    beforeSend:function(){},
    success:function(data){
      conteo = data.iTotalRecords;
      $('.contar').html(conteo);
      if(conteo > 0){
        document.getElementById("ns").style.visibility = "visible";
        /*for(var x =0; x < conteo; x ++){
          console.log(data.aaData[x].DT_RowId);
        }*/

      }


      //alert(conteo);
    }
  }).done( function() {
  }).fail( function( jqXHR, textSttus, errorThrown){
  });


  //alert(conteo);
}

function sustituir_empleado(){
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
              get_viatico_detalle_encabezado('empleados_por_viatico');
            }

            //alert(conteo);
          }
        }).done( function() {
        }).fail( function( jqXHR, textSttus, errorThrown){
        });

      }
    });


  //alert(conteo);
}

function confirmar_ausencia(){
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
            get_viatico_detalle_encabezado('empleados_por_viatico');

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
function confirmar_lugar(){

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
                          get_viatico_detalle_encabezado('empleados_por_viatico');
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
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  div = document.getElementById("pendientes_div");
  a = div.getElementsByTagName("li");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

function sumar_restar(id){
  var subtotal = 0;
  var total_col2 = 0;
  var total = 0;
  //alert($("#tb_montos").find('td[data-id]').click())
  //var id = $(this).closest('#tb_montos tbody tr').attr('id');
  alert(id);
  if ($('#ac'+id).is(':checked') ) {
    console.log('suma');
}else{
  //total_col2-=tdid;
  console.log('resta');
}
  //alert(total_col2);
  /*$('#tb_montos tr').click(function(e) {
    e.stopPropagation();
    var $this = $(this);
    var trid = $this.closest('tr').data('id');
    //alert("TR ID " + trid);
    var tdid = $this.find('td[data-id]').data('id');
    //alert("TD ID " + tdid);
    alert('#ac'+trid);
    if ($('#ac'+trid).is(':checked') ) {
      total_col2+=tdid;
      alert(total_col2);
  }else{
    total_col2-=tdid;
    alert(total_col2);
  }

});
$('#sub_total').text(total_col2);
//alert(total_col2);*/
}

function recalcular_suma(){

  var subtotal = 0;
  var total_col2 = 0;
  var total = 0;
  /*$('#exceltable tr').(function(index, element){
    var serie = $(element).find("td").eq(0).html();
  }*/


  $.each($('input[type=checkbox]:checked'), function(){

    var $this = $(this).closest('tr').data('id');
    var trid = $this.closest('tr').data('id');
    var tdid = $this.find('td[data-id]').data('id');

    alert("TR ID " + $this);
  alert("TD ID " + tdid);
    alert($(id).find("td").eq(0).html());
  });
  $.each($('input[type=checkbox]:not(:checked)'), function(){
    var id = $(this).closest('tr').prop('id');
    alert(id);
  });
  $('#sub_total').text(total_col2.toFixed(2));

}

/*function editar_fecha(){
  $('.f_inicio').editable({


  });
}*/
function confirmar_ausencia_singular(vt_nombramiento,empleado_actual,id_renglon){
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
            get_viatico_detalle_encabezado('empleados_por_viatico');

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

function buscar_nombramiento(){
  var form='';

  Swal.fire({
  title: '<strong>¿Quiere elaborar el cheque a este nombramiento?</strong>',
  text: "",
  type: 'question',
  input: 'number',
  inputPlaceholder: 'Agregar',
  showLoaderOnConfirm: true,
  showCancelButton: true,
  confirmButtonColor: '#28a745',
  cancelButtonText: 'Cancelar',
  confirmButtonText: '¡Si, Elaborar!',
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
}
  }).then((result) => {
  if (result.value) {
    $.ajax({
      type: "GET",
      url: "viaticos/php/front/viaticos/viatico_detalle.php",
      dataType: 'html',
      data: { id_viatico:form, tipo_filtro:1},
      success: function (response) {
        $('.modal-content').html(response);
        $('#modal-remoto-lgg2').modal('show');
      }
    });
  }
})




}

function conversion(){
  operacion=0;
  operacion=($('#sub_total').text()*$('#tasa_cambiaria').val())
  $('#conversion').text(operacion);
}

function contar_emptys(){
  var count=0;
  $("#tb_lugares tbody tr").each(function(index, element){
    id_row = ($(this).attr('id'));
    if($('#f_ini'+index).text()=='Empty' || $('#f_fin'+index).text()=='Empty' || $('#h_ini'+index).text()=='Empty' || $('#h_fin'+index).text()=='Empty'){
      count++;
    }
    console.log(count);
  });


    if(count==0){
      return true;
    }else{
      return false;
    }


}

function agregarLugares(){


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
                          get_viatico_detalle_encabezado('empleados_por_viatico');
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
}
