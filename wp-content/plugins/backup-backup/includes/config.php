<?php

  // Namespace
  namespace BMI\Plugin\Dashboard;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

  if (!function_exists('bmi_get_config')) {
    function bmi_get_config($setting) {

      // Load default and additional
      $defaults = json_decode(file_get_contents(BMI_CONFIG_DEFAULT));

      // Result default
      if (isset($defaults->{$setting}))
        $result = $defaults->{$setting};
      else $result = array();

      // Load user config
      if (file_exists(BMI_CONFIG_PATH) && BMI_CONFIG_STATUS) {

        // Get file contents
        $bmi_config_contents = file_get_contents(BMI_CONFIG_PATH);
        $bmi_config_json = json_decode($bmi_config_contents);

        // If config is correct set it
        if (json_last_error() == JSON_ERROR_NONE) {

          // Setting exist?
          if (isset($bmi_config_json->{$setting})) {

            // Get result
            $result = $bmi_config_json->{$setting};

          }

        }

      }

      // Replace exceptions
      if ($setting == 'STORAGE::LOCAL::PATH' && $result == 'default') {
        $result = BMI_BACKUPS_DEFAULT;
      }

      // Replace backshashes
      if ($setting == 'STORAGE::LOCAL::PATH') {
        $result = str_replace('\\\\', DIRECTORY_SEPARATOR, $result);
        $result = str_replace('\\', DIRECTORY_SEPARATOR, $result);
        $result = str_replace('/', DIRECTORY_SEPARATOR, $result);
      }

      // Return setting
      return $result;

    }
  }

  if (!function_exists('bmi_set_config')) {
    function bmi_set_config($setting, $value) {

      // Load default and additional
      if (file_exists(BMI_CONFIG_PATH)) {

        // Get file contents
        $bmi_config_contents = file_get_contents(BMI_CONFIG_PATH);
        $bmi_config_json = json_decode($bmi_config_contents);

        // Result default
        $default = bmi_get_config($setting);

        // If config is correct set it
        if (!(json_last_error() == JSON_ERROR_NONE)) {

          // Setting refill base
          $bmi_config_json = json_decode(json_encode(array()));;

        }

        // Allow empty
        $allow_empty = ['OTHER:CLI:PATH'];

        // Check if setting is not empty
        if (isset($value) && (!is_string($value) || (in_array($setting, $allow_empty) || strlen(trim($value)) > 0))) {

          // Set new setting
          @$bmi_config_json->{$setting} = $value;

        } else return false;

        // Write edited settings
        file_put_contents(BMI_CONFIG_PATH, json_encode($bmi_config_json));
        return true;

      }

      return false;

    }
  }

  if (!function_exists('bmi_try_checked')) {
    function bmi_try_checked($setting, $reversed = false) {

      if (!$reversed) {

        if (bmi_get_config($setting) == 'true' || bmi_get_config($setting) === true) {
          echo ' checked';
        } else return false;

      } else {

        if (bmi_get_config($setting) == 'true' || bmi_get_config($setting) === true) {
          return false;
        } else {
          echo ' checked';
        }

      }

    }
  }

  if (!function_exists('bmi_try_value')) {
    function bmi_try_value($setting) {

      $res = bmi_get_config($setting);
      if ($res !== false) {
        echo ' value="' . sanitize_text_field($res) . '"';
      } else echo '';

    }
  }

  // Get config and parse it
  if (file_exists(BMI_CONFIG_PATH)) {

    // Get file contents
    $bmi_config_contents = file_get_contents(BMI_CONFIG_PATH);
    $bmi_config_json = json_decode($bmi_config_contents);

    // If config is correct set it
    if (json_last_error() == JSON_ERROR_NONE) {

      if (!defined('BMI_CONFIG_STATUS')) define('BMI_CONFIG_STATUS', true);
      if (!defined('BMI_BACKUPS')) define('BMI_BACKUPS', bmi_get_config('STORAGE::LOCAL::PATH') . DIRECTORY_SEPARATOR . 'backups');

    } else {

      if (!defined('BMI_CONFIG_STATUS')) define('BMI_CONFIG_STATUS', false);

    }

  } else {

    @mkdir(dirname(BMI_CONFIG_PATH), 0755, true);
    @copy(BMI_CONFIG_DEFAULT, BMI_CONFIG_PATH);
    if (!defined('BMI_CONFIG_STATUS')) define('BMI_CONFIG_STATUS', true);

  }
