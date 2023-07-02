<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
get_header();
global $post;
$page_title = get_the_title();
if($post->ID == 126) {
    $page_title = "Available Classes";
}
?>
    <div class="wrapper" id="page-wrapper">
        <?php
        if(is_front_page()) { ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 no-padding">
                        <div class="home-banner-wrapper banner-image">
                            <?= get_the_post_thumbnail($post->ID, 'full') ?>
                            <div class="logo-overlay ani-in">
                                <img src="<?=get_field('logo',5)?>" alt="<?=get_bloginfo('name')?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        } else { ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 no-padding">
                        <div class="page-title">
                            <h1><?=$page_title?></h1>
                            <div class="breadcrumb-wrapper container"><?=breadcrumb()?></div>
                            <div class="paws-background ani-in"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        <div id="content" class="container">
            <div class="row">
                <div class="col-12">
                    <main class="site-main" id="main">
                    <?php while (have_posts()) : the_post(); ?>
                        <?=get_template_part('loop-templates/content', 'page')?>
                    <?php endwhile; // end of the loop. ?>
                    </main>
                </div>
            </div>
        </div>
    </div><!-- #page-wrapper -->
<?php
get_footer();