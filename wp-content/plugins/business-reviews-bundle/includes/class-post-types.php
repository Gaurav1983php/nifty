<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Post_Types {

    public function register() {
        add_action('init', array($this, 'register_post_types'));
    }

    public function register_post_types() {
        $this->register_collection_post_type();
    }

    public function register_collection_post_type() {
        $labels = array(
            'name'                  => _x('Collections', 'Post Type General Name', 'business-reviews-bundle'),
            'singular_name'         => _x('Collection', 'Post Type Singular Name', 'business-reviews-bundle'),
            'menu_name'             => __('Collections', 'business-reviews-bundle'),
            'name_admin_bar'        => __('Collection', 'business-reviews-bundle'),
            'archives'              => __('Collection Archives', 'business-reviews-bundle'),
            'attributes'            => __('Collection Attributes', 'business-reviews-bundle'),
            'parent_item_colon'     => __('Parent Collection:', 'business-reviews-bundle'),
            'all_items'             => __('Collections', 'business-reviews-bundle'),
            'add_new_item'          => __('Add New Collection', 'business-reviews-bundle'),
            'add_new'               => __('Add Collection', 'business-reviews-bundle'),
            'new_item'              => __('New Collection', 'business-reviews-bundle'),
            'edit_item'             => __('Edit Collection', 'business-reviews-bundle'),
            'update_item'           => __('Update Collection', 'business-reviews-bundle'),
            'view_item'             => __('View Collection', 'business-reviews-bundle'),
            'view_items'            => __('View Collections', 'business-reviews-bundle'),
            'search_items'          => __('Search Collections', 'business-reviews-bundle'),
            'not_found'             => __('Not found', 'business-reviews-bundle'),
            'not_found_in_trash'    => __('Not found in Trash', 'business-reviews-bundle'),
            'featured_image'        => __('Featured Image', 'business-reviews-bundle'),
            'set_featured_image'    => __('Set featured image', 'business-reviews-bundle'),
            'remove_featured_image' => __('Remove featured image', 'business-reviews-bundle'),
            'use_featured_image'    => __('Use as featured image', 'business-reviews-bundle'),
            'insert_into_item'      => __('Insert into item', 'business-reviews-bundle'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'business-reviews-bundle'),
            'items_list'            => __('Collections list', 'business-reviews-bundle'),
            'items_list_navigation' => __('Collections list navigation', 'business-reviews-bundle'),
            'filter_items_list'     => __('Filter items list', 'business-reviews-bundle'),
        );

        $args = array(
            'label'               => __('Collection', 'business-reviews-bundle'),
            'labels'              => $labels,
            'supports'            => array('title'),
            'taxonomies'          => array(),
            'hierarchical'        => false,
            'public'              => false,
            'show_in_rest'        => false,
            'show_ui'             => true,
            'show_in_menu'        => 'brb',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capabilities'        => array(
                'create_posts' => 'do_not_allow',
            ),
            'map_meta_cap'        => true,
        );

        register_post_type('brb_collection', $args);
    }
}
