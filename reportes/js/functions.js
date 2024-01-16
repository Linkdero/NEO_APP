let chart, chart_door, chart_rrhh, chart_rrhh_double, chart_notice, chart_user;

function init_graficas(option, ...params){

  if(option == "#puertas"){
    $.ajax({
      type: "POST",
      url: "reportes/php/back/datos_puertas.php",
      dataType: "json",
      data: {
        opcion:1,
        ini:function() { return $('#ini_p').val() },
        fin:function() { return $('#fin_p').val() },
        oficina:function() { return $('#oficina_visita_').val() },
        puerta:function() { return $('#puerta_').val() },
        no_salido:function() { return $('#no_salido').val() }

      },
      success:function(data){
        am4core.ready(function() {
          if($("#all_doors").val() == "true"){
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end
            chart = am4core.create("chart_all_doors", am4charts.XYChart);
            chart.data = data;
            chart.padding(40, 40, 40, 40);

            let title = chart.titles.create();
            title.text = "Visitas / Puertas";
            title.fontSize = 22;
            title.marginBottom = 20;

            let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.dataFields.category = "siglas";
            categoryAxis.renderer.minGridDistance = 60;
            categoryAxis.renderer.inversed = true;
            categoryAxis.renderer.grid.template.disabled = true;

            let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.extraMax = 0.1;
            //valueAxis.rangeChangeEasing = am4core.ease.linear;
            //valueAxis.rangeChangeDuration = 1500;

            let series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.categoryX = "siglas";
            series.dataFields.valueY = "visitas";
            series.tooltipText = "{valueY.value}"
            series.columns.template.strokeOpacity = 0;
            series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.cornerRadiusTopLeft = 10;
            series.columns.template.events.on("hit", function(ev) {
              init_graficas("puertas_2", ev.target.dataItem.categoryX, ev.target.dataItem.dataContext.puerta);
            });

            series.events.on("ready", function(ev) {
              let legenddata = [];
              series.columns.each(function(column) {
                legenddata.push({
                  name: column.dataItem.dataContext.puerta,
                  fill: column.fill
                })
              });
              legenddata.reverse();
              legend.data = legenddata;
            });
            let legend = new am4charts.Legend();
            legend.parent = chart.chartContainer;
            legend.itemContainers.template.togglable = false;
            legend.marginTop = 20;
            //series.interpolationDuration = 1500;
            //series.interpolationEasing = am4core.ease.linear;
            let labelBullet = series.bullets.push(new am4charts.LabelBullet());
            labelBullet.label.verticalCenter = "bottom";
            labelBullet.label.dy = -10;
            labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#.')}";
            chart.logo.height = -15000;
            chart.zoomOutButton.disabled = true;
            // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
            series.columns.template.adapter.add("fill", function (fill, target) {
              return chart.colors.getIndex(target.dataItem.index);
            });
            categoryAxis.sortBySeries = series;
            $("#all_doors").val("false");
          }else{
            chart.data = data;
          }
        });
      }
    });
  }else if(option == "puertas_2"){
    $.ajax({
      type: "POST",
      url: "reportes/php/back/datos_puertas.php",
      dataType: "json",
      data: {
        opcion: 2,
        siglas:params[0],
        ini:function() { return $('#ini_p').val() },
        fin:function() { return $('#fin_p').val() },
        oficina:function() { return $('#oficina_visita_').val() },
        puerta:function() { return $('#puerta_').val() },
        no_salido:function() { return $('#no_salido').val() }
      },
      success:function(data){
        if($("#date_door").val() == "true"){
          // Themes begin
          am4core.useTheme(am4themes_animated);
          chart_door = am4core.create("chart_door", am4charts.XYChart);
          let title = chart_door.titles.create();
          title.text = "Visitas / Fechas (" + params[1]+ ")";
          title.fontSize = 22;
          title.marginBottom = 20;
          chart_door.data = data;
          let dateAxis = chart_door.xAxes.push(new am4charts.DateAxis());
          dateAxis.renderer.grid.template.location = 0;
          dateAxis.renderer.minGridDistance = 50;
          let valueAxis = chart_door.yAxes.push(new am4charts.ValueAxis());
          let series = chart_door.series.push(new am4charts.LineSeries());
          series.dataFields.valueY = "value";
          series.dataFields.dateX = "fecha";
          series.strokeWidth = 3;
          series.fillOpacity = 0.5;

          // Add vertical scrollbar
          chart_door.scrollbarY = new am4core.Scrollbar();
          chart_door.scrollbarY.marginLeft = 0;

          // Add cursor
          chart_door.cursor = new am4charts.XYCursor();
          chart_door.cursor.behavior = "zoomY";
          chart_door.cursor.lineX.disabled = true;
          chart_door.logo.height = -15000;
          $("#date_door").val("false");

        }else{
          chart_door.titles.getIndex(0).text = "Visitas / Fechas (" + params[1]+ ")";
          chart_door.data = data;
        }
      }
    });
  }else if(option == "#rrhh"){
    $.ajax({
      type: "POST",
      url: "reportes/php/back/datos_empleados.php",
      dataType: "json",
      data: { opcion:1 },
      success:function(data){
        data.sort(function(a, b) {
            return a.total - b.total;
        });
        if($("#all_jobs").val() == "true"){
          am4core.ready(function() {
            chart_rrhh = am4core.create("chart_all_jobs", am4charts.XYChart);
            chart_rrhh.numberFormatter.numberFormat = "Q #,###.##";
            chart_rrhh.data = data;
            chart_rrhh.padding(40, 40, 40, 40);

            let title = chart_rrhh.titles.create();
            title.text = "Plazas";
            title.fontSize = 22;
            title.marginBottom = 20;

            let categoryAxis = chart_rrhh.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.dataFields.category = "siglas";
            categoryAxis.renderer.minGridDistance = 60;
            categoryAxis.renderer.inversed = true;
            categoryAxis.renderer.grid.template.disabled = true;

            let valueAxis = chart_rrhh.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.extraMax = 0.1;
            //valueAxis.rangeChangeEasing = am4core.ease.linear;
            //valueAxis.rangeChangeDuration = 1500;

            let series = chart_rrhh.series.push(new am4charts.ColumnSeries());
            series.dataFields.categoryX = "siglas";
            series.dataFields.valueY = "total";
            series.tooltipText = "{valueY.value}"
            series.columns.template.strokeOpacity = 0;
            series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.cornerRadiusTopLeft = 10;
            let hs = series.columns.template.states.create("active");
            hs.properties.fillOpacity = 1;

            series.events.on("ready", function(ev) {
              let legend_data = [];
              series.columns.each(function(column) {
                legend_data.push({
                  name: column.dataItem.dataContext.siglas,
                  fill: column.fill
                })
              });
              legend_data.reverse();
              legend.data = legend_data;
            });

            let legend = new am4charts.Legend();

            legend.parent = chart_rrhh.chartContainer;
            legend.itemContainers.template.togglable = false;
            legend.useDefaultMarker = true;
            legend.marginTop = 20;

            let labelBullet = series.bullets.push(new am4charts.LabelBullet());
            labelBullet.label.verticalCenter = "bottom";
            labelBullet.label.dy = -10;
            labelBullet.label.text = "{values.valueY.workingValue.formatNumber('#,###.##')}";
            chart_rrhh.logo.height = -15000;
            chart_rrhh.zoomOutButton.disabled = true;
            // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
            series.columns.template.adapter.add("fill", function (fill, target) {
              return chart_rrhh.colors.getIndex(target.dataItem.index);
            });
            categoryAxis.sortBySeries = series;
            $("#all_jobs").val("false");

          });

          am4core.ready(function() {
            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Create chart instance
            chart_rrhh_double = am4core.create("chart_job", am4charts.XYChart);
            // Add percent sign to all numbers
            chart_rrhh_double.numberFormatter.numberFormat = "Q #,###.##";
            chart_rrhh_double.data = data;

            // Create axes
            let categoryAxis = chart_rrhh_double.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "siglas";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;

            let valueAxis = chart_rrhh_double.yAxes.push(new am4charts.ValueAxis());
            valueAxis.title.text = "Presupuesto";
            valueAxis.title.fontWeight = 800;

            // Create series
            let series = chart_rrhh_double.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "ocupada";
            series.dataFields.categoryX = "siglas";
            series.clustered = false;
            series.tooltipText = "Ocupada: [bold] {valueY}[/]";

            let series2 = chart_rrhh_double.series.push(new am4charts.ColumnSeries());
            series2.dataFields.valueY = "vacante";
            series2.dataFields.categoryX = "siglas";
            series2.clustered = false;
            series2.columns.template.width = am4core.percent(50);
            series2.tooltipText = "Vacante: [bold] {valueY}[/]";

            chart_rrhh_double.cursor = new am4charts.XYCursor();
            chart_rrhh_double.cursor.lineX.disabled = true;
            chart_rrhh_double.cursor.lineY.disabled = true;
            $("#job").val("false");
          });
        }else{
          chart_rrhh.data = data;
          chart_rrhh_double.data = data;
        }
      }
    });
  }else if(option == "#noticias"){
    $.ajax({
      type: "POST",
      url: "reportes/php/back/datos_noticias.php",
      data: {
        opcion:1,
        ini:function() { return $('#ini_').val() },
        fin:function() { return $('#fin_').val() },
        red:function() { return $('#redes__').val() },
        categoria:function() { return $('#categoria__').val() }
      },
      dataType:'json',
      success:function(data){

        if($("#all_notices").val() == "true"){
          am4core.ready(function() {
            chart_notice = am4core.create("chart_all_notice", am4charts.XYChart);
            // Add data
            chart_notice.data = data.notice;
            // Create axes
            let categoryAxis = chart_notice.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "nombre";
            categoryAxis.renderer.grid.template.disabled = true;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.inside = true;
            categoryAxis.renderer.labels.template.fill = am4core.color("#fff");
            categoryAxis.renderer.labels.template.fontSize = 20;

            let valueAxis = chart_notice.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.grid.template.strokeDasharray = "4,4";
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.min = 0;

            // Do not crop bullets
            chart_notice.maskBullets = false;
            // Remove padding
            chart_notice.paddingBottom = 0;
            chart_notice.logo.height = -15000;
            // Create series
            let series = chart_notice.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "cantidad";
            series.dataFields.categoryX = "nombre";
            series.columns.template.propertyFields.fill = "color";
            series.columns.template.propertyFields.stroke = "color";
            series.columns.template.column.cornerRadiusTopLeft = 15;
            series.columns.template.column.cornerRadiusTopRight = 15;
            series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/b]";
            // Add bullets
            let bullet = series.bullets.push(new am4charts.Bullet());
            let image = bullet.createChild(am4core.Image);
            image.horizontalCenter = "middle";
            image.verticalCenter = "bottom";
            image.dy = 20;
            image.y = am4core.percent(100);
            image.propertyFields.href = "bullet";
            image.tooltipText = series.columns.template.tooltipText;
            image.propertyFields.fill = "color";
            image.filters.push(new am4core.DropShadowFilter());
            $("#all_notices").val("false");
          });


          am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            chart_user = am4core.create("chart_notice", am4charts.PieChart);
            // Add data
            chart_user.data = data.user;
            // Add and configure Series
            let pieSeries = chart_user.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "cantidad";
            pieSeries.dataFields.category = "usuario";
            chart_user.logo.height = -15000;
            // Let's cut a hole in our Pie chart the size of 30% the radius
            chart_user.innerRadius = am4core.percent(30);

            // Put a thick white border around each Slice
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;
            pieSeries.slices.template
              // change the cursor on hover to make it apparent the object can be interacted with
              .cursorOverStyle = [
                {
                  "property": "cursor",
                  "value": "pointer"
                }
              ];

            pieSeries.alignLabels = false;
            pieSeries.labels.template.text = "{value.percent.formatNumber('#.0')}%";
            pieSeries.labels.template.bent = true;
            pieSeries.labels.template.radius = 3;
            pieSeries.labels.template.padding(0,0,0,0);

            pieSeries.ticks.template.disabled = true;

            // Create a base filter effect (as if it's not there) for the hover to return to
            let shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
            shadow.opacity = 0;

            // Create hover state
            let hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

            // Slightly shift the shadow and make it more prominent on hover
            let hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
            hoverShadow.opacity = 0.7;
            hoverShadow.blur = 5;

            // Add a legend
            chart_user.legend = new am4charts.Legend();

          });
        }else{
          chart_notice.data = data.notice;
          chart_user.data = data.user;
        }
      }
    });
  }
}

function recargar_puertas(){
  $.ajax({
    type: "POST",
    url: "reportes/php/back/datos_puertas.php",
    data: {
      opcion:1,
      ini:function() { return $('#ini_p').val() },
      fin:function() { return $('#fin_p').val() },
      oficina:function() { return $('#oficina_visita_').val() },
      puerta:function() { return $('#puerta_').val() },
      no_salido:function() { return $('#no_salido').val() }
    },
    dataType:'json',
    success:function(data){
      chart.data = data;
      }
    });

}

function recargar_noticias(){
  $.ajax({
    type: "POST",
    url: "reportes/php/back/datos_noticias.php",
    data: {
      opcion:1,
      ini:function() { return $('#ini_').val() },
      fin:function() { return $('#fin_').val() },
      red:function() { return $('#redes__').val() },
      categoria:function() { return $('#categoria__').val() }
    },
    dataType:'json',
    success:function(data){
      chart_notice.data = data.notice;
      chart_user.data = data.user;
      }
    });

}
