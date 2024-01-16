function imprimirFicha(id_persona,tipo){
  let salida;
  $.ajax({
    type: "POST",
    url: "empleados/php/back/hojas/hoja_ficha.php",
    data: {
      id_persona
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {
      if(tipo == 1){
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
      }

    },
    success: function (data) {

      var pagina = 1;
      //alert(data);
      // console.log(data);
      //var doc = new jsPDF('p','mm',[215,340 ]);
      var doc = new jsPDF("p", "mm", 'legal',true);
      var margen1 = 7
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

      doc.addImage('data:image/jpeg;base64,'+data.fotografia, 'JPEG', 160, 42, 47, alto);
      doc.addImage(logo_saas_t, 'png', 157, 0, 50, alto);
      doc.setDrawColor(255, 255, 255);
      doc.setFillColor(255, 255, 255);
      doc.roundedRect(150, 100, 120, 20,0,0, 'FD');

      if(pagina == 1){
        doc.setDrawColor(5, 83, 142);
        doc.setFillColor(5, 83, 142);
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
        doc.setTextColor(68, 68, 68);
      }
      //doc.setFontSize(32);
      doc.setDrawColor(59, 175, 218);
      doc.setFillColor(59, 175, 218);
      doc.roundedRect(0, 43, 150, 8, 0,0,'FD');
      doc.roundedRect(0, 122, 150, 8,0,0, 'FD');
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(0, 43, 2, 8, 0,0,'FD');
      doc.roundedRect(0, 122, 2, 8,0,0, 'FD');
      doc.setFont("MontserratSemibold");
      doc.setFontSize(24);
      doc.setTextColor(68, 68, 68);
      doc.setFont("MontserratRegular");
      doc.text(margen1, 13, data.nombres);
      doc.setFont("MontserratSemibold");
      doc.text(margen1, 21, data.apellidos);
      //doc.text(data.primer_nombre, 10, 10, {maxWidth: 185, align: "justify"});

      //doc.text(margen1, 15, data.nombres_apellidos);
      //multilineaConNegrita(margen1, 15, 170, `${data.nombres} **${data.apellidos}**`);
      doc.setFont("MontserratLight");
      doc.setFontSize(15);
      var p_lineas = doc.splitTextToSize(data.cargo, 150);
      doc.text(margen1, 27, p_lineas);
      /*doc.text(margen1, 25, data.cargo);*/

      doc.setFont("MontserratSemibold");
      doc.setFontSize(10);
      doc.text(170, 40 , 'Gafete: '+ data.id_persona);
      doc.setFontSize(12);

      doc.setFont("PoppinsBold");
      doc.setTextColor(255, 255, 255);
      doc.text(margen1, 48.5, 'Datos Personales');



      //titulos en azul
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(18);

      doc.text(160, 107 , 'Edad: '+ data.edad +' años');
      doc.setFontSize(14);
      //doc.text(160, 115 , 'Tipo de Sangre: '+ data.tsangre );
      doc.setFont("MontserratRegular");
      doc.setFontSize(8);

      doc.text(margen1, 58, 'CUI');
      doc.text(margen1, 62, 'Tipo de Sangre:');
      doc.text(margen1, 66, 'Fecha de Nacimiento:');
      doc.text(margen1, 70, 'Lugar de Nacimiento:');
      doc.text(margen1, 74,  'Estado Civil:');
      doc.text(margen1, 78,  'Nacionalidad:');
      doc.text(margen1, 82,  'IGSS:');
      doc.text(margen1, 86,  'NIT:');
      doc.text(margen1, 90,  'Posee arma:');
      doc.text(margen1, 94,  'Licencia de arma:');
      doc.text(margen1, 98,  'Licencia de conducir:');
      doc.text(margen1, 102,  'Género:');
      doc.text(margen1, 106, 'Dirección:');
      doc.text(margen1, (110 + data.lineas), 'Teléfono:');
      doc.text(margen1, (114 + data.lineas), 'Profesión U Oficio:');

      doc.setFont("MontserratSemibold");
      doc.text(margen2, 58, data.cui);
      doc.text(margen2, 62, data.tsangre);
      doc.text(margen2, 66, data.fecha_nacimiento);
      doc.text(margen2, 70, data.municipio+ ', '+data.departamento);
      doc.text(margen2, 74,  data.estado_civil);
      doc.text(margen2, 78,  'Guatemalteca');
      doc.text(margen2, 82,  data.igss);
      doc.text(margen2, 86,  data.nit);
      doc.text(margen2, 90,  data.armas);
      doc.text(margen2, 94,  data.licencia_arma);
      doc.text(margen2, 98,  data.licenciaconducir);
      doc.text(margen2, 102,  data.genero);
      //doc.text(margen2, 98,  data.direccion);
      var p_lineas = doc.splitTextToSize(data.direccion, 100);
      doc.text(margen2, 106, p_lineas);

      doc.text(margen2, (110 + data.lineas), data.telefono);


      var p_d = data.profesion;
      var p_lineas = doc.splitTextToSize(p_d, 100);
      doc.text(margen2, (114 + data.lineas), p_lineas);

      doc.setDrawColor(215, 215, 215);
      doc.setFontSize(9);
      //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
      //doc.line(75, 10, 75,50);

      doc.setFontSize(11);
      // datos laborales

      puntodl = 127.5;
      doc.setFontSize(12);
      doc.setFont("PoppinsBold");
      doc.setTextColor(255, 255, 255);
      doc.text(margen1, puntodl, 'Datos Laborales');
      //titulos en azul

      //datos Laborales
      doc.setTextColor(68,68,68);
      doc.setFontSize(9);
      doc.text(margen1, puntodl + 10, 'Datos Nominales');
      doc.text(margen1, 158, 'Datos Funcionales');

      doc.setFontSize(8);
      doc.setFontType("normal");
      doc.setFont("MontserratRegular");
      doc.text(margen1, 142 , 'Renglón:');
      doc.text(margen1, 146 , 'Dirección:');
      doc.text(margen1, 150 , 'Cargo:');
      doc.text(margen1, 163 , 'Dirección:');
      doc.text(margen1, 167 , 'Puesto Funcional:');

      doc.text(margen2, 142, data.renglon);
      doc.text(margen2, 146, data.dependencia);
      doc.text(margen2, 150, data.cargo);
      doc.text(margen2, 163, data.dep);
      doc.text(margen2, 167, data.puesto);

      // fin datos laborales

      //sidebar
      /*doc.setFontSize(12);
      p2 = 140;
      margen5 = 160;
      doc.setFont("PoppinsBold");
      doc.text(margen5, 133.5, 'Control de Vacunas');

      doc.setFont('MontserratRegular')


      doc.setFontSize(8);
      var reformattedArray = data.vacunas.map(function(obj){
         var rObj = {};
         doc.text(margen5, p2, obj.id_dosis);
         doc.text(margen5, p2+4, obj.tipo_vacuna);
         doc.text(margen5, p2+8, obj.fecha_vacuna);
         p2 +=8;
      });

      doc.setFont('MontserratSemibold');
      if(data.vacunas.find((item) => item.id_dosis == 'Refuerzo')){
        doc.text(margen5, p2, 'Esquema completo');
      }else{
        doc.text(margen5, p2, 'Le faltan dosis');
      }*/
      doc.setFont("MontserratSemibold");
      doc.setFontSize(14);
      doc.text(160, 180, 'Sueldo:');
      doc.setFont('PoppinsBold')
      doc.setFontSize(14);
      doc.writeText(0, 180 ,data.sueldo,{align:'right',width:208});
      //fin sidebar
      //inicio sueldo

      punto = 180;

      doc.setDrawColor(59, 175, 218);
      doc.setFillColor(59, 175, 218);
      doc.roundedRect(0, punto-5, 150, 8, 0, 0, 'FD');
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(0, punto-5, 2, 8, 0, 0, 'FD');
      doc.setTextColor(255, 255, 255);

      doc.setFontSize(12);
      doc.setFont('PoppinsBold')
      doc.text(margen1, punto, 'Ascensos');

      p1 = punto + 10;
      doc.setTextColor(68,68,68);
      doc.setFontSize(9);
      doc.setFont('MontserratSemibold')
      doc.text(margen1, p1, 'Renglón');
      doc.text(margen1+20, p1, 'Inicio');
      doc.text(margen1+37, p1, 'Final');
      doc.text(margen1+85, p1, 'Puesto');
      doc.setFont('MontserratRegular')
      p1 += 5;

      doc.setFontSize(8);
      var reformattedArray = data.plazas.map(function(obj){
         var rObj = {};
         //console.log(obj.inicio);
         doc.text(margen1+ 5, p1, obj.renglon);
         doc.text(margen1+17, p1, obj.inicio);
         doc.text(margen1+35, p1, obj.final);
         doc.text(margen1+55, p1, obj.puesto);
         doc.setFont('MontserratSemibold')
         doc.text(margen1+155, p1, obj.estado);
         doc.setFont('MontserratRegular')
         p1 +=4;
      });

      if(data.vacaciones.length > 0){
        puntov = 127.5;
        p2 = 140;
        var margen8 = 160;
        doc.setFont("PoppinsBold");
        doc.setFontSize(9);
        doc.text(margen8, puntov, 'Vacaciones');

        puntov += 10;

        doc.setFont('MontserratRegular')
        doc.setFontSize(8);
        var reformattedArray = data.vacaciones.map(function(obj){
           var rObj = {};
           var diasdec = obj.dia_asi - obj.dia_goz;
           var diasint = parseInt(obj.dia_asi - obj.dia_goz);
           var horas = ((diasdec - diasint) * 8);
           var horastext = (horas > 0) ? ' con '+Math.round(horas) + ' (horas)' : ''
           doc.setFont('MontserratRegular')
           doc.text(margen8, puntov, 'Perído:');
           doc.text(margen8 + 22, puntov, 'días:');
           doc.setFont('MontserratSemibold')
           doc.text(margen8 + 12, puntov, obj.anio_des);
           doc.text(margen8 + 29, puntov, ''+diasint+''+ horastext);
           //doc.text(margen5, puntov+8, obj.fecha_vacuna);
           puntov +=4;
        });
      }else{
        puntov = 130;
        p2 = 140;
        var margen8 = 160;
        doc.setFont("PoppinsBold");
        doc.text(margen8, puntov-10, 'No cuenta con períodos vacacionales');
      }

      doc.setDrawColor(59, 175, 218);
      doc.setFillColor(59, 175, 218);
      doc.roundedRect(0, p1 +2, 150, 8, 0, 0, 'FD');
      doc.setDrawColor(0, 136, 176);
      doc.setFillColor(0, 136, 176);
      doc.roundedRect(0, p1+2, 2, 8, 0, 0, 'FD');
      doc.setTextColor(255, 255, 255);

      doc.setFontSize(12);
      doc.setFont('PoppinsBold')

      doc.text(margen1, p1+7.5, 'Información Adicional');

      doc.setTextColor(68,68,68);
      doc.setFontSize(8);
      p1+= 7;

      //console.log(data.armas);

      if(data.estudios.length > 0){
        p1 +=8
        doc.setTextColor(68,68,68);
        doc.setFontSize(9);
        doc.setFont('MontserratSemibold')
        doc.text(margen1, p1, 'Nivel académico');
        doc.setFont('MontserratRegular');
        doc.setFontSize(8);
        var reformattedArray = data.estudios.map(function(obj){
          p1 +=4;
          var rObj = {};
          objeto = `${obj.nivel} / **${obj.titulo}**`;
          if(Number.isInteger(parseInt(obj.nro_colegiado))){
            objeto += ` /Colegiado: **${obj.nro_colegiado}**`;
          }
          multilineaConNegrita(margen1+5,p1,200,objeto,8);

          //doc.text(margen1+ 5, p1, obj.nivel+ ' / '+obj.titulo);
          //doc.text(margen1+35, p1, obj.titulo);
          evaluarPunto(p1);
        });
      }

      if(data.familia.length > 0){
        p1 +=8
        doc.setTextColor(68,68,68);
        doc.setFontSize(9);
        doc.setFont('MontserratSemibold')
        doc.text(margen1, p1, 'Parentesco');

        doc.setFont('MontserratRegular');
        doc.setFontSize(8);
        var reformattedArray = data.familia.map(function(obj){
           var rObj = {};
           if(obj.id_parentesco == 902 || obj.id_parentesco == 903 || obj.id_parentesco == 904 || obj.id_parentesco == 905){
             p1+= 4;
             doc.text(margen1+ 5, p1, obj.parentesco);
             doc.text(margen1+35, p1, obj.nombre);
             doc.text(margen1+120, p1, obj.edad);
             evaluarPunto(p1);
           }

        });
      }

      if(data.cursos.length > 0){
        p1 +=8
        doc.setTextColor(68,68,68);
        doc.setFontSize(9);
        doc.setFont('MontserratSemibold')
        doc.text(margen1, p1, 'Cursos');

        doc.setFont('MontserratRegular');
        doc.setFontSize(8);
        var reformattedArray = data.cursos.map(function(obj){
           var rObj = {};
           p1+= 4;
           doc.text(margen1+ 5, p1, obj.nombre_curso);

           evaluarPunto(p1);
        });
      }



      function evaluarPunto(puntoActual){
        if(puntoActual > 299){
          punto = 10;
          p1 = punto;
          doc.addPage();
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
          doc.setTextColor(68,68,68);
          pagina += 1;
        }
      }



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
                      startX = startX + doc.getStringUnitWidth(textItems) * 2.9;
                  });
                  boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
                  startX = startXCached;
                  startY += lineSpacing;
              }

          });
      }
      //console.log(doc.output('datauristring'));
      if(tipo == 1){
        doc.autoPrint()
        $("#pdf_preview_e").attr("src", doc.output('datauristring'));
      }else{
        var out = doc.output('datauristring');
        salida = `${out}`;
        $("#id_pdf").val(doc.output('datauristring'));
      }



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
