// function imprimir_boleta(vac_id) {

//   $.ajax({
//     type: "POST",
//     url: "horarios/php/back/empleados/get_horarios_empleado.php",
//     dataType: 'json',
//     success: function (data) {
//     }
//   }).done(function (data) {
//   });

//   var doc = new jsPDF('p', 'mm');
//   punto = 10;
//   //doc.setTextColor(203, 50, 52);
//   doc.addImage(escudo_viatico, 'png', 60, 3, 20, 20);
//   doc.addImage(logo_cgc, 'png', 142, punto - 2, 17, 17);
//   doc.setFontType("bold");
//   doc.setFontSize(12);
//   doc.setTextColor(220, 48, 35);
//   doc.writeText(5, punto, 'VL-SAAS-001-SCC', { align: 'right', width: 198 });
//   doc.setFontSize(9);
//   doc.setTextColor(33, 33, 33);
//   doc.writeText(5, punto + 7, 'CORRELATIVO CGC No.', { align: 'right', width: 198 });
//   doc.setFontSize(11);



//   doc.setFontSize(10);
//   doc.setFontType("normal");
//   doc.setFontType("bold");
//   doc.writeText(0, punto + 16, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 140 });
//   doc.writeText(0, punto + 20, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'center', width: 140 });
//   doc.setFontType("normal");
//   doc.writeText(0, punto + 24, 'GUATEMALA, C.A.', { align: 'center', width: 140 });
//   doc.setFontSize(12);

//   doc.setFontType("bold");
//   doc.writeText(0, punto + 30, 'VIATICO CONSTANCIA', { align: 'center', width: 140 });
//   doc.setFontSize(5);
//   doc.setFontType("normal");
//   doc.writeText(7, punto + 37, 'EN CUMPLIMIENTO DEL ARTICULO 5 DEL REGLAMENTO DE GASTOS DE VIÁTICOS PARA EL ORGANISMO EJECUTIVO Y LAS ENTIDADES DESCENTRALIZADAS Y AUTÓNOMAS DEL ESTADO VIGENTE', { align: 'center', width: 205 });
//   //doc.writeText(5, punto+35 ,'',{align:'center',width:130});
//   doc.setFontSize(13);
//   punto2 = 57;
//   punto3 = 47;

//   doc.setFontType("bold");
//   doc.writeText(0, punto3 + 7, 'SE HACE CONSTAR', { align: 'center', width: 210 });
//   doc.setFontType("normal");
//   doc.line(7, punto3 + 10, 210, punto3 + 10);
//   doc.setFontSize(10);

//   doc.setLineWidth(0.3);
//   doc.roundedRect(161.5, 11.2, 45, 17.6, 2.5, 2.5);
//   doc.roundedRect(137, 3, 73, 37, 2, 2);

//   doc.roundedRect(7, 43, 203, 184, 1, 1);
//   //doc.line(5, 72, 211, 72);
//   doc.setFontSize(8.5);
//   doc.text(12, punto2 + 8.5, 'QUE EL SEÑOR: ');
//   doc.text(12, punto2 + 15.5, 'NOMBRE: ');
//   doc.text(12, punto2 + 25.5, 'CARGO: ');
//   doc.text(12, punto2 + 35.5, 'DEPENDENCIA: ');
//   doc.setFontSize(8.5);
//   doc.writeText(45, punto2 + 35.5, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 190 });
//   doc.setFontSize(6.5);
//   //doc.writeText(10, punto2+13 ,'(NOMBRE DE LA DEPENDENCIA)',{align:'center',width:205});
//   //doc.writeText(10, punto2+23 ,'(EN LETRAS)',{align:'center',width:205});
//   doc.line(27, punto2 + 17, 203, punto2 + 17);
//   doc.line(27, punto2 + 27, 203, punto2 + 27);
//   doc.line(37, punto2 + 37, 203, punto2 + 37);

//   doc.setFillColor(0, 0, 0);
//   doc.setDrawColor(0, 0, 0);
//   //doc.setLineWidth(1);
//   doc.setFontSize(9);
//   doc.writeText(12, punto2 + 45.5, 'PERMANECIÓ EN COMISIÓN OFICIAL EN LOS LUGARES Y FECHAS QUE SE INDICAN:', { align: 'left', width: 215 });

//   //doc.line(5, punto2+35, 211, punto2+35);
//   doc.line(7, punto2 + 50, 210, punto2 + 50);

//   doc.setFontType('bold');
//   doc.writeText(7, punto2 + 55.5, 'No.', { align: 'center', width: 30 });
//   doc.writeText(21, punto2 + 55, 'LUGAR DE', { align: 'center', width: 75 });
//   doc.writeText(21, punto2 + 60, 'PERMANENCIA', { align: 'center', width: 75 });


