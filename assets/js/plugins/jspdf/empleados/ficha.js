function imprimirFicha(id_persona){
  $.ajax({
    type: "POST",
    url: "empleados/php/back/hojas/hoja_ficha.php",
    data: {
      id_persona
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


      //alert(data);
      // console.log(data);
      var doc = new jsPDF('p','mm',[215,340 ]);

      //inicio tipo 2
      var documento;

      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;

      incremental = 1;

      //doc.writeText(5,8,'Página: '+incremental+'/'+t_hojas,{align:'right',width:200});
      doc.setFontType('normal');
      doc.setTextColor(5, 83, 142);
      doc.setFontSize(9);
      doc.writeText(0, 323, 'Reporte Generado Herramientas Administrativas - Módulo Recursos Humanos', { align: 'center', width: 215 });
      doc.setFontType('bold');
      doc.setFontSize(9);
      doc.writeText(5, 331, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
      doc.writeText(5, 335, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
      doc.writeText(5, 338, 'https://www.saas.gob.gt', { align: 'center', width: 209 });
      doc.line(0, 325, 220, 325);
      doc.setFontType("bold");
      //punto-=20;
      doc.setFontType("normal");
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(9);
      //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
      //documento = data.data[i].solicitud;

      doc.setFontType("bold");
      doc.addImage(baner, 'png', 40, 10, 135, 30);
      var alto = 0;
      var ancho = 0;
      const getBase64Image = (url) => {
      const img = new Image();

      img.setAttribute('crossOrigin', 'anonymous');
      img.onload = () => {
        const canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        const dataURL = canvas.toDataURL("image/png");
        alto = canvas.height;
        ancho = canvas.width;
      }
      img.src = url
    }
    getBase64Image(data.foto);


      doc.addImage('data:image/jpeg;base64,'+data.fotografia, 'JPEG', 10, 43, 50, alto);

      doc.setFontSize(12);
      doc.setDrawColor(0, 171, 255);
      doc.setFillColor(0, 171, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(68, 43, 140, 8, 1, 1, 'FD');
      doc.setTextColor(255, 255, 255);
      doc.setFontType("bold");
      doc.text(72, 48, 'Datos Personales');

      doc.setFillColor(0, 171, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(68, 148, 140, 8, 1, 1, 'FD');

      //titulos en azul
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(9);
      var margen = 117;
      doc.setFontType("normal");
      doc.text(72, 58, 'CUI');     doc.text(margen, 58, data.cui);
      doc.text(72, 63, '1er. Nombre:');   doc.text(margen, 63, data.primer_nombre);
      doc.text(72, 68, '2do. Nombre:');   doc.text(margen, 68, data.segundo_nombre);
      doc.text(72, 73, '3do. Nombre:');   doc.text(margen, 73, data.tercer_nombre);
      doc.text(72, 78, '1er. Apellido:');   doc.text(margen, 78, data.primer_apellido);
      doc.text(72, 83, '2do. Apellido:');   doc.text(margen, 83, data.segundo_apellido);
      doc.text(72, 88, '3do. Apellido:');   doc.text(margen, 88, data.tercer_apellido);


      doc.text(72, 93, 'Fecha de Nacimiento:');   doc.text(margen, 93, data.fecha_nacimiento);
      doc.text(72, 98, 'Lugar de Nacimiento:');   doc.text(margen, 98, data.departamento+'- '+data.municipio);
      doc.text(72, 103, 'Estado Civil:');   doc.text(margen, 103, data.estado_civil);
      doc.text(72, 108, 'Nacionalidad:');   doc.text(margen, 108, 'Guatemalteca');
      doc.text(72, 113, 'IGSS:');   doc.text(margen, 113, data.igss);
      doc.text(72, 118, 'NIT:');    doc.text(margen, 118, data.nit);

      doc.text(72, 123, 'Género:');   doc.text(margen, 123, data.genero);
      doc.text(72, 128, 'Dirección:');    doc.text(margen, 128, data.direccion);
      doc.text(72, 133, 'Móvil:');
      doc.text(margen, 133, data.telefono);
      doc.text(72, 138, 'Profesión U Oficio:');
      var p_d = data.profesion;
      var p_lineas = doc.splitTextToSize(p_d, 100);
      doc.text(margen, 138, p_lineas);

      doc.setDrawColor(215, 215, 215);
      doc.setFontSize(9);
      //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
      //doc.line(75, 10, 75,50);

      doc.setFontType("bold");
      doc.setFontSize(11);

      // datos laborales
      doc.setTextColor(255, 255, 255);
      doc.setFontSize(12);
      doc.setFontType("bold");
      doc.text(72, 153, 'Datos Laborales');
      doc.setTextColor(68,68,68);
      doc.setFontType("normal");
      doc.setFontSize(9);
      doc.text(70, 163, 'Datos Nominales');
      doc.text(70, 203, 'Datos Funcionales');
      doc.setFontSize(9);
      doc.setFontType("normal");
      //datos Laborales
      doc.text(72, 168 , 'Renglón:');
      doc.text(72, 173 , 'Acuerdo:');
      doc.text(72, 178 , 'Fecha:');
      doc.text(72, 183 , 'Fecha Efectiva:');
      doc.text(72, 188 , 'Partida:');
      doc.text(72, 193 , 'Dependencia:');
      doc.text(72, 208 , 'Puesto Nominal:');
      doc.text(72, 213 , 'Ubicación:');
      doc.text(72, 218 , 'Puesto Funcional:');
      doc.text(72, 228 , 'Dependencia Funcional:');
      doc.text(72, 233 , 'Fecha Inicio Laboral:');


      var p_lineas = doc.splitTextToSize('Destitución/Remoción/Renuncia:', 32);
      doc.text(72, 238, p_lineas);

      doc.text(117, 168 , data.renglon);
      doc.text(117, 173 , data.e_acuerdo);
      doc.text(117, 178 , data.e_acuerdo_fecha);
      doc.text(117, 183 , data.e_acuerdo_fecha_efe);
      doc.text(117, 188 , data.e_partida);
      doc.text(117, 193 , data.dependencia);
      doc.text(117, 208 , data.cargo);
      doc.text(117, 213 , data.dep);
      doc.text(117, 218 , data.puesto);
      doc.text(117, 228 , data.dep_padre);
      doc.text(117, 233 , data.f_inicio);
      doc.text(117, 238 , data.f_destitucion);

      // fin datos laborales

      //inicio sueldo
      doc.setDrawColor(0, 171, 255);
      doc.setFillColor(0, 171, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(68, 253, 140, 8, 1, 1, 'FD');
      doc.setTextColor(255, 255, 255);
      doc.setFontType("bold");
      doc.setFontSize(12);
      doc.text(72, 258, 'Nombre');
      doc.text(160, 258, 'Ingresos');
      doc.setTextColor(6, 90, 155);
      doc.setFontSize(8);
      doc.setFontType("normal");
      doc.text(72, 268, '888   Bono Acuerdo 1:');
      doc.text(72, 273, '999   Bono Acuerdo 2:');
      doc.text(72, 278, '---     Complemento Personal:');
      doc.text(72, 283, '777   Bono por Antigüedad:');
      doc.text(72, 288, '---    Bono Profesional:');
      doc.text(72, 293, '---    Gastos de Representación:');

      doc.text(72, 298, '---    Sueldo Base:');

      doc.text(125, 306, 'Total:');
      doc.setTextColor(56, 63, 71);

      doc.text(120, 268, '01-01-2021');
      doc.text(120, 273, '01-01-2021');
      doc.text(120, 278, '01-01-2021');
      doc.text(120, 283, '01-01-2021');
      doc.text(120, 288, '01-01-2021');

      doc.text(152, 268, 'Q ');
      doc.text(152, 273, 'Q ');
      doc.text(152, 278, 'Q ');
      doc.text(152, 283, 'Q ');
      doc.text(152, 288, 'Q ');
      doc.text(152, 293, 'Q ');
      doc.text(152, 298, 'Q ');

      doc.text(152, 306, 'Q ');

      doc.writeText(0, 268 ,'300.00',{align:'right',width:177});
      doc.writeText(0, 273 ,'300.00',{align:'right',width:177});
      doc.writeText(0, 278 ,'300.00',{align:'right',width:177});
      doc.writeText(0, 283 ,'300.00',{align:'right',width:177});
      doc.writeText(0, 288 ,'300.00',{align:'right',width:177});
      doc.writeText(0, 293 ,'300.00',{align:'right',width:177});

      doc.setDrawColor(137, 137, 137);
      doc.line(150, 301, 205, 301);
      doc.setDrawColor(92, 144, 210);
      doc.writeText(0, 298 ,'5,000.00',{align:'right',width:177});
      doc.writeText(0, 306 ,'8,000.00',{align:'right',width:177});
      // fin sueldo

      /*doc.writeText(5, 45, 'INFORME DEL NOMBRAMIENTO SAAS/' + data.data[i].nombramiento, { align: 'center', width: 205 });
      doc.setFontSize(9);
      doc.setFontType('normal');
      //doc.writeText(5, 55 ,fecha,{align:'right',width:185});

      //doc.line(120, 74, 120, 125);
      doc.setFontSize(9);
      doc.setFontType("bold");*/
      doc.setFontSize(12);
      doc.setDrawColor(0, 171, 255);
      doc.setFillColor(0, 171, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(10, 148, 50, 8, 1, 1, 'FD');
      doc.setTextColor(255, 255, 255);
      doc.setFontType("bold");
      doc.text(14, 153, 'Plazas');
      doc.setTextColor(56, 63, 71);
      doc.setFontSize(9);
      doc.setFontType("normal");
       p1 = 163;
      for (nombreIndice in data.plazas) {

        doc.setFontSize(9);
        doc.setFontType("normal");
        doc.setTextColor(6, 90, 155);
        doc.text(14, p1, 'Plaza:');
        doc.setTextColor(56, 63, 71);
        doc.text(40, p1, data.plazas[nombreIndice].cod_plaza);
        p1 +=5;
        doc.setTextColor(6, 90, 155);
        doc.text(14, p1, 'Inicio:');
        doc.setTextColor(56, 63, 71);
        doc.text(40, p1, data.plazas[nombreIndice].inicio);
        p1 +=5;
        doc.setTextColor(6, 90, 155);
        doc.text(14, p1, 'Final:');
        doc.setTextColor(56, 63, 71);
        doc.text(40, p1, data.plazas[nombreIndice].final);
        p1 +=10;
      }

      var x = document.getElementById("pdf_preview_e");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_e").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}
