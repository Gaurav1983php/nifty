<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Core\Core;
use WP_Business_Reviews_Bundle\Includes\View\View;

class Collection_Widget extends \WP_Widget {

    public static $static_collection_deserializer;

    public static $static_core;

    public static $static_view;

    public static $static_assets;

    public function __construct() {
        parent::__construct(
            'brb_widget',
            __('Business Reviews Bundle', 'business-reviews-bundle'),
            array(
                'classname'   => 'brb-collection-widget',
                'description' => __(
                    'Display Business Reviews',
                    'business-reviews-bundle'
                ),
            )
        );

        $this->collection_deserializer = self::$static_collection_deserializer;
        $this->core = self::$static_core;
        $this->view = self::$static_view;
        $this->assets = self::$static_assets;
    }

    public function widget($args, $instance) {
        if (get_option('brb_active') === '0') {
            return;
        }

        if (!isset($instance['collection_id']) || strlen($instance['collection_id']) < 1) {
            return null;
        }

        $brb_demand_assets = get_option('brb_demand_assets');
        if ($brb_demand_assets || $brb_demand_assets == 'true') {
            $this->assets->enqueue_public_styles();
            $this->assets->enqueue_public_scripts();
        }

        $collection = $this->collection_deserializer->get_collection($instance['collection_id']);

        if (!$collection) {
            return null;
        }

        $data = $this->core->get_reviews($collection);

        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
        }

        $businesses = $data['businesses'];
        $reviews = $data['reviews'];
        $options = $data['options'];
        if (count($businesses) > 0 || count($reviews) > 0) {
            echo $this->view->render($collection->ID, $businesses, $reviews, $options);
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $wp_query = new \WP_Query();
        $wp_query->query(array(
            'post_type'      => 'brb_collection',
            'posts_per_page' => 100,
            'no_found_rows'  => true,
        ));
        $collections = $wp_query->posts;

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'business-reviews-bundle'); ?>
            </label>
            <input
                type="text"
                id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                class="widefat"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                value="<?php if (isset($instance['title'])) { echo esc_attr($instance['title']); } ?>"
            >
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('collection_id')); ?>">
                <?php esc_html_e('Collection:', 'business-reviews-bundle'); ?>
            </label>

            <select
                id="<?php echo esc_attr($this->get_field_id('collection_id')); ?>"
                name="<?php echo esc_attr($this->get_field_name('collection_id')); ?>"
                style="display:block;width:100%"
            >
                <option value="">Select Collection</option>
                <?php foreach ($collections as $collection) : ?>
                    <option
                        value="<?php echo esc_attr($collection->ID); ?>"
                        <?php if (isset($instance['collection_id'])) { selected($collection->ID, $instance['collection_id']); } ?>
                    >
                        <?php echo esc_html('ID ' . $collection->ID . ': ' . $collection->post_title); ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['collection_id'] = sanitize_text_field(
            $new_instance['collection_id']
        );
        return $instance;
    }
}
