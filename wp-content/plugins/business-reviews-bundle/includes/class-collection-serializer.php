<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Collection_Serializer {

    protected $post_type = 'brb_collection';

    public function __construct() {
        add_action('admin_post_brb_collection_save', array($this, 'collection_save'), 30);
    }

    public function collection_save() {

        $raw_data_array = wp_unslash($_POST[$this->post_type]);

        $post_id = wp_insert_post(array(
            'ID'           => $raw_data_array['post_id'],
            'post_title'   => $raw_data_array['title'],
            'post_content' => $raw_data_array['content'],
            'post_type'    => $this->post_type,
            'post_status'  => 'publish',
        ));

        // NOT: $referer = empty(wp_get_referer()) ? $raw_data_array['current_url'] : wp_get_referer();
        // COZ: Fatal error: Can't use function return value in write context in .../includes/class-collection-serializer.php on line ...
        $referer = wp_get_referer();
        $referer = empty($referer) ? $raw_data_array['current_url'] : wp_get_referer();

        wp_safe_redirect(
            add_query_arg(array(
                'brb_collection_id' => $post_id,
            ), $referer)
        );
        exit;
    }

}
