function start_countdown()
{
 var counter=60*10;
 myVar= setInterval(function()
 {
  if(counter>=0)
  {
   //document.getElementById("countdown").innerHTML="You Will Be Logged Out In <br>"+counter+" Sec";
  }
  if(counter==0)
  {
    swal({
      title: '<strong>Su sesión ha finalizado</strong>',
      text: "",
      type: 'success',
      showCancelButton: false,
      confirmButtonColor: '#28a745',
      cancelButtonText: 'Cancelar',
      confirmButtonText: '¡Si, Activar!'
      }).then((result) => {
      if (result.value) {

      }
    })
    $.ajax
    ({
      type:'post',
      url:'logout.php',
      data:{
       logout:"logout"
      },
      success:function(response)
      {
       window.location="";
      }
    });
   }
   counter--;
 }, 1000)
}
