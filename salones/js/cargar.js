function mostrar_mobiliario() {
  if ($('#chk_mobiliario').is(':checked')) {
    $('#datos_mobiliario').show();
  } else {
    $('#datos_mobiliario').hide();
    $('#id_persona_diferente').val('');
    //get_datos_empleado(5555,1);
  }
}

function mostrar_equipo() {
  if ($('#chk_equipo').is(':checked')) {
    $('#datos_equipo').show();
  } else {
    $('#datos_equipo').hide();
    $('#id_persona_diferente').val('');
    //get_datos_empleado(5555,1);
  }
}
