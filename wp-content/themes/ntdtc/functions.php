<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
require_once('modal/class.Base.php');
require_once('modal/class.Member.php');
require_once('modal/class.MembershipType.php');
require_once('modal/class.PetClass.php');
require_once('modal/class.Event.php');
require_once('modal/class.Page.php');
require_once('modal/class.Enrolment.php');
require_once('modal/class.WPAjax.php');
$wcAdjustStylesheet = 'understrap-theme';
add_action( 'wp_enqueue_scripts', 'p_enqueue_styles');
function p_enqueue_styles() {
    //wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css');
    //wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/slick-carousel/slick/slick.css');
    //wp_enqueue_style( 'slick-theme', get_stylesheet_directory_uri() . '/slick-carousel/slick/slick-theme.css');
    wp_enqueue_style( 'understrap-theme', get_stylesheet_directory_uri() . '/style.css');
}
//add_image_size( 'grid', 600, 400, true);
function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );
function dg_remove_page_templates( $templates ) {
    unset( $templates['page-templates/blank.php'] );
    unset( $templates['page-templates/right-sidebarpage.php'] );
    unset( $templates['page-templates/both-sidebarspage.php'] );
    unset( $templates['page-templates/empty.php'] );
    unset( $templates['page-templates/fullwidthpage.php'] );
    unset( $templates['page-templates/left-sidebarpage.php'] );
    unset( $templates['page-templates/right-sidebarpage.php'] );

    return $templates;
}
add_filter( 'theme_page_templates', 'dg_remove_page_templates' );
function formatPhoneNumber($ph) {
    $ph = str_replace('(', '', $ph);
    $ph = str_replace(')', '', $ph);
    $ph = str_replace(' ', '', $ph);
    $ph = str_replace('+64', '0', $ph);

    return $ph;
}
function currentYear_shortcode()
{
    return date('Y');
}
add_shortcode('year','currentYear_shortcode');
function membershipFess_shortcode()
{
    $html = '<div class="fees-wrapper">
        <div><label>Adult 16+</label><input type="checkbox" name="fee[]" value="' . get_field('adult',17) . '" id="adult-fee" class="checkbox" data-label="Adult"> $' . number_format(get_field('adult',17), 2) . '</div>
        <div><label>Junior (under 16)</label><input type="checkbox" name="fee[]" value="' . get_field('junior',17) . '" id="junior-fee" class="checkbox" data-label="Junior"> $' . number_format(get_field('junior',17), 2) . '</div>
        <div><label>Family (2 adult + 2 children all living in the same household)</label><input type="checkbox" name="fee[]" value="' . get_field('family',17) . '" id="family-fee" class="checkbox" data-label="Family"> $' . number_format(get_field('family',17), 2) . '</div>
    </div>';
    return $html;
}
add_shortcode('membership_fees','membershipFess_shortcode');
function hiddenFields_shortcode()
{
    $html = '<input type="hidden" name="total" class="raw-total" value="0" />
    <input type="hidden" name="membership_details" class="membership-details" value="" />';
    return $html;
}
add_shortcode('hidden_fields','hiddenFields_shortcode');

