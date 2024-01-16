<div id="reAsginarTicket">
    <div class="modal-header bg-info">
        <h4 class="modal-title text-white"><strong>Re-asignación Ticket:</strong></h4>
        <ul class="list-inline ml-auto mb-0">
            <li class="list-inline-item">
                <span class="link-muted h3 text-white" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </span>
            </li>
        </ul>
    </div>
    <ticketdetalle :idticket='<?php echo $_GET["id"]?>'></ticketdetalle>
    <br>
</div>

<script type="module" src="tickets/src/reAsginarTicket.js?t=<?php echo time();?>"></script>
<script src="assets/js/pages/components.js"></script>