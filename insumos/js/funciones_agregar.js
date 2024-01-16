$(document).ready(function(){
    $('#tipo').on('change', function(){
        var tipoID = $(this).val();
        if(tipoID){
            $.ajax({
                type:'POST',
                url:'insumos/php/back/insumos/get_tipo_marca.php',
                data:'tipo_id='+tipoID,
                success:function(html){
                  //alert(html);
                    $('#subtipo').html(html);
                    //$('#marca').html('<option value="">Select state first</option>');
                }
            });
        }else{
            $('#subtipo').html('<option value="">No hay Subtipos</option>');
            $('#tipo').html('<option value="">Seleccionar Tipo antes</option>');
        }
    });

    /*$('#subtipo').on('change', function(){
        var subtipoID = $(this).val();
        if(subtipoID){
            $.ajax({
                type:'POST',
                url:'insumos/php/back/insumos/get_tipo_marca.php',
                data:'sub_tipo_id='+subtipoID,
                success:function(html){
                  alert(html);
                    $('#marca').html(html);
                    //$('#modelo').html('<option value="">Select state first</option>');
                }
            });
        }else{
            $('#marca').html('<option value="">Select state first</option>');
            //$('#modelo').html('<option value="">Select state first</option>');
        }
    });*/

    $('#marca').on('change', function(){
        var marcaID = $(this).val();
        if(marcaID){
            $.ajax({
                type:'POST',
                url:'insumos/php/back/insumos/get_tipo_marca.php',
                data:'marca_id='+marcaID,
                success:function(html){
                  //alert(html);
                    $('#modelo').html(html);
                    //$('#marca').html('<option value="">Select state first</option>');
                }
            });
        }else{
            $('#marca').html('<option value="">Seleccionar</option>');
            $('#modelo').html('<option value="">Seleccionar marca antes</option>');
        }
    });


});