add_action('cred_submit_complete', 'my_success_action',10,2);
function my_success_action($post_id, $form_data)
{
    global $post;
    //print_r($_POST);
    //exit;
    if ($form_data['id']==22) {
        $new_title = $_POST['wpcf-member-firstname'] . ' ' . $_POST['wpcf-member-surname'];
        $my_post = array(
            'ID'               => $post_id,
            'post_title'=> $new_title
        );
        // Update the post into the database
        wp_update_post( $my_post );
        // update status to pending
        update_post_meta($post_id, 'wpcf-status', "Pending");

        // get fee
        $membershipType = new MembershipType($_POST['membership-type']);
        update_post_meta($post_id, 'wpcf-member-amount-paid', $membershipType->getFee());

        //update membership start date
        date_default_timezone_set('Pacific/Auckland');
        $date = date('Y-m-d');
        $timestamp = strtotime($date);
        update_post_meta($post_id, 'wpcf-membership-start-date', $timestamp);
    }
    if($form_data['id']==209) {
        // renew membership
        // update status back to pending
        update_post_meta($post_id, 'wpcf-status', "Pending renewal");
        //update membership renew date
        date_default_timezone_set('Pacific/Auckland');
        $date = date('Y-m-d');
        $timestamp = strtotime($date);
        update_post_meta($post_id, 'wpcf-date-membership-renewed', $timestamp);
    }
    if($form_data['id']==109) {
        //print_r($_POST);
        //exit;
        $pet_class = new PetClass($post->ID);
        // class enrolments

        // update title
        $new_title = $_POST['wpcf-enrolment-firstname'] . ' ' . $_POST['wpcf-enrolment-lastname'];
        $my_post = array(
            'ID'               => $post_id,
            'post_title'=> $new_title
        );
        // Update the post into the database
        wp_update_post( $my_post );
        //insert class enrolment
        $class_id = $pet_class->id();
        toolset_connect_posts( 'class-enrolment', $class_id, $post_id );
        //update status
        update_post_meta($post_id, 'wpcf-enrolment-status', "Pending");
        //insert into class name field
        update_post_meta($post_id, 'wpcf-enrolment-class-name', $pet_class->getTitle());
    }
}
add_filter('cred_form_validate','my_validation',10,2);
function my_validation($error_fields, $form_data)
{
    //field data are field values and errors
    list($fields,$errors)=$error_fields;
    //uncomment this if you want to print the field values
    //print_r($fields);
    //validate if specific form
    if ($form_data['id']==22)
    {
        // check email address is unique
        // get user by role - member
        foreach (getMembers() as $member)
        {
            if (strtolower($member->getCustomField('member-email')) == strtolower($fields['wpcf-member-email']['value'])) {
                $errors['member-email'] = "A membership has already been registered using this email address. If you are a current member and want to renew your membership, please login and renew via your account. If you are a new member, please try a different email address.";
                break;
            }
        }
        // check membership type
        if($fields['membership-type']['value'] == "") {
            $errors['membership-type'] = "Please select a membership type.";
        }
    }

    //return result
    return array($fields,$errors);
}
/*
function memberVerification_shortcode()
{
    $members = getMembersToVerify();
    $show_duplicate_msg = false;
    $html = '<div class="member-verification-wrapper">';
    if(!empty($members))
    {
        $html .= '
        <div class="table-responsive">
            <div class="notification-wrapper">
                <p></p>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Membership type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
                foreach ($members as $member)
                {
                    $email_class = '';
                    if($member->isEmailAddressUnique() == "no")
                    {
                        $email_class="duplicate";
                        $show_duplicate_msg = true;
                    }
                    $html .= '
                    <tr>
                        <td>' . $member->getDisplayName() . '</td>
                        <td class="' . $email_class . ' email">' . $member->getCustomField('member-email') . '</td>
                        <td class="' . $email_class . '">' . $member->getCustomField('membership-details') . '</td>
                        <td class="' . $email_class . '"><span class="approve btn btn-primary" data-id="' . $member->id() . '">approve</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="decline" data-id="' . $member->id() . '">decline</span><span class="spinner s' . $member->id() . '"></span></td>
                    </tr>';
                }
                $html .= '
                </tbody>    
            </table>';
            if($show_duplicate_msg)
            {
                $html .= '<div class="duplicate-notice">
                        <p>* a user already exists with this email address. Email addresses must be unique.</p>
                    </div>';
            }
        $html .= '    
        </div>';
    }
    $html .= '
    </div>';
    return $html;
}
add_shortcode('member_verification', 'memberVerification_shortcode');
*/
function getMembers()
{
    $arr = Array();
    $posts_array = get_posts([
        'post_type' => 'member',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC'
    ]);
    foreach ($posts_array as $post) {
        $member = new Member($post);
        $arr[] = $member;
    }
    return $arr;
}
function getMembershipTypes()
{
    $arr = Array();
    $posts_array = get_posts([
        'post_type' => 'membership-type',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC'
    ]);
    foreach ($posts_array as $post) {
        $membershipType = new MembershipType($post);
        $arr[] = $membershipType;
    }
    return $arr;
}
function membershipTypes_shortcode()
{
    $label = '';
    $html = '<div class="fees-wrapper">
        <div class="inner-wrapper">
            <div class="left-col header">
                <strong>Membership</strong>
            </div>
            <div class="right-col header">
                <strong>Fee</strong>
            </div>
        </div>';
        foreach (getMembershipTypes() as $membershipType) {
            $label = $membershipType->getTitle();
            if($membershipType->getCustomField('fee-additional-info') <> "") {
                $label .= '<span>('. $membershipType->getCustomField('fee-additional-info') . ')</span>';
            }
            $html .= '<div class="inner-wrapper">
                <div class="left-col info">
                    ' . $label . '
                </div>
                <div class="right-col info">
                    ' . $membershipType->getFee() . '
                </div>
            </div>';
        }
    $html .= '
    </div>';
    return $html;
}
add_shortcode('membership_types','membershipTypes_shortcode');
/***********************
 * Ajax
 */
