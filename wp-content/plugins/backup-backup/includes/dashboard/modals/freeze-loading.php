<?php

  // Namespace
  namespace BMI\Plugin\Dashboard;

  // Exit on direct access
  if (!defined('ABSPATH')) {
    exit;
  }

?>

<div class="modal modal-no-close modal-off-fixed modal-no-background-center" id="freeze-loading-modal">

  <div class="modal-centered-items">
    <div class="modal-wrapper no-hpad no-vpad" style="max-width: 900px; max-width: min(900px, 80vw)">
      <div class="modal-content">

        <div class="modal-freeze-loader"></div><br>
        <b class="f24"><?php _e('Loading...', 'backup-backup'); ?></b>

      </div>
    </div>
  </div>

</div>
