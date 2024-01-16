

const viewFinalizarServicio = new Vue({
    data: {
        mecanicos: [],
        personas: [],
        solicitado: '',
    },
    created: function () {
        this.$nextTick(() => {
            this.cargaUtil();
            $("#personaRecibe").select2({
                width: "100%"
            });

            CKEDITOR.replace("descripcion", {
                language: 'es',
                width: '100%',
                height: '170',
                removeButtons: 'Source,Save,Preview,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CopyFormatting,BidiLtr,BidiRtl,Language,Unlink,Anchor,Link,Flash,Smiley,Iframe,BGColor,Maximize,ShowBlocks,About,Cut,Copy,Paste,PasteText,PasteFromWord,FontSize,Image,Table,Format,Styles,CreateDiv,Print,NewPage,Templates,HorizontalRule,PageBreak'
            });
        });
    },
    //Asi generamos funciones en VUE
    methods: {
        cargaUtil: function () {
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

            axios
                .get("vehiculos/php/back/servicios/detalle/getNuevoServicio.php", {
                    params: {
                        opcion: 7,
                        servicio: $("#idServicio").val()
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        this.solicitado = response.data[0].descripcion;
                        console.log(this.solicitado.descripcio)
                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });

        },
        finalizar: function (noServi) {
            let formulario = [$("#personaRecibe").val(), CKEDITOR.instances['descripcion'].getData()];
            let titulo = ["Persona Recibe", "Descripcion"];
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

            Swal.fire({
                title: '<strong>¿Finalizar servicio #' + noServi + '?</strong>',
                text: "",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Asignar!'
            }).then((result) => {
                if (result.value) {
                    //alert(vt_nombramiento);
                    $.ajax({
                        type: "Post",
                        url: "vehiculos/php/back/servicios/detalle/getNuevoServicio.php",
                        dataType: 'json',
                        data: {
                            opcion: 6,
                            formulario: formulario,
                            noServi: noServi
                        },
                        success: function (data) {
                            if (data.msg == 'OK') {
                                Swal.fire({
                                    type: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                $('#modal-remoto-lgg').modal('hide');
                                $('#tb_servicios').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: data.id,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        }
                    }).done(function () { }).fail(function (jqXHR, textSttus, errorThrown) {
                        alert(errorThrown);
                    });
                }
            })
        }
    }
})

viewFinalizarServicio.$mount('#finalizarServicio');