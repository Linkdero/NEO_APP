function nombramiento_reporte(id_nombramiento) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/nombramiento.php",
    data: { id_nombramiento },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var doc = new jsPDF('p', 'mm');
      var tipo = data.data[0].tipo;
      if (data.data[0].tipo == 1) {
        pages = 1;
        t_hojas = 1;
        var documento;
        var registros = data.data.length;
        //var pages = t_hojas;

        if (registros > 18) {
          verificar_paginas = registros - 18;
          if (verificar_paginas < 0) {
            verificar_paginas = verificar_paginas * -1;
          }
          siguiente_pagina = (verificar_paginas / 50);
          verificacion = siguiente_pagina - Math.floor(siguiente_pagina);
          // console.log(verificar_paginas);
          // console.log(verificacion);
          // console.log(siguiente_pagina);
          suma = 0;

          if (verificacion > 0.23 && verificacion < 0.3 || verificacion > 0.4 && verificacion < 0.5) {
            suma = 1;
          }
          t_hojas = Math.ceil(registros / 50) + suma;
          pages = t_hojas;
        }



        var punto_ = 50;
        var punto = punto_ + 4;
        var correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario, observaciones = '', destinos = '', descripcion_lugar = '';
        punto += 0;
        //console.log(data);
        doc.setFontType("normal");
        doc.setFontSize(9);
        i = 0;
        incremental = 1;
        for (var h = 0; h <= t_hojas; h++) {
          doc.setTextColor(68, 68, 68);
          doc.setFontType("normal");

          for (var x = 0; x < 50; x++) {
            if (i < registros) {
              emp = (i + 1) + ' - ' + data.data[i].empleado;
              caracteres = emp.length
              doc.text(25, punto, emp);
              if (data.data[i].bln_cheque == 1) {
                doc.circle(22, punto - 1, 1, 'FD');
              }
              correlativo = data.data[i].correlativo;
              direccion = data.data[i].direccion;
              fecha = data.data[i].fecha;
              lugar = data.data[i].lugar;
              fecha_ini = data.data[i].fecha_ini;
              fecha_fin = data.data[i].fecha_fin;
              hora_ini = data.data[i].hora_ini;
              hora_fin = data.data[i].hora_fin;
              motivo = data.data[i].motivo;
              nombramiento = data.data[i].nombramiento;
              duracion = data.data[i].duracion;
              beneficios = data.data[i].beneficios;
              funcionario = data.data[i].funcionario;
              observaciones = data.data[i].observaciones;
              punto += 4;
              i++;
            }
          }
          if (pages > 0) {
            doc.setFontSize(10);
            doc.writeText(5, 8, 'Página: ' + incremental + '/' + t_hojas, { align: 'right', width: 200 });

          }
          cabeceras_nombramiento(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, duracion, beneficios, funcionario, 1, incremental, t_hojas, observaciones, destinos, descripcion_lugar);
          if (incremental == pages) {

          }
          if (pages > 1) {
            punto_ = 50;
            punto = punto_ + 4
            doc.addPage();
          }
          incremental++;
          pages--;
        }
        var x = document.getElementById("pdf_preview_v");
        if (x.style.display === "none") {
          x.style.display = "none";
        } else {
          x.style.display = "none";
        }
        doc.autoPrint()
        $("#pdf_preview_v").attr("src", doc.output('datauristring'));
      } else {
        /* tipo = 2 */
        //inicio tipo 2
        var documento;
        var registros = data.data.length;
        pages = registros;
        t_hojas = registros;



        var punto_ = 65;
        var punto = punto_ + 4;

        punto += 0;
        //console.log(data);
        doc.setFontType("normal");
        doc.setFontSize(9);
        i = 0;

        incremental = 1;
        for (var i = 0; i < registros; i++) {

          doc.setTextColor(68, 68, 68);
          doc.setFontType("normal");
          t_filas = 0;
          punto_fila = 0;

          //if(pages>=0){
          var punto_ = 50;
          var punto = punto_ + 4;
          var correlativo, direccion = '', funcionario = '', beneficios = '', motivo = '', nombramiento = '', fecha = '', fecha_ini = '', fecha_fin = '', duracion = '', funcionario = '', observaciones = '', destinos = '', descripcion_lugar = '';
          punto += 0;

          var emp = data.data[i].empleado;
          caracteres = emp.length
          doc.text(25, punto, emp);

          correlativo = data.data[i].correlativo;
          direccion = data.data[i].direccion;
          fecha = data.data[i].fecha;
          lugar = data.data[i].lugar;
          fecha_ini = data.data[i].fecha_ini;
          fecha_fin = data.data[i].fecha_fin;
          hora_ini = data.data[i].hora_ini;
          hora_fin = data.data[i].hora_fin;
          motivo = data.data[i].motivo;
          nombramiento = data.data[i].nombramiento;
          duracion = data.data[i].duracion;
          beneficios = data.data[i].beneficios;
          funcionario = data.data[i].funcionario;
          observaciones = data.data[i].observaciones;
          punto += 4;


          doc.setFontSize(10);
          //doc.writeText(5,8,'Página: '+incremental+'/'+t_hojas,{align:'right',width:200});
          cabeceras_nombramiento_individual(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, duracion, beneficios, funcionario, 1, incremental, t_hojas, observaciones, destinos, descripcion_lugar);
          punto_ = 50;


          //}
          incremental++;
          pages--;
          if (pages > 0) {
            punto_ = 65;
            punto = punto_ + 4
            doc.addPage();
          }



          // console.log(i)


        }
        var x = document.getElementById("pdf_preview_v");
        if (x.style.display === "none") {
          x.style.display = "none";
        } else {
          x.style.display = "none";
        }
        doc.autoPrint()
        $("#pdf_preview_v").attr("src", doc.output('datauristring'));


        /* fin tipo = 2 */
      }

    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function nombramiento_definitivo_reporte(id_nombramiento, dia, mes, year) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/nombramiento_definitivo.php",
    data: {
      id_nombramiento,
      dia,
      mes,
      year
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var tipo = data.data[0].tipo;
      var doc = new jsPDF('p', 'mm');

      if (tipo == 1) {
        pages = 1;
        t_hojas = 1;
        var documento;
        var registros = data.data.length;
        //var pages = t_hojas;

        if (registros > 18) {
          verificar_paginas = registros - 18;
          if (verificar_paginas < 0) {
            verificar_paginas = verificar_paginas * -1;
          }
          siguiente_pagina = (verificar_paginas / 50);
          verificacion = siguiente_pagina - Math.floor(siguiente_pagina);
          // console.log(verificar_paginas);
          // console.log(verificacion);
          // console.log(siguiente_pagina);
          suma = 0;

          if (verificacion > 0.24 && verificacion < 0.3 || verificacion > 0.4 && verificacion < 0.5) {
            suma = 1;
          }
          t_hojas = Math.ceil(registros / 50) + suma;
          pages = t_hojas;
        }



        var punto_ = 50;
        var punto = punto_ + 4;
        var correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario, observaciones = '', destinos = '', descripcion_lugar = '';
        punto += 0;
        //console.log(data);
        doc.setFontType("normal");
        doc.setFontSize(9);
        i = 0;

        incremental = 1;
        for (var h = 0; h <= t_hojas; h++) {
          doc.setTextColor(68, 68, 68);
          doc.setFontType("normal");

          for (var x = 0; x < 50; x++) {
            if (i < registros) {
              doc.text(25, punto, (i + 1) + ' - ' + data.data[i].empleado);
              correlativo = data.data[i].correlativo;
              direccion = data.data[i].direccion;
              fecha = data.data[i].fecha;
              lugar = data.data[i].lugar;
              destinos = data.data[i].destinos;
              descripcion_lugar = data.data[i].descripcion_lugar;
              fecha_ini = data.data[i].fecha_ini;
              fecha_fin = data.data[i].fecha_fin;
              hora_ini = data.data[i].hora_ini;
              hora_fin = data.data[i].hora_fin;
              motivo = data.data[i].motivo;
              nombramiento = data.data[i].nombramiento;
              duracion = data.data[i].duracion;
              beneficios = data.data[i].beneficios;
              funcionario = data.data[i].funcionario;
              observaciones = data.data[i].observaciones;
              punto += 4;
              i++;
            }
          }
          if (pages > 0) {
            doc.setFontSize(10);
            doc.writeText(5, 8, 'Página: ' + incremental + '/' + t_hojas, { align: 'right', width: 200 });

          }
          cabeceras_nombramiento(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, duracion, beneficios, funcionario, 2, incremental, t_hojas, observaciones, destinos, descripcion_lugar);
          if (pages > 1) {
            punto_ = 50;
            punto = punto_ + 4
            doc.addPage();
          }
          incremental++;
          pages--;
        }
      } else {
        /* tipo = 2 */
        //inicio tipo 2
        var documento;
        var registros = data.data.length;
        pages = registros;
        t_hojas = registros;



        var punto_ = 65;
        var punto = punto_ + 4;

        punto += 0;
        //console.log(data);
        doc.setFontType("normal");
        doc.setFontSize(9);
        i = 0;

        incremental = 1;
        for (var i = 0; i < registros; i++) {

          doc.setTextColor(68, 68, 68);
          doc.setFontType("normal");
          t_filas = 0;
          punto_fila = 0;

          //if(pages>=0){
          var punto_ = 50;
          var punto = punto_ + 4;
          var correlativo, direccion = '', funcionario = '', beneficios = '', motivo = '', nombramiento = '', fecha = '', fecha_ini = '', fecha_fin = '', duracion = '', funcionario = '', observaciones = '', destinos = '', descripcion_lugar = '';
          punto += 0;

          var emp = data.data[i].empleado;
          caracteres = emp.length
          doc.text(25, punto, emp);

          correlativo = data.data[i].correlativo;
          direccion = data.data[i].direccion;
          fecha = data.data[i].fecha;
          lugar = data.data[i].lugar;
          fecha_ini = data.data[i].fecha_ini;
          fecha_fin = data.data[i].fecha_fin;
          hora_ini = data.data[i].hora_ini;
          hora_fin = data.data[i].hora_fin;
          motivo = data.data[i].motivo;
          nombramiento = data.data[i].nombramiento;
          duracion = data.data[i].duracion;
          beneficios = data.data[i].beneficios;
          funcionario = data.data[i].funcionario;
          observaciones = data.data[i].observaciones;
          destinos = data.data[i].destinos;
          descripcion_lugar = data.data[i].descripcion_lugar;
          punto += 4;


          doc.setFontSize(10);
          //doc.writeText(5,8,'Página: '+incremental+'/'+t_hojas,{align:'right',width:200});
          cabeceras_nombramiento_individual(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, duracion, beneficios, funcionario, 2, incremental, t_hojas, observaciones, destinos, descripcion_lugar);
          punto_ = 50;


          //}
          incremental++;
          pages--;
          if (pages > 0) {
            punto_ = 65;
            punto = punto_ + 4
            doc.addPage();
          }
          // console.log(i)


        }
      }

      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function cabeceras_nombramiento(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, hojas, fecha_ini, fecha_fin, duracion, beneficios, funcionario, tipo, incremental, t_hojas, observaciones, destinos, descripcion_lugar) {

  if (incremental <= t_hojas) {
    if (hojas == 1) {
      doc.text(25, 48, 'Señor: ');
    } else {
      doc.text(25, 48, 'Señores: ');
    }
    //punto-=20;
    doc.setFontType("normal");
    doc.setTextColor(68, 68, 68);
    doc.setFontSize(9);
    //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
    //documento = data.data[i].solicitud;

    doc.setFontType("bold");
    doc.addImage(baner, 'png', 40, 0, 135, 30);
    doc.setDrawColor(215, 215, 215);
    doc.setFontSize(10);
    doc.writeText(5, 11, 'Correlativo: ' + correlativo, { align: 'right', width: 200 });
    //doc.line(75, 10, 75,50);

    doc.setFontType("bold");
    doc.setFontSize(11);
    definitivo = (tipo == 1) ? '' : 'DEFINITIVO ';
    doc.writeText(5, 35, 'NOMBRAMIENTO ' + definitivo + 'SAAS/' + nombramiento, { align: 'center', width: 205 });
    doc.setFontSize(9);
    doc.setFontType('normal');
    doc.writeText(5, 45, fecha, { align: 'right', width: 185 });
    doc.setFontSize(9);
  }


  doc.setFontType('normal');
  if (incremental == t_hojas) {
    doc.setFontType("bold");
    doc.setTextColor(68, 68, 68);
    doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
    doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
    doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
    doc.writeText(25, punto + 12, 'PRESENTE', { align: 'left', width: 185 });


    //doc.line(120, 74, 120, 125);
    doc.setFontSize(9);
    doc.setFontType("bold");

    //doc.writeText(0, 65 ,data.data[i].fecha_solicitud,{align:'right',width:195});
    str = motivo.length;
    if (hojas == 1) {
      //doc.text(25, 48, 'Señor: ');
      doc.setFontType("normal");
      doc.text(25, punto + 20, 'Estimado Señor: ');
      //doc.text(60, punto+28, 'M ');
      var r_d1 = 'Me dirijo a usted para comunicarle que ha sido designado para realizar la comisión que se describe a continuación: ';
      var r_lineas1 = doc.splitTextToSize(r_d1, 170);
      doc.text(25, punto + 28, r_lineas1);
    } else {
      //doc.text(25, 48, 'Señores: ');
      doc.setFontType("normal");
      doc.text(25, punto + 20, 'Estimados Señores: ');
      var r_d1 = 'Me dirijo a ustedes para comunicarles que han sido designados para realizar la comisión que se describe a continuación: ';
      var r_lineas1 = doc.splitTextToSize(r_d1, 170);
      doc.text(25, punto + 28, r_lineas1);
    }


    doc.setDrawColor(225, 225, 225);
    doc.setFillColor(255, 255, 255);
    punto_mas = 32
    punto_mas_ = 0;
    if (str > 10) {
      punto_mas_ = 60;
    } if (str > 70) {
      punto_mas_ = 70;
    }
    var punto_siguiente = punto + punto_mas_;
    doc.roundedRect(25, punto + 32, 165, punto_mas_ - 10, 1, 1);

    //doc.line(25, punto+punto_mas+50, 190, punto+punto_mas+50);

    punto_destino = punto + punto_mas + 6;
    //alert(data.data[i].descripcion_lugar)
    if (descripcion_lugar == 2) {
      doc.setFontType('bold');
      doc.text(30, punto + punto_mas + 6, 'Lugares:');
      doc.setFontType("normal");
      for (nombreIndice in destinos) {
        doc.writeText(60, punto_destino, destinos[nombreIndice].dep, { align: 'left', width: 215 });
        punto_destino += 5;
        punto += 5;
      }
    } else {
      doc.setFontType('bold');
      doc.text(30, punto + punto_mas + 6, 'Lugar:');
      doc.setFontType("normal");
      doc.writeText(60, punto + punto_mas + 6, lugar, { align: 'left', width: 215 });
    }

    doc.line(25, punto + punto_mas + 10, 190, punto + punto_mas + 10);
    doc.line(25, punto + punto_mas + 20, 190, punto + punto_mas + 20);
    doc.line(25, punto + punto_mas + 30, 190, punto + punto_mas + 30);
    doc.line(25, punto + punto_mas + 40, 190, punto + punto_mas + 40);

    doc.text(30, 104, '');
    doc.text(30, 109, '');
    doc.text(30, 114, '');
    doc.setFontType('bold');

    doc.text(30, punto + punto_mas + 14, 'Fecha salida:');
    doc.text(30, punto + punto_mas + 18, 'Hora salida:');
    doc.text(100, punto + punto_mas + 14, 'Fecha regreso:');
    doc.text(100, punto + punto_mas + 18, 'Hora regreso:');
    doc.text(100, punto + punto_mas + 26, 'Duración:');
    doc.text(30, punto + punto_mas + 26, 'Beneficios:');
    doc.text(30, punto + punto_mas + 36, 'Funcionario:');
    doc.text(30, punto + punto_mas + 46, 'Motivo:');
    doc.text(30, 139, '');
    doc.text(30, 144, '');

    doc.setFontType("normal");

    doc.text(60, punto + punto_mas + 14, fecha_ini);
    doc.text(130, punto + punto_mas + 14, fecha_fin);
    doc.text(60, punto + punto_mas + 18, hora_ini);
    doc.text(130, punto + punto_mas + 18, hora_fin);
    doc.text(130, punto + punto_mas + 26, duracion);
    doc.text(60, punto + punto_mas + 26, beneficios);
    doc.text(60, punto + punto_mas + 36, funcionario);
    var r_d = motivo;
    var r_lineas = doc.splitTextToSize(r_d, 120);
    doc.text(60, punto + punto_mas + 46, r_lineas);

    doc.roundedRect(25, punto_siguiente + 25, 165, 20, 1, 1);
    doc.setFontType("normal");
    var r_d1 = 'CONDICIONES:  Esta Institución le proporcionará los viáticos, conforme a lo que establece el “REGLAMENTO GENERAL DE VIATICOS Y GASTOS CONEXOS”; Acuerdo Gubernativo 106-2016 y sus Reformas según Acuerdos Gubernativos 148-2016 y 35-2017; por lo que deberá abocarse a la Dirección Administrativa y Financiera para los efectos de trámite.';
    var r_lineas1 = doc.splitTextToSize(r_d1, 160);
    doc.text(27.5, punto_siguiente + 30, r_lineas1);
    doc.setDrawColor(215, 215, 215);
    //doc.line(80, 220, 140, 220);
    if (observaciones != '' || observaciones != 'value') {
      var r_d2 = observaciones;
      var r_lineas2 = doc.splitTextToSize(r_d2, 160);
      doc.text(27.5, punto_siguiente + 49, r_lineas2);
      punto_siguiente += 5;
    }

    doc.setFontType('bold');

    doc.writeText(0, punto_siguiente + 49, 'Atentamente', { align: 'center', width: 215 });
  } else if (incremental < t_hojas) {
    doc.setFontType('bold');
    doc.writeText(150, punto - 3, 'Continúa en página siguiente', { align: 'left', width: 215 });
    doc.setFontType('normal');
  }

  if (incremental > 1 && incremental <= t_hojas) {
    doc.setFontType('bold');
    doc.writeText(25, 45, "Vienen de página anterior", { align: 'left', width: 215 });
    doc.setFontType('normal');
  }



  //doc.text(75, 220, '(f)');
  //doc.writeText(0, 224 ,'SECRETARIO GENERAL',{align:'center',width:215});

  if (incremental <= t_hojas) {
    doc.setFontType('normal');
    doc.setTextColor(5, 83, 142);
    doc.setFontSize(8);
    doc.writeText(0, 258, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 215 });
    doc.setFontType('bold');
    doc.setFontSize(10);
    doc.writeText(5, 266, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
    doc.writeText(5, 269, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
    doc.writeText(5, 274, 'https://www.saas.gob.gt', { align: 'center', width: 209 });

    doc.setDrawColor(14, 4, 4);
    doc.line(0, 260, 220, 260);
  }

  //doc.addImage(footer, 'png', 0, 260, 216, 15);
}

function cabeceras_nombramiento_individual(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, hojas, fecha_ini, fecha_fin, duracion, beneficios, funcionario, tipo, incremental, t_hojas, observaciones, destinos, descripcion_lugar) {

  if (incremental <= t_hojas) {
    doc.text(25, 48, 'Señor: ');
    //punto-=20;
    doc.setFontType("normal");
    doc.setTextColor(68, 68, 68);
    doc.setFontSize(9);
    //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
    //documento = data.data[i].solicitud;

    doc.setFontType("bold");
    doc.addImage(baner, 'png', 40, 0, 135, 30);
    doc.setDrawColor(215, 215, 215);
    doc.setFontSize(10);

    //doc.line(75, 10, 75,50);

    doc.setFontType("bold");
    doc.setFontSize(11);
    definitivo = (tipo == 1) ? '' : 'DEFINITIVO ';
    doc.writeText(5, 35, 'NOMBRAMIENTO ' + definitivo + 'SAAS/' + nombramiento, { align: 'center', width: 205 });
    doc.setFontSize(9);
    doc.setFontType('normal');
    doc.writeText(5, 45, fecha, { align: 'right', width: 185 });
    doc.setFontSize(9);
  }


  doc.setFontType('normal');

  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
  doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(25, punto + 12, 'PRESENTE', { align: 'left', width: 185 });


  //doc.line(120, 74, 120, 125);
  doc.setFontSize(9);
  doc.setFontType("bold");

  //doc.writeText(0, 65 ,data.data[i].fecha_solicitud,{align:'right',width:195});
  str = motivo.length;
  //doc.text(25, 48, 'Señor: ');
  doc.setFontType("normal");
  doc.text(25, punto + 20, 'Estimado Señor: ');
  //doc.text(60, punto+28, 'M ');
  var r_d1 = 'Me dirijo a usted para comunicarle que ha sido designado para realizar la comisión que se describe a continuación: ';
  var r_lineas1 = doc.splitTextToSize(r_d1, 170);
  doc.text(25, punto + 28, r_lineas1);



  doc.setDrawColor(225, 225, 225);
  doc.setFillColor(255, 255, 255);
  punto_mas = 32
  punto_mas_ = 0;
  if (str > 10) {
    punto_mas_ = 60;
  } if (str > 70) {
    punto_mas_ = 70;
  }
  var punto_siguiente = punto + punto_mas_;
  var punto_a = punto + 32


  //doc.line(25, punto+punto_mas+50, 190, punto+punto_mas+50);

  punto_destino = punto + punto_mas + 6;
  //alert(data.data[i].descripcion_lugar)
  if (descripcion_lugar == 2) {
    doc.setFontType('bold');
    doc.text(30, punto + punto_mas + 6, 'Lugares:');
    doc.setFontType("normal");
    for (nombreIndice in destinos) {
      doc.writeText(60, punto_destino, destinos[nombreIndice].dep, { align: 'left', width: 215 });
      punto_destino += 5;
      punto += 5;
    }
  } else {
    doc.setFontType('bold');
    doc.text(30, punto + punto_mas + 6, 'Lugar:');
    doc.setFontType("normal");
    doc.writeText(60, punto + punto_mas + 6, lugar, { align: 'left', width: 215 });
  }
  punto = punto + punto_mas + 10;
  doc.line(25, punto, 190, punto);

  punto = punto + 10;
  doc.line(25, punto, 190, punto);
  doc.setFontType('bold');
  doc.text(100, punto - 4, 'Hora regreso:');
  doc.text(30, punto - 4, 'Hora salida:');
  doc.setFontType('normal');
  doc.text(60, punto - 4, hora_ini);
  doc.text(130, punto - 4, hora_fin);


  punto = punto + 10;
  doc.line(25, punto, 190, punto);
  doc.setFontType('bold');
  doc.text(30, punto - 4, 'Fecha salida:');
  doc.text(100, punto - 4, 'Fecha regreso:');
  doc.setFontType('normal');
  doc.text(60, punto - 4, fecha_ini);
  doc.text(130, punto - 4, fecha_fin);

  punto = punto + 10;
  doc.line(25, punto, 190, punto);
  doc.setFontType('bold');
  doc.text(100, punto - 4, 'Duración:');
  doc.setFontType('normal');
  doc.text(130, punto - 4, duracion);
  doc.setFontType('bold');
  doc.text(30, punto - 4, 'Beneficios:');
  doc.setFontType('normal');
  doc.text(60, punto - 4, beneficios);


  punto = punto + 10;
  doc.line(25, punto, 190, punto);
  doc.setFontType('bold');
  doc.text(30, punto - 4, 'Funcionario:');
  doc.setFontType('normal');
  doc.text(60, punto - 4, funcionario);
  punto = punto + 10;
  doc.setFontType('bold');
  doc.text(30, punto - 4, 'Motivo:');
  doc.setFontType('normal');
  var r_d = motivo;
  var r_lineas = doc.splitTextToSize(r_d, 120);

  doc.text(60, punto - 4, r_lineas);
  punto += 10;

  doc.roundedRect(25, punto_a, 165, punto - 100, 1, 1);



  punto_siguiente = punto - 10;
  doc.roundedRect(25, punto_siguiente + 10, 165, 20, 1, 1);
  doc.setFontType("normal");
  var r_d1 = 'CONDICIONES:  Esta Institución le proporcionará los viáticos, conforme a lo que establece el “REGLAMENTO GENERAL DE VIATICOS Y GASTOS CONEXOS”; Acuerdo Gubernativo 106-2016 y sus Reformas según Acuerdos Gubernativos 148-2016 y 35-2017; por lo que deberá abocarse a la Dirección Administrativa y Financiera para los efectos de trámite.';
  var r_lineas1 = doc.splitTextToSize(r_d1, 160);
  doc.text(27.5, punto_siguiente + 15, r_lineas1);
  doc.setDrawColor(215, 215, 215);
  //doc.line(80, 220, 140, 220);
  if (observaciones != '' || observaciones != 'value') {
    var r_d2 = observaciones;
    var r_lineas2 = doc.splitTextToSize(r_d2, 160);
    doc.text(27.5, punto_siguiente + 39, r_lineas2);
    punto_siguiente += 5;
  }

  doc.setFontType('bold');

  doc.writeText(0, punto_siguiente + 49, 'Atentamente', { align: 'center', width: 215 });




  //doc.text(75, 220, '(f)');
  //doc.writeText(0, 224 ,'SECRETARIO GENERAL',{align:'center',width:215});

  if (incremental <= t_hojas) {
    doc.setFontType('normal');
    doc.setTextColor(5, 83, 142);
    doc.setFontSize(8);
    doc.writeText(0, 258, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 215 });
    doc.writeText(5, 258, 'Comisión No. ' + correlativo, { align: 'right', width: 200 });
    doc.setFontType('bold');
    doc.setFontSize(10);
    doc.writeText(5, 266, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
    doc.writeText(5, 269, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
    doc.writeText(5, 274, 'https://www.saas.gob.gt', { align: 'center', width: 209 });

    doc.setDrawColor(14, 4, 4);
    doc.line(0, 260, 220, 260);
  }

  //doc.addImage(footer, 'png', 0, 260, 216, 15);
}

function imprimir_con_fecha(nombramiento, tipo, id_empleado) {
  var f = new Date();
  var months = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre")

  dias = '';
  meses = '';
  for (var i = 1; i <= 31; i++) {
    dias += '<option value="' + i + '"';
    if (i == f.getDate()) {
      dias += 'selected';
    }
    dias += '>' + i + '</option>';
  }
  for (var i = 0; i <= 11; i++) {
    meses += '<option value="' + i + '"';
    if (i == f.getMonth()) {
      meses += 'selected';
    }
    meses += '>' + months[i] + '</option>';
  }
  titulo = '';
  if (tipo == 1) {
    titulo = ' para el Anticipo';
  } else if (tipo == 2) {
    titulo = ' para la Liquidación';
  } else if (tipo == 3) {
    titulo = ' para el Informe';
  }
  Swal.fire({
    title: 'Seleccionar fecha de impresión' + titulo,
    html:
      '<br><div class="row"><div class="col-sm-4">' +
      '<select id="swal-input1" class="form-control">' +
      dias +
      '</select></div><div class="col-sm-4">' +
      '<select id="swal-input2" class="form-control">' +
      meses +

      '</select></div><div class="col-sm-4">' +
      '<input id="swal-input3" class="form-control" value="' + f.getFullYear() + '"></div></div>',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Imprimir!'
  }).then((result) => {
    if (result.value) {
      var dia = document.getElementById('swal-input1').value;
      var mes = $("#swal-input2 option:selected").text();
      var year = document.getElementById('swal-input3').value;
      if (tipo == 1) {
        imprimir_anticipo(nombramiento, dia, mes, year);
      } else if (tipo == 2) {
        imprimir_liquidacion(nombramiento, dia, mes, year, id_empleado);
      } else if (tipo == 3) {
        imprimir_informe(nombramiento, dia, mes, year);
      } else if (tipo == 4) {
        nombramiento_definitivo_reporte(nombramiento, dia, mes, year);
      } else if (tipo == 5) {
        imprimir_complemento(nombramiento, dia, mes, year);
      } else if (tipo == 6) {
        imprimir_monto_por_nombramiento(nombramiento, dia, mes, year);
      }
    }
  })
}

function imprimir_anticipo(nombramiento, dia, mes, year) {
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_viatico_anticipo.php",
    data: {
      nombramiento: nombramiento,
      dia: dia,
      mes: mes,
      year: year
    },
    dataType: 'json', //f de fecha y u de estado.




    beforeSend: function () {
      //$('#response').html('<span class="text-info">Loading response...</span>');


    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var documento;
      var hojas = data.data.length;
      var doc = new jsPDF('p', 'mm');

      for (var i = 0; i < data.data.length; i++) {
        punto = 10;
        //doc.setTextColor(203, 50, 52);
        doc.addImage(escudo_viatico, 'png', 60, 3, 20, 20);
        doc.addImage(logo_cgc, 'png', 137, punto - 2, 17, 17);
        doc.setFontType("bold");
        doc.setFontSize(12);
        doc.writeText(0, punto, 'VA-SAAS-001-SCC', { align: 'right', width: 198 });
        doc.setFontSize(9);
        doc.writeText(0, punto + 7, 'CORRELATIVO CGC No.', { align: 'right', width: 198 });
        doc.setFontSize(11);
        doc.writeText(0, punto + 15, data.data[i].formulario, { align: 'right', width: 194 });
        doc.setFontSize(10);
        doc.setFontType("normal");
        doc.writeText(140, punto + 25, 'Por Q.', { align: 'right', width: 10 });
        doc.setFontSize(9);
        doc.writeText(0, punto + 24, data.data[i].monto_num, { align: 'right', width: 185 });
        doc.line(154, punto + 27, 200, punto + 27);
        doc.setFontSize(7.5);
        doc.writeText(154, punto + 32, '(EN NUMEROS)', { align: 'center', width: 45 });
        doc.setTextColor(33, 33, 33);



        doc.setFontSize(10);
        doc.setFontType("normal");
        doc.setFontType("bold");
        doc.writeText(0, punto + 16, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 140 });
        doc.writeText(0, punto + 20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 140 });
        doc.setFontType("normal");
        doc.writeText(0, punto + 24, 'GUATEMALA, C.A.', { align: 'center', width: 140 });
        doc.setFontSize(12);

        doc.setFontType("bold");
        doc.writeText(0, punto + 34, 'VIATICO ANTICIPO', { align: 'center', width: 140 });
        doc.setFontSize(5);
        doc.setFontType("normal");
        doc.writeText(5, punto + 37, 'EN CUMPLIMIENTO DEL ARTICULO 5 DEL REGLAMENTO DE GASTOS DE VIÁTICOS PARA EL ORGANISMO EJECUTIVO Y LAS ENTIDADES DESCENTRALIZADAS Y AUTÓNOMAS DEL ESTADO VIGENTE', { align: 'center', width: 205 });

        doc.setFontSize(10);
        //doc.writeText(0, 80 ,'NOMBRAMIENTO DE COMISION OFICIAL  '+data.nombramiento,{align:'center',width:215});

        //doc.setDrawColor(225,225,225);
        //doc.setFillColor(255, 255, 255);
        doc.setLineWidth(0.3);
        doc.roundedRect(156.5, 11.2, 45, 17.6, 2.5, 2.5);
        doc.roundedRect(132, 3, 73, 41, 2, 2);
        punto2 = 47;
        doc.roundedRect(10, 49, 195, 207, 1, 1);

        doc.line(10, 72, 205, 72);

        if (data.data[i].bln_confirma == 0) {
          doc.setTextColor(204, 204, 204);
          doc.setFontSize(100);
          doc.writeText(0, 140, 'ANULADO', { align: 'center', width: 215 });
          //doc.text(15, punto2+8.5, 'ANULADO');
        }
        doc.setTextColor(33, 33, 33);
        doc.setFontSize(8.5);
        doc.text(15, punto2 + 8.5, 'RECIBI DE: ');
        doc.text(15, punto2 + 18.5, 'LA CANTIDAD DE: ');
        doc.setFontSize(8.5);
        doc.writeText(10, punto2 + 8.5, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
        doc.writeText(10, punto2 + 18.5, data.data[i].monto_letras, { align: 'center', width: 205 });
        doc.setFontSize(6.5);
        doc.writeText(10, punto2 + 13, '(NOMBRE DE LA DEPENDENCIA)', { align: 'center', width: 205 });
        doc.writeText(10, punto2 + 23, '(EN LETRAS)', { align: 'center', width: 205 });

        doc.line(35, punto2 + 10, 200, punto2 + 10);
        doc.line(47, punto2 + 20, 200, punto2 + 20);

        doc.setFillColor(0, 0, 0);
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(1);
        //doc.roundedRect(10, punto2+35, 195, 0.5,0,0 );

        doc.setFontSize(9);
        doc.writeText(0, punto2 + 31, 'POR CONCEPTO DE ANTICIPO DE VIATICO PARA EL CUMPLIMIENTO DE LA SIGUIENTE COMISIÓN OFICIAL:', { align: 'center', width: 215 });
        doc.line(10, punto2 + 35, 205, punto2 + 35);
        doc.line(10, punto2 + 47, 205, punto2 + 47);
        doc.setFontType('bold');
        doc.writeText(10, punto2 + 40, 'TIPO DE COMISION', { align: 'center', width: 65 });
        doc.writeText(10, punto2 + 44, '(DESCRIPCION)', { align: 'center', width: 65 });


        doc.writeText(64, punto2 + 42, 'LUGARES EN QUE SE REALIZARA', { align: 'center', width: 89 });
        doc.writeText(150, punto2 + 42, 'NUMERO DE DIAS', { align: 'center', width: 50 });
        doc.line(10, punto2 + 110, 205, punto2 + 110);

        //doc.line(120, 74, 120, 125);
        doc.setFontType("normal");
        doc.setFontSize(8);
        /*var r_d1 = data.data[i].resolucion;
        var r_lineas1 = doc.splitTextToSize(r_d1, 190);
        doc.text(10, punto+250, r_lineas1);*/
        var r_d1 = data.data[i].tipo_comision;
        var r_lineas1 = doc.splitTextToSize(r_d1, 55);
        doc.text(13, punto2 + 55, r_lineas1);
        doc.setLineWidth(0.3);

        //doc.writeText(72, punto2+55 ,data.data[i].destino,{align:'center',width:89});
        var r_d1 = data.data[i].destino;
        var r_lineas1 = doc.splitTextToSize(r_d1, 55);
        doc.text(75.5, punto2 + 55, r_lineas1);
        doc.writeText(150, punto2 + 55, data.data[i].num_dias + ' % Acumulado ' + data.data[i].porcentaje_proyectado, { align: 'center', width: 50 });

        doc.line(72, punto2 + 35, 72, punto2 + 110);
        doc.line(145, punto2 + 35, 145, punto2 + 110);
        doc.setFontSize(9);
        //inicio detalle
        doc.writeText(15, punto2 + 116, 'SEGUN NOMBRAMIENTO NUMERO: ', { align: 'left', width: 65 });
        doc.writeText(150, punto2 + 116, 'FECHA:', { align: 'left', width: 65 });
        doc.line(73, punto2 + 118, 145, punto2 + 118);
        doc.line(162, punto2 + 118, 200, punto2 + 118);
        doc.writeText(77, punto2 + 116, data.data[i].nombramiento, { align: 'center', width: 65 });
        doc.writeText(170, punto2 + 116, data.data[i].fecha_solicitud, { align: 'center', width: 20 });
        //fin detalle
        doc.line(10, punto2 + 120, 205, punto2 + 120);
        //inicio nombramiento
        doc.setFontType("bold");
        doc.writeText(15, punto2 + 125, 'EMITIDO POR: ', { align: 'left', width: 65 });
        doc.setFontType("normal");
        doc.writeText(15, punto2 + 132, 'NOMBRE:', { align: 'left', width: 65 });
        doc.writeText(15, punto2 + 141, 'CARGO:', { align: 'left', width: 65 });
        doc.line(35, punto2 + 134, 200, punto2 + 134);
        doc.line(35, punto2 + 143, 200, punto2 + 143);
        doc.writeText(50, punto2 + 132, data.data[i].director, { align: 'left', width: 215 });
        doc.writeText(50, punto2 + 141, data.data[i].director_puesto, { align: 'left', width: 215 });
        //fin nombramiento
        doc.line(10, punto2 + 145, 205, punto2 + 145);
        //inicia emitido por
        doc.setFontType("bold");
        doc.writeText(15, punto2 + 150, 'PERSONA NOMBRADA: ', { align: 'left', width: 65 });
        doc.setFontType("normal");
        doc.writeText(15, punto2 + 157, 'NOMBRE:', { align: 'left', width: 65 });
        doc.writeText(15, punto2 + 164, 'CARGO:', { align: 'left', width: 65 });
        doc.writeText(15, punto2 + 171, 'LUGAR Y FECHA:', { align: 'left', width: 65 });
        doc.line(35, punto2 + 159, 200, punto2 + 159);
        doc.line(35, punto2 + 166, 200, punto2 + 166);
        doc.line(50, punto2 + 173, 200, punto2 + 173);
        doc.writeText(50, punto2 + 157, data.data[i].emp, { align: 'left', width: 215 });
        doc.writeText(50, punto2 + 164, data.data[i].cargo, { align: 'left', width: 215 });
        doc.writeText(50, punto2 + 171, 'Guatemala ' + data.data[i].hoy, { align: 'left', width: 215 });
        //fin emitido por
        doc.line(10, punto2 + 175, 205, punto2 + 175);
        //inicia persona nombrada
        doc.text(15, punto2 + 180, 'FIRMA:');
        doc.text(113, punto2 + 180, 'Vo. Bo.');

        doc.setFontSize(6.5);
        doc.line(20, punto2 + 204, 100, punto2 + 204);
        doc.line(118, punto2 + 204, 198, punto2 + 204);
        doc.writeText(10, punto2 + 207, 'PERSONA NOMBRADA', { align: 'center', width: 95 });
        doc.writeText(108, punto2 + 207, 'AUTORIDAD QUE EMITIO EL NOMBRAMIENTO', { align: 'center', width: 95 });
        //finaliza persona nombrada
        doc.line(108, punto2 + 175, 108, punto2 + 209);
        doc.setFontSize(9);
        doc.setFontType("normal");
        //doc.setTextColor(255, 255, 255);


        //doc.setTextColor(255, 255, 255);

        doc.setTextColor(33, 33, 33);




        //doc.writeText(0, punto+150 ,data.data[i].resolucion,{align:'center',width:215});

        doc.setFontType("normal");
        doc.setFontSize(7.5);
        var r_d1 = data.data[i].resolucion;
        var r_lineas1 = doc.splitTextToSize(r_d1, 190);
        doc.text(10, punto + 250, r_lineas1);

        doc.setFontType("bold");
        doc.setFontSize(9);
        doc.writeText(12, punto + 262, 'Original: Tesorería', { align: 'center', width: 95 });
        doc.writeText(108, punto + 262, 'Duplicado: Archivo', { align: 'center', width: 95 });




        hojas--;
        if (hojas != 0) {
          doc.addPage();
        }





      }
      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });



}

function imprimir_liquidacion(nombramiento, dia, mes, year, id_empleado) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_viatico_liquidacion.php",
    data: {
      nombramiento: nombramiento,
      id_empleado: id_empleado,
      dia: dia,
      mes: mes,
      year: year
    },
    dataType: 'json', //f de fecha y u de estado.




    beforeSend: function () {
      //$('#response').html('<span class="text-info">Loading response...</span>');


    },
    success: function (data) {

      //alert(data);
      //console.log(data);
      var documento;
      var hojas = data.data.length;
      var doc = new jsPDF('p', 'mm');

      for (var i = 0; i < data.data.length; i++) {
        punto = 10;
        //doc.setTextColor(203, 50, 52);
        doc.addImage(escudo_viatico, 'png', 60, 3, 20, 20);
        doc.addImage(logo_cgc, 'png', 142, punto - 2, 17, 17);
        doc.setFontType("bold");
        doc.setFontSize(12);
        doc.writeText(5, punto, 'VL-SAAS-001-SCC', { align: 'right', width: 198 });
        doc.setFontSize(9);
        doc.writeText(5, punto + 7, 'CORRELATIVO CGC No.', { align: 'right', width: 198 });
        doc.setFontSize(11);
        doc.writeText(10, punto + 15, data.data[i].formulario, { align: 'right', width: 194 });
        doc.setFontSize(10);
        doc.setFontType("normal");
        doc.writeText(145, punto + 25, 'Por Q.', { align: 'right', width: 10 });
        doc.setFontSize(9);
        doc.writeText(5, punto + 24, data.data[i].total_real_cabecera, { align: 'right', width: 185 });
        doc.line(159, punto + 27, 205, punto + 27);
        doc.setFontSize(7.5);
        doc.writeText(159, punto + 32, '(EN NUMEROS)', { align: 'center', width: 45 });
        doc.setTextColor(33, 33, 33);



        doc.setFontSize(10);
        doc.setFontType("normal");
        doc.setFontType("bold");
        doc.writeText(0, punto + 16, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 140 });
        doc.writeText(0, punto + 20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 140 });
        doc.setFontType("normal");
        doc.writeText(0, punto + 24, 'GUATEMALA, C.A.', { align: 'center', width: 140 });
        doc.setFontSize(12);

        doc.setFontType("bold");
        doc.writeText(0, punto + 30, 'VIATICO LIQUIDACION', { align: 'center', width: 140 });
        doc.setFontSize(5);
        doc.setFontType("normal");
        doc.writeText(5, punto + 33, 'EN CUMPLIMIENTO DEL ARTICULO 5 DEL REGLAMENTO DE GASTOS DE VIÁTICOS PARA EL ORGANISMO EJECUTIVO Y LAS ENTIDADES', { align: 'center', width: 130 });
        doc.writeText(5, punto + 35, 'DESCENTRALIZADAS Y AUTÓNOMAS DEL ESTADO VIGENTE', { align: 'center', width: 130 });

        doc.setFontSize(10);
        //doc.writeText(0, 80 ,'NOMBRAMIENTO DE COMISION OFICIAL  '+data.nombramiento,{align:'center',width:215});

        //doc.setDrawColor(225,225,225);
        //doc.setFillColor(255, 255, 255);
        doc.setLineWidth(0.3);
        doc.roundedRect(161.5, 11.2, 45, 17.6, 2.5, 2.5);
        doc.roundedRect(137, 3, 73, 41, 2, 2);
        punto2 = 47;
        doc.roundedRect(5, 49, 206, 207, 1, 1);

        doc.line(5, 72, 211, 72);

        doc.setFontSize(8.5);
        doc.text(10, punto2 + 8.5, 'RECIBI DE: ');
        doc.text(10, punto2 + 18.5, 'LA CANTIDAD DE: ');
        doc.setFontSize(8.5);
        doc.writeText(10, punto2 + 8.5, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
        doc.writeText(10, punto2 + 18.5, data.data[i].monto_letras, { align: 'center', width: 205 });
        doc.setFontSize(6.5);
        doc.writeText(10, punto2 + 13, '(NOMBRE DE LA DEPENDENCIA)', { align: 'center', width: 205 });
        doc.writeText(10, punto2 + 23, '(EN LETRAS)', { align: 'center', width: 205 });

        doc.line(30, punto2 + 10, 205, punto2 + 10);
        doc.line(42, punto2 + 20, 205, punto2 + 20);

        doc.setFillColor(0, 0, 0);
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(1);
        //doc.roundedRect(10, punto2+35, 195, 0.5,0,0 );

        doc.setFontSize(9);
        doc.writeText(0, punto2 + 31, 'POR CONCEPTO DE GASTOS DE VIATICO Y OTROS GASTOS DEL CUMPLIMIENTO DE LA SIGUIENTE COMISIÓN OFICIAL:', { align: 'center', width: 215 });
        doc.line(5, punto2 + 35, 211, punto2 + 35);
        doc.line(5, punto2 + 47, 211, punto2 + 47);
        doc.setFontType('bold');
        doc.writeText(5, punto2 + 40, 'TIPO DE COMISION', { align: 'center', width: 65 });
        doc.writeText(5, punto2 + 44, '(DESCRIPCION)', { align: 'center', width: 65 });


        doc.writeText(64, punto2 + 40, 'LUGAR DE', { align: 'center', width: 53 });
        doc.writeText(64, punto2 + 44, 'PERMANENCIA', { align: 'center', width: 53 });
        doc.writeText(100.5, punto2 + 42, 'No. DE DIAS', { align: 'center', width: 53 });
        doc.writeText(137.5, punto2 + 42, 'CUOTA DIARIA', { align: 'center', width: 53 });
        doc.writeText(173.5, punto2 + 42, 'TOTAL Q.', { align: 'center', width: 40 });
        doc.line(5, punto2 + 110, 211, punto2 + 110);

        //doc.line(120, 74, 120, 125);
        doc.setFontType("normal");
        doc.setFontSize(8);
        /*var r_d1 = data.data[i].resolucion;
        var r_lineas1 = doc.splitTextToSize(r_d1, 190);
        doc.text(10, punto+250, r_lineas1);*/
        var r_d1 = data.data[i].tipo_comision;
        var r_lineas1 = doc.splitTextToSize(r_d1, 55);
        doc.text(8, punto2 + 55, r_lineas1);
        doc.setLineWidth(0.3);

        //doc.writeText(72, punto2+55 ,data.data[i].destino,{align:'center',width:89});
        punto_destino = punto2 + 55;
        //alert(data.data[i].descripcion_lugar)
        doc.writeText(100.5, punto2 + 52, data.data[i].num_dias, { align: 'center', width: 53 });
        if (data.data[i].descripcion_lugar == 2) {
          for (nombreIndice in data.data[i].destinos) {
            //var r_d1 = data.data[i].destino;
            var r_d1 = data.data[i].destinos[nombreIndice].dep;
            var r_lineas1 = doc.splitTextToSize(r_d1, 30);
            doc.text(75.5, punto_destino, r_lineas1);

            //doc.writeText(100.5, punto_destino ,data.data[i].num_dias,{align:'center',width:53});
            doc.setFontType("bold");
            doc.writeText(100.5, punto_destino, ' % Acumulado ', { align: 'center', width: 53 });
            doc.setFontType("normal");
            doc.writeText(100.5, punto_destino + 3, '' + data.data[i].destinos[nombreIndice].porcentaje + '', { align: 'center', width: 53 });
            var num = (data.data[i].cuota * data.data[i].destinos[nombreIndice].porcentaje);
            var n = num.toFixed(2);
            doc.writeText(150, punto_destino, data.data[i].moneda, { align: 'left', width: 30 });
            doc.writeText(145, punto_destino, data.data[i].cuota, { align: 'right', width: 30 });
            doc.writeText(181.5, punto_destino, '' + n + '', { align: 'right', width: 25 });
            punto_destino += 10;
          }


        } else {
          var r_d1 = data.data[i].destino;
          var r_lineas1 = doc.splitTextToSize(r_d1, 30);
          doc.text(75.5, punto2 + 55, r_lineas1);


          doc.setFontType("bold");
          doc.writeText(100.5, punto2 + 58, ' % Acumulado ', { align: 'center', width: 53 });
          doc.setFontType("normal");
          doc.writeText(100.5, punto2 + 61, data.data[i].porcentaje_real, { align: 'center', width: 53 });

          doc.writeText(150, punto2 + 55, data.data[i].moneda, { align: 'left', width: 30 });
          doc.writeText(145, punto2 + 55, data.data[i].cuota, { align: 'right', width: 30 });
          doc.writeText(181.5, punto2 + 55, data.data[i].monto_real, { align: 'right', width: 25 });
        }



        var r_d1 = data.data[i].reintegro_texto;
        var r_lineas1 = doc.splitTextToSize(r_d1, 55);
        doc.text(8, punto2 + 95, r_lineas1);
        doc.setLineWidth(0.3);

        var r_d1 = (data.data[i].desc_tipo_cambio != '') ? data.data[i].desc_tipo_cambio : '';
        var r_lineas1 = doc.splitTextToSize(r_d1, 75);
        doc.text(8, punto2 + 105, r_lineas1);

        doc.writeText(181.5, punto2 + 95, data.data[i].total_reintegro_, { align: 'right', width: 25 });


        //LINEAS VERTICALES
        doc.line(72, punto2 + 35, 72, punto2 + 110);
        doc.line(108.5, punto2 + 35, 108.5, punto2 + 110);
        doc.line(145, punto2 + 35, 145, punto2 + 110);
        doc.line(181.5, punto2 + 35, 181.5, punto2 + 128);
        doc.setFontSize(9);
        //inicio detalle
        doc.writeText(10, punto2 + 114, 'SUMAN LOS GASTOS DE VIATICOS ', { align: 'left', width: 65 });
        doc.writeText(10, punto2 + 120, 'OTROS GASTOS DERIVADOS SEGUN COMPROBANTE Y PLANILLA ADJUNTOS ', { align: 'left', width: 65 });
        doc.setFontType('bold');
        doc.writeText(10, punto2 + 126, 'TOTAL ', { align: 'left', width: 65 });
        doc.setFontType('normal');
        doc.writeText(181.5, punto2 + 114, data.data[i].total_real, { align: 'right', width: 28 });
        doc.writeText(181.5, punto2 + 120, data.data[i].otros_gastos, { align: 'right', width: 28 });
        doc.setFontType("bold");
        doc.writeText(181.5, punto2 + 126, data.data[i].total_real_total, { align: 'right', width: 28 });
        //doc.writeText(150, punto2+116 ,'FECHA:',{align:'left',width:65});
        //doc.line(65, punto2+118, 145, punto2+118);
        //doc.line(162, punto2+118, 200, punto2+118);
        //doc.writeText(74, punto2+116 ,data.data[i].nombramiento,{align:'center',width:65});
        //doc.writeText(170, punto2+116 ,data.data[i].fecha_solicitud,{align:'center',width:20});
        //fin detalle
        doc.line(5, punto2 + 116, 211, punto2 + 116);
        doc.line(5, punto2 + 122, 211, punto2 + 122);
        doc.setLineWidth(1);
        doc.line(5, punto2 + 128, 211, punto2 + 128);
        doc.setFontSize(12);
        doc.writeText(5, punto2 + 132, 'LIQUIDACION', { align: 'center', width: 205 });
        doc.setFontSize(9);
        doc.line(5, punto2 + 134, 211, punto2 + 134);
        doc.setLineWidth(0.3);
        doc.setFontType('normal');
        doc.line(5, punto2 + 140, 211, punto2 + 140);
        doc.line(5, punto2 + 146, 211, punto2 + 146);

        doc.text(183, punto2 + 114, 'Q.');
        doc.text(183, punto2 + 120, 'Q.');
        doc.text(183, punto2 + 126, 'Q.');

        doc.text(183, punto2 + 138, 'Q.');
        doc.text(183, punto2 + 144, 'Q.');
        doc.text(183, punto2 + 150, 'Q.');
        doc.text(183, punto2 + 156, 'Q.');



        doc.writeText(10, punto2 + 138, 'RECIBO POR MEDIO DE FORMULARIO V-A No. ' + data.data[i].numero_viatico_anticipo + '    ' + data.data[i].utilizado, { align: 'left', width: 65 });
        doc.writeText(10, punto2 + 144, 'REINTEGRO A LA DEPENDENCIA (-) ', { align: 'left', width: 65 });
        doc.writeText(10, punto2 + 150, 'COMPLEMENTO A MI FAVOR (+) ', { align: 'left', width: 65 });
        doc.writeText(181.5, punto2 + 138, data.data[i].monto_anticipo, { align: 'right', width: 28 });
        doc.writeText(181.5, punto2 + 144, (data.data[i].total_reintegro != '') ? data.data[i].total_reintegro : '0.00', { align: 'right', width: 28 });
        doc.writeText(181.5, punto2 + 150, data.data[i].a_favor, { align: 'right', width: 28 });
        doc.setLineWidth(1);
        doc.setFontType("bold");
        doc.line(5, punto2 + 152, 211, punto2 + 152);
        doc.writeText(10, punto2 + 156, 'TOTAL', { align: 'left', width: 205 });
        doc.writeText(181.5, punto2 + 156, data.data[i].total_, { align: 'right', width: 28 });
        doc.line(5, punto2 + 158, 211, punto2 + 158);
        doc.setLineWidth(0.3);
        doc.line(5, punto2 + 164, 211, punto2 + 164);
        doc.line(5, punto2 + 170, 211, punto2 + 170);
        doc.setFontType("normal");
        doc.writeText(10, punto2 + 162, 'LUGAR Y FECHA:', { align: 'left', width: 65 });
        doc.writeText(40, punto2 + 162, data.data[i].hoy, { align: 'left', width: 65 });
        doc.writeText(10, punto2 + 168, 'NOMBRE: ', { align: 'left', width: 65 });
        doc.writeText(28, punto2 + 168, data.data[i].emp, { align: 'left', width: 65 });
        doc.writeText(140, punto2 + 168, 'FIRMA: ', { align: 'left', width: 65 });
        doc.setLineWidth(1);
        doc.writeText(10, punto2 + 174, 'CARGO: ', { align: 'left', width: 65 });
        doc.setFontSize(7.5);
        doc.writeText(25, punto2 + 174, data.data[i].cargo, { align: 'left', width: 65 });
        doc.setFontSize(9);
        doc.writeText(108, punto2 + 174, 'SUELDO MENSUAL: ', { align: 'left', width: 65 });
        doc.writeText(140, punto2 + 174, data.data[i].sueldo, { align: 'left', width: 65 });
        doc.writeText(158, punto2 + 174, 'PARTIDA No. ', { align: 'left', width: 65 });
        doc.writeText(182, punto2 + 174, data.data[i].partida, { align: 'left', width: 65 });
        doc.line(5, punto2 + 170, 211, punto2 + 170);
        doc.line(5, punto2 + 176, 211, punto2 + 176);

        doc.setLineWidth(0.3);

        doc.setFontType('bold');
        doc.text(10, punto2 + 182, 'REVISADO POR:');
        doc.text(110, punto2 + 182, 'APROBADO POR.');

        doc.setFontType('normal');
        doc.text(10, punto2 + 192, 'CARGO:');
        doc.text(10, punto2 + 204, 'FIRMA:');
        doc.text(110, punto2 + 192, 'CARGO:');
        doc.text(110, punto2 + 204, 'FIRMA:');
        doc.setFontSize(6.5);
        doc.line(25, punto2 + 192, 100, punto2 + 192);
        doc.line(25, punto2 + 204, 100, punto2 + 204);
        doc.line(125, punto2 + 192, 202, punto2 + 192);
        doc.line(125, punto2 + 204, 202, punto2 + 204);
        //doc.writeText(5, punto2+207 ,'PERSONA NOMBRADA',{align:'center',width:95});
        doc.writeText(116, punto2 + 207, 'AUTORIDAD QUE ORDENÓ LA COMISIÓN', { align: 'center', width: 95 });
        //finaliza persona nombrada
        doc.line(108, punto2 + 176, 108, punto2 + 209);
        doc.setFontSize(9);
        doc.setFontType("normal");
        //doc.setTextColor(255, 255, 255);


        //doc.setTextColor(255, 255, 255);

        doc.setTextColor(33, 33, 33);




        //doc.writeText(0, punto+150 ,data.data[i].resolucion,{align:'center',width:215});

        doc.setFontType("normal");
        doc.setFontSize(7.5);
        var r_d1 = data.data[i].resolucion;
        var r_lineas1 = doc.splitTextToSize(r_d1, 190);
        doc.text(10, punto + 250, r_lineas1);

        doc.setFontType("bold");
        doc.setFontSize(9);
        doc.writeText(12, punto + 262, 'Original: Tesorería', { align: 'center', width: 95 });
        doc.writeText(108, punto + 262, 'Duplicado: Archivo', { align: 'center', width: 95 });




        hojas--;
        if (hojas != 0) {
          doc.addPage();
        }





      }
      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });





}

