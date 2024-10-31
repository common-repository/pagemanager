<?php
/*
PageblockTemplateLabel: One big
PageblockTemplateValue: big
PageblockTemplateIcon: layout-big
PageblockTemplateBlocktype: selectedposts,termposts
PageblockTemplateQuantity: 1
LayoutSort: 4
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
    <div class="row mb-3 mt-3">
        <?php postOutput($this->posts[0], "post-big.php") ?>
    </div>

</div>
<br>

