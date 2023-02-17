<?php

namespace WP_Business_Reviews_Bundle\Includes\Core;

class Database {

    const BUSINESS_TABLE = 'brb_business';

    const REVIEW_TABLE = 'brb_review';

    public function create() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . self::BUSINESS_TABLE . " (".
               "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
               "place_id VARCHAR(127) NOT NULL,".
               "name VARCHAR(255) NOT NULL,".
               "photo VARCHAR(255),".
               "icon VARCHAR(255),".
               "address VARCHAR(255),".
               "rating DOUBLE PRECISION,".
               "url VARCHAR(255),".
               "website VARCHAR(255),".
               "review_count INTEGER,".
               "platform VARCHAR(255),".
               "updated BIGINT(20),".
               "PRIMARY KEY (`id`),".
               "UNIQUE INDEX brb_business_place_id (`place_id`)".
               ") " . $charset_collate . ";";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . self::REVIEW_TABLE . " (".
               "id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,".
               "business_id BIGINT(20) UNSIGNED NOT NULL,".
               "review_id VARCHAR(255) NOT NULL,".
               "rating INTEGER NOT NULL,".
               "text VARCHAR(10000),".
               "url VARCHAR(255),".
               "time INTEGER NOT NULL,".
               "time_str VARCHAR(20) NOT NULL,".
               "language VARCHAR(10),".
               "author_name VARCHAR(255),".
               "author_url VARCHAR(255),".
               "author_img VARCHAR(255),".
               "platform VARCHAR(255),".
               "PRIMARY KEY (`id`),".
               "INDEX brb_review_business_id (`business_id`)".
               ") " . $charset_collate . ";";

        dbDelta($sql);
    }

    public function drop() {
        global $wpdb;

        $wpdb->query("DROP TABLE " . $wpdb->prefix . self::BUSINESS_TABLE . ";");
        $wpdb->query("DROP TABLE " . $wpdb->prefix . self::REVIEW_TABLE . ";");
    }

}
