<?php
/*
PageblockTemplateLabel: List
PageblockTemplateValue: list
PageblockTemplateIcon: layout-list
PageblockTemplateBlocktype: selectedposts,termposts
LayoutSort: 13
*/

require_once "partials/_helper_output.php";

?>
<div class="container pageblock pageblock-posts-<?php echo esc_attr($this->blockSettings["title"]); ?> bg-white">

    <?php
    if (isset($this->blockSettings["title"]) AND strlen($this->blockSettings["title"])>0) : ?>
        <div class="row pageblock-title<?php if ($this->blockSettings['layout']!='bigger') echo " mb-3"; ?>">
            <div class="col text-center">
                <h3 class=""><?php echo esc_textarea($this->blockSettings["title"]); ?></h3>
            </div>
        </div>
    <?php endif; ?>
    <?php foreach ($this->posts as $count => $post) {
        postOutput($post, "post-list.php", $count == 0);
    } ?>
</div>
<br>