const formatter = new Intl.NumberFormat('en-US', {
  minimumFractionDigits: 0
});

function imprimir_reportexFecha(){
  $.ajax({
  type: "POST",
  url: "alimentos/php/back/hojas/reporte_fechas.php",
  dataType:'json',
  data:{
    ini:function() { return $('#ini').val() },
    fin:function() { return $('#fin').val() },
    direccion:function() { return $('#direccion_rrhh').val() },
    comedor:function() { return $('#id_comedor').val()}
  },
  beforeSend:function(){
    //$('.data_').html("<div class='spinner-grow-sm ' style='margin-left:0%'></div> <h6>Vacío</h6>");
  },
  success:function(data){
    linxPag=42;
    registros = data.length;
    paginas = Math.ceil(registros / linxPag);
    tpaginas = paginas;
    totDes = 0;
    totAlm = 0;
    totCen = 0;

    var doc = new jsPDF('p','mm');
    punto = linxPag;

     if($('#direccion_rrhh').val()==0){
        des_Direccion='Todas las Direcciones';
     }else{
        des_Direccion=$('#direccion_rrhh option:selected').text();
     }

     if($('#id_comedor').val()==0){
        des_Comedor='Todos';
     }else{
        des_Comedor=$('#id_comedor option:selected').text();
     }

      var cursor=0 ;
      for(var PagAct = 1; PagAct <= tpaginas; PagAct ++){
        doc.setFontSize(10);
        doc.setFontType("bold");
        doc.addImage(logo, 'png', 27, 5, 35, 25);
        doc.writeText(90,10, "SISTEMA DE CONTROL DE ALIMENTOS",{align:'center',width:60});
        doc.writeText(90,15, "REPORTE DE ALIMENTOS SERVIDOS POR FECHA",{align:'center',width:60});
        doc.writeText(90,20, "PERIODO DEL: "+$('#ini').val()+" AL: "+$('#fin').val()+"",{align:'center',width:60});
        doc.setFontSize(9);
        doc.writeText(90,24, ""+des_Direccion+"",{align:'center',width:60});
        doc.writeText(90,28, "Comedor: "+des_Comedor,{align:'center',width:60});
        doc.writeText(29,punto-8, "FECHA",{align:'left',width:17});
        doc.writeText(92,punto-8, "DESAYUNO",{align:'right',width:5});
        doc.writeText(113,punto-8, "ALMUERZO",{align:'right',width:5});
        doc.writeText(132,punto-8, "CENA",{align:'right',width:5});
        doc.roundedRect(27,punto-12,165,punto-37,1,1);
        // doc.setFontSize(9);
        doc.setFontType("normal");
        for(var LinAct =1; LinAct <= linxPag; LinAct ++){
            if(cursor<registros){
                if(data[cursor].fecha == 'Total:') {
                    doc.line(80,punto-3,137,punto-3);
                    punto+=1;
                }

                doc.writeText(29, punto ,""+data[cursor].fecha+"",{align:'left',width:17});
                doc.writeText(90, punto ,""+formatter.format(data[cursor].desayuno)+"",{align:'right',width:5});
                doc.writeText(110, punto ,""+formatter.format(data[cursor].almuerzo)+"",{align:'right',width:5});
                doc.writeText(130, punto ,""+formatter.format(data[cursor].cena)+"",{align:'right',width:5});
                punto +=5;
                cursor ++;
            }
        }

        doc.line(27,260,190,260);
        doc.writeText(80,263, "Pagina "+PagAct+"/"+tpaginas,{align:'center',width:60});

        paginas--;

        if(paginas > 0){
            punto = linxPag;
            doc.addPage();
        }else{
            doc.line(80,punto-3,137,punto-3);
            doc.line(80,punto-2,137,punto-2);
        }
    }

    var x = document.getElementById("impresion");
    if (x.style.display === "none") {
      x.style.display = "none";
    } else {
      x.style.display = "none";
    }
    doc.autoPrint()

    $("#impresion").attr("src", doc.output('datauristring'));
    $('#re_load').hide();
  }

}).done( function() {

}).fail( function( jqXHR, textSttus, errorThrown){
});
}

