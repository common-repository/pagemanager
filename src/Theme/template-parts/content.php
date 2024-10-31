<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 24.02.22
 * Time: 23:02
 */

?>
<div class="row justify-content-md-center">
    <div class="col-md-8 mb-3">
    <h1 class="pt-3"><?php echo get_the_title(); ?></h1>
    <p class="lead"><?php echo get_the_excerpt(); ?></p>

    <?php

    if ( 'post' === get_post_type() ) : ?>
        <div class="entry-meta">
            <?php wp_bootstrap_starter_posted_on(); ?>
        </div><!-- .entry-meta -->
    <?php
    endif; ?>
    </div>
    <?php if(has_post_thumbnail()){ ?>
        <figure class="figure col-md-12">
            <img src="<?php echo get_the_post_thumbnail_url();?>" class="figure-img img-fluid rounded" alt="A generic square placeholder image with rounded corners in a figure.">
            <figcaption class="figure-caption text-right"><?php echo get_the_post_thumbnail_caption(); ?></figcaption>
        </figure>

    <?php } ?>


    <div class="col-md-8 mt-5">
        <?php the_content(); ?>
    </div>
    <div class="col-md-12 mt-5">
        <?php the_post_navigation(); ?>
    </div>
    <hr size="1">
    <div class="col-md-8 mt-5">
        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        ?>
    </div>
</div>
