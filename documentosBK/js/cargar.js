function cargarDestinatariosrender(rowid,docto_id,row){

  $.ajax({
    type: "POST",
    url: "documentos/php/back/documento/get_destinatarios_render.php",
    dataType: 'html',
    data: {

    },
    beforeSend:function(){
      $('#'+docto_id).html('Cargando');
    },
    success:function(data) {
    //return data;

    console.log(row)
    $('#'+docto_id).html(data);

    }
  });

}
