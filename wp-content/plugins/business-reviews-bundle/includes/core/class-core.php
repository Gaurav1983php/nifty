<?php

namespace WP_Business_Reviews_Bundle\Includes\Core;

class Core {

    protected $STARS = array('STAR_RATING_UNSPECIFIED' => 0, 'ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5);

    public function __construct() {
    }

    public function get_reviews($collection) {
        $connection            = json_decode($collection->post_content);
        $cache_time            = isset($connection->options->cache) ? $connection->options->cache : null;
        $data_cache_key        = 'brb_collection_' . BRB_VERSION . '_' . $collection->ID . '_reviews';
        $connection_cache_key  = 'brb_collection_' . BRB_VERSION . '_' . $collection->ID . '_options';

        $data                  = get_transient($data_cache_key);
        $cached_connection     = get_transient($connection_cache_key);
        $serialized_connection = serialize($connection);

        if ($data === false || $serialized_connection !== $cached_connection || !$cache_time) {
            $expiration = $cache_time;
            switch ($expiration) {
                case '1':
                    $expiration = 3600;
                    break;
                case '3':
                    $expiration = 3600 * 3;
                    break;
                case '6':
                    $expiration = 3600 * 6;
                    break;
                case '12':
                    $expiration = 3600 * 12;
                    break;
                case '24':
                    $expiration = 3600 * 24;
                    break;
                case '48':
                    $expiration = 3600 * 48;
                    break;
                case '168':
                    $expiration = 3600 * 168;
                    break;
                default:
                    $expiration = 3600 * 24;
            }
            $data = $this->get_data($connection);
            set_transient($data_cache_key, $data, $expiration);
            set_transient($connection_cache_key, $serialized_connection, $expiration);
        }
        return $data;
    }

    public function get_data($connection) {

        if ($connection == null) {
            return null;
        }

        foreach ($this->get_default_settings() as $field => $value) {
            $connection->options->{$field} = isset($connection->options->{$field}) ? esc_attr($connection->options->{$field}) : $value;
        }
        $options = $connection->options;

        if (isset($connection->connections) && is_array($connection->connections)) {
            $google_business = null;
            $facebook_business = null;
            $yelp_business = null;
            foreach ($connection->connections as $conn) {
                switch ($conn->platform) {
                    case 'google':
                        if (!$google_business) $google_business = array();
                        array_push($google_business, $conn);
                        break;
                    case 'facebook':
                        if (!$facebook_business) $facebook_business = array();
                        array_push($facebook_business, $conn);
                        break;
                    case 'yelp':
                        if (!$yelp_business) $yelp_business = array();
                        array_push($yelp_business, $conn);
                        break;
                }
            }
        } else {
            $google_business     = isset($connection->google)   ? $connection->google   : null;
            $facebook_business   = isset($connection->facebook) ? $connection->facebook : null;
            $yelp_business       = isset($connection->yelp)     ? $connection->yelp     : null;
        }

        $sort                = $options->sort;
        $min_filter          = $options->min_filter;
        $min_letter          = $options->min_letter;
        $top_reviews         = urldecode($options->top_reviews);
        $word_filter         = urldecode($options->word_filter);
        $word_exclude        = urldecode($options->word_exclude);

        $header_merge_social = $options->header_merge_social;
        $header_hide_social  = $options->header_hide_social;

        $google_biz          = array();
        $google_reviews      = array();

        $facebook_biz        = array();
        $facebook_reviews    = array();

        $yelp_biz            = array();
        $yelp_reviews        = array();

        if ($google_business != null) {
            $schedule_step = 60 * 60 * 12;
            foreach ($google_business as $biz) {
                $google_api_limit = strlen($options->google_api_limit) > 0 ? $options->google_api_limit : '100';
                $review_count_manual = isset($biz->review_count) ? $biz->review_count : null;
                if (substr($biz->id, 0, 9) === "accounts/") {
                    $result = $this->get_google_reviews_all($biz, $options->google_success_api, $google_api_limit);
                } else {
                    $result = $this->get_google_reviews($biz, $review_count_manual);
                }
                array_push($google_biz, $result['business']);
                $google_reviews = array_merge($google_reviews, $result['reviews']);

                if (isset($biz->refresh) && $biz->refresh) {
                    $args = array($biz->id, $biz->lang);
                    $schedule_cache_key = 'brb_google_refresh_' . join('_', $args);
                    if (get_transient($schedule_cache_key) === false) {
                        wp_schedule_single_event(time() + $schedule_step, 'brb_google_refresh', array($args));
                        set_transient($schedule_cache_key, $schedule_cache_key, $schedule_step + 60 * 10);
                    }
                }
                $schedule_step = $schedule_step + 60 * 60 * 12;
            }
        }
        if ($facebook_business != null) {
            $fb_api_limit = strlen($options->fb_api_limit) > 0 ? $options->fb_api_limit : '100';
            foreach ($facebook_business as $biz) {
                $access_token = isset($biz->props) && isset($biz->props->access_token) ? $biz->props->access_token : $biz->access_token;
                $result = $this->get_facebook_reviews($biz, $access_token, $options->fb_success_api, $options->fb_rating_calc, $options->reviewer_avatar_size, $fb_api_limit);
                if ($result['business'] != null) {
                    array_push($facebook_biz, $result['business']);
                }
                if ($result['reviews'] != null) {
                    $facebook_reviews = array_merge($facebook_reviews, $result['reviews']);
                }
            }
        }
        if ($yelp_business != null) {
            $schedule_step = 60 * 60 * 12;
            foreach ($yelp_business as $biz) {
                $result = $this->get_yelp_reviews($biz);
                array_push($yelp_biz, $result['business']);
                $yelp_reviews = array_merge($yelp_reviews, $result['reviews']);

                if (isset($biz->refresh) && $biz->refresh) {
                    $args = array($biz->id, $biz->lang);
                    $schedule_cache_key = 'brb_yelp_refresh_' . join('_', $args);
                    if (get_transient($schedule_cache_key) === false) {
                        wp_schedule_single_event(time() + $schedule_step, 'brb_yelp_refresh', array($args));
                        set_transient($schedule_cache_key, $schedule_cache_key, $schedule_step + 60 * 10);
                    }
                }
                $schedule_step = $schedule_step + 60 * 60 * 12;
            }
        }

        $social_biz = array();
        if ($header_merge_social) {
            if (count($google_biz) > 0) {
                array_push($social_biz, $this->merge_biz($google_biz));
            }
            if (count($facebook_biz) > 0) {
                array_push($social_biz, $this->merge_biz($facebook_biz));
            }
            if (count($yelp_biz) > 0) {
                array_push($social_biz, $this->merge_biz($yelp_biz));
            }
        } else {
            $social_biz = array_merge($google_biz, $facebook_biz, $yelp_biz);
        }

        $businesses = array();
        if (!$header_hide_social) {
            $businesses = $social_biz;
        }

        if ($options->summary_rating && count($social_biz) > 0) {
            $first_biz = $social_biz[0];
            $summary_name  = isset($options->summary_name)  && strlen($options->summary_name)  > 0 ? $options->summary_name  : $first_biz->name;
            $summary_url   = isset($options->summary_url)   && strlen($options->summary_url)   > 0 ? $options->summary_url   : $first_biz->url;
            $summary_photo = isset($options->summary_photo) && strlen($options->summary_photo) > 0 ? $options->summary_photo : $first_biz->photo;
            array_unshift($businesses, $this->merge_biz($social_biz, 'summary', $summary_name, $summary_url, $summary_photo, 'summary'));
        }

        // Merge reviews
        $reviews = array_merge($google_reviews, $facebook_reviews, $yelp_reviews);

        // Filtering by rating, text length and words
        foreach ($reviews as $i => $review) {
            if ($review->rating < $min_filter || ($min_letter && isset($review->text) && strlen($review->text) < $min_letter)) {
                unset($reviews[$i]);
            }
            if ($word_filter) {
                $word_found = false;
                $words = explode(',', $word_filter);
                foreach ($words as $word) {
                    if (strrpos($review->author_name, trim($word)) !== false || strrpos($review->text, trim($word)) !== false) {
                        $word_found = true;
                    }
                }
                if (!$word_found) {
                    unset($reviews[$i]);
                }
            }
            if ($word_exclude) {
                $exclude = false;
                $words = explode(',', $word_exclude);
                foreach ($words as $word) {
                    if (strrpos($review->author_name, trim($word)) !== false || strrpos($review->text, trim($word)) !== false) {
                        $exclude = true;
                    }
                }
                if ($exclude) {
                    unset($reviews[$i]);
                }
            }
        }

        // Normalize reviews array indexes after unset filter above
        $reviews = array_values($reviews);

        // Sorting
        switch ($sort) {
            case '1':
                usort($reviews, array($this, 'sort_recent'));
                break;
            case '2':
                usort($reviews, array($this, 'sort_oldest'));
                break;
            case '3':
                usort($reviews, array($this, 'sort_highest'));
                break;
            case '4':
                usort($reviews, array($this, 'sort_lowest'));
                break;
            case '5':
                shuffle($reviews);
                break;
            case '6':
                $buckets = array_count_values(array_map(function($r) { return $r->provider; }, $reviews));
                $i = 0;
                while (array_sum($buckets) > 0) {
                    foreach ($buckets as $index => $value) {
                        if ($value > 0) {
                            $buckets[$index] = $value - 1;
                            for ($j = $i; $j < count($reviews); $j++) {
                                if ($reviews[$j]->provider == $index) {
                                    $review = $reviews[$j];
                                    $reviews[$j] = $reviews[$i];
                                    $reviews[$i++] = $review;
                                    break;
                                }
                            }
                        }
                    }
                }
                break;
            default:
                //TODO
        }

        // Lift top reviews
        foreach ($reviews as $i => $review) {
            if ($top_reviews) {
                $top_review = false;
                $words = explode(',', $top_reviews);
                foreach ($words as $word) {
                    if (strrpos($review->author_name, trim($word)) !== false || strrpos($review->text, trim($word)) !== false) {
                        $top_review = true;
                    }
                }
                if ($top_review) {
                    unset($reviews[$i]);
                    array_unshift($reviews, $review);
                }
            }
        }

        // Normalize reviews array indexes after unset filter above
        $reviews = array_values($reviews);

        if ($options->reviews_limit > 0) {
            $reviews = array_slice($reviews, 0, $options->reviews_limit);
        }

        return array('businesses' => $businesses, 'reviews' => $reviews, 'options' => $options);
    }

    public static function get_default_settings() {
        return array(
            'stars_in_body'             => false,
            'stars_inline'              => false,
            'review_style'              => '',

            'view_mode'                 => 'list',
            'sort'                      => '',
            'min_filter'                => '',
            'top_reviews'               => '',
            'word_filter'               => '',
            'word_exclude'              => '',
            'pagination'                => '',
            'min_letter'                => '',
            'text_size'                 => '',
            'time_format'               => '',
            'hide_avatar'               => false,
            'disable_user_link'         => false,
            'hide_name'                 => false,
            'short_last_name'           => false,
            'disable_review_time'       => false,
            'rating_temp_on'            => true,
            'review_temp_on'            => false,
            'rating_temp'               => '',
            'review_temp'               => '',

            'summary_rating'            => false,
            'summary_photo'             => '',
            'summary_name'              => '',
            'summary_url'               => '',

            'header_hide_photo'         => false,
            'header_hide_name'          => false,
            'header_hide_count'         => false,
            'header_hide_seeall'        => false,
            'header_hide_write'         => false,
            'header_merge_social'       => false,
            'header_hide_social'        => false,

            'flash_pos'                 => 'left',
            'flash_start'               => '3',
            'flash_visible'             => '5',
            'flash_invisible'           => '5',
            'flash_user_photo'          => false,
            'flash_hide_logo'           => false,

            'badge_display_block'       => '',
            'badge_center'              => '',
            'badge_space_between'       => '',
            'badge_click'               => 'sidebar',
            'badge_close'               => false,
            'hide_float_badge'          => false,

            'slider_effect'             => 'slide',
            'slider_speed'              => '5',
            'slider_count'              => '3',
            'slider_space_between'      => '40',
            'slider_review_height'      => '',
            'slider_hide_pagin'         => false,
            'slider_hide_nextprev'      => false,
            'slider_desktop_breakpoint' => 1024,
            'slider_desktop_count'      => 3,
            'slider_tablet_breakpoint'  => 800,
            'slider_tablet_count'       => 2,
            'slider_mobile_breakpoint'  => 500,
            'slider_mobile_count'       => 1,

            'schema_rating'             => '',
            'schema_address_country'    => '',
            'schema_address_locality'   => '',
            'schema_address_region'     => '',
            'schema_address_zip'        => '',
            'schema_address_street'     => '',
            'schema_price_range'        => '',
            'schema_phone'              => '',

            'dark_theme'                => false,
            'centred'                   => false,
            'max_width'                 => '',
            'max_height'                => '',

            'open_link'                 => true,
            'nofollow_link'             => true,
            'lazy_load_img'             => true,
            'google_success_api'        => true,
            'google_def_rev_link'       => false,
            'fb_success_api'            => true,
            'fb_rating_calc'            => false,
            'reviewer_avatar_size'      => 56,
            'cache'                     => 12,
            'google_api_limit'          => 100,
            'fb_api_limit'              => 100,
            'reviews_limit'             => '',
        );
    }

    public function get_google_reviews($google_biz, $review_count_manual) {
        global $wpdb;

        $google_place = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::BUSINESS_TABLE . " WHERE place_id = %s AND platform = %s", $google_biz->id, 'google')
        );