$(function(){

   $("input[name='excelfile']").on("change", function(){
     var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
     /*Checks whether the file is a valid excel file*/
     if (regex.test($("#excelfile").val().toLowerCase())) {
         var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
         if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {
             xlsxflag = true;
         }
         /*Checks whether the browser supports HTML5*/
         if (typeof (FileReader) != "undefined") {
             var reader = new FileReader();
             reader.onload = function (e) {
                 var data = e.target.result;
                 /*Converts the excel data in to object*/
                 if (xlsxflag) {
                     var workbook = XLSX.read(data, { type: 'binary' });
                 }
                 else {
                     var workbook = XLS.read(data, { type: 'binary' });
                 }
                 /*Gets all the sheetnames of excel in to a variable*/
                 var sheet_name_list = workbook.SheetNames;

                 var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                 sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/
                     /*Convert the cell value to Json*/
                     if (xlsxflag) {
                         var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                     }
                     else {
                         var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                     }
                     if (exceljson.length > 0 && cnt == 0) {
                         BindTable(exceljson, '#exceltable');
                         cnt++;
                         var nFilas = $("#exceltable tr").length;
                         if(nFilas>0){
                           $('#but').fadeIn("slow");
                         }
                         else{
                           $('#but').fadeOut("slow");
                         }
                     }
                 });
                 $('#exceltable').show();
             }
             if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                 reader.readAsArrayBuffer($("#excelfile")[0].files[0]);
             }
             else {
                 reader.readAsBinaryString($("#excelfile")[0].files[0]);
             }
         }
         else {
             alert("Sorry! Your browser does not support HTML5!");
         }
     }
     else {
         alert("Please upload a valid Excel file!");
     }
 });



});

 function BindTable(jsondata, tableid) {/*Function used to convert the JSON array to Html Table*/
     var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
     var row$ = '<tbody>';
     for (var i = 0; i < jsondata.length; i++) {
       row$+='<tr id="'+i+'">';
         for (var colIndex = 0; colIndex < columns.length; colIndex++) {
             var cellValue = jsondata[i][columns[colIndex]];
             if (cellValue == null)
                 cellValue = "";
                 row$+='<td id="'+cellValue+'">'+cellValue+'</td>';

             var serie= jsondata[i][columns[0]];
             var sicoin= jsondata[i][columns[1]];
             var existencia= jsondata[i][columns[2]];
             $.ajax({
               type: "POST",
               url: "insumos/php/back/insumos/get_insumo.php",
               dataType:'json',
               data: {
                 serie:serie
               },
               beforeSend:function(){
                 $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
               },
               success:function(data){

                 if(data.serie == serie){
                   console.log(data.serie+' : true');
                   //row$+='<td>'+cellValue+'</td>';
                   //$('#'+serie).addClass('danger');

                   //row$+='<td>'+cellValue+'</td>';
                   //row$.append($('<tr>')).html('<tr class=""><td>'+serie+'</td></tr>');
                 }
                 //alert(data.serie);
                 //alert(data);
                 //$('#datos').html(data);

                   }
                 });
                 //row$.append($('<tbody/>')).html('</tr>');

         }
         row$+='</tr>';

     }
     $(tableid).append(row$);
     verificar();
 }
 function BindTableHeader(jsondata, tableid) {/*Function used to get all column names from JSON and bind the html table header*/
     var columnSet = [];
     var headerTr$ = $('<tr/>');
     for (var i = 0; i < jsondata.length; i++) {
         var rowHash = jsondata[i];
         for (var key in rowHash) {
             if (rowHash.hasOwnProperty(key)) {
                 if ($.inArray(key, columnSet) == -1) {/*Adding each unique column names to a variable array*/
                     columnSet.push(key);
                     headerTr$.append($('<th/>').html(key));
                 }
             }
         }
     }
     $(tableid).append(headerTr$);
     return columnSet;
 }
 function verificar(){
   $('#exceltable tr').each(function(index, element){
      var serie = $(element).find("td").eq(0).html();
      $.ajax({
        type: "POST",
        url: "insumos/php/back/insumos/get_insumo.php",
        dataType:'json',
        data: {
          serie:serie
        },
        beforeSend:function(){
          $('.data').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
        },
        success:function(data){

          if(data.serie == serie){
            console.log(data.serie+' : true');
            //row$+='<td>'+cellValue+'</td>';
            //$('#'+serie).addClass('danger');

          }
          //alert(data.serie);
          //alert(data);
          //$('#datos').html(data);

            }
          });
    });

 }

 function contar_columnas(){



   var id_tipo_insumo = $('#tipo').val();
   var id_sub_tipo_insumo=$('#subtipo').val();
   var id_marca=$('#marca').val();
   var id_modelo=$('#modelo').val();
   var descripcion=$('#producto_desc').val();

   //alert(id_tipo_insumo);


   //alert(id_tipo_insumo);
   var nFilas = $("#exceltable tr").length;
     var nColumnas = $("#exceltable tr:last td").length;
     var msg = "Filas: "+nFilas+" - Columnas: "+nColumnas;
     //alert(msg);
     if(nColumnas==3){

             /*$("#exceltable td").each(function(){
       if(Validar($(this).text())==true){
       return false;
       }else{
       console.log($(this).text());
       }
       });*/
       if(id_tipo_insumo!=0){
         if(id_sub_tipo_insumo!=0){
           if(id_marca!=0){
             if(id_marca!=0){
               if(descripcion!=''){
                 var nFilas = $("#exceltable tbody tr td.danger").length;

                 if(nFilas==0){
                   Swal.fire({
                   title: '<strong>¿Desea crear estos productos?</strong>',
                   text: "¡No podrá revertir esto!",
                   type: 'warning',
                   showCancelButton: true,
                   confirmButtonColor: '#28a745',
                   cancelButtonText: 'Cancelar',
                   confirmButtonText: '¡Si, Crear!'
                   }).then((result) => {
                     if (result.value) {


                       //alert(descripcion);

                       $.ajax({
                         type: "POST",
                         url: "insumos/php/back/funciones/agregar_insumo.php",
                         data: {
                           id_tipo_insumo:id_tipo_insumo,
                           id_sub_tipo_insumo:id_sub_tipo_insumo,
                           id_marca:id_marca,
                           id_modelo:id_modelo,
                           descripcion:descripcion
                         }, //f de fecha y u de estado.

                         beforeSend:function(){
                                       //$('#response').html('<span class="text-info">Loading response...</span>');


                               },
                               success:function(data){
                                 //alert(data);
                                 var id_producto_insumo=data;

                                 Swal.fire({
                                   type: 'success',
                                   title: 'Producto(s) creado(s)',
                                   showConfirmButton: false,
                                   timer: 1100
                                 });

                                 $('#exceltable tr').each(function(index, element){
                                    var serie = $(element).find("td").eq(0).html(),
                                        sicoin = $(element).find("td").eq(1).html(),
                                        existencia = $(element).find("td").eq(2).html()/*,
                                        monto = $(element).find("td").eq(3).html();*/
                                        if(index>0){
                                          console.log(serie + ' '+ sicoin + ' '+ existencia);

                                          $.ajax({
                                            type: "POST",
                                            url: "insumos/php/back/funciones/agregar_insumo_detalle.php",
                                            data: {
                                              id_producto_insumo:id_producto_insumo,
                                              serie:serie,
                                              sicoin:sicoin,
                                              existencia:existencia
                                            }, //f de fecha y u de estado.

                                            beforeSend:function(){
                                                          //$('#response').html('<span class="text-info">Loading response...</span>');


                                                  },
                                                  success:function(data){
                                                    console.log(data);
                                                    /*Swal.fire({
                                                      type: 'success',
                                                      title: 'Producto(s) creado(s)',
                                                      showConfirmButton: false,
                                                      timer: 1100
                                                    });*/
                                                    //alert(data);
                                                    //get_productos();
                                                    $('#modal-remoto').modal('hide');
                                                    //load_panel_producto_tarjeta_detalle(producto);

                                                  }


                                          }).done( function() {

                                          }).fail( function( jqXHR, textSttus, errorThrown){

                                            alert(errorThrown);

                                          });


                                        }
                                 });
                                 reload_insumos_listado();
                               }


                       }).done( function() {

                       }).fail( function( jqXHR, textSttus, errorThrown){

                         alert(errorThrown);

                       });
                     }
                   })
                 }else{
                   var cantidad;
                   if (nFilas == 1)
                   {
                     cantidad = 'Serie';
                   }
                   else {
                     cantidad = 'Series'
                   }
                   Swal.fire({
                     type: 'error',
                     title: 'Tiene ' +nFilas+ ' '+cantidad+ ' ingresadas ya en el sitema.',
                     showConfirmButton: true
                   });
                 }



               }else{
                 Swal.fire({
                   type: 'error',
                   title: 'Ingrese la Descripción'
                 });
               }
             }else{
               Swal.fire({
                 type: 'error',
                 title: 'Seleccione el modelo'
               });
             }
           }else{
             Swal.fire({
               type: 'error',
               title: 'Seleccione la marca'
             });
           }
         }else{
           Swal.fire({
             type: 'error',
             title: 'Seleccione el subtipo de Insumo'
           });
         }
       }else{
         Swal.fire({
           type: 'error',
           title: 'Seleccione el tipo de Insumo'
         });
       }



     }
         else{
           Swal.fire({
             type: 'error',
             title: 'Las columnas no coinciden'
           });
         }


 }
