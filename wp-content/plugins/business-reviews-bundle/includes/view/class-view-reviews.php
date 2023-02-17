<?php

namespace WP_Business_Reviews_Bundle\Includes\View;

class View_Reviews {

    public function __construct(View_Helper $view_helper) {
        $this->view_helper = $view_helper;
    }

    public function review_box($review, $options, $stars_in_body=false, $hide_review=false) {
        ?>
        <div class="rplg-box<?php if ($hide_review) { ?> rplg-hide<?php } ?>">
            <?php $this->review($review, $options, $stars_in_body); ?>
        </div>
        <?php
    }

    public function review($review, $options, $stars_in_body=false, $hide_review=false) {
        ?>
        <div class="rplg-row">
            <?php if (!$options->hide_avatar) { ?>
            <div class="rplg-row-left">
                <?php $this->view_helper->author_avatar($review, $options); ?>
            </div>
            <?php } ?>
            <div class="rplg-row-right">
                <?php
                $this->view_helper->review_name($review, $options);
                if (!$stars_in_body) {
                    $this->view_helper->stars($review->rating, $review->provider);
                }
                $this->view_helper->review_time($review, $options);
                ?>
            </div>
        </div>
        <?php if ($stars_in_body) {
            $this->view_helper->stars($review->rating, $review->provider);
        } ?>
        <div class="rplg-box-content">
            <span class="rplg-review-text">
            <?php if (isset($review->text)) {
                $this->view_helper->trim_text($review->text, $options->text_size);
            } ?>
            </span>
            <span class="rplg-review-badge" data-badge="<?php echo $review->provider; ?>"></span>
        </div>
        <?php
    }

    public function review_new($review, $options, $hide_review=false) {
        ?>
        <div class="rplg-review<?php if ($hide_review) { ?> rplg-hide<?php } ?>">
            <?php
            switch ($options->review_style) {
                case 'shift'       : $this->review_shift($review, $options); break;
                case 'up'          : $this->review_up($review, $options); break;
                case 'down'        : $this->review_down($review, $options); break;
                case 'center_up'   : $this->review_center_up($review, $options); break;
                case 'center_down' : $this->review_center_down($review, $options); break;
                default            : $this->review_shift($review, $options);
            }
             ?>
        </div>
        <?php
    }

    /*
        O ---
          *****
          ----------
          ----------
          ----------
     */
    public function review_shift($review, $options) {
        ?>
        <div class="rplg-row rplg-row-start">
            <?php if (!$options->hide_avatar) { ?>
            <div class="rplg-row-left">
                <?php $this->view_helper->author_avatar($review, $options); ?>
                <span class="rplg-review-badge" data-badge="<?php echo $review->provider; ?>"></span>
            </div>
            <?php } ?>
            <div class="rplg-row-right">
                <?php
                $this->review_info($review, $options);
                $this->review_text($review, $options);
                ?>
            </div>
        </div>
        <?php
    }

    /*
       O ---
       *****
       ----------
       ----------
       ----------
     */
    public function review_up($review, $options) {
        ?>
        <div class="rplg-inner">
            <div class="rplg-row">
                <?php if (!$options->hide_avatar) { ?>
                <div class="rplg-row-left">
                    <?php $this->view_helper->author_avatar($review, $options); ?>
                    <span class="rplg-review-badge" data-badge="<?php echo $review->provider; ?>"></span>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php $this->review_info($review, $options); ?>
                </div>
            </div>
            <div class="rplg-box-content">
                <?php $this->review_text($review, $options); ?>
            </div>
        </div>
        <?php
    }

    /*
       *****
       ----------
       ----------
       ----------
       O ---
     */
    public function review_down($review, $options) {
        ?>
        <div class="rplg-inner">
            <div class="rplg-box-content">
                <?php $this->review_text($review, $options); ?>
            </div>
            <div class="rplg-row">
                <?php if (!$options->hide_avatar) { ?>
                <div class="rplg-row-left">
                    <?php $this->view_helper->author_avatar($review, $options); ?>
                </div>
                <?php } ?>
                <div class="rplg-row-right">
                    <?php $this->review_info($review, $options); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /*
           O
          ---
         *****
       ----------
       ----------
       ----------
     */
    public function review_center_up($review, $options) {
        ?>
        <div class="rplg-inner">
            <?php
            if (!$options->hide_avatar) {
                $this->view_helper->author_avatar($review, $options);
            }
            $this->review_info($review, $options);
            ?>
            <div class="rplg-box-content">
                <?php $this->review_text($review, $options); ?>
            </div>
        </div>
        <?php
    }

    /*
         *****
       ----------
       ----------
       ----------
           O
          ---
     */
    public function review_center_down($review, $options) {
        ?>
        <div class="rplg-inner">
            <div class="rplg-box-content">
                <?php $this->review_text($review, $options); ?>
            </div>
            <?php
            if (!$options->hide_avatar) {
                $this->view_helper->author_avatar($review, $options);
            }
            $this->review_info($review, $options);
            ?>
        </div>
        <?php
    }

    private function review_info($review, $options) {
        if (isset($options->metainfo_order) && strlen($options->metainfo_order) > 0) {
            $mio = explode(',', $options->metainfo_order);
            foreach ($mio as $val) {
                switch ($val) {
                    case 'name':
                        $this->view_helper->review_name($review, $options);
                        break;
                    case 'stars':
                        if (!$options->stars_in_body) {
                            $this->view_helper->stars($review->rating, $review->provider);
                        }
                        break;
                    case 'time':
                        $this->view_helper->review_time($review, $options);
                }
            }
        } else {
            $this->view_helper->review_name($review, $options);
            if (!$options->stars_in_body) {
                $this->view_helper->stars($review->rating, $review->provider);
            }
            $this->view_helper->review_time($review, $options);
        }
    }

    private function review_text($review, $options) {
        if ($options->stars_in_body) {
            $this->view_helper->stars($review->rating, $review->provider, '', $options->stars_inline);
        }
        if (isset($review->text)) {
            ?><span class="rplg-review-text"><?php $this->view_helper->trim_text($review->text, $options->text_size); ?></span><?php
        }
    }
}
