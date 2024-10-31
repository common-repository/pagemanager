<div class="card bg-white border p-3" style="min-height: 250px;background-image: url('<?php echo esc_attr($postImage) ;?>');">
    <a href="<?php echo esc_url($postLink); ?>" class="text-white">
        <h3 class="card-title"><?php echo esc_textarea($postTitle); ?></h3>
        <p class="align-text-bottom p-1" style="position: absolute;  bottom: 0px; "><small>
                <?php echo esc_textarea($post_date); ?></small>
        </p>
    </a>
</div>