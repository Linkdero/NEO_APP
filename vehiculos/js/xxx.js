<div v-if="opc == 2">
<div id="myModal" class="modal-vue">
  <div class="modal-vue-content">
    <div class="card shadow-card">
      <header class="header-color">
        <h4 class="card-header-title" >
          <i class="u-icon u-icon--sm bg-soft-info text-white rounded-circle mr-3 fa fa-ticket-alt">
          </i><span v-if="opc == 2" class="text-white"> Procesar Documento</span>
        </h4>
        <span class="close-icon"  @click="getOpc(1)">
          <i class="fa fa-times"></i>
        </span>
      </header>

    </div>
  </div>
</div>
</div>