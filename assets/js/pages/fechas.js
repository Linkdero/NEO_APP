function establecer_fecha(input,valor){

  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy = today.getFullYear();


  today = dd + '-' + mm + '-' + yyyy;

  var days_ago1 = new Date();
  days_ago1.setDate(days_ago1.getDate()-5);
  var dd1 = String(days_ago1.getDate()).padStart(2, '0');
  var mm1 = String(days_ago1.getMonth() + 1).padStart(2, '0'); //January is 0!
  var yyyy1 = days_ago1.getFullYear();
  days_ago= dd1 + '-' + mm1 + '-' + yyyy1;

  if(valor==1){
    $('#'+input).val(days_ago1);
  }else{
    $('#'+input).val(today);
  }


}
