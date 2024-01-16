function imprimirRequisicion(requisicion_id,tipo){
  //inicio
  $.ajax({
    type: "POST",
    url: "bodega/model/Requisicion.php",
    data: {
      opcion:9,
      fase:2,
      tipo:2,
      requisicion_id
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {
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
          console.log('I was closed by the timer')
        }
      })
    },
    success: function (data) {
      var punto = 0;
      var documento;
      var registros = data.length;
      suma = 0;

      var paginas = 1;


      var doc = new jsPDF("p", "mm", [792,612], true);
      var letras_x_pagina = 0;


      //doc.writeText(5,27,data.direccion,{align:'center',width:205});
      if(data.msg == 'ERROR'){
        Swal.fire({
          type: 'error',
          title: data.message,
          showConfirmButton: true,
          //timer: 1100
        });
      }else{
        //inicio de impresión
        //cuadro global
        doc.line(115, 7, 115,58);
        doc.line(145, 7, 145,30);
        //doc.setLineWidth(1);
        doc.roundedRect(7, 7, 202, 253, 1, 1);
        doc.setLineWidth(0.3);
        doc.addImage(logo_gris, 'png', 9, 10, 36, 26);
        doc.addImage(logo_cgc, 'png', 190, 10, 17, 17);
        //doc.line(7, 36, 209,36);
        doc.setFontType("bold");
        doc.setFontSize(8);
        doc.writeText(35, 12, 'SECRETARIA DE ASUNTOS', { align: 'center', width: 90 });
        doc.writeText(35, 16, 'ADMINISTRATIVOS Y DE SEGURIDAD DE LA', { align: 'center', width: 90 });
        doc.writeText(35,  20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 90 });
        doc.setFontType("normal");
        doc.writeText(35, 24, 'GUATEMALA, C.A.', { align: 'center', width: 90 });

        doc.setFontType("bold");
        doc.setFontSize(11);
        doc.writeText(35, 31, 'REQUISICIÓN DE SUMINISTROS', { align: 'center', width: 90 });

        doc.writeText(35, 36, 'DE ALMACÉN', { align: 'center', width: 90 });
        /*doc.writeText(5, 20, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
        doc.writeText(5, 25, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 205 });*/

        doc.setFontSize(14);
        //doc.writeText(5, 35, 'PEDIDO Y REMESA', { align: 'center', width: 205 });

        //doc.setTextColor(242, 38, 19);
        doc.setFontSize(8);
        doc.writeText(115, 13, 'SERIE A', { align: 'center', width: 103 });
        doc.writeText(115, 17, 'CORRELATIVO CGC No.', { align: 'center', width: 103 });
        doc.writeText(115, 17, 'FECHA', { align: 'center', width: 30 });
        doc.setFontSize(11);
        doc.writeText(115, 22, data.requisicion_num, { align: 'center', width: 103 });
        doc.writeText(115, 22, data.fecha, { align: 'center', width: 30 });
        /*doc.writeText(115, 22, '00000', { align: 'center', width: 103 });
        doc.writeText(115, 22, '00-00-0000', { align: 'center', width: 30 });*/
        doc.line(115, 30, 209,30);
        doc.setFontType("normal");
        doc.setTextColor(204, 204, 204);
        doc.setFontSize(100);
        doc.writeText(0, 140, data.msg_anulado, { align: 'center', width: 215 });
        
        doc.setFontType("bold");
        doc.setFontSize(8);
        doc.setTextColor(0, 0, 0);
        doc.writeText(115, 34, 'JUSTIFICACIÓN (necesidad, finalidad y temporalidad)', { align: 'center', width: 95 });

        doc.line(115, 36, 209,36);
        doc.setFontType("normal");
        doc.setFontSize(7);

        doc.setTextColor(0, 0, 0);
        /*doc.line(70, punto +53, 205,punto +53);
        doc.line(34, punto +61, 205,punto +61);
        doc.line(61, punto +69, 205,punto +69);
        doc.line(35, punto +77, 205,punto +77);

        doc.line(40, punto +86, 205,punto +86);*/

        doc.setFontSize(6.5);
        doc.setFontType("bold");
        doc.text(10, punto + 44, 'DIRECCIÓN SOLICITANTE:');
        doc.text(10, punto + 49, 'DEPARTAMENTO:');
        doc.text(10, punto + 54, 'SE SOLICITA A:');
        doc.setFontType("normal");




        doc.text(data.direccion, 43, punto + 44, {maxWidth: 150, align: "left"});
        doc.text(data.departamento, 43, punto + 49, {maxWidth: 150, align: "left"});
        doc.text(data.bodega, 43, punto + 54, {maxWidth: 150, align: "left"});
        /*doc.text(43, 47, data.direccion);
        doc.text('Back to left',43, 55)*/

        doc.setFontSize(7);
        doc.setFontType("normal");
        //doc.writeText(5, punto + 42, data.fecha, { align: 'right', width: 185 });


        //doc.writeText(36, punto + 52, data.direccion, { align: 'left', width: 195 });
        //doc.writeText(36, punto + 56, data.departamento, { align: 'left', width: 195 });

        //encabezado tabla
        doc.setFontSize(9);
        doc.setFontType("bold");

        //vertical lines
        var puntoinsumos = 58;
        doc.line(16, puntoinsumos, 16,185);
        /*doc.line(32, 73, 32,195);
        doc.line(48, 73, 48,195);
        doc.line(64, 73, 64,195);
        doc.line(80, 73, 80,195);*/
        doc.line(134, puntoinsumos, 134,185);
        doc.line(153, puntoinsumos, 153,185);
        doc.line(171, puntoinsumos, 171,185);
        doc.line(190, puntoinsumos, 190,185);
        //doc.line(112, 73, 112,195);
        //horizontal lines
        doc.setLineWidth(1);
        doc.line(7, punto +puntoinsumos, 209,punto +puntoinsumos);
        doc.setLineWidth(0.3);

        doc.line(7, punto +puntoinsumos + 10, 209,punto +puntoinsumos + 10);


        doc.setFontSize(5.4);

        doc.writeText(7, puntoinsumos + 5, 'No.', { align: 'center', width: 10 });
        doc.writeText(35, puntoinsumos + 5, 'DESCRIPCION DEL ARTÍCULO', { align: 'center', width: 20 });
        //doc.setFontSize(7);

        doc.writeText(135, puntoinsumos + 4, 'UNIDAD DE', { align: 'center', width: 16 });
        doc.writeText(135, puntoinsumos + 7, 'MEDIDA', { align: 'center', width: 16 });

        doc.writeText(154, puntoinsumos + 4, 'CANTIDAD', { align: 'center', width: 16 });
        doc.writeText(154, puntoinsumos + 7, 'SOLICITADA', { align: 'center', width: 16 });

        doc.writeText(172.5, puntoinsumos + 4, 'CANTIDAD', { align: 'center', width: 16 });
        doc.writeText(172.5, puntoinsumos + 7, 'AUTORIZADA', { align: 'center', width: 16 });

        doc.writeText(191, puntoinsumos + 4, 'CANTIDAD', { align: 'center', width: 16 });
        doc.writeText(191, puntoinsumos + 7, 'DESPACHADA', { align: 'center', width: 16 });




        //rectangulo de insumos
        doc.setLineWidth(1);
        doc.line(7, punto +185, 209,punto +185);
        doc.setLineWidth(0.3);
        //doc.roundedRect(7, 67, 196, 125, 0, 0);

        doc.setFontSize(7);
        doc.setFontType("normal");

        var margen1 = 14;
        var p1 = 72;
        var inc = 0;
        var reformattedArray = data.insumos.map(function(obj){
          var rObj = {};
          inc += 1;
           //console.log(obj.inicio);
           doc.text(margen1-5, p1, ''+inc+'');
           doc.text(margen1+ 5, p1, obj.Pro_des);
           doc.writeText(135, p1, obj.Med_nom, { align: 'center', width: 16 });
           doc.writeText(154, p1, obj.cantidadSolicitada, { align: 'right', width: 14.5 });
           doc.writeText(172.5, p1, obj.cantidadAutorizada, { align: 'right', width: 14.5 });



           //doc.text(margen1+155, p1, obj.estado);

           p1 +=4;
        });
        punto_f = 185;
        //doc.writeText(4.3, punto_f, 'Base Legal: Acuerdo Gubernativo 10-56-92 de fecha 22 de diciembre de 1992, Reglamento de la Ley de Contrataciones del Estado Artículo 15 y 16; y Artículo 39 número 18 y 20 Ley de la Contraloría General de Cuentas', { align: 'center', width: 205 });

        doc.roundedRect(8.5, 187, 199, 71.5, 0.5,1);

        doc.line(20, punto_f + 25, 90, punto_f + 25);
        doc.line(120, punto_f + 25, 190, punto_f + 25);
        doc.setFontType("bold");
        doc.setFontSize(10);
        doc.writeText(10, punto_f+6.5, 'NOMBRE Y FIRMA DE:', { align: 'center', width: 190 });

        punto_f += 5;
        doc.setFontType("normal");
        doc.setFontSize(9);

        doc.writeText(24, punto_f+19, data.solicitante, { align: 'center', width: 60 });

        doc.writeText(24, punto_f+24, 'Solicitado por', { align: 'center', width: 60 });
        doc.writeText(125, punto_f+24, 'Dirección solicitante', { align: 'center', width: 60 });

        doc.line(20, punto_f + 40, 90, punto_f + 40);
        doc.line(120, punto_f + 40, 190, punto_f + 40);
        doc.writeText(24, punto_f+44, 'Recibido por', { align: 'center', width: 60 });
        doc.writeText(125, punto_f+44, 'Despachado por', { align: 'center', width: 60 });

        doc.line(70, punto_f + 60, 140, punto_f + 60);
        doc.writeText(70, punto_f+64, 'Autorizado Director o Subdirector', { align: 'center', width: 70 });

        //pie de paginas
        doc.setFontType("normal");
        doc.setFontSize(5.5);
        var r_d1 = 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. F.O.-JM-007-2023 000615 GESTIÓN NÚMERO: 765939 DE FECHA 26-01-2023 DE CUENTA S1-22 · 15000 '+
                   'Formulario de Requisición de Suministros de Almacén en Forma Electrónica DEL No. 1 AL 15,000 SERIE A No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 88-2023 DE FECHA '+
                   '28-02-2023 · ENVIO FISCAL E-FISCAL 4-ASCC 20002 DE FECHA 28-02-2023 LIBRO 4-ASCC FOLIO 50';
        var r_lineas1 = doc.splitTextToSize(r_d1, 195);
        //doc.text(10, punto + 263, r_lineas1);
        doc.text(r_lineas1, 9, punto + 263, {maxWidth: 195, align: "justify"});

        doc.setFontSize(7);
        var r_lineas1 = doc.splitTextToSize(data.observaciones.toUpperCase(), 90);
        //doc.text(116.5, 40, r_lineas1);
        doc.text(r_lineas1, 116.5, 40, {maxWidth: 90, align: "justify"});


        doc.setFontType("bold");
        doc.setFontSize(7);
        doc.writeText(35, punto + 272, 'Original: Almacén', { align: 'center', width: 95 });
        doc.writeText(78, punto + 272, 'Duplicado: Enterante', { align: 'center', width: 95 });

        var x = document.getElementById("pdf_preview_requ");
        if (x.style.display === "none") {
          x.style.display = "none";
        } else {
          x.style.display = "none";
        }
        if(tipo == 0){
          doc.autoPrint()
          $("#pdf_preview_requ").attr("src", doc.output('datauristring'));
        }else if(tipo == 1){
          doc.save(data.requisicion_num+'.pdf');
        }

        //final de impresión
      }



    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });

  //fin
}
