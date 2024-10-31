<?php

function postOutput($post, $partialTemplate, $isFirst = false) {

    if (!is_object($post)) return false;
    $defaultimage =  PAGEMANAGER_PLUGIN_URL . "images/fim.jpg";
    $postTitle = $post->post_title;
    $postImage = has_post_thumbnail($post->ID)
        ? get_the_post_thumbnail_url( $post->ID, 'large' )
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
    include $partialTemplate;
}
?>