add_action('wp_head', function() {
    echo '<script type="text/javascript">
       var ajaxurl = "' . admin_url('admin-ajax.php') . '";
     </script>';
});
add_action('wp_ajax_ajax', function() {
    $WPAjax = new WPAjax($_GET['call']);
});
add_action('wp_ajax_nopriv_ajax', function() {
    $WPAjax = new WPAjax($_GET['call']);
});
function custom_meta_box_member()
{
    add_meta_box('approve-member', 'Membership status', 'approveMember', 'member', 'side', 'high');
}
add_action('add_meta_boxes', 'custom_meta_box_member');

function approveMember()
{
    // if member is pending, display Approve button
    global $post;
    $member = new Member($post->ID);
    $html = '<h3>' . $member->getCustomField('status') . '</h3>';
    if($member->isPending()) {
        $html .= '<a href="javascript:;" class="button button-primary button-large approve" data-id="' . $post->ID . '">Click to approve</a>';
    }
    $html .= '<div class="spinner"></div>';
    echo $html;
}
function add_custom_js_file_to_admin( $hook ) {
    wp_enqueue_script ( 'custom-script', get_stylesheet_directory_uri() . '/js/custom-admin-script.js' );
}
add_action('admin_enqueue_scripts', 'add_custom_js_file_to_admin');
function custom_meta_box_class_enrolment()
{
    add_meta_box('approve-class-enrolment', 'Class enrolment status', 'approveClassEnrolment', 'enrolment', 'side', 'high');
}
add_action('add_meta_boxes', 'custom_meta_box_class_enrolment');

