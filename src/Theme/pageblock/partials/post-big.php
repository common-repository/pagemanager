<?php ?>
    <div class="col-md-7">
        <a href="<?php echo esc_att($postLink);?>"  class="text-dark">
            <img src="<?php echo esc_url($postImage); ?>" alt="<?php echo esc_att($postTitle); ?>" class="img-fluid">
        </a>
    </div>
    <div class="col-md-5">
        <a href="<?php echo esc_url($postLink); ?>" class="text-dark">
            <h3><?php echo esc_textarea($postTitle); ?></h3>
            <p><small>by  <?php echo get_the_author_meta('nicename', $post->post_author); ?> | <?php echo esc_textarea($post_date); ?></small></p>
            <p class="lead"><?php echo esc_textarea($post->post_excerpt); ?></p>
        </a>
    </div>
<?php ?>