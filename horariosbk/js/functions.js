days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];


function get_feriados(date1, date2) {
    var x = $.ajax({
        type: "POST",
        url: "horarios/php/back/boletas/get_feriados.php",
        dataType: 'json',
        data: {
            date1: date1,
            date2: date2
        },
        success: function (data) {
            return data
        }
    }).fail(function (jqXHR, textSttus, errorThrown) {
        alert(errorThrown);
    });

    return x;

}

function update_vacaciones() {

    let dias = parseInt($('#ddias1').val());
    let count = parseInt($('#udias1').val());
    let dia_id = parseInt($('#vac_id').val());
    let vsol = parseFloat($('#vsol1').val());
    let vobs = $('#observaciones1').val();
    let fsol = new Date($('#vsolicitud1').val());
    let sol5 = addDays(fsol, 5);
    fsol.setDate(fsol.getDate() + 1);
    let vini = vcal.getStartDate();
    let vfin = vcal.getEndDate();
    let fini = new Date(vini.getTime());
    let ffin = new Date(vfin.getTime());
    let fpre = new Date($('#vregreso1').val());
    fpre.setDate(fpre.getDate() + 1);
    let fdecs = $('#fdecs1').val();
    let dds = $('#dds').val();
    if (dias < count) {
        Swal.fire({
            type: 'error',
            title: 'Los días solicitados son mayores a los disponibles.',
            showConfirmButton: false,
            timer: 2000
        });
        // } else if (diasua + count > 30 && vini.getFullYear() == today.getFullYear()) {
        //     Swal.fire({
        //         type: 'error',
        //         title: 'No pueden gozarse más de 30 días de vacaciones al año.',
        //         showConfirmButton: false,
        //         timer: 2020
        //     });
    } else if (vini.getTime() >= fsol.getTime() && vini.getTime() <= sol5.getTime()) {
        Swal.fire({
            type: 'error',
            title: 'Debe asignar vacaciones como mínimo 5 días hábiles después de la solicitud',
            showConfirmButton: false,
            timer: 2000
        });
    } else {
        if (fdecs == 1) {
            count = dds;
        }
        Swal.fire({
            title: '<strong></strong>',
            text: "¿Desea generar la solicitud?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
        }).then((result) => {
            // console.log(count);
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/boletas/save_vacaciones.php",
                    dataType: 'json',
                    data: { tipo: 'u', dia_id: dia_id, ddias: dds, udias: count, fsol: formatDate(fsol, "Y-m-d"), fini: formatDate(fini, "Y-m-d"), ffin: formatDate(ffin, "Y-m-d"), fpre: formatDate(fpre, "Y-m-d"), vobs: vobs },
                    success: function (data) {
                        if (data.status == "200") {
                            Swal.fire({
                                type: 'success',
                                title: 'Solicitud generada',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $("#modal-remoto-lg").hide();
                            $('.modal-backdrop').remove();
                            recargarLasBoletas(1);
                            get_periodo_empleado($("#idp").val());
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: data.msg,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                });
            }
        });
    }
}
function modVacaciones() {
    var estado = ($('#vmod').val() === "false");
    $('#vmod').val(estado);
    var vac_id = $('#vac_id').val();
    var fsol = $('#fsol').val();
    var fini = $('#fini').val();
    var ffin = $('#ffin').val();
    var fpre = $('#fpre').val();
    var horas = $('#vhs').val();
    var fobs = $('#fobs').val();
    var horas = 0;
    var dia_id = 0;
    var decs = 0;

    fini = addDays(fini, 1);
    ffin = addDays(ffin, 1);

    // console.log(dias);
    // regreso = fobs + "\n" + "Debe presentarse el día " + days[rgs.getDay()] + " " + rgs.getDate() + " de " + months[rgs.getMonth()] + " de " + rgs.getFullYear();
    // regreso = (horas != 0) ? regreso + " a las " + (7 + horas) + " horas." : regreso + " a primera hora.";
    if (estado == true) {
        fechas = `
                        <div class='col-sm-6' style="z-index:500">
                            <label for='vvini1' class='control-label mr-sm-2'>Inicio:</label>
                            <input type='text' value='${fini}' class='form-control mb-2 mr-sm-2' id='vvini1' disabled>
                        </div>
                        <div class='col-sm-6'>
                            <label for='vfin1' class='control-label mr-sm-2'>Fin:</label>
                            <input type='text' value='${ffin}' class='form-control mb-2 mr-sm-2' id='vvfin1' disabled>
                        </div>
                        <script>
                        vcal = new Litepicker({
                            element: document.getElementById('vvini1'),
                            elementEnd: document.getElementById('vvfin1'),
                            allowRepick: true,
                            format:"DD-MM-YYYY",
                            inlineMode: true,
                            numberOfMonths:2,
                            numberOfColumns: 2,
                            switchingMonths:1,
                            resetButton:true,
                            singleMode: false,
                            splitView:false,
                            minDate: moment(),
                            lang: 'es',
                            lockDaysFilter: (day) => {
                                const d = day.getDay();
                                return [6, 0].includes(d);
                             },
                             tooltipNumber: (day) => {
                                let element1 = document.getElementsByClassName("day-item is-start-date")[0];
                                let element2 = document.getElementsByClassName("day-item is-end-date")[0];
                                let dstart = new Date((element1.attributes['data-time'].value)*1);
                                let dend = new Date((element2.attributes['data-time'].value)*1);
                                return calcBusinessDays(dstart, dend);
                             },
                             tooltipText: {"one":"día","other":"días"},
                             setup: (picker) => {
                                picker.on('selected', (date1, date2) => {
                                    calcfm();
                                });
                                },
                        });
                        </script>
                 `;
        obs = `
                    <div class='col-sm-6'>
                        <label for='vsolicitud1' class='control-label mr-sm-2'>Fecha de Solicitud:</label>
                        <input type='date' value='${fsol}' class='form-control mb-2 mr-sm-2' id='vsolicitud1' disabled>
                    </div>
                        <div class='col-sm-6'>
                        <label for='vregreso1' class='control-label mr-sm-2'>Fecha de Regreso:</label>
                        <input type='date' value='${fpre}' class='form-control mb-2 mr-sm-2' id='vregreso1' disabled>
                    </div>
                    <div class='col-sm-12'>
                        <label for="observaciones1">Observaciones:</label>
                        <textarea class="form-control" id="observaciones1" rows="5" value="${fobs}"></textarea>
                    </div>
                    <div class='col-sm-6'>
                        <input type='hidden' class='form-control mb-2 mr-sm-2' disabled>
                    </div>
                    <div class='col-sm-6'>
                        <label class='control-label mr-sm-2'></label>
                        <input type='hidden' class='form-control mb-2 mr-sm-2'  disabled>
                    </div>
                    <div class='col-sm-12'>
                        <button type='button' class='btn btn-success' onclick='update_vacaciones()' id="gf1"><i class='fa fa-check'></i> Guardar </button>
                    </div>
             `;
        vdias = `
                <div class='col-sm-4'>
                    <label for='ddias1' class='control-label mr-sm-2'>Días Disponibles:</label>
                    <input type='text' value='' class='form-control mb-2 mr-sm-2' id='ddias1' disabled>
                </div>
                    <div class='col-sm-4'>
                        <label for='udias1' class='control-label mr-sm-2'>Días Utilizados:</label>
                        <input type='text' value='' class='form-control mb-2 mr-sm-2' id='udias1' disabled>
                    </div>
                    <div class='col-sm-4'>
                        <label for='pdias1' class='control-label mr-sm-2'>Días Pendietes:</label>
                        <input type='text' value='' class='form-control mb-2 mr-sm-2' id='pdias1' disabled>
                    </div>
                        <input type='hidden' value='${horas}' id='phoras1'>
                        <input type='hidden' value='${dia_id}' id='dia_id1'>
                        <input type='hidden' value='${decs}' id='decdias1'>
                        <input type='hidden' value='0' id='fdecs1'>
                `;
        $("#vfechas1").html(fechas);
        $("#vdias1").html(vdias);
        $("#vobs1").html(obs);
        document.getElementById("observaciones1").value = fobs;
        flatpickr("#vsolicitud1", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d"
        });
        fp = flatpickr("#vregreso1", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d"
        });
    } else {
        fechas = "";
        vdias = "";
        obs = "";
        $("#vfechas1").html(fechas);
        $("#vdias1").html(vdias);
        $("#vobs1").html(obs);
    }

    vcal.setDateRange(fini, ffin);
    let x = document.getElementsByClassName("litepicker")[0];
    x.style["margin-top"] = "20px";
    x.style["margin-bottom"] = "20px";
    x.style["left"] = "30%";
}
async function calcfm() {
    let decdias = parseFloat($('#dds').val());
    let dias = parseInt($('#vds').val());
    let horas = parseInt($('#vhs').val());
    let date1 = vcal.getStartDate()['dateInstance'];
    let date2 = vcal.getEndDate()['dateInstance'];
    let rgs = addDays(date2, 1);
    let count = 1;
    var x = await get_feriados(formatDate(date1), formatDate(date2));
    let df1 = "Para el conteo de las presentes vacaciones, no se tomará en cuenta el día "
    let df2 = "por ser día de asueto según la Ley de Servicio Civil, 'Artículo 69. Días de Asueto.'\n"
    date1.setDate(date1.getDate() + 1);
    date2.setDate(date2.getDate() + 1);
    if (date1 > date2) {
        date2 = new Date(vcal.getStartDate());
        date1 = new Date(vcal.getEndDate());
        rgs = addDays(date2, 1);
        count = 1;
        while (formatDate(date1) != formatDate(date2)) {
            if (date1.getDay() === 0 || date1.getDay() === 6) {
                date1.setDate(date1.getDate() + 1);
            } else {
                date1.setDate(date1.getDate() + 1);
                count++;
            }
        }
    } else {
        count = 1;
        while (formatDate(date1) != formatDate(date2)) {
            if (date1.getDay() === 0 || date1.getDay() === 6) {
                date1.setDate(date1.getDate() + 1);
            } else {
                date1.setDate(date1.getDate() + 1);
                count++;
            }
        }
    }
    if (x.length > 0) {
        (x.forEach(element => {
            let d = new Date(element.fecha_inicio);
            let f = d.getDate() + "/" + d.getMonth() + "/" + d.getFullYear();
            df1 = df1 + f + ", ";
        }));
        df1 = df1 + df2;
        count = count - x.length;
    } else { df1 = ""; }
    let pendientes = (dias - count);
    let disponibles = dias + " días " + horas + " horas     (" + decdias + ")";
    let utilizados = count;
    let regreso = df1 + "Debe presentarse el día " + days[rgs.getDay()] + " " + rgs.getDate() + " de " + months[rgs.getMonth()] + " de " + rgs.getFullYear();
    if (pendientes != 0 | horas == 0) {
        pendientes = pendientes + " días " + horas + " horas";
        utilizados = count + " días 0 horas";
        regreso = regreso + " a primera hora.";
        document.getElementById("fdecs1").value = 0;
    } else {
        pendientes = pendientes + " días 0 horas";
        utilizados = count + " días " + horas + " horas";
        regreso = regreso + " a las " + (7 + horas) + " horas.";
        document.getElementById("fdecs1").value = 1;
    }
    document.getElementById("observaciones1").value = regreso;
    document.getElementById("pdias1").value = pendientes;
    document.getElementById("ddias1").value = disponibles;
    document.getElementById("udias1").value = utilizados;
    fp.setDate(rgs);
}
function eliminar_cambio(id_control, flag_fijo) {
    Swal.fire({
        title: '<strong></strong>',
        text: "¿Desea eliminar el registro?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Eliminar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "horarios/php/back/empleados/eliminar_cambio_horario.php",
                data: {
                    id_control: id_control,
                    flag_fijo: flag_fijo
                },
                success: function (data) {
                    Swal.fire({
                        type: 'success',
                        title: 'Registro eliminado',
                        showConfirmButton: false,
                        timer: 1100,
                    });
                    refresh_cambio();
                }
            }).fail(function (jqXHR, textSttus, errorThrown) {
                alert(errorThrown);
            });
        }
    });
}
function save_horario() {
    let id_persona = $('#id_persona').val();
    let data = document.getElementsByName('weekdays');
    let wks = [];
    let inicio = $('#iniciofecha').val();
    let fin = $('#finfecha').val();
    let entrada = $('#ientrada').val();
    let salida = $('#isalida').val();
    let fijo = 0;
    let turno = document.getElementById('turno').checked;
    let sturno = document.getElementById('sturno').checked;
    for (var i = 0; i < data.length; i++) {
        if (data[i].checked) {
            wks.push(data[i].id);
        }
    }
    if (document.getElementById('fijo').checked) {
        inicio = $('#iniciofecha').val();
        fin = $('#finfecha').val();
        entrada = $('#hfijos').val();
        salida = $('#hfijos').val();
        fijo = 1;
    }
    if (fijo == 1 & $('#hfijos').val() == 0) {
        Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un horario fijo',
            showConfirmButton: false,
            timer: 1800
        });
    } else if ((inicio > fin) && fijo == 0) {
        // console.log(fijo);
        Swal.fire({
            type: 'error',
            title: 'La fecha final es mayor a la fecha de inicio',
            showConfirmButton: false,
            timer: 1800
        });
    } else if (entrada > salida) {
        Swal.fire({
            type: 'error',
            title: 'La hora de salida es antes que la hora de entrada',
            showConfirmButton: false,
            timer: 1800
        });
        // } else if (wks.length == 0) {
        //     Swal.fire({
        //         type: 'error',
        //         title: 'Debe seleccionar al menos un día para el horario',
        //         showConfirmButton: false,
        //         timer: 1800
        //     });
    } else if ((inicio <= fin) || fijo == 1) {
        Swal.fire({
            title: '<strong></strong>',
            text: "¿Desea guardar el horario?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Ingresar!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/empleados/save_horario.php",
                    dataType: 'json',
                    data: {
                        inicio: inicio,
                        fin: fin,
                        entrada: entrada,
                        salida: salida,
                        fijo: fijo,
                        wks: wks,
                        id_persona: id_persona,
                        turno: turno,
                        sturno: sturno
                    },
                    success: function (data) {
                        if (data.status == "200") {
                            $('#modal-remoto-lg').modal('hide');
                            Swal.fire({
                                type: 'success',
                                title: 'Registro guardado',
                                showConfirmButton: false,
                                timer: 1100
                            });
                        } else {
                            Swal.fire({
                                type: 'Error',
                                title: data.msg,
                                showConfirmButton: false,
                                timer: 1100
                            });
                        }
                    }
                });
            }
        });
    }
}
function updateDate(value) {
    if (value) {
        let array_values = value.split("-");
        let m = (array_values[2].length == 1 ? "0" + array_values[2] : array_values[2]);
        let d = (array_values[1].length == 1 ? "0" + array_values[1] : array_values[1]);
        let date = `${new Date().getFullYear()}-${m}-${d}`;
        date = date + "T00:00";

        let fechas = "";
        if (array_values[3] == 1) {
            fechas = `<div class='col-sm-5'>
                            <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
                            <input type='datetime-local' value='${date}' class='form-control mb-2 mr-sm-2' id='inicio'>
                        </div>
                        <div class='col-sm-5'>
                            <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
                            <input type='datetime-local' value='${date}' class='form-control mb-2 mr-sm-2' id='fin'>
                        </div>`;
            $("#fechas").html(fechas);
        } else if (array_values[3] == 2) {
            if (array_values[4] == 3) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/empleados/get_permisos.php",
                    dataType: 'html',
                    success: function (permisos) {
                        $("#fechas").html(permisos);
                    }
                });
            } else if (array_values[4] == 4) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/empleados/empleado_vacaciones.php",
                    dataType: 'html',
                    success: function (permisos) {
                        $("#fechas").html(permisos);
                    }
                });
            } else {
                fechas = `<div class='col-sm-5'>
                                <label for='inicio' class='control-label mr-sm-2'>Inicio:</label>
                                <input type='datetime-local' value='${date}' class='form-control mb-2 mr-sm-2' id='inicio'>
                            </div>
                            <div class='col-sm-5'>
                                <label for='fecha' class='control-label mr-sm-2'>Fin:</label>
                                <input type='datetime-local' value='${date}' class='form-control mb-2 mr-sm-2' id='fin'>
                            </div>`;
            }
            $("#fechas").html(fechas);
        }
    }
}
function reload_table() {
    let year = 2021;
    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_descansos.php",
        dataType: 'html',
        data: { year },
        success: function (data) {
            $("#body_table").html(data);
        }
    });
}
function reload_permisos() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_tabla_permisos.php",
        dataType: 'html',
        data: { month: mm, year: yyyy },
        success: function (data) {
            $("#permisos_table").html(data);
        }
    });
}

