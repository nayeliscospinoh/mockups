<?php

  // Namespace
  namespace BMI\Plugin\Scanner;

  // Use
  use BMI\Plugin\BMI_Logger AS Logger;
  use BMI\Plugin\Zipper\BMI_Zipper AS Zipper;
  use BMI\Plugin\Zipper\Zip AS Zip;

  // Exit on direct access
  if (!defined('ABSPATH')) exit;

  /**
   * Main Backup Scanner Logic
   */
  class BMI_BackupsScanner {

    public function scanBackupDir($path) {

      $files = [];
      foreach (new \DirectoryIterator($path) as $fileInfo) {

        if ($fileInfo->isDot()) continue;
        if ($fileInfo->isFile()) {
          if (in_array($fileInfo->getExtension(), ['zip', 'tar', 'tar.gz', 'gz', 'rar', '7zip', '7z'])) {

            $files[] = array(
              'filename' => $fileInfo->getFilename(),
              'path' => $path,
              'size' => $fileInfo->getSize()
            );

          }
        }

      }

      return $files;

    }

    public function getManifestFromZip($zip_path, &$zipper) {

      // Get manifest content
      $res = array();
      $manifest = $zipper->getZipFileContent($zip_path, 'bmi_backup_manifest.json');
      if ($manifest) {

        $res[] = $manifest->name;
        $res[] = $manifest->date;
        $res[] = $manifest->files;
        $res[] = $manifest->manifest;
        $res[] = @filesize($zip_path);
        $res[] = $zipper->is_locked_zip($zip_path) ? 'locked' : 'unlocked';
        $res[] = $manifest->cron;

        return $res;

      } else return false;

    }

    public function getAvailableBackups() {

      // Require Universal Zip Library
      require_once BMI_INCLUDES . '/zipper/zipping.php';
      $zipper = new Zipper();

      // Scan for manifests
      $manifests = array();
      $backups = array();

      if (file_exists(BMI_BACKUPS))
        $backups = $this->scanBackupDir(BMI_BACKUPS);

      for ($i = 0; $i < sizeof($backups); ++$i) {

        $backup = $backups[$i];
        $manifest = $this->getManifestFromZip($backup['path'] . '/' . $backup['filename'], $zipper);
        if ($manifest) $manifests[$backup['filename']] = $manifest;

      }

      return $manifests;

    }

  }
