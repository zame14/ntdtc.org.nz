<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/19/2023
 * Time: 12:00 PM
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="row">
        <div class="col-12">
            <a name="apply"></a>
            <?=do_shortcode('[cred_form form="109"]')?>
        </div>
    </div>
</article>