function min_Date(month, year) {
    var month = month; // January
    var today = new Date(year, month, 0);
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = dd + '/' + mm + '/' + yyyy;
    return today;

}
function max_Date(date) {
    var today = new Date(date);
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    today = mm + '/' + dd + '/' + yyyy;
    // console.log(today);
    return today;
}
function formatDate3(date) {
    var today = new Date(date);
    var fdate = days[today.getDay()] + " " + today.getDate() + " de " + months[today.getMonth()] + " de " + today.getFullYear();
    return fdate;
}
function formatDate1(date) {
    var today = new Date(date);
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = dd + '/' + mm + '/' + yyyy;
    return today;
}
function formatDate2(date) {
    var today = new Date(date);
    return today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
}
function formatDate(date) {
    var today = new Date(date);
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    return today;
}
function formatDateD(date) {
    var today = String(date);
    var dd = today.substring(0, 2);
    var mm = today.substring(3, 5);
    var yyyy = today.substring(6);

    today = yyyy + '-' + mm + '-' + dd;
    return today;
}

function addDays(date, days) {
    var result = new Date(date);


    while (days != 0) {
        if (result.getDay() == 5 | result.getDay() == 6) {
            result.setDate(result.getDate() + 1);
        } else {
            result.setDate(result.getDate() + 1);
            days--;
        }
    }
    return result;
}
function ver_detalle() {
    // console.log("1");
}

