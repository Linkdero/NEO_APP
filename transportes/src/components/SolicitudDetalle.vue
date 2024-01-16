<template>
  <div class="contenedor" v-if="vista == 1">
    <!--{{ sDetalle }}-->
    <section class="sectionn"id="informations">


      <dl class="dll">
        <dt class="dtt">Fecha</dt>
        <dd class="ddd">{{ sDetalle.solicitud_fecha }}</dd>
        <dt class="dtt">Hora</dt>
        <dd class="ddd">{{ sDetalle.solicitud_hora }}</dd>
        <dt class="dtt">Duración</dt>
        <dd class="ddd">{{ sDetalle.duracion }}</dd>
        <dt class="dtt">Cantidad</dt>
        <dd class="ddd">{{ sDetalle.cantidad }}</dd>
      </dl>

      <ul class="ull">

        <!--<li class="lii">5:10pm</li>
        <li class="lii">Dec 15, 2018</li>
        <li class="lii">Coach</li>
        <li class="lii">1257797706706</li>-->
      </ul>
    </section>

    <section class="sectionn" id="ticket">
      <h1 v-html="sDetalle.solicitud_status_p"></1>
      <dl class="dll">
        <dt class="dtt">Flight</dt>
        <dd class="ddd">DL31</dd>
        <dt class="dtt">Gate</dt>
        <dd class="ddd">29</dd>
        <dt class="dtt">Seat</dt>
        <dd class="ddd">26E</dd>
        <dt class="dtt">Zone</dt>
        <dd class="ddd">4</dd>
      </dl>
      <ul class="ull">
        <li class="lii">CDG ✈ LFLL</li>
        <li class="lii">5:10pm</li>
      </ul>
    </section>

    {{ sDetalle.solicitud_observaciones}}
  </div>
  <div v-else-if="vista == 2" style="border-right:1px solid #ECF0F1">
  <!--<div v-else-if="vista == 2" >-->
    <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 far fa-user"></i> Solicitante
    <dato-persona icono="fa fa-home" texto="Dirección:" :dato="sDetalle.direccion" tipo="1"></dato-persona>
    <dato-persona icono="fa fa-user" texto="Solicitante:" :dato="sDetalle.solicitante" tipo="1"></dato-persona>
    <hr>
    <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-file-signature"></i> Datos de la Solicitud
    <div class="row">
      <div class="col-sm-6">
        <dato-persona icono="fa fa-calendar-check" texto="Fecha:" :dato="sDetalle.solicitud_fecha" tipo="1"></dato-persona>
        <dato-persona icono="fa fa-clock" texto="Salida:" :dato="sDetalle.solicitud_hora" tipo="1"></dato-persona>
      </div>
      <div class="col-sm-6">
        <dato-persona icono="fa fa-list" texto="Duración:" :dato="sDetalle.duracion" tipo="1"></dato-persona>
        <dato-persona icono="fa fa-users" texto="Cantidad:" :dato="sDetalle.cantidad" tipo="1"></dato-persona>
      </div>
    </div>
    <hr>
    <dato-persona icono="fa fa-pencil-alt" texto="Observaciones:" :dato="sDetalle.solicitud_observaciones" tipo="1"></dato-persona>
    <hr>
    <i class="u-icon u-icon--sm bg-info text-white rounded-circle mr-3 fa fa-map-marker-alt"></i> Destinos: {{ sDetalle.cant_lugares }} <br><br><span v-html="sDetalle.destinosd"></span>
  </div>
</main>
  </div>
</template>
<script>
  module.exports = {
    props:["solicitud","solicitud_id","privilegio","estado", "evento","tipo","modalnombre","vista"],
    data() {
      return {
        //solicitud:"",
        sDetalle:"",
        msg:"",
        fecha:""
      }
    },
    mounted(){

    },
    created: function(){
      this.getSolicitudById();
    },
    methods:{
      sepption: function(opc){
        //this.option = opc;
        this.even.$emit('geppcion', opc);
      },
      getSolicitudById: function(){
        axios.get('transportes/model/Transporte.php',{
          params:{
            opcion:1,
            filtro:0,
            solicitud_id: ','+this.solicitud_id
          }
        })
        .then(function (response) {
          this.sDetalle = response.data[0];
          /*const uniques = [...new Set(this.solicitudes.map(item => item.solicitud_fecha))]
          this.even.$emit('getFechasConteo',uniques);*/
        }.bind(this))
        .catch(function (error) {
            console.log(error);
        });
      }
    }
  }
</script>
<style>
.contenedor {
  border: 0px solid #ccc;
  background: #fff;
  box-sizing: border-box;
  color: #2A3239;
  display: flex;
  flex-wrap: wrap;
  /*height: 8cm;*/
  justify-content: space-between;
  margin: 0;
  /*width: 25cm;*/
}

.sectionn {
  box-sizing: border-box;
}

.dll {
  columns: 4;
  text-align: center;
}
.dtt {
  font-size: 9pt;
  font-weight: 500;
  text-transform: uppercase;
}
.dd {
  margin-left: 0;
}
.ull {
  align-items: center;
  display: flex;
  list-style: none;
  margin: 0;
  padding-left: 0;
}
.lii {
  font-weight: 700;
  text-transform: uppercase;
}

