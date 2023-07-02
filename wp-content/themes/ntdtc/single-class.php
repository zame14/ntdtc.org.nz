<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
    <div class="wrapper" id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 no-padding">
                    <div class="page-title">
                        <h1><?=get_the_title()?></h1>
                        <div class="breadcrumb-wrapper container"><?=breadcrumb()?></div>
                        <div class="paws-background ani-in"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="content" class="container">
            <div class="row">
                <div class="col-12">
                    <main class="site-main" id="main">
                        <?php
                        if(!isset($_REQUEST['cred_referrer_form_id'])) {
                            // dont display id for has been submitted
                            echo get_template_part('loop-templates/content', 'class');
                        }
                        ?>
                    </main>
                </div>
            </div>
        </div>
        <div class="class-form-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?=get_template_part('loop-templates/content', 'class-form')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();