function cargar_menu_impresion(id_persona) {
    $.ajax({
        type: "POST",
        url: "horarios/php/front/boletas/menu_impresion.php",
        //dataType: 'html',
        data: {
            id_persona: id_persona
        },
        beforeSend: function () {
            $('#menu' + id_persona).html('Cargando');
        },
        success: function (data) {
            $('#menu' + id_persona).html(data);

        }
    });
}

function calcBusinessDays(startDate, endDate) {
    if (startDate > endDate) {
        x = endDate;
        endDate = startDate;
        startDate = x;
    }
    var day = moment(startDate);
    var businessDays = 0;

    while (day.isSameOrBefore(endDate, 'day')) {
        if (day.day() != 0 && day.day() != 6) businessDays++;
        day.add(1, 'd');
    }
    return businessDays;
}
function checkVacaciones(checkbox, dia_id, dias, horas, decs) {

    let trows = ($("#tt")[0].value);
    let tcheck = checkbox.value
    for (let i = 1; i <= trows; i++) {
        let x = $("#cnt" + i)[0].value;
        let y = document.getElementById(x);
        if (x != tcheck) {
            y.checked = false;
        }
    }
    var today = new Date();
    var regreso = "";
    var rgs = addDays(today, 5 + dias);
    var start = addDays(today, 5);
    var end = addDays(today, 4 + dias);
    regreso = "Debe presentarse el día " + days[rgs.getDay()] + " " + rgs.getDate() + " de " + months[rgs.getMonth()] + " de " + rgs.getFullYear();
    regreso = (horas != 0) ? regreso + " a las " + (7 + horas) + " horas." : regreso + " a primera hora.";

    if (checkbox.checked) {
        fechas = `<div class='col-sm-6' style="z-index:500">
                            <label for='vvini' class='control-label mr-sm-2'>Inicio:</label>
                            <input type='text' value='' class='form-control mb-2 mr-sm-2' id='vvini' disabled>
                        </div>
                        <div class='col-sm-6'>
                            <label for='vfin' class='control-label mr-sm-2'>Fin:</label>
                            <input type='text' value='' class='form-control mb-2 mr-sm-2' id='vvfin' disabled>
                        </div>
                        <script>
                        vcal = new Litepicker({
                            element: document.getElementById('vvini'),
                            elementEnd: document.getElementById('vvfin'),
                            allowRepick: true,
                            format:"DD-MM-YYYY",
                            inlineMode: true,
                            numberOfMonths:2,
                            numberOfColumns: 2,
                            switchingMonths:1,
                            resetButton:true,
                            singleMode: false,
                            splitView:false,
                            minDate: moment(),
                            lang: 'es',
                            lockDaysFilter: (day) => {
                                const d = day.getDay();
                                return [6, 0].includes(d);
                             },
                             tooltipNumber: (day) => {
                                let element1 = document.getElementsByClassName("day-item is-start-date")[0];
                                let element2 = document.getElementsByClassName("day-item is-end-date")[0];
                                let dstart = new Date((element1.attributes['data-time'].value)*1);
                                let dend = new Date((element2.attributes['data-time'].value)*1);
                                return calcBusinessDays(dstart, dend);
                             },
                             tooltipText: {"one":"día","other":"días"},
                             setup: (picker) => {
                                picker.on('selected', (date1, date2) => {
                                    calcf();
                                });
                                },

                        });
                        </script>
                        `;
        obs = `<div class='col-sm-6'>
                <label for='vsolicitud' class='control-label mr-sm-2'>Fecha de Solicitud:</label>
                <input type='date' value='${formatDate(today)}' class='form-control mb-2 mr-sm-2' id='vsolicitud' disabled>
            </div>
                <div class='col-sm-6'>
                <label for='vregreso' class='control-label mr-sm-2'>Fecha de Regreso:</label>
                <input type='date' value='${formatDate(rgs)}' class='form-control mb-2 mr-sm-2' id='vregreso' disabled>
            </div>
            <div class='col-sm-12'>
                <label for="observaciones">Observaciones:</label>
                <textarea class="form-control" id="observaciones" rows="5" value="${regreso}"></textarea>
            </div>
            <div class='col-sm-6'>
                <input type='hidden' class='form-control mb-2 mr-sm-2' disabled>
            </div>
            <div class='col-sm-6'>
                <label class='control-label mr-sm-2'></label>
                <input type='hidden' class='form-control mb-2 mr-sm-2'  disabled>
            </div>
            <div class='col-sm-12'>
            <button type='button' class='btn btn-success' onclick='save_vacaciones()' id="gf"><i class='fa fa-check'></i> Guardar </button>
            </div>`;
        dias = `<div class='col-sm-4'>
                    <label for='ddias' class='control-label mr-sm-2'>Días Disponibles:</label>
                    <input type='text' value='${dias}' class='form-control mb-2 mr-sm-2' id='ddias' disabled>
                </div>
                    <div class='col-sm-4'>
                        <label for='udias' class='control-label mr-sm-2'>Días Utilizados:</label>
                        <input type='text' value='${dias}' class='form-control mb-2 mr-sm-2' id='udias' disabled>
                    </div>
                    <div class='col-sm-4'>
                        <label for='pdias' class='control-label mr-sm-2'>Días Pendietes:</label>
                        <input type='text' value='0 días 0 horas' class='form-control mb-2 mr-sm-2' id='pdias' disabled>
                    </div>
                        <input type='hidden' value='${horas}' id='phoras'>
                        <input type='hidden' value='${dia_id}' id='dia_id'>
                        <input type='hidden' value='${decs}' id='decdias'>
                        <input type='hidden' value='0' id='fdecs'>
                    `;
        $("#vfechas").html(fechas);
        $("#vdias").html(dias);
        $("#vobs").html(obs);
        document.getElementById("observaciones").value = regreso;
        flatpickr("#vsolicitud", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d"
        });
        fp = flatpickr("#vregreso", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d"
        });

    } else {
        $("#vfechas").html("");
        $("#vdias").html("");
        $("#vobs").html("");
        checkbox.checked = false;
    }

    vcal.setDateRange(start, end);
    let x = document.getElementsByClassName("litepicker")[0];
    // console.log(x.style);
    x.style["margin-top"] = "20px";
    x.style["margin-bottom"] = "20px";
    x.style["left"] = "49%";
}
function save_vacaciones() {
    let dias = parseInt($('#ddias').val());
    let count = parseInt($('#udias').val());
    let diasua = parseInt($('#hdiasua').val());
    let dia_id = parseInt($('#dia_id').val());
    let vobs = $('#observaciones').val();
    let decs = $('#decdias').val();
    let fdecs = $('#fdecs').val();
    let fsol = new Date($('#vsolicitud').val());
    let sol5 = addDays(fsol, 5);
    fsol.setDate(fsol.getDate() + 1);
    let vini = vcal.getStartDate();
    let vfin = vcal.getEndDate();
    let fini = new Date(vini.getTime());
    let ffin = new Date(vfin.getTime());
    let fpre = new Date($('#vregreso').val());
    fpre.setDate(fpre.getDate() + 1);
    let today = new Date();
    if (dias < count) {
        Swal.fire({
            type: 'error',
            title: 'Los días solicitados son mayores a los disponibles.',
            showConfirmButton: false,
            timer: 2000
        });
    } else if (diasua + count > 30 && vini.getFullYear() == today.getFullYear()) {
        Swal.fire({
            type: 'error',
            title: 'No pueden gozarse más de 30 días de vacaciones al año.',
            showConfirmButton: false,
            timer: 2020
        });
    } else if (vini.getTime() >= fsol.getTime() && vini.getTime() <= sol5.getTime()) {
        Swal.fire({
            type: 'error',
            title: 'Debe asignar vacaciones como mínimo 5 días hábiles después de la solicitud',
            showConfirmButton: false,
            timer: 2000
        });
    } else {
        if (fdecs == 1) {
            count = decs;
        }
        Swal.fire({
            title: '<strong></strong>',
            text: "¿Desea generar la solicitud?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Generar!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/boletas/save_vacaciones.php",
                    dataType: 'json',
                    data: { tipo: 'c', dia_id: dia_id, ddias: decs, udias: count, fsol: formatDate(fsol, "Y-m-d"), fini: formatDate(fini, "Y-m-d"), ffin: formatDate(ffin, "Y-m-d"), fpre: formatDate(fpre, "Y-m-d"), vobs: vobs },
                    success: function (data) {
                        if (data.status == "200") {
                            Swal.fire({
                                type: 'success',
                                title: 'Solicitud generada',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            $("#modal-remoto-lg").hide();
                            $('.modal-backdrop').remove();
                            recargar_solicitudes(1);
                            get_periodo_empleado($("#idp").val());
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: data.msg,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    }
                });
            }
        });
    }
}

