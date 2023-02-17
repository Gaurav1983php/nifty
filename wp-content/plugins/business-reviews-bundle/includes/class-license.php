<?php

namespace WP_Business_Reviews_Bundle\Includes;

if (!class_exists('EDD_SL_Plugin_Updater')) {
    include_once(dirname(__FILE__) . '/license/EDD_SL_Plugin_Updater.php');
}

class License {

    private $url;
    private $version;
    private $suffix;

    public function __construct() {
        add_action('admin_init', array($this, 'plugin_updater'));
    }

    public function plugin_updater() {
        $brb_license = get_option('brb_license');
        if (strlen($brb_license) < 1) {
            update_option('brb_license_status', '');
            update_option('brb_license_expired', '');
            update_option('brb_latest_version', '');
            return;
        }

        $request_cache_key = 'license_status_' . $brb_license;
        if (($request = get_transient($request_cache_key)) === false) {
            $request = wp_remote_post('https://admin.richplugins.com/plugins/license-status', array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => array(
                    'license' => $brb_license,
                    'slug'    => 'brb',
                    'plugin'  => 'Business Reviews Bundle'
                )
            ));
            if (!is_wp_error($request)) {
                $request = json_decode(wp_remote_retrieve_body($request));
            }
            set_transient($request_cache_key, $request, 60 * 60 * 3);
        }

        if ($request) {
            if (isset($request->status) && $request->status == 'success') {
                update_option('brb_license_status',  $request->license_status);
                update_option('brb_license_expired', $request->license_expired);
                update_option('brb_renewal_status',  $request->renewal_status);
                update_option('brb_renewal_date',    $request->renewal_date);
                update_option('brb_latest_version',  $request->plugin_version);
            } else {
                update_option('brb_license_status',  '');
                update_option('brb_license_expired', '');
                update_option('brb_renewal_status',  '');
                update_option('brb_renewal_date',    '');
                update_option('brb_latest_version',  '');
            }
        }

        $brb_plugin_meta = get_plugin_data(untrailingslashit(plugin_dir_path(BRB_PLUGIN_FILE)) . '/brb.php', false );
        $edd_updater = new \EDD_SL_Plugin_Updater('https://admin.richplugins.com/plugins/update-check', plugin_basename(BRB_PLUGIN_FILE), array(
            'slug'      => 'brb',
            'author'    => 'RichPlugins',
            'version'   => $brb_plugin_meta['Version'],
            'license'   => $brb_license
        ));
    }

}