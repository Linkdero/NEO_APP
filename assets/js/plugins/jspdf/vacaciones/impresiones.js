
var months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

function bloque_certificacion(doc, punto, dia_asi, dia_goz, dhasi, dhgoz, dhdiff, anio_des, dtoday, hoy, fecha_ingreso, fing, per_ini, per_fin) {
  doc.setFontSize(10);
  doc.setTextColor(255, 255, 255);
  doc.setDrawColor(0, 136, 176);
  doc.setFillColor(0, 136, 176);
  doc.roundedRect(24, 90, 166, 2, 0, 0, 'FD');
  doc.roundedRect(24, 80, 166, 12, 1, 1, 'FD');
  doc.writeText(20, 87 ,'Inicio',{align:'center',width:34});
  doc.writeText(55, 87 ,'Fin',{align:'center',width:33});
  //doc.writeText(60, 87 ,'Total',{align:'center',width:32});
  doc.writeText(83, 87 ,'Total',{align:'center',width:32});
  doc.writeText(112, 87 ,'Gozados',{align:'center',width:32});
  doc.writeText(134, 87 ,'Subtotal',{align:'center',width:35});
  doc.writeText(160, 87 ,'Pendientes',{align:'center',width:32});

  doc.roundedRect(24, 80, 166, 115, 1, 1);

  doc.line(90, punto + punto + 15, 90, 186);
  doc.line(115, punto + punto + 15, 115, 186);
  doc.line(140, punto + punto + 15, 140, 186);
  doc.line(164, punto + punto + 15, 164, 186);

  doc.setTextColor(68, 68, 68);

  /*if (fing.getFullYear() == anio_des && dtoday.getFullYear() == anio_des) {
    dperiodo = "      " + fecha_ingreso + "       all " + hoy;
  }
  else if (dtoday.getFullYear() == anio_des) {
    dperiodo = "      01 de enero de " + anio_des + "       al-- " + hoy;
  }
  else if (fing.getFullYear() == anio_des) {
    dperiodo = "      " + fecha_ingreso + "       al111 31 de diciembre de " + anio_des;
  }
  else {
    dperiodo = "      01 de enero de " + anio_des + "       albbb 31 de diciembre de " + anio_des;
  }*/
  doc.setDrawColor(68, 68, 68);
  //doc.rect(25, punto + 58, 165, 30, 1, 1);

  res = String(dia_asi - dia_goz);
  doc.setFontType("bold");
  //doc.text(10, punto + punto_mas + 14, dperiodo);
  doc.line(25, punto + punto_mas + 18, 190, punto + punto_mas + 18);

  doc.setFontType("normal");
  /*doc.text(34, punto + punto_mas + 23, 'No. de días que le');
  doc.text(37, punto + punto_mas + 27, 'corresponden');
  doc.line(67, punto + punto_mas + 18, 67, punto + punto_mas + 38);

  doc.text(74, punto + punto_mas + 23, 'No. de días gozados');
  doc.text(77, punto + punto_mas + 27, 'con anterioridad');
  doc.line(109, punto + punto_mas + 18, 109, punto + punto_mas + 38);

  doc.text(117, punto + punto_mas + 25, 'Subtotal de días');
  doc.line(149, punto + punto_mas + 18, 149, punto + punto_mas + 38);

  doc.text(162, punto + punto_mas + 23, 'No. de días');
  doc.text(162, punto + punto_mas + 27, 'pendientes');
  doc.line(25, punto + punto_mas + 30, 190, punto + punto_mas + 30);*/


  // doc.text(37, punto + punto_mas + 35, String(dia_asi[0]));
  // doc.text(80, punto + punto_mas + 35, String(dia_goz[0]));
  // doc.text(121, punto + punto_mas + 35, res);
  // doc.text(163, punto + punto_mas + 35, res);

  //doc.line(87, punto + punto + 15, 87, 190);
  doc.writeText(25, punto + punto_mas + 15, per_ini ,{align:'center',width:25});
  doc.writeText(60, punto + punto_mas + 15,  per_fin,{align:'center',width:25});
  doc.writeText(86, punto + punto_mas + 15, (parseFloat(dia_asi).toFixed(6)),{align:'right',width:25});
  doc.writeText(111, punto + punto_mas + 15, (parseFloat(dia_goz).toFixed(6)),{align:'right',width:25});
  doc.writeText(135, punto + punto_mas + 15, (parseFloat(dhdiff).toFixed(6)),{align:'right',width:25});
  doc.writeText(160, punto + punto_mas + 15, (parseFloat(dhdiff).toFixed(6)),{align:'right',width:25});

  punto = punto + 8;

  return punto;
}
function periodos_vacaciones(id_persona) {

  $.ajax({
    type: "POST",
    url: "horarios/php/back/boletas/get_periodos.php",
    data: { id_persona: id_persona, id: 1 },
    dataType: 'json',
    beforeSend: function () {

    },
    success: function (data) {
      var x = data.aaData;
      var doc = new jsPDF('p', 'mm');
      var punto_ = 50;
      var punto = punto_ + 4;
      var dtoday = new Date();
      var dd = String(dtoday.getDate()).padStart(2, '0');
      var mm = String(dtoday.getMonth() + 1).padStart(2, '0');
      var yyyy = dtoday.getFullYear();

      today = dd + '/' + mm + '/' + yyyy;
      hoy = dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy;
      punto += 0;
      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;
      incremental = 1;
      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");

      nombre = x.map((item) => item.nombre);
      p_nominal = x.map((item) => item.p_nominal);
      dir_general = x.map((item) => item.dir_general);
      fecha_ingreso = x.map((item) => item.fecha_ingreso);
      dia_id = x.map((item) => item.dia_id);
      dia_asi = x.map((item) => item.dia_asi);
      dia_goz = x.map((item) => item.dia_goz);
      anio_des = x.map((item) => item.year);


      fing = new Date(fecha_ingreso[0]);
      fecha_ingreso = fing.getDate() + " de " + months[fing.getMonth()] + " de " + fing.getFullYear();

      punto += 4;
      let pg = 1;
      let pgt = Math.ceil(x.length / 4);

      doc.setFontType("normal");
      doc.writeText(5, 8, 'Página ' + pg + " de " + pgt, { align: 'right', width: 200 });
      cabeceras_periodo(doc, punto, nombre[0], p_nominal[0], dir_general[0], fecha_ingreso, hoy);

      doc.autoPrint();
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}
function cabeceras_periodo(doc, punto, nombre, p_nominal, dir_general, fecha_ingreso, hoy) {
  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(25, punto + 12, 'DIRECCIÓN DE RECURSOS HUMANOS', { align: 'left', width: 185 });
  doc.setFontType("normal");
  doc.setTextColor(68, 68, 68);
  doc.setFontSize(9);
  doc.setFontType("bold");
  doc.addImage(baner, 'png', 40, 5, 135, 30);
  doc.setDrawColor(215, 215, 215);
  doc.setFontSize(8);
  doc.setFontType("bold");
  doc.setFontSize(11);
  doc.writeText(5, 45, 'REPORTE DE PERÍODOS DE VACACIONES', { align: 'center', width: 205 });
  doc.setFontSize(9);
  doc.setFontType('normal');
  doc.writeText(5, 55, "Guatemala " + hoy, { align: 'right', width: 185 });
  doc.setFontSize(10);
  doc.setFontType("bold");
  doc.setFontType("normal");
  doc.text(25, punto + 20, 'NOMBRE DEL TRABAJADOR : ');
  doc.text(80, punto + 20, nombre);
  doc.text(25, punto + 26, 'PUESTO NOMINAL: ');
  doc.text(80, punto + 26, p_nominal);

  doc.text(25, punto + 32, 'DIRECCION FUNCIONAL: ');
  doc.text(80, punto + 32, dir_general);
  doc.text(25, punto + 38, 'FECHA DE INGRESO : ');
  doc.text(80, punto + 38, fecha_ingreso);

  doc.setFontSize(9);

  doc.setDrawColor(68, 68, 68);
  doc.setFillColor(255, 255, 255);
  punto_mas = 50;
  punto_mas_ = 50;

  var punto_siguiente = punto + punto_mas_ + 20;
}
function certificacion_vacaciones(id_persona) {
  $.ajax({
    type: "POST",
    url: "horarios/php/back/hojas/certificacion_vacaciones.php",
    data: { id_persona: id_persona },
    dataType: 'json',
    beforeSend: function () {

    },
    success: function (data) {
      var doc = new jsPDF('p', 'mm');
      var punto_ = 25;
      var punto = punto_ + 4;
      var id_persona, nombre, p_nominal, dir_general, fecha_ingreso, dia_id, dia_asi, dia_goz, anio_des, fing, dperiodo, per_ini, per_fin;
      var dtoday = new Date();
      var dd = String(dtoday.getDate()).padStart(2, '0');
      var mm = String(dtoday.getMonth() + 1).padStart(2, '0');
      var yyyy = dtoday.getFullYear();

      today = dd + '/' + mm + '/' + yyyy;
      hoy = dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy;
      punto += 0;
      doc.setFontType("normal");
      doc.setFontSize(9);


      i = 0;
      incremental = 1;
      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");
      nombre = data.map((item) => item.nombre);
      p_nominal = data.map((item) => item.p_nominal);
      dir_general = data.map((item) => item.dir_general);
      fecha_ingreso = data.map((item) => item.fecha_ingreso);
      dia_id = data.map((item) => item.dia_id);
      dia_asi = data.map((item) => item.dia_asi);
      dia_goz = data.map((item) => item.dia_goz);
      anio_des = data.map((item) => item.anio_des);
      dhasi = data.map((item) => item.dhasi);
      dhgoz = data.map((item) => item.dhgoz);
      dhdiff = data.map((item) => item.dhdiff);
      diapen = data.map((item) => item.diapen);

      per_ini = data.map((item) => item.per_ini);
      per_fin = data.map((item) => item.per_fin);

      fing = new Date(fecha_ingreso[0]);

      fecha_ingreso = fing.getDate() + " de " + months[fing.getMonth()] + " de " + fing.getFullYear();

      punto += 4;


      let pg = 1;
      let pgt = Math.ceil(data.length / 4);

      doc.setFontType("normal");
      doc.writeText(5, 8, 'Página ' + pg + " de " + pgt, { align: 'right', width: 200 });


      doc.text(30, 210 + 50, '(f)____________________________');
      doc.text(50, 210 + 55, 'Analista');
      doc.text(42, 210 + 59, 'Recursos Humanos');
      doc.text(51, 210 + 63, 'SAAS');

      doc.text(125, 210 + 50, 'Vo. Bo._____________________________ ');
      doc.text(144, 210 + 55, 'Director o Subdirector');
      doc.text(146, 210 + 59, 'Recursos Humanos');
      doc.text(155, 210 + 63, 'SAAS');


      cabeceras_certificacion(doc, punto, nombre[0], p_nominal[0], dir_general[0], fecha_ingreso, hoy);
      punto++;
      data.forEach(function (item, index) {

        punto = bloque_certificacion(doc, punto, dia_asi[index], dia_goz[index], dhasi[index], dhgoz[index], dhdiff[index], anio_des[index], dtoday, hoy, fecha_ingreso, fing, per_ini[index], per_fin[index]);
        if (punto > 160 && index != data.length - 1) {

          // doc.setFontType("normal");
          // doc.writeText(5, 8, 'Página ' + pg + " de " + pgt, { align: 'right', width: 200 });
          doc.addPage();
          pg++;
          doc.setFontType("normal");
          doc.text(30, 210 + 50, '(f)____________________________');
          doc.text(50, 210 + 55, 'Analista');
          doc.text(42, 210 + 59, 'Recursos Humanos');
          doc.text(51, 210 + 63, 'SAAS');

          doc.text(125, 210 + 50, 'Vo. Bo._____________________________ ');
          doc.text(144, 210 + 55, 'Director o Subdirector');
          doc.text(146, 210 + 59, 'Recursos Humanos');
          doc.text(155, 210 + 63, 'SAAS');
          punto = 54;
          cabeceras_certificacion(doc, punto, nombre[0], p_nominal[0], dir_general[0], fecha_ingreso, hoy);
          doc.writeText(5, 8, 'Página ' + pg + " de " + pgt, { align: 'right', width: 200 });
          punto++;
        }
        if (index == data.length - 1) {
          doc.setFontSize(10);
          doc.line(25, 186, 190, 186);
          doc.writeText(0, 192, 'TOTAL DE DÍAS PENDIENTES: ' + (parseFloat(diapen[data.length - 1]).toFixed(6)), { align: 'right', width: 185 });

        }
      });



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


function cabeceras_certificacion(doc, punto, nombre, p_nominal, dir_general, fecha_ingreso, hoy) {
  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  // doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
  doc.writeText(80, 30, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(80, 34, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(80, 38, 'DIRECCIÓN DE RECURSOS HUMANOS', { align: 'left', width: 185 });
  //punto-=20;
  doc.setFontType("normal");
  doc.setTextColor(68, 68, 68);
  doc.setFontSize(9);
  //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
  //documento = data.data[i].solicitud;

  doc.setFontType("bold");
  doc.addImage(logo_saas, 'png', 23, 10, 50, 30);
  doc.setDrawColor(215, 215, 215);
  doc.setFontSize(8);
  doc.setFontType("bold");
  doc.setFontSize(11);
  doc.writeText(80, 20, 'CERTIFICACION DE VACACIONES GOZADAS \nY PENDIENTES DE GOZAR', { align: 'left', width: 160 });
  //doc.writeText(80, 20, 'BOLETA DE SOLICITUD DE VACACIONES', { align: 'left', width: 160 });
  doc.setFontSize(9);
  doc.setFontType('normal');
  //doc.writeText(5, 55, "Guatemala " + hoy, { align: 'right', width: 185 });
  doc.setFontSize(10);
  doc.setFontType("bold");
  doc.setFontType("normal");
  doc.text(25, punto + 20, 'NOMBRE DEL TRABAJADOR : ');
  doc.text(80, punto + 20, nombre);
  doc.text(25, punto + 26, 'PUESTO : ');
  doc.text(80, punto + 26, p_nominal);
  doc.text(25, punto + 32, 'DIRECCION : ');
  doc.text(80, punto + 32, dir_general);
  doc.text(25, punto + 38, 'FECHA EFECTIVA DE BAJA : ');
  doc.text(80, punto + 38, fecha_ingreso);

  doc.setFontSize(9);

  doc.setDrawColor(68, 68, 68);
  doc.setFillColor(255, 255, 255);
  punto_mas = 50;
  punto_mas_ = 50;

  var punto_siguiente = punto + punto_mas_ + 20;
  //doc.rect(25, punto + 45, 165, 13, 1, 1);
  doc.setFontSize(12);
  doc.text(52, punto + punto_mas + 3, 'REPORTE DE VACACIONES GOZADAS Y NO GOZADAS');
  doc.setFontSize(9);
  doc.setFontType("normal");
}
function cabeceras_boleta(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, hojas, fecha_ini, fecha_fin, fecha_sol, fecha_pre, tipo, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol, superior) {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0');
  var yyyy = today.getFullYear();

  today = dd + '/' + mm + '/' + yyyy;

  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  // doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
  doc.writeText(80, 30, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(80, 34, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(80, 38, 'DIRECCIÓN DE RECURSOS HUMANOS', { align: 'left', width: 185 });
  //punto-=20;
  doc.setFontType("normal");
  doc.setTextColor(68, 68, 68);
  doc.setFontSize(9);
  //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
  //documento = data.data[i].solicitud;

  doc.setFontType("bold");
  doc.addImage(logo_saas, 'png', 23, 10, 50, 30);
  doc.setDrawColor(215, 215, 215);
  doc.setFontSize(8);
  doc.writeText(5, 5, 'No. Boleta: ' + correlativo, { align: 'right', width: 200 });
  //doc.line(75, 10, 75,50);

  doc.setFontType("bold");
  doc.setFontSize(11);
  // definitivo = (tipo == 1) ? '' : 'DEFINITIVO ';
  doc.writeText(80, 20, 'BOLETA DE SOLICITUD DE VACACIONES', { align: 'left', width: 160 });
  doc.setFontSize(9);
  doc.setFontType('normal');

  //doc.writeText(5, 55, "Guatemala " + dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy, { align: 'right', width: 185 });

  //doc.line(120, 74, 120, 125);
  doc.setFontSize(10);
  doc.setFontType("bold");

  //doc.writeText(0, 65 ,data.data[i].fecha_solicitud,{align:'right',width:195});
  str = motivo.length;
  // console.log(str);
  // if (hojas == 1) {
  // doc.text(25, 63, 'NOMBRE DEL TRABAJADOR: ');
  doc.setFontType("normal");
  doc.text(25, punto + 20, 'NOMBRE DEL TRABAJADOR: ');
  doc.text(80, punto + 20, nombre);
  doc.text(25, punto + 26, 'PUESTO NOMINAL: ');
  doc.text(80, punto + 26, puesto);
  doc.text(25, punto + 32, 'DIRECCION FUNCIONAL: ');
  doc.text(80, punto + 32, dir_funcional);
  doc.text(25, punto + 38, 'FECHA DE SOLICITUD: ');
  doc.text(80, punto + 38, fecha);
  //doc.text(60, punto+28, 'M ');
  // var r_d1 = 'Me dirijo a usted para comunicarle que ha sido designado para realizar la comisión que se describe a continuación: ';
  // var r_lineas1 = doc.splitTextToSize(r_d1, 170);
  // doc.text(25, punto + 28, r_lineas1);
  // } else {
  //   // doc.text(25, 63, 'Señores: ');
  //   doc.setFontType("normal");
  //   doc.text(25, punto + 20, 'Estimados Señores: ');
  //   var r_d1 = 'Me dirijo a ustedes para comunicarles que han sido designados para realizar la comisión que se describe a continuación: ';
  //   var r_lineas1 = doc.splitTextToSize(r_d1, 170);
  //   doc.text(25, punto + 28, r_lineas1);
  // }
  doc.setFontSize(9);

  doc.setDrawColor(225, 225, 225);
  doc.setFillColor(255, 255, 255);
  punto_mas = 50;
  punto_mas_ = 50;
  // console.log(str);
  if (str > 70) {
    punto_mas_ = 53;
  }
  if (str > 170) {
    punto_mas_ = 56;
  }
  var punto_siguiente = punto + punto_mas_ + 20;
  doc.roundedRect(25, punto + 45, 165, punto_mas_, 1, 1);
  // doc.line(25, punto + punto_mas + 10, 190, punto + punto_mas + 10);
  // doc.setLineWidth(1.0);
  doc.line(25, punto + punto_mas + 13, 190, punto + punto_mas + 13);
  doc.line(25, punto + punto_mas + 25, 190, punto + punto_mas + 25);
  doc.line(25, punto + punto_mas + 35, 190, punto + punto_mas + 35);
  // doc.line(25, punto + punto_mas + 50, 190, punto + punto_mas + 50);
  doc.line(55, 92, 55, punto + punto_mas + 35);
  doc.line(88, 92, 88, punto + punto_mas + 35);
  doc.line(122, 92, 122, punto + punto_mas + 35);
  doc.line(155, 92, 155, punto + punto_mas + 35);

  doc.text(30, 104, '');
  doc.text(30, 109, '');
  doc.text(30, 114, '');
  doc.text(30, punto + punto_mas, 'Período de vacaciones solicitadas : ');
  doc.text(30, punto + punto_mas + 5, 'Fecha en que gozará vacaciones : ');
  doc.text(30, punto + punto_mas + 10, 'Fecha en que debe presentarse a sus labores : ');
  doc.text(32, punto + punto_mas + 18, 'Días que le');
  doc.text(30, punto + punto_mas + 22, 'corresponden');
  doc.text(62, punto + punto_mas + 18, 'Días gozados');
  doc.text(60, punto + punto_mas + 22, 'con anterioridad');
  doc.text(93, punto + punto_mas + 18, 'Subtotal de días');
  doc.text(97, punto + punto_mas + 22, 'pendientes');
  doc.text(136, punto + punto_mas + 18, 'Días');
  doc.text(132, punto + punto_mas + 22, 'solicitados');
  doc.text(163, punto + punto_mas + 18, 'Total de días');
  doc.text(165, punto + punto_mas + 22, 'pendientes');
  doc.text(30, punto + punto_mas + 41, 'Observaciones:');
  doc.text(30, 139, '');
  doc.text(30, 144, '');

  doc.setFontType("normal");
  doc.writeText(100, punto + punto_mas, lugar, { align: 'left', width: 215 });
  doc.text(100, punto + punto_mas + 5, 'del ' + fecha_ini + ' al ' + fecha_fin);
  doc.text(100, punto + punto_mas + 10, fecha_pre);

  doc.text(31, punto + punto_mas + 31, vdia);
  doc.text(62, punto + punto_mas + 31, vgoz);
  doc.text(95, punto + punto_mas + 31, vsub);
  doc.text(129, punto + punto_mas + 31, vsol);
  doc.text(163, punto + punto_mas + 31, vpen);
  var r_d = String(motivo);
  var r_lineas = doc.splitTextToSize(r_d, 120);
  doc.text(60, punto + punto_mas + 41, r_lineas);


  // doc.setDrawColor(215, 215, 215);
  //doc.line(80, 220, 140, 220);
  // doc.writeText(0, 248, 'Atentamente', { align: 'center', width: 215 });
  //doc.text(75, 220, '(f)');
  //doc.writeText(0, 224 ,'SECRETARIO GENERAL',{align:'center',width:215});
  // doc.setFontSize(5);
  doc.setFontType("bold");
  punto_siguiente = 115;
  doc.text(25, punto_siguiente + 43, '(f)_____________________________ ');
  doc.text(45, punto_siguiente + 48, 'Solicitante');
  doc.text(135, punto_siguiente + 43, '(f)_____________________________ ');
  doc.text(152, punto_siguiente + 48, 'Jefe Inmediato');
  doc.text(25, punto_siguiente + 70, '(f)_____________________________ ');
  doc.text(37, punto_siguiente + 75, 'Director o Subdirector \n Dirección solicitante');
  doc.text(117, punto_siguiente + 70, 'Revisado por : _____________________________ ');
  doc.text(147, punto_siguiente + 75, 'Director o Subdirector\nDirección de Recursos Humanos');
  doc.text(70, punto_siguiente + 100, 'Vo.Bo._____________________________ ');


  if (superior == 15) {
    doc.text(84, punto_siguiente + 105, 'Subsecretario de Seguridad');
  } else if (superior == 14) {
    doc.text(83, punto_siguiente + 105, 'Subsecretario Administrativo');
  } else {
    doc.text(96, punto_siguiente + 105, 'Secretario');
  }
  punto_siguiente += 20;
  doc.roundedRect(25, punto_siguiente + 93, 165, 20, 1, 1);
  doc.setFontType("normal");
  var r_d1 = 'NOTA:  Entregue su solicitud a la Dirección de Recursos Humanos para que esta obtenga la autorización del Director de Recursos Humanos. Si el interesado no goza los días solicitados de vacaciones, el jefe inmediato tiene la obligación de notificar a la Dirección de Recursos Humanos por escrito, caso contrario se contarán como gozados. Llenese únicamente en original.';
  var r_lineas1 = doc.splitTextToSize(r_d1, 160);
  doc.text(27.5, punto_siguiente + 98, r_lineas1);

  doc.setFontType('normal');
  doc.setTextColor(5, 83, 142);
  doc.setFontSize(8);
  doc.writeText(0, 253, 'Reporte Generado - Módulo Control de Horarios', { align: 'center', width: 215 });
  doc.setFontType('bold');
  doc.setFontSize(10);
  doc.writeText(5, 261, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
  doc.writeText(5, 265, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
  doc.writeText(5, 269, 'https://www.saas.gob.gt', { align: 'center', width: 209 });

  doc.setDrawColor(14, 4, 4);
  doc.line(0, 255, 220, 255);
  //doc.addImage(footer, 'png', 0, 260, 216, 15);
}
function cabeceras_constancia(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, hojas, fecha_ini, fecha_fin, fecha_sol, fecha_pre, tipo, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol) {
  var today = new Date();
  var dd = String(today.getDate()).padStart(2, '0');
  var mm = String(today.getMonth() + 1).padStart(2, '0');
  var yyyy = today.getFullYear();


  today = dd + '/' + mm + '/' + yyyy;

  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  // doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
  doc.writeText(80, 30, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(80, 34, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(80, 38, 'DIRECCIÓN DE RECURSOS HUMANOS', { align: 'left', width: 185 });
  //punto-=20;
  doc.setFontType("normal");
  doc.setTextColor(68, 68, 68);
  doc.setFontSize(9);
  //doc.writeText(0, 50 ,data.data[i].solicitud,{align:'right',width:190});
  //documento = data.data[i].solicitud;

  doc.setFontType("bold");
  doc.addImage(logo_saas, 'png', 23, 10, 50, 30);
  doc.setDrawColor(215, 215, 215);
  doc.setFontSize(8);
  doc.writeText(5, 5, 'No. Boleta de referencia: ' + correlativo, { align: 'right', width: 200 });
  var today = new Date();
  var moth = (today.getMonth()+1);
  if(moth < 10 ){
    moth = '0'+moth
  }
  var hours = (today.getHours() < 10) ? '0'+today.getHours():today.getHours();
  var minutes = (today.getMinutes() < 10) ? '0'+today.getMinutes():today.getMinutes();
  var seconds = (today.getSeconds() < 10) ? '0'+today.getSeconds():today.getSeconds();
  var date = today.getDate()+'-'+moth+'-'+today.getFullYear()+ '  '+ hours + ":" + minutes + ":" + seconds;

  doc.writeText(5, 8, 'Fecha de impresión: '+ date, { align: 'right', width: 200 });
  //doc.line(75, 10, 75,50);

  doc.setFontType("bold");
  doc.setFontSize(11);
  // definitivo = (tipo == 1) ? '' : 'DEFINITIVO ';
  doc.writeText(80, 20, 'CONSTANCIA DE VACACIONES GOZADAS', { align: 'left', width: 160 });
  doc.setFontSize(9);
  doc.setFontType('normal');

  // doc.writeText(5, 55, "Guatemala " + dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy, { align: 'right', width: 185 });

  //doc.line(120, 74, 120, 125);
  doc.setFontSize(10);
  doc.setFontType("bold");

  //doc.writeText(0, 65 ,data.data[i].fecha_solicitud,{align:'right',width:195});
  str = motivo.length;
  // console.log(str);
  // if (hojas == 1) {
  // doc.text(25, 63, 'NOMBRE DEL TRABAJADOR: ');
  doc.setFontType("normal");
  doc.text(25, punto + 20, 'NOMBRE DEL TRABAJADOR : ');
  doc.text(80, punto + 20, nombre);
  doc.text(25, punto + 26, 'PUESTO : ');
  doc.text(80, punto + 26, puesto);
  doc.text(25, punto + 32, 'DIRECCION : ');
  doc.text(80, punto + 32, dir_funcional);
  doc.text(25, punto + 38, 'FECHA DE CONSTANCIA : ');
  //doc.text(80, punto + 38, dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy);
  doc.text(80, punto + 38, fecha_pre);
  //doc.text(60, punto+28, 'M ');
  // var r_d1 = 'Me dirijo a usted para comunicarle que ha sido designado para realizar la comisión que se describe a continuación: ';
  // var r_lineas1 = doc.splitTextToSize(r_d1, 170);
  // doc.text(25, punto + 28, r_lineas1);
  // } else {
  //   // doc.text(25, 63, 'Señores: ');
  //   doc.setFontType("normal");
  //   doc.text(25, punto + 20, 'Estimados Señores: ');
  //   var r_d1 = 'Me dirijo a ustedes para comunicarles que han sido designados para realizar la comisión que se describe a continuación: ';
  //   var r_lineas1 = doc.splitTextToSize(r_d1, 170);
  //   doc.text(25, punto + 28, r_lineas1);
  // }
  doc.setFontSize(9);

  doc.setDrawColor(225, 225, 225);
  doc.setFillColor(255, 255, 255);
  punto_mas = 50;
  punto_mas_ = 50;
  // console.log(str);
  if (str > 70) {
    punto_mas_ = 53;
  }
  if (str > 170) {
    punto_mas_ = 56;
  }
  var punto_siguiente = punto + punto_mas_ + 20;
  doc.roundedRect(25, punto + 45, 165, punto_mas_, 1, 1);
  // doc.line(25, punto + punto_mas + 10, 190, punto + punto_mas + 10);
  // doc.setLineWidth(1.0);
  doc.line(25, punto + punto_mas + 13, 190, punto + punto_mas + 13);
  doc.line(25, punto + punto_mas + 25, 190, punto + punto_mas + 25);
  doc.line(25, punto + punto_mas + 35, 190, punto + punto_mas + 35);
  // doc.line(25, punto + punto_mas + 50, 190, punto + punto_mas + 50);
  doc.line(55, 92, 55, punto + punto_mas + 35);
  doc.line(88, 92, 88, punto + punto_mas + 35);
  doc.line(122, 92, 122, punto + punto_mas + 35);
  doc.line(155, 92, 155, punto + punto_mas + 35);

  doc.text(30, 104, '');
  doc.text(30, 109, '');
  doc.text(30, 114, '');
  doc.text(30, punto + punto_mas, 'Período de vacaciones gozadas : ');
  doc.text(30, punto + punto_mas + 5, 'Fecha en que gozó las vacaciones : ');
  doc.text(30, punto + punto_mas + 10, 'Fecha en que presentó a sus labores : ');
  doc.text(32, punto + punto_mas + 18, 'Días que le');
  doc.text(30, punto + punto_mas + 22, 'corresponden');
  doc.text(62, punto + punto_mas + 18, 'Días gozados');
  doc.text(60, punto + punto_mas + 22, 'con anterioridad');
  doc.text(93, punto + punto_mas + 18, 'Subtotal de días');
  doc.text(97, punto + punto_mas + 22, 'pendientes');
  doc.text(136, punto + punto_mas + 18, 'Días');
  doc.text(132, punto + punto_mas + 22, 'gozados');
  doc.text(163, punto + punto_mas + 18, 'Total de días');
  doc.text(165, punto + punto_mas + 22, 'pendientes');
  doc.text(30, punto + punto_mas + 41, 'Observaciones:');
  doc.text(30, 139, '');
  doc.text(30, 144, '');

  doc.setFontType("normal");
  doc.writeText(100, punto + punto_mas, lugar, { align: 'left', width: 215 });
  doc.text(100, punto + punto_mas + 5, 'del ' + fecha_ini + ' al ' + fecha_fin);
  doc.text(100, punto + punto_mas + 10, fecha_pre);

  doc.text(31, punto + punto_mas + 31, vdia);
  doc.text(62, punto + punto_mas + 31, vgoz);
  doc.text(95, punto + punto_mas + 31, vsub);
  doc.text(129, punto + punto_mas + 31, vsol);
  doc.text(163, punto + punto_mas + 31, vpen);
  var r_d = String(motivo);
  var r_lineas = doc.splitTextToSize(r_d, 120);
  doc.text(60, punto + punto_mas + 41, r_lineas);


  // doc.setDrawColor(215, 215, 215);
  //doc.line(80, 220, 140, 220);
  // doc.writeText(0, 248, 'Atentamente', { align: 'center', width: 215 });
  //doc.text(75, 220, '(f)');
  //doc.writeText(0, 224 ,'SECRETARIO GENERAL',{align:'center',width:215});
  // doc.setFontSize(5);
  doc.setFontType("bold");
  punto_siguiente = 135;
  doc.text(25, punto_siguiente + 43, '(f)_____________________________ ');
  doc.text(45, punto_siguiente + 48, 'Solicitante');
  doc.text(135, punto_siguiente + 43, '(f)_____________________________ ');
  doc.text(152, punto_siguiente + 48, 'Jefe Inmediato');
  // doc.text(25, punto_siguiente + 63, '(f)_____________________________ ');
  // doc.text(20, punto_siguiente + 68, 'Director o Subdirector de la Dirección solicitante');
  // doc.text(117, punto_siguiente + 63, 'Revisado por : _____________________________ ');
  // doc.text(122, punto_siguiente + 68, 'Director o Subdirector de Recursos Humanos');
  // doc.text(70, punto_siguiente + 83, 'Vo.Bo._____________________________ ');
  // doc.text(75, punto_siguiente + 90, 'Subsecretario de Seguridad');

  doc.roundedRect(25, punto_siguiente + 93, 165, 20, 1, 1);
  doc.setFontType("normal");
  var r_d1 = 'NOTA:  Entregue su constancia a la Dirección de Recursos Humanos para que ésta sea ingresada a su expediente personal, luego entregue la copia con sello de recibido de RRHH a su Dirección. Si el interesado no gozó los días de vacaciones solicitadas, el jefe inmediato tiene la obligación de notificar a la Dirección de Recursos Humanos por escrito, caso contrario se tomaran como gozados.';
  var r_lineas1 = doc.splitTextToSize(r_d1, 160);
  doc.text(27.5, punto_siguiente + 98, r_lineas1);

  doc.setFontType('normal');
  doc.setTextColor(5, 83, 142);
  doc.setFontSize(8);
  doc.writeText(0, 253, 'Reporte Generado - Módulo Control de Horarios', { align: 'center', width: 215 });
  doc.setFontType('bold');
  doc.setFontSize(10);
  doc.writeText(5, 261, '6A AV. "A" 4-18 ZONA 1 CALLEJÓN "DEL MANCHEN", GUATEMALA, GUATEMALA', { align: 'center', width: 209 });
  doc.writeText(5, 265, 'PBX: 2327 - 6000 FAX: 2327 - 6090', { align: 'center', width: 209 });
  doc.writeText(5, 269, 'https://www.saas.gob.gt', { align: 'center', width: 209 });

  doc.setDrawColor(14, 4, 4);
  doc.line(0, 255, 220, 255);
  //doc.addImage(footer, 'png', 0, 260, 216, 15);
}
function imprimir_boleta(vac_id) {
  $.ajax({
    type: "POST",
    url: "horarios/php/back/hojas/boleta.php",
    data: { vac_id },
    dataType: 'json',
    beforeSend: function () {

    },
    success: function (data) {

      var registros = data.length;
      var doc = new jsPDF('p', 'mm');

      var punto_ = 25;
      var punto = punto_ + 4;
      var correlativo, direccion, motivo = '', fecha, fecha_ini, fecha_fin, fecha_ini, fecha_fin, nombre, dir_funcional, puesto;
      punto += 0;
      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;
      incremental = 1;
      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");

      correlativo = data['boleta'];
      direccion = 'DIRECCION';
      fecha = data['fsol'];
      lugar = 'del '+data['per_ini'] +' al '+ data['per_fin'];//'del 01 de enero 2021 al 31 de diciembre 2021';
      fecha_ini = data['fini'];
      fecha_fin = data['ffin'];
      fecha_sol = data['fsol'];
      fecha_pre = data['fpre'];
      motivo = data['vobs'];
      nombramiento = data['vestado'];
      vdia = data['vdia'];
      vgoz = data['vgoz'];
      vpen = data['vpen'];
      vsol = data['vsol'];
      vsub = data['vsub'];
      nombre = data['nombre'];
      // dir_funcional = data['dir_funcional'];
      dir_funcional = $('#dirg').val();
      nombre = data['nombre'];
      dir_funcional = data['dir_funcional'];
      puesto = data['puesto'];
      secre = data['id_secre'];
      ssecre = data['id_subsecre'];
      superior = data['id_superior'];
      // console.log("secre " + secre);
      // console.log("ssecre" + ssecre);
      // console.log("superior" + superior);
      punto += 0;
      for(var x = 0; x < 2; x ++){

        cabeceras_boleta(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, fecha_sol, fecha_pre, 1, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol, superior);
        if(x == 0){
          doc.writeText(5, 8, 'Original', { align: 'right', width: 200 });
          doc.addPage();
        }else {
          doc.writeText(5, 8, 'Copia', { align: 'right', width: 200 });
        }

      }


      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }
      /*punto_ = 25;
      punto = punto_ + 0;
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(9);
      doc.setFontType("normal");
      doc.writeText(5, 8, 'Copia', { align: 'right', width: 200 });
      cabeceras_boleta(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, fecha_sol, fecha_pre, 1, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol, superior);*/

      doc.autoPrint();
      $("#pdf_preview_v").attr("src", doc.output('datauristring'));
    }
  }).done(function (data) {

  }).fail(function (jqXHR, textSttus, errorThrown) {
    alert(errorThrown);
  });
}

function imprimir_constancia(vac_id) {
  $.ajax({
    type: "POST",
    url: "horarios/php/back/hojas/boleta.php",
    data: { vac_id },
    dataType: 'json',
    beforeSend: function () {

    },
    success: function (data) {

      var registros = data.length;
      var doc = new jsPDF('p', 'mm');

      var punto_ = 25;
      var punto = punto_ + 4;
      var correlativo, direccion, motivo = '', fecha, fecha_ini, fecha_fin, fecha_ini, fecha_fin, nombre, dir_funcional, puesto;
      punto += 0;
      doc.setFontType("normal");
      doc.setFontSize(9);
      i = 0;
      incremental = 1;
      doc.setTextColor(68, 68, 68);
      doc.setFontType("normal");

      // doc.text(25, punto, (i + 1) + ' - ' + data.data[i].empleado);
      correlativo = data['boleta'];
      direccion = 'DIRECCION';
      fecha = data['fsol'];
      lugar = 'del '+data['per_ini'] +' al '+ data['per_fin'];//'del 01 de enero 2021 al 31 de diciembre 2021';
      fecha_ini = data['fini'];
      fecha_fin = data['ffin'];
      fecha_sol = data['fsol'];
      fecha_pre = data['fpre'];
      motivo = data['vobs'];
      nombramiento = data['vestado'];
      vdia = data['vdia'];
      vgoz = data['vgoz'];
      vpen = data['vpen'];
      vsol = data['vsol'];
      vsub = data['vsub'];
      nombre = data['nombre'];
      dir_funcional = data['dir_funcional'];
      puesto = data['puesto'];
      punto += 0;
      // doc.writeText(5, 8, 'Original', { align: 'right', width: 200 });
      cabeceras_constancia(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, fecha_sol, fecha_pre, 1, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol);

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

function imprimir_reporte_horario() {
  $.ajax({
    type: "POST",
    url: "horarios/php/back/hojas/reporte_persona.php",
    data: function (d) {
      d.id_persona = id_persona;
      d.month = $('select#month option:checked').val();
      d.year = $('select#year option:checked').val();
      d.month1 = $('select#month1 option:checked').val();
      d.year1 = $('select#year1 option:checked').val();
    },

    success: function (data) {
      console.log("test");
      //   linxPag = 44;
      // registros = data.length;
      //   paginas = Math.ceil(registros / linxPag);
      //   tpaginas = paginas;
      //   totDes = 0;
      //   totAlm = 0;
      //   totCen = 0;

      //   var doc = new jsPDF('p', 'mm');
      //   punto = linxPag;

      //   if ($('#direccion_rrhh').val() == 0) {
      //     des_Direccion = 'Todas las Direcciones';
      //   } else {
      //     des_Direccion = $('#direccion_rrhh option:selected').text();
      //   }

      //   var cursor = 0;
      //   for (var PagAct = 1; PagAct <= tpaginas; PagAct++) {
      //     doc.setFontSize(12);
      //     doc.setFontType("bold");
      //     doc.addImage(logo, 'png', 27, 5, 35, 25);
      //     doc.writeText(90, 12, "SISTEMA DE CONTROL DE ALIMENTOS", { align: 'center', width: 60 });
      //     doc.writeText(90, 18, "REPORTE DE ALIMENTOS SERVIDOS POR FECHA", { align: 'center', width: 60 });
      //     doc.writeText(90, 24, "PERIODO DEL: " + $('#ini').val() + " AL: " + $('#fin').val() + "", { align: 'center', width: 60 });
      //     doc.setFontSize(10);
      //     doc.writeText(90, 30, "" + des_Direccion + "", { align: 'center', width: 60 });
      //     doc.setFontSize(9);
      //     doc.writeText(29, punto - 8, "FECHA", { align: 'left', width: 17 });
      //     doc.writeText(92, punto - 8, "DESAYUNO", { align: 'right', width: 5 });
      //     doc.writeText(113, punto - 8, "ALMUERZO", { align: 'right', width: 5 });
      //     doc.writeText(132, punto - 8, "CENA", { align: 'right', width: 5 });
      //     doc.roundedRect(27, punto - 12, 165, punto - 37, 1, 1);
      //     doc.setFontSize(8);
      //     doc.setFontType("normal");
      //     for (var LinAct = 1; LinAct <= linxPag; LinAct++) {
      //       if (cursor < registros) {
      //         if (data[cursor].fecha == 'Total:') {
      //           doc.line(80, punto - 3, 137, punto - 3);
      //           punto += 1;
      //         }

      //         doc.writeText(29, punto, "" + data[cursor].fecha + "", { align: 'left', width: 17 });
      //         doc.writeText(90, punto, "" + formatter.format(data[cursor].desayuno) + "", { align: 'right', width: 5 });
      //         doc.writeText(110, punto, "" + formatter.format(data[cursor].almuerzo) + "", { align: 'right', width: 5 });
      //         doc.writeText(130, punto, "" + formatter.format(data[cursor].cena) + "", { align: 'right', width: 5 });
      //         punto += 5;
      //         cursor++;
      //       }
      //     }

      //     doc.line(27, 260, 190, 260);
      //     doc.writeText(80, 263, "Pagina " + PagAct + "/" + tpaginas, { align: 'center', width: 60 });

      //     paginas--;

      //     if (paginas > 0) {
      //       punto = linxPag;
      //       doc.addPage();
      //     } else {
      //       doc.line(80, punto - 3, 137, punto - 3);
      //       doc.line(80, punto - 2, 137, punto - 2);
      //     }
      //   }

      //   var x = document.getElementById("impresion");
      //   if (x.style.display === "none") {
      //     x.style.display = "none";
      //   } else {
      //     x.style.display = "none";
      //   }
      //   doc.autoPrint()

      //   $("#impresion").attr("src", doc.output('datauristring'));
      //   $('#re_load').hide();
    }

  }).done(function () {

  }).fail(function (jqXHR, textSttus, errorThrown) {
  });
}
