<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
global $post;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">
    <section id="top-bar">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?=loginMenu()?>
                </div>
            </div>
        </div>
    </div>
    <section id="header">
        <div class="container">
            <div class="row">
                <div class="col-12 no-padding">
                    <div class="inner-wrapper">
                        <div class="menu-wrapper">
                            <div class="main-nav wrapper-fluid wrapper-navbar" id="wrapper-navbar">
                                <nav class="site-navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                                    <?php
                                    wp_nav_menu(
                                        array(
                                            'theme_location' => 'primary',
                                            'container_class' => 'collapse navbar-collapse navbar-responsive-collapse',
                                            'menu_class' => 'nav navbar-nav',
                                            'fallback_cb' => '',
                                            'menu_id' => 'main-menu'
                                        )
                                    );
                                    ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="logo ani-in">
                        <?=the_custom_logo()?>
                    </div>
                </div>
            </div>
        </div>
    </section>
