<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/19/2023
 * Time: 11:34 AM
 */
global $post;
$class = new PetClass($post->ID);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-6 image-col-wrapper left-margin order-md-6">
            <div class="image-wrapper vc_single_image-wrapper">
                <?=$class->getFeatureImage()?>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6">
            <div class="description">
                <h2><?=$class->getCustomField('class-sub-heading')?></h2>
                <?=$class->getContent()?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 class-times">
            <h3>Class Times <span class="fa fa-clock-o"></span></h3>
            <?=$class->getClassTimes()?>
        </div>
        <?php
        if($class->getCustomField('class-calendar-id') <> "") { ?>
            <div class="col-12 key-dates">
                <h3>Key Dates <span class="fa fa-exclamation-circle"></span></h3>
                <?= do_shortcode('[calendar_anything id="' . $class->getCustomField('class-calendar-id') . '"]') ?>
            </div>
            <?php
        }
        ?>
        <div class="col-12 class-fee">
            <h3>Class Fee <span class="fa fa-usd"></span></h3>
            <?=$class->getClassFee()?>
        </div>
        <div class="col-12 apply-now">
            <a href="#apply" class="apply">Apply now</a>
        </div>
    </div>
</article>