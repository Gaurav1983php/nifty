<?php

namespace WP_Business_Reviews_Bundle\Includes\Admin;

class Admin_Menu {

    public function __construct() {

    }

    public function register() {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_menu', array($this, 'add_subpages'));
        add_filter('submenu_file', array($this, 'remove_submenu_pages'));
        add_filter('admin_body_class', array($this, 'add_admin_body_class'));
    }

    public function add_page() {
        add_menu_page(
            'Business Reviews Bundle',
            'Reviews Bundle',
            'edit_posts',
            'brb',
            '',
            BRB_ASSETS_URL . 'img/menu_icon.png',
            25
        );
    }

    public function add_subpages() {
        $builder_page = new Admin_Page(
            'brb',
            'Collection Builder',
            'Builder',
            'edit_posts',
            'brb-builder'
        );
        $builder_page->add_page();

        $setting_page = new Admin_Page(
            'brb',
            'Settings',
            'Settings',
            'manage_options',
            'brb-settings'
        );
        $setting_page->add_page();

        $setting_page = new Admin_Page(
            'brb',
            'Support',
            'Support',
            'manage_options',
            'brb-support'
        );
        $setting_page->add_page();
    }

    public function remove_submenu_pages($submenu_file) {
        global $plugin_page;

        $hidden_pages = array(
            'brb-builder',
        );

        if ($plugin_page && in_array($plugin_page, $hidden_pages)) {
            $submenu_file = 'edit.php?post_type=brb_collection';
        }

        foreach ($hidden_pages as $page) {
            remove_submenu_page('brb', $page);
        }

        return $submenu_file;
    }

    public function add_admin_body_class($classes) {
        $current_screen = get_current_screen();

        if (empty($current_screen)) {
            return;
        }

        if (false !== strpos($current_screen->id, 'brb')) {
            $classes .= ' brb-admin ';
        }

        return $classes;
    }

}
