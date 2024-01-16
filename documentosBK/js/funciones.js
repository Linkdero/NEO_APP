function mostar_formulario_confirma(){
  if( $('#chk_doc_externo').is(':checked') )
  {
    $('#lbl_chk').text('SI');
    document.getElementById("id_correlativo_respuesta").required = false;
  }else{
    $('#lbl_chk').text('NO');
    document.getElementById("id_correlativo_respuesta").required = true;
    //alert('uncheck')
    //document.getElementById("formulario_anterior").hidden=true
  }
}

function exportHTML(docto_id){
  $.ajax({
  type: "POST",
  url: "documentos/php/back/hojas/documento_generado.php",
  data: {docto_id}, //f de fecha y u de estado.
  dataType: 'json',
  beforeSend:function(){
  },
  success:function(data){
    //alert(data.titulo);
    var header = "ajsldkfjalñsdjflasjdflka<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
         "xmlns:w='urn:schemas-microsoft-com:office:word' "+
         "xmlns='http://www.w3.org/TR/REC-html40'>"+
         "<head><style> body{ font-family:arial}</style><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
    var footer = "</body></html>";
    var tipo = '<h2 style="text-align:'+data.alineacion+'">'+data.tipo+'</h2>';
    var correlativo = '<h3 style="text-align:'+data.alineacion+'"><right>'+data.correlativo+'</right><h3>';
    var titulo='<h1 style="text-align:'+data.alineacion+'"><right>'+data.titulo+'</right></h1>';
    var sourceHTML = header+tipo+correlativo+titulo;//+document.getElementById("source-html").innerHTML+footer;

    var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
    var fileDownload = document.createElement("a");
    document.body.appendChild(fileDownload);
    fileDownload.href = source;
    fileDownload.download = data.correlativo+'.doc';
    fileDownload.click();
    document.body.removeChild(fileDownload);

  }

}).done( function() {


}).fail( function( jqXHR, textSttus, errorThrown){

  alert(errorThrown);

});

}

function generate(docto_id) {
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/documento_generado.php",
    data: {docto_id}, //f de fecha y u de estado.
    dataType: 'json',
    beforeSend:function(){

    },
    success:function(data){
      //import {readFileSync } as fs from "fs";

      const doc = new docx.Document();
      //const reader = new FileReader();
      /*const image = docx.Media.addImage({
    document: doc,
    data: fs.readFileSync("LOGO_SAAS.png"),
    transformation: {
        width: 200,
        height: 200,
    }

});*/

      doc.addSection({
          properties: {},
          headers: {
              default: new docx.Header({
                  children: [
                    new docx.Paragraph("Header text"),
                    //new Paragraph(blob),
                  ],
              }),
          },
          children: [
              new docx.Paragraph({
                  children: [
                      new docx.TextRun(data.correlativo),
                      new docx.TextRun({
                          text: data.correlativo,
                          bold: true,
                          font: "Arial",
                          alignment: 'RIGHT',
                      }),
                      /*new docx.TextRun({
                          text: "\tGithub is the best",
                          bold: true,
                      }),*/
                  ],
              }),
          ],
      });

      docx.Packer.toBlob(doc).then(blob => {
          console.log(blob);
          saveAs(blob, data.correlativo+".docx");
          console.log("Document created successfully");
      });
    }
  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){
    alert(errorThrown);
  });
}

function generate_licitacion(docto_id) {
  $.ajax({
    type: "POST",
    url: "documentos/php/back/hojas/documento_licitacion.php",
    data: {docto_id}, //f de fecha y u de estado.
    dataType: 'json',
    beforeSend:function(){

    },
    success:function(data){
      //import {readFileSync } as fs from "fs";


      const doc = new docx.Document();
      //const reader = new FileReader();
      /*const image = docx.Media.addImage({
    document: doc,
    data: fs.readFileSync("LOGO_SAAS.png"),
    transformation: {
        width: 200,
        height: 200,
    }

});*/

      doc.addSection({
          properties: {},
          headers: {
              default: new docx.Header({
                  children: [
                    new docx.Paragraph("Header text"),
                    //new Paragraph(blob),
                  ],
              }),
          },
          children: [
            new docx.Paragraph({
                children: [
                    new docx.TextRun(data.correlativo),
                    new docx.TextRun({
                        text: data.indice.toString(),
                        bold: true,
                        font: "Arial",
                        alignment: 'RIGHT',
                    }),
                    /*new docx.TextRun({
                        text: "\tGithub is the best",
                        bold: true,
                    }),*/
                ],
            }),
              new docx.Paragraph({
                  children: [
                      new docx.TextRun(data.correlativo),
                      new docx.TextRun({
                          text: data.correlativo,
                          bold: true,
                          font: "Arial",
                          alignment: 'RIGHT',
                      }),
                      /*new docx.TextRun({
                          text: "\tGithub is the best",
                          bold: true,
                      }),*/
                  ],
              }),
          ],
      });

      for (nombreIndice in data.indice) {
        doc.addSection({
            children: [
              new docx.Paragraph({
                  children: [
                      new docx.TextRun(data.correlativo),
                      new docx.TextRun({
                          text: data.indice[nombreIndice],
                          bold: true,
                          font: "Arial",
                          alignment: 'RIGHT',
                      }),
                      /*new docx.TextRun({
                          text: "\tGithub is the best",
                          bold: true,
                      }),*/
                  ],
              })
            ],
        });
      }

      docx.Packer.toBlob(doc).then(blob => {
          console.log(blob);
          saveAs(blob, data.correlativo+".docx");
          console.log("Document created successfully");
      });
    }
  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){
    alert(errorThrown);
  });
}
