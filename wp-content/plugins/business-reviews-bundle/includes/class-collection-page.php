<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Collection_Page {

    public function __construct(Collection_Deserializer $collection_deserializer) {
        $this->collection_deserializer = $collection_deserializer;
    }

    public function register() {
        add_filter('views_edit-brb_collection', array($this, 'render'), 20);
    }

    public function render() {
        $collection_count = $this->collection_deserializer->get_collection_count();
        ?>
        <div class="brb-admin-collections">
            <a class="button button-primary" href="<?php echo admin_url('admin.php'); ?>?page=brb-builder">Create Collection</a>
            <?php if ($collection_count < 1) { ?>
            <h3 style="display:inline;vertical-align:middle;"> - First of all, create new Reviews Collection to use it as a shortcode or into the widget</h3>
            <?php } ?>
        </div>
        <?php
    }
}