function approveClassEnrolment()
{
    // if member is pending, display Approve button
    global $post;
    $enrolment = new Enrolment($post->ID);
    $html = '<h3>' . $enrolment->getCustomField('enrolment-status') . '</h3>';
    if($enrolment->isPending()) {
        $html .= '<a href="javascript:;" class="button button-primary button-large approve-class-enrolment" data-id="' . $post->ID . '">Click to approve</a>';
    }
    $html .= '<div class="spinner"></div>';
    echo $html;
}
function loginMenu()
{
    $html = '';
    // check if user is logged in
    if ( is_user_logged_in() ) {
        // get user
        $member = getLoggedInMember();
        $html = 'Hi ' . $member->getCustomField('member-firstname') . ' - <a href="' . wp_logout_url('/') . '">Log out</a>';
        $html = '';
    } else {
        $html = 'Already a member? <a href="' . get_page_link(85) . '">Login</a>';
    }
    return $html;
}
function footer_location_widget_init()
{
    register_sidebar( array(
        'name'          => __( 'Footer Location Widget', 'understrap' ),
        'id'            => 'footerwidget_location',
        'description'   => 'Widget area in the footer',
        'before_widget'  => '<div class="footer-location-widget-wrapper">',
        'after_widget'   => '</div><!-- .footer-widget -->',
        'before_title'   => '<h3 class="widget-title">',
        'after_title'    => '</h3>',
    ) );
}
add_action( 'widgets_init', 'footer_location_widget_init' );
function footer_members_widget_init()
{
    register_sidebar( array(
        'name'          => __( 'Footer Members Widget', 'understrap' ),
        'id'            => 'footerctawidget_members',
        'description'   => 'CTA area in the footer',
        'before_widget'  => '<div class="footer-members-widget-wrapper">',
        'after_widget'   => '</div><!-- .footer-widget -->',
        'before_title'   => '<h3 class="widget-title">',
        'after_title'    => '</h3>',
    ) );
}
add_action( 'widgets_init', 'footer_members_widget_init' );
function socialMediaMenu()
{
    $html = '<ul class="social-media">
        <li><a href="' . get_field('facebook_link',5) . '" target="_blank"><span class="fa fa-facebook"></span></a></li>
    </ul>';
    return $html;
}
function availableClasses_shortcode()
{
    global $post;
    $classes = getPetClasses();
    if($post->ID == 5) {
        // home page, just display feature classes
        $classes = getFeatureClasses();
    }
    $html = '<div class="container-fluid d-flex h-100 flex-column">
    <div class="row justify-content-center flex-fill d-flex">';
    foreach ($classes as $pet_class)
    {
        $slug = str_replace(" ", "-", $pet_class->getTitle());
        $slug = strtolower($slug);
        $html .= '<div class="col-12 col-sm-6 col-md-6 col-lg-3 class-panel">
            <div class="inner-wrapper">
                <div class="image-wrapper" onclick="location.href=/' . $slug . '/">
                    ' . $pet_class->getFeatureImage() . '
                </div>
                <div class="content-wrapper">
                    <h4>' . $pet_class->getTitle() . '</h4>
                    <div class="snippet">
                        ' . $pet_class->getCustomField('class-snippet') . '
                    </div>
                    <a href="' . $pet_class->link() . '" class="learn-more">Learn more</a>
                </div>
            </div>
        </div>';
    }
    $html .= '
    </div>
    </div>';
    return $html;
}
add_shortcode('available_classes', 'availableClasses_shortcode');
function getPetClasses()
{
    $arr = Array();
    $posts_array = get_posts([
        'post_type' => 'class',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC'
    ]);
    foreach ($posts_array as $post) {
        $pet_class = new PetClass($post);
        $arr[] = $pet_class;
    }
    return $arr;
}
function getFeatureClasses()
{
    $arr = Array();
    $posts_array = get_posts([
        'post_type' => 'class',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'ID',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key' => 'wpcf-featured-class',
                'value' => 1
            ]
        ]
    ]);
    foreach ($posts_array as $post) {
        $pet_class = new PetClass($post);
        $arr[] = $pet_class;
    }
    return $arr;
}
function events_shortcode()
{
    $format = 'D, d M Y';
    $html = '<div class="events-wrapper">';
    foreach (getEvents(3) as $event) {
        $html .= '<div class="event-panel" onclick="location.href=/events/">
            <div class="image-wrapper">
                ' . $event->getFeatureImage() . '
            </div>
            <div class="content-wrapper">
                <div class="date">
                    ' . $event->getEventDay($format) . '
                </div>
                <h4>' . $event->getTitle() . '</h4>
                <div class="snippet">
                    ' . $event->getCustomField('event-snippet') . '
                </div>
                <div class="location">
                    ' . $event->getCustomField('event-location') . '
                </div>
            </div>
        </div>';
    }
    $html .= '</div>
    <div class="more-events-wrapper">
        <a href="' . get_page_link(128) . '" class="btn btn-primary">See all events</a>
    </div>';
    return $html;
}
add_shortcode('events', 'events_shortcode');
function getEvents($limit = -1)
{
    $arr = Array();
    $posts_array = get_posts([
        'post_type' => 'event',
        'post_status' => 'publish',
        'numberposts' => $limit,
        'orderby' => 'ID',
        'order' => 'ASC'
    ]);
    foreach ($posts_array as $post) {
        $event = new Event($post);
        if($event->isUpcomingEvent()) {
            $arr[] = $event;
        }
    }
    return $arr;
}
function getEventsList_shortcode()
{
    $html = '';
    foreach (getEvents() as $event)
    {
        $html .= '
        <div class="event-list-wrapper">
            <div class="inner-wrapper">
                <div class="date-col">
                    <span>' . $event->getEventDay('D') . '</span>
                    ' . $event->getEventDay('d') . '
                </div>
                <div class="content-wrapper">
                    <div class="event-date">' . $event->getEventDate() . '</div>
                    <h3>' . $event->getTitle() . '</h3>
                    <div class="description">
                        ' . $event->getContent() . '
                    </div>
                    <div class="location">
                        <span class="fa fa-map-marker"></span> ' . $event->getCustomField('event-location') . '
                    </div>';
                    if($event->link() <> "") {
                        $html .= '<a href="' . $event->link() . '" class="btn btn-primary">reserve your place</a>';
                    }
                    else {
                        $html .= '<strong>Enter on the day</strong>';
                    }
                    $html .= '
                </div>
                <div class="image-wrapper">
                    ' . $event->getFeatureImage() . '
                </div>            
            </div>
        </div>';
    }
    $html .= '
    </div>';
    return $html;
}
add_shortcode('events_list','getEventsList_shortcode');
function membersName_shortcode()
{
    $member = getLoggedInMember();
    return $member->getCustomField('member-firstname');
}
add_shortcode('members_name','membersName_shortcode');

