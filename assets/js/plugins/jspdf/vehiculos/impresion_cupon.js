function reporte_cupon(id_documento) {

  $.ajax({
    type: "POST",
    url: "vehiculos/php/back/hojas/hoja_cupones.php",
    dataType: 'json',
    data: {
      id_documento: id_documento
    },
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
        }
      })
    },
    success: function (data) {
      linxPag = 35;
      registros = data.cupones.length;
      paginas = Math.ceil(registros / linxPag);
      tpaginas = paginas;
      lblTitulo = "";
      lblFecha = "";
      varFecha = "";
      console.log(linxPag);
      /*console.log(tpaginas);
      console.log(paginas);*/

      var doc = new jsPDF('p', 'mm');

      var today = new Date();
      var wFecha = String(today.getDate()).padStart(2, '0') + '/' + String(today.getMonth() + 1).padStart(2, '0') + '/' + today.getFullYear();
      var wHora = String(today.getHours()).padStart(2, '0') + ':' + String(today.getMinutes()).padStart(2, '0') + ':' + String(today.getSeconds()).padStart(2, '0');
      var cursor = 0;
      //doc.addImage(logo_saas, 'png', 18, 10, 50, 30);

      for (var PagAct = 1; PagAct <= tpaginas; PagAct++) {
        doc.addImage(logo_saas, 'png', 18, 8, 40, 30);
        doc.setFontSize(8);
        doc.writeText(170, 25, 'Fecha de impresion:  ' + wFecha, { align: 'right', width: 35 });
        doc.writeText(170, 30, ' Hora de impresion:  ' + wHora, { align: 'right', width: 35 });
        doc.writeText(170, 35, ' Pagina:  ' + PagAct + '/' + tpaginas, { align: 'right', width: 35 });

        doc.setFontSize(13);
        doc.writeText(90, 32, 'Cupones de Combustible', { align: 'center', width: 30 });
        if (data.docto_estado == 4348) {
          lblTitulo = "(Entregados)";
          lblFecha = "Fecha de Entregado:";
          varFecha = data.fecha_entrega;
        } else {
          lblTitulo = "(Liquidados)";
          lblFecha = "Fecha de Liquidacion:";
          varFecha = data.fecha_procesado;
        }
        doc.writeText(90, 37, lblTitulo, { align: 'center', width: 30 });

        doc.setFontSize(8);
        doc.setFontType('bold');
        doc.writeText(25, 45, 'Nro. Documento:', { align: 'right', width: 25 });
        doc.writeText(25, 50, 'Tipo Documento:', { align: 'right', width: 25 });
        doc.writeText(25, 55, 'Estado:', { align: 'right', width: 25 });
        doc.writeText(25, 60, 'Persona operó documento:', { align: 'right', width: 25 });
        doc.writeText(25, 65, 'Autorizó:', { align: 'right', width: 25 });

        doc.writeText(160, 50, lblFecha, { align: 'right', width: 20 });
        doc.writeText(160, 55, 'Total:', { align: 'right', width: 20 });
        doc.writeText(160, 60, 'Total Devuelto:', { align: 'right', width: 20 });
        doc.writeText(160, 65, 'Utilizado:', { align: 'right', width: 20 });
        doc.setFontType('normal');

        doc.writeText(53, 45, data.id_documento + '   ' + data.nro_documento, { align: 'left', width: 40 });
        doc.writeText(53, 50, 'EGRESO', { align: 'left', width: 40 });
        doc.writeText(53, 55, data.estado, { align: 'left', width: 40 });
        doc.writeText(53, 60, data.opero, { align: 'left', width: 40 });
        doc.writeText(53, 65, data.auto, { align: 'left', width: 40 });

        doc.writeText(185, 50, varFecha, { align: 'right', width: 15 });
        doc.writeText(185, 55, data.total, { align: 'right', width: 15 });
        doc.writeText(185, 60, data.devuelto, { align: 'right', width: 15 });
        doc.writeText(185, 65, data.utilizado, { align: 'right', width: 15 });

        doc.roundedRect(10, 68, 195, 6, 1, 1);
        doc.setFontSize(9);
        doc.setFontType('bold');
        doc.writeText(12, 72, 'Nro. Cupón        Monto         Tipo Uso', { align: 'left', width: 50 });
        doc.writeText(90, 72, 'Nro. Placa     Quien utilizó cupon', { align: 'left', width: 50 });
        doc.writeText(185, 72, 'Kilometraje', { align: 'left', width: 12 });
        doc.setFontType('normal');
        doc.setFontSize(8);
        punto = linxPag + 45;

        for (var LinAct = 1; LinAct <= linxPag; LinAct++) {
          if (cursor < registros) {
            doc.writeText(12, punto, data.cupones[cursor].cupon, { align: 'left', width: 20 });
            doc.writeText(30, punto, data.cupones[cursor].monto, { align: 'right', width: 20 });
            doc.writeText(55, punto, data.cupones[cursor].usadoen, { align: 'left', width: 20 });
            doc.writeText(90, punto, data.cupones[cursor].placa, { align: 'left', width: 20 });
            doc.writeText(110, punto, data.cupones[cursor].nombre, { align: 'left', width: 40 });
            doc.writeText(192, punto, data.cupones[cursor].km, { align: 'right', width: 12 });
            punto += 5;
            cursor++;
          }
        }

        doc.writeText(15, 265, '____________________________________', { align: 'center', width: 75 });
        doc.writeText(125, 265, '____________________________________', { align: 'center', width: 75 });
        doc.writeText(15, 270, data.opero, { align: 'center', width: 75 });
        doc.writeText(125, 270, data.recibe, { align: 'center', width: 75 });

        paginas--;

        if (paginas > 0) {
          punto = linxPag;
          doc.addPage();
        }

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

  }).done(function () {

  }).fail(function (jqXHR, textSttus, errorThrown) {


  });
}
