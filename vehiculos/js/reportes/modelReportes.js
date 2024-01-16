new Vue({
    el: '#appReportes',
    data: {
        title: 'Saldo Total',
        saldoTotal: '',
        saldoTotal50: '',
        saldoTotal100: '',
        total: 100000,
        total50: 100000,
        total100: 100000,
        porcentaje: 0,
        porcentaje50: 0,
        porcentaje100: 0,
        bg: '',
        bg2: '',
        bg3: '',
        text: '',
        text2: '',
        text3: '',
        descripcion: '',
        descripcion2: '',
        descripcion3: '',
        tipoGrafica: 1,
        totales: '',
        ingresos: "",

    },
    mounted() {
        this.renderChart();
        this.obtenerDatos();
    },
    methods: {
        renderChart(id) {
            if (id != undefined) {
                this.tipoGrafica = id
            }
            let thes = this
            axios.get('./vehiculos/php/back/reportes/modelReporte.php', {
                params: {
                    opcion: 2,
                    tipoGrafica: this.tipoGrafica
                }
            }).then(function (response) {
                console.log(response.data);
                // Si la respuesta tiene la propiedad "movimientos5"
                if (thes.tipoGrafica == 1) {
                    var labels = response.data.movimientos5.map(item => item.fecha_procesado);
                    var data = response.data.movimientos5.map(item => item.total);

                    var ctx = document.getElementById('ultimosMovimientos').getContext('2d');
                    ctx.canvas.width = 300; // Cambia el ancho según tus necesidades
                    ctx.canvas.height = 200; // Cambia la altura según tus necesidades

                    var myChart = new Chart(ctx, {
                        type: 'line', // Cambia a 'scatter' si prefieres un gráfico de dispersión
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Ultimos Egresos',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                pointStyle: 'circle', // Cambia esto según el estilo deseado
                                pointRadius: 4, // Cambia el tamaño de los puntos según tus necesidades
                                pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Cambia el color según tus necesidades
                                pointBorderColor: 'rgba(75, 192, 192, 1)', // Cambia el color según tus necesidades
                                pointBorderWidth: 1, // Cambia el grosor del borde según tus necesidades
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                } else if (thes.tipoGrafica == 2) {
                    const dataByDate = [];
                    const dataByDate100 = [];
                    const data = {
                        labels: [],
                        datasets: [
                            {
                                label: 'Uso Cupones 50',
                                data: dataByDate,
                                borderColor: 'red',
                                backgroundColor: 'rgba(255, 0, 0, 0.5)',
                                pointStyle: 'circle',
                                pointRadius: 10,
                                pointHoverRadius: 15,
                                fill: false
                            },
                            {
                                label: 'Uso Cupones 100',
                                data: dataByDate100,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(0, 0, 255, 0.5)',
                                pointStyle: 'circle',
                                pointRadius: 10,
                                pointHoverRadius: 15,
                                fill: false
                            }
                        ]
                    };

                    const config = {
                        type: 'line',
                        data: data,
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Progressive Chart - Fechas de Procesado',
                                }
                            }
                        }
                    };

                    const ctx = document.getElementById('usoCupones').getContext('2d');
                    const myChart = new Chart(ctx, config);

                    // Llamada a Axios para obtener los datos y agregarlos a los arrays
                    axios.get('./vehiculos/php/back/reportes/modelReporte.php', {
                        params: {
                            opcion: 2,
                            tipoGrafica: thes.tipoGrafica
                        }
                    }).then(response => {
                        const newData = response.data.usoCupones;
                        thes.totales = response.data.usoCupones
                        newData.forEach(item => {
                            const date = new Date(item.fecha_procesado);
                            const monthIndex = date.getMonth();
                            const day = date.getDate();
                            const label = `${day}-${monthIndex + 1}`; // Agregar 1 para enero que es 0
                            dataByDate.push(parseFloat(item.monto50));
                            dataByDate100.push(parseFloat(item.monto100));
                            data.labels.push(label);
                        });

                        myChart.update();
                    }).catch(error => {
                        console.error('Error al obtener los datos:', error);
                    });
                } else {
                    const dataByDate = [];
                    const dataByDate100 = [];
                    const data = {
                        datasets: [
                            {
                                label: 'Montos Cupón 50',
                                data: dataByDate,
                                borderColor: 'red',
                                backgroundColor: 'rgba(255, 0, 0, 0.5)',
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                fill: false
                            },
                            {
                                label: 'Montos Cupón 100',
                                data: dataByDate100,
                                borderColor: 'blue',
                                backgroundColor: 'rgba(0, 0, 255, 0.5)',
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                fill: false
                            }
                        ]
                    };

                    const config = {
                        type: 'scatter',
                        data: data,
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Gráfica Comparativa de Ingresos por Cupón'
                                }
                            },
                            scales: {
                                x: {
                                    type: 'time',
                                    time: {
                                        unit: 'day'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Fechas de Ingreso'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Montos'
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 5,
                                    hoverRadius: 7
                                }
                            }
                        }
                    };

                    const ctx = document.getElementById('ingresos').getContext('2d');
                    const myChart = new Chart(ctx, config);

                    // Llamada a Axios para obtener los datos y agregarlos a los arrays
                    axios.get('./vehiculos/php/back/reportes/modelReporte.php', {
                        params: {
                            opcion: 2,
                            tipoGrafica: thes.tipoGrafica
                        }
                    }).then(response => {
                        const ingresos = response.data.ingresosTotalPorCupon;
                        thes.ingresos = response.data.ingresos;

                        ingresos.forEach(item => {
                            const date = new Date(item.ingreso);
                            dataByDate.push({ x: date, y: parseFloat(item.montos50) });
                            dataByDate100.push({ x: date, y: parseFloat(item.montos100) });
                        });

                        myChart.update();
                    }).catch(error => {
                        console.error('Error al obtener los datos:', error);
                    });
                }
            }).catch(function (error) {
                // handle error
                console.log(error);
            }).finally(function () {
                // always executed
            });
        },

        obtenerDatos: function () {
            let thes = this;
            axios.get('./vehiculos/php/back/reportes/modelReporte.php', {
                params: {
                    opcion: 1
                }
            })
                .then(function (response) {
                    console.log(response.data);

                    //Información de saldo total
                    let totalEntero = response.data.saldoTotal[0].totalEntero
                    thes.saldoTotal = response.data.saldoTotal[0].total
                    thes.porcentaje = (totalEntero / thes.total) * 100;

                    //Información de saldo cupones 50
                    let totalEntero50 = response.data.totalSaldoCupones[0].cupones50Entero
                    thes.saldoTotal50 = response.data.totalSaldoCupones[0].cupones50
                    thes.porcentaje50 = (totalEntero50 / thes.total50) * 100;

                    //Información de saldo cupones 100
                    let totalEntero100 = response.data.totalSaldoCupones[0].cupones100Entero
                    thes.saldoTotal100 = response.data.totalSaldoCupones[0].cupones100
                    thes.porcentaje100 = (totalEntero100 / thes.total100) * 100;

                    //Generar Porcentajes
                    thes.datosPorcentaje()
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {
                    // always executed
                });
        },
        datosPorcentaje: function () {
            if (this.porcentaje > 50) {
                this.bg = 'bg-info'
                this.text = 'text-info'
                this.descripcion = 'Saldo suficiente'
            } else if (this.porcentaje <= 50 && this.porcentaje >= 25) {
                this.bg = 'bg-warning'
                this.text = 'text-warning'
                this.descripcion = 'Acabando existencias'
            } else if (this.porcentaje < 25) {
                this.bg = 'bg-danger'
                this.text = 'text-danger'
                this.descripcion = 'Necesario comprar'
            }

            if (this.porcentaje50 > 50) {
                this.bg2 = 'bg-info'
                this.text2 = 'text-info'
                this.descripcion2 = 'Saldo suficiente'
            } else if (this.porcentaje50 <= 50 && this.porcentaje50 >= 25) {
                this.bg2 = 'bg-warning'
                this.text2 = 'text-warning'
                this.descripcion2 = 'Acabando existencias'
            } else if (this.porcentaje50 < 25) {
                this.bg2 = 'bg-danger'
                this.text2 = 'text-danger'
                this.descripcion2 = 'Necesario comprar'
            }

            if (this.porcentaje100 > 50) {
                this.bg3 = 'bg-info'
                this.text3 = 'text-info'
                this.descripcion3 = 'Saldo suficiente'
            } else if (this.porcentaje100 <= 50 && this.porcentaje100 >= 25) {
                this.bg3 = 'bg-warning'
                this.text3 = 'text-warning'
                this.descripcion3 = 'Acabando existencias'
            } else if (this.porcentaje100 < 25) {
                this.bg3 = 'bg-danger'
                this.text3 = 'text-danger'
                this.descripcion3 = 'Necesario comprar'
            }
        }
    }
});
