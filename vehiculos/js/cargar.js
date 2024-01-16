function get_datos_empleado(tipo, subtipo) {
  //alert('message');
  if (tipo == 5555) {
    $.ajax({
      type: "POST",
      url: "insumos/php/back/insumos/get_empleado.php",
      //dataType:'json',
      data: {
        id_persona: function () { return $('#id_persona_diferente').val() },
        tipo: tipo, subtipo: subtipo
      },
      beforeSend: function () {
        $('#datos_diferente').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
      },
      success: function (data) {
        //alert(data);
        $('#datos_diferente').html(data);

      }
    }).done(function () {

    }).fail(function (jqXHR, textSttus, errorThrown) {

    });
  } else {
    $.ajax({
      type: "POST",
      url: "insumos/php/back/insumos/get_empleado.php",
      //dataType:'json',
      data: {
        id_persona: function () { return $('#id_persona').val() },
        tipo: tipo, subtipo: subtipo
      },
      beforeSend: function () {
        $('#datos').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
      },
      success: function (data) {
        //alert(data);
        $('#datos').html(data);
        if (tipo == 4351) {
          $('#tipo_movimiento').on('change', function () {
            verificar_chequeados();
          });
          reload_movimientos_empleado_by_bodega();
          /*if ( ! table_movimientos_empleado.data().count() ) {
            $('#btn_save_in').fadeOut('slow');
          }else
          {
            $('#btn_save_in').fadeIn('slow');
          }*/
          //codigos.splice(0,codigos.length);
        } if (subtipo == 2) {
          reload_movimientos_empleado_by_bodega_resguardo();
        }
        else if (subtipo == 3) {

          $('#tipo_movimiento').on('change', function () {
            //alert($('#tipo_movimiento').val());
            reload_insumos_solvencia();
          });
          //alert('solvencia');
          reload_insumos_solvencia();

        }
        else {
          //codigos.splice(0,codigos.length);
        }

      }
    }).done(function () {


    }).fail(function (jqXHR, textSttus, errorThrown) {

    });
  }

}

function verificar_funcion() {
  if (typeof clear_data === 'function') {
    //Es seguro ejecutar la función
    clear_data();
  }
}

