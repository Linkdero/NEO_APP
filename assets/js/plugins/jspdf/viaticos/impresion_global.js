function imprimir_liquidacion_global() {
var type = $('#tipo').val();
  $.ajax({
    type: "POST",
    url: "viaticos/php/back/hojas/formularios_globales/vt_impresion.php",
    data: {
      ini:$('#ini').val(),
      fin:$('#fin').val(),
      tipo:type
    },
    dataType: 'json', //f de fecha y u de estado.
    //contentType:false,
    //processData:false,




    beforeSend: function () {
      //$('#response').html('<span class="text-info">Loading response...</span>');
      Swal.fire({
        title: 'Espere..!',
        text: 'Generando impresiones, por favor espere..',
        onBeforeOpen () {
          Swal.showLoading ()
        },

        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false
      })
      function myFunc(){
        Swal.close()
      }

    },
    success: function (data) {
      Swal.close();
      //alert(data);
      //console.log(data);
      var documento;
      var hojas = data.data.length;
      var doc = new jsPDF('p', 'mm');

      async function asyncCall(doc) {
        console.log('calling');
        const result = await resolveAfter2Seconds(doc);
        console.log(result);
        // Expected output: "resolved"
      }

      asyncCall(doc);

      function resolveAfter2Seconds(doc) {
        return new Promise((resolve) => {
          /*setTimeout(() => {*/
            //inicio

            if(type == 1){
              //inicio
              console.log(data);
              data.data.map(function(obj){
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
                doc.writeText(0, punto + 15, obj.formulario, { align: 'right', width: 194 });
                doc.setFontSize(10);
                doc.setFontType("normal");
                doc.writeText(140, punto + 25, 'Por Q.', { align: 'right', width: 10 });
                doc.setFontSize(9);
                doc.writeText(0, punto + 24, obj.monto_num, { align: 'right', width: 185 });
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

                if (obj.estado_viatico == 0) {
                  doc.setTextColor(204, 204, 204);
                  doc.setFontSize(100);
                  doc.writeText(0, 140, 'ANULADO', { align: 'center', width: 215 });
                }
                doc.setTextColor(33, 33, 33);
                doc.setFontSize(8.5);
                doc.text(15, punto2 + 8.5, 'RECIBI DE: ');
                doc.text(15, punto2 + 18.5, 'LA CANTIDAD DE: ');
                doc.setFontSize(8.5);
                doc.writeText(10, punto2 + 8.5, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
                doc.writeText(10, punto2 + 18.5, obj.monto_letras, { align: 'center', width: 205 });
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
                /*var r_d1 = obj.resolucion;
                var r_lineas1 = doc.splitTextToSize(r_d1, 190);
                doc.text(10, punto+250, r_lineas1);*/
                var r_d1 = obj.tipo_comision;
                var r_lineas1 = doc.splitTextToSize(r_d1, 55);
                doc.text(13, punto2 + 55, r_lineas1);
                doc.setLineWidth(0.3);

                //doc.writeText(72, punto2+55 ,obj.destino,{align:'center',width:89});
                var r_d1 = obj.destino;
                var r_lineas1 = doc.splitTextToSize(r_d1, 55);
                doc.text(75.5, punto2 + 55, r_lineas1);
                doc.writeText(150, punto2 + 55, obj.num_dias + ' % Acumulado ' + obj.porcentaje_proyectado, { align: 'center', width: 50 });

                doc.line(72, punto2 + 35, 72, punto2 + 110);
                doc.line(145, punto2 + 35, 145, punto2 + 110);
                doc.setFontSize(9);
                //inicio detalle
                doc.writeText(15, punto2 + 116, 'SEGUN NOMBRAMIENTO NUMERO: ', { align: 'left', width: 65 });
                doc.writeText(150, punto2 + 116, 'FECHA:', { align: 'left', width: 65 });
                doc.line(73, punto2 + 118, 145, punto2 + 118);
                doc.line(162, punto2 + 118, 200, punto2 + 118);
                doc.writeText(77, punto2 + 116, obj.nombramiento, { align: 'center', width: 65 });
                doc.writeText(170, punto2 + 116, obj.fecha_solicitud, { align: 'center', width: 20 });
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
                doc.writeText(50, punto2 + 132, obj.director, { align: 'left', width: 215 });
                doc.writeText(50, punto2 + 141, obj.director_puesto, { align: 'left', width: 215 });
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
                doc.writeText(50, punto2 + 157, obj.emp, { align: 'left', width: 215 });
                doc.writeText(50, punto2 + 164, obj.cargo, { align: 'left', width: 215 });
                doc.writeText(50, punto2 + 171, 'Guatemala ' + obj.hoy, { align: 'left', width: 215 });
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




                //doc.writeText(0, punto+150 ,obj.resolucion,{align:'center',width:215});

                doc.setFontType("normal");
                doc.setFontSize(7.5);
                var r_d1 = obj.resolucion;
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





              });
              //fin
            }else
            if(type == 2){
              //inicio
              data.data.map(function(obj){

                if (obj.estado_viatico == 0) {
                  doc.setTextColor(204, 204, 204);
                  doc.setFontSize(100);
                  doc.writeText(0, 160, 'ANULADO', { align: 'center', width: 215 });
                  imprimir_constancia_vacia(3, doc);
                }
                doc.setTextColor(33, 33, 33);
                doc.setFontType("normal");
                punto = 10;
                punto2 = 47;
                doc.setFontSize(11);

                doc.writeText(10, punto + 16, obj.formulario, { align: 'right', width: 187 });
                doc.setTextColor(33, 33, 33);
                doc.setFontSize(9);
                doc.writeText(47, punto2 + 25, obj.emp, { align: 'left', width: 65 });
                doc.writeText(47, punto2 + 35, obj.cargo, { align: 'left', width: 65 });

                var punto_destino = punto2 + 84;
                if (obj.descripcion_lugar == 2) {
                  for (nombreIndice in obj.destinos) {
                    doc.setFontSize(9);
                    var r_d1 = obj.destinos[nombreIndice].dep;
                    var r_lineas1 = doc.splitTextToSize(r_d1, 35);
                    doc.text(38, punto_destino, r_lineas1);

                    doc.setFontSize(7);
                    doc.writeText(77, punto_destino, obj.destinos[nombreIndice].f_ini, { align: 'center', width: 30 });
                    doc.writeText(93, punto_destino, obj.destinos[nombreIndice].h_ini, { align: 'center', width: 30 });
                    doc.writeText(111, punto_destino, obj.destinos[nombreIndice].f_fin, { align: 'center', width: 30 });
                    doc.writeText(128, punto_destino, obj.destinos[nombreIndice].h_fin, { align: 'center', width: 30 });

                    punto_destino += 25;
                    doc.writeText(10, punto + 200, obj.hospedaje, { align: 'center', width: 194 });
                    doc.writeText(10, punto + 205, obj.alimentacion, { align: 'center', width: 194 });
                  }
                } else {
                  var r_d1 = obj.destino;
                  var r_lineas1 = doc.splitTextToSize(r_d1, 30);
                  doc.text(40, punto2 + 84, r_lineas1);
                  doc.setFontSize(7);
                  doc.writeText(77, punto2 + 84, obj.hora_llegada, { align: 'center', width: 30 });

                  doc.writeText(93, punto2 + 84, obj.fecha_llegada_lugar, { align: 'center', width: 30 });
                  doc.writeText(111, punto2 + 84, obj.hora_salida, { align: 'center', width: 30 });
                  doc.writeText(128, punto2 + 84, obj.fecha_salida_lugar, { align: 'center', width: 30 });

                  doc.writeText(10, punto + 175, obj.hospedaje, { align: 'center', width: 194 });
                  doc.writeText(10, punto + 180, obj.alimentacion, { align: 'center', width: 194 });
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
                var r_d1 = obj.resolucion;
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

              });
              //fin
            }else
            if(type == 3){
              //inicio
              data.data.map(function(obj){
                if (obj.estado_viatico == 0) {
                  doc.setTextColor(204, 204, 204);
                  doc.setFontSize(100);
                  doc.writeText(0, 140, 'ANULADO', { align: 'center', width: 215 });
                }
                doc.setTextColor(33, 33, 33);
                punto = 10;

                doc.addImage(escudo_viatico, 'png', 60, 3, 20, 20);
                doc.addImage(logo_cgc, 'png', 142, punto - 2, 17, 17);
                doc.setFontType("bold");
                doc.setFontSize(12);
                doc.writeText(5, punto, 'VL-SAAS-001-SCC', { align: 'right', width: 198 });
                doc.setFontSize(9);
                doc.writeText(5, punto + 7, 'CORRELATIVO CGC No.', { align: 'right', width: 198 });
                doc.setFontSize(11);
                doc.writeText(10, punto + 15, obj.formulario, { align: 'right', width: 194 });
                doc.setFontSize(10);
                doc.setFontType("normal");
                doc.writeText(145, punto + 25, 'Por Q.', { align: 'right', width: 10 });
                doc.setFontSize(9);
                doc.writeText(5, punto + 24, obj.total_real_cabecera, { align: 'right', width: 185 });
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

                doc.setLineWidth(0.3);
                doc.roundedRect(161.5, 11.2, 45, 17.6, 2.5, 2.5);
                doc.roundedRect(137, 3, 73, 41, 2, 2);
                punto2 = 47;
                doc.roundedRect(5, 49, 205, 207, 1, 1);

                doc.line(5, 72, 211, 72);

                doc.setFontSize(8.5);
                doc.text(10, punto2 + 8.5, 'RECIBI DE: ');
                doc.text(10, punto2 + 18.5, 'LA CANTIDAD DE: ');
                doc.setFontSize(8.5);
                doc.writeText(10, punto2 + 8.5, 'SECRETARIA DE ASUNTOS ADMINISTRATIVOS Y DE SEGURIDAD', { align: 'center', width: 205 });
                doc.writeText(10, punto2 + 18.5, obj.monto_letras, { align: 'center', width: 205 });
                doc.setFontSize(6.5);
                doc.writeText(10, punto2 + 13, '(NOMBRE DE LA DEPENDENCIA)', { align: 'center', width: 205 });
                doc.writeText(10, punto2 + 23, '(EN LETRAS)', { align: 'center', width: 205 });

                doc.line(30, punto2 + 10, 205, punto2 + 10);
                doc.line(42, punto2 + 20, 205, punto2 + 20);

                doc.setFillColor(0, 0, 0);
                doc.setDrawColor(0, 0, 0);
                doc.setLineWidth(1);


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


                doc.setFontType("normal");
                doc.setFontSize(8);

                var r_d1 = obj.tipo_comision;
                var r_lineas1 = doc.splitTextToSize(r_d1, 55);
                doc.text(8, punto2 + 55, r_lineas1);
                doc.setLineWidth(0.3);


                punto_destino = punto2 + 55;
                //alert(obj.descripcion_lugar)
                doc.writeText(100.5, punto2 + 52, obj.num_dias, { align: 'center', width: 53 });
                if (obj.descripcion_lugar == 2) {
                  for (nombreIndice in obj.destinos) {

                    var r_d1 = obj.destinos[nombreIndice].dep;
                    var r_lineas1 = doc.splitTextToSize(r_d1, 30);
                    doc.text(75.5, punto_destino, r_lineas1);
                    punto_destino += 10;
                  }
                  doc.setFontType("bold");
                  doc.writeText(100.5, punto2 + 58, ' % Acumulado ', { align: 'center', width: 53 });
                  doc.setFontType("normal");
                  doc.writeText(100.5, punto2 + 61, obj.porcentaje_real, { align: 'center', width: 53 });

                  doc.writeText(150, punto2 + 55, obj.moneda, { align: 'left', width: 30 });
                  doc.writeText(145, punto2 + 55, obj.cuota, { align: 'right', width: 30 });
                  doc.writeText(181.5, punto2 + 55, obj.monto_real, { align: 'right', width: 25 });

                } else {
                  var r_d1 = obj.destino;
                  var r_lineas1 = doc.splitTextToSize(r_d1, 30);
                  doc.text(75.5, punto2 + 55, r_lineas1);
                  doc.setFontType("bold");
                  doc.writeText(100.5, punto2 + 58, ' % Acumulado ', { align: 'center', width: 53 });
                  doc.setFontType("normal");
                  doc.writeText(100.5, punto2 + 61, obj.porcentaje_real, { align: 'center', width: 53 });

                  doc.writeText(150, punto2 + 55, obj.moneda, { align: 'left', width: 30 });
                  doc.writeText(145, punto2 + 55, obj.cuota, { align: 'right', width: 30 });
                  doc.writeText(181.5, punto2 + 55, obj.monto_real, { align: 'right', width: 25 });
                }



                var r_d1 = obj.reintegro_texto;
                var r_lineas1 = doc.splitTextToSize(r_d1, 55);
                doc.text(8, punto2 + 95, r_lineas1);
                doc.setLineWidth(0.3);

                var r_d1 = (obj.desc_tipo_cambio != '') ? obj.desc_tipo_cambio : '';
                var r_lineas1 = doc.splitTextToSize(r_d1, 75);
                doc.text(8, punto2 + 105, r_lineas1);

                doc.writeText(181.5, punto2 + 95, obj.total_reintegro_, { align: 'right', width: 25 });



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
                doc.writeText(181.5, punto2 + 114, obj.total_real, { align: 'right', width: 25 });
                doc.writeText(181.5, punto2 + 120, obj.otros_gastos, { align: 'right', width: 25 });
                doc.setFontType("bold");
                doc.writeText(181.5, punto2 + 126, obj.total_real_total, { align: 'right', width: 25 });

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



                doc.writeText(10, punto2 + 138, 'RECIBO POR MEDIO DE FORMULARIO V-A No. ' + obj.numero_viatico_anticipo + '    ' + obj.utilizado, { align: 'left', width: 65 });
                doc.writeText(10, punto2 + 144, 'REINTEGRO A LA DEPENDENCIA (-) ', { align: 'left', width: 65 });
                doc.writeText(10, punto2 + 150, 'COMPLEMENTO A MI FAVOR (+) ', { align: 'left', width: 65 });
                doc.writeText(181.5, punto2 + 138, obj.monto_anticipo, { align: 'right', width: 25 });
                doc.writeText(181.5, punto2 + 144, (obj.total_reintegro != '') ? obj.total_reintegro : '0.00', { align: 'right', width: 25 });
                doc.writeText(181.5, punto2 + 150, obj.a_favor, { align: 'right', width: 25 });
                doc.setLineWidth(1);
                doc.setFontType("bold");
                doc.line(5, punto2 + 152, 211, punto2 + 152);
                doc.writeText(10, punto2 + 156, 'TOTAL', { align: 'left', width: 205 });
                doc.writeText(181.5, punto2 + 156, obj.total_, { align: 'right', width: 25 });
                doc.line(5, punto2 + 158, 211, punto2 + 158);
                doc.setLineWidth(0.3);
                doc.line(5, punto2 + 164, 211, punto2 + 164);
                doc.line(5, punto2 + 170, 211, punto2 + 170);
                doc.setFontType("normal");
                doc.writeText(10, punto2 + 162, 'LUGAR Y FECHA:', { align: 'left', width: 65 });
                doc.writeText(40, punto2 + 162, obj.hoy, { align: 'left', width: 65 });
                doc.writeText(10, punto2 + 168, 'NOMBRE: ', { align: 'left', width: 65 });
                doc.writeText(28, punto2 + 168, obj.emp, { align: 'left', width: 65 });
                doc.writeText(140, punto2 + 168, 'FIRMA: ', { align: 'left', width: 65 });
                doc.setLineWidth(1);
                doc.writeText(10, punto2 + 174, 'CARGO: ', { align: 'left', width: 65 });
                doc.setFontSize(7.5);
                doc.writeText(25, punto2 + 174, obj.cargo, { align: 'left', width: 65 });
                doc.setFontSize(9);
                doc.writeText(108, punto2 + 174, 'SUELDO MENSUAL: ', { align: 'left', width: 65 });
                doc.writeText(140, punto2 + 174, obj.sueldo, { align: 'left', width: 65 });
                doc.writeText(158, punto2 + 174, 'PARTIDA No. ', { align: 'left', width: 65 });
                doc.writeText(182, punto2 + 174, obj.partida, { align: 'left', width: 65 });
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

                doc.writeText(116, punto2 + 207, 'AUTORIDAD QUE ORDENÓ LA COMISIÓN', { align: 'center', width: 95 });

                doc.line(108, punto2 + 176, 108, punto2 + 209);
                doc.setFontSize(9);
                doc.setFontType("normal");

                doc.setTextColor(33, 33, 33);

                doc.setFontType("normal");
                doc.setFontSize(7.5);
                var r_d1 = obj.resolucion;
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





              });
              //fin
            }

            var x = document.getElementById("pdf_preview_v");
            if (x.style.display === "none") {
              x.style.display = "none";
            } else {
              x.style.display = "none";
            }
            doc.save('export.pdf', { returnPromise: true }).then(() => {
              // Code will be executed after save
            });
            $("#pdf_preview_v").attr("src", doc.output('datauristring'));
            //fin
        /*  }, 5000);*/
      });
      }




    }


  }).done(function (data) {
  }).fail(function (jqXHR, textSttus, errorThrown) {

    alert(errorThrown);

  });





}
