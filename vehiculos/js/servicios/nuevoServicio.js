const viewNuevoServicio = new Vue({
    el: '#nuevoServicio',

    data: {
        tipoServicio: [],
        personas: [],
        placas: [],
        vehiculo: [],
        tipoVehiculos: '',
    },
    //Para que se ejecute al inicar el modulo
    created: function () {
        this.$nextTick(() => {
            this.cargaPrincipal();
            $("#personaEntrega").select2();
            $('#placa').select2();
            CKEDITOR.replace("descripcion", {
                language: 'es',
                width: '100%',
                height: '170',
                removeButtons: 'Source,Save,Preview,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CopyFormatting,BidiLtr,BidiRtl,Language,Unlink,Anchor,Link,Flash,Smiley,Iframe,BGColor,Maximize,ShowBlocks,About,Cut,Copy,Paste,PasteText,PasteFromWord,FontSize,Image,Table,Format,Styles,CreateDiv,Print,NewPage,Templates,HorizontalRule,PageBreak'
            });
            let thes = this
            $('#placa').on('change.select2', function (e) {
                thes.getVehiculo($("#tipoVehiculo").val())
            });
        });
    },

    //Asi generamos funciones en VUE
    methods: {
        cargaPrincipal: function () {
            //CARGA PARA EL TIPO DE SERVICIO
            axios
                .get("vehiculos/php/back/servicios/detalle/getNuevoServicio.php", {
                    params: {
                        opcion: 1,
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        this.tipoServicio = response.data;
                        console.log(response.data)
                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });

            //CARGA PARA LAS PERSONAS
            axios
                .get("vehiculos/php/back/servicios/detalle/getNuevoServicio.php", {
                    params: {
                        opcion: 2,
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        this.personas = response.data;
                        console.log(response.data)
                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        tipoVehiculo(event) {
            //Carga de Placas
            this.placas = null
            axios
                .get("vehiculos/php/back/servicios/detalle/getNuevoServicio.php", {
                    params: {
                        opcion: 3,
                        tipo: $("#tipoVehiculo").val()
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        this.placas = response.data;
                        console.log(response.data)
                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });

        },
        getVehiculo: function (tipo) {
            axios
                .get("vehiculos/php/back/servicios/detalle/getNuevoServicio.php", {
                    params: {
                        opcion: 4,
                        placa: $("#placa").val(),
                        tipo: tipo
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        let vehiculo = response.data;
                        this.vehiculo = vehiculo[0];
                        console.log(response.data)
                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },
        nuevoServicio: function () {
            let formulario = [$("#numOficio").val(), $("#tipoServicio").val(), $("#personaEntrega").val(), $("#placa").val(), $("#kmActual").val(), CKEDITOR.instances['descripcion'].getData()];
            let titulo = ["Oficio", "Tipo Servicio", "Persona Entrega", "Placa", "Km Actual", "Descripcion"];
            console.log(CKEDITOR.instances['descripcion'].getData())
            //Validación para el formulario
            for (let i = 0; i < formulario.length; i++) {
                if (formulario[i] == "" || formulario[i] == null) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        type: 'error',
                        title: 'Rellene el campo ' + titulo[i]
                    })
                    return
                }
            }
            let tipo = $("#tipoVehiculo").val()
            //Enviar formulario
            Swal.fire({
                title: '<strong>¿Desea generar un nuevo Servicio?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Generar!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: "vehiculos/php/back/servicios/detalle/getNuevoServicio.php",
                        dataType: 'json',
                        data: {
                            opcion: 5,
                            formulario: formulario,
                            tipo: tipo
                        },
                        success:
                            function (data) {
                                if (data.msg == 'OK' & data.id == 1) {
                                    Swal.fire({
                                        type: 'error',
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                    $('#tb_servicios').DataTable().ajax.reload();
                                }else if (data.msg == 'OK' & data.id == 2) {
                                    Swal.fire({
                                        type: 'success',
                                        title: data.message,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                    $('#tb_servicios').DataTable().ajax.reload();
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: data.id,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                }
                                $('#modal-remoto-lg').modal('hide');
                            }
                    }).fail(function (jqXHR, textSttus, errorThrown) {
                        alert(errorThrown);
                    });
                }
            })
        }
    },
})
