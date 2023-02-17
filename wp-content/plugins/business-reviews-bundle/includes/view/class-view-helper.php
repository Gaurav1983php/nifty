<?php

namespace WP_Business_Reviews_Bundle\Includes\View;

class View_Helper {

    public function author_avatar($review, $opts, $img_width=BRB_AVATAR_SIZE, $img_height=BRB_AVATAR_SIZE) {
        switch ($review->provider) {
            case 'google':
                $regexp = '/googleusercontent\.com\/([^\/]+)\/([^\/]+)\/([^\/]+)\/([^\/]+)\/photo\.jpg/';
                $matches = array();
                preg_match($regexp, $review->author_avatar, $matches, PREG_OFFSET_CAPTURE);
                if (count($matches) > 4 && $matches[3][0] == 'AAAAAAAAAAA') {
                    $review->author_avatar = str_replace('/photo.jpg', '/s128-c0x00000000-cc-rp-mo/photo.jpg', $review->author_avatar);
                }
                if (strlen($review->author_avatar) > 0) {
                    if (strpos($review->author_avatar, "s128") != false) {
                        $review->author_avatar = str_replace('s128', 's' . $opts->reviewer_avatar_size, $review->author_avatar);
                    } elseif (strpos($review->author_avatar, "-mo") != false) {
                        $review->author_avatar = str_replace('-mo', '-mo-s' . $opts->reviewer_avatar_size, $review->author_avatar);
                    } else {
                        $review->author_avatar = str_replace('-rp', '-rp-s' . $opts->reviewer_avatar_size, $review->author_avatar);
                    }
                }
                $default_avatar = BRB_GOOGLE_AVATAR;
                break;
            case 'facebook':
                $default_avatar = BRB_FACEBOOK_AVATAR;
                break;
            case 'yelp':
                if (strlen($review->author_avatar) > 0) {
                    $avatar_size = '';
                    if ($opts->reviewer_avatar_size <= 128) {
                        $avatar_size = 'ms';
                    } else {
                        $avatar_size = 'o';
                    }
                    $review->author_avatar = str_replace('ms.jpg', $avatar_size . '.jpg', $review->author_avatar);
                }
                $default_avatar = BRB_YELP_AVATAR;
                break;
        }

        $author_name = $this->author_name($review, $opts);
        $author_avatar = strlen($review->author_avatar) > 0 ? $review->author_avatar : $default_avatar;

        $img_width = $img_width != BRB_AVATAR_SIZE ? $img_width : $opts->reviewer_avatar_size;
        $img_height = $img_height != BRB_AVATAR_SIZE ? $img_height : $opts->reviewer_avatar_size;

        $this->image($author_avatar, $author_name, $opts->lazy_load_img, $img_width, $img_height, $default_avatar);
    }

    public function review_name($review, $opts) {
        if ($opts->hide_name) {
            return;
        }

        $author_name = $this->author_name($review, $opts);
        $author_url = $this->author_url($review);

        if (strlen($author_url) > 0 && !$opts->disable_user_link) {
            $this->anchor($author_url, 'rplg-review-name rplg-trim', $author_name, $opts->open_link, $opts->nofollow_link, '', $author_name);
        } else {
            echo '<div class="rplg-review-name rplg-trim" title="' . $author_name . '">' . $author_name . '</div>';
        }
    }

    public function author_url($review) {
        if (strlen($review->author_url) > 0) {
            return $review->author_url;
        } else {
            return $review->provider=='google' && strlen($review->biz_id)>0 ? 'https://search.google.com/local/reviews?placeid=' . $review->biz_id : '';
        }
    }

    public function author_name($review, $opts) {
        if ($this->_strlen($review->author_name) > 0) {
            return $opts->short_last_name ? $this->get_short_name($review->author_name) : $review->author_name;
        } else {
            return __(ucfirst($review->provider) . ' User', 'brb');
        }
    }

    public function review_time($review, $opts) {
        if (!$opts->disable_review_time) {
            if (strlen($opts->time_format) > 0) {
                $attr = '';
                $format = $opts->time_format;
            } else {
                $attr = 'data-time="' . $review->time . '"';
                $format = "H:i d M y";
            }
            ?><div class="rplg-review-time rplg-trim" <?php echo $attr; ?>><?php echo gmdate($format, $review->time); ?></div><?php
        }
    }

