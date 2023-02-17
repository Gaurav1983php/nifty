<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Core\Database;

class Settings_Save {

    public function __construct(Activator $activator) {
        $this->activator = $activator;
    }

    public function register() {
        add_action('admin_post_brb_settings_save', array($this, 'save_from_post_array'));
    }

    public function save_from_post_array() {
        global $wpdb;

        if (!function_exists('wp_nonce_field')) {
            function wp_nonce_field() {}
        }

        if (!current_user_can('manage_options')) {
            die('The account you\'re logged in to doesn\'t have permission to access this page.');
        }

        if (!empty($_POST)) {
            $nonce_result_check = $this->check_nonce();
            if ($nonce_result_check === false) {
                die('Unable to save changes. Make sure you are accessing this page from the Wordpress dashboard.');
            }
        }

        $notice_code = null;

        if (isset($_POST['active']) && isset($_GET['active'])) {
            $active = $_GET['active'] == '1' ? '1' : '0';
            update_option('brb_active', $active);
            $notice_code = 'settings_active_' . $active;
        }

        if (isset($_POST['save'])) {
            $fields = array('brb_demand_assets', 'brb_nocss', 'brb_license', 'brb_google_api_key', 'brb_google_places_api', 'brb_yelp_api_key');
            foreach ($fields as $key => $value) {
                if (isset($_POST[$value])) {
                    update_option($value, trim(sanitize_text_field($_POST[$value])));
                }
            }
            $notice_code = 'settings_save';
        }

        if (isset($_POST['create_db'])) {
            $this->activator->create_db();
            $notice_code = 'settings_create_db';
        }

        /*if (isset($_POST['reset'])) {
            $this->activator->delete_all_options();
            $notice_code = 'settings_reset';
        }*/

        if (isset($_POST['install'])) {
            $install_multisite = $_POST['install_multisite'];
            $this->activator->drop_db($install_multisite);
            $this->activator->delete_all_options($install_multisite);
            $this->activator->delete_all_collections($install_multisite);
            $this->activator->activate();
            $notice_code = 'settings_install';
        }

        if (isset($_POST['reset_all'])) {
            $reset_all_multisite = $_POST['reset_all_multisite'];
            $this->activator->drop_db($reset_all_multisite);
            $this->activator->delete_all_options($reset_all_multisite);
            $this->activator->delete_all_collections($reset_all_multisite);
            $notice_code = 'settings_reset_all';
        }

        if (isset($_POST['brb_license'])) {
            $brb_license = trim(sanitize_text_field($_POST['brb_license']));
            if (strlen($brb_license) > 0) {
                $request = wp_remote_post('https://admin.richplugins.com/plugins/license-activate', array(
                    'timeout'   => 15,
                    'sslverify' => false,
                    'body'      => array(
                        'license' => $brb_license,
                        'slug'    => 'brb',
                        'plugin'  => 'Business Reviews Bundle',
                        'active'  => '1',
                        'siteurl' => get_option('siteurl')
                    )
                ));

                if (!is_wp_error($request)) {
                    $request = json_decode(wp_remote_retrieve_body($request));
                }
                if ($request) {
                    if ($request->status == 'error') {
                        update_option('brb_notice_msg', $request->msg);
                        $notice_code = 'custom_msg';
                    }
                }
                delete_transient('license_status_' . $brb_license);
            }
        }

        $brb_license = get_option('brb_license');
        if (isset($_POST['brb_license_deactive']) && strlen($brb_license) > 0) {
            $request = wp_remote_post('https://admin.richplugins.com/plugins/license-activate', array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => array(
                    'license' => $brb_license,
                    'slug'    => 'brb',
                    'plugin'  => 'Business Reviews Bundle',
                    'active'  => '0'
                )
            ));

            if (!is_wp_error($request)) {
                $request = json_decode(wp_remote_retrieve_body($request));
            }
            if ($request) {
                if ($request->status == 'error') {
                    update_option('brb_notice_msg', $request->msg);
                    $notice_code = 'custom_msg';
                }
            }
            delete_transient('license_status_' . $brb_license);
        }

        if (isset($_POST['debug_mode'])) {
            $debug_mode = $_POST['debug_mode'] == 'Enable' ? '1' : '0';
            update_option('brb_debug_mode', $debug_mode);
            $notice_code = 'settings_debug_mode_' . $debug_mode;
        }

        $this->redirect_to_tab($notice_code);
    }

    public function redirect_to_tab($notice_code = '') {
        if (empty($_GET['brb_tab'])) {
            wp_safe_redirect(wp_get_referer());
            exit;
        }

        $tab = sanitize_text_field(wp_unslash($_GET['brb_tab']));

        $query_args = array(
            'brb_tab' => $tab,
        );

        if (!empty($notice_code)) {
            $query_args['brb_notice'] = $notice_code;
        }

        wp_safe_redirect(add_query_arg($query_args, wp_get_referer()));
        exit;
    }

    private function check_nonce() {
        $nonce_actions = array('active', 'save', 'create_db', 'reset', 'reset_all', 'debug_mode');
        $nonce_form_prefix = 'brb-form_nonce_';
        $nonce_action_prefix = 'brb-wpnonce_';
        foreach ($nonce_actions as $key => $value) {
            if (isset($_POST[$nonce_form_prefix.$value])) {
                check_admin_referer($nonce_action_prefix.$value, $nonce_form_prefix.$value);
                return true;
            }
        }
        return false;
    }
}
