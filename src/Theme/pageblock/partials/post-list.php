<div class="row mb-3">
    <div class="col-md-4">
        <a href="<?php echo esc_url($postLink); ?>"  class="text-dark">
            <img src="<?php echo esc_url($postImage); ?>" alt="<?php echo esc_attr($postTitle); ?>" class="img-fluid">
        </a>
    </div>
    <div class="col-md-8">
        <a href="<?php echo esc_url($postLink); ?>" class="text-dark">
            <h3 class="mt-3"><?php echo esc_textarea($postTitle); ?></h3>
            <p><small>by  <?php echo get_the_author_meta('nicename', $post->post_author); ?> | <?php echo esc_textarea($post_date); ?></small></p>
            <p class="lead"><?php echo esc_textarea($post->post_excerpt); ?></p>
        </a>
    </div>
</div>