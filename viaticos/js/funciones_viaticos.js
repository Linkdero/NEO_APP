function sumar_importes(){
  var subtotal = 0;
  var total_col2 = 0;
  var total = 0;
  //Recorro todos los tr ubicados en el tbody
  $('#table_cantidades tbody').find('tr').each(function (i, el) {
    total_col2+= parseFloat($(this).find('td').eq(4).text());

  });
    //subtotal=total_col2.toFixed(2);
    $('#sub_total').text(total_col2.toFixed(2));
    total = $('#sub_total').text()*$('#personas').text();

    $('#_total').text(total.toFixed(2));

    //Muestro el resultado en el th correspondiente a la columna
    //$('#tb_movimientos_producto_egreso tfoot tr th').eq(5).text("Total " + total_col1);
    //$('#tb_movimientos_producto_egreso tfoot tr th').eq(1).text("Total " + total_col2);
}

function sumar_montos(){
  var subtotal = 0;
  var total_col2 = 0;
  var total = 0;
  //Recorro todos los tr ubicados en el tbody
  $('#tb_montos tbody').find('tr').each(function (i, el) {
    total_col2+= parseFloat($(this).find('td').eq(5).text());

  });
    //subtotal=total_col2.toFixed(2);
    $('#sub_total').text(total_col2.toFixed(2));
    //total = $('#sub_total').text()*$('#personas').text();

    //$('#_total').text(total.toFixed(2));

    //Muestro el resultado en el th correspondiente a la columna
    //$('#tb_movimientos_producto_egreso tfoot tr th').eq(5).text("Total " + total_col1);
    //$('#tb_movimientos_producto_egreso tfoot tr th').eq(1).text("Total " + total_col2);
}

function cargar(){
  var selectValues =
          { "0": '0',
           "1": '1',
           "2": '2',
           "3":  '3',
           "4":  '4',
           "5":  '5',
           "6":  '6',
           "7":  '7',
           "8":  '8',
           "9":  '9',
           "10":  '10',
           "11":  '11',
           "12":  '12',
           "13": '13'}
          ;

          var $mySelect1 = $('#cmb_hospedaje');
          var $mySelect2 = $('#cmb_desayuno');
          var $mySelect3 = $('#cmb_almuerzo');
          var $mySelect4 = $('#cmb_cena');
      //
      $.each(selectValues, function(key, value) {
        var $option1 = $("<option/>", {value: key,text: value});
        var $option2 = $("<option/>", {value: key,text: value});
        var $option3 = $("<option/>", {value: key,text: value});
        var $option4 = $("<option/>", {value: key,text: value});
        $mySelect1.append($option1);
        $mySelect2.append($option2);
        $mySelect3.append($option3);
        $mySelect4.append($option4);
      });



}

function cantidades(this_code){
  $('#table_cantidades tbody').on( 'click', '.combo', function () {

  this_code = ($(this).attr('id'));
  //$("#"+this_code).on('change', function(){
    //alert('hello');
    cantidad = $('#'+this_code).val();

    precio = $(this).closest('tr').find('td:eq(1)').text();
    importe = precio*cantidad;
    importe = importe.toFixed(2);
    //var res = str.replace("pr", "tr");
    $(this).closest('tr').find('td:eq(3)').text(importe)
    sumar_importes();
  //});

  });
}

function alerta(this_code){
  $('#table_cantidades tbody').on( 'click', '#'+this_code, function () {

  //this_code = ($(this).attr('id'));
  //$("#"+this_code).on('change', function(){
    //alert('hello');
    cantidad = $('#'+this_code).val();

    precio = $(this).closest('tr').find('td:eq(2)').text();
    importe = precio*cantidad;
    importe = importe.toFixed(2);
    //var res = str.replace("pr", "tr");
    $(this).closest('tr').find('td:eq(4)').text(importe)
    sumar_importes();
  //});

  });
}
