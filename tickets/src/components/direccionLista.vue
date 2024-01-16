<template>
  <div class="container card-body-slide">
    <input id="opcion" type="hidden" :value="tipo">
    <div v-if="tipo == 1">
      <div class="row">
        <div class="col-sm-6">
          <strong>Obtener Soporte de:</strong>
          <div class="form-group">
            <div class=" input-group  has-personalizado">
              <select class="form-control jsDirecciones" ref="seleccionado" @click="getRequerimientos()"
                id="direcciones" name="direcciones" required>
                <option v-for="d in direcciones" v-bind:value="d.id_direccion">
                  {{ d.nombre }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="col-sm-6">
          <strong>Departamento que Pertenece:</strong>
          <div class="form-group">
            <div class=" input-group  has-personalizado">
              <select class="form-control jsDepartamentos" ref="seleccionado2" id="departamentos" name="departamentos"s>
                <option value="" disabled selected>SELECCIONE UN DEPARTAMENTO</option>
                <option v-for="d in departamentos" v-bind:value="d.id_departamento">
                  {{ d.nombre }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <strong>Requerimientos:</strong>
            <div class="form-group">
              <div class=" input-group has-personalizado">
                <select class="jsRequerimientos form-control" id="requerimientos" name="requerimientos" multiple
                  required>
                  <option value="" disabled>SELECCIONE REQUERIMIENTOS</option>
                  <option v-for="r in requerimientos" v-bind:value="r.id_requerimiento" v-bind:title="r.descripcion">
                    {{ r.nombre }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="tipo == 2">
      <div class="row">
        <div class="col-sm-4">
          <strong>DIRECCIÓN DEL SOLICITANTE:</strong>
          <div class="form-group">
            <div class=" input-group  has-personalizado">
              <select class="form-control jsDirecciones" ref="seleccionado3" id="direccionesSoli" name="direccionesSoli"
                @change="getUsuarios()" required>
                <option value="" disabled selected>SELECCIONE UNA DIRECCIÓN</option>
                <option v-for="d in direccionesS" v-bind:value="d.id_direccion">
                  {{ d.nombre }}
                </option>
                <option value="1000000">APOYO</option>
                <!-- <option value="2000000">SALONES</option> -->
              </select>
            </div>
          </div>
        </div>

        <div class="col-sm-4">
          <strong>USUARIO QUE SOLICITA:</strong>
          <div class="form-group">
            <div class=" input-group  has-personalizado">
              <select class="form-control jsUsuarios" ref="seleccionado4" id="usuarios" name="usuarios" required>
                <option value="" disabled selected>SELECCIONE USUARIO</option>
                <option v-for="u in usuarios" v-bind:value="u.idNombre">
                  {{ u.nombre }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="col-sm-4">
          <strong>DEPARTAMENTO DEL SOLICITANTE:</strong>
          <div class="form-group">
            <div class=" input-group  has-personalizado">
              <select class="form-control jsDepartamentos" ref="seleccionado2" id="departamentos" name="departamentos">
                <option value="" disabled selected>SELECCIONE UN DEPARTAMENTO</option>
                <option v-if="depApoyo == 'APOYO'" value="1000000">
                  APOYO
                </option>
                <!-- <option v-else-if="depApoyo == 'SALONES'" value="2000000">
                  SALONES
                </option> -->
                <option v-else v-for="d in departamentos" v-bind:value="d.id_departamento">
                  {{ d.nombre }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <strong>DIRECCIÓN SOLICITADA:</strong>
            <div class="form-group">
              <div class=" input-group  has-personalizado">
                <select class="form-control jsDirecciones" ref="seleccionado" @click="getRequerimientos()"
                  id="direcciones" name="direcciones" required>
                  <option v-for="d in direcciones" v-bind:value="d.id_direccion">
                    {{ d.nombre }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <strong>REQUERIMIENTOS:</strong>
            <div class="form-group">
              <div class=" input-group  has-personalizado">
                <select class="jsRequerimientos form-control" id="requerimientos" name="requerimientos" multiple
                  required>
                  <option value="" disabled>SELECCIONE REQUERIMIENTOS</option>
                  <option v-for="r in requerimientos" v-bind:value="r.id_requerimiento" v-bind:title="r.descripcion">
                    {{ r.nombre }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <strong>FECHA INICIO:</strong>
            <div class="form-group">
              <div class=" input-group  has-personalizado">
                <input id="fechaInicial" name="fechaInicial"
                  class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES" :value="fechaH"
                  data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off" type="datetime-local">
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <strong>FECHA TERMINADO:</strong>
            <div class="form-group">
              <div class=" input-group  has-personalizado">
                <input id="fechaFinal" name="fechaFinal"
                  class="js-datepicker form-control form-icon-input-left form-corto" data-date-language="es-ES"
                  :value="fechaH" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off"
                  type="datetime-local">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
module.exports = {
  props: ["tipo"],
  data: function () {
    return {
      direcciones: [],
      direccionesS: [],
      departamentos: [],
      requerimientos: [],
      usuarios: [],
      depApoyo: "",
      fechaH:""
    };
  },

  //Para que se carguen al inicio las direcciones
  created: function () {
    if (this.tipo == 2) {
      this.getDireccionesSoli();
      this.fechaHoy()
    }

    this.$nextTick(() => {
      this.getDirecciones();
      setTimeout(() => {
        $('#direcciones').click();
      }, 200);
    });

    if (this.tipo == 1) {
      this.getDepartamentos();
    }

  },
  methods: {
    fechaHoy: function () {
      var fecha = new Date(); //Fecha actual
      var mes = fecha.getMonth() + 1; //obteniendo mes
      var dia = fecha.getDate(); //obteniendo dia
      var ano = fecha.getFullYear(); //obteniendo año
      if (dia < 10) {
        dia = '0' + dia; //agrega cero si el menor de 10
      }
      if (mes < 10) {
        mes = '0' + mes //agrega cero si el menor de 10
      }

      let hora = fecha.getHours() 
      let minuto = fecha.getMinutes();

      if (minuto < 10) {
        minuto = '0' + minuto //agrega cero si el menor de 10
      }

      if (hora < 10) {
        hora = '0' + hora //agrega cero si el menor de 10
      }

      let fechah = ano + "-" + mes + "-" + dia
      let horaH = hora + ':' + minuto
      let fechaHora = fechah + "T" + horaH
      this.fechaH = fechaHora
    },
    getDireccionesSoli: function () {
      axios
        .get("tickets/model/tickets.php", {
          params: {
            opcion: 2,
            idDir: 000
          },
          //Si todo funciona se imprime el json con las direcciones
        })
        .then(
          function (response) {
            this.direccionesS = response.data;
            //Si falla da mensaje de error
          }.bind(this)
        )
        .catch(function (error) {
          console.log(error);
        });
    },
    //Metodo para llamar las direcciones con axios
    getDirecciones: function () {
      axios
        .get("tickets/model/tickets.php", {
          params: {
            opcion: 2,
            idDir: 8
          },
          //Si todo funciona se imprime el json con las direcciones
        })
        .then(
          function (response) {
            this.direcciones = response.data;

            //Si falla da mensaje de error
          }.bind(this)
        )
        .catch(function (error) {
          console.log(error);
        });
    },

    getDepartamentos: function () {
      if (this.tipo == 1) {
        axios
          .get("tickets/model/tickets.php", {
            params: {
              opcion: 3,
              tipo: this.tipo
            },
            //Si todo funciona se imprime el json con las direcciones
          })
          .then(
            function (response) {
              this.departamentos = response.data;

              //Si falla da mensaje de error
            }.bind(this)
          )
          .catch(function (error) {
            console.log(error);
          });
      } else if (this.tipo == 2) {
        id = $("#direccionesSoli").val()
        axios
          .get("tickets/model/tickets.php", {
            params: {
              opcion: 3,
              tipo: this.tipo,
              id: id
            },
            //Si todo funciona se imprime el json con las direcciones
          })
          .then(
            function (response) {
              this.departamentos = response.data;

              let dir = document.getElementById("direccionesSoli");
              this.depApoyo = dir.options[dir.selectedIndex].text;

              //Si falla da mensaje de error
            }.bind(this)
          )
          .catch(function (error) {
            console.log(error);
          });
      }
      $("#jsDepartamentos").select2();
      $('#jsDepartamentos').select2({ placeholder: "Seleccione donde pertenece" });

    },

    //Metodo para llamar las incidencias con axios
    getRequerimientos: function () {
      var thisInstance = this;
      var id = '';
      id = thisInstance.$refs.seleccionado.value;
      axios
        .get("tickets/model/tickets.php", {
          params: {
            opcion: 4,
            tipo: this.tipo,
            requerimientos: id,
          },
          //Si todo funciona se imprime el json con las incidencias
        })
        .then(
          function (response) {
            this.requerimientos = response.data;
            $("#requerimientos").select2();
            $('#requerimientos').select2({ placeholder: "Seleccione Requerimientos" });
            //Si falla da mensaje de error
          }.bind(this)
        )
        .catch(function (error) {
          console.log(error);
        });
    },

    //Metodo para llamar las incidencias con axios
    getUsuarios: function () {
      var thisInstance = this;
      var id = '';
      id = thisInstance.$refs.seleccionado3.value;
      axios
        .get("tickets/model/tickets.php", {
          params: {
            opcion: 23,
            dir: id,
          },
          //Si todo funciona se imprime el json con las incidencias
        })
        .then(
          function (response) {
            this.usuarios = response.data;
            setTimeout(() => {
              $('.jsUsuarios').select2();
            }, 100);
            this.getDepartamentos()
            //Si falla da mensaje de error
          }.bind(this)
        )
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>