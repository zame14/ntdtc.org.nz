<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<a class="top">
    <span class="fa fa-chevron-up"></span>
</a>
<div class="yellow-paw-wrapper">
    <div class="yellow-paw"></div>
</div>
<section id="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-7 col-lg-4 f-col-1">
                <h3>Location</h3>
                <?php
                if(is_active_sidebar('footerwidget_location')){
                    dynamic_sidebar('footerwidget_location');
                }
                echo socialMediaMenu();
                ?>
            </div>
            <div class="col-12 col-sm-5 col-lg-3 f-col-2">
                <h3>Members of</h3>
                <?php
                if(is_active_sidebar('footerwidget_member')){
                    dynamic_sidebar('footerwidget_member');
                }
                ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-5 f-col-3">
                <h3>Subscribe</h3>
                <p>Sign up to receive updates on all upcoming events, promotions and other news.</p>
                <!-- Begin Mailchimp Signup Form -->
                <div id="mc_embed_signup">
                    <form action="https://ntdtc.us2.list-manage.com/subscribe/post?u=316c3b4f7fed15d5a2b6d11f4&amp;id=bcf5896bac&amp;f_id=00f750e0f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                        <div id="mc_embed_signup_scroll">
                            <div class="mc-field-group">
                                <input type="email" value="" name="EMAIL" class="required email form-control" id="mce-EMAIL" required placeholder="Enter Email Address">
                                <span id="mce-EMAIL-HELPERTEXT" class="helper_text"></span>
                            </div>
                            <div id="mce-responses" class="clear foot">
                                <div class="response" id="mce-error-response" style="display:none"></div>
                                <div class="response" id="mce-success-response" style="display:none"></div>
                            </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_316c3b4f7fed15d5a2b6d11f4_bcf5896bac" tabindex="-1" value=""></div>
                            <div class="optionalParent">
                                <div class="clear foot">
                                    <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-secondary">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                <!--End mc_embed_signup-->
            </div>
        </div>
    </div>
</section>
<section id="copyright">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="inner-wrapper">
                    <ul>
                        <li>&copy; <?=date('Y')?> <?=get_bloginfo('name')?></li>
                        <li class="site-by">Custom Website by <a href="https://www.azwebsolutions.co.nz/" target="_blank">A-Z Web Solutions<span class="az"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 sitemap-wrapper">
                <a href="<?=get_page_link(295)?>"><span class="fa fa-sitemap"></span>Sitemap</a>
            </div>
        </div>
    </div>
</section>
</div><!-- #page we need this extra closing tag here -->
<?php wp_footer(); ?>
<script src="<?=get_stylesheet_directory_uri()?>/js/noframework.waypoints.min.js" type="text/javascript"></script>
<script src="<?=get_stylesheet_directory_uri()?>/js/theme.js" type="text/javascript"></script>
</body>
</html>