function imprimir_constancia(nombramiento, id_empleado) {
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_viatico_constancia.php",
    data: {
      nombramiento: nombramiento,
      id_empleado: id_empleado
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      var documento;
      var hojas = data.data.length;
      var doc = new jsPDF('p', 'mm');

      for (var i = 0; i < data.data.length; i++) {
        doc.setFontType("normal");
        punto = 10;
        punto2 = 47;
        doc.setFontSize(11);

        doc.writeText(10, punto + 16, data.data[i].formulario, { align: 'right', width: 187 });
        doc.setTextColor(33, 33, 33);
        doc.setFontSize(9);
        doc.writeText(47, punto2 + 25, data.data[i].emp, { align: 'left', width: 65 });
        doc.writeText(47, punto2 + 35, data.data[i].cargo, { align: 'left', width: 65 });

        var punto_destino = punto2 + 84;
        if (data.data[i].descripcion_lugar == 2) {
          for (nombreIndice in data.data[i].destinos) {
            doc.setFontSize(9);
            var r_d1 = data.data[i].destinos[nombreIndice].dep;
            var r_lineas1 = doc.splitTextToSize(r_d1, 35);
            doc.text(38, punto_destino, r_lineas1);

            doc.setFontSize(7);
            doc.writeText(77, punto_destino, data.data[i].destinos[nombreIndice].f_ini, { align: 'center', width: 30 });
            doc.writeText(93, punto_destino, data.data[i].destinos[nombreIndice].h_ini, { align: 'center', width: 30 });
            doc.writeText(111, punto_destino, data.data[i].destinos[nombreIndice].f_fin, { align: 'center', width: 30 });
            doc.writeText(128, punto_destino, data.data[i].destinos[nombreIndice].h_fin, { align: 'center', width: 30 });

            punto_destino += 25;
            doc.writeText(10, punto + 200, data.data[i].hospedaje, { align: 'center', width: 194 });
            doc.writeText(10, punto + 205, data.data[i].alimentacion, { align: 'center', width: 194 });
          }
        } else {
          var r_d1 = data.data[i].destino;
          var r_lineas1 = doc.splitTextToSize(r_d1, 30);
          doc.text(40, punto2 + 84, r_lineas1);
          doc.setFontSize(7);
          doc.writeText(77, punto2 + 84, data.data[i].hora_llegada, { align: 'center', width: 30 });

          doc.writeText(93, punto2 + 84, data.data[i].fecha_llegada_lugar, { align: 'center', width: 30 });
          doc.writeText(111, punto2 + 84, data.data[i].hora_salida, { align: 'center', width: 30 });
          doc.writeText(128, punto2 + 84, data.data[i].fecha_salida_lugar, { align: 'center', width: 30 });

          doc.writeText(10, punto + 175, data.data[i].hospedaje, { align: 'center', width: 194 });
          doc.writeText(10, punto + 180, data.data[i].alimentacion, { align: 'center', width: 194 });
        }

        //finaliza persona nombrada
        //doc.line(108, punto2+176, 108, punto2+209);
        doc.setFontSize(9);
        doc.setFontType("normal");
        //doc.setTextColor(255, 255, 255);
        //doc.setTextColor(255, 255, 255);

        doc.setTextColor(33, 33, 33);

        doc.setFontType("normal");
        doc.setFontSize(7.5);
        var r_d1 = data.data[i].resolucion;
        var r_lineas1 = doc.splitTextToSize(r_d1, 190);
        doc.text(10, punto + 240, r_lineas1);

        doc.setFontType("bold");
        doc.setFontSize(9);
        doc.writeText(12, punto + 252, 'Original: Tesorería', { align: 'center', width: 95 });
        doc.writeText(108, punto + 252, 'Duplicado: Archivo', { align: 'center', width: 95 });

        hojas--;
        if (hojas != 0) {
          doc.addPage();
        }

      }
      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });


}

