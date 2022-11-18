<?php

  // Namespace
  namespace BMI\Plugin\Dashboard;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

?>

<div class="modal" id="delete-confirm-modal">

  <div class="modal-wrapper" style="max-width: 380px; max-width: min(380px, 80vw);">
    <a href="#" class="modal-close">×</a>
    <div class="modal-content">

      <div class="mbl center">
        <img class="center block inline" src="<?php echo $this->get_asset('images', 'trash.png'); ?>" alt="trash">
      </div>

      <div class="center f24 bold mbl lh30 text1">
        <?php _e("You’re sure you want to", 'backup-backup'); ?><br>
        <?php _e("delete this backup?", 'backup-backup'); ?>
      </div>

      <div class="center f24 bold mbl lh30 text2" style="display: none;">
        <?php _e("You’re sure you want to", 'backup-backup'); ?><br>
        <?php _e("delete", 'backup-backup'); ?>
        (<span id="backup-multiple-del-count">1</span>)
        <span id="del-only-one"><?php _e("backup?", 'backup-backup'); ?></span>
        <span id="del-more-than-one" style="display: none;"><?php _e("backups?", 'backup-backup'); ?></span>
      </div>

      <div class="center f19 mbl">
        <a href="#" id="sure_delete" class="btn bold red inline mm60">
          <?php _e("Yes, kill it!", 'backup-backup'); ?>
        </a>
      </div>

      <div class="center f19">
        <a href="#" class="bold modal-closer hoverable text-muted nodec">
          <?php _e("No, cancel", 'backup-backup'); ?>
        </a>
      </div>

    </div>
  </div>

</div>