function imprimir_reportexDireccion(){
  $.ajax({
  type: "POST",
  url: "alimentos/php/back/hojas/reporte_direccion.php",
  dataType:'json',
  data:{
    ini:function() { return $('#ini').val() },
    fin:function() { return $('#fin').val() },
    direccion:function() { return $('#direccion_rrhh').val() },
    comedor:function() { return $('#id_comedor').val()}
  },
  beforeSend:function(){
    //$('.data_').html("<div class='spinner-grow-sm ' style='margin-left:0%'></div> <h6>Vacío</h6>");
  },
  success:function(data){
    linxPag=42;
    registros = data.length;
    paginas = Math.ceil(registros / linxPag);
    tpaginas = paginas;
    totDes = 0;
    totAlm = 0;
    totCen = 0;

    var doc = new jsPDF('p','mm');
    punto = linxPag;

     if($('#direccion_rrhh').val()==0){
        des_Direccion='Todas las Direcciones';
     }else{
        des_Direccion=$('#direccion_rrhh option:selected').text();
     }

     if($('#id_comedor').val()==0){
      des_Comedor='Todos';
      }else{
        des_Comedor=$('#id_comedor option:selected').text();
      }

      var cursor=0 ;
      for(var PagAct = 1; PagAct <= tpaginas; PagAct ++){
        doc.setFontSize(10);
        doc.setFontType("bold");
        doc.addImage(logo, 'png', 27, 5, 35, 25);
        doc.writeText(90,10, "SISTEMA DE CONTROL DE ALIMENTOS",{align:'center',width:60});
        doc.writeText(90,15, "REPORTE DE ALIMENTOS SERVIDOS POR DIRECCION",{align:'center',width:60});
        doc.writeText(90,20, "PERIODO DEL: "+$('#ini').val()+" AL: "+$('#fin').val()+"",{align:'center',width:60});
        doc.setFontSize(9);
        doc.writeText(90,24, ""+des_Direccion+"",{align:'center',width:60});
        doc.writeText(90,28, "Comedor: "+des_Comedor,{align:'center',width:60});
        doc.writeText(29,punto-8, "DIRECCION",{align:'left',width:17});
        doc.writeText(132,punto-8, "DESAYUNO",{align:'right',width:5});
        doc.writeText(153,punto-8, "ALMUERZO",{align:'right',width:5});
        doc.writeText(172,punto-8, "CENA",{align:'right',width:5});
        doc.roundedRect(27,punto-12,165,punto-37,1,1);
        doc.setFontType("normal");
        for(var LinAct =1; LinAct <= linxPag; LinAct ++){
            if(cursor<registros){
                if(data[cursor].direccion == 'Total:') {
                    doc.line(125,punto-3,180,punto-3);
                    punto+=1;
                }

                doc.writeText(29, punto ,""+data[cursor].direccion+"",{align:'left',width:17});
                doc.writeText(130, punto ,""+formatter.format(data[cursor].desayuno)+"",{align:'right',width:5});
                doc.writeText(150, punto ,""+formatter.format(data[cursor].almuerzo)+"",{align:'right',width:5});
                doc.writeText(170, punto ,""+formatter.format(data[cursor].cena)+"",{align:'right',width:5});
                punto +=5;
                cursor ++;
            }
        }

        doc.line(27,260,190,260);
        doc.writeText(80,263, "Pagina "+PagAct+"/"+tpaginas,{align:'center',width:60});

        paginas--;

        if(paginas > 0){
            punto = linxPag;
            doc.addPage();
        }else{
            doc.line(125,punto-3,180,punto-3);
            doc.line(125,punto-2,180,punto-2);
        }
    }

    var x = document.getElementById("impresion");
    if (x.style.display === "none") {
      x.style.display = "none";
    } else {
      x.style.display = "none";
    }
    doc.autoPrint()

    $("#impresion").attr("src", doc.output('datauristring'));
    $('#re_load').hide();
  }

}).done( function() {

}).fail( function( jqXHR, textSttus, errorThrown){
});
}

