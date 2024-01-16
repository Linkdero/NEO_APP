import diagnosticosList from './diagnosticosList.js';

let setFinalizarDiagnostico = new Vue({
    el: '#appFinalizarDiagnostico',
    data: {
        evaluacion: '',
        recomendacion: '',
        id: '',
    },
    mounted: function () {
        this.id = $("#idDiagnostico").val();
    },
    computed: {
        camposCompletos() {
            return this.evaluacion.trim() !== '' && this.recomendacion.trim() !== '';
        },
    },
    watch: {
        recomendacion: function (newVal) {
            console.log(newVal);
        },
        evaluacion: function (newVal) {
            console.log(newVal);
        }
    },
    methods: {
        setFinalizarDiagnostico: function () {
            Swal.fire({
                title: '<strong>¿Desea finalizar el Diagnostico?</strong>',
                text: `Se finalizara el diagnostico #: ${this.id} con la información agregada`,
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Finalizar!'
            }).then((result) => {
                if (result.value) {
                    axios.post('tickets/diagnosticos/model/diagnosticosList.php', {
                        opcion: 4,
                        id: this.id,
                        recomendacion: this.recomendacion,
                        evaluacion: this.evaluacion,
                    })
                        .then(function (response) {
                            console.log(response.data);
                            if (response.data.id == 1) {
                                Swal.fire({
                                    type: 'success',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                                $('#modal-remoto-lg').modal('hide');
                                diagnosticosList.cargarTablaDiagnosticos(6)
                            } else {
                                Swal.fire({
                                    type: 'error',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 1100
                                });
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }
            })
        },
        setAnularDiagnostico: function () {
            Swal.fire({
                title: `<strong>¿Desea anular el diagnóstico #${this.id}?</strong>`,
                html: `
                    <div class="form-group">
                        <label for="motivoAnulacion">Motivo de anulación</label>
                        <textarea id="motivoAnulacion" class="form-control" required></textarea>
                    </div>
                `,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF003E',
                cancelButtonText: 'Cancelar',
                confirmButtonText: '¡Si, Anular!'
            }).then((result) => {
                if (result.value) {
                    const motivoAnulacion = document.getElementById('motivoAnulacion').value;
                    if (motivoAnulacion.trim() !== '') { // Verificar que el campo no esté vacío
                        axios.post('tickets/diagnosticos/model/diagnosticosList.php', {
                            opcion: 3,
                            motivoAnulacion: motivoAnulacion,
                            id: this.id
                        })
                            .then(function (response) {
                                console.log(response.data);
                                if (response.data.id == 1) {
                                    Swal.fire({
                                        type: 'success',
                                        title: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                    $('#modal-remoto-lg').modal('hide');
                                    diagnosticosList.cargarTablaDiagnosticos(6)
                                } else {
                                    Swal.fire({
                                        type: 'error',
                                        title: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1100
                                    });
                                }
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: 'Campo obligatorio',
                            text: 'Por favor ingrese el motivo de anulación.',
                            confirmButtonColor: '#28a745'
                        });
                    }
                }
            })


        }

    }
});
CKEDITOR.replace("recomendacion", {
    language: 'es',
    width: '100%',
    height: '105',
    removeButtons: 'Source,Save,Preview,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CopyFormatting,BidiLtr,BidiRtl,Language,Unlink,Anchor,Link,Flash,Smiley,Iframe,BGColor,Maximize,ShowBlocks,About,Cut,Copy,Paste,PasteText,PasteFromWord,FontSize,Image,Format,Styles,CreateDiv,Print,NewPage,Templates,HorizontalRule,PageBreak',
    extraPlugins: 'table', // Habilita el plugin de tablas
    on: {
        change: function (evt) {
            setFinalizarDiagnostico.recomendacion = evt.editor.getData();
        }.bind(this)
    }
});
CKEDITOR.replace("evaluacion", {
    language: 'es',
    width: '100%',
    height: '105',
    removeButtons: 'Source,Save,Preview,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,CopyFormatting,BidiLtr,BidiRtl,Language,Unlink,Anchor,Link,Flash,Smiley,Iframe,BGColor,Maximize,ShowBlocks,About,Cut,Copy,Paste,PasteText,PasteFromWord,FontSize,Image,Format,Styles,CreateDiv,Print,NewPage,Templates,HorizontalRule,PageBreak',
    extraPlugins: 'table', // Habilita el plugin de tablas
    on: {
        change: function (evt) {
            setFinalizarDiagnostico.evaluacion = evt.editor.getData();
        }.bind(this)
    }
});