function myAccount_shortcode()
{
    $member = getLoggedInMember();
    $html = '<p>Hi ' . $member->getCustomField('member-firstname') . ',</p>';

    //check if membership has expired
    if($member->hasExpired()) {
        $html .= '<p>Please renew your membership below.</p>';
        $html .= do_shortcode('[cred_form form=209 post=' . $member->id() . ']');
    } else {
        $html .= '<p>Use the form below to keep your details up to date.</p>';
        $html .= do_shortcode('[cred_form form=201 post=' . $member->id() . ']');
    }
    return $html;
}
add_shortcode('my_account','myAccount_shortcode');
function currentMembership_shortcode()
{
    $member = getLoggedInMember();
    $membership_type = $member->getMembershipType();
    $html = '<div class="current-membership-wrapper">
        <div class="inner-wrapper">
            <strong>Current membership:</strong><span>' . $membership_type->getTitle() . '</span>
        </div>
        <div class="inner-wrapper">
            <strong>Registration date:</strong><span>' . $member->getRegistrationDate() . '</span>
        </div>
        <div class="inner-wrapper">
            <strong>Last renewal date:</strong><span>' . $member->getRenewDate() . '</span>
        </div> 
        <div class="inner-wrapper">
            <strong>Current status:</strong><span>' . $member->getCustomField('status') . '</span>
        </div>                       
    </div>';
    return $html;
}
add_shortcode('current_membership', 'currentMembership_shortcode');
function getLoggedInMember()
{
    $user = wp_get_current_user();
    $user_meta = get_user_meta($user->ID);
    $member_id = $user_meta['wpcf-user-membership-id'][0];
    return new Member($member_id);
}
function breadcrumb()
{
    global $post;
    $post_type = get_post_type($post->ID);
    $page = new Page($post->ID);
    $page_title = $page->getTitle();
    $html = '<ul>
    <li><a href="' . get_page_link(5) . '"><span class="fa fa-home"></span></a></li>';
    switch($post_type) {
        case "class":
            $html .= '<li><a href="' . get_page_link(126) . '">Classes</a></li>';
            break;
    }
    $html .= '<li>' . $page_title . '</li>
    </ul>';
    return $html;
}
function email_shortcode()
{
    $html = '<a href="mailto:' . get_field('email',5) . '" class="email"><span class="fa fa-envelope"></span>' . get_field('email',5) . '</a>';
    return $html;
}
add_shortcode('email','email_shortcode');
function facebook_shortcode()
{
    $html = '<a href="' . get_field('facebook_link',5) . '" target="_blank" class="facebook"><span class="fa fa-facebook-square"></span> Follow us on Facebook</a>';
    return $html;
}
add_shortcode('facebook','facebook_shortcode');
function getSiteMapPages()
{
    $arr = Array();
    $posts_array = get_posts([
        'post_type' => 'page',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'menu_order',
        'meta_query' => [
            [
                'key' => 'wpcf-exclude-from-sitemap',
                'value' => 0
            ]
        ]
    ]);
    foreach ($posts_array as $post) {
        $page = new Page($post);
        $arr[] = $page;
    }
    return $arr;
}
function sitemap_shortcode()
{
    $html = '<ul class="sitemap">';
    foreach(getSiteMapPages() as $page) {
        $html .= '<li><a href="' . $page->link() . '"><span class="fa fa-paw"></span>' . $page->getTitle() . '</span></a></li>';
        if($page->id() == 126) {
            $html .= '<ul class="sublist">';
            foreach (getPetClasses() as $pet_class) {
                $html .= '<li><a href="' . $pet_class->link() . '">' . $pet_class->getTitle() . '</span></a></li>';
            }
            $html .= '</ul>';
        }
        if($page->id() == 128) {
            $html .= '<ul class="sublist">';
            foreach (getEvents() as $event) {
                $html .= '<li><a href="' . get_page_link(128) . '">' . $event->getTitle() . '</span></a></li>';
            }
            $html .= '</ul>';
        }
    }
    $html .= '</ul>';
    return $html;
}
add_shortcode('ntdtc_sitemap','sitemap_shortcode');
add_action( 'wp_print_styles', 'wc_adjustStylesheetOrder', 99);
function wc_adjustStylesheetOrder() {
    global $wp_styles, $wcAdjustStylesheet;

    if(!$wcAdjustStylesheet) return;

    $keys=[];
    $keys[] = $wcAdjustStylesheet;

    foreach($keys as $currentKey) {
        $keyToSplice = array_search($currentKey,$wp_styles->queue);

        if ($keyToSplice!==false && !is_null($keyToSplice)) {
            $elementToMove = array_splice($wp_styles->queue,$keyToSplice,1);
            $wp_styles->queue[] = $elementToMove[0];
        }

    }

    return;
}
