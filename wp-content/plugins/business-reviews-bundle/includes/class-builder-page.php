<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Core\Core;
use WP_Business_Reviews_Bundle\Includes\View\View;

class Builder_Page {

    public function __construct(Collection_Deserializer $collection_deserializer, Core $core, View $view) {
        $this->collection_deserializer = $collection_deserializer;
        $this->core = $core;
        $this->view = $view;
    }

    public function register() {
        add_action('brb_admin_page_brb-builder', array($this, 'init'));
    }

    public function init() {
        if (isset($_GET['brb_notice'])) {
            $this->add_admin_notice();
        }

        $collection = null;
        if (isset($_GET['brb_collection_id'])) {
            $collection = $this->collection_deserializer->get_collection($_GET['brb_collection_id']);
        }

        $this->render($collection);
    }

    public function add_admin_notice($notice_code = 0) {
        //TODO
    }

    public function render($collection) {
        global $wp_version;
        if (version_compare($wp_version, '3.5', '>=')) {
            wp_enqueue_media();
        }

        wp_nonce_field('brb_wpnonce', 'brb_nonce');

        $collection_id = '';
        $collection_post_title = '';
        $collection_content = '';
        $collection_inited = false;
        $businesses = null;
        $reviews = null;

        if ($collection != null) {
            $collection_id = $collection->ID;
            $collection_post_title = $collection->post_title;
            $collection_content = trim($collection->post_content);

            $data = $this->core->get_reviews($collection);
            $businesses = $data['businesses'];
            $reviews = $data['reviews'];
            $options = $data['options'];
            if (isset($businesses) && count($businesses) || isset($reviews) && count($reviews)) {
                $collection_inited = true;
            }
        }

        $google_places_api = get_option('brb_google_places_api');

        ?>
        <div class="brb-builder">
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=brb_collection_save')); ?>">
                <input type="hidden" id="brb_post_id" name="brb_collection[post_id]" value="<?php echo esc_attr($collection_id); ?>">
                <input type="hidden" id="brb_current_url" name="brb_collection[current_url]" value="<?php echo home_url($_SERVER['REQUEST_URI']); ?>">
                <div class="brb-builder-workspace">
                    <div class="brb-toolbar">
                        <div class="brb-toolbar-title">
                            <input class="brb-toolbar-title-input" type="text" id="brb_title" name="brb_collection[title]" value="<?php if (isset($collection_post_title)) { echo $collection_post_title; } ?>" placeholder="Enter a collection name" maxlength="255" autofocus>
                        </div>
                        <div class="brb-toolbar-control">
                            <?php if ($collection_inited) { ?>
                            <label><span id="brb_sc_msg">Shortcode </span><input id="brb_sc" type="text" value="[brb_collection id=&quot;<?php echo esc_attr($collection_id); ?>&quot;]" data-brb-shortcode="[brb_collection id=&quot;<?php echo esc_attr($collection_id); ?>&quot;]" onclick="this.select(); document.execCommand('copy'); window.brb_sc_msg.innerHTML = 'Shortcode Copied! ';" readonly/></label>
                            <div class="brb-toolbar-options">
                                <label title="Sometimes, you need to use this shortcode in PHP, for instance in header.php or footer.php files, in this case use this option"><input type="checkbox" onclick="var el = window.brb_sc; if (this.checked) { el.value = '&lt;?php echo do_shortcode( \'' + el.getAttribute('data-brb-shortcode') + '\' ); ?&gt;'; } else { el.value = el.getAttribute('data-brb-shortcode'); } el.select();document.execCommand('copy'); window.brb_sc_msg.innerHTML = 'Shortcode Copied! ';"/>Use in PHP</label>
                                <label title="You can use this code to show reviews on any site (not in WordPress), for instance on HTML Landing Page"><input type="checkbox" onclick="var el = window.brb_sc; if (this.checked) { el.value = '<div id=&#34;brb_collection_<?php echo esc_attr($collection_id); ?>&#34;></div><script type=&#34;text/javascript&#34;>!function(e){var c=document.createElement(&#34;script&#34;);c.src=e,document.body.appendChild(c)}(&#34;<?php echo site_url(); ?>?cf_action=brb_embed&brb_collection_id=<?php echo esc_attr($collection_id); ?>&brb_callback=brb_&#34;+(new Date).getTime());</script>'; } else { el.value = el.getAttribute('data-brb-shortcode'); } el.select();document.execCommand('copy'); window.brb_sc_msg.innerHTML = 'Shortcode Copied! ';"/>Use as embedded code in HTML/JS</label>
                            </div>
                            <?php } ?>
                            <button id="collsave" type="submit" class="button button-primary">Save & Refresh</button>
                        </div>
                    </div>
                    <div class="brb-builder-preview">
                        <textarea id="brb-builder-connection" name="brb_collection[content]" style="display:none"><?php echo $collection_content; ?></textarea>
                        <div id="brb_collection_preview">
                            <?php
                            if ($collection_inited) {
                                echo $this->view->render($collection_id, $businesses, $reviews, $options);
                            } else {
                                ?>To show reviews in this preview, firstly connect services on the right menu (Google, Facebook and etc.) and click '<b>Save & Refresh</b>' button. Then you can use this created collection as a widget or shortcode.<?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id="brb-builder-option" class="brb-builder-option"></div>
            </form>
        </div>
        <script>
        jQuery(document).ready(function($) {
            function rplg_builder_init_listener(attempts) {
                if (!window.rplg_builder_init) {
                    if (attempts > 0) {
                        setTimeout(function() { rplg_builder_init_listener(attempts - 1); }, 200);
                    }
                    return;
                }
                rplg_builder_init($, {
                    el: '#brb-builder-option',
                    auth_code: '<?php echo get_option('brb_auth_code'); ?>',
                    use_gpa: <?php echo $google_places_api === true || $google_places_api == 'true' ? 'true' : 'false'; ?>,
                    <?php if (strlen($collection_content) > 0) { echo 'conns: ' . $collection_content; } ?>
                });
            }
            rplg_builder_init_listener(20);
        });
        </script>
        <style>
            .update-nag { display: none; }
        </style>
        <?php
    }
}
