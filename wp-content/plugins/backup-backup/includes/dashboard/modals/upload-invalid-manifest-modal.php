<?php

  // Namespace
  namespace BMI\Plugin\Dashboard;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

?>

<div class="modal" id="upload-invalid-manifest-modal">

  <div class="modal-wrapper" style="max-width: 564px; max-width: min(564px, 80vw);">
    <div class="modal-content">

      <div class="center">
        <div class="mbl cf center block inline">
          <div class="left center">
            <img class="inline" src="<?php echo $this->get_asset('images', 'warning-red.png'); ?>" alt="error">
          </div>
          <div class="left f24 bold lh50 mms"><?php _e("Invalid backup", 'backup-backup'); ?></div>
        </div>
      </div>

      <div class="center f19 regular mbl lh30">
        <?php _e("Uploaded file is not valid backup,", 'backup-backup'); ?><br>
        <?php _e("we removed the file from the server.", 'backup-backup'); ?>
      </div>

      <div class="center">
        <a href="#" class="btn modal-closer inline mm60 center block bold nodec">
          <?php _e("Ok, close", 'backup-backup'); ?>
        </a>
      </div>

    </div>
  </div>

</div>
