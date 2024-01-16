function reporte_movimiento(nro_vale){
  var logo_s = logo_saas;
    $.ajax({
      type: "POST",
      url: "vehiculos/php/back/hojas/get_valexDoc.php",
      dataType:'json',
      data:{
        nro_vale:nro_vale
      },
      beforeSend:function(){
        Swal.fire({
          title: 'Cargando impresión!',
          html: "<div class='spinner-grow text-info'></div>",
          timer: 700,
          timerProgressBar: true,
          showConfirmButton: false,
          didOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
              const content = Swal.getHtmlContainer()
              if (content) {
                const b = content.querySelector('b')
                if (b) {
                  b.textContent = Swal.getTimerLeft()
                }
              }
            }, 100)
          },
          willClose: () => {
            clearInterval(timerInterval)
          }
        }).then((result) => {
          /* Read more about handling dismissals below */
          if (result.dismiss === Swal.DismissReason.timer) {
          }
        })

      },

      success:function(data){
          var total = 0;
        var doc = new jsPDF('p','mm');
        for (var i in data) {
          total+=1;
        }

  
        n = new Date();
        y = n.getFullYear();
        m = n.getMonth()+1;
        var mo;
        if(m<10){
          mo="0"+m;
        }
        else{
          mo=m;
        }
        d = n.getDate();
        h=n.getHours()         // Get the hour (0-23)h=n.getMilliseconds()  // Get the milliseconds (0-999)
        var mi;
        i=n.getMinutes()       // Get the minutes (0-59)
        if(i<10){
          mi="0"+i;
        }
        else{
          mi=i;
        }
        s=n.getSeconds()
        //m =




        //var movimiento,transaccion,fecha,empleado,empleado2,direccion,encargado,bodega;
        var movimiento,corte,fecha,nro_vale,id_combustible,uso,nro_placa,emite,autoriza,recibe,refer;
         for (var i in data) {
  
           corte=data[i].corte;
           fecha=data[i].fecha;
           nro_vale=data[i].nro_vale;
           id_combustible=data[i].id_combustible;
           uso=data[i].uso;
           nro_placa=data[i].nro_placa;
           refer=data[i].refer;
           emite=data[i].emite;
           recibe=data[i].recibe;
           autoriza=data[i].autoriza;
         }
  
            for (var x=1; x<=2; x++){
              var p1,p2,p3,p4;
              doc.setFontSize(10);
              if(x==1){
                p1=72;
                p2 = 60;
                p3 = 120;
                p4 = 15;
                doc.setDrawColor(204, 204, 204);
                doc.line(5, p3+18, 210, p3+18);
                doc.setTextColor(68, 68, 68);
                doc.writeText(185, p2-45 ,'Original',{align:'right',width:17});
              }else{
                p1=72;
                suma=134;
                p1+=suma;
                p2+=suma;
                p3+=suma;
                p4+=suma;
                doc.setTextColor(68, 68, 68);
                doc.writeText(185, p2-45 ,'Copia',{align:'right',width:17});
              }
    
              var movimiento,corte,fecha,nro_vale,id_combustible,uso,nro_placa,emite,autoriza,recibe,refer;
              doc.addImage(logo_s, 'png', 8, p2-50, 60, 40);
                     
  
              for (var i in data) {
                doc.setFontType("bold");
  
                doc.setFontType("normal");
                doc.setTextColor(68, 68, 68);
  
                doc.writeText(60, p1-3 ,""+data[i].cant_galones+" Galones",{align:'center',width:17});
                if (data[i].tlleno == 1) {
                   doc.writeText(75, p1-3 ,"*",{align:'center',width:17});
                   doc.writeText(20, p1+5 ,"* Tanque lleno",{align:'center',width:5});
                 }
                doc.writeText(145, p1-3 ,""+data[i].descripcion+"",{align:'center',width:5});
              }

              doc.setDrawColor(0, 136, 176);
              doc.setDrawColor(204, 204, 204);
              doc.setFillColor(0, 136, 176);
              doc.roundedRect(10, p2-8, 194, 20, 1, 1);
              doc.line(10, p2+1, 204, p2+1);

              doc.line(51, p2-8, 51, p2+12);
              doc.line(88, p2-8, 88,p2+12);
              doc.line(123, p2-8, 123,p2+12);
              doc.line(178, p2-8, 178,p2+12);
  
              doc.setFontSize(10);
              doc.writeText(18, p2-2 ,"KILOMETRAJE",{align:'center',width:17});
              doc.writeText(60, p2-2 ,"CANTIDAD",{align:'center',width:17});
              doc.writeText(97, p2-2 ,"CANTIDAD REAL",{align:'center',width:17});
              doc.writeText(138, p2-2 ,"DESPACHAR",{align:'center',width:5});
              doc.writeText(187, p2-2 ,"BOMBA",{align:'center',width:5});
  
              doc.setTextColor(68, 68, 68);
              doc.setFontSize(9);
              doc.setFontType("bold");
              doc.setFontType("normal");

              doc.setFontType("bold");
              doc.setFontSize(10);
              doc.writeText(110,p4 ,'VALE DE COMBUSTIBLE',{align:'center',width:5});
              doc.setFontSize(11);
              doc.setFontType("normal");

              doc.writeText(85, p4+ 5 ,"Corte:         ",{align:'left',width:5});
              doc.writeText(85, p4+10 ,"Fecha:         ",{align:'left',width:5});
              doc.writeText(85, p4+15 ,"Nro. Vale:     ",{align:'left',width:5});
              doc.writeText(85, p4+20 ,"Documento:     ",{align:'left',width:5});
              doc.writeText(85, p4+25 ,"Uso:           ",{align:'left',width:5});
              doc.writeText(85, p4+30 ,data[0].titulo_placa+":    ",{align:'left',width:5});
              

              doc.writeText(110, p4+ 5 ,corte,{align:'left',width:5});
              doc.writeText(110, p4+10 ,fecha,{align:'left',width:5});
              doc.writeText(110, p4+15 ,nro_vale,{align:'left',width:5});
              doc.writeText(110, p4+20 ,id_combustible,{align:'left',width:5});
              doc.writeText(110, p4+25 ,""+uso+"",{align:'left',width:5});
              doc.writeText(110, p4+30 ,nro_placa,{align:'left',width:5});

              // doc.writeText(191, p2-2 ,"Total",{align:'center',width:5});
              doc.setDrawColor(204, 204, 204);
              doc.setFillColor(204, 204, 204);
              doc.setTextColor(0, 136, 176);
              doc.setFontSize(10);
              doc.setTextColor(68, 68, 68);
  
              doc.line(10, p3-30, 65, p3-30);
              doc.line(75, p3-30, 140, p3-30);
              doc.line(155, p3-30, 205, p3-30);
              doc.line(75, p3-10, 145, p3-10);
              doc.line(10, p3-30, 65, p3-30);

              doc.setFontSize(8);
              
              doc.writeText(35, p3-25 ,emite,{align:'center',width:5});
              doc.writeText(178, p3-25 ,recibe,{align:'center',width:5});
              doc.writeText(105, p3-5 ,autoriza,{align:'center',width:5});
  
              doc.writeText(35, p3-20 ,'Emite',{align:'center',width:5});
              doc.writeText(105, p3-20 ,'Despacha',{align:'center',width:5});
              doc.writeText(178, p3-20 ,'Recibe',{align:'center',width:5});
              doc.writeText(105, p3-0 ,'Autoriza',{align:'center',width:5});

              doc.setFontSize(8);
              doc.setTextColor(5, 83, 142);
              doc.writeText(10, p3+5 ,'Reporte Generado SAAS - Control de Combustibles',{align:'center',width:190});
              doc.writeText(10, p3+8 ,'6ta. Avenida 4-18 Zona 1, Callejón "Del Manchen"',{align:'center',width:190});
              doc.writeText(10, p3+11 ,'PBX: 2327-600 FAX: 2327-6090',{align:'center',width:190});
  
              doc.setTextColor(255,255,255);
  
            }
  
        var x = document.getElementById("pdf_preview_estado");
        if (x.style.display === "none") {
          x.style.display = "none";
        } else {
          x.style.display = "none";
        }
        doc.autoPrint()
        //doc.save();
  
        $("#pdf_preview_estado").attr("src", doc.output('datauristring'));
        $('#re_load').hide();
      }
  
    }).done( function() {
  
    }).fail( function( jqXHR, textSttus, errorThrown){
  
  
    });
  }
  