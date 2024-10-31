 <a href="<?php echo get_permalink($post->ID); ?>" class="text-dark">
        <div style="overflow: hidden;">
            <img src="<?php echo esc_url($postImage); ?>" alt="<?php echo esc_attr($postTitle); ?>" class="mb-2 img-fluid" style="object-fit: fill;object-position: center;height: auto;width: 100%;">
        </div>
        <h3><?php echo esc_textarea($postTitle); ?></h3>
        <p><small>by  <?php echo get_the_author_meta('nicename', $post->post_author); ?> | <?php echo esc_textarea($post_date); ?></small></p>

    <p class="lead">
        <?php echo esc_textarea($post->post_excerpt); ?>
    </p>
 </a>