
    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="text-dark">
        <div style="max-height: 500px; overflow: hidden;" class="mb-2">
            <img src="<?php echo esc_attr($postImage); ?>" alt="<?php echo esc_attr($postTitle); ?>" class="img-fluid" style="object-fit: fill;object-position: center;height: auto;width: 100%;">
        </div>
    <h3><?php echo esc_textarea($postTitle); ?></h3>
    <p><small>by  <?php echo esc_textarea(get_the_author_meta('nicename', $post->post_author)); ?> | <?php echo esc_textarea($post_date); ?></small></p>

    <p class="lead">
        <?php echo esc_textarea($post->post_excerpt); ?>
    </p>
    </a>