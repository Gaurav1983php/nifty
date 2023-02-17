<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Request_Handler {

    public function __construct(Collection_Shortcode $collection_shortcode, Assets $assets) {
        $this->collection_shortcode = $collection_shortcode;
        $this->assets = $assets;
    }

    public function register() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        if (!empty($_GET['cf_action'])) {
            switch ($_GET['cf_action']) {
                case 'brb_embed':
                    header('Content-type: application/javascript');
                    header('Access-Control-Allow-Origin: *');

                    $collection_id = $_GET['brb_collection_id'];
                    $callback      = $_GET['brb_callback'];
                    $response      = $this->collection_shortcode->init(array('id' => $collection_id));

                    if (strlen($response) > 0) {
                        $result = array(
                            'status' => 'success',
                            'data'   => $response,
                            'css'    => $this->assets->get_public_styles(),
                            'js'     => $this->assets->get_public_scripts()
                        );
                    } else {
                        $result = array(
                            'status' => 'error',
                            'data'   => 'Collection with ID ' . $collection_id . ' not found'
                        );
                    }
                    echo $this->embed_code($collection_id, $callback) . $callback . "(" . json_encode($result) . ");";
                    die();
                break;
            }
        }
    }

    private function embed_code($id, $cb) {
        return 'function ' . $cb . '(e){document.body.querySelector("#brb_collection_' . $id . '").innerHTML=e.data;if(e.css)for(var t=0;t<e.css.length;t++)brb_load_css(e.css[t]);if(e.js)for(var n=0;n<e.js.length;n++)brb_load_js(e.js[n])}function brb_load_js(e,t){var n=document.createElement("script");n.type="text/javascript",n.src=e,n.async="true",t&&n.addEventListener("load",function(e){t(null,e)},!1),document.getElementsByTagName("head")[0].appendChild(n)}function brb_load_css(e){var t=document.createElement("link");t.rel="stylesheet",t.href=e,document.getElementsByTagName("head")[0].appendChild(t)}';
    }

}