        if (strlen($google_biz->lang) > 0) {
            $google_reviews = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE business_id = %d AND language = %s ORDER BY time DESC, rating DESC", $google_place->id, $google_biz->lang)
            );
        } else {
            $google_reviews = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE business_id = %d ORDER BY time DESC, rating DESC", $google_place->id)
            );
        }

        if (isset($google_place->review_count) && $google_place->review_count > 0) {
            $review_count = $google_place->review_count;
        } else {
            $review_count = $wpdb->get_var(
                $wpdb->prepare("SELECT count(*) FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE business_id = %d", $google_place->id)
            );
        }

        $google_place->photo = strlen($google_biz->photo) > 0 ?
            $google_biz->photo : (strlen($google_place->photo) > 0 ? $google_place->photo : $google_place->icon);

        $rating = 0;
        if ($google_place->rating > 0) {
            $rating = $google_place->rating;
        } elseif ($review_count > 0) {
            foreach ($google_reviews as $google_review) {
                $rating = $rating + $google_review->rating;
            }
            $rating = round($rating / $review_count, 1);
        }
        $rating = number_format((float)$rating, 1, '.', '');

        $business = json_decode(json_encode(
            array(
                'id'                  => $google_biz->id,
                'name'                => $google_place->name,
                'url'                 => $google_place->url,
                'photo'               => $google_place->photo,
                'address'             => $google_place->address,
                'rating'              => $rating,
                'review_count'        => $review_count,
                'review_count_manual' => $review_count_manual,
                'provider'            => 'google'
            )
        ));

        $reviews = array();
        foreach ($google_reviews as $rev) {
            $review = json_decode(json_encode(
                array(
                    'biz_id'        => $google_biz->id,
                    'rating'        => $rev->rating,
                    'text'          => wp_encode_emoji($rev->text),
                    'author_avatar' => $rev->author_img,
                    'author_url'    => $rev->author_url,
                    'author_name'   => $rev->author_name,
                    'time'          => $rev->time,
                    'provider'      => 'google',
                )
            ));
            array_push($reviews, $review);
        }

        return array('business' => $business, 'reviews' => $reviews);
    }

    public function get_google_reviews_all($google_biz, $google_success_api, $google_api_limit) {
        $business = null;
        $reviews = array();

        $auth_code = get_option('brb_auth_code');
        $google_api_url = "https://app.richplugins.com/gmb/" . $google_biz->id . "/reviews?auth_code=" . $auth_code;
        if (isset($google_biz->props->root_account)) {
            $google_api_url .= '&account_id=' . $google_biz->props->root_account;
        }
        if (isset($google_api_limit) && $google_api_limit > 0) {
            $google_api_url .= '&limit=' . $google_api_limit;
        }

        $google_response = rplg_urlopen($google_api_url);
        $google_response_data = $google_response['data'];
        $google_response_json = rplg_json_decode($google_response_data);

        if ($google_success_api) {
            $cache_key_success = 'brb_google_success_' . $google_biz->id;
            if (isset($google_response_json->reviews)) {
                set_transient($cache_key_success, $google_response, 0);
            } else {
                $google_success_response = get_transient($cache_key_success);
                if ($google_success_response !== false) {
                    $google_response_data = $google_success_response['data'];
                    $google_response_json = rplg_json_decode($google_response_data);
                }
            }
        }

        $google_rating = 0;
        if (isset($google_response_json->averageRating)) {
            $google_rating = number_format((float)$google_response_json->averageRating, 1, '.', '');
        }

        $google_count = 0;
        if (isset($google_response_json->totalReviewCount)) {
            $google_count = $google_response_json->totalReviewCount;
        }

        $business = json_decode(json_encode(
            array(
                'id'           => $google_biz->props->place_id,
                'name'         => $google_biz->name,
                'url'          => $google_biz->website,
                'photo'        => $google_biz->photo,
                'rating'       => $google_rating,
                'review_count' => $google_count,
                'provider'     => 'google'
            )
        ));

        if (isset($google_response_json->reviews)) {
            $reviews = array();
            foreach ($google_response_json->reviews as $rev) {

                $text = isset($rev->comment) && strlen($rev->comment) > 0 ? wp_encode_emoji($rev->comment) : '';
                $trans_idx = strrpos($text, '(Translated by Google)');
                if ($trans_idx > -1) {
                    $original = '(Original)';
                    $origin_idx = strrpos($text, $original);
                    if ($origin_idx > $trans_idx) {
                        $text = substr($text, $origin_idx + strlen($original), strlen($text));
                    } else {
                        $text = substr($text, 0, $trans_idx);
                    }
                }

                $review = json_decode(json_encode(
                    array(
                        'biz_id'        => $google_biz->props->place_id,
                        'rating'        => $this->STARS[$rev->starRating],
                        'text'          => $text,
                        'author_avatar' => $rev->reviewer->profilePhotoUrl,
                        'author_url'    => '', //TODO $rev->author_url,
                        'author_name'   => $rev->reviewer->displayName,
                        'time'          => strtotime($rev->updateTime),
                        'provider'      => 'google',
                    )
                ));
                array_push($reviews, $review);
            }
        }

        return array('business' => $business, 'reviews' => $reviews);
    }

    public function get_facebook_reviews($facebook_biz, $page_access_token, $fb_success_api, $fb_rating_calc, $reviewer_avatar_size, $fb_api_limit) {
        $business = null;
        $reviews = array();

        $facebook_api_url = BRB_FACEBOOK_API . $facebook_biz->id . "?access_token=" . $page_access_token . "&fields=ratings.fields(reviewer{id,name,picture.width(" . $reviewer_avatar_size . ").height(" . $reviewer_avatar_size . ")},created_time,rating,recommendation_type,review_text,open_graph_story{id}).limit(" . $fb_api_limit . "),overall_star_rating";

        if (!$fb_rating_calc) {
            $facebook_api_url = $facebook_api_url . ",rating_count";
        }

        $facebook_response = rplg_urlopen($facebook_api_url);
        $facebook_response_data = $facebook_response['data'];
        $facebook_response_json = rplg_json_decode($facebook_response_data);

        if ($fb_success_api) {
            $cache_key_success = 'brb_fb_success_' . $facebook_biz->id;
            if (isset($facebook_response_json->ratings)) {
                set_transient($cache_key_success, $facebook_response, 0);
            } else {
                $facebook_success_response = get_transient($cache_key_success);
                if ($facebook_success_response !== false) {
                    $facebook_response_data = $facebook_success_response['data'];
                    $facebook_response_json = rplg_json_decode($facebook_response_data);
                }
            }
        }

        $facebook_rating = 0;
        $facebook_count = 0;

        if (isset($facebook_response_json->ratings) && isset($facebook_response_json->ratings->data)) {
            $facebook_reviews = $facebook_response_json->ratings->data;
            $facebook_count = count($facebook_reviews);
            if ($facebook_count > 0) {
                foreach ($facebook_reviews as $facebook_review) {
                    $facebook_review_rating = $this->get_facebook_review_rating($facebook_review);
                    $facebook_rating = $facebook_rating + $facebook_review_rating;
                    $review = json_decode(json_encode(
                        array(
                            'biz_id'        => $facebook_biz->id,
                            'rating'        => $facebook_review_rating,
                            'text'          => isset($facebook_review->review_text) ?
                                                   wp_encode_emoji(str_replace("\n", '<br>', $facebook_review->review_text)) : '',
                            'author_avatar' => isset($facebook_review->reviewer->picture) ?
                                                   $facebook_review->reviewer->picture->data->url : BRB_FACEBOOK_AVATAR,
                            'author_url'    => 'https://facebook.com/' .
                                               (isset($facebook_review->open_graph_story) ?
                                                   $facebook_review->open_graph_story->id : $facebook_biz->id . '/reviews'),
                            'author_name'   => isset($facebook_review->reviewer->name) ? $facebook_review->reviewer->name : '',
                            'time'          => strtotime($facebook_review->created_time),
                            'provider'      => 'facebook',
                        )
                    ));
                    array_push($reviews, $review);
                }
                $facebook_rating = round($facebook_rating / $facebook_count, 1);
                $facebook_rating = number_format((float)$facebook_rating, 1, '.', '');
            }
        }

        if (isset($facebook_response_json->overall_star_rating) && !$fb_rating_calc) {
            $facebook_rating = number_format((float)$facebook_response_json->overall_star_rating, 1, '.', '');
        }
        if (isset($facebook_response_json->rating_count) && $facebook_response_json->rating_count > 0) {
            $facebook_count = $facebook_response_json->rating_count;
        }

        $business = json_decode(json_encode(
            array(
                'id'           => $facebook_biz->id,
                'name'         => $facebook_biz->name,
                'photo'        => strlen($facebook_biz->photo) > 0 ?
                                  $facebook_biz->photo : 'https://graph.facebook.com/' . $facebook_biz->id . '/picture',
                'url'          => 'https://fb.com/' . $facebook_biz->id,
                'rating'       => $facebook_rating,
                'review_count' => $facebook_count,
                'provider'     => 'facebook'
            )
        ));

        return array('business' => $business, 'reviews' => $reviews);
    }

    public function get_facebook_review_rating($review) {
        if (isset($review->rating)) {
            return $review->rating;
        } elseif (isset($review->recommendation_type)) {
            return ($review->recommendation_type == 'negative' ? 1 : 5);
        } else {
            return 5;
        }
    }

    public function get_yelp_reviews($yelp_biz) {
        global $wpdb;

        $yelp_business = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::BUSINESS_TABLE . " WHERE place_id = %s AND platform = %s", $yelp_biz->id, 'yelp')
        );

        if (strlen($yelp_biz->lang) > 0) {
            $yelp_reviews = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE business_id = %d AND language = %s ORDER BY time_str DESC, rating DESC", $yelp_business->id, $yelp_biz->lang)
            );
        } else {
            $yelp_reviews = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE . " WHERE business_id = %d ORDER BY time_str DESC, rating DESC", $yelp_business->id)
            );
        }

        $business = json_decode(json_encode(
            array(
                'id'           => $yelp_biz->id,
                'name'         => $yelp_business->name,
                'url'          => $yelp_business->url,
                'photo'        => isset($yelp_biz->photo) && strlen($yelp_biz->photo) > 0 ? $yelp_biz->photo : $yelp_business->photo,
                'address'      => $yelp_business->address,
                'rating'       => number_format((float)$yelp_business->rating, 1, '.', ''),
                'review_count' => $yelp_business->review_count,
                'provider'     => 'yelp'
            )
        ));

        $reviews = array();
        foreach ($yelp_reviews as $yelp_review) {
            $author_img = strlen($yelp_review->author_img) > 0 ? str_replace('o.jpg', 'ms.jpg', $yelp_review->author_img) : BRB_YELP_AVATAR;
            $review = json_decode(json_encode(
                array(
                    'biz_id'        => $yelp_biz->id,
                    'rating'        => $yelp_review->rating,
                    'text'          => wp_encode_emoji($yelp_review->text),
                    'author_avatar' => $author_img,
                    'author_url'    => $yelp_review->url,
                    'author_name'   => $yelp_review->author_name,
                    'time'          => strtotime($yelp_review->time_str),
                    'provider'      => 'yelp',
                )
            ));
            array_push($reviews, $review);
        }

        return array('business' => $business, 'reviews' => $reviews);
    }

    private function merge_biz($businesses, $id = '', $name = '', $url = '', $photo = '', $provider = '') {
        $count = 0;
        $rating = 0;
        $review_count = array();
        $review_count_manual = array();
        $business_platform = array();
        $biz_merge = null;
        foreach ($businesses as $business) {
            if ($business->rating < 1) {
                continue;
            }

            $count++;
            $rating += $business->rating;

            if (isset($business->review_count_manual) && $business->review_count_manual > 0) {
                $review_count_manual[$business->id] = $business->review_count_manual;
            } else {
                $review_count[$business->id] = $business->review_count;
            }

            array_push($business_platform, $business->provider);

            if ($biz_merge == null) {
                $biz_merge = json_decode(json_encode(
                    array(
                        'id'           => strlen($id)       > 0 ? $id       : $business->id,
                        'name'         => strlen($name)     > 0 ? $name     : $business->name,
                        'url'          => strlen($url)      > 0 ? $url      : $business->url,
                        'photo'        => strlen($photo)    > 0 ? $photo    : $business->photo,
                        'provider'     => strlen($provider) > 0 ? $provider : $business->provider,
                        'review_count' => 0,
                    )
                ));
            }
            $rating_tmp = round($rating / $count, 1);
            $rating_tmp = number_format((float)$rating_tmp, 1, '.', '');
            $biz_merge->rating = $rating_tmp;
        }
        $review_count = array_merge($review_count, $review_count_manual);
        foreach ($review_count as $id => $count) {
            $biz_merge->review_count += $count;
        }
        if ($biz_merge != null) {
            $biz_merge->platform = array_unique($business_platform);
        }
        return $biz_merge;
    }

    private function sort_recent($a, $b) {
        return $a->time < $b->time;
    }

    private function sort_oldest($a, $b) {
        return $a->time > $b->time;
    }

    private function sort_highest($a, $b) {
        return $a->rating == $b->rating ? $this->sort_recent($a, $b) : $a->rating < $b->rating;
    }

    private function sort_lowest($a, $b) {
        return $a->rating == $b->rating ? $this->sort_recent($a, $b) : $a->rating > $b->rating;
    }

}