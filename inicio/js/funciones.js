window.addEventListener("keyup",function(e){
    if(e.keyCode==27) {
        cerrar_modal();
        cancelar_solucion_conflictos();
    }
    if(e.keyCode==113) {
        $("#agregar_button").focus();
    }
});
﻿function get_usuario(){
  $.ajax({
    type: "POST",
    url: "inicio/php/back/get_usuario.php",
    dataType:'json',
    beforeSend:function(){
      $('#cargando').fadeIn("slow");
      /*$('#evento').removeClass('animacion_right_to_left');
      $('#puerta').removeClass('animacion_right_to_left');
      $('#fecha').removeClass('animacion_right_to_left');*/

    },
    success:function(data){
      $('#cargando').fadeOut("slow");
      /*$('#evento').addClass('animacion_right_to_left');
      $('#puerta').addClass('animacion_right_to_left');
      $('#fecha').addClass('animacion_right_to_left');*/

      $('#evento').text(data.evento);
      $('#puerta').text(data.punto);//+ ', Ingresos por este punto: '+data.conteo+' - Total: '+data.total);
      $('#fecha').text(data.fecha);

    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}

function get_totales(){
  $.ajax({
    type: "POST",
    url: "inicio/php/back/get_chart.php",
    dataType:'json',
    beforeSend:function(){
      $('.data_').html("<div class='spinner-grow-sm ' style='margin-left:0%'></div>");
    },
    success:function(data){
      //alert(data.grap1);
      $('#acreditaciones_graph').attr("data-set",data.graph1);
      contar('acreditaciones_per',data.por_personas,1);
      contar('acreditaciones_cant',data.personas,2);

      $('#invitados_graph').attr("data-set",data.graph2);
      contar('invitados_per',data.por_total,1);
      contar('invitados_cant',data.total,2);

      //$('#servicio_per').text(data.por_total);

      crear_circle();

    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}
function crear_circle(){

  $('.js-doughnut-chart').each(function (i, el) {
    var data = JSON.parse(el.getAttribute('data-set')),
      colors = JSON.parse(el.getAttribute('data-colors'));

    var chart = new Chart(el, {
      type: 'doughnut',
      data: {
        datasets: [{
          backgroundColor: colors,
          data: data
        }]
      },
      options: {
        legend: {
          display: false
        },
        tooltips: {
          enabled: false
        },
        cutoutPercentage: 64
      }
    });
  });


}



function get_acreditacion(){
  document.getElementById("myDiv").style.paddingRight = '0px';
  var imgModal = $('#modal-remoto');
    var imgModalBody = imgModal.find('.modal-content');
  $.ajax({
    type: "POST",
    url: "inicio/php/back/get_acreditacion.php",
    //dataType:'json',
    data:{invitado: function() { return $('#bar_code').val() }},
    beforeSend:function(){
      imgModal.modal('show');
      //$('#div_invitado').html("<br><br><div class='loaderr '></div>");
      imgModalBody.html("<br><br><div class='loaderr '></div><br><br>");
    },
    success:function(data){


      document.getElementById("bar_code").disabled = true;
      document.getElementById("bar_code").disabled = false;
      $("#agregar_button").focus();

      $('#bar_code').val('');

        //Shows the modal
        //imgModal.modal('show');

        imgModalBody.html(data);
        //setInterval(, 1000);
        setTimeout(function(){
    //do what you need here
    clickButton();
}, 1000);

    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}

function addClick() {
            clicks = clicks + 1;
            document.querySelector('.total-clicks').textContent
                        = clicks;
        }

        // Simulate click function
        function clickButton() {
            document.querySelector('#agregar_button').click();
        }

function edit_acreditacion(){

  document.getElementById("myDiv").style.paddingRight = '0px';
  var imgModal = $('#modal-remoto');
    var imgModalBody = imgModal.find('.modal-content');
  $.ajax({
    type: "POST",
    url: "inicio/php/back/edit_acreditacion.php",
    //dataType:'json',
    data:{invitado: function() { return $('#bar_code_e').val() }},
    beforeSend:function(){
      imgModal.modal('show');
      //$('#div_invitado').html("<br><br><div class='loaderr '></div>");
      imgModalBody.html("<br><br><div class='loaderr '></div><br><br>");
    },
    success:function(data){


      document.getElementById("bar_code_e").disabled = true;
      document.getElementById("bar_code_e").disabled = false;
      $("#agregar_button").focus();

      $('#bar_code_e').val('');

        //Shows the modal


        imgModalBody.html(data);

    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });


/*





  $.ajax({
    type: "POST",
    url: "inicio/php/back/edit_acreditacion.php",
    //dataType:'json',
    data:{invitado: function() { return $('#bar_code_e').val() }},
    beforeSend:function(){
      $('#div_invitado').html("<br><br><div class='loaderr '></div>");
    },
    success:function(data){
      $('#div_invitado').html(data);

      $('#bar_code_e').val('');
      //$('#agregar_button').eq(index).focus();
      //$('#agregar_button').focus();
      //document.getElementById("agregar_button").focus();

    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });*/

}

function update_nuevo_acreditado(invitado){
  swal({
    title: '<strong>¿Desea Actualizar este Invitado?</strong>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Actualizar!'
    }).then((result) => {
    if (result.value) {
      //funcionamiento
      nombre=$('#nombre').val();
      institucion=$('#institucion').val();
      $.ajax({
        type: "POST",
        url: "inicio/php/back/update_transaccion.php",
        data: {invitado:invitado,nombre:nombre,institucion:institucion}, //f de fecha y u de estado.

        beforeSend:function(){
          //$('#response').html('<span class="text-info">Loading response...</span>');
          swal({
title: 'Espere..!',
text: 'Se están guardando los datos',
allowOutsideClick: false,
allowEscapeKey: false,
allowEnterKey: false,
//timer: 1000,
onOpen: () => {
swal.showLoading()
}
})
        },
        success:function(data){
          //alert(data);
          //get_last_5_directors();
          //$('#div_invitado').html('');
          cancelar_solucion_conflictos();

          //$('#modal-remoto').modal('hide');
          swal({
            type: 'success',
            title: 'Invitado actualizado',
            showConfirmButton: false,
            timer: 1100
          });
        }
      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){
        alert(errorThrown);
      });
      //fin
    }

  })
}

function getFocus() {
  document.getElementById("agregar_button").focus();
}

function agregar_asistencia(invitado){

  /*swal({
    title: '<strong>¿Desea Ingresar este registro?</strong>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Registrar!'
    }).then((result) => {
    if (result.value) {
      //funcionamiento*/
      observaciones=$('#observaciones').val();
      $.ajax({
        type: "POST",
        url: "inicio/php/back/insert_transaccion.php",
        data: {invitado:invitado,observaciones:observaciones}, //f de fecha y u de estado.

        beforeSend:function(){
          //$('#response').html('<span class="text-info">Loading response...</span>');
          swal({
title: 'Espere..!',
text: 'Se están guardando los datos',
allowOutsideClick: false,
allowEscapeKey: false,
allowEnterKey: false,
//timer: 1000,
onOpen: () => {
swal.showLoading()
}
})
        },
        success:function(data){
          //alert(data);
          //get_last_5_directors();
          //$('#div_invitado').html('');
          cerrar_modal();
          //get_last_5_ingresos();
          reload_acreditaciones();
          //get_usuario();
          //$('#modal-remoto').modal('hide');
          swal({
            type: 'success',
            title: 'Registro ingresado',
            showConfirmButton: false,
            timer: 1100
          });
          document.getElementById("myDiv").style.paddingRight = '0px';

        }
      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){
        alert(errorThrown);
      });
      //fin
    /*}
  })*/

}





function contar(elemento, valor,tipo){

  jQuery({ Counter: 0 }).animate({ Counter: valor }, {
              duration: 2000,
              easing: "swing",
              step: function () {
                if(tipo==1){
                  $('#'+elemento).text(Math.ceil(this.Counter)+'%');
                }else if(tipo==2){
                  $('#'+elemento).text(Math.ceil(this.Counter));
                }

              }
            });
}


function get_chart(){
  $.ajax({
    type: "POST",
    url: "inicio/php/back/get_chart.php",
    dataType:'json',
    beforeSend:function(){
      //$('.data_').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
    },
    success:function(data){
      var name=[];
      var dato1=[];
      var dato2=[];
      var dato3=[];

      //for (var i in data) {
          name.push('Invitados');
          dato1.push(data.total);
          dato2.push(data.personas);
          //dato3.push(data.total);
      //}

      var chartdata = {
        labels: name,
        datasets: [
            {
              label: 'Total Invitados',
              borderColor: 'rgba(41,114,250,0.6)',
              backgroundColor: '#007bff',
                hoverBackgroundColor: '#007bff',
                hoverBorderColor: '#666666',
                data: dato1
            },
            {
              label: 'Total Presentes',
              borderColor: '#FFB61E',
              backgroundColor: '#0dd157',
                hoverBackgroundColor: '#0dd157',
                hoverBorderColor: '#666666',
                data: dato2
            }/*,
            {
              label: 'Total Ausentes',
              borderColor: 'rgba(97,200,167,0.6)',
              backgroundColor: '#fb4143',
                hoverBackgroundColor: '#dc3545',
                hoverBorderColor: '#666666',
                data: dato3
            }*/
        ]

      };

      $('.js-overall-income-chart').each(function (i, el) {
        var chart = new Chart(el, {
          type: 'bar',
          data: chartdata,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
              display: false
            },
            elements: {
              point: {
                radius: 0
              },
              line: {
                borderWidth: 1
              }
            },
            scales: {
              xAxes: [{
                gridLines: {
                  borderDash: [8, 8],
                  color: '#eaf2f9'
                },
                ticks: {
                  fontFamily: 'Open Sans',
                  fontColor: '#6e7f94'
                }
              }],
              yAxes: [{
                gridLines: {
                  borderDash: [8, 8],
                  color: '#eaf2f9'
                },
                ticks: {
                  fontFamily: 'Open Sans',
                  fontColor: '#6e7f94'
                }
              }]
            },
            tooltips: {
              enabled: false,
              intersect: 0,
              custom: function (tooltipModel) {
                // Tooltip Element
                var tooltipEl = document.getElementById('overallIncomeChartTooltip' + i);

                // Create element on first render
                if (!tooltipEl) {
                  tooltipEl = document.createElement('div');
                  tooltipEl.id = 'overallIncomeChartTooltip' + i;
                  tooltipEl.className = 'u-chart-tooltip';
                  tooltipEl.innerHTML = '<div class="u-tooltip-body"></div>';
                  document.body.appendChild(tooltipEl);
                }

                // Hide if no tooltip
                if (tooltipModel.opacity === 0) {
                  tooltipEl.style.opacity = 0;
                  return;
                }

                // Set caret Position
                tooltipEl.classList.remove('above', 'below', 'no-transform');
                if (tooltipModel.yAlign) {
                  tooltipEl.classList.add(tooltipModel.yAlign);
                } else {
                  tooltipEl.classList.add('no-transform');
                }

                function getBody(bodyItem) {
                  return bodyItem.lines;
                }

                // Set Text
                if (tooltipModel.body) {
                  var titleLines = tooltipModel.title || [],
                    bodyLines = tooltipModel.body.map(getBody),
                    innerHtml = '<h4 class="u-chart-tooltip__title">';

                  titleLines.forEach(function (title) {
                    innerHtml += title;
                  });

                  innerHtml += '</h4>';

                  bodyLines.forEach(function (body, i) {
                    var colors = tooltipModel.labelColors[i];
                    innerHtml += '<div class="u-chart-tooltip__value">' + body + '</div>';
                  });

                  var tableRoot = tooltipEl.querySelector('.u-tooltip-body');
                  tableRoot.innerHTML = innerHtml;
                }

                // `this` will be the overall tooltip
                var $self = this,
                  position = $self._chart.canvas.getBoundingClientRect(),
                  tooltipWidth = $(tooltipEl).outerWidth(),
                  tooltipHeight = $(tooltipEl).outerHeight();

                // Display, position, and set styles for font
                tooltipEl.style.opacity = 1;
                tooltipEl.style.left = (position.left + tooltipModel.caretX - tooltipWidth / 2) + 'px';
                tooltipEl.style.top = (position.top + tooltipModel.caretY - tooltipHeight - 15) + 'px';

                $(window).on('scroll', function() {
                  var position = $self._chart.canvas.getBoundingClientRect(),
                    tooltipWidth = $(tooltipEl).outerWidth(),
                    tooltipHeight = $(tooltipEl).outerHeight();

                  // Display, position, and set styles for font
                  tooltipEl.style.left = (position.left + tooltipModel.caretX - tooltipWidth / 2) + 'px';
                  tooltipEl.style.top = (position.top + tooltipModel.caretY - tooltipHeight - 15) + 'px';
                });
              }
            }
          }
        });
      });



    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}

//
function get_last_5_ingresos(){
  $.ajax({
    type: "POST",
    url: "inicio/php/back/get_last_ingresos.php",
    dataType:'json',
    beforeSend:function(){
      $('#div_invitado').html("<br><br><div class='loaderr' ></div>");
    },
    success:function(data){

      var html_='';

      for (var i in data) {
          html_+='<span class="list-group-item list-group-item-action card-body-slide">';
          html_+='<div class="media">';
          //html_+=hexToBase64(data[i].foto);

          if(data[i].foto!=''){
            html_+='<img class="u-avatar rounded-circle mr-3" src="data:image/jpeg;base64,'+data[i].foto+'" alt="Image description">';
          }else{
            html_+="<img class='u-avatar rounded-circle mr-3' src='assets/svg/mockups/escudo.png' style='width:55px; '> ";
          }

          html_+='<div class="media-body">';
          html_+='<div class="d-md-flex align-items-center">';
          html_+='<h4 class="mb-1">';
          var tipo = '';
          if(data[i].conteo==1){
            tipo='<span class="badge badge-soft-danger mx-1" style="position:absolute">Salida</span>';
          }else{
            tipo='<span class="badge badge-soft-secondary mx-1" style="position:absolute">Entrada</span>';
          }

          html_+=data[i].invitado+tipo;
          html_+='</h4>';
          html_+='<small class="text-muted ml-md-auto">'+data[i].hora+'</small>';
          html_+='</div>';
          html_+='<p class="mb-0">'+data[i].institucion+'</p>';
          html_+='</div>';
          html_+='</div>';
          html_+='</span>';
      }
      $('#div_invitado').html(html_);
    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}

function hexToBase64(str) {
    return btoa(String.fromCharCode.apply(null, str.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" ")));
}

function cerrar_modal(){
  $('#modal-remoto').modal('hide');
  $('#bar_code').val('');
  $('#bar_code').focus();

}

function inactivar_invitado(invitado,estado){
  swal({
    title: '<strong>¿Desea Inactivar este Invitado?</strong>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#28a745',
    cancelButtonText: 'Cancelar',
    confirmButtonText: '¡Si, Inactivar!'
    }).then((result) => {
    if (result.value) {
      //funcionamiento
      observaciones=$('#observaciones').val();
      $.ajax({
        type: "POST",
        url: "inicio/php/back/inactivar_invitado.php",
        data: {invitado:invitado,estado:estado}, //f de fecha y u de estado.

        beforeSend:function(){
          //$('#response').html('<span class="text-info">Loading response...</span>');
          swal({
title: 'Espere..!',
text: 'Se están guardando los datos',
allowOutsideClick: false,
allowEscapeKey: false,
allowEnterKey: false,
//timer: 1000,
onOpen: () => {
swal.showLoading()
}
})
        },
        success:function(data){
          //alert(data);
          //get_last_5_directors();
          //$('#div_invitado').html('');
          //cerrar_modal();
          //get_last_5_ingresos();
          //get_usuario();
          //$('#modal-remoto').modal('hide');
          cancelar_solucion_conflictos();
          swal({
            type: 'success',
            title: 'Invitado Inactivado',
            showConfirmButton: false,
            timer: 1100
          });
          document.getElementById("myDiv").style.paddingRight = '0px';

        }
      }).done( function() {

      }).fail( function( jqXHR, textSttus, errorThrown){
        alert(errorThrown);
      });
      //fin
    }
  })

}

function cancelar_solucion_conflictos(){
  $('#modal-remoto').modal('hide');

  $('#bar_code_e').val('');
  $('#bar_code_e').focus();
}

function get_totales_por_evento(evento){
  $.ajax({
    type: "POST",
    url: "inicio/php/back/get_totales_evento.php",
    dataType:'json',
    beforeSend:function(){
          },
    success:function(data){
      //alert(data.conteo+' - - '+data.total)
      $('#puerta_2').text(data.punto+ ', Ingresos por este punto: '+data.conteo+' - Total: '+data.total);
    }

  }).done( function() {

  }).fail( function( jqXHR, textSttus, errorThrown){


  });
}
