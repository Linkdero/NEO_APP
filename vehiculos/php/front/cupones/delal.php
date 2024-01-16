<div id="MyModal">
        <div class="modal-header">
            <h5 class="modal-title">Ingresar correlativos></h5>
            <ul class="list-inline ml-auto mb-0">
                <li class="list-inline-item">
                    <span class="link-muted h3" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </span>
                </li>
            </ul>
        </div>

        <div class="card-body">
        <form class="jsValidacionSelectCupon" id="formValidacionSelectCupon">
          <div class="row">
            <cupones-disponibles-lista row="col-sm-6" label="Cupón inicial" codigo="c_ini"></cupones-disponibles-lista>
            <cupones-disponibles-lista row="col-sm-6" label="Cupón final" codigo="c_fin"></cupones-disponibles-lista>
            <!--<campo row="col-sm-6" label="Correlativo inicial:" codigo="c_ini" tipo="number" requerido="true"></campo>
            <campo row="col-sm-6" label="Correlativo final:" codigo="c_fin" tipo="number" requerido="true"></campo>-->
            <accion confirmacion="1" cancelar="2" v-on:event_child="eventAgregarC"></accion>
          </div>
        </form>
      </div>