async function calcf() {
    let dias = parseInt($('#ddias').val());
    let horas = parseInt($('#phoras').val());
    let decdias = $('#decdias').val();
    let date1 = vcal.getStartDate()['dateInstance'];
    let date2 = vcal.getEndDate()['dateInstance'];
    let rgs = addDays(date2, 1);
    let count = 1;
    var x = await get_feriados(formatDate(date1), formatDate(date2));
    let df1 = "Para el conteo de las presentes vacaciones, no se tomará en cuenta el día "
    let df2 = "por ser día de asueto según la Ley de Servicio Civil, 'Artículo 69. Días de Asueto.'\n"
    date1.setDate(date1.getDate() + 1);
    date2.setDate(date2.getDate() + 1);
    if (date1 > date2) {
        date2 = new Date(vcal.getStartDate());
        date1 = new Date(vcal.getEndDate());
        rgs = addDays(date2, 1);
        while (formatDate(date1) != formatDate(date2)) {
            if (date1.getDay() === 0 || date1.getDay() === 6) {
                date1.setDate(date1.getDate() + 1);
            } else {
                date1.setDate(date1.getDate() + 1);
                count++;
            }
        }
    } else {

        while (formatDate(date1) != formatDate(date2)) {
            if (date1.getDay() === 0 || date1.getDay() === 6) {
                date1.setDate(date1.getDate() + 1);
            } else {
                date1.setDate(date1.getDate() + 1);
                count++;
            }
        }
    }


    if (x.length > 0) {
        (x.forEach(element => {
            let d = new Date(element.fecha_inicio);
            let f = d.getDate() + "/" + d.getMonth() + "/" + d.getFullYear();
            df1 = df1 + f + ", ";
        }));
        df1 = df1 + df2;
        count = count - x.length;
    } else { df1 = ""; }
    let pendientes = (dias - count);
    let disponibles = dias + " días " + horas + " horas     (" + decdias + ")";
    let utilizados = count;
    let regreso = df1 + "Debe presentarse el día " + days[rgs.getDay()] + " " + rgs.getDate() + " de " + months[rgs.getMonth()] + " de " + rgs.getFullYear();
    if (pendientes != 0 | horas == 0) {
        pendientes = pendientes + " días " + horas + " horas";
        utilizados = count + " días 0 horas";
        regreso = regreso + " a primera hora.";
        document.getElementById("fdecs").value = 0;
    } else {
        pendientes = pendientes + " días 0 horas";
        utilizados = count + " días " + horas + " horas";
        regreso = regreso + " a las " + (7 + horas) + " horas.";
        document.getElementById("fdecs").value = 1;
    }
    document.getElementById("observaciones").value = regreso;
    document.getElementById("pdias").value = pendientes;
    document.getElementById("ddias").value = disponibles;
    document.getElementById("udias").value = utilizados;
    fp.setDate(rgs);
}
// function calcularFechas() {
//     let dias = parseInt($('#ddias').val());
//     let horas = parseInt($('#phoras').val());
//     let date1 = new Date($('#vinicio').val());
//     let date2 = new Date($('#vfin').val());

