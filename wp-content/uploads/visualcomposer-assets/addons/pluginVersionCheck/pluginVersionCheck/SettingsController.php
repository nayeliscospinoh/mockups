<?php

namespace pluginVersionCheck\pluginVersionCheck;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

use VisualComposer\Framework\Container;
use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Helpers\Notice;
use VisualComposer\Helpers\Options;
use VisualComposer\Helpers\Traits\WpFiltersActions;
use VisualComposer\Helpers\Wp;

/**
 * Class SettingsController
 * @package pluginVersionCheck\pluginVersionCheck
 */
class SettingsController extends Container implements Module
{
    use WpFiltersActions;

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
        $this->wpAddAction('current_screen', 'pluginVersionWarning');
    }

    /**
     * If plugin is outdated, show an error message for that
     *
     * @param \VisualComposer\Helpers\Notice $noticeHelper
     * @param \VisualComposer\Helpers\Options $optionsHelper
     * @param \VisualComposer\Helpers\Wp $wpHelper
     */
    protected function pluginVersionWarning(Notice $noticeHelper, Options $optionsHelper, Wp $wpHelper)
    {
        // if current installed version is less than required then check wp.org api for new version
        if ($wpHelper->isUpdateNeeded()) {
            if ((int)$optionsHelper->getTransient('addonPluginVersionCheck') < time()) {
                $response = wp_remote_get(
                    'https://api.wordpress.org/plugins/info/1.0/visualcomposer.json',
                    ['timeout' => 30]
                );
                if (!vcIsBadResponse($response)) {
                    $optionsHelper->setTransient(
                        'addonPluginVersionCheck',
                        time() + 4 * HOUR_IN_SECONDS,
                        4 * HOUR_IN_SECONDS
                    );
                    $jsonData = json_decode($response['body'], true);
                    if (is_array($jsonData)) {
                        if ($wpHelper->isUpdateNeeded()) {
                            // new version is available
                            $noticeHelper->addNotice(
                                'pluginVersionCheck',
                                sprintf(
                                    __(
                                        'You have an outdated version of Visual Composer that requires an update. Update the plugin to get new features, improve performance, avoid compatibility issues, and keep your site secure. <a href="%s">Update</a>',
                                        'visualcomposer'
                                    ),
                                    admin_url('plugins.php')
                                ),
                                'warning',
                                false,
                                true
                            );
                        }
                    }
                }
            }
        } else {
            $noticeHelper->removeNotice('pluginVersionCheck');
        }
    }
}
