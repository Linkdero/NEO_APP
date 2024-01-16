const viewFormularioVehiculos = new Vue({
  el: '#formularioVehiculos',

  data: {
    tipoFormulario: "",
    titulo: "",
    combustibles: '',
    colores: '',
    tipos: '',
    usos: '',
    estados: '',
    personas: '',
    asignaciones: '',
    proveedores: '',
    dependencias: '',
    vehiculo: '',
    marcas: '',
    lineasObtenidas: '',
    parteFormulario: 1,
    marcaSeleccionada: null,
    base64Image: '',
    idVehiculo: $("#idVehiculo").val(),
    foto: 'https://www.bicifan.uy/wp-content/uploads/2016/09/producto-sin-imagen.png',
  },

  mounted() {
    let thes = this;
    this.cargaPrincipal();
    $(".custom-select").select2();
    this.$nextTick(() => {
      $('#marca').on('change.select2', function (e) {
        thes.obtenerLineas();
      });
  });

    $('.select2').css('width', '75%');
    $("#modelo").datepicker({
      format: "yyyy",
      viewMode: "years",
      minViewMode: "years",
      startDate: "1900",
      endDate: new Date().getFullYear().toString(),
      autoclose: true,
      keyboardNavigation: false,
      forceParse: false,
      beforeShowYear: function (date) {
        if (date.getFullYear() > new Date().getFullYear()) {
          return false;
        }
      }
    });
  },

  methods: {
    obtenerLineas: function () {
      axios
        .get("vehiculos/php/back/vehiculos/formulario.php", {
          params: {
            opcion: 3,
            marca: $("#marca").val()
          }
        })
        .then((response) => {
          this.lineasObtenidas = response.data;
          console.log(this.lineasObtenidas);
        }).catch((error) => {
          console.log(error);
        });
    },
    formulario: function (tipo) {
      this.parteFormulario = tipo
    },

    cargaPrincipal: function () {
      if ($('#tipo').val() == 1) {
        this.titulo = 'Nuevo Vehiculo:';
        this.datos();
        this.tipoFormulario = true;
      } else {
        this.titulo = 'Editar Vehiculo:' + this.idVehiculo;
        this.datos();
        this.editarVehiculo(this.idVehiculo);
        this.tipoFormulario = false;
      }
    },

    openImageUploader() {
      Swal.fire({
        title: 'Seleccionar imagen',
        input: 'file',
        inputAttributes: {
          accept: 'image/*',
          'aria-label': 'Seleccionar imagen'
        },
        showCancelButton: true,
        showConfirmButton: true,
        preConfirm: (file) => {
          return new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = (e) => {
              const image = new Image();
              image.src = e.target.result;
              Swal.fire({
                title: 'Recortar imagen',
                html: `
                  <img id="preview" src="${image.src}" class="rounded-circle" style="width: 350px; height: 350px;">
                  <div>
                    <img id="cropperjs" src="${image.src}" style="display: block; max-width: 100%;">
                  </div>
                `,
                showCancelButton: true,
                showConfirmButton: true,
                onBeforeOpen: () => {
                  const imageElement = Swal.getContent().querySelector('#cropperjs');
                  const cropper = new Cropper(imageElement, {
                    aspectRatio: 1,
                    viewMode: 1,
                    crop: throttle(function () {
                      const croppedCanvas = cropper.getCroppedCanvas();
                      const preview = Swal.getContent().querySelector('#preview');
                      preview.src = croppedCanvas.toDataURL();
                    }, 25)
                  });
                },
                preConfirm: () => {
                  const preview = Swal.getContent().querySelector('#preview');
                  resolve(preview.src);
                  $('#previa').attr('src', preview.src);
                  this.base64Image = preview.src
                  console.log(this.base64Image);
                }
              });
            };
            reader.readAsDataURL(file);
          });
        }
      })
    },

    nuevoVehiculo: function (id) {
      let formulario = ["#placa", "#chasis", "#motor", "#color", "#estado", "#tipoVehiculo", "#marca", "#linea", "#modelo", "#franjas",
        "#galones", "#kmGalon", "#combustible", "#kmServicios", "#kmActual",
        "#perAutoriza", "#perAsignada", "#asignacion", "#aseguradora", "#poliza", "#dependencia", "#observaciones"];

      let titulo = ['No. Placa', 'Chasis', 'No. Motor', 'Color', 'Estado', 'Tipo', 'Marca', 'Linea', 'Modelo', 'Franjas',
        'Cap. Galones', 'Km. Galon', 'Combustible', 'Km. Para Servicios', 'Km. Actual',
        'Persona Autoriza', 'Persona Asignada', 'Tipo de Asignación', 'Aseguradora', 'No. Poliza', 'Dependencia', 'Observaciones']
      let datos = []
      let pagina;
      let formValido = true; // Variable para verificar si el formulario es válido

      // Validación para el formulario
      for (let i = 0; i < formulario.length; i++) {

        if (i <= 9) {
          pagina = ' En la página 1 ';
        } else if (i > 9 && i <= 14) {
          pagina = ' En la página 2 ';
        } else {
          pagina = ' En la página 3 ';
        }
        if ($(formulario[i]).val() == "" || $(formulario[i]).val() == null && $(formulario[i]).val() != 10) {
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
            title: 'Rellene el campo ' + titulo[i] + pagina
          })
          $(formulario[i]).addClass('input-invalid'); // Agrega la clase 'input-invalid' al input vacío
          formValido = false; // El formulario no es válido
          break; // Sale del bucle for al encontrar un campo vacío
        } else {
          $(formulario[i]).removeClass('input-invalid'); // Remueve la clase 'input-invalid' si el input no está vacío
        }
      }

      if (formValido) {
        let titulo;
        for (let i = 0; i < formulario.length; i++) {
          datos.push($(formulario[i]).val())
        }

        if (id != 0) {
          this.base64Image = $("#previa").attr("src");
          titulo = '¿Actualizar Vehículo?';
        }else{
titulo = `¿Agregar Nuevo Vehículo?`;
        }
        datos.push(this.base64Image)

        Swal.fire({
          title: `<strong>${titulo}</strong>`,
          text: "",
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonText: 'Cancelar',
          confirmButtonText: '¡Si, Agregar!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: 'POST',
              url: "vehiculos/php/back/vehiculos/formulario.php",
              dataType: 'json',
              data: {
                opcion: 4,
                formulario: datos,
                id: id
              },
              success:
                function (data) {
                  if (data.msg == 'OK' & data.id == 1) {
                    Swal.fire({
                      type: 'success',
                      title: data.message,
                      showConfirmButton: false,
                      timer: 1100
                    });
                    $('#modal-remoto-lgg3').modal('hide');
                    $('#tb_vehiculos').DataTable().ajax.reload();
                  } else {
                    Swal.fire({
                      type: 'error',
                      title: data.msg,
                      showConfirmButton: false,
                      timer: 1100
                    });
                  }
                }
            }).fail(function (jqXHR, textSttus, errorThrown) {
              alert(errorThrown);
            });
          }
        })
      }
    },

    editarVehiculo: function (id) {
      axios
        .get("vehiculos/php/back/vehiculos/formulario.php", {
          params: {
            opcion: 5,
            id: id
          }
        })
        .then((response) => {
          const data = response.data;
          const vehiculo = data[0];
          console.log(vehiculo);
          $("#placa").val(vehiculo['nro_placa'])
          $("#chasis").val(vehiculo['chasis'])
          $("#motor").val(vehiculo['motor'])
          $("#color").val(vehiculo['id_color']).change();
          $("#estado").val(vehiculo['id_status']).change();
          $("#tipoVehiculo").val(vehiculo['id_tipo']).change();
          $("#marca").val(vehiculo['id_marca']).change();
          $("#linea").val(vehiculo['id_linea']).change();
          $("#modelo").val(vehiculo['modelo'])
          $("#franjas").val(vehiculo['detalle_franjas'])

          $("#galones").val(vehiculo['capacidad_tanque'])
          $("#kmGalon").val(vehiculo['kilometros_x_galon'])
          $("#combustible").val(vehiculo['id_tipo_combustible']).change();
          $("#kmServicios").val(vehiculo['km_servicio_proyectado'])
          $("#kmActual").val(vehiculo['km_actual'])

          $("#perAutoriza").val(vehiculo['id_persona_autoriza']).change();
          $("#perAsignada").val(vehiculo['id_persona_asignado']).change();
          $("#asignacion").val(vehiculo['id_uso']).change();
          $("#aseguradora").val(vehiculo['id_empresa_seguros']).change();
          $("#poliza").val(vehiculo['poliza_seguro'])
          $("#dependencia").val(vehiculo['id_propietario']).change();
          $("#observaciones").val(vehiculo['observaciones'])

          if (vehiculo['foto'].substring(0, 20) == 'dataimage/jpegbase64') {
            this.foto = vehiculo['foto'];
            this.foto = this.foto.replace("dataimage/jpegbase64", "");
            this.foto = 'data:image/jpeg;base64,' + this.foto;
          } else {
            this.foto = 'data:image/jpeg;base64,' + vehiculo['foto'];
          }
          console.log(this.foto);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    datos() {
      axios
        .get("vehiculos/php/back/vehiculos/formulario.php", {
          params: {
            opcion: 1,
          }
        })
        .then((response) => {
          const data = response.data;
          this.combustibles = data.combustibles;
          this.colores = data.colores;
          this.tipos = data.tipos;
          this.usos = data.usos;
          this.estados = data.estados;
          this.marcas = data.marcas;
          this.personas = data.personas;
          this.asignaciones = data.asignaciones;
          this.proveedores = data.proveedores;
          this.dependencias = data.dependencias;
          console.log(data);
          console.log(data.tipos)
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
});

function throttle(func, delay) {
  let timeoutId;
  let lastExecTime = 0;

  return function (...args) {
    const context = this;
    const currentTime = Date.now();

    if (currentTime < lastExecTime + delay) {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(function () {
        lastExecTime = currentTime;
        func.apply(context, args);
      }, delay);
    } else {
      lastExecTime = currentTime;
      func.apply(context, args);
    }
  };
}
