<script type="module" src="tickets/src/ticketDetalle.js?t=<?php echo time(); ?>"></script>

<div id="AppDescripcionTicket">
    <div class="modal-header bg-info">
        <input type="hidden" id="id_ticket" value='<?php echo $_GET["id"] ?>'>
        <h4 class="modal-title text-white"><strong>Detalle Ticket #<?php echo $_GET["id"] ?></strong></h4>
        
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3 text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>
    <ticketdetalle :key="keyReload" :idticket='idTicket'></ticketdetalle>
</div>
</div>