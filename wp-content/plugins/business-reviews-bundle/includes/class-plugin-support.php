<?php

namespace WP_Business_Reviews_Bundle\Includes;

class Plugin_Support {

    public function __construct() {
    }

    public function register() {
        add_action('brb_admin_page_brb-support', array($this, 'init'));
        add_action('brb_admin_page_brb-support', array($this, 'render'));
    }

    public function init() {
    }

    public function render() {
        ?>
        <div class="brb-page-title">
            Support and Troubleshooting
        </div>

        <div class="brb-settings-workspace">

            <div data-nav-tabs="">
                <div class="nav-tab-wrapper">
                    <a href="#brb-introduction" class="nav-tab nav-tab-active">Introduction</a>
                    <a href="#brb-common" class="nav-tab">Common</a>
                    <a href="#brb-rich-snippets" class="nav-tab">Rich Snippets</a>
                    <a href="#brb-google" class="nav-tab">Google</a>
                    <a href="#brb-facebook" class="nav-tab">Facebook</a>
                    <a href="#brb-yelp" class="nav-tab">Yelp</a>
                    <a href="#brb-request" class="nav-tab">Request to Support</a>
                </div>
                <div id="brb-introduction" class="tab-content" style="display:block">
                    <h3>Business Reviews Bundle for WordPress</h3>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <p>Business Reviews Bundle is a first WordPress plugin with ability to merge business reviews from several Google, Facebook or Yelp places between each other and show a summary rating. It is also an easy and fast way to integrate business reviews right into your WordPress website through Widgets or Shortcodes.</p>
                            <p>Please see Introduction Video to understand how it works. Also you can find most common answers and solutions for most common questions and issues in next tabs.</p>
                            <p><b>Like this plugin? Give it a like on socials:</b></p>
                            <div class="brb-flex-row">
                                <div class="brb-flex-col3">
                                    <div id="fb-root"></div>
                                    <script>(function(d, s, id) {
                                      var js, fjs = d.getElementsByTagName(s)[0];
                                      if (d.getElementById(id)) return;
                                      js = d.createElement(s); js.id = id;
                                      js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.6&appId=1501100486852897";
                                      fjs.parentNode.insertBefore(js, fjs);
                                    }(document, 'script', 'facebook-jssdk'));</script>
                                    <div class="fb-like" data-href="https://richplugins.com/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
                                </div>
                                <div class="brb-flex-col3 twitter">
                                    <a href="https://twitter.com/richplugins?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @richplugins</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                                </div>
                                <div class="brb-flex-col3 googleplus">
                                    <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="200" data-href="https://plus.google.com/101080686931597182099"></div>
                                    <script type="text/javascript">
                                        window.___gcfg = { lang: 'en-US' };
                                        (function () {
                                            var po = document.createElement('script');
                                            po.type = 'text/javascript';
                                            po.async = true;
                                            po.src = 'https://apis.google.com/js/plusone.js';
                                            var s = document.getElementsByTagName('script')[0];
                                            s.parentNode.insertBefore(po, s);
                                        })();
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <iframe width="100%" height="315" src="https://www.youtube.com/embed/VADB5PvG1IM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div id="brb-common" class="tab-content" style="display:none">
                    <h3>Most Common Questions</h3>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Is that a one time or annual fee?</h3>
                                <p>The new Business Reviews Bundle plugin has an annual subscription payment model and as long as the subscription is available, a license for priority support and auto updates will also be valid.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I can't download the plugin, there's a folder, not a zip file.</h3>
                                <p>Most likely you downloaded the plugin from Safari browser and it automatically unpacked to the folder. Unfortunately, Safari has this sometimes unusable feature for the archive files. Just pack this folder back to the file (/business-reviews-bundle/ -> /business-reviews-bundle.zip) and upload zip in wp-admin.</p>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>If I cancel my subscription, will the license be vaild?</h3>
                                <p>The subscription is used only for license, in order for it to be automatically renewed and the plugin always has the latest update. If you cancel the subscription, the license will also canceled. However, if you still want to cancel the subscription, you can do this a few days before the subscription is renewed so that the license is active for a year.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Do you offer refunds?</h3>
                                <p>Sure! Here’s the simplest way to describe our 'Refund Policy': if you’re not happy, request a refund within 30 days of purchase and we’ll refund your purchase without any questions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Can I continue to use the plugin if my subscription past due?</h3>
                                <p>Yes, sure the plugin will continue to work. However, the license for priority support and plugin's auto updates expired and you will not get updates or support.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Is the plugin compatible with the latest version of PHP? I saw warnings about the compatibility with PHP 7 in the checker plugin.</h3>
                                <p>Yes, the plugin is absolutely compatible with PHP 7 and by the way, we are using PHP 7 on the demo site.</p>
                                <p>The warnings come from the code which is needed for compatible with old versions of PHP (&lt; 5.0) which is sometimes found in some users and without this code, the plugin will not work.</p>
                                <p>The problem is that the plugin which you’re using to test compatibility with PHP 7 cannot understand that this code is not used under PHP 7 or greater. The compatibility plugin just search some keywords which deprecated in the latest version PHP and show warnings about it (without understanding that this is not used).</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="brb-rich-snippets" class="tab-content" style="display:none">
                    <h3>About Rich Snippets</h3>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Google does not guarantee that your structured data will show up</h3>
                                <p>Google does not guarantee that your structured data will show up in search results even if structured data is marked up and can be extracted successfully according to the testing tool. <a href="https://developers.google.com/search/docs/guides/mark-up-content#how_does_it_work" target="_blank">Link to Google source</a>.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>We recommend to enable Rich Snippets for Summary Rating</h3>
                                <p>If you select <b>Summary Rating</b>, please make sure that the option <b>Show Summary Rating</b> is enabled in the <b>Header Options</b> panel at <b>Collection Builder</b>.</p>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Rich Snippets will not if the collection shows only reviews</h3>
                                <p>Rich Snippets will not be added without at least one business header (if only reviews are shown) and following mandatory fields: image, name, reviews count.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Rich Snippets is not for homepage</h3>
                                <p>Google does not index Rich Snippets on homepages, it is a limitation of Google, not specifically the plugin.</p>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>We recommend to enable Rich Snippets for one page</h3>
                                <p>Do not place a widget or shortcode with enable Rich Snippets option on the each page of the site because in this case Google might consider a schema markup as duplicate content. Just select a single page (except a homepage) and place such shortcode there.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Test before going live</h3>
                                <p>Test the page in the <a href="https://search.google.com/structured-data/testing-tool" target="_blank">Google Structured Data Testing Tool</a> before published.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="brb-google" class="tab-content" style="display:none">
                    <h3>Google's Most Common Questions</h3>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I can't connect my Google Place.</h3>
                                <p>Please check that you correctly found the Place ID of your Google business. It should look like <b>ChIJ</b>3TH9CwFZwokRI... This instruction helps to find any Place ID regardless of whether it has a physical address or it is an area: <a href="https://www.launch2success.com/guide/find-any-google-id/" target="_blank">how to find Place ID of any Google business</a></p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I have some error messages about the Google API key.</h3>
                                <p>Please make sure that your correctly created the Google Places API key with <b>Places API library and without any restrictions (IP or Referrer)</b>. It should look like <b>AIzaS</b>yB3k4oWDJPF... Here is detailed instruction: <a href="<?php echo admin_url('admin.php?page=brb-settings&brb_tab=google'); ?>" target="_blank">how to create Google API key</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Why I see only 5 reviews even in this Business version?</h3>
                                <p>The plugin uses the Google Places API to get your reviews. The API only returns the 5 most helpful reviews. When Google changes the 5 current most helpful the plugin will automatically add the new one to your database. Thus slowly building up a database of reviews. Unfortunately, it is a limitation of Google, not specifically the plugin.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I got new reviews, but these do not show in the plugin.</h3>
                                <p>The plugin can only download what Google returns in their Places API. It is usually the 5 Most Helpful (not newest) reviews. You can check what the API returns by entering your Google Place ID and API key in this url:</p>
                                <code>https://maps.googleapis.com/maps/api/place/details/json?placeid=YOUR_PLACE_ID&key=YOUR_GOOGLE_API_KEY</code>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I chose Most Recent sorting, but see the same reviews.</h3>
                                <p>The Google Places API which used in the plugin does not pass a sort parameter and instantly return 5 most helpful (not recent) reviews. The sorting feature in the plugin sorts these 5 (or more when it will be available) reviews by recent or other sort methods.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Why I see 5 reviews count in the based section?</h3>
                                <div class="brb-flex-row">
                                    <div class="brb-flex-col">
                                        <p style="margin:0">The Google Places API which used in the plugin does not return information about the total number of reviews and the plugin can't get this value. However, the plugin has a special option for Google places <b>Total number of reviews</b> and you can type the total count manually.</p>
                                    </div>
                                    <div class="brb-flex-col">
                                        <img src="<?php echo BRB_ASSETS_URL . 'img/google_reviews_count.png'; ?>" alt="" style="width:250px"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="brb-facebook" class="tab-content" style="display:none">
                    <h3>Facebook's Most Common Questions</h3>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>How many reviews the plugin shows?</h3>
                                <p>The plugin uses a Facebook Graph API to show your reviews and there is no limitation on the number of reviews, as in Google or Yelp. The plugin shows all Facebook reviews.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I can't connect my Facebook Page.</h3>
                                <p>Please check:</p>
                                <ul>
                                    <li>Your Facebook account has an admin right for the page;</li>
                                    <li>Your browser supports(has enabled) Cookies for external websites;</li>
                                    <li>Your Facebook page is public and visible for all countries.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>When I connect Facebook, the popup closed and there's needed FB page(s)</h3>
                                <p>Try to remove our integration <b>WidgetPack</b> from Facebook integration list <a href="https://facebook.com/settings?tab=business_tools" target="_blank">https://facebook.com/settings?tab=business_tools</a> and then re-connect Facebook page(s). You should see the popup where you need to allow all permission requests.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I have error message: <b style="color:red">Error validating access token: The session has been invalidated...</b></h3>
                                <p>The plugin uses a Facebook Graph API to show your reviews and if connected FB account changed the password or invalidate the session, such error message will appear. Please re-connect your Facebook page(s) in the Collection Builder again.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="brb-yelp" class="tab-content" style="display:none">
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>How I can connect my Yelp business?</h3>
                                <p>You just need to find your business page on Yelp, for instance https://yelp.com/biz/benjamin-steakhouse-new-york-2 and copy & paste this link to the <b>Link to Business</b> field and connect.</p>
                            </div>
                        </div>
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>I can't connect my business.</h3>
                                <p>Please check that your business has at least one review for use in the plugin, if so, check the Yelp API key that it is correctly fetched by instruction: <a href="<?php echo admin_url('admin.php?page=brb-settings&brb_tab=yelp'); ?>" target="_blank">how to create Yelp API key</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="brb-flex-row">
                        <div class="brb-flex-col">
                            <div class="brb-support-question">
                                <h3>Why I see only 3 reviews even in this Business version?</h3>
                                <p>The plugin uses the Yelp API to get your reviews. The API only returns the 3 most helpful reviews. When Yelp changes the 3 current most helpful the plugin will automatically add the new one to your database. Thus slowly building up a database of reviews. Unfortunately, it is a limitation of Yelp, not specifically the plugin.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="brb-request" class="tab-content" style="display:none">
                    <h3>If you need support</h3>
                    <p>You can contact us directly by email <a href="mailto:support@richplugins.com">support@richplugins.com</a> and would be great and save us a lot of time if each request to the support will contain the following data:</p>
                    <ul>
                        <li><b>1.</b> Clear and understandable description of the issue;</li>
                        <li><b>2.</b> Direct links to your reviews on: Google map, Facebook or Yelp;</li>
                        <li><b>3.</b> Link to the page of your site where the plugin installed;</li>
                        <li><b>4.</b> Better if you attach a screenshot(s) (or screencast) how you determine the issue;</li>
                        <li><b>5. The most important: please always copy & paste the <a href="<?php echo admin_url('admin.php?page=brb-settings&brb_tab=advance#debug_info'); ?>" target="_blank">Debug Information</a> from the settings page of the plugin to your initial request.</b></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}