//   doc.writeText(75, punto2 + 55, 'INGRESO', { align: 'center', width: 53 });
//   doc.writeText(65, punto2 + 60, 'HORA', { align: 'center', width: 53 });
//   doc.writeText(82, punto2 + 60, 'FECHA', { align: 'center', width: 53 });

//   doc.writeText(107.5, punto2 + 55, 'SALIDA', { align: 'center', width: 53 });
//   doc.writeText(99.5, punto2 + 60, 'HORA', { align: 'center', width: 53 });
//   doc.writeText(116.5, punto2 + 60, 'FECHA', { align: 'center', width: 53 });


//   doc.writeText(155, punto2 + 55, 'AUTORIDAD QUIEN CONSTA', { align: 'center', width: 53 });
//   doc.writeText(155, punto2 + 60, 'NOMBRE, FIRMA, SELLO', { align: 'center', width: 53 });

//   doc.line(82, punto2 + 56, 152.5, punto2 + 56);
//   doc.line(7, punto2 + 62, 210, punto2 + 62);

//   doc.line(35, punto2 + 50, 35, punto2 + 170);
//   doc.line(82, punto2 + 50, 82, punto2 + 170);
//   doc.line(100, punto2 + 56, 100, punto2 + 170);
//   doc.line(134, punto2 + 56, 134, punto2 + 170);
//   doc.line(117, punto2 + 50, 117, punto2 + 170);
//   doc.line(152.5, punto2 + 50, 152.5, punto2 + 170);
//   doc.text(7, punto2 + 175, 'OBSERVACIONES: ');
//   doc.line(40, punto2 + 177, 210, punto2 + 177);
//   doc.line(7, punto2 + 184, 210, punto2 + 184);


//   doc.setFontSize(9);
//   doc.setFontType("normal");
//   var x = document.getElementById("pdf_preview_v");
//   if (x.style.display === "none") {
//     x.style.display = "none";
//   } else {
//     x.style.display = "none";
//   }
//   doc.autoPrint()
//   $("#pdf_preview_v").attr("src", doc.output('datauristring'));
// }

