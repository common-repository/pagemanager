<?php
/*
PageblockTemplateName: Test
PageblockTemplatePosts: 7
*/
include "partials/posts-header.php";

$defaultimage =  PAGEMANAGER_PLUGIN_URL . "images/fim.jpg";

foreach($this->posts as $count => $post){
    $postTitle = $post->post_title;
    $postImage = has_post_thumbnail($post->ID)
        ? get_the_post_thumbnail_url( $post->ID, $count==0 ? 'large':'large' )
        : $defaultimage;
    $postLink = get_permalink($post->ID);

    $date = get_post_time('G', true, $post);
    $current_time = current_time( 'mysql', $gmt = 0 );
    $newer_date = strtotime( $current_time );

    $postsage = $newer_date - $date;

    if($postsage < 86400*5){
        $post_date = human_time_diff($date,$newer_date). " ago";

    } else {
        $post_date = mysql2date('j. M Y',$post->post_date);
    }


    if ($count==0){
        include "partials/post-big.php";
    } else { ?>
        <?php if ($count == 1 ) : ?>
        <div class="row mb-3">
        <?php endif; ?>
        <?php if ($count == 4 ) : ?>
        </div>
        <div class="row mb-3">
        <?php endif; ?>
        <div class="col-sm-4" style="min-height: 300px;">

            <a href="<?php echo get_permalink($post->ID); ?>" class="text-dark">
                <div style="max-height: 230px; overflow: hidden;">
                    <img src="<?php echo esc_url($postImage); ?>" alt="<?php echo esc_attr($postTitle); ?>" class="mb-2 img-fluid" style="object-fit: fill;object-position: center;height: auto;width: 100%;">
                </div>
                <h3><?php echo esc_textarea($postTitle); ?></h3>
            </a>

                <p class="align-text-bottom" style="position: absolute;  bottom: 0px; "><small class="text-muted">
                        <?php echo esc_textarea($post_date); ?></small>
                </p>

        </div>
        <?php if ($count == 6 ) : ?>
        </div>
        <?php endif; ?>
        <?php
        }
    }
?>
</div>
<br>