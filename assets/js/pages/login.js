$(document).ready(function(){
$('#login_form').on('submit', function(event){

  event.preventDefault();
  $.ajax({
   url:"process_login.php",
   method:"POST",
   dataType: 'json',
   data:$(this).serialize(),
   beforeSend:function(){
     $('#loading').fadeIn(0);
   },
   success:function(data){
     console.log(data);
    if(data.login == false){
      $('#loading').fadeOut(0);
      $('#error_message').html(data.msg);
    }else{
      window.location = 'principal.php';
    }
   }
  })
 });

});