//     date1.setDate(date1.getDate() + 1);
//     date2.setDate(date2.getDate() + 1);
//     let count = 0;
//     vcal.setDateRange(date1, date2);
//     if (date1 > date2) {
//         Swal.fire({
//             type: 'error',
//             title: 'Fecha de inicio es mayor a la final',
//             showConfirmButton: false,
//             timer: 2000
//         });
//         date1.setDate(date2.getDate());
//         date2.setDate(date1.getDate());
//     } else {
//         count = 1;
//         while (formatDate(date1) != formatDate(date2)) {
//             if (date1.getDay() === 0 || date1.getDay() === 6) {
//                 date1.setDate(date1.getDate() + 1);
//             } else {
//                 date1.setDate(date1.getDate() + 1);
//                 count++;
//             }
//         }
//     }
//     let pendientes = (dias - count);
//     if (dias < count) {
//         Swal.fire({
//             type: 'error',
//             title: 'Los días utilizados son mayor a los disponibles',
//             showConfirmButton: false,
//             timer: 2000
//         });
//     }
//     document.getElementById("ddias").value = dias;
//     document.getElementById("udias").value = count;

//     let rgs = addDays(date2, 1);
//     let regreso = "Debe presentarse el día " + days[rgs.getDay()] + " " + rgs.getDate() + " de " + months[rgs.getMonth()] + " de " + rgs.getFullYear();
//     if (pendientes != 0 | horas == 0) {
//         pendientes = pendientes + " días " + horas + " horas"
//         regreso = regreso + " a primera hora.";
//     } else {
//         pendientes = pendientes + " días 0 horas"
//         regreso = regreso + " a las " + (7 + horas) + " horas.";
//     }