function imprimir_constancia_vacia() {
  var documento;
  var doc = new jsPDF('p', 'mm');
  punto = 10;
  //doc.setTextColor(203, 50, 52);
  doc.addImage(escudo_viatico, 'png', 60, 3, 20, 20);
  doc.addImage(logo_cgc, 'png', 142, punto - 2, 17, 17);
  doc.setFontType("bold");
  doc.setFontSize(12);
  doc.setTextColor(220, 48, 35);
  doc.writeText(5, punto, 'VC-SAAS-001-SCC', { align: 'right', width: 198 });
  doc.setFontSize(9);
  doc.setTextColor(33, 33, 33);
  doc.writeText(5, punto + 7, 'CORRELATIVO CGC No.', { align: 'right', width: 198 });
  doc.setFontSize(11);



  doc.setFontSize(10);
  doc.setFontType("normal");
  doc.setFontType("bold");
  doc.writeText(0, punto + 16, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 140 });
  doc.writeText(0, punto + 20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 140 });
  doc.setFontType("normal");
  doc.writeText(0, punto + 24, 'GUATEMALA, C.A.', { align: 'center', width: 140 });
  doc.setFontSize(12);

  doc.setFontType("bold");
  doc.writeText(0, punto + 30, 'VIATICO CONSTANCIA', { align: 'center', width: 140 });
  doc.setFontSize(5);
  doc.setFontType("normal");
  doc.writeText(7, punto + 37, 'EN CUMPLIMIENTO DEL ARTICULO 5 DEL REGLAMENTO DE GASTOS DE VIÁTICOS PARA EL ORGANISMO EJECUTIVO Y LAS ENTIDADES DESCENTRALIZADAS Y AUTÓNOMAS DEL ESTADO VIGENTE', { align: 'center', width: 205 });
  //doc.writeText(5, punto+35 ,'',{align:'center',width:130});
  doc.setFontSize(13);
  punto2 = 57;
  punto3 = 47;

  doc.setFontType("bold");
  doc.writeText(0, punto3 + 7, 'SE HACE CONSTAR', { align: 'center', width: 210 });
  doc.setFontType("normal");
  doc.line(7, punto3 + 10, 210, punto3 + 10);
  doc.setFontSize(10);

  doc.setLineWidth(0.3);
  doc.roundedRect(161.5, 11.2, 45, 17.6, 2.5, 2.5);
  doc.roundedRect(137, 3, 73, 37, 2, 2);

  doc.roundedRect(7, 43, 203, 184, 1, 1);
  //doc.line(5, 72, 211, 72);
  doc.setFontSize(8.5);
  doc.text(12, punto2 + 8.5, 'QUE EL SEÑOR: ');
  doc.text(12, punto2 + 15.5, 'NOMBRE: ');
  doc.text(12, punto2 + 25.5, 'CARGO: ');
  doc.text(12, punto2 + 35.5, 'DEPENDENCIA: ');
  doc.setFontSize(8.5);
  doc.writeText(45, punto2 + 35.5, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 190 });
  doc.setFontSize(6.5);
  //doc.writeText(10, punto2+13 ,'(NOMBRE DE LA DEPENDENCIA)',{align:'center',width:205});
  //doc.writeText(10, punto2+23 ,'(EN LETRAS)',{align:'center',width:205});
  doc.line(27, punto2 + 17, 203, punto2 + 17);
  doc.line(27, punto2 + 27, 203, punto2 + 27);
  doc.line(37, punto2 + 37, 203, punto2 + 37);

  doc.setFillColor(0, 0, 0);
  doc.setDrawColor(0, 0, 0);
  //doc.setLineWidth(1);
  doc.setFontSize(9);
  doc.writeText(12, punto2 + 45.5, 'PERMANECIÓ EN COMISIÓN OFICIAL EN LOS LUGARES Y FECHAS QUE SE INDICAN:', { align: 'left', width: 215 });

  //doc.line(5, punto2+35, 211, punto2+35);
  doc.line(7, punto2 + 50, 210, punto2 + 50);

  doc.setFontType('bold');
  doc.writeText(7, punto2 + 55.5, 'No.', { align: 'center', width: 30 });
  doc.writeText(21, punto2 + 55, 'LUGAR DE', { align: 'center', width: 75 });
  doc.writeText(21, punto2 + 60, 'PERMANENCIA', { align: 'center', width: 75 });


  doc.writeText(75, punto2 + 55, 'INGRESO', { align: 'center', width: 53 });
  doc.writeText(65, punto2 + 60, 'HORA', { align: 'center', width: 53 });
  doc.writeText(82, punto2 + 60, 'FECHA', { align: 'center', width: 53 });

  doc.writeText(107.5, punto2 + 55, 'SALIDA', { align: 'center', width: 53 });
  doc.writeText(99.5, punto2 + 60, 'HORA', { align: 'center', width: 53 });
  doc.writeText(116.5, punto2 + 60, 'FECHA', { align: 'center', width: 53 });


  doc.writeText(155, punto2 + 55, 'AUTORIDAD QUIEN CONSTA', { align: 'center', width: 53 });
  doc.writeText(155, punto2 + 60, 'NOMBRE, FIRMA, SELLO', { align: 'center', width: 53 });

  doc.line(82, punto2 + 56, 152.5, punto2 + 56);
  doc.line(7, punto2 + 62, 210, punto2 + 62);

  doc.line(35, punto2 + 50, 35, punto2 + 170);
  doc.line(82, punto2 + 50, 82, punto2 + 170);
  doc.line(100, punto2 + 56, 100, punto2 + 170);
  doc.line(134, punto2 + 56, 134, punto2 + 170);
  doc.line(117, punto2 + 50, 117, punto2 + 170);
  doc.line(152.5, punto2 + 50, 152.5, punto2 + 170);
  doc.text(7, punto2 + 175, 'OBSERVACIONES: ');
  doc.line(40, punto2 + 177, 210, punto2 + 177);
  doc.line(7, punto2 + 184, 210, punto2 + 184);


  doc.setFontSize(9);
  doc.setFontType("normal");
  var x = document.getElementById("pdf_preview_v");
  if (x.style.display === "none") {
    x.style.display = "none";
  } else {
    x.style.display = "none";
  }
  doc.autoPrint()
  $("#pdf_preview_v").attr("src", doc.output('datauristring'));
}


