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
        }
      })
    },
    success: function (data) {

      //alert(data);
      // console.log(data);
      //var doc = new jsPDF('p','mm',[215,340 ]);
      var doc = new jsPDF("p", "mm", 'legal',true);
      var margen1 = 4
      var margen2 = 42;
      var callAddFont = function () {
        //doc.addFileToVFS('WorkSans-normal.ttf', font);
        //doc.addFont("WorkSans-normal.ttf", "WorkSans", "normal");
        doc.addFileToVFS('Ubuntu-Medium.ttf', font);
        doc.addFont("Ubuntu-Medium.ttf", "UbuntuMedium", "normal");

        doc.addFileToVFS('Fredoka-One.ttf', font_fredoka);
        doc.addFont("Fredoka-One.ttf", "FredokaOne", "normal");

        doc.addFileToVFS('Montserrat-Regular.ttf', font_montregular);
        doc.addFont("Montserrat-Regular.ttf", "MontserratRegular", "normal");

        doc.addFileToVFS('Montserrat-Semibold.ttf', font_montsemibold);
        doc.addFont("Montserrat-Semibold.ttf", "MontserratSemibold", "normal");

        doc.addFileToVFS('Montserrat-Light.ttf', font_montlight);
        doc.addFont("Montserrat-Light.ttf", "MontserratLight", "normal");

        doc.addFileToVFS('Poppins-Bold.ttf', font_poppinsbold);
        doc.addFont("Poppins-Bold.ttf", "PoppinsBold", "normal");

        //doc.addFileToVFS('Font Awesome 6 Brands-Regular-400.ttf', fontawesome);
        //doc.addFont("Font Awesome 6 Brands-Regular-400.ttf", "FontAwesome", "normal");
      };
      callAddFont();

      var imageUrl = 'assets/js/plugins/jspdf/fonts/mail.png';
      var convertType = 'Canvas';
      var convertFunction = convertType === 'FileReader' ?
        convertFileToDataURLviaFileReader :
        convertImgToDataURLviaCanvas;
        var x;


      //inicio tipo 2
      var documento;

      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;

      incremental = 1;

      //doc.writeText(5,8,'Página: '+incremental+'/'+t_hojas,{align:'right',width:200});
      //doc.setFontType("bold");
      //punto-=20;
      doc.setFontType("normal");
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(9);
      //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
      //documento = data.data[i].solicitud;

      //doc.setFontType("bold");

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
      //getBase64Image(data.foto);

      doc.addImage('data:image/jpeg;base64,'+data.fotografia, 'JPEG', 159, 42, 47, alto);

      doc.setLineWidth(15)
      doc.setDrawColor(255,255,255);
      doc.setFillColor(255, 255, 255)
      doc.circle(183, 67, 30)
      doc.roundedRect(156, 100, 80, 55, 0, 0, 'FD');
      doc.addImage(logo_saas_t, 'png', 157, 0, 50, alto);
      doc.setLineWidth(0.8);
      doc.setDrawColor(191, 191, 191);
      doc.setFillColor(191, 191, 191);
      doc.circle(183, 67, 25)
      doc.setLineWidth(1);
      doc.setDrawColor(252, 253, 255);
      doc.setFillColor(252, 253, 255);
      doc.roundedRect(0, 0, 150, 300, 1, 1, 'FD');
      //doc.addImage(logo, 'png', 0, 0, 40, 30);



      //doc.setFontSize(32);
      doc.setDrawColor(59, 175, 218);
      doc.setFillColor(59, 175, 218);
      doc.roundedRect(0, 43, 150, 8, 0,0,'FD');
      doc.roundedRect(0, 128, 150, 8,0,0, 'FD');
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(0, 43, 2, 8, 0,0,'FD');
      doc.roundedRect(0, 128, 2, 8,0,0, 'FD');
      doc.setFont("MontserratSemibold");
      doc.setFontSize(24);
      doc.setTextColor(68, 68, 68);
      doc.setFont("MontserratRegular");
      doc.text(margen1, 13, data.nombres);
      doc.setFont("MontserratSemibold");
      doc.text(margen1, 20, data.apellidos);
      //doc.text(data.primer_nombre, 10, 10, {maxWidth: 185, align: "justify"});

      //doc.text(margen1, 15, data.nombres_apellidos);
      //multilineaConNegrita(margen1, 15, 170, `${data.nombres} **${data.apellidos}**`);
      doc.setFont("MontserratLight");
      doc.setFontSize(15);
      doc.text(margen1, 25, data.cargo);
      doc.setFontSize(12);

      doc.setFont("PoppinsBold");
      doc.setTextColor(255, 255, 255);
      doc.text(margen1, 48.5, 'Datos Personales');



      //titulos en azul
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(18);
      doc.text(160, 110 , 'Edad: '+ data.edad +' años');
      doc.setFontSize(14);
      //doc.text(160, 115 , 'Tipo de Sangre: '+ data.tsangre );
      doc.setFont("MontserratRegular");
      doc.setFontSize(9);

      doc.text(margen1, 58, 'CUI');
      doc.text(margen1, 63, 'Tipo de Sangre:');
      doc.text(margen1, 68, 'Fecha de Nacimiento:');
      doc.text(margen1, 73, 'Lugar de Nacimiento:');
      doc.text(margen1, 78,  'Estado Civil:');
      doc.text(margen1, 83,  'Nacionalidad:');
      doc.text(margen1, 88,  'IGSS:');
      doc.text(margen1, 93,  'NIT:');
      doc.text(margen1, 98,  'Género:');
      doc.text(margen1, 103, 'Dirección:');
      doc.text(margen1, (108 + data.lineas), 'Teléfono:');
      doc.text(margen1, (113 + data.lineas), 'Profesión U Oficio:');

      doc.setFont("MontserratSemibold");
      doc.text(margen2, 58, data.cui);
      doc.text(margen2, 63, data.tsangre);
      doc.text(margen2, 68, data.fecha_nacimiento);
      doc.text(margen2, 73, data.municipio+ ', '+data.departamento);
      doc.text(margen2, 78,  data.estado_civil);
      doc.text(margen2, 83,  'Guatemalteca');
      doc.text(margen2, 88,  data.igss);
      doc.text(margen2, 93,  data.nit);
      doc.text(margen2, 98,  data.genero);
      //doc.text(margen2, 98,  data.direccion);
      var p_lineas = doc.splitTextToSize(data.direccion, 100);
      doc.text(margen2, 103, p_lineas);

      doc.text(margen2, (108 + data.lineas), data.telefono);


      var p_d = data.profesion;
      var p_lineas = doc.splitTextToSize(p_d, 100);
      doc.text(margen2, (113 + data.lineas), p_lineas);

      doc.setDrawColor(215, 215, 215);
      doc.setFontSize(9);
      //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
      //doc.line(75, 10, 75,50);

      doc.setFontSize(11);

      // datos laborales

      doc.setFontSize(12);

      doc.setFont("PoppinsBold");
      doc.setTextColor(255, 255, 255);
      doc.text(margen1, 133.5, 'Datos Laborales');


      //titulos en azul

      //datos Laborales
      if(data.renglon == '011'){
        doc.setTextColor(68,68,68);
        doc.setFontSize(9);
        doc.text(margen1, 143, 'Datos Nominales');
        doc.text(margen1, 188, 'Datos Funcionales');

        doc.setFontSize(9);
        doc.setFontType("normal");
        doc.setFont("MontserratRegular");
        doc.text(margen1, 148 , 'Renglón:');
        doc.text(margen1, 153 , 'Acuerdo:');
        doc.text(margen1, 158 , 'Fecha:');
        doc.text(margen1, 163 , 'Fecha Efectiva:');
        doc.text(margen1, 168 , 'Partida:');
        doc.text(margen1, 173 , 'Dependencia:');
        doc.text(margen1, 178 , 'Puesto Nominal:');
        doc.text(margen1, 193 , 'Ubicación:');
        doc.text(margen1, 198 , 'Puesto Funcional:');
        doc.text(margen1, 208 , 'Dependencia:');
        doc.text(margen1, 213 , 'Inicio Laboral:');
        var p_lineas = doc.splitTextToSize('Destitución/ Remoción/ Renuncia:', 20);
        doc.text(margen1, 218, p_lineas);
      }else{
        doc.setTextColor(68,68,68);
        doc.setFontSize(9);
        doc.text(margen1, 143, 'Datos del Contrato');
        //doc.text(margen1, 178, 'Datos Funcionales');
        doc.setFont("MontserratRegular");
        /*doc.text(margen1, 138 , 'Dirección:');
        doc.text(margen1, 143 , 'Puesto:');*/
        doc.text(margen1, 148 , 'Renglón:');
        doc.text(margen1, 153 , 'No. de Acuerdo:');
        doc.text(margen1, 158 , 'Fecha del Contrato:');
        doc.text(margen1, 163 , 'Fecha de Inicio:');
        doc.text(margen1, 168 , 'Fecha de Finalización:');
        doc.text(margen1, 173 , 'Fecha del Acuerdo:');
        doc.text(margen1, 178 , 'Monto:');
        doc.text(margen1, 183 , 'Ubicación:');
        doc.text(margen1, 188 , 'Monto Mensual:');
        doc.text(margen1, 193 , 'Dirección:');
        doc.text(margen1, 198 , 'Puesto:')
      }

      doc.setTextColor(68, 68, 68);
      doc.setFontSize(9);
      doc.setFont("MontserratSemibold");

      if(data.renglon == '011'){
        doc.text(margen2, 148, data.renglon);
        doc.text(margen2, 153, data.e_acuerdo);
        doc.text(margen2, 158, data.e_acuerdo_fecha);
        doc.text(margen2, 163, data.e_acuerdo_fecha_efe);
        doc.text(margen2, 168, data.e_partida);
        doc.text(margen2, 173, data.dependencia);
        doc.text(margen2, 178, data.cargo);
        doc.text(margen2, 193, data.dep);
        doc.text(margen2, 198, data.puesto);
        doc.text(margen2, 208, data.dep_padre);
        doc.text(margen2, 213, data.f_inicio);
        doc.text(margen2, 218, data.f_destitucion);
      }else{
        doc.text(margen2, 148, data.renglon);
        doc.text(margen2, 153, data.nro_contrato );
        doc.text(margen2, 158, data.fecha_contrato);
        doc.text(margen2, 163, data.fecha_inicio);
        doc.text(margen2, 168, data.fecha_fin);
        doc.text(margen2, 173, data.acuerdo);
        doc.text(margen2, 178, data.fecha_acuerdo_aprobacion);
        //doc.text(margen2, 183, data.tipo_contrato);

        doc.text(margen2, 183, data.direccionfun);
      }

      // fin datos laborales

      //inicio sueldo

      doc.setDrawColor(59, 175, 218);
      doc.setFillColor(59, 175, 218);
      doc.roundedRect(0, 233, 150, 8, 0, 0, 'FD');
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(0, 233, 2, 8, 0, 0, 'FD');
      doc.setTextColor(255, 255, 255);

      doc.setFontSize(12);
      doc.setFont('PoppinsBold')
      doc.text(margen1, 238, 'Ascensos');

      p1 = 248;
      doc.setTextColor(68,68,68);
      doc.setFontSize(9);
      doc.setFont('MontserratSemibold')
      doc.text(margen1, p1, 'Renglón');
      doc.text(margen1+20, p1, 'Inicio');
      doc.text(margen1+37, p1, 'Final');
      doc.text(margen1+85, p1, 'Puesto');
      doc.setFont('MontserratRegular')
      p1 += 5;

      doc.setFontSize(9);
      var reformattedArray = data.plazas.map(function(obj){
         var rObj = {};
         console.log(obj.inicio);
         doc.text(margen1+ 5, p1, obj.renglon);
         doc.text(margen1+17, p1, obj.inicio);
         doc.text(margen1+35, p1, obj.final);
         doc.text(margen1+55, p1, obj.puesto);
         doc.setFont('MontserratSemibold')
         doc.text(margen1+155, p1, obj.estado);
         doc.setFont('MontserratRegular')
         p1 +=5;
      });

      doc.setFontSize(12);
      p2 = 140;
      margen5 = 160;
      doc.setFont("PoppinsBold");
      doc.text(margen5, 133.5, 'Control de Vacunas');

      doc.setFont('MontserratRegular')


      doc.setFontSize(9);
      var reformattedArray = data.vacunas.map(function(obj){
         var rObj = {};
         doc.text(margen5, p2, obj.id_dosis);
         doc.text(margen5, p2+4, obj.tipo_vacuna);
         doc.text(margen5, p2+8, obj.fecha_vacuna);

         p2 +=15;
      });

      doc.setFont('MontserratSemibold');
      if(data.vacunas.find((item) => item.id_dosis == 'Refuerzo')){
        doc.text(margen5, p2, 'Esquema completo');
      }else{
        doc.text(margen5, p2, 'Le faltan dosis');
      }
      doc.setFont("MontserratSemibold");
      doc.setFontSize(14);
      doc.text(margen5, 238, 'Sueldo:');


      doc.setFont('PoppinsBold')
      doc.setFontSize(14);
      doc.writeText(0, 238 ,data.sueldo,{align:'right',width:208});

      doc.setFontType('normal');
      doc.setLineWidth(0);
      doc.setFont('MontserratRegular');
      doc.setTextColor(5, 83, 142);
      doc.setFontSize(9);
      doc.writeText(0, 310, 'Reporte Generado Herramientas Administrativas - Módulo Recursos Humanos', { align: 'center', width: 215 });
      //doc.setFontType('bold');
      doc.setFontSize(9);
      doc.writeText(5, 319, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
      doc.writeText(5, 322, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
      doc.writeText(5, 325, 'https://www.saas.gob.gt', { align: 'center', width: 209 });
      doc.line(0, 312, 220, 312);
      /*doc.text(160, 258, 'Ingresos');
      doc.setTextColor(6, 90, 155);
      doc.setFontSize(8);
      doc.setFontType("normal");
      doc.text(margen1, 268, '888   Bono Acuerdo 1:');
      doc.text(margen1, 273, '999   Bono Acuerdo 2:');
      doc.text(margen1, 278, '---     Complemento Personal:');
      doc.text(margen1, 283, '777   Bono por Antigüedad:');
      doc.text(margen1, 288, '---    Bono Profesional:');
      doc.text(margen1, 293, '---    Gastos de Representación:');

      doc.text(margen1, 298, '---    Sueldo Base:');

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
      doc.writeText(0, 306 ,'8,000.00',{align:'right',width:177});*/
      // fin sueldo


      console.log(doc.getFontList());

      doc.setFontSize(12);
      doc.setDrawColor(0, 171, 255);
      doc.setFillColor(0, 171, 255);
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);

      doc.setTextColor(0, 0, 0);
      //doc.setFontType("bold");
      //doc.setFont("FontAwesome");
      margen3 = 172;
      margen4 = 190;


      var x = document.getElementById("pdf_preview_e");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      function multilineaConNegrita(x, y, endx, text) {
          const isBoldOpen = (arrayLength, valueBefore = false) => {
              const isEven = arrayLength % 2 === 0;
              const result = valueBefore !== isEven;
              return result;
          };
          const lineSpacing = 10;
          const fontSize = 10;
          //mezcla de normal y negrita multiples lineas
          let startX = x;
          let startY = y;
          const endX = endx;


          doc.setLineWidth(1);
          let textMap = doc.splitTextToSize(text, endX);
          const startXCached = startX;
          let boldOpen = false;
          textMap.map((text, i) => {
              if (text) {
                  const arrayOfNormalAndBoldText = text.split('**');
                  const boldStr = 'MontserratSemibold';
                  const normalOr = 'MontserratRegular';
                  //doc.setFont("MontserratRegular");
            //doc.setFont("MontserratSemibold");
                  arrayOfNormalAndBoldText.map((textItems, j) => {
                      doc.setFont(boldOpen ? normalOr : boldStr);
                      if (j % 2 === 0) {
                          doc.setFont(boldOpen ? boldStr : normalOr);
                      }
                      doc.text(textItems,startX, startY);
                      startX = startX + doc.getStringUnitWidth(textItems) * 6.1;
                  });
                  boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                  startX = startXCached;
                  startY += lineSpacing;
              }

          });
      }

      doc.autoPrint()
      $("#pdf_preview_e").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function convertImgToDataURLviaCanvas(url, callback, outputFormat) {
  var img = new Image();
  img.crossOrigin = 'Anonymous';
  img.onload = function() {
  var canvas = document.createElement('CANVAS');
  var ctx = canvas.getContext('2d');
  var dataURL;
  canvas.height = this.height;
  canvas.width = this.width;
  ctx.drawImage(this, 0, 0);
  dataURL = canvas.toDataURL(outputFormat);
  callback(dataURL);
  canvas = null;
  };
  img.src = url;
}