//     document.getElementById("observaciones").value = regreso;
//     document.getElementById("pdias").value = pendientes;
//     document.getElementById("vregreso").value = formatDate(rgs);
//     fp.setDate(formatDate(addDays(date2, 1)));
// }
function check_turno(checkbox) {
    if (checkbox.checked) {
        document.getElementById('hfijos').disabled = false;
        document.getElementById('ientrada').disabled = true;
        document.getElementById('isalida').disabled = true;
        document.getElementById("ientrada").value = "";
        document.getElementById("isalida").value = "";
        document.getElementById("hfijos").value = 0;
        document.getElementById('hfijos').disabled = true;
        document.getElementById('hfijos').value = "";
        document.getElementById("sturno").checked = false;
        document.getElementById("fijo").checked = false;
        document.getElementById('finfecha').disabled = false;
        document.getElementById("iniciofecha").value = $('#iniciofecha').val();
        document.getElementById("finfecha").value = $('#finfecha').val();
    } else {
        document.getElementById('hfijos').disabled = true;
        document.getElementById('fijo').disabled = false;
        checkbox.checked = false;
        document.getElementById('ientrada').disabled = false;
        document.getElementById('isalida').disabled = false;
        document.getElementById("ientrada").value = "07:00";
        document.getElementById("isalida").value = "15:00";
        document.getElementById('hfijos').value = "";
    }
}
function check_sturno(checkbox) {
    if (checkbox.checked) {
        document.getElementById('hfijos').disabled = false;
        document.getElementById('ientrada').disabled = true;
        document.getElementById('isalida').disabled = true;
        document.getElementById("ientrada").value = "";
        document.getElementById("isalida").value = "";
        document.getElementById("hfijos").value = 0;
        document.getElementById('hfijos').disabled = true;
        document.getElementById("turno").checked = false;
        document.getElementById('hfijos').value = "";
        document.getElementById("fijo").checked = false;
        document.getElementById('finfecha').disabled = false;
        document.getElementById("iniciofecha").value = $('#iniciofecha').val();
        document.getElementById("finfecha").value = $('#finfecha').val();
    } else {
        document.getElementById('hfijos').disabled = true;
        document.getElementById('fijo').disabled = false;
        checkbox.checked = false;
        document.getElementById('ientrada').disabled = false;
        document.getElementById('isalida').disabled = false;
        document.getElementById("ientrada").value = "07:00";
        document.getElementById("isalida").value = "15:00";
        document.getElementById('hfijos').value = "";
    }
}
function horario_fijo(checkbox) {
    let data = document.getElementsByName('weekdays');
    if (checkbox.checked) {
        document.getElementById('hfijos').disabled = false;
        document.getElementById('ientrada').disabled = true;
        document.getElementById('isalida').disabled = true;
        document.getElementById("finfecha").value = "";
        document.getElementById('finfecha').disabled = true;
        document.getElementById("hfijos").value = 0;
        document.getElementById("turno").checked = false;
        document.getElementById("sturno").checked = false;

        for (var i = 0; i < data.length; i++) {
            id = ("l" + String(i + 1));
            document.getElementById(data[i].id).disabled = true;
            document.getElementById(data[i].id).checked = false;
            document.getElementById(id).className = "btn btn-info";
        }
        // for (var i = 0; i < 5; i++) {
        //     id = ("l" + String(i + 1));
        //     document.getElementById(data[i].id).checked = true;
        //     document.getElementById(id).className = "btn btn-info active";
        // }
    } else {
        document.getElementById('hfijos').disabled = true;
        document.getElementById('ientrada').disabled = false;
        document.getElementById('isalida').disabled = false;
        document.getElementById("finfecha").value = $('#iniciofecha').val();
        document.getElementById('finfecha').disabled = false;
        document.getElementById("ientrada").value = "07:00";
        document.getElementById("isalida").value = "15:00";
        document.getElementById('hfijos').value = "";
        for (var i = 0; i < data.length; i++) {
            id = ("l" + String(i + 1));
            document.getElementById(data[i].id).disabled = false;
            document.getElementById(data[i].id).checked = false;
            document.getElementById(id).className = "btn btn-info";
        }
    }
}
function select_horario_fijo() {
    let data = $('#hfijos option:selected').text().split(' - ');
    document.getElementById("ientrada").value = data[0];
    document.getElementById("isalida").value = data[1];
}
function save_fechas(x, tipo) {
    let data = [];
    let inicio, fin, motivo, persona, opcion, autoriza;
    let datos = [];
    // let goce = document.getElementById("goce").checked;
    let goce = true;
    let nro_boleta;
    let aut;
    if (x == 1) {
        data = $('#motivo').val().split("-");
        persona = $('#select_employee option:selected').val();
        autoriza = $('#select_employee1 option:selected').val();
    } else {
        data[0] = "47";
        data[1] = "";
        data[2] = "";
        data[3] = "2";
        data[4] = "3";
        persona = $('#select_employee').val();
        autoriza = $('#select_employee1 option:selected').val();
    }
    if (tipo == 1) {
        nro_boleta = $('#nro_boleta').val();
        aut = document.getElementById("aut").checked;
    } else if (tipo == 2) {
        nro_boleta = 0;
        aut = false;
        autoriza = 0;
    }
    aut = (aut == true) ? 5 : 1;
    motivo = $('#tipo option:selected').val();
    obs = $('#observaciones').val();
    if (data[3] == "1") {
        inicio = $('#inicio').val();
        fin = $('#inicio').val();
    } else if (data[3] == "2") {
        inicio = $('#inicio').val();
        fin = $('#fin').val();
    }
    motivo = $("#tipo option:selected").val();
    if (data[0] == 38 || data[0] == 39 || data[0] == 40) {
        datos.push(persona);
        datos.push(motivo);
        datos.push(inicio);
        datos.push(fin);
        datos.push(obs);
        datos.push(autoriza);
        datos.push(goce);
        datos.push(nro_boleta)
        opcion = 2;
    } else if (data[0] == 47) {
        datos.push(persona);
        datos.push(motivo);
        datos.push(inicio);
        datos.push(fin);
        datos.push(obs);
        datos.push(autoriza);
        datos.push(goce);
        datos.push(nro_boleta);
        opcion = 2;
    } else {
        datos.push(data[0]);
        datos.push(inicio);
        datos.push(fin);
        datos.push(motivo);
        opcion = 1;
    }
    if (inicio == "" || fin == "") {
        Swal.fire({
            type: 'error',
            title: 'Debe elegir una fecha de inicio y fin',
            showConfirmButton: false,
            timer: 3000
        });
    } else if (isNaN(nro_boleta)) {
        Swal.fire({
            type: 'error',
            title: 'Debe ingresar un número de boleta',
            showConfirmButton: false,
            timer: 3000
        });
    } else if (isNaN(motivo)) {
        Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un motivo de ausencia',
            showConfirmButton: false,
            timer: 3000
        });
    } else if (isNaN(persona) || isNaN(autoriza)) {
        Swal.fire({
            type: 'error',
            title: 'Debe seleccionar un empleado',
            showConfirmButton: false,
            timer: 3000
        });
    } else if (new Date(inicio) <= new Date(fin)) {
        Swal.fire({
            title: '<strong></strong>',
            text: "¿Desea guardar el registro?",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Ingresar!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/empleados/save_descanso.php",
                    dataType: 'json',
                    data: { data: datos, opcion: opcion, aut: aut },
                    success: function (data) {
                        if (data.status == "200") {
                            Swal.fire({
                                type: 'success',
                                title: 'Registro guardado',
                                showConfirmButton: false,
                                timer: 1100
                            });
                            $.ajax({
                                type: "POST",
                                url: "horarios/php/back/empleados/get_listado_descansos.php",
                                dataType: 'html',
                                success: function (select) {
                                    $("#tipo").html(select);
                                    $("#fechas").html("");
                                }
                            });
                            $('#modal-remoto-lg').hide();
                            $('.modal-backdrop').remove();
                        } else {
                            Swal.fire({
                                type: 'error',
                                title: data.msg,
                                showConfirmButton: false,
                                timer: 1100
                            });
                        }
                    }
                });
            }
        });
    } else {
        Swal.fire({
            type: 'error',
            title: 'Fecha de inicio es mayor a la final',
            showConfirmButton: false,
            timer: 3000
        });
    }
}
function get_users(id_permiso) {
    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_empleado_permiso.php",
        dataType: 'html',
        data: { id_tipo: id_permiso },
        success: function (personas) {
            $("#listado_personas").html(personas);
        }
    });
}
function get_periodo_empleado(id_persona) {
    $("#vfechas").html("");
    $("#vdias").html("");
    $("#vobs").html("");
    document.getElementById('tb_empleados').removeAttribute("hidden");
    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_empleado_vacaciones.php",
        dataType: 'html',
        data: { id_persona },
        success: function (data) {
            $("#vacaciones_table").html(data);
            document.getElementById("diasua").value = document.getElementById('hdiasua').value;
        }
    });
}
function refresh_periodo_empleado() {
    $("#vfechas").html("");
    $("#vdias").html("");
    $("#vobs").html("");
    $('#empleado_v').val(null).trigger('change');
    document.getElementById('tb_empleados').setAttribute("hidden", true);
    document.getElementById("diasua").value = "";
}
function save_boleta() {
    jQuery('.validation_nueva_boleta').validate({
        ignore: [],
        errorClass: 'help-block animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function (error, e) {
            jQuery(e).parents('.form-group > div').append(error);
        },
        highlight: function (e) {
            var elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error').addClass('has-error');
            elem.closest('.help-block').remove();
        },
        success: function (e) {
            var elem = jQuery(e);
            elem.closest('.form-group').removeClass('has-error');
            elem.closest('.help-block').remove();
        },
        submitHandler: function (form) {
            let empleado = $('#empleado').val();
            let id_persona = $('#id_persona').val();
            let estado = 1;
            let observaciones = $('#observaciones').val();
            let inicio = $('#inicio').val();
            let fin = $('#fin').val();
            let tipo = $('#tipo').val();
            //   let municipio = $("#municipio option:selected").val();
            //   let url= $('#url_noticia').val();
            //   let categoria= $('#categoria').val();
            // console.log(1);
            let data = { id_persona, tipo, observaciones, inicio, fin, estado };
            if (tipo == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Debe elegir un tipo de permiso',
                    showConfirmButton: false,
                    timer: 1700
                });
            } else if (new Date(inicio) > new Date(fin)) {
                Swal.fire({
                    type: 'error',
                    title: 'Fecha de inicio es mayor a la final',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                Swal.fire({
                    title: '<strong></strong>',
                    text: "¿Desea generar la solicitud?",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: '¡Si, Ingresar!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "horarios/php/back/boletas/save_boleta.php",
                            data: data,
                            success: function (data) {
                                $('#modal-remoto-lg').modal('hide');
                                Swal.fire({
                                    type: 'success',
                                    title: 'Solicitud generada',
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        }).fail(function (jqXHR, textSttus, errorThrown) {
                            alert(errorThrown);
                        });
                    }
                });
            }
        }
    });
}


