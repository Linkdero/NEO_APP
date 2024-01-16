<template>
    <div class="container card-body-slide">
        <form class="jsValidacionAsignarTecnico">
            <div class="row" style="z-index:5555">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="row">
                            <label for="tecnicos">Reasigar a:*</label>
                            <div class=" input-group  has-personalizado">
                                <select class="jsTecnicos form-control form-control-sm" id="asignado" required>
                                    <option value="" disabled selected>TECNICO ASIGNADO</option>
                                    <option v-for="t in tecAsignados" v-bind:value="t.id_tecnico">
                                        {{ t.primer_nombre }} {{ t.segundo_nombre }} {{ t.tercer_nombre }}
                                        {{ t.primer_apellido }} {{ t.segundo_apellido }} {{ t.tercer_apellido }}
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <label for="tecnicos">Por:*</label>
                            <div class=" input-group  has-personalizado">
                                <select class="jsTecnicos form-control form-control-sm" id="reasignado" required>
                                    <option value="" disabled selected>REASIGNADO</option>
                                    <option v-for="t in tecnicos" v-bind:value="t.id_persona">
                                        {{ t.primer_nombre }} {{ t.segundo_nombre }} {{ t.tercer_nombre }}
                                        {{ t.primer_apellido }} {{ t.segundo_apellido }} {{ t.tercer_apellido }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <label for="tecnicos">Razón Reasignación:*</label>
                        <div class="row">
                            <div class=" input-group  has-personalizado">
                                <textarea placeholder="Agregue Descripción" class="form-control form-control-sm"
                                    rows="3" id="descripcion" name="descripcion" required></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <br><br>
                <div class="col-sm-12">
                    <button class="btn btn-info btn-block btn-sm" @click="reAsignarTecnico()"><i
                            class="fa fa-check-circle"></i> Re-asignar Técnicos</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
module.exports = {
    props: ["idticket"],
    data: function () {
        return {
            tecAsignados: [],
            tecnicos: [],
        }
    },
    created: function () {
        this.getTecnicos()
    },

    methods: {
        getTecnicos: function () {
            axios
                .get("tickets/model/tickets.php", {
                    params: {
                        opcion: 14,
                        id: this.idticket,
                        tipo: 2
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        this.tecAsignados = response.data;

                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });

            axios
                .get("tickets/model/tickets.php", {
                    params: {
                        opcion: 10
                    },
                    //Si todo funciona se imprime el json con los tecnicos
                })
                .then(
                    function (response) {
                        this.tecnicos = response.data;

                        //Si falla da mensaje de error
                    }.bind(this)
                )
                .catch(function (error) {
                    console.log(error);
                });
        },

        reAsignarTecnico: function () {
            idticket= this.idticket
            jQuery('.jsValidacionAsignarTecnico').validate({
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
                    //regformhash(form,form.password,form.confirmpwd);
                    var form = $('#formNuevoEscolaridad');
                    Swal.fire({
                        title: '<strong>¿Desea Re-asignar al técnico?</strong>',
                        text: "",
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: '¡Si, asignar!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "GET",
                                url: "tickets/model/tickets.php",
                                dataType: 'json',
                                data: {
                                    opcion: 13,
                                    estado:2,
                                    id: idticket,
                                    tecnicosAsignados: $('#reasignado').val(),
                                    tecnicoOriginal: $('#asignado').val(),
                                    razon: $('#descripcion').val()
                                }, //f de fecha y u de estado.
                                beforeSend: function () {
                                    //$('#response').html('<span class="text-info">Loading response...</span>');
                                    //alert('message_before')
                                },
                                success:
                                    function (data) {
                                        $('#modal-remoto-lg').modal('hide');
                                        if (data.msg == 'OK') {
                                            Swal.fire({
                                                type: 'success',
                                                title: data.message,
                                                showConfirmButton: false,
                                                timer: 1100
                                            });
                                            $('#tb_tickets').DataTable().ajax.reload();
                                        } else {
                                            Swal.fire({
                                                type: 'error',
                                                title: data.id,
                                                showConfirmButton: false,
                                                timer: 1100
                                            });
                                        }
                                    }
                            }).done(function () {

                            }).fail(function (jqXHR, textSttus, errorThrown) {

                                alert(errorThrown);

                            });

                            $.ajax({
                                type: "GET",
                                url: "tickets/model/tickets.php",
                                dataType: 'json',
                                data: {
                                    opcion: 18,
                                    idTick: ticket,
                                    tipoEnviar: 1
                                }, //f de fecha y u de estado.
                                beforeSend: function () {
                                    //$('#response').html('<span class="text-info">Loading response...</span>');
                                    //alert('message_before')
                                },
                                success: function (data) {
                                    var reformattedArray = data.correos.map(function (obj) {
                                        sendMail(obj.emisor, obj.receptor, obj.body, obj.subject)
                                    });

                                }
                            }).done(function () {

                            }).fail(function (jqXHR, textSttus, errorThrown) {

                                alert(errorThrown);

                            });

                            function sendMail(emisor, receptor, body, subject) {
                                $.ajax({
                                    type: "POST",
                                    url: "https://saas.gob.gt/mailer/",
                                    dataType: 'json',
                                    data: {
                                        emisor: emisor,
                                        receptor: receptor,
                                        body: body,
                                        subject: subject
                                        /*,
                                        subject:subject,
                                        body:body*/

                                    }, //f de fecha y u de estado.

                                    beforeSend: function () {
                                    },
                                    success: function (data) {
                                        if (data.msg == 'OK') {
                                            $('#modal-remoto-lg').modal('hide');
                                            Swal.fire({
                                                type: 'success',
                                                title: data.message,
                                                showConfirmButton: false,
                                                timer: 1100
                                            });
                                        } else {
                                            Swal.fire({
                                                type: data.message,
                                                title: data.id,
                                                showConfirmButton: false,
                                                timer: 1100
                                            });
                                        }
                                        console.log(data);
                                    }
                                }).done(function () {

                                }).fail(function (jqXHR, textSttus, errorThrown) {

                                });
                            }

                        }
                    })
                },
                rules: {
                }
            });
        }

    }
}
</script>