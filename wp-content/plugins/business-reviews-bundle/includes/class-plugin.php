<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Admin\Admin_Menu;
use WP_Business_Reviews_Bundle\Includes\Admin\Admin_Tophead;
use WP_Business_Reviews_Bundle\Includes\Admin\Admin_Notice;
use WP_Business_Reviews_Bundle\Includes\Admin\Admin_Collection_Columns;

use WP_Business_Reviews_Bundle\Includes\Core\Database;
use WP_Business_Reviews_Bundle\Includes\Core\Core;
use WP_Business_Reviews_Bundle\Includes\Core\Connect_Google;
use WP_Business_Reviews_Bundle\Includes\Core\Connect_Yelp;

use WP_Business_Reviews_Bundle\Includes\View\View;
use WP_Business_Reviews_Bundle\Includes\View\View_Helper;
use WP_Business_Reviews_Bundle\Includes\View\View_Reviews;

final class Plugin {

    protected $plugin_name;
    protected $version;
    protected $activator;

    public function __construct() {
        $this->plugin_name  = 'business-reviews-bundle';
        $this->version      = BRB_VERSION;
    }

    public function register() {
        register_activation_hook(BRB_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(BRB_PLUGIN_FILE, array($this, 'deactivate'));

        add_action('plugins_loaded', array($this, 'register_services'));
    }

    public function register_services() {
        $this->init_language();

        $database = new Database();

        $activator = new Activator($database);
        $activator->register();

        $license = new License();

        $debug_info = new Debug_Info($activator);

        $assets = new Assets(BRB_ASSETS_URL, $this->version, get_option('brb_debug_mode') == '1');
        $assets->register();

        $post_types = new Post_Types();
        $post_types->register();

        $collection_deserializer = new Collection_Deserializer(new \WP_Query());

        $collection_page = new Collection_Page($collection_deserializer);
        $collection_page->register();

        $core = new Core();

        $view_helper = new View_Helper();
        $view_reviews = new View_Reviews($view_helper);
        $view = new View($view_reviews, $view_helper);

        $builder_page = new Builder_Page($collection_deserializer, $core, $view);
        $builder_page->register();

        $collection_shortcode = new Collection_Shortcode($collection_deserializer, $core, $view, $assets);
        $collection_shortcode->register();

        Collection_Widget::$static_collection_deserializer = $collection_deserializer;
        Collection_Widget::$static_core = $core;
        Collection_Widget::$static_view = $view;
        Collection_Widget::$static_assets = $assets;
        add_action('widgets_init', function() {
            register_widget('WP_Business_Reviews_Bundle\Includes\Collection_Widget');
        });

        $connect_google = new Connect_Google();

        $connect_yelp = new Connect_Yelp();

        $request_handler = new Request_Handler($collection_shortcode, $assets);
        $request_handler->register();

        if (is_admin()) {
            $collection_serializer = new Collection_Serializer();
            $collection_serializer_ajax = new Collection_Serializer_Ajax($collection_deserializer, $core, $view);

            $admin_notice = new Admin_Notice();
            $admin_notice->register();

            $admin_menu = new Admin_Menu();
            $admin_menu->register();

            $admin_tophead = new Admin_Tophead();
            $admin_tophead->register();

            $admin_collection_columns = new Admin_Collection_Columns($collection_deserializer);
            $admin_collection_columns->register();

            $settings_save = new Settings_Save($activator);
            $settings_save->register();

            $plugin_settings = new Plugin_Settings($debug_info);
            $plugin_settings->register();

            $plugin_support = new Plugin_Support();
            $plugin_support->register();
        }
    }

    public function init_language() {
        load_plugin_textdomain('brb', false, basename(dirname(BRB_PLUGIN_FILE)) . '/languages');
    }

    public function activate($network_wide = false) {
        add_option('brb_is_multisite', $network_wide);

        $activator = new Activator(new Database());
        $activator->activate();

        add_action('activated_plugin', array($this, 'activated'));
    }

    public function activated($plugin) {
        if($plugin == plugin_basename(BRB_PLUGIN_FILE)) {
            exit(wp_redirect(admin_url('edit.php?post_type=brb_collection')));
        }
    }

    public function deactivate() {
        $deactivator = new Deactivator();
        $deactivator->deactivate();
    }
}