// function insertData(id, creado_por){

//     jQuery('.js-validation-iggss').validate({
//         ignore: [],
//         errorClass: 'help-block animated fadeInDown',
//         errorElement: 'div',
//         errorPlacement: function(error, e) {
//             jQuery(e).parents('.form-group > div').append(error);
//         },
//         highlight: function(e) {
//             var elem = jQuery(e);

//             elem.closest('.form-group').removeClass('has-error').addClass('has-error');
//             elem.closest('.help-block').remove();
//         },
//         success: function(e) {
//             var elem = jQuery(e);

//             elem.closest('.form-group').removeClass('has-error');
//             elem.closest('.help-block').remove();
//         },
//         submitHandler: function(form){
//             //regformhash(form,form.password,form.confirmpwd);
//             codigo=id;
//             vid=$('#codigo').val();
//             user_id=creado_por;
//             from=$('#from').val();
//             to=$('#to').val();
//             resolucion=$('#resolucion').val();
//             dia=$('#dia').val();
//             sus_desc=$('#sus_desc').val();


//             swal({
//             title: '<strong>¿Desea generar este Permiso?</strong>',
//             text: "",
//             type: 'info',
//             showCancelButton: true,
//             confirmButtonColor: '#28a745',
//             cancelButtonText: 'Cancelar',
//             confirmButtonText: '¡Si, Generar!'
//             }).then((result) => {
//             if (result.value) {



//             $.ajax({
//               type: "POST",
//               url: "usuarios/php/funciones/ausencias/crear_suspencion.php",
//               data: {codigo:codigo,user_id:creado_por,vid:vid,from:from,to:to,resolucion:resolucion,dia:dia,sus_desc:sus_desc}, //f de fecha y u de estado.

//               beforeSend:function(){
//                             //$('#response').html('<span class="text-info">Loading response...</span>');

//                             $('#loading').fadeIn("slow");
//                     },
//                     success:function(data){
//                       swal({
//                         type: 'success',
//                         title: 'Permiso generado',
//                         showConfirmButton: false,
//                         timer: 1100
//                       });
//                       //alert(data);

//                       $("#s_form")[0].reset();
//                       setTimeout(function(){
//                                       $('#loading').fadeOut("slow");
//                                  }, 5000);
//                        $('#message').fadeIn().html(data);
//                        $("#message").addClass('alert alert-success');
//                        setTimeout(function(){
//                                       $('#message').fadeOut("slow");
//                                       $('#loading').fadeOut("slow");
//                                       mostrar_tabla();
//                                       get_suspenciones_list(id,creado_por);
//                                  }, 500);

//                     }


//             }).done( function() {










//             }).fail( function( jqXHR, textSttus, errorThrown){

//               alert(errorThrown);

//             });

//           }

//         })
//         },
//         rules: {
//             'from': {
//                 remote: {
//                     url: 'usuarios/validar_fecha_suspencion.php',
//                     data: {
//                       from: function(){ return $('#from').val();},
//                       codigo: function(){ return $('#codigo').val();}

//                   }
//                 }
//             },

//             'resolucion': {
//                 remote: {
//                     url: 'usuarios/validar_resolucion_suspencion.php',
//                     data: {
//                       from: function(){ return $('#resolucion').val();}

//                   }
//                 }
//             },
//             'to': {
//                 remote: {
//                     url: 'usuarios/validar_cantidad_dias_vacacionales.php',
//                     data: {
//                       from: function(){ return $('#from').val();},
//                       to: function(){ return $('#to').val();},
//                       codigo: function(){ return $('#codigo').val();},
//                       dia: function(){ return $('#dia').val();},
//                       user_id: id

//                   }
//                 }
//             },
//             'dia': {
//                 remote: {
//                     url: 'usuarios/validar_periodo_vacacional.php',
//                     data: {
//                       dia: function(){ return $('#dia').val();},
//                       codigo: id

//                   }
//                 }
//             }




//         },
//         messages: {
//             'from': {
//                 remote: "La fecha no existe en los horarios."
//             },
//             'resolucion':{
//               remote: "La resolución ya existe."
//             },
//             'to':{
//               remote: "La cantidad de dias solicitados excede a los días pendientes del período disponible."
//             },
//             'dia':{
//               remote: "El empleado no tiene dias vacacionales disponibles. (No ha cumplido período vacacional)"
//             }
//         }

//     });

