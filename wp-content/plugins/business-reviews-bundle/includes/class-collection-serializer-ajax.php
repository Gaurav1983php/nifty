<?php

namespace WP_Business_Reviews_Bundle\Includes;

use WP_Business_Reviews_Bundle\Includes\Core\Core;
use WP_Business_Reviews_Bundle\Includes\View\View;

class Collection_Serializer_Ajax {

    public function __construct(Collection_Deserializer $collection_deserializer, Core $core, View $view) {
        $this->collection_deserializer = $collection_deserializer;
        $this->core = $core;
        $this->view = $view;

        add_action('wp_ajax_brb_collection_save_ajax', array($this, 'collection_save_ajax'));
    }

    public function collection_save_ajax() {

        $post_id = wp_insert_post(array(
            'ID'           => $_POST['post_id'],
            'post_title'   => $_POST['title'],
            'post_content' => $_POST['content'],
            'post_type'    => 'brb_collection',
            'post_status'  => 'publish',
        ));

        if (isset($post_id)) {
            $collection = $this->collection_deserializer->get_collection($post_id);

            $data = $this->core->get_reviews($collection);
            $businesses = $data['businesses'];
            $reviews = $data['reviews'];
            $options = $data['options'];

            echo $this->view->render($collection->ID, $businesses, $reviews, $options);
        }

        wp_die();
    }

}
