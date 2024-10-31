<?php

get_header();

//get the id of the current category
$queriedObject = get_queried_object();
?>
<h2><?php echo esc_textarea($queriedObject->name); ?></h2>
<?php


\Pagemanager\PageManager::run($queriedObject->taxonomy, $queriedObject->term_id);


?>

<?php
get_footer();
?>