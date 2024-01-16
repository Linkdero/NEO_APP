function set_dates(){
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
  
  
    today = dd + '-' + mm + '-' + yyyy;
  
    var days_ago1 = new Date();
    days_ago1.setDate(days_ago1.getDate()-5);
    var dd1 = String(days_ago1.getDate()).padStart(2, '0');
    var mm1 = String(days_ago1.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy1 = days_ago1.getFullYear();
    days_ago= dd1 + '-' + mm1 + '-' + yyyy1;
  
    $('#ini').val(days_ago);
    $('#fin').val(today);
  }
    
  function get_chart(){
    $.ajax({
      type: "POST",
      url: "insumos/php/back/reportes/get_chart_por_status.php",
      dataType:'json',
      beforeSend:function(){
        //$('.data_').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
      },
      success:function(data){
        var name=[];
        var dato1=[];
        var dato2=[];
        var dato3=[];
  
        for (var i in data) {
            name.push(data[i].Dir_descor);
            dato1.push(data[i].TOTAL);
            dato2.push(data[i].TOTAL3);
            dato3.push(data[i].TOTAL2);
        }
  
        var chartdata = {
          labels: name,
          datasets: [
              {
                label: 'Total en servicio',
                borderColor: 'rgba(41,114,250,0.6)',
                backgroundColor: 'rgba(41,114,250,0.6)',
                  hoverBackgroundColor: '#007bff',
                  hoverBorderColor: '#666666',
                  data: dato1
              },
              {
                label: 'Total otros destinos',
                borderColor: '#FFB61E',
                backgroundColor: '#FFB61E',
                  hoverBackgroundColor: '#F4D03F',
                  hoverBorderColor: '#666666',
                  data: dato2
              },
              {
                label: 'Total Ausentes',
                borderColor: 'rgba(97,200,167,0.6)',
                backgroundColor: '#fb4143',
                  hoverBackgroundColor: '#dc3545',
                  hoverBorderColor: '#666666',
                  data: dato3
              }
          ]
  
        };
  
        $('.js-char-radios').each(function (i, el) {
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
  