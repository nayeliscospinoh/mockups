<?php

  // Namespace
  namespace BMI\Plugin\Dashboard;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

?>

<table>
  <tr class="br_tr_template">
    <td>
      <label class="br_label" for="">
        <input class="br_checkbox" id="" type="checkbox">
        <span class="br_date">---</span>
      </label>
    </td>
    <td class="br_name">---</td>
    <td class="br_size">---</td>
    <td class="center">
      <img class="tooltip bc-unlocked-btn hoverable" tooltip="<?php _e('Lock backup files', 'backup-backup') ?>" src="<?php echo $this->get_asset('images', 'unlocked-min.svg'); ?>" height="23px" alt="image">
      <img class="tooltip bc-locked-btn hoverable" tooltip="<?php _e('Unlock backup files', 'backup-backup') ?>" src="<?php echo $this->get_asset('images', 'lock-min.svg'); ?>" height="23px" alt="image">
    </td>
    <td>
      <a href="#" class="bc-download-btn hoverable nodec untab" tabindex="-1" download>
        <img class="tooltip" tooltip="<?php _e('Download the backup file. Click on it downloads it', 'backup-backup') ?>" src="<?php echo $this->get_asset('images', 'download-min.png'); ?>" height="23px" alt="image">
      </a>
      <img class="tooltip bc-url-btn hoverable untab" tabindex="-1" tooltip="<?php _e('Copy link to backup file for super-quick migration', 'backup-backup') ?>" src="<?php echo $this->get_asset('images', 'link-min.png'); ?>" height="23px" alt="image">
      <a href="#" class="bc-logs-btn hoverable nodec untab" tabindex="-1" download>
        <img class="tooltip" tooltip="<?php _e('Download log file which was created at time of backup', 'backup-backup') ?>" src="<?php echo $this->get_asset('images', 'log-min.svg'); ?>" height="23px" alt="image">
      </a>
    </td>
    <td>
      <div class="restore-btn hoverable tooltip" tooltip="<?php _e('Restore this backup on this site', 'backup-backup') ?>">
        <img src="<?php echo $this->get_asset('images', 'restore-min.svg'); ?>" width="12px" alt="image">
        <?php _e('Restore', 'backup-backup') ?>
      </div>
    </td>
    <td><img class="tooltip bc-remove-btn hoverable" tooltip="<?php _e('Delete this backup', 'backup-backup') ?>" src="<?php echo $this->get_asset('images', 'red-close-min.svg'); ?>" height="12px" alt="image"></td>
  </tr>
</table>