#informations {
  flex: 1;
  padding: 0;
  position: relative;
}
#informations h1 {
  display: inline-block;
  font-size: 15pt;
  font-weight: 200;
  text-transform: uppercase;
}
#informations #name {
  margin-left: 0cm;
}
#informations #destination {
  position: absolute;
  right: 1cm;
}
#informations dl {
  background: #434A54;
  color: #fff;
  margin: 0;
  padding: 0.2cm 0;
}
#informations dd {
  border-left: 1pt solid #fff;
  font-size: 25pt;
}
#informations dd:first-of-type {
  border-left: 0;
}
#informations ul {
  margin-left: 1cm;
}
#informations li {
  font-weight: 300;
  padding: 0.15cm;
}
#informations li:first-of-type {
  background: #2A3239;
  border-radius: 4pt;
  color: #2972fa;
}
#informations li:last-of-type {
  font-size: 25pt;
  margin-left: au;
  padding-right: 1cm;
  padding-top: 0.5cm;
}

#ticket {
  border-left: 1pt dashed #2A3239;
  display: flex;
  flex-direction: column;
  /*height: 8cm;*/
  justify-content: space-around;
  padding: 0 1cm;
}
#ticket h2 {
  font-weight: 300;
  margin: 0;
  text-transform: uppercase;
}
#ticket p {
  font-size: 25pt;
  margin: 0;
  text-align: center;
}
#ticket dl {
  margin: 0;
}
#ticket li {
  margin: 0 0.25cm;
}

/*vista 2 */
/*.tickett-wrapper {
  overflow: hidden;
  width: 105%;
  margin-top: -15px;
  padding-bottom: 10px;
  margin-left:-15px;
  color:#000;
}*/
.tickett {
  /*position: absolute;*/
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
  opacity: 0;
  transform: translateY(-510px);
  animation-duration: 0.5s;
  animation-delay: 100ms;
  animation-name: print;

  animation-fill-mode: forwards;
}
.basic {
  display: none;
}

.airline {
  display: block;
  height: 575px;
  width: 100%;
  box-shadow: 5px 5px 30px rgba(0, 0, 0, 0.3);
  border-radius: 15px;
  z-index: 3;
}
.airline .topp {
  height: 220px;
  background: #CCD1D9;
  border-top-right-radius: 15px;
  border-top-left-radius: 15px;
}
.airline .topp h1 {
  text-transform: uppercase;
  font-size: 12px;
  letter-spacing: 2;
  text-align: center;
  position: absolute;
  top: 30px;
  left: 50%;
  transform: translateX(-50%);
}
.airline .bottomm {
  height: 355px;
  background: #fff;
  border-bottom-right-radius: 25px;
  border-bottom-left-radius: 25px;
}

.topp .big {
  position: absolute;
  top: 100px;
  font-size: 65px;
  font-weight: 700;
  line-height: 0.8;
}
.topp .big .from {
  color: #ffcc05;
  text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
}
.topp .big .too {
  position: absolute;
  left: 32px;
  font-size: 35px;
  display: flex;
  flex-direction: row;
  align-items: center;
}
.topp .big .too i {
  margin-top: 5px;
  margin-right: 10px;
  font-size: 15px;
}
.topp--side {
  position: absolute;
  right: 35px;
  top: 110px;
  text-align: right;
}
.topp--side i {
  font-size: 25px;
  margin-bottom: 18px;
}
.topp--side p {
  font-size: 10px;
  font-weight: 700;
}
.topp--side p + p {
  margin-bottom: 8px;
}

.bottomm p {
  display: flex;
  flex-direction: column;
  font-size: 13px;
  font-weight: 700;
}
.bottomm p span {
  font-weight: 400;
  font-size: 11px;
  color: #6c6c6c;
}
.bottomm .columnn {
  margin: 0 auto;
  width: 80%;
  padding: 2rem 0;
}
.bottomm .row {
  display: flex;
  justify-content: space-between;
}
.bottomm .row--right {
  text-align: right;
}
.bottomm .row--center {
  text-align: center;
}
.bottomm .row-2 {
  margin: 30px 0 60px 0;
  position: relative;
}
.bottomm .row-2::after {
  content: "";
  position: absolute;
  width: 100%;
  bottom: -30px;
  left: 0;
  background: #000;
  height: 1px;
}

.bottomm .bar--code {
  height: 50px;
  width: 80%;
  margin: 0 auto;
  position: relative;
}
.bottomm .bar--code::after {
  content: "";
  position: absolute;
  width: 6px;
  height: 100%;
  background: #000;
  topp: 0;
  left: 0;
  box-shadow: 10px 0 #000, 30px 0 #000, 40px 0 #000, 67px 0 #000, 90px 0 #000, 100px 0 #000, 180px 0 #000, 165px 0 #000, 200px 0 #000, 210px 0 #000, 135px 0 #000, 120px 0 #000;
}
.bottomm .bar--code::before {
  content: "";
  position: absolute;
  width: 3px;
  height: 100%;
  background: #000;
  topp: 0;
  left: 11px;
  box-shadow: 12px 0 #000, -4px 0 #000, 45px 0 #000, 65px 0 #000, 72px 0 #000, 78px 0 #000, 97px 0 #000, 150px 0 #000, 165px 0 #000, 180px 0 #000, 135px 0 #000, 120px 0 #000;
}

.info {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 10px;
  font-size: 14px;
  text-align: center;
  z-index: 1;
}
.info a {
  text-decoration: none;
  color: #000;
  background: #ffcc05;
}
.receipts-wrapper {
  overflow: hidden;
  margin-top: -10px;
  padding-bottom: 10px;
}

.tickett-system .receipts {
  width: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
  transform: translateY(-510px);
  animation-duration: 0.2s;
  animation-delay: 50ms;
  animation-name: print;
  animation-fill-mode: forwards;
}


@keyframes print {

  0% {
    opacity: 0;
    transform: translateY(-510px);
  }
  /*35% {
    transform: translateY(-395px);
  }
  70% {
    transform: translateY(-140px);
    opacity: 0.5;
  }*/
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}
</style>
