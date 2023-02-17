<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Core\Database;

class Activator {

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function options() {
        return array(
            'brb_license',
            'brb_license_status',
            'brb_license_expired',
            'brb_latest_version',
            'brb_version',
            'brb_previous_version',
            'brb_active',
            'brb_demand_assets',
            'brb_nocss',
            'brb_google_api_key',
            'brb_google_places_api',
            'brb_yelp_api_key',
            'brb_auth_code',
            'brb_debug_mode',
        );
    }

    public function register() {
		add_action('init', array($this, 'check_version'));
	}

    public function check_version() {
		if (version_compare(get_option('brb_version'), BRB_VERSION, '<')) {
			$this->activate();
		}
	}

    public function activate() {
        $network_wide = get_option('brb_is_multisite');
        if ($network_wide) {
            $this->activate_multisite();
        } else {
            $this->activate_single_site();
        }
    }

    public function create_db() {
        $network_wide = get_option('brb_is_multisite');
        if ($network_wide) {
            $this->create_db_multisite();
        } else {
            $this->create_db_single_site();
        }
    }

    public function drop_db($multisite = false) {
        $network_wide = get_option('brb_is_multisite');
        if ($multisite && $network_wide) {
            $this->drop_db_multisite();
        } else {
            $this->drop_db_single_site();
        }
    }

    public function delete_all_options($multisite = false) {
        $network_wide = get_option('brb_is_multisite');
        if ($multisite && $network_wide) {
            $this->delete_all_options_multisite();
        } else {
            $this->delete_all_options_single_site();
        }
    }

    public function delete_all_collections($multisite = false) {
        $network_wide = get_option('brb_is_multisite');
        if ($multisite && $network_wide) {
            $this->delete_all_collections_multisite();
        } else {
            $this->delete_all_collections_single_site();
        }
    }

    private function activate_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->activate_single_site();
            restore_current_blog();
        }
    }

    private function activate_single_site() {
        $current_version     = BRB_VERSION;
        $last_active_version = get_option('brb_version');

        if (empty($last_active_version)) {
            $this->first_install();
            update_option('brb_version', $current_version);
            update_option('brb_auth_code', $this->random_str(127));
        } elseif ($last_active_version !== $current_version) {
            $this->exist_install($current_version, $last_active_version);
            update_option('brb_version', $current_version);
            update_option('brb_previous_version', $last_active_version);
        }
    }

    private function first_install() {
        $this->database->create();
        add_option('brb_active', '1');
        add_option('brb_license', '');
    }

    private function exist_install($current_version, $last_active_version) {
        global $wpdb;
        switch($last_active_version) {
            case version_compare($last_active_version, '1.3.3', '<'):
                update_option('brb_auth_code', $this->random_str(127));
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . Database::BUSINESS_TABLE . " MODIFY COLUMN place_id VARCHAR(127)");
                $wpdb->query("ALTER TABLE " . $wpdb->prefix . Database::REVIEW_TABLE . " MODIFY COLUMN review_id VARCHAR(255)");
            break;
        }
    }

    private function create_db_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->create_db_single_site();
            restore_current_blog();
        }
    }

    private function create_db_single_site() {
        $this->database->create();
    }

    private function drop_db_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->drop_db_single_site();
            restore_current_blog();
        }
    }

    private function drop_db_single_site() {
        $this->database->drop();
    }

    private function delete_all_options_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->delete_all_options_single_site();
            restore_current_blog();
        }
    }

    private function delete_all_options_single_site() {
        foreach ($this->options() as $opt) {
            delete_option($opt);
        }
    }

    private function delete_all_collections_multisite() {
        global $wpdb;

        $site_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

        foreach($site_ids as $site_id) {
            switch_to_blog($site_id);
            $this->delete_all_collections_single_site();
            restore_current_blog();
        }
    }

    private function delete_all_collections_single_site() {
        $args = array(
			'post_type'      => 'brb_collection',
			'post_status'    => array('any', 'trash'),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		$query     = new \WP_Query($args);
		$brb_posts = $query->posts;

		if (!empty($brb_posts)) {
			foreach ($brb_posts as $brb_post) {
				wp_delete_post($brb_post, true);
			}
		}
    }

    private function random_str($len) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charlen = strlen($chars);
        $randstr = '';
        for ($i = 0; $i < $len; $i++) {
            $randstr .= $chars[rand(0, $charlen - 1)];
        }
        return $randstr;
    }

}
