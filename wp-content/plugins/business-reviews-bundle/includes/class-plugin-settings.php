<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Plugin_Settings {

    public function __construct(Debug_Info $debug_info) {
        $this->debug_info = $debug_info;
    }

    public function register() {
        add_action('brb_admin_page_brb-settings', array($this, 'init'));
        add_action('brb_admin_page_brb-settings', array($this, 'render'));
    }

    public function init() {
        //TODO
    }

    public function render() {

        $tab = isset($_GET['brb_tab']) && strlen($_GET['brb_tab']) > 0 ? $_GET['brb_tab'] : 'active';

        $brb_enabled            = get_option('brb_active') == '1';
        $brb_demand_assets      = get_option('brb_demand_assets');
        $brb_nocss              = get_option('brb_nocss');

        $brb_google_api_key     = get_option('brb_google_api_key');
        $brb_google_places_api  = get_option('brb_google_places_api');
        $brb_yelp_api_key       = get_option('brb_yelp_api_key');

        $brb_license            = get_option('brb_license');
        $brb_license_status     = get_option('brb_license_status');
        $brb_license_expired    = get_option('brb_license_expired');

        $brb_renewal_status     = get_option('brb_renewal_status');
        $brb_renewal_date       = get_option('brb_renewal_date');

        $brb_latest_version     = get_option('brb_latest_version');

        $brb_debug_mode         = get_option('brb_debug_mode') == '1';

        $milliseconds           = round(microtime(true) * 1000);
        $license_is_active      = $milliseconds < $brb_license_expired;
        ?>

        <div class="brb-page-title">
            Settings
        </div>

        <?php do_action('brb_admin_notices'); ?>

        <div class="brb-settings-workspace">

            <div data-nav-tabs="">
                <div class="nav-tab-wrapper">
                    <a href="#brb-general"  class="nav-tab<?php if ($tab == 'active')   { ?> nav-tab-active<?php } ?>">General</a>
                    <a href="#brb-google"   class="nav-tab<?php if ($tab == 'google')   { ?> nav-tab-active<?php } ?>">Google</a>
                    <a href="#brb-facebook" class="nav-tab<?php if ($tab == 'facebook') { ?> nav-tab-active<?php } ?>">Facebook</a>
                    <a href="#brb-yelp"     class="nav-tab<?php if ($tab == 'yelp')     { ?> nav-tab-active<?php } ?>">Yelp</a>
                    <a href="#brb-license"  class="nav-tab<?php if ($tab == 'license')  { ?> nav-tab-active<?php } ?>">License</a>
                    <a href="#brb-advance"  class="nav-tab<?php if ($tab == 'advance')  { ?> nav-tab-active<?php } ?>">Advanced</a>
                </div>

                <div id="brb-general" class="tab-content" style="display:<?php echo $tab == 'active' ? 'block' : 'none'?>;">
                    <h3>General Settings</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=brb_settings_save&brb_tab=active&active=' . (string)((int)($brb_enabled != true)))); ?>">
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Busness Reviews Bundle is currently <b><?php echo $brb_enabled ? 'enabled' : 'disabled' ?></b></label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('brb-wpnonce_active', 'brb-form_nonce_active'); ?>
                                <input type="submit" name="active" class="button" value="<?php echo $brb_enabled ? 'Disable' : 'Enable'; ?>" />
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Load assets on demand</label>
                            </div>
                            <div class="wp-review-field-option">
                                <label>
                                    <input type="hidden" name="brb_demand_assets" value="false">
                                    <input type="checkbox" id="brb_demand_assets" name="brb_demand_assets" value="true" <?php checked('true', $brb_demand_assets); ?>>
                                    Load static assets (JavaScripts/CSS) only on pages where reviews are showing
                                </label>
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Disable plugin's CSS</label>
                            </div>
                            <div class="wp-review-field-option">
                                <label>
                                    <input type="hidden" name="brb_nocss" value="false">
                                    <input type="checkbox" id="brb_nocss" name="brb_nocss" value="true" <?php checked('true', $brb_nocss); ?>>
                                    Do not load the main CSS asset of the plugin (for custom design)
                                </label>
                                <div style="padding-top:15px">
                                    <input type="submit" value="Save" name="save" class="button" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="brb-google" class="tab-content" style="display:<?php echo $tab == 'google' ? 'block' : 'none'?>;">
                    <h3>Google</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=brb_settings_save&brb_tab=google')); ?>">
                        <?php wp_nonce_field('brb-wpnonce_save', 'brb-form_nonce_save'); ?>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Use Google Places API</label>
                            </div>
                            <div class="wp-review-field-option">
                                <label>
                                    <input type="hidden" name="brb_google_places_api" value="false">
                                    <input type="checkbox" id="brb_google_places_api" name="brb_google_places_api" value="true" <?php checked('true', $brb_google_places_api); ?>>
                                    The API returns only 5 most helpful reviews
                                </label>
                                <br>
                                <small>
                                    Use this option only if you do not have a verified Google My Business account.<br>
                                    With this option you must create the Google Places API key by instruction below and save into the setting.
                                </small>
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Google Places API key</label>
                            </div>
                            <div class="wp-review-field-option">
                                <input type="text" id="brb_google_api_key" name="brb_google_api_key" class="regular-text" value="<?php echo esc_attr($brb_google_api_key); ?>">
                                <div style="padding-top:15px">
                                    <input type="submit" value="Save" name="save" class="button" />
                                </div>
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Instruction: how to create Google Places API key</label>
                            </div>
                            <div class="wp-review-field-option">
                                <p>1. Go to your <a href="https://console.developers.google.com/apis/dashboard?pli=1" target="_blank">Google Console</a></p>
                                <p>2. Click '<b>Create Project</b>' or '<b>Select Project</b>' button</p>
                                <p>3. Create new project or select existing</p>
                                <p>4. On the project page click '<b>ENABLE APIS AND SERVICES</b>'</p>
                                <p>5. Type '<b>Places API</b>' in the search area</p>
                                <p>6. Select the first result '<b>Places API</b>' and click '<b>ENABLE</b>' button</p>
                                <p>7. On the 'Places API' page select '<b>Credential</b>' tab and '<b>Create credential</b>' / '<b>API key</b>' option</p>
                                <p>8. Copy created API key, paste to this setting and save</p>
                                <h3>Video instruction</h3>
                                <iframe src="//www.youtube.com/embed/Kf_bkg7WeC0?rel=0" allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="brb-facebook" class="tab-content" style="display:<?php echo $tab == 'facebook' ? 'block' : 'none'?>;">
                    <h3>Facebook</h3>
                    <p>There are no specific settings for the Facebook platform.</p>
                    <p>If you are looking how to connect the Facebook reviews, you need to <a href="<?php echo admin_url('admin.php'); ?>?page=brb-builder">create collection</a> and use 'Facebook Reviews' section.</p>
                </div>

                <div id="brb-yelp" class="tab-content" style="display:<?php echo $tab == 'yelp' ? 'block' : 'none'?>;">
                    <h3>Yelp</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=brb_settings_save&brb_tab=yelp')); ?>">
                        <?php wp_nonce_field('brb-wpnonce_save', 'brb-form_nonce_save'); ?>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Yelp API key</label>
                            </div>
                            <div class="wp-review-field-option">
                                <input type="text" id="brb_yelp_api_key" name="brb_yelp_api_key" class="regular-text" value="<?php echo esc_attr($brb_yelp_api_key); ?>">
                                <div style="padding-top:15px">
                                    <input type="submit" value="Save" name="save" class="button" />
                                </div>
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Instruction: how to create Yelp API key</label>
                            </div>
                            <div class="wp-review-field-option">
                                <p>1. If you do not have a <b>free Yelp account</b> (not a business), please <a href="https://www.yelp.com/signup" target="_blank">Sign Up Here</a></p>
                                <p>2. Under the free Yelp account, go to the <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank">Yelp developers</a> page and create new app</p>
                                <p>3. Copy <b>API Key</b> to this setting and <b>Save</b></p>
                                <h3>Video instruction</h3>
                                <iframe src="//www.youtube.com/embed/GFhGN36Wf7Q?rel=0" allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="brb-license" class="tab-content" style="display:<?php echo $tab == 'license' ? 'block' : 'none'?>;">
                    <h3>License</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=brb_settings_save&brb_tab=license')); ?>">
                        <?php wp_nonce_field('brb-wpnonce_save', 'brb-form_nonce_save'); ?>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>License</label>
                            </div>
                            <div class="wp-review-field-option">
                                <input type="text" id="brb_license" name="brb_license" class="regular-text" value="<?php echo esc_attr($brb_license); ?>">
                                <div style="padding-top:15px">
                                    <input type="submit" value="Activate License" name="save" class="button" />
                                </div>
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>License Details</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php if ($brb_license_status) { ?>
                                <div class="brb-alert brb-alert-dismissible brb-alert-<?php echo $license_is_active ? 'success' : 'warning' ?>">
                                    <strong>
                                        <?php if ($license_is_active) { ?>
                                        Your Pro license is active until
                                        <?php } else { ?>
                                        The license has expired at
                                        <?php } ?>
                                        <u><?php echo gmdate("d M y", $brb_license_expired / 1000); ?></u>
                                    </strong><br>
                                    <?php if (BRB_VERSION == $brb_latest_version) { ?>
                                    * The latest version of the plugin <b><?php echo $brb_latest_version; ?></b> is installed
                                    <?php } else { ?>
                                    * The plugin is outdated, the latest version <b><?php echo $brb_latest_version; ?></b>, please update the plugin on the <a href="<?php echo esc_url(admin_url('plugins.php')); ?>">Plugins</a> page
                                    <?php } ?><br>
                                    * Automatic license renewal is <b><?php echo $brb_renewal_status ? 'enabled' : 'disabled'; ?></b><br>
                                    <?php if ($brb_renewal_status) { ?>
                                    * Automatic license renewal date <?php echo substr($brb_renewal_date, 0, 10); ?><br>
                                     <?php } ?>
                                    <?php if ($license_is_active) { ?>
                                    * Plugin automatically updates<br>
                                    * Access to priority support <a href="mailto:priority@richplugins.com">priority@richplugins.com</a><br>
                                    <?php } ?>
                                    <button name="brb_license_deactive" type="submit" class="button-primary button" onclick="return confirm('Are you sure you want to deactivate the license?');">Deactivate License</button>
                                </div>
                                <?php } else { ?>
                                <p>Your license is not activated. Activate your Pro license to receive automatic plugin updates and priority support for the life of your license.</p>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="brb-advance" class="tab-content" style="display:<?php echo $tab == 'advance' ? 'block' : 'none'?>;">
                    <h3>Advanced</h3>
                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php?action=brb_settings_save&brb_tab=advance')); ?>">
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Re-create the database tables of the plugin (service option)</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('brb-wpnonce_create_db', 'brb-form_nonce_create_db'); ?>
                                <input type="submit" value="Re-create Database" name="create_db" onclick="return confirm('Are you sure you want to re-create database tables?')" class="button" />
                            </div>
                        </div>
                        <!--div class="brb-field">
                            <div class="brb-field-label">
                                <label>This removes all plugin-specific settings (options)</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('brb-wpnonce_reset', 'brb-form_nonce_reset'); ?>
                                <input type="submit" value="Delete Options" name="reset" onclick="return confirm('Are you sure you want to reset all plugin settings?')" class="button" />
                            </div>
                        </div-->
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label><b>Please be careful</b>: this removes all settings, reviews, collections and install the plugin from scratch</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('brb-wpnonce_create_db', 'brb-form_nonce_create_db'); ?>
                                <input type="submit" value="Install from scratch" name="install" onclick="return confirm('It will delete all current collections, are you sure you want to install from scratch the plugin?')" class="button" />
                                <p><label><input type="checkbox" id="install_multisite" name="install_multisite"> For all sites (WP Multisite)</label></p>
                            </div>
                        </div>
                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label><b>Please be careful</b>: this removes all plugin-specific settings, reviews and collections</label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('brb-wpnonce_reset_all', 'brb-form_nonce_reset_all'); ?>
                                <input type="submit" value="Delete All Data" name="reset_all" onclick="return confirm('Are you sure you want to reset all plugin data including collections?')" class="button" />
                                <p><label><input type="checkbox" id="reset_all_multisite" name="reset_all_multisite"> For all sites (WP Multisite)</label></p>
                            </div>
                        </div>
                        <div id="debug_info" class="brb-field">
                            <div class="brb-field-label">
                                <label>Debug information</label>
                            </div>
                            <div class="wp-review-field-option">
                                <input type="button" value="Copy Debug Information" name="reset_all" onclick="window.brb_debug_info.select();document.execCommand('copy');window.brb_debug_msg.innerHTML='Debug Information copied, please paste it to your email to support';" class="button" />
                                <textarea id="brb_debug_info" style="display:block;width:30em;height:100px;margin-top:10px" onclick="window.brb_debug_info.select();document.execCommand('copy');window.brb_debug_msg.innerHTML='Debug Information copied, please paste it to your email to support';" readonly><?php $this->debug_info->render(); ?></textarea>
                                <p id="brb_debug_msg"></p>
                            </div>
                        </div>

                        <div class="brb-field">
                            <div class="brb-field-label">
                                <label>Debug mode is currently <b><?php echo $brb_debug_mode ? 'enabled' : 'disabled' ?></b></label>
                            </div>
                            <div class="wp-review-field-option">
                                <?php wp_nonce_field('brb-wpnonce_debug_mode', 'brb-form_nonce_debug_mode'); ?>
                                <input type="submit" name="debug_mode" class="button" value="<?php echo $brb_debug_mode ? 'Disable' : 'Enable'; ?>" />
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
        <?php
    }

}
