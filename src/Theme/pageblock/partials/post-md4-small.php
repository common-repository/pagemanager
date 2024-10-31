    <a href="<?php echo get_permalink($post->ID); ?>" class="text-dark">
        <h3><?php echo esc_textarea($postTitle); ?></h3>
    </a>

    <p class="align-text-bottom"><small class="text-muted">
            <?php //echo get_the_author_meta('nicename', $post->post_author); ?> <?php echo esc_textarea($post_date); ?></small>
    </p>