function bloque_certificacion(doc, punto, dia_asi, dia_goz, dhasi, dhgoz, dhdiff, anio_des, dtoday, hoy, fecha_ingreso, fing) {


  if (fing.getFullYear() == anio_des && dtoday.getFullYear() == anio_des) {
    dperiodo = "      del " + fecha_ingreso + "       al " + hoy;
  }
  else if (dtoday.getFullYear() == anio_des) {
    dperiodo = "      del 01 de enero de " + anio_des + "       al " + hoy;
  }
  else if (fing.getFullYear() == anio_des) {
    dperiodo = "      del " + fecha_ingreso + "       al 31 de diciembre de " + anio_des;
  }
  else {
    dperiodo = "      del 01 de enero de " + anio_des + "       al 31 de diciembre de " + anio_des;
  }
  doc.setDrawColor(68, 68, 68);
  doc.rect(25, punto + 58, 165, 30, 1, 1);

  res = String(dia_asi - dia_goz);
  doc.setFontType("bold");
  doc.text(30, punto + punto_mas + 14, 'Periódo Vacacional: ' + dperiodo);
  doc.line(25, punto + punto_mas + 18, 190, punto + punto_mas + 18);

  doc.setFontType("normal");
  doc.text(34, punto + punto_mas + 23, 'No. de días que le');
  doc.text(37, punto + punto_mas + 27, 'corresponden');
  doc.line(67, punto + punto_mas + 18, 67, punto + punto_mas + 38);

  doc.text(74, punto + punto_mas + 23, 'No. de días gozados');
  doc.text(77, punto + punto_mas + 27, 'con anterioridad');
  doc.line(109, punto + punto_mas + 18, 109, punto + punto_mas + 38);

  doc.text(117, punto + punto_mas + 25, 'Subtotal de días');
  doc.line(149, punto + punto_mas + 18, 149, punto + punto_mas + 38);

  doc.text(162, punto + punto_mas + 23, 'No. de días');
  doc.text(162, punto + punto_mas + 27, 'pendientes');
  doc.line(25, punto + punto_mas + 30, 190, punto + punto_mas + 30);

  doc.setFontType("bold");
  // doc.text(37, punto + punto_mas + 35, String(dia_asi[0]));
  // doc.text(80, punto + punto_mas + 35, String(dia_goz[0]));
  // doc.text(121, punto + punto_mas + 35, res);
  // doc.text(163, punto + punto_mas + 35, res);

  doc.text(37, punto + punto_mas + 35, dhasi);
  doc.text(78, punto + punto_mas + 35, dhgoz);
  doc.text(119, punto + punto_mas + 35, dhdiff);
  doc.text(159, punto + punto_mas + 35, dhdiff);

  punto = punto + 31;

  return punto;
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
      var punto_ = 50;
      var punto = punto_ + 4;
      var id_persona, nombre, p_nominal, dir_general, fecha_ingreso, dia_id, dia_asi, dia_goz, anio_des;
      var fing, dperiodo;
      var dtoday = new Date();
      var dd = String(dtoday.getDate()).padStart(2, '0');
      var mm = String(dtoday.getMonth() + 1).padStart(2, '0');
      var yyyy = dtoday.getFullYear();

      today = dd + '/' + mm + '/' + yyyy;
      hoy = dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy;
      months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
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

        punto = bloque_certificacion(doc, punto, dia_asi[index], dia_goz[index], dhasi[index], dhgoz[index], dhdiff[index], anio_des[index], dtoday, hoy, fecha_ingreso, fing);
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
          doc.text(30, punto + 65, 'TOTAL DE DÍAS PENDIETES: ' + diapen[data.length - 1]);
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
  doc.writeText(5, 45, 'CERTIFICACION DE VACACIONES GOZADAS Y PENDIENTES DE GOZAR', { align: 'center', width: 205 });
  doc.setFontSize(9);
  doc.setFontType('normal');
  doc.writeText(5, 55, "Guatemala " + hoy, { align: 'right', width: 185 });
  doc.setFontSize(10);
  doc.setFontType("bold");
  doc.setFontType("normal");
  doc.text(25, punto + 20, 'NOMBRE DEL TRABAJADOR : ');
  doc.text(80, punto + 20, nombre);
  doc.text(25, punto + 26, 'PUESTO : ');
  doc.text(80, punto + 26, p_nominal);
  doc.text(25, punto + 32, 'DIRECCION : ');
  doc.text(80, punto + 32, dir_general);
  doc.text(25, punto + 38, 'FECHA DE INGRESO : ');
  doc.text(80, punto + 38, fecha_ingreso);

  doc.setFontSize(9);

  doc.setDrawColor(68, 68, 68);
  doc.setFillColor(255, 255, 255);
  punto_mas = 50;
  punto_mas_ = 50;

  var punto_siguiente = punto + punto_mas_ + 20;
  doc.rect(25, punto + 45, 165, 13, 1, 1);
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
  months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];


  today = dd + '/' + mm + '/' + yyyy;

  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  // doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
  doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(25, punto + 12, 'DIRECCIÓN DE RECURSOS HUMANOS', { align: 'left', width: 185 });
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
  doc.writeText(5, 5, 'No. Boleta: ' + correlativo, { align: 'right', width: 200 });
  //doc.line(75, 10, 75,50);

  doc.setFontType("bold");
  doc.setFontSize(11);
  // definitivo = (tipo == 1) ? '' : 'DEFINITIVO ';
  doc.writeText(5, 45, 'BOLETA DE SOLICITUD DE VACACIONES', { align: 'center', width: 205 });
  doc.setFontSize(9);
  doc.setFontType('normal');

  doc.writeText(5, 55, "Guatemala " + dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy, { align: 'right', width: 185 });

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
  doc.text(25, punto + 38, 'FECHA DE SOLICITUD : ');
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
  doc.line(55, 121, 55, punto + punto_mas + 35);
  doc.line(88, 121, 88, punto + punto_mas + 35);
  doc.line(122, 121, 122, punto + punto_mas + 35);
  doc.line(155, 121, 155, punto + punto_mas + 35);

  doc.text(30, 104, '');
  doc.text(30, 109, '');
  doc.text(30, 114, '');
  doc.text(30, punto + punto_mas, 'Periódo de vacaciones solicitadas : ');
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
  punto_siguiente = 135;
  doc.text(25, punto_siguiente + 43, '(f)_____________________________ ');
  doc.text(45, punto_siguiente + 48, 'Solicitante');
  doc.text(135, punto_siguiente + 43, '(f)_____________________________ ');
  doc.text(152, punto_siguiente + 48, 'Jefe Inmediato');
  doc.text(25, punto_siguiente + 63, '(f)_____________________________ ');
  doc.text(20, punto_siguiente + 68, 'Director o Subdirector de la Dirección solicitante');
  doc.text(117, punto_siguiente + 63, 'Revisado por : _____________________________ ');
  doc.text(122, punto_siguiente + 68, 'Director o Subdirector de Recursos Humanos');
  doc.text(70, punto_siguiente + 83, 'Vo.Bo._____________________________ ');


  if (superior == 15) {
    doc.text(84, punto_siguiente + 88, 'Subsecretario de Seguridad');
  } else if (superior == 14) {
    doc.text(83, punto_siguiente + 88, 'Subsecretario Administrativo');
  } else {
    doc.text(96, punto_siguiente + 88, 'Secretario');
  }

  doc.roundedRect(25, punto_siguiente + 93, 165, 20, 1, 1);
  doc.setFontType("normal");
  var r_d1 = 'NOTA:  Entregue su solicitud a la Dirección de Recursos Humanos para que esta obtenga la autorización del Director de Recursos Humanos. Si el interesado no goza los días solicitados de vacaciones, el jefe inmediato tiene la obligación de notificar a la Dirección de Recursos Humanos por escrito, caso contrario se contarán como gozados. Llenese únicamente en original.';
  var r_lineas1 = doc.splitTextToSize(r_d1, 160);
  doc.text(27.5, punto_siguiente + 98, r_lineas1);

  doc.setFontType('normal');
  doc.setTextColor(5, 83, 142);
  doc.setFontSize(8);
  doc.writeText(0, 253, 'Reporte Generado Herramientas Administrativas - Módulo Control de Horarios', { align: 'center', width: 215 });
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
  months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];


  today = dd + '/' + mm + '/' + yyyy;

  doc.setFontType("bold");
  doc.setTextColor(68, 68, 68);
  // doc.writeText(25, punto, direccion, { align: 'left', width: 185 });
  doc.writeText(25, punto + 4, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'left', width: 185 });
  doc.writeText(25, punto + 8, 'PRESIDENCIA DE LA REPÚBLICA', { align: 'left', width: 185 });
  doc.writeText(25, punto + 12, 'DIRECCIÓN DE RECURSOS HUMANOS', { align: 'left', width: 185 });
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
  // doc.writeText(5, 5, 'No. Boleta: ' + correlativo, { align: 'right', width: 200 });
  //doc.line(75, 10, 75,50);

  doc.setFontType("bold");
  doc.setFontSize(11);
  // definitivo = (tipo == 1) ? '' : 'DEFINITIVO ';
  doc.writeText(5, 45, 'CONSTANCIA DE VACACIONES GOZADAS', { align: 'center', width: 205 });
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
  doc.text(80, punto + 38, dd + " de " + months[parseInt(mm) - 1] + " de " + yyyy);
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
  doc.line(55, 121, 55, punto + punto_mas + 35);
  doc.line(88, 121, 88, punto + punto_mas + 35);
  doc.line(122, 121, 122, punto + punto_mas + 35);
  doc.line(155, 121, 155, punto + punto_mas + 35);

  doc.text(30, 104, '');
  doc.text(30, 109, '');
  doc.text(30, 114, '');
  doc.text(30, punto + punto_mas, 'Periódo de vacaciones gozadas : ');
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
  doc.writeText(0, 253, 'Reporte Generado Herramientas Administrativas - Módulo Control de Horarios', { align: 'center', width: 215 });
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

      var punto_ = 50;
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
      lugar = 'del 01 de enero 2021 al 31 de diciembre 2021';
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
      secre = data['id_secre'];
      ssecre = data['id_subsecre'];
      superior = data['id_superior'];
      // console.log("secre " + secre);
      // console.log("ssecre" + ssecre);
      // console.log("superior" + superior);
      punto += 4;
      doc.writeText(5, 8, 'Original', { align: 'right', width: 200 });
      cabeceras_boleta(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, fecha_sol, fecha_pre, 1, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol, superior);

      var x = document.getElementById("pdf_preview_v");
      if (x.style.display === "none") {
        x.style.display = "none";
      } else {
        x.style.display = "none";
      }


      doc.addPage();

      punto_ = 50;
      punto = punto_ + 4;
      doc.setTextColor(68, 68, 68);
      doc.setFontSize(9);
      doc.setFontType("normal");
      doc.writeText(5, 8, 'Copia', { align: 'right', width: 200 });
      cabeceras_boleta(doc, punto, direccion, correlativo, nombramiento, fecha, motivo, registros, fecha_ini, fecha_fin, fecha_sol, fecha_pre, 1, nombre, dir_funcional, puesto, vdia, vgoz, vpen, vsol, superior);

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

      var punto_ = 50;
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
      lugar = 'del 01 de enero 2021 al 31 de diciembre 2021';
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
      punto += 4;
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