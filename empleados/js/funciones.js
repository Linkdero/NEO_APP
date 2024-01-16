function mostrar_formulario_funcional(){
  if( $('#chk_funcional').is(':checked') )
  {
    $('#formulario_funcional').show();
    //alert('cheked')
    //document.getElementById("formulario_anterior").hidden=false
  }else{
    $('#formulario_funcional').hide();
    //alert('uncheck')
    //document.getElementById("formulario_anterior").hidden=true
  }
}
