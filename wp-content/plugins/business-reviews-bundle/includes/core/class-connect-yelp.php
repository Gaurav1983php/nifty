<?php

namespace WP_Business_Reviews_Bundle\Includes\Core;

class Connect_Yelp {

    public function __construct() {
        add_action('init', array($this, 'request_handler'));
        add_action('brb_yelp_refresh', array($this, 'brb_yelp_refresh'));
    }

    public function request_handler() {
        global $wpdb;

        if (!empty($_GET['cf_action'])) {

            switch ($_GET['cf_action']) {

                case 'brb_connect_yelp':
                    if (current_user_can('manage_options')) {
                        if (isset($_POST['brb_wpnonce']) === false) {
                            $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.');
                            $response = compact('error');
                        } else {
                            check_admin_referer('brb_wpnonce', 'brb_wpnonce');

                            $lang = $_POST['lang'];
                            $api_key = $_POST['key'];
                            if (strlen($api_key) > 0) {
                                update_option('brb_yelp_api_key', $api_key);
                            }
                            $yelp_api_key = get_option('brb_yelp_api_key');

                            $business = rplg_json_urlopen(BRB_YELP_API . '/' . $_POST['id'], null, array(
                                'Authorization: Bearer ' . $yelp_api_key
                            ));
                            $reviews = rplg_json_urlopen($this->api_url($_POST['id'], $lang), null, array(
                                'Authorization: Bearer ' . $yelp_api_key
                            ));

                            $this->save_reviews($business, $reviews, $lang);

                            $result = array(
                                'id'      => $business->id,
                                'name'    => $business->name,
                                'photo'   => $business->photos[0],
                                'reviews' => $reviews
                            );
                            $status = 'success';

                            $response = compact('status', 'result');
                        }
                        header('Content-type: text/javascript');
                        echo json_encode($response);
                        die();
                    }
                break;

            }
        }
    }

    function brb_yelp_refresh($args) {
        $yelp_api_key = get_option('brb_yelp_api_key');
        if (!$yelp_api_key) {
            return;
        }

        $biz_id = $args[0];
        $biz_lang = $args[1];

        $business = rplg_json_urlopen(BRB_YELP_API . '/' . $biz_id, null, array(
            'Authorization: Bearer ' . $yelp_api_key
        ));
        $reviews = rplg_json_urlopen($this->api_url($biz_id, $biz_lang), null, array(
            'Authorization: Bearer ' . $yelp_api_key
        ));

        $this->save_reviews($business, $reviews, $lang);

        delete_transient('brb_yelp_refresh_' . join('_', $args));
    }

    function save_reviews($business, $reviews, $lang = null) {
        global $wpdb;

        $business_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . Database::BUSINESS_TABLE . " WHERE place_id = %s AND platform = %s", $business->id, 'yelp'));
        if ($business_id) {

            $wpdb->update($wpdb->prefix . Database::BUSINESS_TABLE, array(
                'name'         => $business->name,
                'photo'        => $business->image_url,
                'rating'       => $business->rating,
                'review_count' => $business->review_count
            ), array('ID' => $business_id));

        } else {

            $address = implode(", ", array(
                $business->location->address1,
                $business->location->city,
                $business->location->state,
                $business->location->zip_code
            ));
            $wpdb->insert($wpdb->prefix . Database::BUSINESS_TABLE, array(
                'place_id'     => $business->id,
                'name'         => $business->name,
                'photo'        => $business->image_url,
                'address'      => $address,
                'rating'       => $business->rating,
                'url'          => $business->url,
                'review_count' => $business->review_count,
                'platform'     => 'yelp'
            ));
            $business_id = $wpdb->insert_id;

        }

        if ($reviews && $reviews->reviews) {
            foreach ($reviews->reviews as $review) {

                $review_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE review_id = %s AND business_id = %d AND platform = %s", $review->id, $business_id, 'yelp'));
                if ($review_id) {

                    $wpdb->update($wpdb->prefix . Database::REVIEW_TABLE, array(
                        'rating'      => $review->rating,
                        'text'        => $review->text,
                        'author_name' => $review->user->name,
                        'author_img'  => $review->user->image_url
                    ), array('ID' => $review_id));

                } else {

                    $wpdb->insert($wpdb->prefix . Database::REVIEW_TABLE, array(
                        'business_id' => $business_id,
                        'review_id'   => $review->id,
                        'rating'      => $review->rating,
                        'text'        => $review->text,
                        'url'         => $review->url,
                        'language'    => $lang,
                        'time_str'    => $review->time_created,
                        'author_name' => $review->user->name,
                        'author_img'  => $review->user->image_url,
                        'platform'    => 'yelp'
                    ));

                }

            }
        }
    }

    function api_url($yelp_business_id, $reviews_lang = '') {
        $url = BRB_YELP_API . '/' . $yelp_business_id . '/reviews';
        if (strlen($reviews_lang) > 0) {
            $url = $url . '?locale=' . $reviews_lang;
        }
        return $url;
    }

}