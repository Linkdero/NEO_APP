<template>
  <div>
    <article class="ticket">
      <header class="ticket__wrapper">
        <div class="ticket__header">
          Solicitudes seleccionadas: {{ solicitudes.length  }}
        </div>
      </header>
      <div class="ticket__divider">
        <div class="ticket__notch"></div>
        <div class="ticket__notch ticket__notch--right"></div>
      </div>
      <div class="ticket__body">
        <section class="ticket__section" v-for="s in solicitudes">
          <h3 v-html="s.correlativo"></h3>
          <div class="row">
            <div v-bind:class="[tipo == 2 ? 'col-sm-6' : 'col-sm-4']">
              <dato-persona icono="fa fa-home" texto="Dirección:" :dato="s.direccion" tipo="0"></dato-persona>
              <dato-persona icono="fa fa-calendar-check" texto="Fecha:" :dato="s.solicitud_fecha" tipo="0"></dato-persona>
              <dato-persona icono="fa fa-time" texto="Hora de salida:" :dato="s.solicitud_hora" tipo="0"></dato-persona>
            </div>
            <div v-bind:class="[tipo == 2 ? 'col-sm-6' : 'col-sm-4']">
              <span v-html="s.destinos"></span>
            </div>
            <div class="col-sm-4" v-if="tipo == 3">
              aldkfjñajdñflajsdñfl
            </div>
          </div>
        </section>
      </div>
      <footer class="ticket__footer">
      </footer>
    </article>
  </div>
</template>
<script>
  module.exports = {
    props:["solicitud","solicitud_id","privilegio","estado", "evento","tipo"],
    data() {
      return {
        solicitudes: [],
        msg:"",
        fecha:""
      }
    },
    mounted(){

    },
    created: function(){
      this.getSolicitudesList();
    },
    methods:{
      setOption: function(opc){
        //this.option = opc;
        this.evento.$emit('getOpcion', opc);
      },
      getSolicitudesList: function(){
        axios.get('transportes/model/Transporte.php',{
          params:{
            opcion:1,
            filtro:0,
            solicitud_id: this.solicitud_id
          }
        })
        .then(function (response) {
          this.solicitudes = response.data;
          const uniques = [...new Set(this.solicitudes.map(item => item.solicitud_fecha))]
          this.evento.$emit('getFechasConteo',uniques);
        }.bind(this))
        .catch(function (error) {
            console.log(error);
        });
      }
    }
  }
</script>
<style>
.ticket {
  display: grid;
  grid-template-rows: auto 1fr auto;
  max-width: 100%;
}
.ticket__header, .ticket__body, .ticket__footer {
  padding: 1.25rem;
  background-color: white;
  border: 1px solid #89C4F4;
  box-shadow: 0 2px 4px rgba(41, 54, 61, 0.25);
}
.ticket__header {
  font-size: 1.5rem;
  border-top: 0.25rem solid #19B5FE;
  border-bottom: none;
  box-shadow: none;
}
.ticket__wrapper {
  box-shadow: 0 2px 4px rgba(41, 54, 61, 0.25);
  border-radius: 0.375em 0.375em 0 0;
  overflow: hidden;
}
.ticket__divider {
  position: relative;
  height: 1rem;
  background-color: white;
  margin-left: 0.5rem;
  margin-right: 0.5rem;
}
.ticket__divider::after {
  content: "";
  position: absolute;
  height: 50%;
  width: 100%;
  top: 0;
  border-bottom: 2px dashed #e9ebed;
}
.ticket__notch {
  position: absolute;
  left: -0.5rem;
  width: 1rem;
  height: 1rem;
  overflow: hidden;
}
.ticket__notch::after {
  content: "";
  position: relative;
  display: block;
  width: 2rem;
  height: 2rem;
  right: 100%;
  top: -50%;
  border: 0.5rem solid white;
  border-radius: 50%;
  box-shadow: inset 0 2px 4px rgba(41, 54, 61, 0.25);
}
.ticket__notch--right {
  left: auto;
  right: -0.5rem;
}
.ticket__notch--right::after {
  right: 0;
}
.ticket__body {
  border-bottom: none;
  border-top: none;
}
.ticket__body > * + * {
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e9ebed;
}
.ticket__section > * + * {
  margin-top: 0.25rem;
}
.ticket__section > h3 {
  font-size: 1.125rem;
  margin-bottom: 0.5rem;
}
.ticket__header, .ticket__footer {
  font-weight: bold;
  font-size: 1.25rem;
  display: flex;
  justify-content: space-between;
}
.ticket__footer {
  border-top: 2px dashed #e9ebed;
  border-radius: 0 0 0.325rem 0.325rem;
}
</style>