    public function stars($rating, $provider = '', $color = '#777', $inline = false) {
        $info = array($rating, $provider, $color);
        ?><div class="rplg-stars<?php if ($inline) { echo ' rplg-stars-inline'; } ?>" data-info="<?php echo implode(',', $info); ?>"></div><?php
    }

    public function anchor($url, $class, $text, $open_link, $nofollow_link, $onclick = '', $title = '') {
        $rel = array();
        if ($open_link) {
            array_push($rel, 'noopener');
        }
        if ($nofollow_link) {
            array_push($rel, 'nofollow');
        }
        ?><a href="<?php echo $url; ?>" class="<?php echo $class; ?>" <?php if ($open_link) { ?>target="_blank"<?php } ?> <?php if (count($rel) > 0) { ?>rel="<?php echo implode(' ', $rel); ?>"<?php } ?> <?php if (strlen($onclick) > 0) { ?>onclick="<?php echo $onclick; ?>"<?php } ?> <?php if ($this->_strlen($title) > 0) { ?>title="<?php echo $title; ?>"<?php } ?>><?php echo $text; ?></a><?php
    }

    public function image($src, $alt, $lazy, $width=BRB_AVATAR_SIZE, $height=BRB_AVATAR_SIZE, $def_ava = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7', $atts = '') {
        ?><img <?php if ($lazy) { ?>src="<?php echo $def_ava; ?>" data-<?php } ?>src="<?php echo $src; ?>" class="rplg-review-avatar<?php if ($lazy) { ?> rplg-blazy<?php } ?>" alt="<?php echo $alt; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" title="<?php echo $alt; ?>" onerror="if(this.src!='<?php echo $def_ava; ?>')this.src='<?php echo $def_ava; ?>';" <?php echo $atts; ?>><?php
    }

    public function trim_text($text, $size) {
        if ($size > 0 && $this->_strlen($text) > $size) {
            $sub_text = $this->_substr($text, 0, $size);
            $idx = $this->_strrpos($sub_text, ' ') + 1;

            if ($idx < 1 || $size - $idx > ($size / 2)) {
                $idx = $size;
            }
            if ($idx > 0) {
                $visible_text = $this->_substr($text, 0, $idx - 1);
                $invisible_text = $this->_substr($text, $idx - 1, $this->_strlen($text));
            }
            echo $visible_text;
            if ($this->_strlen($invisible_text) > 0) {
                ?><span>... </span><span class="rplg-more"><?php echo $invisible_text; ?></span><span class="rplg-more-toggle"><?php echo __('read more', 'brb'); ?></span><?php
            }
        } else {
            echo $text;
        }
    }

    public function correct_url_proto($url){
        return substr($url, 0, 2) == '//' ? 'https:' . $url : $url;
    }

    private function get_short_name($author_name){
        $names = explode(" ", $author_name);
        if (count($names) > 1) {
            $last_index = count($names) - 1;
            $last_name = $names[$last_index];
            if ($this->_strlen($last_name) > 1) {
                $last_char = $this->_substr($last_name, 0, 1);
                $last_name = $this->_strtoupper($last_char) . ".";
                $names[$last_index] = $last_name;
                return implode(" ", $names);
            }
        }
        return $author_name;
    }

    private function _strlen($str) {
        return function_exists('mb_strlen') ? mb_strlen($str, 'UTF-8') : strlen($str);
    }

    private function _strrpos($haystack, $needle, $offset = 0) {
        return function_exists('mb_strrpos') ? mb_strrpos($haystack, $needle, $offset, 'UTF-8') : strrpos($haystack, $needle, $offset);
    }

    private function _substr($str, $start, $length = NULL) {
        return function_exists('mb_substr') ? mb_substr($str, $start, $length, 'UTF-8') : substr($str, $start, $length);
    }

    private function _strtoupper($str) {
        return function_exists('mb_strtoupper') ? mb_strtoupper($str, 'UTF-8') : strtoupper($str);
    }

}
