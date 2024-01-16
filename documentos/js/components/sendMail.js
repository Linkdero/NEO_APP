function sendMail(emisor, receptor, body, subject){

  $.ajax({
    type: "POST",
    url: "https://saas.gob.gt/mailer/",
    dataType: 'json',
    data: {
      emisor:emisor,
      receptor:receptor,
      body:body,
      subject:subject
      /*,
      subject:subject,
      body:body*/

    }, //f de fecha y u de estado.

    beforeSend:function(){
    },
    success:function(data){
      if(data.msg == 'OK'){
        Swal.fire({
          type: 'success',
          title: data.message,
          showConfirmButton: false,
          timer: 1100
        });
      }else{
        Swal.fire({
          type: data.message,
          title: data.id,
          showConfirmButton: false,
          timer: 1100
        });
      }
      console.log(data);
    }
  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){
    //alert(errorThrown);

  });
}
