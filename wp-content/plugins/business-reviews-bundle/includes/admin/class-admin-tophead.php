<?php

namespace WP_Business_Reviews_Bundle\Includes\Admin;

class Admin_Tophead {

    public function register() {
        add_action('wp_after_admin_bar_render', array($this, 'render'));
    }

    public function render() {
        $current_screen = get_current_screen();

        if (empty($current_screen)) {
            return;
        }

        if (strpos($current_screen->id, 'brb') !== false) {

            $current_screen->render_screen_meta();

            ?>
            <div class="brb-tophead">
                <div class="brb-tophead-title">
                    <img src="<?php esc_attr_e(BRB_ASSETS_URL . 'img/logo.png') ?>" alt="logo"> Business Reviews Bundle
                </div>
            </div>
            <?php
        }
    }
}