// }
function aprobar_vacaciones(vac_id, control_id, tipo) {
    Swal.fire({
        title: '<strong></strong>',
        text: "¿Desea aprobar la solicitud?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Aprobar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "horarios/php/back/boletas/validar_vacaciones.php",
                data: { vac_id: vac_id, control_id: control_id, tipo: 1 },
                success: function (data) {
                    let jason = JSON.parse(data);
                    if (jason.status == "201") {
                        Swal.fire({
                            type: 'success',
                            title: 'Solicitud aprobada',
                            showConfirmButton: false,
                            timer: 1100,
                        });
                        recargarLasBoletas(tipo);
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: jason.msg,
                            showConfirmButton: false,
                            timer: 1100
                        });
                    }
                }
            }).fail(function (jqXHR, textSttus, errorThrown, data) {
                alert(data);
            });
        }
    });
}

function anular_vacaciones(vac_id, control_id, tipo) {
    Swal.fire({
        title: '<strong></strong>',
        text: "¿Desea anular la solicitud?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Anular!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "horarios/php/back/boletas/validar_vacaciones.php",
                data: { vac_id: vac_id, control_id: control_id, tipo: 0 },
                success: function (data) {
                    let jason = JSON.parse(data);
                    if (jason.status == "201") {
                        Swal.fire({
                            type: 'success',
                            title: 'Solicitud anulada',
                            showConfirmButton: false,
                            timer: 1100,
                        });
                        recargarLasBoletas(tipo);
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: jason.msg,
                            showConfirmButton: false,
                            timer: 1100
                        });
                    }
                }
            }).fail(function (jqXHR, textSttus, errorThrown) {
                alert(errorThrown);
            });
        }
    });
}
function aprobar_boleta(id_control, control_id) {
    Swal.fire({
        title: '<strong></strong>',
        text: "¿Desea aprobar la solicitud?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonText: 'Cancelar',
        confirmButtonText: '¡Si, Aprobar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "horarios/php/back/boletas/validar_boleta.php",
                data: { id_control: id_control, control_id: control_id, obs: "" },
                success: function (data) {
                    Swal.fire({
                        type: 'success',
                        title: 'Solicitud generada',
                        showConfirmButton: false,
                        timer: 1100,
                    });
                    reload_permisos();
                    refresh();
                }
            }).fail(function (jqXHR, textSttus, errorThrown) {
                alert(errorThrown);
            });
        }
    });
}

function anular_boleta(id_control, control_id) {
    if (true) {
        Swal.fire({
            title: '<strong>¿Desea anular la solicitud?</strong>',
            text: "",
            type: 'question',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Anular!',
            inputPlaceholder: 'Agrege una observación'
        }).then((result) => {
            if (result.value == '' || result.value) {
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/boletas/validar_boleta.php",
                    data: { id_control: id_control, control_id: control_id, obs: result.value },
                    success: function (data) {
                        Swal.fire({
                            type: 'success',
                            title: 'Solicitud anulada',
                            showConfirmButton: false,
                            timer: 1100,
                        });
                        reload_permisos();
                        refresh();
                    }
                }).fail(function (jqXHR, textSttus, errorThrown) {
                    alert(errorThrown);
                });
            }
        });
    };
}
function cambiar_horario(id_persona) {
    $.ajax({
        type: "POST",
        url: "horarios/php/back/empleados/get_horarios_empleado.php",
        dataType: 'json',
        success: function (data) {
        }
    }).done(function (data) {
        dias = "";
        for (var i = 0; i <= data.length - 1; i++) {
            let x = data[i];
            dias += '<option value="' + x['id_horario'] + '"';
            // if(i==f.getDate()){
            //   dias+='selected';
            // }
            dias += '>' + x['horario'] + '</option>';
        }
        swalfire = '<br><div class="row"><div class="col-sm-4">' +
            '<select id="idpermiso" class="form-control">' +
            dias +
            '</select></div><div class="col-sm-4"><div class="custom-control custom-checkbox text-center">' +
            '<input class="custom-control custom-checkbox" type="checkbox" id="ffijo" name="ffijo" checked><label for="ffijo"> Horario Fijo</label><br>' +
            '</div></div>';
        Swal.fire({
            title: '<strong>¿Desea cambiar el horario?</strong>',
            html: swalfire,
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Si, Cambiar!'
        }).then((result) => {
            if (result.value) {
                idpersona = id_persona;
                idpermiso = $('select#idpermiso option:checked').val();
                ffijo = document.getElementById('ffijo');
                $.ajax({
                    type: "POST",
                    url: "horarios/php/back/boletas/cambiar_horario.php",
                    data: { id_persona: idpersona, id_permiso: idpermiso, ffijo: ffijo.checked },
                    success: function (data) {
                        Swal.fire({
                            type: 'success',
                            title: 'Horario cambiado',
                            showConfirmButton: false,
                            timer: 1100,
                        });
                        refresh();
                        reload_detalle();
                    }
                }).fail(function (jqXHR, textSttus, errorThrown) {
                    alert(errorThrown);
                });
            }
        });
    }).fail(function (jqXHR, textStatus, errorThrown) {
    });
}
function cambiar_horario_url(url, opc) {
    url_ = '';
    if (opc == 1) {
        url_ = "empleados/php/front/puestos/" + url + ".php";
    } else if (opc == 2) {
        url_ = "empleados/php/front/plazas/" + url + ".php";
    }
    $.ajax({
        type: "POST",
        url: url_,
        data: {
            id_persona: $('#id_gafete').val()
        },
        dataType: 'html',
        beforeSend: function () {
            $("#datos_puesto").removeClass('slide_up_anim');
            $("#datos_puesto").html('<div class="loaderr"></div>');
        },
        success: function (data) {
            $("#datos_puesto").addClass('slide_up_anim');
            $("#datos_puesto").fadeIn('slow').html(data).fadeIn('slow');
        }
    });
}
// function cambiar_horario(id_persona) {
    // // array = new Array();
    // arr = get_horarios_id();
    // // console.log("test"+arr);


    // // array.forEach(function(item, index, array){
    // //     console.log(item, index);
    // // })

    // if (true) {
    //     Swal.fire({
    //         title: '<strong>¿Desea cambiar el horario?</strong>',
    //         html:
    //             '<br><div class="row"><div class="col-sm-4">' +
    //             '</div><div class="col-sm-4">' +
    //             '<select id="swal-input" class="form-control">' +

    //             '</select></div><div class="col-sm-4">' +
    //             '</div></div>',
    //         type: 'question',
    //         showCancelButton: true,
    //         confirmButtonColor: '#28a745',
    //         cancelButtonText: 'Cancelar',
    //         confirmButtonText: '¡Si, Cambiar!'
    //     }).then((result) => {
    //         if (result.value) {
    //             $.ajax({
    //                 type: "POST",
    //                 url: "",
    //                 data: {},
    //                 success: function (data) {
    //                     Swal.fire({
    //                         type: 'success',
    //                         title: 'Solicitud anulada',
    //                         showConfirmButton: false,
    //                         timer: 1100,
    //                     });
    //                     refresh();

    //                 }
    //             }).fail(function (jqXHR, textSttus, errorThrown) {
    //                 alert(errorThrown);
    //             });
    //         }
    //     });
    // };

// }