function imprimir_reportexColab(){
  
     $.ajax({
          type: "POST",
          url: "alimentos/php/back/hojas/reporte_colaboradores.php",
          dataType:'json',
          data:{
            ini:function() { return $('#ini').val() },
            fin:function() { return $('#fin').val() },
            direccion:function() { return $('#direccion_rrhh').val() },
            comedor:function() { return $('#id_comedor').val()}
          },
          beforeSend:function(){
            //$('.data_').html("<div class='spinner-grow-sm ' style='margin-left:0%'></div> <h6>Vacío</h6>");
          },
          success:function(data){
            linxPag=42;
            registros = data.length;
            paginas = Math.ceil(registros / linxPag);
            tpaginas= paginas;
            totDes = 0;
            totAlm = 0;
            totCen = 0;

            var doc = new jsPDF('p','mm');
            punto = linxPag;

            if($('#direccion_rrhh').val()==0){
              des_Direccion='Todas las Direcciones';
            }else{
              des_Direccion=$('#direccion_rrhh option:selected').text();
            }

            if($('#id_comedor').val()==0){
              des_Comedor='Todos';
            }else{
              des_Comedor=$('#id_comedor option:selected').text();
            }
              var cursor=0 ;
              for(var PagAct = 1; PagAct <= tpaginas; PagAct ++){
                doc.setFontSize(10);
                doc.setFontType("bold");
                doc.addImage(logo, 'png', 19, 5, 29, 25);
                doc.writeText(90,10, "SISTEMA DE CONTROL DE ALIMENTOS",{align:'center',width:60});
                doc.writeText(90,15, "REPORTE DE ALIMENTOS SERVIDOS POR COLABORADOR",{align:'center',width:60});
                doc.writeText(90,20, "PERIODO DEL: "+$('#ini').val()+" AL: "+$('#fin').val()+"",{align:'center',width:60});
                doc.setFontSize(9);
                doc.writeText(90,24, ""+des_Direccion+"",{align:'center',width:60});
                doc.writeText(90,28, "Comedor: "+des_Comedor,{align:'center',width:60});
                doc.writeText(18.5,punto-8, "DIRECCION",{align:'left',width:17});
                doc.writeText(80,punto-8, "NOMBRE DE COLABORADOR",{align:'left',width:17});
                doc.writeText(162,punto-8, "DESAYUNO",{align:'right',width:5});
                doc.writeText(183,punto-8, "ALMUERZO",{align:'right',width:5});
                doc.writeText(194,punto-8, "CENA",{align:'right',width:5});
                doc.roundedRect(17,punto-12,183,punto-37,1,1);
                // doc.setFontSize(8);
                doc.setFontType("normal");
                for(var LinAct =1; LinAct <= linxPag; LinAct ++){
                    if(cursor<registros){
                        if(data[cursor].nombre == 'Total:') {
                            doc.line(150,punto-3,200,punto-3);
                            punto+=1;
                        }

                        doc.writeText(18.5, punto ,""+data[cursor].direccion.substr(0,35)+"",{align:'left',width:17});
                        doc.writeText(80, punto ,""+data[cursor].nombre+"",{align:'left',width:17});
                        doc.writeText(160, punto ,""+formatter.format(data[cursor].desayuno)+"",{align:'right',width:5});
                        doc.writeText(175, punto ,""+formatter.format(data[cursor].almuerzo)+"",{align:'right',width:5});
                        doc.writeText(190, punto ,""+formatter.format(data[cursor].cena)+"",{align:'right',width:5});
                        punto +=5;
                        cursor ++;

                    }
                }

                doc.line(17,260,200,260);
                doc.writeText(80,263, "Pagina "+PagAct+"/"+tpaginas,{align:'center',width:60});

                paginas--;

                if(paginas > 0){
                    punto = linxPag;
                    doc.addPage();
                }else{
                    doc.line(150,punto-3,200,punto-3);
                    doc.line(150,punto-2,200,punto-2);
                }
            }

            var x = document.getElementById("impresion");
            if (x.style.display === "none") {
              x.style.display = "none";
            } else {
              x.style.display = "none";
            }
            doc.autoPrint()
      
            $("#impresion").attr("src", doc.output('datauristring'));
            $('#re_load').hide();
          }
      
        }).done( function() {
      
        }).fail( function( jqXHR, textSttus, errorThrown){
        });
}