function get_vales_combustible() {
  $.ajax({
    type: "POST",
    url: "vehiculos/php/front/vales/get_vales_por_Fecha.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      $('#_data').empty();
      $('#titulo').text('Movimientos');
      $('#_data').html(data);
      $('#b_1').show();
      $('#b_2').hide();
      $('#b_3').hide();
      $('#b_4').hide();
      $('#b_5').hide();
      verificar_funcion();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_cupones_entregados() {
  $.ajax({
    type: "POST",
    url: "vehiculos/php/front/cupones/get_cupones_entregados.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      $('#_data').empty();
      $('#titulo').text('Movimientos');
      $('#_data').html(data);
      $('#b_1').show();
      $('#b_2').hide();
      $('#b_3').hide();
      $('#b_4').hide();
      $('#b_5').hide();
      verificar_funcion();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_cupones_ingresados() {
  $.ajax({
    type: "POST",
    url: "vehiculos/php/front/cupones/get_cupones_ingresados.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      $('#_data').empty();
      $('#titulo').text('Movimientos');
      $('#_data').html(data);
      $('#b_1').show();
      $('#b_2').hide();
      $('#b_3').hide();
      $('#b_4').hide();
      $('#b_5').hide();
      verificar_funcion();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}



function get_all_insumos() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/insumos/get_all_insumos.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#_data').empty();
      $('#_data').html(data);
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_all_insumos_by_bodega() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/insumos/get_all_insumos_by_bodega.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#_data').empty();
      $('#titulo').text('Insumos');
      $('#_data').html(data);
      $('#b_1').hide();
      $('#b_2').show();
      $('#b_3').hide();
      $('#b_4').hide();
      $('#b_5').hide();
      verificar_funcion();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_all_insumos_resguardo_by_bodega() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/insumos/get_all_insumos_resguardo_by_bodega.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#_data').empty();
      $('#titulo').text('Resguardo');
      $('#_data').html(data);
      $('#b_1').hide();
      $('#b_2').hide();
      $('#b_3').hide();
      $('#b_4').hide();
      $('#b_5').hide();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_egreso_insumo() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/insumos/insumo_egreso.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#_data').empty();
      $('#titulo').text('Salidas');
      $('#_data').html(data);
      $('#b_1').hide();
      $('#b_2').hide();
      $('#b_3').show();
      $('#b_4').hide();
      $('#b_5').hide();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}
function get_ingreso_insumo() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/insumos/insumo_ingreso.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#_data').empty();
      $('#titulo').text('Ingresos');
      $('#_data').html(data);
      $('#b_1').hide();
      $('#b_2').hide();
      $('#b_3').hide();
      $('#b_4').show();
      $('#b_5').hide();
      verificar_funcion();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}
function get_entrega_insumo() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/insumos/insumo_entrega.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#_data').empty();
      $('#titulo').text('Entrega');
      $('#_data').html(data);
      $('#b_1').hide();
      $('#b_2').hide();
      $('#b_3').hide();
      $('#b_4').hide();
      $('#b_5').show();
      verificar_funcion();
    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}
function get_datos_empleado_by_transaccion() {
  //alert('message');
  $.ajax({
    type: "POST",
    url: "insumos/php/back/insumos/get_empleado_by_transaccion.php",
    //dataType:'json',
    data: { transaccion: function () { return $('#transaccion').val() } },
    beforeSend: function () {
      $('#datos').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#datos').html(data);


    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_data() {
  //alert('message');
  $.ajax({
    type: "POST",
    url: "insumos/php/back/insumos/get_insumo_by_id.php",
    dataType: 'json',
    data: { id_insumo: function () { return $('#id_insumo').val() } },
    beforeSend: function () {
      $('.data_').html("<div class='fa fa-sync fa-spin text-info' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('.num').html(data.sicoin);
      $('.tipo').html(data.tipo);
      $('.marca').html(data.marca);
      $('.modelo').html(data.modelo);
      $('.serie').html(data.serie);
      $('.existencia').html(data.existencia);


    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}


//Reportes
function get_all_totales() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/reportes/get_totales_por_status.php",
    //dataType:'json',

    beforeSend: function () {
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#titulo').text('Totales por Estado');
      $('#__data').html(data);

    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function get_all_totales_por_direccion() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/reportes/get_totales_por_direccion.php",
    //dataType:'json',

    beforeSend: function () {
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#titulo').text('Totales por Dirección');
      $('#__data').html(data);

    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

//Solvencia
function get_nueva_solvencia() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/solvencia/solvencia_nueva.php",
    //dataType:'json',

    beforeSend: function () {
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      //alert(data);
      $('#titulo').text('Solvencia Nueva');
      $('#__data').html(data);

    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}

function mostrar_empleado_diferente() {
  if ($('#chk_otro_empleado').is(':checked')) {
    $('#datos_empleado_entrega').show();
  } else {
    $('#datos_empleado_entrega').hide();
    $('#id_persona_diferente').val('');
    get_datos_empleado(5555, 1);
  }
}
function get_bodegas() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/bodegas/bodegas.php",
    //dataType:'json',

    beforeSend: function () {
      $('#_bodegas').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      $('#_bodegas').html(data);
      //alert(data);

    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}


function get_empleados_asignados() {
  $.ajax({
    type: "POST",
    url: "insumos/php/front/reportes/empleados_asignados.php",
    //dataType:'json',

    beforeSend: function () {
      $('#__data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success: function (data) {
      $('#__data').html(data);
      //alert(data);

    }
  }).done(function () {


  }).fail(function (jqXHR, textSttus, errorThrown) {

  });
}
