<?php

/*
PageblockTemplateLabel: One bigger post
PageblockTemplateValue: bigger
PageblockTemplateIcon: layout-bigger
PageblockTemplateBlocktype: selectedposts,termposts
PageblockTemplateQuantity: 1
LayoutSort: 8
*/


require_once "partials/_helper_output.php";
?>


<div class="container pageblock pageblock-posts-<?php echo esc_attr($this->blockSettings["title"]); ?> bg-white border">
    <?php
    if (isset($this->blockSettings["title"]) AND strlen($this->blockSettings["title"])>0) : ?>
    <div class="row pageblock-title<?php if ($this->blockSettings['layout']!='bigger') echo " mb-3"; ?>">
        <div class="col text-center">
            <h3 class=""><?php echo esc_textarea($this->blockSettings["title"]); ?></h3>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <?php postOutput($this->posts[0], "post-bigger.php") ?>
    </div>

</div>
<br>