function imprimir_informe(id_nombramiento, dia, mes, year) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_informe.php",
    data: {
      id_nombramiento,
      dia,
      mes,
      year
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {

      //alert(data);
      // console.log(data);
      var doc = new jsPDF('p', 'mm');
      if (data.data[0].tipo == 1) {
        var documento;
        var registros = data.data.length;
        suma = 0;
        verificar_paginas = registros - 5;
        siguiente_pagina = (verificar_paginas / 8);
        verificacion = siguiente_pagina - Math.floor(siguiente_pagina);
        // console.log(verificar_paginas);
        // console.log(siguiente_pagina);
        // console.log(verificacion);
        if (verificacion > 0 && verificacion < 0.5) {
          suma = 1;
        }
        var t_hojas = Math.ceil(registros / 8) + suma;
        var pages = t_hojas;


        var punto_ = 65;
        var punto = punto_ + 4;
        var correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario;
        punto += 0;
        //console.log(data);
        doc.setFontType("normal");
        doc.setFontSize(9);
        i = 0;

        incremental = 1;
        for (var h = 0; h <= t_hojas; h++) {

          doc.setTextColor(68, 68, 68);
          doc.setFontType("normal");
          t_filas = 0;
          punto_fila = 0;
          if (pages != t_hojas) {
            t_filas = 8;
            punto_fila += 60;
          } else {
            if (data.data[0].id_pais == 'GT') {
              t_filas = 5;
              punto_fila += 145;
            } else {
              t_filas = 5;
              punto_fila += 120;
            }
          }


          for (var x = 0; x < t_filas; x++) {

            if (i < registros) {

              doc.text(25, punto_fila, (i + 1) + '.');
              doc.text(30, punto_fila, '(f)    ' + data.data[i].empleado);
              doc.line(30, punto_fila + 2, 150, punto_fila + 2);
              punto_fila += 25;
              i++;
              if (i == registros) {
                doc.setFontType('bold');
                punto_fila -= 17
                doc.text(30, punto_fila, '** ULTIMA LINEA **');
              }
            }


          }

          if (pages > 0) {
            doc.writeText(5, 8, 'Página: ' + incremental + '/' + t_hojas, { align: 'right', width: 200 });

            doc.setFontType('normal');
            doc.setTextColor(5, 83, 142);
            doc.setFontSize(8);
            doc.writeText(0, 253, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 215 });
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(5, 261, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
            doc.writeText(5, 265, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
            doc.writeText(5, 269, 'https://www.saas.gob.gt', { align: 'center', width: 209 });

            doc.line(0, 255, 220, 255);




            doc.setFontType("bold");

            //punto-=20;
            doc.setFontType("normal");
            doc.setTextColor(68, 68, 68);
            doc.setFontSize(9);
            //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
            //documento = data.data[i].solicitud;

            doc.setFontType("bold");
            doc.addImage(baner, 'png', 40, 10, 135, 30);

            doc.setDrawColor(215, 215, 215);
            doc.setFontSize(8);
            //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
            //doc.line(75, 10, 75,50);

            doc.setFontType("bold");
            doc.setFontSize(11);

            doc.writeText(5, 45, 'INFORME DEL NOMBRAMIENTO SAAS/' + data.data[0].nombramiento, { align: 'center', width: 205 });
            doc.setFontSize(9);
            doc.setFontType('normal');
            //doc.writeText(5, 55 ,fecha,{align:'right',width:185});

            //doc.line(120, 74, 120, 125);
            doc.setFontSize(9);
            doc.setFontType("bold");

            //doc.writeText(0, 65 ,data.data[i].fecha_solicitud,{align:'right',width:195});
            str = motivo.length;
            if (pages == t_hojas) {
              doc.setTextColor(68, 68, 68);
              doc.setFontType('normal');
              doc.text(25, 55, 'Guatemala: ' + data.data[0].dia + ' de ' + data.data[0].mes + ' de ' + data.data[0].year);
              doc.setFontType('bold');
              doc.writeText(25, punto - 4, data.data[0].director, { align: 'left', width: 185 });

              doc.writeText(25, punto, data.data[0].direccion, { align: 'left', width: 185 });
              doc.setFontType('normal');
              doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
              doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
              doc.writeText(25, punto + 12, 'SU DESPACHO', { align: 'left', width: 185 });
              if (registros == 1) {
                //doc.text(25, 63, 'Señor: ');
                doc.setFontType("normal");
                var ess = (data.data[0].ess == 1) ? "Estimado Señor Subsecretario: " : "Estimado Señor: ";
                doc.text(25, punto + 20, ess);
                //doc.text(60, punto+28, 'M ');
                var r_d1 = 'Me dirijo a usted para informarle que  de conformidad con el Nombramiento número ' + data.data[0].nombramiento + ' de fecha ' + data.data[0].dia_nombramiento + ' de ' + data.data[0].mes_nombramiento + ' de ' + data.data[0].year_nombramiento + '. Me constituí en ' + data.data[0].lugar + ' del ' + data.data[0].dia_salida + ' de ' + data.data[0].mes_salida + ' de ' + data.data[0].year_salida + ' al ' + data.data[0].dia_regreso + ' de ' + data.data[0].mes_regreso + ' de ' + data.data[0].year_regreso + ' para ' + data.data[0].motivo + ' de conformidad con el horario siguiente: ';;
                var r_lineas1 = doc.splitTextToSize(r_d1, 170);
                doc.text(25, punto + 28, r_lineas1);
              } else {
                //doc.text(25, 63, 'Señores: ');
                doc.setFontType("normal");
                var ess = (data.data[0].ess == 1) ? "Estimado Señor Subsecretario: " : "Estimado Señor: ";
                doc.text(25, punto + 20, ess);
                var r_d1 = 'Nos dirijimos a usted para informarle que  de conformidad con el Nombramiento número ' + data.data[0].nombramiento + ' de fecha ' + data.data[0].dia_nombramiento + ' de ' + data.data[0].mes_nombramiento + ' de ' + data.data[0].year_nombramiento + '. Nos constituimos en ' + data.data[0].lugar + ' del ' + data.data[0].dia_salida + ' de ' + data.data[0].mes_salida + ' de ' + data.data[0].year_salida + ' al ' + data.data[0].dia_regreso + ' de ' + data.data[0].mes_regreso + ' de ' + data.data[0].year_regreso + ' para ' + data.data[0].motivo + ' de conformidad con el horario siguiente: ';
                var r_lineas1 = doc.splitTextToSize(r_d1, 170);
                doc.text(25, punto + 28, r_lineas1);
              }
              if (data.data[0].id_pais == 'GT') {
                doc.text(35, punto + 45, 'Hora de Salida de : ');
                doc.setFontType("bold");
                doc.text(65, punto + 45, 'SAAS ');

                doc.setFontType("normal");
                doc.text(140, punto + 45, data.data[0].hora_salida_saas);
                doc.text(160, punto + 45, data.data[0].fecha_salida);

                doc.text(35, punto + 50, 'Hora de llegada a : ');
                doc.text(65, punto + 50, data.data[0].lugar);
                doc.text(140, punto + 50, data.data[0].hora_llegada_lugar);
                doc.text(160, punto + 50, data.data[0].fecha_llegada_lugar);

                doc.text(35, punto + 55, 'Hora de Salida de : ');
                doc.text(65, punto + 55, data.data[0].lugar);
                doc.text(140, punto + 55, data.data[0].hora_salida_lugar);
                doc.text(160, punto + 55, data.data[0].fecha_salida_lugar);

                doc.text(35, punto + 60, 'Hora de llegada a : ');
                doc.setFontType("bold");
                doc.text(65, punto + 60, 'SAAS ');
                doc.setFontType("normal");
                doc.text(140, punto + 60, data.data[0].hora_regreso_saas);
                doc.text(160, punto + 60, data.data[0].fecha_regreso);

              }

            }

          }

          if (pages > 1) {
            punto_ = 65;
            punto = punto_ + 4
            doc.addPage();
          }
          incremental++;
          pages--;
        }
      }//fin tipo 1
      else {
        //inicio tipo 2
        var documento;
        var registros = data.data.length;
        pages = registros;



        var punto_ = 65;
        var punto = punto_ + 4;
        var correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario;
        punto += 0;
        //console.log(data);
        doc.setFontType("normal");
        doc.setFontSize(9);
        i = 0;

        incremental = 1;
        for (var i = 0; i <= registros; i++) {

          doc.setTextColor(68, 68, 68);
          doc.setFontType("normal");
          t_filas = 0;
          punto_fila = 0;

          if (pages > 0) {
            //doc.writeText(5,8,'Página: '+incremental+'/'+t_hojas,{align:'right',width:200});

            doc.setFontType('normal');
            doc.setTextColor(5, 83, 142);
            doc.setFontSize(8);
            doc.writeText(0, 253, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 215 });
            doc.setFontType('bold');
            doc.setFontSize(10);
            doc.writeText(5, 261, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
            doc.writeText(5, 265, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
            doc.writeText(5, 269, 'https://www.saas.gob.gt', { align: 'center', width: 209 });

            doc.line(0, 255, 220, 255);




            doc.setFontType("bold");

            //punto-=20;
            doc.setFontType("normal");
            doc.setTextColor(68, 68, 68);
            doc.setFontSize(9);
            //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
            //documento = data.data[i].solicitud;

            doc.setFontType("bold");
            doc.addImage(baner, 'png', 40, 10, 135, 30);

            doc.setDrawColor(215, 215, 215);
            doc.setFontSize(8);
            //doc.writeText(5,5,'Correlativo: '+correlativo,{align:'right',width:209});
            //doc.line(75, 10, 75,50);

            doc.setFontType("bold");
            doc.setFontSize(11);

            doc.writeText(5, 45, 'INFORME DEL NOMBRAMIENTO SAAS/' + data.data[i].nombramiento, { align: 'center', width: 205 });
            doc.setFontSize(9);
            doc.setFontType('normal');
            //doc.writeText(5, 55 ,fecha,{align:'right',width:185});

            //doc.line(120, 74, 120, 125);
            doc.setFontSize(9);
            doc.setFontType("bold");
            //dato empleado


            //doc.writeText(0, 65 ,data.data[i].fecha_solicitud,{align:'right',width:195});
            str = motivo.length;
            //if(pages==t_hojas){
            doc.setTextColor(68, 68, 68);
            doc.setFontType('normal');
            doc.text(25, 55, 'Guatemala: ' + data.data[0].dia + ' de ' + data.data[i].mes + ' de ' + data.data[i].year);
            doc.setFontType('bold');
            doc.writeText(25, punto - 4, data.data[i].director, { align: 'left', width: 185 });

            doc.writeText(25, punto, data.data[i].direccion, { align: 'left', width: 185 });
            doc.setFontType('normal');
            doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
            doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
            doc.writeText(25, punto + 12, 'SU DESPACHO', { align: 'left', width: 185 });
            //if(registros==1){
            //doc.text(25, 63, 'Señor: ');
            doc.setFontType("normal");
            var ess = (data.data[0].ess == 1) ? "Estimado Señor Subsecretario: " : "Estimado Señor: ";
            doc.text(25, punto + 20, ess);
            //doc.text(60, punto+28, 'M ');
            var place = (data.data[i].descripcion_lugar == 2) ? ' los ' + data.data[i].destinos.length + ' destinos que se detallan a continuación' : data.data[i].lugar;
            var r_d1 = 'Me dirijo a usted para informarle que  de conformidad con el Nombramiento número ' + data.data[i].nombramiento + ' de fecha ' + data.data[i].dia_nombramiento + ' de ' + data.data[i].mes_nombramiento + ' de ' + data.data[i].year_nombramiento + '. Me constituí en ' + place + ' del ' + data.data[i].dia_salida + ' de ' + data.data[i].mes_salida + ' de ' + data.data[i].year_salida + ' al ' + data.data[i].dia_regreso + ' de ' + data.data[i].mes_regreso + ' de ' + data.data[i].year_regreso + ' para ' + data.data[i].motivo + ' de conformidad con el horario siguiente: ';
            var r_lineas1 = doc.splitTextToSize(r_d1, 170);
            doc.text(25, punto + 28, r_lineas1);
            /*}else{
              //doc.text(25, 63, 'Señores: ');
              doc.setFontType("normal");
              doc.text(25, punto+20, 'Estimado Señor: ');
              var r_d1 = 'Nos dirijimos a usted para informarle que  de conformidad con el Nombramiento número '+data.data[i].nombramiento+' de fecha '+data.data[i].dia_nombramiento+ ' de '+data.data[i].mes_nombramiento+' de '+data.data[i].year_nombramiento+'. Nos constituimos en '+data.data[i].lugar+ ' del '+data.data[i].dia_salida +' de '+data.data[i].mes_salida +' de '+data.data[i].year_salida +' al '+data.data[i].dia_regreso +' de '+data.data[i].mes_regreso +' de '+data.data[i].year_regreso +' para '+data.data[i].motivo +' de conformidad con el horario siguiente: ' ;
              var r_lineas1 = doc.splitTextToSize(r_d1, 170);
              doc.text(25, punto+28, r_lineas1);
            }*/
            if (data.data[i].id_pais == 'GT') {
              if (data.data[i].descripcion_lugar == 0 || data.data[i].descripcion_lugar == 1) {
                doc.text(35, punto + 45, 'Hora de Salida de : ');
                doc.setFontType("bold");
                doc.text(65, punto + 45, 'SAAS ');

                doc.setFontType("normal");
                doc.text(140, punto + 45, data.data[i].hora_salida_saas);
                doc.text(160, punto + 45, data.data[i].fecha_salida);

                doc.text(35, punto + 50, 'Hora de llegada a : ');
                doc.text(65, punto + 50, data.data[i].lugar);
                doc.text(140, punto + 50, data.data[i].hora_llegada_lugar);
                doc.text(160, punto + 50, data.data[i].fecha_llegada_lugar);

                doc.text(35, punto + 55, 'Hora de Salida de : ');
                doc.text(65, punto + 55, data.data[0].lugar);
                doc.text(140, punto + 55, data.data[i].hora_salida_lugar);
                doc.text(160, punto + 55, data.data[i].fecha_salida_lugar);

                doc.text(35, punto + 60, 'Hora de llegada a : ');
                doc.setFontType("bold");
                doc.text(65, punto + 60, 'SAAS ');
                doc.setFontType("normal");
                doc.text(140, punto + 60, data.data[i].hora_regreso_saas);
                doc.text(160, punto + 60, data.data[i].fecha_regreso);
                punto += 45;
              } else if (data.data[i].descripcion_lugar == 2) {
                for (nombreIndice in data.data[i].destinos) {

                  doc.setFontType("bold");
                  doc.setFontType("normal");

                  if (nombreIndice == 0) {
                    doc.text(25, punto + 45, 'Hora de Salida de : ');
                    doc.text(55, punto + 45, 'SAAS ');

                    doc.setFontType("normal");
                    doc.text(150, punto + 45, data.data[i].hora_salida_saas);//data.data[i].destinos[nombreIndice].h_ini);
                    doc.text(170, punto + 45, data.data[i].fecha_salida);
                  }


                  doc.text(25, punto + 50, 'Hora de llegada a : ');
                  doc.text(55, punto + 50, data.data[i].destinos[nombreIndice].dep);
                  doc.text(150, punto + 50, data.data[i].destinos[nombreIndice].h_ini);
                  doc.text(170, punto + 50, data.data[i].destinos[nombreIndice].f_ini);

                  doc.text(25, punto + 55, 'Hora de Salida de : ');
                  doc.text(55, punto + 55, data.data[i].destinos[nombreIndice].dep);
                  doc.text(150, punto + 55, data.data[i].destinos[nombreIndice].h_fin);
                  doc.text(170, punto + 55, data.data[i].destinos[nombreIndice].f_fin);

                  if (nombreIndice == data.data[i].destinos.length - 1) {
                    doc.text(25, punto + 60, 'Hora de llegada a : ');
                    doc.setFontType("bold");
                    doc.text(55, punto + 60, 'SAAS ');
                    doc.setFontType("normal");
                    doc.text(150, punto + 60, data.data[i].hora_regreso_saas);
                    doc.text(170, punto + 60, data.data[i].fecha_regreso);
                    punto += 55;
                  } else {
                    punto += 15;
                  }


                }
              }
            }
            //doc.text(30, punto+100, 'Atentamente:    ' +data.data[i].empleado);

            //doc.text(25, punto+100, (i+1)+'.');
            if (data.data[i].id_pais != 'GT') {
              punto += 30;
            }
            punto += 35;
            doc.setTextColor(68, 68, 68);
            doc.text(30, punto, '(f)    ' + data.data[i].empleado);
            punto += 10;
            doc.text(30, punto, '** ULTIMA LINEA **');

            doc.setTextColor(0, 0, 0);
            doc.setFontType("bold");
            doc.setDrawColor(0, 0, 0);
            punto -= 5;
            doc.line(30, punto, 150, punto);
            doc.setFontType("normal");
            doc.setTextColor(0, 0, 0);


          }

          if (pages > 1) {
            punto_ = 65;
            punto = punto_ + 4
            doc.addPage();
          }
          incremental++;
          pages--;
        }
        //fin tipo 2
      }

      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function imprimir_exterior_vacia() {
  var documento;
  var doc = new jsPDF('p', 'mm');
  punto = 10;
  //doc.setTextColor(203, 50, 52);
  doc.addImage(escudo_viatico, 'png', 60, 3, 20, 20);
  doc.addImage(logo_cgc, 'png', 142, punto - 2, 17, 17);
  doc.setFontType("bold");
  doc.setFontSize(12);
  //doc.setTextColor(220, 48, 35);
  doc.writeText(5, punto + 2, 'FORMULARIO V-E', { align: 'right', width: 192.5 });
  doc.setFontSize(9);
  doc.setTextColor(33, 33, 33);
  //doc.writeText(5, punto+7 ,'CORRELATIVO CGC No.',{align:'right',width:198});
  doc.setFontSize(11);



  doc.setFontSize(10);
  doc.setFontType("normal");
  doc.setFontType("bold");
  doc.writeText(0, punto + 16, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 140 });
  doc.writeText(0, punto + 20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 140 });
  doc.setFontType("normal");
  doc.writeText(0, punto + 24, 'GUATEMALA, C.A.', { align: 'center', width: 140 });
  doc.setFontSize(12);

  doc.setFontType("bold");
  //doc.writeText(0, punto+30 ,'VIATICO CONSTANCIA',{align:'center',width:140});
  doc.setFontSize(5);
  doc.setFontType("normal");
  doc.writeText(7, punto + 30, 'EN CUMPLIMIENTO DEL ARTICULO 5 DEL REGLAMENTO DE GASTOS DE VIÁTICOS PARA EL ORGANISMO EJECUTIVO Y LAS ENTIDADES DESCENTRALIZADAS Y AUTÓNOMAS DEL ESTADO VIGENTE', { align: 'center', width: 205 });
  //doc.writeText(5, punto+35 ,'',{align:'center',width:130});
  doc.setFontSize(13);
  punto2 = 57;
  punto3 = 47;

  doc.setFontType("bold");

  doc.setFontType("normal");

  doc.setFontSize(10);

  doc.setLineWidth(0.3);
  doc.roundedRect(161.5, 14.2, 38, 9, 1.5, 1.5);
  //doc.roundedRect(izquierda, arriba, ancho, altura, borde, borde);
  //CUADRO 1
  p_cuadro = 43;
  titulo1 = ''; titulo2 = ''; titulo3 = ''; titulo4 = ''; titulo5 = ''; titulo6 = '';
  for (x = 0; x < 2; x++) {
    if (x == 1) {
      p_cuadro = 145
      punto3 = 149;
      titulo1 = 'ENTRADA';
      titulo2 = 'INGRESO A LAS:';
      titulo3 = 'PROCEDENTE DE:';
    } else {
      titulo1 = 'SALIDA';
      titulo2 = 'SALIÓ A LAS:';
      titulo3 = 'CON DESTINO A:';
    }
    doc.setFontType("bold");
    doc.setFontSize(13);
    doc.writeText(0, punto3 + 1.5, titulo1, { align: 'center', width: 210 });
    doc.line(7, punto3 + 4, 210, punto3 + 4);
    doc.roundedRect(7, p_cuadro, 203, 100, 1, 1);
    doc.setFontSize(8);
    doc.writeText(0, punto3 + 10, 'LA AUTORIDAD DE MIGRACION HACE CONSTAR QUE EL SEÑOR', { align: 'center', width: 210 });
    doc.setFontType("normal");
    doc.text(10, punto3 + 17, 'NOMBRE COMPLETO: ');
    doc.text(10, punto3 + 23, titulo2);
    doc.text(10, punto3 + 29, titulo3);
    doc.text(120, punto3 + 23, 'HORAS DEL DIA:');
    doc.text(10, punto3 + 35, 'VIA TERRESTRE:');
    doc.text(10, punto3 + 41, 'EMPRESA:');
    doc.text(10, punto3 + 47, 'VIA AÉREA:');
    doc.text(10, punto3 + 53, 'EMPRESA:');
    doc.text(122, punto3 + 53, 'No. DE VUELO:');
    //horizontal line data
    doc.line(42, punto3 + 19, 207, punto3 + 19);
    doc.line(37, punto3 + 30, 207, punto3 + 30);
    doc.line(35, punto3 + 24, 118, punto3 + 24);
    doc.line(145, punto3 + 24, 207, punto3 + 24);

    doc.line(28, punto3 + 42, 207, punto3 + 42);
    doc.line(28, punto3 + 54, 118, punto3 + 54);
    doc.line(145, punto3 + 54, 207, punto3 + 54);

    //horizontal line
    doc.line(7, punto3 + 60, 210, punto3 + 60);

    //vertical line
    doc.line(108, punto3 + 60, 108, punto3 + 96);
    doc.text(10, punto3 + 65, 'AUTORIDAD DE MIGRACION: ');
    doc.text(111, punto3 + 65, 'LUGAR Y FECHA: ');
    doc.text(60, punto3 + 90, 'Y SELLO: ');
  }

  var r_d1 = 'AUTORIZADO POR LA CONTRALORIA GENERAL DE CUENTAS SEGUN RESOLUCION No. Fb./2662 Clas.: 365-12-8-I-4-97 DEL 1-4-97 No. DE CUENTA S1-22  · '
    + '1000 '
    + 'Formulario de Viatico Exterior DEL No. 1001 AL 2000 SIN SERIE '
    + 'No. CORRELATIVO Y FECHA DE AUTORIZACION DE IMPRESION 575 - 2014'
    + ' DEL 17 - 10 - 2014 · '
    + 'ENVIO FISCAL 4-ASCC 11384 DEL '
    + '17 - 10 - 2014 LIBRO 4-ASCC FOLIO 68';
  var r_lineas1 = doc.splitTextToSize(r_d1, 200);
  doc.text(7, punto3 + 100, r_lineas1);


  doc.setFontSize(9);
  doc.setFontType("normal");
  var x = document.getElementById("pdf_preview_v");
  if (x.style.display === "none") {
    x.style.display = "none";
  } else {
    x.style.display = "none";
  }
  doc.autoPrint()
  $("#pdf_preview_v").attr("src", doc.output('datauristring'));
}

function imprimir_resumenbk(id_nombramiento) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/nombramiento.php",
    data: { id_nombramiento },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var documento;
      var registros = data.data.length;
      var t_hojas = Math.ceil(registros / 15);
      var pages = t_hojas;
      var doc = new jsPDF('p', 'mm');

      var punto_ = 65;
      var punto = punto_ + 4;
      var nombramiento = '', correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario;
      punto += 0;
      //console.log(data);
      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;

      incremental = 1;
      empleados = '';
      //for(var h = 0; h <= t_hojas; h ++ ){
      //doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");

      for (var x = 0; x < 15; x++) {
        if (i < registros) {

          empleados += data.data[i].empleado + ',';
          correlativo = data.data[i].correlativo;
          direccion = data.data[i].direccion;
          fecha = data.data[i].fecha;
          lugar = data.data[i].lugar;
          fecha_ini = data.data[i].fecha_ini;
          fecha_fin = data.data[i].fecha_fin;
          hora_ini = data.data[i].hora_ini;
          hora_fin = data.data[i].hora_fin;
          motivo = data.data[i].motivo;
          nombramiento += data.data[i].nombramiento + ',  ';
          duracion = data.data[i].duracion;
          beneficios = data.data[i].beneficios;
          funcionario = data.data[i].funcionario;

          i++;

          doc.setFontType("bold");
          //doc.setTextColor(68, 68, 68);
          doc.setFontSize(11);
          doc.writeText(5, punto + 12, direccion, { align: 'center', width: 205 });
          doc.setFontSize(9);
          doc.setFontType("normal");
          var r_d1 = `Correlativo: No. **${correlativo}**      Nombramiento: **${nombramiento}**.`;
          multilineaConNegrita(10, punto + 20, 190, r_d1, doc);

          var r_d1 = `Lugar de la Comisión: **${lugar}**`;
          multilineaConNegrita(10, punto + 30, 190, r_d1, doc);

          var r_d1 = `Fecha: **${data.data[0].fecha_i}** al **${data.data[0].fecha_f}**`;
          multilineaConNegrita(10, punto + 35, 195, r_d1, doc);
          //doc.writeText(10,20,'Correlativo: No. '+correlativo+'     Nombramiento: SAAS/'+nombramiento,{align:'left',width:200});
          punto += 35;
          t_lineas = 0;
          if (registros == 1) {
            //doc.text(60, punto+28, 'M ');
            var r_d1 = `Se informa que: **${empleados}** ha sido nombrado para asistir a la comisión Oficial.`;
            multilineaConNegrita(10, punto += 10, 195, r_d1, doc);
            punto += 10;
            //doc.setTextColor(68, 68, 68);
            /*var r_lineas1 = doc.splitTextToSize(r_d1, 185);
            doc.text(15, 45, r_lineas1);*/
            t_lineas = r_d1.length;
          } else {

            /*var r_lineas1 = doc.splitTextToSize(r_d1, 185);
            doc.text(15, 45, r_lineas1);*/
            var r_d1 = `Se informa que: **${empleados}** han sidos nombrado para asistir a la comisión Oficial.`;
            multilineaConNegrita(10, punto + 10, 190, r_d1, doc);
            //doc.setTextColor(68, 68, 68);
            t_lineas = r_d1.length;
          }

          punto = 55;

          if (t_lineas > 120) {
            punto += 20;
          } else if (t_lineas > 210) {
            punto += 30;
          } else if (t_lineas > 310) {
            punto += 40;
          } else if (t_lineas > 410) {
            punto += 50;
          } else if (t_lineas > 510) {
            punto += 60;
          }

          var r_d = 'Motivo de la Comisión: ' + motivo;
          var r_lineas = doc.splitTextToSize(r_d, 180);
          doc.text(10, punto, r_lineas);
          cant = (registros == 1) ? ' persona' : ' personas';
          doc.writeText(10, punto + 10, 'No. Personal nombrado: ' + registros + cant, { align: 'left', width: 200 });

          doc.setLineWidth(0.3);
          doc.roundedRect(5, 5, 205, punto + 15, 1, 1);
          doc.setFontType("bold");
          doc.writeText(10, punto + 15, 'Vo. Bo.', { align: 'right', width: 120 });

          punto += 40;

          doc.writeText(10, punto + 15, 'Punto: ' + punto, { align: 'right', width: 120 });
        }
      }


      //doc.writeText(15,150 ,t_lineas.toString(),{align:'left',width:200});
      //}
      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function imprimir_resumen1(id_nombramiento) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/nombramiento.php",
    data: { id_nombramiento },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var documento;
      var registros = data.data.length;
      var t_hojas = Math.ceil(registros / 15);
      var pages = t_hojas;
      var doc = new jsPDF('p', 'mm');

      var punto_ = 65;
      var punto = punto_ + 4;
      var nombramiento = '', correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario;
      punto += 0;
      //console.log(data);
      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;

      incremental = 1;
      empleados = '';
      //for(var h = 0; h <= t_hojas; h ++ ){
      //doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");



      for (var x = 0; x < 15; x++) {
        if (i < registros) {
          empleados += data.data[i].empleado + ',';
          correlativo = data.data[i].correlativo;
          direccion = data.data[i].direccion;
          fecha = data.data[i].fecha;
          lugar = data.data[i].lugar;
          fecha_ini = data.data[i].fecha_ini;
          fecha_fin = data.data[i].fecha_fin;
          hora_ini = data.data[i].hora_ini;
          hora_fin = data.data[i].hora_fin;
          motivo = data.data[i].motivo;
          nombramiento += data.data[i].nombramiento + ',  ';
          duracion = data.data[i].duracion;
          beneficios = data.data[i].beneficios;
          funcionario = data.data[i].funcionario;

          i++;
        }
      }
      doc.setFontType("bold");
      //doc.setTextColor(68, 68, 68);
      doc.setFontSize(11);
      doc.writeText(5, 12, direccion, { align: 'center', width: 205 });
      doc.setFontSize(9);
      doc.setFontType("normal");
      var r_d1 = `Correlativo: No. **${correlativo}**      Nombramiento: **${nombramiento}**.`;
      multilineaConNegrita(10, 20, 190, r_d1, doc);

      var r_d1 = `Lugar de la Comisión: **${lugar}**`;
      multilineaConNegrita(10, 30, 190, r_d1, doc);

      var r_d1 = `Fecha: **${data.data[0].fecha_i}** al **${data.data[0].fecha_f}**`;
      multilineaConNegrita(10, 35, 195, r_d1, doc);
      //doc.writeText(10,20,'Correlativo: No. '+correlativo+'     Nombramiento: SAAS/'+nombramiento,{align:'left',width:200});

      t_lineas = 0;
      if (registros == 1) {
        //doc.text(60, punto+28, 'M ');
        var r_d1 = `Se informa que: **${empleados}** ha sido nombrado para asistir a la comisión Oficial.`;
        multilineaConNegrita(10, 45, 195, r_d1, doc);
        //doc.setTextColor(68, 68, 68);
        /*var r_lineas1 = doc.splitTextToSize(r_d1, 185);
        doc.text(15, 45, r_lineas1);*/
        t_lineas = r_d1.length;
      } else {

        /*var r_lineas1 = doc.splitTextToSize(r_d1, 185);
        doc.text(15, 45, r_lineas1);*/
        var r_d1 = `Se informa que: **${empleados}** han sidos nombrado para asistir a la comisión Oficial.`;
        multilineaConNegrita(10, 45, 190, r_d1, doc);
        //doc.setTextColor(68, 68, 68);
        t_lineas = r_d1.length;
      }

      punto = 55;

      if (t_lineas > 120) {
        punto += 20;
      } else if (t_lineas > 210) {
        punto += 30;
      } else if (t_lineas > 310) {
        punto += 40;
      } else if (t_lineas > 410) {
        punto += 50;
      } else if (t_lineas > 510) {
        punto += 60;
      }

      var r_d = 'Motivo de la Comisión: ' + motivo;
      var r_lineas = doc.splitTextToSize(r_d, 180);
      doc.text(10, punto, r_lineas);
      cant = (registros == 1) ? ' persona' : ' personas';
      doc.writeText(10, punto + 10, 'No. Personal nombrado: ' + registros + cant, { align: 'left', width: 200 });

      doc.setLineWidth(0.3);
      doc.roundedRect(5, 5, 205, punto + 15, 1, 1);
      doc.setFontType("bold");
      doc.writeText(10, punto + 15, 'Vo. Bo.', { align: 'right', width: 120 });

      //doc.writeText(15,150 ,t_lineas.toString(),{align:'left',width:200});
      //}
      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function imprimir_resumen(id_nombramiento) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/nombramiento.php",
    data: { id_nombramiento },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var documento;
      var registros = data.data.length;
      var t_hojas = Math.ceil(registros / 15);
      var pages = t_hojas;
      var doc = new jsPDF('p', 'mm');

      var punto_ = 0;
      var punto = 10;//punto_+4;
      var nombramiento = '', correlativo, direccion, funcionario = '', motivo = '', fecha, fecha_ini, fecha_fin, duracion, funcionario;
      //punto +=0;
      //console.log(data);
      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;

      incremental = 1;
      empleados = '';
      //for(var h = 0; h <= t_hojas; h ++ ){
      //doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");



      for (var i = 0; i < registros; i++) {
        //if(i<registros){

        empleados = data.data[i].empleado + ',';
        correlativo = data.data[i].correlativo;
        direccion = data.data[i].direccion;
        fecha = data.data[i].fecha;
        lugar = data.data[i].lugar;
        fecha_ini = data.data[i].fecha_ini;
        fecha_fin = data.data[i].fecha_fin;
        hora_ini = data.data[i].hora_ini;
        hora_fin = data.data[i].hora_fin;
        motivo = data.data[i].motivo;
        nombramiento = data.data[i].nombramiento;
        duracion = data.data[i].duracion;
        beneficios = data.data[i].beneficios;
        funcionario = data.data[i].funcionario;



        doc.setFontType("bold");
        //doc.setTextColor(68, 68, 68);
        doc.setFontSize(11);
        punto += 10;
        doc.writeText(5, punto, direccion, { align: 'center', width: 205 });
        doc.setFontSize(9);
        doc.setFontType("normal");
        var r_d1 = `Comisión: No. **${correlativo}**      Nombramiento: **${nombramiento}**.`;
        punto += 8;
        multilineaConNegrita(10, punto, 190, r_d1, doc);

        var r_d1 = `Lugar de la Comisión: **${lugar}**`;
        punto += 10
        multilineaConNegrita(10, punto, 190, r_d1, doc);

        var r_d1 = `Fecha: **${data.data[0].fecha_i}** al **${data.data[0].fecha_f}**`;
        punto += 10;
        multilineaConNegrita(10, punto, 195, r_d1, doc);
        //doc.writeText(10,20,'Correlativo: No. '+correlativo+'     Nombramiento: SAAS/'+nombramiento,{align:'left',width:200});

        t_lineas = 0;
        if (registros == 1) {
          //doc.text(60, punto+28, 'M ');
          var r_d1 = `Se informa que: **${empleados}** ha sido nombrado para asistir a la comisión Oficial.`;
          punto += 5;
          multilineaConNegrita(10, punto, 195, r_d1, doc);
          //doc.setTextColor(68, 68, 68);
          /*var r_lineas1 = doc.splitTextToSize(r_d1, 185);
          doc.text(15, 45, r_lineas1);*/
          t_lineas = r_d1.length;
        } else {

          /*var r_lineas1 = doc.splitTextToSize(r_d1, 185);
          doc.text(15, 45, r_lineas1);*/
          var r_d1 = `Se informa que: **${empleados}** ha sido nombrado para asistir a la comisión Oficial.`;
          punto += 5;
          multilineaConNegrita(10, punto, 190, r_d1, doc);
          //doc.setTextColor(68, 68, 68);
          t_lineas = r_d1.length;
        }

        //punto=55;

        if (t_lineas > 120) {
          punto += 20;
        } else if (t_lineas > 210) {
          punto += 30;
        } else if (t_lineas > 310) {
          punto += 40;
        } else if (t_lineas > 410) {
          punto += 50;
        } else if (t_lineas > 510) {
          punto += 60;
        }

        var r_d = 'Motivo de la Comisión: ' + motivo;
        var r_lineas = doc.splitTextToSize(r_d, 180);
        punto += 5;
        doc.text(10, punto, r_lineas);
        cant = (registros == 1) ? ' persona' : ' personas';
        //doc.writeText(10,punto+10 ,'No. Personal nombrado: '+registros+ cant,{align:'left',width:200});
        punto += 10;
        doc.writeText(10, punto, 'No. Personal nombrado: ' + 1, { align: 'left', width: 200 });

        doc.setLineWidth(0.3);
        doc.roundedRect(5, punto - 55, 205, 80, 1, 1);
        doc.setFontType("bold");
        punto += 5;
        doc.writeText(10, punto, 'Vo. Bo.', { align: 'right', width: 120 });
        punto += 5;

        punto += 15;
        //doc.writeText(10,punto ,'Punto: '+punto,{align:'right',width:120});

        if (punto > 250) {
          punto = 10;
          doc.addPage();
        }
        //}
      }


      //doc.writeText(15,150 ,t_lineas.toString(),{align:'left',width:200});
      //}
      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}
function imprimir_complemento(nombramiento, dia, mes, year) {

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_complemento.php",
    data: {
      vt_nombramiento: nombramiento,
      dia: dia,
      mes: mes,
      year: year
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      var documento;
      var hojas = data.length;
      var doc = new jsPDF('p', 'mm');
      punto = 40;
      x = 0;

      for (var i = 0; i < data.length; i++) {
        doc.setTextColor(68, 68, 68);

        doc.addImage(baner, 'png', 40, 0, 135, 30);
        doc.setFontType("bold");
        doc.setFontSize(11);

        doc.writeText(5, 35, 'RESUMEN DE GASTOS DEL NOMBRAMIENTO SAAS/' + data[i].nombramiento, { align: 'center', width: 205 });
        doc.setFontSize(9);
        doc.setFontType('normal');
        doc.writeText(20, punto + 10, 'FUNCIONARIO:', { align: 'left', width: 65 });
        doc.writeText(20, punto + 15, 'LUGAR DE LA COMISIÓN:', { align: 'left', width: 65 });
        doc.writeText(20, punto + 20, 'FECHA:', { align: 'left', width: 65 });
        doc.setFontType('bold');
        doc.writeText(80, punto + 10, (data[0].funcionario == 'NINGUNO') ? 'COMISION OFICIAL' : data[0].funcionario, { align: 'left', width: 65 });
        doc.writeText(80, punto + 15, data[0].lugar, { align: 'left', width: 65 });
        doc.writeText(80, punto + 20, data[0].fecha, { align: 'left', width: 65 });
        punto += 45;
        doc.setFontSize(9);
        doc.setFontType('normal');
        var cheque = '';
        p = punto - 15;
        ultimo_registro = hojas - 1;
        doc.writeText(17, punto - 8, 'No.', { align: 'center', width: 5 });
        doc.writeText(28, punto - 8, 'V-L', { align: 'center', width: 7 });
        doc.writeText(43, punto - 8, 'EMPLEADO', { align: 'left', width: 65 });

        doc.setFontSize(6);
        doc.writeText(125, punto - 8, 'GASTADO', { align: 'center', width: 28 });
        doc.writeText(143, punto - 8, 'ANTICIPO', { align: 'center', width: 28 });
        doc.writeText(161, punto - 8, 'REINTEGRO', { align: 'center', width: 28 });
        doc.writeText(179, punto - 8, 'COMPLEMENTO', { align: 'center', width: 28 });
        doc.setFontSize(9);
        var suma_a = 0;

        //if (data[i].complemento!='0.00'){
        x += 1;
        doc.writeText(17, punto, '' + x + '', { align: 'center', width: 5 });
        doc.writeText(28, punto, data[i].vl, { align: 'left', width: 65 });
        doc.writeText(43, punto, data[i].empleado, { align: 'left', width: 65 });
        doc.writeText(118, punto, data[i].m_r, { align: 'right', width: 28 });
        doc.writeText(136, punto, (data[i].bln_anticipo == 1) ? data[i].m_p : '--', { align: 'right', width: 28 });
        doc.writeText(154, punto, data[i].reintegro, { align: 'right', width: 28 });
        doc.writeText(172, punto, data[i].complemento, { align: 'right', width: 28 });
        //if(data[i].bln_cheque==1){
        cheque = data[i].empleado;
        //}
        suma_a += (data[i].bln_anticipo == 1) ? parseFloat(data[i].m_p) : 0;
        //doc.writeText(10, punto ,'NOMBRE: ',{align:'left',width:65});
        //}
        punto += 5;

        doc.setLineWidth(0.1);
        //vertical lines
        doc.line(24, p, 24, punto - 2);
        //doc.line(35, p, 35, punto-2);
        doc.line(40, p, 40, punto - 2);
        doc.line(130, p, 130, punto - 2);
        doc.line(148, p, 148, punto - 2);
        doc.line(166, p, 166, punto - 2);
        doc.line(184, p, 184, punto - 2);
        //horizontal lines
        doc.line(15, p + 10, 202, p + 10);

        doc.roundedRect(15, p, 187, punto - 72, 1, 1);
        /*doc.writeText(118, punto+2 ,data[ultimo_registro].total_gastado,{align:'right',width:28});
        doc.writeText(136, punto+2 ,(suma_a>0)?data[ultimo_registro].total_proyectado:'--',{align:'right',width:28});
        doc.writeText(154, punto+2 ,data[ultimo_registro].total_reintegrado,{align:'right',width:28});
        doc.writeText(172, punto+2 ,data[ultimo_registro].total_complemento,{align:'right',width:28});*/


        doc.writeText(20, punto + 20, 'CHEQUE No.', { align: 'left', width: 65 });
        doc.writeText(90, punto + 20, 'A FAVOR DE:', { align: 'left', width: 65 });

        doc.writeText(130, punto + 40, 'TOTAL REINTEGRO:', { align: 'left', width: 65 });
        doc.writeText(130, punto + 45, 'TOTAL COMPLEMENTO:', { align: 'left', width: 65 });
        //doc.writeText(20, punto+50 ,'TOTAL GASTADO:',{align:'left',width:65});
        doc.setFontType('bold');
        doc.writeText(120, punto + 20, cheque, { align: 'left', width: 65 });


        //doc.writeText(0, punto+35 ,data[ultimo_registro].total_gastado,{align:'right',width:200});
        doc.writeText(0, punto + 40, data[ultimo_registro].total_reintegrado, { align: 'right', width: 200 });
        doc.writeText(0, punto + 45, data[ultimo_registro].total_complemento, { align: 'right', width: 200 });



        var r_d1 = `TOTAL GASTADO **${data[ultimo_registro].total_gastado_letras}** `;
        multilineaConNegrita(20, punto + 60, 190, r_d1, doc);

        punto = 200;
        doc.setTextColor(5, 83, 142);
        doc.setFontSize(8);
        doc.writeText(0, 258, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 215 });
        doc.setFontType('bold');
        doc.setFontSize(10);
        doc.writeText(5, 266, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
        doc.writeText(5, 269, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
        doc.writeText(5, 274, punto + 'https://www.saas.gob.gt', { align: 'center', width: 209 });

        doc.setDrawColor(14, 4, 4);
        doc.setLineWidth(0.1);
        doc.line(0, 260, 220, 260);

        punto += 5;
        if (punto > 200) {
          punto = 40;
          doc.addPage();
        }
      }

      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });





}


function imprimir_monto_por_nombramiento(nombramiento) {
  var dia = '', mes = '', year = '';

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_montos.php",
    data: {
      vt_nombramiento: nombramiento,
      dia: dia,
      mes: mes,
      year: year
    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      //console.log(data);
      pages = 1;
      t_hojas = 1;
      var documento;
      var registros = data.length;

      var doc = new jsPDF('p', 'mm');
      punto = 40;
      x = 0;

      if (registros > 12) {
        paginas = registros / 12;
        siguiente_pagina = (registros / 12);
        verificacion = siguiente_pagina - Math.floor(siguiente_pagina);
        // console.log(paginas);
        // console.log(verificacion);
        // console.log(siguiente_pagina);
        suma = 0;

        if (verificacion > 0.24 && verificacion < 0.3 || verificacion > 0.4 && verificacion < 0.5) {
          suma = 1;
        }
        t_hojas = Math.ceil(paginas) + suma;
        pages = t_hojas;
      }

      doc.setTextColor(68, 68, 68);



      doc.setFontType('bold');
      /*doc.writeText(80, punto+10 ,data[0].funcionario,{align:'left',width:65});
      doc.writeText(80, punto+15 ,data[0].lugar,{align:'left',width:65});
      doc.writeText(80, punto+20 ,data[0].fecha,{align:'left',width:65});*/
      punto += 10;
      doc.setFontSize(9);
      doc.setFontType('normal');
      var cheque = '';
      p = punto - 15;
      var incremental = 0;
      paginas = pages;
      // console.log(paginas)
      for (var p = 0; p <= paginas; p++) {

        if (p < paginas) {
          doc.addImage(baner, 'png', 40, 0, 135, 30);
          doc.setFontSize(10);
          doc.writeText(5, 8, 'Página: ' + (p + 1) + '/' + t_hojas, { align: 'right', width: 200 });

          doc.setFontType("bold");
          doc.setFontSize(11);

          doc.writeText(5, 35, 'NOMBRAMIENTO SAAS/' + data[0].nombramiento, { align: 'center', width: 205 });
          doc.setFontSize(9);
          doc.setFontType('normal');
          doc.writeText(5, punto - 5, 'MONTO POR NOMBRAMIENTO', { align: 'center', width: 205 });
          doc.setTextColor(5, 83, 142);
          doc.setFontSize(8);
          doc.writeText(0, 258, 'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos', { align: 'center', width: 215 });
          doc.setFontType('bold');
          doc.setFontSize(10);
          doc.writeText(5, 266, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
          doc.writeText(5, 269, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
          doc.writeText(5, 274, 'https://www.saas.gob.gt', { align: 'center', width: 209 });
          doc.setDrawColor(14, 4, 4);
          doc.setLineWidth(0.1);
          doc.line(0, 260, 220, 260);

          doc.setTextColor(68, 68, 68);
          doc.setFontType('normal');

          doc.writeText(16, punto + 5, 'No.', { align: 'center', width: 5 });

          doc.writeText(30, punto + 5, 'EMPLEADO', { align: 'center', width: 50 });
          doc.writeText(120, punto + 5, 'MONTO', { align: 'center', width: 28 });
          doc.writeText(160, punto + 5, 'FIRMA', { align: 'center', width: 28 });
          doc.line(15, punto + 7, 202, punto + 7);
        }

        doc.setTextColor(68, 68, 68);
        doc.setFontType('normal');



        for (var i = 0; i < 12; i++) {
          punto += 15;
          if (incremental < registros) {
            x += 1;
            doc.writeText(16, punto, '' + x + '', { align: 'center', width: 5 });

            doc.writeText(30, punto, data[incremental].empleado, { align: 'left', width: 65 });
            doc.writeText(110, punto, (data[incremental].bln_anticipo == 1) ? data[incremental].m_p : '--', { align: 'right', width: 28 });
            doc.line(150, punto + 2, 202, punto + 2);

            //doc.writeText(10, punto ,'NOMBRE: ',{align:'left',width:65});
            incremental++;
          }
        }



        if (pages > 1) {
          punto = 50;

          doc.addPage();

          /*var r_d1 = `TOTAL GASTADO **${data[ultimo_registro].total_gastado_letras}** `;
          multilineaConNegrita(20,punto+60,190,r_d1,doc);*/


          pages--;

        }
        if (pages == 1) {
          ultimo_registro = registros - 1;
          doc.writeText(0, 240, 'TOTAL ENTREGADO: ' + data[ultimo_registro].total_proyectado, { align: 'right', width: 200 });
        }

      }


      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });





}



function multilineaConNegrita(x, y, endx, text, doc) {
  const isBoldOpen = (arrayLength, valueBefore = false) => {
    const isEven = arrayLength % 2 === 0;
    const result = valueBefore !== isEven;
    return result;
  };
  const lineSpacing = 5;
  const fontSize = 9;
  //mezcla de normal y negrita multiples lineas
  let startX = x;
  let startY = y;
  const endX = endx;

  doc.setDrawColor();
  doc.setTextColor();
  doc.setFontType("normal");
  doc.setFontSize(fontSize);
  doc.setLineWidth(1);
  let textMap = doc.splitTextToSize(text, endX);
  const startXCached = startX;
  let boldOpen = false;
  textMap.map((text, i) => {
    if (text) {
      const arrayOfNormalAndBoldText = text.split('**');
      const boldStr = 'bold';
      const normalOr = 'normal';
      arrayOfNormalAndBoldText.map((textItems, j) => {
        doc.setFontType(boldOpen ? normalOr : boldStr);
        if (j % 2 === 0) {
          doc.setFontType(boldOpen ? boldStr : normalOr);
        }
        doc.text(textItems, startX, startY);
        startX = startX + doc.getStringUnitWidth(textItems) * 3.6;
      });
      boldOpen = isBoldOpen(arrayOfNormalAndBoldText.length, boldOpen);
      startX = startXCached;
      startY += lineSpacing;
    }
  });
}

function titleCase(str) {
  // console.log(str);
  str = str.toLowerCase().split(' ');
  for (var i = 0; i < str.length; i++) {
    str[i] = str[i].charAt(0).toUpperCase() + str[i].slice(1);
  }
  return str.join(' ');
}

function impresion_formato_informe() {
  var dia = '', mes = '', year = '';

  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/hoja_formato_informe.php",
    data: {

    },
    dataType: 'json', //f de fecha y u de estado.
    beforeSend: function () {

    },
    success: function (data) {
      //alert(data);
      // console.log(data);
      punto = 40;
      pages = 1;
      t_hojas = 1;
      var documento;
      var registros = data.length;

      var doc = new jsPDF('p', 'mm');


      //doc.addImage(baner, 'png', 40, 0, 135, 30);
      doc.setFontSize(10);


      doc.setFontType("bold");
      doc.setFontSize(11);

      doc.writeText(5, 25, 'INFORME DE COMISIÓN', { align: 'center', width: 205 });
      doc.setFontSize(9);
      doc.setFontType('normal');
      doc.writeText(5, punto, 'Lugar y fecha', { align: 'right', width: 155 });
      doc.line(160, punto + 1, 200, punto + 1);

      punto += 10;
      var sr = (data.sss != '') ? 'Licenciado' : 'Sr. (a)';

      var sss = titleCase(data.direccion);
      var ssa = (data.sss != '') ? 'Estimado Señor Subsecretario:' : 'Estimado Señor Director: ';
      if (data.sss == 'SSS') {
        sss = 'Subsecretario de Seguridad';
      } else if (data.sss == 'SSA') {
        sss = 'Subsecretario Administrativo';
      }
      doc.writeText(25, punto + 5, sr, { align: 'left', width: 100 });
      doc.writeText(25, punto + 10, titleCase(data.director), { align: 'left', width: 100 });
      doc.writeText(25, punto + 15, sss, { align: 'left', width: 100 });
      doc.writeText(25, punto + 20, 'Su Despacho', { align: 'left', width: 100 });

      doc.writeText(25, punto + 30, ssa, { align: 'left', width: 100 });
      punto += 35
      for (var i = 0; i < 8; i++) {
        punto += 10;
        doc.line(15, punto, 202, punto);
      }

      doc.setFontSize(10);
      punto += 20;
      doc.writeText(25, punto, 'Atentamente:', { align: 'left', width: 155 });

      punto += 10;
      doc.writeText(25, punto, 'Firma:', { align: 'left', width: 155 });
      doc.line(40, punto + 1, 120, punto + 1);
      punto += 10;
      doc.writeText(25, punto, 'Nombre:', { align: 'left', width: 155 });
      doc.line(40, punto + 1, 120, punto + 1);
      punto += 10;
      doc.writeText(25, punto, 'Cargo:', { align: 'left', width: 155 });
      doc.line(40, punto + 1, 120, punto + 1);

      punto += 20;
      doc.writeText(5, punto, 'Vo. Bo.', { align: 'right', width: 147 });
      doc.line(155, punto + 1, 200, punto + 1);
      doc.writeText(5, punto + 6, 'Autoridad que nombra.', { align: 'right', width: 190 });

      /*doc.setTextColor(5, 83, 142);
      doc.setFontSize(8);
      doc.writeText(0, 258 ,'Reporte Generado Herramientas Administrativas - Módulo control de Viáticos',{align:'center',width:215});
      doc.setFontType('bold');
      doc.setFontSize(10);
      doc.writeText(5,266,'6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA',{align:'center',width:209});
      doc.writeText(5,269,'PBX: 2327 - 6000 FAX: 2327 - 6090',{align:'center',width:209});
      doc.writeText(5,274,'https://www.saas.gob.gt',{align:'center',width:209});
      doc.setDrawColor(14,4,4);
      doc.setLineWidth(0.1);
      doc.line(0, 260, 220, 260);*/

      doc.setTextColor(68, 68, 68);
      doc.setFontType('normal');

      /*  doc.writeText(16, punto+5 ,'No.',{align:'center',width:5});

        doc.writeText(30, punto+5 ,'EMPLEADO',{align:'center',width:50});
        doc.writeText(120, punto+5 ,'MONTO',{align:'center',width:28});
        doc.writeText(160, punto+5 ,'FIRMA',{align:'center',width:28});*/





      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      doc.autoPrint()
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });
}
