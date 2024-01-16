(function ($) {
  $(document).on('ready', function () {

    $.ajax({
      type: "POST",
      url: "insumos/php/back/reportes/get_estados.php",
      dataType:'json',
      beforeSend:function(){
        //$('.data_').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
      },
      success:function(data){

        var grafica='.js-overall-income-chart';
        var mm =0;
        for (var i in data) {
          mm++;
          console.log(mm);
          console.log(data[i].id_status);
          console.log(grafica+mm);
          var graph=''+grafica+mm+'';
          var chartdata;
          $.ajax({
            type: "POST",
            url: "insumos/php/back/reportes/get_chart_por_status.php",
            dataType:'json',
            data: {estado:data[i].id_status},
            beforeSend:function(){
              //$('.data_').html("<div class='spinner-grow-sm text-primary' style='margin-left:0%'></div> ");
            },
            success:function(data){
              //console.log(data.estado);
              var name=[];
              var dato1=[];
              var dato2=[];
              var dato3=[];

              for (var i in data) {

                  name.push(data[i].estado);
                  dato1.push(data[i].MOTOROLA);
                  dato2.push(data[i].Chicom);
                  dato3.push(data[i].HYTERA);
              }

              chartdata = {
                labels: name,
                datasets: [
                    {
                      label: 'Motorola',
                      borderColor: 'rgba(41,114,250,0.6)',
                      backgroundColor: 'rgba(41,114,250,0.6)',
                        hoverBackgroundColor: '#007bff',
                        hoverBorderColor: '#666666',
                        data: dato1
                    },
                    {
                      label: 'Chicom',
                      borderColor: '#FFB61E',
                      backgroundColor: '#FFB61E',
                        hoverBackgroundColor: '#F4D03F',
                        hoverBorderColor: '#666666',
                        data: dato2
                    },
                    {
                      label: 'Hytera',
                      borderColor: 'rgba(97,200,167,0.6)',
                      backgroundColor: '#fb4143',
                        hoverBackgroundColor: '#dc3545',
                        hoverBorderColor: '#666666',
                        data: dato3
                    }
                ]

              };

          $(graph).each(function (i, el) {
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
      });

        }

        //console.log(data);



      }
    });



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
          cutoutPercentage: 87
        }
      });
    });
  });
})(jQuery);
