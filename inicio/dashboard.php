<?php

if (function_exists('verificar_session') && verificar_session()){


?>
<div class="u-content">

  <div class="u-body">
    <div class="row">
    <?php
      if (usuarioPrivilegiado()->hasPrivilege(278) || usuarioPrivilegiado()->hasPrivilege(87) || usuarioPrivilegiado()->hasPrivilege(285)){
        include('reportes/index.php');
      }
    ?>
  </div>
  </div>

  <?php
}
  ?>
