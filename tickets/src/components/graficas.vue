<template>
    <div class="card">
        <br><br><br>
        <div class="row" id="rowDesa">
            <div class="col-sm">
                <h2>
                    <center>Tickets Pendientes Desarrollo <br> {{ totalDesa }}</center>
                </h2>
                <div id="desarrollo"></div>
            </div>
            <div class="col-sm">
                <h2>
                    <center>Tickets Pendientes Soporte <br> {{ totalSopo }} </center>
                </h2>
                <div id="soporte"></div>
            </div>
        </div>

        <div class="row" id="rowDesa2">
            <div class="col-sm">
                <h2>
                    <center>Tickets Pendientes Radiocomunicaciones <br> {{ totalRadio }} </center>
                </h2>
                <div id="radios"></div>
            </div>

            <div class="col-sm">
                <h2>
                    <center>Tickets del Dia <br> {{ totalDia }} </center>
                </h2>
                <div id="total"></div>
            </div>
        </div>
    </div>

</template>

<style>
#desarrollo {
    width: 100%;
    height: 250px;
}

#soporte {
    width: 100%;
    height: 250px;
}

#radios {
    width: 100%;
    height: 250px;
}

#total {
    width: 100%;
    height: 250px;
}
</style>
<script>
module.exports = {
    data: function () {
        return {
            identificadorIntervaloDeTiempo: "",
            totalDesa: "",
            totalSopo: "",
            totalRadio: "",
            totalDia: "",
        };
    },

    //Para que se carguen al inicio las direcciones
    created: function () {
        this.dibujarGraficas(),
            this.$nextTick(() => {
                this.repetirCadaSegundo();
            });
    },
    methods: {
        dibujarGraficas: function () {
            //inicio
            desarrollo = am4core.ready(function grafica() {
                let requerimientos;
                axios.get('tickets/model/tickets.php', {
                    params: {
                        opcion: 22,
                        depa: 60
                    }
                    //Si todo funciona se imprime el json con las direcciones
                }).then(function (response) {
                    requerimientos = response.data;

                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("desarrollo", am4charts.PieChart);

                    // Add data
                    chart.data = requerimientos

                    // Add and configure Series
                    var pieSeries = chart.series.push(new am4charts.PieSeries());
                    pieSeries.dataFields.value = "tickets";
                    pieSeries.dataFields.category = "estado";
                    pieSeries.slices.template.stroke = am4core.color("#fff");
                    pieSeries.slices.template.strokeOpacity = 1;

                    pieSeries.colors.list = [
                        am4core.color("#E64204"),
                        am4core.color("#E9FA15"),
                    ]

                    // This creates initial animation
                    pieSeries.hiddenState.properties.opacity = 1;
                    pieSeries.hiddenState.properties.endAngle = -90;
                    pieSeries.hiddenState.properties.startAngle = -90;

                    chart.hiddenState.properties.radius = am4core.percent(0);
                }.bind(this)).catch(function (error) {
                    console.log(error);
                });
            }); // end am4core.ready()

            soporte = am4core.ready(function grafica() {
                let requerimientos;
                axios.get('tickets/model/tickets.php', {
                    params: {
                        opcion: 22,
                        depa: 65
                    }
                    //Si todo funciona se imprime el json con las direcciones
                }).then(function (response) {
                    requerimientos = response.data;
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("soporte", am4charts.PieChart);

                    // Add data
                    chart.data = requerimientos

                    // Add and configure Series
                    var pieSeries = chart.series.push(new am4charts.PieSeries());
                    pieSeries.dataFields.value = "tickets";
                    pieSeries.dataFields.category = "estado";
                    pieSeries.slices.template.stroke = am4core.color("#fff");
                    pieSeries.slices.template.strokeOpacity = 1;

                    pieSeries.colors.list = [
                        am4core.color("#E64204"),
                        am4core.color("#E9FA15"),
                    ]

                    // This creates initial animation
                    pieSeries.hiddenState.properties.opacity = 1;
                    pieSeries.hiddenState.properties.endAngle = -90;
                    pieSeries.hiddenState.properties.startAngle = -90;

                    chart.hiddenState.properties.radius = am4core.percent(0);
                }.bind(this)).catch(function (error) {
                    console.log(error);
                });
            }); // end am4core.ready()

            radios = am4core.ready(function grafica() {
                let requerimientos;
                axios.get('tickets/model/tickets.php', {
                    params: {
                        opcion: 22,
                        depa: 90
                    }
                    //Si todo funciona se imprime el json con las direcciones
                }).then(function (response) {
                    requerimientos = response.data;
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("radios", am4charts.PieChart);

                    // Add data
                    chart.data = requerimientos

                    // Add and configure Series
                    var pieSeries = chart.series.push(new am4charts.PieSeries());
                    pieSeries.dataFields.value = "tickets";
                    pieSeries.dataFields.category = "estado";
                    pieSeries.slices.template.stroke = am4core.color("#fff");
                    pieSeries.slices.template.strokeOpacity = 1;

                    pieSeries.colors.list = [
                        am4core.color("#E64204"),
                        am4core.color("#E9FA15"),
                    ]

                    // This creates initial animation
                    pieSeries.hiddenState.properties.opacity = 1;
                    pieSeries.hiddenState.properties.endAngle = -90;
                    pieSeries.hiddenState.properties.startAngle = -90;

                    chart.hiddenState.properties.radius = am4core.percent(0);
                }.bind(this)).catch(function (error) {
                    console.log(error);
                });
            }); // end am4core.ready()

            todos = am4core.ready(function grafica() {
                let requerimientos;
                axios.get('tickets/model/tickets.php', {
                    params: {
                        opcion: 22,
                        depa: 0
                    }
                    //Si todo funciona se imprime el json con las direcciones
                }).then(function (response) {
                    requerimientos = response.data;
                    am4core.useTheme(am4themes_animated);
                    // Themes end

                    // Create chart instance
                    var chart = am4core.create("total", am4charts.PieChart);

                    // Add data
                    chart.data = requerimientos

                    // Add and configure Series
                    var pieSeries = chart.series.push(new am4charts.PieSeries());
                    pieSeries.dataFields.value = "total";
                    pieSeries.dataFields.category = "estado";
                    pieSeries.slices.template.stroke = am4core.color("#fff");
                    pieSeries.slices.template.strokeOpacity = 1;

                    pieSeries.colors.list = [
                        am4core.color("#42D713"),
                        am4core.color("#E9FA15"),
                        am4core.color("#E64204"),
                    ]

                    // This creates initial animation
                    pieSeries.hiddenState.properties.opacity = 1;
                    pieSeries.hiddenState.properties.endAngle = -90;
                    pieSeries.hiddenState.properties.startAngle = -90;

                    chart.hiddenState.properties.radius = am4core.percent(0);
                }.bind(this)).catch(function (error) {
                    console.log(error);
                });
            }); // end am4core.ready()

            axios.get('tickets/model/tickets.php', {
                params: {
                    opcion: 22,
                    depa: 1
                }
                //Si todo funciona se imprime el json con las direcciones
            }).then(function (response) {
                let requerimientos
                requerimientos = response.data;

                total = []
                var reformattedArray = requerimientos.map(function (o) {
                    total[0] = o.desarrollo;
                    total[1] = o.soporte;
                    total[2] = o.radios;
                    total[3] = o.dia;
                });

                this.totalDesa = total[0]
                this.totalSopo = total[1]
                this.totalRadio = total[2]
                this.totalDia = total[3]
            }.bind(this)).catch(function (error) {
                console.log(error);
            });

        },
        repetirCadaSegundo: function () {
            this.identificadorIntervaloDeTiempo = setInterval(this.recargarTablaTickets, 60000);
        },

        recargarTablaTickets: function () {
            this.dibujarGraficas()
        },

    },
};
</script>