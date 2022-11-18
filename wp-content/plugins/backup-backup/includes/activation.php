<?php

  // Namespace
  namespace BMI\Plugin;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

  // Alias for classes
  use BMI\Plugin\BMI_Logger as Logger;

  // Activation
  if (!function_exists('bmi_activate_plugin')) {
    function bmi_activate_plugin() {

      // Require classes
      require_once BMI_INCLUDES . '/logger.php';
      require_once BMI_ROOT_DIR . '/includes/constants.php';

      // Log the activation
      Logger::log(__("Plugin has been activated", 'backup-backup'));
      update_option('_bmi_redirect', true);

    }
  }
