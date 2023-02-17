<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Assets {

    private $url;
    private $version;
    private $debug;

    private static $css_assets = array(
        'brb-admin-main-css'    => 'css/admin-main',
        'brb-public-main-css'   => 'css/public-main',
        'brb-public-swiper-css' => 'css/public-swiper.min',
    );

    private static $js_assets = array(
        'brb-admin-main-js'    => 'js/admin-main',
        'brb-admin-builder-js' => 'js/admin-builder',

        'brb-public-time-js'   => 'js/public-time',
        'brb-public-blazy-js'  => 'js/public-blazy.min',
        'brb-public-swiper-js' => 'js/public-swiper.min',
        'brb-public-main-js'   => 'js/public-main',
    );

    public function __construct($url, $version, $debug) {
        $this->url     = $url;
        $this->version = $version;
        $this->debug   = $debug;
    }

    public function register() {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'register_styles'));
            add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'register_styles'));
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
            $brb_demand_assets = get_option('brb_demand_assets');
            if (!$brb_demand_assets || $brb_demand_assets != 'true') {
                add_action('wp_enqueue_scripts', array($this, 'enqueue_public_styles'));
                add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
            }
            add_filter('script_loader_tag', array($this, 'add_async'), 10, 2);
        }
    }

    function add_async($tag, $handle) {
        $js_assets = array(
            'brb-admin-main-js'    => 'js/admin-main',
            'brb-admin-builder-js' => 'js/admin-builder',
            'brb-public-time-js'   => 'js/public-time',
            'brb-public-blazy-js'  => 'js/public-blazy.min',
            'brb-public-swiper-js' => 'js/public-swiper.min',
            'brb-public-main-js'   => 'js/public-main',
        );
        if (isset($handle) && array_key_exists($handle, $js_assets)) {
            return str_replace(' src', ' defer="defer" src', $tag);
        }
        return $tag;
    }

    public function register_styles() {
        $styles = array('brb-admin-main-css', 'brb-public-main-css', 'brb-public-swiper-css');
        $this->register_styles_loop($styles);
    }

    public function register_scripts() {
        $scripts = array('brb-admin-main-js', 'brb-public-main-js', 'brb-public-swiper-js');
        if ($this->debug) {
            array_push($scripts, 'brb-admin-builder-js');
            array_push($scripts, 'brb-public-time-js');
            array_push($scripts, 'brb-public-blazy-js');
        }
        $this->register_scripts_loop($scripts);
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style('brb-admin-main-css');
        wp_style_add_data('brb-admin-main-css', 'rtl', 'replace');

        /* Load swiper js coz it doesn't loaded by ajax request */
        wp_enqueue_style('brb-public-swiper-css');

        $this->enqueue_public_styles();
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-sortable');

        $vars = array(
            'wordpress'      => true,
            'googleAPIKey'   => get_option('brb_google_api_key'),
            'yelpAPIKey'     => get_option('brb_yelp_api_key'),
            'settingsUrl'    => admin_url('admin.php?page=brb-settings'),
            'handlerUrl'     => admin_url('options-general.php?page=brb'),
            'BRB_ASSETS_URL' => BRB_ASSETS_URL,
        );

        if ($this->debug) {
            wp_localize_script('brb-admin-builder-js', 'BRB_VARS', $vars);
            wp_enqueue_script('brb-admin-builder-js');
        } else {
            wp_localize_script('brb-admin-main-js', 'BRB_VARS', $vars);
        }
        wp_enqueue_script('brb-admin-main-js');

        /* Load swiper js coz it doesn't loaded by ajax request */
        wp_enqueue_script('brb-public-swiper-js');

        $this->enqueue_public_scripts();
    }

    public function enqueue_public_styles() {
        $brb_nocss = get_option('brb_nocss');
        if ($brb_nocss != 'true') {
            wp_enqueue_style('brb-public-main-css');
            wp_style_add_data('brb-public-main-css', 'rtl', 'replace');
        }
    }

    public function enqueue_public_scripts() {
        if ($this->debug) {
            wp_enqueue_script('brb-public-time-js');
            wp_enqueue_script('brb-public-blazy-js');
        }
        wp_enqueue_script('brb-public-main-js');
    }

    public function get_public_styles() {
        $assets = array();
        if ($this->debug) {
            array_push($assets, $this->get_css_asset('brb-public-swiper-css'));
        }
        array_push($assets, $this->get_css_asset('brb-public-main-css'));
        return $assets;
    }

    public function get_public_scripts() {
        $assets = array();
        if ($this->debug) {
            array_push($assets, $this->get_js_asset('brb-public-time-js'));
            array_push($assets, $this->get_js_asset('brb-public-blazy-js'));
            array_push($assets, $this->get_js_asset('brb-public-swiper-js'));
        }
        array_push($assets, $this->get_js_asset('brb-public-main-js'));
        return $assets;
    }

    private function register_styles_loop($styles) {
        foreach ($styles as $style) {
            wp_register_style($style, $this->get_css_asset($style), array(), $this->version);
        }
    }

    private function register_scripts_loop($scripts) {
        foreach ($scripts as $script) {
            wp_register_script($script, $this->get_js_asset($script), array(), $this->version);
        }
    }

    private function get_css_asset($asset) {
        return $this->url . ($this->debug ? 'src/' : '') . self::$css_assets[$asset] . '.css';
    }

    private function get_js_asset($asset) {
        return $this->url . ($this->debug ? 'src/' : '') . self::$js_assets[$asset] . '.js';
    }

}